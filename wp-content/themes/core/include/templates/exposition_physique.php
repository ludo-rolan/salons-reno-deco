<?php  
	get_header();
?>

<div id="content"class="col-xs-12 col-md-8">
	<h1 class="page-title"> L'ÉVÈNEMENT</h1>
	<?php
		$posts_type = 'post';
		$nb_posts = 4;
		$hide_read_more_link = true;
		$sub_categories = get_categories(
			array( 
				'slug' => ['animations'],
				'hide_empty' => 0,
			)
		);
		include(locate_template('include/templates/a-propos.php'));
		include(locate_template('include/templates/sub_cats_posts.php'));
		include(locate_template('include/templates/infos_pratiques.php'));
		include(locate_template('include/templates/bloc_invitation.php'));
	?>
</div>

<?php
get_sidebar();
get_footer();