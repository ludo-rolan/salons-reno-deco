<?php


$csv_map = array(
	'Identifiant_unique'=> 'ID',
	'Titre'=> 'Nom',
	'Prix_TTC'=> 'Prix TTC',
	'URL_produit'=> 'url de redirection',
	'URL_image'=> 'url visuel',
	'URL_image_miniature'=> 'url visuel',
	'Description'=> 'descriptif',
	'Exposant'=> 'Exposant',
	//'Disponibilite'=> 'Disponibilite',
	//'Categorie'=> 'Categorie',
	//'Soldes'=> 'Soldes',
	//'Promo_texte'=> 'Promo_texte',
	//'Description_code_promo'=> 'Description_code_promo',
	//'Devise'=> 'Devise',
	//'URL_image_miniature'=> 'URL_image',

);

$csv_row_separator = ";";
$no_utm_tracking = true;
$file_name_local = true ;

$file_name =  dirname(__FILE__) . '/../temp/all_products.csv' ;

include_once ("import-product.php");