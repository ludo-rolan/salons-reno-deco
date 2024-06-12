<?php
/*Filters*/
add_filter('carousel-gallery-title', 'split_title', 1, 2);
add_filter( 'last_post_args_by_menu', 'update_args_homepage', 100, 2 );
add_filter( 'block_post_push' , 'hide_post_push_folder', 10,2);
add_filter('block_push_template', 'get_block_push_template', 100, 2);
add_filter('title_home_h2', 'set_title_home_h2', 100, 1);
add_filter('the_excerpt', 'excerpt_seo');
add_filter('insert_script_microdata', 'insert_script_microdata');
add_filter('breadcrumb_rewo', 'add_accueil_in_breadcrumb') ;
add_filter('query_vars', 'salon_query_vars');
add_filter('frontpage_template','salon_page_template', 1000 );
add_filter('body_class', 'custom_pages_cls');
add_filter('args_popular','last_post_',10,2);
add_filter('widget_display_callback','change_title_widget_',10,3);
add_filter('option_posts_per_page','real_number_posts_archive');
add_filter('menu_child_cats_h1', 'add_folder_title', 10, 2);
add_filter('pagination_js',function(){ return true;});
add_filter('comments_title',  'add_comment_title');
add_filter('classes_megabanner_top',  '__return_false');
add_filter('partner_filter_dossier_france','add_bliink_to_list_partner');
add_filter('archive_has_diaporama_accueil',  '__return_false');
add_filter('archive_carousel_visibility', 'conseils_expert_blocs_visibility', 10, 2);
add_filter('exposition_physique_visibility', 'conseils_expert_blocs_visibility', 10, 2);
add_filter('conseils_experts_visibility', 'exposition_virtuelle_blocs_visibility', 10, 2);
add_filter('conseils_experts_by_cat', 'exposition_virtuelle_blocs_visibility', 10, 2);
add_filter('conseils_experts_cat', 'conseils_experts_edito_cat', 10, 2);
add_filter('conseils_experts_read_more', 'conseils_experts_readmore_cat_link', 10, 2);
add_filter('ga_api_id','set_ga_api_id');
add_filter('display_most_popular_exposant','display_most_popular_exposant_statue');
add_filter('exclude_cats_last_post', 'exclude_cats_last_posts');
/* Actions */
add_action('wp_enqueue_scripts', 'core_enqueue_css' );
add_action('wp_head', 'add_meta_name_responsive',0);
add_action('wp_head' ,'RW_Hooks::call_google_analytic', 1);
add_action('wp_head','print_site_config_js', 2);
add_action( 'widgets_init', 'widgets_init' );
add_action('custom_block_push','block_push',10,2);
add_action('liste_plus_artiles_rubriques', 'mf_block_overlay', 10, 2);
add_action('before_wp_footer','before_wp_footer_',10);
add_action('display_footer','get_nav_footer',8);
add_action('wp', 'post_intro');
add_action( 'before_id_container' , 'add_megabanner_top' );
add_action('seo_single','metas_seo',10,1);
add_action ('after_thecontent', 'fix_onscroll_social',100);
add_action( 'init', 'RW_Hooks::reworldmedia_image_sizes' );
add_action('admin_enqueue_scripts', 'RW_Hooks::add_datepicker');
add_action( 'rw_result_search', 'RW_Hooks::result_search' );
add_action('init', 'salon_rewirte_rules');
add_action('cat_title', 'category_title');
add_action( 'pre_get_posts','set_subcategory_posts_per_page');
add_action('wp_footer','add_ga_event_popular_shortcode');
add_action('top_popular_post_types','add_post_type_ga_post_popular');

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/* Functions */

function print_site_config_js(){ 
    global $site_config_js, $site_config, $devs ; 
    if(is_array($devs) && count($devs)){
        foreach ($devs as $key => $value) {
            if(is_dev($key)){
                $site_config_js['devs'][$key] = true ;
            }else{
                $site_config_js['devs'][$key] = false ;
            }
        }
    }
    echo '<script type="text/javascript"> site_config_js='. json_encode( $site_config_js ) .' </script> ' ;
    do_action('after_print_site_config_js') ;
}

