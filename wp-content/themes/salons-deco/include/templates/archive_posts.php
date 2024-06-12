<div class="row">
	<?php
		while ( have_posts() ) : the_post();
			setup_postdata($post);
			?>
				<div class="post col-xs-12 col-sm-6">
					<?php include(locate_template('include/templates/block_post.php')); ?>
				</div>					
			<?php
		endwhile;
		wp_reset_postdata();
	?>
	<div class="col-xs-12">
        <?php echo RW_Utils::reworldmedia_pagination(); ?>
	</div>
</div>
