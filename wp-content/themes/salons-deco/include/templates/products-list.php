<div class="col-xs-6 col-sm-4 col-md-3">
	<div class="shoopingbox_products_item">
		<div class="shoopingbox_products_item_visual">
			<a  class="product_link_tracking" data-product-campaign="<?php echo $slug; ?>" data-product-id="<?php echo $produit->ID; ?>" href="javascript:void(0);" rel="nofollow" target='_blank' title="<?php echo $produit->post_title ;?>" data-href="<?php echo $link; ?>">
				<img alt="<?php echo $produit->post_title ;?>" class="img-responsive" src="<?php echo $img ; ?>">
			</a>
		</div>
		<div class="shoopingbox_products_item_details">
			<a class="title product_link_tracking shoopingbox_products_item_title" data-product-campaign="<?php echo $slug; ?>" data-product-id="<?php echo $produit->ID; ?>" href="javascript:void(0);" target='_blank' rel="nofollow" title="<?php echo $produit->post_title ;?>" data-href="<?php echo $link; ?>">
				<?php echo $produit->post_title; ?>	
			</a>
			<p class="shoopingbox_products_item_excerpt">
				<?php echo $produit->post_content; ?>	
			</p>
		</div>
	</div>
</div>