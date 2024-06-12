<?php
class AdsTxt {
	
    // Make sure to add this rewrite rule to your .nginx.conf file
	
    /**
     location = /ads.txt {
	allow all;
	log_not_found off;
	access_log off;
	rewrite ^ /index.php?adstxt=1;
     } 
     */   

    public function __construct() {
        
        $this->option_name = 'adstxt__'.str_replace('.','_',apply_filters('name_option', 'adstxt_option') );

        if(is_admin()){
            /* Admin init */
            add_action( 'admin_init', array($this, 'adstxt_init') );
        }
    }
    
    /* Settings Init */
    public function adstxt_init(){
    
        /* Register Settings */
        register_setting(
            'reading',             // Options group
            $this->option_name,      // Option name/database
            false
        );
    
        /* Create settings section */
        add_settings_section(
            'section-adstxt',                   // Section ID
            'Authorized Digital Sellers',  // Section title
            false, // Section callback function
            'reading'                          // Settings page slug
        );
    
        /* Create settings field */
        add_settings_field(
            'adstxt',       // Field ID
            'Contenu du fichier ads.txt',       // Field title 
            array( $this, 'adstxt_field_callback' ), // Field callback function
            'reading',                    // Settings page slug
            'section-adstxt'               // Section ID
        );
    }
    
    /* Settings Field Callback */
    public function adstxt_field_callback(){
        $value = get_option($this->option_name, '');
        ?>
        <label for="adstxt">
            <textarea id="adstxt" style="width: 100%" rows="10" name="<?php echo $this->option_name; ?>"><?php echo $value; ?></textarea>
        </label>
        <?php
    }

    public function render_output() {
        $output = get_option($this->option_name, '');
        header('HTTP/1.1 200 OK');
        header('Content-Type: text/plain; charset=utf-8');
        echo $output;
        exit();
    }
}
