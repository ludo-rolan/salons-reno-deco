<?php
/**
* 
*/
class RW_Category {
	

	private static $_instance;
	private static $permalinked_categoryBy_post = array();
	private static $top_parent_category = array();
	private static $category_parents_slugs = array();

	private static $categories_from_url = array(); 
	private static $categories_slugs_from_url = array();
	private static $post_category_from_url = array();
	private static $menu_cat_post = array();
	private static $category_parents_ids = array();
	private static $post_categories_ids = array();


	function __construct(){
		# code...
	}
	
	static function get_instance(){
		if(is_null(self::$_instance)){
			self::$_instance = new RW_Category();
		}
		return self::$_instance;
	}
	
	/**
	 * Gets the post category from url.
	 *
	 * @param      <object>   $post         The post
	 * @param      boolean  $first_level  The first level
	 *
	 * @return     <object>   category.
	 */
	static function get_post_category_from_url($p=null, $first_level=false) {

		global $post ;
		$post_id = $p!=null?$p->ID: $post->ID;
		$post_ = $p !=null ? $p : $post;

		if(isset(self::$post_category_from_url[$post_id . $first_level])){
			return self::$post_category_from_url[$post_id . $first_level] ;
		}

		if(in_array($post_->post_status, array( 'draft', 'pending', 'auto-draft', 'future' ))){
			$link_current_page = RW_Post::get_not_publish_permalink($post_id);
		}else{
			$link_current_page = get_permalink($post_);
		}
		
		$link_current_page = str_replace(home_url('/'), '', $link_current_page);
		$link_current_page = trim($link_current_page, '/');
		$link_current_page = explode('/', $link_current_page);

		if (!$first_level) {
			$cat_part = (count($link_current_page) >= 2 && isset($link_current_page[count($link_current_page)-2]) ) ? $link_current_page[count($link_current_page)-2] : false;
		} else {
			$cat_part = array_shift($link_current_page);
		}	
		$cat_object = self::rw_get_category_by_slug($cat_part);
		self::$post_category_from_url[$post_id . $first_level] = $cat_object ;
		return $cat_object;
	}

	/**
	 * Gets the cat slug.
	 *
	 * @param      <integer>  $cat_id 
	 *
	 * @return     string  The category's slug.
	 */
	static function get_cat_slug($cat_id) {
		$cat_id = (int) $cat_id;
		$category = get_category($cat_id);
		if (isset($category->slug)) {
			return $category->slug;
		} else {
			return "";
		}	
	}
	
	static function gen_link_cat($cat, $class='', $parent_id = 0, $href_js=null, $attr=array()) {
		
		if(!is_object($cat)){
			$cat = get_category($cat) ;
		}

		$style = 'info_cat '.$class;
		$attr_str = "";
		if(count($attr)>0){
			foreach ($attr as $k => $v){
				$attr_str .= $k ."=\"$v\" ";
			}
		}

		$cat_name = $cat->name;
		$cat_link = get_category_link($cat);
		
		if ($parent_id!=0) {
			$style .= ' '.self::get_cat_slug($parent_id);
		}
		$style = apply_filters('classes_gen_link_cat', $style, $cat->term_id, $parent_id);

		if($href_js){
			$link  = '<span '.$attr_str.' class="'.$style.'" data-href="'.$cat_link.'">';		
			$link .= $cat_name;
			$link .= '</span>';
		}else{
			$link  = '<a '.$attr_str.' class="'.$style.'" href="'.$cat_link.'">';
			$link .= $cat_name;
			$link .= '</a>';			
		}

		if(get_param_global('no-link-cat-vignette')) {
			$link  = '<span '.$attr_str.' class="'.$style.'">';
			$link .= $cat_name;
			$link .= '</span>';
		}
		return $link;
	}

	static function gen_most_popular_link_cat($cat_id, $class='', $parent_id = 0, $attr =array()) {
		$style = $class;
		if ($parent_id!=0) {
			$style .= ' '.get_cat_slug($parent_id);
		}
		$cat_name = get_cat_name($cat_id) ;
		if(isset($attr['lines'])){
			$cat_name = RW_Utils::mini_text_for_lines($cat_name, $attr['lines'][0], $attr['lines'][1]) ;
		}
		$link  = '<span  class="info_link" data-href="'.get_category_link($cat_id).'">';		
		$link .= '<span class="'. $style .'"  >'. $cat_name . '</span>';
		$link .= '</span>';
		return $link;
	}

