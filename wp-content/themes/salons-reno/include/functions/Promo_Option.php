<?php
class Promo_Option
{
    const GROUP = 'promo_option';
    const cta_title = self::GROUP . '_cta_title';
    const cta_link = self::GROUP . '_cta_link';
    const img_desktop = self::GROUP . '_img_1';
    const img_mobile = self::GROUP . '_img_2';
    const date = self::GROUP . '_date';


    public static  function register()
    {
        add_action('admin_menu', [self::class, 'add_menu']);
        add_action('admin_init', [self::class, 'registerSettings']);
        add_action('admin_enqueue_scripts', [self::class, 'media_uploader_enqueue']);
        add_action('wp_enqueue_scripts', [self::class, 'promo_enqueue']);
    }
    public static function registerSettings()
    {
        register_setting(self::GROUP, self::cta_title);
        register_setting(self::GROUP, self::cta_link);
        register_setting(self::GROUP, self::img_desktop);
        register_setting(self::GROUP, self::img_mobile);
        register_setting(self::GROUP, self::date);

        add_settings_section('promo_pages', 'Pop-up offre promotionnelle', function () {
            echo "Vous pouvez configurer le pop-up offre promotionnelle :";
        }, self::GROUP);


        add_settings_field(self::cta_title . 'option', 'Texte CTA ', function () {
            self::text_input(self::cta_title);
        }, self::GROUP, 'promo_pages');
        add_settings_field(self::cta_link . 'option', 'Lien CTA ', function () {
            self::text_input(self::cta_link);
        }, self::GROUP, 'promo_pages');
        add_settings_field(self::img_desktop . 'option', 'Image Desktop :', function () {
            self::edit_term_media("Visuel 1", self::img_desktop, 1);
        }, self::GROUP, 'promo_pages');
        add_settings_field(self::img_mobile . 'option', 'Image Mobile :', function () {
            self::edit_term_media("Visuel 2", self::img_mobile, 2);
        }, self::GROUP, 'promo_pages');

        add_settings_field(self::date . 'option', 'Date :', function () {
            $value = get_option(self::date);
?>
            <input type="text" name="<?php echo self::date; ?>" value="<?php echo $value ?>" />

        <?php
        }, self::GROUP, 'promo_pages');
    }
    public static  function add_menu()
    {
        add_options_page(
            "Gestion pop-up offre promotionnelle",
            "Gestion pop-up offre promotionnelle",
            'manage_options',
            self::GROUP,
            [self::class, 'render']
        );
    }
    public static  function render()
    {
        ?>
        <div class="wrap">
            <h1>Gestion pop-up offre promotionnelle </h1>
            <form method="post" action="options.php">
                <?php
                settings_fields(self::GROUP);
                do_settings_sections(self::GROUP);
                ?>


                <?php
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    static function text_input($type)
    {
    ?>
        <div>
            <input class="widefat" type="text" id="<?php echo $type ?>" name="<?php echo $type ?>" value="<?php echo get_option($type) ?>">
        </div>
    <?php
    }


    static function check_input($type)
    {
        get_option($type) == '1' ? $checked = 'checked' : $checked = '';
    ?>
        <div>
            <input type="checkbox" id="<?php echo $type ?>" name="<?php echo $type ?>" <?php echo $checked ?> value="1"></input>
        </div>
    <?php
    }

    static function buttons($class, $i)
    {
        $btn = $class . "_button_" . $i;
        $remove = $class . "_remove_" . $i;
    ?>
        <p>
            <input type="button" class="button button-secondary  <?php echo $btn ?>" id="<?php echo $btn ?>" name="<?php echo $btn ?>" value="<?php _e('Ajouter ', 'hero-theme'); ?>" />
            <input type="button" class="button button-secondary <?php echo $remove ?>" id="<?php echo $remove ?>" name="<?php echo $remove ?>" value="<?php _e('Supprimer ', 'hero-theme'); ?>" />
        </p>
    <?php
    }
    static function edit_term_media($title, $name, $i)
    {

    ?>
        <tr class="form-field term-group-wrap">

            <?php $image_id =  self::media_hidden_input($title, $name);
            ?>
            <td>
                <div id="promo_option_wrapper_<?php echo $i ?>">
                    <?php if ($image_id) { ?>
                        <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                    <?php } ?>
                </div>
                <?php self::buttons("ct_tax_media", $i); ?>
            </td>
        </tr>


    <?php
    }
    public static function media_hidden_input($name, $type)
    {
    ?>
        <th scope="row">
            <label for="<?php echo $type ?>"><?php _e($name, 'hero-theme'); ?></label>
        </th>
        <?php $media_id = get_option($type); ?>
        <input type="hidden" id="<?php echo $type ?>" name="<?php echo $type ?>" value="<?php echo $media_id; ?>">
<?php
        return $media_id;
    }
    static function media_uploader_enqueue($suffix)
    {
        wp_enqueue_media();
        wp_enqueue_script('pop-up-moment', STYLESHEET_DIR_URI . "/assets/javascripts/moment.min.js", array('jquery'), null, true);
        wp_enqueue_script('pop-up-datepicker',  STYLESHEET_DIR_URI . "/assets/javascripts/daterangepicker.min.js", array('jquery', 'moment'), null, true);
        wp_enqueue_style('pop-up-datepicker-css',  STYLESHEET_DIR_URI . "/assets/css/daterangepicker.css");
        wp_enqueue_script('pop-up-promo', STYLESHEET_DIR_URI . '/assets/javascripts/pop-up-promo-admin.js', array('jquery'), null, true);
    }
    static function promo_enqueue()
    {
        
        if (get_option('promo_option_date')) {

            $dates = get_option('promo_option_date');
            $dates = explode(' - ', $dates);
            $date1 = new DateTime($dates[0]);
            $date2 = new DateTime($dates[1]);
            $today = new DateTime('now');
            if ($date1 <= $today  && $today <= $date2) {
                
                    self::lunch_promo();
                }

            
        }
    }
    static function lunch_promo()
    {
        wp_enqueue_script('bootstrap-js', "https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js", array('jquery'));
        wp_enqueue_script("pop_up_ad_promo", STYLESHEET_DIR_URI . '/assets/javascripts/promo_up_ad.js', array('jquery', 'bootstrap-js'));
    }
}



Promo_Option::register();
