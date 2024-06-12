<?php

function reworld_save_meta($post_id, $meta_key, $format="", $force=false) {
    $old = get_post_meta($post_id, $meta_key, true);
    if( !isset($_POST[$meta_key]) ){
	    if($force && $old){ // for checkbox 
	        delete_post_meta($post_id, $meta_key, $old);
	    }
        return false;
    }
    $new = $_POST[$meta_key];
    if (!empty($format) && $format!="") {
        $new = sprintf($format, $new);
    }
    $return = false;
    if (isset($_POST[$meta_key]) && $new != $old) {
        update_post_meta($post_id, $meta_key, $new);
        $return = true;       
    } elseif ('' == $new && $old) {
        delete_post_meta($post_id, $meta_key, $old);
    }
    return $return;
}

function article_golbal_meta_box() {
    add_meta_box(
	    'article_global_properties',
	    __('Propriétés de l\'article' ) ,
	    'gen_article_golbal_meta_box',
	    array('post', ),
	    'normal', 
	    'high' 
    );
}
add_action('admin_init', 'article_golbal_meta_box');


function gen_article_golbal_meta_box() {	
  global $post;
  $custom = get_post_custom($post->ID);
  $post_auteur = $title_highlight = '';
  $is_google_news = '' ;
  $google_news = '0' ;
  $is_rss_redactions = '' ;
  $rss_redactions_disabled = '' ;
  $rss_redactions = '0' ;
  $post_auteur_ops = '';
  $screen = get_current_screen();

  if (isset($custom['title_highlight'])) $title_highlight = $custom['title_highlight'][0] ;
  if (isset($custom['post_auteur'])) $post_auteur = $custom['post_auteur'][0] ;
	if (isset($custom['google_news'])) $google_news = $custom['google_news'][0] ;
	if (isset($custom['rss_redactions'])) $rss_redactions = $custom['rss_redactions'][0] ;
  if (isset($custom['post_auteur_ops'])) $post_auteur_ops = $custom['post_auteur_ops'][0] ;
  if (isset($custom['is_thumb_featured'])) $is_thumb_featured = $custom['is_thumb_featured'][0] ;

  if($google_news!='0' || ( get_param_global('checked_google_news') && $screen->action == 'add' ) ){
      $is_google_news='checked';
  }else{
  	$rss_redactions_disabled = 'disabled' ;
  }
  if($rss_redactions!='0'){
      $is_rss_redactions='checked';       
  }

  if((isset($is_thumb_featured) && $is_thumb_featured=='1') || (get_param_global('display_featured_img_checked') && $screen->action == 'add')){
        $is_thumb_featured_select='checked';       
    }

  if(check_post_news($post->ID)){
  	$google_news_disabled = '' ;
  }else{
	  $google_news_disabled = 'disabled' ;
  }

 	$post_read_more = 0;
 	if(get_param_global('active_signature_bo')) {
    $afficher_signature = '';
    $is_checked='';
    if (count($custom) > 1 && !empty($custom['afficher_signature'])){
    	$afficher_signature = $custom['afficher_signature'][0] ;
    } 
    if($afficher_signature == "oui"){
        $is_checked='checked';
    }
 		if(get_param_global('active_signature_bo') == 'default' && $screen->action == 'add') {
		$is_checked='checked';
 		}
  } 
    ?>
  <div class="wrap">

    <p class='post_auteur_ops'>
      <label><?php _e('SIGNATURE OPS PERSONALISEE'); ?></label>
      <br />
      <input type="text" name="post_auteur_ops" id="post_auteur_ops" value="<?php echo $post_auteur_ops ; ?>"  />
    </p>

    <p class='thumb_featured'>
      <label for="is_thumb_featured"><input type="checkbox" name="is_thumb_featured" id="is_thumb_featured" value="1" <?php echo isset($is_thumb_featured_select)? $is_thumb_featured_select:''; ?> /><strong> <?php _e('Image à la une en avant ( afficher l\'image à la une dans l\'article )' ,  REWORLDMEDIA_TERMS ); ?></strong></label>
    </p>

  	<p class='title_highlight'>
      <label><?php _e(apply_filters('label_highlit_title_meta_box', 'Titre en avant ( utilisable sur le slideshow )') ); ?></label>
      <br />
      <input class="large-text" type="text" name="title_highlight" id="title_highlight" value="<?php echo $title_highlight ; ?>"  />
    </p>
  	
    <p class='post_auteur'>
      <label><?php _e('Auteur'); ?></label>
      <br />
      <input type="text" name="post_auteur" id="post_auteur" value="<?php echo $post_auteur ; ?>"  />
    </p>  

  	<p class="google_news">

      	<input <?php echo $google_news_disabled ;?> type="checkbox" name="google_news" id="google_news" value="1" <?php echo $is_google_news; ?> />&nbsp;<label for="google_news"><?php _e('Google Actualités' ) ; ?></label> 
      	(<span id="google_news_length"></span> <?php _e('mot(s)') ; ?>)
      	<br> <span><?php _e('Les articles comprenant entre 200 mots minimum et 800 mots maximum') ; ?></span>
  	</p>

  	<p class="rss_redactions">

      	<input <?php echo $rss_redactions_disabled ;?> type="checkbox" name="rss_redactions" id="rss_redactions" value="1" <?php echo $is_rss_redactions; ?> />&nbsp;<label for="rss_redactions"><?php _e('Afficher dans le flux rédactions') ; ?> </label>
  	</p>

  	<script type="text/javascript">
          

    jQuery(document).ready(function(){
    
      (function($, counter) {
        wpkContentEditor=false ;

          $( document ).on( 'tinymce-editor-init', function( event, editor ) {
            if ( editor.id !== 'content' ) {
              return;
            } 
            wpkContentEditor = editor;

          } );


        function check_google_news (){
          if ( ! wpkContentEditor || wpkContentEditor.isHidden() ) {
            text = $("#content").val();
          } else {
            text = wpkContentEditor.getContent( { format: 'raw' } );
          }
                
          length = counter.count( text );

          if(length<= 800 && length >= 200){
            jQuery("#google_news").removeAttr("disabled");
            jQuery("#google_news").attr("checked", "checked");

          }else{
            jQuery("#google_news").attr("disabled", "disabled").attr('checked',false);  
            jQuery("#rss_redactions").attr("disabled", "disabled").attr('checked',false); 
          }
          jQuery("#google_news_length").text(length);
        }
        
        check_google_news ();

        $("#content").change(function(){
          check_google_news ();
        });
        $("#google_news").click(function(){
          if(jQuery(this).is(":checked")){
                jQuery("#rss_redactions").removeAttr("disabled");
            }else{
              jQuery("#rss_redactions").attr("disabled", "disabled").attr('checked',false);                     
            }
        }); 
        
      })(jQuery, new wp.utils.WordCounter()); 
    }); 
    </script>

    <p class="afficher_signature">
      <input type="checkbox" name="afficher_signature" id="checkbox_afficher_signature" value="oui" <?php echo $is_checked; ?> />&nbsp;<label for="checkbox_afficher_signature"> <?php _e('Afficher Signature' ) ; ?></label>  
    </p>
      
    <input type="hidden" name="article_meta_box_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
  </div>
  <!-- end .wrap -->
  <?php 
  
}


