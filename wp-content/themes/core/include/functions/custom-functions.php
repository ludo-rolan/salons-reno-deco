<?php

function mini_excerpt_for_lines($max , $nLigne, $fin= "...") { 
	if( has_excerpt() ){
        $text =  get_the_excerpt() ;
        return RW_Utils::mini_text_for_lines($text, $max , $nLigne, $fin) ;
    } else {
    	return '';
    }
}

function get_locking_config( $page , $element ){
	if ( class_exists('Locking') ){
		$locking_page = (defined('RELATED_MAIN_SECTION')  && in_array( $page , array('home', 'widget'))  )? $page .'_' . RELATED_MAIN_SECTION : $page ;
		global $site_config;
		if ( isset($site_config['locking'][$locking_page][$element]) ) {
			return  array(
				'page'=>$locking_page,
				'element'=>$element
			);
		}
	}
	return false;
}

function get_balisage_seo($param, $default){
	$seo = get_param_global('balises_seo');
	$balise = $default ;
	if(!empty($seo)){
		$balise = !empty ($seo[$param]) ? $seo[$param] : $default;
	}
	return $balise;
}

function get_nav_header(){
	$key_cache = 'nav_header';
	if(rw_is_mobile()){
		$key_cache .= "_m";
	}else{
		$key_cache .= "_d";
	}
	if(is_category()){
		$key_cache .= '_cat_'. RW_Category::get_top_parent_category(get_queried_object());
	}else if(is_single()){
		$cat = RW_Category::get_menu_cat_post();
		$key_cache .= '_single_'. RW_Category::get_top_parent_category($cat);
	}

	$template_nav_menu = 'nav-menu.php';
	$key_cache .= '_'.substr( md5($template_nav_menu) , 0, 6 );

	echo_from_cache(  $key_cache , 'nav_header' , TIMEOUT_CACHE_MENU_ITEMS, function() use ($template_nav_menu) {
		load_template( locate_template('include/templates/'.$template_nav_menu)) ;
		$nav_header = ob_get_contents();
	});
}


function get_the_menu_header_v2(){
	global $wp_query ;
	$ul_menu = $current_cat = $target = $html ='';
	if( is_single() ){
		$current_cat = RW_Category::get_post_category_from_url();
	} elseif ( is_category() ){
		$current_cat = get_term($wp_query->query_vars['cat'], 'category');	
	}
	$menu_id = apply_filters('get_menu_name', 'menu_header', 'menu_header');

	// Setup cache menu
	$key_cache = $menu_id . rw_is_mobile() ;
	$key_cache .= isset($current_cat) && is_object($current_cat) ? $current_cat->term_id : '' ;
	if(TIMEOUT_CACHE_MENU_NAV > 0 && !isset($_GET['disable_cache'])) {
		if ( $cache = wp_cache_get( $key_cache, 'menu_nav_cats' ) ){
			return $cache;
		}
	}

	$menu_items = wp_get_nav_menu_items($menu_id);
	if ( is_array($menu_items) ){
		$menu_items = apply_filters('menu_header_items', $menu_items) ;
		$menu_arrow = get_param_global('add_menu_arrow') ? rw_is_mobile() ? '<span class="arrow down"></span>' : '<span class="arrow"></span>' : '';
		foreach ($menu_items as &$menu_item){
			if($menu_item->menu_item_parent == 0){
				$is_current_menu_item = false;
				if(isset($current_cat) && is_object($current_cat) && $current_cat!==false
				 && in_array(
				 	$menu_item->object_id, 
				 	array($current_cat->term_id ,$current_cat->parent))){
					$is_current_menu_item = true;
				} elseif ( ($current_page = get_query_var('page_name')) && strpos($menu_item->url, $current_page) ) {
					$is_current_menu_item = true;
				} elseif( is_page() && isset($wp_query->queried_object->ID) && $menu_item->object_id == $wp_query->queried_object->ID ){
					$is_current_menu_item = true;
				}elseif ( is_home() && $menu_item->url == '/' ) {
					$is_current_menu_item = true;
				}


				$sub_ul_menu = '';
				foreach ($menu_items as $menu_item_level_2){
					if($menu_item_level_2->menu_item_parent == $menu_item->ID){
						$sub_ul_menu .= '<li class="menu-item ' . (isset($menu_item_level_2->classes)? implode(" ", $menu_item_level_2->classes):"") .'">' . "\n";
						$target = !empty($menu_item_level_2->target) ? 'target="'. $menu_item_level_2->target .'"' : '';
						$sub_ul_menu .= '<a  href="'.$menu_item_level_2->url.'" '. $target .'>'.$menu_item_level_2->title.'</a>' . "\n";
						$sub_ul_menu .= '</li>';
						if( !$is_current_menu_item && $menu_item_level_2->menu_item_parent == $menu_item->ID && !empty($current_page) && strpos($menu_item_level_2->url, $current_page) ){
							$is_current_menu_item = true;
						}
					}					
				}

				$ul_menu .='<li class="menu-item ' . (isset($menu_item->classes) ? implode(" ", $menu_item->classes) : "") . (($is_current_menu_item) ? " current-menu-item" : "") . ($sub_ul_menu ? ' menu-item-has-children' : ''  )  .'" id="menu-item-'.$menu_item->ID.'">'. (!empty($sub_ul_menu)? $menu_arrow :'') .'';
				$atts_link = apply_filters('attr_link_menu', '', $menu_item);
				$target = !empty($menu_item->target) ? 'target="'. $menu_item->target .'"' : '';
				$ul_menu .='<a '. $atts_link .'  href="'.$menu_item->url.'" '. $target .'>'.$menu_item->title.'</a>' . "\n";
				if( !empty($sub_ul_menu) ){
					$ul_menu .= '<ul class="nav nav-bar list-children-cat up">'.$sub_ul_menu.'</ul>';
				}
				$ul_menu .='</li>' . "\n";

			}
		}	
		$ul_menu = apply_filters( 'ul_menu_header_v2', $ul_menu );
		$html = '<div id="principal-menu">';
		$html .= '<ul class="nav navbar-nav nav-menu">'. $ul_menu .'</ul>';
		$html .= '</div>';
		if(TIMEOUT_CACHE_MENU_NAV > 0) {
			wp_cache_set($key_cache, $html , 'menu_nav_cats' , TIMEOUT_CACHE_MENU_NAV );
		}
	}
	return $html;				
}

