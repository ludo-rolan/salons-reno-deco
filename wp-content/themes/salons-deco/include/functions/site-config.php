<?php

$himediads_fullscreen_html = <<<FULLSCREEN
<!-- BEGIN Hi-Media Media AdTag, maisontravaux.fr 1x1 - v1.0 -->
<div class="hmads_1x1">

    <script type="text/javascript">
        (function() {
            var utTagCountry = 'fr';
            var utTagSite = 'maisontravaux.fr';
            var utTagZone = 'fullscreen';
            var utTagUrl = ('https:' == document.location.protocol ? 'https://' : 'http://') +
                'js.himediads.com/js' +
                '?country=' + encodeURIComponent(utTagCountry) +
                '&site=' + encodeURIComponent(utTagSite) +
                '&zone=' + encodeURIComponent(utTagZone);
            document.write('<sc' + 'ript type="text/javascript" src="' + utTagUrl + '"><\/sc' + 'ript>');
        })();
    </script>
 
    <script type="text/javascript">
        try {
            window.hiMediaUt.callTag({
                "format": "1x1",
                "keyValues": ""
            });
        } catch(ex) {
        }
    </script>

    <noscript>
        <a href="http://rd.himediads.com/fr/jump/maisontravaux.fr/fullscreen;sz=1x1;tile=1;ord=" target="_blank">
            <img src="http://rd.himediads.com/fr/ad/maisontravaux.fr/fullscreen;sz=1x1;tile=1;ord=?" width="1" height="1" alt="" />
        </a>
    </noscript>
</div>
<!-- END Hi-Media Media AdTag, maisontravaux.fr 1x1 - v1.0 -->
FULLSCREEN;

$krux = <<<KRUX
<!-- BEGIN Krux Control Tag for "REW_MaisonTravaux" -->
<!-- Source: /snippet/controltag?confid=JlH1GkVp&site=REW_MaisonTravaux&edit=1 -->
<script class="kxct" data-id="JlH1GkVp" data-timing="async" data-version="1.9" type="text/javascript">
  window.Krux||((Krux=function(){Krux.q.push(arguments)}).q=[]);
  (function(){
    var k=document.createElement('script');k.type='text/javascript';k.async=true;
    var m,src=(m=location.href.match(/\bkxsrc=([^&]+)/))&&decodeURIComponent(m[1]);
    k.src = /^https?:\/\/([a-z0-9_\-\.]+\.)?krxd\.net(:\d{1,5})?\//i.test(src) ? src : src === "disable" ? "" :
      (location.protocol==="https:"?"https:":"http:")+"//cdn.krxd.net/controltag?confid=JlH1GkVp"
  ;
    var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(k,s);
  }());
  
</script>
<!-- END Krux Controltag -->
KRUX;

global $site_config, $devs;

