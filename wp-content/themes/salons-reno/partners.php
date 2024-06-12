<?php

$dfp_id_account = '46980923/salondelarenovation-web';

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

	'dfp_v2' => array (
     	'config' => array (
            'dfp_id_account' => $dfp_id_account,
            'pages_types' => $pages_types_dfp,
            'plan_tagagge' => $plan_tagagge_dfp,
        ),
    ),
    'cmp_didomi' => array(),
);