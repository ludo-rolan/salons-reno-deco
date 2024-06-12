<?php
/**
* 
*/
class RW_Post {
	

	private static $_instance;
	private static $metapic_posts_ids = array();
	private static $gallery_type = false;
	private static $post_meta;
	private static $the_post_tag = array();

	function __construct(){
		if( !get_param_global('disable_auto_add_hasvideo') ){
			add_action('save_post', array($this, 'add_hasvideo_tag_to_post'), 10, 2);
		}
		if( !get_param_global('disable_auto_add_hasdiapo') ){
			add_action('save_post', array($this, 'add_hasdiapo_tag_to_post'), 10, 2);
		}
	}
	
	static function get_instance(){
		if(is_null(self::$_instance)){
			self::$_instance = new RW_Post();
		}
		return self::$_instance;
	}
	
	static function last_exposants($count = 3) {
		global $site_config,$posts_exclude;	
		$last_posts = array();
		$menu_id = apply_filters('get_menu_name', 'menu_header', 'menu_header');
		$nbr_element = apply_filters('home_nbr_bloc_category', -1) ;

		$menu_items = wp_get_nav_menu_items( $menu_id );

		$exclude_cats_ids = apply_filters( 'exclude_cats_last_post', array() );
		$index = 0;
		if ( $menu_items){
				
			foreach ($menu_items as $menu_item) {
				$count_by_menu_item = $count ;

				$displayed_cats = array();

				if( !in_array( $menu_item->object_id, $exclude_cats_ids )){
					if($index == $nbr_element) break;
					if ($menu_item->type == 'taxonomy' 
						&& !in_array($menu_item->object_id, $displayed_cats) ) {
						/*save displayed cats*/
						$displayed_cats[] = $menu_item->object_id;
						$bloc = array();
						$bloc["title"] = $menu_item->title;
						$category_parent = get_category($menu_item->object_id);
						$bloc["url"] = get_category_link($category_parent);
						$bloc["category"] = $menu_item->object_id;
						$bloc["category_object"] = $category_parent;
						foreach ($menu_items as $menu_item){
						    if($menu_item->object_id == $bloc["category"]){
						        $menu_item_parent = $menu_item->ID ;
						        $bloc["menu_item_parent"] = $menu_item_parent;
						        break ;
						    }
						}
						if(!get_param_global('hide_post_push') && empty($bloc["post_push"])){
								$count_by_menu_item ++;
						}
						$args = array(
							'showposts' => $count_by_menu_item, 
							'category' =>$menu_item->object_id, 
							'post__not_in' => $posts_exclude,
							'post_type' => ['exposant'],
						);

						$args = apply_filters( 'last_posts_by_category_args' , $args, $bloc );
						$args = apply_filters( 'last_post_args_by_menu' , $args, $menu_item, $bloc );
						
						// ajouter le verrouillage #7986
						if( defined('_LOCKING_ON_') && _LOCKING_ON_ && is_home() && $args_lock = get_locking_config( 'home' , 'bloc_rubrique_'.$menu_item->object_id) ){
							$bloc["posts"] = Locking::get_locking_ids($args_lock , $args);
						}else{
							$bloc["posts"] = get_posts( $args);
						}

						foreach ($bloc["posts"] as $key => $post) {
							$posts_exclude = RW_Utils::add_value_to_array($post->ID, $posts_exclude);
						}
						$condition = !get_param_global('hide_post_push') &&  empty($bloc["post_push"]) && count($bloc["posts"]);

						if( apply_filters('block_post_push',$condition ,$bloc['category'] ) ) {
							$bloc["post_push"] =  array_splice($bloc["posts"], 0,1)[0];
						}
						array_push($last_posts, $bloc);
						$index++;
					}
				}
			}
		} 
		return $last_posts;
	}

	static function post_list($first_in_large=false) {
		global $posts_exclude ,$wp_query, $articles_accueil, $post, $display_parent_cat;
		$i = 0;



		$number_posts_cat_by_page = (get_param_global('number_posts_cat_by_page')) ? get_param_global('number_posts_cat_by_page') : 4;	
		if ($display_parent_cat && !get_param_global('archive_pagination_normal')){
			$articles_accueil = apply_filters( 'modify_posts_list', $articles_accueil, 'cat');
			$articles_accueil_array=array_chunk($articles_accueil, $number_posts_cat_by_page);

			$j=1;
			foreach ($articles_accueil_array as $articles_accueil) {
				?>
				<div class="articles item_<?php echo $j; ?>"<?php echo ($j>1) ? ' style="display:none;"' : ''; ?> >
				<?php	
					foreach ($articles_accueil as $post) {	
						setup_postdata($post);	   
						$posts_exclude = RW_Utils::add_value_to_array(get_the_ID(), $posts_exclude);  
				        $i++;		
							include(locate_template('include/templates/list-item-normal.php'));
							do_action('after_item_'. $i .'_rubrique');
							do_action( 'after_item_archive_parent_cat' , $j,$i);
							                      
				    }
				    $j++;
			    ?>
			    </div>
			    <?php 
		    }
		}else{
			$wp_query->posts = apply_filters( 'modify_posts_list', $wp_query->posts, 'sub_cat');
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			do_action('before_post_list', $paged);
			while (have_posts()) { 
				the_post();
				$posts_exclude = RW_Utils::add_value_to_array(get_the_ID(), $posts_exclude);
				$i++;
				include(locate_template('include/templates/list-item-normal.php'));
				do_action('after_item_'. $i .'_rubrique');
				do_action( 'after_item_archive_sous_rubrique', $i);
			}
		}
		
	}

	static function get_recipe_ingredients(){
		$ingredients = get_the_terms( get_the_ID(), 'ingredient' );
		if(!$ingredients) return null;
		$html = '<ul class="nav nav-pills">';
		if($ingredients){
			foreach ((array)$ingredients as $ingredient) {
				$search_string = str_replace(" ", "+", $ingredient->name);
				$html .= "<li><a class='link_cat'  href=\"".  get_term_link ($ingredient) ."\"".">".$ingredient->name."</a></li>\n";
			}	
		}	
		$html .= '</ul>';
		return $html;
	}

	static function get_post_tags($current_cat) {
		$post_tags = get_the_tags();
		$blog_url=home_url();
		$tags = '<ul class="nav nav-pills">';
		foreach ((array)$post_tags as $tag) {
			$search_string = str_replace(" ", "+", $tag->name);
			$tags .= "<li><a class='link_cat ".get_cat_slug($current_cat->parent)."'  href=\"".  get_tag_link($tag->term_id) ."\"".">".$tag->name."</a></li>\n";
		}
		$tags .= '</ul>';
		return $tags;
	}

