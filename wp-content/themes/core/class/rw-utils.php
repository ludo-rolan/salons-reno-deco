<?php
/**
* 
*/
class RW_Utils {
	

	private static $_instance;

	function __construct(){
		# code...
	}
	
	static function get_instance(){
		if(is_null(self::$_instance)){
			self::$_instance = new RW_Utils();
		}
		return self::$_instance;
	}
	
	static function add_search_rw(){
		wp_nonce_field( 'internal-linking', '_ajax_linking_nonce', false );
		wp_enqueue_style('search-rw-css', RW_THEME_DIR_URI.'/assets/css/search_rw.css');
		wp_enqueue_script('search-rw-js', RW_THEME_DIR_URI.'/assets/js/search_rw.js', array('jquery'), CACHE_VERSION_CDN, true );
	}

	static function get_cache_version_cdn(){
		if (is_multisite())
			$cache_version_cdn = get_site_option('cache_version_cdn', DEFAULT_CACHE_VERSION_CDN) ;
		else $cache_version_cdn = get_option('cache_version_cdn', DEFAULT_CACHE_VERSION_CDN) ;
		
		return ($cache_version_cdn >  DEFAULT_CACHE_VERSION_CDN)? $cache_version_cdn : DEFAULT_CACHE_VERSION_CDN ;
	}

	static function lastest_jquery_in_bottom(){
		global $wp_scripts;
		// Copied from next-gen
		if (isset($wp_scripts->registered['jquery'])) {
			$jquery = $wp_scripts->registered['jquery'];
			if (!isset($jquery->ver) OR version_compare('1.8', $jquery->ver) == 1) {
				wp_deregister_script('jquery');
				wp_deregister_script('jquery-core');
				wp_deregister_script('jquery-migrate');
				wp_register_script('jquery',false , array( 'jquery-core', 'jquery-migrate' ), '1.10.2' , true );
				wp_register_script('jquery-core', '/wp-includes/js/jquery/jquery.js' , array(), '1.10.2' , true );
				wp_register_script('jquery-migrate', '/wp-includes/js/jquery/jquery-migrate.min.js', array(), '1.2.2' , true );
				wp_enqueue_script('jquery');
			}
		}
	}
	static function replace_cnt(){
		global $pos_content;
		return $pos_content++;
	}

	static function breadcrumb() {
		global $post, $folder;
		$breadcrumb = apply_filters('generate_breadcrumb', '') ;
		$hide_breadcrumb = get_param_global('hide_rw_breadcrumb', false);
		if($breadcrumb == '' && !$hide_breadcrumb ){
			$cat_site_id=(function_exists('get_cat_site_id')) ? RW_Category::rw_get_category_by_slug(get_cat_site_id())->term_id : '';
			if (is_single()) {	
				$link_current_page = get_permalink();
				$link_current_page = str_replace(get_site_url().'/', '', $link_current_page);
				$link_current_page = explode('/', $link_current_page);
				if($folder !=null){
					$breadcrumb .='<li class="parent">'. __('Dossier' )  .'</li>' ;
					$breadcrumb .='<li class="parent" ><a href="'. get_permalink($folder->ID) .'" >'. $folder->post_title .'</a></li>' ;
					
				}else{	
					for ($i=0; $i<count($link_current_page)-1; $i++) {
						$link_part = $link_current_page[$i];
						$cat_object = RW_Category::rw_get_category_by_slug($link_part);
						if (is_object($cat_object) && $cat_object->term_id != $cat_site_id) {
							$cat_id = $cat_object->term_id;
							$breadcrumb .='<li class="'. (($cat_object->parent== 0)? "parent":"") .'" ><a href="'. get_category_link($cat_id) .'" >'.get_cat_name($cat_id).'</a></li>' ;	
						}
					}			
				}
			} 
			if (is_category()) {
				$cat_id = get_query_var('cat');
				$cat = $current_cat = get_category($cat_id);
				$breadcrumb_parts = array();
				while ($cat->parent!=0 && $cat->parent!=$cat_site_id) {
					array_push($breadcrumb_parts, '<li class="parent" ><a href="'. get_category_link($cat->parent) .'" >'.get_cat_name($cat->parent).'</a></li>');
					$cat = get_category($cat->parent);
				}
				$breadcrumb_parts = array_reverse($breadcrumb_parts);
				$breadcrumb .= implode('', $breadcrumb_parts);
				$breadcrumb .= '<li class=" '. ($current_cat->parent==0 ? 'parent':'') .'" ><a href="'. get_category_link($cat_id) .'" >'.get_cat_name($cat_id).'</a></li>';
						
			}

			if (is_page() ) {
				$breadcrumb .='<li class="" ><a href="'.get_permalink().'" >'.get_the_title().'</a></li>' ;	
			}

			if (is_author() ) {
				$author_name = get_author_name();
				$posts_url = get_author_posts_url(get_the_author_meta('user_nicename'));
				// Activer le lien vers la page des auteurs si elle existe
				$page_auteurs = get_page_by_path('auteurs');
				$page_auteurs_link =  $page_auteurs ? get_page_link($page_auteurs) : "";
				if($page_auteurs_link) {
					$breadcrumb .='<li class="" ><a href="' . $page_auteurs_link . '">'. __('AUTEURS').'</a></li>' ;
				}
				$breadcrumb .='<li class="" ><a href="'.$posts_url.'" >'.$author_name.'</a></li>' ;
				
			}
		}
		$breadcrumb = apply_filters('breadcrumb_rewo', $breadcrumb) ;
		if($breadcrumb){
			$class_breadcrumb = apply_filters("class_breadcrumb","breadcrumb");
			$breadcrumb  ='<ol class="'. $class_breadcrumb .'" >' . $breadcrumb.'</ol>';	
			$breadcrumb = apply_filters('breadcrumb_ol_rewo', $breadcrumb) ;
			
			global $pos_content;
			$pos_content=1;
			$breadcrumb = preg_replace_callback('/CNT_COUNT/','self::replace_cnt',$breadcrumb);			
		}
		$breadcrumb .= apply_filters('insert_script_microdata', '') ;
		$breadcrumb = apply_filters('filter_after_breadcrumb', $breadcrumb) ;

		return $breadcrumb;
	}

	/**
	 * Check validity of author social links
	 * @param STRING ($link) STRING ($type)
	 * @return STRING ($link)
	 */
	static function social_link_after_check($link, $type){
		if(!empty($link)){
			$pos = strpos($link, 'https://');

			if ($pos === false) {
				switch ($type) {
					case 'facebook':
						$link = "https://facebook.com/".$link;
						break;
					
					case 'twitter' :
						$link = "https://twitter.com/".$link;
						break;

					case 'instagram' :
						$link = "https://instagram.com/".$link;
						break;

					case 'googleplus' :
						$link = "https://plus.google.com/".$link;
						break;

					case 'pinterest' :
						$link = "https://pinterest.com/".$link;
						break;
				}
			}
		}

		return $link;
	}

