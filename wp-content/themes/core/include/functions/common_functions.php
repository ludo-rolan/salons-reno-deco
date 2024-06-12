<?php 

add_action('end_barretopinfo', function(){
	?>
	<div class="col-sm-12 col-md-4">
		<?php
		$header_partner_menu = apply_filters('get_menu_name', 'header_partner_menu');
		if ( has_nav_menu( $header_partner_menu ) ) {
		      wp_nav_menu( array(
		      	'theme_location' => $header_partner_menu,
		      	'items_wrap'      => '<ul id="%1$s" class="%2$s nav nav-pills partners_links">%3$s</ul>',
		      	) ); 
		} 
		echo do_shortcode('[social_links in_header=true]');
		do_action('after_social_links') ;
		?>
	</div>
	</div><!-- .row -->
	<?php
});




function update_jquery() {
	if( !is_admin()){
		wp_deregister_script('jquery');
		if( is_dev('rendre_jquery_interne_150973956') ){
			wp_register_script('jquery', (RW_THEME_DIR_URI. '/assets/javascripts/jquery-2.2.0.min.js'), false, '2.2.0');
		}else{
			wp_register_script('jquery', ("https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"), false, '2.2.0');
		}

		wp_enqueue_script('jquery');
	}
}
add_action('init', 'update_jquery');

add_action('just_after_thecontent','link_nav_posts');
function link_nav_posts(){
	global $post ;

	$args_prev = array(
		'posts_per_page' => 1,
		'date_query' => array(
			'after' => $post->post_date
			),
		"order" => "ASC"
		); 

	$args_next = array(
		'posts_per_page' => 1,
		'date_query' => array(
			'before' => $post->post_date
			)
		); 

	$current_cat= RW_Category::get_menu_cat_post($post);
	if(!empty($current_cat) && $post->post_type !='plant'){
		$args_prev['category_name']=$current_cat->slug;
		$args_next['category_name']=$current_cat->slug;
	}elseif($post->post_type =='plant'){
		$args_prev['post_type']='plant';
		$args_next['post_type']='plant';
	}

	$post_prev = get_posts($args_prev);
	$post_next = get_posts($args_next);

	echo '<div class="posts_nav"><div class="row">';
	if(isset($post_prev[0]) && $post_prev[0]->ID){
		echo '<div class="nav col-xs-12 col-sm-6 prev pull-left">';
		echo '<div class="nav_wrapper"><div class="v_center"><div class="nav_label">Précedent</div><a href='.get_permalink( $post_prev[0] ).'>'.RW_utils::mini_text_for_lines($post_prev[0]->post_title,30,2).'</a></div></div>';
		echo '</div>';
	}

	if(isset($post_next[0]) && $post_next[0]->ID){
		echo '<div class="nav col-xs-12 col-sm-6 next pull-right">';
		echo '<div class="nav_wrapper"><div class="v_center"><div class="nav_label">Suivant</div><a href='.get_permalink( $post_next[0] ).' >'.RW_Utils::mini_text_for_lines($post_next[0]->post_title,30,2).'</a></div></div>';
		echo '</div>';
	}
	echo'</div></div>';
}


add_filter('the_content', function($content){
	$patternLink ='#(<h[1-6][^>]*>)(((?!(<\/span>|<span>|<\/h[1-6]>)).)*)(<\/h[1-6]>)#ims';
	$replacementLink = '$1 <span>$2</span> $5';
	$content = preg_replace($patternLink, $replacementLink, $content);
	return $content;
}, 10000);

add_filter('current_pagination_nb', 'current_pagination_nb', 10, 1);
function current_pagination_nb($s){
	$s = '';
	return $s;
}


add_filter('get_avatar', function($avatar, $id_or_email, $size, $default, $alt){
	$avatar = ' ';
	return $avatar;
}, 10 , 5);


