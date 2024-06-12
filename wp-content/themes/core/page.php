<?php get_header(); ?>

<div id="content"class="col-xs-12 col-md-8">
	<?php 
		while (have_posts()) {
			the_post();
	?>

		<h2 class="page-title"><?php the_title(); ?></h2>
		<div class="page-content"><?php the_content(); ?></div>                              

	<?php } ?>
</div>

<?php
get_sidebar();
get_footer();