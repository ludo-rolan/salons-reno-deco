<?php
// add admin options

if (is_admin()):
    
    class Devs_Options {

        function __construct() {
            // activate admin page
            add_action('admin_menu', array(&$this, 'add_options_admin'));
        }
        
        function add_options_admin() {
            add_options_page('Options Reworld', __('Options mises en prod'), 'manage_options' , 'devs_admin', array(&$this, 'devs_admin'));
        }
        
        function devs_admin() {
			global $devs, $option_devs ;

            $chars=array('\\','\"',"\'");
            
			$old_option_devs =  get_option(apply_filters('name_option','option_devs'), array());
			$option_devs = array() ;
			if(is_array($devs) && count($devs)){
				if(isset($_POST)){
					foreach ($devs as $key => $value) {
						if(isset($old_option_devs[$key])){
							$option_devs[$key] = $old_option_devs[$key] ;
						}
						if (isset($_POST['devs_' . $key])) {
							$new_value = $_POST['devs_' . $key] ;
			                if ($new_value != is_dev($key)){
				                $option_devs[$key]['date'] = time() ;
								$current_user = wp_get_current_user();
								$option_devs[$key]['user'] =  $current_user->user_login ;
								$option_devs[$key]['user_id'] =  $current_user->ID ;
							}
			                $option_devs[$key]['active'] = $new_value ;
			            }
					}
					$option  = apply_filters('name_option','option_devs');
					update_option($option, $option_devs, false);
					do_action('save_rw_option' , $option );

				}
			}
            //$reworld_async_ads = get_option('reworld_async_ads' , 1 );

            
			?>
	
			<div>

			<h2><?php _e('Options mises en prod')  ; ?></h2>
			<form id="addtag" method="post" class="validate">


				<p class="submit">					
					<input id="submit" class="button button-primary" type="submit" value="<?php _e('Enregistrer les modifications')  ; ?>" name="submit" />
				</p>


				<table class="form-table" >
				<?php
				foreach ($devs as $key => $dev) {
					$val =  is_dev($key) ;
					$name_option  = apply_filters('name_option','devs_' . $key);
					
					if(is_array($dev)){
						$desc = $dev['desc'] ;
					}else{
						$desc = $dev ;
					}

				?>

					<tr valign="top">
						<th scope="row" ><label for="<?php echo $name_option ; ?>"><?php echo $desc  ; ?></label>
						</th>
						<td ><select name="<?php echo'devs_' . $key; ?>"
							id="<?php echo $name_option ; ?>">
								<option value="1" <?php if ($val == 1) { echo 'selected'; } ?>><?php _e('Oui')  ; ?></option>
								<option value="0" <?php if ($val == 0) { echo 'selected'; } ?>><?php _e('Non')  ; ?></option>
						</select>
						<?php
							$date = isset($option_devs[$key]['date']) ? (__('Le ') . date_i18n('l j F Y \a H:i:s ', $option_devs[$key]['date'])): null ;
							$user = isset($option_devs[$key]['user']) ? $option_devs[$key]['user'] : null ;
						?>
						 <em>	
	                        <?php if($date){
								echo '<br> ('. $date  . __(' par ')  . $user .  ')';
							}?>
	                    </em>

						</td>
					</tr>
				<?php
				}
				?>

				</table>




				<p class="submit">					
					<input id="submit" class="button button-primary" type="submit" value="<?php _e('Enregistrer les modifications')  ; ?>" name="submit" />
				</p>
			</form>
		<?php
        }
    }
    new Devs_Options();
endif;

