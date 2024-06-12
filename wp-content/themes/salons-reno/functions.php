<?php

if (!defined('STYLESHEET_DIR_URI'))
	define('STYLESHEET_DIR_URI', get_stylesheet_directory_uri());
if (!defined('STYLESHEET_DIR'))
	define('STYLESHEET_DIR', get_stylesheet_directory());

define('NEWRW', true);


/**
 * include site config file
 */
require(locate_template('include/functions/site-config.php'));
require(locate_template('include/functions/texte_bo_options.php'));
require(locate_template('include/functions/Promo_Option.php'));
require(STYLESHEET_DIR.'/include/functions/gaiacrm.php');
require(STYLESHEET_DIR.'/include/functions/case_cocher_slider.php');


/**
 * include header-visuel.php
 */
add_action('after_nav', function () {
	global $post;
	$is_exposant = is_singular('exposant');
	$is_home = is_home();
	$page_name =  get_query_var('page_name');
	if (($is_home && empty($page_name)) || $is_exposant) {
		if ($is_home) {
			$cta_link = get_site_url() . '/exposition-virtuelle';
		} else if ($is_exposant) {
			$custom = get_post_custom($post->ID);
			if (isset($custom['logo_exposant'][0])) {
				$visual = wp_get_attachment_image_src($custom['logo_exposant'][0], 'rw_thumb_exposant')[0];
			}
			$title = (!empty($custom['num_stand'][0])) ? 'N° de stand ' . $custom['num_stand'][0] : 'N° de stand à venir';
			$cta_link = (isset($custom['link_exposant'][0])) ? $custom['link_exposant'][0] : '#';
			$cat = RW_Category::get_permalinked_category(get_the_ID(), true);
			$catName = $cat->cat_name;
			$subtitle = '<div class="exposant_name">' . $post->post_title . '</div> <div class="exposant_cat">' . $catName . '</div>';
			$cta_link = '#';
		}
		include(locate_template('include/templates/header-visuel.php'));
	}
}, 1, 1);

function custom_ninja_forms_after_submission( $form_data )
{
    foreach ($form_data["fields"] as $key => $value) {
        if($value["type"]=="email"){
            GAIACRM::getInstance()->synchronyzeDbToGaia($value["value"]);
        }
    }
    return $form_data;
}

add_action('ninja_forms_after_submission', 'custom_ninja_forms_after_submission');
add_action('wp_footer', 'newsletter_pop_up');
function newsletter_pop_up()
{
  require(STYLESHEET_DIR . "/include/templates/newsletter_pop_up.php");
}