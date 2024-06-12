<?php



class Partenaire
{

    const custom_post = "partenaire";
    
        static function register()
    {

        add_action('init', [self::class, 'partenaire_init']);
    }
    static   function partenaire_init()
    {
        $labels = array(
            'name'               => _x('partenaire', 'post type general name', REWORLDMEDIA_TERMS),
            'singular_name'      => _x('partenaire', 'post type singular name', REWORLDMEDIA_TERMS),
            'menu_name'          => _x('partenaire', 'admin menu', REWORLDMEDIA_TERMS),
            'name_admin_bar'     => _x('partenaire', 'add new on admin bar', REWORLDMEDIA_TERMS),
            'add_new'            => _x('Ajouter', 'partenaire', REWORLDMEDIA_TERMS),
            'add_new_item'       => __('Ajouter un nouveau partenaire', REWORLDMEDIA_TERMS),
            'new_item'           => __('Nouveau partenaire', REWORLDMEDIA_TERMS),
            'edit_item'          => __('Editer partenaire', REWORLDMEDIA_TERMS),
            'view_item'          => __('Voir partenaire', REWORLDMEDIA_TERMS),
            'all_items'          => __('Tous les partenaire', REWORLDMEDIA_TERMS),
            'search_items'       => __('Rechercher partenaire', REWORLDMEDIA_TERMS),
            'parent_item_colon'  => __('partenaire Mère:', REWORLDMEDIA_TERMS),
            'not_found'          => __('Aucune partenaire trouvé.', REWORLDMEDIA_TERMS),
            'not_found_in_trash' => __('Aucune partenaire trouvé dans la corbeille.', REWORLDMEDIA_TERMS)
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __('Description.', REWORLDMEDIA_TERMS),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
            'taxonomies'         => array( 'category', 'post_tag' ),
            'menu_icon'          => 'dashicons-location-alt',
            'show_in_rest'       => true,
        );

        register_post_type(self::custom_post, $args);
    }
  
    
}

Partenaire::register();
