<?php
new Options_Factory(
	array(
		"page_name" => "locking_ev", // page name in wp-admin
		"is_single_option" => true, // if true, the options will be created in a single way in the database
		"option_page" => array(
			"id" => "locking_ev", // option ID to get using Options
			"page_title" => "Locking des categories Exposition Virtuelle ", // Option Title
			"menu_title" => "Contenu statiques Exposition Virtuelle",
			"description" => "this is a page to administrate options", // just a bla bla page description,
		),
		"fields" => array(
			array(
				"label" => "Id des catégories séparé par ,",
				"suffix_id" => "_cats", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
		)
	)
);