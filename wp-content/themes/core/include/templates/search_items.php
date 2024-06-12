<?php
$title = strip_tags(get_the_title());
$excerpt = mini_excerpt_for_lines(60, 2);
?>
<div class="item full-width row">
	<div class="col-xs-12 col-sm-6">
		<a title="<?php echo $post->post_title ;?>" href="<?php echo get_permalink(); ?>">
			<?php 
				if (has_post_thumbnail(get_the_ID())){ 
					$yoast_wpseo_title = get_post_meta(get_the_ID(), "_yoast_wpseo_title", true);
					$img_attr = ($yoast_wpseo_title != "") ? array('alt' => $yoast_wpseo_title) : array();
					echo get_the_post_thumbnail(get_the_ID(), 'rw_medium', ['class'=>'img-responsive']); 
				}
			?>
		</a>
	</div>    	
	<div class="col-xs-12 col-sm-6 items-posts">    
		<div class="caption"> 
			<?php echo RW_Category::get_menu_cat_link(get_post(), '', false, false, true) ;?>
			<h2> 
				<a title="<?php echo $post->post_title ;?>" href="<?php echo get_permalink(); ?>">
					<?php echo $title; ?>
				</a>
			</h2>
			<div class="desc"> <?php echo $excerpt; ?> </div>
		</div>
	</div>
</div>
