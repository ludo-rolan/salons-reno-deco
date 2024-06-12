<?php
new Options_Factory(
	array(
		"page_name" => "texte_statique_hp", // page name in wp-admin
		"is_single_option" => true, // if true, the options will be created in a single way in the database
		"option_page" => array(
			"id" => "texte_statique_hp", // option ID to get using Options
			"page_title" => "Contenu statiques HP", // Option Title
			"menu_title" => "Contenu statiques HP",
			"description" => "this is a page to administrate options", // just a bla bla page description,
		),
		"fields" => array(
			array(
				"label" => "Image background à propos",
				"suffix_id" => "_apropos_image",
				"type" => Single_image_MetaBox_Type::class
			),
			array(
				"label" => "Couleur background à propos",
				"suffix_id" => "_apropos_color",
				"type" => ColorWheel_MetaBox_Type::class
			),
			array(
				"label" => "Titre à propos",
				"suffix_id" => "_apropos_title", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
			array(
				"label" => "Contenu zone à propos",
				"suffix_id" => "_apropos_content",
				"sanitize" => false, 
				"type" => WPEditor_MetaBox_Type::class
			),

			array(
				"label" => "Image background exposition physique",
				"suffix_id" => "_exposition_physique_image",
				"type" => Single_image_MetaBox_Type::class
			),
			array(
				"label" => "Couleur background exposition physique",
				"suffix_id" => "_exposition_physique_color",
				"type" => ColorWheel_MetaBox_Type::class
			),
			array(
				"label" => "Titre exposition physique",
				"suffix_id" => "_exposition_physique_title", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
			array(
				"label" => "Contenu zone exposition physique",
				"suffix_id" => "_exposition_physique_content",
				"sanitize" => false, 
				"type" => WPEditor_MetaBox_Type::class
			),
		)
	)
);