<?php

function shortcode_social_links($atts=false) {
	$in_header=isset($atts['in_header'])? true:false;
	$in_footer=(isset($atts['footer']) && $atts['footer']) ? true:false;
	//print_r($in_footer);
	global $site_config;
	$shares_ul='';
	if($in_header==true || $in_footer){
		if($in_footer){
			$class_social="list-inline navbar-sociallink";
		}else{
			$class_social="nav navbar-sociallink";
		}
		$shares_ul.='<hr>';
		$shares_ul.='<ul class="'.$class_social.'">';

		/*utiliser un ordre personnalise si il est defini dans site-config*/
		if(isset($site_config['social_links_order']) && is_dev('rs_mt_mjmm_157288259')){
			$list_shares = $site_config['social_links_order'];
		}else{
			$list_shares = array(array('facebook_url','fab fa-facebook-f'),array('twitter_url','fab fa-twitter'),array('instagram_url','fab fa-instagram'),array('linkedin','fab fa-linkedin-in'),array('youtube_link','fab fa-youtube'));	    }
	    /*---*/
		foreach ($list_shares as $list_share) {
			if(isset($site_config[$list_share[0]])) {
				$link_share_page=($site_config[$list_share[0]]=="") ? "" : $site_config[$list_share[0]];
				$_BLANK=($site_config[$list_share[0]]=="") ? "" : ' target="_BLANK"';
				$hidden_sm="";
				if((get_param_global('youtube_hidden')=='sm' && $list_share[1]=='youtube') || (get_param_global('dailymotion_hidden')=='sm' && $list_share[1]=='daily') ) {
					$hidden_sm=" hidden-xs hidden-sm";
				}
				if($link_share_page){
					$shares_ul.='<li>
						<a '. (($list_share[1] == 'g')? 'rel="publisher"':'') .' href="' . $link_share_page . '" class="'.$list_share[0].' '.$hidden_sm.'"'.$_BLANK.'>'.'<i class="'.$list_share[1].'" style="color: black">'.'</i>'.'</a>
					</li>';
				}
			}
		}
		$shares_ul.='</ul>';
		$shares_ul.='<hr>';	
	}

	$shares_ul = apply_filters('social_links_html', $shares_ul, $in_footer);
	return $shares_ul;
}
add_shortcode("social_links", "shortcode_social_links");


if(!function_exists('get_the_menu_footer')) :
function get_the_menu_footer($atts){
	$menu_id=isset($atts['name']) ? $atts['name'] : apply_filters('get_menu_name', 'pages_footer', 'pages_footer');
	
	$key_cache = $menu_id;
	if(TIMEOUT_CACHE_MENU_FOOTER > 0) {
     	if ( $cache = wp_cache_get( $key_cache, 'menu_footer' ) ){
     		return $cache;
     	}
    }

	$menu_items = apply_filters('menu_items_footer',wp_get_nav_menu_items($menu_id), $menu_id);
	$classSelector = isset($atts['selector']) ? $atts['selector'] :'';
	$target = '';
	$html_ul = '<ul id="footer-top-menu" class="menu-footer list-inline '.$classSelector.' ">' ;
	if(!empty($menu_items)){
		foreach ($menu_items as $menu_item){
			if($menu_item->menu_item_parent == 0){
				$menu_item_url = apply_filters('set_menu_item_url',  $menu_item->url);
				$target = !empty($menu_item->target) ? 'target="'. $menu_item->target .'"' : '';
				$html_ul .='<li class="'. (isset($menu_item->classes)? implode(" ", $menu_item->classes):"") .' ' .' menu-item menu-item-type-taxonomy menu-item-object-category menu-item-has-children menu-item-'.$menu_item->ID.'">
						<a href="'.$menu_item_url.'" '.$target.'>'.$menu_item->title.'</a>
					</li>' ;
			}		
		}			
	}
	$html_ul .='  </ul>';

	if(TIMEOUT_CACHE_MENU_FOOTER > 0) {
		wp_cache_set($key_cache, $html_ul , 'menu_footer' , TIMEOUT_CACHE_MENU_FOOTER );
    }

	return $html_ul ;				
}
endif;
add_shortcode('menufooter','get_the_menu_footer');

add_shortcode("simple_addthis_single", "simple_sharer");

