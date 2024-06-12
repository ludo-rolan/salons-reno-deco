<div class="shoopingbox_products" >
	<h2 class="shoopingbox_products_title default-title">
		<?php echo $product_title; ?>
	</h2>
	<div class="shoopingbox_products_list row">
	<?php
		$products_content = '';
    	foreach ( $produits as $produit ) {
    		$custom = get_post_custom( $produit->ID );
    		$img = $custom['bigimage'][0];
			$link = home_url() . '/s.php?product_id=' . $produit->ID;
			ob_start();
			include( locate_template( 'include/templates/products-list.php' ) );
			$products_content .= ob_get_clean();
		}
		echo $products_content;
	?>
	</div>
</div>