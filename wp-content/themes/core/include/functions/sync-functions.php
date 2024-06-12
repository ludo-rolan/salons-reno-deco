<?php
defined('ALREADY_IMPORTED') or define('ALREADY_IMPORTED' , 0 );
defined('IMPORTED_ARTICLE') or define('IMPORTED_ARTICLE' , 1 );
defined('ARTICLE_NOT_DRAFT') or define('ARTICLE_NOT_DRAFT' , 2 );


use TESTSPHALCON\CF_quizz_profile;
use TESTSPHALCON\CF_qa_question;
use TESTSPHALCON\CF_qa_response;
use TESTSPHALCON\CF_quizz_response;
use TESTSPHALCON\CF_quizz_question;
use TESTSPHALCON\CF_media_qa_participation;
use TESTSPHALCON\CF_quizz;
use TESTSPHALCON\RW_tests;
if ( ! function_exists( 'post_is_in_descendant_category' ) ) :
function post_is_in_descendant_category( $cats, $_post = null ) {
	foreach ( (array) $cats as $cat ) {
		// get_term_children() accepts integer ID only
		$term = get_term_by( 'slug', $cat, 'category' );
		if ( isset($term->term_id) ){
			$descendants = get_term_children( (int) $term->term_id , 'category' );
			if ( $descendants && in_category( $descendants, $_post ) )
				return true;
		}
	}
	return false;
}
endif;

function append_to_sync_dynamic($source, $config_dl, $id ){
	
	if ( !file_exists($config_dl) ){
		touch($config_dl);
	}

	$fp = fopen( $config_dl,"c");

	if (  flock($fp, LOCK_EX)  ) {
		// Lock file
		//echo  $config_dl;
		$posts = require ( $config_dl );
		
		if ( !isset($posts)  ||  !is_array($posts)  ) {
			$posts = array();
		}

		if ( !isset( $posts[$source])){
			$posts[$source]=array($id);
		} else{
			// avoid duplicates
			if (!in_array( $id , $posts[$source] ))
				$posts[$source][]=$id;
			
		}

		$code = var_export($posts ,true );
		$file = <<<SOURCE
<?php 
return $code;
SOURCE;
		
		// truncate to 0 
		ftruncate($fp, 0);
		rewind( $fp );
		// use temp
		fwrite($fp,$file);
		flock($fp, LOCK_UN);   

	} else {
		// couldn't lock
		fclose($fp);
		usleep( 10000);
		// try again 
		append_to_sync_dynamic($source, $config_dl, $id );

	}


}


function append_to_sync( $source , $destination , $id ){
	$config_dl = ABSPATH.'/script/sync/copy_posts_'.$destination.'.php';
	if ( !file_exists(ABSPATH.'/script/sync') ){
		mkdir(ABSPATH.'/script/sync', 0755);
	}

	append_to_sync_dynamic($source, $config_dl, $id );
	
}


function transition_post_status_sync_with_asia($new_status, $old_status, $post){
	if('publish' == $new_status && 'future' == $old_status){
		do_sync_with_asia_site($post->ID, $post);
	}	
	
}

function do_sync_site_post( $post_id, $post , $force_site , $metas=array() ){
	return do_sync_with_asia_site($post_id, $post , $force_site , $metas );
}

function url_to_slug($url){
	$url = str_replace ( 'http://' , '' , $url );
	$url = str_replace ( '/' , '' , $url );
	return str_replace ( '.' , '-' , $url );
}

if ( !function_exists('delete_post_media')) :
function delete_post_media( $post_id ) {

	if(!isset($post_id)) return; // Will die in case you run a function like this: delete_post_media($post_id); if you will remove this line - ALL ATTACHMENTS WHO HAS A PARENT WILL BE DELETED PERMANENTLY!
	elseif($post_id == 0) return; // Will die in case you have 0 set. there's no page id called 0 :)
	elseif(is_array($post_id)) return; // Will die in case you place there an array of pages.
	else {
	    $attachments = get_posts( array(
	        'post_type'      => 'attachment',
	        'posts_per_page' => -1,
	        'post_status'    => 'any',
	        'post_parent'    => $post_id
	    ) );
	    foreach ( $attachments as $attachment ) {
	        if ( false === wp_delete_attachment( $attachment->ID ) ) {
	            // Log failure to delete attachment.
	        }
	    }
	}
}

