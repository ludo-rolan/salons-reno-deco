<?php
global $partners_default ;

$partners_default = array(

	'tiresio' => array(
		'desc' => 'Tiresio',
		'default_activation' => 0,
		'implementation' => 'tiresio.php',
		'action' => array( 'wp_footer',8 , 1 ),
		'callback' => 'tiresio_implementation',
		'callback_admin' => 'tiresio_implementation_admin',
		'is_tag' => true,
	),
	'FacebookPixel' => array(
		'desc' => 'Facebook pixel',
		'default_activation' => 0,
		'implementation' => 'facebook_pixel.php',
		'action' => array( 'wp_footer',10, 1 ),
		'callback' => 'facebook_pixel_implementation',
		'is_tag' => true,
	),
	'outbrain' => array(
		'desc' => 'Outbrain',
		   'default_activation' => 1,
		'implementation' => 'outbrain.php',
		'shortcodes' => array(
			'outbrain' => 'outbrain_implementation',
			'outbrain_lire_sur_le_web' => 'outbrain_lire_sur_le_web_implementation',
			'outbrain_same_subject' => 'read_on_same_subject_implementation',
			'outbrain_generator' => 'outbrain_generator_implementation',
		),
		'is_tag' => true,
	),

	'mediabong' => array(
		'desc' => 'Mediabong',
		'default_activation' => 1,
		'implementation' => 'mediabong.php',
		'shortcodes' => array(
			'mediabong' => 'mediabong_implementation',
			'videoMediabong' => 'video_mediabong_implementation',

		 ),
		'is_tag' => true,
	),

	'cookies_axciom' => array(
		'desc' => 'Cookies axciom',
		'action' => array( 'wp_enqueue_scripts',1 , 0 ),
		'implementation' => 'cookies_axciom.php',
		'callback' => function () {
			global $site_config_js ;
			if ( $cookies_axciom = get_param_global( 'Cookies_Axciom' ) ) {
				$site_config_js['cookies_axciom'] = $cookies_axciom ;
			}
		},
		'is_tag' => true,
	),
	'ligatus' => array(
		'desc' => 'Ligatus',
		'default_activation' => 1,
		'implementation' => 'ligatus.php',
		  'shortcodes' => array(
			'add_ligatus_smartbox' => 'ligatus_smartbox_implementation',
			'ligatus' => 'ligatus_implementation',
			'ligatus_block_right' => 'ligatus_block_right_implementation',

		 ),
		'action' => array( 'wp_head',10 , 1 ),
		'callback' => 'ligatus_init',
		'is_tag' => true,

	),
	'ligatus_infeed' => array(
		'desc' => 'Ligatus Infeed',
		'default_activation' => 0,
		'implementation' => 'ligatus_infeed.php',
		'class_name' => 'ligatus_infeed',
		'callback' => 'ligatus_infeed_implementation',
		'is_tag' => true,
	),
	// Facebook ads pixel tracking
	'fb_ads_pixel_tracking' => array(
		'desc' => 'Facebook pixel tracking',
		'default_activation' => 1,
		'implementation' => 'fb_pixel_tracking.php',
		'action' => array( 'wp_footer',10 , 0 ),
		'callback' => 'fb_ads_pixel_tracking_implementation',
		'is_tag' => true,
	),

	'imonomy' => array(
		'desc' => 'Imonomy',
		'default_activation' => 0,
		'implementation' => 'imonomy.php',
		'class_name' => 'imonomy_partner',
		'action' => array('wp_footer', 10, 1),
		'callback' => 'imonomy_implementation',
		'is_tag' => true,
	),

	'krux' => array(
		'desc' => 'Krux',
		'default_activation' => 1,
		'implementation' => 'krux.php',
		'class_name' => 'krux',
		'callback' => 'integration_tags_knux',
		'action' => array( 'wp_head' ),
		'is_tag' => true,
	),
	/*'inread' => array(
		'desc' => 'Inread',
		'default_activation' => 1,
		'implementation' => 'inread.php',
		'shortcodes' => array(
			'inRead' => 'inread_implementation',
		 ),
		'callback' => 'init_inread',
		'is_tag' => true,
	),*/
	/*'inBoard' => array(
		'desc' => 'InBoard',
		'default_activation' => 1,
		'implementation' => 'inboard.php',

		'shortcodes' => array(
			'inBoard' => 'inboard_implementation',
			),
		'callback' => 'init_inboard',
		'is_tag' => true,
	),*/
	'sublimskinz' => array(
		'desc' => 'Sublim Skinz',
		'default_activation' => 0,
		'implementation' => 'sublimskinz.php',
		'action' => array( 'wp_footer' , 10 , 1 ),
		'callback' => 'sublimskinz_implementation',
		'is_tag' => true,
	),
	'numbate' => array(
		'desc' => 'Numbate/Mozoo',
		'default_activation' => 0,
		'implementation' => 'numbate.php',
		'action' => array( 'wp_footer' , 10 , 1 ),
		'callback' => 'numbate_implementation',
		'is_tag' => true,
	),
	'insidepic' => array(
		'desc' => 'insidepic',
		'default_activation' => 0,
		'implementation' => 'insidepic.php',
		'action' => array( 'wp_footer' , 11 , 1 ),
		'callback' => 'insidepic_implementation',
		'is_tag' => true,
	),
	'unruly' => array(
		'desc' => 'unruly',
		'default_activation' => 0,
		'implementation' => 'unruly.php',
		'action' => array( 'wp_footer' , 10 , 1 ),
		'callback' => 'unruly_implementation',
		'is_tag' => true,
	),
	'dmp' => array(
		'desc' => 'DMP',
		'default_activation' => 0,
		'implementation' => 'dmp.php',
		'action' => array( 'rew_top_head' , 10 , 1 ),
		'callback' => 'dmp_implementation',
		'is_tag' => true,
	),
	'innity' => array(
	  'desc' => 'Innity Underlay',
	  'default_activation' => 0,
	  'implementation' => 'innity.php',
	  'callback' => 'innity_callback',
	  'is_tag' => true,
	),
	'wideonetv2' => array(
		'is_tag' => false,
	),
	'wideonet' => array(
	  'desc' => 'Videos Wideonet',
	  'default_activation' => 0,
	  'implementation' => 'wideonet.php',
	  'callback_admin' => 'wideonet_admin',
	  'callback' => 'wideonet_show',
	  'is_tag' => false,
	),
	'contentbox_reebonz' => array(
		'desc' => 'Content Box Reebonz',
		'default_activation' => 1,
		'implementation' => 'contentbox_reebonz.php',
		'shortcodes' => array(
			'content_box' => 'contentbox_reebonz_shortcode',
		),
		'action' => array( 'after_single', 9, 1 ),
		'callback' => 'contentbox_reebonz_implementation',
		'is_tag' => true,
	),
	'pixel_media_post' => array(
		'desc' => 'Pixel media post',
		 'default_activation' => 0,
		 'implementation' => 'pixel-media-post.php',
		  'shortcodes' => array(
			'pixel_media_post' => 'pixel_media_post_implementation',
		),
		  'callback' => function () {
			  add_action('wp_footer', function () {
				  echo do_shortcode( '[pixel_media_post]' );
			  });
		  },
		  'is_tag' => true,
	),

	'pub_video' => array(
		'desc' => 'pub video',
		'default_activation' => 1,
		 'implementation' => 'pub_video.php',
		'callback' => 'pub_video_init',
		'is_tag' => true,

	),
	// 'sirdata' => array(
	// 	'desc' => 'SIRDATA',
	// 	'default_activation' => 0,
	// 	'implementation' => 'sirdata.php',
	// 	'action' => array( 'wp_footer',10 , 1 ),
	// 	'callback' => 'sirdata_implementation',
	// 	'is_tag' => true,
	// ),
	'taboola' => array(
		'desc' => 'Taboola',
		'default_activation' => 0,
		'implementation' => 'taboola.php',
		'class_name' => 'taboola_partner',
		'callback' =>  'taboola_implementation',
		'shortcodes' => array(
			'taboola_below_mobile' => 'taboola_below_implementation_mobile',
			'taboola_below_desktop' => 'taboola_below_implementation_desktop',
			'taboola_below' => 'taboola_below_implementation',
			'taboola_right' => 'taboola_right_implementation',
		),
		'is_tag' => true,
	),

	'taboola_organique' => array(
		'desc' => 'Taboola Organique',
		'default_activation' => 0,
		'implementation' => 'taboola.php',
		'class_name' => 'taboola_partner',
		'callback' =>  'taboola_implementation',
		'shortcodes' => array(
			'taboola_organique_mobile' => 'taboola_organique_implementation_mobile',
			'taboola_organique_desktop' => 'taboola_organique_implementation_desktop',
			'taboola_organique' => 'taboola_organique_implementation',
		),
		'is_tag' => true,
	),

	'ancre_teads' => array(
		'desc' => 'ancre teads article',
		'default_activation' => 0,
		'implementation' => 'ancre_teads.php',
		'action' => array('wp_head'),
		'comportement_inverse'=> 1,
		'callback' =>  'ancre_teads_implementation',
		'is_tag' => false,
	),

	'quantum' => array(
		'desc' => 'QUANTUM',
		'default_activation' => 1 ,
		'implementation' => 'quantum.php',
		'action' => array('after_wp_footer'),
		'callback' =>  'quantum_implementation_hp',
		'is_tag' => true,
	),

	'quantum_article' => array(
		'desc' => 'QUANTUM Article',
		'default_activation' => 0 ,
		'implementation' => 'quantum.php',
		'action' => array('after_wp_footer'),
		'callback' =>  'quantum_implementation_articles',
		'is_tag' => true,
	),

	'player_bas_article' => array(
		'desc' => "Player bas d'article",
		'default_activation' => 0 ,
		'class_name' => 'player_bas_article',
		'implementation' => 'player_bas_article.php',
		'callback' =>  'implementation_player_bas_article',
		'shortcodes' => array(
			'player_bas_article' => 'player_bas_article_shortcode',
		),
		'is_tag' => false,
	),

    'tradedoubler' => array(
      'desc' => 'Tradedoubler',
      'default_activation' => 0,
      'implementation' => 'tradedoubler.php',
      'action' => array('wp_footer',10, 1),
      'callback' =>  'tradedoubler_implementation',
      'is_tag' => true,
    ),

    'pixel_mediamath' => array(
      'desc' => 'Pixel mediamath',
      'default_activation' => 0,
      'implementation' => 'pixel_mediamath.php',
      'action' => array('wp_footer',10, 1),
      'callback' =>  'pixel_mediamath_implementation',
      'is_tag' => true,
    ),

    'ariane_labs' => array(
      'desc' => 'Ariane Lab',
      'default_activation' => 0,
      'class_name' => 'ariane_labs',
      'implementation' => 'arianelabs.php',
      'action' => array('wp_footer',10, 1),
      'callback' =>  'arianlabs_implementation',
      'is_tag' => true,
    ),
	'seedtag' => array(
		'desc' => 'Seedtag',
		'default_activation' => 0,
		'class_name' => 'seedtag',
		'implementation' => 'seedtag.php',
		'action' => array('wp_head'),
		'callback' => 'seedtag_implementation',
		'is_tag' => true,
	),
	'seedtag_mobile' => array(
		'desc' => 'Seedtag mobile',
		'default_activation' => 1,
		'class_name' => 'seedtag',
		'implementation' => 'seedtag.php',
		'action' => array('wp_head'),
		'callback' => 'seedtag_mobile_implementation',
		'is_tag' => true,
	),
	'seedtag_desktop_tablet' => array(
		'desc' => 'Seedtag Desktop & Tablet',
		'default_activation' => 1,
		'class_name' => 'seedtag',
		'implementation' => 'seedtag.php',
		'action' => array('wp_head'),
		'callback' => 'seedtag_desktop_tablet_implementation',
		'is_tag' => true,
	),
	'player_sticky' => array(
			'desc' => 'Player Sticky',
			'default_activation' => 1,
			'class_name' => 'player_sticky',
			'implementation' => 'player_sticky.php',
			'action' => array('wp_head'),
			'shortcodes' => array(
				'sticker_video' => 'player_sticky_shortcode',
				'player_nosticker' => 'player_nosticker_shortcode',
			),
			'is_tag' => false,


	),
	'ariane_labs_push' => array(
		'desc' => 'Ariane Lab push notif',
		'default_activation' => 0,
		'class_name' => 'ariane_labs',
		'implementation' => 'arianelabs.php',
		'action' => array( 'wp_footer', 10, 1 ),
		'callback' =>  'arianlabs_push_notification_implementation',
		'is_tag' => true,
 	),
	'ad_you_like' => array(
		'desc' => 'AdYouLike',
		'default_activation' => 0,
		'class_name' => 'adyoulike',
		'implementation' => 'adyoulike.php',
		'callback' =>  'adyoulike_init',
		'shortcodes' => array (
			'adyoulike' => 'adyoulike_shortcode'
		),
		'is_tag' => true,
    ),
	'Simple_adyoulike'	=> array(
		'desc'					=> 'Simple adyoulike',
		'default_activation'	=> is_dev(),
		'implementation'		=> 'simple_adyoulike.php',
		'class_name'			=> 'simple_adyoulike',
		'callback'				=> 'simple_adyoulike_implementation',
		'action'				=> array('wp_footer', 9, 1 ),
		'is_tag'				=> true,
	),
	'mediametrie' => array(
		'desc' 					=> 'Mediametrie',
		'default_activation' 	=> 0,
		'class_name' 			=> 'mediametrie',
		'implementation' 		=> 'mediametrie.php',
		'action' 				=> array( 'wp_footer', 10, 1 ),
		'callback' 				=>  'mediametrie_implementation',
		'config'	 			=> array(
			'serial'				=> "800000000086",
			),
		'is_tag' => true,
    ),
    'mbrand' => array(
		'desc' => 'MBrand',
		'default_activation' => 0,
		'class_name' => 'admbrand',
		'implementation' => 'admbrand.php',
		'callback' =>  'admbrand_init',
		'is_tag' => true,
    ),

    'innity_video_desktop_tablet' => array(
		'desc' => 'Innity Welcome video desktop + tablet (interstitiel)',
		'default_activation' => 0,
		'class_name' => 'adinnity',
		'implementation' => 'adinnity.php',
		'callback' => 'innity_init',
		'is_tag' => true,
	),
	'keywee' => array(
		'desc' => 'keywee',
		'default_activation' => 0,
		'implementation' => 'keywee.php',
		'class_name' => 'keywee_partner',
	    'callback' => 'keywee_implementation',
		'action' => array('wp_head'),
		'is_tag' => true,
	),
	'floodlight' => array(
		'desc' => 'Floodlight',
		'default_activation' => 0,
		'class_name' => 'floodlight',
		'implementation' => 'floodlight.php',
		'action' => array( 'rew_top_head', 10, 1 ),
		'callback' =>  'floodlight_implementation',
		'is_tag' => true,
    ),
	'notify' => array(
		'desc' => 'Notify',
		'default_activation' => 0,
		'class_name' => 'Notify',
		'implementation' => 'notify.php',
		'action' => array( 'wp_footer', 10, 1 ),
		'callback' =>  'notify_implementation',
		'is_tag' => true,
    ),
    'sdk_beopinion' => array(
		'desc' => 'SDK BeOpinion',
		'default_activation' => 0,
		'class_name' => 'sdk_beopinion',
		'implementation' => 'sdk_beopinion.php',
		'callback' => 'sdk_beopinion_init',
		'is_tag' => true,
	),
	'keymantics' => array(
		'desc' => 'Keymantics',
		'default_activation' => 0,
		'class_name' => 'keymantics',
		'implementation' => 'keymantics.php',
		'callback' => 'keymantics_implementation',
		'action' => array('after_wp_footer', 10, 1),
		'is_tag' => true,
	),

	'meta_fb_page' => array(
		'desc' => 'Facebook : Authorize Pages',
		'default_activation' => 0,
		'class_name' => 'meta_fb_pages',
		'implementation' => 'meta_fb_pages.php',
		'callback' => 'meta_fb_implementation',
		'action' => array('wp_head', 1),
		'is_tag' => false,
	),
	'pixel' => array(
		'is_tag' => true,
	),
	'pixel_td' => array(
		'desc' => 'Pixel TD',
		'default_activation' => 0,
		'class_name' => 'pixel_td',
		'implementation' => 'pixel_td.php',
		'callback' => 'pixel_td_init',
		'action' => array( 'wp_footer', 10 ),
		'is_tag' => true,
	),
	'Nugg' => array (
        'desc' => 'Nugg',
        'default_activation' => 1 ,
        'class_name' => 'Nugg',
        'implementation' => 'nugg.php',
        'action' => array('wp_footer', 10, 1),
        'callback' =>  'nugg_implementation',
        'is_tag' => true,
    ),
    'Nielsen' => array (
        'desc' => 'Nielsen',
        'default_activation' => 0,
        'class_name' => 'Nielsen',
        'implementation' => 'nielsen.php',
        'action' => array('wp_footer', 10, 1),
        'callback' =>  'nielsen_implementation',
        'callback_admin' => 'admin_nielsen_implementation',
        'is_tag' => true,
    ) ,
    'Kidioui' => array (
    	'is_tag' => true,
    ) ,
    'kidioui_iframe' => array (
        'desc' => 'Kidioui Iframe',
        'default_activation' => 1,
        'implementation' => 'kidioui_iframe.php',
      	'class_name' => 'kidioui_iframe',
        'shortcodes' => array (
            'kidioui_iframe'=> 'kidioui_iframe_short_code',
        ) ,
        'is_tag' => true,
    ),
    'paruvendu' => array(
    	'is_tag' => true,
    ),
    'paruvendu_iframe' => array (
        'desc' => 'paruvendu Iframe',
        'default_activation' => 1,
        'implementation' => 'paruvendu_iframe.php',
      	'class_name' => 'paruvendu_iframe',
        'shortcodes' => array (
            'paruvendu_iframe'=> 'paruvendu_iframe_short_code',
        ) ,
        'is_tag' => true,
    ),
    'likebox' => array (
        'desc' => 'Facebook likebox',
        'default_activation' => 1,
        'implementation' => 'likebox.php',
      	'class_name' => 'likebox',
        'shortcodes' => array (
            'likebox_partner'=> 'likebox_sc',
        ),
        'is_tag' => false,
    ),
    'aalkabot' => array (
        'desc' => 'Aalkabot (reserver un essai)',
        'default_activation' => 1,
        'implementation' => 'aalkabot.php',
      	'class_name' => 'aalkabot',
        'shortcodes' => array (
            'aalkabot'=> 'aalkabot_sc',
        ),
        'is_tag' => true,
    ),
    'analytics' => array(
    	'desc' => 'Analytics',
        'default_activation' => 1,
        'implementation' => 'analytics_partner.php',
      	'class_name' => 'analytics_partner',
      	'callback' => 'init_google_outbound_script',
      	'action' => array('partners_core_ready', 1000, 1) ,
        'shortcodes' => array (
            'google_analytics'=> 'shortcode_ga',
        ),
        'is_tag' => false,
    ),
    'newsletter_crm' => array(
    	'desc' => 'Newsletter footer CRM',
        'default_activation' => 1,
        'implementation' => 'newsletter_crm.php',
      	'class_name' => 'newsletter_crm',
        'shortcodes' => array (
            'footer_newsletter'=> 'nl_footer_newsletter',
        ),
        'is_tag' => false,
    ),
    'marques_a_suivre' => array(
    	'desc' => 'marques à suivre',
        'default_activation' => 1,
        'implementation' => 'marques_asuivre.php',
      	'class_name' => 'marques_asuivre',
      	'callback' => 'init_marques_a_suivre',
		'shortcodes' => array (
			'marques_a_suivre'=> 'callback_marques_a_suivre',
		) ,

      	'is_tag' => false,
    ),

	'gtm' => array(
		'desc' => 'Google tag Manager',
		'default_activation' => 0,
		'class_name' => 'Gtm',
		'implementation' => 'google-tag-manager.php',
		'callback' => 'init_gtm',
		'is_tag' => true,
	),
	'criteo_adblocks' => array(
		'is_tag' => true,
	),
	'dfp_v2' => array(
		'desc' => 'DFP ( Google ) v3 desktop',
		'default_activation' => 1,
		'implementation' => 'dfp_v2.php',
		'class_name' => 'dfp_v2',
		'is_tag' => true,
		'shortcodes' => array (
			'dfp_v2'=> 'dfp_short_code',
		) ,
        'callback' => 'init' ,
		
	),
	'rothelec_track' => array(
		'is_tag' => true,
	),
	'Adikteev' => array(
		'desc' => 'Adikteev',
        'default_activation' => 0,
        'implementation' => 'adikteev.php',
		'is_tag' => true,
		'action' => array('rew_top_head'),
        'callback' =>  'adikteev_implementation'
	),
	'culture-g' => array(
		'is_tag' => true,
	),
	'adikteev_recette' => array(
		'is_tag' => true,
	),
	'drhat' => array(
		'is_tag' => true,
	),
	'acpm' => array(
		'desc'					=> 'ACPM',
		'class_name' 			=> 'acpm',
		'implementation' 		=> 'acpm.php',
		'action' 				=> array( 'wp_footer', 10, 1 ),
		'callback' 				=>  'acpm_implementation',
		'is_tag' => true,
	),
	'sentry' => array(
		'desc'					=> 'Sentry',
		'class_name' 			=> 'sentry',
		'implementation' 		=> 'sentry.php',
		'action' 				=> array( 'wp_head'),
		'callback' 				=>  'sentry_implementation',
		'is_tag' 				=> true,
		'default_activation' 	=> 0,
	),
	'bliink_mobile' => array(
		'desc'					=> 'Bliink inpicture mobile',
		'class_name' 			=> 'bliink',
		'implementation' 		=> 'bliink.php',
		'callback' 				=>  'bliink_mobile_implementation',
		'is_tag' 				=> true,
		'default_activation' 	=> 0,
	),
	'bliink_desktop_tablet' => array(
		'desc'					=> 'Bliink inpicture desktop tablet',
		'class_name' 			=> 'bliink',
		'implementation' 		=> 'bliink.php',
		'callback' 				=>  'bliink_desktop_tablet_implementation',
		'is_tag' 				=> true,
		'default_activation' 	=> 0,
	),
	'bliink_article' => array(
		'desc'					=> 'Bliink inpicture article simple',
		'class_name' 			=> 'bliink',
		'implementation' 		=> 'bliink.php',
		'callback' 				=>  'bliink_article_implementation',
		'is_tag' 				=> true,
		'default_activation' 	=> 0,
	),
    'index_exchange' => array(
        'desc'					=> 'Index Exchange',
        'class_name' 			=> 'index_exchange',
        'implementation' 		=> 'index_exchange.php',
        'action' 				=> array('wp_head', 9, 1 ),
        'callback' 				=> 'index_exchange_implementation',
        'is_tag' 				=> true,
    ),
    'index_exchange_mobile' => array(
    	'is_tag' => true,
    ),
    'quantcast' => array(
    	'desc' => 'QuantCast',
    	'default_activation' => is_dev(),
    	'implementation' => 'quantcast.php',
    	'callback' => 'init_quant_cast',
    	'class_name' => 'Quantcast',
    	'action' => array('wp_head', 3, 1),
    	'is_tag' => true,
    ),
    'disqus_rw' => array(
		'desc' => 'Disqus RW',
		'default_activation' => 0,
		'class_name' => 'disqus',
		'implementation' => 'disqus_rw.php',
		'callback' => 'disqus_implementation',
		'is_tag' => true,
	),
	'captify' => array(
    	'desc' => 'Captify',
    	'default_activation' => is_dev(),
    	'implementation' => 'captify.php',
    	'callback' => 'init_captify',
    	'class_name' => 'Captify',
    	'action' => array('wp_footer'),
		'is_tag' => true,
	),
	 'crm_box_article' => array(
		'desc' => 'CRM box article',
		'default_activation' => is_dev(),
		'class_name' => 'crm_box_article',
		'implementation' => 'crm-box-article.php',
		'callback' =>  'init',
    ),
	'crm_box_article_mobile' => array(
		'desc' => 'CRM box article mobile',
		'default_activation' => is_dev(),
		'class_name' => 'crm_box_article',
		'implementation' => 'crm-box-article.php',
		'callback' =>  'init_mobile',
    ),
    'pinit_img' => array(
		'desc'					=> 'pinit_img',
		'class_name' 			=> 'pinit_img',
		'implementation' 		=> 'pinit_img.php',
		'action' 				=> array( 'wp_head'),
		'callback' 				=>  'pinit_img_implementation',
		'default_activation' 	=> is_dev(),
	),
	'bliink_article_desktop' => array(
		'desc'					=> 'Bliink Desktop article simple',
		'class_name' 			=> 'bliink',
		'implementation' 		=> 'bliink.php',
		//'action' 				=> array( 'wp'),
		'callback' 				=>  'bliink_article_desktop_implementation',
		'is_tag' 				=> true,
		'default_activation' 	=> 0,
		'setting_bouton'		=> home_url().'/wp-admin/options-general.php?page=ciblage_bliink_target_admin',
	),
	'bliink_article_mobile' => array(
		'desc'					=> 'Bliink Mobile article simple',
		'class_name' 			=> 'bliink',
		'implementation' 		=> 'bliink.php',
		//'action' 				=> array( 'wp'),
		'callback' 				=>  'bliink_article_mobile_implementation',
		'is_tag' 				=> true,
		'default_activation' 	=> 0,
		'setting_bouton'		=> home_url().'/wp-admin/options-general.php?page=ciblage_bliink_target_admin',
	),
	'bliink_diapo_desktop' => array(
		'desc'					=> 'Bliink Desktop article Diaporama',
		'class_name' 			=> 'bliink',
		'implementation' 		=> 'bliink.php',
		//'action' 				=> array( 'wp'),
		'callback' 				=>  'bliink_diapo_desktop_implementation',
		'is_tag' 				=> true,
		'default_activation' 	=> 0,
		'setting_bouton'		=> home_url().'/wp-admin/options-general.php?page=ciblage_bliink_target_admin',
	),
	'bliink_diapo_mobile' => array(
		'desc'					=> 'Bliink Mobile article Diaporama',
		'class_name' 			=> 'bliink',
		'implementation' 		=> 'bliink.php',
		//'action' 				=> array( 'wp'),
		'callback' 				=>  'bliink_diapo_mobile_implementation',
		'is_tag' 				=> true,
		'default_activation' 	=> 0,
		'setting_bouton'		=> home_url().'/wp-admin/options-general.php?page=ciblage_bliink_target_admin',
	),
	'bliink_video_desktop' => array(
		'desc'					=> 'Bliink Desktop article Video',
		'class_name' 			=> 'bliink',
		'implementation' 		=> 'bliink.php',
		//'action' 				=> array( 'wp'),
		'callback' 				=>  'bliink_video_desktop_implementation',
		'is_tag' 				=> true,
		'default_activation' 	=> 0,
		'setting_bouton'		=> home_url().'/wp-admin/options-general.php?page=ciblage_bliink_target_admin',
	),
	'bliink_video_mobile' => array(
		'desc'					=> 'Bliink Mobile article Video',
		'class_name' 			=> 'bliink',
		'implementation' 		=> 'bliink.php',
		//'action' 				=> array( 'wp'),
		'callback' 				=>  'bliink_video_mobile_implementation',
		'is_tag' 				=> true,
		'default_activation' 	=> 0,
		'setting_bouton'		=> home_url().'/wp-admin/options-general.php?page=ciblage_bliink_target_admin',
	),
	'motoblouz_products' => array(
		'desc' => 'Motoblouz widget',
		'default_activation' => 0,
		'implementation' => 'motoblouz_widget.php',
		'class_name' => 'Motoblouz_widget',
		'callback' => 'init_motoblouz_widget',
		'shortcodes' => array (
			'shopping_box_widget'=> 'shopping_box_widget_products',
		) ,

		'is_tag' => false,
	),
	'prebid' => array(
		'desc' => 'Prebid',
		'default_activation' => 0,
		'class_name' => 'prebid',
		'implementation' => 'prebid.php',
		'callback' => 'init',
		'is_tag' => true,
	),
	'amazon' => array(
		'desc'					=> 'Amazon',
		'default_activation' 	=> 0,
		'implementation' 		=> 'amazon.php',
		'class_name' 			=> 'Amazon',
		'callback' 				=> 'init_amazon',
		'is_tag' 				=> true,
	),
	'adways' => array(
		'desc'					=> 'Adways Native Video',
		'default_activation' 	=> 0,
		'implementation' 		=> 'adways.php',
		'class_name' 			=> 'Adways',
		'callback' 				=> 'init_adways',
		'is_tag' 				=> true,
	),
	'skimlinks' => array(
		'desc'					=> 'Skimlinks',
		'class_name' 			=> 'skimlinks',
		'implementation' 		=> 'skimlinks.php',
		'callback' 				=>  'skimlinks_implementation',
		'is_tag' 				=> true,
		'default_activation' 	=> 0,
	),
	'soundcast' => array(
		'desc'					=> 'Soundcast',
		'default_activation' 	=> 0,
		'implementation' 		=> 'soundcast.php',
		'class_name' 			=> 'soundcast_partner',
		'callback' 				=> 'soundcast_init',
		'callback_admin'        => 'soundcast_init_admin',
		'shortcodes' 			=> array (
			'audio_ads' => 'player_audio_shortcode'
		),
		'is_tag' 				=> true,
	),
	'mindlytix' => array(
		'desc'					=> 'mindlytix',
		'class_name' 			=> 'mindlytix',
		'implementation' 		=> 'mindlytix.php',
		'callback' 				=>  'mindlytix_implementation',
		'is_tag' 				=> true,
		'default_activation' 	=> 0,
		'action' => array( 'wp_head', 10 ),
	),
	'appnexus' => array(
		'desc'					=> 'Appnexus',
		'class_name' 			=> 'appnexus',
		'implementation' 		=> 'appnexus.php',
		'callback' 				=>  'appnexus_implementation',
		'is_tag' 				=> true,
		'default_activation' 	=> 0,
		'action' => array( 'wp_head', 10 ),
	),
	'cloud_media' => array(
		'desc'					=> 'Cloud Media',
		'default_activation' 	=> 0,
		'implementation' 		=> 'cloud_media.php',
		'class_name' 			=> 'cloud_media_partner',
		'callback' 				=> 'cloud_media_init',
		'is_tag' 				=> true,
	),
	'widget_ops' => array(
		'desc'					=> 'Widget OPS',
		'default_activation' 	=> 0,
		'implementation' 		=>  is_dev('monetisation_widget_ops_v2_7065') ? 'widget_ops_v2.php' : 'widget_ops.php',
		'class_name' 			=> 'Widget_ops',
		'callback' 				=> 'widget_ops_init',
		'callback_admin'        => 'widget_ops_init_admin',
		'shortcodes' => array(
			'widget_ops_end_content' => 'widget_ops_end_content_implementation',
		),
	),
  'publica' => array(
    'desc'					     => 'Publica',
    'default_activation' => 0,
    'implementation' 		 => 'publica.php',
    'class_name' 			   => 'Publica',
    'callback' 				   => 'publica_implementation',
    'action' => array('wp_footer', 10, 1),
    'is_tag' 				=> true,
  ),
  'cmp_didomi' => array(
		'desc'					=> 'CMP didomi',
		'class_name' 			=> 'cmp_didomi',
		'implementation' 		=> 'cmp_didomi.php',
		'callback' 				=> 'cmp_didomi_implementation',
		'action' => array('wp_head', 1),
    	'is_tag' 				=> true,

	),
	'brecoveryr' => array(
		'desc' => 'BrecoveryR',
		'default_activation' => 0,
		'implementation' => 'brecoveryr.php',
		'class_name' => 'BrecoveryR',
		'callback' => 'init_script',
		'action' => array('wp_head', 99,1) ,
	),
    'pixel_outbrain' => array(
        'desc'				  => 'Pixel Outbrain',
        'default_activation'  => 0,
        'implementation' 	  => 'pixel_outbrain.php',
        'class_name' 		  => 'Pixel_outbrain',
        'callback' 			  => 'pixel_outbrain_implementation',
        'action'              => array('wp_footer'),
        'is_tag' 			  => true,
    ),
    'dfp_amp' => array(
		'desc' => 'AMP DFP',
		'default_activation' => 1,
		'implementation' => 'dfp_amp.php',
		'class_name' => 'dfp_amp',
		'is_tag' => true,
		'shortcodes' => array (
			'dfp_amp'=> 'dfp_amp_shortcode',
		),
		'is_tag' => true,
	),
	'taboola_amp' => array(
		'desc' => 'AMP taboola',
		'default_activation' => 0,
		'implementation' => 'taboola_amp.php',
		'class_name' => 'taboola_amp',
		'callback' =>  'init',
		'shortcodes' => array(
			'taboola_amp'=> 'taboola_amp_shortcode',
		),
		'is_tag' => true,
	),
	'pixel_taboola' => array(
		'desc' => 'Pixel_Taboola',
		'default_activation' => 0,
		'implementation' => 'pixel_taboola.php',
		'class_name' => 'pixel_taboola_partner',
		'callback' => 'pixel_taboola_implementation',
		'action' => array('wp_head'),
		'is_tag' => true,

	),
	'pixel_tracking' => array(
		'desc' => 'Credit conso tracking',
		'default_activation' => 0,
		'implementation' => 'credit_conso_tracking.php',
		'class_name' => 'pixel_tracking',
		'callback' => 'pixel_tracking_implementation',
		'action' => array('wp_footer'),
	),
	'sync' => array(
		'desc' => 'Sync',
		'default_activation' => 0,
		'implementation' => 'sync.php',
		'class_name' => 'sync_partner',
		'callback' => 'sync_implementation',
		'action' => array('before_head_tag'),
	),
	'twitter_analytics' => array(
		'desc' => 'Google Analytics pour Twitter',
		'default_activation' => 0,
		'implementation' => 'twitter_analytics.php',
		'class_name' => 'twitter_analytics_partner',
		'callback' => 'twitter_analytics_implementation',
		'action' => array('wp_footer'),
	),
	'pixel_taboola' => array(
		'desc' => 'Pixel_Taboola',
		'default_activation' => 0,
		'implementation' => 'pixel_taboola.php',
		'class_name' => 'pixel_taboola_partner',
		'callback' => 'pixel_taboola_implementation',
		'action' => array('wp_head'),
	),
	'pixel_tracking' => array(
		'desc' => 'Credit conso tracking',
		'default_activation' => 0,
		'implementation' => 'credit_conso_tracking.php',
		'class_name' => 'pixel_tracking',
		'callback' => 'pixel_tracking_implementation',
		'action' => array('wp_footer'),
	),
	'mediarithmics' => array(
		'desc' 					=> 'Mediarithmics',
		'default_activation' 	=> 0,
		'class_name' 			=> 'Mediarithmics',
		'implementation' 		=> 'mediarithmics.php',
		'action' 				=> array('wp_footer', 10, 1),
		'callback' 				=>  'mediarithmics_implementation',
		'is_tag' => true,
    ),
  'yieldkit' => array(
    'desc'					     => 'Yieldkit',
    'default_activation' => 0,
    'implementation' 		 => 'yieldkit.php',
    'class_name' 			   => 'Yieldkit',
    'callback' 				   => 'yieldkit_implementation',
    'action' => array('wp_head'),
    'is_tag' 				=> true,
  ),
  	'widget_prefs' => array(
		'desc' => 'Widget Prefs',
		'default_activation' => 0,
		'implementation' => 'widget-perfs.php',
		'class_name' => 'Widget_perfs',
		'callback' => 'widget_perfs_init',
		'shortcodes' => array(
			'widget_perfs'=> 'widget_perfs_shortcode',
		),
		'action' => array('after_wp_footer'),
	),
	  'missena_mobile' => array(
        'desc'				  => 'Missena mobile',
        'default_activation'  => 0,
        'implementation' 	  => 'missena.php',
        'class_name' 		  => 'missena',
        'callback' 			  => 'missena_mobile_implementation',
        'action'              => array('wp_footer'),
        'is_tag' 			  => true,
    ),
  'web_push' => array(
    'desc'					     => 'Web push',
    'default_activation' => 0,
    'implementation' 		 => 'web_push.php',
    'class_name' 			   => 'Web_push',
    'callback' 				   => 'web_push_implementation',
    'action' => array('wp_footer', 10, 1),
    'is_tag' 				=> true,
  ),
  'leesten' => array(
		'desc' => 'Google leesten',
		'default_activation' => 0,
		'implementation' => 'leesten.php',
		'class_name' => 'Leesten',
		'callback' => 'leesten_implementation',
		'is_tag' => true,
   ),
  'Avant_widget_OPS' => array(
		'desc' => "Avant widget OPS",
		'default_activation' => 0 ,
		'class_name' => 'Avant_widget_OPS',
		'implementation' => 'Avant_widget_OPS.php',
		'callback' =>  'implementation_Avant_widget_OPS',
	),
	'gravity'=>[
		'desc'=>'gravity partner',
		'default_activation' => 0 ,
		'class_name' => 'Gravity',
		'implementation' => 'gravity.php',
		'callback' =>  'gravity_implementation',
		'action' => array('wp_footer'),
	],
	'wysistat' => array(
		'desc' => 'Wysistat',
		'default_activation' => 0,
		'implementation' => 'wysistat.php',
		'class_name' => 'wysistat',
		'callback' => 'init',
	),
	'pepsia' => array(
		'desc' => 'Pepsia',
		'default_activation' => 0,
		'implementation' => 'pepsia.php',
		'class_name' => 'pepsia_partner',
		'shortcodes' => array(
			'pepsia' => 'pepsia_shortcode',
		),
	),
   'staytuned' => array(
		'desc'				=> 'Staytuned',
		'default_activation'		=> 0,
		'implementation'		=> 'staytuned.php',
		'class_name'			=> 'staytuned_partner',
		'shortcodes'			=> array (
			'staytuned_podcast' => 'staytuned_podcast_shortcode'
		),
		'action'			=> array('wp_head'),
		'is_tag'			=> true,
   ),
    'avantis' => array(
	   	'desc'				  => 'Avantis',
	   	'default_activation'  => 0,
	   	'implementation' 	  => 'avantis.php',
	   	'class_name' 		  => 'avantis',
	   	'callback' 			  => 'avantis_implementation',
	   	'action'              => array('wp_footer'),
	   	'is_tag' 			  => true,
   ),
   'art19' => array(
		'desc' => 'Art19',
		'default_activation' => 0,
		'implementation' => 'art19.php',
		'class_name'     => 'Art19',
		'shortcodes' => array(
			'art19' => 'art19_implementation',
		),
	),
	'edisound' => array(
		'desc' => 'Edisound',
		'default_activation' => 0,
		'implementation' => 'edisound.php',
		'class_name'     => 'Edisound',
		'shortcodes' => array(
			'edisound' => 'edisound_implementation',
		),
	),
);


