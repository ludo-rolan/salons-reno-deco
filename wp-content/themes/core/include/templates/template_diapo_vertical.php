<?php
	echo '<script type="text/javascript"> var diapo_ids = '. json_encode( explode(",", $attr['ids']) ) .' </script> ' ;
	ob_start();
	$nb_items_to_load = 1;
	$count = 0;
	for ($i = 0; $i < $nb_items_to_load; $i++):
		$item_id = $current_gallery_images[$i]->ID;
		$item_title = $current_gallery_images[$i]->post_title;
		$item_excerpt = $current_gallery_images[$i]->post_excerpt;
		$size = wp_is_mobile() ? 'medium' : 'full';
		$item_image = $attachments[$i][$size][0];
		$count=$i;
?>

		<div class="diapo_vertical" id="diapo_vertical">
			<?php include(locate_template('include/templates/template_diapo_vertical_items.php')); ?>
		</div><!-- .diapo_vertical -->

<?php
	endfor;
wp_nonce_field( 'load_next_diapo_item_nonce', 'load_next_diapo_item' );
$content_diapo = ob_get_clean();