$share_instance=1;
function simple_sharer($attr, $url_item=null,$title=null,$description=null, $spec_social_array=array()){
	global $share_instance, $post;
	$url_item = isset($attr['url_item']) ? $attr['url_item'] : get_permalink($post->ID);
	$url_thumb = isset($attr['url_thumb']) ? $attr['url_thumb'] : wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	$url_thumb = apply_filters('url_thumb_format_filter', $url_thumb);
	$title = isset($attr['title']) ? $attr['title'] : $post->post_title;
	$description = isset($attr['description']) ? $attr['description']  : $post->post_excerpt;
	$description = apply_filters("share_description_filter", $description, $post);
	if(count($spec_social_array)==0) {
	 	$spec_social_array = array(
			"twitter"=>apply_filters ('get_twitter_details', twitter_details($post), $url_item)
		);
	}
	$show_sharecount = get_param_global('show_total_shares' , false ) ;
	if( $show_sharecount && is_single()){
		$sharedcount = get_single_sharedcount($post->ID) ;
	}
	$position = isset($attr['position'])?  $attr['position'] : null ;
	// Params Twitter
	if( $show_sharecount && isset($sharedcount) ) {
 		$sharedcount = apply_filters('share_total' , $sharedcount ) ; 
	} else {
 		$sharedcount= array();
 	}
	$total_shares = apply_filters('simple_sharer_html', '', $sharedcount, $position, $spec_social_array, $attr);
	if($total_shares == ''){
 		return '';
 	}
 	$class= (isset($position) && $position=="v") ? ' vertical ' : '' ; 
 	$id = 'share_'.$share_instance ; 
 	$share_instance++;
	$html = "<div id='$id'  class='share-buttons$class'  >".$total_shares.'</div>';
	$html .= "<script type='text/javascript'> 
		jQuery(document).ready( function(){ 
			show_default_sharer( '#$id' ,
			 ". json_encode($url_item). ','.json_encode($title).','. json_encode($description) .','.json_encode($url_thumb) . ') ; 
		});</script>';
	return $html;

}

function simple_sharer_html($val, $sharedcount, $pos, $spec_social_arra=array(), $attr = array()) {
	/* Paramètre pour desactiver les boutons de reseau sociaux : no_social_share */
	if( !empty( $_GET['no_social_share'] ) ){
		return '';
	}
	if(is_single()) {
		if($pos == 'v') {
			$deactivate_vertical_sharer = apply_filters('deactivate_vertical_sharer' , false);
			if($deactivate_vertical_sharer)
				return '';
			$html='<div class="social blockShare_vertical">' ;
					$html .='<div class="addthis_toolbox " >';
						$html .='<a class="social-network fb addthis_button_facebook" data-network="facebook"></a>';
						$html .='<a class="social-network addthis_button_tweet  tw " data-network="twitter" data-text ="'.$spec_social_arra['twitter']['title'] .'"  data-url="' . $spec_social_arra['twitter']['url'] . '" data-hashtags="' . $spec_social_arra['twitter']['hashtags'] . '" data-via="' . $spec_social_arra['twitter']['via'] . '"></a>';
					$html .='</div>';
				$html .='</div>';
				$html = apply_filters('new_addthis_filter', $html, $attr);
		}
		else {
			$total_shares = apply_filters('share_total' , $sharedcount ) ; 

			$html = '';
			$show_total_shares = $total_shares && count($sharedcount) > 0;
			if(!get_param_global("move_total_shares_to_li") && $show_total_shares ){
				$html .=  $total_shares;
			}
			$html .= '<div class="social blockShare_horizontal ">';
			$html .= '	<ul>';

			$sharer_to_deactivated = apply_filters('sharer_to_deactivated' , array());
				if(!in_array('facebook', $sharer_to_deactivated))
			$html .= '		<li class="social-network rw_btn_share facebook" data-network="facebook"></li>';
				if(!in_array('twitter', $sharer_to_deactivated))
			$html .= '		<li class="social-network rw_btn_share twitter" data-network="twitter" data-text ="'.str_replace(array('"','#'),'',($spec_social_arra['twitter']['title'])) .'"  data-url="' . $spec_social_arra['twitter']['url'] . '" data-hashtags="' . $spec_social_arra['twitter']['hashtags'] . '" data-via="' . $spec_social_arra['twitter']['via'] . '"></li>';
				if(!in_array('pinterest', $sharer_to_deactivated))
			$html .= '		<li class="social-network rw_btn_share pinterest" data-network="pinterest"></li>';
				if(!in_array('email', $sharer_to_deactivated))
 
			$html .= '	</ul>';
			$html .= '</div>';
			$html = apply_filters('new_addthis_filter', $html, $attr);
		}
	}
	return $html;
}
add_filter( 'simple_sharer_html' , 'simple_sharer_html', 10, 5);

function share_total_default($shares){
	if(is_array($shares)){
		$string = '';
		$count_ajax = false;
		$count_ajax = apply_filters('total_shares_ajax_filter', $count_ajax);
		if ( (is_array($shares) && count($shares)) || $count_ajax) {
			$total_shares = get_shared_total($shares);
			$total_shares = apply_filters('total_shares_format_filter', $total_shares);
			if($total_shares > 0 || $count_ajax) {
				$string = '<div class="total-shares';
				if($count_ajax){
					$string.=' total-shares-ajax';
				}
				$string .= '" data-index="0"><div class="num_share  pull-right">
						<em>' . $total_shares . '</em>
						<div class="caption">'. _n('partage','partages', $total_shares) . '</div>
						</div></div>';
			}
		}
		return $string;
	}
	return $shares ;
}

add_filter( 'share_total' , 'share_total_default');


function today_date_($attrs){
	$current_day = date_i18n("j");
	?>
	<div class="today_date">
		<?php 
		if(isset($attrs['display_week_day'])){
			echo date_i18n("l");
		}
		?>
		<strong><?php echo $current_day ;?></strong><?php echo date_i18n( get_param_global('date_format_header' , "F" )  );?>
	</div>
	<?php
}
add_shortcode('today_date','today_date_');

function add_logo($atts){
	return get_data_from_cache('footer_logo_img', 'footer', 60*60*24, function(){
		$img_logo = isset($atts['logo']) ? $atts['logo'] :  STYLESHEET_DIR_URI . '/assets/images-v3/logo-white.svg';
		$img_alt = isset($atts['alt']) ? $atts['alt'] : apply_filters('alt_logo_footer' , get_bloginfo('name'));
		$home_url = esc_url(apply_filters('logo_home_url', home_url('/')));
		$html = '<div class="col-xs-12 footer-widget">';;
		$html .= '<a class="footer-logo" href="'. $home_url .'">';
		$html .= '<img src="'. $img_logo .'" class="img-responsive" alt="'. $img_alt .'" />';
		$html .= '</a>';
		$html .= '</div>';
		return $html;
	});
}
add_shortcode('logo', 'add_logo');

function sub_domain_links($atts){
	return get_data_from_cache('sub_domain_links', 'footer', 60*60*24, function(){
		$menu_items = wp_get_nav_menu_items('pages_footer');	
		$html = '<ul class="footer-menu-pages">' ;
		if (is_array($menu_items)){
			foreach ($menu_items as $menu_item){
				if($menu_item->menu_item_parent == 0){
					$html .='<li class="'. (isset($menu_item->classes)? implode(" ", $menu_item->classes):"") .'">
							<a href="'.$menu_item->url.'">'.$menu_item->title.'</a>
						</li>' ;
				}		
			}					
		}
		$html .='</ul>';
		return $html;
	});
}
add_shortcode('sub_domain_links', 'sub_domain_links');

function widget_social_media($attrs){
	return get_data_from_cache('social_media_widget', 'shortcode', 3600, function(){
		$facebook_url = get_param_global('facebook_url');
		$instagram_url = get_param_global('instagram_url');
		$twitter_url = get_param_global('twitter_url');
		$linkedin_url = get_param_global('linkedin');
		$pinterest_url = get_param_global('pinterest_url');
		$html =  '<ul class="reseaux-sociaux list-unstyled list-inline">';
		if(!empty($facebook_url)){
			$html .= '<li>
						<a href="'. $facebook_url .'" target="_blank" class="reseaux-sociaux-btn facebook">
						<span>facebook</span>
						</a>
					</li>';
		}
		if(!empty($instagram_url)){
			$html .= '<li>
						<a href="'. $instagram_url .'" target="_blank" class="reseaux-sociaux-btn instagram">
							<span>instagram</span>
						</a>
					</li>';		
		}
		if(!empty($twitter_url)){
			$html .= '<li>
						<a href="'. $twitter_url .'" target="_blank" class="reseaux-sociaux-btn twitter">
							<span>twitter</span>
						</a>
					</li>';		
		}
		if(!empty($linkedin_url)){
			$html .= '<li>
						<a href="'. $linkedin_url .'" target="_blank" class="reseaux-sociaux-btn linkedin">
							<span>linkedin</span>
						</a>
					</li>';		
		}
		if(!empty($pinterest_url)){
			$html .= '<li>
						<a href="'. $pinterest_url .'" target="_blank" class="reseaux-sociaux-btn pinterest">
							<span>pinterest</span>
						</a>
					</li>';		
		}
		$html .= '</ul>';

		return $html;
	});
}
add_shortcode( 'social_media_widget', 'widget_social_media' );

function footerdesc($atts, $content){
	if (isset($atts['src']) && !is_home()) {
		$content = '<div class="footerdesc"><img src="'.$atts['src'].'" alt="'. get_bloginfo('name') .'"/></div>';
	} else {
		$content = '<div class="footerdesc">' . $content .'</div>';
	}
	return $content ;
}
add_shortcode('footerdesc', 'footerdesc');

function newsletter($atts) {
	$id = $atts['ninja_forms_id'];
	$title = (!empty( $atts['title'] ) && $atts['title'] ) ? '<div class="title">'. $atts['title'] .'</div>' : '';
	$desc = !empty( $atts['desc'] ) ? '<div class="desc">'. $atts['desc'] .'</div>' : '';
	$r = "<div class=\"widget_newsletter\">\n";
	$r.= $title . $desc;
	// Ninja Forms v3
	if(shortcode_exists('ninja_form')) {
		$r.= do_shortcode("[ninja_form id=$id]") . "\n";
	}
	// Ninja Forms v2
	elseif(shortcode_exists('ninja_forms_display_form')) {
		$r.= do_shortcode("[ninja_forms_display_form id=$id]") . "\n";
	}
	$r.= "</div>";
	return $r;
}
add_shortcode('newsletter', 'newsletter');

function shortcode_post_most_popular($atts) {
	global $post;
	$filter_videos = isset($atts["filter_videos"]) ? true : false;
	$filter_gallery = isset($atts["filter_gallery"]) ? true : false;
	$carrousel = isset($atts["carrousel"]) ? $atts["carrousel"]=='yes' : false ;
	$filter_videos_list = isset($atts["filter_videos_list"]) ? true : false;
	$n_videos = isset($atts["n_videos"]) ? $atts["n_videos"] : 3;
	$filter_dossier = isset($atts["filter_dossier"]) ? true : false;
	$nbr_dossiers = isset($atts["nbr_dossiers"]) ? $atts["nbr_dossiers"] : 2;
	$cat = isset($atts["cat"]) ? trim($atts["cat"]) : "";
	$title = isset($atts["title"]) ? $atts["title"] : __( "les articles" , REWORLDMEDIA_TERMS );
	$last_title = isset($atts["last_title"]) ? $atts["last_title"] : __("POPULAIRES" , REWORLDMEDIA_TERMS ) ;
	$video_cat_name = isset($atts["video_cat_name"]) ? $atts["video_cat_name"] : "videos";
	$is_carousel = isset($atts["carousel"]) ? $atts["carousel"] : false;
	$filter_exposant = isset($atts["filter_exposant"]) ? true : false;
	$n = isset($atts["n"]) ? $atts["n"] : 4;
	if ( $filter_videos && ( preg_match (  REG_VIDEO , $post->post_content , $matches ) && $post!='' && (is_page() || is_single()) ) ) {
		$most_populars = do_shortcode('[post_most_popular_video n=5 last_title="lus" video_cat_name="'. $video_cat_name .'"]');
	}elseif ( $filter_gallery  && $post!='' && (is_page() || is_single())  && page_has_gallery() ) {
		$most_populars = do_shortcode('[post_most_popular_gallery n=5 last_title="lus"]');
	}else if($filter_videos_list && is_category(apply_filters('filter_cat_video','videos'))){
		$most_populars = do_shortcode('[popular_videos_list n_videos='.$n_videos.']');
	}else if ($filter_dossier && apply_filters('display_most_popular_dossier',false)){
		$most_populars = do_shortcode('[popular_dossiers nbr_dossiers='.$nbr_dossiers.']');
	}else if ($filter_exposant && apply_filters('display_most_popular_exposant',false)){
		$most_populars = do_shortcode('[popular_exposant number='.$n.']');
	}else {
		if($cat){
			$most_popular = get_option(apply_filters('most_popular_option',"most_popular_" . $cat ));
		}else{
			$most_popular = get_option(apply_filters('most_popular_option',"most_popular"), array());
		}
		if($cat && empty($most_popular)){
			$args_popular = array('category_name' => $cat, 'posts_per_page' => $n, 'meta_key' => 'sharedcount_total', 'orderby'  => 'meta_value', 'order' => 'DESC');	
			
		}
		else{
			$args_popular = array('include' =>$most_popular,'posts_per_page' => $n, 'orderby'  => 'post__in', 'order' => 'DESC');
		}
		$args_popular = apply_filters('args_popular',$args_popular,$n);
       	$active = get_param_global('popular_posts_locking', defined('_LOCKING_ON_'));
       	// cache the most popular posts based on shortcode atts
       	$key = 'post_most_popular_'.$cat.'_'.$active.md5(serialize($atts));
		$most_populars = get_data_from_cache($key, 'sidebare', TIMEOUT_CACHE_MOST_POPULAR, function() use ($n,$active, $cat, $most_popular, $args_popular, $carrousel, $filter_dossier, $is_carousel) {

			$most_popular=get_posts($args_popular);

			if ( $filter_dossier ) {
				usort( $most_popular, 'compareDate' );
			}
			ob_start();
			if($carrousel){
				include(locate_template ("/include/templates/carrousel_post_most_popular.php"));
			}else{
				include(locate_template ("/include/templates/post_most_popular.php"));
			}
			$most_populars = ob_get_contents();
			ob_end_clean(); 	
			return $most_populars;
       	}, true);
	}
	wp_reset_postdata();
	return $most_populars;
}
add_shortcode("post_most_popular", "shortcode_post_most_popular");

if(!function_exists('videojs_shortz')) :
function videojs_shortz($atts) { 
	global $is_live_content, $is_sidebar;

	if(isset($_GET['no_video']) && $_GET['no_video']){
		return false;
	}
	if(!player_shortcode_active($atts)){
		return false;
	}
	if( $is_sidebar && is_dev('evolution_param_exclude_152101483') ){
		$exclude = isset($atts['exclude'])? explode(",", $atts['exclude']):array();
		$is_active = check_if_player_active($atts, $exclude, true);
		if(!$is_active){
			return false;
		}
	}

	// Afficher le player dans une ou plusiers categories (séparer par des virgule)
	if(!RW_Category::show_exclusive_cats($atts)){
		return false;
	}
	
	do_action('fpvideo_init');
	$new_fpvideo = apply_filters( 'new_fpvideo', '', $atts );
	if( $new_fpvideo ) return $new_fpvideo;
	/**
	 * Done :
	 *  --- Mute : attr mutevideo
	 *  --- height
	 * 	--- autoplay
	 *  --- thumbnail
	 *  --- Analytics player
	 *
	 * mediabong : mb:...
	 *  --- dailymotion ( passage player dailymotion )
	 *  --- metas itempro
	 */

	/**
	 * TODO : 
	 * Attrs
	 *  --- is_pub :  ( possiblité de desactiver les pubs par default )
	 * 		- sticky
	 * 			- override preroll , midroll, postroll
	 * 		- liverails
	 *  --- Analytics pubs
	 *  --- Force : attr force
	 *  --- mediaid
	 *  --- last : récuprer les dernieres videos
	 * 
	 */

	global $site_config, $videos,$exclude_videos;

	if( isset($_GET['force_mp4']) && $_GET['force_mp4'] ){
		$atts['mediaid'] = "http://videos.ladmobile.fr/3/7/1/20371/hd-20371.mp4";
	}
	$template_dir_uri =  get_template_directory_uri() ;
	$params = array() ;
	//Mute for dailymotion
	$mutevideo = false;
	$mutevideo_iframe = '';
	if(isset($atts['mutevideo']) && $atts['mutevideo'] != 'no'){
		$mutevideo = true;
		$mutevideo_iframe = '&mute=1';
	}
	$other_videos=array(); 
	if(isset($atts['othersmediaid']) && !empty($atts['othersmediaid']) ){ 
		
		$urls=explode(',', $atts['othersmediaid']); 
		foreach ($urls as $key => $value) { 
			$other_videos[] = get_video_properties($value);
		}
	}else{ 
		$other_videos = 'false'; 
	}
	if(isset($atts['force'])){
		$force = true;
	}else {
		$force = false;
	}
	
	$right_video = false;

	if($is_sidebar){
		$right_video = true;

	}

	if(isset($atts['loop']) && strtolower($atts['loop']) == 'yes'){
		$loop = 'true';
	}else {
		$loop = 'false';
	}
	if(isset($atts['autoloop']) && strtolower($atts['autoloop']) == 'yes'){
		$autoloop = 'true';
	}else {
		$autoloop = 'false';
	}
	$force_play_type = 'false';
	if(isset($atts['playlist']) && strtolower($atts['playlist']) == 'yes'){
		$playlist = 'true';
		if (!empty($atts['forcetype'])) {
			$force_play_type = $atts['forcetype'];
		}
	}else {
		$playlist = 'false';
	}

	if( isset($atts['hide_play_icon']) ){
		$hide_play_icon = 'true';
	}else {
		$hide_play_icon = 'false';
	}

	$forcejw = isset($atts['forcejw']) && (strtolower($atts['forcejw']) == 'yes');
	$forcejw7 = isset($atts['forcejw7']) && (strtolower($atts['forcejw7']) == 'yes');
	$random = isset($atts['random']) ? $atts['random'] : false;
	if ($random) {
		$args = array(
			'orderby' => 'rand',
			'showposts' => 1 ,
		);

		if(is_dev('optimize_query_video_tr_1088')){
			$args['tag'] = 'has_video';
		}else{
			$args['s'] = '[fpvideo';
		}

		$post_rand = get_posts($args);
		if (!isset($post_rand[0])) {
			return '';
		}
		$media_id = get_source_video_id_by_content($post_rand[0]->post_content);
		$atts['mediaid'] = $media_id['id'];
	}
	$active_jw_player = (is_dev('passage_du_player_sur_jw6_111776366') || $forcejw) ;
	$active_jw7_player = false ;


	if(isset($videos[$atts['mediaid']]) && !$force){
		return '';
	}
	if(isset($atts['height'])){
		$val_height =$atts['height']."px";
	}else {
		$val_height ="360px";	
	}
	if (isset($atts['last']))	{
		$latestPost = get_posts('numberposts=1&s=[fpvideo');
		$video_item = get_video_item($latestPost[0]);
		$atts['mediaid'] = $video_item['video_id'];
	}
	if( isset($atts['mediaid']) && RW_Utils::starts_with($atts['mediaid'] ,'mb:') ) {
		return "";
	}

	$videos[$atts['mediaid']] = 1;
	$post_video = '';
	$provider = '';

	$id_pub = RW_Utils::get_id_pub_video($atts);


	/*
	* Ticket ID : #110878748 : Correction du conflit des player vidéos JS sur une meme page
	* Fixer le probleme des conflits de tags
	* By Abdoulaye
	*/
	if(is_dev('correction_conflit_player_videosjs_meme_page_110878748')){

		/*
		* Ne pas faire du autoplay dans la seconde video dans le content de l'article
		*/
		global $index_video_short;
		$index_video_short = isset($index_video_short) ? $index_video_short : 0;
		$index_video_short ++;

	}



	/*
	* End Ticket ID : #110878748
	*/

	/*
	* Ticket ID : #110878748 (2) : Correction du conflit des player vidéos JS sur une meme page
	* - Ne pas faire de autoplay pour les autres videos de larticle a partir de la deuxieme
	* - Faire le autoplay de la video du jour avec un param forceplay
	*/
	if(is_dev('correction_conflit_player_videosjs_meme_page_110878748')){
		if(isset($atts['forceplay']) AND $atts['forceplay'] == "yes"){
			$autoPlay = 'true'; 
		}else if((isset($atts['autoplay']) AND $atts['autoplay'] == "no") OR ($index_video_short > 1 AND is_singular())){
			$autoPlay = 'false'; 
		}else{
			$autoPlay = 'true' ;
		}
	}else{
		if(isset($atts['autoplay']) && $atts['autoplay'] == "no"){
			$autoPlay = 'false';
		}else{
			$autoPlay = 'true' ;
		}
	}


	if(is_dev('lenteur_chargement_pub_player_150412161')){
		$page_ready = 'jQuery(window).load(function(){' ;

	}else{
		$page_ready ='jQuery(document).ready(function(){' ;
	}

	if(isset($atts['brightcove_video_id'])){
		$video_id = $atts['brightcove_video_id'] ;
		if(isset($atts['height'])){
			$iframe_height = $atts['height'];
		} else {
			$iframe_height = '380';
		}
		if(!isset($atts['width'])){
			$iframe_width='100%';
		}else {
			$iframe_width = $atts['width'];
		}



		if(isset($_GET['brightcove_player'])){
			$force_player =  $_GET['brightcove_player'] ;
		}else{
			$force_player =  false ;
		}

		$iframe_autoplay =  ( $autoPlay == 'true' ) ;

		$id_block = 'brightcove_' . $video_id ;

		$param = array(
			'width' =>  $iframe_width ,
			'height' =>  $iframe_height ,
			'default_player' =>  get_param_global('brightcove_player_flash', 'default') ,
			'mobile_player' =>  get_param_global('brightcove_player_mobile') ,
			'html5_player' =>  get_param_global('brightcove_player_html5') ,
			'force_player' =>  $force_player ,
			'video_id' =>  $video_id ,
			'autoplay' =>  $iframe_autoplay,
			'mutevideo' => $mutevideo
		);

		$iframe = ' <div id="'. $id_block .'" ></div>

		<script type="text/javascript">
			

			'. $page_ready .'
				var param = ' . json_encode($param) . '

				show_video_brightcove( "'. $id_block .'", param);

				jQuery(document).trigger("ads_player_ended", ["'. $id_block .'"]);
			});

			</script>

		' ;	

			return $iframe ;

	}


	/*
	* End Ticket ID : #110878748 (2)
	*/
	$redirect_id = isset($atts['redirect']) ? $atts['redirect'] : '';
	$id_post_video = 0;
	if (intval($atts['mediaid']) > 0) {
		$post_array = get_post($atts['mediaid'], ARRAY_A);
		$post_video = $post_array['guid'];
	} else if ( isset($atts['mostviewed']) &&  $atts['mostviewed'] ) {
		$most_popular_video = get_option(apply_filters('most_popular_video_option',"most_popular_video"), array());
		if( isset($most_popular_video[0]) ) {
			$post_most_viewed = get_post($most_popular_video[0]);
			$id_post_video = $most_popular_video[0];
			$most_viewed_video_params = get_video_params($post_most_viewed->post_content);
			$post_video = $most_viewed_video_params['link'];
		}
	} else {

		$mediaids = explode('|',$atts['mediaid']);
		$post_video = $mediaids[rand(0, count($mediaids)-1)];
	}

	$url = $post_video ;

	$args = array('width'=>740);
	if(isset($atts['width'])){
		$args['width'] = $atts['width'];
	}
	$src_img = "";
	if (strpos($post_video, 'youtu') !== false || strpos($post_video, 'dai') !== false) {
		$provider = "dailymotion";
		$val_split = '/';
		if (strpos($post_video, 'youtu') !== false) {
			$val_split = (strpos($post_video, '/watch?v=') !== false) ? "/watch?v=" : "/";
			$provider = "youtube";
			preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $post_video, $matches);
			$video_id = $matches[0] ;
			$video_id = apply_filters('fp_video_id', $video_id, $provider);

			if($args['width']> 360){
				$src_img = SITE_SCHEME."://img.youtube.com/vi/$video_id/maxresdefault.jpg";
				$data_default_src = SITE_SCHEME."://img.youtube.com/vi/$video_id/0.jpg" ;
			}else{
				$src_img = SITE_SCHEME."://img.youtube.com/vi/$video_id/mqdefault.jpg";
			}
			$params["youtube_id"] = $video_id ;
		}
		if (strpos($post_video, 'dai') !== false){
			preg_match("#(?<=dai.ly/)[^&\n]+|(?<=video/)[^&\n]+#", $post_video, $matches);
			$video_id = $matches[0] ;
			$video_id = apply_filters('fp_video_id', $video_id, $provider);
			$src_img = SITE_SCHEME."://www.dailymotion.com/thumbnail/video/$video_id" ;
			$params["dailymotion_id"] = $video_id ;
			$api_video = explode($val_split, $post_video);
			$post_video = "api:" . $api_video[count($api_video) - 1];	
		}
	}
	if (isset($atts['src'])) {
		$src_img = $atts['src'];
	} elseif(!$src_img && is_single()){
		$thumb_post_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'large');		
		$src_img = $thumb_post_url[0];
	}
	$fpvideo='';
	$params = array_merge($params, $atts);
	if (isset($atts['id_block'])) {
		$id_block = $atts['id_block'];
	}else{
		$id_block = md5(serialize($params));
	}
	if(strpos($atts['mediaid'], 'adways.com') !== false){
		if(isset($atts['height'])){
			$height = $atts['height'];
		} else {
			$height = '380';
		}
		if(!isset($atts['width'])){
			$width='100%';
		}else {
			$width = $atts['width'];
		}
		$src =$atts['mediaid'] ;
		$fpvideo = "<div class=\"adways_block\">
		<div id=\"player_$id_block\"></div>
		<script>
				$page_ready 
				var params_$id_block = {
					$opt_player
					src : \"$src\",
					height: \"$height\",
					width: \"$width\",
					muted: \"$mutevideo\",
					autoplay: $autoPlay
				};
			show_adways_player('player_$id_block', params_$id_block);
		});
		</script>
		</div>";
		return $fpvideo ;
	}
	if($is_live_content){
		if(!empty($video_id)){
			$info_video_id = $video_id;
		}else{
			$info_video_id = $atts['mediaid'];
		}
		$info = RW_Utils::get_info_viedo($info_video_id, $provider);
		if($info){
			global $post;
			$fpvideo = '<div class="video-pub-post" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">';
			$fpvideo .= '<meta itemprop="name" content="'.  str_replace('"', '\"', $info['name'])  .'" />';
			if($info['duration']){
				$fpvideo .= '<meta itemprop="duration" content="'. $info['duration']  .'" />';
			}
			$fpvideo .= '<meta itemprop="contentUrl" content="'. $info['contentUrl'] .'" />';
			$fpvideo .= '<meta itemprop="playerType" content="Flash" />';
			$video_img = RW_Utils::get_video_img($info_video_id, $provider, true) ;
			$video_img = $video_img ? $video_img : get_the_post_thumbnail_url($post);
			if($video_img){
				$fpvideo .= '<meta itemprop="thumbnail" content="'. $video_img .'" />';
			}

			if(is_dev('seo_propriete_itemprop_manquant_101198852')){
				if($video_img){
					$fpvideo .= '<meta itemprop="thumbnailUrl" content="'. $video_img .'" />';
					$fpvideo .= '<meta itemprop="image" content="'. $video_img .'" />';
				}
				if(isset($info['uploadDate'])){
					$fpvideo .= '<meta itemprop="uploadDate" content="'. $info['uploadDate'].'" />';
				}
			}

		}
	}

	$opt_player='';
	if ($active_jw_player){
		
		init_jwplayer();

	}elseif($active_jw7_player){
		
		init_jwplayer7();

	}else{
		RW_Player::init_video_js();
		if( $provider == 'youtube' ||  $playlist == 'true'){
			$opt_player='techOrder: ["youtube","html5","flash"],
			playsinline:true,
			ytcontrols:1,';
		}	
	}


	if(strpos($url, 'dai') !== false){
		if(isset($atts['height'])){
			$iframe_height = $val_height;
		} else {
			$iframe_height = '380';
		}
		if(!isset($atts['width'])){
			$iframe_width='100%';
		}else {
			$iframe_width = $atts['width'];
		}
		if(isset($atts['diapo_full']) && $atts['diapo_full'] == "1" ){
			$iframe_autoplay = 0;
		} else {
			$iframe_autoplay = $autoPlay=='true'?1:0;
		}
		$fpvideo .= '<div class="dailymotion_block" data-video="'.$video_id.'" >';

		if(is_dev() && $syndication = get_param_global('dail_synd_key')){
			$syndication_param = $syndication;
			$wmode = "transparent";
			$syndication = '&syndication=' .  $syndication . '&wmode=transparent';
		}else{
			$syndication = '';
		}

		$val_height = apply_filters('dai_player_js_height', $val_height, array('atts'=>$atts));
		$iframe_width = apply_filters('dai_player_js_width', $iframe_width, array('height'=>$val_height, 'atts'=>$atts));
		$frame_id = "player_".$id_block;
		$fpvideo .= '<div id="'.$frame_id.'" > </div>';
		$vid_params = array("video" => $video_id,
														"width" => $iframe_width,
														"height" => $val_height,
														"autoplay" => $autoPlay,
														"syndication" => (isset($syndication_param) ? $syndication_param : '') ,
														"wmode" => (isset($wmode) ? $wmode : '') ,
														"mute" => $mutevideo,
														"id" => $frame_id,
														"playlist" => $playlist,
														"other_videos" => $other_videos,
														"force_play_type" => $force_play_type,
														"right_video" => $right_video,
													);

		$vid_params['hide_play_icon'] = $hide_play_icon;

		$video_dai_params = get_video_dai_params($vid_params);
		$fpvideoDaiTag =  "
		<script>
			var video_dai_params$id_block = $video_dai_params;	
			$page_ready 
				var id_pub = ". json_encode($id_pub) ." ;
				if(window.confing_tag_video_mobile){
					id_pub = confing_tag_video_mobile(id_pub)
				}
				show_dai_video(\"$frame_id\", video_dai_params$id_block, id_pub);
			});
		</script>
		";
		$fpvideo.=$fpvideoDaiTag;
		
		$fpvideo .= '</div>';
	}else{
		if(isset($id_pub['liverail'])){
			wp_enqueue_script('reworldmedia-videojs-liverail', $template_dir_uri . '/assets/videojs-plugins/videojs-liverail.js', array('reworldmedia-videojs-ads'), '', true);
		}elseif(isset($id_pub) && (isset($id_pub['prerollZoneId']) || isset($id_pub['url'])) ){
			if (!$active_jw_player && !$active_jw7_player){
				init_videojs_vast();
			}
		}
		if(!isset($atts['width'])){
			$val_width='100%';
		}else{
			$val_width = $atts['width'];
		}
		$val_height = apply_filters('show_videojs_height', $val_height, array('atts'=>$atts));
		$val_width = apply_filters('show_videojs_width', $val_width, array('height'=>$val_height, 'atts'=>$atts));
		if (!$active_jw_player  &&  !$active_jw7_player){
			$video_js_class = "video-js vjs-default-skin vjs-big-play-centered";
			if(isset($atts['ratio'])){
				$video_js_class .=   " vjs-" . $atts['ratio'];
			}
			if($autoPlay == 'false' and is_dev('click_to_play_videojs_153745207')){
				$fpvideo.= '
				<div id="player_img_' . $id_block . '" class="img_player vjs-youtube">
				<img   src="'. $src_img .'"  width="100%" height="auto"  />
				</div>
				';

				$fpvideo .= '<video width="100%" height="'. $val_height .'" controls id="player_' . $id_block . '" preload="auto" class="'. $video_js_class .'" style="display:none; text-align: center; margin-bottom: 4px;" ';
				if ($src_img != '') $fpvideo.= 'poster="'.$src_img.'"';

				$fpvideo.= '>';
				
			}else{

				$fpvideo .= '<video width="100%" height="'. $val_height .'" controls id="player_' . $id_block . '" preload="auto" class="'. $video_js_class .'" style="display:block; text-align: center; margin-bottom: 4px;" ';
				if ($src_img != '') $fpvideo.= 'poster="'.$src_img.'"';

				$fpvideo.= '>';
			}
			if (preg_match('/\.(mp4)$/', $post_video, $matches)) {
				$fpvideo.='<source src="'. $post_video .'" type="video/mp4" />';
			}
			$fpvideo.='</video>';	
		}else{
			$fpvideo .= '<div id="player_' . $id_block . '"  style="display:block;width:100%; text-align: center; margin-bottom: 4px;height:'.$val_height.';" class="jwplayer" ></div>';
		}

		$muted = ($mutevideo)?"true":"false";
		$params['other_videos'] = $other_videos;
		$params['force_play_type'] = $force_play_type;
		$params['right_video'] = $right_video;
		
		$id_pub_encoded=json_encode($id_pub);
		$params_encoded=json_encode($params); 
		if($active_jw_player){
			if(isset($atts['ratio'])){
				$auto_height = "aspectratio: \"{$atts['ratio']}\"";
			}else{
				$auto_height = "height: \"$val_height\"" ;
			}
			if(isset($atts['noflash']) && $atts['noflash'] == 'yes'){
				$noflash = 'noflash:true' ;
			}else{
				$noflash = 'noflash:false' ;
			}

			$jwplayer_key = get_param_global('jwplayer_key') ;
			$fpvideotag =  "
			<script>
			var setup_$id_block = {
				$opt_player
				src : \"$post_video\",
				$auto_height, 
				width: \"$val_width\",
				muted: $muted,
				language:\"fr\",
				autoplay: $autoPlay,
				src_img: \"$src_img\",
				repeat:$loop,
				$noflash ,
				autoloop:$autoloop,
				playlist:$playlist,
				force_play_type:$force_play_type,
			};
				$page_ready 
					jwplayer.key=\"$jwplayer_key\";
					show_jw_player(\"player_$id_block\",setup_$id_block,\"$template_dir_uri\",$id_pub_encoded,\"$provider\", $params_encoded);	
				});
			</script>";
		}elseif($active_jw7_player){
			if(isset($atts['ratio'])){
				$auto_height = "aspectratio: \"{$atts['ratio']}\"";
			}else{
				$auto_height = "height: \"$val_height\"" ;
			}
			if(isset($atts['noflash']) && $atts['noflash'] == 'yes'){
				$noflash = 'noflash:true' ;
			}else{
				$noflash = 'noflash:false' ;
			}

			$jwplayer_key = get_param_global('jwplayer7_key') ;
			$fpvideotag =  "
			<script>
			var setup_$id_block = {
				$opt_player
				src : \"$post_video\",
				//$auto_height, 
				//width: \"$val_width\",
				muted: $muted,
				language:\"fr\",
				autoplay: $autoPlay,
				src_img: \"$src_img\",
				repeat:$loop,
				$noflash ,
				autoloop:$autoloop,
				playlist:$playlist,
				force_play_type:$force_play_type,
			};
				$page_ready 
					if(\"$jwplayer_key\"){
						jwplayer.key=\"$jwplayer_key\";
						
					}

					show_jw7_player(\"player_$id_block\",setup_$id_block,\"$template_dir_uri\",$id_pub_encoded,\"$provider\", $params_encoded);	
				});
			</script>";
		}

		else {
			$fpvideotag =  "
			<script>
				setup_$id_block = {
					$opt_player
					src : \"$post_video\",
					height: \"$val_height\",
					width: \"$val_width\",
					muted: $muted,
					language:\"fr\",
					autoplay: $autoPlay,
					loop:$loop,
					other_videos: $other_videos,
					autoloop:$autoloop,
					playlist:$playlist,
			";

			// Add param 'start_at' from the youtube param 't' that is set in the mediaid 
			if($provider == 'youtube') {
				$query = parse_url(htmlspecialchars_decode($atts['mediaid']), PHP_URL_QUERY);
				parse_str($query, $prms);
				if(!empty($prms['t'])) {
					$start_at = (int)$prms['t'];
					$fpvideotag .= 'start_at:' . $start_at . ',';
				}
				
			}

			$fpvideotag .=  "hide_play_icon:" . $hide_play_icon . ',';

			$fpvideotag .=  "
				};
				
				";

				if(isset($atts['cat_event'])){
					$fpvideotag .=  "setup_$id_block ['cat_event'] = '{$atts['cat_event']}'";
				}
				if($autoPlay == 'false' and is_dev('click_to_play_videojs_153745207') ){
					$fpvideotag .= "
					setup_$id_block.autoplay = true ;
					jQuery(\"#player_img_$id_block\").click(function(){
						jQuery(\"#player_$id_block\").show();
						jQuery(this).hide();
						
						var id_pub = $id_pub_encoded ;
						if(window.confing_tag_video_mobile){
							id_pub = confing_tag_video_mobile(id_pub)
						}

						show_videojs(\"player_$id_block\",setup_$id_block,\"$template_dir_uri\",id_pub,\"$provider\", $params_encoded);	
					});";

				}else{
					$fpvideotag .= "
					$page_ready 
						var id_pub = $id_pub_encoded ;
						if(window.confing_tag_video_mobile){
							id_pub = confing_tag_video_mobile(id_pub)
						}
						show_videojs(\"player_$id_block\",setup_$id_block,\"$template_dir_uri\",id_pub,\"$provider\", $params_encoded);	
					});";
					
				}
			$fpvideotag .= "</script>";
		}

		$fpvideo.=$fpvideotag;
	}
	if($is_live_content){
		if($info){
			global $post;
			$desc = $info['description'] ? $info['description'] : $post->post_excerpt;
			$capt = $info['caption'] ? $info['caption'] : $post->post_excerpt;
			if($desc){				
				$fpvideo.= '<meta itemprop="description" content="'. htmlentities($desc, ENT_QUOTES).'" />';
			}
			if($capt){			
				$fpvideo.= '<meta itemprop="caption" content="'. htmlentities($capt, ENT_QUOTES).'" />';
			}
			$fpvideo.= '</div>';
		}
	}
	$link_read_post = "";
	if( $redirect_id ){
		$link = get_permalink($redirect_id);
		if($link)
			$link_read_post = '<a href="'.$link.'" class="read_post_video"></a>';
	}
	$fpvideo .=	$link_read_post;
	$fpvideo = apply_filters('fpvideo_html', $fpvideo, $atts);
	return $fpvideo;
}
endif;