function add_search_rw(){
	//wp_nonce_field( 'internal-linking', '_ajax_linking_nonce', false );
	//wp_enqueue_style('search-rw-css', STYLESHEET_DIR_URI . '/assets/stylesheets/search_rw.css');
	//wp_enqueue_script('search-rw-js', STYLESHEET_DIR_URI.'/assets/javascripts/search_rw.js', array('jquery'), CACHE_VERSION_CDN, true );
}

function get_id_by_slug($slug, $type_id = 'category'){
	$id = "";
	if($type_id == 'category'){
		$idCatObj = RW_Category::rw_get_category_by_slug($slug); 
  		$id = ($idCatObj) ? $idCatObj->term_id : $id;
  	}
  	return $id;
}

function get_single_sharedcount($post_id= null){
	$post_id = (is_numeric($post_id) && $post_id > 0)? $post_id :get_the_ID();
	$sharedcount = get_post_meta($post_id, 'sharedcount', true);
	if(!is_array($sharedcount)){
		$sharedcount = array() ;
	}
	$defaults = array(
	    'Twitter' => 0,
	    'Facebook' => array(
	            'share_count' =>0,
	            'like_count' =>0,
	            'comment_count' =>0,
	            'total_count' =>0,
	            'click_count' =>0,
	            'comments_fbid' =>0,
	            'commentsbox_count' => 0,
		),
	    'Pinterest' => 0,
	    'GooglePlusOne' => 0,
	    'last_update' => 0,
	    'hash' => hash_post_id($post_id, true),
	) ;
	$defaults = apply_filters('sharedcount_defaults', $defaults);
	$sharedcount = wp_parse_args($sharedcount, $defaults);


	return $sharedcount ;
}

function hash_post_id($post_id, $first = false){
	if($first){
		$s =  $post_id  ;
	}else{
		$s =  $post_id . "-" .time() ;
	}
	return substr(md5( $s), 0, 8)  ;
}

function top_intro_article (){
	global $has_gallery, $post;

	include(RW_THEME_DIR."/include/templates/title-page.php");
	if( !wp_is_mobile() ): 
		do_action('single_after_title');

		$time_post_modified = strtotime( $post->post_modified) ;
		$time_post_date = strtotime( $post->post_date) ;
		if( $time_post_modified < $time_post_date){
			$date = get_the_date();
		}else{
			$date = get_the_modified_date();
		}
	endif; ?>

	<div class="post_signature">
		<div class="post_signature_item">
	        <?php
				$content = apply_filters( 'custom_posts_datetime', get_the_date() ); 
				echo $content; 
	        ?>                 
		</div>
	    <?php if( !wp_is_mobile() ): ?>
			<div class="post_signature_item">
				<?php echo __( 'mis à jour le ' ) . $date ; ?>
			</div>
		<?php endif ?>
	</div>

	<?php
    do_action('before_top_img_single');

    if( rw_post::page_has_gallery() ){
	    do_action('show_gallery');
    }else{
		$is_thumb_featured = get_post_meta($post->ID, 'is_thumb_featured', true);
		if( apply_filters('is_thumb_featured', $is_thumb_featured) ){ 
			$attachment = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');?>
			<span data-href="<?php echo $attachment[0]; ?>"  alt="<?php echo $post->post_title; ?>" title="<?php echo $post->post_title; ?>">				
				<?php 
					$attrs = array("class" => "img-responsive");
					if( !is_dev('seo_correction_micro_donnees_articles_111394476') ){
						$attrs["itemprop"] = "image";
					}
					echo get_the_post_thumbnail( $post->ID, "rw_gallery_full", $attrs ); 
				?>
			</span>	
		<?php 
	    }
    }
    if( has_excerpt() ){
        the_excerpt();
    }
}