function split_title( $text, $post_id ) {
    $text = trim($text);
    $text = str_replace('&nbsp;&raquo;', '"', $text);
    $text = str_replace('&laquo;&nbsp;', '"', $text);
    $text = html_entity_decode($text, ENT_QUOTES, "utf-8");
    $title_highlight = get_post_meta($post_id , 'title_highlight' , true);
    if($title_highlight){
        $title_highlight = str_replace('&nbsp;&raquo;', '"', $title_highlight);
        $title_highlight = str_replace('&laquo;&nbsp;', '"', $title_highlight);
        $title_highlight = html_entity_decode($title_highlight, ENT_QUOTES, "utf-8");
    }
    
    if ( $title_highlight && strpos( $text, $title_highlight ) !== false){
        $text = mini_text_for_lines($text, 36, 3);
        // be sure that the highlighted is included
        if(strpos( $text, $title_highlight ) !== false){
            return '<strong>'.str_replace($title_highlight , '</strong><em>'.$title_highlight , $text ).'</em>';
        }
    }

    if($title_highlight){
        $text =  $title_highlight ;
    }

    if(strlen($text) >=36){
        $strong = RW_Utils::mini_text($text, 32, '');
        $em = substr($text, strlen($strong));
        $em = RW_Utils::mini_text_for_lines($em, 36,2);
        return "<strong>".$strong . "</strong><em>" . $em."</em>";
    }else{
        $words = explode(' ', $text );
        $count = count($words);
        $half = 1 + (int)$count/2 ;
        if (isset($words[$half]) && strlen($words[$half])==1)
            $half+=1;
        array_splice($words, $half , 0, "</strong><em>");
        return "<strong>".implode( ' ' , $words )."</em>";
    }
}

function widgets_init() {
    $bootstrap_class_widget="col-xs-12 col-sm-6 col-md-12 col-lg-12 ms-item pull-left";
    $bootstrap_class_widget_img_right = $bootstrap_class_widget." pull-right ";
    
    register_sidebar(array(
        'name' => __( 'Main Sidebar' ),
        'id' => 'sidebar-1',
        'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets' ),
        'before_widget' => '<aside id="%1$s" class="'.$bootstrap_class_widget.'widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="widget-title"><div>',
        'after_title' => '</div></div>',
    ));
    register_sidebar(array(
        'name' => __( 'Main Sidebar Mobile' ),
        'id' => 'sidebar-mobile',
        'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets' ),
        'before_widget' => '<aside id="%1$s" class="'.$bootstrap_class_widget.'widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="widget-title"><div>',
        'after_title' => '</div></div>',
    ));

    register_sidebar(array(
        'name' => __( 'Header Habillage' ),
        'id' => 'sidebar-header-pub',
        'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets' ),
        'before_widget' => '<div id="%1$s" class="'.$bootstrap_class_widget.'widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<div class="widget-title"><div>',
        'after_title' => '</div></div>',
    ));

    register_sidebar(array(
        'name' => __( 'Après la balise BODY' ),
        'id' => 'sidebar-after-body',
        'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets' ),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));

    register_sidebar(array(
        'name' => __( 'Footer Pub' ),
        'id' => 'footer-pub',
        'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets' ),
        'before_widget' => '<aside id="%1$s" class="'.$bootstrap_class_widget.'widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="widget-title"><div>',
        'after_title' => '</div></div>',
    )); 

    register_sidebar(array(
        'name' => __( 'A ne pas manquer (Popin)'),
        'id' => 'sidebar_popin_a_ne_pas_manquer',
        'description' => __( 'Popin appear before the footer'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));

    register_sidebar(array(
        'name' => __( 'Footer' ),
        'id' => 'footer',
        'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets' ),
        'before_widget' => '<div id="%1$s" class="col-xs-12 col-sm-6 col-md-4 %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<div class="widget-title" ><div>',
        'after_title' => '</div></div>',
    ));

    register_sidebar(array(
        'name' => __( 'Header Megabanner Top' ),
        'id' => 'sidebar-header-megabanner-top',
        'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets' ),
        'before_widget' => '<aside id="%1$s" class="'.apply_filters('classes_megabanner_top','col-xs-12 col-md-12 col-lg-12 ms-item widget ').'%2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="widget-title"><div>',
        'after_title' => '</div></div>',
    ));

    register_sidebar(array(
        'name' => __( 'Header img right' ),
        'id' => 'sidebar-header',
        'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets' ),
        'before_widget' => '<aside id="%1$s" class="'.$bootstrap_class_widget_img_right.'widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="widget-title"><div>',
        'after_title' => '</div></div>',
    ));

    register_sidebar(array(
        'name' => __( 'Header logo right' ),
        'id' => 'sidebar-header-right',
        'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets' ),
        'before_widget' => '<aside id="%1$s" class="widget-logo-right %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="widget-title"><div>',
        'after_title' => '</div></div>',
    ));     

}

