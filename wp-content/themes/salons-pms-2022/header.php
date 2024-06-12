<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?> class='no-js'>
<!--<![endif]-->
<head>
<meta charset="utf-8" />
<?php 
global $site_config_js;
$refresh_desactive = false;
if( is_single() && get_post_meta(get_the_ID(), 'refresh_desactive', true) ) $refresh_desactive = true;
if( defined('_IS_REFRESH_') && !_IS_REFRESH_ ) $refresh_desactive = true;
$refresh_desactive = apply_filters('disable_refresh', $refresh_desactive);
if( !$refresh_desactive ){ 
	$refresh_duration = apply_filters('refresh_duration', 300);
	if( $refresh_duration != 0 ){
		$site_config_js['refresh_meta_duration'] = $refresh_duration;;
		$site_config_js['enable_refresh_meta'] = true;
	}else{
		$site_config_js['enable_refresh_meta'] = false;
	}
} 

if(is_category()){
	echo '<meta name="robots" content="noindex follow" />';
}

// events pages list 

$events = array(
	"edito",
	"mondial-de-lauto",
	"paris-automotive-week"
);

foreach( $events as $event){
	if ( is_page($event)){
?>
<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Event",
      "name": "Le Mondial de l’Auto 2022 ",
      "startDate": "2022-10-17T19:00-05:00",
      "endDate": "2022-10-23T23:00-05:00",
      "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
      "eventStatus": "https://schema.org/EventScheduled",
      "location": {
        "@type": "Place",
        "name": "Snickerpark Stadium",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "PARIS, PORTE DE VERSAILLES",
          "addressLocality": "PARIS",
          "postalCode": "19019",
          "addressRegion": "IDF",
          "addressCountry": "FR"
        }
      },
      "image": [
        "https://mondial.paris/wp-content/uploads/salons-pms/2022/05/21-Mondial-de-lAuto-centre-d%E2%80%99essais-1.jpg",
       ],
      "description": "Le Mondial de l’Auto 2022 s’ouvrira au Public du 18 au 23 octobre 2022, dans le cadre de la Paris Automotive Week. \n Il est organisé au sein du Parc Paris Expo Porte de Versailles. Les Pavillons 3-4-6 et 5.1 accueilleront l’évènement.",
      "performer": {
        "@type": "PerformingGroup",
        "name": "Mondial Paris"
      },
      "organizer": {
        "@type": "Organization",
        "name": "Mondial Paris",
        "url": "https://mondial.paris"
      }
    }
</script>
<?php
	}
}
do_action('top_head_rw');

$favicon = STYLESHEET_DIR_URI .'/assets/images-v3/favicon.png';

?>
<link rel="shortcut icon" type="image/png" href="<?php echo  $favicon ; ?>">

<?php 
$favicons_formats =  array( '32','48','64','128' );
$favicons_formats = apply_filters('favicons_formats', $favicons_formats);
foreach ($favicons_formats as $format) {
	if(!$favicon = get_param_global('favicon' . $format)){
		$favicon = STYLESHEET_DIR_URI .'/assets/images/favicon'. $format .'.png';
	}

?>
<link rel="icon" href="<?php echo $favicon; ?>" sizes="<?php echo $format. 'x' .$format ; ?>">
<?php

}
?>

<?php do_action('wp_head'); ?>

<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/assets/javascripts/html5.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/javascripts/modernizr.custom.86039.js" type="text/javascript"></script>
<![endif]-->
	
<?php do_action('wp_head_end'); ?>
<script>
	(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-K6SCPWL');
</script>

<!-- Twitter universal website tag code -->
<script>
!function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.pus
h(arguments);
},s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='//static.adstwitter.com/uwt.js',
a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
// Insert Twitter Pixel ID and Standard Event data below
twq('init','o3z6r');
twq('track','PageView');
</script>
<!-- End Twitter universal website tag code -->

<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '604117794466937');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=604117794466937&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
<meta name="facebook-domain-verification" content="hpx4gctr93na4tziz6ycyezkrgqfbe" />

</head>

<body <?php body_class(); ?>  >
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K6SCPWL"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<?php
	do_action('rew_top_head'); 
	
	$sidebar = apply_filters('filter_all_sidebar', 'sidebar-after-body') ;
	if (is_active_sidebar($sidebar)) { 
		dynamic_sidebar($sidebar); 
	}

		
	$sidebar = apply_filters('filter_all_sidebar', 'sidebar-header-pub') ;
	if (is_active_sidebar($sidebar)) { 
		?>
		<div class="sidebar-header-pub row">
		<?php
		dynamic_sidebar($sidebar); 
		?>
		</div>
		<?php
	}				

		
	?>
	<div id="page" class="hfeed site"> <!-- to delete id & hfeed site -->
		
		<?php 
			do_action('after_barretopinfo');
			if(!is_page("landing-billetterie")){
				get_nav_header();
			}
			do_action("after_nav");
			include(locate_template('include/templates/popup.php'));
		?>
<div class="container">

<?php
do_action('rew_head','all');