	static function share_post(){
		$share_content='';
		if(get_param_global('share_post')=='yes'){
		$share_content.='<div class="blockShare">
					<span class="linkShare">'.__('Partager cet article', REWORLDMEDIA_TERMS).'</span>
					
					<div class="socialLink">
						<div class="addthis_toolbox addthis_default_style ">
							<ul>
								<li class="fb">
									<a class="addthis_button_facebook"></a>
								</li>
								<li class="twitter">
									<a class="addthis_button_twitter"></a>
								</li>
								<li class="google">
									<a class="addthis_button_google_plusone_share"></a>
								</li>
								<li class="shape">
									<a class="addthis_button_pinterest_share"></a>
							
								</li>
								
								
							</ul>
						</div>
					</div>
				</div>';
		}
		return $share_content;
	}

	static function get_posts_have_gallery($str_search="[gallery"){
		global $wpdb;
	 
		if(get_param_global('custom_related_posts_gallery')){
			$menu_cat =  RW_Category::get_menu_cat_post();
			$gallery_posts = relatted_posts_gallery($menu_cat);
			
			if(!empty($gallery_posts))
				return $gallery_posts;
		}

		$post_id=get_the_ID();
		$cat=get_the_category($post_id);
		$result_posts=array();
		// exclure les catégories de mise en avant
		$excluded_categories = get_param_global('highlight_categories');
		if(!empty($excluded_categories)){ 
			foreach ($cat as $key => $value) { 
				if( in_array($value->slug, $excluded_categories) ){ 
					unset($cat[$key]);  
				}
			}
			
		}

		$first_cat = reset($cat);
		$cat_id = (isset($first_cat->term_id)) ? $first_cat->term_id : '';

		if ( function_exists('is_dedicated_area'))
			$return_cat = is_dedicated_area();
		
		if ( WP_DEBUG && isset($first_cat->term_id) && isset($first_cat->category_parent) ) {
			echo'<!--Current category = ' . get_cat_name($first_cat->term_id) .' -->';
			echo'<!--Parent category = ' . get_cat_name($first_cat->category_parent) .' -->';
		}

		$params = array('cat' => $cat_id,
				's' => $str_search,
				'post_status' => 'publish',
				'orderby' => 'rand',
				'posts_per_page' => 4,
				'post__not_in' => array($post_id),
		);
		// No search in diapo type
		if ( $diapo_type = get_param_global('has_type_diapo' , false )){
			unset( $params['s']);
			$params['post_type'] = $diapo_type;
		}
		if ( isset($return_cat) && !($return_cat['return'] && $return_cat['category']))
		{
			$params = array_merge($params, array(
				'date_query'    => array( 'column'  => 'post_date','after'   => '- 90 days' ),
				));
		}
		$get_posts_have_gallery = new WP_Query($params);
		array_push($result_posts, $get_posts_have_gallery);
		if(isset($get_posts_have_gallery->posts) && isset($first_cat->category_parent) && count($get_posts_have_gallery->posts)<4 && $first_cat->category_parent != 0){
			$exclude_posts_id=array($post_id);
			foreach ($get_posts_have_gallery->posts as $post_) {
				array_push($exclude_posts_id, $post_->ID);
			}
			$more_posts_page=4-count($get_posts_have_gallery->posts);
			$params = array('cat' => $first_cat->category_parent,
					's' => $str_search,
					'posts_per_page' => $more_posts_page,
					'post_status' => 'publish',
					'orderby' => 'rand',
					'post__not_in' => $exclude_posts_id,
					);
			// No search in diapo type
			if ( $diapo_type ) {
				unset( $params['s']);
				$params['post_type'] = $diapo_type;
			}

			if (!($return_cat['return'] && $return_cat['category']))
			{
				$params = array_merge($params, array(
					'date_query'    => array( 'column'  => 'post_date','after'   => '- 90 days' ),
					));
			}
			$get_posts_have_gallery_parent = new WP_Query($params);
			array_push($result_posts, $get_posts_have_gallery_parent);	
		}
		return $result_posts;
		
	}

	static function get_post_tag_pushed($cat_id){
		global $site_config;
		$tags_list="";
		$pos_tag=1;
		foreach ($site_config['tags_en_avant'] as $key => $value) {
			$tags_list.=$key;
			if($post_tag < count($site_config['tags_en_avant'])){
				$tags_list.=',';
			}
			$pos_tag++;
		}
		$args = array(
		    'tag' => $tags_list,
		    'category' => $cat_id
		);
		return get_posts($args);
	}

	static function get_related_author_posts($posts_per_page=3) {
	    global $authordata, $post;
	    $params = apply_filters('params_related_author_posts', array( 'orderby' => 'post_date', 'order' => 'DESC', 'author' => $authordata->ID, 'post__not_in' => array( $post->ID ), 'posts_per_page' => $posts_per_page ) );
	    $authors_posts = get_posts( $params );
	    return $authors_posts;
	}

	static function page_has_video($p = null){
		global $post;
		if(!$p){
			$p = $post ;
		}
		$r = false ;
		$post_content = $p->post_content;
		$post_content = apply_filters('post_content_video', $post_content, $p) ;
		
		$pattern = get_shortcode_regex( array('fpvideo') );
		if(preg_match_all ("/$pattern/", $post_content, $matches )){
			//Get shortcode attributes
			foreach ($matches[0] as $key => $value) {
				$attr = shortcode_parse_atts( $matches[3][$key] ); 
				$r = player_shortcode_active($attr);
				if($r)
					return $r;
			}
		}
	
		// featured video
		if(!$r){
			$id = get_post_meta($p->ID, '_video_id', true );
			if ($id){
				$r=true;
			}
		}
		$r = apply_filters('filter_page_has_video', $r, $p) ;
		
		return $r ;
	}


	static function page_has_video_top(){
		global $post;
		$video_top_page = false;
		if (is_single() && preg_match(REG_VIDEO, $post->post_content, $matches)) {
			$video_top_page = true;
			if(preg_match("/no_top_page=['|\"]?yes['|\"]?/im", $matches[0] , $matches2)){
				$video_top_page = false;
			}
		}
		return $video_top_page;
	}

