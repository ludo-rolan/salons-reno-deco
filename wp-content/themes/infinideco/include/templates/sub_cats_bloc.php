<?php
if( !empty($sub_categories) ){
	?>
	<div class="archive-cats">
		<div class="row">
			<?php
				foreach ($sub_categories as $categorie) {
					$categorie_link = get_category_link($categorie);
					// get image term meta
					$image_id = get_term_meta($categorie->term_id, 'cat_image', true);
					// get image url full size
					$image_url = wp_get_attachment_image_src($image_id, 'full')[0]??'';
					?>
					<div class="col-xs-6 col-sm-3">
						<div class="archive-cat" style="background-image:url(<?php echo $image_url; ?>)" >
							<a href="<?php echo $categorie_link; ?>" class="archive-cat-title">
								<?php echo $categorie->name; ?>
							</a>
						</div>
					</div>
					<?php
				}
			?>
		</div>
	</div> <!-- .archive-cats -->
	<?php
}
?>