function save_shorten_url_metadata($post_id, $post, $update) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)   {
    	return $post_id;
    }
	$shorten_url = get_post_meta($post->ID, 'shorten_url', true );
	$current_permalink = get_post_meta($post->ID, 'current_permalink', true );
	$permalink = get_permalink( $post_id );
	if(empty($current_permalink) || strcmp($current_permalink, $permalink) !== 0 || strcmp($shorten_url, $permalink) === 0 || strpos($shorten_url ,"http://") === false) {
		$shorten_url = get_reworld_bitly_shorten_url($permalink);
		if(strcmp($shorten_url, $permalink) !== 0  && strpos($shorten_url ,"http://") !== false) {
			update_post_meta( $post_id, 'shorten_url', $shorten_url );
			update_post_meta( $post_id, 'current_permalink', $permalink );	
		}
	}
}

function get_reworld_bitly_shorten_url($url){
	if(get_param_global('bitly_login') && get_param_global('bitly_appkey')){
		$login=get_param_global('bitly_login');
		$appkey = get_param_global('bitly_appkey');
		
		return rtrim(get_bitly_short_url($url, $login, $appkey),"\n");
	}
	return $url;
}

function get_bitly_short_url($url,$login,$appkey,$format='txt') {
	$connectURL = 'http://api.bit.ly/v3/shorten?login='.$login.'&apiKey='.$appkey.'&uri='.urlencode($url).'&format='.$format;
	$response = wp_remote_get($connectURL);
	if ( is_wp_error($response) ) {
		return null;
	}
	$body = wp_remote_retrieve_body($response);
	if(empty($body)){
		$body = file_get_contents($connectURL);
	}
	return $body;
}

function twitter_details($post){
	$twitter_shorten_url = get_post_meta( $post->ID, 'shorten_url', true );
	$regenerate_shortcode = (empty($twitter_shorten_url) || strpos($twitter_shorten_url, "http://bit.ly") === false) && get_param_global('bitly_login') && get_param_global('bitly_appkey');
	if( $regenerate_shortcode ) {
		
		save_shorten_url_metadata($post->ID, $post, true);
		
		$twitter_shorten_url = get_post_meta( $post->ID, 'shorten_url', true );
	}
	$twitter_shorten_url = rtrim($twitter_shorten_url,"\n");
	if(strpos($twitter_shorten_url ,"http://") === false) {
		$twitter_shorten_url = get_permalink();
	}
	
	$tw_hastag = get_param_global('hastag');
	$tw_hastag = isset($tw_hastag) ? $tw_hastag:'';

	$tw_via = get_param_global('twvia');
	$tw_via = isset($tw_via) ? $tw_via:'';

	return array(
				"title"     => $post->post_title,
				"url"       => $twitter_shorten_url, 
				"hashtags"  => $tw_hastag, 
				"via"       => $tw_via
			);
}

function get_shared_total($return){
	if(isset($return['Facebook']))
		$total = $return['Facebook']['total_count'] +  $return['GooglePlusOne'] + $return['Twitter'] + $return['Pinterest'];
	else $total = 0;
	if(isset($return['Facebook_#']))
		$total += $return['Facebook_#']['total_count'];
	
	return $total;
}

function header_menu(){
	$nav_cls = "nav navbar-nav";
	if(rw_is_mobile()){
		$nav_cls .= " scrolable_nav";
	}
	$html_nav = '';
	$menu_header = apply_filters('get_menu_name', 'menu_header', 'menu_header');
	$menu_items = apply_filters('menu_items_header', wp_get_nav_menu_items($menu_header), $menu_header);

	if(!empty($menu_items)){
		$html_nav .= '<nav class="menu-site">';
		$html_nav .= '<ul class="'.$nav_cls.'">' ;
		$target = '';
		foreach ($menu_items as $menu_item){
			if($menu_item->menu_item_parent == 0){
				$link=$menu_item->url;
				$target = !empty($menu_item->target) ? 'target="'. $menu_item->target .'"' : '';
				$link=apply_filters('header_menu_href_link',$link);
				$html_nav .='<li class="'. (isset($menu_item->classes)? implode(" ", $menu_item->classes):"") .' ' .' menu-item menu-item-type-taxonomy menu-item-object-category menu-item-has-children menu-item-'.$menu_item->ID.'">
					<a href="'.$link.'" '. $target .'>'.$menu_item->title.'</a>
					</li>' ;
			}
		}
		$html_nav .='</ul>';
		$html_nav .='</nav>';
	}
	echo $html_nav;
}