	// Pagination par 1 - 10 - 100 -1000
	static function get_seo_pagination($nb_pages, $actual_page) {
		$pages = array();

		$current_hundred = intval($actual_page / 100);
		$current_ten     = intval($actual_page / 10);

		$max_hundred = ceil(($nb_pages + 1) / 100);
		$max_ten     = ceil(($nb_pages % 100) / 10);
		$max_unit    = min($nb_pages, ($current_ten * 10) + 9);

		$max_hundred = apply_filters('seo_pagination_max_hundred', $max_hundred, $nb_pages, $actual_page );
		$max_ten = apply_filters('seo_pagination_max_ten', $max_ten, $nb_pages, $actual_page );
		$max_unit = apply_filters('seo_pagination_max_unit', $max_unit, $nb_pages, $actual_page );

		for ($hundred_index = 0; $hundred_index < $max_hundred; $hundred_index++) {
			if ($hundred_index != 0) {
				$pages[$hundred_index * 100] = $actual_page == $hundred_index * 100 ? 'nolink' : ($actual_page == 1 ? 'link' : 'jslink');
			} elseif ($current_ten != 0) {
				$pages[1] = $actual_page == 1 ? 'nolink' : 'jslink';
			}

			if ($current_hundred == $hundred_index) {
				$nb_ten_loop = $hundred_index == $max_hundred - 1 ? $max_ten : 10;
				$ten_last    = ($current_hundred * 10) + $nb_ten_loop;
				for ($ten_index = $current_hundred * 10; $ten_index < $ten_last; $ten_index++) {
					if ($ten_index == $current_ten || ($actual_page == $nb_pages && $actual_page % 10 == 0 && $current_ten > 0 && $ten_index == $current_ten - 1)) {
						for ($unit_index = $ten_index * 10 + $current_ten - $ten_index; $unit_index < $max_unit; $unit_index++) {
							$this_unit_index = $unit_index + 1;
							if ($actual_page == $this_unit_index) {
								$pages[$this_unit_index] = 'nolink';
							} elseif (($actual_page == 1 && $hundred_index == 0 && $ten_index == 0)
									  || ($actual_page == ($hundred_index * 100) + ($ten_index * 10))
							) {
								$pages[$this_unit_index] = 'link';
							} else {
								$pages[$this_unit_index] = 'jslink';
							}
						}
					}

					$this_ten_index = ($ten_index * 10) + 10;
					if ($ten_index != $ten_last - 1 || ($ten_index < 10 && $actual_page != $this_ten_index && $this_ten_index <= $nb_pages)) {
						if ($actual_page == $this_ten_index) {
							$pages[$this_ten_index] = 'nolink';
						} elseif (($actual_page == 1 && $hundred_index == 0)
								  || ($actual_page == $hundred_index * 100)
						) {
							$pages[$this_ten_index] = 'link';
						} else {
							$pages[$this_ten_index] = 'jslink';
						}
					}
				}
			}
		}

		if (!isset($pages[$nb_pages])) {
			$pages[$nb_pages] = $actual_page == 1 ? 'link' : 'jslink';
		}

		return $pages;
	}

	static function reworldmedia_pagination($max_page=0) { 
		global $wp_query ;
		if ($max_page==0) $max_page = $wp_query->max_num_pages;
		$big = 999999999;
		$paginate_args = array(
			'base'         => str_replace($big, '%#%', get_pagenum_link($big)),
			'format'       => '?paged=%#%',
			'current' 	   => max( 1, get_query_var('paged')),
			'show_all'     => false,
			'end_size'     => 2,
			'mid_size'     => 2,
			'prev_next'    => apply_filters('remove_next_previous',true) ,
		 	'prev_text'    => __('Précédent' , REWORLDMEDIA_TERMS ),
		 	'next_text'    => __('Suivant' , REWORLDMEDIA_TERMS),
		 	'total'        => $max_page,
		 	'type'		   => 'array'
		);
		if(get_param_global('seo_pagination')) $paginate_args['seo_pagination'] = true;
		$paginate_links = paginate_links($paginate_args) ;
		$r = '';
		if ( $paginate_links ){
			foreach ($paginate_links as $link) {
				$is_active = strpos($link, 'page-numbers current') !== false ;
				$is_prev = strpos($link, 'prev page-numbers') !== false ;
				$is_next = strpos($link, 'next page-numbers') !== false ;
				$r .= '<li class="'. ($is_active?'active':'') . ($is_prev?'prev-page':'') . ($is_next?'next-page':'') .'">'.  $link .'</li>'; 
			}
		}
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		if( !empty($r) ) $r = '<ul class="pagination">'.$r.'</ul>';
		if($wp_query->max_num_pages){
			$r .= '<span class="number_page">'.  __('Page' , REWORLDMEDIA_TERMS)  .' <span class="active_page">'. $paged .'</span> '. __('sur' , REWORLDMEDIA_TERMS) .' '. $wp_query->max_num_pages.' </span>';
		}
		return $r;
	}

	static function mini_text($text, $max ,$fin= " ...") { 
		$text = trim($text);
		$text = str_replace('&nbsp;&raquo;', '"', $text);
		$text = str_replace('&laquo;&nbsp;', '"', $text);
		$text = html_entity_decode($text, ENT_QUOTES, "utf-8");
		$text = trim($text);
		$return = "" ;
		if ($text != "") {
			$words = explode(" ", $text );
			foreach($words as $word) {
				if ( strlen($return . " " . $word) >$max ) {							
					return $return . $fin ;
				}	
				$return .= " " . $word ;
			}
		}
		return $return  ;
	}

	static function mini_text_for_lines($text, $max , $nLigne=1, $fin= "...") {
		$text = str_replace('&nbsp;&raquo;', '"', $text);
		$text = str_replace('&laquo;&nbsp;', '"', $text);
		$text = html_entity_decode($text, ENT_QUOTES, "utf-8");
		$text = strip_tags($text);
		$text = trim($text);
		$return ="";
		$pointer=0;
		for($i=0;$i<$nLigne;$i++) {
			$return1 = self::mini_text(substr($text,$pointer), $max, "");
			if ($i +1 == $nLigne && strlen($return1 .$fin ) > $max) {
				 $return1 = substr($return1, 0, strrpos($return1, " "));  
			}	
			$return .=  $return1 ;
			$pointer = strlen( trim ($return) );
			if ($pointer  == strlen($text )) {
				return $return  ;
			}	
		}
		return $return . $fin ;
	}

	static function split_title($text , $post_id) {
		$text = trim($text);
		$text = str_replace('&nbsp;&raquo;', '"', $text);
		$text = str_replace('&laquo;&nbsp;', '"', $text);
		$text = html_entity_decode($text, ENT_QUOTES, "utf-8");
		$title_highlight = get_post_meta($post_id , 'title_highlight' , true);
		if ( $title_highlight && strpos( $text, $title_highlight  )!==false){
			// be sure that the highlighted is included		
			$text = self::mini_text_for_lines($text, 36, 3);
			return '<strong>'.str_replace($title_highlight , '</strong><em>'.$title_highlight , $text ).'</em>';
		} else{
			if(strlen($text) >=36){
				$strong = mini_text($text, 32, '');
				$em = substr($text, strlen($strong));
				$em = self::mini_text_for_lines($em, 36,2);
				return "<strong>".$strong . "</strong><em>" . $em."</em>";
			}else{
				$words = explode(' ', $text );
				$count = count($words);
				$half = 1 + (int)$count/2 ;
				if (isset($words[$half]) && strlen($words[$half])==1)
					$half+=1;
				array_splice($words, $half , 0, "</strong><em>");
				return "<strong>".implode( ' ' , $words )."</em>";
			}
		} 
	}

