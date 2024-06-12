<?php
/*
// ON utilise menu_order : https://core.trac.wordpress.org/ticket/22608
*/
// Possibilité d'ordonner, affiche Boite avec ordre
add_post_type_support('attachment', 'page-attributes');

function add_query_vars_filter($vars) {
	$vars[] = "item";
	return $vars;
}
add_filter('query_vars', 'add_query_vars_filter');

function media_slideshow($params) {
	parse_str($_SERVER['QUERY_STRING'], $_GET);

	$gallery_slug = $params['gallery'];
	$args = array('gallery' => $gallery_slug, 'post_type' => 'attachment', 'posts_per_page' => 30, 'meta_key' => 'priority', 'order' => 'DESC', 'orderby' => 'meta_value');
	$images = get_posts($args);

	$current_item = isset($_GET['item']) ? $_GET['item'] : 1;
	$current_item = max(1, min($current_item, count($images)));
	$previous_item = max(1, $current_item - 1);
	$next_item = min(count($images), $current_item + 1);

	$current_image = $images[$current_item - 1];
	ob_start();
	include (get_template_directory() . "/include/templates/media-slideshow.php");
	$return = ob_get_clean();
	//ob_end_clean ();
	return $return ;
}
add_shortcode("media_slideshow", "media_slideshow");

add_action('admin_init', 'gallery_register_taxonomy_meta_boxes');
function gallery_register_taxonomy_meta_boxes() {
	if (!class_exists('RW_Taxonomy_Meta')) {
		return;
	}
	$meta_sections = array();
    $meta_sections[] = array('title' => 'Old nextgen', // section title
    'taxonomies' => array('gallery'), // list of taxonomies. Default is array('category', 'post_tag'). Optional
    'id' => 'old-nextgen', // ID of each section, will be the option name
    'fields' => array( // List of meta fields
    array('name' => 'ID nextgen-gallery', // field name
    'desc' => '', // field description, optional
    'id' => 'id-nextgen-gallery', // field id, i.e. the meta key
    'type' => 'text', // field type
    'std' => '', // default value, optional
    )),);
    foreach ($meta_sections as $meta_section) {
    	new RW_Taxonomy_Meta($meta_section);
    }
}

function image_header_taxonomy_meta_boxes() {
	global $site_config;
	if (!class_exists('RW_Taxonomy_Meta')) {
		return;
	}
    $meta_section = array('title' => 'Illustration Header', // section title
    'taxonomies' => array('category'), // list of taxonomies. Default is array('category', 'post_tag'). Optional
    'id' => 'custom-header-image', // ID of each section, will be the option name
    'fields' => array( // List of meta fields
    array('name' => 'Image', // field name
    'desc' => '', // field description, optional
    'id' => 'custom-header-image', // field id, i.e. the meta key
    'type' => 'image', // field type
    'std' => '', // default value, optional
    )));
    new RW_Taxonomy_Meta($meta_section);

    if(get_param_global('logo-header-categorie')) {

	    $meta_section = array('title' => 'logo Header de la catégorie', // section title
	    'taxonomies' => array('category'), // list of taxonomies. Default is array('category', 'post_tag'). Optional
	    'id' => 'custom-logo-header', // ID of each section, will be the option name
	    'fields' => array( // List of meta fields
	    array('name' => 'Logo Header catégorie', // field name
	    'desc' => '', // field description, optional
	    'id' => 'custom-logo-header', // field id, i.e. the meta key
	    'type' => 'image', // field type
	    )));
	    new RW_Taxonomy_Meta($meta_section);

    }

    $meta_section = array('title' => 'Sildeshow', // section title
    'taxonomies' => array('category'), // list of taxonomies. Default is array('category', 'post_tag'). Optional
    'id' => 'category-slideshow', // ID of each section, will be the option name
    'fields' => array( // List of meta fields
    array('name' => 'Slug catégorie', // field name
    'desc' => '', // field description, optional
    'id' => 'category-slideshow', // field id, i.e. the meta key
    'type' => 'text', // field type
    'std' => '', // default value, optional
    )));
    new RW_Taxonomy_Meta($meta_section);
}
add_action('admin_init', 'image_header_taxonomy_meta_boxes');


//define('HAS_GALLERY_REGEXP' ,"/\[gallery\s*ids\s*=\s*\"?\'?((\d|,)+)\"?\'?\s*\](.*?\[\/gallery\])?/im" );
//define('HAS_GALLERY_REGEXP' ,"/\[gallery\s*(g\_d=\"[^\"]*\")?\s*ids\s*=\s*\"?\'?((\d|,)+)\"?\'?\s*\](.*?\[\/gallery\])?/im" );
//define('HAS_GALLERY_REGEXP' ,"/\[gallery.*(g\_d=\"[^\"]*\")?\s*ids\s*=\s*\"?\'?((\d|,)+)\"?\'?.*\](.*?\[\/gallery\])?/im" );
//define('HAS_GALLERY_REGEXP' ,"/\[gallery\s*(g\_d=\"[^\"]*\")?\s*ids\s*=\s*\"?\'?((\d|,)+)\"?\'?.*\](.*?\[\/gallery\])?/im" );

$pattern = get_shortcode_regex( array('gallery') );
define('HAS_GALLERY_REGEXP2', "/$pattern/" );

