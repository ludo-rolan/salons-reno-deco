<?php

define('TEMPLATE_NL_POST_TYPE', 'nltemplate');

/**
 * define a new post type : newsletter
 */
function rw_newsletter_module() {
	$labels = array(
		'name'               => __( 'Newsletter', REWORLDMEDIA_TERMS),
		'singular_name'      => __( 'Newsletter', REWORLDMEDIA_TERMS),
		'menu_name'          => __( 'Newsletters',REWORLDMEDIA_TERMS),
		'name_admin_bar'     => __( 'Newsletters', REWORLDMEDIA_TERMS),
		'add_new'            => __( 'Nouvelle NL', REWORLDMEDIA_TERMS),
		'add_new_item'       => __( 'Ajouter Nouvelle Newsletter', REWORLDMEDIA_TERMS),
		'new_item'           => __( 'Nouvelle Newsletter', REWORLDMEDIA_TERMS),
		'edit_item'          => __( 'Editer Newsletter', REWORLDMEDIA_TERMS),
		'view_item'          => __( 'Afficher Newsletter', REWORLDMEDIA_TERMS),
		'all_items'          => __( 'Toutes Les NL', REWORLDMEDIA_TERMS),
		'search_items'       => __( 'Search Newsletters', REWORLDMEDIA_TERMS),
		'parent_item_colon'  => __( 'Parent Newsletters:', REWORLDMEDIA_TERMS),
		'not_found'          => __( "Aucune Newsletter n'est trouvée.", REWORLDMEDIA_TERMS),
		'not_found_in_trash' => __( "Aucune Newsletter n'est trouvée dans la corbeille.", REWORLDMEDIA_TERMS)
	);
	$args = array(
		'label' => __('Newsletter'),
		'labels' => $labels,
		'singular_label' => __('Newsletter'),
		'public' => true,
		'show_ui' => true,
		'_builtin' => false, // It's a custom post type, not built in
		'_edit_link' => 'post.php?post=%d',
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array("slug" => "newsletter"),
		'query_var' => "newsletter", // This goes to the WP_Query schema
		'supports' => array('title', 'thumbnail') //titre + zone de texte + champs personnalisés + miniature valeur possible : 'title','editor','author','thumbnail','excerpt'
	);
	register_post_type( 'newsletter' , $args ); // enregistrement de l'entité projet basé sur les arguments ci-dessus
}

/**
 * add template neswletter post type
 */
function rw_template_newsletter_type() {
	$labels = array(
		'name'               => __( 'Template', REWORLDMEDIA_TERMS),
		'singular_name'      => __( 'Template', REWORLDMEDIA_TERMS),
		'menu_name'          => __( 'Templates',REWORLDMEDIA_TERMS),
		'name_admin_bar'     => __( 'Template', REWORLDMEDIA_TERMS),
		'add_new'            => __( 'Nouvelle Template', REWORLDMEDIA_TERMS),
		'add_new_item'       => __( 'Ajouter Template', REWORLDMEDIA_TERMS),
		'new_item'           => __( 'Nouvelle Template', REWORLDMEDIA_TERMS),
		'edit_item'          => __( 'Editer Template', REWORLDMEDIA_TERMS),
		'view_item'          => __( 'Afficher Template', REWORLDMEDIA_TERMS),
		'all_items'          => __( 'Toutes Les Templates', REWORLDMEDIA_TERMS),
		'search_items'       => __( 'Search Templates', REWORLDMEDIA_TERMS),
		'parent_item_colon'  => __( 'Parent Templates:', REWORLDMEDIA_TERMS),
		'not_found'          => __( "Aucune Templates n'est trouvée.", REWORLDMEDIA_TERMS),
		'not_found_in_trash' => __( "Aucune Templates n'est trouvée dans la corbeille.", REWORLDMEDIA_TERMS)
	);
	$args = array(
		'label' => __('Templates', REWORLDMEDIA_TERMS),
		'labels' => $labels,
		'singular_label' => __('Template', REWORLDMEDIA_TERMS),
		'public' => true,
		'show_ui' => true,
		'_builtin' => false, // It's a custom post type, not built in
		'_edit_link' => 'post.php?post=%d',
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array("slug" => TEMPLATE_NL_POST_TYPE),
		'query_var' => "cars", // This goes to the WP_Query schema
		'supports' => array('title', 'editor') //titre + zone de texte + champs personnalisés + miniature valeur possible : 'title','editor','author','thumbnail','excerpt'
	);
	register_post_type( TEMPLATE_NL_POST_TYPE , $args ); // enregistrement de l'entité projet basé sur les arguments ci-dessus
}

// add_action('init', 'rw_newsletter_module');
// add_action('init', 'rw_template_newsletter_type');

/**
 * enqueues scripts
 * @return [type]
 */
function nl_enqueue_scripts() {
	global $post;

	$template_directory_uri = get_template_directory_uri();
	if(isset($post ) && $post->post_type == "newsletter") {
		wp_enqueue_style("newsletter-css" , $template_directory_uri . "/assets/stylesheets/newsletter.css");
		wp_enqueue_script("newsletter-js" , $template_directory_uri . "/assets/javascripts/newsletter.js", array('jquery'), CACHE_VERSION_CDN);

		wp_register_script('zero_clipboard_js', $template_directory_uri.'/assets/javascripts/zeroClipboard/ZeroClipboard.js', array('jquery'), '1.0' , true );
		wp_register_script('newsletter_widget_js', $template_directory_uri.'/assets/javascripts/jquery-ui.js', array('jquery'), '1.2.2' , true );
		wp_enqueue_script('newsletter_widget_js');
		wp_enqueue_script('zero_clipboard_js');
		wp_enqueue_script('upload-img-js', $template_directory_uri.'/assets/javascripts/image-uploader.js', array('jquery'), CACHE_VERSION_CDN);
    	wp_enqueue_media();
	}
}
add_action('admin_enqueue_scripts', 'nl_enqueue_scripts');

/**
 * init post type and enqueue js
 */
function set_up_newsletter() {
	rw_newsletter_module();
	rw_template_newsletter_type();
	// nl_enqueue_scripts();
}
add_action('init','set_up_newsletter');

/**
 * preview newsletter template
 * @return [type]
 */
function show_newsletter_template() {
	global $post;
	if(is_single() && $post->post_type == TEMPLATE_NL_POST_TYPE) {
		echo $post->post_content;
		exit;
	}
}
add_action('wp_head', 'show_newsletter_template');

/**
 * add template submenu to newsletter menu
 */
function add_template_menu_rw_newsletter() {
	$sub_menu = add_submenu_page("edit.php?post_type=newsletter", __('Templates',REWORLDMEDIA_TERMS), __('Templates',REWORLDMEDIA_TERMS), 1, "edit.php?post_type=".TEMPLATE_NL_POST_TYPE);
}
add_action('admin_menu', 'add_template_menu_rw_newsletter');  


/**
 *  Adds a box to the main column on the newsletter.
 */
function template_newsletter_add_meta_box() {
	global $post;
	if($post->post_type == TEMPLATE_NL_POST_TYPE) {
		if( is_dev('fix_warnings_migration_0068') ){
			add_filter('user_can_richedit' , function() { return false; } , 50); 
		}else{
			add_filter('user_can_richedit' , create_function('' , 'return false;') , 50); 
		}
		nlMinArticle_add_meta_box();
	}
}
/**
 *  Adds a box to the main column on the template.
 */