	static function get_publireportages($position=null, $cat=null, $value=1){
		$publireportages_cat = rw_get_category_by_slug(__('verrouillage',REWORLDMEDIA_TERMS ) )  ;
		$cats_filter=$publireportages_cat->term_id;
		$array_filter = array();
		$tax_query = array(
			'relation' => 'AND',
			array(
				      'taxonomy' => 'category',
				      'field' => 'id',
				      'terms' => $cats_filter,
				    ));

		if (in_array($position, array('home', 'most_popular', 'home_carrousel','most_popular_video') )) {
			array_push($array_filter, array(
				'key' => 'post_publireportage_position_' . $position ,
				'value' => $value,
	            'compare' => 'LIKE'
			));
		}

		if (in_array($position, array('rubrique','rubrique_carrousel','rubrique_block','home_block' ) )) {
			array_push($array_filter, array(
				'key' => 'post_publireportage_position_' . $position,
				'value' => $value ,
	            'compare' => 'LIKE'
			));
			if(!empty($cat)){
				array_push($tax_query, array(
				      'taxonomy' => 'category',
				      'field' => 'id',
				      'terms' => $cat,
				    )
	    		);

			}
		}

		array_push($array_filter, array(
	            'key' => 'post_publireportage_date_fin',
	            'value' => date('Y-m-d H:i',time()),
	            'compare' => '>=',
	            'type' => 'DATETIME',
	        )
	    );

		array_push(
			$array_filter, 
			array(
				'relation' => 'OR',
				array(
		            'key' => 'post_publireportage_date_debut',
		            'value' => date('Y-m-d H:i',time()),
		            'compare' => '<=',
		            'type' => 'DATETIME',
		        ),
		        array(
		            'key' => 'post_publireportage_date_debut',
		            'compare' => 'NOT EXISTS'
	        	)
	        )
	    );

		$args = array(
			'posts_per_page'   => 1,
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'post_status' => 'publish',
		    'meta_query' => $array_filter,
		    'tax_query' => $tax_query
		);

		$posts_reportage = get_posts($args);
		if(count($posts_reportage)>0) {
			$return = $posts_reportage[0];
		} else {
			$return = false;
		}
		return $return ;
	}

	static function get_publireportages_id (){
		global $publireportages_id ;
		$publireportages_id = false;

		if (!get_param_global('hasnt_publireportages')) {
			if(!$publireportages_id){
				$cat = rw_get_category_by_slug('verrouillage');
				$publireportages_id = $cat->term_id ;
			}
		}	
		return $publireportages_id;
	}

	static function setup_publireportages ($args){
		global $publireportages_id ;
		if (!get_param_global('hasnt_publireportages')) {
			if(isset($args['category'])){
				$args['category'].= ',' . ($publireportages_id*-1) ;
			}else {
				$args['category']= ($publireportages_id*-1) ;
			}
		}
		return $args;
	}

	static function get_slug_post($id) {
		$post_data = get_post($id, ARRAY_A);
		$slug = $post_data['post_name'];
		return $slug; 
	}

	/**
	 * Check if a page is single and has the shortcode [gallery
	 *
	 * @return     boolean  
	 */
	static function page_has_gallery(){
		global $post;
		if ( is_single() && $post ) {
			$post_content = $post->post_content ;
			return stristr($post_content , "[gallery" );
		}
		return false ;
	}

	static function count_gallery_items(){
		global $post;
		if( self::page_has_gallery() && !empty($post->post_content) ){
			preg_match(HAS_GALLERY_REGEXP, $post->post_content, $matches);
			return count(explode( ',', self::get_gallery_ids($matches)));
		}
		return 0;
	}

	static function get_gallery_ids ($matches){
		$ids = '' ;
		if(isset($matches[3])){
			$attr = shortcode_parse_atts( $matches[3] );
			$ids = isset($attr['ids'])? $attr['ids'] :'';
		}
		return $ids ;
	}



	static function has_diapo_monetisation($p= null) {
		if( is_dev('type_diapo_radio_bouton_8067') && get_option('type_diapo_meta_updated') ){
			$gallery_type = self::get_gallery_type();
			if( $gallery_type == 'diapo_monetisation_normal' || $gallery_type == 'diapo_monetisation_desktop' ){
				return true;
			}
			return false;
		}else{
			global $post ;
			if( get_param_global('force_no_diapo_monetisation') )  return false;

			if( empty($p) ){
				$p  = $post;
			}

			if( get_param_global('diapo_monetisation_for_all_diapo') || (get_param_global('diapo_monetisation_desktop') && get_post_meta($p->ID ,"diapo_monetisation_desktop",true)) ){
				return stristr($p->post_content , "[gallery" );
			}
			
			return has_category('diapo_monetisation');
		}
	}

	static function is_simple_monetisation($p= null) {
		if( is_dev('type_diapo_radio_bouton_8067') && get_option('type_diapo_meta_updated') ){
			if( self::get_gallery_type() == 'diapo_monetisation_normal' ){
				return true;
			}
			return false;
		}else{
			global $post ;
			if(empty($p)){
				$p  = $post ;
			}
			$return = false;
			$post_content = $p->post_content;
			if(!stristr($post_content , "[gallery" )){
				$return = has_category('diapo_monetisation', $p);
			}
			return $return;
		}
	}

	/**
	 * Get gellery type
	 * il faut appeler cette fonction apres le hook wp la priorité >=1001 
	 * @return     boolean|string  linear|linear_mobile|classical.
	 */
	static function get_gallery_type(){
		global $post;
		if ( !self::$gallery_type && self::page_has_gallery() ) {
			if( is_dev('type_diapo_radio_bouton_8067') && get_option('type_diapo_meta_updated') ){
				if ( rw_is_mobile() ){
					$type_diapo_mobile = get_post_meta( $post->ID ,"type_diapo_mobile", true);
					if( is_dev('diapo_monetisation_mobile_5334') && $type_diapo_mobile == 'diapo_monetisation_mobile'){
						self::$gallery_type = "diapo_monetisation_mobile" ;
					}else if( get_param_global('enable_linear_diapo_on_mobile') ) {
						self::$gallery_type = 'linear_mobile';
					}
				}else{
					$type_diapo = get_post_meta( $post->ID ,"type_diapo", true);
					if( get_param_global('diapo_vertical') && $type_diapo == 'diapo_vertical' ){
						self::$gallery_type = "diapo_vertical";
					}else if( get_param_global('diapo_popup') && $type_diapo == 'diapo_popup' ){
						self::$gallery_type = "diapo_popup";
					}
					else if( get_param_global('diapo_monetisation_desktop') && $type_diapo == 'diapo_monetisation_desktop' ) {
						self::$gallery_type = "diapo_monetisation_desktop";
					}else if( get_param_global('diapo_monetisation_for_all_diapo') || has_category('diapo_monetisation') ){
						self::$gallery_type = "diapo_monetisation_normal";
					}else{
						self::$gallery_type = 'classical';
						if( is_dev('nwk_template_multi_images_137513253') ){
							$selected_template = get_post_meta( $post->ID, 'post_gallery_template', true );
							if( !empty($selected_template) ) self::$gallery_type = $selected_template;
						} else if( get_param_global("disable_full_diapo") ){
							self::$gallery_type = 'mini_diapo';
						}
					}
				}
			}else{
				if ( get_param_global( 'enable_linear_diapo_on_mobile' ) && rw_is_mobile() ) {
					self::$gallery_type = 'linear_mobile';
				}else if(is_dev('nwk_template_multi_images_137513253')){
					$selected_template = get_post_meta( $post->ID, 'post_gallery_template', true );
					self::$gallery_type = !empty($selected_template)? $selected_template : 'classical';
				}else {
					self::$gallery_type = 'classical';
				}
				if(self::$gallery_type == 'classical' && get_param_global("disable_full_diapo")){
					self::$gallery_type = 'mini_diapo';
				}
				if (is_dev('diapo_monetisation_mobile_5334') && rw_is_mobile() && get_post_meta( $post->ID ,"diapo_monetisation_mobile",true)){
					self::$gallery_type = "diapo_monetisation_mobile" ;
				}
				if ( get_param_global('diapo_monetisation_desktop') && !rw_is_mobile() && get_post_meta( $post->ID ,"diapo_monetisation_desktop",true)){
					self::$gallery_type = "diapo_monetisation_desktop" ;
				}

			}
		}
		return self::$gallery_type;
	}