if(is_dev('adaption_shortcode_galerie_116895557')){
	define('HAS_GALLERY_REGEXP', HAS_GALLERY_REGEXP2 );
	define('NEW_HAS_GALLERY_REGEXP', true );
}else{
	define('HAS_GALLERY_REGEXP' ,"/\[gallery\s*(g\_d=\"[^\"]*\")?\s*ids\s*=\s*\"?\'?((\d|,)+)\"?\'?.*\](.*?\[\/gallery\])?/im" );
	define('NEW_HAS_GALLERY_REGEXP', false );
}


/* New galery Switch */
if ((defined('NEW_GALLERY_ACTIVE') && NEW_GALLERY_ACTIVE) || isset($_GET["NEW_GALLERY_ACTIVE"])):

    // TODO : Trouver un meilleur endroit de virer ce shortcode
    // TODO : s'assurer que slideshow renvoie toujours '' quand nextgen sera supprimé


	function cancel_content_slideshow($content) {
        // desactiver le shortcode nextgen
		if (shortcode_exists('slideshow')) {
			remove_shortcode('slideshow');
		}
		add_shortcode('slideshow', create_function('', 'return;'));
		return $content;
	}
	add_filter('the_content', 'cancel_content_slideshow', 0);

	/**end customize gallerie**/
endif;

// override gallery shortcode
remove_shortcode('gallery');
global $first_gallery , $indexii ;
$first_gallery = false;
function reworld_gallery_shortcode($attr , $content=null){
	if ( is_feed() || isset( $_GET['disable_gallery'] ) ){
		return '' ;
	}
	// save the attrs
	global $first_gallery, $after_wp_head;
	$return='';
	if($after_wp_head){
		$gd = (isset($attr['g_d'])) ?  $attr['g_d']: '' ;
		if ( $gd ){
	    	$attr['content'] = str_replace('&quot;', '"', $gd);
		}else if($content){
			$attr['content'] = $content ;
		}

		$first_time = false;
		if ( $first_gallery===false){
			$first_gallery = $attr;
			$first_time = true;
		}
		//
		if ( $first_gallery['ids'] != $attr['ids'] || $first_time == true ){
			
	    	// just skip the first in the content
			$return = gallery_shortcode($attr);	
		}
	}
	return $return;

}
add_shortcode('gallery', 'reworld_gallery_shortcode');
add_action('wp_head',function(){
	global $after_wp_head ;
	$after_wp_head = true ;
}) ;

// add a class and make sure body has..
function set_layout_gallery_single($classes) {
	if (is_single() || is_page()) {
		if(is_dev('nwk_template_multi_images_137513253')){
			$gallery_type = Rw_Post::get_gallery_type();
			if ( $gallery_type ) {
				if ( $gallery_type == 'mini_diapo' || has_diapo_monetisation() ) {
					$classes[] = 'has_normal_gallery';
				} elseif ( $gallery_type == 'classical' || $gallery_type == 'diapo_popup' ) {
					$classes[] = 'has_gallery';
				} else {
					$classes[] = 'linear-gallery';
				}
			}
		}else{
			if( is_dev('type_diapo_radio_bouton_8067') && get_option('type_diapo_meta_updated') ){
				$gallery_type = Rw_Post::get_gallery_type();
				if( !empty($gallery_type) ){
					if($gallery_type == 'diapo_vertical' ){
						$classes[] = 'has_vertical_gallery';
					}else{
						$classes[] = get_param_global("disable_full_diapo") ? 'has_normal_gallery' : 'has_gallery';
					}
				}
			}else{
				if ( RW_Post::page_has_gallery() )
					$classes[] = get_param_global("disable_full_diapo") ? 'has_normal_gallery' : 'has_gallery';
			}
		}
	}
	return $classes;
}
add_filter('body_class', 'set_layout_gallery_single');
$current_gallery_images=false;
function do_show_gallery() {
	 // TODO : Extract the first gallery and print it here.. 
	global $post;
	if (preg_match(HAS_GALLERY_REGEXP, $post->post_content, $matches)) {
		echo do_shortcode($matches[0]);
	}
}

add_action('show_gallery', 'do_show_gallery');

function get_the_gallery() {
	global $post;
	if (preg_match(HAS_GALLERY_REGEXP, $post->post_content, $matches)) {
		return do_shortcode($matches[0]);
	}
	return false;
}

/**customize gallerie**/



function customize_style_gallery() {
	wp_enqueue_script('reworldmedia-gallery-js', get_template_directory_uri() . '/assets/javascripts/diaporama_galerie'.JS_EXT, array('reworldmedia-bootstrap-base'), CACHE_VERSION_CDN, true);
}
$printed_top_gallery = false;
function get_attachements_post_array($attr){
	global $post,$current_gallery_images;
	$current_gallery_images = array();

	$ids=explode(',' , $attr['ids']);
	// we need to keep the order like in post__in!
	$args = array('post_type' => 'attachment', 'post__in' => $ids, 'orderby' => 'post__in' , 'posts_per_page' => -1);
    // the widget needs it
	
	$current_gallery_images = get_posts($args);

	return $current_gallery_images;
}


