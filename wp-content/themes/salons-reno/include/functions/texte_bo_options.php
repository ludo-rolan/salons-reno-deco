<?php

if (is_admin()) :

    class Salon_Textes_HP
    {

        function __construct()
        {
            add_action('admin_menu', array(&$this, 'add_options_admin'));
            add_action('admin_init', array(&$this, 'add_option'));
        }

        function add_options_admin()
        {
            add_options_page('Textes de Homepage', __('Modification Des Textes', REWORLDMEDIA_TERMS), 'manage_options', 'Textes_de_Homepage', array(&$this, 'Textes_de_Homepage'));
        }
        function add_option()
        {
            register_setting('text_options', 'special_content');
        }

        function Textes_de_Homepage()
        {
            settings_fields('text_options');
            do_settings_sections(__FILE__);
            $special_content = get_option('special_content');
            if (isset($_POST['apropos_block'])) {
                $special_content['data_apropos'] = $_POST['apropos_block'];
            }
            if (isset($_POST['header_date'])) {
                $special_content['data_header_date'] = $_POST['header_date'];
            }
            if (isset($_POST['header_texte'])) {
                $special_content['data_header_texte'] = $_POST['header_texte'];
            }
             if (isset($_POST['evenement_block'])) {
                $special_content['data_evenement_bloc'] = $_POST['evenement_block'];
            }
            if (isset($_POST['bf_map_date'])) {
                $special_content['data_bf_map_date'] = $_POST['bf_map_date'];
            }
            if (isset($_POST['bf_map_location'])) {
                $special_content['data_bf_map_location'] = $_POST['bf_map_location'];
            }
             if (isset($_POST['after_map_block'])) {
                $special_content['data_after_map'] = $_POST['after_map_block'];
            }
            if (isset($_POST['apropos_evenement'])) {
                $special_content['data_apropos_evenement'] = $_POST['apropos_evenement'];
            }

            update_option('special_content', $special_content);

?>
            <div class='wrap'>
                <form method='post' action="">
                    <h1> HOME PAGE  </h1>
                    <h3> --HP - a propos block</h3>
                    <?php
                    wp_editor($special_content['data_apropos'], 'editor1', $settings = array('textarea_name' => 'apropos_block', 'textarea_rows' => '10')); ?>
                    <h3> --HP -Header Date </h3>
                    <input type="texte" name="header_date" value="<?php echo $special_content['data_header_date']; ?>" style="width:100%;">

                    <h3> --HP - Header title </h3>
                    <input type="texte" name="header_texte" value="<?php echo $special_content['data_header_texte']; ?>" style="width:100%;">
                    
                    <h3> --HP - Bloc "L'ÉVÈNEMENT" </h3>
                    <?php
                    wp_editor($special_content['data_evenement_bloc'], 'editor2', $settings = array('textarea_name' => 'evenement_block', 'textarea_rows' => '10')); ?>
                    <hr>
                    <h1> PAGE EVENEMENT  </h1>

                    <h3> --PAGE EVENEMENT - le texte à mettre avant la map Google - Date de l'événement: </h3>
                    <input type="texte" name="bf_map_date" value="<?php echo $special_content['data_bf_map_date']; ?>" style="width:100%;">

                    <h3> --PAGE EVENEMENT - le texte à mettre avant la map Google -  Lieu de l'événement: </h3>
                    <input type="texte" name="bf_map_location" value="<?php echo $special_content['data_bf_map_location']; ?>" style="width:100%;">



                    <h3> --PAGE EVENEMENT - le texte à mettre sous la map Google - BLOC INFOS PRATIQUES :</h3>
                    <?php
                    wp_editor($special_content['data_after_map'], 'editor4', $settings = array('textarea_name' => 'after_map_block', 'textarea_rows' => '10')); ?>
                    

                    <h3> --PAGE EVENEMENT - A propos :</h3>
                    <?php
                    wp_editor($special_content['data_apropos_evenement'], 'editor5', $settings = array('textarea_name' => 'apropos_evenement', 'textarea_rows' => '10')); ?>
                    <?php
                    submit_button('Save', 'primary'); ?>
                </form>
            </div>
<?php
        }
    }

    new Salon_Textes_HP();
endif;