$home = home_url('/');
$site_config = array(
	'locking_posts_types' => array('post','exposant'),
	'last_posts_by_category_not_global'=>true,
	'hide_post_push' =>true,
	'meta_tag_paination_seo'=>true,
	'wedgit_check_ops_post'=> true,
	'kpi_ga'=>array(
		'ratio_player_dm_start_per_visites_dsk_mob' => array(
			'ref_value' => 0.40,
			'min' => 0.3,
			'max' => 0.6,
			'controle_msg_min' => 'Problème compatibilité pub',
			'controle_msg_max' => 'Problème comptage',
			'name' => 'Sonde Player DM | Start / Visites Desktop + mobile'
		),
		'ratio_vast_ready_over_start_dm' => array(
			'ref_value' => 'NC' ,
			'min' => 0.6,  
			'max' => 1.0, 
			'controle_msg_min' => 'Problème compatibilité pub',
			'controle_msg_max' => 'Problème comptage',
			'name' => 'Vast Ready vidéo/Start player DM',
		),
		'ratio_call_tag_ads_per_start_js' => array(
			'ref_value' => 0.89,
			'min' => 0.7,
			'max' => 1,
			'controle_msg_min' => 'Conflit de tags sur appel, souci d\'adblock',
			'controle_msg_max' => 'Faux appels générés',
			'name' => 'Sonde Player JS | Call tags ads vidéo / Start'
		),
		'pageviews_per_session' => array(
			'ref_value' => 5.5 ,
			'min' => 4.1 , 
			'max' => 8.3 , 
			'controle_msg_min' => 'Veuillez vérifier si il y a un blocage ou des lenteur sur le temps de chargement des pages',
			'controle_msg_max' => 'Veuillez vérifier Tags pubs qui ont sauté / Souci d\'adblock',
			'name' => 'Nbr de Pages Vues par visite sur Desktop',
		),
		'pagespeed_secondes' => array(
			'ref_value' => 18.8 ,
			'min' => 14.1,  
			'max' => 28.2, 
			'controle_msg_min' => 'Veuillez vérifier la présence des tags pub',
			'controle_msg_max' => 'Veuillez vérifier l absence de conflits entre les tags . une lenteur du temps de chargement détéctée',
			'name' => 'Temps de chargement (en secondes)',
		),
		'ratio_vast_ready_over_start_js' => array(
			'ref_value' => 0.61 ,
			'min' => 0.6,  
			'max' => 1.0, 
			'controle_msg_min' => 'Problème compatibilité pub',
			'controle_msg_max' => 'Problème comptage',
			'name' => 'Sonde player JS vastready/start',
		),
		'ratio_dfp_call_per_visites_dsk' => array(
			'ref_value' => 6.56,
			'min' => 4.9,  
			'max' => 8.2, 
			'controle_msg_min' => 'Problème d\'appel des pubs DFP',
			'controle_msg_max' => 'Problème comptage',
			'name' => 'Sonde Total des appels DFP Desktop / Pages Vues Desktop',
		),
	),

	'hide_next_previous_post' => true,
	
	"google_analytics_id" => "UA-199070484-1",
    "test_google_analytics_id" => "UA-199070484-1",
    'integration_ga_reworld' => true,
	
	"ga_api_id" => 'ga:5674994',
	"test_ga_api_id" => 'ga:229531735',

	// PUB config
	"nuggAd_id"=> '1378252818' ,
	"krux" => $krux,
	"PixelMediapost_cid" => false,
	"id_pub" => array(
			'type' => 'liverail', 
			'liverail' => array(
				'LR_PUBLISHER_ID'=> "69436",
				'LR_TITLE'=> "MaisonTravaux_Flowplayer",
				'LR_VIDEO_ID'=>'video',
				'LR_VERTICALS'=>'MI,V2,C10, C16,A3,G2',
				//'LR_TAGS'=>"testlr",
				'LR_TAGS'=>"[Keyword]",
				'LR_CONTENT'=>"1",
				'LR_AUTOPLAY'=>"1",
				'LR_VIDEO_POSITION'=>'0',
				 'LR_ADMAP'=>'in::0',
				 //'LR_ADMAP'=>'in::100%',
				'LR_SKIP_COUNTDOWN'=>"Vous pouvez passer la pub dans {COUNTDOWN} secondes",
				'LR_SKIP_MESSAGE'=>'Fermer X'
			),
			'liverail2' => array(
				'LR_PUBLISHER_ID'=> "69436",
				'LR_TITLE'=> "MaisonTravaux_Flowplayer",
				'LR_VIDEO_ID'=>'video',
				'LR_VERTICALS'=>'MI,V2,C10, C16,A3,G2',
				//'LR_TAGS'=>"testlr",
				'LR_TAGS'=>"[Keyword]",
				'LR_CONTENT'=>"1",
				'LR_AUTOPLAY'=>"1",
				'LR_VIDEO_POSITION'=>'0',
				 //'LR_ADMAP'=>'in::0',
				 'LR_ADMAP'=>'in%3A%3A100%25',
				'LR_SKIP_COUNTDOWN'=>"Vous pouvez passer la pub dans {COUNTDOWN} secondes",
				'LR_SKIP_MESSAGE'=>'Fermer X'
			)
	),
	"smart_focus" => array (
		"login"=> "deco_api",
		"pass"=>"Reworld!2015",
		"Serveur"=>"p9apie.emv3.com",
		"API"=>"CdX7CqRC6FKArkFNWagLnrmoGHxLWjZnp4jqXw",

		'sources' => array( 
			'NL_MTRA' => array( 
					"labels"=>array(
						
						"Civilité"=>array("key" => "TITLE","translate_values" => array('Mlle'=> 2,'Mme'=> 2,'M'=> 1)),
						"Prénom"=>"FIRSTNAME",
						"Nom"=>"LASTNAME",
						"Email"=>"EMAIL",
						"Code postal"=>"CP",
						"Êtes-vous propriétaire ?"=>array("key" => "PROPRIETAIRE","translate_values" => array('Oui'=> 1,'Non'=> 0)),


						"Avez-vous des projets de rénovation ?"=>array("key" => "INTENTION_TRAVAUX_12_MOIS","translate_values"=> array('Oui'=> date("d/m/Y"),'Non'=> "")),


						"Je souhaite recevoir la newsletter Salon Art & Deco" => array("key"=>"OPTIN_MTRA","datejoin" => "OPTIN_MTRA_DATEJOIN","dateunjoin" => "OPTIN_MTRA_DATEUNJOIN","value"=>1),
						"Je souhaite recevoir les communications des partenaires de Salon Art & Deco" => array("key"=>"OPTIN_PART","value"=>1),
					
					),
					"defaults"=>array(
						"EMAIL_ORIGINE"=>"MTRA",
						"DATEJOIN"=> date("d/m/Y"),
					)

			)
		)
	),


	// Social config
    "facebook_url" => 'https://www.facebook.com/SalonArtetDeco/',
    "instagram_url"=> 'https://www.instagram.com/salon_art_deco/',
	'sharedcount_apikey' => 'c3a1f81053d94a6846c5067d373202fe2b673cb6',
	'recaptcha_publickey'=> '6LewHPsSAAAAAKbmeJ_fOFtWNwNsmTLcB5F-3ld6',
	'recaptcha_privatekey'=> '6LewHPsSAAAAAI5rq8e_WbDwbq17e1qR2-6ipCJQ',
	'addthis_button_shoopit' => false,
	'addthis_button_share_email' => true,
	'social_links_order' => array(
			array('facebook_url','fb'),
			array('twitter_url','tw'),
	),
	'social_links_footer_custom_order'=> array(
		'facebook_url'=>'fb',
		'twitter_url'=>'twitter',
	),
	'auto_pinterest_publish_param' => array( 
							'auto_pinterest_publish_access_token' => 'AQsYEBWehvrXnOuYz8r5mjIodznOFURHvkn-C0lFGw6ogMAppQAAAAA',
							'auto_pinterest_publish_board' => 'maison_travaux/nouveautés'
						),
	"IS_READ_MORE" => "yes",
	"SHOW_DESC_CATEGORY" => "yes",	
	"TYPE_CARROUSEL" => "mf",	

	"show_category_post_content" => "no",	
	"share_post" => "yes",
	
	'favicon' => STYLESHEET_DIR_URI . '/assets/images-v3/favicon.ico?v=3',
	'favicon32' => STYLESHEET_DIR_URI. '/assets/images-v3/favicon32_deco.png?v=3',
	'favicon48' => STYLESHEET_DIR_URI . '/assets/images-v3/favicon48_deco.png?v=3',
	'favicon64' => STYLESHEET_DIR_URI . '/assets/images-v3/favicon64_deco.png?v=3',
	'favicon128' => STYLESHEET_DIR_URI . '/assets/images-v3/favicon128_deco.png?v=3',
	
	'top_simple_addthis_single' => true,
	'mid_simple_addthis_single' => 'true',
	'google_play_id'=>'',
	'google_play_url'=>'https://play.google.com/store/newsstand/details/Maison_Travaux?id=CAowsZS_Bw',
	'not_reverse'=>true,
	'title_home' => __('Maison et travaux : des idées pour une maison moderne' ,  'reworldmedia' ),	
	'thumb_full_width_diapo' => true,
	'number_posts_diaporama'=> 6,
	
	'type_diapo' => '1',
	'link_visu_diapo' => true,
	'bloginfo_name' => 'Salon Art & Deco',
	'switch_autor_date' => false,
	'link_page_politique' => 'https://static.digimondo.net/communication/juridique/charte_dpcookies_rmf.pdf',
	'pos_navs_legende' => 'center',
	'carousel-automatically-interval'=>3000,
	'show_msg_cookies'=>true,
	'blogname'=>'Salon Art & Deco',
	'blogdescription'=>"le 1er magazine sur l'univers de la rénovation et de l'aménagement.",
	'thumb_author' => array(90,90),
	"has_folder"=>true,
	'newsletter_qualif' => 'Inscription newsletter Salon Art & Deco',

	'show_opt_home_diapo' => true, //show options of diaporama home (is_full or normal)
	'active_signature_bo'=> true,
	'has_beforeafter'=> true,

	"show_pagination_on_title" => true,	
	"title_must_popular_video" => "A NE PAS MANQUER",

	"has_video_popin" => true,

	'has_rating' => true, // faire appel au fichier rating.php
	"active_vote" => array( 'display_vote_count_by_percentage' => false ), // pour activer les votes sur les articles
	'show_total_shares' => false,
	'new_shares' => true,
	//Changer l'emplacement des tag ligatus outbrain mediabong
	"active_after_single"=>true,
	'active_scrolldepth' => true,
	'number_posts_cat_by_page' => 8,
	'posts_per_page_archive' => 8,
	'archive_pagination_normal' => true,
	'ajouter_dimensions_preso'=>true,
	'dimension_longeur_article' => is_dev('ajout_dimension_personnalisee_4180'),
	"fix_sidebar_onscroll" => true,
	
	'disabled_link_footer_and_header' => true,
	'change_outbrain_links_to_prod'=> 'www.maison-travaux.fr',

	"sidebar-after-3nd-thumbnails-hp" => true,
	"sidebar-before-paragraph-2" => true ,
	'meta_color_bar_android' => '#d33600',
	'hide_count_vote_bofore_voting' => true,
	/**
	 * Paramétrage des caches
	 */
	'enable_block_cache' => true,
	'enable_cache_diapo_rubrique' => is_dev('enable_cache_diapo_rubrique_138257115'),
	'cache_block_author' => is_dev('cache_block_author_138257115'),
	'enable_most_populat_cache' => is_dev('cache_most_popular_138519847'),
	'enable_cache_popupar_folders' => is_dev('cache_popupar_folders_136906919'),
	'tests' => 'quiz',
	'quizz_title_page' => 'Quiz',
	"diapo_redirection" => true,
	'highlight_categories' =>  array('diaporama', 'diaporama-accueil', 'mise-en-avant'),
	'newsletter_qualif_url' => '/inscription-newsletter-maison-travaux',
	'nav_bar_plus' => true,
	'activate_date_article_NL' => true,

	'hide_navbar_logo' => false,
	'hide_share_in_items'=>true,
	'show_partners_menu' => true,
	'include_slick_carousel' => true,
	'variable_width_carousel'=>true, //must add this "include_slick_carousel"
	'date_format' => "j F Y",
	'title_tv_blok'=>'Vidéos travaux et agencement',
	'number_posts_vignette_home' => 4,
	'show_sub_catgories'=>false,
	'enable_custom_block_push'=>true,
	'folder_with_child_thumbs'=>true,
	'show_date_bk_push'=>true,
	'block_push_date_bottom'=>true,
	'move_html_elements_on_mobile'=>true,
	'customize_archive'=>true,
	'no_top_intro_gallery'=>true,
	'pagination_show_first_last'=>true,
	'social_links_single_after_title'=>true,
	'gallery_refonte_v3'=>true,
	'pos_date_post_content'=>'none',
	'hide_sommaire'=>true,
	'url_tv_blok' =>  home_url(). '/maison-travauxs/videos-renovation',
	'show_title_all_video_link'=>true,
	'item_search-norma'=>false,
	'show_date_after_cat_link'=>'top',
	'hide_author_recette'=>true,
	'show_pagination_home_dossier'=>true,
	'activate_dropdown_filter'=>true,
	'position_date_comment'=> 1,
	'balise_title_last_posts_author'=>'h2',
	'balise_title_list_item'=>'h2',
	'position_quizz_description'=>2,
	'has_recursive_level_menu'=>4,
	'title_quizz_form_subscribe'=>'Vos coordonnées',
	'show_menu_last_post_and_items_small_list'=>false,
	'show_date_item_folder_type'=>true,
	'disable_push_menu_content_down_desktop' => true,
	'category_video' => 'videos',
	'use_seo_js_link_most_popular'=>true,
	'most_popular_seo_tag'=>'ul',
	'add_accueil_breadcrumb'=>true,
	'scrollfix_article_social_media'=>true,
	'tags_to_hide_bo' => array( 'mediametrie', 'Nielsen', 'analytics' ),

	'cheetah_nl' => array(
		'apiPostId'		=> 33,
		'apiPostId_unbounce' => 56,
		'prefix' => 'mt',
		'acronym'=> 'mt',
		'optin' 		=> array(
			
		),
		'ref_id_rmp'   	=>	array('Salon Art & Deco' => 20),
		'footer_nl_info' => array(
			'msg'   => "Inscrivez-vous gratuitement aux newsletters de Salon Art & Deco",
			'class' => 'mt',
			'optin' => array('')
		)
	),
	'zoom_images_diapo_linear' => is_dev('zoom_images_diapo_linear_156084199'),
	'has_syndication_msn' => true,
	'syndic_posts_types_msn' => array( 'post' ),
	'enable_msn' => is_dev( 'msn_feed_rss' ),
	'disable_auto_add_hasvideo' => is_dev('retirer_etiquettes_159621503'),
	'disable_auto_add_hasdiapo' => is_dev('retirer_etiquettes_159621503'),
	'has_ciblage_widget_mpr' => true,
	'has_ciblage_widget_lb' => true,
	'has_ciblage_widget_v2' => true,
	'widget_mpr_desactivation_de_l_auto_refresh' =>is_dev('mt_gestion_de_campagne_iframe_desactivation_de_l_auto_refresh_2770'),
	'SmartAdServerAjaxOneCall'  => true,
	'custom_related_posts_gallery' => is_dev('enchainement_diapo_160988206'),
	'forum_dissociation' => is_dev('mt_forum_meta_dissociation_161447059'),
	'change_sabai_breadcrumb' => true,
	'add_menu_arrow' => true,
	'integration_magazines' => array(
		'ftp_folder' => 'Maison_et_Travaux',
		'posts_author' => 'maisontravaux',
	),
	'config_projet_collect_js_css' => array(
        'Mon_Passeport_Decoration' => array(
            'js' => array(
                'header_js' => $home. 'wp-content/themes/deco/assets/javascripts/mpr_header.js',
            ),
            'css' => array(
              	'header_css' => $home. 'wp-content/themes/deco/assets/stylesheets/mpr_header.css',
            ),
        ),
    ),
    'nbr_posts_child_index_dossier' => 3,
    'show_scroll_to_top_btn' => true,
    'show_link_to_folder' => true,
    'instagram_username' => 'maisonettravaux',
    'instagram_access_token' => '2083079909.2591476.700c5d4d969544abaed876978de04f62',
    'logo_player_soundcast' =>  STYLESHEET_DIR_URI.'/decoration/assets/images-v3/soundcast_mt.jpg',
    'soundcast_audioboom_link' =>  'https://audioboom.com/channels/4994809',
    'posts_orderby_date_modified' => is_dev('enchainement_articles_7684'),
    'folders_orderby_date_modified' => true,
    'widget_ops_univers_slug' =>'univers_deco',
    'truncate_text' => true,
    'remove_print_css' => true,
    'no_generated_content_deco' => true,
    'site_name' => 'mt',
    'generic_custom_css_amp'=>'decoration/assets/stylesheets/custom_amp_style.css',
    'has_folder' => true,
    'display_author_bloc_in_post' => true,
    'remove_rw_desktop_js' => true,
    'remove_footer_js' => true,
    'addthis_bubble_comment' => true,
    'hide_comment_btn_count' => true,
    'comment_btn_txt' => 'commenter',
    'diapo_legend_keep_p_tag' => true,
    'disable_full_diapo' => true,
    'enable_linear_diapo_on_mobile' => rw_is_mobile(),
    'hide_search_breadcrumb' => true,
    'gallery_big_image_zise' => 'large',
    'bo_login_logo' => '/assets/images-v3/logo_header.svg',
    'hide_excerpt_list_item' => true,
    'access_to_site_meta_data_route' => true,
    'site_meta_data' => array(
    	'title' => 'Le chasseur français',
    	'link' => 'https://www.lechasseurfrancais.com/',
    	'category' => 'Homme',
    	'description' => "Suivez l'actualité chasse, pêche et vie de nos campagnes avec les journalistes du Chasseur Français",
    	'webMaster' => 'abdoulaye@webpick.info',
    	'image' => array(
    		'title' => 'Le chasseur français',
    		'url' => 'https://www.lechasseurfrancais.com/wp-content/themes/chasseur-francais/assets/images-v3/logo_header.svg',
    		'link' => 'https://www.lechasseurfrancais.com/',
    		'description' => 'Le chasseur français',
    		'width' => '410',
    		'height' => '60',
    	),
    ),
    'has_products' => true,
    'products_shoppingbox' => array(
		'active' => true,
		'title' => 'Produits et services',
		'items_to_show' => 50,
		'orderby' => 'ID',
		'order' => 'ASC'
	),
    'scrollfix_article_social_media' => false,
    'hide_breadcrumb_archive' => true,
	'hide_comment_template' => true,
	'has_exposant'=>true
);


