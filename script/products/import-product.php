<?php

define('DO_NOT_SYNC', true) ;
$product_not_to_delete = array();
error_reporting(E_ERROR | E_WARNING | E_PARSE);
set_time_limit (0);
if (isset($memory_limit)) {
	ini_set('memory_limit', $memory_limit);
}

$host = @$argv[1] ; 

if(isset($argv[1])){
	$use_console = true ;
}

if($use_console && !$host){
   die("Il faut mettre le 1er parametre qui concerne le host exemple deco.viepratique.fr \n");
}
if($use_console){
	define('HTTP_HOST',$host);
	$_SERVER['HTTP_HOST'] = $host ;
}


if($host){
	define('HTTP_HOST',$host);
	$_SERVER['HTTP_HOST'] = $host ;
}

//set_time_limit (  5000 ) ;
require (dirname(__FILE__) . '/../../wp-load.php');


include_once (ABSPATH . "wp-admin/includes/media.php");
include_once (ABSPATH . "wp-admin/includes/file.php");
include_once (ABSPATH . 'wp-admin/includes/image.php');


function parse_csv($file, $csv_row_separator = ';') {
	//$xx = 0 ;
	$r = array() ;
    if (($handle = fopen($file, "r")) === FALSE) return;
    while (($cols = fgetcsv($handle, 10000, $csv_row_separator)) !== FALSE) {
        foreach( $cols as $key => $val ) {
            $cols[$key] = trim( $cols[$key] );
            //$cols[$key] = iconv('UCS-2', 'UTF-8', $cols[$key]."\0") ;
            $cols[$key] = str_replace('""', '"', $cols[$key]);
            // $cols[$key] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $cols[$key]);
            $cols[$key] = preg_replace("/^\"(.*)\"$/sim", "$1", $cols[$key]);
        }
        //echo print_r($cols, 1);
        $r[] = $cols ;
		//$xx ++  ;
		//if($xx > 10) break;
    }
    return $r ;
}

function get_csv_field($row, $key){
	global $csv_keys, $csv_map ;
	
	if(isset($csv_map[$key])){
		$key = $csv_map[$key];
		if(FALSE !==($key = array_search($key, $csv_keys))){
			return $row[$key];
		}else{
			return false ;
		}
	}
	return false ;
}

function download_csv($file_source, $file_target) {
    //$rh = fopen($file_source, 'rb');
    //$wh = fopen($file_target, 'wb');
   

	$ch = curl_init();
	/**
	* Set the URL of the page or file to download.
	*/
	curl_setopt($ch, CURLOPT_URL, $file_source);

	$fp = fopen($file_target, 'w+');
	/**
	* Ask cURL to write the contents to a file
	*/
	curl_setopt($ch, CURLOPT_FILE, $fp);

	curl_exec ($ch);

	curl_close ($ch);
	fclose($fp);


    return false;
}





