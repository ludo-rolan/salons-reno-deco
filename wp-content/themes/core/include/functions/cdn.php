<?php
// SITE_SCHEME IS defined in mu-plugins/https.php
if ( !defined('SITE_SCHEME')){
	define( 'SITE_SCHEME' , 'https');
}

define("REG_DOMAIN_CONTENT_UPLOAD" ,  'http[s]?:\/\/[a-zA-Z0-9\.\-]+\/wp-content\/uploads\/');

if(!function_exists('get_cdn_attachment_url')){
	function get_cdn_attachment_url($url){
		$url = preg_replace('/'.REG_DOMAIN_CONTENT_UPLOAD.'/',  SITE_SCHEME . '://'.CDN_HOST.'/wp-content/uploads/', $url);
		return $url ;
	}
}

function cdn_update_attachment_url($url, $attachment_id=null) {
	if (!is_admin()) {
		return get_cdn_attachment_url($url);
	}
	return $url;
}


function cdn_update_content_images_url($content) {
	if (!is_admin()) {
		//$cdn_host = "cdn".CDN_HOST;
		$content = preg_replace('/src\=\"'.REG_DOMAIN_CONTENT_UPLOAD.'/', 'src="'. SITE_SCHEME .'://'.CDN_HOST.'/wp-content/uploads/', $content);
		return $content;
	}
	return $content;
}

function apply_cdn_to_uri($uri){
	if(defined('CDN_HOST') && defined('DOMAIN_CURRENT_SITE')){
		$uri = str_replace(DOMAIN_CURRENT_SITE, CDN_HOST, $uri);
	}
	return $uri;
}

// PRODUCTION ONLY
if(!is_dev()){
	add_filter("rw_attachment_url", "cdn_update_attachment_url", 10);
	add_filter("wp_get_attachment_url", "cdn_update_attachment_url", 10, 2);
	add_filter("wp_get_attachment_thumb_url", "cdn_update_attachment_url", 10, 2);
	add_filter('the_content', 'cdn_update_content_images_url', 1);
	add_filter('stylesheet_directory_uri', 'apply_cdn_to_uri');
	add_filter('template_directory_uri', 'apply_cdn_to_uri');
}