$site_config_js['disable_folder_slick'] = true;
$site_config['diapo_lineaire_retour_arriere'] = is_dev('diapo_lineaire_retour_arriere_155851194');
$site_config_js['diapo_lineaire_retour_arriere'] = is_dev('diapo_lineaire_retour_arriere_155851194');
$site_config_js['header_sticky_on_mobile'] = true;
$site_config_js['disable_header_slide_menu'] = true;
$site_config_js['seo_bubble_links'] = true;

//ticket #110747548 URGENT | ADVIDEUM - Instream | Intégration PREROLL Web et Mobile
//By gounane

if (is_dev('Passage_du_player_sur_videojs')){
	$site_config['id_pub'] = array(	
		'type'=> 'liverail', //9793
		'url'=> 'http://ad4.liverail.com/',
		'skip' => -1
	);

	if(defined('MOBILE_MODE') && MOBILE_MODE ){
		$site_config['id_pub']['liverail'] = array(
			"LR_ADMAP" => "in::0",
			"LR_VIDEO_POSITION" => "0",
			"CACHEBUSTER" => "[TIMESTAMP]",
			"LR_PUBLISHER_ID" => "164081",
			"LR_SCHEMA" => "vast2",
			"LR_AUTOPLAY" => "0",
			"LR_VERTICALS" => "maisonettravaux_fr_mobile",
			"LR_TITLE" => "MaisonEtTravaux_title",
			"LR_VIDEO_ID" => "MaisonEtTravaux_videoID",
			"LR_PARTNERS" => "",
			"LR_FORMAT" => "video/mp4",
			"LR_TAGS" => "novpaid",
			"LR_BITRATE" => "low",
			"LR_DISABLE_UDS" => "1",
			"LR_TIMEOUT_ADSOURCE" => "2",
			"LR_WATERFALL" => "1"
		);
	}else{
		$site_config['id_pub']['liverail'] = array(
			"LR_ADMAP" => "in::0",
			"LR_VIDEO_POSITION" => "0",
			"CACHEBUSTER" => "[TIMESTAMP]",
			"LR_SKIP_MESSAGE" => "X",
			"LR_SKIP_COUNTDOWN" => "Passer+dans+{COUNTDOWN}+s",
			"LR_LAYOUT_SKIN_MESSAGE" => "Publicité,+fin+dans+{COUNTDOWN}+secondes",
			"LR_PUBLISHER_ID" => "164081",
			"LR_SCHEMA" => "vast2-vpaid",
			"LR_AUTOPLAY" => "0",
			"LR_VERTICALS" => "maisonettravaux_fr",
			"LR_TITLE" => "MaisonEtTravaux_title",
			"LR_VIDEO_ID" => "MaisonEtTravaux_videoID",
			"LR_PARTNERS" => "",
			"LR_LAYOUT_LINEAR_PAUSEONCLICKTHRU" => "0",
			"LR_DISABLE_UDS" => "1",
			"LR_TIMEOUT_ADSOURCE" => "2",
			"LR_WATERFALL" => "1",
		);
	}
}

