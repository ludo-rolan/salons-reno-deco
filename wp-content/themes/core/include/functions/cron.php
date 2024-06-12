<?php
add_action('wp_ajax_go_cron_ga', 'go_cron_ga');
add_action('wp_ajax_nopriv_go_cron_ga', 'go_cron_ga');
function go_cron_ga(){		
	cron_ga_popular();	
	return "";
}
if(!function_exists('cron_ga_popular')):
function cron_ga_popular() {		
	require_once 'ga_framework/Google_Client.php';
	require_once 'ga_framework/contrib/Google_AnalyticsService.php';
	$client = new Google_Client();
	$client->setApplicationName("Google Analytics PHP Starter Application");	
	$client->setClientId(  apply_filters( 'analytics_client_id' , '743676929027-cg3u4862v1m4p4hvhc40b80mhkroufhc.apps.googleusercontent.com' ) ) ;
	$client->setClientSecret(  apply_filters( 'analytics_client_secret' , 'ka8ozfaFrjEsdtlBBrT-mE-5' )   );
	$client->setRedirectUri( home_url(). '/wp-admin/admin-ajax.php?action=go_cron_ga');
	$service = new Google_AnalyticsService($client);
					
	$ga_token = get_option("ga_token");
	$start_date = apply_filters('ga_cron_start_date',date("Y-m-d"));
	$start_date_2_month = date("Y-m-d" , time() - (365*24*60*60));
	$end_date = date("Y-m-d" );
	
	if(!$ga_token || isset($_GET['force'] )){
		$ga_token = apply_filters (   'analytics_token'  , '{"access_token":"ya29.1.AADtN_WKpX0zH0iLXPLtORtu70Huvmlq8uEv2AlEIXi46o4TiAF7RLH2ycU8TeRPBeHG","token_type":"Bearer","expires_in":3600,"refresh_token":"1\/vBZprTNKPxcesBHRQFZGgWClv0dQoE96IKxvYJqGNdg","created":1387566065}' ) ;
		update_option("ga_token",$ga_token,false);	
	}
	if (isset($_GET['code'])) {
		$client->authenticate();
		$ga_token = $client->getAccessToken();
		update_option("ga_token",$ga_token,false);
	}
	$client->setAccessToken($ga_token);				
	try {
		$client->getAccessToken();
		$optParams = array(
		  'dimensions' => 'ga:eventLabel',
		  'sort' => '-ga:totalEvents',
		  'filters' => is_dev('ga_opt_event_top_popular_152881992') ? 'ga:eventCategory=@top_popular' : 'ga:eventCategory==top_popular',
		  'max-results' => '16'	
		);
		// param for top_popular_video
		$optVideoParams = array(
		  'dimensions' => 'ga:eventLabel',
		  'sort' => '-ga:totalEvents',
		  //'filters' => 'ga:eventCategory==top_popular_video',
		  'filters' => is_dev('ga_opt_event_top_popular_152881992') ? 'ga:eventCategory==top_popular/video' : 'ga:eventCategory==top_popular_video_cat',
		  'max-results' => '16'	
		);
		$optGalleryParams = array(
		  'dimensions' => 'ga:eventLabel',
		  'sort' => '-ga:totalEvents',
		  //'filters' => 'ga:eventCategory==top_popular_video',
		  'filters' =>  is_dev('ga_opt_event_top_popular_152881992') ? 'ga:eventCategory==top_popular/diapo' : 'ga:eventCategory==top_diapos',
		  'max-results' => '16'	
		);
		
		global $site_config ;
		if(is_dev() &&  isset($site_config["test_ga_api_id"])){
			$ga_api_id = apply_filters('ga_api_id', $site_config["test_ga_api_id"] ); ;
		}else{
			$ga_api_id = apply_filters('ga_api_id', $site_config["ga_api_id"] ); ;
		}
		$row = $service->data_ga->get(
			$ga_api_id,
			$start_date,
			$end_date,
			'ga:totalEvents' ,
			$optParams		   
	    );
		// retrieve data ga of simple article
	 	$most_popular = get_option(apply_filters('most_popular_option',"most_popular"), array()); 	 
		if(!empty($row["rows"])){
			foreach($row["rows"] as $k => $v){
			  $most_popular[$k] = $v[0] ;
			}
		}
		$most_popular = array_unique($most_popular);
		update_option(apply_filters('most_popular_option',"most_popular"), $most_popular, false);
	    print_r ($row["rows"]) ."<br />";

	    //Most poplar per category
		$menu_id=isset($atts['name']) ? $atts['name'] : apply_filters('get_menu_name', 'menu_header', 'menu_header');

		$menu_items = wp_get_nav_menu_items($menu_id);
		$cats_slug = array(); 
		foreach ($menu_items as $menu_item){
			
			if(get_param_global('cron_most_popular_all_cats_menu')){
				$condtion = true ;
			}else{
				$condtion = $menu_item->menu_item_parent == 0 ;
			}
			if($condtion && $menu_item->object == 'category'){
				$cat = get_category($menu_item->object_id);
				if($cat){
					$cats_slug[] = $cat->slug ;
				}
			}
		}
		$dedicated_area = get_param_global('dedicated_area',  array());
		if(count($dedicated_area)){
			foreach ($dedicated_area as $ops) {
				if(!empty($ops['category']) && !in_array($ops['category'], $cats_slug) ){
						$cats_slug[] = $ops['category'] ;
				}
			}
		}

		foreach ($cats_slug as $cat_slug) {
			if($cat_slug){
				$optParams_rubrique = array(
				  'dimensions' => 'ga:eventLabel',
				  'sort' => '-ga:totalEvents',
				  'filters' => is_dev( 'ga_opt_event_top_popular_152881992' ) ? 'ga:eventCategory=@top_popular;ga:eventAction==' . $cat_slug : 'ga:eventCategory==top_popular;ga:eventAction==' . $cat_slug,
				  'max-results' => '16'
				);

				$start_date_by_cat = apply_filters('ga_start_date_by_cat', $start_date_2_month, $cat_slug);

				echo "start_date_by_cat $cat_slug :$start_date_by_cat : \n" ;
				echo "end_date $cat_slug :$end_date : \n" ;

			    $row_rubrique = $service->data_ga->get(
					$ga_api_id,
					$start_date_by_cat,
					$end_date,
					'ga:totalEvents' ,
					$optParams_rubrique
			    );
				// retrieve data ga of simple article
				$option_name  = apply_filters('most_popular_option',"most_popular_" . $cat_slug ) ;
				$most_popular_rubrique = get_option( $option_name, array()); 	 
				if(!empty($row_rubrique["rows"]) && count($row_rubrique["rows"])){
					foreach($row_rubrique["rows"] as $k => $v){
					  $most_popular_rubrique[$k] = $v[0] ;
					}
				}
				$most_popular_rubrique = array_unique($most_popular_rubrique);
				update_option($option_name, $most_popular_rubrique , false );

				   echo "<pre>";
				    print_r ("<br/> -------- <br/>Popular posts {$cat_slug} <br />"  );
				    print_r ($most_popular_rubrique);
				   echo "</pre>";

			}

		}

		//End most poplar per category

		//Most popular for all categories
		$row_all_popular = $service->data_ga->get(
			$ga_api_id,
			$start_date_2_month,
			$end_date,
			'ga:totalEvents' ,
			$optParams		   
		);
		// retrieve data ga of simple article
		$most_popular_all = get_option(apply_filters('most_popular_option',"most_popular_all"), array()); 	 
		foreach($row_all_popular["rows"] as $k => $v){
			$most_popular_all[$k] = $v[0] ;
		}
		$most_popular_all = array_unique($most_popular_all);
		update_option(apply_filters('most_popular_option',"most_popular_all"), $most_popular_all,false);
		//End most popular for all categories


		// retrieve data ga of video article
		$rowVideo = $service->data_ga->get(
			$ga_api_id,
			$start_date,
			$end_date,
			'ga:totalEvents' ,
			$optVideoParams		   
	 	);
	 	// pretty_echo($rowVideo);
		$most_popular_video = get_option(apply_filters('most_popular_video_option',"most_popular_video"), array()); 	
		if(!empty($rowVideo["rows"])){
			foreach($rowVideo["rows"] as $k => $vid){
			  $most_popular_video[$k] = $vid[0] ;
			}			
		} 

		$most_popular_video = array_unique($most_popular_video);
	    // Popular video article
	    echo "<pre>";
	    print_r ('<br/> -------- <br/>Popular Video most<br />');
	    print_r ($most_popular_video);
	    print_r ('<br/> -------- <br/>Popular Video') ."<br />";
	    update_option(apply_filters('most_popular_video_option',"most_popular_video"), $most_popular_video , false );
	    if(!empty($rowVideo["rows"]))
	    	print_r ($rowVideo["rows"]) ."<br />";
	    echo "</pre>";




		// retrieve data ga of gallery article
		$rowGallry = $service->data_ga->get(
			$ga_api_id,
			$start_date,
			$end_date,
			 //date("Y-m-d" , time() - (10*24*60*60)),
			'ga:totalEvents' ,
			$optGalleryParams		   
	 	);
	 	// pretty_echo($rowGallry);
		$most_popular_gallery = get_option(apply_filters('most_popular_gallery_option',"most_popular_gallery"), array()); 	
		if(!empty($rowGallry["rows"])){
			foreach($rowGallry["rows"] as $k => $vid){
			  $most_popular_gallery[$k] = $vid[0] ;
			}			
		} 

		$most_popular_gallery = array_unique($most_popular_gallery);
	    update_option(apply_filters('most_popular_gallery_option',"most_popular_gallery"), $most_popular_gallery , false );
	    // Popular gallery article
	    echo "<pre>";
	    print_r ('<br/> -------- <br/>Popular galleries most<br />');
	    print_r ($most_popular_gallery);
	    print_r ('<br/> -------- <br/>Popular galleries') ."<br />";
	    if(!empty($rowGallry["rows"]))
	    	print_r ($rowGallry["rows"]) ."<br />";
	    echo "</pre>";


	    if(get_param_global('has_folder')){
	    	$optDossierParams = array(
	    		'dimensions' => 'ga:eventLabel',
	    		'sort' => '-ga:totalEvents',
	    		'filters' => is_dev('ga_opt_event_top_popular_152881992') ? 'ga:eventCategory==top_popular/dossier' : 'ga:eventCategory==top_popular_dossier',
	    		'max-results' => '16'	
	    		);
	    // retrieve data ga of Dossier article
	    	$rowDossier = $service->data_ga->get(
	    		$ga_api_id,
	    		$start_date,
	    		$end_date,
	    		'ga:totalEvents' ,
	    		$optDossierParams		   
	    		);

	    	$most_popular_dossier = get_option(apply_filters('most_popular_dossier_option',"most_popular_dossier"), array()); 	
	    	if(!empty($rowDossier["rows"])){
	    		foreach($rowDossier["rows"] as $k => $d){
	    			$most_popular_dossier[$k] = $d[0] ;
	    		}			
	    	} 

	    	$most_popular_dossier = array_unique($most_popular_dossier);
	    // Popular dossier article
	    	echo "<pre>";
	    	print_r ('<br/> -------- <br/>Popular Dossier most<br />');
	    	print_r ($most_popular_dossier);
	    	print_r ('<br/> -------- <br/>Popular Dossier') ."<br />";
	    	update_option(apply_filters('most_popular_dossier_option',"most_popular_dossier"), $most_popular_dossier , false );
	    	if(!empty($rowDossier["rows"]))
	    		print_r ($rowDossier["rows"]) ."<br />";
	    	echo "</pre>";
	    }
		if(get_param_global('has_exposant')){
	    	$optExposantParams = array(
	    		'dimensions' => 'ga:eventLabel',
	    		'sort' => '-ga:totalEvents',
	    		'filters' => is_dev('ga_opt_event_top_popular_152881992') ? 'ga:eventCategory==top_popular/exposant' : 'ga:eventCategory==top_popular_exposant',
	    		'max-results' => '16'	
	    		);
	    // retrieve data ga of Exposant article
	    	$rowExposant = $service->data_ga->get(
	    		$ga_api_id,
	    		$start_date,
	    		$end_date,
	    		'ga:totalEvents' ,
	    		$optExposantParams		   
	    		);

	    	$most_popular_exposant = get_option(apply_filters('most_popular_exposant_option',"most_popular_exposant"), array()); 	
	    	if(!empty($rowExposant["rows"])){
	    		foreach($rowExposant["rows"] as $k => $d){
	    			$most_popular_exposant[$k] = $d[0] ;
	    		}			
	    	} 

	    	$most_popular_exposant = array_unique($most_popular_exposant);
	    // Popular exposant article
	    	echo "<pre>";
	    	print_r ('<br/> -------- <br/>Popular Exposant most<br />');
	    	print_r ($most_popular_exposant);
	    	print_r ('<br/> -------- <br/>Popular Exposant') ."<br />";
	    	update_option(apply_filters('most_popular_exposant_option',"most_popular_exposant"), $most_popular_exposant , false );
	    	if(!empty($rowExposant["rows"]))
	    		print_r ($rowExposant["rows"]) ."<br />";
	    	echo "</pre>";
	    }

		
		$args = array(
			'service'    => $service,
			'ga_api_id'  => $ga_api_id,
			'start_date' => $start_date,
			'end_date'   => $end_date,
		);
		do_action('cron_ga' , $args );

	    $accessToken = $client->getAccessToken();
	    $accessToken = json_decode($accessToken);
	  
	   	   	   	  	
	}catch(Exception $e){ 
		$authUrl = $client->createAuthUrl();
		$subject = "Erreur exécution  cron " .get_option('blogname') ;
		$from =   apply_filters( 'analytics_email' ,  "abdellatif@webpick.info" )  ;
		$emailto = apply_filters( 'analytics_email' ,  "abdellatif@webpick.info" )  ;			
		$headers = 'From: '.get_option('blogname').' <'.$from.'>' . "\r\n";
		$message = "Erreur exécution cron google analytic url connextion = $authUrl" ;		
		echo $message ;
		wp_mail($emailto, $subject, $message, $headers) ;
	}
	exit();
}
endif;
if(!defined('_IS_LOCAL_')){
	if (!wp_next_scheduled( 'task_hook_ga_popular')  ){
	  wp_schedule_event(time(), 'hourly', 'task_hook_ga_popular');
	}
	add_action( 'task_hook_ga_popular', 'cron_ga_popular' );
}
add_action('wp_ajax_trancate_sam_stats', 'trancate_sam_stats');
add_action('wp_ajax_nopriv_trancate_sam_stats', 'trancate_sam_stats');
function trancate_sam_stats(){		
	global $wpdb ;
	$sql = "TRUNCATE {$wpdb->prefix}sam_stats";
	//$wpdb->query($sql);	
	echo "OK";
	return "" ;
}

