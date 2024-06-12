<?php

//ADMINISTRATION DES tags PUB SUR DES URLS SPECIFIQUES 

class Optiontags {

	function __construct() {
            // AJOUTER tags to admin menu
		add_action('admin_menu', array(&$this, 'add_tags_option_admin'));
	}

	function add_tags_option_admin() {
		add_options_page(__('Gestion de l\'affichage des pages'), __('Gestion de l\'affichage des pages'), 'manage_options', 'tags_admin_option', array(&$this, 'tags_admin'));
	}

	function tags_admin(){
		global $tags_options; 
		$tags_options = get_option('tags_option' , array() );

		if(isset($_REQUEST['edite'])){
			$this->get_tags_admin();
		}else{
			if(isset($_REQUEST['delete'])&& isset($_REQUEST['index'])){
				unset($tags_options[$_REQUEST['index']]);
				update_option('tags_option' , $tags_options );
				do_action('save_rw_option' , 'tags_option' );
			}

			?>
			<div class="wrap">
				<h2> <?php _e('Gestion de l\'affichage des pages'); ?> <a class="add-new-h2" href="<?php echo home_url(); ?>/wp-admin/options-general.php?page=tags_admin_option&edite">Ajouter</a></h2>
				<br>
				<table class="wp-list-table widefat fixed posts">
					<thead>
						<tr>
							<th><?php _e('Nom') ; ?></th>
							<th><?php _e('Editer') ; ?></th>
							<th><?php _e('Actif') ; ?></th>
							<th><?php _e('Supprimer') ; ?></th>
						</tr>
					</thead>
					<?php
					foreach ($tags_options as $key => $tags) {
						?>
						<tr>
							<td><?php echo $tags['name'] ; ?></td>
							<td><a href='<?php echo home_url(); ?>/wp-admin/options-general.php?page=tags_admin_option&edite&index=<?php echo $key ?>'><?php _e('Editer') ; ?></a></td>
							<td><?php echo ($tags['active'])? __('Oui'):__('Non')  ; ?></td>
							<td><a href='<?php echo home_url(); ?>/wp-admin/options-general.php?page=tags_admin_option&delete&index=<?php echo $key ?>'><?php _e('Supprimer') ; ?></a></td>

						</tr>
						<?php
					}
					?>
				</table>
			</div>
			<?php

		}
	}