	static function get_menu_cat_link($post, $class='', $first_level=false, $parent_id=false, $href_js=false, $simple_cat=false, $attr=array(),$exclude_cat='') {
		$category = self::get_menu_cat_post($post, $class, $first_level, $parent_id, $href_js, $simple_cat, $attr,$exclude_cat);
		if(is_object($category))
			$link = self::gen_link_cat($category, $class, $category->parent, $href_js, $attr);

		return $link;
	}

	static function get_menu_cat_post_old($p='', $class='', $first_level=false, $parent_id=false, $href_js=false, $simple_cat=false, $attr=array(),$exclude_cat='') {
		global $post ;
		$p = ($p)? $p:$post ;
		if(!is_object($p)){
			$p = get_post($p);
		} 
		$category  = null ;
		// TODO : get rid of this get_post_category_from_url
		if( apply_filters( 'disable_category_from_url' , !$simple_cat , $p) ) {
			$category = self::get_post_category_from_url($p, $first_level);
		} 
		if($simple_cat OR !$category){
			$cat =  get_post_meta($p->ID,'_category_permalink',true) ;
			if($cat){
				$category = get_category($cat) ;	
			}else{			
				$category = get_the_category();
				if($exclude_cat!='') {
					foreach ($category as $categor) {
						if($categor->slug!=$exclude_cat) {
							$category = $categor;
							break;
						}
					}
				}else {
					$category=isset($category[0]) ? $category[0] : '';
				}
			}
		}
		return $category;
	}


	static function get_menu_cat_post($p='', $class='', $first_level=false, $parent_id=false, $href_js=false, $simple_cat=false, $attr=array(),$exclude_cat='') {

		global $post ;
		$p = ($p)? $p:$post ;
		if(!is_object($p)){
			$p = get_post($p);
		} 

		if(isset(self::$menu_cat_post[$p->ID . $first_level])){
			return self::$menu_cat_post[$p->ID . $first_level] ;
		}


		$category  = null ;


		$cat =  get_post_meta($p->ID,'_category_permalink',true) ;
		if($cat){
			$category = get_category($cat) ;	
		}

		if(!$category){

			// TODO : get rid of this get_post_category_from_url
			if( apply_filters( 'disable_category_from_url' , !$simple_cat , $p) ) {
				$category = self::get_post_category_from_url($p, $first_level);
			} 

			if(!$category){
				$category = get_the_category();
				if($exclude_cat!='') {
					foreach ($category as $categor) {
						if($categor->slug!=$exclude_cat) {
							$category = $categor;
							break;
						}
					}
				}else {
					$category=isset($category[0]) ? $category[0] : '';
				}
			}
		}
		self::$menu_cat_post[$p->ID . $first_level]  = $category;
		return $category;
	}


	static function get_most_popular_cat_link($post, $class='', $attr =array()) {

		$link='';
		$category = self::get_menu_cat_post($post);
		
		if(is_object($category))
			$link = self::gen_most_popular_link_cat($category->term_id, $class, $category->parent, $attr);

		return $link;
	}

	static function get_origin_cat($post_id,$exclude_cat){
		global $exclude_categories;	
		$cat_slug = "";
		$exclude_categories []= 'mise-en-avant';
		$exclude_categories []= 'diaporama-accueil';
		
		$category = get_the_category($post_id);
		if($exclude_cat!='') {
			foreach ($category as $categor) {
				if($categor->slug!=$exclude_cat && !in_array($categor->slug, $exclude_categories)) {
					$category = $categor;
					break;
				}
			}
		}else {
			foreach ($category as $cat) {
				if(!in_array($cat->slug, $exclude_categories)) {
					$category = $cat;
					break;
				}
			}
		}
		$cat_slug = self::get_top_parent_category($category);
		return $cat_slug;
	}

	static function desc_category_children($current_cat,$paged=1) {
		global $current_cat,$wp_query ;
		ob_start();
		$max_num_pages=$wp_query->max_num_pages;
		include (RW_THEME_DIR . "/include/templates/title-page.php");
		$desc_category = ob_get_contents();
		ob_end_clean();
		return $desc_category;		
	}