if (is_dev('passage_du_player_sur_jw6_111776366')){
	$site_config['id_pub'] = array(	
		'type'=> 'liverail', 
		'liverail' => array(
			'LR_PUBLISHER_ID'  =>  '164081',
			'LR_ADMAP'  =>  'in::0%;in::100%',
			'LR_VERTICALS'  => 'maisonettravaux_fr',
			'LR_LAYOUT_SKIN_MESSAGE'  => 'Publicité, fin dans {COUNTDOWN} secondes',
			'LR _SKIP_MESSAGE'  => 'X',
			'LR _SKIP_COUNTDOWN'  => 'Passer dans {COUNTDOWN} s',
			'LR_LAYOUT_SKIN_ID'  => '1',
			'LR_WATERFALL'  => '1',
			'LR_TIMEOUT_ADSOURCE'  =>  '2',
			'LR_DISABLE_UDS'  =>  '1 ',
			'LR_TAGS'  => '',
			'LR_AUTOPLAY'  => '0'

		)
	);
}
if(is_dev('pinterest_auto_publish_158687654')){
	$site_config['auto_pinterest_publish'] = true;
}

/*
//Commenté le 27/12/2017
A supprimer après verification 
if(is_dev("custom_cache_durations")){
	$site_config['cache_menu_items'] =  60*60;
	$site_config['cache_menu'] =  60*60;
	$site_config['cache_menu_wp'] =  60*60;
	$site_config['cache_diapo'] = 60*5; // diaporama HP
	$site_config['cache_footer'] =  60*60*24*7; 
	$site_config['cache_posts'] =  60*60*24*7; // cache HP à ne pas manquer
	$site_config['cache_block_rubrique'] = 60*10; //block rubrique 
	$site_config['cache_author'] = 60*60*24; // bloc author
	$site_config['cache_most_popular'] = 60*60*2; // bloc des articles les plus lus	
	$site_config['cache_popuplar_folders'] = 60*60*2; // bloc des dossier les plus populaires	
	$site_config['cache_child_cats'] =  60*60; // menu des catégories profondes	
}
*/