function core_enqueue_css(){
    global $wp_query;

    $css_file = 'all';
    if( is_wp_home() ){
        $page_name = get_query_var('page_name');
        if( $page_name == 'plan_salon' ){
            $css_file =  "plan_salon";
        }else{
            $css_file =  "home";
        }
    }else if( is_single() ){
        if( RW_Post::page_has_gallery() ){
            $css_file = "single_gallery";
        }else{
            $css_file = "single";
        }
        if( !wp_is_mobile() ){
            wp_enqueue_script('share_js',RW_THEME_DIR_URI.'/assets/javascripts/share.min.js', array('jquery'), '1', true);
            wp_enqueue_script('fix_social_widget_js', RW_THEME_DIR_URI.'/assets/javascripts/fix_social_widget.min.js', array('jquery'), '1', true);
        }
    }else if( is_page() ){
        $css_file =  "page";
    }else if( is_search() ){
        $css_file =  "search";
    }else if( is_category() || is_archive() ){
        $css_file =  "rubrique";
    }
    if( wp_is_mobile() ) $css_file .= '_mobile';

    $global_css = wp_is_mobile() ? 'global_mobile.css' : 'global.css';
    wp_enqueue_style('global_css', STYLESHEET_DIR_URI.'/assets/css/'.$global_css, array(), CACHE_VERSION_CDN);
    wp_enqueue_style('page_css', STYLESHEET_DIR_URI.'/assets/css/'.$css_file.'.css', array('global_css'), CACHE_VERSION_CDN);

    if( $wp_query->query_vars['post_type'] == 'exposant' || is_singular('exposant') ){
        wp_enqueue_style( 'exposant_css', STYLESHEET_DIR_URI.'/assets/css/exposant.css', array('reworldmedia-style', 'page_css'), CACHE_VERSION_CDN  );
    }

    wp_enqueue_script('bootstrap_js', RW_THEME_DIR_URI.'/assets/javascripts/bootstrap.min.js', array('jquery'), '3.3.5', true);
    wp_enqueue_script('ismobile_js', RW_THEME_DIR_URI.'/assets/javascripts/ismobile.js', array('jquery'), '1', false);

    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' );
    if (file_exists(STYLESHEET_DIR.'/assets/javascripts/main.js')) {
        wp_enqueue_script('theme_main',STYLESHEET_DIR_URI.'/assets/javascripts/main.js',['jquery','reworldmedia-shave'],CACHE_VERSION_CDN,true);
    }
}


function update_args_homepage ($args = array(), $menu_item = null) {
    if(isset($menu_item->object) && $menu_item->object == 'category' && is_home()) {
        $dossier_cat = RW_Category::rw_get_category_by_slug('dossier'); 
        if(!empty($dossier_cat) && $dossier_cat->term_id == $menu_item->object_id){
            $args['post_type'] = 'folder';
            $args['post__not_in'] = array();
        }
    }
    return $args;
}

function hide_post_push_folder($condition,$category_id){
    $dossier_cat = get_category_by_slug('dossier');
    if(!empty($dossier_cat) && $category_id == $dossier_cat->term_id){
        return false;
    }
    return $condition;
}

function block_push($menu_items,$bloc){
    $bloc = apply_filters('block_push_data', $bloc);
    $template = apply_filters('block_push_template', 'include/templates/block_push_v3.php', $bloc);
    include(locate_template($template));
}

function get_block_push_template($template, $bloc){
    $dossier_cat = RW_Category::rw_get_category_by_slug('dossier'); 
    if($dossier_cat && isset($bloc['category']) && $bloc['category'] == $dossier_cat->term_id){
        $template = 'include/templates/block_push_dossier.php';
    }
    return $template;
}

function set_title_home_h2($title){
    if($title == 'Dossier'){
        $title = 'Dossiers';
    }
    return $title;
}

function mf_block_overlay($i, $last_posts){ 
    if($i == floor(count($last_posts)/2) ){
        $sidebar = 'sidebar-apres-rubriques' ;
        $sidebar = apply_filters('filter_all_sidebar',$sidebar ,100);
        if (is_active_sidebar($sidebar)) {
            dynamic_sidebar($sidebar);
        }
    }
}

function before_wp_footer_(){
    dynamic_sidebar('sidebar_popin_a_ne_pas_manquer');
}

function get_nav_footer(){
    //TIMEOUT_CACHE_MENU_ITEMS
    $key_cache = 'nav_footer';
    if(rw_is_mobile()){
        $key_cache .= "_m";
    }else{
        $key_cache .= "_d";
    }
    if(is_category()){
        $key_cache .= '_cat_'.RW_Category::get_top_parent_category(get_queried_object());
    }else if(is_single()){
        $cat = RW_Category::get_menu_cat_post();
        $key_cache .= '_cat_'.RW_Category::get_top_parent_category($cat);
    }
    $key_cache = apply_filters('key_cache_nav_footer', $key_cache);
    echo_from_cache(  $key_cache , 'nav_footer' , TIMEOUT_CACHE_MENU_ITEMS, function() {
        load_template( locate_template('include/templates/template_footer.php')) ;
        $nav_header = ob_get_contents();
    }); 
}

