<?php

if(!defined('STYLESHEET_DIR'))
	define( 'STYLESHEET_DIR', get_stylesheet_directory() );
if(!defined('STYLESHEET_DIR_URI'))
	define( 'STYLESHEET_DIR_URI', get_stylesheet_directory_uri() );

if(!defined('RW_THEME_DIR'))
    define( 'RW_THEME_DIR', get_template_directory());
if(!defined('RW_THEME_DIR_URI'))
    define( 'RW_THEME_DIR_URI', get_template_directory_uri());

define('TIMEOUT_CACHE_DIAPO', 60*60*4 );
define('TIMEOUT_CACHE_MENU_ITEMS', 60*60*24 );
define('TIMEOUT_CACHE_MENU_NAV', 60*15 );
define('TIMEOUT_CACHE_LAST_POSTS', 60*60*4 );
define('TIMEOUT_CACHE_MENU_FOOTER', 60*60*24*7 );
define('TIMEOUT_CACHE_POPULAR_EXPOSANTS', get_param_global('cache_popuplar_exposant', 60*60*24));
define('TIMEOUT_CACHE_MOST_POPULAR', get_param_global('cache_most_popular', 60*60));
define('REWORLDMEDIA_TERMS' , 'reworldmedia');
if(!defined('CUSTOM_POPULAR_VIDEO_DURATION')) define('CUSTOM_POPULAR_VIDEO_DURATION', 60*60*24);
if( !defined( 'REWORLDMEDIA_TERMS' ) ) define( 'REWORLDMEDIA_TERMS' , 'reworldmedia' );
if ( !defined('REG_VIDEO'))
    define('REG_VIDEO' ,"/\[fpvideo.*mediaid\s*=\s*\"?\'?([^\"]+)\"?\'?(.*)]/im" );

$pattern = get_shortcode_regex( array('gallery') );
// define('HAS_GALLERY_REGEXP', "/$pattern/" );

include (TEMPLATEPATH.'/include/functions/cdn.php');
/*locking*/
require(RW_THEME_DIR .'/include/functions/locking.php');
require(RW_THEME_DIR."/include/functions/salons_options.php");
require(RW_THEME_DIR .'/include/functions/custom-functions.php');
require(RW_THEME_DIR .'/include/functions/shortcodes.php');
require(STYLESHEET_DIR."/partners.php");
require(RW_THEME_DIR."/include/functions/partners-core.php");
require(RW_THEME_DIR."/include/functions/hooks.php");
require(RW_THEME_DIR."/include/functions/media-gallery.php");
/**
 * include Ninja Forms v3 Exposant
 */
include(RW_THEME_DIR.'/include/functions/ninja-forms-v3-exposant.php');
/**
 * include newsletters.php
 */
include(RW_THEME_DIR. '/include/functions/newsletters.php');
include(RW_THEME_DIR. '/include/functions/newsletters-dedie.php');

if(is_admin()){
    require(RW_THEME_DIR .'/include/functions/meta-boxes.php');
    require(RW_THEME_DIR .'/include/functions/partners_options.php');
    require(RW_THEME_DIR .'/include/functions/devs_options.php');
    require(RW_THEME_DIR .'/include/functions/omeps.php');
}
require(RW_THEME_DIR .'/include/functions/tags-options.php');
require(RW_THEME_DIR .'/include/functions/sas-target.php');
require_once(RW_THEME_DIR."/include/functions/sas_target.php");

require(RW_THEME_DIR .'/include/functions/bandeau-partenaires.php');
if(file_exists(RW_THEME_DIR.'/include/functions/cheetah.php')){
	require_once RW_THEME_DIR.'/include/functions/cheetah.php';
}
if(get_param_global('has_products')){
    require(RW_THEME_DIR."/include/functions/produit.php");
}

require_once( RW_THEME_DIR . '/class-autoload-wp.php' );
new Autoload_WP(RW_THEME_DIR . '/class/');

define('DEFAULT_CACHE_VERSION_CDN',1);

function get_cache_version_cdn(){
    $cache_version_cdn = get_option('cache_version_cdn', DEFAULT_CACHE_VERSION_CDN) ;
    return ($cache_version_cdn > DEFAULT_CACHE_VERSION_CDN) ? $cache_version_cdn : DEFAULT_CACHE_VERSION_CDN ;
}