global $site_config_js;
$site_config_js['nl_automatique'] = array( "folder_id" => 29935, "mail_domaine_id" => 24, 'ma_category' => "MTRA", "segment_id" => array('edito'=> 19),'form_name' => 'Salon Art & Deco', 'reply_name'=> 'Relation Client Salon Art & Deco', 'form_addr' => 'info@news.maison-travaux.fr' );
$site_config_js['activate_date_article_NL'] = true;
$site_config_js["zoom_images_diapo_linear"] = $site_config["zoom_images_diapo_linear"];
$site_config_js['disable_ga_event_mobile_diapo_linear'] = true;
// Manage dfp events ga tracking
if(is_dev('remove_dfp_formats_tracker')){
	$site_config_js['manage_tracking_ga'] = array('mpu_haut');
}

if( is_dev('one_page_template_161057750') ){
	$site_config[ 'custom_post_format' ] = array (
		'uniqlo' => array (
			'category'     => array( 'onepage', 'uniqlo' ),
			'body_class'   => 'uniqlo',
			'remove'  => array (
				'filters'  => array (
					'inread_filter', 'inPicture_filter', 'fullscreen_filter', 'inBoard_filter', 'customize_mediabong',
					'id_pub_filter', 'outbrain_filter', 'ligatus_filter', 'has_video_popin', 'kiosked_filter', 'deactivate_vertical_sharer' => '__return_true',
				),
			    'actions'  => array ( 'single_after_title' => array( 'single_after_title_action' ), 'after_thecontent' => array( 'social_links_deco_project' ) ),
				'config'   => array (
					'hide_comment_template' => true, 'hide_author_bloc' => true,
					'detailed_publication_infos_bloc' => false, 'hide_author_and_date_recette' => true, 'content_box' => '',
					'hide_signature_for_userid' => false, 'live_products_shoppingbox' => false,
				)
			),
			'stylesheets' => 'uniqlo.css',
			'include_file' => 'uniqlo.php',
			'shortcodes' => array (
				'uniqlo' => 'shortcode_uniqlo',
			),
		)
	);
}