	static function get_datas_seo() {
		global $post;
		$datas["title"] = get_the_title($post->ID);
		$datas["author"] = get_the_author();// pas besoin de $post->ID  , déprecié
		$datas["link"] = get_the_permalink($post->ID);
		$datas["date_single"] = get_the_date( 'c', $post->ID );
		$datas["date_update_single"] = get_the_modified_date( 'c', $post->ID);
		$datas["sharedcount"] = get_single_sharedcount($post->ID);
		$attachment_id = get_post_thumbnail_id($post->ID) ;
		$src_200 = wp_get_attachment_image_src( $attachment_id , array(200,200) ) ;
		$datas["thumbnail_url"] = isset($src_200[0]) ? $src_200[0] :'';
		$src_rw_large = wp_get_attachment_image_src( $attachment_id , 'rw_large' ) ;
		$datas["image"] = isset($src_rw_large[0]) ? $src_rw_large[0] :'';
		$datas["image_path"] = get_attached_file($attachment_id);
		return $datas;
	}

	static function is_child_of ($cats, $post =null){
		$post_category = RW_Category::get_menu_cat_post($post);
		$r = false ;
		if(in_array($post_category->term_id, $cats) || in_array($post_category->category_parent, $cats)){
			$r = true ;
		}else{
			$categories = array();
			foreach ($cats as $id) {
				$categories = get_categories(array('child_of' => $id));
				foreach ($categories as $category) {
					if($post_category->term_id == $category->term_id){
						$r = true ;
						break 2 ;
					}					
				}
			}	
		}
		return $r ;
	}

	static function post_is_in_descendant_category( $cats, $_post = null ) {
		foreach ( (array) $cats as $cat ) {
			$term = rw_get_category_by_slug( $cat );
			if ( isset($term->term_id) ){
				$descendants = get_term_children( (int) $term->term_id , 'category' );
				$descendants [] = $term->term_id ;
				if ( $descendants && in_category( $descendants, $_post ) )
					return true;
			}
		}
		return false;
	}

	static function delete_post_media( $post_id ) {
		if(!isset($post_id)) return; // Will die in case you run a function like this: delete_post_media($post_id); if you will remove this line - ALL ATTACHMENTS WHO HAS A PARENT WILL BE DELETED PERMANENTLY!
		elseif($post_id == 0) return; // Will die in case you have 0 set. there's no page id called 0 :)
		elseif(is_array($post_id)) return; // Will die in case you place there an array of pages.
		else {
		    $attachments = get_posts( array(
		        'post_type'      => 'attachment',
		        'posts_per_page' => -1,
		        'post_status'    => 'any',
		        'post_parent'    => $post_id
		    ) );
		    foreach ( $attachments as $attachment ) {
		        if ( false === wp_delete_attachment( $attachment->ID ) ) {
		            // TODO : 
		            // Log failure to delete attachment.
		        }
		    }
		}
	}

	//return most popular
	static function most_popular_all($number_of_posts = -1 ){
		global $posts_exclude;
		$most_popular = get_option(apply_filters('most_popular_option',"most_popular_all"), array());
		$args = array('post__in' => $most_popular, 'posts_per_page' => $number_of_posts, 'orderby' => 'post__in', 'order' => 'DESC', 'post__not_in' => $posts_exclude);
		$results = get_posts( apply_filters('most_popular_all_args', $args) );
		return $results ;
	}

	//return most popuar in a specific category
	static function most_popular_cat($cat_slug, $number_of_posts){
		global $posts_exclude;
		$most_popular_cat = get_option(apply_filters('most_popular_option',"most_popular_" . $cat_slug), array());
		$args = array('post__in' => $most_popular_cat, 'posts_per_page' => $number_of_posts, 'orderby' => 'post__in', 'post_status'=> 'publish', 'order' => 'DESC', 'post__not_in' => $posts_exclude);
		$results = get_posts( apply_filters('most_popular_cat_args', $args) );
		return $results;
	}

	static function get_similar_posts($current_cat , $number_posts , $post_id){
		global $to_exclude;
		$posts_related = get_posts(array('category' => $current_cat->term_id, 'posts_per_page' => $number_posts, 'exclude' => $post_id ));
		if(count($posts_related)<$number_posts) {
			// we should exclude already extracted posts
			$to_exclude = [ $post_id ];
			foreach ( $posts_related as $post_to_exclude){
				$to_exclude[] = $post_to_exclude->ID ; 
			}
			$number_posts = $number_posts-count($posts_related);
			$posts_related_parent = get_posts(array('category' => $current_cat->parent, 'posts_per_page' => $number_posts, 'exclude' => $to_exclude ));	
			$posts_related = (object)array_merge((array)$posts_related,(array)$posts_related_parent);
		}
		return $posts_related;
	}

