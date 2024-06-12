<?php
$exposants = get_data_from_cache('exposant_posts_'.$current_cat->term_id, 'home_rubrique', 60*60*24, function() use( $current_cat ) {
	global $posts_exclude;
	$args = array(
		'numberposts' => 50,
		'category' => $current_cat->term_id,
		'orderby' => 'title',
		'order' => 'ASC',
		'post_type' => ['exposant'],
	);
	return get_posts( $args);
});

if( !empty($exposants) ){
	?>
	<div class="bloc-posts">
		<h3 class="default-title"><?php echo $categorie->name; ?></h3>
		<div class="row">
			<?php
			foreach ($exposants as $post) {
				setup_postdata($post);
				include(locate_template('include/templates/block_post_exposant.php'));
				wp_reset_postdata();
			}
		?>
		</div>
	</div> <!-- .bloc_exposants -->
	<?php
}