function nlMinArticle_add_meta_box() {

	$newsletter_slug =  'nltemplate' ;

	add_meta_box(
		'rw_nlMinArticle_id',
		__( "Nombre minimum d'articles", REWORLDMEDIA_TERMS ),
		'nl_min_article_callback',
		$newsletter_slug
	);
}
function nl_min_article_callback(){
	global $post;
	
	$metasPost = get_post_custom($post->ID);
	$nlMinArticle = isset($metasPost["nlminarticle"]) ? $metasPost["nlminarticle"][0] : "";
?>

	<form action="" method="post">
		<p>
			<label for="nlminarticle">Nombre minimum d'articles dans cette template : </label>
			<input type="text" name="nlminarticle"  id="nlminarticle" value="<?php echo $nlMinArticle ?>" />
		</p>
	</form>

<?php

}

/**
 * save newsletter meta and display 
 */
function save_min_article_meta_box($post_id){
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)   {
    	return $post_id;
    }
    reworld_save_meta($post_id,"nlminarticle");

}
add_action('save_post_nltemplate', 'save_min_article_meta_box');
add_action( 'add_meta_boxes', 'template_newsletter_add_meta_box' );

function disable_for_nl_template($default) {
    global $post;
    if (TEMPLATE_NL_POST_TYPE == get_post_type($post)) {
        return false;
    }
    return $default;
}
add_filter('user_can_richedit', 'disable_for_nl_template');

/**
 *  Adds a box to the main column on the newsletter.
 */
function newsletter_add_meta_box() {

	$newsletter_slug =  'newsletter' ;

	add_meta_box(
		'rw_newsletter_id',
		__( 'My Post Section Title', REWORLDMEDIA_TERMS ),
		'newsletter_add_meta_box_callback',
		$newsletter_slug
	);
}
add_action( 'add_meta_boxes', 'newsletter_add_meta_box' );


/**
 * displays fields for newsletter
 */