	static function get_external_posts($url,$posts,$limit,$same_server=false, $rand = false){
		global $posts_exclu;
		if( !$rand ){
			$url = add_query_arg('count', $limit, $url) ;
		}
		$caches_duration = 60*60*6;
		$key_cache = md5($url) ;
		$group_cache = 'external_json' ;

		$body = wp_cache_get( $key_cache, $group_cache ) ; 
		if( $body===false ){
			if( $same_server ){
				$hostname = parse_url($url ,  PHP_URL_HOST );
				$url = str_replace ( $hostname.'/' , '127.0.0.1/' , $url ) ;
				$headers=array('Host: '.$hostname) ;
			} else {
				$headers=array();
			}

			$response = wp_remote_get( $url, array( 'timeout'=> 50 , 'headers' => $headers ));
			if( is_array($response) ) {
			  	$body = $response['body'];
				$result = wp_cache_set($key_cache, $body, $group_cache , $caches_duration );
			}else{
				$body = '[empty]';
				wp_cache_set($key_cache, $body  ,  $group_cache , 60*60 );

			}
		}elseif ( defined('WP_DEBUG') && WP_DEBUG ){
		 		echo '<span style="display:none"> USING CACHE '.$key_cache.' : '.$group_cache.' : '.$caches_duration.'</span>'.$body;
		}
		if( $body == '[empty]' ){
			$body = '[]';
		}
		$json=json_decode($body);

		if($json->posts){
			$posts_ = $json->posts ;
		}
		// else{
		// 	$posts_ = $json ;
		// }
		
		// init if not already done
		if ( !is_array($posts))
			$posts=array();
		if ( !is_array($posts_exclu))
			$posts_exclu =array();

		if( isset($posts_) && count($posts_)){
			if( $rand ){
				shuffle($posts_);
			}
			
			foreach ($posts_ as $p) {
				if(count($posts)==$limit)
					break;

				if(!in_array($p, $posts) && !in_array($p, $posts_exclu)){
					$posts[]=$p;
					$posts_exclu[]=$p;
				}
			}
		}
		return $posts;
	}

	static function get_url_first_img(){
		global $post;
		$first_img = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		if(isset($matches [1]) && count($matches [1]) > 0){
			$first_img = $matches [1] [0];
		}

		return $first_img;
	}

	static function page_has_jeu(){
		if (is_single()) {
			global $post;
		$post_content = $post->post_content ;
			return stristr($post_content , "[jeu" );
			
		}
		
		return false ;
	}

	static function page_has_ninja_forms(){
		global $post;
		$post_content = $post->post_content ;
			return stristr($post_content , "[ninja_forms_display_form" );
		return false ;
	}

	/**
	 * Test if a post is ctr type or not
	 * @return boolean
	 */
	static function is_post_ctr_type(){
		global $post;
		$return = false;
		if( $post && get_param_global('active_article_ctr') && is_single() && !page_has_gallery() ){
			$is_post_ctr = get_post_meta( $post->ID, 'is_ctr_post', true );
			if( !empty($is_post_ctr) && $is_post_ctr == 'post_with_ctr' ){
				$return = true;
			}
		}
		return $return;
	}

	static function post_contain_single_img(){
		global $post;
		$post_content = $post->post_content;
		$matches = false;
		$is_thumb_featured = get_post_meta($post->ID, 'is_thumb_featured', true);
		if(!page_has_gallery() && !$is_thumb_featured){
			preg_match('/<img[^>]+\>/i', $post_content, $matches);
		}
		return $matches;
	}

	// Edit : arg 1 : Use id instead of object post : fix warning in hash_post_id in seo file 
	static function get_total_shared_post( $post_id, $shared_total = 0, $nbr_min = 0 ){
		if(!$shared_total){
			$shared_total=get_shared_total(get_single_sharedcount( $post_id ));
		}
		if( $shared_total > $nbr_min ){
			if($shared_total > 999){
				$total=intval($shared_total/1000)."K";
			}else{
				$total=$shared_total;
			}
		}else{
			$total=0;
		}
		return $total;
	}

	/**
	 * Retourne les ids des articles avec des tags communs.
	 *
	 * @param int $nbr_common_tags nombre des tags
	 * @param array $ids_ les ids des articles deja récuperer
	 * @param int $limit pour spécifier le nombre maximum de résultats
	 * @return array
	 */

	static function get_posts_with_common_tags( $nbr_common_tags, $ids_ = array(), $limit = 0 ){
		global $post,$wpdb;
		$exclude_ids = $ids_;
		$exclude_ids[] = $post->ID; 

		$exclud = "(".implode(",", $exclude_ids).")";
		$sql_tag = "SELECT name
	                  FROM ".$wpdb->prefix."terms wt1
	                  JOIN ".$wpdb->prefix."term_taxonomy t1 ON wt1.term_id = t1.term_id
	                  JOIN ".$wpdb->prefix."term_relationships r1 ON r1.term_taxonomy_id = t1.term_taxonomy_id
	                  JOIN ".$wpdb->prefix."posts p1 ON r1.object_id = p1.ID
	                  AND t1.taxonomy =  'post_tag'
	                  AND wt1.slug != 'yout_video' 
	                  AND wt1.slug != 'dai_video' 
	                  AND wt1.slug != 'has_video'
	                  AND p1.ID =".$post->ID;
	    $sql_tag = apply_filters( 'sql_tag_playlist', $sql_tag );

		$sql_ = "SELECT SQL_CACHE ID
	            FROM (
	                  (
	                  SELECT ID,name 
	                  FROM ".$wpdb->prefix."terms wt 
	                  JOIN ".$wpdb->prefix."term_taxonomy t ON wt.term_id = t.term_id 
	                  JOIN ".$wpdb->prefix."term_relationships r ON r.term_taxonomy_id = t.term_taxonomy_id 
	                  JOIN ".$wpdb->prefix."posts p ON r.object_id = p.ID 
	                  AND t.taxonomy = 'post_tag'
	                  AND ID in(
	                  	SELECT ID
	                  	FROM ".$wpdb->prefix."terms wt 
	                  	JOIN ".$wpdb->prefix."term_taxonomy t ON wt.term_id = t.term_id 
	                  	JOIN ".$wpdb->prefix."term_relationships r ON r.term_taxonomy_id = t.term_taxonomy_id 
	                  	JOIN ".$wpdb->prefix."posts p ON r.object_id = p.ID 
	                  	AND t.taxonomy = 'post_tag' 
	                  	AND ( wt.slug = 'yout_video' OR wt.slug = 'dai_video' )
	                  )
	              ) a
	                INNER JOIN 
	                  (".$sql_tag.") b
	              ON ( a.name = b.name )
	            )
	          WHERE ID NOT IN ".$exclud."
	          GROUP BY ID DESC
	          HAVING COUNT( * ) >=".$nbr_common_tags."
	          ORDER BY COUNT( * ) DESC 
	          LIMIT ".( $limit - count($ids_) );
	    $ids_posts =  $wpdb->get_results($sql_) ;
	    if( !empty($ids_posts) ){
	       	foreach ($ids_posts as $p) {
	       		$ids_[] = $p->ID;
	       	}
	    }
	    return $ids_;
	}
	/**
	 * Redirect post form current site to destination site
	 *
	 * @param      Object  $post         The post
	 * @param      Integer  $destination  Id du site 9 pour automoto par exemple
	 */
	static function redirect_post_from_site_to_site($post, $destination){
		switch_to_blog($destination);
		$args = array(
		    'meta_key' => 'old_post_id',
		    'meta_value' => $post->ID,
		    'posts_per_page' => -1
		);
		$posts = get_posts($args);
		if(!empty($posts)){
			$link = get_permalink($posts[0]);
			$link = apply_filters('subdomain_link', $link);
			wp_redirect( $link, 301 );
			exit;	
		}
		restore_current_blog();

	}

