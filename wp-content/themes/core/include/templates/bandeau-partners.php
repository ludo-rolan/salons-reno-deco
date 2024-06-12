<?php
$partners_count = count($bandeau_partner['name']);
if($partners_count) {
?>
	<div class="bandeau-partners">
		<div class="container">
			<div class="col-xs-12">
				<h2 class="default-title">Partenaires</h2>
				<div class="list-unstyled bandeau-partners-carousel" id="bandeau-partners-carousel">
					<?php 
					for($i = 0; $i < $partners_count; $i++) {
						if($bandeau_partner['name'][$i] && 
							$bandeau_partner['url'][$i] && 
							$bandeau_partner['logo'][$i]) {
							?>
							<div class="bandeau-partners-carousel-item">
								<a href="<?php echo $bandeau_partner['url'][$i]; ?>" target="_blank">
									<img data-src="<?php echo $bandeau_partner['logo'][$i]; ?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8Xw8AAoMBgDTD2qgAAAAASUVORK5CYII=" class="img-responsive lazy-load" alt="<?php echo $bandeau_partner['name'][$i]; ?>"/>
								</a>
							</div>
							<?php
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
<?php 
} 
?>