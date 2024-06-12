<?php

/**
 * Ce script va importer des articles se trouvant dans un fichier copy_posts_$num_blog.php
 * Ce fichier "copy_post_$num_blog.php" est généré par le back office dans le menu "Export"
 * Dans ce menu il y a le site "source" d'où l'on veut chercher les articles, ainsi qu'un
 * champ "List des ids ..." qu'on doit renseigner avec les ids des articles appartenant au site "source".
 *
 * Ex : se connecter sur le BO de "feminin", ensuite dans le menu "Export" choisir
 * la source "mariefrance.rw.loc.env" et renseigner les ids des posts à importer à partir de mariefrance,
 * ce qui a pour conséquence de générer le fichier copy_posts_$num_blog.php.
 * Enfin executer le script :
 * php do_sync_posts.php feminin.rw.loc.env
 */

$host = @$argv[1];

define('LOCK_FILE', dirname(__FILE__) . '/sync/'. basename(__FILE__ , ".php") . '_'. $host .'_activ.lock' );
define('CHECK_SHELL_PARAMS', 1) ;

ini_set('memory_limit', '264M');

require_once dirname(__FILE__).'/init_script.php';

define('DL_PER_RUN', 10);

$sapi_type = php_sapi_name();

$host = @$argv[1] ; 

// wa can have more sources..
$is_fix = @$argv[3] ; 
if ( $is_fix=='1'){
	define('FIX_DUPLICATE_SYNC' , true );
}


$destination = get_current_blog_id() ;

$sync_path = ABSPATH.'script/sync/';

if($destination){
	$config_dl = $sync_path.'copy_posts_'.$destination.'.php'; 
	define( 'HOST_BLOG' , $destination );
	$force =false;
}else{
	die('Define destination ');
}

function uncopy_post( $source , $post_id ){ 
	// Save the remaining files
	
	global $config_dl ; 

	
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
		uncopy_post( $source , $post_id );
	}
}



function copy_post($post_id, $post , $metas=array() ) {
    // grad nb_persons
    echo " \ncopy post ".$post_id. ' : '.$post->post_title ;
    $return = do_sync_site_post( $post_id , $post , HOST_BLOG , $metas );
    if ( is_array($return)){
    	if ($return[0]==ALREADY_IMPORTED){
    		echo " ( ALREADY_IMPORTED : ". $return[1].") " .  date_i18n('Y-m-d H:i:s' );
    	} else if ($return[0]==ARTICLE_NOT_DRAFT){
    		echo " ( ARTICLE_NOT_DRAFT : ". $return[1].") " .  date_i18n('Y-m-d H:i:s' );
    	} else {
    		echo " ( création ". $return[1]. ") " .  date_i18n('Y-m-d H:i:s' );	
    	}
    } else {
    	echo " - Non importé " .  date_i18n('Y-m-d H:i:s' );
    }

    ob_flush();
	flush();
}

 

function get_sync_metas($source){
	$metas = array(
			'sharedcount',
			'afficher_signature',
			'rss_redactions',
			'_yoast_wpseo_title',
			'_yoast_wpseo_metadesc',
			'_yoast_wpseo_opengraph-title',
			'_yoast_wpseo_metakeywords',
			'_yoast_wpseo_focuskw',
			'google_news',
			'exclude_from_latest_posts',
			'is_thumb_featured',
			'not_in_feed',
			'post_auteur',
			'refresh_desactive',
			'title_highlight',
	);

	if ( in_array( $source , [2,5]) ) {
		$metas_recettes = array(  'post_is_recette' , 'post_ingredients' , 'post_prep_duration' , 'post_cook_duration' , 'post_cook_repos' , 'post_cook_refrigeration' ,'post_cook_marinade' ,
	'post_calories' , 'post_fat_content' ,'post_nb_persons' , 'post_list_ingredients' , 'post_email_contributor' ,'post_contributor','wideonet_url');
		$metas = array_merge( $metas , $metas_recettes );
	} else if($source == 8){
		$metas_deco = array(
			'navs_thumbs_diapo_article', 
			'section_permalink', // [deco, renovation, jardin]
			'content_type',
			'original_raise',
			'original_author',
			'original_id',
			'original_meta_name',
			'original_url',
			'export_file'
		);
		$metas = array_merge( $metas , $metas_deco );
	}

	$metas = apply_filters('add_to_metas_to_export',$metas);
	return $metas;

}

/* Site deco needs this */
function get_site_deco_by_section($section){
	global $rwm_prod_deco_domains;
	return 'http://'.$rwm_prod_deco_domains[$section];
}

