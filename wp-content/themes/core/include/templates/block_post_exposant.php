<?php 

$custom = get_post_custom($post->ID);
if (isset($custom['logo_exposant'][0])){
	$exposant_img = wp_get_attachment_image_src( $custom['logo_exposant'][0] )[0];
}
$numstand = (!empty($custom['num_stand'][0])) ?  $custom['num_stand'][0] : 'à venir';
$exposant_stand = 'N° de stand ' . $numstand ;
$exposant_link = get_the_permalink($post);

 ?>
<div class="bloc-post col-xs-12 col-sm-6">
	<div class="media">
		<div class="media-left">
			<img data-src="<?php echo $exposant_img; ?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8Xw8AAoMBgDTD2qgAAAAASUVORK5CYII=" class="bloc-post-visual media-object lazy-load" alt="<?php echo $post->post_title; ?>">
		</div>
		<div class="media-body">
			<?php echo RW_Category::get_menu_cat_link($post); ?>
			<div class="bloc-post-stand"><?php echo $exposant_stand; ?></div>
			<h3 class="bloc-post-title">
				<a href="<?php echo $exposant_link; ?>">
					<?php echo $post->post_title; ?>
				</a>
			</h3>
			<a href="<?php echo $exposant_link; ?>" class="btn btn-link">
				<?php echo _e('en savoir plus'); ?>
			</a>
		</div>
	</div>
</div>