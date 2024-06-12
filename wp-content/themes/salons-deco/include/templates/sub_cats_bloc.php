<?php
if( !empty($sub_categories) ){
	?>
	<div class="archive-cats">
		<div class="row">
			<?php
				foreach ($sub_categories as $categorie) {
					$categorie_link = get_category_link($categorie);
					?>
					<div class="col-xs-6 col-sm-3">
						<div class="archive-cat <?php echo $categorie->slug; ?>">
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