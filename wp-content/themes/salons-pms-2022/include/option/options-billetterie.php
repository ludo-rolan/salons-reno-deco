<?php 

new Options_Factory(
	array(
		"page_name" => "lp_billetterie", // page name in wp-admin
		"is_single_option" => true, // if true, the options will be created in a single way in the database
		"option_page" => array(
			"id" => "lp_billetterie", // option ID to get using Options
			"page_title" => "Landing Page Billetterie", // Option Title
			"menu_title" => "Landing Page Billetterie",
			"description" => "this is a page to administrate options", // just a bla bla page description,
		),
		"fields" => array(
            array(
				"label" => "URL CTA (page billetterie) FR",
				"suffix_id" => "_cta_url_fr", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
            array(
				"label" => "URL CTA (page billetterie) EN",
				"suffix_id" => "_cta_url_en", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
            array(
				"label" => "Texte de première CTA (haut de page)",
				"suffix_id" => "_button_top_right_text", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
            array(
				"label" => "Texte des autres CTA (ACHETEZ VOS BILLETS)",
				"suffix_id" => "_button_text", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
            array(
				"label" => "Texte Banner (1er)",
				"suffix_id" => "_banner_text_1", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
            array(
				"label" => "Texte Banner (2ieme)",
				"suffix_id" => "_banner_text_2", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
            array(
				"label" => "Texte Banner (1ere zone)",
				"suffix_id" => "_banner_text_3", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
            array(
				"label" => "Texte CTA Banner (ACHETEZ VOS BILLETS)",
				"suffix_id" => "_banner_button_text", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
            // block des chiffres 3 (blocks) 
            array(
				"label" => "1er texte (Chiffres - Block 1)",
				"suffix_id" => "_chiffres_block_1_texte_1", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
            array(
				"label" => "2ieme texte (Chiffres - Block 1)",
				"suffix_id" => "_chiffres_block_1_texte_2", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
            array(
                "label" => "3ieme texte (Chiffres - Block 1)",
                "suffix_id" => "_chiffres_block_1_texte_3", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),
            array(
                "label" => "1er texte (Chiffres - Block 2)",
                "suffix_id" => "_chiffres_block_2_texte_1", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),
            array(
                "label" => "2ieme texte (Chiffres - Block 2)",
                "suffix_id" => "_chiffres_block_2_texte_2", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),
            array(
                "label" => "3ieme texte (Chiffres - Block 2)",
                "suffix_id" => "_chiffres_block_2_texte_3", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),
            array(
                "label" => "1er texte (Chiffres - Block 3)",
                "suffix_id" => "_chiffres_block_3_texte_1", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),
            array(
                "label" => "2ieme texte (Chiffres - Block 3)",
                "suffix_id" => "_chiffres_block_3_texte_2", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),
            array(
                "label" => "3ieme texte (Chiffres - Block 3)",
                "suffix_id" => "_chiffres_block_3_texte_3", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),

            // block articles (au-dessous des chiffres)
            array(
				"label" => "Titre Article (Article 1)",
				"suffix_id" => "_article_1_titre", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => Text_MetaBox_Type::class,
			),
            array(
				"label" => "Contenu Article (Article 1)",
				"suffix_id" => "_article_1_content", // option_page id + suffix_id
				"sanitize" => false, 
				"type" => WPEditor_MetaBox_Type::class,
			),
            array(
                "label" => "Image Article (Article 1)",
                "suffix_id" => "_article_1_image", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Single_image_MetaBox_Type::class,
            ),

            // block partenaires 
            array(
                "label" => "Titre Partenaires",
                "suffix_id" => "_partenaires_titre", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),
            array(
                "label" => "Images Partenaires",
                "suffix_id" => "_partenaires_image", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Gallery_MetaBox_Type::class,
            ),

            // block articles 2 (au-dessus des partenaires)
            array(
                "label" => "Titre Article (Article 2)",
                "suffix_id" => "_article_2_titre", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),
            array(
                "label" => "Contenu Article (Article 2)",
                "suffix_id" => "_article_2_content", // option_page id + suffix_id
                "sanitize" => false,
                "type" => WPEditor_MetaBox_Type::class,
            ),
            array(
                "label" => "Image Article (Article 2)",
                "suffix_id" => "_article_2_image", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Single_image_MetaBox_Type::class,
            ),

            // block passions (4 blocs) image et titre  
            array(
                "label" => "Titre Passion (Passion 1)",
                "suffix_id" => "_passion_1_titre", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),
            array(
                "label" => "Image Passion (Passion 1)",
                "suffix_id" => "_passion_1_image", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Single_image_MetaBox_Type::class,
            ),
            array(
                "label" => "Titre Passion (Passion 2)",
                "suffix_id" => "_passion_2_titre", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),
            array(
                "label" => "Image Passion (Passion 2)",
                "suffix_id" => "_passion_2_image", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Single_image_MetaBox_Type::class,
            ),
            array(
                "label" => "Titre Passion (Passion 3)",
                "suffix_id" => "_passion_3_titre", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),
            array(
                "label" => "Image Passion (Passion 3)",
                "suffix_id" => "_passion_3_image", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Single_image_MetaBox_Type::class,
            ),
            array(
                "label" => "Titre Passion (Passion 4)",
                "suffix_id" => "_passion_4_titre", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),
            array(
                "label" => "Image Passion (Passion 4)",
                "suffix_id" => "_passion_4_image", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Single_image_MetaBox_Type::class,
            ),

            // block Carrousel 
            array(
                "label" => "Titre Carrousel",
                "suffix_id" => "_carrousel_titre", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),
            array(
                "label" => "Images Carrousel",
                "suffix_id" => "_carrousel_images", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Gallery_MetaBox_Type::class,
            ),

            // block infos pratiques, titre, contenu, map
            array(
                "label" => "Titre Infos Pratiques",
                "suffix_id" => "_infos_pratiques_titre", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),
            array(
                "label" => "Contenu Infos Pratiques",
                "suffix_id" => "_infos_pratiques_content", // option_page id + suffix_id
                "sanitize" => false,
                "type" => WPEditor_MetaBox_Type::class,
            ),
            array(
                "label" => "Map Infos Pratiques",
                "suffix_id" => "_infos_pratiques_map", // option_page id + suffix_id
                "sanitize" => false,
                "type" => Text_MetaBox_Type::class,
            ),
		)
	)
);