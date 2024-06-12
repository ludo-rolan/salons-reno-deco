<?php $eco_part =  isset($custom['Eco-part'][0])?  $custom['Eco-part'][0] : '' ;  ?>
<?php $marque =  isset($custom['Marque'][0])?  $custom['Marque'][0] : '' ;  ?>
<?php $price = apply_filters( 'price_format', $price ); ?>

<div id="post-<?php echo $produit->ID; ?>" class="item col-xs-6 col-md-<?php echo 12/$nb_products_to_show; ?> pull-left" >	
    <div class="block">
	    <div class="visu">
	    	<a  data-product-campaign="<?php echo $slug; ?>" data-product-id="<?php echo $produit->ID; ?>"  class="product_link_tracking" href="javascript:void(0);" rel="nofollow" target='_blank' title="<?php echo $produit->post_title ;?>" data-href="<?php echo $link; ?>">
				<img alt="<?php echo $produit->post_title ;?>" class="attachment-thumbnail  wp-post-image" src="<?php echo $img ; ?>">
				<?php if( isset($product_cta_btn) ){ ?>
				<span class="btn"><?php _e($product_cta_btn) ; ?></span>
				<?php }else{ ?>
				<span class="btn"><?php _e("En savoir +") ; ?></span>
				<?php } ?>
			</a> 
	    </div>
	    <div class="info">
			<a data-product-campaign="<?php echo $slug; ?>" data-product-id="<?php echo $produit->ID; ?>"   href="javascript:void(0);" target='_blank' rel="nofollow" class="title product_link_tracking" title="<?php echo $produit->post_title ;?>" data-href="<?php echo $link; ?>">
				<?php echo $produit->post_title.' '.$marque; ?>	
			</a>
			<?php if ($price){?>
				<span class="price"><?php echo $price; ?></span>
			<?php }?>
			<?php if ($old_price && $old_price > 0){?>
				<s class="old_price"><?php echo number_format(str_replace(',', '.',$old_price),2); ?>&euro;</s>
			<?php }?>
			<?php if( !empty($eco_part) ){?>
				<div class="eco_part">Dont <?php echo $eco_part;?>&euro; d'eco-participation </div>    	
			<?php }
			do_action( 'after_eco_part_item_product_v2', $custom );
			?>
	    </div>
	</div>
</div>

