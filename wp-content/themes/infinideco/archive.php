<?php  
global $wp_query, $posts_exclude;
get_header();
$current_cat = $wp_query->get_queried_object();
if( !empty($current_cat->parent) ) $parent_cat = get_category($current_cat->parent);
?>

<div id="content"class="col-xs-12 col-md-8">
	<h2 class="page-title"><?php echo $current_cat->name; ?></h2>

	<?php 
		if( !$current_cat->parent && in_array($current_cat->slug, array('exposition-virtuelle', 'conseils-deco')) ){
			$nb_posts = 4;
			if( $current_cat->slug == 'conseils-deco' )  $posts_type = 'post';
			$sub_categories = get_data_from_cache('hp_sub_cats_'.$current_cat->term_id, 'home_rubrique', 60*60*24, function() use( $current_cat ) {
				$sub_cats = get_option("locking_ev_cats","");
				$sub_cats = explode(',', get_option("locking_ev_cats",""));
				return get_categories(
					array( 
						'include' => $sub_cats,
						'hide_empty' => 0,
					)
				);
			});
			
			include(locate_template('include/templates/sub_cats_bloc.php'));
			if( apply_filters('archive_carousel_visibility', false, $current_cat) ){
				include(locate_template('include/templates/archive_carousel.php'));
			}
			include(locate_template('include/templates/sub_cats_posts.php'));
			// disactiver le block conseil deco en infinideco
			// if( apply_filters('conseils_experts_visibility', false, $current_cat) ){
			// 	include(locate_template('include/templates/conseils_experts_posts.php'));
			// }
			if( apply_filters('exposition_physique_visibility', false, $current_cat) ){
				include(locate_template('include/templates/exposition-physique.php'));
			}
		}else if( !empty($parent_cat->slug) && $parent_cat->slug == 'exposition-virtuelle' ){
			include(locate_template('include/templates/archive_carousel.php'));
			include(locate_template('include/templates/exposants_posts.php'));
			include(locate_template('include/templates/conseils_experts_posts.php'));
			include(locate_template('include/templates/exposition-physique.php'));
		}else{
			include(locate_template('include/templates/archive_posts.php'));
		}
	?>

</div>

<?php
get_sidebar();
do_action('before_archive_footer',$current_cat);
get_footer();