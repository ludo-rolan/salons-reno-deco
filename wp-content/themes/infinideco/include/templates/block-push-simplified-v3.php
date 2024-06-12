<div class="homeMoreArticles pull_list bloc_rubrique clearfix">
	<h2 class="default-title"><a class="txt_wrapper" href="<?php $category_link ?>"><?php echo $category_name; ?></a></h2>
    <div class="row">
    	<div class="col-xs-12 col-sm-6">

			<div class="pushed-post">
				<?php
				global $posts_exclude ;
				
				$pushed_post = array_shift($post_hp);
				$posts_exclude = RW_Utils::add_value_to_array($pushed_post->ID, $posts_exclude);

				$link_attr = apply_filters('link_attr','',$pushed_post->ID) ;

				if (has_post_thumbnail($pushed_post->ID)){
					$yoast_wpseo_title = get_post_meta($pushed_post->ID, "_yoast_wpseo_title", true);
					if($yoast_wpseo_title != ""){
						$img_attr = array('alt' => $yoast_wpseo_title,'class' => "img-responsive");
					}else{
						$img_attr = array('class' => "img-responsive");
					}
					$size_img_pushed_post = apply_filters( 'size_img_pushed_post', 'rw_large' );		    			
					echo "<div class='thumbnail'><a href='javascript:void(0);' data-href='".get_permalink($pushed_post)."' ". $link_attr .">".get_the_post_thumbnail($pushed_post->ID, $size_img_pushed_post, $img_attr)."</a></div>";
				}
				$show_date_bk_push='';
				if(get_param_global('show_date_bk_push')){
					$show_date_bk_push='<span class="info_link">'.get_the_date(get_param_global('date_format'),$pushed_post->ID).'</span>';
				}
				
				echo '<div class="title-push">';
				echo '<div class="bk_push">';
				echo $show_date_bk_push;
				echo '<h3><a href="'.get_permalink($pushed_post).'" '.  $link_attr  .'>'.mini_text_for_lines($pushed_post->post_title, 35, 2).'</a></h3>';
				echo '</div>';
				echo "</div>";	
				?>
			</div>
			</div>
			<ul class="items-small-list clearfix <?php $class_div_pushed_post;?>">
				<?php

				$i=0;
				foreach ($post_hp as $p) {
					$i++ ;
					setup_postdata($p);
					$posts_exclude = RW_Utils::add_value_to_array($p->ID, $posts_exclude);					
					if (!isset($cat_style)) {
						$cat_style = "link_cat";
					}
					if (!isset($current_cat_id)) {
						$current_cat_id = false;
					}
					get_param_global('IS_READ_MORE','no');
					$title = strip_tags(mini_title_for_lines(26 , 2));
					$excerpt = mini_excerpt_for_lines(32, 2);
					$h2_class = "link";
					$cat_link = get_menu_cat_link($p, $cat_style, false, $current_cat_id, true);
					$div_style = (!has_post_thumbnail($p->post_title))?'style="width:100%"':'';
					$li_class = "item";
					$title_complement = "";
					$thumb_size = $format_pushed_small;
					$link_attr = apply_filters('link_attr','',$p->ID) ;
					$class_item=apply_filters('class_thumbnail_item_block_push','');
					?>

					<li class="thumbnail-item">
						<div class="post_thumbnail">
							<a href="javascript:void(0);" title="<?php echo $p->post_title ;?>" data-href="<?php echo get_permalink($p); ?>" <?php echo $link_attr ; ?>>
								<?php if (has_post_thumbnail($p->ID)){ ?>	
								<?php $yoast_wpseo_title = get_post_meta($p->ID, "_yoast_wpseo_title", true);
								if($yoast_wpseo_title != ""){
									$img_attr = array('alt' => $yoast_wpseo_title);
								}else{
									$img_attr = array();
								}
								?>

								<?php echo get_the_post_thumbnail($p->ID, $thumb_size, $img_attr); ?>			
								<?php }?>
							</a>
						</div>
						<div class="post_details">

							<?php 
							$date_span = '';
							$date_span_bottom = '';
							$date_span = '<span class="info_link">' .get_the_date(get_param_global('date_format'),$p->ID). '</span>';
							
							if(get_param_global('block_push_date_bottom')){
								$date_span_bottom = $date_span;
								$date_span = "";
							}else{
								echo $date_span;
							}

							?>
							<h3>
								<a title="<?php echo $p->post_title ;?>" href="<?php echo get_permalink($p); ?>" <?php echo $link_attr ; ?>>
									<span class="title-item-small"><?php echo mini_text_for_lines($p->post_title, 35, 2) ;?></span>
								</a>
							</h3>
							<?php echo $date_span_bottom; ?>
							<?php do_action('after_title_block_push',$p); ?>
						</div>
					</li>

					<?php 
				wp_reset_postdata();
				}
				?>
			</ul>
	</div>
</div>