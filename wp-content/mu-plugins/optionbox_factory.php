<?php

/**
 * Abstraction for the creation of Option Pages
 * with many implementations for optionboxes in WordPress
 * such as : TextInputs, WPEditor ...
 * @class Options_Factory
 */

class Options_Factory
{
    /**
     * the Option Page Name
     * @var string page_name
     */
    protected $page_name ;

    /**
     * Option Definition
     * @var array option_page
     */
    protected $option_page = array();

    /**
     * optionbox to create defining their types
     * @var array $option_page_fields
     */
    protected $option_page_fields = array();
    /**
     * condition to define if postoption are created in a single way in the database
     * @var boolean $is_single_option
     */
    protected $is_single_option = false;
    /**
     * This is a Test Block tht will be deleted after Preparing the documentation
     * TODO: Add Documentation 
        array(
            "page_name" => "hp_options", // page name in wp-admin
            "is_single_option" => false, // if true, the options will be created in a single way in the database
            "option_page" => array(
                "id" => "test_option", // option ID to get using Options
                "page_title" => "Test Option", // Option Title
                "menu_title" => "Test Option Menu",
                "description" => "normal", // just a bla bla page description,
            ),
            "fields" => array(
                array(
                    "label" => "Test Option Text",
                    "suffix_id" => "_text", // option_page id + suffix_id
                    "sanitize" => true, //store without html tags
                    "type" => Text_MetaBox_Type::class,
                ),
                array(
                    "label" => "Test Option WPEditor",
                    "suffix_id" => "_description",
                    "type" => WPEditor_MetaBox_Type::class
                ),
            )
        )
     */
    public function __construct($args)
    {
        $this->page_name = $args["page_name"];
        $this->option_page = $args["option_page"];
        $this->option_page_fields = $args["fields"];
        $this->is_single_option = $args["is_single_option"]??false;

        add_action('admin_footer', [&$this, 'ajax_fetch']);
        add_action('admin_menu', [&$this, 'create_option_page']);
        // add_action('admin_init', [&$this, 'save_option_page']);
    }
    
    function ajax_fetch()
    {
        $screen = get_current_screen();
        if ( is_admin() && in_array( $screen->id, array( $this->page_name) ) ){
            wp_register_script("hp_options", STYLESHEET_DIR_URI . '/assets/javascripts/admin/hp_option.js', array('jquery'));
            wp_localize_script('hp_options', 'data', array('ajax_url' => admin_url('admin-ajax.php')));
            wp_enqueue_script('hp_options');
        }
    }
    function create_option_page()
    {
        add_options_page(
            $this->option_page["page_title"],
            $this->option_page["menu_title"],
            'manage_options', 
            $this->page_name,
            [&$this, 'render_option_page']
        );
    }

    function render_option_page()
    {
        $this->save_option_page();
        echo 
        '<div class="wrap">
        <h1>'.$this->option_page["page_title"].'</h1>
        <form method="post" >';
        settings_fields( $this->page_name ); // settings group name
        do_settings_sections( $this->page_name ); // just a page slug
        
        if ($this->is_single_option) {
            foreach ($this->option_page_fields as $field) {
                $option_field_id = $this->option_page['id'] . $field['suffix_id'];
                $options[$option_field_id]=get_option($option_field_id,"");
            }    
        }
        else {    
            $options = get_option( $this->option_page['id'],"");
        }
        foreach ($this->option_page_fields as $field) {
            $option_field_id = $this->option_page['id'] . $field['suffix_id'];
            $value = "";
            if (!empty($options)) {
                $value = $options[$option_field_id] ?? "";
            }
            $args=$field["args"]??[];
            $field_type = new $field['type'](
                $field['label'],
                $value,
                $option_field_id,
                $args
            );
            $field_type->render_metabox_html();
        }
        
        submit_button();
        echo '</form></div>';
    }
    function save_option_page()
    {
        if ($_POST['option_page'] == $this->page_name ) {
            
            $option_fields = array();     
            foreach ($this->option_page_fields as $field) {
                $option_field_id = $this->option_page['id'] . $field['suffix_id'];
                $option_val = "";
                if (array_key_exists($option_field_id, $_POST)) {
                    if ($field["sanitize"]) {
                        $option_val = stripslashes(sanitize_text_field($_POST[$option_field_id]));
                    } else {
                        $option_val = stripslashes($_POST[$option_field_id]);
                    }
                    if ($field["type"] == "WPEditor_MetaBox_Type") {
                        $allowed_tags = wp_kses_allowed_html( 'post' );
                        $allowed_tags['iframe'] = array(
                            'src'             => true,
                            'width'           => true,
                            'height'          => true,
                            'frameborder'     => true,
                            'allowfullscreen' => true,
                        );
                        $option_val = wp_kses($option_val,$allowed_tags);
                    }
                } else{
                    if ($field["type"] == Checkbox_MetaBox_Type::class) {
                        $option_val='false';
                    }
                }
                if($this->is_single_option){
                    update_option(
                        $option_field_id,
                        $option_val
                    );
                }
                else{
                    $option_fields[$option_field_id] = $option_val;
                }
            }
            if(!$this->is_single_option){
                update_option(
                    $this->option_page['id'],
                    $option_fields
                );
            }
        }
    }
}
