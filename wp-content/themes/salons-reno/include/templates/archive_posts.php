<div class="row">
	<?php
	$category =get_queried_object();
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args = array(
			'post__not_in' => $posts_exclude,
			'posts_per_page' => 8,
			'paged' => $paged,
			'category_name' => $category->slug
		);
		$postslist = get_posts( $args );
		foreach ($postslist as $post) {
			setup_postdata($post);
			?>
		<div class="post col-xs-12 col-sm-6">
			<?php include(locate_template('include/templates/block_post.php')); ?>
		</div>
		<?php 
			wp_reset_postdata();
		}
		
	?>
	<div class="col-xs-12">
        <?php
		
			$post_args=array(
			'post__not_in' => $posts_exclude,
			'category_name' => 'conseils-experts',
			'posts_per_page' => -1,
			'fields' => 'ids'
			);
			$postTypes = get_posts($post_args);
			$total_posts = count($postTypes); 
			echo RW_Utils::reworldmedia_pagination(ceil($total_posts / 8)); ?>
	</div>
</div>