if(!function_exists('customize_post_gallery')) :
function customize_post_gallery($null, $attr = array()) {
    // nouveau style
	global $printed_top_gallery, $site_config_js, $current_gallery_images ,$post;
	$is_gallery_in_ajax = get_param_global('gallery_in_ajax');
	if( ! $is_gallery_in_ajax ){
		add_action('add_scripts_css_js', 'customize_style_gallery');
	}
	$current_gallery_images = get_attachements_post_array($attr);
	$get_template_directory = get_template_directory();
	$navs_thumbs_diapo_article = get_post_meta(get_the_ID(), 'navs_thumbs_diapo_article',true);
	$disable_next_gallery = get_post_meta(get_the_ID(), 'disable_next_gallery', true);
	$class_disable_carousel = (isset($attr['disable_carousel']) && $attr['disable_carousel'] && is_dev('disable_carousel_116598303')) ? ' hidden' : '';
	$content_diapo = "";
	$related_posts_all = [];

	if (!$printed_top_gallery) {
		if(get_param_global('diapo_redirection') && $disable_next_gallery != true) {

			$id_next_post = get_post_meta( get_the_ID(), 'next_gallery', true );
			$url_next_post = get_post_meta( get_the_ID(), 'next_gallery_url', true );

			if($url_next_post){
				$gallery_url = $url_next_post;
			}else{
				if(!$id_next_post){
					$my_posts = array();
					$related_posts_all = RW_Post::get_posts_have_gallery();
					foreach ($related_posts_all as $related_posts) {
						if( $related_posts->have_posts() ){	
							while( $related_posts->have_posts() ){	
								$related_posts->the_post();    
								$my_posts[] = $post;
								break;
							}								    
						}				    
					}
					wp_reset_postdata();
					if(!empty($my_posts))
						$id_next_post = $my_posts[0]->ID;

				
				}
				if($id_next_post){
					$gallery_url = get_the_permalink($id_next_post);
				}
			}
		}
		
		if(!get_param_global('active_multi_gallery')){
			$printed_top_gallery = true;
		}
		
		if( ! $is_gallery_in_ajax ){
			if(!get_param_global('active_multi_gallery')){
				wp_enqueue_script('reworldmedia-gallery-js', get_template_directory_uri() . '/assets/javascripts/diaporama_galerie.js', array(), CACHE_VERSION_CDN, true);
			}else{
				wp_enqueue_script('reworldmedia-gallery-js', get_template_directory_uri() . '/assets/javascripts/diaporama_multi_galerie.js', array(), CACHE_VERSION_CDN, true);
			}
		}
		
		if(get_param_global('diapo_redirection') && isset($gallery_url) ){
			wp_localize_script( 'reworldmedia-gallery-js', 'gallery_url', $gallery_url );
		}
		do_action('do_customize_post_gallery');

		$format_thumb_diapo=( get_param_global('type_diapo_article') == 1 && $navs_thumbs_diapo_article!=1 ) ? "medium" : "gallery_thumb";
		$no_nav_thumbs=( get_param_global('type_diapo_article') == 1 && $navs_thumbs_diapo_article!=1 ) ? ' no_nav_thumbs' : '';

		$id_thumbs = explode(",", $attr['ids']);

		$attachments = array();
		$swfobject_already_loaded=false;
		
		$attachments = get_customize_post_gallery($post, $attr  ) ;

		//$custom_urls = get_custom_url_by_ids($id_thumbs);
		
		foreach ($attachments as $key => &$attachment) {
		


			$get_the_content = $attachment['post_content'];
			
		    
			$shortcode = array();
		    $gallery_video = '' ;
			if(is_dev('autoplay_article_diaporama_116516201')){
				global $shortcode_tags;
				preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $get_the_content, $matches );
				if($matches){
					$tagnames = array_intersect( array_keys( $shortcode_tags ), $matches[1] );
					if(count($tagnames)){
						$pattern = get_shortcode_regex( $tagnames );
						preg_match ("/$pattern/", $get_the_content, $matches );
						$attr = shortcode_parse_atts( $matches[3] );
						$shortcode['attr'] = $attr ;
						$shortcode['name'] = $matches[2] ;
						$shortcode['text'] = $matches[0] ;

						if($shortcode['name'] == 'fpvideo') {
		    				$gallery_video = $shortcode['attr']['mediaid'] ;
		    			}
					}
				}
			}else{
				$pattern = '#\[fpvideo (.*)\]#';
				preg_match($pattern, $get_the_content, $videos_short);
				$video_params=RW_Utils::get_video_params($get_the_content);
		    	$gallery_video = isset($video_params['url']) ? $video_params['url'] : '';				
			}
		    


	    	if(!$swfobject_already_loaded && strpos($gallery_video,'dai') !==false){
				enqueScriptIfNotAlreadyDone('reworldmedia-swfobject', get_template_directory_uri() . '/assets/javascripts/swfobject.js');
				$swfobject_already_loaded=true;
	    	}


		    $attachment['shortcode'] = $shortcode ;

			$list_gallery_video = array();
			if($gallery_video){
				$list_gallery_video=get_video_params('[fpvideo mediaid="'. $gallery_video .'"]');
			}

			if(isset($shortcode) && !empty($shortcode['attr']) ){
				foreach ($shortcode['attr'] as $key => $value) {
					$attachment['data'][$key] = $value ;
				}

			}
		    $attachment['data'] = array_merge($attachment['data'],  array(
		    	'video' => $gallery_video,
		    	'video_type' => isset($list_gallery_video['video_id'])? $list_gallery_video['type'] : '',
		    	'video_id' => isset($list_gallery_video['video_id'])? $list_gallery_video['video_id'] : '',
		    ));
            // make the image on the diapo clickable.
          
		}


		if(empty($related_posts_all)){
			$related_posts_all = get_posts_have_gallery();
		}
		$content_related_posts="";
			
		global $has_gallry ;
		$has_gallry = true ;
			
		foreach ($related_posts_all as $related_posts) {
			if( $related_posts->have_posts() ){	
				while( $related_posts->have_posts() ){
					
					$related_posts->the_post();				    
					$content_related_posts .= RW_Utils::rw_load_template(locate_template('include/templates/list-item-normal.php'));
				}								    
			}				    
		}
		wp_reset_postdata();
		



		$content_related_posts=($content_related_posts!="") ? "<div class='homeMoreArticles items-posts'> <div class='row'>".$content_related_posts."</div></div>" : "";
		$attachments_count = count($attachments);
		if(get_param_global('active_multi_gallery')){
			$template_diapo = 'include/templates/template_multi_galerie.php';
		}else{
			$template_diapo = apply_filters('template_diapo', 'include/templates/template_diapo.php');
		}

		include(locate_template($template_diapo));

	}
	
	return $content_diapo;


}
endif;