function msg_page_search($s){
	global $wp_query;
	echo '<div class="bar_result_search">';
		if(have_posts()){
			echo '<h2><span class="count-search">'.$wp_query->found_posts . '</span>' . sprintf( __( 'résultats  pour votre recherche sur &laquo; %s &raquo;', REWORLDMEDIA_TERMS ), '<span class="search_query" >' . $s . '</span>' ) . '</h2>'; 
		}else{
			echo '<h2>'.__('Désolé, il n\'y a pas de résultat correspondant à votre recherche',REWORLDMEDIA_TERMS).'</h2>';
			echo'<h3>'.__('Vous trouverez surement votre bonheur dans nos derniers articles',REWORLDMEDIA_TERMS).'</h3>'; 
		} 
	echo '</div>';
}
add_action('after_header_search','msg_page_search');

add_filter('before_search_results',function(){return '';});

add_filter('empty_search_result','last_posts_');
add_filter('empty_search_result_msg', '__return_empty_string');


add_filter('search_thumb_class', function($s){ return 'col-xs-12 col-sm-5'; });
add_filter('search_caption_class', function($s){ return 'col-xs-12 col-sm-7'; });

add_action('before_result_search','searchform');
function searchform($s){ 
	?>
	<div class="blockSearch">
		<form class="form-inline" action="<?php echo esc_url( home_url( '/' ) ); ?>" id="searchform-recette" method="get" role="search">
		  <div class="form-group search_txt_input">
		    <input type="text" class="form-control" placeholder="<?php _e("Entrez ici votre recherche...", REWORLDMEDIA_TERMS ); ?>" name="s" value="<?php echo $s;?>"/>
		  </div>
		  <div class="form-group form-group-btn">
		  	<button type="submit" class="btn"></button>
		  </div>
		  <div class="submit_search_btn"></div>
		</form>
	</div>
	<?php
}

add_filter('display_most_popular_dossier','popular_dossier_');
function popular_dossier_($p){
	global $wp_query;
	if((isset($wp_query->query['post_type']) && $wp_query->query['post_type'] == 'folder' ) ){
		return true;
	}
	return $p;
}

function last_posts_($msg){
	RW_Hooks::last_posts_($msg);
}

function header_mag_subscription_widget($atts){
	$title_mag=isset($atts['title']) ? $atts['title'] : __('Abonnez-vous', REWORLDMEDIA_TERMS);
	$url_mag=$atts['url_mag'];
	$src_mag=$atts['src'];
	$alt_mag=$atts['alt'];
	$sub_price=$atts['sub_price'];
	
	$content="";
	$content.='<span data-href="'.$url_mag.'" data-target="_blank" class="hidden-xs"> 
		<img class="pull-right sub_media" src="'.$src_mag.'" alt="'.$alt_mag.'"> 
		<div class="sub_widget_text">
			<div class="title">'.$title_mag.'</div>
			<div class="sub_price">'.$sub_price.'</div>
		</div>
	</span>';
	return $content;
}

add_action('init', function(){
	remove_shortcode('headermag');
	add_shortcode('headermag', 'header_mag_subscription_widget');

});


if( is_dev('mobile_nouveau_fonctionnel_diapo_133823477') ){
	add_action('extra_menu_header_name','add_small_logo');
}

if(get_param_global('hide_next_previous_post')){
	remove_action('just_after_thecontent','link_nav_posts');
	remove_action('just_after_thecontent','link_nav_posts_folder');
}

/**
 * menu catégories profondes sur les pages dossier
 */

