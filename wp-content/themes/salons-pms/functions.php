<?php 

if(!defined('STYLESHEET_DIR_URI'))
    define( 'STYLESHEET_DIR_URI', get_stylesheet_directory_uri());
if(!defined('STYLESHEET_DIR'))
    define( 'STYLESHEET_DIR', get_stylesheet_directory());

define('NEWRW', true);

/**
 * include site config file
 */
require(STYLESHEET_DIR.'/include/functions/site-config.php');
require(STYLESHEET_DIR.'/include/visual_header_options.php');
require(STYLESHEET_DIR.'/include/functions/feed-json.php');

/**
 * include hooks
 */
require(STYLESHEET_DIR.'/include/functions/hooks.php');
require(STYLESHEET_DIR.'/include/functions/shortcodes.php');

/**
 * include header-visuel.php
 */
add_action('after_nav',function() {
	global $post;
	$is_exposant = is_singular('exposant');
	$is_home = is_home() ;
	$page_name =  get_query_var('page_name');
	$is_actualite =  is_category("actualite");
    if( ($is_home && empty($page_name)) || $is_exposant || $is_actualite ){
    	if( $is_home || $is_actualite){
			// visual header Post ID
			$vh_post_id = intval(esc_attr( get_option('visual_header_post_id' , '0')));
			if($vh_post_id <= -1){
				$date = esc_attr( get_option('subtitle_visual_header_fr' , ''));
				$subtitle = esc_attr( get_option('title_visual_header_fr' , ''));
				if(isset($_GET["lang"]) && $_GET["lang"] == "en"){
					$date = esc_attr( get_option('subtitle_visual_header_en' , ''));
					$subtitle = esc_attr( get_option('title_visual_header_en' , ''));
				}
				$cta_link = esc_attr( get_option('url_visual_header_fr' , '#'));
				$vh_image = esc_attr( get_option('bg_visual_header_fr' , ''));
			}
			else{
				$vh_post = null;
				// get latest post 
				if ($vh_post_id == 0){				
					$actu_cat = get_category_by_slug("actualite");	
					$vh_post = wp_get_recent_posts(array(
						"numberposts"=> 1, 
						"category"=> $actu_cat->term_id
					));
					$vh_post = $vh_post[0];	
					$subtitle = $vh_post["post_title"];
					$vh_cat = get_the_category($vh_post["guid"]);

					if (!empty($vh_cat)) {
						$date = $vh_cat[0]->name;
					}
					else{
						$date = (isset($_GET["lang"]) && $_GET["lang"] == "en")?"News":"Actualité";
					}
					$cta_link = $vh_post["guid"];
					$thmbid =  get_post_thumbnail_id(intval($vh_post["guid"]));
					if(!empty($thmbid)){
						$thumbnail = wp_get_attachment_image_src($thmbid, 'full', false );
						$vh_image = $thumbnail[0]?:null;
					}
				} 
				else {
					$vh_post = get_post($vh_post_id);
					if ($vh_post) {
						$subtitle = $vh_post->post_title;
						$vh_cat = get_the_category($vh_post);
						if (!empty($vh_cat)) {
							$date = $vh_cat[0]->name;
						}
						else{
							$date = (isset($_GET["lang"]) && $_GET["lang"] == "en")?"News":"Actualité";
						}
						$thmbid =  get_post_thumbnail_id($vh_post_id);
						if(!empty($thmbid)){
							$thumbnail = wp_get_attachment_image_src($thmbid, 'full', false );
							$vh_image = $thumbnail[0]?:null;
						}	
						$cta_link = get_permalink($vh_post_id);
					}
				}
			}
    	}else if( $is_exposant ){
			$custom = get_post_custom($post->ID);
			if (isset($custom['logo_exposant'][0])){
				$visual = wp_get_attachment_image_src( $custom['logo_exposant'][0], 'rw_thumb_exposant' )[0];
			}
			$title = (!empty($custom['num_stand'][0])) ? 'N° de stand ' . $custom['num_stand'][0] : 'N° de stand à venir';
			$cta_link = (isset($custom['link_exposant'][0])) ? $custom['link_exposant'][0] : '#';
			$cat = RW_Category::get_permalinked_category(get_the_ID(), true);
			$catName = $cat->cat_name;
			$subtitle = '<div class="exposant_name">'. $post->post_title.'</div> <div class="exposant_cat">'. $catName .'</div>';
			$cta_link = '#';
    	}
        include( locate_template( 'include/templates/header-visuel.php' ) );
    }
}, 1, 1);
