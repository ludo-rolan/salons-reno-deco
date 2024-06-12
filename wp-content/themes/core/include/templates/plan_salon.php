<?php  
	get_header();

	$exposant_slugs = ['amenagement', 'batiment', 'energie', 'exterieur'];
	$exposant_filters = !empty($_GET['filters']) ? explode(',', $_GET['filters']) : array();

	$sub_categories = get_data_from_cache('plan_salons_cats', 'page', 60*60*24, function() use($exposant_slugs) {
		return get_categories(
			array( 
				'slug' => $exposant_slugs,
				'hide_empty' => 0,
			)
		);
	});


?>

<div id="content"class="col-xs-12 col-md-8">
	<h1 class="page-title"><?php echo _e('plan du salon'); ?></h1>
	<div class="plan_salon_visuel">
		<img src="<?php echo STYLESHEET_DIR_URI ?>'/assets/images-v3/plan-salon.png'" alt="plan salon" class="img-responsive">
	</div>
	<a href="<?php echo get_option('pdf_plan_salon' , '#'); ?>" class="btn btn-primary" target="_blank">Télécharger le plan</a>

	<h2 class="page-title"><?php echo _e('liste des exposants'); ?></h2>

	<p class="bold">Découvrez les exposants du Salon de la Rénovation qui se tient du 14 au 17 janvier 2021 à Paris, Porte de Versailles.</p>

	<p class="bold">Vous souhaitez dès à présent prendre rendez-vous avec eux sur le salon ?</p>
	
	<p class="bold">Prenez contact avec eux sur leur page exposant.</p>

	<div class="filters" id="exposants_filter" data-hash-target="plan_salons_filters">
		<?php foreach ($sub_categories as $cat) { ?>
			<div class="filter-item">
				<div class="checkbox">
					<input type="checkbox" value="<?php echo $cat->slug; ?>" id="<?php echo $cat->slug; ?>" <?php if( is_array($exposant_filters) && in_array($cat->slug, $exposant_filters) ) echo 'checked'; ?>>
					<label class="form-check-classic-label" for="<?php echo $cat->slug; ?>">
						<?php echo $cat->name; ?>
					</label>
				</div>
			</div>
		<?php } ?>
	</div>

	<div class="bloc-posts">
		<div class="row">

			<?php

				if( !empty($exposant_filters) ){
					$sub_categories = get_data_from_cache('plan_salons_cats_filtered', 'page', 60*60*24, function() use($exposant_filters) {
						return get_categories(
							array( 
								'slug' => $exposant_filters,
								'hide_empty' => 0,
							)
						);
					});
				}

				$categories_ids = [];
				if ( !empty($sub_categories) ){
					foreach ($sub_categories as $categorie) {
						$categories_ids[] = $categorie->term_id;
					}
				}

				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$exposants = get_data_from_cache('plan_salons_exposantss_'.md5(serialize($categories_ids)).$paged, 'page', 60*60*24, function() use($categories_ids, $paged) {
					global $posts_exclude;
					$args = array(
						'posts_per_page' => 50,
						'post__not_in' => $posts_exclude,
						'paged' => (get_query_var('paged')) ? get_query_var('paged') : 1,
						'orderby' => 'title',
						'order' => 'ASC',
						'post_type' => ['exposant'],
						'tax_query' => array(
							array(
								'taxonomy' => 'category',
								'field'    => 'term_id',
								'terms'    => $categories_ids,
							),
						),
					);
					return new WP_Query( $args );
				});
				if ( $exposants->have_posts() ) {
					while ( $exposants->have_posts() ) {
						$exposants->the_post();
						setup_postdata($post);
						include(locate_template('include/templates/block_post_exposant.php'));
						wp_reset_postdata();
					}
				}else{
					echo "<p>Aucun article n'a été trouvé</p>";
				}
			?>
			<div class="col-xs-12">
				<?php 
					$max_num_pages = !empty($exposants->max_num_pages) ? $exposants->max_num_pages : 1;
					echo RW_Utils::reworldmedia_pagination($max_num_pages);
				?>
			</div>
		</div>
	</div> <!-- .bloc-posts -->

</div>

<?php
get_sidebar();
get_footer();