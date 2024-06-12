<?php

// Metabox Avion

new MetaBox_Factory (
    array(
        "post_types" => array('exposant'),
        "is_single_meta" => true,
        "meta_box"   => array(
            "id"        => "",
            "title"     => "Exposant",
            "position"  => "normal",
            "priority"  => "high",
        ),
        "fields"     => array(
            array(
                "label"     => "Logo Exposant",
                "suffix_id" => "logo_exposant",
                "sanitize"  => false,
                "type"      => Single_image_MetaBox_Type::class,
            ),
            array(
                "label"     => "Numéro Stand",
                "suffix_id" => "num_stand",
                "sanitize"  => true,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Email exposant",
                "suffix_id" => "email_exposant",
                "sanitize"  => false,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Numéro de téléphone exposant",
                "suffix_id" => "phone_exposant",
                "sanitize"  => false,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "URL Exposant",
                "suffix_id" => "url_exposant",
                "sanitize"  => false,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Où nous trouver",
                "suffix_id" => "ou_nous_trouver_exposant",
                "sanitize"  => false,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Descriptif Exposant",
                "suffix_id" => "descriptif_exposant",
                "sanitize"  => false,
                "type"      => WPEditor_MetaBox_Type::class,
            )
        )
    )
);
