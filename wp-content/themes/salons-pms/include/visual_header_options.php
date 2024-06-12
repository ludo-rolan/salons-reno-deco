<?php
// add admin options

if (is_admin()):
    
    class Visual_header_Options {
        
        function __construct() {
            add_action('admin_menu', array(&$this, 'add_options_admin'));
        }
        
        function add_options_admin() {
            add_options_page('Options Header Banner', __('Options Header Banner',REWORLDMEDIA_TERMS), 'manage_options', 'visual_header_admin', array(&$this, 'visual_header_admin'));
        }
        
        function visual_header_admin() {
    		fix_slashes_posts_once();
                       
            if( isset($_POST['visual_header_post_id']) && is_numeric($_POST['visual_header_post_id']) ) {
                update_option('visual_header_post_id',  $_POST['visual_header_post_id'], false);
            }
            $visual_header_post_id = esc_attr( get_option('visual_header_post_id' , ''));

            if( isset($_POST['title_visual_header_fr'])) {
                update_option('title_visual_header_fr',  $_POST['title_visual_header_fr'], false);
            }
            $title_visual_header_fr = esc_attr( get_option('title_visual_header_fr' , ''));
            
			if( isset($_POST['title_visual_header_en'])) {
                update_option('title_visual_header_en',  $_POST['title_visual_header_en'], false);
            }
            $title_visual_header_en = esc_attr( get_option('title_visual_header_en' , ''));
            
			if( isset($_POST['subtitle_visual_header_fr'])) {
                update_option('subtitle_visual_header_fr',  $_POST['subtitle_visual_header_fr'], false);
            }
            $subtitle_visual_header_fr = esc_attr( get_option('subtitle_visual_header_fr' , ''));
            
			if( isset($_POST['subtitle_visual_header_en'])) {
                update_option('subtitle_visual_header_en',  $_POST['subtitle_visual_header_en'], false);
            }
            $subtitle_visual_header_en = esc_attr( get_option('subtitle_visual_header_en' , ''));
            
			if( isset($_POST['bg_visual_header_fr'])) {
                update_option('bg_visual_header_fr',  $_POST['bg_visual_header_fr'], false);
            }
            $bg_visual_header_fr = esc_attr( get_option('bg_visual_header_fr' , ''));
            
			if( isset($_POST['url_visual_header_fr'])) {
                update_option('url_visual_header_fr',  $_POST['url_visual_header_fr'], false);
            }
            $url_visual_header_fr = esc_attr( get_option('url_visual_header_fr' , ''));
            

			?>

			<h1><?php _e('Options du visual_header', REWORLDMEDIA_TERMS)  ; ?></h1>
			<form id="addtag" method="post" class="validate">
				<table class="form-table" >
					<tr valign="top">
						<th scope="row" >
							<label for="visual_header_post_id"><?php _e('ID du Post', REWORLDMEDIA_TERMS); ?></label>
						</th>
						<td>
							<input type="text" name="visual_header_post_id" id="visual_header_post_id"  value="<?php echo $visual_header_post_id; ?>" class="regular-text"/>
							<p class="description">
								<?php _e('Identifiant de post pour le banner HP et Actualité, si vous voulais mettre du contenu statique. mettez -1, et 0 pour mode auto ', REWORLDMEDIA_TERMS); ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" >
							<label for="title_visual_header_fr"><?php _e('Titre Français', REWORLDMEDIA_TERMS); ?></label>
						</th>
						<td>
							<input type="text" name="title_visual_header_fr" id="title_visual_header_fr"  value="<?php echo $title_visual_header_fr; ?>" class="regular-text"/>
							<p class="description">
								<?php _e('Titre Visual Header FR, n\'oubliez pas de mêttre -1 en ID Post pour que ces champs au-dessous s\'active', REWORLDMEDIA_TERMS); ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" >
							<label for="title_visual_header_en"><?php _e('Titre Anglais', REWORLDMEDIA_TERMS); ?></label>
						</th>
						<td>
							<input type="text" name="title_visual_header_en" id="title_visual_header_en"  value="<?php echo $title_visual_header_en; ?>" class="regular-text"/>
							<p class="description">
								<?php _e('Titre Visual Header En', REWORLDMEDIA_TERMS); ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" >
							<label for="subtitle_visual_header_fr"><?php _e('Sous-Titre Français', REWORLDMEDIA_TERMS); ?></label>
						</th>
						<td>
							<input type="text" name="subtitle_visual_header_fr" id="subtitle_visual_header_fr"  value="<?php echo $subtitle_visual_header_fr; ?>" class="regular-text"/>
							<p class="description">
								<?php _e('Sous-Titre Visual Header FR', REWORLDMEDIA_TERMS); ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" >
							<label for="subtitle_visual_header_en"><?php _e('Sous-Titre Anglais', REWORLDMEDIA_TERMS); ?></label>
						</th>
						<td>
							<input type="text" name="subtitle_visual_header_en" id="subtitle_visual_header_en"  value="<?php echo $subtitle_visual_header_en; ?>" class="regular-text"/>
							<p class="description">
								<?php _e('Sous-Titre Visual Header En', REWORLDMEDIA_TERMS); ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" >
							<label for="url_visual_header_fr"><?php _e('découvrir (URL)', REWORLDMEDIA_TERMS); ?></label>
						</th>
						<td>
							<input type="text" name="url_visual_header_fr" id="url_visual_header_fr"  value="<?php echo $url_visual_header_fr; ?>" class="regular-text"/>
							<p class="description">
								<?php _e('Url pour le boutton découvrir', REWORLDMEDIA_TERMS); ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" >
							<label for="bg_visual_header_fr"><?php _e('Image Banner (URL)', REWORLDMEDIA_TERMS); ?></label>
						</th>
						<td>
							<input type="text" name="bg_visual_header_fr" id="bg_visual_header_fr"  value="<?php echo $bg_visual_header_fr; ?>" class="regular-text"/>
							<p class="description">
								<?php _e('Url d\'image bg de Banner', REWORLDMEDIA_TERMS); ?>
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
    new Visual_header_Options();
endif;

