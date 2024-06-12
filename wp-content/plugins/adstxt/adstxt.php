<?php
/*
 Plugin Name: Ads Txt manager
 Plugin URI: http://this-plugin-has-no-uri.com
 Description: Authorized Digital Sellers (ads.txt) file manager
 Version: 1.0.0
 Author: webpick.info
 Author URI: webpick.info
 License: GPLv2 or later
*/
if( !defined('ABSPATH') ){ exit();}
if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

require_once( dirname( __FILE__ ) . '/adstxt.class.php' );

if(class_exists('AdsTxt')){
    $adstxt = new AdsTxt;
    if(isset($_GET['adstxt']) && $_GET['adstxt']==1){
        $adstxt->render_output();
    }
}