	static function is_parent_cat_view(){
		global $wp_query ;
		$display_parent_cat =false;
		if($wp_query->query_vars['cat']){
			$current_cat = $wp_query->queried_object;
		}else if(!empty($wp_query->query_vars['category_name'])){
			$current_cat = self::rw_get_category_by_slug($wp_query->query_vars['category_name']) ;
		}
		if($current_cat){

			$cat_parent_site = apply_filters('cat_parent_site', 0);
			$is_parent_cat = $current_cat->parent==$cat_parent_site;
			$is_cat_has_children = count( get_categories( array('child_of'=> $current_cat->term_id))) > 0;

			if($is_parent_cat){
				$parent_cat = $current_cat ;
			}else{
				$parent_cat = get_term($current_cat->parent, 'category');
			}

			$home_rubrique =  RW_Taxonomy::get_taxonomy_option('meta_boxes_category', 'home_rubrique', $wp_query->query_vars['cat']) ;

			if(($is_parent_cat and $is_cat_has_children) or $home_rubrique){
				$display_parent_cat = true; 
			}else{
				$display_parent_cat = false; 
			}
			if(isset($wp_query->query_vars['taxonomy']) && $wp_query->query_vars['taxonomy']=='ingredient')
				$display_parent_cat = false;

			$display_parent_cat = apply_filters('display_parent_cat', $display_parent_cat);
		}else{
			$display_parent_cat = apply_filters('display_parent_cat_taxonomy', $display_parent_cat, $wp_query->queried_object);
		}

		return $display_parent_cat ;
	}

	static function compare_cat($c1, $c2){
		return $c1->term_id != $c2->term_id;
	}

	/**
	 * Retrieve category parents with separator including the category id.
	 * @param int $id Category ID.
	 * @param string $separator Optional, default is '/'. How to separate categories.
	 * @param array $visited Optional. Already linked to categories to prevent duplicates.
	 *
	 * @return string|WP_Error A list of category parents on success, WP_Error on failure.
	 */
	static function reworld_get_category_parents_ids( $id, $separator = '/', $visited = array()) {
		$key = $id.$separator.serialize($visited);
		if(isset(self::$category_parents_ids[$key])){
			return self::$category_parents_ids[$key] ;
		}
		$chain = '';
		$parent = get_term( $id, 'category' );
		if ( is_wp_error( $parent ) )
			return $parent;

		$name = $parent->term_id;

		if ( $parent->parent && ( $parent->parent != $parent->term_id ) && !in_array( $parent->parent, $visited ) ) {
			$visited[] = $parent->parent;
			$chain .= self::reworld_get_category_parents_ids( $parent->parent, $separator,  $visited );
		}

		
		$chain .= $name.$separator;
		self::$category_parents_ids[$key] = $chain;
		return $chain;
	}

	static function get_category_parent($id_cat=null,$is_slug=false) {
		if($is_slug){
			$cat=self::rw_get_category_by_slug($id_cat,$is_slug);
		}else {
			$id_cat = ($id_cat==null) ? get_query_var('cat') : $id_cat;
			$cat = get_category($id_cat,false);
		}
		return ($cat->category_parent != 0) ? $cat->category_parent : $cat->term_id ;
	}

	static function get_permalinked_category($post_id,$first=false) {
		if(isset(self::$permalinked_categoryBy_post[$post_id . $first])){
			return self::$permalinked_categoryBy_post[$post_id . $first] ;
		}

		$id_cat =  get_post_meta($post_id,'_category_permalink',true) ;
		if($id_cat){
			$return = get_category($id_cat,false);
		}elseif($first){
			$category=get_the_category($post_id);
			$return = $category[0];
		}
		else $return = false;

		self::$permalinked_categoryBy_post[$post_id . $first] = $return;
		return $return;
	}

	static function get_top_parent_category($category){
		
		if(isset(self::$top_parent_category[$category->term_id])){
			return self::$top_parent_category[$category->term_id] ;
		}
		if($category->parent != 0){
			$cat_tree = get_category_parents($category->term_id, FALSE, ':', TRUE);
			$cat_tree = apply_filters('category_parents_tree', $cat_tree) ;
			$top_cat = explode(':',$cat_tree);
			$slug = $top_cat[0];
		}else{
			$slug = $category->slug;
		}
		self::$top_parent_category[$category->term_id] = $slug ;
		return $slug ;
	}

