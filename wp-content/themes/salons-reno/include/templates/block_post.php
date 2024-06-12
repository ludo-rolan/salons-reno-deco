<?php
$cat_link 	= RW_Category::get_menu_cat_link($post, "link_cat", false, false, true);
$post_link 	= get_permalink($post->ID);
$post_img 	= '';

if( has_post_thumbnail($post->ID)){
	$yoast_wpseo_title = get_post_meta( $post->ID, "_yoast_wpseo_title", true );
	$post_img_alt = ($yoast_wpseo_title) ? $yoast_wpseo_title : $post->post_title;
	$size = !empty($size) ? $size : "rw_medium" ;
	$post_img = get_the_post_thumbnail_url($post, $size);
}
?>

<div class="post_img">
	<a href="javascript:void(0);" title="<?php echo $post->post_title ;?>" data-href="<?php echo $post_link; ?>">
		<img data-src="<?php if( !empty($post_img) ) echo $post_img; ?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8Xw8AAoMBgDTD2qgAAAAASUVORK5CYII=" class="img-responsive lazy-load" alt="<?php echo $post_img_alt; ?>">
	</a>
	<?php echo $cat_link; ?>
</div>
<div class="post_caption">
	<div class="post_title">	
		<a title="<?php echo $titre;?>" href="<?php echo $post_link; ?>" >
			<?php echo $post->post_title; ?>
		</a>
	</div>
	<?php  if( apply_filters('show_post_excerpt', true, $post) && $post_expert = get_the_excerpt($post->ID) ){ ?>
		<div class="post_excerpt"><?php echo $post_expert; ?></div>
 	<?php } ?>
</div>