function newsletter_add_meta_box_callback() {
	global $post;
	
	$newsletter_post = get_post_custom($post->ID);
	$ex_articles = isset($newsletter_post['nl_articles']) ? maybe_unserialize($newsletter_post['nl_articles'][0]) : array();
	$ex_template = isset($newsletter_post['nl_template']) ? intval($newsletter_post['nl_template'][0]) : '';
	$ex_tracking_url = isset($newsletter_post['nl_tracking_url']) ? $newsletter_post['nl_tracking_url'][0] : '';
	if( !get_param_global('hide_premier_bandeau') ){
		$bandeau_image = isset($newsletter_post['nl_bandeau']) ? $newsletter_post['nl_bandeau'][0] : 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw';
		$nl_bandeau_url = isset($newsletter_post['nl_bandeau_url']) ? $newsletter_post['nl_bandeau_url'][0] : '#';
	}
	if( get_param_global('show_deuxieme_bandeau') ){
		$bandeau_image_2 = isset($newsletter_post['nl_bandeau_2']) ? $newsletter_post['nl_bandeau_2'][0] : 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw';
		$nl_bandeau_url_2 = isset($newsletter_post['nl_bandeau_url_2']) ? $newsletter_post['nl_bandeau_url_2'][0] : '#';
	}

	$obj1 = isset($newsletter_post['obj_1']) ? $newsletter_post['obj_1'][0] : '';
	$obj2 = isset($newsletter_post['obj_2']) ? $newsletter_post['obj_2'][0] : '';
	$startdt = isset($newsletter_post['startdt']) ? $newsletter_post['startdt'][0] : date('Y/m/d H:i:s');
	$segement_id = isset($newsletter_post['segement_id']) ? $newsletter_post['segement_id'][0] : '';
	$hide_tags_nl = isset($newsletter_post['hide_tags_nl']) ? $newsletter_post['hide_tags_nl'][0] : 0;

	if( !get_param_global('hide_premier_bandeau') ){
		$hide_bandeau_nl = isset($newsletter_post['hide_bandeau_nl']) ? $newsletter_post['hide_bandeau_nl'][0] : 0;
	}

	if( get_param_global('show_deuxieme_bandeau') ){
		$hide_bandeau_nl_2 = isset($newsletter_post['hide_bandeau_nl_2']) ? $newsletter_post['hide_bandeau_nl_2'][0] : 0;
	}

	if(is_dev('intégration_encarts_serviciels_162603628')){
		$show_pave_serviciel = isset($newsletter_post['show_pave_serviciel']) ? $newsletter_post['show_pave_serviciel'][0] : 0;
		$show_bandeau_serviciel = isset($newsletter_post['show_bandeau_serviciel']) ? $newsletter_post['show_bandeau_serviciel'][0] : 0;
	}
	$ref_id_rmp = isset($newsletter_post['ref_id_rmp']) ? $newsletter_post['ref_id_rmp'][0] : '';

	$articles_to_exclude = [];

	foreach($ex_articles as $ar){
		$articles_to_exclude[] = $ar['id'];
	}
	// retrieve template args
	$args_templates = array(
		'post_type'  		=> TEMPLATE_NL_POST_TYPE,
		'orderby'    		=> 'post_date',
		'order'      		=> 'DESC',
		'posts_per_page'	=> 12,
	);

	$args_templates = apply_filters('nl_args_templates', $args_templates);
	$templates = get_posts($args_templates);
	
	// retrieve articles args
	$args_articles = array(
		'post_type'  		=> 'post',
		'orderby'    		=> 'post_date',
		'order'      		=> 'DESC',
		'posts_per_page'    => 15,
		'post_status'      => 'any',
		'post__not_in' 		=> $articles_to_exclude,
		'date_query'    => array(
	        'column'  => 'post_date',
	        'after'   => '- 10 days'
	    ),
	);
	$articles = get_posts($args_articles);
?>

	<form class="newsletter_form" action ="#" method="POST" enctype="multipart/form-data">
	 <div class=" wpseo-metabox-tabs-div newsletter-container">
	 	<ul class="wpseo-metabox-tabs header-tabs" style="display: block">
		 	<li class="nl_tabs template active" ref=".newsletter_template_div">
		 		<a href="#" onclick="return false;"> Templates</a>
		 	</li>
		 	<li class="nl_tabs articles" ref=".edit_articles_div">
		 		<a href="#" onclick="return false;"> Articles</a>
		 	</li>
		 	<li class="nl_tabs apercu" ref=".preview_newsletter_tab">
		 		<a href="#" onclick="return false;"> Aperçu</a>
		 	</li>
		 	<li class="nl_tabs xml_tab" ref=".xml_newsletter_tab">
		 		<a href="#" onclick="return false;"> XML</a>
		 	</li>
		</ul>
	 	<div class="wpseotab nl_tab newsletter_template_div active">
	 		<div class="choose_template">
		 		<select name="rw_templates">
				<?php 
					foreach($templates as $template) {
						$minart = get_post_meta($template->ID,"nlminarticle",true);
				?>
					<option data-minart="<?php echo $minart ?>" value="<?php echo $template->ID; ?>" <?php if($template->ID == $ex_template ) echo 'selected="selected"'; ?> ><?php echo $template->post_title; ?></option>
				<?php	
					}
				?>
		 		</select><span style="margin-left: 5px; font-size: 11px;" id="nlmessagereste"></span>
	 		</div>
	 		<div class="selected_articles_area">
	 			<h3><?php echo __('Les articles séléctionnés', REWORLDMEDIA_TERMS); ?></h3>
	 			<ul class="list_selected_articles">
	 			<?php 

					foreach($ex_articles as $ex_article) {
				?>
					<li id="line_<?php echo $ex_article['id'];?>">
						<a class="remove">X</a>
						<input class="newsletter_checked_article" type="checkbox" value="<?php echo $ex_article['id']; ?>" style="display:none;" />
						<span class="newsletter_article_title"><?php echo $ex_article['title']; ?></span>
						<textarea class="newsletter_article_excerpt" style="display:none;"><?php echo $ex_article['excerpt']; ?></textarea>
						<input type="hidden" value="<?php echo get_permalink( $ex_article['id'] ); ?>" class="newsletter_article_permalink" />
						<?php
							 $thumb_src = wp_get_attachment_thumb_url( get_post_thumbnail_id( $ex_article['ID'] ));
						?>
						<input type="hidden" value="<?php echo $thumb_src;  ?>" class="newsletter_article_image" width="160px"/>
						<?php
							$newsletter_article_category = get_menu_cat_post($ex_article['ID'])->name;
						?>
						<input type="hidden" value="<?php echo $newsletter_article_category;  ?>" class="newsletter_article_category" width="160px"/>
					</li>
				<?php	
					}
				?>
				</ul>
	 		</div>
	 		<div>
				<div class="list_articles">
	 				<ul>
	 				<?php 
						foreach($articles as $article) {
					?>
						<li>
							<input class="newsletter_checked_article" type="checkbox" value="<?php echo $article->ID; ?>" />
							<span class="newsletter_article_title"><?php echo $article->post_title; ?></span>
							<textarea class="newsletter_article_excerpt" style="display:none;"><?php echo $article->post_excerpt; ?></textarea>
						</li>
					<?php	
						}
					?>
	 				</ul>
	 			</div>
	 			<div class="search_articles">
	 				<?php do_action( 'autocomplete_search_articles', "newsletter_articles_autocomplete" ); ?>
	 			</div>
	 			<?php if(is_dev('vpf_crm_template_142315831')): ?>
	 			<div class="popular_articles search-rw">
	 				<h3>Les articles les plus populaires :</h3>
	 				<?php do_action( 'select_popular_articles', "newsletter_popular_articles" ); ?>
	 			</div>
	 			<?php endif;?>
	 		</div>
	 		<div>
	 			<input class="newsletter_edit_articles_btn button btn-action" style="float:right;" type="button" value="Appliquer" />
	 		</div>
	 	</div>
	 	<div class="wpseotab nl_tab edit_articles_div" >
	 		<div class="tracking_url">
		 		<label for="tracking_url"><?php echo __('Tracking à ajouter', REWORLDMEDIA_TERMS); ?></label>
		 		<input type="text" id="tracking_url" name="tracking_url" value="<?php echo $ex_tracking_url; ?>" />
	 		</div>

	 		<div class="obj_1">
	 			<label for="obj_1"><?php echo __('OBJECT 1', REWORLDMEDIA_TERMS); ?></label>
	 			<input type="text" id="obj_1" style="width: 400px;" name="obj_1" value="<?php echo $obj1; ?>" />
	 		</div>

	 		<div class="obj_2">
	 			<label for="obj_2"><?php echo __('OBJECT 2', REWORLDMEDIA_TERMS); ?></label>
	 			<input type="text" id="obj_2"  style="width: 400px;" name="obj_2" value="<?php echo $obj2; ?>" />
	 		</div>

	 		<div class="startdt">
	 			<label for="startdt"><?php echo __('START_DT', REWORLDMEDIA_TERMS); ?></label>
	 			<input type="text" id="startdt" name="startdt" value="<?php echo $startdt; ?>" />
	 		</div>
	 		<div class="segements">
		 		<label for="segement_id"><?php echo __('SEGMENT_ID', REWORLDMEDIA_TERMS); ?></label>
		 		<select style="width: 100px" id="segement_id" name="segement_id">
					<?php
					global $site_config_js;
					$segments = $site_config_js['nl_automatique']['segment_id'];
					foreach ($segments as $key => $value) {?>
							<option value="<?php echo $value; ?>" <?php if ($value == $segement_id) { echo 'selected'; } ?>><?php echo $key; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="template">
				<label for="template_id"><?php echo __('TEMPLATE_ID', REWORLDMEDIA_TERMS); ?></label>
				<select style="width: 100px" id="template_id" name="template_id">
					<?php
					global $site_config;
					$template_ids = $site_config['cheetah_nl']['ref_id_rmp'];
					foreach ($template_ids as $key => $value) {?>
					<option value="<?php echo $value; ?>" <?php if ($value == $ref_id_rmp) { echo 'selected'; } ?>><?php echo $key; ?></option>
					<?php } ?>
				</select>
			</div>
			<?php if(is_dev('crm_ajout_fonctionnalites_151514902')){?>
				<div class="tags_hide">
					<label for="hide_tags_nl"><?php echo __('Enlever les tags PUB', REWORLDMEDIA_TERMS); ?></label>
					<input type="checkbox" style="margin: 0 20px;" name="hide_tags_nl" <?php echo ($hide_tags_nl==1) ? 'checked':'' ;?> id="hide_tags_nl" value="1" />
				</div>

			<?php }?>
				<?php if( !get_param_global('hide_premier_bandeau') ){ ?>
					<div class="bandeau_hide">
						<label for="hide_bandeau_nl"><?php echo __('Enlever le bandeau', REWORLDMEDIA_TERMS); ?></label>
						<input type="checkbox" style="margin: 0 30px;" <?php echo ($hide_bandeau_nl==1) ? 'checked':'' ;?> name="hide_bandeau_nl" id="hide_bandeau_nl" value="1" />
					</div>
				<?php } ?>
				<?php if( get_param_global('show_deuxieme_bandeau') ){ ?>
				<div class="bandeau_hide_2">
					<label for="hide_bandeau_nl_2"><?php echo __('Enlever le 2eme bandeau', REWORLDMEDIA_TERMS); ?></label>
					<input type="checkbox" style="margin: 0 30px;" <?php echo ($hide_bandeau_nl_2==1) ? 'checked':'' ;?> name="hide_bandeau_nl_2" id="hide_bandeau_nl_2" value="1" />
				</div>
			<?php } ?>
			<?php if(is_dev('intégration_encarts_serviciels_162603628')){?>
				<div class="pave_serviciel">
					<label for="pave_serviciel"><?php echo __('Afficher le pavé serviciel', REWORLDMEDIA_TERMS); ?></label>
					<input type="checkbox" style="margin: 0 30px;" <?php echo ($show_pave_serviciel==1) ? 'checked':'' ;?> name="show_pave_serviciel" id="show_pave_serviciel" value="1" />
				</div>
				<div class="bandeau_serviciel">
					<label for="bandeau_serviciel"><?php echo __('Afficher le bandeau serviciel', REWORLDMEDIA_TERMS); ?></label>
					<input type="checkbox" style="margin: 0 30px;" <?php echo ($show_bandeau_serviciel==1) ? 'checked':'' ;?> name="show_bandeau_serviciel" id="show_bandeau_serviciel" value="1" />
				</div>
			<?php }?>
	 		<h3>Les articles</h3>
	 		<div id="sortable-articles" class="widgets-sortables ui-sortable">
	 			
	 		</div>
	 		<?php if(!get_param_global('hide_premier_bandeau') && !$hide_bandeau_nl) { ?>
		 		<h3>Le bandeau</h3>
		 		<div id="add-bandeau">
					<button type="button" class="button upload-image" onclick="upload_image(this, 'bandinput') ">Changer le bandeau</button>
					<br />
					<img src="<?php echo $bandeau_image; ?>" id="src_image_bandinput" style="max-width: 100%">
					<input value="<?php echo $bandeau_image ?>" name="image_bandeau" type="hidden" id="post_image_bandinput">
					<br />
					<label for="nl_bandeau_url">Url du bandeau :</label>
					<input type="text" value="<?php echo $nl_bandeau_url ?>" name="nl_bandeau_url" id="nl_bandeau_url" style="width: 80%;">
		 		</div>
		 	<?php } ?>
	 		<?php if(get_param_global('show_deuxieme_bandeau') && !$hide_bandeau_nl_2) { ?>
		 		<h3>Le 2eme bandeau</h3>
		 		<div id="add-bandeau_2">

					<button type="button" class="button upload-image" onclick="upload_image(this, 'bandinput_2') ">Changer le bandeau 2</button>
					<br />
					<img src="<?php echo $bandeau_image_2; ?>" id="src_image_bandinput_2" style="max-width: 100%">
					<input value="<?php echo $bandeau_image_2 ?>" name="image_bandeau_2" type="hidden" id="post_image_bandinput_2">
					<br />
					<label for="nl_bandeau_url_2">Url du 2eme bandeau :</label>
					<input type="text" value="<?php echo $nl_bandeau_url_2 ?>" name="nl_bandeau_url_2" id="nl_bandeau_url_2" style="width: 80%;">
		 		</div>
	 		<?php } ?>
	 		<div>
	 			<input class="button previous_btn btn-action" back-to="newsletter_template_div" style="float:right;" type="button" value="Précédents" />
	 			<input class="button preview_newsletter_btn btn-action" style="float:right;" type="button" value="Apercu" />
	 		</div>
	 	</div>
	 	<div class="wpseotab nl_tab preview_newsletter_tab">
	 		<div id="previewer_html"></div>
	 		<div style="float:right;">
	 			<?php $startid = $startdt ? date('Ymdhis', strtotime($startdt)) : ''; ?>
	 			<input class="button xml_btn btn-action" type="button" value="Afficher l'XML" />
	 			<input class="button json_btn btn-action" type="button" value="Créer la Compagne CHEETAH" />
	 		</div>
	 		<div style="float:right;">
	 			<button type="button" class="button btn-action copy-nl-btn">copier la newsletter</button>
	 		</div>
	 		<div style="float:right;">
	 			<input class="button previous_btn btn-action" back-to="edit_articles_div" type="button" value="Précédent" />
	 		</div>
	 		<div id="nl_loading" style="width: 100%;text-align: center;display: none;">
	 			<img src="/wp-content/themes/reworldmedia/assets/images-v2/logo_loading.gif" style="margin-left: auto;margin-right: auto;width: 55px;">
	 		</div>
			<div id="btns_cheetah" style="width: 100%;text-align: center;margin-top: 30px;padding: 15px;background: #000;">
		 		<input class="button launch_cheetah btn-action" disabled type="button" value="Approuver cette campagne" />
			</div>

	 	</div>

	 	<div class="wpseotab nl_tab xml_newsletter_tab">
	 		<div id="previewer_xml"></div>
	 		<div style="float:right;">
	 			<input id="selligent-send-btn" class="button send-xml btn-action" back-to="preview_newsletter_tab" type="button" value="Send XML to Selligent" />
	 		</div>
	 		<div style="float:right;">
	 			<input class="button previous_btn btn-action" back-to="preview_newsletter_tab" type="button" value="Précédent" />
	 		</div>
	 	</div>
	 </div>
	</form>
	 <script>
	 		
	 		<?php if(is_dev('network_module_nl_cms_enregistrement_nl_143045745')){?>
			var choosen_articles = <?php echo json_encode($ex_articles);?> ;
			<?php }else{?>
			var ex_articlesjs  = <?php echo json_encode($ex_articles);?>;
			var choosen_articles = {};
			<?php }?>
			var position = -1;
			var __articles_to_exclude = [];
			var ZeroClipboard_ini_url = "<?php echo get_template_directory_uri().'/assets/javascripts/zeroClipboard/ZeroClipboard.swf';?>"
			var messageMinArt = "<?php _e( 'Il vous reste {param} articles à choisir !', REWORLDMEDIA_TERMS ) ?>";
			var messageMustArt = "<?php _e( 'Il faut choisir {param} articles !', REWORLDMEDIA_TERMS ) ?>";
	 </script>
<?php
}
/**
 * save newsletter meta and display 
 */
function save_newsletter_meta_box($post_id, $post){
	if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !isset($_POST['rw_templates']))   {
    	return $post_id;
    }
	global $nl_articles, $bandeau,$nl_bandeau_url, $bandeau_2, $nl_bandeau_url_2;
	$template_id = $_POST['rw_templates'];
	$template = get_post( $template_id);
	if(isset($template) && isset($_POST['article'])) {
		$nl_articles = $_POST['article'];
		$tracking_url = $_POST['tracking_url'];
		$obj1 = isset($_POST['obj_1']) ? $_POST['obj_1'] : '';
		$obj2 = isset($_POST['obj_2']) ? $_POST['obj_2'] : '';
		$startdt = isset($_POST['startdt']) ? $_POST['startdt'] : '';
		$segement_id = isset($_POST['segement_id']) ? $_POST['segement_id'] : '';
		$ref_id_rmp = isset($_POST['ref_id_rmp']) ? $_POST['ref_id_rmp'] : '';
		$hide_tags_nl = isset($_POST['hide_tags_nl']) ? $_POST['hide_tags_nl'] : 0;

		if( !get_param_global('hide_premier_bandeau') ){
			$bandeau = $_POST['image_bandeau'];
			$nl_bandeau_url = $_POST["nl_bandeau_url"];
			$hide_bandeau_nl = isset($_POST['hide_bandeau_nl']) ? $_POST['hide_bandeau_nl'] : 0;
		}

		if( get_param_global('show_deuxieme_bandeau') ){
			$bandeau_2 = $_POST['image_bandeau_2'];
			$nl_bandeau_url_2 = $_POST["nl_bandeau_url_2"];
			$hide_bandeau_nl_2 = isset($_POST['hide_bandeau_nl_2']) ? $_POST['hide_bandeau_nl_2'] : 0;
		}

		if(is_dev('intégration_encarts_serviciels_162603628')){
			$show_pave_serviciel = isset($_POST['show_pave_serviciel']) ? $_POST['show_pave_serviciel'] : 0;
			$show_bandeau_serviciel = isset($_POST['show_bandeau_serviciel']) ? $_POST['show_bandeau_serviciel'] : 0;
		}

		$nlMinArticle = get_post_meta($template_id,"nlminarticle",true);

		if($nlMinArticle == "" or $nlMinArticle <= count($nl_articles)) {
			add_post_meta( $post_id, 'nl_template', $template_id, true ) || update_post_meta( $post_id, 'nl_template', $template_id);
			add_post_meta( $post_id, 'nl_tracking_url', $tracking_url, true ) || update_post_meta( $post_id, 'nl_tracking_url', $tracking_url);
			add_post_meta( $post_id, 'nl_articles', $nl_articles, true ) || update_post_meta( $post_id, 'nl_articles', $nl_articles);	
			
			add_post_meta( $post_id, 'obj_1', $obj1, true ) || update_post_meta( $post_id, 'obj_1', $obj1);
			add_post_meta( $post_id, 'obj_2', $obj2, true ) || update_post_meta( $post_id, 'obj_2', $obj2);
			add_post_meta( $post_id, 'startdt', $startdt, true ) || update_post_meta( $post_id, 'startdt', $startdt);
			add_post_meta( $post_id, 'segement_id', $segement_id, true ) || update_post_meta( $post_id, 'segement_id', $segement_id);
			add_post_meta( $post_id, 'hide_tags_nl', $hide_tags_nl, true ) || update_post_meta( $post_id, 'hide_tags_nl', $hide_tags_nl);
			if(is_dev('intégration_encarts_serviciels_162603628')){
				add_post_meta( $post_id, 'show_pave_serviciel', $show_pave_serviciel, true ) || update_post_meta( $post_id, 'show_pave_serviciel', $show_pave_serviciel);
				add_post_meta( $post_id, 'show_bandeau_serviciel', $show_bandeau_serviciel, true ) || update_post_meta( $post_id, 'show_bandeau_serviciel', $show_bandeau_serviciel);
			}
			add_post_meta( $post_id, 'ref_id_rmp', $ref_id_rmp, true ) || update_post_meta( $post_id, 'ref_id_rmp', $ref_id_rmp);

			if( !get_param_global('hide_premier_bandeau') ){
				add_post_meta( $post_id, 'nl_bandeau', $bandeau, true ) || update_post_meta( $post_id, 'nl_bandeau', $bandeau);	
				add_post_meta( $post_id, 'nl_bandeau_url', $nl_bandeau_url, true ) || update_post_meta( $post_id, 'nl_bandeau_url', $nl_bandeau_url);
				add_post_meta( $post_id, 'hide_bandeau_nl', $hide_bandeau_nl, true ) || update_post_meta( $post_id, 'hide_bandeau_nl', $hide_bandeau_nl);
			}

			if( get_param_global('show_deuxieme_bandeau') ){
				add_post_meta( $post_id, 'nl_bandeau_2', $bandeau_2, true ) || update_post_meta( $post_id, 'nl_bandeau_2', $bandeau_2);	
				add_post_meta( $post_id, 'nl_bandeau_url_2', $nl_bandeau_url_2, true ) || update_post_meta( $post_id, 'nl_bandeau_url_2', $nl_bandeau_url_2);
				add_post_meta( $post_id, 'hide_bandeau_nl_2', $hide_bandeau_nl_2, true ) || update_post_meta( $post_id, 'hide_bandeau_nl_2', $hide_bandeau_nl_2);
			}
		}
		else{ // le nombre d articles choisis est inferieur au nombre min de la template ou
			// afficher message d erreur
										
		}

	}
	
}
add_action('save_post_newsletter', 'save_newsletter_meta_box', 10, 2);