function post_intro(){
    if( is_single() ){
        add_action('top_intro_article', 'top_intro_article');
        add_action('just_after_post_v2', 'add_post_intro');
    }
}

function add_post_intro(){
    echo RW_Utils::breadcrumb();
}

function add_megabanner_top(){
    ?>
    <div class="clearfix"></div>
    <div id="megabanner_top">
        <?php
        $sidebar = apply_filters('filter_all_sidebar', 'sidebar-header-megabanner-top') ;
        if (is_active_sidebar($sidebar)) { 
            dynamic_sidebar($sidebar); 
        }   
        ?>
    </div>
    <?php
}

function get_the_menu_footer_pages(){
    $menu_id = apply_filters('get_menu_name', 'pages_footer', 'pages_footer');
    // Setup cache menu
    $key_cache = $menu_id;
    if(TIMEOUT_CACHE_MENU_FOOTER > 0) {
        if ( $cache = wp_cache_get( $key_cache, 'menu_footer_pages' ) ){
            return $cache;
        }
    }

    $menu_items = wp_get_nav_menu_items($menu_id);  
        
    $html_ul = '<ul class="menu-footer list-inline">' ;
    if (is_array($menu_items)){
        foreach ($menu_items as $menu_item){
            if($menu_item->menu_item_parent == 0){
                $html_ul .='<li class="'. (isset($menu_item->classes)? implode(" ", $menu_item->classes):"") .' ' .' menu-item menu-item-type-post_type menu-item-object-page menu-item-'.$menu_item->ID.'">
                        <a href="'.$menu_item->url.'">'.$menu_item->title.'</a>
                    </li>' ;
            }       
        }                   
    }
    $html_ul .='</ul>';

    if(TIMEOUT_CACHE_MENU_FOOTER > 0) {
        wp_cache_set($key_cache, $html_ul , 'menu_footer_pages' , TIMEOUT_CACHE_MENU_FOOTER );
    }
    return $html_ul;
}

if(!function_exists('convert_menu_mobile')):
function convert_menu_mobile(){
    $menu_id = apply_filters('get_menu_name', 'menu_top_pages', 'menu_top_pages');
    $menu_items = wp_get_nav_menu_items($menu_id);  
    $html='';
    if(!get_param_global('hide_menu_mobile')){
        $html .='<div class="dropdown pull-right visible-xs visible-sm">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.__('Nos Sites' ).' <b class="caret"></b></a>
              <ul class="dropdown-menu">';
        if($menu_items) {
            foreach ($menu_items as &$menu_item){
                $html .='<li class="" id="menu-item-'.$menu_item->ID.'">' ."\n";
                $html .='<a href="'.$menu_item->url.'">'.$menu_item->title.'</a>' . "\n";
                $html .='</li>';
            }
        }
        $html .='</ul></div>';
    }
    return $html;
}
endif;

function add_meta_name_responsive() {
    echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
}

add_action('after_header_logo', function(){
    ?>
    <div class="col-mag pull-left">
        <?php
            $sidebar = apply_filters('filter_all_sidebar', 'sidebar-header');
            if (is_active_sidebar($sidebar)) {
                dynamic_sidebar($sidebar);
            }
        ?>
    </div>
    <?php 

    $sidebar_right = 'sidebar-header-right';
    if (is_active_sidebar($sidebar_right)) {
        dynamic_sidebar($sidebar_right);
    }

});

function excerpt_seo( $excerpt ) {
    if(is_single() && has_excerpt()){
        $excerpt = str_replace( '<p>', '<p itemprop="description">', $excerpt );
    }
    return $excerpt;
}