add_shortcode('fpvideo', 'videojs_shortz');
add_shortcode('jwplayer', 'videojs_shortz');


function get_video_dai_params($video_params_array = null){
	$video_params = [];
	$video_params_params = [];
	foreach ($video_params_array as $key => $value) {
		if(isset($value)){
			if(in_array($key, array('video', 'width', 'height'))){
				$video_params[$key] = $value;
			}else{
				$video_params_params[$key] = $value;
			}
		}
	}
	$video_params['params'] = $video_params_params;
	$video_params['other_videos'] = $video_params_array['other_videos']; 
	$video_params['right_video'] = $video_params_array['right_video']; 
	$video_params = json_encode($video_params);
	return $video_params;
}

add_filter('fp_video_id', 'filter_fp_video_id');
function filter_fp_video_id($video_id){
	$pos = strpos($video_id, '#') ;
	if($pos !== false){

		$video_id = substr($video_id, 0, $pos);
	}
	return $video_id;
}

add_shortcode("push_top_popular", "shortcode_ga_top_popular");

function shortcode_ga_top_popular($atts){
	
	$post_types = apply_filters('top_popular_post_types', array('post'))  ;
	$r='';
	$post_type = get_post_type( get_the_ID() );
	//get current category id from url
	$current_url_cat = RW_Category::get_post_category_from_url();
	$current_cat =  RW_Category::get_top_parent_category($current_url_cat);
	if(!$current_cat){
		$current_cat = 'no_cat'; 
	}

	if ( !$r && is_single() &&  in_array( $post_type , $post_types) ) {
		$content	= get_the_content();
		if(is_dev('ga_opt_event_top_popular_152881992')):
			$top_popular = '';
			if( RW_Post::page_has_video() ){
				// log GA event for top_popular article within video
				if(get_param_global('force_GA_video_cat_send')){
					$top_popular = 'top_popular/video';
				}else{
					$id_video_cat = RW_Category::rw_get_category_by_slug( 'videos' )->term_id;
					$id_video_cat = apply_filters('ga_categories_videos' , array($id_video_cat) );
					$list_cats = wp_get_post_categories(get_the_ID());

					$new_list_cats = $list_cats;
					
					foreach ($list_cats as $id_cat) {
						$id_parent_cat = get_term_by('id', $id_cat, 'category')->parent;
						if($id_parent_cat!=0 && !in_array($id_parent_cat,$new_list_cats))
							$new_list_cats[] =$id_parent_cat;
					}

					if(array_intersect($id_video_cat,$new_list_cats)!=null){
						$top_popular = 'top_popular/video';
					}
				}
			}else if( stristr($content , "[gallery" ) ) {
				// log GA event for top_popular article within video
				if( !get_param_global('disable_top_popular_diapo_event') ){
					$top_popular = 'top_popular/diapo';
				}
			}else if(get_post_type()=="exposant"){
				if( !get_param_global('disable_top_popular_exposant_event') ){
					$top_popular = 'top_popular/exposant';	
				}
			}else {
				$top_popular = apply_filters( 'top_popular', 'top_popular' , $post_type );
			}
			if( !empty( $top_popular ) ):
				$r .= "<script type='text/javascript'>
				setTimeout(function(){
					send_GA( '$top_popular', '$current_cat', '" . get_the_ID() . "');
				}, 15000);
				</script>";
			endif;
		else:
		// search inside post content for string [fpvideo](ie. contains video)
		if(RW_Post::page_has_video()) {
			// log GA event for top_popular article within video
			if(get_param_global('force_GA_video_cat_send')){
				$r .= "<script type='text/javascript'>
				setTimeout(function(){
					send_GA( 'top_popular_video_cat', '$current_cat', '" . get_the_ID() . "');
				}, 15000);
				</script>";

			}else{
				$category = RW_Category::rw_get_category_by_slug( 'videos' );
				$id_video_cat = isset($category->term_id)? $categories->term_id : '';
				$id_video_cat = apply_filters('ga_categories_videos' , array($id_video_cat) );
				$list_cats = wp_get_post_categories(get_the_ID());

				$new_list_cats = $list_cats;
				
				foreach ($list_cats as $id_cat) {
					$id_parent_cat = get_term_by('id', $id_cat, 'category')->parent;
					if($id_parent_cat!=0 && !in_array($id_parent_cat,$new_list_cats))
						$new_list_cats[] =$id_parent_cat;
				}

				if(array_intersect($id_video_cat,$new_list_cats)!=null){
					$r .= "<script type='text/javascript'>
							setTimeout(function(){
								send_GA( 'top_popular_video_cat', '$current_cat', '" . get_the_ID() . "');
							}, 15000);
							</script>";
				}
			}
		}

		if(stristr($content , "[gallery" )) {
			// log GA event for top_popular article within video
			$r .= "<script type='text/javascript'>
			setTimeout(function(){
				send_GA( 'top_diapos', '$current_cat', '" . get_the_ID() . "');
			}, 15000);
			</script>";
		}

		if(get_post_type()=="exposant"){
			$r.= "<script type='text/javascript'>
			setTimeout(function(){
				send_GA( 'top_popular_exposant', 'exposant', '" . get_the_ID() . "');
			}, 15000);
			</script>";
		}
		
		$top_popular = apply_filters( 'top_popular', 'top_popular' , $post_type );
		// log GA event for top_popular article
		$r .= "<script type='text/javascript'>
		setTimeout(function(){
			send_GA( '$top_popular', '$current_cat', '" . get_the_ID() . "');
		}, 15000);
		</script>";
		endif;
	}
	$r = apply_filters('top_popular_tracking', $r);
	return $r ;
}

