<?php 
global $post;
$link = get_permalink();
?>

<div class="post col-xs-12 col-sm-6">
	<div class="post_img">
			<span href="javascript:void(0);" title="<?php echo $title;?>" class="link-post" data-href="<?php echo $link; ?>">
				<?php
				if ( has_post_thumbnail(get_the_ID()) ){	
					echo get_the_post_thumbnail($post, 'rw_medium', $img_attr);
				}
				?>
			</span>
		<?php echo apply_filters('info_cat_list_item_v3', $cat_link, $i);?>
	</div>
	<div class="post_caption">
		<h3 class="post_title">	
			<a title="<?php echo $title;?>" href="<?php echo $link; ?>">
				<?php echo $title; ?>	
			</a>                	
		</h3>
		<?php if( !empty($excerpt) ){ ?>
			<div class="post_excerpt"><?php echo RW_Utils::mini_text_for_lines($excerpt,55,2); ?></div>
		<?php } ?>
	</div>

</div>
<?php
