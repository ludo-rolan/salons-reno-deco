<?php 
global $posts_exclude;

get_header(); 

include(locate_template('include/templates/title-page.php'));

?>

<div id="content" role="main" class="<?php echo apply_filters("class_div_content","col-sm-12 col-md-8 col-lg-8 pull-left") ?>"> 

	<?php include(locate_template('include/templates/a-propos.php')); ?>

	<div id="results">

		<?php

		do_action('before_last_posts');

		if(get_param_global('enable_block_cache')){
			$cache_key = 'bloc_rubriques_hp';
			echo_from_cache($cache_key, 'last_posts', TIMEOUT_CACHE_LAST_POSTS, function(){
	        	include(locate_template('include/templates/last-exposants.php'));
			});
		}else{
			include(locate_template('include/templates/last-exposants.php'));
		}

		do_action('after_last_posts');

		?>

	</div>
	
	<?php include(locate_template('include/templates/exposition-physique.php')); ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>