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

function get_the_footer_menu_list($atts){
	$menu_id=isset($atts['name']) ? $atts['name'] : apply_filters('get_menu_name', 'pages_footer', 'pages_footer');
	
	/*$key_cache = $menu_id;
	if(TIMEOUT_CACHE_MENU_FOOTER > 0) {
     	if ( $cache = wp_cache_get( $key_cache, 'menu_footer' ) ){
     		return $cache;
     	}
    }*/

	$menu_items = apply_filters('menu_items_footer',wp_get_nav_menu_items($menu_id), $menu_id);
	$classSelector = isset($atts['selector']) ? $atts['selector'] :'';
	$target = '';
	$html_ul = '<div  id="menu-footer_bottom" class="'.$classSelector.' row ">' ;
	if(!empty($menu_items)){
		$count = 1;
		foreach ($menu_items as $menu_item){
			if($menu_item->menu_item_parent == 0){
				$menu_item_url = apply_filters('set_menu_item_url',  $menu_item->url);
				$target = !empty($menu_item->target) ? 'target="'. $menu_item->target .'"' : '';
				$html_ul .= '<a class="col-xs-12 col-md-2" href="'.$menu_item_url.'" '.$target.'>'.$menu_item->title.'</a>';
				if($count === 2){
					$html_ul .= '<div class="col-xs-12 col-md-4"></div>';
				}
			}	
			$count++;	
		}			
	}
	$html_ul .='  </div>';

	if(TIMEOUT_CACHE_MENU_FOOTER > 0) {
		wp_cache_set($key_cache, $html_ul , 'menu_footer' , TIMEOUT_CACHE_MENU_FOOTER );
    }

	return $html_ul ;		
				
}

add_shortcode('footermenulist','get_the_footer_menu_list');

function side_nl_shortcode() {
	ob_start();
    include(locate_template('include/templates/sidebar_newsletter.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
add_shortcode('sidebar_nl','side_nl_shortcode');

function footer_nl_shortcode() {
	ob_start();
    include(locate_template('include/templates/footer_newsletter.php'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
add_shortcode('footernl','footer_nl_shortcode');

function sidebar_podcasts_videos($attrs)
{
	$is_video_podcast = true; 
	$n=5;
	$args = array(
		'post_type' => 'post',
		'posts_per_page' => 3,
		'orderby' => 'date',
		'order' => 'DESC',
		'post_status' => 'publish',
		'category_name' => 'videos,podcasts'
	);
	$most_popular = get_posts($args);
	ob_start();
	include(locate_template('include/templates/post_most_popular.php'));
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}
add_shortcode('sidebar_podcasts_videos', 'sidebar_podcasts_videos');