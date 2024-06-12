<?php
	$title = '<h2 class="diapo_vertical_title">'. $item_title .'</h2>';
	$excerpt = '<div class="diapo_vertical_excerpt">'. $item_excerpt .'</div>';
?>





<div class="diapo_vertical_item" data-id="<?php echo $item_id; ?>">
	<?php 
		if( !rw_is_mobile() ){
			echo $title;
			echo $excerpt;
		}
	?>
	
<div id="before_diapo_vertical_items_<?php echo isset($_GET['index_items']) ? $_GET['index_items'] + 1 : 1 ;?>" class="defore_diapo_vertical_item">
		<?php do_action('before_diapo_vertical_img') ?>
	</div>


	<?php if( wp_doing_ajax() ): ?>
		<img class="diapo_vertical_img img-responsive lazy-load" src="<?php echo $item_image; ?>">
	<?php else: ?>
		<img class="diapo_vertical_img img-responsive lazy-load" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z/C/HgAGgwJ/lK3Q6wAAAABJRU5ErkJggg==" data-src="<?php echo $item_image; ?>">
	<?php endif; ?>
	
	<div id="after_diapo_vertical_img_<?php echo isset($_GET['index_items']) ? $_GET['index_items'] + 1 : 1 ;?>" class="after_diapo_vertical_img">
	<?php do_action('after_diapo_vertical_img') ?>
	</div>
	<?php 
		if( rw_is_mobile() ){
			echo $title;
			echo $excerpt;
		}
	?>
</div><!-- .diapo_vertical_item -->


	<div id="after_diapo_vertical_items_<?php echo isset($_GET['index_items']) ? $_GET['index_items'] + 1 : 1 ;?>" class="after_diapo_vertical_item">
<?php do_action('diapo_vertical_items', $count); ?>
		
	</div>


