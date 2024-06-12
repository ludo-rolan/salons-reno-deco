<?php
class RW_Hooks {

	private static $_instance;

	function __construct(){
		$this->init_hooks();
	}
	
	static function get_instance(){
		if(is_null(self::$_instance)){
			self::$_instance = new RW_Hooks();
		}
		return self::$_instance;
	}
	
	function init_hooks(){

		add_action( 'wp_head', array( 'RW_Hooks', 'wp_head' ) );
		if(is_dev('quiz_et_test_en_hp_opti_du_cache_144924831')){
			add_filter( 'rw_query_filter', array( 'RW_Hooks', 'rw_force_cache_queries' ), 100, 1 );
		}

		// wp_link_pages() : Add prev and next links to a numbered link list
		add_filter( 'wp_link_pages_args', array( $this, 'wp_link_pages_args_first_last_add_class' ) );

		add_filter( 'wp_calculate_image_srcset_meta', array( $this, 'desable_wp_calculate_image_srcset_meta' ), 100 );

		if ( get_param_global( 'hide_bloc_author_post' ) ) {
			add_action( 'save_post', array( $this, 'change_meta_on_publish_post_save' ), 14 );
		}

		if ( is_dev( 'masquer_le_footer_crm_sur_mobile_148665881' ) ) {
			add_filter( 'hide_nl_footer', array( $this, 'hide_nl_footer_crm' ) );
		}
		if( get_param_global('Lock_event_in_category_page') ){
			add_filter( 'modify_posts_list', array( $this, 'modify_posts_list' ), 1, 2);
		}
	}

	///////////////////////////////////////////////////////////////////////////
	////////////////////////////// FUNCTIONS //////////////////////////////////
	///////////////////////////////////////////////////////////////////////////


	function desable_wp_calculate_image_srcset_meta ($r){
		return false ;
	}


	/**
	 * filter_rest_api_access 
	 * 
	 * filter forced to disable the access to REST API 
	 *   if you need to enable it again, delete the || true in the condition, in this case returns an authentication 
	 *   error if a user who is not logged in tries to query the REST API
	 * @param  $access
	 * @return WP_Error
	 */
	static function filter_rest_api_access( $access ) {
	    $error = false;
	    if( !is_user_logged_in() && empty($_GET['api_key'])) {
	        $error = true;
	    }
	    $keys =['9c92c20e89ceb8db03faec66a2c47685'];
	    if( !in_array($_GET['api_key'], $keys) ){
	        $error = true;
	    }
	    if($error){
	        $access = new WP_Error( 'rest_cannot_access', __( 'Access to the REST API is disabled', 'disable-json-api' ), array( 'status' => rest_authorization_required_code() ) );
	    }
	    return $access;
	}


	static function rw_force_cache_queries($query){
		return preg_replace ( '/SELECT /' , 'SELECT SQL_CACHE ', $query, 1) ; 
	}


	static function show_mediabong() {
		echo do_shortcode('[mediabong]');
	}

	static function show_shortcode_outbrain(){
		// echo "what is happening here";
		echo shortcode_outbrain(array());
	}
	static function show_shortcode_ligatus(){
		echo shortcode_ligatus(array());
	}
	// end : elements to include afterinside single recipe

	static function default_after_single(){
		if(get_param_global('ligatus_before_outbrain')) {
	        echo do_shortcode('[ligatus]'); 
	        echo do_shortcode('[outbrain]');
	    } else {
	    	//do_action('before_outbrain');
	        echo do_shortcode('[outbrain]');
	        if(get_param_global('mediabong_between_outbrain_ligatus')) {
	        	echo do_shortcode('[mediabong]');
	        }
	        echo do_shortcode('[ligatus]'); 
	    }
	}

	static function sidebar_after_single(){
		$sidebar = 'after-single' ;
		$sidebar = apply_filters('filter_all_sidebar',$sidebar ,100);

	    if (is_active_sidebar($sidebar)) { 
	        dynamic_sidebar( $sidebar );
	    } 
	}

	static function sidebar_before_widget_ops(){
		$sidebar = 'before_widget_ops' ;
		$sidebar = apply_filters('filter_all_sidebar',$sidebar ,100);
	    if (is_active_sidebar($sidebar)) { 
	        dynamic_sidebar( $sidebar );
	    } 
	}

	static function before_wp_footer_(){
		if(is_dev('video_a_ne_pas_manquer_112137459')) {
			dynamic_sidebar('sidebar_popin_a_ne_pas_manquer');
		}else {
			echo do_shortcode('[must_popular_video]');
		}
	}

	static function after_wp_footer_(){
		echo do_shortcode('[addthis]');
		echo do_shortcode('[push_top_popular]');
		echo do_shortcode('[inBoard]'); 
		echo do_shortcode('[inPicture]');
		echo do_shortcode('[inRead]');
		echo do_shortcode('[himediads_fullscreen]');
		do_action('before_body_close');
	}


	static function apply_shortcode_to_ninjaforms($data, $field_id){
		
		$data['label'] = do_shortcode($data['label']);
		return $data;
	}

	static function metas_seo($datas) {
		//print_r($datas);
		//die('---');
		$title = isset($datas["title"]) ? $datas["title"] : '';
		$author = isset($datas["author"]) ? $datas["author"] : "";
		$sharedcount=isset($datas["sharedcount"]) ? $datas["sharedcount"] : array('Twitter' => '', 'Facebook' => '', 'GooglePlusOne' => '');
		$date_single = isset($datas["date_single"]) ? $datas["date_single"] : "";
		$date_update_single = isset($datas["date_update_single"]) ? $datas["date_update_single"] : "";
		$link = isset($datas["link"]) ? $datas["link"] : "";

		$sharedcount_twitter = isset($sharedcount["Twitter"]) ? $sharedcount["Twitter"] : 0; 
		$sharedcount_facebook = isset($sharedcount["Facebook"]) ? $sharedcount["Facebook"]["like_count"] : 0; 
		$sharedcount_GooglePlusOne = isset($sharedcount["GooglePlusOne"]) ? $sharedcount["GooglePlusOne"] : 0; 
		//wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		$thumbnail_url = isset($datas["thumbnail_url"]) ? $datas["thumbnail_url"] : "";
		$image = isset($datas["image"]) ? $datas["image"] : "";
		//print_r($sharedcount);die('--');
		echo '<meta itemprop="name" content="'.$title.'" />';
	    if(!is_dev('seo_correction_micro_donnees_articles_111394476')) { echo '<meta itemprop="author" content="'.$author.'" />'; } // Ticket ID : 111394476 : element deplacé en bas de articlebody
	    echo '<!--	Interactions - Récupérer ces informations via les API des différents services	-->';
	    echo '<meta itemprop="interactionCount" content="UserTweets:'.$sharedcount_twitter.'"/>';
	    echo '<meta itemprop="interactionCount" content="UserComments:'.get_comments_number().'"/>';
	    echo '<meta itemprop="interactionCount" content="UserLikes:'.$sharedcount_facebook.'"/>';
	    echo '<meta itemprop="interactionCount" content="UserPlusOnes:'.$sharedcount_GooglePlusOne.'"/>';
	    echo '<!--	/ Interactions	-->';
	    echo '<meta itemprop="keywords" content="'.get_meta_tag().'"/>';
	    if(!is_dev('seo_correction_micro_donnees_articles_111394476')) { echo '<meta itemprop="datePublished" content="'.$date_single.'"/>';} // Ticket ID : 111394476 : element deplacé en bas de articlebody
	    if(!is_dev('seo_correction_micro_donnees_articles_111394476')) { echo '<meta itemprop="dateModified" content="'.$date_update_single.'"/>';} // Ticket ID : 111394476 : element deplacé en bas de articlebody
	    
	    echo '<meta itemprop="contentLocation" content="'.get_locations() .'"/>';
	    echo '<meta itemprop="discussionUrl" content="'.$link.'#comments"/>';
	    echo '<meta itemprop="thumbnailUrl" content="'.$thumbnail_url.'" />';
		if(!is_dev('seo_correction_micro_donnees_articles_111394476')) { echo '<meta itemprop="image" content="'.$image.'" />'; } // Ticket ID : 111394476 : element deplacé en bas de articlebody
	}

	/*
	* Ticket ID : 111394476 : SEO Correction des micro données articles
	* Pour se conformer aux mise a jour de Google sur les micro donnes des articles et news
	* @author : Abdoulaye
	*/

	static function after_article_body_seo($datas) {
		global $post;
		$date_single = isset($datas["date_single"]) ? $datas["date_single"] : '';
		$date_update_single = isset($datas["date_update_single"]) ? $datas["date_update_single"] : '';
		$image = isset($datas["image"]) ? $datas["image"] : '';
		$image_path = isset($datas["image_path"]) ? $datas["image_path"] : '';
		$author = $datas["author"] ;
		// get size image in prod or dev
		if(is_dev()){
			$image_size = is_valid_url_image($image, $image_path) ? getimagesize($image) : false;
		}else{
			$image_size = is_valid_url_image($image, $image_path) ? getimagesize($image_path) : false;
		}
		
		$image_width = ( $image_size && count($image_size) > 0 ) ? $image_size[0] : '';
		$image_height = ( $image_size && count($image_size) > 1 ) ? $image_size[1] : '';
		$url_logo_site = get_param_global('logo_home_url', STYLESHEET_DIR_URI ."/assets/images-v2/main-logo.png" );
		$path_logo_site = str_replace('http://'.$_SERVER[ 'HTTP_HOST' ].'/', ABSPATH, $url_logo_site);
		$path_array = explode('?', $path_logo_site);
		$path_logo_site = $path_array[0];
		// get size image logo in prod or dev
		$logo_size = file_exists($path_logo_site) ? getimagesize($path_logo_site) : false;
		$logo_width = ( $logo_size && count($logo_size) > 0 ) ? $logo_size[0] : '';
		$logo_height = ( $logo_size && count($logo_size) > 1 ) ? $logo_size[1] : '';
		$name_site = get_bloginfo('name');
		$url_post = get_permalink($post);


		$html ='';
		$html .= '<!--  nouveaux éléments -->';
		$html .= '<div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">';
			$html .= '<meta itemprop="url" content="'.$image.'">';
			$html .= '<meta itemprop="width" content="'.$image_width.'">';
			$html .= '<meta itemprop="height" content="'.$image_height.'">';
		$html .= '</div>';
		
		$html .= '<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">';
			$html .= '<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">';
				$html .= '<meta itemprop="url" content="'.$url_logo_site.'">';
				$html .= '<meta itemprop="width" content="'.$logo_width.'">';
				$html .= '<meta itemprop="height" content="'.$logo_height.'">';
			$html .= '</div>';
			$html .= '<meta itemprop="name" content="'.$name_site.'">';
		$html .= '</div>';
		$html .= '<meta itemprop="datePublished" content="'.$date_single.'"/>';
		$html .= '<meta itemprop="dateModified" content="'.$date_update_single.'"/>';
		$html .= '<meta itemprop="headline" content="' . addslashes(get_the_title()) .'" />' ;
		if( !get_param_global('hide_author_meta_after_article_body_seo') )
			$html .= '<meta itemprop="author" content="'. $author .'" />' ;
		$html .= '<meta itemscope itemprop="mainEntityOfPage" content="" itemType="https://schema.org/WebPage" itemid="'.$url_post.'"/>';


		$html .= '<!-- / nouveaux éléments -->';

	    echo $html;
	}

	static function is_valid_url_image($img_url, $img_path){
		if(is_dev()){
			$headers = !empty($img_url) ? get_headers($img_url) : false;
			return ( $headers && count($headers) && strpos($headers[0], '404') === false );
		}else{
			return file_exists($img_path);
		}
		
	}

	static function before_title_seo(){
		global $post;
		$url_post = get_permalink($post);
		$html = '';
		$html .= '<meta itemscope itemprop="mainEntityOfPage" content="" itemType="https://schema.org/WebPage" itemid="'.$url_post.'"/>';
		echo $html;
	}

	/* Ajouter la propriete itemprop=description dans le chapo de l article */
	static function excerpt_seo( $excerpt ) {
		if(is_single() && has_excerpt()){
			$excerpt = str_replace( '<p>', '<p itemprop="description">', $excerpt );
		}
		return $excerpt;
	}

	/*
	* End Ticket ID : 111394476
	*/

	static function additional_block_archive($current_cat,$display_parent_cat) {
		global $site_config, $parent_cat;

			if ($display_parent_cat) { 
	        	if(get_param_global('enable_block_cache')){
		        	if($current_cat->slug){
			        	$cache_key = 'block_archive_current_cat_'.$current_cat->slug;
			        }else {
			        	$cache_key = 'block_archive_current_cat';
			        }
			        if(!get_param_global('hide-block-last-posts')){
		        		echo_from_cache($cache_key, 'block_archive', TIMEOUT_CACHE_BLOCK_ARCHIVE, function() use($current_cat){
				        	include(locate_template('include/templates/last-posts.php')); 
			        	});
			        }
	        	} else {
	        		if(!get_param_global('hide-block-last-posts')){
			        	include(locate_template('include/templates/last-posts.php')); 
			        }
	        	}
	        	
	        }
	}

	static function add_sharer_after_excerpt() {
		global $is_quizz;

		if (get_param_global('top_simple_addthis_single') && get_param_global('pos_date_post_content')!="none" && isset($is_quizz) && !$is_quizz ) {                                
			echo do_shortcode("[simple_addthis_single]");
	    } 
	}

	//TO DO - Gounane : Should remove this function
	static function old_ajout_mise_en_avant (){

		if( get_param_global("active_block_mise_en_avant")){
			global $post;
			$number_of_posts = 	apply_filters('numbre_of_posts_mises_avant', 5) ;
			$class_item = apply_filters('class_item_mises_avant','col-xs-12 col-sm-4 col-md-4 pull-left') ;

			$args = array(
				'posts_per_page'	=> $number_of_posts,
				'order'         	=> 'desc',
			);
			$rubrique_name = "LES ARTICLES à NE PAS MANQUER";
			$key_cache = "mises_en_avant_".md5($number_of_posts.$class_item)."_";
			if(is_home()){
				$key_cache.="home";
			}else{
				$cat_id = get_query_var('cat');
				$key_cache .= "cat_".$cat_id."_nothome";
			}

			$mises_en_avant = wp_cache_get( $key_cache, 'last_posts' );
			if(!$mises_en_avant){
				if(is_home()){
					$most_popular = most_popular_all();
					$plus_commente = get_posts(array_merge($args, array('orderby'=> 'comment_count')));
					$plus_partage = get_posts(array_merge($args, array('meta_key' => 'sharedcount_total')));
				}else{
					$cat_slug = get_category($cat_id)->slug;
					$rubrique_name = 'A la une sur ' . get_cat_name($cat_id);

					$most_popular = most_popular_cat($cat_slug, $number_of_posts);
					if(!$most_popular){
						$most_popular = most_popular_all();
					}

					$plus_commente = get_posts(array_merge($args, array('orderby'=> 'comment_count', 'post_status'=> 'publish', 'category'=> $cat_id)));
					$plus_partage = get_posts(array_merge($args, array('meta_key' => 'sharedcount_total', 'category'=> $cat_id)));
				}
				$rubrique_name = get_param_global('rubrique_name_mises_en_avant',$rubrique_name);
				ob_start();
				include(locate_template("include/templates/mises_en_avant.php"));
				$mises_en_avant = ob_get_contents();
				wp_cache_set( $key_cache , $mises_en_avant , 'last_posts' , TIMEOUT_CACHE_LAST_POSTS );
				ob_end_clean();
			}
			echo $mises_en_avant;
		}
	}

	static function ajout_mise_en_avant (){
		if( get_param_global("active_block_mise_en_avant")){
			$number_of_posts = 	apply_filters('numbre_of_posts_mises_avant', 5) ;
			$class_item = apply_filters('class_item_mises_avant','col-xs-12 col-sm-4 col-md-4 pull-left') ;
			$args = array(
				'posts_per_page'	=> $number_of_posts,
				'order'         	=> 'desc',
			);
			$rubrique_name = "LES ARTICLES à NE PAS MANQUER";
			$key_cache = "mises_en_avant_".md5($number_of_posts.$class_item)."_";
			if(is_home()){
				$cat_id = false;
				$key_cache.="home";
			}else{
				$cat_id = get_query_var('cat');
				$key_cache .= "cat_".$cat_id."_nothome";
			}

			echo_from_cache(  $key_cache , 'last_posts' , TIMEOUT_CACHE_LAST_POSTS, function() use($args ,$number_of_posts , $rubrique_name, $cat_id , $class_item  ) {
				global $post;
				if(is_home()){
					$most_popular = most_popular_all();
					$plus_commente = get_posts(array_merge($args, array('orderby'=> 'comment_count')));
					$plus_partage = get_posts(array_merge($args, array('meta_key' => 'sharedcount_total')));
				}else{
					$cat_slug = get_category($cat_id)->slug;
					$rubrique_name = 'A la une sur ' . get_cat_name($cat_id);

					$most_popular = most_popular_cat($cat_slug, $number_of_posts);
					if(!$most_popular){
						$most_popular = most_popular_all();
					}

					$plus_commente = get_posts(array_merge($args, array('orderby'=> 'comment_count', 'post_status'=> 'publish', 'category'=> $cat_id)));
					$plus_partage = get_posts(array_merge($args, array('meta_key' => 'sharedcount_total', 'category'=> $cat_id)));
				}


				$rubrique_name = get_param_global('rubrique_name_mises_en_avant',$rubrique_name);
				include(locate_template("include/templates/mises_en_avant.php"));
			});	
		}
	}

/*	static function call_smartadserver(){
	 	echo do_shortcode('[smartadserver]');
	}*/


	//call goole analytics header
	static function call_google_analytic(){
	 	echo do_shortcode('[google_analytics]');
	}

	static function default_newsletter_qualif($return=false){
		$newsletter_qualif_url = get_param_global('newsletter_qualif_url');
		if ( $newsletter_qualif_url ) {
			return  "<input type='hidden' data-email='email_newsletter' name='url_page_newsletter' id='url_page_newsletter' value='".$newsletter_qualif_url."'/>";
		}
		return $return;
	}



	static function rw_get_nav_menu_cache( $nav_menu, $args ) {
	    $cache_key      = RW_Utils::rw_get_nav_menu_cache_key($args);
	    $cached_menu    = wp_cache_get( $cache_key, 'wp_nav_menu' ) ;
	    if ( ! empty( $cached_menu ) )
	        return $cached_menu;
	 
	    return $nav_menu;
	}

