<?php
class Hp_Option
{
    const GROUP = 'hp_options';
    const first_post_actu = self::GROUP . '_first_post_actu';
   
    public static $global_option;
    public static $page;

    public static  function register()
    {
        add_action('admin_footer', [self::class, 'ajax_fetch']);
        add_action('admin_menu', [self::class, 'add_menu']);
        add_action('admin_init', [self::class, 'registerSettings']);
        add_action('hp_option_search_table_header', [self::class, 'hp_option_search_table_header']);
    }

    public static function registerSettings()
    {
        add_settings_field('', 'Rechercher ', function () {

            do_action('hp_option_input_container', "post");
        }, self::GROUP, 'home_pages');
        register_setting(self::GROUP, 'home_pages');

        register_setting(self::GROUP, self::first_post_actu);
        add_settings_section('home_pages', 'HP section Actualités', function () {
            echo "Saisissez l'ID de l'article à afficher en premier dans la HP section Actualités:";
        }, self::GROUP);
        add_settings_field(self::first_post_actu . 'option', 'ID ARTICLE ', function () {
            self::text_input(self::first_post_actu);
        }, self::GROUP, 'home_pages');

    }
    public static  function add_menu()
    {
        self::$page = add_options_page(
            "Gestion HomePage",
            "Gestion HomePage ",
            'manage_options',
            self::GROUP,
            [self::class, 'render']
        );
    }

    public static  function render()
    {
    ?>
        <div class="wrap">
            <h1>Gestion de la home page</h1>
            <form method="post" action="options.php">

                <?php
                settings_fields(self::GROUP);
                do_settings_sections(self::GROUP);
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
            <input type="text" id="<?php echo $type ?>" name="<?php echo $type ?>" value="<?php echo get_option($type) ?>">
            <?php
                // Le bloc qui affiche l'url du premier article dans les Actualités
                do_action('hp_option_post_link', get_option($type) );
            ?>
        </div>
    <?php
    }
    static function hp_option_search_table_header($padding)
    {
        ?>
        <th style="<?php echo $padding ?>">Id article</th>
        <th style="<?php echo $padding ?>">Titre</th>
        <th style="<?php echo $padding ?>">copier l'id</th>
        <th style="<?php echo $padding ?>">Voir article</th>
    <?php
    }
    static function ajax_fetch()
    {
        $screen = get_current_screen();
         if ( is_admin() && in_array( $screen->id, array( 'settings_page_hp_options') ) ){
            wp_register_script("hp_options", STYLESHEET_DIR_URI . '/assets/javascripts/admin/hp_option.js', array('jquery'));
            wp_localize_script('hp_options', 'data', array('ajax_url' => admin_url('admin-ajax.php')));
            wp_enqueue_script('hp_options');
        }
    }

   
}

Hp_Option::register();