function get_customize_post_gallery($post, $attr = array() , $forse = false) {
    // nouveau style
	global $printed_top_gallery, $site_config_js, $current_gallery_images ,$post;

	$meta_key= 'post_gallery_cache_' . md5($attr['ids']) ;
	if(!$forse){
		$data = get_post_meta($post->ID, $meta_key,true);
		if($data){
			return $data ;
		}	
	}

	$current_gallery_images = get_attachements_post_array($attr);

	$navs_thumbs_diapo_article = get_post_meta($post->ID, 'navs_thumbs_diapo_article',true);
	$class_disable_carousel = (isset($attr['disable_carousel']) && $attr['disable_carousel'] && is_dev('disable_carousel_116598303')) ? ' hidden' : '';
	$content_diapo = "";

		
	

		$format_thumb_diapo=( get_param_global('type_diapo_article') == 1 && $navs_thumbs_diapo_article!=1 ) ? "medium" : "gallery_thumb";
		$no_nav_thumbs=( get_param_global('type_diapo_article') == 1 && $navs_thumbs_diapo_article!=1 ) ? ' no_nav_thumbs' : '';

		$id_thumbs = explode(",", $attr['ids']);

		$attachments = array();
		$swfobject_already_loaded=false;
		
		$custom_urls = get_custom_url_by_ids($id_thumbs);
		
		foreach ($current_gallery_images as $image_gallery) {
			$id_thumb = $image_gallery->ID;			

  			$size = array(
				'thumbnail' =>  'rw_thumb', 
		    	'medium' =>  'rw_medium', 
		    	'large' =>  'rw_large', 
		    	'full' =>  'rw_full', 
		    	'image_diaporama' =>  'image_diaporama', 
		    	'thumbnail_diaporama' =>  'thumbnail_diaporama', 
		    	'gallery_full' =>  apply_filters( 'size_diaporama_full', 'rw_gallery_full') , 
		    	'gallery_thumb' => 'rw_gallery_thumb', 
		    );


			$get_the_content = $image_gallery->post_content;
			
		    
			
			
		   

			$insta_param=extract_shortcode_params($get_the_content);
			$insta_url = isset($insta_param[1])? $insta_param[1]: '' ;

		    $format_gallery_thumb = '';
		    if(is_dev('generer_une_miniature_du_post_instagram_113730507') && !empty($insta_url)) {
				$insta_shortcode_id = end(explode('/', $insta_url));
		    	$format_gallery_thumb = get_instagram_image_url('xs', $insta_shortcode_id, $id_thumb);
		    	$format_gallery_thumb = array($format_gallery_thumb);
		    }else{
				$format_gallery_thumb = wp_get_attachment_image_src($id_thumb, $size[ $format_thumb_diapo] );
		    }
		   
			$attachment_size = get_param_global('gallery_big_image_zise', 'gallery_full');
			$size_diaporama_full = wp_get_attachment_image_src($id_thumb, $size[ $attachment_size] ) ;

		  







		    $attachment = array(
		    	'id' => $id_thumb, 
		    	'thumbnail' => $size_diaporama_full, 
		    	'medium' => $format_gallery_thumb, 
		    	'large' => $size_diaporama_full, 
		    	'full' => $size_diaporama_full, 
		    	'image_diaporama' => $size_diaporama_full, 
		    	'thumbnail_diaporama' => $size_diaporama_full, 
		    	'gallery_full' => $size_diaporama_full , 
		    	'gallery_thumb' => $format_gallery_thumb, 
		    	//'gallery_video' => $gallery_video, 
		    	//'gallery_insta' => $insta_url, 
		    	'post_content' => $image_gallery->post_content
		    );

			$list_gallery_video = array();
			if(!empty($gallery_video)){
				$list_gallery_video=get_video_params('[fpvideo mediaid="'. $gallery_video .'"]');
			}
			$attachment['data'] = array();
			if(isset($shortcode) && !empty($shortcode['attr']) ){
				foreach ($shortcode['attr'] as $key => $value) {
					$attachment['data'][$key] = $value ;
				}

			}
		    $attachment['data'] = array_merge($attachment['data'],  array(
		    	'src' => $size_diaporama_full[0],
		    	'insta' => $insta_url,
		    	'video' => isset($gallery_video) ? $gallery_video : '' ,
		    	'video_type' => isset($list_gallery_video['video_id'])? $list_gallery_video['type'] : '',
		    	'video_id' => isset($list_gallery_video['video_id'])? $list_gallery_video['video_id'] : '',
		    ));
            // make the image on the diapo clickable.
            $custom_url = isset($custom_urls[$id_thumb]) ? $custom_urls[$id_thumb] : '';

            if ( ! empty( $custom_url ) ) {
                $attachment[ 'data' ][ 'custom_url' ] = $custom_url;
            }
            array_push( $attachments, $attachment );
		}

		update_post_meta($post->ID, $meta_key, $attachments);


		return $attachments ;

	


}