function section_permalink_domain($url , $post ){

	$meta = get_post_meta($post->ID , 'section_permalink', true);
	if ( $meta ){
		$url = DOMAIN_CURRENT_SITE=='rw.loc.dev' ? 'http://'. $meta .'.' . DOMAIN_CURRENT_SITE : get_site_deco_by_section($meta) ;
	}

	return $url;

}
add_filter( 'section_permalink_domain' , 'section_permalink_domain' , 10 , 2);

// Need sync functions
require(get_template_directory()."/include/functions/sync-functions.php");
if ( !function_exists('media_handle_upload') ) {
  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
  require_once(ABSPATH . "wp-admin" . '/includes/media.php');
}




if ( file_exists($config_dl )) {
	$posts = lock_required_file( $config_dl );
	$i=0;
	
	if ( count($posts)){
		foreach ( $posts as $source=>$posts_id){
			if ( count( $posts_id)){
				if(is_numeric( $source )){
					echo "Switching to ".$source;
					switch_to_blog( $source );
					foreach ( $posts_id as $post_id){
						if ( $i < DL_PER_RUN ){
							//$lock_file = $sync_path.'/.'.$post_id.'.lock';
							//if( !file_exists($lock_file)){
								$post = get_post($post_id) ;
								if( $post && !wp_is_post_revision( $post ) ) {
		                            //touch($lock_file);
		                            // TODO : deco case.. need to fix meta 'section_permalink' if not set
		                            copy_post( $post_id , $post , get_sync_metas($source) );
		                            //unlink($lock_file);
		                        } else {
		                            echo "\nSkipped article $post_id, deleted or revision";
		                        }
		                        uncopy_post( $source , $post_id );
								
							/*} else {
								echo "\nLocked post.. $post_id";
							}*/
							
						}else{
							echo "\nDone..".DL_PER_RUN. ' Syncs ' .  date_i18n('Y-m-d H:i:s' );
							break;
						}
						$i++;
					}

				} elseif (isset($external_import[$source])){
					$domain = 'http://'. $external_import[$source]['domain'] ;
					$url = $domain .'?feed_posts_json=' . implode(',', $posts_id) ;
					echo "Get posts from  ". $url  ."\n";

					$response = wp_remote_get($url);
					if( is_array($response) ) {
						$json = $response['body'];
						$posts_json = json_decode($json);
						if(count($posts_json)){
							foreach ($posts_json as $post){ 
								if($post->id  && in_array($post->id, $posts_id)){
									$post_id = $post->id  ;
									$index_p = array_search($post_id, $posts_id);
									unset($posts_id[$index_p]);
									if ( $i < DL_PER_RUN ){
										/*$lock_file = $sync_path.'/.sync-'.$source.'-'.$post_id.'.lock';
										if( !file_exists($lock_file)){*/

				                           // touch($lock_file);
				                            $return = do_sync_extern_post($post, $source) ;

											if ( is_array($return)){
												if ($return[0]==ALREADY_IMPORTED){
													echo " ( $post_id ALREADY_IMPORTED : ". $return[1].") " .  date_i18n('Y-m-d H:i:s' );
												} else {
													echo " ( $post_id création as : ". $return[1]. ") " .  date_i18n('Y-m-d H:i:s' ) ;	
												}
											} else {
												echo "$source : $post_id - Non importé " .  date_i18n('Y-m-d H:i:s' );
											}
											 ob_flush();
											 flush();

				                           // unlink($lock_file);
					                        uncopy_post( $source , $post_id );
											
										/*} else {
											echo "\nLocked post.. $lock_file";
										}*/
										
									}else{
										echo "\nDone..".DL_PER_RUN. ' Syncs ' .  date_i18n('Y-m-d H:i:s' );
										break;
									}
									$i++;
									
								}
							}
							foreach ($posts_id as $post_id) {
	                            echo "\nSkipped article $post_id, deleted or revision " .  date_i18n('Y-m-d H:i:s' );
				                uncopy_post($source, $post_id);
							}

						}
					
					}
				}


			} else {
				echo "\nNo posts to sync for source :" . $source .  date_i18n('Y-m-d H:i:s' );
			}

		} 
	
	} else {
		echo "\nNo posts to sync " .  date_i18n('Y-m-d H:i:s' );
	}
	
} else {
	echo "Config files not present $config_dl " .  date_i18n('Y-m-d H:i:s' ) . " \n";
}

unlink( LOCK_FILE );