if(isset($_GET['newcdn'])){
    add_action('wp', function(){
        $cdn =  get_cache_version_cdn() ;
        $cdn ++ ;
        update_option('cache_version_cdn', $cdn) ;

    });
}

define('CACHE_VERSION_CDN' , get_cache_version_cdn());

/*Disable gutenberg editor wp */
add_filter('use_block_editor_for_post', '__return_false');


if(!function_exists('is_wp_home')):
function is_wp_home(){
	global $wp_query ;
	return is_home() && empty($wp_query->query['post_type']) ;
}
endif;
/**
 * [get_id_pub_video obtenir l'id de pub video a utiliser dans le shortcode fpvideo ]
 * @param  [Array] $atts [les attributs du shortcode fpvideo]
 * @return [Array] [liste des valeurs pub video]
 */
function get_id_pub_video($atts){
	global $is_sidebar;
	$id_pub = false;
	if(!(isset($atts['is_pub'])&& $atts['is_pub'] == 'no')){
		$id_pub_sidebar = get_param_global('id_pub_sidebar');
		if( $is_sidebar && !empty( $id_pub_sidebar ) ){
			$id_pub = apply_filters('id_pub_filter', $id_pub_sidebar);
		}else{
			$id_pub = apply_filters('id_pub_filter', get_param_global('id_pub'));
		}
	}


	// EDIT TAG preroll ,midroll & postroll
	if(isset($atts['preroll']) OR isset($atts['midroll']) OR isset($atts['postroll'])){
		$id_pub = array(
			'prerollZoneId'=> isset($atts['preroll']) ? $atts['preroll'] : -1, 
			'midrollZoneId'=> isset($atts['midroll']) ? $atts['midroll'] : -1, 
			'postrollZoneId'=> isset($atts['postroll']) ? $atts['postroll'] : -1, 
			'midrollInterval'=> -1,
			'midrollDelay'=> -1
		);
	}

	// Vast url pub
	if(isset($atts['type_pub']) && $atts['type_pub'] == 'vast'){
		$id_pub = array(	
			'type'=> 'vast', 
			'url' => $atts['vast_url'],
		);
	}
	return $id_pub;
} 

function get_taxonomy_option($section_id, $key, $cat_id=0) {
    return RW_Taxonomy::get_taxonomy_option($section_id, $key, $cat_id);
}

add_action('wp_enqueue_scripts', 'RW_Hooks::add_scripts_css_js', 1);

function reworldmedia_setup() { 
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'reworldmedia_setup');

function init_js_ext(){
    define('JS_EXT', '.js');
}
add_action('init', 'init_js_ext');

function add_new_image_sizes() {
    add_image_size('thumbnail_60_x_60', 60, 60, true);

}
add_action('init', 'add_new_image_sizes');

function extract_shortcode_params($content){
    if(preg_match ("/\[instagram.*url\s*=\s*\"?\'?([^\"]+)\"?\'?(.*)]/im" ,$content, $matches)){
        return $matches;
    }
    return '';
}

function player_shortcode_active($atts){
    if( !rw_is_mobile() && isset($atts['mobile_only']) && $atts['mobile_only'] == 'yes') {
        return false;
    }
    return true;
}

function fix_slashes_posts_once( ){
    global $fixed_posts_once;
    if ( !$fixed_posts_once ){
        $_POST = array_map('stripslashes_deep', $_POST);
        $fixed_posts_once=true;
    }
}
if (!get_param_global('disable_top_popular')){
	require(RW_THEME_DIR."/include/functions/cron.php");
}


function mini_text_for_lines($text, $max , $nLigne=1, $fin= "...") {
	return RW_Utils::mini_text_for_lines($text, $max , $nLigne, $fin);
}

function mini_title_for_lines($max , $nLigne, $fin= "...") { 
	return RW_Utils::mini_title_for_lines($max , $nLigne, $fin);
}


function get_post_category_from_url($post=null, $first_level=false) {
	return RW_Category::get_post_category_from_url($post, $first_level);
}
 /* Permalink for not published post */
function get_not_publish_permalink( $post_id ) {
	return RW_Post::get_not_publish_permalink( $post_id );
}

function get_cat_slug($cat_id) {
	return RW_Category::get_cat_slug($cat_id);
}

function gen_link_cat($cat_id, $class='', $parent_id = 0, $href_js=null, $attr=array()) {
	return RW_Category::gen_link_cat($cat_id, $class, $parent_id , $href_js, $attr);
}