/**
 *
 */
class rw_partner {

	public function __construct( $name, $partner ) {
		$this->name  = $name ;
		$this_ = $this ;
		if ( isset( $partner['config'] ) ) {
			$this->config  = $partner['config'] ;
		}
		$shortcodes = isset( $partner['shortcodes'] ) ? $partner['shortcodes']  : null ;
		$cached_shortcodes = isset( $partner['cached_shortcodes'] ) ? $partner['cached_shortcodes']  : null ;

		if ( is_admin() ) {
			$action = isset( $partner['action_admin'] ) ? $partner['action_admin']  : null ;
			$callback = isset( $partner['callback_admin'] ) ? $partner['callback_admin']  : null ;
			$action_name = (is_array( $action )) ? $action[0]  : $action ;
			$priority = (is_array( $action ) && isset( $action[1] )) ? $action[1]  : 10 ;
			$accepted_args = (is_array( $action ) && isset( $action[2] )) ? $action[2]  : 1 ;
		} else {
			$action = isset( $partner['action'] ) ? $partner['action']  : null ;
			$callback = isset( $partner['callback'] ) ? $partner['callback']  : null ;
			$code = isset( $partner['code'] ) ? $partner['code']  : null ;
			$action_name = (is_array( $action )) ? $action[0]  : $action ;
			$priority = (is_array( $action ) && isset( $action[1] )) ? $action[1]  : 10 ;
			$accepted_args = (is_array( $action ) && isset( $action[2] )) ? $action[2]  : 1 ;
		}

		if ( $callback ) {
			if ( $action_name ) {
				add_action( $action_name, array( $this, $callback ), $priority, $accepted_args );
			} else {
				// call_user_func_array(array($this, $callback), array());
				$this->$callback();
			}
		}

		if ( ! is_admin() ) {
			if ( $code ) {
				add_action($action_name, function () use ( $code ) {
					echo $code ;
				}, $priority, $accepted_args);
			}

			if ( $shortcodes ) {
				foreach ( $shortcodes as $shortcode => $function ) {
					add_action('partners_core_ready', function () use ( $this_, $shortcode, $function ) {
						add_shortcode( $shortcode, array( $this_, $function ) );
					});
				}
			}
			if ( $cached_shortcodes ) {
				foreach ( $cached_shortcodes as $shortcode => $function ) {
					add_action('partners_core_ready', function () use ( $this_, $shortcode, $function ) {
						add_cached_shortcode( $shortcode, array( $this_, $function ) );
					});
				}
			}
		}
	}