	static function get_not_publish_permalink( $post_id ) {
		require_once ABSPATH . '/wp-admin/includes/post.php';
	    list( $permalink, $postname ) = get_sample_permalink( $post_id );
	    $link = str_replace( '%postname%', $postname, $permalink );
	    return $link;
	}

	static function show_link_more($post_id) {
		$custom = get_post_custom($post_id);
		$readmore_val = (isset($custom['post_read_more'])) ? $custom['post_read_more'][0] : 0;
		$link_more='';
		$link_more.='<div class="links" >
				<span class="readmore" data-href="'.get_permalink().'">';
					if($readmore_val==1)
						$link_more.=__("J'en profite" , REWORLDMEDIA_TERMS); 
					else
						$link_more.=__("Lire la suite"  , REWORLDMEDIA_TERMS)." >";
		$link_more.='</span>';
		if(is_category() && get_param_global('shares_in_list')=='yes') {
			$link_more.= do_shortcode('[social_links_single]');
		}
			$link_more.='</div>';

		return $link_more;
	}

	static function get_meta_tag(){
		$post_tags = get_the_tags();
		$meta_tag ="";
		if($post_tags){
			foreach ((array)$post_tags as $tag) {
				$meta_tag .= $tag->name .", " ;
			}
		}
		return substr ($meta_tag ,0 ,-2);
	}

	/**
	* Sur SPO, Mathieu recommande d avoir le lien sous forme de balise <a> dans le code source, d ou 
	*  l ajout de la variable $js_link
	* */
	static function show_title_home_h2($title, $url='' , $more_title='', $js_link = true ){ 
		$title_content = !empty($url) ? '<a href="'.$url.'" class="txt_wrapper">'.$title.'</a>' : $title;
		$title_content = '<h2 class="default-title">'.$title_content.'</h2>';
		if ( $more_title )
			$title_content .= '<a class="read_more" href="'.$url.'" >'.$more_title.'</a>';
		echo '<div class="bloc_rubrique_head">'. $title_content .'</div>';
	}

	static function cancel_content_video($content) {
	    // desactiver le shortcode nextgen
		if (shortcode_exists('fpvideo')) {
			remove_shortcode('fpvideo');
		}
		add_shortcode('fpvideo', create_function('', 'return;'));
		return $content;
	}

	static function infos_recipe() {
		$post_prep_duration = get_post_meta(get_the_ID(), 'post_prep_duration', true);
		$post_cook_duration = get_post_meta(get_the_ID(), 'post_cook_duration', true);
		$post_nb_persons = get_post_meta(get_the_ID(), 'post_nb_persons', true);
		$post_calories = get_post_meta(get_the_ID(), 'post_calories', true);
		$post_fat_content = get_post_meta(get_the_ID(), 'post_fat_content', true);
		
		$post_cook_repos = get_post_meta(get_the_ID(), 'post_cook_repos', true);
		$post_cook_refrigeration = get_post_meta(get_the_ID(), 'post_cook_refrigeration', true);
		$post_cook_congelation = get_post_meta(get_the_ID(), 'post_cook_congelation', true);
		$post_cook_marinade = get_post_meta(get_the_ID(), 'post_cook_marinade', true);

		if($post_prep_duration or $post_cook_duration or $post_calories or $post_fat_content){
			?>
			<div class="block-border col-xs-12 pos-top">
				<div class='title-block'>
					<span class="glyphicon-info-article">&nbsp;</span>
					<span class="txt">
						<?php _e('Informations générales' , REWORLDMEDIA_TERMS ) ;?>
					</span>
				</div>
				<ul class="nav recipe_infos">
				<?php
				if($post_prep_duration) {
					?>
					<li>
						<span class="disc">&nbsp;</span>
						<span class="txt">
							<span class="txt_label"><?php _e('Temps de préparation' , REWORLDMEDIA_TERMS ) ;?> : </span><?php echo $post_prep_duration; ?><meta itemprop="prepTime" content="<?php echo get_time_PT($post_prep_duration);?>">
						</span>
					</li>                                
					<?php
				}													
				if($post_cook_duration) {
					?>
					<li>
						<span class="disc">&nbsp;</span>
						<span class="txt">
							<span class="txt_label"><?php _e('Temps de cuisson' , REWORLDMEDIA_TERMS ) ;?> : </span><?php echo $post_cook_duration; ?><meta itemprop="cookTime" content="<?php echo get_time_PT($post_cook_duration);?>">
						</span>
					</li>
					<?php
				}

				if($post_cook_refrigeration) {
					?>
					<li>
						<span class="disc">&nbsp;</span>
						<span class="txt">
							<span class="txt_label"><?php _e('Temps de réfrigération' , REWORLDMEDIA_TERMS ) ;?> : </span><?php echo $post_cook_refrigeration; ?>
						</span>
					</li>
					<?php
				}	

				if($post_cook_congelation) {
					?>
					<li>
						<span class="disc">&nbsp;</span>
						<span class="txt">
							<span class="txt_label"><?php _e('Temps de congélation' , REWORLDMEDIA_TERMS ) ;?> : </span><?php echo $post_cook_congelation; ?>
						</span>
					</li>
					<?php
				}

				if($post_cook_repos) {
					?>
					<li>
						<span class="disc">&nbsp;</span>
						<span class="txt">
							<span class="txt_label"><?php _e('Temps de repos' , REWORLDMEDIA_TERMS ) ;?> : </span><?php echo $post_cook_repos; ?>
						</span>
					</li>
					<?php
				}

				
				if($post_cook_marinade) {
					?>
					<li>
						<span class="disc">&nbsp;</span>
						<span class="txt">
							<span class="txt_label"><?php _e('Marinade' , REWORLDMEDIA_TERMS ) ;?> : </span><?php echo $post_cook_marinade; ?>
						</span>
					</li>
					<?php
				}				

				if($post_nb_persons) {
					?>
					<li>
						<span class="disc">&nbsp;</span>
						<span class="txt">
						<?php if(get_param_global('update_ingredient_by_nb_person')) { ?>	
							<span class="txt_label"><?php _e('Recette pour' , REWORLDMEDIA_TERMS ) ;?> : </span><input type="text" id="nb_preson_recette" value="<?php echo $post_nb_persons ; ?>" size="2" /> <?php _e('personnes' , REWORLDMEDIA_TERMS) ; ?>
							<input type="hidden" id="hidden_nb_preson_recette" value="<?php echo $post_nb_persons ; ?>"  />
						<?php } else { ?>
							<span class="txt_label"><?php _e('Recette pour' , REWORLDMEDIA_TERMS ) ;?> : </span><?php echo $post_nb_persons ; ?> <?php _e('personnes' , REWORLDMEDIA_TERMS) ; ?>
						<?php } ?>	
							<meta itemprop="recipeYield" content="<?php echo $post_nb_persons ; ?> <?php _e('personnes' , REWORLDMEDIA_TERMS) ; ?>">
						</span>
					</li>
					<?php
				}
				?>
				</ul>
				<?php
				if($post_calories or $post_fat_content) { 
					?>
					  <div itemprop="nutrition" itemscope itemtype="http://schema.org/NutritionInformation">
						<ul class="nav">							
						<?php
						if($post_calories) {
						?> 
						<li itemprop="calories">
							<span class="disc">&nbsp;</span>
							<span class="txt">
								<?php _e('Nombre de calories' , REWORLDMEDIA_TERMS ) ;?> : <?php echo $post_calories ;?>
							</span>
						</li>
						<?php					
						}
						if($post_fat_content) {
						?>   
						<li itemprop="fatContent">
							<span class="disc">&nbsp;</span>
							<span class="txt">
								<?php _e('Poids de matières grasses' , REWORLDMEDIA_TERMS ) ;?> : <?php echo $post_fat_content ;?>
							</span>
						</li>
						<?php
						}
						?> 
						</ul>                               
					  </div>
					<?php
					} ?>
			</div>
		<?php																					
		}
		if(get_param_global('update_ingredient_by_nb_person')){
			wp_enqueue_script('reworld-recettes-js', get_template_directory_uri().'/assets/javascripts/recettes.js', array('jquery'), '', true );
			wp_enqueue_style('reworld-recettes-css', get_template_directory_uri() . '/assets/stylesheets/recettes.css');
		}
	}