	function get_tags_admin() {
		global $partners;

		global $tags_options,$tags, $instance_partners;

		$dfp_formats = array();
		if( isset($instance_partners['dfp_v2']) ){
			$dfp_v2 = $instance_partners['dfp_v2'];
			$dfp_formats = $dfp_v2->get_formats_dfp();
		}

		$list = array_keys($partners);
		if(isset($_REQUEST['index'])){
			$index = $_REQUEST['index'] ;
			$tags = $tags_options[$index];
		}else{
			$index = false ;
			$tags = array();
		}

		$chars=array('\\','\"',"\'");

		if (isset($_POST['tags_active'])) {
			$tags['active'] = str_replace($chars, '', $_POST['tags_active']);
		}

		if (isset($_POST['submit'])) {
			$tags_partners = isset($_POST['tags_partners']) ? $_POST['tags_partners'] : array();
			$tags['partners'] = str_replace($chars, '', $_POST['tags_partners']);

			$tags['dfp_tags'] = isset($_POST['tags_dfp']) ? $_POST['tags_dfp'] : array();
		}

		if (isset($_POST['tags_urls'])) {
			$tags['urls'] = array_filter(str_replace($chars, '', $_POST['tags_urls']));
		}
		if (isset($_POST['tags_name'])) {
			$tags['name'] = str_replace($chars, '', $_POST['tags_name']);
		}

		$tags['active_all_site'] = 0;
		if (isset($_POST['active_all_site']) && $_POST['active_all_site']) {
			$tags['active_all_site'] = str_replace($chars, '', $_POST['active_all_site']);
		}
		if (isset($_POST['submit'])) {
			// OPTIONS DESKTOP
			$tags['disable_desktop_comms'] = 0;
			if (isset($_POST['disable_desktop_comms']) && $_POST['disable_desktop_comms']) {
				$tags['disable_desktop_comms'] = str_replace($chars, '', $_POST['disable_desktop_comms']);
			}

			$tags['disable_desktop_breadcrumb'] = 0;
			if (isset($_POST['disable_desktop_breadcrumb']) && $_POST['disable_desktop_breadcrumb']) {
				$tags['disable_desktop_breadcrumb'] = str_replace($chars, '', $_POST['disable_desktop_breadcrumb']);
			}

			$tags['disable_desktop_footer'] = 0;
			if (isset($_POST['disable_desktop_footer']) && $_POST['disable_desktop_footer']) {
				$tags['disable_desktop_footer'] = str_replace($chars, '', $_POST['disable_desktop_footer']);
			}
			
			$tags['disable_desktop_footercrm'] = 0;
			if (isset($_POST['disable_desktop_footercrm']) && $_POST['disable_desktop_footercrm']) {
				$tags['disable_desktop_footercrm'] = str_replace($chars, '', $_POST['disable_desktop_footercrm']);
			}

			$tags['disable_desktop_playerbas'] = 0;
			if (isset($_POST['disable_desktop_playerbas']) && $_POST['disable_desktop_playerbas']) {
				$tags['disable_desktop_playerbas'] = str_replace($chars, '', $_POST['disable_desktop_playerbas']);
			}

			$tags['disable_desktop_blocauthor'] = 0;
			if (isset($_POST['disable_desktop_blocauthor']) && $_POST['disable_desktop_blocauthor']) {
				$tags['disable_desktop_blocauthor'] = str_replace($chars, '', $_POST['disable_desktop_blocauthor']);
			}

			$tags['disable_desktop_dateauthor'] = 0;
			if (isset($_POST['disable_desktop_dateauthor']) && $_POST['disable_desktop_dateauthor']) {
				$tags['disable_desktop_dateauthor'] = str_replace($chars, '', $_POST['disable_desktop_dateauthor']);
			}

			$tags['disable_desktop_sharebtn'] = 0;
			if (isset($_POST['disable_desktop_sharebtn']) && $_POST['disable_desktop_sharebtn']) {
				$tags['disable_desktop_sharebtn'] = str_replace($chars, '', $_POST['disable_desktop_sharebtn']);
			}
			$tags['disable_refresh_desktop'] = 0;
			if (isset($_POST['disable_refresh_desktop']) && $_POST['disable_refresh_desktop']) {
				$tags['disable_refresh_desktop'] = str_replace($chars, '', $_POST['disable_refresh_desktop']);
			}
			$tags['disable_mpr_iframe_desktop'] = 0;
			if (isset($_POST['disable_mpr_iframe_desktop']) && $_POST['disable_mpr_iframe_desktop']) {
				$tags['disable_mpr_iframe_desktop'] = str_replace($chars, '', $_POST['disable_mpr_iframe_desktop']);
			}

			// OPTION MOBILE
			$tags['disable_mobile_comms'] = 0;
			if (isset($_POST['disable_mobile_comms']) && $_POST['disable_mobile_comms']) {
				$tags['disable_mobile_comms'] = str_replace($chars, '', $_POST['disable_mobile_comms']);
			}

			$tags['disable_mobile_breadcrumb'] = 0;
			if (isset($_POST['disable_mobile_breadcrumb']) && $_POST['disable_mobile_breadcrumb']) {
				$tags['disable_mobile_breadcrumb'] = str_replace($chars, '', $_POST['disable_mobile_breadcrumb']);
			}

			$tags['disable_mobile_footer'] = 0;
			if (isset($_POST['disable_mobile_footer']) && $_POST['disable_mobile_footer']) {
				$tags['disable_mobile_footer'] = str_replace($chars, '', $_POST['disable_mobile_footer']);
			}
			
			$tags['disable_mobile_footercrm'] = 0;
			if (isset($_POST['disable_mobile_footercrm']) && $_POST['disable_mobile_footercrm']) {
				$tags['disable_mobile_footercrm'] = str_replace($chars, '', $_POST['disable_mobile_footercrm']);
			}

			$tags['disable_mobile_playerbas'] = 0;
			if (isset($_POST['disable_mobile_playerbas']) && $_POST['disable_mobile_playerbas']) {
				$tags['disable_mobile_playerbas'] = str_replace($chars, '', $_POST['disable_mobile_playerbas']);
			}

			$tags['disable_mobile_blocauthor'] = 0;
			if (isset($_POST['disable_mobile_blocauthor']) && $_POST['disable_mobile_blocauthor']) {
				$tags['disable_mobile_blocauthor'] = str_replace($chars, '', $_POST['disable_mobile_blocauthor']);
			}

			$tags['disable_mobile_dateauthor'] = 0;
			if (isset($_POST['disable_mobile_dateauthor']) && $_POST['disable_mobile_dateauthor']) {
				$tags['disable_mobile_dateauthor'] = str_replace($chars, '', $_POST['disable_mobile_dateauthor']);
			}

			$tags['disable_mobile_sharebtn'] = 0;
			if (isset($_POST['disable_mobile_sharebtn']) && $_POST['disable_mobile_sharebtn']) {
				$tags['disable_mobile_sharebtn'] = str_replace($chars, '', $_POST['disable_mobile_sharebtn']);
			}
			$tags['disable_refresh_mobile'] = 0;
			if (isset($_POST['disable_refresh_mobile']) && $_POST['disable_refresh_mobile']) {
				$tags['disable_refresh_mobile'] = str_replace($chars, '', $_POST['disable_refresh_mobile']);
			}
			$tags['disable_mpr_iframe_mobile'] = 0;
			if (isset($_POST['disable_mpr_iframe_mobile']) && $_POST['disable_mpr_iframe_mobile']) {
				$tags['disable_mpr_iframe_mobile'] = str_replace($chars, '', $_POST['disable_mpr_iframe_mobile']);
			}

		}
		$tags = apply_filters('filter_before_submit_tags_options', $tags) ;
		do_action('before_submit_tags_options');
		if (isset($_POST['submit'])) {
			if($index !== false){
				$tags_options[$index] = $tags  ;
			}else{ 
				$index = max(array_keys($tags_options))+1;       		
				$tags_options[$index] = $tags  ;
			}
			update_option('tags_option' , $tags_options );
			do_action('save_rw_option' , 'tags_option' );
			if( !empty($tags['urls']) ){
				foreach ($tags['urls'] as $url) {
					$url = str_replace(array("?exact_url", "&exact_url"), "", $url);
					RW_Utils::purge_url($url);
				}
			}
		}

		$tags_active     = $tags['active'];
		$tags_name       = $tags['name'];
		$tags_partners   = !empty($tags['partners']) ? $tags['partners'] : array();
		$tags_urls   	 = $tags['urls'];
		$dfp_tags   	 = $tags['dfp_tags'];

		if(isset($tags['active_all_site']) && $tags['active_all_site']){
			$active_all_site = true;
		}else{
			$active_all_site = false;

		}

			// Options DESKTOP
			if(isset($tags['disable_desktop_comms']) && $tags['disable_desktop_comms']){
				$disable_desktop_comms_select='checked';
			}
			if(isset($tags['disable_desktop_breadcrumb']) && $tags['disable_desktop_breadcrumb']){
				$disable_desktop_breadcrumb_select='checked';
			}
			if(isset($tags['disable_desktop_footer']) && $tags['disable_desktop_footer']){
				$disable_desktop_footer_select='checked';
			}
			if(isset($tags['disable_desktop_footercrm']) && $tags['disable_desktop_footercrm']){
				$disable_desktop_footercrm_select='checked';
			}
			if(isset($tags['disable_desktop_playerbas']) && $tags['disable_desktop_playerbas']){
				$disable_desktop_playerbas_select='checked';
			}
			if(isset($tags['disable_desktop_blocauthor']) && $tags['disable_desktop_blocauthor']){
				$disable_desktop_blocauthor_select='checked';
			}
			if(isset($tags['disable_desktop_dateauthor']) && $tags['disable_desktop_dateauthor']){
				$disable_desktop_dateauthor_select='checked';
			}
			if(isset($tags['disable_desktop_sharebtn']) && $tags['disable_desktop_sharebtn']){
				$disable_desktop_sharebtn_select='checked';
			}
			if(isset($tags['disable_refresh_desktop']) && $tags['disable_refresh_desktop']){
				$disable_refresh_desktop_select='checked';
			}
			if(isset($tags['disable_mpr_iframe_desktop']) && $tags['disable_mpr_iframe_desktop']){
				$disable_mpr_iframe_desktop_select='checked';
			}

			// Options MOBILE
			if(isset($tags['disable_mobile_comms']) && $tags['disable_mobile_comms']){
				$disable_mobile_comms_select='checked';
			}
			if(isset($tags['disable_mobile_breadcrumb']) && $tags['disable_mobile_breadcrumb']){
				$disable_mobile_breadcrumb_select='checked';
			}
			if(isset($tags['disable_mobile_footer']) && $tags['disable_mobile_footer']){
				$disable_mobile_footer_select='checked';
			}
			if(isset($tags['disable_mobile_footercrm']) && $tags['disable_mobile_footercrm']){
				$disable_mobile_footercrm_select='checked';
			}
			if(isset($tags['disable_mobile_playerbas']) && $tags['disable_mobile_playerbas']){
				$disable_mobile_playerbas_select='checked';
			}
			if(isset($tags['disable_mobile_blocauthor']) && $tags['disable_mobile_blocauthor']){
				$disable_mobile_blocauthor_select='checked';
			}
			if(isset($tags['disable_mobile_dateauthor']) && $tags['disable_mobile_dateauthor']){
				$disable_mobile_dateauthor_select='checked';
			}
			if(isset($tags['disable_mobile_sharebtn']) && $tags['disable_mobile_sharebtn']){
				$disable_mobile_sharebtn_select='checked';
			}
			if(isset($tags['disable_refresh_mobile']) && $tags['disable_refresh_mobile']){
				$disable_refresh_mobile_select='checked';
			}
			if(isset($tags['disable_mpr_iframe_mobile']) && $tags['disable_mpr_iframe_mobile']){
				$disable_mpr_iframe_mobile_select='checked';
			}
		
		?>
		<style>
			.tags-pointer{
				cursor:pointer;
			}
			.drop_tags_url{
				color:red;
			}
			.label_chk_tags {
				font-weight: 900;
			}
			.tag_name {
				margin-right: 13px;
			}
			input[type="checkbox"] {
				margin: 0;
			}
			.disable_blocks td{
				width: 30%;
				text-align: center;
				padding: 9px;
			}
			.disable_blocks th{
				text-align: left;
			}
			.disable_blocks .top_title{
				text-align: center;
			}
		</style>
		<div>

			<h2><?php _e('Réglage tags')  ; ?></h2>
			<form id="addtag" method="post" class="validate">

				<table class="form-table" >
					<tr valign="top">
						<th scope="row" ><label for="tags_active"><?php _e('Activer Retrait')  ; ?></label>
						</th>
						<td ><select name="tags_active"
							id="tags_active">
							<option value="1" <?php if ($tags_active == 1) { echo 'selected'; } ?>><?php _e('Oui')  ; ?></option>
							<option value="0" <?php if ($tags_active == 0) { echo 'selected'; } ?>><?php _e('Non')  ; ?></option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" ><label for="tags_name"><?php _e('Nom de la campagne')  ; ?></label>
					</th>
					<td >
						<input type='text' id="tags_name" name="tags_name" value="<?php echo wp_kses_data($tags_name); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" >
						<label for="tags_partners"><?php _e('Tags partenaires à retirer' )  ; ?></label>
					</th>
					<td>
						<?php
						
						$tags_to_hide_bo = get_param_global( 'tags_to_hide_bo' , array() );
						
						foreach ($list as $partner) :
							if ( ! $partners[ $partner ]['comportement_inverse'] ) {
								if ( ! in_array( $partner, $tags_to_hide_bo ) ) {
							?>
									<label class="tag_name"><input name="tags_partners[]" class="tags_partners" type="checkbox" <?php echo (in_array( $partner, $tags_partners ) ) ? 'checked' : ''; ?> value='<?php echo $partner; ?>'> <?php echo $partners[ $partner ]['desc']; ?>
									</label>
							<?php
								}
							}
						endforeach;
						?>
						<label class="label_chk_tags"><input name="chk_all_tags" id="chk_all_tags" type="checkbox" value="all_tags"> <?php _e('Tous les tags') ?></label>
					</td>
				</tr>



				<tr valign="top">
					<th scope="row" >
						<label for="tags_dfp"><?php _e('Tags DFP DESKTOP à retirer')  ; ?></label>
					</th>
					<td>
						<div class="tags_dfp_container">
							<label class="tag_name">
								<input class="disable_all" type="checkbox" value="disable_all_desktop">
								<strong>Tous</strong>
							</label>
							<?php
							foreach ($dfp_formats as $format => $val) :
								if( $val['condition'] == 'is_desktop' ){
									?>
									<label class="tag_name">
										<input name="tags_dfp[]" class="tags_dfp" type="checkbox" <?php echo (in_array($format,$dfp_tags))? 'checked' : '' ;?> value='<?php echo $format ?>'> <?php echo $format ?>
									</label>
									<?php
								}
							endforeach;
							?>
						</div>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" >
						<label for="tags_dfp"><?php _e('Tags DFP MOBILE à retirer')  ; ?></label>
					</th>
					<td>
						<div class="tags_dfp_container">
							<label class="tag_name">
								<input class="disable_all" type="checkbox" value="disable_all_mobile"> 
								<strong>Tous</strong>
							</label>
							<?php
							foreach ($dfp_formats as $format => $val) :
								if( $val['condition'] == 'is_mobile' ){
									?>
									<label class="tag_name">
										<input name="tags_dfp[]" class="tags_dfp" type="checkbox" <?php echo (in_array($format,$dfp_tags))? 'checked' : '' ;?> value='<?php echo $format ?>'> <?php echo $format ?>
									</label>
									<?php
								}
							endforeach;
							?>
						</div>
					</td>
				</tr>



				<tr valign="top" >
					<th scope="row" >
						<label for="active_all_site"><?php _e('Appliquer sur tout le site ')  ; ?></label>
						
					</th>

					<td >
							<div>
								<input id="active_all_site" name="active_all_site" class="active_all_site" type="checkbox"  value="1" <?php echo ($active_all_site == '1')? 'checked' :''; ?> />  
							</div>
												
							<script type="text/javascript">


								jQuery("#active_all_site").click(function() {
								    if (this.checked) {
								        jQuery("#tr_tags_urls").hide("slow");

										jQuery(".tags_url").each(function( index ) {
											if( jQuery( this ).val() == ""){
												jQuery( this ).val(" ");
											}
										});

								    } else {
								        jQuery("#tr_tags_urls").show("slow");

										jQuery(".tags_url").each(function( index ) {
											if( jQuery( this ).val() == " "){
												jQuery( this ).val("");
											}
										});

								    }
								});

							</script>

					</td>


				</tr>



				<tr valign="top" id="tr_tags_urls" style="<?php echo ($active_all_site == '1')? 'display: none;' :''; ?>" >
					<th scope="row" >
						<label for="tags_urls"><?php _e('Urls cible')  ; ?></label>
						<p class="description"><?php _e('Le paramètre "?exact_url" permet de cibler l\'url exact')  ; ?></p>
					</th>
					<td id="tags_url">
						<div>
							Url Cible ou Slug de la catégorie :<input name="tags_urls[]" style="width:100%" required class="tags_url" type="text" placeholder="Entrer une URL" value="<?php echo ($tags_urls[0] != '')?$tags_urls[0]:''; ?>" />  <span style="<?php if(count($tags_urls)>1) : ?>display:none <?php endif; ?>" class="add_tags_url tags-pointer">Add</span>&nbsp;&nbsp;<span style="<?php if(count($tags_urls)==1 || count($tags_urls)==0) : ?>display:none <?php endif; ?>" class="drop_tags_url tags-pointer">Drop</span>
						</div>
						<?php
						for ($i=1;$i<count($tags_urls);$i++) {
							?>
						</br>
						<div>
							Url Cible :<input name="tags_urls[]" style="width:100%" class="tags_url" type="text" placeholder="Entrer une URL" value="<?php echo ($tags_urls[$i] != '')?$tags_urls[$i]:''; ?>" /> <span class="add_tags_url tags-pointer">Add</span>&nbsp;&nbsp;<span class="drop_tags_url tags-pointer">Drop</span>
						</div>
						<?php
					}
					?>
				</td>
				<script>
					$=jQuery;
					var i=0;
					<!-- Click to add input url -->
					$("body").on("click",".add_tags_url",function(){
						if($(".tags_url").length>0){
							$(".add_tags_url").hide();
							$(".drop_tags_url").show();
						}
						$("#tags_url").append('</br><div>Url Cible :<input name="tags_urls[]" style="width:100%" class="tags_url" type="text" placeholder="Entrer une URL" /><span class="add_tags_url tags-pointer">Add</span>&nbsp;&nbsp;<span class="drop_tags_url tags-pointer">Drop</span></div>');

					});
					<!-- Remove Url input -->
					$("body").on("click",".drop_tags_url",function(){
						$(this).parent().remove();
						$(".add_tags_url:last").show()
						if($(".tags_url").length==1) {
							$(".drop_tags_url").hide()
						}
					});
						var $chk_all_tags = $('#chk_all_tags');
						var $all_tags = $("input.tags_partners");
						if ( $("input.tags_partners:checked").length == $all_tags.length ) {
						    $chk_all_tags.attr('checked',  'checked');
				    	} else {
				    		$chk_all_tags.removeAttr('checked',  'checked');
				    	}
						$chk_all_tags.click(function() {
						    if (this.checked) {
						        $all_tags.attr('checked',  'checked');
						    } else {
						        $all_tags.removeAttr('checked', 'checked');
						    }
						});



					$(window).load(function(){
							$('.tags_dfp_container').each(function( index, value ){
								$check_length = $(this).find('.tags_dfp').length;
								$checked_length = $(this).find('.tags_dfp:checked').length;
								$disable_all_checkbox = $(this).find('.disable_all');
								if( $check_length !== $checked_length){
									$disable_all_checkbox.removeAttr('checked', 'checked');
								}else{
									$disable_all_checkbox.attr('checked', 'checked');
								}
							});

							$('.disable_all').on('click', function(){
								$checkbox = $(this).parents('.tags_dfp_container').find('.tags_dfp');
								if ( this.checked )  $checkbox.attr('checked', 'checked');
								else $checkbox.removeAttr('checked', 'checked');
							});
					});

				</script>
			</tr>
			<!-- #138594757 | NETWORK | Ancre Teads | Maj interface retrait des tags cible -->
				<tr valign="top">
					<th scope="row" >
						<h2><?php _e('Autres réglages')  ; ?></h2>
					</th>
				</tr>

				<?php do_action('tags_option_befor_other_settings', $tags); ?>

				<?php foreach ($list as $partner) :

					if ( isset( $partners[ $partner ]['comportement_inverse'] ) && $partners[ $partner ]['comportement_inverse'] ) {
						if ( ! in_array( $partner, $tags_to_hide_bo ) ) {
						?>
						<tr valign="top">
							<th scope="row" ><label for="tags_partners"><?php echo "Activer ".$partners[ $partner ]['desc']; ?></label> </th>
							<td >
								<input id="tags_partners" name="tags_partners[]" type="checkbox" <?php echo (in_array($partner,$tags_partners))?'checked':''; ?> value='<?php echo $partner?>'>
							</td>
						</tr>
						<?php
						}
					}
				endforeach;
				?>
			</table>
				<h2><?php _e('Options d’affichage')  ; ?></h2>
			<table border="1" class="disable_blocks">
				<tr valign="top">
					<th class="top_title" scope="row" ><label>OPTIONS</label></th>
					<th class="top_title" scope="row" ><label>DESKTOP</label></th>
					<th class="top_title" scope="row" ><label>MOBILE</label></th>
				</tr>
				<tr valign="top">
					<th scope="row" ><label for="disable_desktop_comms"><?php _e("Masquer les commentaires");?></label></th>
					<td><input name="disable_desktop_comms" type="checkbox"  <?php echo isset($disable_desktop_comms_select)? $disable_desktop_comms_select:''; ?> value="1">
					</td>
					<td><input name="disable_mobile_comms" type="checkbox"  <?php echo isset($disable_mobile_comms_select)? $disable_mobile_comms_select:''; ?> value="1">
					</td>
				</tr>

				<tr valign="top">
					<th scope="row" ><label for="disable_desktop_breadcrumb"><?php _e("Masquer le fil d’ariane");?></label></th>
					<td><input name="disable_desktop_breadcrumb" type="checkbox"  <?php echo isset($disable_desktop_breadcrumb_select)? $disable_desktop_breadcrumb_select:''; ?> value="1"></td>
					<td><input name="disable_mobile_breadcrumb" type="checkbox"  <?php echo isset($disable_mobile_breadcrumb_select)? $disable_mobile_breadcrumb_select:''; ?> value="1"></td>
				</tr>

				<tr valign="top">
					<th scope="row" ><label for="disable_desktop_footer"><?php _e("Masquer le FOOTER");?></label></th>
					<td><input name="disable_desktop_footer" type="checkbox"  <?php echo isset($disable_desktop_footer_select)? $disable_desktop_footer_select:''; ?> value="1"></td>
					<td><input name="disable_mobile_footer" type="checkbox"  <?php echo isset($disable_mobile_footer_select)? $disable_mobile_footer_select:''; ?> value="1"></td>
				</tr>

				<tr valign="top">
					<th scope="row" ><label for="disable_desktop_footercrm"><?php _e("Masquer le footer CRM");?></label></th>
					<td><input name="disable_desktop_footercrm" type="checkbox"  <?php echo isset($disable_desktop_footercrm_select)? $disable_desktop_footercrm_select:''; ?> value="1"></td>
					<td><input name="disable_mobile_footercrm" type="checkbox"  <?php echo isset($disable_mobile_footercrm_select)? $disable_mobile_footercrm_select:''; ?> value="1"></td>
				</tr>

				<tr valign="top">
					<th scope="row" ><label for="disable_desktop_playerbas"><?php _e("Masquer le player bas d’article");?></label></th>
					<td><input name="disable_desktop_playerbas" type="checkbox"  <?php echo isset($disable_desktop_playerbas_select)? $disable_desktop_playerbas_select:''; ?> value="1"></td>
					<td><input name="disable_mobile_playerbas" type="checkbox"  <?php echo isset($disable_mobile_playerbas_select)? $disable_mobile_playerbas_select:''; ?> value="1"></td>
				</tr>

				<tr valign="top">
					<th scope="row" ><label for="disable_desktop_blocauthor"><?php _e("Masquer le bloc auteur");?></label></th>
					<td><input name="disable_desktop_blocauthor" type="checkbox"  <?php echo isset($disable_desktop_blocauthor_select)? $disable_desktop_blocauthor_select:''; ?> value="1"></td>
					<td><input name="disable_mobile_blocauthor" type="checkbox"  <?php echo isset($disable_mobile_blocauthor_select)? $disable_mobile_blocauthor_select:''; ?> value="1"></td>
				</tr>
				<tr valign="top">
					<th scope="row" ><label for="disable_desktop_dateauthor"><?php _e("Masquer la ligne : auteur + date de publication ");?></label></th>
					<td><input name="disable_desktop_dateauthor" type="checkbox"  <?php echo isset($disable_desktop_dateauthor_select)? $disable_desktop_dateauthor_select:''; ?> value="1"></td>
					<td><input name="disable_mobile_dateauthor" type="checkbox"  <?php echo isset($disable_mobile_dateauthor_select)? $disable_mobile_dateauthor_select:''; ?> value="1"></td>
				</tr>
				<tr valign="top">
					<th scope="row" ><label for="disable_desktop_sharebtn"><?php _e("Masquer les boutons de partage article");?></label></th>
					<td><input name="disable_desktop_sharebtn" type="checkbox"  <?php echo isset($disable_desktop_sharebtn_select)? $disable_desktop_sharebtn_select:''; ?> value="1"></td>
					<td><input name="disable_mobile_sharebtn" type="checkbox"  <?php echo isset($disable_mobile_sharebtn_select)? $disable_mobile_sharebtn_select:''; ?> value="1"></td>
				</tr>
				<tr valign="top">
					<th scope="row" ><label for="disable_refresh_desktop"><?php _e("Désactiver le refresh");?></label></th>

					<td><input name="disable_refresh_desktop" type="checkbox"  <?php echo isset($disable_refresh_desktop_select)? $disable_refresh_desktop_select:''; ?> value="1"></td>
					<td><input name="disable_refresh_mobile" type="checkbox"  <?php echo isset($disable_refresh_mobile_select)? $disable_refresh_mobile_select:''; ?> value="1"></td>
				</tr>

				<?php if (get_param_global('has_ciblage_widget_mpr')): ?>
					<tr valign="top">
						<th scope="row" ><label for="disable_mpr_iframe_desktop"><?php _e("Désactiver MPR iframe");?></label></th>

						<td><input name="disable_mpr_iframe_desktop" type="checkbox"  <?php echo isset($disable_mpr_iframe_desktop_select)? $disable_mpr_iframe_desktop_select:''; ?> value="1"></td>
						<td><input name="disable_mpr_iframe_mobile" type="checkbox"  <?php echo isset($disable_mpr_iframe_mobile_select)? $disable_mpr_iframe_mobile_select:''; ?> value="1"></td>
					</tr>
				<?php endif; ?>

			<?php
			do_action('after_options_disable_blocks');
			?>
		</table>

		<p class="submit">	
			<?php if($index !== false){ ?>
			<input type="hidden" name="index" value="<?php echo $index; ?>" > 
			<?php } ?>

			<input id="submit" class="button button-primary" type="submit" value="<?php _e('Enregistrer les modifications')  ; ?>" name="submit" />
		</p>
	</form>
</div>
<?php
}
}
// FRONT OFFICE POUR AFFICHAGE DES tags
class tagsFront {