	/**
	 * Get category parents' slugs 
	 *
	 * @param      int  category_id { category's id }
	 *
	 * @return     ARRAY ( array of categories parents' slugs )
	 */
	static function get_category_parents_slugs( $category_id ){
		if(isset(self::$category_parents_slugs[$category_id])){
			return self::$category_parents_slugs[$category_id] ;
		}

		$category_parents 		= get_category_parents($category_id, FALSE, ':', TRUE);

		// Apply filter to ignore RELATED_MAIN_SECTION categories : le-journal-de-la-maison ... 
		$category_parents 		= apply_filters('category_parents_tree', $category_parents);
		$category_parents_array = explode(':',$category_parents);
		array_pop( $category_parents_array );
		self::$category_parents_slugs[$category_id] = $category_parents_array ; 
		return $category_parents_array;
	}

	static function current_cat_is_child_of($parent_cat_slug){
		global $wp_query;
		if(is_category()){
			$id_current_cat = $wp_query->query_vars['cat'];
			$cat = self::rw_get_category_by_slug($parent_cat_slug);
			if( !empty($cat) ){
				return cat_is_ancestor_of($cat->term_id,$id_current_cat);
			}
		}
		return false;
	}

	static function get_image_category( $cat_id, $size_img, $class, $get_src = false ){
		$img = '';
		$attachment_id = RW_Taxonomy::get_taxonomy_option( 'custom-header-image', 'custom-header-image', $cat_id);
		if($attachment_id){
			if($get_src){
				$img = wp_get_attachment_image_src( $attachment_id, $size_img, true );
			}else{
				$img = wp_get_attachment_image( $attachment_id, $size_img, false, array('class' => $class) );
			}
		}
		return $img;
	}

	/**
	 * Check if a category has a parent category
	 * @param $category_id
	 * @return bool
	 */
	static function category_has_parent( $category_id ){
		$category = get_category( $category_id );
		if ( $category->category_parent > 0 ){
			return true;
		}
		return false;
	}

