<?php

// Metabox Avion

new MetaBox_Factory (
    array(
        "post_types" => array('partenaire'),
        "is_single_meta" => true,
        "meta_box"   => array(
            "id"        => "",
            "title"     => "partenaire",
            "position"  => "normal",
            "priority"  => "high",
        ),
        "fields"     => array(
            array(
                "label"     => "Logo partenaire",
                "suffix_id" => "logo_partenaire",
                "sanitize"  => false,
                "type"      => Single_image_MetaBox_Type::class,
            ),
            array(
                "label"     => "Email partenaire",
                "suffix_id" => "email_partenaire",
                "sanitize"  => false,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Numéro de téléphone partenaire",
                "suffix_id" => "phone_partenaire",
                "sanitize"  => false,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "URL partenaire",
                "suffix_id" => "url_partenaire",
                "sanitize"  => false,
                "type"      => Text_MetaBox_Type::class,
            ),
            array(
                "label"     => "Descriptif partenaire",
                "suffix_id" => "descriptif_partenaire",
                "sanitize"  => false,
                "type"      => WPEditor_MetaBox_Type::class,
            )
        )
    )
);
