<?php

$dfp_id_account = '';

$plan_tagagge_dfp = [
	"divers" => array( 'id' => "" ),
];

$pages_types_dfp = array(
	'hp' => array('habillage', 'mpu_haut', 'mpu_milieu','pavet_mobile_1','pavet_mobile_2'),
	'rg '=> array('habillage', 'mpu_haut', 'mpu_milieu','pavet_mobile_1','pavet_mobile_2'),
);

$partners = array (
	'analytics' => array(
		'config' => array(
			'google_analytics_id' => get_param_global('google_analytics_id'), 
			'test_google_analytics_id' => get_param_global('test_google_analytics_id'),
		),
	),
    'cmp_didomi' => array(),
	'art19' => array(),
	'edisound' => array(),
);