<?php

$post_link 	= get_permalink($post->ID);
$post_img 	= '';
$post_date = get_the_date('Y-m-d');
if( has_post_thumbnail($post->ID)){
	$yoast_wpseo_title = get_post_meta( $post->ID, "_yoast_wpseo_title", true );
	$post_img_alt = ($yoast_wpseo_title) ? $yoast_wpseo_title : $post->post_title;
	$size = !empty($size) ? $size : "rw_medium_lg" ;
	$post_img = get_the_post_thumbnail_url($post, $size);
}
?>

<div class="post-card_img">
	<a href="javascript:void(0);" title="<?php echo $post->post_title ;?>" data-href="<?php echo $post_link; ?>">
		<img data-src="<?php if( !empty($post_img) ) echo $post_img; ?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8Xw8AAoMBgDTD2qgAAAAASUVORK5CYII=" class="img-responsive lazy-load <?php echo ($is_first_post)? 'first-img':''; ?>" alt="<?php echo $post_img_alt; ?>">
	</a>
</div>
<div class="post-card_caption">
	<div class="post-card_title">	
		<a title="<?php echo $titre;?>" href="<?php echo $post_link; ?>" >
			<?php echo $post->post_title; ?>
		</a>
	</div>
	<?php  if( apply_filters('show_post_excerpt', true, $post) && $post_expert = get_the_excerpt($post->ID) ){ ?>
		<div class="post-card_excerpt"><?php echo $post_expert; ?></div>
 	<?php } ?>
</div>
<div class="post-card_date">
    <?php echo $post_date; ?>
</div>
<div class="post-card_more">
    <a class="post-card_more_link" href="<?php echo $post_link; ?>">Lire la suite</a>
</div>