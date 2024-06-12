<?php 
global $posts_exclude;

get_header(); 

include(locate_template('include/templates/title-page.php'));

?>

<div id="content" role="main" class="<?php echo apply_filters("class_div_content","col-sm-12 col-md-8 col-lg-8 pull-left") ?>"> 

	<div id="results">

		<?php

		do_action('before_last_posts');
		$current_cat = get_category_by_slug('actualite');
		$nb_posts = 4;
		$sub_categories = get_data_from_cache('hp_sub_cats_'.$current_cat->term_id, 'home_rubrique', 60*60*24, function() use( $current_cat ) {
			
			$categories = [];

			array_push($categories, get_term_by('name', 'VOITURES ÉLECTRIQUES', 'category')->term_id);
			array_push($categories, get_term_by('name', 'VOITURES À HYDROGÈNE', 'category')->term_id);
			array_push($categories, get_term_by('name', 'VOITURES SANS PERMIS', 'category')->term_id);
			array_push($categories, get_term_by('name', 'MADE IN FRANCE', 'category')->term_id);
			array_push($categories, get_term_by('name', 'SPORT AUTOMOBILE', 'category')->term_id);
			array_push($categories, get_term_by('name', 'VOITURES DE LUXE', 'category')->term_id);
			// array_push($categories, get_term_by('name', 'VÉLOS ÉLECTRIQUES', 'category')->term_id);
			// array_push($categories, get_term_by('name', 'TROTTINETTES ÉLECTRIQUES', 'category')->term_id);
			// array_push($categories, get_term_by('name', 'DRONES', 'category')->term_id);

			return get_categories(
					array( 
						'parent' => $current_cat->term_id,
						'hide_empty' => 0,
						'orderby'    => 'include',
						'include'    => $categories
					)
				);
			});
			//include(locate_template('include/templates/sub_cats_posts.php'));
			include(locate_template('include/templates/actualite_posts.php'));
			if( apply_filters('conseils_experts_visibility', false, $current_cat) ){
				include(locate_template('include/templates/conseils_experts_posts.php'));
			}
			
		do_action('after_last_posts');

		?>

	</div>
	
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>