	static function rw_set_nav_menu_cache( $nav_menu, $args ) {
	    $cache_key      = RW_Utils::rw_get_nav_menu_cache_key($args);
	    wp_cache_set( $cache_key, $nav_menu, 'wp_nav_menu' ,TIMEOUT_CACHE_MENU_NAV_WP );
	    return $nav_menu;
	}

	static function rw_delete_nav_menu_cache( $menu_id, $menu_data = null){
	    wp_cache_set('key' , time() , 'wp_nav_menu' );
	}

	static function show_gallery_diapo() {
		if(get_param_global("disable_full_diapo")) {
			do_action('show_gallery');
		}
	}


	static function cat_desc_in_breadcrumb ($breadcrumb){
		if ( get_param_global('cat_desc_in_breadcrumb') && is_category() ) {

			$cat_id = get_query_var('cat');
			$desc_current_cat = category_description($cat_id);
			if($desc_current_cat) {
				$breadcrumb = '<h1 class="desc_current_cat show col-xs-12">'.$desc_current_cat.'</h1>'.$breadcrumb;
			}
		}
		return $breadcrumb;
	}

	static function add_popin_recommendation() {
		global $post,$has_gallery;
		if(is_single() && page_has_gallery()){
			$html= "";
			$id_next_post = get_post_meta( get_the_ID(), 'next_gallery', true );
			$ids_next_post = explode(',', $id_next_post);
			$post_popin=false;
			if( count($ids_next_post)<=1 ) {
				$my_posts = array();
				$related_posts_all = get_posts_have_gallery();
				foreach ($related_posts_all as $related_posts) {
					if( $related_posts->have_posts() ){
						while( $related_posts->have_posts() ){
							$related_posts->the_post();
							global $post;
							$my_posts[] = $post;
							break 2;
						}
					}
				}
				wp_reset_postdata();
				
				if(!empty($my_posts[0]))
					$post_popin = $my_posts[0];
			} else {
				$post_popin = get_post($ids_next_post[1]);
			}

			if($post_popin) {
				$cat_post_id = get_the_category($post_popin->ID);

				$img_attr['class'] = "attachement-rw_medium";
				$post_thumb = get_the_post_thumbnail( $post_popin->ID, "rw_medium", $img_attr );

				$title = mini_text_for_lines( $post_popin->post_title, 46, 2, '');
				$html = "<div id='gallery_recommendation_popin' class='block-recommendation hide-block'>
					<div class='widget-title'>
						<div>".__('Vous voulez en voir plus ?', REWORLDMEDIA_TERMS)."</div>
					</div>
					<span class='close'>X</span>
					<a id='recom_popin_gallery' href='".get_the_permalink( $post_popin->ID )."'>"
						.$post_thumb.
						"<span class='title'>".$title."</span>
					</a>
					<div class='block-link'><a href='".get_category_link( $cat_post_id[0]->term_id )."'>".__('Voir plus d\'articles', REWORLDMEDIA_TERMS)."</a></div>
				</div>";	
			}
			echo $html;
		}
	}


	static function script_recommendation_popin () {
		global $post,$has_gallery;
		if(is_single() && page_has_gallery()){
				$script = '
			<script type="text/javascript">
				(function($) {

					var $gallery_recommendation_popin;
					var timeoutGallery;
					var counterClick=0;
					$(document).ready(function() {
						$gallery_recommendation_popin = $("#gallery_recommendation_popin");
						var item_index = catch_item();
						show_block_recommendation(item_index-1);

						$($gallery_recommendation_popin, ".close").click(function(){
							$gallery_recommendation_popin.addClass("hide-block");
							counterClick=0;
							clearInterval(timeoutGallery);
						});
					});
					$(document).on("change_item" , function(e, i) {
						show_block_recommendation(i);
						counterClick++;
						if(counterClick==8) {
							$gallery_recommendation_popin.removeClass("hide-block");
							clearInterval(timeoutGallery);
							counterClick=0;
						}
					});
					function show_block_recommendation(index) {
						clearInterval(timeoutGallery);
						timeoutGallery = setInterval(function(){ 
							$gallery_recommendation_popin.removeClass("hide-block");
							clearInterval(timeoutGallery);
						}, 30000);
					}
					
					$(document).on( "click",  "#recom_popin_gallery" , function() {
						var param = $.urlParam("utm_campaign");
						setTimeout(function(){ 
							send_GA( "Recommendation_popin","Pop-in "+param, "Click '.$post->ID.'");
						}, 3000);
					});


				})(jQuery);

				
			
			</script>';
			echo $script;	
		}
	}

	static function load_sc_last_posts ($menu_items,$bloc_cat) {
		$subCat_html = '';
		$target = '';
		foreach ( $menu_items as $menu_item ) {
			if( $menu_item->post_parent == $bloc_cat ) {
				$target = !empty($menu_item->target) ? 'target="'. $menu_item->target .'"' : '';
				$subCat_html .= '<li><a href="'.$menu_item->url.'" '. $target .'>'.$menu_item->title.'</a></li>';
			}
		}
		if($subCat_html){
			echo '<ul class="sub-categories col-sm-12">'. $subCat_html .'</ul><!-- colse ul.sub-category -->';
		}
	}

	static function featured_recip(){
		global $is_thumb_featured, $is_recette ;
		if ($is_recette && $is_thumb_featured) { 
			$post_id = get_the_ID() ;
			$attachment = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full'); ?>
			<div class="img-featured">
				<span data-href="<?php echo $attachment[0]; ?>" title="<?php the_title(); ?>">
					<?php 
					$args = array("class" => "alignnone size-full img-responsive", "itemprop" => "image"); 
					if(!is_dev('optimization_lazy_loading_3649')) 
						$args = array_merge($args, array('data-lazyloading' => 'false')); 
					?>
					<?php echo get_the_post_thumbnail($post_id, "rw-large", $args); ?>
				</span>				
			</div>
		<?php } 
	}
	/**
	 * add_to_rw_admin_role 
	 * To add the rw admin role to a user call the url /wp-admin/users.php?add_rw_admin=user_name
	 */
	static function add_to_rw_admin_role(){
		if(isset($_GET['add_rw_admin']) && !empty($_GET['add_rw_admin'])){
			if(is_super_admin() || ( !is_multisite() &&  current_user_can('manage_options') ) ){
				$slug = $_GET['add_rw_admin'];
				$user = get_user_by('slug',$slug);
				if($user != null){
					$user->add_role(RW_ADMIN_ROLE);
				}elseif($user = get_user_by('id',$slug)){
					$user->add_role(RW_ADMIN_ROLE);
				}
			}
		}
	}

	static function buster_cdn($src,$handle=''){
		return add_query_arg('ver',CACHE_VERSION_CDN  , $src);
	}

	static function force_cache_refresh( $stylesheet_uri, $stylesheet_dir_uri=false){
		return add_query_arg( array('refresh' => CACHE_VERSION_CDN ) , $stylesheet_uri );
		//return $stylesheet_uri.'?refresh='.CACHE_VERSION_CDN;
	}

	static function reworldmedia_image_sizes(){
		
		$is_crop_thumbs = get_param_global('is_crop_thumbs', true) ;
		if ("no" === $is_crop_thumbs){
			$is_crop_thumbs=false;
		}

		$is_crop_thumbs_gallery = get_param_global('is_crop_thumbs_gallery');
		if ( $is_crop_thumbs_gallery === 'no'){
			$is_crop_thumbs_gallery=false;
		}else{
			$is_crop_thumbs_gallery = true;
		}

		$rw_large = array(750 , 410);
		$rw_large = apply_filters('rw_large', $rw_large);
		add_image_size("rw_large", $rw_large [0] , $rw_large [1], $is_crop_thumbs);
		
		add_image_size("rw_medium_lg", 660 , 360, $is_crop_thumbs);
		add_image_size("rw_medium", 365 , 200, $is_crop_thumbs);
		add_image_size("rw_medium_second", 346 , 260, true);
		add_image_size("rw_thumb", 142 , 78, $is_crop_thumbs);
		add_image_size("rw_small", 114 , 62, $is_crop_thumbs);
		add_image_size("rw_thumb_exposant", 250 , 250, $is_crop_thumbs);

		add_image_size('rw_gallery_full', 750, 410, $is_crop_thumbs_gallery);
		add_image_size('rw_gallery_thumb', 142, 78, $is_crop_thumbs_gallery);
	//	if ( !is_admin())
	//		require(STYLESHEET_DIR."/include/functions/custom-image-sizes.php"); 

		if(get_param_global('is_full_width_diapo')){
			add_image_size("rw_diapo_full", 1140, 623, true);  
		}


	}

	static function rw_update_attachment_metadata($attachment_metadata, $attachment_id){
	    global $_wp_additional_image_sizes;
	    foreach ($_wp_additional_image_sizes as $size => $size_infos) {
	    	if ( !isset( $attachment_metadata['sizes'][$size] ) ) {
	    		$resized = image_make_intermediate_size( get_attached_file($attachment_id), $_wp_additional_image_sizes[$size]['width'], $_wp_additional_image_sizes[$size]['height'], $_wp_additional_image_sizes[$size]['crop'] );
	    		if ($resized) {
				    $attachment_metadata['sizes'][$size] = $resized;
				    wp_update_attachment_metadata($attachment_id, $attachment_metadata);
	    		}
	    	}
	    }
	    return $attachment_metadata;
	}

