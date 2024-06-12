<?php
	$conseils_experts_cat = apply_filters('conseils_experts_cat', $current_cat, $parent_cat);
	$categorie_link = apply_filters('conseils_experts_read_more', '', $conseils_experts_cat);
	if( !$categorie_link ){
		$cat_conseils_experts = RW_Category::rw_get_category_by_slug('conseils-deco');
		$categorie_link = get_category_link($cat_conseils_experts); 
	}

?>

<div class="row">
	<?php
	if( apply_filters('conseils_experts_by_cat', false, $conseils_experts_cat) ){
		$posts = get_data_from_cache('conseils_experts_posts', 'home_rubrique', 60*60*24, function(){
			global $posts_exclude;
			$args = array(
				'showposts' => 2,
				'category_name' => 'conseils-deco',
				'post__not_in' => $posts_exclude,
				'post_type' => 'post',
			);
			if( defined('_LOCKING_ON_') && _LOCKING_ON_&&  $args_lock = get_locking_config('sous_category', 'conseils-deco'.$current_cat->slug)){
				return Locking::get_locking_ids($args_lock , $args);
			}else{
				return get_posts( $args);
			}
		});
	}else{
		$posts = get_data_from_cache('conseils_experts_'.$conseils_experts_cat->term_id, 'home_rubrique', 60*60*24, function() use( $conseils_experts_cat , $current_cat) {
			global $posts_exclude;
			$args = array(
				'showposts' => 4,
				'category__in' => $conseils_experts_cat->term_id,
				'post__not_in' => $posts_exclude,
				'post_type' => 'post',
				'orderby' => 'date',
				'order'   => 'ASC',
			);
			if( defined('_LOCKING_ON_') && _LOCKING_ON_&&  $args_lock = get_locking_config('sous_category', 'conseils-deco'.$current_cat->slug)){
				return Locking::get_locking_ids($args_lock , $args);
			}else{
				return get_posts( $args);
			}
		});
	}

		foreach ($posts as $post) {
			setup_postdata($post);
			?>
			<div class="post col-xs-12 col-sm-6">
				<?php include(locate_template('include/templates/block_post.php')); ?>
			</div>
			<?php 
			wp_reset_postdata();
		}
	?>
</div>