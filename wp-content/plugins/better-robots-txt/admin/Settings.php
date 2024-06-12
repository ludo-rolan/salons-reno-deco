<?php
namespace Pagup\BetterRobots;
use Pagup\BetterRobots\Core\Asset;

class Settings {

    public function __construct()
    {

        $settings = new \Pagup\BetterRobots\Controllers\SettingsController;
        $metabox = new \Pagup\BetterRobots\Controllers\MetaboxController;

        // Add settings page
        add_action( 'admin_menu', array( &$settings, 'add_settings' ) );

        // Add metabox to post-types
        add_action( 'add_meta_boxes', array(&$metabox, 'add_metabox') );

        // Save meta data
        add_action( 'save_post', array(&$metabox, 'metadata'));

        // Add setting link to plugin page
        $plugin_base = ROBOTS_PLUGIN_BASE;
        add_filter( "plugin_action_links_{$plugin_base}", array( &$this, 'setting_link' ) );
        
        // Add styles and scripts
        add_action( 'admin_enqueue_scripts', array( &$this, 'assets') );

    }

    public function setting_link( $links ) {

        array_unshift( $links, '<a href="admin.php?page=better-robots-txt">Settings</a>' );

        return $links;
    }

    public function assets() {

        Asset::style_remote('rt_font', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');
        Asset::style('rt_tyles', 'app.css');
        Asset::script('rt_script', 'admin/assets/app.js', array(), true);
        Asset::script('rt_masonry', 'vendor/masonry.min.js', array(), true);
    
    }

}

$settings = new Settings;