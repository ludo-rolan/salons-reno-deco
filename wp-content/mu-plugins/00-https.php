<?php
defined( 'SITE_SCHEME') or define ( 'SITE_SCHEME' , (isset($_SERVER['IS_HTTPS_CF']) && $_SERVER['IS_HTTPS_CF']) ? 'https':'http' );


$site_config_js['SITE_SCHEME'] = SITE_SCHEME;



if( SITE_SCHEME == 'https'){
	function change_home_https($url){
		$url = str_replace('http://', 'https://', $url);
		return $url;
	}
	add_filter( 'home_url', "change_home_https", 190);
	add_filter( 'wp_get_attachment_url', 'change_home_https', 190);
	add_filter( 'site_url', "change_home_https" ,100);
	add_filter( 'plugins_url', "change_home_https" ,100);
	add_filter( 'template_directory_uri', "change_home_https" ,100);
	add_filter( 'stylesheet_directory_uri', "change_home_https" ,100);
	add_filter( 'param_global_favicon', "change_home_https" ,100);
	add_filter( 'style_loader_src', 'change_home_https' , 190);
	
	add_action('plugins_loaded', function(){
		if(function_exists('w3tc_add_action')){
			w3tc_add_action('w3tc_object_cache_key', function($key){
			 	return $key . '_s' ;
			});
		}
	});
}

function get_request_origin_uri() {
	return isset($_SERVER['ORIG_REQUEST_URI']) ? $_SERVER['ORIG_REQUEST_URI'] : $_SERVER['REQUEST_URI'];
}

