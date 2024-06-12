<div class="row">
	<?php
		global $post;
		foreach ($posts as $post) {
			setup_postdata($post);
			$title = get_the_title();
			$link = get_the_permalink();
			$cat = RW_Category::get_permalinked_category(get_the_ID(), true);
			$thumbnail = get_the_post_thumbnail(get_the_ID(), 'rw_medium', array('class'=>'img-responsive'));
			$cat_link = get_category_link($cat);
			$date = get_the_date();
			?>
				<div class="col-xs-12 col-sm-4 col-md-4 post_item_<?php echo get_the_ID(); ?>">
					<div class="post">
						<div class="post_img">
							<a href="<?php echo $link; ?>">
								<?php echo $thumbnail; ?>
							</a>
							<a class="info_cat" href="<?php echo $cat_link;?>"><?php echo $cat->name; ?></a>
						</div>
						<div class="post_caption">
							<span class="post_date"><?php echo $date; ?></span>
							<h3 class="post_title">
								<a href="<?php echo $link; ?>">
									<?php echo $title; ?>
								</a>
							</h3>
						</div>
					</div>
				</div>
			<?php
		}
		wp_reset_postdata();
	?>
</div>