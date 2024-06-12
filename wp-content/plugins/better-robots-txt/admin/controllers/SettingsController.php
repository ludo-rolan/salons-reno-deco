<?php

namespace Pagup\BetterRobots\Controllers;

use  Pagup\BetterRobots\Core\Option ;
use  Pagup\BetterRobots\Core\Plugin ;
use  Pagup\BetterRobots\Core\Request ;
use  Pagup\BetterRobots\Traits\RobotsHelper ;
use  Pagup\BetterRobots\Traits\Sitemap ;
class SettingsController
{
    use  RobotsHelper, Sitemap ;
    protected  $get_pro = '' ;
    public  $yoast_sitemap_url = '' ;
    public  $xml_sitemap_url = '' ;
    public function __construct()
    {
        $this->get_pro = sprintf( wp_kses( __( '<a href="%s">Get Pro version</a> to enable', "better-robots-txt" ), array(
            'a' => array(
            'href'   => array(),
            'target' => array(),
        ),
        ) ), esc_url( "admin.php?page=better-robots-txt-pricing" ) );
        $this->yoast_sitemap_url = home_url() . '/sitemap_index.xml';
        $this->xml_sitemap_url = home_url() . '/sitemap.xml';
    }
    
    public function add_settings()
    {
        add_menu_page(
            __( 'Better Robots.txt Settings', 'better-robots-txt' ),
            __( 'Better Robots.txt', 'better-robots-txt' ),
            'manage_options',
            'better-robots-txt',
            array( &$this, 'page' ),
            'dashicons-text-page'
        );
    }
    
    public function page()
    {
        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sorry, you are not allowed to access this page.', "better-robots-txt" ) );
        }
        // only users with `unfiltered_html` can edit scripts.
        if ( !current_user_can( 'unfiltered_html' ) ) {
            wp_die( __( 'Sorry, you are not allowed to edit this page. Ask your administrator for assistance.', "better-robots-txt" ) );
        }
        $safe = [
            "allow",
            "disallow",
            "yes",
            "no",
            "remove_settings",
            "rt-settings",
            "rt-faq",
            "rt-recs",
            "rt-growth",
            "wp_sitemap",
            "yoast_sitemap",
            "aios_sitemap",
            "custom_sitemap"
        ];
        $agents = $this->agents();
        $yoast_sitemap = $this->yoast_sitemap();
        $xml_sitemap = $this->xml_sitemap();
        $success = '';
        
        if ( isset( $_POST['update'] ) ) {
            if ( function_exists( 'current_user_can' ) && !current_user_can( 'manage_options' ) && !current_user_can( 'unfiltered_html' ) ) {
                die( 'Sorry, not allowed...' );
            }
            $options = [
                'feed_protector'  => Request::safe( 'feed_protector', $safe ),
                'user_agents'     => Request::textarea( 'user_agents' ),
                'crawl_delay'     => (int) Request::numeric( 'crawl_delay' ),
                'personalize'     => Request::textarea( 'personalize' ),
                'covid-19'        => Request::safe( 'covid-19', $safe ),
                'boost-alt'       => Request::safe( 'boost-alt', $safe ),
                'rt-mobilook'     => Request::safe( 'rt-mobilook', $safe ),
                'rt-bigta'        => Request::safe( 'rt-bigta', $safe ),
                'rt-meta'         => Request::safe( 'rt-meta', $safe ),
                'rt-vidseo'       => Request::safe( 'vidseo', $safe ),
                'ads-txt'         => Request::safe( 'ads-txt', $safe ),
                'app-ads-txt'     => Request::safe( 'app-ads-txt', $safe ),
                'remove_settings' => Request::safe( 'remove_settings', $safe ),
            ];
            // Step 1
            foreach ( $agents as $key => $bot ) {
                $options[$bot['slug']] = Request::safe( $bot['slug'], $safe );
            }
            update_option( 'robots_txt', $options );
            // update options
            echo  '<div class="notice rt-notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Settings saved.' ) . '</strong></p></div>' ;
        }
        
        $options = new Option();
        $notification = new \Pagup\BetterRobots\Controllers\NotificationController();
        echo  $notification->support() ;
        //set active class for navigation tabs
        $active_tab = ( isset( $_GET['tab'] ) && in_array( $_GET['tab'], $safe ) ? sanitize_key( $_GET['tab'] ) : 'rt-settings' );
        //Plugin::dd($_POST);
        // var_dump(Option::get("sitemap"));
        $sitemap_notification = $this->sitemap_notification();
        $get_pro = $this->get_pro;
        // Return Views
        if ( $active_tab == 'rt-settings' ) {
            return Plugin::view( 'settings', compact(
                'active_tab',
                'options',
                'agents',
                'sitemap_notification',
                'get_pro',
                'success'
            ) );
        }
        if ( $active_tab == 'rt-faq' ) {
            return Plugin::view( "faq", compact( 'active_tab' ) );
        }
        if ( $active_tab == 'rt-recs' ) {
            return Plugin::view( "recommendations", compact( 'active_tab' ) );
        }
        if ( $active_tab == 'rt-growth' ) {
            return Plugin::view( "growth", compact( 'active_tab' ) );
        }
    }

}
$settings = new SettingsController();