function gen_most_popular_link_cat($cat, $class='', $parent_id = 0, $attr =array()) {
	return RW_Category::gen_most_popular_link_cat($cat, $class, $parent_id, $attr);
}

if ( !function_exists('get_menu_cat_link')):
function get_menu_cat_link($post, $class='', $first_level=false, $parent_id=false, $href_js=false, $simple_cat=false, $attr=array(),$exclude_cat='') {
	return RW_Category::get_menu_cat_link($post, $class, $first_level, $parent_id, $href_js, $simple_cat, $attr, $exclude_cat);
}
endif;


function get_menu_cat_post($p='', $class='', $first_level=false, $parent_id=false, $href_js=false, $simple_cat=false, $attr=array(),$exclude_cat='') {
	return RW_Category::get_menu_cat_post($p, $class, $first_level, $parent_id, $href_js, $simple_cat, $attr, $exclude_cat);
}

function get_most_popular_cat_link($post, $class='', $attr =array()) {
	return RW_Category::get_most_popular_cat_link($post, $class, $attr);
}

function init_video_js(){
	RW_Player::init_video_js();
}

function init_jwplayer(){
	RW_Player::init_jwplayer();
}



function init_jwplayer7(){
	RW_Player::init_jwplayer7();
}

function init_videojs_vast(){
	RW_Player::init_videojs_vast();

}


function get_info_viedo($video_id, $provider, $from_mobile = false, $force = false){
	return RW_Utils::get_info_viedo($video_id, $provider, $from_mobile, $force);
}


function get_youtube_img_by_id ($videoid) {
    return RW_Utils::get_youtube_img_by_id($videoid);
}

if ( !function_exists('get_video_img')) :
    function get_video_img($video_id, $provider, $force = false){
        return RW_Utils::get_video_img($video_id, $provider, $force);
}
endif;

if ( !function_exists('upload_img')) :
function upload_img($src_img, $title='', $description='', $post_id='', $local_file=false, $args = []){
    return RW_Utils::upload_img($src_img, $title, $description, $post_id, $local_file, $args);
}
endif;

function get_category_parent($id_cat=null,$is_slug=false) {
	return RW_Category::get_category_parent($id_cat, $is_slug);
}

function get_permalinked_category($post_id, $first=false) {
	return RW_Category::get_permalinked_category($post_id,$first);
}

function category_has_parent( $category_id ){
	return RW_Category::category_has_parent( $category_id );
}	

function verify_is_active_cat ($cat_id, $prefix_='all_cat_', $exclude=array()) {
	$is_active = true ;
	$parents_cat = get_category_parents( $cat_id, false, ',',true );
	$parents_cat = explode(',',$parents_cat);
	
	foreach ($parents_cat as $parents_cat_) {
		if( $parents_cat_!='' && in_array($prefix_.$parents_cat_ , $exclude) ) {
			$is_active = false;
			break;
		}
	}

	return $is_active;
}

function page_has_video($p = null){
	return RW_Post::page_has_video($p);
}

function check_if_player_active($atts, $exclude,  $is_active){
	global $wp_query, $post;
	if(is_single() && page_has_video() && !get_param_global('is_elastiques')){
		$is_active = false ;
	}elseif(!empty($exclude)){
		if(is_category()){
			if( in_array('cat', $exclude) OR in_array('cat_' . $wp_query->query_vars['category_name'] , $exclude) ) {
				$is_active = false ;
			} else if(strpos($atts['exclude'], 'all_cat_') !== false ) {
				if( !verify_is_active_cat (get_query_var('cat'), 'all_cat_', $exclude) ) {
					$is_active = false ;
				}
			}
		}elseif(is_home() && in_array('home', $exclude) ){
			$is_active = false ;
		}elseif(is_single()){
			if(in_array('single', $exclude)) {
				$is_active = false ;
			} else if(strpos($atts['exclude'], 'all_cat_') !== false ) { 
				$cat_post = get_menu_cat_post($post);
				if(!empty($cat_post)){
					if( !verify_is_active_cat ($cat_post->term_id, 'all_cat_', $exclude) ){
						$is_active = false ;
					} 
				}
			}
		}
	}
	return $is_active;
}


// require(RW_THEME_DIR."/include/functions/rw-player-videos.php");

/*
Merci de mettre le code à venir au dessus de cette action
*/
do_action('init_core');