if ($use_console OR is_user_logged_in()) {
	if(isset($file_name_local) && !$file_name_local){
		if (!isset($time_out)) {
			$time_out = 10000;
		}
		$file_data = wp_remote_get($url_download, [
			'timeout'     => $time_out,
			'headers' => [
				'content-type' => 'application/json; charset=utf-8'
			]
		]);
	
		if (is_wp_error($file_data)) {
			print("\n Error : " . $file_data->get_error_message() . " \n");
			exit;
		}
		if (!empty($file_data['body'])) {
			$imported = file_put_contents($file_name, $file_data['body']);
	
			if ($imported) {
				print("\nSuccess : Le flux bien importer dans : " . $file_name . " \n");
			}
		}
	}

	$rows =  parse_csv($file_name, $csv_row_separator);
	$csv_keys = $rows[0] ;
	unset($rows[0]) ;



	$post_type = 'produit' ;
	$added_campaign = '';


	foreach($rows as $row){

		$id = get_csv_field($row,'Identifiant_unique');
		$title = get_csv_field($row,'Titre');
		$post_content = get_csv_field($row,'Description');
		$exposant = get_csv_field($row,'Exposant');

		$producturl = get_csv_field($row,'URL_produit');
		$smallimage = get_csv_field($row,'URL_image_miniature');
		$price = get_csv_field($row,'Prix_TTC');
		$old_price = get_csv_field($row,'old_price');
		$bigimage = get_csv_field($row,'URL_image');
		$description_offre = get_csv_field($row,'Promo_texte');
		$categoryid = get_csv_field($row,'Categorie');
		$instock = get_csv_field($row,'Disponibilite');
		$Devise = get_csv_field($row,'Devise');
		$marque = get_csv_field($row,'Marque');
		$eco_part = get_csv_field($row,'Eco-part');


		if( !empty($exposant) && $added_campaign!=$exposant ){
			$cat = get_term_by( 'slug', $exposant, 'campaign' );
			$added_campaign = $exposant;
			if(!$cat->term_id ){
				$cat = wp_insert_term(
					$exposant, // the term 
					'campaign' // the taxonomy
					
				);
				$campaign_id = $cat['term_id'];

			}else{
				$campaign_id = $cat->term_id ;
				
			}
			if( isset($no_utm_tracking) && $no_utm_tracking ){
				update_term_meta( $campaign_id, 'no_utm_tracking', true);
			}else{
				delete_term_meta( $campaign_id, 'no_utm_tracking');
			}
		}


		/*if('Roues Cruiser Bantam Orange' != $title ){
			continue;
		}else{
			print_r($row);
			echo '---<br>';
		}*/

		$posts_array = get_posts( array( 
			'meta_key'=> 'id', 
			'meta_value'=> $id , 
			'post_type' => $post_type,
			'post_status'=>'any',
			'tax_query' => array(
					array(
						'taxonomy' => 'campaign',
						'field' => 'id',
						'terms' => $campaign_id
					)
				)
			));

		$post_id = 0 ;
		if(count($posts_array) > 0){
			if (isset($force_update) && $force_update) {
				$post_id = $posts_array[0]->ID ;
			}else{
				echo ( 'Produit ' . $title . " passed \n" ) ;
				continue;
			}
		}
	

		$my_post = array( 
		 	'post_title' => $title ,		
		 	'post_content' => $post_content , 
		 	'post_status'    => 'publish',
		 	'post_type' => $post_type,
		 	'tax_input'    => array('campaign'=> array($campaign_id))

		);
		if($post_id){
			$my_post['ID'] = $post_id ;
			wp_update_post($my_post);
			if (!empty($delete_not_exist_product)) {
				$product_not_to_delete[] = $post_id;
			}

		print_flush("\n Update :  Post Id : $post_id \n") ;	

		}else{
			$post_id = wp_insert_post($my_post);
			if (!empty($delete_not_exist_product)) {
				$product_not_to_delete[] = $post_id;
			}
			print_flush("\n Insert :  Post Id : $post_id \n") ;
		}

		wp_set_post_terms( $post_id,array($campaign_id), 'campaign');
		//die;
		if($id) update_post_meta($post_id, "id", $id);
		if($producturl) update_post_meta($post_id, "producturl", $producturl); 
		if($smallimage) update_post_meta($post_id, "smallimage", $smallimage); 
		if($price) update_post_meta($post_id, "price", $price); 
		if($old_price) update_post_meta($post_id, "old_price", $old_price); 
		if($instock) update_post_meta($post_id, "instock", $instock); 
		if($categoryid) update_post_meta($post_id, "categoryid", $categoryid); 
		if($bigimage) update_post_meta($post_id, "bigimage", $bigimage);
		if($description_offre) update_post_meta($post_id, "description_offre", $description_offre);
		if($Devise) update_post_meta($post_id, "Devise", $Devise);

		if($marque) update_post_meta($post_id, "Marque", $marque);
		if($eco_part) update_post_meta($post_id, "Eco-part", $eco_part);

		if($exposant) update_post_meta($post_id, "Exposant", $exposant);

		print_flush('Produit : ' . $title . " | Post Id : $post_id \n") ;	
	}
	if (!empty($delete_not_exist_product) && count($product_not_to_delete)>0){
		// soft delete, post_status -> draft
		$limit = 100;
		$offset = 0;
		do{
			$args = [
				'post_type' => $post_type,
				'post_status'=>'publish',
				'posts_per_page' => $limit,
				'post__not_in' => $product_not_to_delete,
				'tax_query' => array(
					array(
						'taxonomy' => 'campaign',
						'field' => 'id',
						'terms' => $campaign_id
					)
				)
			];
			$product_to_delete = get_posts($args);
			$count_posts = count($product_to_delete);

			foreach ($product_to_delete as $product) {
				$update_product = [
					'ID' => $product->ID,
      				'post_status' => 'draft',
				];
				wp_update_post($update_product);
				print_flush("\n delete :  Post Id : $product->ID \n") ;
			}
			if($count_posts == $limit) {
				$offset += $limit;
			}
		}while($count_posts == $limit);
	}

}else{
	echo 'Vous devez vous connecter pour exécuter cette tache' ;
}

die;
?>