/**
 * show article field shortcode [nl_article key=val, field]
 * possible field values (key, image, title, excerpt, permalink)
 * key index of articles
 */
function nl_article_shortcode($attrs) {

	global $nl_articles, $nl_tracking_url, $bandeau,$nl_bandeau_url, $bandeau_2, $nl_bandeau_url_2;

 	$index = isset($attrs['key']) ? intval($attrs['key']) : 0;
 	$field = $attrs['field'];
 	if ( is_dev('newsletter_crop_auto_134798525') ) {
		$size = isset($attrs['size']) ? $attrs['size'] : 'rw_newsletter_content';
		$force_size = isset($attrs['force_size']) ? $attrs['force_size'] : false;
		if( $force_size == false ){
	 		if ( $index === 0) {
	 			$size = 'rw_newsletter_la_une';
			}else if ( in_array( $index, array('right_vid', 'left_vid') ) ){
				$size = 'rw_medium';
			}
		}
		$size = apply_filters( 'rw_newsletter_image_sizes', $size, array('index' => $index));
 	}else {
 		$size = isset($attrs['size']) ? $attrs['size'] :'rw_medium';
 	}
	$width = isset($attrs['width']) ? intval($attrs['width']) : 0;
	$height = isset($attrs['height']) ? intval($attrs['height']) : 0;
	$for_background = isset($attrs['background']) ? intval($attrs['background']) : 0;
	$custom_attr = isset($attrs['custom_attr']) ? $attrs['custom_attr'] : '';

	// if($width && $height) {
	// 	$size = array($width, $height);

	// }
	if(isset($nl_articles) && count($nl_articles) > 0 && $field != '') {
		if($field == 'image') {

			$thumb_src = wp_get_attachment_image_src( $nl_articles[$index]['attachment_id'] , $size )[0];
			// $thumb_src = wp_get_attachment_image_src( $nl_articles[$index]['$nl_articles'] , $size' )[0];
			
			if($for_background == 1){
				return 'background="'.$thumb_src."#$size".'"';
			}else if($custom_attr){
				return $custom_attr.'="'.$thumb_src.'"';
			}
			return $thumb_src."#$size";

	 	}
	 	/*if($field == "player"){
	 		if( isset($nl_articles[$index]['player']) and $nl_articles[$index]['player']==1)
	 		return '<img style="width: 50px; position: relative; z-index: 1111; margin: 49px 125px;" src="'. get_template_directory_uri() . '/assets/images-v2/icone_play_video.png">';
	 	}*/
		if($field == 'permalink') {
			$permalink_tracking = $nl_tracking_url;
			if( is_dev('ajout_tracking_specifique_156084076') && !empty($nl_articles[$index]['post_tracking']) ){
				$permalink_tracking = $nl_articles[$index]['post_tracking'];
			}
			if($custom_attr){
				return $custom_attr.'="'.$nl_articles[$index][$field].$permalink_tracking.'"';
			}
			return $nl_articles[$index][$field].$permalink_tracking;
		}
		if( !get_param_global('hide_premier_bandeau') ){
			if($field == "bandeau"){
				if($custom_attr){
					return $custom_attr.'="'.$bandeau.'"';
				}
				return $bandeau;
			}
			if($field == "bandeau_url"){
				if($custom_attr){
					return $custom_attr.'="'.$nl_bandeau_url.'"';
				}
				return $nl_bandeau_url;
			}
		}
		if( get_param_global('show_deuxieme_bandeau') ){
			if($field == "bandeau_2"){
				if($custom_attr){
					return $custom_attr.'="'.$bandeau_2.'"';
				}
				return $bandeau_2;
			}
			if($field == "bandeau_url_2"){
				if($custom_attr){
					return $custom_attr.'="'.$nl_bandeau_url_2.'"';
				}
				return $nl_bandeau_url_2;
			}
		}
		if($field == "title"){
			$field_title = stripslashes($nl_articles[$index]['title']);
			return preg_replace("/<img[^>]+\>/i", " ", $field_title);
		}
		return apply_filters('add_custom_fields_nl_article',stripslashes($nl_articles[$index][$field]),$nl_articles,$index,$field);
	}
	return '';
}
add_shortcode('nl_article', 'nl_article_shortcode');
if ( is_dev('newsletter_crop_auto_134798525') ) {
	add_action( 'init', 'rw_nl_image_sizes_genration' );
	function rw_nl_image_sizes_genration(){
		$la_une_nl_size_dimension = apply_filters('la_une_nl_size_dimension', array('width' => 680, 'height' => 372));
		add_image_size("rw_newsletter_la_une", $la_une_nl_size_dimension['width'] , $la_une_nl_size_dimension['height'], true);
		$content_nl_size_dimension = apply_filters('content_nl_size_dimension', array('width' => 310, 'height' => 170));
		add_image_size("rw_newsletter_content", $content_nl_size_dimension['width'] , $content_nl_size_dimension['height'], true);
	}
}
//Show a given number of articles in newsletter
function show_nl_articles_loop($attrs, $content){
	global $nl_articles, $nl_tracking_url, $bandeau,$nl_bandeau_url, $bandeau_2, $nl_bandeau_url_2, $template_id,$beginning_loop;

	if(isset($nl_articles) && isset($content) && isset($template_id)){
		$selected_articles = count($nl_articles);
		$min_articles_nb = get_post_meta($template_id, "nlminarticle", true);
		$min_articles_nb = $min_articles_nb ? $min_articles_nb : 4;
		$nb_posts_exlude = isset($attrs['nb_posts_exlude'])? $attrs['nb_posts_exlude'] : 3;
		if($selected_articles >= $min_articles_nb - $nb_posts_exlude){
			//$number_of_articles = $min_articles_nb;
			if( empty($beginning_loop) )
			//get value of beginning_loop
			$beginning_loop = isset($attrs['beginning_loop']) ? $attrs['beginning_loop'] : 1;
			$number_of_articles = $selected_articles;
			$nbr_body_posts = isset($attrs['posts-per-bloc']) ? ($attrs['posts-per-bloc']+$beginning_loop) : $number_of_articles - ($nb_posts_exlude - 1);
			
			$j = 1;
			for($i = $beginning_loop; $i < $nbr_body_posts ; $i++){
				$current_content = str_replace(array('{_', '_}'), array('[',']'), $content);
				$html_articles .= str_replace('key=n', 'key='.$i, $current_content);
				if( isset($attrs['posts-per-bloc']) && $attrs['posts-per-bloc'] == $j ){
					$beginning_loop += $attrs['posts-per-bloc'];
					break;
				}
				$j++;
			}
			$content = $html_articles;
		}else{
			$content = '<div style="font-size:16px;color:red;text-align:center; padding:20px 0; border: 1px solid red;">Vous devez sélectionner au moins <strong>' . $min_articles_nb . "</strong> d'articles!</div>";
		}
	}else{
		$content = '';
	}
	$content = html_entity_decode($content);
	return do_shortcode($content);
}
add_shortcode("loop_nl_articles", "show_nl_articles_loop");


