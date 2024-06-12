<?php
// add admin options

if (is_admin()):
    
    class Partners_options {
        
        function __construct() {
            // activate admin page
            add_action('admin_menu', array(&$this, 'add_options_admin'));
        }
        
        function add_options_admin() {
            add_options_page('Options Reworld', __('Options des partenaires'),  'manage_options', 'partners_admin', array(&$this, 'init'));
        }
        
        function init() {
			global $partners, $partners_default ;

			$minisite = isset($_GET['minisite']) ? $_GET['minisite'] : null ;
			$dedicated_area = get_param_global('dedicated_area');
			$name_option   = 'partners_activation' ;
			if($minisite){
				$name_option   = 'partners_activation_' . $minisite ;
				$site_partners = $partners  ;
				$site_partners = apply_filters('init_partners', $site_partners) ;	
				$partners = isset($dedicated_area[$minisite]['partners']) ?  $dedicated_area[$minisite]['partners'] : array();
				$site_partners_activation = get_option(apply_filters('name_option', 'partners_activation'), array());
			}
			$partners = apply_filters('init_partners', $partners) ;	

			$name_option  = apply_filters('name_option', $name_option);
			$partners_activation = get_option($name_option, array());
            $update = false ;
			if(is_array($partners) && count($partners)){
				foreach ($partners as $key => $partner) {
					$active = isset($partners_activation[$key]) ? $partners_activation[$key]['active'] :  $partner['default_activation'] ;
					if (isset($_POST[$key]) &&  $_POST[$key] != $active ) {
						$update = true ;
						$partners_activation[$key]['active'] = $_POST[$key] ;
						$partners_activation[$key]['date'] = time() ;
						$current_user = wp_get_current_user();
						$partners_activation[$key]['user'] =  $current_user->user_login ;
						$partners_activation[$key]['user_id'] =  $current_user->ID ;
		            }
		            if($update){
		            	update_option($name_option, $partners_activation);
		            }
				}
			}


            
			?>
	
			<div class="wrap">

			<h2><?php _e('Options d\'activation / désactivation des scripts partenaire ')  ; ?></h2>
			
			
			<form id="addtag" method="post" class="validate">

			<div class="tablenav top">
					<div class="alignleft actions bulkactions">
						<label for="locking_page" class="screen-reader-text">Page</label>
						<select name="select_site" id="select_site"  style="display: inline-block;">
							<option value="" <?php  echo (!$minisite)? 'selected="selected"': '' ; ?> >Site général</option>
							<?php
							if ( is_array($dedicated_area)){
								foreach ($dedicated_area as $key_minisite => $config) {
									$selected = ($key_minisite == $minisite) ? 'selected="selected"' : '' ;
									echo "<option value='$key_minisite' $selected >{$config['title']}</option>" ;
								}
							}

							 ?>

							</select>
							<script type="text/javascript">
								jQuery(function($){
									$("#select_site").on('change',function(){
										var val = $(this).val();
										if(val){
											url = '/wp-admin/options-general.php?page=partners_admin&minisite=' + val ;
										}else{
											url = '/wp-admin/options-general.php?page=partners_admin' ;
										}
										window.location = url ;
									});
								})(jQuery);

							</script>

					</div>
				</div>

				<p class="submit">					
					<input id="submit" class="button button-primary" type="submit" value="<?php _e('Enregistrer les modifications' )  ; ?>" name="submit" />
				</p>

				<div class="wp-list-table widefat fixed striped elements" style="border-color:#CFCFCF;">

			    <h2>Partenaires <?php echo (isset($minisite)) ? ('( ' . $dedicated_area[$minisite]['title'].' )') : 'général' ; ?></h2>
			    <div>
			        <div>

				<?php
				foreach ($partners as $key => $partner) {
					//if(!$partner['comportement_inverse']){
					
						$val = isset($partners_activation[$key]) ? $partners_activation[$key]['active'] :  $partner['default_activation'] ;
						$select_name  =  $key;
						
						$desc = $partner['desc'] ;
						$date = isset($partners_activation[$key]['date']) ? (__('Le ') . date_i18n('l j F Y \a H:i:s ', $partners_activation[$key]['date'])): null ;
						$user = isset($partners_activation[$key]['user']) ? $partners_activation[$key]['user'] : null ;
						$implementation = isset($partner['implementation']) ? $partner['implementation']  : null ;
						$code = isset($partner['code']) ? $partner['code']  : null ;
						$settings_page = isset($partner['setting_bouton']) ? $partner['setting_bouton']  : null ;
						/*if($implementation && ! $code){
							$code = file_get_contents( locate_template('include/functions/implementation/'.$implementation) ) ;
						}*/

				?>
			  			<div class="search-rw inside search-rw-popular_gallery" >
			                <div class="link-search-wrapper">
		                        <span class="search-label">  <?php echo $desc  ; ?> </span>
								<select name="<?php echo $select_name; ?>" id="<?php echo $select_name ; ?>">
									<option value="1" <?php if ($val == 1) { echo 'selected'; } ?>><?php _e('Oui')  ; ?></option>
									<option value="0" <?php if ($val == 0) { echo 'selected'; } ?>><?php _e('Non')  ; ?></option>
								</select>
								<?php if ( $val == 1 && !empty($settings_page) ) { ?>
									<a href="<?php echo $settings_page; ?>" >Targetting</a>
								<?php } ?>
			                </div>

			                <span class="spinner search-rw-load" style="float: none; visibility: visible; display: none;"></span>
			                <div id="search-results-popular_gallery_1" class="query-results" tabindex="0">
			                    <div class="query-nothing" id="no-results-popular_gallery_1">
			                        <em>	
			                        <?php if($date){
										echo '<br> ('. $date  . __(' par ')  . $user .  ')';
									}?>
			                        </em>
			                    </div>
			                    <ul></ul>
			                    <div class="query-results-loading">
			                        <span class="spinner" style="float:none;"></span>
			                    </div>
			                </div>
			            </div>  

				<?php
					//}
				}
				?>

			            <div class="clear"></div>

			        </div>
			        
			    </div>
			</div>

			<?php 
				if($minisite){
					?>
				<br>

				<div class="wp-list-table widefat fixed striped elements" style="border-color:#CFCFCF;">

			    <h2>Partenaires <?php  echo  'Général' ; ?></h2>
			    <div>
			        <div>

				<?php

				if(is_array($site_partners) && count($site_partners)){
				foreach ($site_partners as $key => $partner) {

						$active = isset($partners_activation[$key]) ? $partners_activation[$key]['active'] :  1 ;
						if (isset($_POST[$key]) &&  $_POST[$key] != $active ) {
							$update = true ;
							$partners_activation[$key]['active'] = $_POST[$key] ;
							$partners_activation[$key]['date'] = time() ;
							$current_user = wp_get_current_user();
							$partners_activation[$key]['user'] =  $current_user->user_login ;
							$partners_activation[$key]['user_id'] =  $current_user->ID ;
			            }
			            if($update){
			            	update_option($name_option, $partners_activation);
			            }
					}
				}



				foreach ($site_partners as $key => $partner) {
					$active_in_site = isset($site_partners_activation[$key]) ? $site_partners_activation[$key]['active'] :  $partner['default_activation'] ;
					if(!array_key_exists($key, $partners) && $active_in_site){

						$val = isset($partners_activation[$key]) ? $partners_activation[$key]['active'] :  1 ;
						$name_option  =  $key;
						$desc = $partner['desc'] ;
						$date = isset($partners_activation[$key]['date']) ? (__('Le ') . date_i18n('l j F Y \a H:i:s ', $partners_activation[$key]['date'])): null ;
						$user = isset($partners_activation[$key]['user']) ? $partners_activation[$key]['user'] : null ;
						$implementation = isset($partner['implementation']) ? $partner['implementation']  : null ;
						$code = isset($partner['code']) ? $partner['code']  : null ;
						/*if($implementation && ! $code){
							$code = file_get_contents( locate_template('include/functions/implementation/'.$implementation) ) ;
						}*/

				?>
		  			<div class="search-rw inside search-rw-popular_gallery" >
		                <div class="link-search-wrapper">
	                        <span class="search-label">  <?php echo $desc  ; ?> </span>
							<select name="<?php echo $name_option; ?>" id="<?php echo $name_option ; ?>">
								<option value="1" <?php if ($val == 1) { echo 'selected'; } ?>><?php _e('Oui')  ; ?></option>
								<option value="0" <?php if ($val == 0) { echo 'selected'; } ?>><?php _e('Non')  ; ?></option>
							</select>
		                </div>

		                <span class="spinner search-rw-load" style="float: none; visibility: visible; display: none;"></span>
		                <div id="search-results-popular_gallery_1" class="query-results" tabindex="0">
		                    <div class="query-nothing" id="no-results-popular_gallery_1">
		                        <em>	
		                        <?php if($date){
									echo '<br> ('. $date  . __(' par ')  . $user .  ')';
								}?>
		                        </em>
		                    </div>
		                    <ul></ul>
		                    <div class="query-results-loading">
		                        <span class="spinner" style="float:none;"></span>
		                    </div>
		                </div>
		            </div>  

				<?php
					}
				}
				?>

			            <div class="clear"></div>

			        </div>
			        
			    </div>
			</div>






					<?php

				}

			?>




				<p class="submit">					
					<input id="submit" class="button button-primary" type="submit" value="<?php _e('Enregistrer les modifications')  ; ?>" name="submit" />
				</p>



			</form>
			</div>
<style type='text/css'>
	.tablenav{
		margin-bottom:15px;
	}
	.validate .striped{
		border:1px solid #CFCFCF;
		background:#FFF;
		-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.07);
		box-shadow: 0 1px 2px rgba(0,0,0,.07);
	}
	.validate .striped h2{
		margin:.83em;
	}
	.validate .striped > div{
		border-top:1px solid #CFCFCF;
		padding: 0 10px;
		/*margin:0 -12px;*/
	}
	.validate .striped > div:nth-child(even){
		background-color:#f9f9f9;
	}
	.search-rw{
	    display:inline-block;
	    width: 49%;
		max-width: 49%;
		margin: 10px 0% 10px 0.5%;
	    padding:10px 0;
	    vertical-align: top;
		background-color:#fff;
	}
	.search-rw > div{
		margin:0 10px;
	}
	.search-rw .list_selected_articles li{
		cursor: default;
	}
	.search-rw li.loading:before{
	  content: 'loading...';
	  display: block;
	  position: absolute;
	  background: rgba(205,205,205,0.8);
	  width: 100%;
	  color: #000;
	  line-height: 40px;
	  height: 100%;
	  left: 0;
	  top: 0;
	  text-align: center;
	}
	.search-rw .success .input-daterange input[type=text]{
		border:1px solid transparent; font-weight:bold; box-shadow:none; width:130px; background: transparent;cursor:pointer;
	}
	.search-rw .error .input-daterange input[type=text]{
		border:1px solid #d9534f; background: #f2dede;
	}
</style>




		<?php
        }
    }
    new Partners_options();
endif;