function shortcode_popular_exposants($atts,$content){
	$html = '';
	$block_per_line = 1;
	$most_popular_exposant = get_option(apply_filters('most_popular_exposant_option',"most_popular_exposant"), array());
	$n = isset($atts["number"]) ? $atts["number"] : 2;
	$args = array('post__in' => $most_popular_exposant,'post_type' => 'exposant','posts_per_page' => $n);
	if(get_param_global('enable_cache_popupar_exposants')){
		$key = "popular_exposants_".md5(serialize($atts));
		$html = get_data_from_cache($key, 'shortcode_popular', TIMEOUT_CACHE_POPULAR_EXPOSANTS, function()use($args, $n, $block_per_line){
			if(defined('_LOCKING_ON_') && _LOCKING_ON_ &&  $args_lock = get_locking_config('widget', 'popular_posts')){

				$page_name = 'widget';
				$element = 'popular_posts' ;

				$args_lock = array(
					'page'=> $page_name,
					'element'=> $element
				);
				$exposants=Locking::get_locking_ids($args_lock , $args);
			}else{
				$exposants=get_posts($args);
			}
			
			if(count($exposants)){
				ob_start();
				$most_popular=$exposants;
				require(locate_template('/include/templates/post_most_popular.php'));
				$html = ob_get_contents();
				ob_end_clean();
				return $html;
			}
		});
	}else {
		if(defined('_LOCKING_ON_') && _LOCKING_ON_ &&  $args_lock = get_locking_config('widget', 'popular_posts')){
				$page_name = 'widget';
				$element = 'popular_posts' ;

				$args_lock = array(
					'page'=> $page_name,
					'element'=> $element
				);
				$exposants=Locking::get_locking_ids($args_lock , $args);
			}else{
				$exposants=get_posts($args);

			}
		if(count($exposants)){
			ob_start();
			$most_popular=$exposants;
			require(locate_template('/include/templates/post_most_popular.php'));
			$html = ob_get_contents();
			ob_end_clean();
		}
	}

	return $html;
}
add_shortcode('popular_exposant','shortcode_popular_exposants');

add_shortcode('bloc_pms_read_more','get_bloc_pms_read_more');
function get_bloc_pms_read_more($attr) {
    $html = '';
    ob_start();
	include(locate_template('include/templates/read-more-block.php'));
	$html .= ob_get_clean();
    return $html;
}