function rw_not_add_weekly( $schedules ) {
    $schedules['weekly'] = array(
        'interval' => 604800, //that's how many seconds in a week, for the unix timestamp
        'display' => __('weekly')
    );
    return $schedules;
}
add_filter('cron_schedules', 'rw_not_add_weekly');

if (!wp_next_scheduled( 'task_hook_trancate_sam_stats')  ){
  wp_schedule_event(time(), 'weekly', 'task_hook_trancate_sam_stats');
}

add_action( 'task_hook_trancate_sam_stats', 'trancate_sam_stats' );
add_action('wp_ajax_go_cron_sharecount', 'go_cron_sharecount');
add_action('wp_ajax_nopriv_go_cron_sharecount', 'go_cron_sharecount');
function go_cron_sharecount(){	
	$nombe	= isset($_GET['nombe'])? $_GET['nombe']:100 ;
	cron_sharecount($nombe);	
	return "";
}
function cron_sharecount($nombe =100) {		
	require_once 'ga_framework/Google_Client.php';
	require_once 'ga_framework/contrib/Google_AnalyticsService.php';
	$client = new Google_Client();
	$client->setApplicationName("Google Analytics PHP Starter Application");	
	$client->setClientId('743676929027-cg3u4862v1m4p4hvhc40b80mhkroufhc.apps.googleusercontent.com');
	$client->setClientSecret('ka8ozfaFrjEsdtlBBrT-mE-5');
	$client->setRedirectUri( home_url(). '/wp-admin/admin-ajax.php?action=go_cron_ga');
	$service = new Google_AnalyticsService($client);
					
	$ga_token = get_option("ga_token");
	
	if(!$ga_token){
		$ga_token = apply_filters (   'analytics_token'  , '{"access_token":"ya29.1.AADtN_WKpX0zH0iLXPLtORtu70Huvmlq8uEv2AlEIXi46o4TiAF7RLH2ycU8TeRPBeHG","token_type":"Bearer","expires_in":3600,"refresh_token":"1\/vBZprTNKPxcesBHRQFZGgWClv0dQoE96IKxvYJqGNdg","created":1387566065}' ) ;
		update_option("ga_token",$ga_token,false);	
	}
	if (isset($_GET['code'])) {
		$client->authenticate();
		$ga_token = $client->getAccessToken();
		update_option("ga_token",$ga_token,false);
	}
	$client->setAccessToken($ga_token);				
	try {
		$client->getAccessToken();
		$optParams = array(
		  'dimensions' => 'ga:eventLabel',
		  'sort' => '-ga:totalEvents',
		  'filters' => is_dev( 'ga_opt_event_top_popular_152881992' ) ? 'ga:eventCategory=@top_popular' : 'ga:eventCategory==top_popular',
		  'max-results' => $nombe	
		);
		global $site_config ;
		if(is_dev()  &&  isset($site_config["test_ga_api_id"])){
			$ga_api_id = apply_filters('ga_api_id', $site_config["test_ga_api_id"] ); ;
		}else{
			$ga_api_id = apply_filters('ga_api_id', $site_config["ga_api_id"] ); ;
		}
		$row = $service->data_ga->get(
			$ga_api_id,
			date("Y-m-d" , time()- (2*60)),
			date("Y-m-d"),
			'ga:totalEvents' ,
			$optParams		   
	  );
		if(isset($row["rows"])){
			foreach($row["rows"] as $k => $v){
				echo  $v[0] ."\n";
				$data = save_sharedcount($v[0]);
			 	
			 	if($data){
					print_flush( "save_sharedcount for : " .get_permalink($v[0]) ."\n" );
				}else{
					print_flush( "Error save_sharedcount for : " .get_permalink($v[0]) ."\n" );
				}
				usleep(200000);
			}	
		}  
	   	   	   	  	
	}catch(Exception $e){ 
		$authUrl = $client->createAuthUrl();
		$subject = "Erreur exécution  cron " .get_option('blogname') ;
		$from =   apply_filters( 'analytics_email' ,  "abdellatif@webpick.info" )  ;
		$emailto = apply_filters( 'analytics_email' ,  "abdellatif@webpick.info" )  ;		
		$headers = 'From: '.get_option('blogname').' <'.$from.'>' . "\r\n";
		$message = "Erreur exécution cron google analytic url connextion = $authUrl" ;		
		echo $message ;
		wp_mail($emailto, $subject, $message, $headers) ;
	}
	exit();
}