function get_custom_url_by_ids($ids){
	global $wpdb;
	$ids = implode(',', $ids);
	$items = $wpdb->get_results( 'select SQL_CACHE post_id, meta_value from '.$wpdb->prefix.'postmeta WHERE meta_key="custom_url" AND post_id IN('.$ids.')', ARRAY_A );
	$result = [];
	foreach ($items as $key => $value) {
		# code...
		if( isset($value['post_id']) && isset($value['meta_value']) ){
			$result[$value['post_id']] = $value['meta_value'];
		}
	}
	return $result;
}

function linear_customize_post_gallery( $input , $attr = array()) {

	// nouveau style
	global $printed_top_gallery, $site_config_js, $current_gallery_images, $content_diapo_scroll ;

	$current_gallery_images = get_attachements_post_array($attr);
	$content_diapo = "";

	$printed_top_gallery = true;
	$id_next_post = get_post_meta( get_the_ID(), 'next_gallery', true );
	$class_diapo_linear = apply_filters('diapo_linear_gallery_class','diapo_linear block_diapo');

	if(!$id_next_post){
		$my_posts = array();
		$related_posts_all = get_posts_have_gallery();
		foreach ($related_posts_all as $related_posts) {
			if( $related_posts->have_posts() ){
				while( $related_posts->have_posts() ){
					$related_posts->the_post();
					global $post;
					$my_posts[] = $post;
				}
			}
		}
		wp_reset_postdata();
		if(!empty($my_posts[1]))
			$id_next_post = $my_posts[1]->ID;
	}

	$params = array(
		'url' => get_the_permalink($id_next_post) // get_post($id_next_post)->guid
	);

	$format_thumb_diapo=( get_param_global('type_diapo_article') == 1 ) ? "medium" : "gallery_thumb";
	$id_thumbs = explode(",", $attr['ids']);
	$attachments = array();
	$swfobject_already_loaded=false;
	foreach ($id_thumbs as $id_thumb) {
		$id_thumb = stripslashes(str_replace('&#034;', '', $id_thumb)) ;
		$post_ = get_post($id_thumb);
		$get_the_content = $post_->post_content;
		$get_the_title = $post_->post_title;

		$post_excerpt = stripslashes(str_replace('&quot;', '"', $post_->post_excerpt));
		$post_excerpt = str_replace('../wp-content/', '/wp-content/', $post_excerpt);
		$post_excerpt = apply_filters('media_gallery_excerpt',$post_excerpt, $post_);

		$pattern = '#\[fpvideo (.*)\]#';
		preg_match($pattern, $get_the_content, $videos_short);
		$video_params=get_video_params($get_the_content);
	    $gallery_video ='';
	    if(count($video_params)>0) {
			$gallery_video = $video_params['url'];
	    	if(!$swfobject_already_loaded && strpos($gallery_video,'dai') !==false){
				enqueScriptIfNotAlreadyDone('reworldmedia-swfobject', get_template_directory_uri() . '/assets/javascripts/swfobject.js');
				$swfobject_already_loaded=true;
	    	}
	    }

	    $image_format = 'rw_gallery_full' ;

	    if(rw_is_mobile()){
	    	/*if(existe_image_size('rw_medium')){
	    		$image_format = 'rw_medium' ;
	    	}*/

	    	$image_format = get_param_global('linear_gallery_format_mobile', $image_format)   ;
	    }
	    $attach = array (
			'id' => $id_thumb, 
			'post_title' => $get_the_title, 
			'post_excerpt' => $post_excerpt,  
			'gallery_full' => wp_get_attachment_image_src($id_thumb, $image_format),
			'gallery_video' => $gallery_video 
		);
		array_push($attachments, $attach);

	}
	$content_diapo .= '<!--bloc_diapo-->';
	$content_diapo.= "<div class='".$class_diapo_linear."'>";
	$video_in_first = false;
	$attachments_count = count($attachments);
	for($i = 0; $i < $attachments_count; $i++){
		//var_dump($attachments[$i]);
		$content_diapo = apply_filters('before_gallery_linear_infos_div',$content_diapo, $i);
		$content_diapo .= '<div class="diaporama-infos">';
		$item_ = $i+1;
		$id_thumb_ = $attachments[$i]['id'];
		$diap_title = $attachments[$i]["post_title"];
		$diap_title_rewritten = sanitize_title($diap_title, 'item-'.$item_);
		$is_video =($attachments[$i]["gallery_video"]!="")?true:false;
		$tag=($is_video)?'div':'figure';
		$figure_data = '';
		if( get_param_global('zoom_images_diapo_linear') && !$is_video ){
			$figure_data = ' data-featherlight="'. $attachments[$i]["gallery_full"][0] . '"';
		}
		$item_index = $i+1;
		 
		$content_diapo .= '<'.$tag.' class="diaporama-image" id="'.$diap_title_rewritten.'-'.$id_thumb_.'" data-id="'.$item_.'" >';


			if($is_video) {
				// autoplay="no"
				if($i == 0){
					$video_in_first = true;
				}
				$content_diapo.= '<div class="block_video_gallery">';
				$content_diapo.= do_shortcode('[fpvideo mediaid="'.$attachments[$i]["gallery_video"].'" height="420"]');
				$content_diapo.= '</div>';
				//$content_diapo.= "<img  src='" . $attachments[$i]["gallery_full"][0] . "' alt='' />";
				$content_diapo .= '</div>'; // diaporama-image div
				$content_diapo .='<div class="infos">';
				$content_diapo .='	<div class="caption">'.$attachments[$i]["post_title"].'</div>';
				$content_diapo .='	<div class="credit">'.$attachments[$i]["post_excerpt"].'</div>';
				$content_diapo .='</div>'; // close info div
			} else {
				$itemprop = '';
				$meta_itemprop = '';
				if($i == 0 || $video_in_first == true){
					$itemprop = 'itemprop = "image"';
					$meta_itemprop = '<meta itemprop="thumbnailUrl" content="'.$attachments[$i]["gallery_full"][0].'"  />';
					$video_in_first = false;
				}

				$data_attrs = array(
					'data-src' => $attachments[$i]['gallery_full'][0],
				);
				$data_attrs = apply_filters('media_gallery_item_data_attrs', $data_attrs, $attachments[$i]['id']);
				$item_attrs = '';
				foreach($data_attrs as $k => $v) {
					$item_attrs .= " $k='$v' ";
				}
				
				// lazy loading ne fonctionne pas pour l'instruction commentée
				$content_diapo .= "	<img $figure_data $item_attrs $itemprop width='100%' height='auto' src='http://sf.be.com/wp-content/themes/reworldmedia/assets/images/blank.gif' class='lazy-load img-responsive pinit-here' alt='".htmlentities($attachments[$i]["post_title"], ENT_QUOTES)."' />";
				//$content_diapo .="	<img $itemprop src='" . $attachments[$i]["gallery_full"][0] . "' ".htmlentities($attachments[$i]["post_title"], ENT_QUOTES)."' />";
				$content_diapo .= $meta_itemprop;
				$content_diapo .= '<div class="diapo_legend_mobile">';
				$mobile_pagination_diapo = '';
				if( get_param_global('add_pagination_diapo_mobile')){
					$mobile_pagination_diapo ='<span class="pagination_diapo"><span class="mobile_active_slide">'.$item_index.'</span>/'.$attachments_count.'</span> ';
				}
		
				if ( !get_param_global("disable_mobile_img_gallery_title") && ! empty( $attachments[ $i ]['post_title'] ) ) {
					$content_diapo .= '	<h2>' . $mobile_pagination_diapo . $attachments[ $i ]['post_title'] . '</h2>';
				}
				$content_diapo .= '</div>';
				if ( ! empty( $attachments[ $i ]['post_excerpt'] ) ) {
					$content_diapo .= '	<figcaption>' . $attachments[ $i ]['post_excerpt'] . '</figcaption>';
				}
				$content_diapo .='</figure>';
			}
		$content_diapo .='</div>';
		$content_diapo = apply_filters('after_linear_diapo_item', $content_diapo);
		 // close diaporama-infos div
		$content_diapo=apply_filters('content_diapo_after_diaporama_infos_div',$content_diapo, array('i' => $i) );
	}
	if(@$attr['content']!="" && trim(str_replace("&nbsp;", "", $attr['content'])) != "")
		$content_diapo .= "<div class='gallery_desc'>".$attr['content']."</div>";

	$content_diapo .= '</div>';
	$content_diapo .= '<!--/bloc_diapo-->';

	$content_diapo = apply_filters('after_diapo_linear_content', $content_diapo);

	return $content_diapo;
}