	function __construct() {
            // activate admin page
		$active = get_option('tags_active', false);
           // if ($active) {
		add_action('partners_options', array(&$this, 'run_tags'));
            //}

	}

	function run_tags() {
		global $wp_query, $instance_partners;
		global $partners;

		$dfp_formats = array();
		if( isset($instance_partners['dfp_v2']) ){
			$dfp_v2 = $instance_partners['dfp_v2'];
			$dfp_formats = $dfp_v2->get_formats_dfp();
		}

		$partners = apply_filters( 'init_partners', $partners );
		$request_uri = isset( $_SERVER['CLEAN_REQUEST_URI'] ) ?  $_SERVER['CLEAN_REQUEST_URI'] : $_SERVER['REQUEST_URI'];

		$current_url="//".$_SERVER['HTTP_HOST'].$request_uri;
		// remove args
		$parts = parse_url( "http:".$current_url);
		$current_url = SITE_SCHEME . "://".$parts['host'].$parts['path'];
		$current_path = trim($parts['path'] , "/") ;
		$current_url_params = array();
		if( isset($parts['query']) ) parse_str($parts['query'], $current_url_params);

		if( isset($_GET['debug_partners']) ){
			echo " DEBUG PARTNERS UL : $current_url <br>" ;				
		}
		$tags_options = get_option('tags_option' , array() );
		global $tags_options_active ;
		$tags_options_active = array();
		foreach ($tags_options as  $index_tag =>  $options) {

			$do_run_action = false;
			if($options['active']){

				if(!empty($options['active_all_site'])){
					$do_run_action=true;
				}else{
					foreach ($options['urls'] as $url) {
						// not an url.. assume a category_slug
						$url = trim($url);
						if( strpos($url , '://' ) === false ){ 
							if(  is_single() ){
								if ( has_category($url)){
									$do_run_action=true;
									break;
								}
							} elseif ( is_category($url)){
								$do_run_action=true;
								break;
							}

						} else{
							$parse_url = parse_url($url) ;
							$url_path_parsed = isset($parse_url['path'])? $parse_url['path'] :  '' ;
							$url_path = trim($url_path_parsed, "/") ;
							$url = $parse_url['scheme']."://".$parse_url['host'].$url_path_parsed;
							$target_url_params = array();
							if( isset($parse_url['query']) ) parse_str($parse_url['query'], $target_url_params);
							$exact_params = $this->verify_params($current_url_params, $target_url_params);

							$exact_url = false;
							if( isset($parse_url['query']) ){
								parse_str($parse_url['query'], $url_params);
								if( isset($url_params['exact_url']) ) $exact_url = true;
							}

							if( $exact_url ){
								$path_condition = ($parse_url['host'] == $_SERVER['HTTP_HOST'] && $exact_params && $url_path == $current_path);
							}else{
								$path_condition = (($parse_url['host'] == $_SERVER['HTTP_HOST'] && $exact_params) && (($url_path == "" && $current_path == "" ) || ( $url_path != "" && stripos($current_url, $url)!==false)));
							}

							if( $path_condition ){
								$do_run_action=true;
								break;
							}
						}

					}

				}


				if ( $do_run_action ){

					$tags_options_active[] =  $options ;

					if ( isset($options['dfp_tags']) &&  is_array($options['dfp_tags']) ){
						$dfp_tags = $options['dfp_tags'];
						add_filter('activate_dfp_format', function( $active_format, $key ) use ($dfp_tags){
							if( in_array($key, $dfp_tags) ){
								$active_format = false;
							}
							return $active_format;
						}, 10, 2);
					}

					global $site_config;
					$is_mobile = rw_is_mobile();

					if( ($options['disable_desktop_comms'] && $options['disable_mobile_comms']) || ($options['disable_desktop_comms'] && !$is_mobile) || ($options['disable_mobile_comms'] && $is_mobile)){
						$site_config['hide_comment_template'] = true;
					}

					if(($options['disable_desktop_breadcrumb'] && $options['disable_mobile_breadcrumb']) || ($options['disable_desktop_breadcrumb'] && !$is_mobile) || ($options['disable_mobile_breadcrumb'] && $is_mobile)){
						$site_config['hide_breadcrumb'] = true;
						add_filter('breadcrumb_rewo','__return_false');
					}

					if(($options['disable_desktop_footer'] && $options['disable_mobile_footer']) || ($options['disable_desktop_footer'] && !$is_mobile) || ($options['disable_mobile_breadcrumb'] && $is_mobile)){
						add_action('wp_footer',function(){
							echo '<script type="text/javascript">
							jQuery("footer").addClass( "hidden" );
							jQuery("#footersite").addClass( "hidden" );
							</script>';
						});
					}

					if(($options['disable_desktop_footercrm'] && $options['disable_mobile_footercrm']) || ($options['disable_desktop_footercrm'] && !$is_mobile) || ($options['disable_mobile_footercrm'] && $is_mobile)){
						add_shortcode('newsletter_footer_bar', '__return_false');
					}

					if(($options['disable_desktop_playerbas'] && $options['disable_mobile_playerbas']) || ($options['disable_desktop_playerbas'] && !$is_mobile) || ($options['disable_mobile_playerbas'] && $is_mobile)){
						add_filter('partner_filter_player_bas_article',"__return_false");
					}

					if(($options['disable_desktop_blocauthor'] && $options['disable_mobile_blocauthor']) || ($options['disable_desktop_blocauthor'] && !$is_mobile) || ($options['disable_mobile_blocauthor'] && $is_mobile)){
						add_filter('posts_show_author',function($s){ return false; });
					}

					if(($options['disable_desktop_dateauthor'] && $options['disable_mobile_dateauthor']) || ($options['disable_desktop_dateauthor'] && !$is_mobile) || ($options['disable_mobile_dateauthor'] && $is_mobile)){
						$site_config['hide_block_autor_date_single'] = true;
						remove_action( 'add_post_author_date', 'add_post_author_date' );
						remove_action( 'single_after_title', 'add_post_author_date' ); 
						remove_action('after_post_info_title','infos_date_author');
					}  
					if(($options['disable_desktop_sharebtn'] && $options['disable_mobile_sharebtn']) || ($options['disable_desktop_sharebtn'] && !$is_mobile) || ($options['disable_mobile_sharebtn'] && $is_mobile)){
						add_shortcode('simple_addthis_single','__return_false');	
						$site_config["sharer_below_title"] = false;
					}
					if(($options['disable_refresh_desktop'] && $options['disable_refresh_mobile']) || ($options['disable_refresh_desktop'] && !$is_mobile) || ($options['disable_refresh_mobile'] && $is_mobile)){
						add_filter('disable_refresh',"__return_true");	
					}
					if(($options['disable_mpr_iframe_desktop'] && $options['disable_mpr_iframe_mobile']) || ($options['disable_mpr_iframe_desktop'] && !$is_mobile) || ($options['disable_mpr_iframe_mobile'] && $is_mobile)){
						add_filter('active_campagne_mpr_ciblage',function($s){ return false; });
					}
					do_action('remove_blocks_options',$options);

					if( isset($_GET['debug_partners']) ){
						echo "<br/><a href=\"/wp-admin/options-general.php?page=tags_admin_option&edite&index=" . $index_tag . "\" target=\"_blanlk\">URL régle "
							. $options['name']
						.":</a><br/>";
					}


					if (is_array($options['partners'])){
						foreach ($options['partners'] as $option) {
							if(isset($_GET[$option])){
								continue;
							}
							if( isset($_GET['debug_partners']) ){
								echo " $option   <br/> \n" ;
							}
							if(isset($partners[$option]['comportement_inverse']) && $partners[$option]['comportement_inverse']){
								add_filter('partner_filter_'.$option,"__return_true");
							}else{
								add_filter('partner_filter_'.$option,"__return_false");
							}
						}
					}
				}
			}

		}

		if( isset($_GET['debug_partners']) ){
			add_action('wp_head', function(){
				exit();
			}, 100);
		}
	}
	/**
	 * Compare URL Params
	 *
	 * @param   array   $current_url_params   Current URL Params
	 * @param   array   $target_url_params   Target(BO) URL Params
	 * @return  BOOLEAN
	 */
	function verify_params($current_url_params=array(), $target_url_params=array()){
		unset($target_url_params['exact_url']);
		$url_params_diff = array_diff_assoc($target_url_params, $current_url_params);
		if( empty($url_params_diff) ) return true;
		return false;
	}

}


if (is_admin()):
	new Optiontags();
else : 
	new tagsFront();
endif;