function metas_seo($datas) {
    $title = isset($datas["title"]) ? $datas["title"] : '';
    $author = isset($datas["author"]) ? $datas["author"] : "";
    $sharedcount=isset($datas["sharedcount"]) ? $datas["sharedcount"] : array('Twitter' => '', 'Facebook' => '', 'GooglePlusOne' => '');
    $date_single = isset($datas["date_single"]) ? $datas["date_single"] : "";
    $date_update_single = isset($datas["date_update_single"]) ? $datas["date_update_single"] : "";
    $link = isset($datas["link"]) ? $datas["link"] : "";

    $sharedcount_twitter = isset($sharedcount["Twitter"]) ? $sharedcount["Twitter"] : 0; 
    $sharedcount_facebook = isset($sharedcount["Facebook"]) ? $sharedcount["Facebook"]["like_count"] : 0; 
    $sharedcount_GooglePlusOne = isset($sharedcount["GooglePlusOne"]) ? $sharedcount["GooglePlusOne"] : 0; 
    $thumbnail_url = isset($datas["thumbnail_url"]) ? $datas["thumbnail_url"] : "";
    $image = isset($datas["image"]) ? $datas["image"] : "";
    echo '<meta itemprop="name" content="'.$title.'" />';
    echo '<meta itemprop="interactionCount" content="UserTweets:'.$sharedcount_twitter.'"/>';
    echo '<meta itemprop="interactionCount" content="UserComments:'.get_comments_number().'"/>';
    echo '<meta itemprop="interactionCount" content="UserLikes:'.$sharedcount_facebook.'"/>';
    echo '<meta itemprop="interactionCount" content="UserPlusOnes:'.$sharedcount_GooglePlusOne.'"/>';
    echo '<!--  / Interactions  -->';
    echo '<meta itemprop="keywords" content="'.RW_Post::get_meta_tag().'"/>';
    
    echo '<meta itemprop="contentLocation" content="'.RW_Utils::get_locations() .'"/>';
    echo '<meta itemprop="discussionUrl" content="'.$link.'#comments"/>';
    echo '<meta itemprop="thumbnailUrl" content="'.$thumbnail_url.'" />';
}


function fix_onscroll_social(){
    global $is_folder;
    if(!$is_folder && !wp_is_mobile()){
        echo do_shortcode("[simple_addthis_single]");
    }
}

function add_accueil_in_breadcrumb ($breadcrumb){
    $breadcrumb = '<li > <a class="home" href="'. home_url() .'" >'. __('Accueil' ) .'</a> </li>' . $breadcrumb  ;
    return $breadcrumb;
}

function insert_script_microdata($return){ 
    global $wp, $post, $wp_query;
    $items = array();  
    if (is_single()) {  
        $items = RW_Category::get_categories_from_url(get_permalink()); 
    }else if (is_category()) {  
        $items = RW_Category::get_categories_from_url(home_url(add_query_arg(array(),$wp->request)));    
    }   
    
    $json_items = '{
            "@type": "ListItem",
            "position": 1,
            "item": {
                "@id": "'.get_site_url().'",
                "url": "'.get_site_url().'",
                "name": "'.get_bloginfo().'"
                }
            },';
    $i = 2; 
    if(!empty($items)){
        foreach ($items as $item) {  
            $cat_id = $item->term_id;
            $json_items .= ' { 
                "@type": "ListItem",
                "position": '.$i.',
                "item": {
                   "@id": "'. get_category_link($cat_id) .'",
                   "url": "'. get_category_link($cat_id) .'",
                   "name": "'.get_cat_name($cat_id).'"
                   } 
                },';
            $i++;
        }
    }
    if( is_single() ){
        $post_url = get_permalink();
        $post_title = get_the_title();
        if( !empty($post_url) && !empty($post_title) ){
            $json_items .= ' { 
                "@type": "ListItem",
                "position": '.$i.',
                "item": {
                    "@id": "'. $post_url .'",
                    "url": "'. $post_url .'",
                    "name": "'. $post_title .'"
                    } 
                },';
        }

        $json_items = rtrim($json_items,','); 

        $return .=  '<script type="application/ld+json"> {
                "@context": "http://schema.org",
                "@type": "BreadcrumbList",
                "itemListElement": [ '
                    . $json_items .'
                ] 
            } 
            </script>   ';

    }

    return $return; 
     
}





function salon_rewirte_rules() {
    add_rewrite_rule('exposition_physique', 'index.php?page_name=exposition_physique', 'top');
    add_rewrite_rule('plan_salon/page/?([0-9]{1,})/?$', 'index.php?page_name=plan_salon&paged=$matches[1]', 'top');
    add_rewrite_rule('plan_salon', 'index.php?page_name=plan_salon', 'top');

}

function salon_query_vars($query_vars) {
    $query_vars[] = 'page_name';
    return $query_vars;
}

function salon_page_template($template){
    $page_name = get_query_var('page_name');
    if( $page_name == 'exposition_physique' ){
        return locate_template('include/templates/exposition_physique.php');
    } else if( $page_name == 'plan_salon' ){
        return locate_template('include/templates/plan_salon.php');
    }
    return $template;
}


function custom_pages_cls($cls){
    $page_name = get_query_var('page_name');
    if( is_home() && !empty($page_name) ){
        $cls[] = 'custom_page';
        $cls[] = $page_name;
    }
    return $cls;
}

// main sidebar mobile filter

add_filter('main_sidebar','edit_main_sidebar');

function edit_main_sidebar($sidebar){
    if (rw_is_mobile()){
        $sidebar = 'sidebar-mobile';
    }
    return $sidebar;
}

