<?php
function produit_module() {
	$args = array(
		'label' => __('Produits'),
		'singular_label' => __('produit'),
		'public' => true,
		'show_ui' => true,
		'_builtin' => false, // It's a custom post type, not built in
		'_edit_link' => 'post.php?post=%d',
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array("slug" => "produits"),
		'query_var' => "product", // This goes to the WP_Query schema
		'supports' => array('title', 'editor', 'thumbnail', 'excerpt') //titre + zone de texte + champs personnalisés + miniature valeur possible : 'title','editor','author','thumbnail','excerpt'
	);
	register_post_type( 'produit' , $args ); // enregistrement de l'entité projet basé sur les arguments ci-dessus
	add_post_type_support( 'produit', 'custom-fields') ;
	register_taxonomy_for_object_type('product-caregory', 'produit','show_tagcloud=1&hierarchical=true'); // ajout des mots clés pour notre custom post type


	register_taxonomy( 'campaign', 'produit', array(
		'hierarchical' => false,
		'labels' => array(
				'name' => __('Campagnes','campaign'),
				'singular_name' => __('Campagnes','campaign'),
				'menu_name' => __('Campagnes','product-caregory'),
			),
		'public' => true,
		'show_ui' => true,
		'show_tagcloud' => true,
		'query_var' => true,
		'show_admin_column' => true,
	));
}
add_action('init', 'produit_module');

add_filter( 'manage_produit_posts_columns', 'column_heading_produit', 10, 1 );
add_action( 'manage_produit_posts_custom_column', 'column_content_produit', 10, 2 );

add_action( 'manage_produit_posts_custom_column', 'column_content_produit_short_code', 10, 2 );

function column_heading_produit($columns){
	return array_merge( $columns, 
		array( 'produit-clicks' => __( 'Nombre de clics', 'REWORLDMEDIA_TERMS' ),
		'produit-short_code' => __( 'Short_code', 'REWORLDMEDIA_TERMS' )
		)
	);
}

function column_content_produit( $column_name, $post_id ) {
	if ($column_name == 'produit-clicks'){
		$clicks = (int) get_post_meta($post_id, 'clicks', true);

		echo $clicks ;    					
	}
}

function column_content_produit_short_code( $column_name, $post_id ) {
	if ($column_name == 'produit-short_code'){
		
		echo ' [product_url id='. $post_id .']' ;		
	}
}

add_action( 'manage_edit-produit_sortable_columns', 'column_sort_produit', 10, 2 );

function column_sort_produit( $columns ) {
	$columns['produit-clicks']  = 'produit-clicks';
	return $columns;
}


add_filter('views_edit-produit', 'edit_produit_total_clics');
function edit_produit_total_clics($views){
	global $wpdb ;

	$sql = "SELECT sum(pm.meta_value) somme
	FROM {$wpdb->postmeta} pm
	JOIN {$wpdb->posts} p ON (p.ID = pm.post_id)
	WHERE pm.meta_key = 'clicks'
	AND p.post_type = 'produit'
	";
	$somme = $wpdb->get_var($sql);
	$views[] = 'Nombre total de clics: '. $somme;
	return $views ;

}

function show_product($name, $msg, $slug ){
	$produits_array = array() ;
	if($slug != 'trois-suisses'){
		$post_meta = "produits-$slug";
	}else{
		$post_meta = "produits";
	}
	$produits = get_post_meta(get_the_ID(),$post_meta, true) ;
	$show_random = false;

	if(is_array($produits) && count($produits)){
	?>
	<div class="col-xs-12 article-context article-context-produit" >
		<h2 class="default-title-produit">
			<span><?php echo __($msg, REWORLDMEDIA_TERMS ); ?></span> <a class="title-widget <?php echo $slug ; ?>" herf="#"><?php echo $name ?></a>
		</h2>
		<div class="row items-related-post">
			<?php

			//$i=0 ;
			// foreach ($produits_array as $produit) {
			$format =  apply_filters( 'image_format_product', 'smallimage', $slug);
			$s='';
	    	foreach ($produits as $id) {  /*filter produis $name = campaign*/
	    		// if random... we already have products
	    		if ( $show_random){
	    			$produit = $id;
	    		} else {
	    			$produit = get_post($id);
	    		}
				//$i++ ;
	    		$custom = get_post_custom($produit->ID);

				$img = $custom[$format][0] ;
				$price = $custom['price'][0] ;
				$link = home_url() . "/s.php?product_id=".$produit->ID ;
				ob_start();
				include(locate_template('include/templates/list-item-product.php'));
				$s .= ob_get_contents() ;
				ob_end_clean();
			}
			echo $s ;
			?>
	</div>					
	</div>
	<br class="clearfix">
	<?php
	}
}
/**
 * Permet d'afficher la shopping box
 * @param string $slug slug de la campagne
 * @param array $campaign configuration de la campagne
 * @param string $position positionner la shoppingbox à droite ou à gauche en mettant une marge
 * @param string $cat parametre pour le shortcode qui permet de filter les produits à afficher en fonction de $cat du produit
 * @return void afficher la shoppingbox
 */
