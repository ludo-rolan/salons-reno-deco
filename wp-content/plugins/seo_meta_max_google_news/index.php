<?php

/**
 * Plugin Name: Seo Meta Max Google News
 */

define('SEO_META_MAX_SNIPPET',150);
define('SEO_META_IMG_PREVIEW','large');
define('SEO_META_VIDEO_PREVIEW',-1);
require_once( plugin_dir_path(__FILE__).'/hooks.php' );
add_action('admin_init', 'seo_setting_init');
add_action( 'admin_menu', 'seo_setting_display_page');
function seo_setting_display_page() {
    add_options_page(
        'Optimisation search console',
        'Seo meta Google News',
        'manage_options',
        'seo_setting_meta',
        'seo_setting_display_page_html'
    );
}
function seo_setting_init()
{
    $option_group = 'seo-setting';
    $option_name  = 'seo_setting_meta';
    register_setting($option_group, $option_name);
    add_settings_section(
        'seo_meta_section',
        'NETWORK | SEO | Optimisation search console',
        'seo_setting_section_description',
        'seo-setting'
    );
    seo_setting_add_fields();
    register_setting( $option_group, 'seo_meta_max', 'seo_setting_seo_meta_max_snippet_validate2');
}

function seo_setting_section_description()
{
    echo '<p>les pages articles qui seront affichées sur les listings Google Actualités, donc la priorité va à ces contenus.</p>';
}


function seo_setting_add_fields()
{

    add_settings_field(
        'seo_meta_max',
        'Meta Tag Values',
        'seo_meta_max_snippet_callback2',
        'seo-setting',
        'seo_meta_section'
    );
}

function seo_meta_max_snippet_callback2()
{
    $seo_max_option = get_option('seo_meta_max');
    ?>
    <label>max snippet
    <input type="number" name="seo_meta_max[max_snippet]" value="<?php echo $seo_max_option['max_snippet'] ?>">
    </label>
    <label>image preview
     <input type="text" name="seo_meta_max[img_preview]" value="<?php echo $seo_max_option['img_preview'] ?>">
     </label>
    <label>video preview
     <input type="number" name="seo_meta_max[video_preview]" value="<?php echo $seo_max_option['video_preview'] ?>">
     </label>
<?php
}

function seo_setting_seo_meta_max_snippet_validate2($value){
    if (empty($value['max_snippet'])) {
        $value['max_snippet'] = SEO_META_MAX_SNIPPET;
    }
    if (empty($value['img_preview'])) {
        $value['img_preview'] = SEO_META_IMG_PREVIEW;
    }
    if (empty($value['video_preview'])) {
        $value['video_preview'] = SEO_META_VIDEO_PREVIEW;
    }
    return $value;
}

function seo_setting_display_page_html(){?>

    <form action="options.php" method="post">
        <?php settings_fields( 'seo-setting' ); ?>
        <?php do_settings_sections( 'seo-setting' ); ?>
         
        <input name="Submit" type="submit" value="sauvegarder" class="button button-primary" />
    </form>
<?php 
}