	static function ingredients_recipe() {
		$post_list_ingredients = get_post_meta(get_the_ID(), 'post_list_ingredients', true);
		if(count($post_list_ingredients) and  $post_list_ingredients[0] != ""){			
			?>
			<div class="block-border col-xs-12">
				<div class='title-block'>
					<span class="glyphicon-ingredients-article">&nbsp;</span>
					<span class="txt">
						<?php _e('Ingrédients' , REWORLDMEDIA_TERMS ) ;?>
					</span>
				</div>
				<ul class="nav">
				<?php
				foreach($post_list_ingredients as $ingredient){
					if($ingredient){
						?>
		                    <li>
								<?php if(get_param_global('update_ingredient_by_nb_person')) { ?>
									<?php
										// pattern to find digitals within the ingredient line
										$pattern  = '/\s?([0-9\.]+)(.*)/';
										// initiate dislayed variable
										$quantityVal = $ingredient; 
										// if the line starts with __ (title)
										if(strpos($quantityVal, '__',0) !== 0){
										// checked if the line respect the patter above
										preg_match($pattern, $ingredient, $matches, PREG_OFFSET_CAPTURE);
											// if the line contains the pattern
											if(count($matches) > 0 ) {
												// remove tags
												$ingredient = strip_tags($ingredient);
												// replace the pattern(digital) with span containing the value found
												$quantityVal = preg_replace($pattern,'<span class="ing_quantity" data-initial="$1">$1</span> $2', str_replace('*', '' , $ingredient ));
												// to check if the ingredient quantity found is float number
												preg_match('/>([0-9]+\.[0-9]+)</',$quantityVal,$matches);
												$indexSpan = strrpos($quantityVal, '</span>');
												$half = '½';
												$quarter = '¼';
												$three_quarter='¾';
												if($indexSpan !== false && count($matches)>0){
													switch($matches[1]) {
														case "0.5":$quantityVal = '<span class="ing_quantity" data-initial="0.5">'.$half.'</span>'. substr($quantityVal,$indexSpan+7);break;
														case "0.25":$quantityVal = '<span class="ing_quantity" data-initial="0.25">'.$quarter.'</span>'. substr($quantityVal,$indexSpan+7);break;
														case "0.75":$quantityVal = '<span class="ing_quantity" data-initial="0.75">'.$three_quarter.'</span>'. substr($quantityVal,$indexSpan+7);break;
													}
												}
											}
											// display the full circle only for ingredient not tille (which starts with __)
											echo '<span class="disc">&nbsp;</span>';
										} else {
											// delete __ from the title
											$quantityVal = substr($quantityVal, 2);
										}
									?>
									
									<span class="txt">
		                    		<span itemprop="ingredients"><?php echo $quantityVal; ?></span>
		                    <?php } else {?>
		                    	<span class="disc">&nbsp;</span>
								<span class="txt">
		                    		<span itemprop="ingredients"><?php echo str_replace('*', '' , $ingredient ); ?></span>
		                    <?php }?>
		                    	</span>
		                    </li>
		                <?php
					}						
				}
				?>	
				</ul>
			</div>
			<?php           
		}
	}
	static function rate_recipe() {
		?>
		<div class="block-border col-xs-12">
			<div class='title-block'>
				<span class="glyphicon-rank-article">&nbsp;</span>
				<span class="txt">
					<?php _e('Noter cette recette' , REWORLDMEDIA_TERMS ) ;?>
				</span>
			</div>
		    <div class="rating-box">
		        <?php 
		            echo do_shortcode('[rating]');
		         ?>
		    </div>
		</div>
	<?php	
	}