function show_nl_bloc_articles($attrs, $content){
	global $nl_articles, $nl_tracking_url, $bandeau,$nl_bandeau_url, $bandeau_2, $nl_bandeau_url_2, $template_id,$beginning_loop,$show_bandeau_serviciel;
	if(isset($nl_articles) && $content && isset($template_id)){
		$selected_articles = count($nl_articles);
		$min_articles_nb = get_post_meta($template_id, "nlminarticle", true);
		$min_articles_nb = $min_articles_nb ? $min_articles_nb : 4;
		$nb_posts_exlude = isset($attrs['nb_posts_exlude'])? $attrs['nb_posts_exlude'] : 4;
		$articles_to_show = isset($attrs['articles_to_show']) ? $attrs['articles_to_show'] : 0;
		if($selected_articles >= $min_articles_nb - $nb_posts_exlude){
			$number_of_articles = ($selected_articles >= $articles_to_show && $articles_to_show > 1) ? $articles_to_show : $selected_articles - ($nb_posts_exlude - 1) ;
			$html = '';
			for($j = 1; $j <= ((int)$number_of_articles/2); $j++){
				$html .= $content;
			}
			$content = $html;
		}else{
			return '';
		}
	}else{
		$content = '';
	}
	return do_shortcode($content);
}
add_shortcode('show_nl_bloc_articles','show_nl_bloc_articles');