function save_article_golbal_meta_box($post_id) {   
	if (!isset($_POST['article_meta_box_nonce']) || (isset($_POST['article_meta_box_nonce']) && !wp_verify_nonce($_POST['article_meta_box_nonce'], basename(__FILE__))) && !wp_is_post_revision( $post_id )) {
		return $post_id;
	}   
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)   {
		return $post_id;
	}
          
	if (isset($_POST['post_type']) && 'post' == $_POST['post_type'] or 1 ) { 
		if (current_user_can('edit_post', $post_id)) { 
      reworld_save_meta($post_id, "post_auteur"); 
			reworld_save_meta($post_id, "post_auteur_ops");
			reworld_save_meta($post_id, "title_highlight");
			reworld_save_meta($post_id, "afficher_signature", "", true);
      reworld_save_meta($post_id, "is_thumb_featured", "", true);
			
			if(check_post_news($post_id)){
				reworld_save_meta($post_id, "google_news", "", true);          	
			}else{
				update_post_meta($post_id, "google_news", 0);
			}

			if(check_post_news($post_id) && isset($_POST['google_news']) && $_POST['google_news'] == 1){
				reworld_save_meta($post_id, "rss_redactions", "", true);           	
			}else{
				update_post_meta($post_id, "rss_redactions", 0);
			}
		} else {
			return $post_id; 
		}
	}
}  
add_action('save_post', 'save_article_golbal_meta_box');

