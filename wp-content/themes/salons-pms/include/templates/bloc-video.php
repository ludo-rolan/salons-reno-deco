<?php 
	$posts = get_posts(array(
		'showposts' => 2, 
		's' => '[fpvideo ' ,
		'orderby'          => 'post_date', 
		'order'            => 'DESC',
	));
	if( !empty($posts) && count($posts) == 2 ):
?>
	<div class="homeMoreArticles bloc-video bloc_rubrique">
		<div class="default-title">
			<h2 class="pull-left"><?php _e('Videos'); ?></h2>
			<?php
				$category_video = get_param_global('category_video', 'videos');
				$category_link = get_term_link($category_video, 'category');
				if($category_link){
					?>
					<a href="javascript:void(0);" data-href="<?php echo $category_link; ?>" class="pull-right more_cat">
						<?php echo __("Voir toutes les vidéos", REWORLDMEDIA_TERMS);?>
					</a>
					<?php
				}
		 	?>
		 </div>
		<div class="row">
			<?php 
			foreach ($posts as $post) {
				setup_postdata($post);
				?>
					<div class="post col-xs-12 col-sm-6">
						<?php
							$size = "rw_medium";
							include(locate_template('include/templates/block_post.php'));
						?>
					</div>
				<?php 
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
<?php endif; ?>