//Show in NL the last two videos in the selected list of articles
function show_last_two_nl_videos($attrs, $content){
	global $nl_articles, $nl_tracking_url, $bandeau,$nl_bandeau_url, $bandeau_2, $nl_bandeau_url_2, $template_id,$show_pave_serviciel;

	if(isset($nl_articles) && isset($content) && isset($template_id)){
		$selected_articles = count($nl_articles);
		$min_articles_nb = get_post_meta($template_id, "nlminarticle", true);
		$min_articles_nb = $min_articles_nb ? $min_articles_nb : 4;

		if($selected_articles >= $min_articles_nb){
			//$number_of_articles = $min_articles_nb;
			$number_of_articles = $selected_articles;
			$current_content = str_replace(array('{_', '_}'), array('[',']'), $content);

			$show_more_videos = isset($attrs['show_more_videos'])? $attrs['show_more_videos'] : '';
			$index = isset($attrs['index'])? $attrs['index'] : 0;

			if( $show_more_videos ){
				$html_articles .= str_replace('key=vid_1', 'key=' . ($number_of_articles - (1 + $index)), $current_content);
				$html_articles = str_replace('key=vid_2', 'key=' . ($number_of_articles - (2 + $index)), $html_articles);
				$html_articles = str_replace('key=vid_3', 'key=' . ($number_of_articles - (3 + $index)), $html_articles);
			}else{
				$html_articles .= str_replace('key=right_vid', 'key=' . ($number_of_articles-2), $current_content);
				$html_articles = str_replace('key=left_vid', 'key=' . ($number_of_articles-1), $html_articles);
			}

			$content = $html_articles;
		}
	}else{
		$content = '';
	}
	$content  = str_replace(array('<!--', '-->'), array('_{','}_'), html_entity_decode($content));
	$content  =  do_shortcode($content);
	$content  = str_replace(array('_{','}_'), array('<!--', '-->'), $content);
	return $content;
}
add_shortcode("last_two_nl_videos", "show_last_two_nl_videos");

//Show in NL the last two videos in the selected list of articles
function show_last_two_nl_popular($attrs, $content){
	global $nl_articles, $nl_tracking_url, $bandeau,$nl_bandeau_url, $bandeau_2, $nl_bandeau_url_2, $template_id;

	if(isset($nl_articles) && isset($content) && isset($template_id)){
		$selected_articles = count($nl_articles);
		$min_articles_nb = get_post_meta($template_id, "nlminarticle", true);
		$min_articles_nb = $min_articles_nb ? $min_articles_nb : 4;

		if($selected_articles >= $min_articles_nb){
			$number_of_articles = $selected_articles;
			$current_content = str_replace(array('{_', '_}'), array('[',']'), $content);
			$html_articles .= str_replace('key=popular_1', 'key=' . ($number_of_articles-2), $current_content);
			$html_articles = str_replace('key=popular_2', 'key=' . ($number_of_articles-1), $html_articles);

			$content = $html_articles;
		}
	}else{
		$content = '';
	}
	$content  = str_replace(array('<!--', '-->'), array('_{','}_'), html_entity_decode($content));
	$content  =  do_shortcode($content);
	$content  = str_replace(array('_{','}_'), array('<!--', '-->'), $content);
	return $content;
}
add_shortcode("last_two_nl_popular", "show_last_two_nl_popular");



function getPostDetails(){
	$id = $_GET['id'];
}

/**
 * newsletter preview, its used on ajax call.
 */