	public function action_admin() {
	}

	public function action_front() {
	}

	public function get_param( $param, $default_if_not_set = '' ) {
		$v = (isset( $this->config[ $param ] )) ? $this->config[ $param ] : $default_if_not_set ;
		return $v;
	}

	/**
	 * Supprimer les tags pubs données en paramètre
	 * @param array $tags tableau de tags à supprimer
	 * @return void
	 */
	static function remove_selected_tags_pubs( $tags ) {
		foreach ( $tags as $tag ) {
			add_filter( 'partner_filter_' . $tag, '__return_false' );
		}
	}
}

/*if( is_dev('dissociation_inread_interface_partenaire_151724296') ){
	unset( $partners_default['inread'] );

	$partners_default = array_merge(
	array(
		'inread_desktop' => array (
			'desc' => 'Inread Desktop',
			'default_activation' => 0,
			'implementation' => 'inread.php',
			'shortcodes' => array (
				'inRead' => 'inread_implementation'
			),
			'callback' => 'init_inread_desktop',
			'is_tag' => true,
		),
		'inread_tablet' => array (
			'desc' => 'Inread Tablet',
			'default_activation' => 1,
			'implementation' => 'inread.php',
			'shortcodes' => array (
				'inRead' => 'inread_implementation'
			),
			'callback' => 'init_inread_tablet',
			'is_tag' => true,
		),
		'inread_mobile' => array (
			'desc' => 'Inread Mobile',
			'default_activation' => 1,
			'implementation' => 'inread.php',
			'shortcodes' => array (
				'inRead' => 'inread_implementation'
			),
			'callback' => 'init_inread_mobile',
			'is_tag' => true,
		),
	)
	, $partners_default);
}*/



