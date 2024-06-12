<?php 

function add_nl_scripts() {
	$nl_css = 'main.css';
	wp_enqueue_style( 'nl_main_rw', RW_THEME_DIR_URI . '/include/newsletter/css/'.$nl_css, array(), CACHE_VERSION_CDN );
}

function shortcode_page_newsletter($atts){
	add_action('wp_footer', 'add_nl_scripts');
	wp_enqueue_script('nl_validate_rw', RW_THEME_DIR_URI .'/include/newsletter/js/validate.min.js', array(), CACHE_VERSION_CDN, true );
	wp_enqueue_script('nl_inputmask_rw', RW_THEME_DIR_URI .'/include/newsletter/js/inputmask.min.js', array(), CACHE_VERSION_CDN, true );
	wp_enqueue_script('nl_validate_rw', RW_THEME_DIR_URI .'/include/newsletter/js/jquery.form.min.js', array(), CACHE_VERSION_CDN, true );
	wp_enqueue_script('nl_main_js_rw', RW_THEME_DIR_URI .'/include/newsletter/js/newsletter_main.js', array('nl_validate_rw','nl_inputmask_rw'), CACHE_VERSION_CDN, true );
	$template = apply_filters('nl_page_template', '/include/newsletter/newsletter_html.php');
	ob_start();
	include( locate_template($template) );
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}
add_shortcode('page_newsletter', 'shortcode_page_newsletter');
