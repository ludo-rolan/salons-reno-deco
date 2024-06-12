<?php
new Options_Factory(
	array(
		"page_name" => "texte_statique_ep", // page name in wp-admin
		"is_single_option" => true, // if true, the options will be created in a single way in the database
		"option_page" => array(
			"id" => "texte_statique_ep", // option ID to get using Options
			"page_title" => "Contenu statiques Exposition Physique", // Option Title
			"menu_title" => "Contenu statiques Exposition Physique",
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
				"sanitize" => false, 
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
				"label" => "Block Infos Pratiques -Couleur background",
				"suffix_id" => "_ip_color",
				"type" => ColorWheel_MetaBox_Type::class
			),
			array(
				"label" => "Block Infos Pratiques - Titre",
				"suffix_id" => "_ip_title",
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class
			),
			array(
				"label" => "Block Infos Pratiques - Texte intro (avant MAP)",
				"suffix_id" => "_ip_intro", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => WPEditor_MetaBox_Type::class,
			),
			array(
				"label" => "Block Infos Pratiques - Iframe de Map",
				"suffix_id" => "_ip_map",
				"sanitize" => false, 
				"type" => WPEditor_MetaBox_Type::class
			),
			array(
				"label" => "Block Infos Pratiques - texte Infos (après Map)",
				"suffix_id" => "_ip_texte",
				"sanitize" => false, 
				"type" => WPEditor_MetaBox_Type::class
			),
			array(
				"label" => "Block Infos Pratiques - Texte CTA  (laissez vide si pas de cta)",
				"suffix_id" => "_ip_cta_texte", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
			array(
				"label" => "Block Infos Pratiques - Url CTA",
				"suffix_id" => "_ip_cta_link", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
		)
	)
);