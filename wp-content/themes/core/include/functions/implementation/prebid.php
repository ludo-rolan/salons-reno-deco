<?php

class prebid extends rw_partner {

    function init(){
    	add_filter( 'partner_filter_amazon', '__return_false');
		add_action('wp_head', array($this, 'wp_head'),5);   
		add_filter('dfp_function_display_js', array($this, 'dfp_function_display_js'));  
		add_filter('dfp_v2_after_enableservices', array($this, 'display_via_prebid_refresh'), 1,3);
		add_filter('dfp_v2_disableInitialLoad', '__return_true');
		//add_filter('dfp_v2_refresh_lazyloading', '__return_empty_string');  
    }


    function wp_head(){
        
        $site_folder = $this->get_param("site_folder");

        if(!is_dev()){
            //script for production
            echo "<script type='text/javascript' async src='https://prebid.reworldmediafactory.com/$site_folder/script.min.js?version=". CACHE_VERSION_CDN ."'></script>";
        }else {
            //script for preprod
            echo "<script type='text/javascript' async src='https://prebid-dev.reworldmediafactory.com/$site_folder/script.min.js?version=". CACHE_VERSION_CDN ."'></script>";
        }
        // else{
        //     //script for sandbox
        //     // this file can be used internally for local testing :)
        //     echo "<script type='text/javascript' async src='".STYLESHEET_DIR_URI.'/assets/javascripts/prebid-sandbox.js'."'></script>";
        // }
    }


    public function dfp_function_display_js($js) {
        $js = '
        <script type="text/javascript">
        
            function dfp_display_js (div_id, format_name ){
                googletag.cmd.push(function() { 
                    googletag.display(div_id);
                });
            } 

            function dfp_refresh_lazy_load_js (div_id, format_name ){
                console.log("%c => Refreshing with Lazyloading DFP PREBID for "+ format_name,"background: #222; color: #bada55");
                reworldAd.que.push(function() {
                    reworldAd.refresh([site_config_js.dfp_slots[format_name] ]) ; 
                }); 
            }

            function dfp_show_one_slot ( format_name ){
            	googletag.cmd.push(function() {
		            reworldAd.que.push(function() {
		                if(site_config_js.dfp_slots[format_name]){
			                console.log("%c => SHOWING single ad with DFP PREBID for "+ format_name,"background: #222; color: #bada55");
			                
			                    reworldAd.refresh([site_config_js.dfp_slots[format_name] ]) ; 
		                }
		            }); 
	            }); 
            }

            function dfp_refresh_all_ads(){
                console.log("%c => Refreshing all ads by DFP PREBID","background: #222; color: #bada55");
                reworldAd.que.push(function(){
                    reworldAd.refresh();
                });
            }



        </script>' ;
        return $js ;
    }


    function display_via_prebid_refresh($s,$slots_to_refresh,$formats_lazyloading){

        //to_do_khalil: remove this items after tests or comment the console.log line
        $json_slots_to_refresh = json_encode($slots_to_refresh);
        $json_formats_lazyloading = json_encode($formats_lazyloading);

    	$js = <<<PREBID
    		var reworldAd = reworldAd || {};
            reworldAd.que = reworldAd.que || [];
            console.log('%c => Slots to refresh : $json_slots_to_refresh','background: #222; color: #bada55');
            console.log('%c => Lazy loaded ads : $json_formats_lazyloading','background: #222; color: #bada55');
PREBID;
        if(empty($slots_to_refresh)){
            $js .= <<<PREBID
            console.log('%c => no lazyload : SHOWING ALL ADS','background: #222; color: #bada55');
            dfp_refresh_all_ads();
PREBID;
        }/*else{
            
            $js .= <<<PREBID
            console.log('%c => lazyload : SHOWING ONLY SlotstoRESFRESH the rest will be lazyloaded','background: #222; color: #bada55');
PREBID;
            //to_do_khalil: remove this items after tests or comment the console.log line
            foreach ($slots_to_refresh as $slot) {
                $js .= <<<PREBID
                dfp_show_one_slot('$slot');
PREBID;
            }          
        }*/
    	return $js;
    }

}