/**
 * Switche between template
 *
 * @param      <type>  $null   The null
 * @param      array   $attr   The attribute
 */
function post_gallery_template_switcher(){ 
	global $post;
	$selected_template = Rw_Post::get_gallery_type();
	if( $selected_template == "diapo_monetisation_mobile" ){
		add_filter('post_gallery', 'customize_post_gallery', 10, 2);
	}
	elseif( $selected_template == 'linear' || $selected_template == 'linear_mobile' || $selected_template == 'diapo_conseille_taboola_mobile' ){

		add_filter('post_gallery', 'linear_customize_post_gallery', 10, 2);
		remove_action('seo_single','show_gallery_diapo');
	}else{
		add_filter('post_gallery', 'customize_post_gallery', 10, 3);
	}
}

if(is_dev('nwk_template_multi_images_137513253') || is_dev('article_mobile_diaporama_lineaire_151554665') ){
	add_action('wp', 'post_gallery_template_switcher', 99999);
}else{
	add_filter('post_gallery', 'customize_post_gallery', 10, 2);
}


add_action('wp', 'load_gallery_ajax');
/**
 * Charge les images de la gallery et l'envoie le resultat à ajax
 */
function load_gallery_ajax(){
	if ( get_param_global('gallery_in_ajax') && !empty($_REQUEST['gallery_ajax']) ) {
		$attr = $_REQUEST['attr'];
		$html_gallery = customize_post_gallery(null,  $attr);
		echo $html_gallery;
		exit();
	}
}