function category_title($current_cat){
    if($current_cat->slug == 'videos-'.RELATED_MAIN_SECTION){ ?>
        <h2 class="cat_big_title"><span>Dernières vidéos</span></h2>
        <?php
    }else if($current_cat->slug=='actualites'){?>
        <h2 class="cat_big_title"><span>Dernières <?php echo $current_cat->name; ?></span></h2>
        <?php
    }else{ ?>
        <h2 class="cat_big_title"><span>Dernières actualités <?php  echo apply_filters('the_custom_archive_title_addon',$current_cat->name); ?></span></h2>
        <?php
    }
}

function last_post_($args,$n){
    global $has_gallery;
    if($has_gallery){
        $args=array('post_type'=>'folder','posts_per_page'=>$n);
        return $args;
    }
    return $args;
}

function change_title_widget_($instance, $widget, $args){
    global $post,$has_gallery;
    if(is_single() && !$has_gallery && (strpos($instance['text'],'last_post="true"') !== false)){
        $instance['title'] = __('Top articles' , REWORLDMEDIA_TERMS);
    }elseif ($has_gallery && (strpos($instance['text'],'last_post="true"') !== false)){
        $instance['title'] = __('Nos dossiers' , REWORLDMEDIA_TERMS);
    }
    return $instance;
}

/**
 *  Title to show in the menu for child cats (catégories profondes)
 */
function add_folder_title($h1, $post_type){
    return '<h1 class="name"> Dossiers </h1>';
}

function add_comment_title($title){
    $title = '<div class="default-title"><h2> Commentaires </h2></div>';
    return $title;
}

function real_number_posts_archive($n){
    if(is_category()){
        global $wp_query, $real_number_posts_archive;
        $category_slug = $wp_query->query_vars['category_name'];
        $current_cat = RW_Category::rw_get_category_by_slug($category_slug);
        $mt_cat = RW_Category::rw_get_category_by_slug('maison-travaux');
        $salon_mt_cat= RW_Category::rw_get_category_by_slug('salonmaisonettravaux');
        if($current_cat->category_parent == $mt_cat->term_id){
            $n=6;
        }
        elseif($current_cat->term_id== $salon_mt_cat->term_id){
            $n=24;
        }
        else{
            $n=4;
        }
        $real_number_posts_archive = $n;
    }
    return $n;
}

include(locate_template('include/functions/common_functions.php'));

function popular_questions_forum($attrs){
    $html = '';
    global $wpdb;
    if(is_home()&& !rw_is_mobile()){
        $questions = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'sabai_content_post WHERE post_status="published" and post_entity_bundle_type="questions" ORDER BY post_views DESC Limit 3' );
        if(!empty($questions)){
            $html = '<div class="widget-title"><a href="'.get_home_url().'/questions">Vos questions</a></div>';
            $html .= '<ul class="menu-footer list-inline">';
            foreach ($questions as $question) {
                $html .= '<li><a href="'.get_home_url().'/questions/question/'.$question->post_slug.'" class="three_dots">'.$question->post_title.'</a></li>';
            }
            $html .= '</ul>';
        }
    }
    return $html;
}

add_shortcode('questions_les_plus_vues','popular_questions_forum');

function add_bliink_to_list_partner($partner){

    array_push($partner, 'bliink_article_desktop','bliink_article_mobile','bliink_diapo_desktop','bliink_diapo_mobile');

    return $partner;
}

function orderby_modified( $query ) {
    if ( isset($query->query['post_type']) && $query->query['post_type'] == 'folder' && get_param_global('folders_orderby_date_modified') == true && $query->is_main_query()  ) {
        $query->set( 'orderby' , 'modified');
    }
}
add_action( 'pre_get_posts', 'orderby_modified' );


function modify_parent_folder($post_id, $post) {
    remove_action( 'save_post', 'modify_parent_folder' ,100);
    // Update the parent folder
    if (!empty($post->post_parent) && get_post_type( $post->post_parent ) == "folder"){
        $parent_folder = array(
            'ID'           => $post->post_parent
        );
        // Update the parent folder into the database
        wp_update_post( $parent_folder);
    }
}
add_action( 'save_post', 'modify_parent_folder',100,2);


add_action('after_single','get_last_deco_posts');
function get_last_deco_posts(){
    $args = array(
        'posts_per_page'	=> 3,
        'post_status'		=> 'publish',
        'orderby'			=> 'date',
        'order'				=> 'DESC',
        'meta_key'			=> 'section_permalink',
        'meta_value'		=> 'deco',
        'no_force_category'	=> true,
    );
    $content = "";
    $posts = get_posts($args);
    $_block_title = 'La déco sur Le Journal de la Maison';
    if( !empty($posts))
    {
        require(locate_template("renovation/include/templates/block_posts_related.php"));
    }
    echo $content;
}