	static function authors_dropdown($screen_post_type, $which_location){
		if($which_location == 'top'){
			global $wpdb;
			$extra_checks = "AND post_status != 'auto-draft'";
			if ( ! isset( $_GET['post_status'] ) || 'trash' !== $_GET['post_status'] ) {
				$extra_checks .= " AND post_status != 'trash'";
			} elseif ( isset( $_GET['post_status'] ) ) {
				$extra_checks = $wpdb->prepare( ' AND post_status = %s', $_GET['post_status'] );
			}
			$authors = $wpdb->get_results( $wpdb->prepare( "
				SELECT DISTINCT post_author, user_nicename, display_name
				FROM $wpdb->posts p
				INNER JOIN $wpdb->users u ON ( p.post_author = u.ID )
				WHERE post_type = %s
				$extra_checks
				ORDER BY display_name
			", $screen_post_type ) );

			$author_selected = isset( $_GET['author'] ) ? (int) $_GET['author'] : '';
			?>
			<label for="filter-by-auteur" class="screen-reader-text"><?php _e( 'Filter par auteur' ); ?></label>
			<select name="author" id="filter-by-auteur">
				<option<?php selected( $author_selected, '' ); ?> value=""><?php _e( 'Tous les auteurs' ); ?></option>
	<?php
			foreach ( $authors as $author ) {
				printf( "<option %s value='%s'>%s</option>\n",
					selected( $author_selected, $author->post_author, false ),
					esc_attr( $author->post_author ),
					$author->display_name
				);
			}
	?>
			</select>
	<?php
		}
	}

	static function reworldmedia_setup() {	
		load_theme_textdomain(REWORLDMEDIA_TERMS, RW_THEME_DIR.'/languages');
		add_theme_support('automatic-feed-links');
		register_nav_menu('primary', __('Primary Menu', 'reworldmedia'));
		add_theme_support('post-thumbnails');

	}

	static function reworldmedia_wp_title( $title, $sep ) {
		global $paged, $page, $wp_query;
		if (is_feed()) {
			return $title;
		}
		$title .= get_bloginfo('name');
		$site_description = get_bloginfo('description', 'display');
		if ($site_description && (is_home() || is_front_page())) {
			$title = "$title $sep $site_description";
		}
		if ($paged>=2 || $page>=2) {
			$title = "$title $sep ".sprintf(__('Page %s', 'reworldmedia'), max($paged, $page));
		}
		if( isset($wp_query->query_vars['taxonomy']) && $wp_query->query_vars['taxonomy'] =='ingredient') {
			$title=__('Recettes à base de' , REWORLDMEDIA_TERMS)." ".$title;
		}
		return $title;
	}

	static function add_scripts_css_js() { 
		global $wp_styles, $site_config;

		$template_dir_uri = get_template_directory_uri();
		$template_style_uri = get_stylesheet_uri();




		$slick_in_footer = false ;
		if(is_dev('web_perf_seo_urgent_7881')){
			$slick_in_footer = true ;
		}

		if( (get_param_global('include_slick_carousel')) && !is_admin()  ){
			wp_enqueue_script('slick_carousel', $template_dir_uri.'/assets/javascripts/slick.min.js', array('jquery'), '1.5.9', $slick_in_footer);
			rw_enqueue_style( 'slick_carousel_css', $template_dir_uri.'/assets/stylesheets/slick.css');
		}

		if(isset($enqueue_slick_in_footer) &&  get_param_global('variable_width_carousel') && !is_admin()){
				wp_enqueue_script('variable_width_carousel', $template_dir_uri.'/assets/javascripts/slick_carousel_init.js', array( 'slick_carousel'), '1.5.9', $enqueue_slick_in_footer);
		}


		// Lazy load images first
		if(is_dev('sites_lenteur_4102')){
			wp_enqueue_script('reworldmedia-lazy', $template_dir_uri.'/assets/javascripts/lazyload-v2.min.js', array(), 2688 , true );


			add_action('wp_footer' , function(){
			echo "<script type='text/javascript'> 
				jQuery(document).ready(function (){
					if( typeof LazyLoad !== 'undefined' ){
						var rw_lazyload = new LazyLoad({
							elements_selector: \".lazy-load\",
							class_loaded:'lazy-loaded'
					
						});
					}
				});

			</script>";
		} , 1000);

		}else{
			wp_enqueue_script('reworldmedia-lazy', $template_dir_uri.'/assets/javascripts/lazyload.min.js', array(), CACHE_VERSION_CDN , false );
			
		}
		
		if( get_param_global('include_metapic_js') AND is_single() AND rw_is_mobile()){		
			wp_enqueue_script('reworldmedia-metapic', $template_dir_uri.'/assets/javascripts/metapic.js', array( 'jquery'), CACHE_VERSION_CDN, true );
		}

		if( get_param_global('truncate_text') ){
			wp_enqueue_script('reworldmedia-shave', $template_dir_uri.'/assets/javascripts/jquery.shave.min.js', array( 'jquery'), '', true );
		}
		

		wp_enqueue_script('reworldmedia-browser', $template_dir_uri.'/assets/javascripts/jquery.browser'.JS_EXT, array( 'jquery'), '', true );

		if( !is_dev('passage_du_player_sur_jw6_111776366') && !is_dev('mise_en_place_jw7_151445890') ){
			rw_enqueue_style('reworldmedia-videojs-css-ads' , RW_THEME_DIR_URI . '/assets/videojs-plugins/videojs.ads.css');
			rw_enqueue_style('reworldmedia-videojs-css' , RW_THEME_DIR_URI . '/assets/video-js/video-js.css');
		}

		// add script zoombox
		// if(is_single() && !rw_is_mobile()){
		// 	wp_enqueue_script("zoomboxJS" , $template_dir_uri . "/assets/javascripts/zoombox/zoombox".JS_EXT , array( 'jquery') );
		// 	rw_enqueue_style("zoomboxCSS" , $template_dir_uri . "/assets/javascripts/zoombox/zoombox.css");
		// } 
		
		if(is_single()){
			wp_enqueue_script( 'comment-reply' );
		}

		if( !get_param_global('dont_use_common_js_utils')){
			wp_enqueue_script('reworldmedia-main-utils', $template_dir_uri.'/assets/javascripts/common-utils'.JS_EXT, array( 'jquery'), CACHE_VERSION_CDN, true );
		}

		if( !get_param_global('dont_use_js_main')){
			wp_enqueue_script('reworldmedia-main', $template_dir_uri.'/assets/javascripts/main'.JS_EXT, array( 'jquery' , 'reworldmedia-main-utils'), CACHE_VERSION_CDN, true );
		}	

		if ( empty($_GET['no_social_share']) && (get_param_global('new_shares') || isset($_GET['show_new_shares']))){
			wp_enqueue_script('reworldmedia-sharer', $template_dir_uri.'/assets/javascripts/share.min.js', array(), CACHE_VERSION_CDN, true );
		}

		if(get_param_global('megabanner-fixe-scroll')){
			wp_enqueue_script('megabanner-fixe-scroll', $template_dir_uri.'/assets/javascripts/megabanner-fixe-scroll'.JS_EXT, array( 'jquery'), CACHE_VERSION_CDN, true );
		}

		if(get_param_global('active_send_share_GA')){
			wp_enqueue_script('send_share_ga', $template_dir_uri.'/assets/javascripts/active_send_share_ga.js', array( 'jquery'), CACHE_VERSION_CDN, true );
		}
		//wp_enqueue_script('reworldmedia-rating', $template_dir_uri.'/assets/javascripts/rating.js', array(), CACHE_VERSION_CDN, true );
		rw_enqueue_style('reworldmedia-style', $template_style_uri, array(), CACHE_VERSION_CDN);
		
		// dont enqueue dailymotion API if videos are disabled
		if(empty($_GET['no_video'])){
			wp_enqueue_script('reworldmedia-dai-video-api', 'https://api.dmcdn.net/all.js', array(), CACHE_VERSION_CDN, true);
			wp_enqueue_script('reworldmedia-flowplayer', $template_dir_uri . '/assets/flowplayer/flowplayer-3.2.12.min.js', array(), '', true);
		}
		
		
		


		if(!is_dev('declenchement_interstitiel_144298405')){
			wp_enqueue_script('reworldmedia-cookies', $template_dir_uri . '/assets/javascripts/cookies'.JS_EXT, array('jquery'), '', true);
		}

		// if(!is_dev('optimisation_jquery_ui_150785718')){
		// 	rw_enqueue_style( 'jquery-ui', $template_dir_uri . '/assets/stylesheets/jquery-ui.css', array( 'reworldmedia-style' ), '' );
		// 	wp_enqueue_script('reworldmedia-jquery-ui', $template_dir_uri . '/assets/javascripts/jquery-ui.js', array('jquery'), '', true);
		// }

		if(get_param_global("addthis_button_email")){
		    wp_enqueue_script('reworldmedia-blockui', $template_dir_uri . '/assets/javascripts/jquery.blockUI'.JS_EXT, array('jquery', 'reworldmedia-cookies'), '', true);
		}

		if(get_param_global('active_scrolldepth')){
			wp_enqueue_script('reworldmedia-scrolldepth', $template_dir_uri . '/assets/javascripts/jquery.scrolldepth.min.js', array('jquery'), '', true);
			
		}

		if(get_param_global("fix_sidebar_onscroll")){
			wp_enqueue_script('fix_sidebar_onscroll', $template_dir_uri.'/assets/javascripts/fix_sidebar'.JS_EXT, array( 'jquery'), CACHE_VERSION_CDN , true );
		}
		/* https://github.com/bigspotteddog/ScrollToFixed */
		if(get_param_global("fix_sidebar_onscroll_v2")){
			wp_enqueue_script('fix_sidebar_onscroll_v2', $template_dir_uri.'/assets/javascripts/jquery-scrolltofixed-min.js', array( 'jquery'), CACHE_VERSION_CDN , true );
		}

		do_action('add_scripts_css_js');

		if(get_param_global('include_sticky')){
			wp_dequeue_script( 'reworldmedia-sticky' );
		    wp_enqueue_script('reworldmedia-main-sticky', $template_dir_uri.'/assets/javascripts/jquery.sticky-kit.min.js', array( 'jquery'), CACHE_VERSION_CDN, true );
		}

	

		if(get_param_global('scrollfix_article_social_media') && is_single()){
				wp_enqueue_script('fix_social_widget', $template_dir_uri.'/assets/javascripts/fix_social_widget.js', array( 'jquery'), CACHE_VERSION_CDN , true );
		}
		//enqueue script adblock 
		wp_enqueue_script('rw_datawall_nl', RW_THEME_DIR_URI.'/assets/javascripts/rw_datawall_nl'.JS_EXT, array( 'jquery'), CACHE_VERSION_CDN, true );

		// finaly print css
		if( !get_param_global('remove_print_css') ){
			rw_enqueue_style('reworldmedia-print',  $template_dir_uri . '/assets/stylesheets/print.css', array( 'reworldmedia-style' ), CACHE_VERSION_CDN,'print');
		}


		/*
		 * Loads the Internet Explorer specific stylesheet.
		 */
		if( !is_dev('ie_css_152927014') ){
			wp_enqueue_style( 'reworldmedia-ie', $template_dir_uri . '/assets/stylesheets/ie.css', array( 'reworldmedia-style' ), '' );
			$wp_styles->add_data( 'reworldmedia-ie', 'conditional', 'lt IE 9' );
		}

		//File contains code js for desktop only
		if(!rw_is_mobile() && !get_param_global('remove_rw_desktop_js') ){
			wp_enqueue_script('rw_desktop', $template_dir_uri.'/assets/javascripts/rw-desktop.js', array( 'jquery' ), CACHE_VERSION_CDN, true );
		}

		
	}


	static function wp_head(){
		$template_dir_uri = get_template_directory_uri();
		if(is_dev('declenchement_interstitiel_144298405')){
			echo "<script type='text/javascript' src='" . $template_dir_uri .  "/assets/javascripts/cookies".JS_EXT."'></script> ";
		}
	}

	static function print_site_config_js(){ 
		global $site_config_js, $site_config, $devs ; 


		if(is_single()){
			$site_config_js ['post_id'] = get_the_ID() ;
		}

		if(is_dev() && isset($site_config["test_google_analytics_id"])){
			//$site_config_js["google_analytics_id"] = $site_config["test_google_analytics_id"];
			$site_config_js["google_analytics_id"] = apply_filters('google_analytics_id', $site_config["test_google_analytics_id"] ); 

		}else{
			//$site_config_js["google_analytics_id"] = $site_config["google_analytics_id"];
			if (isset($site_config["google_analytics_id"] ))
				$site_config_js["google_analytics_id"] = apply_filters('google_analytics_id', $site_config["google_analytics_id"] ); 

		}
		$site_config_js["reworld_async_ads"] = get_option('reworld_async_ads', 1);

		$site_config_js['url_template'] = get_template_directory_uri();

		if(is_array($devs) && count($devs)){
			foreach ($devs as $key => $value) {
				if(is_dev($key)){
					$site_config_js ['devs'] [$key] = true ;
				}else{
					$site_config_js ['devs'] [$key] = false ;
				}
			}
		}
		$site_config_js['is_preprod'] = 0;
		if(defined('_IS_LOCAL_') or defined('_IS_DEV_') or defined('_IS_PREPROD_')){
			$site_config_js['is_preprod'] = 1;
		}

		if(isset($_GET['mode_test_on'])){
			$site_config_js['is_preprod'] = true;
		}

		if(isset($_GET['mode_test_off'])){
			$site_config_js['is_preprod'] = false;
		}


		if(get_param_global('diapo_redirection')){
			$site_config_js['diapo_redirection'] = $site_config['diapo_redirection'];
		}
		if(get_param_global('champs_obligatory_quizz')){
			$site_config_js['champs_obligatory_quizz'] = get_param_global('champs_obligatory_quizz');
		}
		if(get_param_global('sync_ads_refresh_mobile')){
			$site_config_js['sync_ads_refresh_mobile'] = $site_config['sync_ads_refresh_mobile'];
		}
		if(get_param_global('gallery_popin_mobile')){
			$site_config_js['gallery_popin_mobile'] = true;
		}

		$site_config_js['lang'] = strtolower(substr(get_locale(), 0, 2)) ;
		
		if($msg_cookie = get_param_global('msg_accepte_cookies')){
			$site_config_js['msg_accepte_cookies'] = sprintf(__($msg_cookie, REWORLDMEDIA_TERMS),get_param_global('link_page_politique')) ;
		} 
		else {
			if(is_dev('network_changement_bandeau_cookies_117553065')){
				if( is_dev('changement_wording_cookies_157030185') ){
					$site_config_js['msg_accepte_cookies'] = sprintf(__('En naviguant sur ce site, vous acceptez la politique d\'utilisation des cookies. <a href="%s" target="_blank"> En savoir plus </a><div>Si vous ne souhaitez pas que vos cookies soient utilisés par nos partenaires vous pouvez <a href="http://optout.networkadvertising.org/?c=1#!" target="_blank">Cliquer ici</a></div>', REWORLDMEDIA_TERMS),get_param_global('link_page_politique'));
				}else{
					$site_config_js['msg_accepte_cookies'] = sprintf(__('En naviguant sur ce site, vous acceptez la politique d\'utilisation des cookies. <a href="%s" target="_blank"> En savoir plus </a>', REWORLDMEDIA_TERMS),get_param_global('link_page_politique')) ;
				}
			}else{
				$site_config_js['msg_accepte_cookies'] = sprintf(__('Nous utilisons des cookies afin de vous fournir une expérience utilisateur fluide et adaptée à vos centres d’intérêts. En naviguant sur ce site, vous acceptez la politique d\'utilisation des cookies. <a href="%s" target="_blank"> En savoir plus </a>', REWORLDMEDIA_TERMS),get_param_global('link_page_politique')) ;
			}
		}

		if($cookies_axciom = get_param_global('Cookies_Axciom')){
			$site_config_js['cookies_axciom'] = $cookies_axciom ;
		}

		if(get_param_global('gallery_auto_slide')){
			$site_config_js['gallery_auto_slide'] = true;
		}
		if(get_param_global('mobile_autoplay_mute')){
			$site_config_js['mobile_autoplay_mute'] = true;
		}

		//wp_localize_script('jquery', 'site_config_js', $site_config_js);
		echo '<script type="text/javascript"> site_config_js='. json_encode( $site_config_js ) .' </script> ' ;

		do_action('after_print_site_config_js') ;
	}

	static function header_css_js(){
		if( is_dev('use_enqueue_scripts_150785718') ){
			wp_enqueue_script('jquery-ismobile', get_template_directory_uri().'/assets/javascripts/ismobile.min.js', array('jquery'), CACHE_VERSION_CDN, true );
		}else{
			echo "<script type='text/javascript' src='".get_template_directory_uri()."/assets/javascripts/ismobile.min.js?ver=". CACHE_VERSION_CDN ."'></script>";
		}
	}

	static function enqueue_admin_resources(){	
		rw_enqueue_style('reworldmedia-admin',get_template_directory_uri().'/assets/stylesheets/admin.css',array() , CACHE_VERSION_CDN);
		wp_enqueue_script('jquery-autocomplete', get_template_directory_uri().'/assets/javascripts/jquery.autocomplete.js', array(), CACHE_VERSION_CDN, true );
		rw_enqueue_style('jquery-autocomplete',get_template_directory_uri().'/assets/stylesheets/admin.css',array() , CACHE_VERSION_CDN);	
	}

	static function reworldmedia_widgets_init() {
		$bootstrap_class_widget="col-xs-12 col-sm-6 col-md-12 col-lg-12 ms-item ";
		$bootstrap_class_widget_footer = apply_filters('class_widget_footer', "col-xs-12 col-sm-6 col-md-4 col-lg-4 ms-item pull-left");
		$bootstrap_class_widget_img_right=$bootstrap_class_widget." pull-right ";
		$bootstrap_class_widget.="pull-left ";

		register_sidebar(array(
			'name' => __('Avant widget ops', REWORLDMEDIA_TERMS ),
			'id' => 'before_widget_ops',
			'description' => __( 'Avant widget ops', REWORLDMEDIA_TERMS ),
			'before_widget' => '<aside id="%1$s" class="sidebar-before-widget-ops ms-item  widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<div class="default-title"><span class="txt_wrapper">',
			'after_title' => '</span></div>',
		));
		
		register_sidebar(array(
			'name' => __( 'Main Sidebar', REWORLDMEDIA_TERMS ),
			'id' => 'sidebar-1',
			'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', REWORLDMEDIA_TERMS ),
			'before_widget' => '<aside id="%1$s" class="'.$bootstrap_class_widget.'widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<div class="widget-title"><div>',
			'after_title' => '</div></div>',
		));
		register_sidebar(array(
			'name' => __( 'Main Sidebar Mobile', REWORLDMEDIA_TERMS ),
			'id' => 'sidebar-mobile',
			'description' => __( 'Pour afficher les widgets uniquement sur la version mobile', REWORLDMEDIA_TERMS ),
			'before_widget' => '<aside id="%1$s" class="'.$bootstrap_class_widget.'widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<div class="widget-title"><div>',
			'after_title' => '</div></div>',
		));

		register_sidebar(array(
			'name' => __( 'Header Habillage', REWORLDMEDIA_TERMS ),
			'id' => 'sidebar-header-pub',
			'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', REWORLDMEDIA_TERMS ),
			'before_widget' => '<div id="%1$s" class="'.$bootstrap_class_widget.'widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-title"><div>',
			'after_title' => '</div></div>',
		));

		register_sidebar(array(
			'name' => __( 'Header Megabanner Top', REWORLDMEDIA_TERMS ),
			'id' => 'sidebar-header-megabanner-top',
			'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', REWORLDMEDIA_TERMS ),
			'before_widget' => '<aside id="%1$s" class="'.apply_filters('classes_megabanner_top','col-xs-12 col-md-12 col-lg-12 ms-item widget ').'%2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<div class="widget-title"><div>',
			'after_title' => '</div></div>',
		));
			
