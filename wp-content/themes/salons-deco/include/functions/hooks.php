<?php 

add_filter( 'last_post_args_by_menu' , 'block_conseils_expert_deco', 10, 3);
add_filter('archive_carousel_visibility', 'conseils_expert_blocs_visibility_deco', 10, 2);
add_filter('conseils_experts_read_more', 'conseils_experts_readmore_cat_link_deco', 10, 2);
add_action('ninja_forms_after_submission', 'custom_ninja_forms_after_submission');

add_filter('more_category_text', function ( $return, $title = '', $cat_slug ) {
    if($cat_slug == 'conseils-deco') {
        $return = 'Voir tous les conseils';
    } else {
        $return = 'Voir tous les exposants';
    }
    return $return;
}, 10, 3);

function block_conseils_expert_deco($args, $menu_item, $bloc){
    if(!empty($bloc['category_object']->slug) && $bloc['category_object']->slug == 'conseils-deco'){
        $args['post_type'] = ['post'];
    }
    return $args;
}

function conseils_expert_blocs_visibility_deco($show, $current_cat){
    if( $current_cat->slug == 'conseils-deco' ) return true;
    return $show;
}

function conseils_experts_readmore_cat_link_deco($link, $current_cat){
    if( !empty($current_cat->parent) ) $parent_cat = get_category($current_cat->parent);
    if( !empty($parent_cat) && $parent_cat->slug == 'conseils-deco' ){
        return get_category_link($current_cat);
    }
    return $category_link;
}

function custom_ninja_forms_after_submission( $form_data )
{
    foreach ($form_data["fields"] as $key => $value) {
        if($value["type"]=="email"){
            GAIACRM::getInstance()->synchronyzeDbToGaia($value["value"]);
        }
    }
    return $form_data;
}