function rw_preview_newsletter() {
	global $nl_articles, $nl_tracking_url, $bandeau,$nl_bandeau_url, $bandeau_2, $nl_bandeau_url_2, $template_id,$show_pave_serviciel,$show_bandeau_serviciel;
	if(is_dev('intégration_encarts_serviciels_162603628')){
		$show_pave_serviciel =  $_POST['show_pave_serviciel'];
		$show_bandeau_serviciel =  $_POST['show_bandeau_serviciel'];
	}
	$post_id = $_POST['post_ID'];
	$post = get_post($post_id, OBJECT);
	if($post != null && $post->post_type == "newsletter") {
		
		$template_id = $_POST['rw_templates'];
		$nl_tracking_url = isset($_POST['tracking_url']) ? trim($_POST['tracking_url']): '';
		$template = get_post( $template_id);
		if(isset($template)) {
			$nl_articles = $_POST['article'];
			for ($k=0; $k < count($nl_articles); $k++) {
				 $nl_articles[$k]['title']=preg_replace('/[^ -\x{2122}]/u','',$nl_articles[$k]['title']);
				 $nl_articles[$k]['title']=trim(preg_replace('/(?<=\s)\s+/','',$nl_articles[$k]['title']));
			}

			if( !get_param_global('hide_premier_bandeau') ){
				$bandeau = $_POST['image_bandeau'];
				$nl_bandeau_url = $_POST['nl_bandeau_url'];
			}

			if( get_param_global('show_deuxieme_bandeau') ){
				$bandeau_2 = $_POST['image_bandeau_2'];
				$nl_bandeau_url_2 = $_POST['nl_bandeau_url_2'];
			}

			$content = $template->post_content;
			$nl_html = do_shortcode($content);
			$pLink = '/<a(.*)href="([^"^'.$nl_tracking_url.']*)"(.*)>(.*)<\/a>/';
			$rLink = '<a$1href="$2'.$nl_tracking_url.'"$3>$4</a>';

			$contentModified = preg_replace($pLink, $rLink, $nl_html);
			$contentModified = str_replace("[nl_article key=2 field=image size=rw_medium]",wp_get_attachment_image_src( $nl_articles[2]['attachment_id'] , "rw_medium" )[0],$contentModified);

			/**
			 * Ticket : tr - 3486 
			 * Remplacer les shortcodes non compilés par leurs valeurs
			 * By : bouhou@webpick.info
			 */
			$contentModified = preg_replace_callback(
				'~\[nl_article key=(.*) field=image size=thumb_hp\]~',
				function ($matches) {
					global $nl_articles;
		            return wp_get_attachment_image_src($nl_articles[(int)$matches[1]]['attachment_id'] , "thumb_hp")[0];
		        },
		        $contentModified);
			/**
			 *	End of ticket tr - 3486
			 */
			
			header('Content-Type: text/html; charset=utf-8');
			echo $contentModified; //html_entity_decode($contentModified );
			
		} else {
			echo "Error : template not found";
		}
	} else {
		echo "Error : current newsletter not found";
	}
	exit;
}
add_action('wp_ajax_rw_preview_newsletter', 'rw_preview_newsletter');
add_action('wp_ajax_nopriv_rw_preview_newsletter', 'rw_preview_newsletter');
	
/**
 * autocomplete search component
 */
function gen_search_article_box_for_nl($component_id="search-panel-gallery"){
	add_search_rw(); 
?>	 
	<div class="search-rw" id="<?php echo $component_id;?>">
		<div class="link-search-wrapper">
			<label>
				<span class="search-label"><?php echo __('Rechercher un article/ID :', REWORLDMEDIA_TERMS);?> </span>
				<input type="search" id="search-field-gallery" class="link-search-field" autocomplete="off" />
			</label>
		</div>
		<div class="next_gallery link-search-wrapper">
		    <strong id="next-gallery-id">
		    	<?php if ($next_gallery_id): ?>
		    		  <a onclick="removeNextGallery();" class="remove">X</a> <?php echo get_post($next_gallery_id)->post_title; ?>
				<?php endif ?>
			</strong>
		    <input id="next_gallery" type="hidden" name="next_gallery" value="<?php echo $next_gallery_id; ?>" />
		</div>

  		<span class="spinner search-rw-load" style="float:none;"></span>
		<div id="search-results-gallery" class="query-results" tabindex="0">
			<div class="query-nothing" id="no-results-gallery">
				<em>Aucun résultat trouvé pour les galeries</em>
			</div>
			<ul></ul>
			<div class="query-results-loading">
  				<span class="spinner" style="float:none;"></span>
  			</div>
		</div>
	</div>
<?php
}
add_action('autocomplete_search_articles', 'gen_search_article_box_for_nl', 10, 1);


/**
 * ajax retrieve article newsletter
 * @return [type]
 */
function rw_retrieve_article_newsletter() {
	$post_ids = $_GET['post_ids'];
	$ex_articles = get_post_meta($post->ID, 'nl_articles', true);
	$ex_articles = isset($ex_articles) ? maybe_unserialize($ex_articles) : array();

	$post_ids_array = explode(",", $post_ids);
	$args = array(
	    'post__in' => $post_ids_array,
	    'posts_per_page' => count($post_ids_array),
   	    'post_type' => 'any',
   	    'post_status'      => 'any',
	);

	$articles = array();
	
	$posts = get_posts($args);
	foreach ($posts as $post) :
		$post_status = $post->post_status;
		$post->post_status = 'publish';
		$permalink = get_permalink( $post );
		$post->post_status = $post_status;

		$attachment_id =  get_post_thumbnail_id( $post->ID );
		$thumb_src = wp_get_attachment_thumb_url($attachment_id);
		$newsletter_article_category = get_menu_cat_post($post);
		if( get_param_global('activate_date_article_NL') )
			$date = mysql2date( "j F Y", $post->post_date );

		$articles[$post->ID]['excerpt'] = $post->post_excerpt;
		$articles[$post->ID]['image'] = $thumb_src;
		$articles[$post->ID]['permalink'] = $permalink;
		$articles[$post->ID]['attachment_id'] = $attachment_id;
		$articles[$post->ID]['category_name'] = $newsletter_article_category ? $newsletter_article_category->name : '';
		$articles[$post->ID]['category_link'] = $newsletter_article_category ? get_category_link($newsletter_article_category) : '';
		if( get_param_global('activate_date_article_NL') )
			$articles[$post->ID]['date'] = $date;
	    
	endforeach;

	echo json_encode($articles);
	exit;
}
add_action('wp_ajax_rw_retrieve_article_newsletter', 'rw_retrieve_article_newsletter');
add_action('wp_ajax_nopriv_rw_retrieve_article_newsletter', 'rw_retrieve_article_newsletter');




function duplicate_nl_link( $actions, $post ) {
	if (current_user_can('edit_posts') and in_array($post->post_type, array(TEMPLATE_NL_POST_TYPE,"newsletter"))) {
		$actions['duplicate'] = '<a href="admin.php?action=duplicate_newsletter&amp;post=' . $post->ID . '" title="'. __("Dupliquer cette newsletter",REWORLDMEDIA_TERMS) .'" rel="permalink">'. __("Dupliquer",REWORLDMEDIA_TERMS) .'</a>';
	}
	return $actions;
}
function  duplicate_newsletter(){
	global $wpdb;
	
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'duplicate_newsletter' == $_REQUEST['action'] ) ) ) {
		wp_die('No post to duplicate has been supplied!');
	}
 
	$post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);

	$post = get_post( $post_id );
 
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;
 
	if (isset( $post ) && $post != null) {
 
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);
 
		$new_post_id = wp_insert_post( $args );
 
		$taxonomies = get_object_taxonomies($post->post_type); 
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
		}
 
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}
 
		wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		exit;
	} else {
		wp_die('Post creation failed, could not find original post: ' . $post_id);
	}
}