if( is_dev('retirer_bloc_videos_travaux_4581') ){
	$site_config['title_tv_blok'] = 'Vidéos';
	$site_config['hide-block-tv'] = true;
}



if( is_dev('supprimer_dates_tags_4683') ){
	$site_config['hide_hp_full_diapo_tags'] = true;
	$site_config['hide_hp_full_diapo_dates'] = true;
	$site_config['hide_hp_folder_dates'] = true;
	$site_config['hide_list_item_v3_dates'] = true;
	$site_config['hide_list_item_v3_tags'] = true;
	$site_config['hide_block_push_v3_dates'] = true;
	$site_config['show_date_bk_push'] = false;
}

//Config du Locking


$site_config['locking']= array(
	
	//Config de la sidebar  : 

	'widget' => array(
		'popular_posts' => array(
			'desc' => 'Exposant position ',
			'title' => 'Les exposants les + lus', 
			'nb_pos' => 2,
			'nb_pos_max' => 5,
			'args' => array(
				'post_type'=> 'exposant',
			) 
		),
		'title'=>"Sidebar général",
	),
);

//locking Home

define('_LOCKING_ON_',true);
$menu_id = apply_filters('get_menu_name', 'menu_header', 'menu_header');

$menu_items = wp_get_nav_menu_items( $menu_id );
if ($menu_items){
	foreach ($menu_items as $menu_item) {
		
		if ($menu_item->type=='taxonomy') {
		//Config de la homepage : 
			$site_config['locking']['home']['bloc_rubrique_'.$menu_item->object_id] = 
			[
				'desc' => 'Bloc rubrique '.$menu_item->title,
				'title' => 'BLOC RUBRIQUE '.$menu_item->title,
				'nb_pos' => 4,
				'args' => array(
					'post_type'=> $site_config['locking_posts_types'],
				) 
			];			
		}
	}
}

