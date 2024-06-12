<?php 

$last_posts = RW_Post::last_exposants( apply_filters( 'count_posts_block_category' , 4 ));

$menu_id = apply_filters('get_menu_name', 'menu_header', 'menu_header');
				
$menu_items = wp_get_nav_menu_items($menu_id);

$index_block= 0 ;
foreach ($last_posts as $bloc) {
	do_action('block_push_before_index_'.($index_block+1));
	$force_bloc_push = apply_filters('force_bloc_push', false , $bloc["category"]) ;
	$force_hide_bloc_push = apply_filters('force_hide_bloc_push', false , $bloc['category_object']) ;
	do_action('before_homeMoreArticle_bloc', $bloc['category_object']) ;

	if(((count($bloc['posts']) >= 1 or $force_bloc_push)) && !$force_hide_bloc_push){
		$cat_id_block_push=$bloc["category"];
		$cat_slug = RW_Category::get_cat_slug($cat_id_block_push);
		$cat_style_bloc = apply_filters('bloc_push_cls', " block_" .$cat_slug);
	 	?>
    	<div class="homeMoreArticles <?php echo $cat_style_bloc; ?> bloc_rubrique clearfix">
    		<?php do_action('before_block_push', $bloc); ?>
	    	<?php
	    	$title = apply_filters('title_home_h2', $bloc['title'],$cat_slug);
	    	
	    	RW_Post::show_title_home_h2($title, $bloc['url'] , apply_filters('more_category_text' , '', $bloc['title'], $cat_slug), apply_filters('bloc_push_js_link', true));

			if(get_param_global('enable_custom_block_push')) {
				do_action('custom_block_push',$menu_items,$bloc);
			}else{
				include(locate_template('include/templates/block-push.php'));
			}
	        
			do_action('sub_categories_last_posts',$menu_items,$bloc["category"]);
			do_action('after_block_push', $bloc );
	        ?>
    	</div>
	<?php
		do_action('after_homeMoreArticle_bloc', $bloc['category_object']) ;
	} 

	do_action('liste_plus_artiles_rubriques', $index_block, $last_posts);
	$index_block++;
}
?>