function bloc_filter($post_type){
    global $wp_query,$wpdb;

    if(isset($wp_query->query['post_type']) && $wp_query->query['post_type'] == $post_type){
        if(RELATED_MAIN_SECTION=='jardin'){
            $cat_slug = 'mon-jardin-ma-maison';
        }else if(RELATED_MAIN_SECTION=='renovation'){
            $cat_slug = 'maison-travaux';
        }

        if($category=rw_get_category_by_slug($cat_slug)) {
            $parent_cat_id = $category->term_id;
            $home = trim(get_home_url(), '/');
            $cache_key = 'block_child_cats' . $cat_slug . '_' . $parent_cat_id;
            if (is_dev('cache_child_cats_139206283')){

                echo_from_cache($cache_key, 'block_child_cats', TIMEOUT_CACHE_CHILD_CATS, function () use ($parent_cat_id, $post_type, $home) {
                    global $wpdb;
                    $sql = "SELECT DISTINCT name,slug FROM " . $wpdb->prefix . "terms wt
                JOIN " . $wpdb->prefix . "term_taxonomy t ON wt.term_id = t.term_id
                JOIN " . $wpdb->prefix . "term_relationships r ON r.term_taxonomy_id = t.term_taxonomy_id
                JOIN " . $wpdb->prefix . "posts p ON r.object_id = p.ID
                AND p.post_type = '" . $post_type . "'";
                    if ($parent_cat_id) {
                        $sql = $sql . " AND t.parent = " . $parent_cat_id;
                    }

                    $list_cat_ = $wpdb->get_results($sql);
                    $rubrique_cls = $list_cat_ ? 'has_childs' : '';
                    ?>
                    <div class="rubrique_info <?php echo $rubrique_cls; ?>">
                    <div class="cat_name">
                        <span class="sep left"></span>
                        <?php echo apply_filters('menu_child_cats_h1', '<h1 class="name"> Dossiers </h1>', $post_type); ?>
                        <span class="sep right"></span>
                    </div>
                    <div class="cat_excerpt"></div>
                    <div class="child_cats">
                    <?php if ($list_cat_) { ?>
                        <span class="sep left"></span>
                        <div class="filter">
                            <ul class="list-unstyle list-inline all_filters">
                                <li>
                                    <div class="dd_filter list_title"><span class="filter_label">Par thème</span><span
                                                class="down_arrow"></span></div>
                                    <ul class="list-unstyle list-inline">
                                        <?php
                                        foreach ($list_cat_ as $cat) {
                                            if ($post_type == 'folder') {
                                                echo '<li><a href="' . $home . '/dossiers/' . $cat->slug . '" >' . $cat->name . '</a></li>';
                                            } else if (RELATED_MAIN_SECTION == 'jardin') {
                                                echo '<li><a href="' . $home . '/plantes/' . $cat->slug . '" >' . $cat->name . '</a></li>';
                                            }
                                        }
                                        ?>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <span class="sep right"></span>
                    <?php }
                });
        }else {
                $sql = "SELECT DISTINCT name,slug FROM " . $wpdb->prefix . "terms wt
                JOIN " . $wpdb->prefix . "term_taxonomy t ON wt.term_id = t.term_id
                JOIN " . $wpdb->prefix . "term_relationships r ON r.term_taxonomy_id = t.term_taxonomy_id
                JOIN " . $wpdb->prefix . "posts p ON r.object_id = p.ID
                AND p.post_type = '" . $post_type . "'";
                if ($parent_cat_id) {
                    $sql = $sql . " AND t.parent = " . $parent_cat_id;
                }

                $list_cat_ = $wpdb->get_results($sql);
                $rubrique_cls = $list_cat_ ? 'has_childs' : '';
                ?>
                <div class="rubrique_info <?php echo $rubrique_cls; ?>">
                <div class="cat_name">
                    <span class="sep left"></span>
                    <?php echo apply_filters('menu_child_cats_h1'); ?>
                    <span class="sep right"></span>
                </div>
                <div class="cat_excerpt"></div>
                <div class="child_cats">
                <?php if ($list_cat_) { ?>
                    <span class="sep left"></span>
                    <div class="filter">
                        <ul class="list-unstyle list-inline all_filters">
                            <li>
                                <div class="dd_filter list_title"><span class="filter_label">Par thème</span><span
                                            class="down_arrow"></span></div>
                                <ul class="list-unstyle list-inline">
                                    <?php
                                    foreach ($list_cat_ as $cat) {
                                        if ($post_type == 'folder') {
                                            echo '<li><a href="' . $home . '/dossiers/' . $cat->slug . '" >' . $cat->name . '</a></li>';
                                        } else if (RELATED_MAIN_SECTION == 'jardin') {
                                            echo '<li><a href="' . $home . '/plantes/' . $cat->slug . '" >' . $cat->name . '</a></li>';
                                        }
                                    }
                                    ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <span class="sep right"></span>
                <?php }
            }
            }else{
                echo"<hr/>";
            }?>
            </div>
        </div>
        <?php
    }
}

