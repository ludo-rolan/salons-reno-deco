<?php
// add admin options

if (is_admin()):
    
    class Salon_Options {
        
        function __construct() {
            add_action('admin_menu', array(&$this, 'add_options_admin'));
        }
        
        function add_options_admin() {
            add_options_page('Options Salon', __('Options Salon',REWORLDMEDIA_TERMS), 'manage_options', 'salon_admin', array(&$this, 'salon_admin'));
        }
        
        function salon_admin() {
    		fix_slashes_posts_once();
                       
            if( isset($_POST['pdf_plan_salon']) ) {
                update_option('pdf_plan_salon',  $_POST['pdf_plan_salon'], false);
            }
            $pdf_plan_salon = esc_attr( get_option('pdf_plan_salon' , ''));

            if( isset($_POST['id_formulaire_exposant']) && is_numeric($_POST['id_formulaire_exposant']) ) {
                update_option('id_formulaire_exposant',  $_POST['id_formulaire_exposant'], false);
            }
            $id_formulaire_exposant = esc_attr( get_option('id_formulaire_exposant' , ''));
            
			if( isset($_POST['text_page_exposant']) ) {
                update_option('text_page_exposant',  $_POST['text_page_exposant'], false);
            }
            $text_page_exposant = get_option('text_page_exposant' , '');
			if( isset($_POST['text_page_partenaires']) ) {
                update_option('text_page_partenaires',  $_POST['text_page_partenaires'], false);
            }
            $text_page_partenaires = get_option('text_page_partenaires' , '');
            
			?>

			<h1><?php _e('Options du salon', REWORLDMEDIA_TERMS)  ; ?></h1>
			<form id="addtag" method="post" class="validate">
				<table class="form-table" >
					<tr valign="top">
						<th scope="row" >
							<label for="text_page_exposant"><?php _e('Intro Page Listing Exposant', REWORLDMEDIA_TERMS); ?></label>
						</th>
						<td>
							<textarea type="text" name="text_page_exposant" id="text_page_exposant"   class="regular-text" rows="14"><?php echo $text_page_exposant; ?></textarea>
							<p class="description">
								<?php _e('Text intro de page listing Exposant', REWORLDMEDIA_TERMS); ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" >
							<label for="text_page_partenaires"><?php _e('Intro Page Listing Partenaires', REWORLDMEDIA_TERMS); ?></label>
						</th>
						<td>
							<textarea type="text" name="text_page_partenaires" id="text_page_partenaires"   class="regular-text" rows="14"><?php echo $text_page_partenaires; ?></textarea>
							<p class="description">
								<?php _e('Text intro de page listing Des Partenaires', REWORLDMEDIA_TERMS); ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" >
							<label for="pdf_plan_salon"><?php _e('PDF plan du salon (URL)', REWORLDMEDIA_TERMS); ?></label>
						</th>
						<td>
							<input type="text" name="pdf_plan_salon" id="pdf_plan_salon"  value="<?php echo $pdf_plan_salon; ?>" class="regular-text"/>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" >
							<label for="id_formulaire_exposant"><?php _e('ID formulaire exposants', REWORLDMEDIA_TERMS); ?></label>
						</th>
						<td>
							<input type="text" name="id_formulaire_exposant" id="id_formulaire_exposant"  value="<?php echo $id_formulaire_exposant; ?>" class="regular-text"/>
							<p class="description">
								<?php _e('Identifiant ninja form du formulaire contact des exposants', REWORLDMEDIA_TERMS); ?>
							</p>
						</td>
					</tr>
				</table>		
				<p class="submit">					
					<input id="submit" class="button button-primary" type="submit" value="<?php _e('Enregistrer les modifications',REWORLDMEDIA_TERMS )  ; ?>" name="submit" />
				</p>
			</form>

			<?php
        }
    }
    new Salon_Options();
endif;

