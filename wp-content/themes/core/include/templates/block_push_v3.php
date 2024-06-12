<?php
global $post, $posts_exclude;
?>
<div class="row">
<?php if( !empty($bloc["post_push"]) ): ?>
<?php $pushed_excerpt = RW_Utils::mini_excerpt_for_lines(60, 2); ?>
<div class="col-xs-12">
	<div class="pushed-post <?php echo (!empty($pushed_excerpt)) ? 'has_excerpt' : ''; ?>">
		<?php
		$post = $bloc["post_push"];
		setup_postdata($post);
		$the_permalink = get_permalink();
		if (has_post_thumbnail($post)){
			$yoast_wpseo_title = get_post_meta($post->ID, "_yoast_wpseo_title", true);
			if($yoast_wpseo_title != ""){
				$img_attr = array('alt' => $yoast_wpseo_title,'class' => "img-responsive");
			}else{
				$img_attr = array('class' => "img-responsive");
			}
			echo "<div class='thumbnail'><a href='javascript:void(0);' data-href='".$the_permalink."'>".get_the_post_thumbnail($post, 'rw_large', $img_attr)."</a></div>";
		}
		$show_date_bk_push='';
		echo '<div class="title-push">';
		echo '<div class="bk_push">';
		echo '<span class="info_link_cat">'.RW_Category::get_menu_cat_link($post).'</span>';
		echo '<h3><a href="'.$the_permalink.'">'.get_the_title().'</a></h3>';
		echo '<a href="'. $the_permalink .'"><div class="info_link_excerpt"><p>'.$pushed_excerpt.'</p></div></a>';
		echo '</div>';//bk_push
		echo "</div>";//title-push	
		wp_reset_postdata();
		?>
	</div>
	<?php do_action( 'after_item_hp_bloc_rubrique'); ?>
</div>
<?php endif; ?>
<ul class="items-small-list clearfix <?php $class_div_pushed_post;?>">
	<?php
		if(count($bloc['posts'])>4) unset($bloc['posts'][3]);
		$bloc['posts'] = apply_filters('articles_block', $bloc['posts'], $bloc['category']) ;
		$i=0;
		foreach ($bloc['posts'] as $post) {
			$i++ ;
			setup_postdata($post);
			?>

			<li class="thumbnail-item col-xs-12 col-sm-6 col-md-4">
				<div class="post_thumbnail">
					<span href="javascript:void(0);" class="link-post" title="<?php echo str_replace('"', '', $post->post_title) ;?>" data-href="<?php echo get_permalink(); ?>">
						<?php if (has_post_thumbnail(get_the_ID())){ ?>	
						<?php $yoast_wpseo_title = get_post_meta(get_the_ID(), "_yoast_wpseo_title", true);
						if($yoast_wpseo_title != ""){
							$img_attr = array('alt' => $yoast_wpseo_title);
						}else{
							$img_attr = array();
						}
						?>

						<?php echo get_the_post_thumbnail(get_the_ID(), 'rw_medium', $img_attr); ?>			
						<?php }?>
					</span>
					<?php 
						$excerpt = RW_Utils::mini_excerpt_for_lines(32, 2);
						if( !empty($excerpt) ){
							echo '<a href="'. get_permalink($post) .'">';
							echo '	<div class="desc"><p>'. $excerpt .'</p></div>';
							echo '</a>';
						}
					?>
				</div>
				<div class="post_details">
					<span class="info_link_cat">
						<?php echo RW_Category::get_menu_cat_link($post); ?>
					</span>
					<h3>
						<a title="<?php echo str_replace('"', '', $post->post_title) ;?>" href="<?php echo get_permalink(); ?>">
							<span class="title-item-small"><?php echo $post->post_title;?></span>
						</a>
					</h3>
					<?php do_action('after_title_block_push',$post); ?>
				</div>
			</li>
			<?php do_action( 'after_item_hp_bloc_rubrique'); ?>
			<?php 
		}
		wp_reset_postdata();
	?>
</ul>
</div>
