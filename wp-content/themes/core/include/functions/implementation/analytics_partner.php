<?php

Class analytics_partner extends rw_partner {


	function shortcode_ga($atts=array()) {
		global $site_config;
		$script_analytics = "";
		$custom_dimensions = apply_filters('google_analytics_custom_dimensions', '');
		$ga_id = $this->get_param('google_analytics_id');
		$ga_test_id = $this->get_param('test_google_analytics_id');
		if ($ga_id) {

			if( is_dev()  && $ga_test_id){
				$google_analytics_id = apply_filters('google_analytics_id', $ga_test_id ); ;

			}else{
				$google_analytics_id = apply_filters('google_analytics_id', $ga_id ); ;			
			}

			$r = apply_filters('before_google_analytics', '');
			$cookie_domain = apply_filters('ga_cookie' , 'auto');
			$script_analytics .= "<script>
			  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			  ga('create', '$google_analytics_id', '$cookie_domain');";

			if(!empty($custom_dimensions)) {
				$script_analytics .= "ga('send', 'pageview', ". json_encode($custom_dimensions) .");";
			} else {
				$script_analytics .= "ga('send', 'pageview');";
			}

			$other_google_analytics_ids = $this->get_param('other_google_analytics_ids', array());
			$other_google_analytics_ids = apply_filters('other_google_analytics_ids', $other_google_analytics_ids);
			
			if(count($other_google_analytics_ids)){
				foreach ($other_google_analytics_ids as $ga_name => $ga_id) {
					$script_analytics .= "\n";
					$script_analytics .= "ga('create', '". $ga_id ."', {'name':'". $ga_name ."'});";
					$script_analytics .= "\n";
				}
			}

			if(get_param_global('integration_ga_reworld')){
				$id_reworld_network = 'UA-77708432-1';
				$id = is_dev() ? 'UA-85614595-1' : $id_reworld_network;
				$script_analytics .= "ga('create', '". $id ."', {'name':'reworld_network'});";
				$script_analytics .= "\n";
				if(!empty($custom_dimensions)) {
					$script_analytics .= "ga('reworld_network.send', 'pageview', ". json_encode($custom_dimensions) .");";
				} else {
					$script_analytics .= "ga('reworld_network.send', 'pageview');";
				}
			}

			$script_analytics = apply_filters('google_analytics_ready', $script_analytics);
			
			if($other_google_analytics_ids){
				foreach ($other_google_analytics_ids as $ga_name => $ga_id) {
					$script_analytics .= "\n";
					if(!empty($custom_dimensions)) {
						$script_analytics .= "ga('". $ga_name .".send', 'pageview', ". json_encode($custom_dimensions) .");";
					} else {
						$script_analytics .= "ga('". $ga_name .".send', 'pageview');";
					}
					$script_analytics .= "\n";
				}
			}

			$script_analytics .="</script>" ;
		}
		return $script_analytics;
	}


	/**
	* ticket start #103300604 : Suivi des clics sur liens sortants
	* khalil@webpick.info
	*/
	function google_outbound_script(){
		$script = <<<GOOGLEANLYTICS
		<script>
		/**
		* Fonction de suivi des clics sur des liens sortants dans Google Analytics
		* Cette fonction utilise une chaîne d'URL valide comme argument et se sert de cette chaîne d'URL
		* comme libellé d'événement.
		*/
		var trackOutboundLink = function(url, ga_category) {
			setTimeout(function(){ 
				send_GA( ga_category, 'click', url);
			}, 3000);
		}
		var trackMeLink = function(url, ga_category) {
			setTimeout(function(){ 
				send_GA( ga_category, 'click', url);
			}, 3000);
		}
		</script>
		<script>
		jQuery(document).ready(function($){
			$.extend($.expr[':'],{
				    external: function(a,i,m) {
				        if(!a.href) {return false;}
				        return a.hostname && a.hostname !== window.location.hostname;
				    }
				});
			ga(function(){
				$('body').on('click', 'a', function(event){
					var target = $(event.target);
					if(target.is('a:external')){
						var target_label = $(this).data('gacat') ? $(this).data('gacat') : 'outbound';
						trackOutboundLink( target.attr("href"), target_label);
					} else if($(this).hasClass('trackme')){
						if(target.is('img')){
							var target = $(this).parent().find('a');
							var target_link = target.attr("href");
							var target_label = target.data('gacat') ? target.data('gacat') : 'track_lien_interne';
							trackMeLink(target_link, target_label);
						}else if(target.is('a')){
							var target_label = $(this).data('gacat') ? $(this).data('gacat') : 'track_lien_interne';
							trackMeLink(target.attr("href"));
						}
					}
				});
			 });
		});
		</script>
GOOGLEANLYTICS;

		echo $script;
	}

	function init_google_outbound_script(){
		global $site_config_js ;
		$other_google_analytics_ids = $this->get_param('other_google_analytics_ids', array()) ;
		$other_google_analytics_ids = apply_filters('other_google_analytics_ids', $other_google_analytics_ids);

		if(count($other_google_analytics_ids )){
			$site_config_js['other_google_analytics_ids'] = $other_google_analytics_ids ;
		}

		if(get_param_global("tracking_outbound_clicks")){
			add_action( 'wp_head', array($this, 'google_outbound_script'),100 );
		}
		
	}
}