function check_post_news($id){
	$content_post = get_post($id);
	$content = strip_tags($content_post->post_content);

	return true ;
}

function filter_tiny_mce_before_init($mceInit, $editor_id=null){
	$mceInit['setup'] = 'function(ed) {
			          ed.onChange.add(function(ed, l) {			          			          
			         
			           //console.log(ed);
			           if(ed.id == "content"){
							tinyMCE.triggerSave();
							jQuery("#" + ed.id).change() ; 
							//console.log(jQuery("#" + ed.id).val());
						}


			          });			          
			     }';
	return $mceInit ;
}
add_filter('tiny_mce_before_init', 'filter_tiny_mce_before_init');

/**
 * Register a maintenance checkbox to general settings page
 * @param
 *
 * @return
**/

add_filter( 'admin_init' , 'maintenance_mode_register_field' );
function maintenance_mode_register_field() {
    register_setting( 'general', 'enable_maintenance_mode', 'esc_attr' );
    add_settings_field('enable_maintenance_mode', '<label for="enable_maintenance_mode">'. 'Enable maintenance mode' . '</label>' , 'maintenance_mode_field' , 'general' );
}
function maintenance_mode_field() {
    $value = get_option( 'enable_maintenance_mode' );
    echo '<p><label>
        <input id="enable_maintenance_mode" type="checkbox" name="enable_maintenance_mode" value="1" '.checked(!empty($value), true, false).'/> Check if you want to enable maintenance mode
        </label></p>';
}


function reg_exclude_from_home_diapo_meta_box() {
    add_meta_box("exclude-from-diapo","Diaporama Accueil","gen_exclude_from_home_diapo_meta_box","post","side","high");
}

add_action('add_meta_boxes', 'reg_exclude_from_home_diapo_meta_box');

function gen_exclude_from_home_diapo_meta_box($post) {
    wp_nonce_field( basename( __FILE__ ), 'exclude_home_diapo' );
    $_postMeta = get_post_meta( $post->ID ,"exclude_from_home_diapo",true);
    ?>
    <p>
    <span class="prfx-row-title">
      <?php _e( 'Checker si vous voulez exclure l\'article du diaporama accueil', REWORLDMEDIA_TERMS )?>
    </span>

    <div class="prfx-row-content">
        <label for="exclude_from_home_diapo">
            <input type="checkbox" name='exclude_from_home_diapo' id="exclude_from_home_diapo" <?php if (isset ($_postMeta) && $_postMeta) echo "checked"; ?>>
            <?php _e('Exclure du diaporama accueil', REWORLDMEDIA_TERMS ) ?>
        </label>
    </div>
    </p>
    <?php
}

