<?php  
global $wp_query, $posts_exclude;
get_header();
$current_cat = $wp_query->get_queried_object();
if( !empty($current_cat->parent) ) $parent_cat = get_category($current_cat->parent);
echo RW_Utils::breadcrumb();
?>
<h2 class="page-title"><?php echo $current_cat->name; ?></h2>
    
<div id="content"class="col-xs-12 col-md-8">
	
	<?php 
		if( !$current_cat->parent && in_array($current_cat->slug, array('actualite')) ){
			$nb_posts = 4;
			if( $current_cat->slug == 'conseils-experts' )  $posts_type = 'post';
			$sub_categories = get_data_from_cache('hp_sub_cats_'.$current_cat->term_id, 'home_rubrique', 60*60*24, function() use( $current_cat ) {
			
				$categories = [];
	
				array_push($categories, get_term_by('name', 'VOITURES ÉLECTRIQUES', 'category')->term_id);
				array_push($categories, get_term_by('name', 'VOITURES À HYDROGÈNE', 'category')->term_id);
				array_push($categories, get_term_by('name', 'VOITURES SANS PERMIS', 'category')->term_id);
				array_push($categories, get_term_by('name', 'MADE IN FRANCE', 'category')->term_id);
				array_push($categories, get_term_by('name', 'SPORT AUTOMOBILE', 'category')->term_id);
				array_push($categories, get_term_by('name', 'VOITURES DE LUXE', 'category')->term_id);
				//array_push($categories, get_term_by('name', 'VÉLOS ÉLECTRIQUES', 'category')->term_id);
				//array_push($categories, get_term_by('name', 'TROTTINETTES ÉLECTRIQUES', 'category')->term_id);
				//array_push($categories, get_term_by('name', 'DRONES', 'category')->term_id);
	
				return get_categories(
						array( 
							'parent' => $current_cat->term_id,
							'hide_empty' => 0,
							'orderby'    => 'include',
							'include'    => $categories
						)
					);
				});
				include(locate_template('include/templates/sub_cats_posts.php'));
				if( apply_filters('conseils_experts_visibility', false, $current_cat) ){
					include(locate_template('include/templates/conseils_experts_posts.php'));
				}
				
			
			if( apply_filters('archive_carousel_visibility', false, $current_cat) ){
				include(locate_template('include/templates/archive_carousel.php'));
			}
			if( apply_filters('conseils_experts_visibility', false, $current_cat) ){
				include(locate_template('include/templates/conseils_experts_posts.php'));
			}
			if( apply_filters('exposition_physique_visibility', false, $current_cat) ){
				include(locate_template('include/templates/exposition-physique.php'));
			}
		}else if( !empty($parent_cat->slug) && $parent_cat->slug == 'exposition-virtuelle' ){
			include(locate_template('include/templates/sub_cats_links.php'));
		}else{
			$is_communique_presse = false;
			if(in_array($current_cat->slug, array('communiques-dossier-presse'))) {
				echo "<p>Accédez au Dossier de Presse et aux communiqués de presse du Mondial de l’Auto 2022</p>";
				$is_communique_presse = true;
			}
			include(locate_template('include/templates/archive_posts.php'));
			include(locate_template('include/templates/sub_cats_posts.php'));
			if( apply_filters('conseils_experts_visibility', false, $current_cat) ){
				include(locate_template('include/templates/conseils_experts_posts.php'));
			}
			if( apply_filters('exposition_physique_visibility', false, $current_cat) ){
				include(locate_template('include/templates/exposition-physique.php'));
			}
		}
	?>

</div>

<?php
get_sidebar();
do_action('before_archive_footer',$current_cat);
get_footer();