function show_shoopingbox( $slug, $campaign, $atts = array() ) {
	global $post;
	$campaign_cats = !empty($campaign['categories']) ? $campaign['categories'] : $slug;
	$nb_products_to_show = 3;
	$product_logo = "";
	if(isset($campaign['items_to_show'])) $nb_products_to_show = $campaign['items_to_show'];
	if(isset($campaign['title'])) $product_title = $campaign['title'];

	// if logo is not svg file
	if ( is_array( $campaign['logo'] )
			&& isset( $campaign['logo']['type'] )
			&& $campaign['logo']['type'] == 'svg' ) { // if img is of type svg
		$product_logo = isset( $campaign['logo']['img'] ) ? $campaign['logo']['img'] : '';
		if ( $product_logo )
			$product_logo = '<div class="scaling-svg-container clearfix">' . $product_logo . '</div>';
	} elseif ( isset( $campaign['logo'] ) && ! is_array( $campaign['logo'] ) ) { // if img of type png or jpg
		$product_logo = '<img src="'. get_stylesheet_directory_uri() . $campaign['logo'] .'" title="'. $name .'" alt="'. $name .'" class="img-responsive">';
	}

	if( isset($campaign['cta_btn_text']) ){
		$product_cta_btn = $campaign['cta_btn_text'];
	}else{
		$product_cta_btn = 'En savoir +';
	} 
	$args = array(
		'post_type' => 'produit' ,
		'posts_per_page'   => $nb_products_to_show,
		'orderby' => 'rand',
		'post_status' => 'any',
		'meta_key' => array(),
		'tax_query' => array(
			array(
				'taxonomy' => 'campaign',
				'field' => 'slug',
				'terms' => $slug
			)
		)
	);

	if ( isset( $campaign['orderby'] ) ) {
		$args['orderby'] = $campaign['orderby'];
	}

	if ( isset( $campaign['order'] ) ) {
		$args['order'] = $campaign['order'];
	}

	// 'cat' correspond à la catégorie du produit
	if ( isset( $atts['cat'] ) && ! empty( $atts['cat'] ) ) {
		$args['meta_query'] = array (
			array(
				'key' => 'categoryid',
				'value' => $atts['cat'],
				'compare' => '=',
			)
		);
	}

	$args = apply_filters( 'shopping_box_query_args', $args, $campaign_cats, $slug ); 
	$produits = get_data_from_cache('shoppingbox_'.$slug, 'single', 60*60*24, function() use($args) {
		return get_posts($args);
	});


	if ( is_array( $produits ) && count( $produits ) ) {
		include( locate_template( 'include/templates/default_shoppingbox.php' ) );
	}
}

add_action('show_product', 'show_exposant_products');

function show_exposant_products(){
	global $post;
	$campaigns = get_categories(array('taxonomy' => 'campaign', 'hide_empty' =>false));
	if( count ($campaigns)){
		$shoopingbox_campaign = get_param_global('products_shoppingbox');
		foreach ($campaigns as $campaign) {
			$slug = $campaign->slug;
			if( !empty($shoopingbox_campaign) && $slug == RW_Post::get_slug_post($post->ID) ) {
				show_shoopingbox($slug, $shoopingbox_campaign) ;
				break;
			}
		}
	}
}

function show_widgets_products(){

	global $site_config ;
	$shoopingbox_campaigns = get_param_global('products_shoppingbox');

	$campaigns = get_categories(array('taxonomy' => 'campaign', 'hide_empty' =>false));

	$product_campaigns = get_param_global('product_campaigns',array()) ;
	if( count ($campaigns)){
		foreach ($campaigns as $campaign) {
			$slug = $campaign->slug ;
			if(isset($shoopingbox_campaigns[$slug]) && $shoopingbox_campaign = $shoopingbox_campaigns[$slug]){
				if($shoopingbox_campaign['active']){ 
					show_shoopingbox($slug, $shoopingbox_campaign) ;
				}
			}elseif($product_campaigns  &&in_array($slug, $product_campaigns)){
				$name = $campaign->name ;
				show_product($name, 'Vous aimerez aussi notre sélection', $slug) ;
			}

		}
	}
}
add_action('wp_enqueue_scripts',function(){
	if(is_single()){
		wp_enqueue_script('reworldmedia-product', RW_THEME_DIR_URI.'/assets/javascripts/product.js', array( 'jquery'), CACHE_VERSION_CDN, true );
	}
});

function save_article_meta_box_product($post_id) { 

    if (isset($_POST['article_meta_box_poduct_nonce']) && !wp_verify_nonce($_POST['article_meta_box_poduct_nonce'], basename(__FILE__))) {
    	return $post_id;
    }   
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)   {
    	return $post_id;
    }
          
    // check permissions  
    if (isset($_POST['post_type']) && 'post' == $_POST['post_type']) {  
        if (current_user_can('edit_post', $post_id)) {  
            
            $campaigns = get_categories(array('taxonomy' => 'campaign')); 
            $product_campaigns = get_param_global('product_campaigns',array()) ;
			foreach ($campaigns as $campaign) {
				if(in_array($campaign->slug, $product_campaigns)){
					$slug = $campaign->slug ;
	            	reworld_save_meta($post_id, "produits-{$slug}" );
				}
			}

        } else {
        	return $post_id; 
        }
    }
}  
add_action('save_post', 'save_article_meta_box_product');

function shortcode_products_shoppingbox(){
	ob_start();
	show_widgets_products();
	$html = ob_get_contents() ;
	ob_end_clean();
	return $html;
}
add_shortcode( 'products_shoppingbox', 'shortcode_products_shoppingbox' );