function get_cat_sous_menu($cat_id){
	global $post ;
	$cat_mise_en_avant_slug = apply_filters('menu_site_slug', 'menu_site');
	$cat_mise_en_avant =  RW_Category::rw_get_category_by_slug($cat_mise_en_avant_slug);
	$args = array( 
			'posts_per_page' => apply_filters("nbr_post_sous_menu",3,$cat_id), 
			'tax_query' => array(
		        'relation' => 'AND',
		        array(
		            'taxonomy' => 'category',
		            'field' => 'term_id',
		            'terms' => $cat_id,
		        ),
		        array(
		            'taxonomy' => 'category',
		            'field' => 'term_id',
		            'terms' => $cat_mise_en_avant->term_id
		        )
		    )
	 	) ;
	$args = apply_filters('args_posts_sous_menu', $args , $cat_id) ;

	$posts_sticky = get_posts($args);
	$sticky_ids = array();
	foreach ($posts_sticky  as &$post_new) { 
		$post  = $post_new ;
		setup_postdata( $post );
		$sticky_ids[] = $post->ID ;
		$post_new->date =  get_the_date(); 
		$post_new->link = get_permalink();
		$post_new->mini_title = strip_tags(RW_Utils::mini_text_for_lines($post->post_title, 26 , 2)) ;
		$yoast_wpseo_title = get_post_meta(get_the_ID(), "_yoast_wpseo_title", true);
		if (has_post_thumbnail(get_the_ID())){ 	
			$img_attr = array();
			if($yoast_wpseo_title != ""){
				$img_attr = array('alt' => $yoast_wpseo_title);
			}
			$post_new->img = get_the_post_thumbnail(get_the_ID(), 'rw_small', $img_attr);
		}
	}
	wp_reset_postdata();


	$last_post = false;
	$last_posts = get_posts( array( 'post__not_in' => $sticky_ids, 'posts_per_page' => 1, 'category' => $cat_id) );
	if(count($last_posts)){
	 	$post_new2 = $post =  $last_posts[0] ; 
	 	setup_postdata( $post );
	 	$post_new2->date =  get_the_date(); 
		$post_new2->link = get_permalink();
		$post_new2->mini_title = strip_tags(RW_Utils::mini_text_for_lines($post->post_title, 46 , 2)) ;
		$yoast_wpseo_title = get_post_meta(get_the_ID(), "_yoast_wpseo_title", true);
		if (has_post_thumbnail(get_the_ID())){ 	
			$img_attr = array();
			if($yoast_wpseo_title != ""){
				$img_attr = array('alt' => $yoast_wpseo_title);
			}
			$post_new2->img = get_the_post_thumbnail(get_the_ID(), 'rw_medium', $img_attr);
		}
		$cat = RW_Category::get_post_category_from_url($post);
		
		if(!empty($cat->term_id)){
			$post_new2->cat_name = @$cat->name;
			$post_new2->cat_link = get_category_link ($cat->term_id) ;
		}
		$last_post = $post_new2 ;
		wp_reset_postdata();
	}

	return array('last_post'=>$last_post, 'posts_sticky'=>$posts_sticky);	
}

function generate_sub_level($items, $parent_id , $level=2, $atts=array()){
	$ul_classes = isset($atts['ul_classes']) ? ' class="'. $atts['ul_classes'] .'"' : '' ;
	$childs = array();
	$html=array();
	foreach ( $items as $item ){
		if ( $item->menu_item_parent == $parent_id ){
			$childs[]=$item;
		}
	}
	if ( count($childs) ){
		$html[]='<ul'.$ul_classes.'>';
		foreach ( $childs as $child){
			
			$menu_classes = "" ;
			if(get_param_global('activate_subcategories')){
				if(isset($atts['parent_cat_slug'])){
					$menu_classes = "sub-menu-item-".$atts['parent_cat_slug'];
				}
				if(isset($atts['current_cat_id']) and $child->object_id == $atts['current_cat_id']){
					$menu_classes .= " active";
				}
			}
			$html[]='<li class="'. (isset($child->classes)? implode(" ", $child->classes):"") .' menu-item menu-item-type-taxonomy menu-item-object-category menu-item-'.$child->ID.' '.$menu_classes.'"><a href="'.$child->url.'">'.$child->title.'</a>';
			// level

			if( get_param_global('has_recursive_level_menu') > $level+1  ){
				$html[] .= generate_sub_level($items , $child->ID , $level+1);
			}
			$html[] .='</li>' . "\n";
		}
		$html[]='</ul>';
	}

	return implode( '' , $html ) ;
}