	static function mini_excerpt_for_lines($max , $nLigne, $fin= "...") { 
		if( has_excerpt() ){
	        $text =  get_the_excerpt() ;
	        return self::mini_text_for_lines($text, $max , $nLigne, $fin) ;
	    } else {
	    	return '';
	    }
	}

	static function mini_title_for_lines($max , $nLigne, $fin= "...") { 
		$text =  get_the_title() ;
		if($max == -1){
			return $text;
		}
		return self::mini_text_for_lines($text, $max , $nLigne, $fin) ;
	}

	static function show_menu_on_site(){
		$menu_site='';
		$url_feed=get_param_global('url_feed');
		$site_name=get_param_global('site_name_feed');
		if($url_feed!=''){
			$list_json = get_transient("json_sagamf");
			if(!$list_json){
				$list_json=file_get_contents($url_feed);
				set_transient( "json_sagamf" , $list_json, 60*60*12);
			}
			$list_json=json_decode($list_json);
			$menu_site.='<div class="menus">
					<ul class="nav-menu">					
						<li class="menu-item">'.$site_name.'
							<ul class="sub-menu" id="mariefrance" style="height:70px;width: 963px;">';
									foreach ($list_json as $item_json) {
										$menu_site.='<li class=""menu-item " style="float:left;display: inline;width: 310px;"><a href="'.$item_json->permalink.'" title="'.$item_json->title.'">'.$item_json->title.'</a></li>';
									}
							
				$menu_site.='</ul>
						</li>
					</ul>	
			</div>';
		}
		return $menu_site;
	}

	static function get_time_PT($str){
		$str = strtolower($str);
		$str = str_replace(array("minutes", "heures" ," et "), array("M", "H" ," "), $str);
		$str = str_replace(array("minute", "heure", " "), array("M", "H", ""), $str);
		return "PT".$str ;
	}

	static function remover_style_attribute($content){
		return preg_replace ( '/(\s+)style="(.*?)"\s*/mi' , '$1' , $content );
	}

	static function get_locations(){
		global $post ;
		$locations = '';
		$sep = '';
		$locs = array('gn-location-1','gn-location-2','gn-location-3');
		foreach ($locs as $tax) {
			$terms = get_the_terms($post->ID,$tax);
			if ( is_array($terms) ) {
				$obj = array_shift($terms);
				$term = is_object($obj) ? trim($obj->name) : '';
				if ( !empty($term) ) { 
					$locations .= $sep . $term; 
					$sep = ', ';			
				}
			}
		}
		return $locations ;
	}

	static function add_role_RW_admin(){
	    global $wp_roles;
	    global $current_user;
	    if ( ! isset( $wp_roles ) )
	        $wp_roles = new WP_Roles();
	    $adm = $wp_roles->get_role('administrator');
	    $wp_roles->add_role('rw-admin', 'RW admin', $current_user->allcaps);    
	}

	/**
	 * Prepare html block for mobile
	 * @param integer $id (post id)
	 * @return string  
	 */

	static function get_video_properties ($url){ 
		$video_id = '' ;
		$val_split = '/';     
		if (strpos($url, 'youtu') !== false ) {
			$type='youtube'; 
			if (strpos($url, '/?v=') !== false){
				$val_split = '/?v=';
			}
			if (strpos($url, '/?vi=') !== false){
				$val_split = '/?vi=';
			}	
			if (strpos($url, '/watch?v=') !== false){
				$val_split = '/watch?v=';
			}
			if (strpos($url, '/watch?vi=') !== false){
				$val_split = '/watch?vi=';
			}

			$api_video = explode($val_split, $url);
			$video_id = end($api_video) ;
			if($strpos = strpos($video_id, '&')){
				$video_id = substr($video_id, 0, $strpos);	
			}
			if($strpos = strpos($video_id, '?')){
				$video_id = substr($video_id, 0, $strpos);								
			}							
		}else if(strpos($url, 'dai') !== false){
			$video_id =strtok(basename($url), '_');
			$type='dailymotion';								
		}
		if($video_id == '' || $type == ''){

			return 'false';
		}

		return array('video_id' => $video_id , 'type' => $type , 'url' => $url );

	}

	static function get_video_params($content, $all = false){
		$params_videos_return = array();
		$all_videos_params = array();
		if (preg_match_all(REG_VIDEO, $content, $all_matches, PREG_SET_ORDER)) {
			foreach ($all_matches as $matches) {
				$params_video = array();
				$post_video = $matches[1];
				$post_video_init = $matches[1];
				$param = '{}' ;
				$video_id = '' ;
				$val_split = '/';
				$and_feature="";
				if (strpos($post_video, 'youtu') !== false ) {
					$type='youtube';
					$val_split = (strpos($post_video, '/watch?v=') !== false) ? "/watch?v=" : "/";
					$and_feature="&feature=youtube_gdata";
				}else if(strpos($post_video, 'dai') !== false){
					$type='dailymotion';								
				}

				if (strpos($post_video, 'youtu') !== false || strpos($post_video, 'dai') !== false) {							
					$api_video = explode($val_split, $post_video);
					$video_id = end($api_video) ;
					if($strpos = strpos($video_id, '&')){
						$video_id = substr($video_id, 0, $strpos);								
					}	
					if($strpos = strpos($video_id, '?')){
						$video_id = substr($video_id, 0, $strpos);								
					}	
					$post_video = end($api_video);							
				}
				$params_video['type']=$type;
				$params_video['video_id']=$video_id;
				$params_video['post_video']=$post_video.$and_feature;
				$params_video['url']= $matches[1].$and_feature;;
				$params_video['link']= $matches[1];

				if( is_dev('seo_projet_integration_videos') && get_param_global('active_upload_videos') ){
					$provider = '';
					if (strpos($post_video_init, 'dai') !== false) {
						$provider = 'dailymotion';
					}else if (strpos($post_video_init, 'youtu') !== false) {
						$provider = 'youtube';
					}
					$video_url_server = get_valid_url_video($video_id, $provider);
					$params_video['link_server']= $video_url_server;
				}
				$all_videos_params[] = $params_video;
			}
			$params_videos_return = $all ? $all_videos_params : $all_videos_params[0];
		}
		return $params_videos_return;
	}

