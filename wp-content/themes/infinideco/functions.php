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

/**
 * include hooks
 */
require(STYLESHEET_DIR.'/include/functions/gaiacrm.php');

require(STYLESHEET_DIR.'/include/functions/hooks.php');

require(STYLESHEET_DIR.'/include/options/options.php');
/**
 * include header-visuel.php
 */
add_action('after_nav',function() {
	global $post;
	$is_exposant = is_singular('exposant');
	$is_home = is_home();
	$page_name =  get_query_var('page_name');
    if( ($is_home && empty($page_name)) || $is_exposant ){
    	if( $is_exposant ){
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
			include( locate_template( 'include/templates/header-visuel.php' ) );
    	}
		if( $is_home ){
			include( locate_template( 'include/templates/header-visuel-hp.php' ) );
		}
    }
}, 1, 1);