add_filter( 'post_row_actions', 'duplicate_nl_link', 10, 2 );
add_action( 'admin_action_duplicate_newsletter', 'duplicate_newsletter' );

function traking_links($attrs){
	global $nl_tracking_url, $nl_articles;
	if( is_dev('ajout_tracking_specifique_156084076') ){
 		$index = isset($attrs['key']) ? intval($attrs['key']) : false;
		if( is_int($index) && !empty($nl_articles[$index]['post_tracking']) ){
			return $nl_articles[$index]['post_tracking'];
		}
	}
	if($nl_tracking_url){
		return $nl_tracking_url;
	}
	return '';
}
add_shortcode('traking_links','traking_links');

function tracking_content($attrs,$content){
	global $nl_tracking_url;
	if(strpos($content, '?'))
	{
		$nl_tracking_url_adapt= ltrim($nl_tracking_url, '?');
		$nl_tracking_url_adapt='&'.$nl_tracking_url_adapt;
		return $content.$nl_tracking_url_adapt;
	}else{
		return $content.$nl_tracking_url;
	}
}
add_shortcode('tracking_content','tracking_content');

function articles_populaire($attrs){
	global $articles_populaire;
	$index = isset($attrs['key'])? $attrs['key'] : '';
	$field = isset($attrs['field'])? $attrs['field'] : '';
	$nbr = isset($attrs['nbr_popular_post'])? $attrs['nbr_popular_post'] : 2;

	if( $index && $field && empty($articles_populaire)){
		$posts_populaire = get_option(apply_filters('most_popular_option',"most_popular"), array());
		$posts_exclds = explode(",", $_GET['post_ids']);
		$ids_populaire = array();
		foreach ($posts_populaire as $id_post) {
			if(!in_array($id_post, $posts_exclds)){
				$ids_populaire[] = $id_post;
			}
		}
		/*
		L'option most_popular return les IDs des articles les plus populaire et puisque les 2 environnements local et préprod utilisent le meme ID GOOGLE ANALYTICS on peut avoir des ids de préprod qui n'existe pas en local ou l'inverse.
		*/

		$args = array(
			'post__in' => $ids_populaire, 
			'orderby' => 'post__in',
			'posts_per_page' => 16,
			'suppress_filters' => false
		);

		$posts = get_posts( $args );

		$articles_populaire = array();
		$j = 1;
		foreach ($posts as $post) {
			if( !empty($post)){
				$permalink = get_permalink( $post->ID );
				$attachment_id =  get_post_thumbnail_id( $post->ID );
				$thumb_src = wp_get_attachment_thumb_url($attachment_id);
				$newsletter_article_category = get_menu_cat_post($post);


				$articles_populaire[$j]['excerpt'] = $post->post_excerpt;
				$articles_populaire[$j]['title'] = $post->post_title;
				$articles_populaire[$j]['permalink'] = $permalink;
				$articles_populaire[$j]['thumb_src'] = wp_get_attachment_image_src( $attachment_id , 'rw_medium' )[0];
				$articles_populaire[$j]['attachment_id'] = $attachment_id;
				$articles_populaire[$j]['category_name'] = $newsletter_article_category ? $newsletter_article_category->name : '';
				$articles_populaire[$j]['category_link'] = $newsletter_article_category ? get_category_link($newsletter_article_category) : '';
				$j++;
			}
			if($j == ($nbr+1))
				break;
		}
	}

	if( !empty($articles_populaire) && $index && $field  ){
		if($field == "excerpt")
			return $articles_populaire[$index]['excerpt'];
		if($field == "title")
			return $articles_populaire[$index]['title'];
		if($field == "permalink")
			return $articles_populaire[$index]['permalink'];
		if($field == "category_name")
			return $articles_populaire[$index]['category_name'];
		if($field == "category_link")
			return $articles_populaire[$index]['category_link'];
		if($field == "image")
			return $articles_populaire[$index]['thumb_src'];

	}
	return "";
}
add_shortcode('nl_post_populaire','articles_populaire');

/**
 * popular articles
 */
function newsletter_popular_articles(){
	?>
	<div class="query-results">
	<?php
	$results = RW_Post::most_popular_all();
	foreach ($results as $object) {
	?>	 
		<div class="wrap_popular_articles">
			<ul class="list_popular_articles">
				<li data-id="<?php echo $object->ID; ?>">
					<span class="item-title"><?php echo $object->post_title; ?></span>
				</li>
			</ul>
		</div>
	<?php
	}
	?>
	</div>
	<?php
}
add_action( 'select_popular_articles', "newsletter_popular_articles", 10, 1);

if(file_exists(RW_THEME_DIR.'/include/functions/selligent.php')){
	require_once RW_THEME_DIR.'/include/functions/selligent.php';
}


function rw_get_planified_posts($query){
	if( isset($_POST["get_planified_posts"]) && $_POST["get_planified_posts"] == 'true' ){
		if(is_array($query['post_status'])){
			$query['post_status'][] = 'future';
		}else if($query['post_status']){
			$query['post_status'] = array($query['post_status'], 'future');
		}else{
			$query['post_status'] = array('future');
		}
	}
	return $query ;
}
add_filter( 'wp_link_query_args', 'rw_get_planified_posts'); 

//BO Search by ID
function rw_search_by_id($query){
	$search_id = $query['s'];
	if($search_id && is_numeric($search_id)){
		unset( $query['s'] );
		$query['p'] = $search_id;
	}
	return $query ;
}

add_filter( 'wp_link_query_args', 'rw_search_by_id');

function get_object2_value(){
	$object2 = isset($_POST['obj_2']) ? stripslashes($_POST['obj_2']): '';
	return $object2;

}
add_shortcode("nl_preheader" , "get_object2_value");



function add_v_to_allowed_html($tags, $context){
	if( $context == 'post') $tags['v'] = array('src'=>true, 'style'=>true);
	return $tags;
}
if( is_dev('fix_outlook_v_images_4501') ){
	add_filter( 'wp_kses_allowed_html',  'add_v_to_allowed_html', 10, 2); 
}

function nl_instagram_imgs($atts, $content){
	$instagram_username = get_param_global('instagram_username');
	$instagram_access_token = get_param_global('instagram_access_token');
	$instagram_nbr_imgs = (isset($atts['nb']) ? $atts['nb'] : 3);
	require_once(get_template_directory()."/include/functions/instagram.php");
	if(!empty($instagram_username) && !empty($instagram_access_token)){
		$isg = new instagramPhp($instagram_username,$instagram_access_token);
		$shots = $isg->getUserMedia(array('count'=> $instagram_nbr_imgs));
	} 
	if( !empty($shots->data) && !empty($content) ){  
		$all_content = '';
		foreach($shots->data as $istg){
			$img_url = $istg->link;
			$img_src = $istg->images->low_resolution->url;
			if( !empty($img_url) && !empty($img_src) ){
				$all_content .= str_replace(array('{_nl_insta_img_}', '{_nl_insta_link_}'), array($img_src, $img_url), $content);
			}
		}
		$content = $all_content;
	}
	return $content;
}
add_shortcode('nl_instagram', 'nl_instagram_imgs');