// Locking THematique
$thematiques = get_categories();

if ($thematiques){
	foreach ($thematiques as $thematique) {	
	//Config des posts - bloc posts suggeres  : 

		if( !strpos($thematique->slug, 'edito') ){
			$site_config['locking']['post']['post_exposants_par_thematique_'.$thematique->slug] = 
			[
				'desc' => 'Posts suggérés de la thematique '.$thematique->name,
				'title' => 'Posts suggérés de la thematique '.$thematique->name,
				'nb_pos' => 3,
				'args' => array(
					'post_type'=> 'exposant',
				) 
			];

			$site_config['locking']['category']['bloc_conseils_experts_'.$thematique->slug] = 
			[
				'desc' => 'Bloc conseils d\'expert de la categorie '.$thematique->name,
				'title' => 'Conseils d\'expert de la category '.$thematique->name, 
				'nb_pos' => 4,
				'args' => array(
					'post_type'=> $site_config['locking_posts_types'],
				) 
			];

			$site_config['locking']['sous_category']['conseils-experts'.$thematique->slug] = 
			[
				'desc' => 'Bloc conseils d experts de la sous categories '.$thematique->name,
				'title' => 'Bloc conseils d experts de la sous categories '.$thematique->name,
				'nb_pos' => 4,
				'args' => array(
					'post_type'=> 'post',
				) 
			];
			

			
		}
	//Config de la sous categorie - bloc carousel : 

		$site_config['locking']['sous_category']['carousel'.$thematique->slug] = 
		[
			'desc' => 'Carousel de la Categorie '.$thematique->name,
			'title' => 'Carousel de la Categorie '.$thematique->name,
			'nb_pos' => 5,
			'args' => array(
				'post_type'=> 'exposant',
			) 
		];
	//Config de la sous categorie - bloc exposant : 

		$site_config['locking']['sous_category']['exposants'.$thematique->slug] = 
		[
			'desc' => 'Bloc Exposants de  la sous categorie'.$thematique->name,
			'title' => 'Bloc des Exposants de la sous-category '.$thematique->name, 
			'nb_pos' => 2,
			'args' => array(
				'post_type'=> 'exposant',
			) 
		];

		
	}
}

// FIn de la config du locking 

