<?php
/**
* 
*/
class RW_Taxonomy {
	

	private static $_instance;

	function __construct(){
		# code...
	}
	
	static function get_instance(){
		if(is_null(self::$_instance)){
			self::$_instance = new RW_Taxonomy();
		}
		return self::$_instance;
	}

	static function get_taxonomy_option($section_id, $key, $cat_id=0) {
		global $taxonomy_option;
		$value = '';
		if ($cat_id==0 && get_query_var('cat')) {
			$cat_id = (int)get_query_var('cat');
		}
		if ($cat_id!=0) {
			if( empty($taxonomy_option[$section_id]) ){
				$section = get_option($section_id, '');
				$taxonomy_option[$section_id] = $section;
			}else{
				$section = $taxonomy_option[$section_id];
			}
			if (isset($section) && $section!='' && isset($section[$cat_id]) && isset($section[$cat_id][$key])) {
				if (is_array($section[$cat_id][$key])) {
					$value = $section[$cat_id][$key][0];
				} else {
					$value = $section[$cat_id][$key];
				}
				
			}
		}
		return $value;
	}

	static function display_image_taxonomy($custom_param) {
		$with_image = false;
		if(is_single()){
			$term =  get_post_category_from_url();
			$image_id = get_taxonomy_option($custom_param, $custom_param, $term->term_id);
		}else{
			$image_id = get_taxonomy_option($custom_param, $custom_param);
		}
		$with_image="";
		if ($image_id!='') {
			$image = wp_get_attachment_image_src($image_id, 'full');
			if ($image!==false) {
				
				$with_image = $image[0];
			}
		}
		return $with_image;
	}

}