// 2000 Apres les init de dedicated_area
add_action( 'wp', 'partners_core', 2000 );

if(is_admin()){
	add_action( 'init_reworld', 'partners_core' );
}

function partners_core() {
	global $partners, $site_config, $partners_default,$site_config_js ;

	if(is_feed()){
		return false;
	}
	$active = false;
	$name_option   = 'partners_activation' ;
	$dedicated_area = false ;

	do_action( 'partners_options' );


	if ( function_exists( 'is_dedicated_area' ) ){
		$dedicated_area = is_dedicated_area() ;
	}

	if (!empty($dedicated_area["category"])  ) {

		$name_option   = 'partners_activation_' . $dedicated_area['category'] ['category'] ;
		$dedicate_partners = isset( $dedicated_area['category']['partners'] ) ? $dedicated_area['category']['partners']  : array() ;
		/*
        if ( get_option('disable_sitepartners_' . $current_category) ){
            $partners = $dedicate_partners  ;
        }else {*/

		$site_partners = $partners  ;
		$site_partners = apply_filters( 'init_partners', $site_partners );
		$partners = array_merge( $partners, $dedicate_partners );
		$site_partners_activation = get_option( apply_filters( 'name_option', 'partners_activation' ), array() );

		// }
	}

	$partners = apply_filters( 'init_partners', $partners );

	$name_option  = apply_filters( 'name_option', $name_option );
	$partners_activation = get_option( $name_option, array() );
	foreach ( $partners as $key => $partner ) {
		$active = false;
		$shortcodes = isset( $partner['shortcodes'] ) ? $partner['shortcodes']  : null ;
		$cached_shortcodes = isset( $partner['cached_shortcodes'] ) ? $partner['cached_shortcodes']  : null ;
		$implementation = isset( $partner['implementation'] ) ? $partner['implementation']  : null ;


		if(empty( $partner['comportement_inverse']) || is_admin()){

			if ( $dedicated_area && ! array_key_exists( $key, $dedicate_partners ) ) {
				$site_activation = isset( $site_partners_activation[ $key ] ) ? $site_partners_activation[ $key ]['active'] : $partner['default_activation'] ;
				if ( $site_activation ) {
					$active = isset( $partners_activation[ $key ] ) ? $partners_activation[ $key ]['active'] : 1 ;
				}
			} else {
				$active = isset( $partners_activation[ $key ] ) ? $partners_activation[ $key ]['active'] : $partner['default_activation'] ;
			}
		}else{

			$active = false;
		}
	

		$active = apply_filters( 'partner_filter_' . $key, $active );
		$active = apply_filters( 'partner_filters', $active, $key ,  $partner );
		if(!empty($_GET['desactive_partners'])){
			$desactive_partners = $_GET['desactive_partners'] ;
			$desactive_partners = explode(',',$desactive_partners ) ;
			if(in_array($key, $desactive_partners)){
				$active = false ;
			}
		}

		if(!empty($_GET['no_pub']) && $_GET['no_pub'] == 'tags' ){
			if(!empty ($partner['is_tag'])){
				$active= false ;
			}
		}elseif(!empty($_GET['no_pub'])){
			$active= false ;
		}
		
		if(!empty($_GET['active_partners'])){
			$active_partners = $_GET['active_partners'] ;
			$active_partners = explode(',',$active_partners ) ;
			if(in_array($key, $active_partners)){
				$active = true ;
			}
		}
		if ( $active ) {
			if ( $implementation ) {
				$site_config_js['partners'][$key] = true;
				require(RW_THEME_DIR. '/include/functions/implementation/' . $implementation  );
			}

			$config = isset( $partner['config'] ) ? $partner['config']  : null ;
			$class_name = isset( $partner['class_name'] ) ? $partner['class_name']  : null ;
			if ( $class_name ) {
				global $instance_partners;
				$instance = new $class_name ($key, $partner) ;
				$instance_partners[$key] = $instance ;
			} else {
				if ( is_admin() ) {
					$action = isset( $partner['action_admin'] ) ? $partner['action_admin']  : null ;
					$callback = isset( $partner['callback_admin'] ) ? $partner['callback_admin']  : null ;
					$action_name = (is_array( $action )) ? $action[0]  : $action ;
					$priority = (is_array( $action ) && isset( $action[1] )) ? $action[1]  : 10 ;
					$accepted_args = (is_array( $action ) && isset( $action[2] )) ? $action[2]  : 1 ;
				} else {
					$action = isset( $partner['action'] ) ? $partner['action']  : null ;
					$callback = isset( $partner['callback'] ) ? $partner['callback']  : null ;
					$code = isset( $partner['code'] ) ? $partner['code']  : null ;
					$action_name = (is_array( $action )) ? $action[0]  : $action ;
					$priority = (is_array( $action ) && isset( $action[1] )) ? $action[1]  : 10 ;
					$accepted_args = (is_array( $action ) && isset( $action[2] )) ? $action[2]  : 1 ;
				}
				if ( $config ) {
					foreach ( $config as $key => $value ) {
						$site_config[ $key ] = $value ;
					}
				}

				if ( $callback ) {
					if ( $action_name ) {
						add_action( $action_name, $callback, $priority, $accepted_args );
					} else {
						call_user_func_array( $callback, array() );
					}
				}

				if ( ! is_admin() ) {
					if ( $code ) {
						add_action($action_name, function () use ( $code ) {
							echo $code ;
						}, $priority, $accepted_args);
					}
					if ( $shortcodes ) {
						foreach ( $shortcodes as $shortcode => $function ) {
							add_action('partners_core_ready', function () use ( $shortcode, $function ) {
								add_shortcode( $shortcode, $function );
							});
						}
					}
					if ( $cached_shortcodes ) {
						foreach ( $cached_shortcodes as $shortcode => $function ) {
							add_action('partners_core_ready', function () use ( $shortcode, $function ) {
								add_cached_shortcode( $shortcode, $function );
							});
						}
					}
				}
			}

			if ( isset( $_GET['debug_partners'] ) ) {
				echo "<!-- Active Partner : $key -->" ;
			}
		} else {
			if ( $implementation ) {
				require(RW_THEME_DIR. '/include/functions/implementation/' . $implementation  );
			
				
			}

			if ( $cached_shortcodes ) {
				if ( $shortcodes ) {
					$shortcodes = array_merge( $shortcodes, $cached_shortcodes );
				} else {
					$shortcodes = $cached_shortcodes;
				}
			}

			if ( $shortcodes ) {
				foreach ( $shortcodes as $shortcode => $function ) {
					add_shortcode( $shortcode, '__return_false' );
				}
			}
		}
	}
	do_action( 'partners_core_ready' );
}


add_filter('init_partners', function ( $partners ) {
	global $partners_default ;
	if ( count( $partners ) ) {
		foreach ( $partners as $key => &$partner ) {
			if ( isset( $partners_default[ $key ] ) ) {
				if(!isset($partners_default[ $key ]['default_activation'])){
					$partners_default[ $key ]['default_activation'] = false ;
				}
				$partner = wp_parse_args( $partner,   $partners_default[ $key ] );

				
			}
		}
	}
	return $partners ;
});