	static function get_meta_values( $key = '', $type = 'post', $status = 'publish' ) {
	    global $wpdb;
	    if( empty( $key ) )
	        return;

	    $r = $wpdb->get_col( $wpdb->prepare("
	        SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
	        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
	        WHERE pm.meta_key = '%s' 
	        AND p.post_status = '%s' 
	        AND p.post_type = '%s' order by pm.meta_value 
	    ", $key, $status, $type ) );

	    return $r;
	}

	static function tn_count_all_share($url){
		return array() ;
		//twitter
		$json_string = file_get_contents('http://cdn.api.twitter.com/1/urls/count.json?url=' . $url);
		$json = json_decode($json_string, true);
		$count['Twitter'] = isset($json['count']) ? intval($json['count']) : 0;

		//facebook
		$json_string = file_get_contents('https://api.facebook.com/method/links.getStats?urls='.  urlencode($url) .'&format=json');
		$json = json_decode($json_string, true);
		if(count($json)){
			$json = $json[0] ;
			unset($json['url']);
			unset($json['normalized_url']);
			$count['Facebook'] = $json;
		}

		//Pinterest
		$return_data = file_get_contents('http://api.pinterest.com/v1/urls/count.json?url=' . $url);
		$json_string = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $return_data);
		$json = json_decode($json_string, true);
		$count['Pinterest'] = isset($json['count']) ? intval($json['count']) : 0;

		//google plus
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . rawurldecode($url) . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		$curl_results = curl_exec($curl);
		curl_close($curl);
		$json = json_decode($curl_results, true);
		$count['GooglePlusOne'] = isset($json[0]['result']['metadata']['globalCounts']['count']) ? intval($json[0]['result']['metadata']['globalCounts']['count']) : 0;
		return $count;
	}

	/**
	 * Récuperer le type d'article
	 * @return string
	 */
	static function get_article_type(){
		global $post;
		if( RW_Post::page_has_gallery()){
			return "diapo";
		}elseif(page_has_video()){
			return "video";
		}else{
			return "simple";
		}
	}

	/**
	 * Ajouter ou supprimer le tag has_video après ajout ou mise à jour d'un post
	 *
	 * @param      <type>  $post_id  The post identifier
	 * @param      <type>  $post     The post
	 */
	public function add_hasvideo_tag_to_post($post_id, $post){
		$tags = array();

		$has_video = apply_filters('custom_tag_wpml', 'has_video'); 
		$yout_video = apply_filters('custom_tag_wpml', 'yout_video');
		$dai_video = apply_filters('custom_tag_wpml', 'dai_video');

		$videos_tags = array(
			$has_video ? $has_video : 'has_video' => false,
			$yout_video ? $yout_video : 'yout_video' => 'youtu',
			$dai_video ? $dai_video : 'dai_video' => 'dai',
		);

		$posttags = get_the_tags($post_id);

		$do_save = false;

		//Fix wanrning when $posttags is not array
		if (is_array($posttags)){
			foreach ($posttags as $posttag) {
				$tags[$posttag->slug] = $posttag->slug;
			}
		}

		foreach ($videos_tags as $key => $value) {
			if(isset($tags[$key])){
				unset($tags[$key]);
				$do_save = true; 
			}
		}

		if(preg_match (REG_VIDEO , $post->post_content , $matches )){
			$url_video = $matches[1];
			foreach ($videos_tags as $key => $value) {
				if(!$value){
					$tags[$key] = $key;
					$do_save = true; 

				}else if($value){
					if ( strpos($url_video, $value) !== false ) {
						$tags[$key] = $key;
						$do_save = true; 
					}
				}
			}
		}
		if($do_save){
			wp_set_post_tags($post_id, $tags);
		}
	}
	/**
	 * Ajouter ou supprimer le tag has_video après ajout ou mise à jour d'un post
	 *
	 * @param      <type>  $post_id  The post identifier
	 * @param      <type>  $post     The post
	 */
	public function add_hasdiapo_tag_to_post($post_id, $post){
		$tags = array();
		$already_has_diapo = false;
		
		$has_diapo = apply_filters('custom_tag', 'has_diapo');

		$posttags = get_the_tags($post_id);

		if($posttags){
			foreach ($posttags as $posttag) {
				$tags[$posttag->slug] = $posttag->slug;
			}
		}
		
		if(isset($tags[$has_diapo])){
			$already_has_diapo = true;
		}

		if(preg_match ('/\[gallery\s.*\]/' , $post->post_content , $matches )){
			if(!$already_has_diapo)
				wp_set_post_tags( $post_id, $has_diapo, true );
		}else{
			if($already_has_diapo){
				unset($tags[$has_diapo]);
				wp_set_post_tags( $post_id, $tags );
			}
		}
	}
/**
 * Return last published posts in a given site
 * @param  [string]  $domain [site url]
 * @param  [int] $limit  [number of posts]
 * @return [json]  json posts array
 */
	public static function get_last_posts($domain, $limit=false){
		$url = $domain.'?controller=posts&action=last';
		if(!empty($limit)){
			$url .= '&limit='.$limit;
		}
		$args = array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( 'reworldmedia:reworldmedia' )
			)
		);
		$response = wp_remote_get( $url, $args ); 
		return $response;
	}

	/**
	 * Check if the current post's content contains the shortcode [live_comments
	 *
	 * @return     boolean  
	 */
	public static function is_live_post($post=null){
		if(is_single()){
			if(empty($post)){
				global $post; 
			}
			$post_content = $post->post_content ;
			return stristr($post_content , "[live_comments" );
		}
	} 

	/**
	 * Check if the given post's content contains a metapic bloc
	 * @param   [WP_Post]  $post
	 * @return  boolean  
	 */
	public static function has_metapic($post = null){
		if(empty($post)){
			global $post; 
		}
		if(isset(self::$metapic_posts_ids[$post->ID])){
			return self::$metapic_posts_ids[$post->ID];
		}
		if(strpos($post->post_content, 'mtpc-container') !== false) {
			self::$metapic_posts_ids[$post->ID] = true;
			return true;
		} else {
			self::$metapic_posts_ids[$post->ID] = false;
			return false;
		}
	}



  	public static function rw_get_post_meta($post_id , $meta_key = '', $single = true ){

		if(isset(self::$post_meta[$post_id . $meta_key . $single])){
			return self::$post_meta[$post_id . $meta_key . $single] ;
		}
		self::$post_meta[$post_id . $meta_key . $single] = get_post_meta($post_id, $meta_key, $single);
		return self::$post_meta[$post_id . $meta_key . $single];
	}

	public static function rw_has_tag($key = '', $p = null){
		global $post ;
		if(!$p) {
			$p = $post ;
		}
		$has_tag = false;

		if (empty(self::$the_post_tag[$p->ID])){
			self::$the_post_tag[$p->ID] = get_the_tags($p->ID);
		}

		$tags = self::$the_post_tag[$p->ID];
		foreach ($tags as $tag) {
			if ($tag->slug == $key){
				$has_tag = true;
				return $has_tag;
			} 
		}

		return $has_tag;
	}

	static function is_how_to(){
		global $post;
		$count_steps = 0;
	    $meta_how_to = RW_Post::rw_get_post_meta($post->ID, "_how_to", true);
	    if( !$meta_how_to ) return $count_steps;
	    $count_steps = count($meta_how_to['how_to_titles']);
	    return $count_steps;
	}

}