// Différenciation template
if( is_dev('change_template_by_resolution_156188083') ){
	$site_config['change_template_by_resolution'] = true ;
}
add_action('wp', function(){
	if( is_dev('modifs_article_diapo_160919454') || (get_param_global('change_template_by_resolution') && !has_diapo_monetisation()) ){
		move_post_title_block();
		if( !rw_is_mobile() ){
			add_filter( 'ul_menu_header_v2', 'small_nav_menu_logo');
		}

		add_filter( 'body_class', 'body_class_change_template_by_resolution' );
		function body_class_change_template_by_resolution( $classes ) {

		        $classes[] = 'template_by_resolution';
		    return $classes;
		}
	}
}, 2000);
function small_nav_menu_logo($ul_menu){
		$blog_title = get_bloginfo('title');
		$logo = apply_filters('nav_menu_logo', STYLESHEET_DIR_URI .'/renovation/assets/images-v3/main-logo-mobile.png');
		$ul_menu = '<li class="nav_logo">
					<a href="'. esc_url(home_url( '/' )) .'">
						<img src="'. $logo .'" title="'. $blog_title .'" alt="'. $blog_title .'"/>
					</a>
				</li>'.$ul_menu;
		return $ul_menu;
}

/**
* Move title block elements (#156188083)
*
* @return void
*/
function move_post_title_block(){
	if( is_single() ){
		global $has_gallery;
		remove_action('just_after_post_v2', 'add_breadcrumb_post_v2');
		remove_action( 'before_id_container', 'add_megabanner_top' );
		remove_action('single_intro_excerpt','show_single_intro_excerpt');
		remove_action('single_after_excerpt', 'do_show_gallery');
		add_action('init_template_single_article', function(){
			remove_action('top_intro_gallery', 'top_intro_article') ;
			remove_action('top_intro_article', 'top_intro_article') ;
		}, 22);

		add_action('seo_single_before_title', 'add_breadcrumb_post_v2');
		add_action('before_content_page_single', 'full_width_article_title');
		if(rw_is_mobile() && is_dev('positionnement_chapo_article_4434')){
			add_action('before_content_page_single', 'article_intro_excerpt', 30);
		}
		else{
			add_action('after_top_img_single', 'show_single_intro_excerpt', 30);
		}
		if( is_dev('modifs_article_diapo_160919454') ){
			add_action( 'single_after_author_info', 'add_megabanner_top', 20);
		}else{
			add_action( 'after_intro_single', 'add_megabanner_top', 5);
		}
		if( $has_gallery &&  !get_param_global('no_top_intro_gallery') ){ 
			add_action('top_intro_gallery', 'do_show_gallery');
		}else{
			add_action('top_intro_article', 'do_show_gallery');
		}
	}
}

/**
* Full width title block
*
* @return void
*/
function full_width_article_title(){
	global $has_gallery;
	$modifs_article_diapo_160919454 = is_dev('modifs_article_diapo_160919454');
	if( $modifs_article_diapo_160919454 || !$has_gallery ) echo '<div class="title-article">';
	top_intro_article();
	if( $modifs_article_diapo_160919454 ||  !$has_gallery ) echo '</div>';
}
//\ Différenciation template



/**
 * Add div to diapo legend excerpt
 *
 * @return String   
 */
function add_div_to_legend_excerpt($s){
	$s = '<div class="legende_excerpt">'. $s .'</div>';
	return $s;
}
if( is_dev('modifs_article_diapo_160919454') ){
	add_filter('diapo_legende_excerpt', 'add_div_to_legend_excerpt');
}

add_action( 'create_category', 'save_meta_first_level_parent_cat' );
add_action( 'edited_category', 'save_meta_first_level_parent_cat');

function save_meta_first_level_parent_cat($term_id ){
	global $section_to_slug ;
	$first_level_cat = '';

	$cat_parents = get_category_parents($term_id, false, '/', true);
	$array_cat_parents = explode('/', $cat_parents);

	if( !empty($array_cat_parents) && !empty($section_to_slug) ){
		$cats = array_intersect($array_cat_parents, $section_to_slug);
		if( !empty($cats) ){
			$first_level_cat = $cats[0];
		}
	}

    if( !empty($first_level_cat) ){
		return update_term_meta($term_id, "top_level_cat", $first_level_cat);
    }

    return false;
}