add_action('before_last_bloc', 'add_title_recents_posts');

function add_title_recents_posts(){
    ?>
    <h2 class="default-title">
        <strong class="default-title-prefix">Articles</strong> récents
    </h2>
    <?php
}

add_filter('more_category_text', function ( $return, $title = '', $cat_slug ) {
    if($cat_slug == 'conseils-experts') {
        $return = 'Voir tous les conseils';
    } else {
        $return = 'Voir tous les exposants';
    }
    return $return;
}, 10, 3);


add_filter('diaporama_accueil_rubrique', '__return_false');
add_filter('archive_has_diaporama_accueil', '__return_false');
add_filter('count_posts_block_category', function($nbr){ return 4; });

add_action('side-bar_before-footer', 'before_footer_sidebar');
function before_footer_sidebar(){
    echo do_shortcode('[logo]');
}

add_action('side-bar_after-footer', 'after_footer_sidebar');
function after_footer_sidebar(){
    echo do_shortcode('[menufooter]');
}

add_filter('class_widget_footer', 'change_widget_footer_cls');
function change_widget_footer_cls($s){
    return 'col-xs-12 col-sm-4';
}

add_filter( 'the_excerpt', 'add_strong_excerpt');
function add_strong_excerpt($excerpt){
    if( has_excerpt() && is_single() ) $excerpt = '<div class="post-excerpt">'.$excerpt.'</div>' ;
    return $excerpt;
}

add_filter('archive_title', 'prefix_archive_title');
function prefix_archive_title($title){
    $title = '<span class="title-prefix">Articles</span> '.$title;
    return $title;
}



add_action( 'single_after_author_info', 'add_post_author_date' );

function add_post_author_date(){
    global $post;
    $datef = __( 'j F Y ' );
    $time_post_modified = strtotime( $post->post_modified) ;
    $time_post_date = strtotime( $post->post_date) ;
    if( $time_post_modified < $time_post_date){
        $time_post_modified = $time_post_date ;
    }
    $date_modified = date_i18n( $datef, $time_post_modified );
    ?>
    <span class="edited_date"> Mis à jour le  <?php  echo $date_modified; ?></span>

    <?php
}

/* ajouter le post type exposant*/
function create_post_type_exposant() {
    register_post_type( 'exposant',
        array(
            'labels' => array(
                'name' => __( 'Exposants' ),
                'singular_name' => __( 'Exposant' ),
                'add_new' => 'Ajouter un exposant',
                'add_new_item' => 'Ajouter un exposant',
                'edit_item' => 'Editer Exposant',
                'new_item' => 'Nouvelle Exposant',
                'view_item' => 'Voir Exposant',
                'search_items' => 'Chercher Exposants',
                'not_found' => 'Pas de exposants trouvées',
            ),
            'public' => true,
            'hierarchical' => true,
            'support'=>array('page-attributes'),
            'excerpt'=>true,
            'thumbnail'=>true,
            'show_in_admin_bar' => true,
            'menu_icon'=>'dashicons-editor-customchar',
            'rewrite'=> false,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments',
                'custom-fields', 'page-attributes','post-formats')
        )
    );
    register_taxonomy_for_object_type('category', 'exposant','show_tagcloud=1&hierarchical=true'); // ajout des mots clés pour notre custom post type
}

add_action( 'init', 'create_post_type_exposant' );

add_filter( 'last_post_args_by_menu' , 'block_conseils_expert', 10, 3);

function block_conseils_expert($args, $menu_item, $bloc){
    if(!empty($bloc['category_object']->slug) && $bloc['category_object']->slug == 'conseils-experts'){
        $args['post_type'] = ['post'];
    }
    return $args;
}


add_filter('show_post_excerpt','block_conseils_expert_excerpt', 10 ,2);
function block_conseils_expert_excerpt($show, $post){
    if( (is_home() || is_single()) && $post->post_type == "exposant" ){
        return false;
    }
    return $show;
}

add_action('before_megabanner_bottom', 'show_bandeau_partners');

function show_bandeau_partners(){
    $activer_bandeau_partenaire = apply_filters('enable_bandeau_partners',true);
    if ($activer_bandeau_partenaire ){
        $html = '';
        $bandeau_partner = get_option('bandeau_partner' , '');
        if( !empty($bandeau_partner) ) {
            ob_start();
            include(locate_template('include/templates/bandeau-partners.php'));
            $html = ob_get_clean();
        }
        echo $html;
    }
}


add_action('after_content_page_single', 'exposants_par_thematique');

