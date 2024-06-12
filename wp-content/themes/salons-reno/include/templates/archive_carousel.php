<?php
	if( empty($posts_type) ) $posts_type = 'exposant';
	$posts = get_data_from_cache('archive_carousel_'.$current_cat->term_id, 'home_rubrique', 60*60*24, 
	function() use( $current_cat, $posts_type ) {
		global $posts_exclude;
		$args = array(
			'showposts' => 5,
			'category__in' => $current_cat->term_id,
			'post__not_in' => $posts_exclude,
			'post_type' => $posts_type,
			
		);
			$args['meta_query'] = array(
				array(
					'key'       => 'reno_slider_locking',
					'value'     => true,
				)
			);
		
		if( defined('_LOCKING_ON_') && _LOCKING_ON_&&  $args_lock = get_locking_config('sous_category', 'carousel'.$current_cat->slug)){
			return Locking::get_locking_ids($args_lock , $args);
		}else{
			return get_posts($args);
		}
	});
?>
<div id="archive_carousel" class="carousel carousel-archive slide" data-ride="carousel">
	<div class="carousel-inner">
		<?php
		$active_slide = true;
		foreach ($posts as $post) {
			$posts_exclude = RW_Utils::add_value_to_array($post->ID, $posts_exclude);
			?>
			<div class="item <?php if( $active_slide ) echo 'active'; ?>">
				<div class="carousel-visual">
					<img data-src="<?php echo get_the_post_thumbnail_url($post, 'rw_gallery_full'); ?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8Xw8AAoMBgDTD2qgAAAAASUVORK5CYII=" alt="<?php echo $post->post_title; ?>" class="img-responsive lazy-load">
				</div>

				<?php if( $posts_type == 'exposant'){ ?>
					<div class="carousel-caption carousel-exposant-caption">
						<?php 
						$logo_exposant = get_post_meta($post->ID, 'logo_exposant');
						if( !empty($logo_exposant[0]) ){ 
							$exposant_img = wp_get_attachment_image_src( $logo_exposant[0], 'full' )[0];
							?>
							<a href="<?php the_permalink($post); ?>">
								<img data-src="<?php echo $exposant_img; ?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8Xw8AAoMBgDTD2qgAAAAASUVORK5CYII=" class="carousel-exposant-img lazy-load" alt="<?php echo $post->post_title; ?>">
							</a>
							<?php 
						}
						echo RW_Category::get_menu_cat_link($post);
						$num_stand = get_post_meta($post->ID, 'num_stand')
						?>
						<div class="carousel-exposant-stand">
							<?php echo !empty($num_stand[0]) ? 'N° de stand ' . $num_stand[0] : 'N° de stand à venir'; ?>
						</div>
						<a href="<?php the_permalink($post); ?>" class="carousel-exposant-name">
							<?php echo $post->post_title; ?>
						</a>
					</div>
				<?php } else { ?>
					<div class="carousel-caption">
						<?php echo RW_Category::get_menu_cat_link($post); ?>
						<a href="<?php the_permalink($post); ?>" class="carousel-title">
							<?php echo $post->post_title; ?>
						</a>
					</div>
				<?php } ?>

			</div><!-- .item -->
			<?php
			$active_slide = false;
		}
		?>
	</div>
	<!-- Controls -->
	<a class="left carousel-control" href="#archive_carousel" role="button" data-slide="prev">
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#archive_carousel" role="button" data-slide="next">
		<span class="sr-only">Next</span>
	</a>

</div> <!-- #archive_carousel -->