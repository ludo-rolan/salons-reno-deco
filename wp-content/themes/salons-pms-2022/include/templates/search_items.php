<?php

$cat = get_the_category($post->ID)[0];
$cat_meta  = get_option('category_'.$cat->term_id);
$cat_color = $cat_meta['color'];
$cat_lien = get_category_link($cat);
$post_link 	= get_permalink($post->ID);
$post_img 	= '';

if( has_post_thumbnail($post->ID)){
	$yoast_wpseo_title = get_post_meta( $post->ID, "_yoast_wpseo_title", true );
	$post_img_alt = ($yoast_wpseo_title) ? $yoast_wpseo_title : $post->post_title;
	$size = !empty($size) ? $size : "rw_medium_second" ;
	$post_img = get_the_post_thumbnail_url($post, $size);
}
?>
<div class ="post col-xs-6">
	<div class="post_img">
		<a href="javascript:void(0);" title="<?php echo $post->post_title ;?>" data-href="<?php echo $post_link; ?>">
			<img data-src="<?php if( !empty($post_img) ) echo $post_img; ?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8Xw8AAoMBgDTD2qgAAAAASUVORK5CYII=" class="img-responsive lazy-load" alt="<?php echo $post_img_alt; ?>">
		</a>
		<a class="info_cat" href="<?php echo $cat_lien; ?>" style="<?php echo (isset($cat_color)) ? "color:$cat_color":"";?>"><?php echo $cat->name; ?></a>
		
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
		<span class="post_line"></span>
	</div>
</div>