function exposants_par_thematique(){
    global $posts_exclude, $post;
    $exposants_cat = RW_Category::get_permalinked_category($post->ID);
    if( empty($exposants_cat) ){
        $exposants_cat = RW_Category::get_post_category_from_url($post);
    }
    if( strpos($exposants_cat->slug, '-edito') ){
        $exposants_cat_slug = str_replace('-edito', '', $exposants_cat->slug);
        $exposants_cat = RW_Category::rw_get_category_by_slug($exposants_cat_slug);
    }
    $posts = get_data_from_cache('exposants_par_thematique_'.$exposants_cat->term_id, 'post', 60*60*24, function() use( $exposants_cat, $posts_exclude ) {
        $args = array(
            'showposts' => 3,
            'category__in' => array($exposants_cat->term_id),
            'post__not_in' => $posts_exclude,
            'orderby'   => 'rand',
            'post_type' => 'exposant',
        );
        if( defined('_LOCKING_ON_') && _LOCKING_ON_ && is_single() && $args_lock = get_locking_config('post', 'post_exposants_par_thematique_'.$exposants_cat->slug)){
            return Locking::get_locking_ids($args_lock , $args);
        }else{
            return get_posts( $args);
        }
    });
    if( !empty($posts) ){
        ?>
        <div class="bloc-posts">
            <div class="bloc_rubrique_head">
                <h2 class="default-title">
                    <?php _e('exposants de la même thématique') ?>
                </h2>
            </div>
            <div class="row">
                <?php
                foreach ($posts as $post) {
                    setup_postdata($post);
                    ?>
                    <div class="post col-xs-12 col-sm-4">
                        <?php include(locate_template('include/templates/block_post.php')); ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div> <!-- .bloc_exposants -->
        <?php

    }
}


function conseils_expert_blocs_visibility($show, $current_cat){
    if( $current_cat->slug == 'conseils-experts' ) return true;
    return $show;
}
function exposition_virtuelle_blocs_visibility($show, $current_cat){
    if( $current_cat->slug == 'exposition-virtuelle' ) return true;
    return $show;
}

function set_subcategory_posts_per_page($query) {
    if ( !is_admin() && $query->is_main_query() && $query->is_category() ) {
        $category = get_queried_object();
        if ($category->parent) {
            $query->set( 'posts_per_page', 12);
        }
    }
}

function conseils_experts_edito_cat($current_cat, $parent_cat){
    if( !empty($parent_cat) && $parent_cat->slug == 'exposition-virtuelle' ){
        $cat_edito = RW_Category::rw_get_category_by_slug($current_cat->slug.'-edito');
        if( !empty($cat_edito) ) return $cat_edito;
    }
    return $current_cat;
}

function conseils_experts_readmore_cat_link($link, $current_cat){
    if( !empty($current_cat->parent) ) $parent_cat = get_category($current_cat->parent);
    if( !empty($parent_cat) && $parent_cat->slug == 'conseils-experts' ){
        return get_category_link($current_cat);
    }
    return $category_link;
}

function add_ga_event_popular_shortcode(){
    echo do_shortcode('[push_top_popular]');
}
function add_post_type_ga_post_popular($array_types){
    array_push($array_types,'exposant');
    return $array_types;
}
function set_ga_api_id($value){
    if(is_dev()){
        $new = get_param_global('test_ga_api_id');
    }else{
        $new = get_param_global('ga_api_id');
    }
    if (!empty($new)) {
        $value = $new;
    }
    return $value;
}

function display_most_popular_exposant_statue($bool){
    if (get_param_global('has_exposant')) {
        $bool = true;
    }
    return $bool;
}

function exclude_cats_last_posts($cats_ids) {
    if(is_wp_home()) {
        $cats_slugs = ['exposition-virtuelle'];
        foreach ($cats_slugs as $slug) {
            $cat = RW_Category::rw_get_category_by_slug($slug);
            if(!empty($cat)){
                $cats_ids[] = $cat->term_id;
            }
        }
    }
    return $cats_ids;
}

function print_flush($s){
    RW_Utils::print_flush($s);
}

function display_read_more_block_single() {
    include(locate_template('include/templates/read-more-block.php'));
}

add_action( 'save_post', 'check_post_video_or_podcast', 10,3 );
 
function check_post_video_or_podcast( $post_id, $post, $update ) { 
   $hasmedia = false;
   $tags=array();
   if(strpos($post->post_content,'[art19') !== false || strpos($post->post_content,'[edisound') !== false ){
        $tags[]='has_poscast';
   }
   if(strpos($post->post_content,'[fpvideo') !== false || strpos($post->post_content,'[videojs') !== false ){
        $tags[]='has_video';
   }
   if(count($tags)>0)wp_set_post_tags($post_id,$tags);
}