// Customize admin reworld gallery
//  Renders extra controls in the Gallery Settings section of the new media UI.
if ( !class_exists('Reworld_Gallery_Settings')) :
class Reworld_Gallery_Settings {
	function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'print_media_templates', array( $this, 'print_media_templates' ) );

		add_action( 'wp_enqueue_media', array( $this, 'wp_enqueue_media' ) );
		add_action( 'print_media_templates', array( $this, 'print_media_templates' ) );

		
		// active copyright field on media uploader
		add_filter( 'attachment_fields_to_edit', array( $this, 'active_copyright_field_on_media' ), 10, 2 );
        add_action( 'edit_attachment', array( $this, 'active_copyright_field_on_media_save' ), 11, 1 );
        if ( get_param_global( 'active_diapo_custom_url_field' ) ) {
            add_filter( 'attachment_fields_to_edit', array( $this, 'active_custom_url_for_media' ), 11, 2 );
            add_action( 'edit_attachment', array( $this, 'active_custom_url_field_on_media_save' ), 12, 1 );
        }
	}

	function admin_init() {}

	
	 // Registers/enqueues the gallery settings admin js.
	 

	function wp_enqueue_media() {
		global $site_config_js ;
		if ( ! wp_script_is( 'reworld-gallery-settings', 'registered' ) )
			wp_enqueue_script('reworld-gallery-settings', get_template_directory_uri().'/assets/javascripts/gallery-dash.js', array('media-views'), CACHE_VERSION_CDN, true );
		
		$lang = substr(get_locale(), 0, 2) ;
		$site_config_js['lang'] = $lang ;
	}

	function print_media_templates(){
		?>
		<script type="text/html" id="tmpl-webpick-gallery-settings">
			<label class="setting">
				<span><?php _e( 'Description', REWORLDMEDIA_TERMS ); ?></span>				
				<textarea name='g_d'  class="g_d" id='g_d' data-setting="g_d"></textarea>
			</label>
			
		</script>
		<style type="text/css">
		.media-sidebar .mceLayout .mceLast .mceStatusbar {
		    display: none;
		}
		#wp-link-wrap {
		    z-index: 200123;
		}
		.mce-container-body.mce-abs-layout{
			z-index: 200123 !important;
		}

		</style>

		<script type="text/html" id="tmpl-webpick-attachment-settings">
				<a class="button-primary"  href="javascript:void(0)">Save media</a>			
		</script>

		<?php
		/* Ajouter les deux case à coucher dupliquer titre et légende*/
		if(get_param_global( "dupliquer_titre_legende" ) && is_dev("dupliquer_titre_legende_diaporama_139269671")){
			$gallery = get_post_gallery( $post, false );
			/*Récuperer les valeurs des cases à partir du shortcode*/
			$title_value = (isset($gallery['titre_dupliquer']) && $gallery['titre_dupliquer'] == "true") ? 'checked="checked"' : "";
			$legende_value = (isset($gallery['extrait_dupliquer']) && $gallery['extrait_dupliquer'] == "true") ? 'checked="checked"' : "";
			?>

			<script type="text/html" id="tmpl-my-gallery-title-legend">
			    <label class="setting">
					<span><?php _e(' Dupliquer le titre pour tout le diapo ', REWORLDMEDIA_TERMS); ?>
					</span>
					<input type="checkbox" <?php echo $title_value; ?> id="title_dup" data-setting="titre_dupliquer"/>
			    </label>

			    <label class="setting">
					<span><?php _e(' Dupliquer la légende pour tout le diapo ', REWORLDMEDIA_TERMS ); ?></span>
					<input type="checkbox" <?php echo $legende_value; ?> id="legende_dup" data-setting="extrait_dupliquer"/>
			    </label>
			</script>
			<script>
			    jQuery(document).ready(function(){
			        _.extend(wp.media.gallery.defaults, {
			       	  titre_extrait_dupliquer: false,
			        });
			    });
			</script>
			<?php
		}
	}
	 
    
  
    function load_tiny_mce_editor() {}

    function active_custom_url_for_media( $form_fields, $post ) {
        $value = get_post_meta( $post->ID, 'custom_url', true );

        $form_fields[ 'custom_url' ] = array(
			'label' => __( 'Custom URL', REWORLDMEDIA_TERMS ),
			'input' => 'text',
			'value' => $value,
			// 'helps' => 'If provided, photo credit will be displayed',
		);

		return $form_fields;
    }

	/**
	 * Add copyright field to media uploader
	 *
	 * @param $form_fields array, fields to include in attachment form
	 * @param $post object, attachment record in database
	 * @return $form_fields, modified form fields
	 */
 
	function active_copyright_field_on_media( $form_fields, $post ) {
		$value = get_post_meta( $post->ID, 'copy_right_for_media', true );
		
		$form_fields['copy_right_for_media'] = array(
			'label' => __('Copyright', REWORLDMEDIA_TERMS),
			'input' => 'text',
			'value' => $value,
			// 'helps' => 'If provided, photo credit will be displayed',
		);


		return $form_fields;
	}

	/**
	 * Save values of copyright field in media uploader
	 *
	 * @param $post array, the post data for database
	 * @param $attachment array, attachment fields from $_POST form
	 * @return $post array, modified post data
	 */
	function active_copyright_field_on_media_save( $attachment_id ) {
	    if ( isset( $_REQUEST['attachments'][$attachment_id]['copy_right_for_media'] ) ) {
	        $copy_right_for_media = $_REQUEST['attachments'][$attachment_id]['copy_right_for_media'];
	        update_post_meta( $attachment_id, 'copy_right_for_media', $copy_right_for_media );
	    }
	}

    /**
     * Save values of Custom URL field in media uploader
     * @param $post array, the post data for database
     * @param $attachment array, attachment fields from $_POST form
     * @return $post array, modified post data
     */
    function active_custom_url_field_on_media_save( $attachment_id ) {
        if ( isset( $_REQUEST[ 'attachments' ][ $attachment_id ][ 'custom_url' ] ) ) {
            $custom_url = $_REQUEST[ 'attachments' ][ $attachment_id ][ 'custom_url' ];
            update_post_meta( $attachment_id, 'custom_url', $custom_url );
        }
    }

}
endif;
if (  is_admin() )
	new Reworld_Gallery_Settings;


