<?php

class Ninja_Forms_V3_Exposant {

	private static $_instance;
	private $form_ids = [];
    private $script_added = false;
    private $id_form_exposant = 0;

	public static function get_instance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {
		$this->id_form_exposant = get_option('id_formulaire_exposant', 0);
		add_filter( 
			'ninja_forms_run_action_settings', 
			[$this, 'edit_to_email'], 
			999,
			4
		);
		add_action(
			'ninja_forms_before_form_display',
			[$this, 'add_post_id_exposant_to_extra'],
			999,
			1
		);
	}

	public function edit_to_email($action_settings, $form_id, $action_id, $form_settings) {
		if($action_settings['type'] == 'email') {
			if($form_id == $this->id_form_exposant) {
				$form_data = json_decode(stripslashes($_POST['formData']), true);
				if(!empty($form_data['extra']) && !empty($form_data['extra']['post_id_exposant'])) {
					$post_id_exposant = (int)$form_data['extra']['post_id_exposant'];
					$exposant_email = get_post_meta($post_id_exposant, 'email_exposant', true);
					if($exposant_email) {
						$action_settings['to'] = $exposant_email;
					}
				}
			}
		}
		return $action_settings;
	}

	public function add_post_id_exposant_to_extra($form_id) {
		$id_form_exposant = get_option('id_formulaire_exposant', 0);
		if($form_id == $this->id_form_exposant) {
			$this->form_ids[] = $this->id_form_exposant;
		    //Make sure we only add the script once
		    if(!$this->script_added) {
		        add_action('wp_footer', [$this, 'add_extra_to_exposant_form_script'], 999);
		        $this->script_added = true;
		   }
		}
	}

	public function add_extra_to_exposant_form_script() {
		?>
		<script>
	        (function() {
	          var form_ids = [<?php echo join(", ", $this->form_ids); ?>];
	          nfRadio.channel("forms").on("before:submit", function(e) {
	            //Make sure the form being submitted is one we want to modify
	            if(form_ids.indexOf(+e.id) === -1)return;
	            
	            //Get any existing extra data
	            var extra = e.get('extra');

	            //Merge in new extra data
	            //EG the post ID
	            extra.post_id_exposant = <?php the_ID(); ?>;

	            //Set the extra data
	            e.set('extra', extra);
	          });
	        })();
	      </script>
		<?php
	}

}

Ninja_Forms_V3_Exposant::get_instance();