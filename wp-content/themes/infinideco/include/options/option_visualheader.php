<?php
new Options_Factory(
	array(
		"page_name" => "visual_hdr", // page name in wp-admin
		"is_single_option" => true, // if true, the options will be created in a single way in the database
		"option_page" => array(
			"id" => "visual_hdr", // option ID to get using Options
			"page_title" => "Options Visual Header HomePage", // Option Title
			"menu_title" => "Visual Header HP",
			"description" => "this is a page to administrate options", // just a bla bla page description,
		),
		"fields" => array(
			array(
				"label" => "Titre Visual Header ",
				"suffix_id" => "_title", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
			array(
				"label" => "Date Visual Header",
				"suffix_id" => "_date",
                "sanitize" => false,
				"type" => Text_MetaBox_Type::class
			),
            array(
				"label" => "Adresse Visual Header",
				"suffix_id" => "_adresse",
				"sanitize" => false, 
				"type" => WPEditor_MetaBox_Type::class
			),
            array(
				"label" => "Image Visual Header",
				"suffix_id" => "_image",
				"type" => Single_image_MetaBox_Type::class
			),
		)
	)
);