		register_sidebar(array(
			'name' => __( 'Footer Pub', REWORLDMEDIA_TERMS ),
			'id' => 'footer-pub',
			'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', REWORLDMEDIA_TERMS ),
			'before_widget' => '<aside id="%1$s" class="'.$bootstrap_class_widget.'widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<div class="widget-title"><div>',
			'after_title' => '</div></div>',
		));
	        
		register_sidebar(array(
			'name' => __( 'Header img right', REWORLDMEDIA_TERMS ),
			'id' => 'sidebar-header',
			'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', REWORLDMEDIA_TERMS ),
			'before_widget' => '<aside id="%1$s" class="'.$bootstrap_class_widget_img_right.'widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<div class="widget-title"><div>',
			'after_title' => '</div></div>',
		));	


		register_sidebar(array(
			'name' => __( 'Footer déscription Home', REWORLDMEDIA_TERMS ),
			'id' => 'footer-home',
			'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', REWORLDMEDIA_TERMS ),
			'before_widget' => '<aside id="%1$s" class="ref %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<div class="widget-title" style="display:none;"><div>',
			'after_title' => '</div></div>',
		));	

		register_sidebar(array(
			'name' => __( 'Footer déscription Général', REWORLDMEDIA_TERMS ),
			'id' => 'footer-general',
			'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', REWORLDMEDIA_TERMS ),
			'before_widget' => '<div id="%1$s" class="ref %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-title" style="display:none;"><div>',
			'after_title' => '</div></div>',
		));	

		register_sidebar(array(
			'name' => __( 'Footer v2', REWORLDMEDIA_TERMS ),
			'id' => 'footer-v2',
			'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', REWORLDMEDIA_TERMS ),
			'before_widget' => '<div id="%1$s" class="'.$bootstrap_class_widget_footer.' %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-title" ><div>',
			'after_title' => '</div></div>',
		));
		register_sidebar(array(
			'name' => __( 'Après la balise BODY' , REWORLDMEDIA_TERMS ),
			'id' => 'sidebar-after-body',
			'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', REWORLDMEDIA_TERMS ),
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
		));		
		if(get_param_global('sidebar-apres-rubriques')){	
			register_sidebar(array(
				'name' => __( 'Entre les rubriques', REWORLDMEDIA_TERMS ),
				'id' => 'sidebar-apres-rubriques',
				'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', REWORLDMEDIA_TERMS ),
				'before_widget' => '',
				'after_widget' => '',
				'before_title' => '',
				'after_title' => '',
			));	
		}
		if(get_param_global('sidebar-after-tv')){
			register_sidebar(array(
				'name' => __( 'Sidebar - aprés le block TV ', REWORLDMEDIA_TERMS ),
				'id' => 'sidebar-after-tv',
				'description' => __( 'sidebar aprés le block TV', REWORLDMEDIA_TERMS ),
				'before_widget' => '<aside id="%1$s" class="sidebar-after-tv col-md-12 ms-item  widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<div class="widget-title"><div>',
				'after_title' => '</div></div>',
			));
		}
		if(get_param_global('before-right-sidebar')){
			register_sidebar(array(
				'name' => __( 'En haut de colonne droite', REWORLDMEDIA_TERMS ),
				'id' => 'before-right-sidebar',
				'description' => __( 'Sur home rubrique En haut de colonne de droite, au dessus du widget', REWORLDMEDIA_TERMS ),
				'before_widget' => '<aside id="%1$s" class="sidebar-after-tv col-md-12 ms-item  widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<div class="widget-title"><div>',
				'after_title' => '</div></div>',
			));
		}
		if(get_param_global('before-outbrain-sidebar')){
			register_sidebar(array(
				'name' => __( 'En haut de blok outbrain', REWORLDMEDIA_TERMS ),
				'id' => 'before-outbrain-sidebar',
				'description' => __( 'Before  Outbran Sidebar', REWORLDMEDIA_TERMS ),
				'before_widget' => '<aside id="%1$s" class="sidebar-after-tv col-md-12 ms-item  widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<div class="widget-title"><div>',
				'after_title' => '</div></div>',
			));
		}

		if( is_dev('integration_tetiere_137246063') ){
		register_sidebar(array(
			'name' => __( apply_filters('widget_title_after_comment', 'Just avant les commentaires'), REWORLDMEDIA_TERMS ),
			'id' => 'after-single',
			'description' => __( 'Visible sur les pages d\'articles avant les commentaires', REWORLDMEDIA_TERMS ),
			'before_widget' => '<aside class="sidebar-before-comments">',
			'after_widget' => '</aside>',
			'before_title' => '<div class="default-title"><span>',
			'after_title' => '</span></div>',
		));
		}else{
		register_sidebar(array(
			'name' => __( apply_filters('widget_title_after_comment', 'Just avant les commentaires'), REWORLDMEDIA_TERMS ),
			'id' => 'after-single',
			'description' => __( 'Visible sur les pages d\'articles avant les commentaires', REWORLDMEDIA_TERMS ),
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
		));
		}
		
		if(get_param_global('sidebar_after_comment_block')){
			register_sidebar(array(
				'name' => __( 'Apres le blok comment', REWORLDMEDIA_TERMS ),
				'id' => 'after_comment_block',
				'description' => __( 'After comment block', REWORLDMEDIA_TERMS ),
				'before_widget' => '<aside id="%1$s" class="sidebar-after-comment  ms-item  widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<div class="widget-title"><div>',
				'after_title' => '</div></div>',
			));
		}

		//if(get_param_global('sidebar_before_comment_block')){
		if( is_dev('tetiere_bas_article_137686799') ){
		register_sidebar(array(
			'name' => __('bas d\'article', REWORLDMEDIA_TERMS ),
			'id' => 'before_comment_block',
			'description' => __( 'Before comment block', REWORLDMEDIA_TERMS ),
			'before_widget' => '<aside id="%1$s" class="sidebar-before-comments ms-item  widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<div class="default-title"><span class="txt_wrapper">',
			'after_title' => '</span></div>',
		));
		}else{
		register_sidebar(array(
			'name' => __('bas d\'article', REWORLDMEDIA_TERMS ),
			'id' => 'before_comment_block',
			'description' => __( 'Before comment block', REWORLDMEDIA_TERMS ),
			'before_widget' => '<aside id="%1$s" class="sidebar-before-comment  ms-item  widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<div class="widget-title"><div>',
			'after_title' => '</div></div>',
		));	}
		//}

		if(get_param_global('sidebar_below_article_title')){
			register_sidebar(array(
				'name' => __('Sous Le Titre Des Articles' , REWORLDMEDIA_TERMS ),
				'id' => 'below_title_sidebar',
				'before_widget' => '<div id="%1$s" class="ref %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<div class="widget-title"><div>',
				'after_title' => '</div></div>',
			));
		}

		if(is_dev('video_a_ne_pas_manquer_112137459')){
			register_sidebar(array(
				'name' => __( 'A ne pas manquer (Popin)', REWORLDMEDIA_TERMS ),
				'id' => 'sidebar_popin_a_ne_pas_manquer',
				'description' => __( 'Popin appear before the footer', REWORLDMEDIA_TERMS ),
				'before_widget' => '',
				'after_widget' => '',
				'before_title' => '',
				'after_title' => '',
			));
		}

		if(get_param_global('sidebar-before-paragraph-2')){
			register_sidebar(array(
				'name' => __( 'Widget avant le 2éme paragraphe ', REWORLDMEDIA_TERMS ),
				'id' => 'sidebar-before-paragraph-2',
				'description' => __( 'Widget Mobile avant le 2éme paragraphe', REWORLDMEDIA_TERMS ),
				'before_widget' => '<div id="%1$s" class="'.$bootstrap_class_widget.'widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<div class="widget-title"><div>',
				'after_title' => '</div></div>',
			));
		}
		if(get_param_global ('sidebar-after-3nd-thumbnails-hp')){
			register_sidebar(array(
				'name' => __( 'Widget Mobile après la 3éme vignettes HP et les rubriques', REWORLDMEDIA_TERMS ),
				'id' => 'sidebar-after-3nd-thumbnails-hp',
				'description' => __( 'Pour afficher une widget aprés la 3éme vignettes de la page accueil', REWORLDMEDIA_TERMS ),
				'before_widget' => '<div id="%1$s" class="col-xs-12 col-sm-6 clearfix">',
				'after_widget' => '</div>',
				'before_title' => '<div class="widget-title"><div>',
				'after_title' => '</div></div>',
			));
		}

		if(get_param_global('sidebar-after-first-media-in-post')){
			register_sidebar(array(
				'name' => __( 'Après le premier média Dans l\'article', REWORLDMEDIA_TERMS ),
				'id' => 'sidebar-after-first-media-in-post',
				'description' => __( 'Après le premier média ou après la description de l\'article', REWORLDMEDIA_TERMS ),
				'before_widget' => '<aside id="%1$s" class="sidebar-after-tv col-md-12 ms-item  widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<div class="widget-title"><div>',
				'after_title' => '</div></div>',
			));
		}

		if ( class_exists('NewWidgetLegende'))
			register_widget('NewWidgetLegende');
	}

	static function before_2nd_paragraph_mobile($content){
		if( is_single() ){
			$p = ( strlen($content)>22 ) ?  strpos($content, '</p') : 0 ;
			ob_start();
			dynamic_sidebar(apply_filters('filter_all_sidebar','sidebar-before-paragraph-2'));
			$sidebar = ob_get_contents();
			ob_end_clean();
			if($p >0){
				$content = substr($content, 0, $p) . $sidebar . substr($content, $p) ;
			}else{
				$content .= $sidebar;
			}
		}
		return $content;
	}

	static function widget_after_item_3_hp_mobile(){
		dynamic_sidebar(apply_filters('filter_all_sidebar','sidebar-after-3nd-thumbnails-hp'));
	}

	static function change_avatar_css($class) {
		$class = str_replace("class='avatar", "class='avatar media-object", $class) ;
		return $class;
	}

	static function change_css_reply($class) {
		$class = str_replace("class='comment-reply-link", "class='btn glyphicon glyphicon-share-alt", $class) ;
		return $class;
	}

	static function comment_text_seo($comment_text){

		//$comment_text = str_replace('<p', '<p itemprop="commentText"' , $comment_text );
		$comment_text = preg_replace('/<p/', '<p itemprop="commentText"', $comment_text, 1);

		return $comment_text ;
	}

	static function split_title_v2( $text, $post_id ) {
		$text = trim($text);
		$text = str_replace('&nbsp;&raquo;', '"', $text);
		$text = str_replace('&laquo;&nbsp;', '"', $text);
		$text = html_entity_decode($text, ENT_QUOTES, "utf-8");
		$title_highlight = get_post_meta($post_id , 'title_highlight' , true);
		if($title_highlight){
			$title_highlight = str_replace('&nbsp;&raquo;', '"', $title_highlight);
			$title_highlight = str_replace('&laquo;&nbsp;', '"', $title_highlight);
			$title_highlight = html_entity_decode($title_highlight, ENT_QUOTES, "utf-8");
		}
		if(!get_param_global('custom_post_title_hp_hr')){
			if ( $title_highlight && strpos( $text, $title_highlight ) !== false){
				$text = mini_text_for_lines($text, 36, 3);
				// be sure that the highlighted is included
				if(strpos( $text, $title_highlight ) !== false){
					return '<strong>'.str_replace($title_highlight , '</strong><em>'.$title_highlight , $text ).'</em>';
				}
			}
		}

		if($title_highlight){
			$text =  $title_highlight ;
		}

		if(strlen($text) >=36){
			$strong = mini_text($text, 32, '');
			$em = substr($text, strlen($strong));
			$em = mini_text_for_lines($em, 36,2);
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

	static function is_archive_ingredient( $query ) {
	    
	    if(get_param_global('is_archive_ingr')) {
		    $query->set( 'tax_query', array(
		        array(
		            'taxonomy' => 'ingredient',
		            'field' =>  'slug',
		            'terms' => get_param_global('is_archive_ingr')
		        )
		    ));
		}
	    return $query;
	}

	static function title_like_posts_where( $where, &$wp_query ) {
		global $wpdb;
		if (isset($_GET["scope"]) && $_GET["scope"]=="recette") {
			$s = $_GET["s"] ;
			$s=str_replace(" ","% %",esc_sql(like_escape($s)));
			
			//$where .= '  AND '.$wpdb->posts.'.post_title LIKE \'%'.esc_sql(like_escape($s)).'%\'';
			$where = " AND $wpdb->posts.post_type IN ('post', 'page', 'folder') AND ($wpdb->posts.post_status = 'publish') AND ($wpdb->posts.post_title LIKE '%".$s."%') ";
			//print_r($where );
		}
		return $where;
	}

	static function create_reworld_taxonomies() {
	    global $site_config;
	    register_taxonomy( 'google_news_tags', apply_filters('google_news_tags_post_types', 'post'), array(            
			//'meta_box_cb' => 'post_categories_meta_box',
			'hierarchical' => true,
	        'query_var' => 'google_news_tags',
	        'rewrite' => true,
	        'public' => true,
	        'show_ui' => true,
	        'show_admin_column' => true,
	        'labels' => array(
	            'name' => _x( 'Mots-clés Google News', 'taxonomy general name', REWORLDMEDIA_TERMS ),
	            'singular_name' => _x( 'mot-clé Google News', 'taxonomy singular name', REWORLDMEDIA_TERMS ),                
	            'all_items' => __( 'Tous les Mots-clés', REWORLDMEDIA_TERMS ),
	            'edit_item' => __( 'Editer un mot-clé Google News', REWORLDMEDIA_TERMS ),
	            'update_item' => __( 'Modifier mot-clé Google News', REWORLDMEDIA_TERMS ),
	            'add_new_item' => __( 'Ajouter nouveau mot-clé Google News', REWORLDMEDIA_TERMS ),
	            'new_item_name' => __( 'Nouvel mot-clé Google News', REWORLDMEDIA_TERMS ),
	            'menu_name' => __( 'Mots-clés Google News', REWORLDMEDIA_TERMS ),                
	        ),   
	        'capabilities'=>array(
	        	    'manage_terms' => 'edit_posts',
				    'edit_terms' => 'edit_posts',
				    'delete_terms' => 'edit_posts'			    
	        )

	    ) );

	    if ( isset($site_config['has_recipes']) && $site_config['has_recipes'] ) {
	        register_taxonomy( 'ingredient', 'post', array(
	            'hierarchical' => false,
	            'query_var' => 'ingredients',
	            'rewrite' => array( 'slug' => 'ingredients' ),
	            'public' => true,
	            'show_ui' => true,
	            'show_admin_column' => true,
	            'labels' => array(
	                'name' => _x( 'Ingrédients', 'taxonomy general name' ),
	                'singular_name' => _x( 'Ingrédient', 'taxonomy singular name' ),
	                'search_items' =>  __( 'Chercher dans les ingrédients' ),
	                'all_items' => __( 'Tous les ingrédients' ),
	                'edit_item' => __( 'Editer un ingrédient' ),
	                'update_item' => __( 'Modifier ingrédient' ),
	                'add_new_item' => __( 'Ajouter nouvel ingrédient' ),
	                'new_item_name' => __( 'Nouvel ingrédient' ),
	                'menu_name' => __( 'Ingrédients' ),
	            ),
	            
	        ) );
	    }
		
	}

	static function remove_no_js() {
		$removenojs = '
			<script type="text/javascript">
			<!--
				var html = document.getElementsByTagName("html")[0];
				html.className = html.className.replace(\'no-js\' ,\'\');
			//-->
			</script>';
		echo $removenojs;
	}

	/*static function google_play(){
		global $site_config ;
		$google_play_id = isset($site_config['google_play_id']) ? $site_config['google_play_id'] : '';
		$html = "";
		if($google_play_id){			
			$html = '<script type="text/javascript">
			jQuery( document ).ready( function(){
			
				if (navigator.userAgent.match(/Android/i) && document.cookie.indexOf("testandroid") == -1) {
					if (confirm("'.__('Voulez vous télécharger l\'application', REWORLDMEDIA_TERMS).' ' . get_bloginfo('name') .' ?")) {
						document.location = "https://play.google.com/store/apps/details?id='. $google_play_id .'";		
					}
					document.cookie="testandroid=1; expires='. gmdate("D, d M Y H:i:s", time() + 60*60*24)." GMT" .'; path=/";	
				}
					
			} );

			</script>';	
			$html .= "\n" ;	
		}
		echo $html ;
	}*/

	static function add_bootstrap_js(){
		$template_directory_uri = get_template_directory_uri();
		wp_enqueue_script('reworldmedia-bootstrap', $template_directory_uri.'/assets/javascripts/bootstrap/bootstrap.min.js', array(), CACHE_VERSION_CDN, true );
		if(!rw_is_mobile()){
			if( !get_param_global('enlever_masonry_jquery') ) {
				wp_enqueue_script('reworldmedia-bootstrap-masonry', $template_directory_uri.'/assets/javascripts/bootstrap/bootstrap.masonry.min.js', array( 'reworldmedia-bootstrap' ), CACHE_VERSION_CDN, true );
			}
		}else{
			if( get_param_global('disable_carousel_tablet_swipe') ){
				wp_enqueue_script('reworldmedia-touchSwipe', $template_directory_uri.'/assets/javascripts/bootstrap/jquery.touchSwipe.min.js', array(), CACHE_VERSION_CDN, true );
			}
		}
		wp_enqueue_script('reworldmedia-bootstrap-base', $template_directory_uri.'/assets/javascripts/bootstrap/base'.JS_EXT, array( 'jquery' ), CACHE_VERSION_CDN, true );
		if( !get_param_global('remove_footer_js') ){
			wp_enqueue_script('reworldmedia-bootstrap-masonryfooter', $template_directory_uri.'/assets/javascripts/bootstrap/footer'.JS_EXT, array(), CACHE_VERSION_CDN, true );
		}
	}

	static function wpi_stylesheet_uri($stylesheet_uri, $stylesheet_dir_uri) {
		

		if(get_param_global('link_theme_style') ){
			return $stylesheet_dir_uri . get_param_global('link_theme_style') ;

		}elseif(get_param_global('include_scss') ){
			return $stylesheet_dir_uri.'/assets/stylesheets/colors.css';

		}else{
			return $stylesheet_dir_uri.'/style-v2.css';
			
		}
	}

	static function add_meta_name_responsive() {
		echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">';
	}

	static function ad_ie_css_hack() {
		echo '<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	    <![endif]-->';
	}

	static function change_image_class($attr) {
	  $attr['class'] .= ' zoom-it';
	  return $attr;
	} 

	static function filter_content_cleaner($string){
		$cleaner=new Cleaner();
		return $cleaner->clean($string);
	}


	/***********widget text************/
	static function display_title_in_widget_form($t,$return,$instance) {
	    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '') );

	    
	   if($t->id_base!='ninja_forms_widget'){
	   	    $attribut_html = isset($instance['attribut_html_title'])?$instance['attribut_html_title']:'';
	    ?>
	    <p>
	        <input id="<?php echo $t->get_field_id('display_title'); ?>" name="<?php echo $t->get_field_name('display_title'); ?>" type="checkbox" <?php checked(isset($instance['display_title']) ? $instance['display_title'] : 0); ?> />
	        <label for="<?php echo $t->get_field_id('display_title'); ?>"><?php _e('Display title'); ?></label>
	    </p>
			<p><input id="<?php echo $t->get_field_id('clear_html'); ?>" name="<?php echo $t->get_field_name('clear_html'); ?>" type="checkbox" <?php checked(isset($instance['clear_html']) ? $instance['clear_html'] : 0); ?> />&nbsp;<label for="<?php echo $t->get_field_id('clear_html'); ?>"><?php _e('Clear html'); ?></label></p>		
		<p>
			<label for="<?php echo $t->get_field_id('attribut_html_title'); ?>"><?php _e('Attribut HTML title'); ?></label>
			<input id="<?php echo $t->get_field_id('attribut_html_title'); ?>" type="text" value="<?php echo htmlentities($attribut_html); ?>" name="<?php echo $t->get_field_name('attribut_html_title'); ?>" />
		</p>		
		

	    <?php
		}
	    $retrun = null;
	    return array($t,$return,$instance);
	}

	static function display_title_in_widget_form_update($instance, $new_instance, $old_instance) {
	    $instance['display_title'] = isset($new_instance['display_title']);
	    $instance['clear_html'] = isset($new_instance['clear_html']);
	    $instance['attribut_html_title'] = isset($new_instance['attribut_html_title'])?$new_instance['attribut_html_title']:'';
	    return $instance;
	}

	static function recaptcha_script_lang(){
		$lang = substr(get_locale(), 0, 2) ;
		?><script type="text/javascript">
			if(self.RecaptchaOptions){
				RecaptchaOptions.lang= '<?php echo $lang ; ?>' ;
			}
			</script><?php
	}

	static function filter_ninja_forms_admin_capabilities($capability){
		return 'edit_published_posts'; 
	}

	static function do_show_video() {
		 // TODO : Extract the first gallery and print it here.. 
		global $post, $video_top_single;
		//echo "In hook ".$the_main_content=get_the;
		if (preg_match(REG_VIDEO, $post->post_content, $matches)) {
			echo do_shortcode($matches[0]);		
			$video_top_single = $matches[0] ;
		}
	}

	static function init_cats(){	

		$mise_en_avant =  rw_get_category_by_slug('mise-en-avant');
		
		if(!isset($mise_en_avant->term_id)){
			$mise_en_avant = wp_insert_term(
				'Mise en avant', // the term 
				'category', // the taxonomy
				array(
				'slug' => 'mise-en-avant',
				)
			);	
			$mise_en_avant_id = $mise_en_avant['term_id'];
		}else{
			$mise_en_avant_id = $mise_en_avant->term_id ;
		}
		


		$cat = rw_get_category_by_slug(apply_filters('diaporama_accueil_cat','diaporama-accueil'));
		if(!isset($cat->term_id) ){
			wp_insert_term(
				'Diaporama Accueil', // the term 
				'category', // the taxonomy
				array(
				'slug' => 'diaporama-accueil',
				'parent' => $mise_en_avant_id 				
				)
			);
		}	
		
		$cat = rw_get_category_by_slug('verrouillage');
		if(! isset($cat->term_id) ){
			wp_insert_term(
				'Verrouillage', // the term 
				'category', // the taxonomy
				array(
				'description'=> '',
				'slug' => 'verrouillage',
				'parent' => $mise_en_avant_id
				)
			);
		}

		$cat = rw_get_category_by_slug('menu_site');
		if(! isset($cat->term_id) ){
			wp_insert_term(
				'Menu de site', // the term 
				'category', // the taxonomy
				array(
				'description'=> '',
				'slug' => 'menu_site',
				'parent' => $mise_en_avant_id
				)
			);
		}
	}

	static function init_rw(){
		global $recaptcha ;
		remove_action('pre_comment_on_post', array($recaptcha, 'recaptcha_check_or_die'));
		add_action( 'pre_comment_on_post', array( 'RW_Hooks', 'fix_recaptcha_check_or_die' ) );
	}

	static function print_recaptcha_error($comment_field){
		if(isset($_GET['error_recaptcha'])){
			$comment_field .= __("Sorry, the Captcha didn’t verify.",'recaptcha') ;
		}
		return $comment_field ;
	}

	static function google_news_tags_order() {
	    global $wp_meta_boxes;
	    $google_news_tag = $wp_meta_boxes['post']['side']['core']['google_news_tagsdiv'];
	    unset($wp_meta_boxes['post']['side']['core']['google_news_tagsdiv']);
	    $core = array();
	    foreach ($wp_meta_boxes['post']['side']['core'] as $key => $value) {
	    	$core[$key] = $value ;
	    	unset($wp_meta_boxes['post']['side']['core'][$key]);
	    	if($key == 'tagsdiv-post_tag'){
	    		break;
	    	}
	    }
	    $core['google_news_tagsdiv'] = $google_news_tag ;
	    $wp_meta_boxes['post']['side']['core'] = $core + $wp_meta_boxes['post']['side']['core'];
	}

	static function add_datepicker(){
		wp_enqueue_script('jquery-ui-datetimepicker', RW_THEME_DIR_URI.'/assets/js/jquery.datetimepicker.min.js', array('jquery'), '', true );
		wp_enqueue_style('datetimepicker-css', RW_THEME_DIR_URI.'/assets/css/datetimepicker.css');
	}

	static function add_meta_tag_site(){
		global $site_config;
		$metas_tag = isset( $site_config['add_metas_tag'] ) ? $site_config['add_metas_tag'] : false ;
		if(is_single()) {
			echo '<meta name="original-source" content="'.get_permalink().'" />';
			echo '<meta name="syndication-source" content="'.get_permalink().'" />';
		}
		if( is_array($metas_tag) ){
			foreach ($metas_tag as $meta_tag) {
				echo $meta_tag;
			}
		}
		
	}

	static function plant_golbal_meta_box() {
	    add_meta_box(
		    'plant_global_properties', // $id
		    __('Propriétés de la plante' ,  REWORLDMEDIA_TERMS ) , // $title
		    'gen_plant_golbal_meta_box', // $callback
		    'plant', // $page /* stick with $post_type for now */
		    'normal', // $context /* 'normal' = main column. 'side' = sidebar */
		    'high' // $priority /* placement on admin page */
	    );
	}


	static function add_instagram_info( $contactmethods ) {
		// Add instagram+
		$contactmethods['instagram'] = 'Instagram';
		return $contactmethods;
	}

	static function add_pinterest_info( $contactmethods ) {
		// Add pinterest+
		$contactmethods['pinterest'] = 'Pinterest';
		return $contactmethods;
	}

	static function get_the_date_($the_date, $d) {
		global $post;
		$date_format=get_param_global('date_format');
		if ($d ==''  && '' != $date_format ) {
			$the_date = mysql2date( $date_format, $post->post_date );
		}
		return $the_date;
	}

	static function add_class_body_short_code( $c ) {

	    global $post;
	    if(isset($post->post_content)){
		    if(has_shortcode( $post->post_content, 'plan_du_site' ) ) {
		        $c[] = 'body-plan-site';
		    }
		    if(is_single() && page_has_video($post)){
		        $c[] = 'page-has-video';
			}
	    }
	    return $c;
	}

	static function filter_post_categories_en_avant($post_categories){
		$mise_en_avant =  rw_get_category_by_slug('mise-en-avant');
		$cats_en_avant = get_categories(array('parent'=> $mise_en_avant->term_id));
		$cats_en_avant_ids = array();
		foreach ($cats_en_avant as  $value) {
			$cats_en_avant_ids[] = $value->term_id ;
		}
		$cat = rw_get_category_by_slug('diaporama-accueil');
		$cats_en_avant_ids[] = $cat->term_id ;

		foreach ($post_categories as $key =>  $value) {

			if(in_array($value->term_id, $cats_en_avant_ids)) {
				unset($post_categories[$key]);
			}
		}
		return $post_categories ;
	}

	static function acme_product_feed_rss2( $for_comments ) {
	    //$rss_template = locate_template( 'feed-rss2.php');
	    
	    if ( $for_comments )
			load_template( ABSPATH . WPINC . '/feed-rss2-comments.php' );
		else
			load_template( locate_template( 'feed-rss2.php') );
	}

	static function wpq_link_query_args( $query ){
		$query['post_type'] = Array(
	        'post',
	        'page',
	        'attachment'
	    );
	    return $query; 
	}

	//save bitly shorten url as post metadata 
	static function save_shorten_url_metadata($post_id, $post, $update) {
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


	static function admin_head_favicon (){
		if(get_param_global('favicon')){
			$favicon = get_param_global('favicon');
		}else{
			$favicon = get_bloginfo('stylesheet_directory') .'/assets/images-v3/favicon.png';
		}
		?>
		<link rel="shortcut icon" type="image/png" href="<?php echo  $favicon ; ?>">
		<?php
	}

	static function get_meta_posts_by_meta ($meta_queries=array(),$key_get='', $type = 'post', $status = 'publish') {
		global $wpdb;
		$meta_queries=array();
		$key_get='';
		$type = 'car';
		$status = 'publish';
		$request_post = $_POST['postdata'];
		foreach ($_POST['postdata'] as $key => $value) {
			if($key=='key_get')
				$key_get=$value;
			else
				array_push($meta_queries,array('key' => $key, 'value' => $value));
		}

		$args = array(
		'posts_per_page'   => -1,
		'post_type' => $type,
		'post_status' => $status,
		'meta_query' => $meta_queries,
		'fields' => 'ids'
		);
		$postslist = get_posts( $args );
		$posts_ids = implode(',', $postslist);

		$response = array("postslist" => $postslist,"POST" => $_POST['postdata']);
		if((count($postslist)==1 || 1) && $key_get=="id") {

			$name_car=get_the_title($postslist[0]);
			$response['name'] = str_replace('&#215;','x',$name_car);
			$response['id'] = $postslist[0];
			$response['slug'] = get_slug_post($postslist[0]);
		}else {
			if($key_get=="id" && !isset($request_post['CATEGORIE'])) {
				$key_get="CATEGORIE";
			} else if($key_get=="id" && isset($request_post['CATEGORIE']) && !isset($request_post['CARROSSERIE'])) {
				$key_get="CARROSSERIE";
			}
			$meta = array();
			if($posts_ids!='') {
				$meta = $wpdb->get_col( $wpdb->prepare("
		        SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
		        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
		        WHERE pm.meta_key = '%s'
		        AND pm.post_id IN ($posts_ids)
		        AND p.post_status = '%s' 
		        AND p.post_type = '%s'
		    	", $key_get, $status, $type ) );

		    	$response['request'] = $meta;
			}

	    	asort($meta);
	    	$response['options'] = array_to_options($meta);

			
	    	$response['key_get'] = $key_get;
	    	$response['request_post'] = $request_post;
	    	if($key_get=="id") {

				$name_car=get_the_title($postslist[0]);
				$response['name'] = str_replace('&#215;','x',$name_car);
				$response['id'] = $postslist[0];
				$response['slug'] = get_slug_post($postslist[0]);
			}
		}
		echo json_encode($response);		
		exit();
	}

	static function exclure_inread_article_video(){
	    if(is_singular()){
	    	global $post;
	    	if(preg_match(REG_VIDEO, $post->post_content, $matches)){
	    		add_filter('inread_filter', '__return_false');
	    	}
	    }
	}
	static function insert_hmutinread($s){
 
 		$close_form_position =  strpos($s, '</form' )  ;
 		$p = ( strlen($s)>22 ) ?  strpos($s, '</p', 23 + $close_form_position) : 0 ;
 		$div_id = $class_name= '';
 		if( is_single()  && (is_dev('activate_inread_diapos_140910419') || 
 			 (!page_has_gallery() && !page_has_video() )   )){ 
 				$div_id		= "dfp_inread";
 				$class_name	= "DFPinread";
 
 				
 		}
 			
 		if($p >0){
 			$s = substr($s, 0, $p) . '<div id='.$div_id.' class='.$class_name.'></div>' . substr($s, $p) ;
 		}else{
 			$s .= '<div class='.$class_name.'></div>' ;
 		}
 		return $s;
 	}

	static function add_hmutinboard_div(){
		if(get_param_global('inboard_spec_div') && !(is_single() && !page_has_gallery() && !page_has_video())){
			echo ' <div id="dfp_inboard" class="DFP-inboard"></div>';
		}else{
			echo '<div class="hmutinboard"></div>';
		}
	}

	//do_action is called in reworld/include/functions/cron.php
	static function get_popular_analytics($args){
		$end_date   = date('Y-m-d');
		$start_date = date('Y-m-d',strtotime("$end_date -7 day"));
		$custom_actions = get_param_global('custom_ga_actions');
		if( !$custom_actions)
			$custom_actions=[];
		if ( count($custom_actions)){
			foreach ( $custom_actions as $action ) {
				$action ='top_popular_'.$action;
				$optParams = array(
					'dimensions' => 'ga:eventLabel',
					'sort' => '-ga:totalEvents',
					'filters' => 'ga:eventCategory=='.$action,
					'max-results' => '2'	
				);
				$row = $args['service']->data_ga->get(
					$args['ga_api_id'],
					$start_date,
					$end_date,
					'ga:totalEvents' ,
					$optParams		   
				);
				// retrieve data ga of simple article
				$items = array(); 	 
				if(isset($row["rows"])){
					
					foreach($row["rows"] as $k => $v){
						$items[$k] = $v[0] ;
					}
					echo "<pre>";
				    print_r ('<br/> -------- <br/>'.$action.'<br />');
				    print_r ($items);
				    echo "</pre>";
					update_option($action, $items);
				}

			}
		}	
	}

	static function integration_scrolldepth(){
		if(get_param_global('active_scrolldepth')){
			echo "<script>
				jQuery.scrollDepth();
			</script>\n" ;
		}
	}

	// static function integration_quantum(){
	// 	if(get_param_global('active_script_quantum')){
	// 		$html = "<script type=\"text/javascript\" id=\"ean-native-embed-tag\" src=\"//cdn.elasticad.net/native/serve/js/nativeEmbed.gz.js\"></script>\n" ;
	// 		$html = apply_filters('quantum_filter', $html);
	// 		echo $html;
	// 	}
	// }

	static function replace_main_sidebar($main_sidebar) {
		if( defined('MOBILE_MODE') && MOBILE_MODE)
			$main_sidebar = 'sidebar-mobile' ;

		return $main_sidebar ;
	}

	static function add_vary_header($headers) {
		$headers['Vary'] = 'User-Agent';
		return $headers;
	}

	static function px_transparent_outbrain() {
		if (get_param_global('px_transparent_outbrain'))
			echo get_param_global('px_transparent_outbrain');
	}

	static function ajouter_dimensions_preso($s){
		global $wp_query ,$has_gallery, $site_config_js ;

		$js_tag_ga = is_dev('maj_implementation_ga_153564655') ? "gtag('set', key, value);" : "ga('set', key, value);";

		$s .= "function rw_ga_set_dimension (key, value){ ".$js_tag_ga." }";

		if(!empty( $site_config_js['other_google_analytics_ids'])){
			if(is_dev('maj_implementation_ga_153564655')){
				foreach ($site_config_js['other_google_analytics_ids'] as $ga_name => $ga_id) {
					$s .= "gtag('set', { 'send_to' : '"+$ga_id+"',key: value });\n";
				}
			}else{
				foreach ($site_config_js['other_google_analytics_ids'] as $ga_name => $ga_id) {
					$s .= " ga( '{$ga_name}.set', key, value);\n";

				}
			}
		}


		if(get_post_type() == 'attachment'  ){
			$s .= "
			var dimensionValue = 'Media';
			rw_ga_set_dimension('dimension1', dimensionValue);
			";
		}elseif(is_singular() && in_array(get_post_type(), array('post' , 'folder'))){
			$s .= "
			var dimensionValue = 'article';
			rw_ga_set_dimension('dimension1', dimensionValue);
			var dimensionValue = '".get_the_ID()."';
			rw_ga_set_dimension('dimension2', dimensionValue);
			var dimensionValue = '".get_the_title()."';
			rw_ga_set_dimension('dimension3', dimensionValue);	
			var dimensionValue = '".get_the_date('F')."';
			rw_ga_set_dimension('dimension4', dimensionValue);	
			var dimensionValue = '".get_the_date('Y')."';
			rw_ga_set_dimension('dimension5', dimensionValue);
			var dimensionValue = '".get_the_date()."';
			rw_ga_set_dimension('dimension6', dimensionValue);			
			" ; 
			$format = 'news' ;
			if(get_post_type() == 'folder'){
				$format = 'dossier' ;
			}elseif($has_gallery){
				$format = 'diaporama' ;
			}

			$s .= "var dimensionValue = '$format';
			rw_ga_set_dimension('dimension7', dimensionValue);
			";
			$provenance = get_post_meta(get_the_ID(), 'provenance', true) ;
			if($provenance == "1"){
				$provenance_value = "exclu" ;
			}else{
				$provenance_value = "republication" ;
			}
			$s .= "var dimensionValue = '$provenance_value';
			rw_ga_set_dimension('dimension10', dimensionValue);
			";
			/* dimension Longueur de l'article #4180 */
			if(get_param_global('dimension_longeur_article')){
				global $post;
				$content = $post->post_content;
				$content_array = explode(' ', $content );
				$content_count = count($content_array);
				if($content_count < 300 ){
					$s .= "var dimensionValue = '- 300';
					rw_ga_set_dimension('dimension11', dimensionValue);";
				}elseif( $content_count < 600 ){
					$s .= "var dimensionValue = '300-600';
					rw_ga_set_dimension('dimension11', dimensionValue);";
				}elseif( $content_count < 800 ){
					$s .= "var dimensionValue = '600-800';
					rw_ga_set_dimension('dimension11', dimensionValue);";
				}else{
					$s .= "var dimensionValue = '+ 800';
					rw_ga_set_dimension('dimension11', dimensionValue);";
				}
			}
		}elseif(is_home()){
			$s .= "
			var dimensionValue = 'Home';
			rw_ga_set_dimension('dimension1', dimensionValue);
			";		
		}elseif(is_tag()){
			$s .= "
			var dimensionValue = 'Tag';
			rw_ga_set_dimension('dimension1', dimensionValue);
			";		
		}elseif(is_search()){
			$s .= "
			var dimensionValue = 'Recherche';
			rw_ga_set_dimension('dimension1', dimensionValue);
			";			
		}elseif(is_category()){
			$s .= "
			var dimensionValue = 'Catégorie';
			rw_ga_set_dimension('dimension1', dimensionValue);
			";	
			$cat_parent_id = apply_filters('cat_parent_id', 0);
			$cat = $wp_query->queried_object ;
			$cat_name = addslashes( $cat->name );
			if($cat->term_id == $cat_parent_id OR $cat->parent == $cat_parent_id ){
				$s .= "
			var dimensionValue = '{$cat_name}';
			rw_ga_set_dimension('dimension8', dimensionValue);
				";	
			}else{
				$s .= "
			var dimensionValue = '{$cat_name}';
			rw_ga_set_dimension('dimension9', dimensionValue);
				";		
			}

		}
		$s = apply_filters("custom_dimension_ga",$s);
		return $s;
	}

	static function add_custom_dimensions() {
		global $wp_query ,$has_gallery, $site_config_js;
		
		$dimensions = [];

		if(get_post_type() == 'attachment'){
			$dimensions['dimension1'] = 'Media';
		}elseif(is_singular() && in_array(get_post_type(), array('post' , 'folder'))){
			$dimensions['dimension1'] = 'article';
			$dimensions['dimension2'] = get_the_ID();
			$dimensions['dimension3'] = get_the_title();
			$dimensions['dimension4'] = get_the_date('F');
			$dimensions['dimension5'] = get_the_date('Y');
			$dimensions['dimension6'] = get_the_date();
			$cat = RW_Category::get_permalinked_category(get_the_ID(), true);
			if(!empty($cat)) {
				$dimensions['dimension8'] = $cat->name;
			} 
			$format = 'news';
			if(get_post_type() == 'folder'){
				$format = 'dossier';
			}elseif($has_gallery){
				$format = 'diaporama';
			}
			$dimensions['dimension7'] = $format;
			$provenance = get_post_meta(get_the_ID(), 'provenance', true) ;
			if($provenance == "1"){
				$provenance_value = "exclu" ;
			}else{
				$provenance_value = "republication" ;
			}
			$dimensions['dimension10'] = $provenance_value;
			if(get_param_global('dimension_longeur_article')){
				global $post;
				$content = $post->post_content;
				$content_array = explode(' ', $content );
				$content_count = count($content_array);
				$longueur = '';
				if($content_count < 300){
					$longueur = '- 300';
				}elseif($content_count < 600){
					$longueur = '300-600';
				}elseif($content_count < 800){
					$longueur = '600-800';
				}else{
					$longueur = '+ 800';
				}
				$dimensions['dimension11'] = $longueur;
			}
		}elseif(is_home()){
			$dimensions['dimension1'] = 'Home';	
		}elseif(is_tag()){
			$dimensions['dimension1'] = 'Tag';
		}elseif(is_search()){
			$dimensions['dimension1'] = 'Recherche';
		}elseif(is_category()){
			$dimensions['dimension1'] = 'Catégorie';
			$cat = $wp_query->queried_object;
			$cat_name = addslashes($cat->name);
			$dimensions['dimension8'] = $cat_name;	
		}

		$dimensions = apply_filters("custom_data_dimension_ga", $dimensions);
		return $dimensions;
	}

	static function ml_custom_image_choose( $args ) {
		global $_wp_additional_image_sizes;

		foreach( $_wp_additional_image_sizes as $key => $value ) {
			$custom[ $key ] = ucwords( str_replace( '-', ' ', $key ) );
		}

		return array_merge( $args, $custom );
	}

	static function widget_before_comment_block(){
		$sadebar_name =apply_filters('filter_all_sidebar','before_comment_block') ;
		if (is_active_sidebar($sadebar_name)) { 
			dynamic_sidebar( $sadebar_name );
		} 
	}

	//Integration du tags adleave
	static function integration_tag_adleave(){
		echo"<script type='text/javascript'>
			(function() {
			var valtmp = Math.floor((Math.random() * 10000000) + 1);
			var adlS = document.createElement('script'); adlS.type = 'text/javascript'; adlS.async = true;
			adlS.src = 'http://www.adwidecenter.com/adlscript/adleavescr.php?tmp='+valtmp+'&idpub=". get_param_global("tags_adleave_id") ."&display=over';
			adlS.id = 'adLeaveScript';
			var sadlS = document.getElementsByTagName('script')[0]; sadlS.parentNode.insertBefore(adlS, sadlS);
			})();
			</script>";
	}

	/**
	* ticket start #102844154 : Spécifier les articles diapo dans le backoffice
	* Cette fonctionnalité ajoute l'article automatiquement aprés sauvegarde à la cat : Galerie.
	* khalil@webpick.info
	*/
	static function save_post_galerie($post_id, $post) {
		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) OR  wp_is_post_revision( $post_id ) )   {
			return $post_id;
		}
		$idObj = rw_get_category_by_slug('galerie');
		$new_array_category=array();
		if ( has_shortcode( $post->post_content, 'gallery' ) ){
			if(empty($idObj->term_id) ){
				$cat_galerie = wp_insert_term(
					'Galerie',
					'category',
					array(
						'slug' => 'galerie',
						)
					);
				$id_cat_galerie = $cat_galerie['term_id'];
			}else{
				$id_cat_galerie = $idObj->term_id;
			}
			if(!in_array($id_cat_galerie, $new_array_category)){
				$new_array_category[]=$id_cat_galerie;
			}
			wp_set_post_categories( $post_id, $new_array_category , true );
		}
		else
		{
			if(in_category( 'galerie', $post )){
				$categories = get_the_category($post_id) ;
				if ( ! empty( $categories ) ) {
					foreach ($categories as $c) {
						if($c->term_id == $idObj->term_id)
							continue;
						$new_array_category[]=$c->term_id;
					}
					wp_set_post_categories( $post_id, $new_array_category );
				}
			}
		}
	}

	static function rw_save_post_videos($post_id, $post) {
		
		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) OR  wp_is_post_revision( $post_id )  OR  defined('DOING_SYNC_CONTENT') )   {
			return $post_id;
		}
		
		if(preg_match (  REG_VIDEO , $post->post_content , $matches )){

			$cat_video = apply_filters('cat_video', array('name' => 'Videos', 'slug'=> 'videos'), $post);

			$slug = $cat_video['slug'] ;
			$idObj = rw_get_category_by_slug($slug);
			if(!$idObj->term_id){
				$video_cat = wp_insert_term(
					$cat_video['name'] ,
					'category',
					array(
						'slug' => $slug,
						)
					);	
				$id_cat_videos = $video_cat['term_id'];
			}else{
				$id_cat_videos = $idObj->term_id;
			}

			$new_array_category=array();
			$post_category = get_the_category($post_id) ;
			foreach ($post_category as $c) {
				$new_array_category[]=$c->term_id;
			}
			if(!in_array($id_cat_videos, $new_array_category)){
				$new_array_category[]=$id_cat_videos;
			}
			wp_set_post_categories( $post_id, $new_array_category );
		}

	}


	//Facebook ads pixel tracking
	static function fb_ads_pixel_tracking(){
		$fb_pixel_id = get_param_global('fb_ads_pixel_tracking_id');
		echo '<script>(function() {
			var _fbq = window._fbq || (window._fbq = []);
			if (!_fbq.loaded) {
			var fbds = document.createElement("script");
			fbds.async = true;
			fbds.src = "//connect.facebook.net/en_US/fbds.js";
			var s = document.getElementsByTagName("script")[0];
			s.parentNode.insertBefore(fbds, s);
			_fbq.loaded = true;
			}
			_fbq.push(["addPixelId", "'. $fb_pixel_id .'"]);
			})();
			window._fbq = window._fbq || [];
			window._fbq.push(["track", "PixelInitialized", {}]);
			</script>
			<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id='. $fb_pixel_id .'&amp;ev=PixelInitialized" /></noscript>';
	}

	static function body_class_devs($classes){
		global $devs ;
		if(is_array($devs) && count($devs)){
			foreach ($devs as $key => $value) {
				if(!(isset($value['no_css']) && $value['no_css']) && is_dev($key)){
					$classes []= $key ;
				}
			}
		}
		return $classes ;
	}

	//******************************
	//			Template V3
	//******************************


	static function topbar_social_links(){
		echo do_shortcode('[social_links in_header=true]');

		do_action('after_social_links') ;
		?>
		<div class="col-mag col-xs-1 col-sm-3 col-md-2 pull-right">
			<?php
				display_header_image();
			?>
		</div>
		<?php
	}

	static function add_toggle_btn(){
		$toggle_pos = "";
		if(get_param_global('navbar_pull_right') ){
			$toggle_pos = 'pull-right'; 
		}else {
			$toggle_pos = 'pull-left';
		}
		echo '<div class="navbar-header ' . $toggle_pos . '">
			<button type="button" class="navbar-toggle">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>';
	}

	static function topbar_search(){
		$menu_id = apply_filters('get_top_menu_name', 'menu_top_pages');
		$menu_items_list = wp_get_nav_menu_items($menu_id);

		$hide_search_top = get_param_global('hide_search_top');
		$class_default_top_header="col-sm-7 col-md-8 col-lg-8";
		if(isset($hide_search_top) && $hide_search_top){ 
			$class_default_top_header="col-sm-12 col-md-12 col-lg-12";
		}
		
		if(is_array($menu_items_list)){
		 ?>
		<div class="top-pages-menu pull-left ">
			<?php 
			$menu_id = apply_filters('get_top_menu_name', 'menu_top_pages');
			wp_nav_menu(array('menu' => $menu_id, 'theme_location' => 'primary', 'menu_class' => 'nav nav-pills pull-left hidden-xs hidden-sm', 'child_of' => '$PARENT' )); ?>    
		</div>
		<?php } ?>
		<div class="right_block pull-right col-sm-12 col-md-3 col-lg-3">
			<?php 
			if(is_array($menu_items_list) && rw_is_mobile()){
				echo convert_menu_mobile(); 
			}
			if(isset($hide_search_top) && !$hide_search_top){
				echo '<div class="hidden-xs pull-right col-sm-8 col-md-12"> ';
				get_search_form();
				echo '</div>';
			}	
		?>
		</div>
		<?php
	}

	static function nav_menu_logo(){
		if( rw_is_mobile() ){
		?>
			<div class="block_top_menu visible-xs">
				<span class="close">&nbsp;</span>
				<div class="row">
					<?php get_search_form(); ?>
				</div>
			</div>
			<?php
			$hide_navbar_logo = get_param_global('hide_navbar_logo');
			if(!isset($hide_navbar_logo)){
			$home_url = esc_url(apply_filters('logo_home_url', home_url('/')));
			$navbar_logo = apply_filters('navbar_logo', get_stylesheet_directory_uri() .'/assets/images-v2/navbar-logo.png?v=3');
			?>
			<a class="navbar-brand hidden-xs" href="<?php echo $home_url; ?>">
				<img src="<?php echo $navbar_logo; ?>" class="header-image img-responsive" alt="<?php bloginfo('name');?>" />
			</a>
		<?php
			}
		}
	}


	static function after_footer_links(){
		?>
		<div id="footersite">
				<div class="container">
			        <?php echo get_the_menu_footer_pages(); ?>
			        <?php $img_logo_footer = apply_filters('default_logo_footer_site', get_stylesheet_directory_uri() ."/assets/images-v2/logo_footer.png");?>
			        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="pull-right footer_logo">
			        	<?php if( is_dev('lazy_loading_3610') ){ ?>
			        		<img data-src="<?php echo $img_logo_footer; ?>" src="<?php echo RW_THEME_DIR_URI; ?>/assets/images/blank.gif" alt="<?php echo apply_filters("alt_logo_footer","Logo") ?>" class="lazy-load"/>
			        	<?php }else{ ?>
			        		<img src="<?php echo $img_logo_footer; ?>"  alt="<?php echo apply_filters("alt_logo_footer","Logo") ?>"/>
			        	<?php } ?>
			        </a>
			    </div>
			</div>
		<?php
	}
	//end

	/**
	 * Logo personnalisé pour l'espace admin ainsi que le style
	 * @return string
	 */
	static function custom_login_logo_admin() {
		// noms du logo sur tous les sites sont les mêmes
		$logo = get_param_global('bo_login_logo', '/assets/images-v2/main-logo.png');

	    echo '<style type="text/css">
	          	#login h1 a 
	    		{ 	background-image:url(' . get_stylesheet_directory_uri() . $logo .') !important; 
					width : 100%;
					margin-bottom: 10px;

					background-position: center;
					background-size: contain;
	    			height: 120px;
				}

				#login h1
				{
					width : 100%;
				}
	          </style>';
	}


	static function add_comment_block($post_id, $is_quizz, $is_folder){
		if(comments_open($post_id) && !$is_quizz && (!isset($is_folder) || !$is_folder) && !get_param_global('hide_comment_template')) { 
			$comment_btn_link = apply_filters('comment_btn_link', '#');
			?>
			<div class="comment-btn text-center">
				<a href="<?php echo $comment_btn_link; ?>" class="btn btn-default"><?php _e('Commenter cet article', REWORLDMEDIA_TERMS); ?></a>	
			</div>
		<?php } 
	}

	static function script_for_device( $script, $device = 'is_desktop' ) {
		if($script){
			$script =  preg_replace('/<script.*>/i', "<script type=\"text/javascript\">\nif(" . $device . "){", $script );
			$script =  preg_replace('/<script>/i', "<script type=\"text/javascript\">\nif(" . $device . "){", $script );
			$script =  preg_replace('/<\/script>/i', "}\n</script>", $script)   ;
			$script =  preg_replace('/>}\n</i', "><", $script)   ;
		}
		return $script ;
	}

	static function export_search_results_to_csv() {
		if(is_search()){
			$script = "<script>
			jQuery( '#posts-filter' ).children('p.search-box').children('#search-submit').after( '<input type=\"button\" class=\"button\" name=\"button_search_results_to_csv\" value=\"Export Search\" />' );
			jQuery('input[name=button_search_results_to_csv]').click(function(){
				val = jQuery('input[id=post-search-input]').val();
				var url = '". home_url()  ."/?s='+val+'&feed=csv' ;
				window.location = url ;

				});</script>";
			echo $script;
		}
	}

	static function sharer_after_author(){
		global $is_quizz;
	    if (get_param_global('pos_date_post_content')!="none" && !$is_quizz ) {
	    //echo simple_addthis_single();
	        echo '<div class="sharer_after_comment">' . do_shortcode("[simple_addthis_single]") . '</div><br class="clearfix">';
	    }
	}

	static function remove_accents_upload_filter( $file ){
	    $file['name'] = rw_remove_accents ($file['name']);
	    $file['name'] = strtolower($file['name']) ;
	    return $file;
	}

	static function force_posts_per_page($posts_per_page){
		global $wp_query;
		$changed = false;
		if(is_category()) {
			$dedicated_area = get_param_global('dedicated_area');
			if($dedicated_area){
				$category_slug = $wp_query->query_vars['category_name'];
				if(isset($dedicated_area[$category_slug])){
					if(isset($dedicated_area[$category_slug]['posts_per_page_archive'])){
						$posts_per_page =  $dedicated_area[$category_slug]['posts_per_page_archive'];
						$changed = true;
					}
				}else{
					$category = rw_get_category_by_slug($category_slug);
					if($category &&  $category->term_id){
						$parent_cat = get_category_parents($category->term_id, FALSE, ':', TRUE);
						$parent_cat_tree = explode(':',$parent_cat);
						foreach ($parent_cat_tree as $current_parent_cat) {
							if(isset($dedicated_area[$current_parent_cat]) && isset($dedicated_area[$current_parent_cat]['posts_per_page_archive'])){
								$posts_per_page =  $dedicated_area[$current_parent_cat]['posts_per_page_archive'];
								$changed = true;
								break;
							}
						}
					}
				}
			}
			
			if(!$changed){
				$posts_per_page = get_param_global('posts_per_page_archive', $posts_per_page);
			}
			
		}
		return $posts_per_page;
	}

	static function show_sidebar_below_title(){
		echo '<div id="below_title_sidebar">';
		dynamic_sidebar('below_title_sidebar'); 
		echo '</div>';
	}


	static function uri_linear_gallery_image_scroll(){
		$gallery_type = Rw_Post::get_gallery_type();
		if ( $gallery_type == 'linear' || $gallery_type == 'linear_mobile' ) {
			wp_enqueue_script( 'linear_gallery', RW_THEME_DIR_URI . '/assets/javascripts/linear_gallery.js', array('jquery'), CACHE_VERSION_CDN, true );
		}
	}

	static function last_and_first_pagination($r,$paged,$max_page){
		$html='';
		$current_ng_class = '';
		if ( $paged != 1 ){
			$html.= '<li class="page-numbers"><a class="first page-numbers" href='.get_pagenum_link(1).'>'.__('Première page', REWORLDMEDIA_TERMS).' </a></li>';
			$current_ng_class .= 'last_page ';
		}
		$html.=$r;
		if ( $max_page > 1 && $paged != $max_page ){
			$html.= '<li class="page-numbers"><a class="last page-numbers" href='.get_pagenum_link($max_page).'> '.__('Derniére page', REWORLDMEDIA_TERMS).'</a></li>';
			$current_ng_class .= 'first_page ';
		}

		if($paged != $max_page && $paged != 1){
			$current_ng_class = '';
		}
		$html.= '<li><span class="current_page_nb '. $current_ng_class .'">'. $paged .'/'. $max_page .'</span></li>';
		return $html;
	}

	static function post_init_sharedcount_total( $post_id, $post ) {

		if ( (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) OR $post->post_type == 'revision' ) {
			return;
		}
		
		if(!in_array($post->post_type , array( 'attachment', 'nav_menu_item', 'page'))){
			$sharedcount_total = get_post_meta($post_id, 'sharedcount_total', true );
			if(!$sharedcount_total){
				update_post_meta($post_id,'sharedcount_total', 0);
			}
		}
	}

	static function on_click_play_video_carousel($img_attr, $post){

	 	if(get_param_global('play_carousel_video')){
			$video_params = get_video_params($post->post_content);
			if(count($video_params) && ($video_params['type'] == 'youtube' || $video_params['type'] == 'dailymotion')){
				$id_block = md5(serialize($video_params));
				$id_player = "player_".$id_block;
				echo '<div id="' . $id_player . '"  ></div>';
				$img_attr['onclick'] = "play_carousel_video(event, '". $id_player ."','". $video_params['type'] ."', '".$video_params['video_id']."', '".$video_params['url']."')" ;
			}

			$template_directory_uri = get_template_directory_uri();

			if (!is_dev('passage_du_player_sur_jw6_111776366') && is_dev('Passage_du_player_sur_videojs')){
				wp_enqueue_script('reworldmedia-videojs', '//vjs.zencdn.net/4.7/video.js', array(), CACHE_VERSION_CDN, true);
				rw_enqueue_style('reworldmedia-videojs-css' , '//vjs.zencdn.net/4.7/video-js.css', array(), CACHE_VERSION_CDN);
				wp_enqueue_script('reworldmedia-videojs-youtube', $template_directory_uri . '/assets/videojs-plugins/youtube.js', array('reworldmedia-videojs'), CACHE_VERSION_CDN, true);

				wp_enqueue_script('reworldmedia-videojs-ga', $template_directory_uri . '/assets/videojs-plugins/videojs.ga.min.js', array('reworldmedia-videojs'), CACHE_VERSION_CDN, true);
				wp_enqueue_script('reworldmedia-videojs-ads', $template_directory_uri . '/assets/videojs-plugins/videojs.ads.js', array('reworldmedia-videojs'), CACHE_VERSION_CDN, true);
				rw_enqueue_style('reworldmedia-videojs-css-ads' , $template_directory_uri . '/assets/videojs-plugins/videojs.ads.css', array(), CACHE_VERSION_CDN);

				rw_enqueue_style('reworldmedia-videojs-css-vast' , $template_directory_uri . '/assets/videojs-plugins/videojs.vast.css', array(), CACHE_VERSION_CDN);
				wp_enqueue_script('reworldmedia-videojs-vast-client', $template_directory_uri . '/assets/videojs-plugins/vast-client.js', array('reworldmedia-videojs-ads'), CACHE_VERSION_CDN, true);
				wp_enqueue_script('reworldmedia-videojs-vast', $template_directory_uri . '/assets/videojs-plugins/videojs.vast.js', array('reworldmedia-videojs-vast-client'), CACHE_VERSION_CDN, true);
				$id_pub = get_param_global('id_pub');
				if(isset($id_pub['liverail']) && $id_pub['liverail']){
					wp_enqueue_script('reworldmedia-videojs-liverail', $template_directory_uri . '/assets/videojs-plugins/videojs-liverail.js', array('reworldmedia-videojs-ads'), CACHE_VERSION_CDN, true);
				}
			}
					
		}
		return $img_attr;
	}

	static function add_input_disable_carousel(){
	  ?>
	  <script type="text/html" id="tmpl-my-custom-gallery-setting">
	    <label class="setting">
			<span><?php _e('désactiver le carrousel'); ?></span>
			<input type="checkbox" data-setting="disable_carousel"/>
	    </label>

	  </script>

	  <script>

	    jQuery(document).ready(function(){

	      _.extend(wp.media.gallery.defaults, {
	        disable_carousel: false
	      });

	      wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
	        template: function(view){
	          return wp.media.template('gallery-settings')(view)
	               + wp.media.template('my-custom-gallery-setting')(view);
	        }
	      });

	    });

	  </script>
	  <?php

	}

	static function drop_down_menu($menu_item){
		$sub_ul_menu = '';
		if ( isset($menu_item->sous_menu ) && get_param_global('show_menu_last_post_and_items_small_list',true)){
			$sub_ul_menu .= '<div class="menu-last-posts hidden-xs col-sm-4 col-md-4 col-lg-4 pull-left">' ;
				$sub_ul_menu.= apply_filters('title_menu_header_last_posts','');
				$link_attr = apply_filters('link_attr','',$menu_item->sous_menu['last_post']->ID) ;
				$sub_ul_menu .='<a href="'. $menu_item->sous_menu['last_post']->link .'" title="'. $menu_item->sous_menu['last_post']->post_title .'" '. $link_attr .' >'  ;
				$sub_ul_menu .='<span class="thumbnail">'. $menu_item->sous_menu['last_post']->img  ;
				$sub_ul_menu .='<span class="info_thumbnail_cat"><span class="info_link info_cat">'. $menu_item->sous_menu['last_post']->cat_name .'</span></span>'  ;
				$sub_ul_menu .='</span>'  ;
				$sub_ul_menu .='<div class="caption">'  ;
				$sub_ul_menu .= '<span class="info_link">'. $menu_item->sous_menu['last_post']->date .'</span>';
				$sub_ul_menu .='<span class="title-item-small">'.  $menu_item->sous_menu['last_post']->mini_title .'</span>' ;
				$sub_ul_menu .='</div>'  ;
				$sub_ul_menu .= '</a>';	
			$sub_ul_menu .= '</div>';
			

			$sub_ul_menu .= '<div class="items-small-list hidden-xs col-sm-4 col-md-5 col-lg-5 pull-left">' ;
			$sub_ul_menu.= apply_filters('title_menu_header_items_small','');
			foreach ($menu_item->sous_menu['posts_sticky'] as $post){
				$link_attr = apply_filters('link_attr','',$post->ID) ;
				$sub_ul_menu .= '<div class="thumbnail-item">';
				$sub_ul_menu .= '<span class="info_link">'. $post->date .'</span>';
				$sub_ul_menu .='<a href="'. $post->link .'" title="'. $post->post_title .'" '. $link_attr .'>'. $post->img  ;
				$post_title = $post->mini_title;
				if( get_param_global('display_complete_title_sub_menu') ) {
					$post_title = $post->post_title;
				}
				$sub_ul_menu .='<span class="title-item-small">'. $post_title .'</span>' ;
				$sub_ul_menu .= '</a>';	
				$sub_ul_menu .= '</div>';	
			}
			$sub_ul_menu .= '</div>';
		}

		return $sub_ul_menu;
	}

	static function get_footer_or_header_v2() {
		$val = isset($_GET['template_part_v2']) ? $_GET['template_part_v2'] : false ;
		$is_sticky = isset($_GET['is_sticky']) ? $_GET['is_sticky'] : false ;
		if($val) {
			$stylesheet_directory_uri = get_stylesheet_directory_uri() ;
			$html = '<!DOCTYPE html>
					<!--[if IE 7]>
					<html class="ie ie7" lang="fr-FR">
					<![endif]-->
					<!--[if IE 8]>
					<html class="ie ie8" lang="fr-FR">
					<![endif]-->
					<!--[if !(IE 7) | !(IE 8)  ]><!-->
					<html lang="fr-FR" class="no-js">
					<!--<![endif]-->';

			$header	='<head>
					   <meta charset="utf-8" />
					   <meta name="viewport" content="width=device-width, initial-scale=1.0">
					 ';
			if($val == "header"){
				$template = 'nav-menu.php';
				$template = apply_filters('custom_menu_template', $template);
				$header_template = "<link rel='stylesheet' id='header-css'  href='$stylesheet_directory_uri/assets/stylesheets/header.css' type='text/css' media='all' />" ;
				$header_template .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>';
				$header_template .= "<script type='text/javascript' src='$stylesheet_directory_uri/assets/javascripts/bootstrap/header.js'></script>";
				$header .= apply_filters('template_custom_css', $header_template);

			}elseif($val == "top_header"){
				$stylesheet_directory_uri = get_master_stylesheet_directory_uri();
				$template = 'include/templates/top_header.php';
				$header	.= "<link rel='stylesheet' id='header-css'  href='$stylesheet_directory_uri/assets/stylesheets/header.css' type='text/css' media='all' />" ;
			}else{
				$template = 'template_footer.php';
				$template_directory_uri=get_template_directory_uri();
				$header_template = "<link rel='stylesheet' id='footer-css'  href='$stylesheet_directory_uri/assets/stylesheets/refonte_v3/colors-v3.css' type='text/css' media='all' />" ;
				$header_template .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>';

				$header_template .= "<script type='text/javascript' src='$template_directory_uri/assets/javascripts/jquery.masonry.js'></script>";
				$header_template .= "<script type='text/javascript' src='$template_directory_uri/assets/javascripts/bootstrap/bootstrap.masonry.min.js'></script>";


				if(!rw_is_mobile()){
					$header_template .= "<script type='text/javascript' src='$template_directory_uri/assets/javascripts/bootstrap/footer.js'></script>";
				}
				$header .= apply_filters('template_custom_css', $header_template);
			}

			$header	.=		 '  
					 </head><body>';

			ob_start();
			if($template){

				echo $html;
				echo $header;

				add_style_to_header();
				load_template( locate_template($template)) ;
				echo '</div>' ;


			}
			$content = ob_get_contents();
			ob_end_clean();
			$partternLogoLink = '/<a class="navbar-brand/';
			$partternLogoLinkReplacement = '<a target="_blank" class="navbar-brand';
			$patternLink = '/<a\s([^>]*)target=\"([^\"]*)\"([^>]*)>(.*)<\/a>/siU';
			$replacementLink = '<a $1$3 target="_blank" >$4</a>';

			if($is_sticky){
				$initialclasses ='/<nav\\sclass="(navbar-site text-center navbar navbar-default)/';
				$classToAdd = '<nav style="top:0;" class="$1 navbar-fixed-top';
				$header = '/<header>/';
				$hideHeader='<header style="display:none;">';
				$content = preg_replace($header, $hideHeader, $content);
				$content = preg_replace($initialclasses, $classToAdd, $content);
			}

			// add target="_blank" to each form
			$patternForm = '/<form\s/';
			$replacementForm = '<form target="_blank" ';

			$content = preg_replace($partternLogoLink, $partternLogoLinkReplacement, $content);
			$contentModified = preg_replace($patternLink, $replacementLink, $content);

			echo preg_replace($patternForm, $replacementForm, $contentModified);
			
			echo "</body></html>";
			// the die bellow is added intentionally to break the script.
			exit();
		}
	}

	static function add_accueil_in_breadcrumb ($breadcrumb){
		if(BREADCRUMB_MICRO_DONNEES_HTML){
			$breadcrumb  = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
								<a class="home" href="'. home_url() .'"  itemprop="item">
									<span itemprop="name"> '. __('Accueil' , REWORLDMEDIA_TERMS) .'</span>
								</a>
								<meta itemprop="position" content="CNT_COUNT" />
							</li>' . $breadcrumb  ;
		}else{
			$breadcrumb = '<li > <a class="home" href="'. home_url() .'" >'. __('Accueil' , REWORLDMEDIA_TERMS) .'</a> </li>' . $breadcrumb  ;
		}
		return $breadcrumb;
	}

	static function simple_addthis_share(){
		echo do_shortcode("[simple_addthis_single position='v']");
	}

	static function invalidate_option($option){
		wp_cache_delete($option , 'options');
		wp_cache_delete('alloptions' , 'options');
	}

	static function thumbnail_post_folder(){
		global $post,$is_play_video_home_folder,$has_gallry;
		$id_post = get_the_ID();
		if(has_post_thumbnail($id_post)){
			$yoast_wpseo_title = get_post_meta($id_post, "_yoast_wpseo_title", true);
			if($yoast_wpseo_title != "") {
				$img_attr = array('alt' => $yoast_wpseo_title);
			}else{
				$img_attr = array();
			}
			$img_attr['class'] = '';
			if($has_gallry && !is_dev('rectifier_lazy_load_1092212')){
				$img_attr['data-lazyloading' ]= 'false' ;
			}
			$is_play_video_home_folder =false ;
			if(get_param_global('play_video_home')){
				if(preg_match (REG_VIDEO ,$post->post_content, $matches) && strpos($matches[1], 'youtu') !== false ){
					$is_play_video_home_folder =true ;

					$post_video = $matches[1];
					$param = '{}' ;
					$video_id = '' ;
					$val_split = '/';

					if (strpos($post_video, 'youtu') !== false ) {
						$type='youtube';
						$val_split = (strpos($post_video, '/watch?v=') !== false) ? "/watch?v=" : "/";
					}else if(strpos($post_video, 'dai') !== false){
						$type='dailymotion';								
					}

					if (strpos($post_video, 'youtu') !== false || strpos($post_video, 'dai') !== false) {							
						$api_video = explode($val_split, $post_video);
						$video_id = end($api_video) ;
						if($strpos = strpos($video_id, '&')){
							$video_id = substr($video_id, 0, $strpos);								
						}	
						$post_video = "api:" . end($api_video);							
					}						

					$img_attr['onclick'] = 'playHomeVideo(event, '. $id_post .',\''. $type .'\', \''. $video_id.'\')' ;
				}
			}
			$img_attr['class'] .= 'img-responsive' ;
			echo get_the_post_thumbnail($id_post, 'rw_medium', $img_attr);			
		}
	}

	static function caption_post_folder($title,$excerpt){
		global $post;
		$target_ = get_param_global('target_children_folder','');
		?>
		<a title="<?php echo get_the_title() ;?>" <?php echo $target_;?> href="<?php echo get_permalink(); ?>">		
			<h2>	
				<?php 
				$title = mini_text_for_lines (strip_tags($title),25 , 2);
				echo $title;
				?>	
			</h2>
			<?php if ( !get_param_global('hide_folder_post_excerpt') && !get_param_global('disable_folder_description_clicks')) echo '<div class="desc">'.$excerpt.'</div>'; ?>
		</a>
		<?php if ( !get_param_global('hide_folder_post_excerpt') && get_param_global('disable_folder_description_clicks')) echo '<div class="desc">'.$excerpt.'</div>'; ?>

		<?php if(get_param_global('show_date_item_folder_type')){?>
			<div class="date"><?php echo get_the_date();?></div>
		<?php }
	}


	static function display_cat_and_social_links($cat_link){
		global $post;
		echo $cat_link; 
		echo shortcode_social_links_single_v2(get_permalink().'#post-'.get_the_ID(),get_the_title());
	}


	static function sticky_menu_with_share_btn(){                         
		    $html = '';
		    $img_logo = apply_filters('default_logo_site', get_stylesheet_directory_uri() .'/assets/images-v2/main-logo.png'  );
	      	$html .='<div class="blockShare_fixed">';
	      		$html .='<div class="sticky-menu">';
		    		$html .='<a href="'. home_url('/') .'" class="navbar-brand hidden-xs"><img src="'.$img_logo.'" 
		    			alt="'.get_bloginfo('name').'" class="main-logo img-responsive"></a>';
					$html .= do_shortcode("[simple_addthis_single]");
	    		$html .='</div>';
	    	$html .='</div>';
	    	echo $html;
	}

	static function add_rss_description(){
		echo "<description><![CDATA[";
		the_excerpt_rss();
		echo "]]></description>";
	}

	/**
	 * get random next video 
	 * @return json : parameters of the next video
	 */
	static function ajax_random_post_video(){
		if( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'ajax_random_post_video') ){	
			
			if(is_dev('optimize_query_video_tr_1088')){		
				$video_tags = array('yout_video','dai_video');

				if ( isset($_REQUEST['force_play_type']) && $force_play_type = $_REQUEST['force_play_type'] ) {
					if ($force_play_type == 'youtube') {
						$video_tags = array('yout_video');
					}else if ($force_play_type == 'dailymotion') {
						$video_tags = array('dai_video');
					}
				}

				$post_rand = get_posts( 
					array(
						'tag' => $video_tags, 
						'numberposts' => 1,
						'orderby' => 'rand',
	    				'order'    => 'DESC'
					) 
				);	
			}else{

				global $wpdb;
				$search_string = '';
				// Force use the index on post_content
				if(is_dev('opti_sql_video_post_140335669')){
					$media_pattern = "[fpvideo mediaid"; 
					$query_where = 'WHERE match (p.post_content) ';
					if ( isset($_REQUEST['force_play_type']) && $force_play_type = $_REQUEST['force_play_type'] ) {
						if ($force_play_type == 'youtube') {
							$search_string .= $media_pattern.'youtu ]';
						}else if ($force_play_type == 'dailymotion') {
							$search_string .= $media_pattern.'dai ]';
						}
					}
					if(!empty($search_string)){
						$query_where .= "against ('".$search_string."') "; 
					}else {
						$query_where .= "against ('".$media_pattern." youtu ]') OR  match (p.post_content) against ('".$media_pattern." dai ]')";
					}

				}else{
					$media_pattern = '\\[fpvideo%mediaid=%';
					$query_where = ' WHERE p.post_content like ';
					if ( isset($_REQUEST['force_play_type']) && $force_play_type = $_REQUEST['force_play_type'] ) {
						if ($force_play_type == 'youtube') {
							$search_string .= $media_pattern.'youtu%]';
						}else if ($force_play_type == 'dailymotion') {
							$search_string .= $media_pattern.'dai%]';
						}
					}

					if(!empty($search_string)){
						$query_where .= "'%".$search_string."%'"; 
					}else {
						$query_where .= "'%".$media_pattern."youtu%]%' OR p.post_content like '%".$media_pattern.'dai'."%]%'";
					}
				}
				
				$sql_ = "SELECT * FROM ".$wpdb->prefix."posts p ".$query_where." ORDER BY RAND() LIMIT 1";
		  		$post_rand =  $wpdb->get_results($sql_) ;
			}
			
			$video_params = get_video_params($post_rand[0]->post_content);
			$params['post_video'] = $post_rand[0];
			$params['mediaid'] = $video_params['link'];

			$params['video_id'] = $video_params['video_id'];
			if(isset($video_params['type'])){
				if($video_params['type'] == 'youtube'){
					$params['provider'] = 'youtube';
				}else{
					$params['provider'] = 'dailymotion';
				}
			}

			$params_encoded=json_encode($params);
			echo $params_encoded;
			exit;
		}
	}

	static function add_megabanner_top(){
		?>
		<div class="clearfix"></div>
		<div id="megabanner_top">
			<?php
			$sidebar = apply_filters('filter_all_sidebar', 'sidebar-header-megabanner-top') ;
			if (is_active_sidebar($sidebar)) { 
				dynamic_sidebar($sidebar); 
			}	
			?>
		</div>
		<?php
	}


	static function result_search(){
		global $wp_query, $query_string, $post;
		if(count($wp_query->posts)){
			foreach ($wp_query->posts as $post) {
				setup_postdata( $post );
				include(locate_template('include/templates/search_items.php')); 
			}
			wp_reset_postdata();
		}
	}



	static function add_div_offerts($custom){
		$offerts =  isset($custom['offerts'][0])?  $custom['offerts'][0] : '' ;
		if( !empty($offerts) ){ ?>
			<div class="eco_part"><?php echo $offerts;?>&euro; offerts en bon d'achat <br> avec la carte Mr Bricolage</div>    	
		<?php }
	}

	static function add_illustrate_share($html, $attr){
		if( rw_is_mobile() && (empty($attr['position']) || (isset($attr['position']) && $attr['position']!= 'v') )){
			$html_ = '<div id="social_share_mobile">';
			$html_ .= $html;
			$html_ .= '<div id="illustrate"></div>';
			$html_ .= '</div>';
			return $html_;
		}
		return $html;
	}

	static function get_videos_from_other_blog($posts, $player){
		global $switched_blog,$wpdb;
		if( $player && $site_id = get_param_global('get_posts_video_from_other_site') ){
			$switched_blog = get_current_blog_id();
			switch_to_blog($site_id);
			$ids = get_posts_with_common_tags( 1, array(), 10 );
			if( count($ids) ){
				$posts = get_posts( array('post__in' => $ids, 'posts_per_page' => count($ids), 'orderby' => 'post__in' ) );
			}
			restore_current_blog();
		}
		return $posts;
	}

	static function get_tags_current_post_($sql){
		global $switched_blog,$current_post_id_pl;
		if( $switched_blog != get_current_blog_id() && $site_id = get_param_global('get_posts_video_from_other_site') ){
			$prefix_ = "wp_".$switched_blog."_";
			$sql = "SELECT name
	                FROM ".$prefix_."terms wt1
	                JOIN ".$prefix_."term_taxonomy t1 ON wt1.term_id = t1.term_id
	                JOIN ".$prefix_."term_relationships r1 ON r1.term_taxonomy_id = t1.term_taxonomy_id
	                JOIN ".$prefix_."posts p1 ON r1.object_id = p1.ID
	                AND t1.taxonomy LIKE  'post_tag'
	                AND p1.ID =".$current_post_id_pl;
		}
		return $sql;
	}

	static function static_video_playlist($video_params){
		if( !empty($video_params) ){
			$src = '';
			if( $video_params['type'] == 'youtube' ){
				$src = "https://i1.ytimg.com/vi/".$video_params['video_id']."/0.jpg";
			}else if( $video_params['type'] == 'dailymotion' ){
				$src = SITE_SCHEME."://www.dailymotion.com/thumbnail/video/".$video_params['video_id'];
			}
			?>
			<div class="video active" data-provider="<?php echo $video_params['type'];?>" data-scroll="true" data-video-id="<?php echo $video_params['video_id'];?>" data-mediaid="<?php echo $video_params['url'];?>">
				<div class="img_post_video">
					<img src="<?php echo $src;?>" style="height:112px;" alt="video image">
				</div>
			</div>
			<?php
		}
	}
	 
	 
	//split shortcode multi images
	static function split_shortcode_gallery( $post ) {
		if(empty($post->split_gallery) && get_post_meta($post->ID,'post_gallery_template',true) == "linear"){
			$article_pages_number=1;
			//pregmatch for shortcodes
		    preg_match_all( '/' . get_shortcode_regex() . '/s', $post->post_content, $matches, PREG_SET_ORDER );
		    if ( ! empty( $matches ) ) {
		    	//extraction de la partie ids
			    $regex_extract_ids='/"(.*)"/';
			    $ids_comma_separated=array();
			    $ids_string=$matches[0][3];
			    preg_match($regex_extract_ids, $ids_string,$ids_comma_separated);
			    if ( ! empty( $ids_comma_separated ) ) {
				    $ids_array=explode(',', $ids_comma_separated[1]);
				    $ids_array_size=sizeof($ids_array);
				    $post_meta_gallery_number_images=get_post_meta($post->ID,'number_images_per_page',true);
				   	//nombre d'images par page
				    $number_images_per_page= $post_meta_gallery_number_images ? $post_meta_gallery_number_images : $ids_array_size;
				    //array of chunked elements
				    $ids_array_chunked= array();
				    if($ids_array_size>0 && $ids_array_size>$number_images_per_page){
				    	$ids_array_chunked=array_chunk($ids_array, $number_images_per_page);
				    	$article_pages_number=sizeof($ids_array_chunked);
				    }else{
				    	$ids_array_chunked[0]=$ids_array;
				    }
				    //COncatenate the new gallery shortcodes
				    $gallery_shortcodes_array = array();
				    foreach ($ids_array_chunked as $one_page_ids) {
				    	$gallery_shortcodes_array[]='[gallery ids="'.implode(',', $one_page_ids).'"]';
				    }
				    $gallery_shortcodes_splitted=implode('<!--nextpage--> ', $gallery_shortcodes_array);
				    $post->post_content = str_replace($matches[0][0], $gallery_shortcodes_splitted, $post->post_content);
				    	
			    }
		    }
		    $post->split_gallery =1 ;

		}
	    return $post;

	}
	//Add new rewrite rules
	//NB : Remember to save permalinks settings from the BO once this function is edited
	static function create_rewrite_rules_post_in_root_for_multipage($wp_rewrite) {
		$new_rules = array(
			'(.+?)/([^/]+)-([0-9]+).html.?/page?([0-9]{1,})/?/?$' => 'index.php&category_name=$matches[1]&name=$matches[2]&p=$matches[3]&page=$matches[4]',
		);
		$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
		return $wp_rewrite->rules;
	}

	static function multi_page_redirect_canonical($redirect_url, $requested_url) {
		$regex='#(.+?)\/([^\/]+)-([0-9]+).html.?\/page?([0-9]{1,})\/?\/?$#';
		if (preg_match($regex, $requested_url)) {
			return $requested_url;
		} else {
			return $redirect_url;
		}
	}
	//Special treatment for paginated posts first page
	//function to know if is a post and is paginated and linear
	static function rw_is_paginated_linear_post() {
		global $post;
		return is_singular() && ( get_post_meta( $post->ID, 'post_gallery_template', true ) == 'linear' ) && get_post_meta( $post->ID, 'number_images_per_page', true ) && $post->post_status == 'publish';
	}

	//redirects the first page of a post to /page1
	static function redirect_first_page_paginated_post(){
		if(rw_is_paginated_linear_post()){
			$current_url=$_SERVER['REQUEST_URI'];
			$regex='#.*.html.*(page([0-9]+)).*#';
			$url=get_permalink();
			if(!preg_match($regex, $current_url)){
				wp_redirect( $url.'/page1');
				exit;
			}
		}
	}
	//for the filter in wp_link_pages() to rewrite suivant precedent links
	static function rewrite_the_default_pagination_urls ($url) {
		$regex='#(.*\.html\/)(\d+)?(\#)?(.*$)?#';
		if(preg_match($regex, $url,$matches)){
			$url=$matches[1].'page'.$matches[2];
		}
		else {
			$url=$url.'/page1';
		}
		return $url;	
	}
	static function no_index_default_publish_multiimages_post($post) {
		global $post;
		if(get_post_meta($post->ID,'post_gallery_template',true) == "linear"){
			update_post_meta($post->ID,'_yoast_wpseo_meta-robots-noindex','1');	
		}
	}

	//function to save the category of a post depending on the mode
	static function save_cat_mode_multiimages_post($post) {
		global $post;
		$old_post_categories_ids = wp_get_post_categories( $post->ID );
		$category_multiimages=get_term_by('slug','article-multiimages','category');//all term
		$category_diaporama_class=get_term_by('slug','diaporama-classique','category');//All term
		$new_post_categories_ids=array();

		if($_POST['post_gallery_template']){
			foreach ($old_post_categories_ids as $cat_id) {
				if($cat_id != $category_diaporama_class->term_id){
					array_push($new_post_categories_ids,$cat_id);
				}
			}
			array_push($new_post_categories_ids,$category_multiimages->term_id);

		}else{
			foreach ($old_post_categories_ids as $cat_id) {
				if($cat_id != $category_multiimages->term_id){
					array_push($new_post_categories_ids,$cat_id);
				}
			}
			array_push($new_post_categories_ids,$category_diaporama_class->term_id);
			
		}
		wp_set_post_categories( $post->ID,$new_post_categories_ids , false );
	}

	 
	static function indicators_home_diapo($diaporama_accueil){
		global $post;
		?>
		<ol class=" <?php echo apply_filters('carousel_indicators_css', 'carousel-indicators hidden-xs'); ?> ">
		<?php 
			$i=0 ;
		    foreach ($diaporama_accueil as $post) { 		
				setup_postdata($post);
				?>
				<li data-target="#carousel-gallery-generic" data-slide-to="<?php echo $i; ?>" class="<?php echo ($i==0)?'active':''; ?>"> 
					<?php
					$img_attr = array('class'=>'img-responsive' ) ;

					if(!is_dev('activer_le_lazyload_sur_le_home_diapos_3459')){
                 		$img_attr['data-lazyloading'] =   'false' ;
                 	}
 
					 echo get_the_post_thumbnail($post, 'rw_thumb', $img_attr ); ?> 	
				</li>
				<?php
		        $i++;
			}
			wp_reset_postdata(); ?>
		</ol>
		<?php
	}


	/**
	 * add pagination to multi images single page
	 */
	static function add_pagination_to_single_multi_images() {
		if ( is_single() && rw_is_paginated_linear_post() ) {
			//args for the multi images post pagination
			$args = array (
				'before'            => '<div class="page-links">',
				'after'             => '</div>',
				'link_before'       => '<span class="page-link">',
				'link_after'        => '</span>',
				'next_or_number'    => 'next',
				'separator'         => ' ',
				'nextpagelink'      => __( 'Suivant', REWORLDMEDIA_TERMS ),
				'previouspagelink'  => __( 'Precedent', REWORLDMEDIA_TERMS ),
			);

	    	wp_link_pages( $args );
		}
	}

	static function article_intro_excerpt(){ 
		global $post;
		do_action('single_before_excerpt');                                       
		?>
		<div class="article-intro excerpt" style="margin-top:8px">
			<?php 
			
			do_action('single_intro_excerpt');

			do_action('single_after_excerpt');                                       
			?>
		</div> 
		<?php
	}

	static function remove_refresh_teads_on_multiimages () {
		global $post;
		if(get_post_meta($post->ID,'post_gallery_template',true) == "linear"){
			remove_action ('wp_footer', 'refresh_teads_script_js',100);
		}
	}

	static function display_footer(){

		if(is_dev('cache_nav_footer_134446899')){
			get_nav_footer();
		}else{
			load_template( locate_template('template_footer.php')) ;
		}
	}

	static function add_scroller(){ 
		if ( rw_is_mobile() && is_single()) {  
			remove_action('display_footer','display_footer',8);
			remove_action('after_wp_footer','after_wp_footer_',10);
			if (RW_Post::get_gallery_type() != "diapo_monetisation_mobile") {
				add_action('after_wp_footer', array('RW_Hooks', 'show_more_posts') );
			}
		}	 
	}

	static function show_more_posts(){
		echo do_shortcode('[posts_mobile title="Nos autres articles"]');
	}

	static function refresh_teads_script_js(){

	$code_rafraichir_inread = 'jQuery(".teads-inread").remove() ;' ;

	$script = <<<SCRIPT
			<script type="text/javascript">
				jQuery(document).ready(function(){
				    count_item_clicked = window.count_item_clicked || 0;
					jQuery(document).on("change_item" , function(e , i, rafraichir_pub) { 
						if(is_desktop && rafraichir_pub){ 
							var html = '' ;

							if(site_config_js.teads.inread){
								jQuery(".tt-wrapper.inread ").remove() ;
								$code_rafraichir_inread 
								html += site_config_js.teads.inread ;
							}

							if(html){ 
								jQuery('body').append(html) ;
							}
						}
						count_item_clicked++;
					});
				});
			</script>
SCRIPT;
			echo $script ;
	}


	static function fix_recaptcha_check_or_die($post_ID){
		global $recaptcha ;
		if ( !empty($recaptcha) && ! $recaptcha->recaptcha_check() ){
			$link =get_permalink($post_ID);
			$link .= '?error_recaptcha=1&comment='. urlencode(@$_REQUEST['comment']) .'&author='. urlencode(@$_REQUEST['author']) .'&email='. urlencode(@$_REQUEST['email']) .'#comments';
			wp_redirect( $link);
			exit;			
		}
	}

	static function lazy_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ){
			/* accept lazy load if image not contains attribute data-lazyloading*/
		if( !(defined('DOING_AJAX') && DOING_AJAX) && ( !isset($attr['data-lazyloading']) || $attr['data-lazyloading']!='false' ) && !get_param_global('remove_noscript_lazyload') ) {
			$lazied = str_replace ( 'src=' , 'src="'.get_template_directory_uri().'/assets/images/blank.gif" data-src=' , $html );
			$lazied = str_replace ('class="' , 'class="lazy-load ' , $lazied);
			$lazied = apply_filters('edit_default_alt', $lazied) ;
			return $lazied.'<noscript>'.$html.'</noscript>';
		} else {
			return $html;
		}
	}

	static function speedup_lazyloading(){
		echo "<script type='text/javascript'>  lazyLoader.init(true); </script>";
	}

	static function fix_sidebar_onscroll(){ 
		echo "<script type='text/javascript'>jQuery('div.scroll_to_fixed').scrollToFixed(); </script>";
	}

	static function update_teads_config(){
		global $site_config_js ;
		$site_config_js['teads'] = array(
			'inread' =>  apply_filters('inread_filter', get_param_global('inRead_html'),'refresh') ,
			'inboard' => apply_filters('inBoard_filter',get_param_global('inBoard_html'),'refresh') ,
			'infooter' => apply_filters('infooter_filter',get_param_global('infooter_html'),'refresh') ,
		);
	}

	static function init_videojs(){
		init_video_js();
		init_videojs_vast();
	}

	static function last_posts_($msg){
		global $post;
		$html = apply_filters('empty_search_result_msg', '<div class="bar_result_search">'. $msg .'</div>');
		$args = array('posts_per_page' => 10);		
		$last_posts = get_posts( $args);
		$html .= '<div class="row items-posts">';
		if(!empty($last_posts)){
			foreach($last_posts  as $post) {
				setup_postdata($post);
				ob_start() ;
				include(locate_template('include/templates/list-item-normal.php'));
				$html.= ob_get_contents();
				ob_end_clean();	
			}
			wp_reset_postdata();
			$html.='<div class="col-xs-12"><a href="'.get_home_url().'" class="search_all_articles"> Voir tous les articles &raquo;</a></div>';
		}
		$html .= '</div>';
		return $html;
	}

	static function widget_update_delecte_cache( $instance ) { 	
		wp_cache_delete( 'alloptions', 'options' );
		return $instance; 
	}

	static function mce_css_filter($string){
		$array = explode(',', $string);
		foreach ($array as $key =>  $value) {
			if(strpos($value, '?')){
				$array[$key] = $value .'&v=' . CACHE_VERSION_CDN ;
			}else{
				$array[$key] = $value .'?v=' . CACHE_VERSION_CDN ;
			}
			
		}
		$string = implode(',', $array);	
		return $string;
	}

	static function integration_tags_js(){
		if($nugg_ad = get_param_global("nugg_ad")){
			echo $nugg_ad ."\n";
		}elseif($nuggsid = get_param_global("nuggAd_id")){
			echo '<script type="text/javascript">
				var nugg4Rubicon ="";
				var nuggrid= encodeURIComponent(top.location.href);
				document.write(\'<scr\'+\'ipt type=\"text/javascript\" src=\"http://lpm-reworldmedia.nuggad.net/rc?nuggn=72355471&nuggsid='. $nuggsid .'&nuggrid=\'+nuggrid+\'"><\/scr\'+\'ipt>\');
				</script>';
		}
	}


	static function get_footer_or_header() {
		$val = isset($_GET['template_part']) ? $_GET['template_part'] : false ;
		$is_sticky = isset($_GET['is_sticky']) ? $_GET['is_sticky'] : false ;
		if($val) {
			if(!get_param_global('disabled_link_footer_and_header')){

				add_filter('ligatus_filter','__return_false');
				add_filter('outbrain_filter','__return_false');
				add_filter('has_video_popin','__return_false');
				//add_filter('inBoard_filter','__return_false');
				add_filter('customize_mediabong','__return_false');
				add_shortcode('himediads_fullscreen','__return_false');
				add_shortcode('inRead','__return_false');
				// supprime le shortcode de NL Header
				add_shortcode( 'dfp_v2', '__return_false' );
				remove_action('wp_footer', 'accepte_cookies_popin');
				remove_action('wp_head','integration_tags_js');
				remove_action('wp_head','integration_tags_knux');

				$site_config["nuggAd_id"]=false;

				$html = '<!DOCTYPE html>
						<!--[if IE 7]>
						<html class="ie ie7" lang="fr-FR">
						<![endif]-->
						<!--[if IE 8]>
						<html class="ie ie8" lang="fr-FR">
						<![endif]-->
						<!--[if !(IE 7) | !(IE 8)  ]><!-->
						<html lang="fr-FR" class="no-js">
						<!--<![endif]-->';

				$header	='<head>
						   <meta charset="utf-8" />
						   
						 </head>';

				// remove footer pub
				unregister_sidebar('footer-pub');
				// catch the output
				ob_start();
				if($val == "footer"){
					add_action('wp_footer', 'print_site_config_js', 1);
					add_action('wp_footer', 'wp_print_scripts', 5);
					add_action('wp_footer', 'wp_enqueue_scripts', 1);
					add_action('wp_footer', 'wp_print_head_scripts', 5);
					do_action('init_footer_or_header', 'footer');
					echo $html;
					echo $header;
					echo "<body><!-- start body -->\n
							<style>
							iframe,#megabanner_bottom{display:none;}
							@media(max-width: 767px){
								.textwidget{
									max-width:400px;
								}
							}
						</style>";
					echo "<div><!-- start #container-->\n";
					echo "<div><!-- start .container -->\n";
					echo "<div><!-- start .container -->\n";
					get_footer();
					do_action('wp_footer');
				}else {
					add_action('wp_head', 'add_style_to_header');

					add_action('after_barretopinfo', function(){
						echo '<style>#barreTopInfo{display:none;}</style>';
					});
					do_action('init_footer_or_header', 'header');
					get_header();
					echo "</div><!-- .row.wrapper -->\n";
					echo "</div><!-- .container -->\n";
					echo "</div><!-- #container-->\n";
				}
				$content = ob_get_contents();
				ob_end_clean();
				// end catching output
				$partternLogoLink = '/<a class="navbar-brand/';
				$partternLogoLinkReplacement = '<a target="_blank" class="navbar-brand';
				$patternLink = '/<a\s([^>]*)target=\"([^\"]*)\"([^>]*)>(.*)<\/a>/siU';
				$replacementLink = '<a $1$3 target="_blank" >$4</a>';

				if($is_sticky){
					$initialclasses ='/<nav\\sclass="(navbar-site text-center navbar navbar-default)/';
					$classToAdd = '<nav style="top:0;" class="$1 navbar-fixed-top';
					$header = '/<header>/';
					$hideHeader='<header style="display:none;">';
					$content = preg_replace($header, $hideHeader, $content);
					$content = preg_replace($initialclasses, $classToAdd, $content);
				}

				// add target="_blank" to each form
				$patternForm = '/<form\s/';
				$replacementForm = '<form target="_blank" ';

				$content = preg_replace($partternLogoLink, $partternLogoLinkReplacement, $content);
				$contentModified = preg_replace($patternLink, $replacementLink, $content);

				echo preg_replace($patternForm, $replacementForm, $contentModified);
				
				echo "</html>";
				// the die bellow is added intentionally to break the script.
				exit();
			}else{
				exit();
			} 
		}
	}

	static function add_style_to_header() {
		echo '<style>
			   	.container {
			   	 	width:100% !important;
			   	 }
			   	#container{
			   		display : none;
			   	}
			   </style>';
	}

	static function change_title_widget_videos($instance, $widget, $args){
		global $post;
	    if ( is_single() && preg_match (  REG_VIDEO , $post->post_content , $matches ) && (strpos($instance['text'],'filter_videos=true') !== false) ){
	       $instance['title'] = __('Vidéos les + vues' , REWORLDMEDIA_TERMS);
	       remove_filter('widget_display_callback','change_title_widget_videos');
	    }elseif ( isset($instance['text']) && is_single() && page_has_gallery() && (strpos($instance['text'],'filter_gallery=true') !== false) ){
	       $instance['title'] = __('Diaporamas les + lus' , REWORLDMEDIA_TERMS);
	       remove_filter('widget_display_callback','change_title_widget_videos');
	    }elseif( ( isset( $instance['text'] ) && strpos($instance['text'],'filter_videos_list=true') ) && is_category( apply_filters('filter_cat_video','videos') ) ){
	 		$instance['title'] = __('Vidéos les plus vues' , REWORLDMEDIA_TERMS);
	    	remove_filter('widget_display_callback','change_title_widget_videos');
	    }elseif( ( isset( $instance['text'] ) && strpos( $instance['text'], 'filter_dossier=true' ) ) && apply_filters( 'display_most_popular_dossier',false ) ){
	 		$instance['title'] = __('Dossiers populaires' , REWORLDMEDIA_TERMS);
	    	remove_filter('widget_display_callback','change_title_widget_videos');
	    }
	    return $instance;
	}


	static function save_post_diaporama($post_id, $post) {

		if(preg_match ('/\[gallery.*ids=.(.*).\]/' , $post->post_content , $matches )){

			
			if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) OR  wp_is_post_revision( $post_id )  OR  defined('DOING_SYNC_CONTENT') )   {
				return $post_id; 
			}

			$slug = apply_filters( 'category_diapo_slug' ,'diaporama');

			if(isset($slug) && $slug ){  
				$idObj = rw_get_category_by_slug($slug);
				if($idObj->term_id){ 
					$id_cat_diapo = $idObj->term_id;
				}else{ 
					$diapo_cat = wp_insert_term(
						'Diaporama '.ucfirst($section_),
						'category',
						array(
							'slug' => $slug,
							)
						);	
					$id_cat_diapo = $diapo_cat['term_id'];
				}
				$new_array_category=array();
				$post_category = get_the_category($post_id) ;
				if(apply_filters('do_set_category_diapo', true, $post_category)){
					foreach ($post_category as $c) {
						$new_array_category[]=$c->term_id;
					}
					if(!in_array($id_cat_diapo, $new_array_category)){
						$new_array_category[]=$id_cat_diapo;
					}
					wp_set_post_categories( $post_id, $new_array_category );
					
				}
			}
			
		}
	}


	static function add_breadcrumb_post_v2(){ 
		echo breadcrumb();
	}

	/**
	 * Add class to first and last pagination's element
	 * on multiimage pages : wp_link_pages()
	 *
	 * @param array $args Config of wp_link_pages
	 * @return array Return the new config of wp_link_pages
	 */
	function wp_link_pages_args_first_last_add_class( $args ) {
	    global $page, $numpages, $more, $pagenow;

	    $args['before']      = '<div class="page-links">';
	    $args['after']       = '</div>';
	    $args['link_before'] = '<span class="page-link">';
	    $args['link_after']  = '</span>';

	    if ( $page == 1 ) // if this is the first page
	        $args['link_before'] = '<span class="page-link first">';
	    if ( $page == $numpages ) // if this is the last page
	        $args['link_before'] = '<span class="page-link last">';

	    return $args;
	}

	/**
	 * Update meta afficher_signature to 'non' when you save a post
	 *
	 * @param integer $id id of post
	 *
	 */
	public function change_meta_on_publish_post_save( $id ) {
		if ( get_post_meta( $id, 'afficher_signature', true ) == '' ) {
			update_post_meta( $id, 'afficher_signature', 'non' );
		}
	}

	public function hide_nl_footer_crm( $html ) {
		if ( wp_is_mobile() ) {
			$html = '';
		}
		return $html;
	}


	/**
     * lock an event in a category or sub-category pages, depending on a lock end date ( inserted in the event proprieties )
	 * @param $posts :  an array of posts, to which, the event is prepended
	 * @param $where : 'cat' ou 'sub_cat'
	 *
	 * @return modified posts' list
	 */
	public  function modify_posts_list( $posts, $where ){
	    global $wp_query;


        $args = array(
            'numberposts' => 1,
            'post_type' => array( 'schema_event_post', 'schema_car_post', ),
            'category_name' => $wp_query->query['category_name'],
        );
        $event = get_posts( $args );

        if( $event ){
            $lock_end_date = get_post_meta( $event[0]->ID , 'lock_end_date' );
            if( $lock_end_date ){
                switch ( $where ){
                    case 'cat' :
                        if( time() <= strtotime($lock_end_date[0]) ){
                            array_unshift( $posts, $event[0]);
                        }
                        return $posts;
	                case 'sub_cat' :
		                if( time() <= strtotime($lock_end_date[0]) && $wp_query->have_posts() ){
			                array_unshift( $wp_query->posts, $event[0]);
		                }
		                return $wp_query->posts;
                    default:
                        return $posts;
                }
            }
        }
		return $posts;
    }
}



$rw_hooks = new RW_Hooks();