endif;



function do_sync_with_asia_site($post_id, $post , $force_site=false , $metas=array()){
	// sync only posts and tests,  don't sync empty titles, don't sync empty content , non published
	if ( !in_array($post->post_type , ['post','test','folder']) ||  $post->post_title=='' || $post->post_content=='' || $post->post_status != 'publish') 
		return;
	// clone object to make sure $post is not modified ( and other hooks can use )
	$my_post = clone($post);
	$original_slugs = get_all_categories_slug_by_post($post->ID, 'STRING');

	if ( $force_site ){
		$to_sync=$force_site;
		$set_cat_source=true;
	} else {
		$to_sync = get_param_global('sync_asia') ;
		if ( !$to_sync ){
			return;
		}
	}

	// if post_type is tests
	if($post->post_type == 'test'){
		$data_quizz = do_export_tests_quizz($post);
	}

	global $wpdb, $homeOrigin , $site_config;
	
	$homeOrigin = home_url();  

	// Decoration case
	$homeOrigin = apply_filters('section_permalink_domain', $homeOrigin , $post ) ; 
	
	// We should be able to skip some operations if syncing.. ( dailymotion, newsml..)
	defined( 'DOING_SYNC_CONTENT' ) or define('DOING_SYNC_CONTENT',true ) ;
	
	$post_blog_id = $wpdb->blogid;
	$my_post->guid = $post_blog_id . '.' . $post_id;

	$global_meta = array();
	$global_meta['permalink'] =  apply_filters('section_permalink_domain', get_permalink( $post_id )   , $post )  ;
	$global_meta['old_post_id'] =  $post_id;

	// Save the category as a meta
	$categories= wp_get_post_categories( $post_id );
	$cats = $cats_slugs = array();
	foreach( $categories as $c ) {
		$cat = get_category( $c );
		$cats[] =  $cat->name ;
		$cats_slugs[] =  $cat->slug ;
	}
	$global_meta['category'] = implode(',' , $cats );

	// Should we save any metas
	if ( count($metas) || $metas=='all') {
		$meta_keys_to_save = $metas;	
	} else {
		$meta_keys_to_save = get_param_global('sync_asia_meta') ;
	}

	if( is_array( $meta_keys_to_save ) && count( $meta_keys_to_save ) ) {
		foreach( $meta_keys_to_save as $key => $value)
			$global_meta[$value] = get_post_meta( $my_post->ID, $value, true );
	} elseif ( $meta_keys_to_save == 'all'){
		$post_metas = get_post_custom($my_post->ID);	
		foreach ( $post_metas as $key=>$value){
			// IMPORTANT, we may need to save
			if ( !in_array( $key , ['_edit_post', '_edit_lock'] ))
				$global_meta[$key]=$value;
		}
	}


	

	//Extract featured image
	$thumbnail = NULL;
	if( $thumb_id = get_post_meta( $my_post->ID, '_thumbnail_id', true ) ) {
		$thumbnail =  get_post($thumb_id,OBJECT);
		$thumb_image_alt = get_post_meta($thumbnail->ID , '_wp_attachment_image_alt', true);
		$thumb_src = get_attached_file($thumb_id);

	}
	$current_gallery_images = array();
	//Extract gallery	 
	if (preg_match(HAS_GALLERY_REGEXP, $my_post->post_content, $matches)) {
		
		if(NEW_HAS_GALLERY_REGEXP){
			$attr = shortcode_parse_atts( $matches[3] );
			$ids = $attr['ids'];
			$gallery_desc = isset( $attr['g_d']) ?  ("g_d='".$attr['g_d'] . "'") :'';
		}else{
			$gallery_desc = $matches[1];
			$ids = $matches[2];
		}
		
		//debug($matches);
		$ids = explode(',' , $ids);
		$args = array('post_type' => 'attachment' , 'post_status' => 'any' , 'post__in' => $ids, 'orderby' => 'post__in' , 'posts_per_page' => -1);
		$current_gallery_images = get_posts($args);
		$gallery_info=array();
		foreach ( $current_gallery_images as $key => $photo){
			$gallery_info[$key]['src'] = get_attached_file($photo->ID);
			$gallery_info[$key]['alt'] = get_post_meta($photo->ID , '_wp_attachment_image_alt', true);

		}
		
	}

	// custom taxonomies ??

	switch_to_blog( $to_sync);

	$new_thumb_id= false;
	//Copy featured image if exists
	if ($thumbnail){	
		$old_id =  $thumbnail->ID ;
		unset( $thumbnail->ID );
		$new_thumb_id = asia_image_exists($old_id  , $post_blog_id  );
		if ( $new_thumb_id )
			$global_meta['_thumbnail_id'] = $new_thumb_id ;
		else {

			//
			$new_imported_id = asia_sideload_image( $thumb_src  , $thumbnail->post_title, $thumbnail->post_content , $old_id , $thumb_image_alt , $thumbnail->post_excerpt , $post_blog_id ) ;
			if( $new_imported_id )
				$global_meta['_thumbnail_id'] = $new_imported_id ;
			elseif ( php_sapi_name()=='cli' ) {
				echo "\nNo featured image : ".$my_post->post_title;
			}
		}
	} else {
		if( php_sapi_name()=='cli' )
    		echo "\nNo featured image : ".$my_post->post_title;
	}
	//Copy gallery if exists
	$new_ids = array();

	if ( count( $current_gallery_images ) ){
		 
		foreach($current_gallery_images as $key => $image){
			// 
			if ( $new_id = asia_image_exists($image->ID , $post_blog_id )){
				$new_ids[] = $new_id;		
			} else {

				//$image_desc = ( !empty( $image->post_excerpt ) )? $image->post_excerpt : $image->post_content;				
				
				/*$img_src = wp_get_attachment_image_src( $image->ID , 'full' );
				
				$src = get_src_from_url($img_src[0]);*/
				$src = $gallery_info[$key]['src'];
				$old_id=$image->ID;
				$image_alt = $gallery_info[$key]['alt']; 
				$new_id = asia_sideload_image( $src , $image->post_title, $image->post_content , $old_id , $image_alt ,  $image->post_excerpt , $post_blog_id  ) ;
				if ( $new_id ){
					$new_ids[]  = $new_id ;
				}
			} 

			// $post_blog_id : source
			// $to_sync : destination
			store_media_to_correct($image, $new_id, $post_blog_id , $to_sync);
			 
		}		 

	}

	//Reformat copied gallery shortcode if any
	if ( count( $new_ids ) ){
			$new_gallery_shortcode =  sprintf ("[gallery %s ids=\"%s\"]", $gallery_desc, implode ( "," , $new_ids ) );
			$my_post->post_content = preg_replace(HAS_GALLERY_REGEXP, $new_gallery_shortcode, $my_post->post_content);		
	}
	// query_exists
	$exists_query =  $wpdb->prepare( "SELECT ID , post_status FROM {$wpdb->posts} WHERE guid IN (%s,%s)", $my_post->guid, esc_url( $my_post->guid ) ) ;
	$global_post = $wpdb->get_row( $exists_query );
	if ( $global_post ){
		
		if ( defined('FIX_DUPLICATE_SYNC') ) {
			// fix duplicates if exists
			$offset = 1 ;
			while( $an_other_post = $wpdb->get_row( $exists_query , OBJECT , $offset ) ){
				// delete the post
				$post_to_delete = $an_other_post->ID ; 
				// delete the attachements
				delete_post_media($post_to_delete);
				// delete the duplicate post 
				wp_delete_post($post_to_delete);
				$offset++;

			}

			// Remove all the old metas
			$clean_meta_query = $wpdb->prepare( "delete FROM {$wpdb->postmeta} WHERE post_id = %d ", $global_post->ID ) ;
			$wpdb->query($clean_meta_query);

			// update metas on update
			foreach( $global_meta as $key => $value )
				if( $value )
					add_post_meta( $global_post->ID , $key, $value );
			add_post_meta( $global_post->ID , 'not_in_feed', 1 );
		}


		update_post_meta( $global_post->ID, 'new_post_content',  $my_post->post_content );
		
		// link photos to page
		asia_link_photos_to_page($new_thumb_id , $new_ids , $global_post->ID );
		restore_current_blog();

		$status =  ( $global_post->post_status == 'draft' ? ALREADY_IMPORTED  :  ARTICLE_NOT_DRAFT ) ;

		$copied_to_blog = 'copied_to_blogid_' . $to_sync;
		if ( '' == get_post_meta( $post_id, $copied_to_blog, true ) ) {
			update_post_meta( $post_id, $copied_to_blog, $post_id );
		}
		return array( $status ,  $global_post->ID ) ;
	} else {
		unset( $my_post->ID ); // new post
	}
	// Force to draft
	$my_post->post_status='draft';


	if( isset($set_cat_source) ){
		$slug_source = url_to_slug($homeOrigin);

		$source_cat =  rw_get_category_by_slug($slug_source);

		if ( false == $source_cat || ! $source_cat->term_id ) {
			//$mise_en_avant = wp_create_category('mise-en-avant');
			$source_cat = wp_insert_term(
				str_replace( 'http://' , '', $homeOrigin ) , // the term
				'category', // the taxonomy
				array(
				'slug' => $slug_source,
				)
			);
			$source_id = $source_cat['term_id'];
		}else{
			$source_id = $source_cat->term_id ;
		}

		// ajouter la catégorie 'articles-export' pour regrouper tous les imports à un seul endroit,
		// contrairement à la catégorie ci-dessus de slug '$homeOrigin'
		$slug_source = 'articles-export';
		$source_cat =  rw_get_category_by_slug( $slug_source );

		if ( false == $source_cat || ! $source_cat->term_id ) {
			$source_cat = wp_insert_term(
				'Articles export', // the term
				'category', // the taxonomy
				array(
					'slug' => $slug_source,
				)
			);
			$source_id_2 = $source_cat['term_id'];
		} else {
			$source_id_2 = $source_cat->term_id;
		}

		$my_post->post_category = array( $source_id, $source_id_2 );
	}

	$my_post = apply_filters('edit_post_author_duplicate', $my_post, $to_sync);
	
	if($post->post_type == 'test'){
		$p = wp_insert_post( $my_post );
		do_import_tests_quizz($data_quizz, $p);
	}else{
		$p = wp_insert_post( $my_post );
		if($original_slugs){
			wp_set_post_tags( $p, $original_slugs, true );
		}
	}

	// copy metas
	foreach( $global_meta as $key => $value )
		if( $value )
			add_post_meta( $p, $key, $value );

	// link photos to page

	add_post_meta( $p, 'not_in_feed', 1 );

	asia_link_photos_to_page($new_thumb_id , $new_ids , $p );

	restore_current_blog();

	if ( IMPORTED_ARTICLE ) {
		update_post_meta( $post_id, 'copied_to_blogid_' . $to_sync, $post_id ) ;
		return array( IMPORTED_ARTICLE, $p );
	}
}