	static function getSSLPage($url) {
		if(!function_exists('curl_init')){
			return  false ;
		}
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_SSLVERSION,3); 
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	    $result = curl_exec($ch);
	    curl_close($ch);
	    return $result;
	}

	static function get_info_viedo($video_id, $provider, $from_mobile = false, $force = false){
		$return = false;
		if (isset($_GET["force"])) {	
			$force = $_GET["force"];								
		}
		if($provider == 'youtube'){
			$return = array();
			$objet = get_transient( 'video_json_V3_'. $provider .'_' . $video_id );
			if(empty($objet) AND $force){ 
				$API_KEY_YT = get_param_global('API_KEY_YouTube', "AIzaSyA1Uv0pHC8O221OJ69ZmEA4oF-bsfITkWk");
	
				$newUrl = "https://www.googleapis.com/youtube/v3/videos?id=$video_id&key=$API_KEY_YT&part=snippet,contentDetails,status";
				$json = getSSLPage($newUrl);
				$objet = json_decode($json);
				if((empty($objet->items) || empty($objet->items[0]))){
					$t_trans = 60*10 ;
					$json = '{}';
				}else{
					$t_trans = 60*60*24*30*6; ;
				}
				set_transient( 'video_json_V3_'. $provider .'_' . $video_id, $json, $t_trans);
				//get_video_img($video_id, $provider, true);
			}
			if(is_dev('seo_propriete_itemprop_manquant_101198852')){
				if(!is_object($objet)){
					$objet = json_decode($objet);
				}
				if(empty($objet->items) || empty($objet->items[0])){
					return false;
				}
			}else{
				if(empty($objet->items) || !empty($objet->items[0])){
					return false;
				}
			}
			$item = $objet->items[0];
			$duration = $item->contentDetails->duration;
			$return ['name'] = $item->snippet->title;
			$return ['description'] = $return ['caption'] = $item->snippet->description;

			preg_match('/PT([0-9]+)M([0-9]+)S/', $duration, $matches);
			$m = (count($matches) > 0)? $matches[1]:0;
			$s = (count($matches) > 1)? $matches[2]:0;

			if(!$from_mobile)
				$return ['duration'] = "T{$m}M{$s}S";
			else
				$return ['duration'] = ($m*60)+$s;

			$return['contentUrl'] = "https://youtube.googleapis.com/v/" . $video_id ; 

			//ajout du meta uploadDate
			 if(is_dev('seo_propriete_itemprop_manquant_101198852')){
			 	if($item->snippet){
			 		$snippet = $item->snippet;
			 		if($snippet){
			 			$uploadDate = $snippet->publishedAt;
			 			if($uploadDate){
			 				$date_upload = strtotime($uploadDate);
							$correct_format = date(DATE_ATOM,$date_upload);
			 				$return['uploadDate']  = $correct_format;
			 			}
			 		}
			 	}
			 }

			return $return ;
		}elseif($provider == 'dailymotion'){
			$json = get_transient( 'video_json_'. $provider .'_' . $video_id );
			if(!$json AND $force){
				if(is_dev('seo_propriete_itemprop_manquant_101198852')){
					$json = file_get_contents('https://api.dailymotion.com/video/'. $video_id .'?fields=title,duration,description,created_time') ;
				}else{	
					$json = file_get_contents('https://api.dailymotion.com/video/'. $video_id .'?fields=title,duration,description') ;
				}
				if(!$json){
					$json = '{}' ;
					$t_trans = 60*10 ;
				}else{
					$t_trans = 60*60*24*30*6 ;
				}
				set_transient('video_json_'. $provider .'_' . $video_id, $json, $t_trans );
			}
			$return = array();
			$objet = json_decode($json);
			$duration = $objet->duration;
			$m = floor( $duration / 60 );
			$s = $duration % 60 ;

			if(is_dev('seo_propriete_itemprop_manquant_101198852')){
				if($objet){
					$date_created = date(DATE_ATOM, $objet->created_time);
					$return ['uploadDate'] =  $date_created;
				}
			}

			$return ['name'] =  $objet->title;
			$return ['duration'] = "T{$m}M{$s}S";
			$return ['description'] = $objet->description;
			$return ['caption'] = $objet->description ;
			if(is_dev('seo_projet_integration_videos') && get_param_global('active_upload_videos') ){
				/* Projet intégration des videos */
				$video_url_server = get_valid_url_video($video_id, 'dailymotion');
				if($video_url_server){
					$return ['contentUrl'] = $video_url_server;
				}else{
					 // maintain old contentUrl type 
					$return ['contentUrl'] = 'http://www.dailymotion.com/embed/video/' . $video_id ;
				}
				
			}else{
				$return ['contentUrl'] = 'http://www.dailymotion.com/embed/video/' . $video_id ;
			}
			wp_enqueue_script('reworldmedia-swfobject', get_template_directory_uri() . '/assets/javascripts/swfobject.js', array('jquery'), '', true);
			return $return ;
		}else{
			global $post;
			$return = array(
				'uploadDate' => $post->post_date,
				'name' => $post->post_title,
				'description' => $post->post_excerpt,
				'caption' => $post->post_excerpt,
				'contentUrl' => $video_id
			);
			return $return ;
		}
	}

	static function get_youtube_img_by_id ($videoid) {
	    $resolutions = array('maxresdefault', 'hqdefault', 'mqdefault');     
	    foreach($resolutions as $res) {
	        $imgUrl = "http://i.ytimg.com/vi/$videoid/$res.jpg";
	        if(@getimagesize(($imgUrl))) 
	            return $imgUrl;
	    }
		return '';
	}

	static function get_video_img($video_id, $provider, $force = false){


		if($video_id && $provider){
			$path =  WP_CONTENT_DIR.'/uploads/img_video/'. $provider .'/'  ;
			if(!file_exists($path)){
				mkdir ( $path, 0777, true );
			}
			$file = $path . $video_id .'.jpg'  ;
			$url = home_url() . '/wp-content/uploads/img_video/'. $provider .'/' . $video_id .'.jpg'  ;
			if(file_exists($file)){ 
				return $url ;
			}elseif($force){
				$src_img = '';
				if('youtube' == $provider){
					$src_img =  get_youtube_img_by_id($video_id) ;
				}elseif('dailymotion' == $provider){
					$video_id = strtok(basename($video_id), '_');
					$src_img = SITE_SCHEME."://www.dailymotion.com/thumbnail/video/$video_id" ;
				}else{
					global $post;
					return get_the_post_thumbnail_url($post);
				}
				$src_img_response = self::get_http_response_code( $src_img ); 
				if($src_img && ( $src_img_response == 200 || $src_img_response == 301 )  ){
					return $src_img ;
					$content =file_get_contents($src_img);
					if($content){
						file_put_contents($file, $content);
						return $url ;

					}else{
						global $post;
						return get_the_post_thumbnail_url($post);
					}
					$src_img_response = self::get_http_response_code( $src_img ); 
					if($src_img && ( $src_img_response == 200 || $src_img_response == 301 )  ){
						$content =file_get_contents($src_img);
						if($content){
							file_put_contents($file, $content);
							return $url ;
						}else{
							return '' ;
						}

					}
				}
			}
			if(!file_exists($file)){
				$file = false  ;
			}
		}
		return false;
	}

	static function pretty_echo($var) {
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
	}

	static function print_flush($s){
		echo $s;
		ob_flush();
		flush();
	}
	static function print_r_flush($s){
		print_r($s);
		ob_flush();
		flush();
	}

	static function multiexplode ($delimiters,$string) {   
	    $ready = str_replace($delimiters, $delimiters[0], $string);
	    $launch = explode($delimiters[0], $ready);
	    return  $launch;
	}

	static function starts_with($haystack, $needle) {
	    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}

	static function ends_with($haystack, $needle) {
	    // search forward starting from end minus needle length characters
	    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
	}

	//supprime les accents upload images
	static function rw_remove_accents($s){ 	
		$s =  remove_accents($s)  ;	
		$s =  $username = preg_replace( '|[^a-z0-9 _.\-@]|i', '', $s ); ;	
		return $s ;
	}

	/**
	* Calculer l'age à partir de la date de naissance
	* php 5.3 +
	*By gounane@webpick.info
	*/
	static function age_calculator($birthday){
		if(strtotime($birthday)){
			return date_create($birthday)->diff(date_create('today'))->y;
		}else{
			return 0;
		}
	}

	//Vérification du site PV #109024170/#106089964
	static function verification_redirect_link($slug) {
		global $wp_query;
		$rocket_links_uri = '/'. $slug .'.html';
		if ($wp_query->is_404) {
			$url = $_SERVER['REQUEST_URI'];
			if($url == $rocket_links_uri) {
				echo $slug;
				exit();
			}
		}
	}

	static function is_dailymotion_video($video_url) {
	    return preg_match(DAILY_LONG, $video_url) || preg_match(DAILY_SHORT, $video_url);
	}

	static function get_source_video_id_by_content($content){
		preg_match (  REG_VIDEO , $content , $matches );
		$video_id= $matches[1] ;
		$video_info=array();
		if($video_id){
			$video_info['id']=$video_id;
			if (strpos($video_id, 'dai')!== false){
				$video_info['source']='dailymotion';
			}elseif(strpos($video_id, 'youtu')!== false){
				$video_info['source']='youtube';
			}
		}
		return $video_info;
	}

	static function mb_convert_encoding( $text ){
		return mb_convert_encoding(html_entity_decode($text), 'UTF-16LE', 'UTF-8');
	}

	/**
	* Retourne le code de response de l'url : 301, 200, 404, ...
	* @param String
	* @return int
	*/

	static function get_http_response_code( $theURL ) {
	    $header = 0;
	    if(!empty($theURL)){
	    	$headers = get_headers($theURL);
	    	$header = substr($headers[0], 9, 3);
	    }
	    return $header;
	    
	}

	static function get_age($birthdate, $exact = false){
		$age = 0;
		if (!empty($birthdate)) {
			$birthdate = str_replace('/', '-', $birthdate);
			$age = ( time() - strtotime($birthdate) ) / 3600 / 24 / 365;
			$age = $exact ? $age : (int) $age;
		}
		return $age;
	}

	static function share_total_format($shares){
		global $post;
		$html = '';
		$total_shares = get_shared_total($shares);
		$total_shares = get_total_shared_post( $post->ID, $total_shares ,10 );
		if($total_shares) {
			$html = '<div class="total-shares">
						<em>'.$total_shares.'</em>
						<span>'. __("partages", REWORLDMEDIA_TERMS) .
					'</span></div>';
		}
		return $html;
	}

	static function storeapp(){
		global $site_config;
		$apple_store_id=isset($site_config['apple_store_id']) ? $site_config['apple_store_id'] :'';
		$meta = ($apple_store_id!='') ? "<meta name=\"apple-itunes-app\" content=\"app-id=$apple_store_id\" />\n" : '';
		return $meta ;
	}

	static function rw_get_nav_menu_cache_key($args){
		if( !defined('WP_MENU_TIMEOUT_CACHE')){
			$key = wp_cache_get( 'key', 'wp_nav_menu' ) ;
		    if ( !empty( $key ) ){
		    	$key = time() ;
		    	wp_cache_set('key' , $key , 'wp_nav_menu' );
		    }
		    define('WP_MENU_TIMEOUT_CACHE' , $key);
		}
	    return apply_filters( 'nav_menu_cache_key' ,  md5( serialize( $args ) ) . WP_MENU_TIMEOUT_CACHE  );
	}

	static function init_scripts_playlist(){
		if(is_dev('mise_en_place_jw7_151445890')){
			init_jwplayer7();
		}else if( is_dev( 'passage_du_player_sur_jw6_111776366' ) ){
			init_jwplayer();
		}else{
			init_video_js();
			init_videojs_vast();
		}
		wp_enqueue_script('playlist_post', get_template_directory_uri().'/assets/javascripts/playlist_post'.JS_EXT, array( 'jquery'), CACHE_VERSION_CDN, true );
	}

	static function display_header_image() {
		$with_image = false;
		if(is_single()){
			$term =  get_post_category_from_url();
			$image_id = get_taxonomy_option('custom-header-image', 'custom-header-image', $term->term_id);
		}else{
			$image_id = get_taxonomy_option('custom-header-image', 'custom-header-image');
		}
		
		if ($image_id!='') {
			$image = wp_get_attachment_image_src($image_id, 'full');
			if ($image!==false) {
				?><div class="mag"><img src="<?php echo $image[0]; ?>" class="imgmag"></div><?php
				$with_image = true;
			}
		}
		$sidebar_header = apply_filters('filter_all_sidebar', 'sidebar-header') ;
		if (!$with_image && is_active_sidebar($sidebar_header)) {
			dynamic_sidebar($sidebar_header);	
		}
	}

	static function show_img_accueil() {
		$content='';
		$content.='<div>';
		if(is_active_sidebar('sidebar-header')){ 
			$content.=get_dynamic_sidebar('sidebar-header'); 
		}
		$content.='</div>';

		return $content;
	}

	static function rw_load_template($tmp){
		ob_start();
		include($tmp);
		$content = ob_get_clean();
		return $content ;
	}

	static function upload_img($src_img, $title='', $description='' , $post_id='',$local_file=false){	
		if(!$local_file){
			$tmp = download_url( $src_img );
		}else{
			$tmp = $src_img;
		}	
		$file_array = array();
		preg_match('/[^\?]+\.(jpe?g|jpe|gif|png|svg)/i', $src_img, $matches);
		$file_array['name'] =  basename($matches[0]);
		$file_array['tmp_name'] = $tmp;

		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';
		}
		// do the validation and storage stuff
		$attach_id = media_handle_sideload( $file_array, $post_id , $description, array('post_title' => $title, 'post_excerpt' => $description));
		return $attach_id ;
	}

	static function array_to_options($tab) {
		$options="";
		foreach ($tab as $row) {
			$options.='<option value="'.$row.'">'.$row.'</option>';
		}
		return $options;
	}

	static function XML2Array( $parent) {
		 
		if(is_object($parent) && get_class($parent) == 'SimpleXMLElement'){
	    	$array = (array)$parent;
		}else{
	    	$array = $parent;
		} 
	    foreach ($array as $key => &$value) { 
	    	if(is_array($value) OR (is_object($value) && get_class($value)  == 'SimpleXMLElement')){
				$array[$key] = self::XML2Array($value);
	    	}
	    }
	    
	    return $array;
	}

	// Parser le fichier CSV
	static function parse_csv($file) {
		$r = array() ;
		if (($handle = fopen($file, "r")) === FALSE){
			print_flush("\npermission denied\n");
			exit();
		}
		while (($cols = fgetcsv($handle, 10000, ";")) !== FALSE) {
			foreach( $cols as $key => $val ) {
				$cols[$key] = iconv('ISO-8859-2', 'UTF-8', trim( $cols[$key] )) ;
			}
			$r[] = $cols ;
		}
		return $r ;
	}

	/**
	 * Get current url wp
	 *
	 * @return     string   ( current url )
	 */
	static function get_current_url() {
		global $wp;
		$current_url = home_url(add_query_arg(array(),$wp->request));
		return $current_url;
	}
    
    /**
     ** add resource hints to browsers for pre-fetching, pre-rendering,
     * and pre-connecting to web sites.
     *
     * Gives hints to browsers to prefetch specific pages or render them
     * in the background, to perform DNS lookups or to begin the connection
     * handshake (DNS, TCP, TLS) in the background.
     *
     * These performance improving indicators work by using `<link rel"…">`.
     * @param $urls : array of urls to pre-fetch, pre-connect, pre-render ...
     * @param $relation_type : The relation type the URLs are printed for, e.g. 'preconnect' or 'dns-prefetch'.
     *
     * TODO : Replace with wp_resource_hints of WP
     */
    static function add_resource_hint( $urls, $relation_type ){
        foreach ( $urls as $url){
            echo '<link rel='.$relation_type.' href='.$url.' />';
        }
    }

    /**
     * Adds a value to array.
     *
     * @param      <String|int>  $value  The value
     * @param      array   $array  The array
     *
     * @return     array   ( new array )
     */
	static function add_value_to_array($value, $array){
		if(!is_array($array)) 
			$array = array();
		if($value && !in_array($value, $array)){
			$array[] = $value;
		}
		return $array;
	}

	/**
	* Purge given URL
	*
	* @param string $url
	*/
	static function purge_url( $url ){

		$parse_url = parse_url($url) ;
		$home_url = $parse_url['scheme'] .'://' .$parse_url['host'].'/' ;
		$url_to_purge = str_replace($home_url, $home_url . 'purge/', $url ) ;

		wp_remote_get($url_to_purge);
		$ch = curl_init($url_to_purge);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if(is_dev()){
			curl_setopt($ch, CURLOPT_USERPWD, 'reworldmedia:reworldmedia');
		}
		curl_exec($ch);
		curl_close($ch);
	}

	static function get_img_top_article_id(){

		$img_top_article_id = apply_filters('img_top_article_id', false);

		if($img_top_article_id == false){
			$img_top_article_id = get_post_thumbnail_id(get_the_ID());
		}

		return $img_top_article_id ;

	}
	static function meks_wp_parse_args( &$a, $b ) {
		$a = (array) $a;
		$b = (array) $b;
		$result = $b;
		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && isset( $result[ $k ] ) ) {
				$result[ $k ] = RW_Utils::meks_wp_parse_args( $v, $result[ $k ] );
			} else {
				$result[ $k ] = $v;
			}
		}
		return $result;
	}


	/**
	 * [str_word_count_utf8 description]
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	static function str_word_count_utf8($text) {

		$text = $text  . "\n";
	   
		// Replace all HTML with a new-line.
		//text = text.replace( this.settings.HTMLRegExp, '\n' );
		$text = preg_replace('/<\\/?[a-z][^>]*?>/i', "\n" , $text  );
		//echo $text; die;
		// Remove all HTML comments.
		//text = text.replace( this.settings.HTMLcommentRegExp, '' );
		$text = preg_replace('/<!--[\s\S]*?-->/', '' , $text  );
		
		// If a shortcode regular expression has been provided use it to remove shortcodes.
		//text = text.replace( this.settings.shortcodesRegExp, '\n' );
		$text = preg_replace('/\[\/?(?:wp_caption|caption|playlist|audio|video|embed|acf|wpseo_breadcrumb|wpseo_sitemap|planning_gp|nl_grand_prix|lire_aussi|derniers_fiches_publies|dossiers_a_suivre|last_questions_forum|comparatif|a_lire_sur_automoto|bloc_a_lire|social_media_fan_count|plan_du_site|fpvideo|jwplayer|ligatus|mediabong|push_top_popular|outbrain|outbrain_generator|outbrain_lire_sur_le_web|outbrain_same_subject|social_links|social_links_single|smartadserver|post_most_popular|post_most_poplar|post_most_popular_video|post_most_popular_gallery|descpage|mag|feed_site|applications|showimage|page_redirect|likebox|newsletter|public_recipe|iframe|html|grooveshark|headermag|footerdesc|last_post|product_url|inBoard|inPicture|inRead|himediads_fullscreen|block_tv_pub|instagram_box|instagram|beforeafter|tag_config|videoMediabong|battle|img|PixelMediapost|PixelAcxiom|must_popular_video|widget_product|simple_addthis_single|addthis|link|remarketing|partner_script|share_facebook_post|add_ligatus_smartbox|popular_videos_list|popular_dossiers|pinterest|get_it_here|qualifio|recent_posts|encre_from|encre_to|sommaire_dossier|mobile_content|desktop_content|review_this_product|traking_url_tlc|diapo_shoppable|more_info|sociallinks_single|posts_mobile|newsletter_diapo|nl_welcome|post_link|innity_analytic|is_not_mobile_shortcode|inner_blocks|page_newsletter|media_slideshow|gallery|menufooter|menufooterpages|nl_article|loop_nl_articles|show_nl_bloc_articles|last_two_nl_videos|last_two_nl_popular|traking_links|tracking_content|nl_post_populaire|nl_preheader|json_cache_maillage_inter|sam|ligatus_block_right)[^\]]*?\]/', "\n" , $text  );



		// Normalize non-breaking space to a normal space.
		//text = text.replace( this.settings.spaceRegExp, ' ' );
		$text = preg_replace('/&nbsp;|&#160;/i', ' ' , $text  );


		// text = text.replace( this.settings.HTMLEntityRegExp, '' );
		$text = preg_replace('/&\S+?;/', '' , $text  );


		// // Convert connectors to spaces to count attached text as words.
		// text = text.replace( this.settings.connectorRegExp, ' ' );
		//$text = preg_replace('/--|\u2014/g', ' ' , $text  );

		// // Remove unwanted characters.
		// text = text.replace( this.settings.removeRegExp, '' );
		
		$pattern = array("'é'", "'è'", "'ë'", "'ê'", "'É'", "'È'", "'Ë'", "'Ê'", "'á'", "'à'", "'ä'", "'â'", "'å'", "'Á'", "'À'", "'Ä'", "'Â'", "'Å'", "'ó'", "'ò'", "'ö'", "'ô'", "'Ó'", "'Ò'", "'Ö'", "'Ô'", "'í'", "'ì'", "'ï'", "'î'", "'Í'", "'Ì'", "'Ï'", "'Î'", "'ú'", "'ù'", "'ü'", "'û'", "'Ú'", "'Ù'", "'Ü'", "'Û'", "'ý'", "'ÿ'", "'Ý'", "'ø'", "'Ø'", "'œ'", "'Œ'", "'Æ'", "'ç'", "'Ç'");

		$replace = array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E', 'a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'A', 'o', 'o', 'o', 'o', 'O', 'O', 'O', 'O', 'i', 'i', 'i', 'I', 'I', 'I', 'I', 'I', 'u', 'u', 'u', 'u', 'U', 'U', 'U', 'U', 'y', 'y', 'Y', 'o', 'O', 'a', 'A', 'A', 'c', 'C'); 

		$text = preg_replace($pattern, $replace, $text);

		//echo  $text ; die;

		$text = preg_replace("/[^a-z \n]/i", "", $text);




		// Match with the selected type regular expression to count the items.
	    $r  = preg_match_all("/\S\s+/", $text, $matches) ; 
	   
	   return $r ; 
	       
	}
	//added function for pagination
	static function custom_link_pages( $args = '' ) {
	
		global $page, $numpages, $multipage, $more;

		//variables
		$start = 1;
		$number_of_pages_todisplay = $numpages;


		$defaults = array(
			'before'           => '<p class="post-nav-links">' . __( 'Pages:' ),
			'after'            => '</p>',
			'link_before'      => '',
			'link_after'       => '',
			'aria_current'     => 'page',
			'next_or_number'   => 'number',//next | number | next_and_number
			'separator'        => ' ',
			'nextpagelink'     => __( 'Next page' ),
			'previouspagelink' => __( 'Previous page' ),
			'pagelink'         => '%',
			'echo'             => 1,
			'number_of_pages_todisplay' => 0,
		);

		$params = wp_parse_args( $args, $defaults );

		$r = apply_filters( 'wp_link_pages_args', $params );

		//test if mode is mixet : numbers and Prev, next linkes
		if($r['next_or_number']==='next_and_number')
		{

			//calculate start and end of loop => number of linkes to display
			$number_of_pages_todisplay = $page +$r['number_of_pages_todisplay'];

			$start = $page > 1 ? $page -1 : $page;

			if($number_of_pages_todisplay > $numpages)
			{
				$number_of_pages_todisplay = $numpages;
			}
			
			//display the next and prev buttons
			if($page-1) # there is a previous page
		        $r['before'] .= _wp_link_page($page-1)
		            . $r['link_before']. $r['previouspagelink'] . $r['link_after'] . '</a>'
		        ;

        	if ($page<$numpages) # there is a next page
		        $r['after'] = _wp_link_page($page+1)
		            . $r['link_before'] . ' ' . $r['nextpagelink'] . $r['link_after'] . '</a>'
		            . $r['after'];
		}


		$output = '';
		if ( $multipage ) {
			if ( 'number' == $r['next_or_number'] || $r['next_or_number']==='next_and_number') {
				$output .= $r['before'];
				for ( $i = $start; $i <= $number_of_pages_todisplay; $i++ ) {
					$link = $r['link_before'] . str_replace( '%', $i, $r['pagelink'] ) . $r['link_after'];
					if ( $i != $page || ! $more && 1 == $page ) {
						$link = _wp_link_page( $i ) . $link . '</a>';
					} elseif ( $i === $page ) {
						$link = '<span class="post-page-numbers current" aria-current="' . esc_attr( $r['aria_current'] ) . '">' . $link . '</span>';
					}
					
					$link = apply_filters( 'wp_link_pages_link', $link, $i );

					// Use the custom links separator beginning with the second link.
					$output .= ( 1 === $i ) ? ' ' : $r['separator'];
					$output .= $link;
				}
				$output .= $r['after'];
			} elseif ( $more ) {
				$output .= $r['before'];
				$prev    = $page - 1;
				if ( $prev > 0 ) {
					$link = _wp_link_page( $prev ) . $r['link_before'] . $r['previouspagelink'] . $r['link_after'] . '</a>';

					/** This filter is documented in wp-includes/post-template.php */
					$output .= apply_filters( 'wp_link_pages_link', $link, $prev );
				}
				$next = $page + 1;
				if ( $next <= $numpages ) {
					if ( $prev ) {
						$output .= $r['separator'];
					}
					$link = _wp_link_page( $next ) . $r['link_before'] . $r['nextpagelink'] . $r['link_after'] . '</a>';

					/** This filter is documented in wp-includes/post-template.php */
					$output .= apply_filters( 'wp_link_pages_link', $link, $next );
				}
				$output .= $r['after'];
			}
		}

		/**
		 * Filters the HTML output of page links for paginated posts.
		 *
		 * @since 3.6.0
		 *
		 * @param string $output HTML output of paginated posts' page links.
		 * @param array  $args   An array of arguments.
		 */
		$html = apply_filters( 'wp_link_pages', $output, $args );

		if ( $r['echo'] ) {
			echo $html;
		}
		return $html;
	}
	/*
	* Is current page AMP endpoint
	*/
	static function is_amp(){
	    return (function_exists('is_amp_endpoint') && is_amp_endpoint()) ? true : false ;
	}	


    static function upload_image_by_meta($image, $title, $desc, $post_id, $meta_key, $meta_value, $local_file = false){
        $args = array(
            'post_type'   => 'attachment',
            'post_status' => 'inherit',
            'meta_query'  => array(
                array(
                    'key'     => $meta_key,
                    'value'   => $meta_value
                )
            )
        );
        $posts = get_posts($args) ;
        if(count($posts)){
            return $posts[0]->ID ;
        }
        $attach_id = RW_Utils::upload_img($image, $title, $desc, $post_id, $local_file) ;
        update_post_meta($attach_id, $meta_key, $meta_value);
        return $attach_id;
    }


	static function rw_regenerate_thumbnails($image) {

		// No timeout limit
		set_time_limit(0);
	
		// Don't break the JSON result
		//error_reporting(0);


		try {
            
			

			

			if ('attachment' != $image->post_type || 'image/' != substr($image->post_mime_type, 0, 6)) {
				throw new Exception(sprintf(__('Failed: %d is an invalid image ID.', 'force-regenerate-thumbnails'), $id));
        	}

			/*if (!current_user_can($this->capability)) {
				throw new Exception(__('Your user account does not have permission to regenerate images.', 'force-regenerate-thumbnails'));
        	}*/
            
            
            /**
			 * Fix for get_option('upload_path')
			 * Thanks (@DavidLingren)
			 * 
			 * @since 2.0.1
			 */
			$upload_dir = wp_upload_dir();
            
            // Get original image
            $image_fullpath = get_attached_file($image->ID);
            $debug_1 = $image_fullpath;
            $debug_2 = '';
            $debug_3 = '';
            $debug_4 = '';
            
            
            // Can't get image path
            if (false === $image_fullpath || strlen($image_fullpath) == 0) {
                
                // Try get image path from url
                if ((strrpos($image->guid, $upload_dir['baseurl']) !== false)) {
                    $image_fullpath = realpath($upload_dir['basedir'] . DIRECTORY_SEPARATOR . substr($image->guid, strlen($upload_dir['baseurl']), strlen($image->guid)));
                    $debug_2 = $image_fullpath;
                    if (realpath($image_fullpath) === false) {
                        throw new Exception(sprintf(__('The originally uploaded image file cannot be found at &quot;%s&quot;.', 'force-regenerate-thumbnails'), esc_html((string) $image_fullpath)));
                    }
                } else {
                    throw new Exception(__('The originally uploaded image file cannot be found.', 'force-regenerate-thumbnails'));
                }
                
			}
            
            // Image path incomplete
            if ((strrpos($image_fullpath, $upload_dir['basedir']) === false)) {
                $image_fullpath = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . $image_fullpath;
                $debug_3 = $image_fullpath;
            }
            
           

            // Image don't exists
            if (!file_exists($image_fullpath) || realpath($image_fullpath) === false) {
            
                // Try get image path from url
                if ((strrpos($image->guid, $upload_dir['baseurl']) !== false)) {
                    $image_fullpath = realpath($upload_dir['basedir'] . DIRECTORY_SEPARATOR . substr($image->guid, strlen($upload_dir['baseurl']), strlen($image->guid)));
                    $debug_4 = $image_fullpath;
                    if (realpath($image_fullpath) === false) {
                        throw new Exception(sprintf(__('The originally uploaded image file cannot be found at &quot;%s&quot;.', 'force-regenerate-thumbnails'), esc_html((string) $image_fullpath)));
                    }
                } else {
                    throw new Exception(sprintf(__('The originally uploaded image file cannot be found at &quot;%s&quot;.', 'force-regenerate-thumbnails'), esc_html((string) $image_fullpath)));
                }
                
        	}
            
            
            /**
             * Update META POST
             * Thanks (@norecipes)
             *
             * @since 2.0.2
             */
            update_attached_file($image->ID, $image_fullpath);


            // Results
        	$thumb_deleted = array();
        	$thumb_error = array();
        	$thumb_regenerate = array();

            
            // Hack to find thumbnail
            $file_info = pathinfo($image_fullpath);
            $file_info['filename'] .= '-';


            /**
         	 * Try delete all thumbnails
         	 */
            $files = array();
            $path = opendir($file_info['dirname']);

            if ( false !== $path ) {
                while (false !== ($thumb = readdir($path))) {
                    if (!(strrpos($thumb, $file_info['filename']) === false)) {
                        $files[] = $thumb;
                    }
                }
                closedir($path);
                sort($files);
            }
            foreach ($files as $thumb) {
                $thumb_fullpath = $file_info['dirname'] . DIRECTORY_SEPARATOR . $thumb;
                $thumb_info = pathinfo($thumb_fullpath);
            	$valid_thumb = explode($file_info['filename'], $thumb_info['filename']);
        	    if ($valid_thumb[0] == "") {
        	       	$dimension_thumb = explode('x', $valid_thumb[1]);
        	       	if (count($dimension_thumb) == 2) {
        	       		if (is_numeric($dimension_thumb[0]) && is_numeric($dimension_thumb[1])) {
        	       			unlink($thumb_fullpath);
        	       			if (!file_exists($thumb_fullpath)) {
        	       				$thumb_deleted[] = sprintf("%sx%s", $dimension_thumb[0], $dimension_thumb[1]);
        					} else {
        						$thumb_error[] = sprintf("%sx%s", $dimension_thumb[0], $dimension_thumb[1]);
        					}
        	       		}
        	       	}
        	    }
            }
            

            /**
             * Regenerate all thumbnails
         	 */
			$metadata = wp_generate_attachment_metadata($image->ID, $image_fullpath);
			if (is_wp_error($metadata)) {
				throw new Exception($metadata->get_error_message());
        	}
			if (empty($metadata)) {
				throw new Exception(__('Unknown failure reason.', 'force-regenerate-thumbnails'));
        	}
			wp_update_attachment_metadata($image->ID, $metadata);
            
            
            /**
             * Verify results (deleted, errors, success)
             */
            $files = array();
            $path = opendir($file_info['dirname']);
            if ( false !== $path ) {
                while (false !== ($thumb = readdir($path))) {
                    if (!(strrpos($thumb, $file_info['filename']) === false)) {
                        $files[] = $thumb;
                    }
                }
                closedir($path);
                sort($files);
            }
            foreach ($files as $thumb) {
            	$thumb_fullpath = $file_info['dirname'] . DIRECTORY_SEPARATOR . $thumb;
            	$thumb_info = pathinfo($thumb_fullpath);
            	$valid_thumb = explode($file_info['filename'], $thumb_info['filename']);
        	    if ($valid_thumb[0] == "") {
        	       	$dimension_thumb = explode('x', $valid_thumb[1]);
        	       	if (count($dimension_thumb) == 2) {
        	       		if (is_numeric($dimension_thumb[0]) && is_numeric($dimension_thumb[1])) {
        	       			$thumb_regenerate[] = sprintf("%sx%s", $dimension_thumb[0], $dimension_thumb[1]);
        	       		}
        	       	}
        	    }
            }
			

			// Remove success if has in error list
           	foreach ($thumb_regenerate as $key => $regenerate) {
           		if (in_array($regenerate, $thumb_error))
           			unset($thumb_regenerate[$key]);
           	}            

            // Remove deleted if has in success list
           	foreach ($thumb_deleted as $key => $deleted) {
           		if (in_array($deleted, $thumb_regenerate))
           			unset($thumb_deleted[$key]);
           	}


            /**
             * Display results
             */
            $message  = sprintf(__('<b>&quot;%s&quot; (ID %s)</b>', 'force-regenerate-thumbnails'), esc_html(get_the_title($id)), $image->ID);
			

			$message .= "\n\n";
			$message .= sprintf(__("<code>BaseDir: %s</code>\n", 'force-regenerate-thumbnails'), $upload_dir['basedir']);
			$message .= sprintf(__("<code>BaseUrl: %s</code>\n", 'force-regenerate-thumbnails'), $upload_dir['baseurl']);
			$message .= sprintf(__("<code>Image: %s</code>\n", 'force-regenerate-thumbnails'), $debug_1);
			if ($debug_2 != '')
				$message .= sprintf(__("<code>Image Debug 2: %s</code>\n", 'force-regenerate-thumbnails'), $debug_2);
			if ($debug_3 != '')
				$message .= sprintf(__("<code>Image Debug 3: %s</code>\n", 'force-regenerate-thumbnails'), $debug_3);
			if ($debug_4 != '')
				$message .= sprintf(__("<code>Image Debug 4: %s</code>\n", 'force-regenerate-thumbnails'), $debug_4);

			if (count($thumb_deleted) > 0) {
				$message .= sprintf(__("\nDeleted: %s", 'force-regenerate-thumbnails'), implode(', ', $thumb_deleted));	
			}
			if (count($thumb_error) > 0) {
				$message .= sprintf(__("\n" .'<b><span style="color: #DD3D36;">Deleted error: %s</span></b>', 'force-regenerate-thumbnails'), implode(', ', $thumb_error));
				$message .= sprintf(__("\n" .'<span style="color: #DD3D36;">Please, check the folder permission (chmod 777): %s</span>', 'force-regenerate-thumbnails'), $upload_dir['basedir']);
			}
			if (count($thumb_regenerate) > 0) {
				$message .= sprintf(__("\n" .'Regenerate: %s</span>', 'force-regenerate-thumbnails'), implode(', ', $thumb_regenerate));
				if (count($thumb_error) <= 0) {
					$message .=	sprintf(__("\n" .'Successfully regenerated in %s seconds', 'force-regenerate-thumbnails'), timer_stop());
				}
			}


			println_flush( $message );
			

				
		} catch (Exception $e) {
			//$this->die_json_failure_msg($id, '<b><span style="color: #DD3D36;">' . $e->getMessage() . '</span></b>');
			println_flush( $e->getMessage() );
		}
	}

	/**
	 *	Retourne la version française de la date anglaise
	 *  @param string $date
	 *  @return string $date
	 */
	public static function get_date_in_frensh($date) {
		$mouths_en = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		$days_en = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
		$months_fr = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
		$days_fr = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
		$date = str_replace($mouths_en, $months_fr, $date);
		$date = str_replace($days_en, $days_fr, $date);
		return $date;
	}

	public static function get_id_pub_video($atts){
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
}
