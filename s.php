<?php
// to where we are.
define('IS_S_PHP' , true);
require (dirname(__FILE__) . '/wp-load.php');
wp();
function track_click($custom,$product_id){
	if(isset($custom['clicks'])){
		$clicks = (int) $custom['clicks'][0] ;
	}else{
		$clicks = 0;		
	}
	$clicks ++ ;
	update_post_meta($product_id, 'clicks', $clicks);	
}

if(isset($_GET['product_id'])){

	$product_id = $_GET['product_id'] ;
	$custom = get_post_custom($product_id);


	$campaigns =  get_the_terms( $product_id, 'campaign' );
	if ( is_array($campaigns) )
		$first_campaign = array_shift( $campaigns );
	else $first_campaign=false;
	if(isset( $first_campaign->slug)){
		$campaign = $first_campaign->slug ;
	}else{
		$campaign = 'trois-suisses' ;
	}
	
	if ( isset($custom['producturl']))
		$producturl = $custom['producturl'][0] ;
	else $producturl = apply_filters( 'get_product_url' ,  get_permalink ( $product_id ) , $product_id ,  $campaign  );
	
	track_click($custom,$product_id);	

	do_action('before_product_tracking', $producturl , $campaign);
	$producturl = apply_filters( 'product_tracking' , $producturl , $campaign );

	header('Location: ' . $producturl);	
} else if(isset($_GET['voucher_id'])){
	if( is_dev( 'erreur_404_vouchers_151760056' ) ){
		wp_redirect( '/', 301 );
		exit;
	}else{
		$voucher_id = $_GET['voucher_id'] ;
		$custom = get_post_custom($voucher_id);

		$voucher_url = $custom['voucher_defaultTrackUri'][0] ;


		track_click($custom,$voucher_id);
		if(is_dev("widget_102902242") && isset($_GET['show_code']) ){
			$bp = get_post( $voucher_id );
			ob_start();
			include (locate_template("/include/templates/show.php"));
			$output = ob_get_contents();
			ob_end_clean();
			echo $output;
		} else {
			header('Location: ' . $voucher_url);
		}
	}
}
exit;