function post_save_exclude_from_home_diapo( $post_id )
{
    // Checks save status - overcome autosave, etc.
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'exclude_home_diapo' ] ) && wp_verify_nonce( $_POST[ 'exclude_home_diapo' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    // Checks for input and saves - save checked as yes and unchecked at no
    //var_dump($_POST[ 'ops_post' ] );die;
    if( !empty( $_POST[ 'exclude_from_home_diapo' ] ) ) {
        update_post_meta( $post_id, 'exclude_from_home_diapo', true );
    } else {
        delete_post_meta( $post_id, 'exclude_from_home_diapo' );
    }
}

add_action( 'save_post', 'post_save_exclude_from_home_diapo' );


/* meta de post type exposant */

function exposant_include_uploadscript() {

    if ( ! did_action( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
    }

    wp_enqueue_script( 'myuploadscript', STYLESHEET_DIR_URI . '/assets/javascripts/logo_upload.js', array('jquery'), null, false );
}

add_action( 'admin_enqueue_scripts', 'exposant_include_uploadscript' );

function logo_uploader_field( $name, $value = '') {
    $image = ' button">Upload image';
    $image_size = 'thumbnail';
    $display = 'none'; // display state ot the "Remove image" button

    if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {

        $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
        $display = 'inline-block';

    }

    return '
    <h2>Logo</h2>
    <div>
        <a href="#" class="logo_upload_image_button' . $image . '</a>
        <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
        <a href="#" class="logo_remove_image_button" style="display:inline-block;display:' . $display . '">Remove image</a>
    </div>';
}

/*
 * Add a meta box
 */
global $site_config;
if(!isset($site_config["reset_cpt_exposant"])){
  add_action( 'admin_init', 'exposant_meta_box_add' );
  add_action('save_post_exposant', 'save_exposant');
}

function exposant_meta_box_add() {
    add_meta_box('logodiv', // meta box ID
        "Exposant", // meta box title
        'gen_exposant_golbal_meta_box', // callback function that prints the meta box HTML
        'exposant', // post type where to add it
        'normal', // priority
        'high' ); // position
}

/*
 * Meta Box HTML
 */
function gen_exposant_golbal_meta_box( $post ) {
    $custom = get_post_custom($post->ID);
    $logo_exposant = $num_stand = $email_exposant = $phone_exposant = '';

    if (isset($custom['logo_exposant'])) $logo_exposant = $custom['logo_exposant'][0] ;
    if (isset($custom['num_stand'])) $num_stand = $custom['num_stand'][0] ;
    if (isset($custom['email_exposant'])) $email_exposant = $custom['email_exposant'][0] ;
    if (isset($custom['phone_exposant'])) $phone_exposant = $custom['phone_exposant'][0] ;

    echo logo_uploader_field( 'logo_exposant', $logo_exposant );
    ?>
    <div class="wrap">
        <p class='num_stand'>
            <label><?php _e('N° de Stand'); ?></label>
            <br />
            <input type="text" name="num_stand" id="num_stand" value="<?php echo $num_stand ; ?>"  />
        </p>
    </div>
    <div class="wrap">
        <p class='email_exposant'>
            <label><?php _e('Email exposant'); ?></label>
            <br />
            <input type="text" name="email_exposant" id="email_exposant" value="<?php echo $email_exposant ; ?>"  />
        </p>
    </div>
    <div class="wrap">
        <p class='phone_exposant'>
            <label><?php _e('Numéro de téléphone exposant'); ?></label>
            <br />
            <input type="text" name="phone_exposant" id="phone_exposant" value="<?php echo $phone_exposant ; ?>"  />
        </p>
    </div>
    <?php
}

/*
 * Save Meta Box data
 */

function save_exposant( $post_id )
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    update_post_meta($post_id, 'logo_exposant', $_POST['logo_exposant']);
    update_post_meta($post_id, 'num_stand', $_POST['num_stand']);
    update_post_meta($post_id, 'email_exposant', $_POST['email_exposant']);
    update_post_meta($post_id, 'phone_exposant', $_POST['phone_exposant']);

    return $post_id;
}