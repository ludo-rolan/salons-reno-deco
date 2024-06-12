<?php
global $post, $wpdb;
$posts = [];

// Nombre de posts à récupérer
$nbr_posts = 3;

if($post->post_type == "post" || $post->post_type == "folder") {

	// Récupérer l'id parent du post
	$post_parent = $post->post_type == "post" ? $post->post_parent : $post->ID;

	// Récupérer les ids de la catégorie courante et la catégorie parente du post
	$cat_id = "";
	$cat_parent_id = "";

	$cat = RW_Category::get_permalinked_category($post->ID, true);

	if(isset($cat->term_id)) {
		$cat_id = $cat->term_id;
		if($cat->parent){
			$cat_parent = get_category($cat->parent);
			if(isset($cat_parent->term_id)) {
				$cat_parent_id = $cat_parent->term_id;
			}
		}
	}

	// Construire les conditions
	$cond_static = [];
	$cond_static[] = $wpdb->prefix . "posts.post_type = 'post'";
	$cond_static[] = $wpdb->prefix . "posts.post_status = 'publish'";

	if($post->post_type == "post") {
		$cond_static[] = $wpdb->prefix . "posts.ID != '" . $post->ID . "'";
	}
	
	$conditions = [];
	$conditions[] = $post_parent ? $wpdb->prefix . "posts.post_parent = '" . $post_parent . "'" : "";
	$conditions[] = $cat_id ? $wpdb->prefix . "terms.term_id = '" . $cat_id . "'" : "";
	$conditions[] = $cat_parent_id ? $wpdb->prefix . "terms.term_id = '" . $cat_parent_id . "'": "";

	// Supprimer les conditions vides
	$conditions = array_filter($conditions);

	// Construire la clause WHERE
	$or_cond = implode($conditions, " OR ");
	$and_cond = implode($cond_static, " AND ");

	$where_clause = $or_cond ? $and_cond . " AND ( " . $or_cond . " ) " : $and_cond;

	// Constuire la clause ORDER BY
	$order_by_cond = "";
	$order_by_date = "post_date DESC ";

	foreach($conditions as $condition) {
		$order_by_cond .= $condition . " DESC, ";
	}

	$order_by_clause = $order_by_cond . $order_by_date;

	// La requête à exécuter
	$sql_query = "SELECT DISTINCT " . $wpdb->prefix . "posts.ID, " . $wpdb->prefix . "posts.* FROM " . $wpdb->prefix . "posts INNER JOIN " . $wpdb->prefix . "term_relationships ON (" . $wpdb->prefix . "posts.ID = " . $wpdb->prefix . "term_relationships.object_id) INNER JOIN " . $wpdb->prefix . "term_taxonomy ON (" . $wpdb->prefix . "term_relationships.term_taxonomy_id = " . $wpdb->prefix . "term_taxonomy.term_taxonomy_id AND " . $wpdb->prefix . "term_taxonomy.taxonomy='category') INNER JOIN " . $wpdb->prefix . "terms ON (" . $wpdb->prefix . "terms.term_id = " . $wpdb->prefix . "term_taxonomy.term_id) WHERE " . $where_clause . " ORDER BY " . $order_by_clause . " LIMIT " . $nbr_posts . "";

	// Ajouter le sql cache
	$sql_query = apply_filters('rw_force_cache_queries', $sql_query);

	// Exécuter la requête
	$posts = $wpdb->get_results($sql_query);
}

if(count($posts)) {
	echo '<div class="read_more_block"><h2 class="read_more_block_title">'.__('À lire aussi' ,  REWORLDMEDIA_TERMS ).'</h2>';
	include locate_template('include/templates/inner_block.php');
	echo '</div>';
}