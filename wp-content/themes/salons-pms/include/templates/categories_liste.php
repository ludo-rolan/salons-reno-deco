<?php 
$menu_items = wp_get_nav_menu_items( 'menu_header' );
$btn_txt = '+';
$cat_selected = "Tout voir";
$menu_items_nb = 4;
$submenu_added = false;
$count = 0;
$post_type = is_category() ? 'archive' : 'post';
$category = get_category($cat_parent);
$class = '';
$html = $menu_lis = '';

if($cat_parent == 0 || $category->category_parent == 0 ){
	$class = 'filter-category';
}
$colors = get_param_global('color_of_category');
if(isset($colors[$category->slug])){
	$class .= ' _' . $colors[$category->slug];
}

if( !rw_is_mobile() ) {
	if (!empty($menu_items)) {
		foreach ($menu_items as $menu_item) {
			if( $menu_item->title == $current_cat_name ) $current_menu_id = $menu_item->ID;
			if( $menu_item->menu_item_parent == $current_menu_id ){
				if( $count < $menu_items_nb ){
					$menu_lis .= '<li><a class="' . $class . '"  data-parent-id="'.$cat_parent.'" data-object-id="'.$menu_item->object_id.'" data-object-type="'.$post_type.'" href="'.$menu_item->url.'">'. ucfirst($menu_item->title) .'</a></li>';
				}else{
					if( !$submenu_added ){
						$menu_lis .= '<li class="categories-list-submenu">';
						$menu_lis .= '<a href="javascript:void(0);">'. $btn_txt .'</a>';
						$menu_lis .= '<ul>';
					}
					$menu_lis .= '<li><a class="' . $class . '"  data-parent-id="'.$cat_parent.'" data-object-id="'.$menu_item->object_id.'" data-object-type="'.$post_type.'" href="'.$menu_item->url.'">'. ucfirst($menu_item->title) .'</a></li>';
					$submenu_added = true;

				}
				++$count;
			}
		}
		if( !empty($menu_lis) ){
			$html = '<nav class="categories-list"><ul class="list-inline">'. $menu_lis . '</ul>';
			if( $submenu_added ) {
				$html .= '</li><!-- .categories-list-submenu --></ul>';
			}
			$html .='</nav><!-- .categories-list -->';
		}
	}
} else {
	if( !empty($menu_items) ){
		foreach ($menu_items as $menu_item) {
			if( $menu_item->title == $current_cat_name ) $current_menu_id = $menu_item->ID;
			if( $menu_item->menu_item_parent == $current_menu_id ){
				if( $count < $menu_items_nb ){
					$menu_lis .= '<li><a class="' . $class . '"  data-parent-id="'.$cat_parent.'" data-object-id="'.$menu_item->object_id.'" data-object-type="'.$post_type.'" href="'.$menu_item->url.'">'. $menu_item->title .'</a></li>';
				}
				++$count;
			}
		}
		if( !empty($menu_lis) ){
			$menu_first_cat_title = 'Tout voir';
			$html .= '<div class="dropdown pull-right">';
			$html .= '<button class="btn btn-light dropdown-toggle btn-text" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="'.$cat_parent.'">'. $menu_first_cat_title .'</span><i class="fa"></i></button>';
			$html .= '<ul class="dropdown-menu pull-right">';
			$html .= '<li><a href="#" class="' . $class . '" data-parent-id="'.$cat_parent.'" data-object-id="all" data-object-type="'.$post_type.'">Tout voir</a></li>';
			$html .= $menu_lis.'</ul>';
			$html .= '</div>';
		}
	}
}
echo $html;
