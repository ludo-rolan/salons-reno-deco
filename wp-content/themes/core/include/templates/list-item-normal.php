<?php
if (!isset($cat_style)) {
	$cat_style = "info_cat";
}

$title = $post->post_title;
$excerpt = mini_excerpt_for_lines(60, 2);
$h2_class = "post";
$cat_link = RW_Category::get_menu_cat_link(get_post(), $cat_style, false, false, true, false, array(), '');
$div_style = (!has_post_thumbnail(get_the_ID())) ? 'style="width:100%"' : '';
$li_class = "item";
$title_complement = "";
$thumb_size = "thumbnail";

include(locate_template('include/templates/list-item-v3.php'));
?>