function get_gallery_ids ($matches){
	$ids = '' ;
	if(isset($matches[3])){
		$attr = shortcode_parse_atts( $matches[3] );
		$ids = isset($attr['ids'])? $attr['ids'] :'';
	}
	return $ids ;
}


if( rw_is_mobile() && is_dev('mobile_ads_gallery_linear_152711795') ){
	add_action('init', 'add_gallery_lineaire_content_ads');
	add_filter('before_gallery_linear_infos_div', 'add_gallery_lineaire_mobile_ads', 10, 2);
}
/**
* Add Gallery linear content ads shortcode on mobile
*/
function add_gallery_lineaire_content_ads(){
	$content_ads_list = get_param_global('diapo_lineaire_mobile_ads');
	$content_ads_list = $content_ads_list['content_ads'];
	if( !empty($content_ads_list) ){
		foreach ($content_ads_list as $key => $value) {
			add_action($key, function () use ($value){ 
				echo do_shortcode($value);
			}, 1);
		}
	}
}
/**
* Add Gallery linear ads shortcode on mobile
* @return String gallery content HTML
*/
function add_gallery_lineaire_mobile_ads($content_diapo, $i){
	global $ads_count;
	if( $i==0 ) $ads_count = 0;
	$diapo_ads_list = get_param_global('diapo_lineaire_mobile_ads');
	$diapo_ads_list = !empty($diapo_ads_list['diapo_ads']) ? $diapo_ads_list['diapo_ads'] : false;
	if( !empty($diapo_ads_list) && $i%3 == 0 ){
		if( !empty($diapo_ads_list[$ads_count]) ){
			$content_diapo .= do_shortcode($diapo_ads_list[$ads_count]);
		}
		$ads_count++;
	}
	return $content_diapo;
}
	
/**
 *  forcer l'affichage du diapo en premie
 * @return  null
 */

function linear_mobile_before_content (){
	$gallery_type = Rw_Post::get_gallery_type();
	if( $gallery_type == 'linear_mobile' || $gallery_type == 'diapo_conseille_taboola_mobile' ) {
		add_action('after_visu_diapo', 'do_show_gallery');
	}
}
add_action('wp', 'linear_mobile_before_content', 9999);


/**
* Enqueue featherlight library
*
* @return void
*/
function featherlight_scripts(){
	$selected_template = Rw_Post::get_gallery_type();
	if( get_param_global('zoom_images_diapo_linear') && ($selected_template == 'linear' || $selected_template == 'linear_mobile' || $selected_template == 'diapo_conseille_taboola_mobile') ){
		$template_dir_uri = get_template_directory_uri();
		wp_enqueue_style('featherlight-css',  $template_dir_uri . '/assets/stylesheets/featherlight.min.css', array(), CACHE_VERSION_CDN);
		wp_enqueue_script('featherlight', RW_THEME_DIR_URI.'/assets/javascripts/featherlight.min.js', array('jquery'), CACHE_VERSION_CDN, true );
	}
}
if( is_dev('zoom_images_diapo_linear_156084199') ){
	add_action('wp_enqueue_scripts', 'featherlight_scripts' , 100 );
}

if( get_param_global('diapo_lineaire_retour_arriere') ){
	add_filter('after_diapo_linear_content', 'diapo_linear_history_cleaner_btn');
}
function diapo_linear_history_cleaner_btn($content, $item){
	$content .= '<div id="linear_top" class="hide"></div>';
	return $content;
}