	/**
	 * Retourne true si le slug appartient à l'uri, false sinon.
	 *
	 * @param String $slug correspond à une partie de l'uri /$slug/.../.../
	 * @param String $current_uri correspond à l'uri courante donnée par $_SERVER
	 * @return boolean
	 */
	static function is_url_contains_category( $slug, $current_uri = '' ) {

		$current_uri  = empty( $current_uri ) ? $_SERVER['REQUEST_URI'] : $current_uri;
		$exploded_uri = explode( '/', $current_uri );

		if ( in_array( $slug, $exploded_uri ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get all categoris slug by post
	 * @param integer $post_id
	 * @param string $return (ARRAY, STRING)
	 * @return array string 
	 */
	static function get_all_categories_slug_by_post($post_id, $return = 'ARRAY'){
		$return  = strtoupper($return);
		$result = array();
		$categories = get_the_category($post_id);
		if(!empty($categories)){
			foreach ($categories as $category) {
				array_push($result, $category->slug);
			}
		}
		if($return  == 'STRING'){
			return implode($result, ',');
		}else{
			return $result;
		}
	}

	static function display_image_category() {
		$descriptionCategory='';
		$image_id = RW_Taxonmomy::get_taxonomy_option('custom-header-image', 'custom-header-image');
		if ($image_id!='') {
			$image = wp_get_attachment_image_src($image_id, 'full');
			$descriptionCategory='<div class="descriptionCategory"><img src="'.$image[0].'"></div>';
		}
		return $descriptionCategory;
	}

	static function filter_get_the_categories($categories){
		$cat_id = apply_filters('force_category_ads',false , $categories);
		if(!$cat_id){
			$cat_id =  get_post_meta(get_the_ID() , '_category_permalink',true) ;
		}

		if($cat_id){
			foreach ($categories as $index => $c) { 
				if($cat_id == $c->term_id){
					$categories = array($c);
					break ;
				}
			}
		}
		return $categories ;
	}

	static function get_categories_from_url($link_current_page){

		$link_current_page_origin = $link_current_page ;
		if(isset(self::$categories_from_url[$link_current_page_origin])){
			return self::$categories_from_url[$link_current_page_origin] ;
		}

		$items = array();
		$link = str_replace(get_site_url().'/', '', $link_current_page);
		$link = explode('/', $link);

		if (is_single()) {
			$count = count($link)-1;
		}
		if (is_category()) { 
			$count = count($link);
			 
		} 

		for ($i=0; $i<$count; $i++) {  
			$cat_object = self::rw_get_category_by_slug($link[$i]);
			if(is_object($cat_object)){
				$items[] = $cat_object; 
			} 
			 
		}
		self::$categories_from_url[$link_current_page_origin] = $items;
		return $items;

	}
	/**
	 * Gets the categories slugs from url.
	 *
	 * @param      <type>  $link_current_page  The link current page
	 *
	 * @return     array   The categories slugs from url.
	 */
	static function get_categories_slugs_from_url($link_current_page){
		$link_current_page_origin = $link_current_page ;
		if(isset(self::$categories_slugs_from_url[ '_' . $link_current_page_origin] )){
			return self::$categories_slugs_from_url[ '_' . $link_current_page_origin] ;
		}
		$items = array();
		$link_current_page = str_replace(get_site_url().'/', '', $link_current_page);
		$link_current_page = explode('/', $link_current_page);
		$count = 0;
		if (is_single()) {
			$count = count($link_current_page)-1;
		}
		if (is_category()) { 
			$count = count($link_current_page);
			 
		} 
		for ($i=0; $i<$count; $i++) {  
				$items[] = $link_current_page[$i];
		}
		self::$categories_slugs_from_url['_' . $link_current_page_origin] = $items;
		return $items;

	}

	/**
	 * Gets all ops rubrique.
	 * @return catories id
	 */
	static function get_all_ops_rubrique(){
		global $site_config, $wpdb;
		$slugs = '';
		$ids = [];
		
		if(!empty($site_config['dedicated_area'])){
			$slugs = "(";
			foreach ($site_config['dedicated_area'] as $slug => $item) {
				# code...
				$slugs .="'".$slug."',";
			}
			$slugs = substr($slugs, 0, -1);
			$slugs .=")";
		}
		$sql = 'SELECT term_id FROM '. $wpdb->prefix. 'terms WHERE slug IN '.$slugs;
		$categories = $wpdb->get_results( $sql) ;
		
		if(!empty($categories)){
			foreach ($categories as $category) {
				array_push($ids, $category->term_id);
			}
		}
		return $ids;
	}

	/**
	 * Get all post categories
	 * @return array of ids
	 */
	static function rw_get_post_categories(){
		global $post;
		if(isset(self::$post_categories_ids[$post->ID])){
			return self::$post_categories_ids[$post->ID] ;
		}
		$post_categories = self::$post_categories_ids[$post->ID] = wp_get_post_categories( $post->ID ); 
		return $post_categories;
	}
	
	static function rw_get_category_by_slug($slug){
		global $cache_categories;
		if(!isset($_GET['debug_cache'])):
			if ( defined('TIMEOUT_CACHE_TERMS') && TIMEOUT_CACHE_TERMS > 0 ){
				$time = time() ;
				// get from cache
				if ( empty($cache_categories) ) 
					$cache_categories = wp_cache_get( 'all' , 'categories_by_slug' ) ;
		     		
		 		if ( isset($cache_categories[$slug]) &&  ( $time - $cache_categories[$slug][1] ) < TIMEOUT_CACHE_TERMS )
		 			return $cache_categories[$slug][0];	
			}
		endif;
		$term = get_category_by_slug($slug);
		
		if( defined('TIMEOUT_CACHE_TERMS') &&  TIMEOUT_CACHE_TERMS > 0 ) {
			// don't cache not existing categories
			if( $term ){
				$cache_categories[$slug]= array ( $term , $time );
				wp_cache_set( 'all' , $cache_categories , 'categories_by_slug' , TIMEOUT_CACHE_TERMS );
			}
	    }
	    return $term;
	}
	static function show_exclusive_cats($atts){
		global $post,$wp_query;
		if( isset($atts['exclusive']) && $atts['exclusive'] ){
			$exclusive_cats = explode(',', $atts['exclusive'] );
			if(is_category()){
				$current_cat = $wp_query->query['category_name'];
				if(!in_array($current_cat, $exclusive_cats)){
					return false;
				}
			}else if( is_single() ){
				if( !in_category( $exclusive_cats ) ){
					return false;
				}
			}
		}
		return true;
	}
}