function asia_link_photos_to_page($new_thumb_id , $new_ids , $p ){
	global $wpdb;
	if ( $new_thumb_id )
		$new_ids[]= $new_thumb_id;
	if ( count($new_ids)){
		// make sure parent is set
		$ids = implode(',', $new_ids);
		$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} set post_parent = %d WHERE post_type='attachment' and ID IN ($ids)", $p) );
	} 

}

function asia_image_exists( $value , $site_id ){
	global $wpdb;
	/*
	$args = array('post_type'=>'attachment',
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'meta_key'=>'origin_id' , 'meta_value'=>$value
			),
			array(
				'meta_key'=>'site_id' , 'meta_value'=>$site_id
			)
		)
	);*/
	// temp 
	$args = array('post_type'=>'attachment','meta_key'=>'origin_id' , 'meta_value'=>$site_id.'.'.$value);

	$exists = get_posts($args );
	if($exists){
		return $exists[0]->ID;
	}
	else{
		return false ;
	}
}

function asia_sideload_image($file, $post_title, $post_content , $old_id , $alt = '' , $excerpt ='' , $post_blog_id ='') {
    preg_match('/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches);
    $file_array['name'] = basename($matches[0]);
    /*créer un fichier temporaire qui sera supprimer par la fonction media_handle_sideload */
    $tmp_name = asia_create_img_tmp($file);
    if ( $tmp_name){
	    $file_array['tmp_name'] = $tmp_name;

	    if ( !function_exists('media_handle_upload') ) {
		  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		  require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		}
	    
	    $id = media_handle_sideload($file_array, 0, '');
	    if ( is_numeric($id)){
		    $my_post = array('ID' => $id, 'post_content' => $post_content , 'post_excerpt' => $excerpt , 'post_title' => $post_title );
		    //debug($my_post); 
		    wp_update_post($my_post);
		    add_post_meta($id, 'origin_id', $post_blog_id.'.'.$old_id  );
		    // make sure origin_id is linked to site.
		    //add_post_meta($id, 'site_id', $post_blog_id  );
		    // set alt
		    if ( $alt )
		    	add_post_meta($id, '_wp_attachment_image_alt', $alt  );

		    return $id;
		} else {
			if( php_sapi_name()=='cli' ) {
			    echo "\nProblème import image: ".$file;
			    var_dump( $id);
			}
		}
	}
	if( php_sapi_name()=='cli' )
    	echo "\nProblème import image: ".$file;
	return false;

    
}

function asia_create_img_tmp($file) {
    $tmp_name = tempnam(ABSPATH . "wp-content/gallery", "mgr");
   	
    if ( strpos( $file , 'http://') === false  ){
    	if ( file_exists($file) )
    		copy($file, $tmp_name);
    	else {
    		if( php_sapi_name()=='cli' )
    			echo "\nPB copy file : ".$file;
    		return false;
    	}
    } else {
    	$tmp_name = download_url($file);
    }
    if( is_string( $tmp_name ) && file_exists($tmp_name)){
	    preg_match('/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches);
	    $tmp_name_new = $tmp_name . '.' . $matches[1];
	    rename($tmp_name, $tmp_name_new);
	    return $tmp_name_new;
    } else {
    	if( php_sapi_name()=='cli' )
    		echo "\nPB download url : ".$file;
    	return false;
    }
}
/*
function debug($var){

		echo "<pre>";
		var_dump($var);
		echo "</pre>";
		exit();
}*/

function get_src_from_url($guid){

	global $homeOrigin;
	if ( strpos(  $guid , $homeOrigin ) === false ) 
		return $guid; 
	$src = str_replace($homeOrigin, '', $guid) ;
	return  ABSPATH . trim($src,'/');
}

function  do_sync_extern_post($post_ex, $source) {
	global $external_import ;
	$cat_name = $external_import[$source]['cat'] ;
	$post_key = $external_import[$source]['post_key'] ;

	$posts_array = get_posts( array( 'meta_key'=> $post_key, 'meta_value'=> $post_ex->id, 'post_status' => 'any' ));
  	if(count($posts_array) > 0 ){
  		$return = array('ALREADY_IMPORTED', $posts_array[0]->ID);
  	}else{
		$category  = get_term_by('name', $cat_name, 'category');
		if(empty($category->term_id)){
			if ( !function_exists('wp_create_category') ) {
		  		require_once(ABSPATH . "wp-admin" . '/includes/taxonomy.php');
			}
			$cat_id = wp_create_category($cat_name);
		}else{
			$cat_id= $category->term_id;
		}


		//$post_key = $source .'_id' ;
		/*if($source == 'be_com'){
			$post_key = 'be_id' ;
		}*/
		$post_content = $post_ex->content ;
		if(isset($post_ex->videos_short_code) && count($post_ex->videos_short_code)){
			$post_content  = implode(' ', $post_ex->videos_short_code) ." \n". $post_content ;
		}

		$post_id = wp_insert_post(
			array(
				'post_content'   => $post_content,
				'post_title'     => $post_ex->title,
				'tags_input'     => $post_ex->tags,
				'post_category'	 =>	array($cat_id),
				'post_excerpt'	 => $post_ex->excerpt,
			)
		) ;
		if($post_ex->thumbnail_full){
			$attach_id = RW_Utils::upload_img($post_ex->thumbnail_full ,$post_ex->title  , '', $post_id );
			if(is_numeric($attach_id)){
				add_post_meta($post_id,'_thumbnail_id',$attach_id);
			}
		}
		add_post_meta($post_id, $post_key,  $post_ex->id);

		//add meta_data to post
		$post_ex_meta = json_decode(json_encode($post_ex->meta_data), true);
		foreach($post_ex_meta as $k => $v) {
			add_post_meta($post_id, $k, $v['0']);
		}

		if(!empty($post_ex->galleries)){
			$attachment_ids = array() ;
			foreach ($post_ex->galleries as $img){
				$id_img = RW_Utils::upload_img($img->url ,$img->title ,$img->excerpt, $post_id);
				if(is_numeric($id_img)){
					$attachment_ids[]= $id_img ;
				}
			}
			if(count($attachment_ids)){
				$post_content = '[gallery ids="'. implode(',', $attachment_ids) .'"] ' ."\n" . $post_content ;
				$my_post = array(
					'ID' => $post_id,
					'post_content' => $post_content,

				) ;
				wp_update_post($my_post) ;
			}

		}
		$return = array(IMPORTED_ARTICLE, $post_id);
  	}
	return $return ;

}

function do_export_tests_quizz($post){

	// Export if post_type == test we should get all profile, questions and responses data of each test

	$quizz = new CF_quizz($post->ID);

	if($quizz){

		$profiles = array();
		$questions = array();
		$profiles_obj = CF_quizz_profile::get_profiles($quizz);
		$index_profiles =array();

		if(!empty($profiles_obj)){

			foreach ($profiles_obj as $k => $profile) {

				$index_profiles[$profile->get_id()] = $k;

				$image_profile = $profile->get_image();
				$profile_image = ($image_profile) ? $image_profile : '';

				$profiles[] = array(
					'image_info' => $profile_image,
					'name'       => $profile->get_name(),
					'description'=> htmlentities( $profile->get_description() ),
					'n_responses'=> $profile->get_n_responses()
		 		);

			}
		}

		$questions_obj = CF_qa_question::get_questions($post->ID);

		if(!empty($questions_obj)){
			foreach ($questions_obj as $question) {
				
				$q = CF_qa_question::get($question->id_question);
				
				$image_question = $q->get_image();

				$question_image = ($image_question) ? $image_question : '';

		 		$responses = array();

		 		$responses_obj = CF_qa_response::get_responses($q);
		 		
		 		foreach ($responses_obj as $response) {

		 			$responses_id_profile = $response->get_id_profile();

		 			$index = isset($index_profiles[$responses_id_profile])? $index_profiles[$responses_id_profile]:0;

		 			$responses[] = array(
		 				'text'    => $response->get_text(),
		 				'profile'=> $index,
		 				'correct'=> $response->is_correct()
		 			);
		 		}

				$questions[] = array(
					'image_info' => $question_image,
					'name'       => $question->question_text,
					'responses'  => $responses
		 		);
		 		
			}
		}

		$data_quizz = array(
			'profiles'    => $profiles,
			'questions'   => $questions
		);
	}
	
	return !empty($data_quizz) ? $data_quizz : null;	
}

function do_import_tests_quizz($data_quizz,$post_id){

	// if post_type == test we should save all profile, questions and responses data for each test 
	if(!empty($data_quizz)){

		$rw_tests = new RW_tests();

		if(!empty($data_quizz['profiles']) && !empty($data_quizz['questions'])){
			
			foreach ($data_quizz['profiles'] as $k => $v) {

				$id_image_profile = RW_Utils::upload_img($v['image_info'], $v['name'], '', $post_id);

				if ( is_wp_error( $id_image_profile ) ) {

					$data_quizz['profiles'][$k]['image_info'] = 0;

				}else{

					$data_quizz['profiles'][$k]['image_info'] = $id_image_profile;

				}
			}

			foreach ($data_quizz['questions'] as $k => $v) {
				
				$id_image_question = RW_Utils::upload_img($v['image_info'], $v['name'], '', $post_id);

				if ( is_wp_error( $id_image_question ) ) {

					$data_quizz['questions'][$k]['image_info'] = 0;

				}else{

					$data_quizz['questions'][$k]['image_info'] = $id_image_question;

				}
			}

			$profiles = $rw_tests->update_profiles($post_id, $data_quizz['profiles'], array());

			$rw_tests->update_questions($post_id, $data_quizz['questions'], array(), false, $profiles);

			print_flush("Test ==> " .$data_quizz['title']. " imported successfully \n");
			
		}
	}
}
/**
 * Create file where stroring posts to sync 
 *
 * @param      post   $media       attachment post to correct
 * @param      int    $new_media_id    id post after copy
 * @param      int    $source          id site source 
 * @param      int    $destination     id site destination
 *  
 */
function store_media_to_correct($media, $new_media_id, $source, $destination){

	if(!empty($media)){ 
		$config_dl = ABSPATH.'/script/sync/copy_medias_'.$destination.'.php';
		if ( !file_exists(ABSPATH.'/script/sync') ){
			mkdir(ABSPATH.'/script/sync', 0755);
		}
		$posts_found = extract_posts_from_legend($media, $source);
		if(!empty( $posts_found)){ 
			append_to_sync_dynamic($source, $config_dl, $new_media_id );
		}

	}
}

/**
 * Scan post excerpt to extract inserted posts links 
 *
 * @param      post   $media       post to correct 
 * @param      int    $source      id site source  
 *  
 * @return     array of posts 
 */
function extract_posts_from_legend($media, $source){
	$posts_found = array();
	$excerpt = $media->post_excerpt;

	preg_match_all('/href=[\"\"].*[post_link.*id=[\"\'](\d+)[\"\'].*\].*[\"\"]|href=[\"\"].*-(\d+).html.*[\"\"]/iUm', $excerpt, $posts_links); 

	
	if(!empty($posts_links)){
		foreach ($posts_links[0] as $i => $full_link) {
			
		 	if(!empty($posts_links[1][$i])) {
			   	$p_id = $posts_links[1][$i];
			}elseif(!empty($posts_links[2][$i])) {
			    $p_id = $posts_links[2][$i];
			}else{
				echo 'BAD POST URL INSERTED';
			}

		 	if(!empty($p_id)){ 
			 	if(post_is_valid($p_id,$full_link, $source)){
			 		$posts_found[$full_link] =  $p_id;
			 	} 
			}
		}
	}
	return $posts_found;
}

/**
 * Check if post exists in a given site
 *
 * @param      post   $id       id post
 * @param      int    $source      id site source  
 *  
 * @return     boolean
 */
function post_is_valid($id, $link, $source){
	switch_to_blog($source); 
	$site_slug = get_site_url($source);

	// remove http/https from url 
	$site_slug  = str_replace('http://', '', $site_slug ) ;
	$site_slug  = str_replace('https://', '', $site_slug ) ;

	if(get_post($id)){
		if( strpos($link, '../') or strpos($link,$site_slug  ) ){ 
			echo 'THE POST IS VALID';
			$valid =  true;
		}else{
			echo 'THE POST DOESNT BELONG TO SOURCE SITE';
			$valid = false;
		}
 	}else{
 		echo 'THE POST DOESNT EXIST';
 		$valid = false;
 	}
 	restore_current_blog();
 	return $valid;
}
/**
 * copy inserted posts in the excerpt of a given post
 *
 * @param      post   $media       post to correct 
 * @param      int    $source      id site source  
 *   
 */
function correct_legend_text_and_copy_inserted_posts($media, $source){
 
	$posts_found = extract_posts_from_legend($media, $source); 
	$ids_corrected = array();
	if(!empty($posts_found)){

		switch_to_blog($source);
		foreach ($posts_found as $key => $post_id) { 
			

			$return = do_sync_site_post( $post_id, get_post($post_id), HOST_BLOG, get_sync_metas( $source ) ) ;
			
			if ( is_array( $return ) ) {
		    	if ( $return[0] == ALREADY_IMPORTED ) {
		    		echo ' ( ALREADY_IMPORTED : ' . $return[1] . ' ) ' . date_i18n( 'Y-m-d H:i:s' ) . "\n";
		    	} elseif ( $return[0] == ARTICLE_NOT_DRAFT ) {
		    		echo ' ( ARTICLE_NOT_DRAFT : ' . $return[1] . ' ) ' . date_i18n( 'Y-m-d H:i:s' ) . "\n";
		    	} else {
		    		echo ' ( Création ' . $return[1] . ' ) ' . date_i18n( 'Y-m-d H:i:s' ) . "\n";

		    	}
		    	$ids_corrected[$post_id] = $return[1];
		    } else {
		    	echo ' - Non importé ' . date_i18n( 'Y-m-d H:i:s' ) . "\n";
		    }

		}
		restore_current_blog();
		correct_legend_text($media, $ids_corrected, $source);
		
		 
	}
}
/**
 * Edit post excerpt with new id after copy 
 *
 * @param      post   $media       post to correct 
 * @param      int    $id_copied_post      id post
 * @param      int    $source      id site source  
 *  
 */
function correct_legend_text($media, $ids_corrected, $source){
	 
	$excerpt = $media->post_excerpt;
	$new_excerpt = $excerpt;
	$links = extract_posts_from_legend($media, $source); 
	 
	if(!empty($links)){  
 
		foreach ($links as $link => $id) {  
			if(!empty($ids_corrected[$id])){ 
				$id = "id='".$ids_corrected[$id]."'";
				$new_link = 'href="[post_link '.$id.']"';     
				$new_excerpt = str_replace($link, $new_link, $new_excerpt); 
				$my_post = array('ID' => $media->ID, 'post_excerpt' => $new_excerpt );
				wp_update_post($my_post);
			}
		} 
		echo 'POST UPDATE DONE SUCCESSFULLY => '.$media->ID;
	}
 
	 
}
/**
 * Clean out the file after correction
 *
 * @param      post   $source       id site source 
 * @param      int    $post_id      id post
 * @param      string    $config_dl     path to file    
 *  
 */
function uncopy_post_from_file( $source , $post_id, $config_dl){ 
	// Save the remaining files
	
	
	$fp = fopen( $config_dl,"c");
	if ( flock($fp, LOCK_EX) ) { 
		// need to read before.
		$posts = require ( $config_dl );
		
		
		if (($key = array_search($post_id, $posts[$source])) !== false) {
	    	unset($posts[$source][$key]);
		}
		if ( count($posts[$source]) == 0 ){
			unset( $posts[$source] );
		}

		$code = var_export($posts ,true );
		$file = <<<SOURCE
<?php 
return $code;
SOURCE;
		// truncate to 0 
		ftruncate($fp, 0);
		rewind( $fp );
		fwrite($fp , $file);
		fclose($fp);

	} else {
		usleep( 10000);
		fclose($fp);
		uncopy_post_from_file( $source , $post_id, $config_dl );
	}
}




