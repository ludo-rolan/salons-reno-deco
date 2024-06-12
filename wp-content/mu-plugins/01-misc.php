<?php

function get_param_global($param,$default_if_not_set='') {
	global $site_config;
	$v = (isset($site_config[$param])) ? $site_config[$param] : $default_if_not_set ;
	$v = apply_filters('param_global_' .$param , $v) ;
	return $v;
}

function rw_is_mobile(){
	return defined('MOBILE_MODE') && MOBILE_MODE;
}

function rw_is_tablet(){
  return defined('DEVICE_TYPE') && DEVICE_TYPE == 'Tablet';
}


function is_dev($key = null, $show_warning = true){
	global $devs, $option_devs ;
	if(defined('WP_IS_UNITTESTING') AND WP_IS_UNITTESTING == true){
		return true;
	}
	$return =  ((defined('_IS_PREPROD_') AND _IS_PREPROD_ == true) OR (defined('_IS_DEV_') and _IS_DEV_ == true)OR (defined('_IS_LOCAL_') and _IS_LOCAL_ == true)  or isset($_GET['is_dev'])) ;
	if($key){
		if( $show_warning && empty($devs[$key])  && defined('_SHOW_WARNING_IS_DEV_') ){
			echo "<p class='errors'>Erreur : la clé utilisé pour la mises en prod est non existe ($key) </>";
		}
		if(isset($devs[$key])){
			if(is_array($devs[$key]) && isset($devs[$key]['default'])){
				$return = $devs[$key]['default'] ;
			}
		}else{
			$return = false ;	
		}
		if(!$option_devs){
			$option_devs =  get_option(apply_filters('name_option','option_devs'), array());
		}
	
		if(count($option_devs) && isset($devs[$key]) ){
			$return = isset( $option_devs[ $key ]['active'] ) ? $option_devs[ $key ]['active'] : $return;
		}


		// ajouter une valeur pour activer ou desactiver l'option à travers l'url (pour les tests cachés en prod )
		if ( isset( $_GET[ $key ] ) ) {
			if ( in_array( $_GET[ $key ], array( 'false', '0' ) ) ) {
				$return = false;
			} else {
				$return = true;
			}
		}
	}
	return $return ;

}

function rw_enqueue_style( $handle, $src = '', $deps = array(), $ver = false, $media = 'all' ) {

	if(is_dev('web_perf_seo_urgent_7881_css_footer') && !is_admin() ){
		add_action('get_footer', function()use ($handle, $src,  $deps , $ver, $media){
			wp_enqueue_style( $handle, $src,  $deps , $ver, $media)  ;

		});
	}else{
		wp_enqueue_style( $handle, $src,  $deps , $ver, $media)  ;
	}

}
