function setCookie(cname, cvalue, exminutes) {
    var d = new Date();
    d.setTime(d.getTime() + (exminutes * 60 * 1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function getCookie(cname) {
    var name = cname + "=";
    try{
    	var decodedCookie = decodeURIComponent(document.cookie);
	}catch( e){
	    var decodedCookie = document.cookie;
	}
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
function display_dfp_pub_onscroll(tag_div, format_id ){
	// Valeur de scrollY lorsque l'annonce est sur le point d'être visible.
	site_config_js.dfp_elemnts = site_config_js.dfp_elemnts || {} ;
	var $element = jQuery('#'+tag_div); 



	  // Avertissement : Il s'agit d'un exemple de mise en œuvre. Écouter l'événement onscroll 
	  // sans fonction throttle peut ne pas être très efficace.
	  site_config_js.dfp_refreshed = site_config_js.dfp_refreshed || {} ;
	  var listener = function() { 
	  	var scroll = window.scrollY + jQuery(window).height() ;
	  	if ((scroll >= $element.offset().top - 100 ) && (scroll >= $element.parent().offset().top - 100 )  && ! site_config_js.dfp_refreshed[format_id]) {
	  		
	  		if(site_config_js.devs.amazon_mobile_tr_3140 && site_config_js.partners.amazon ){
		  		googletag.pubads().refresh(googletag.pubads().getSlots().filter(function(slot){
					return slot.getSlotElementId() === tag_div;
				}));
	  		}else{
	  			dfp_refresh_lazy_load_js (tag_div, format_id);
	  			/*googletag.cmd.push(function() {
		  			googletag.pubads().refresh( [site_config_js.dfp_slots[format_id] ]) ; 
		  		});*/
	  		}

	      	// Actualiser l'annonce une seule fois
	      	site_config_js.dfp_refreshed[format_id] = true;	
	  		jQuery(document).ready(function(){
				if(typeof adblock != 'undefined' && !adblock){
					if( site_config_js.manage_tracking_ga){ 
						if( site_config_js.manage_tracking_ga.indexOf(format_id) != -1 ){ 
							send_GA( "DFP", format_id, self.location.href);
						}
					}else{ 
						send_GA( "DFP", format_id, self.location.href);
					}
				}else{
					if( !site_config_js.disable_dfp_adblocker_event ){
						send_GA( "DFP Adblocker", format_id, self.location.href);
					}
				}	
	  		});



	      // Supprimer l'écouteur
	      window.removeEventListener('scroll', listener);
	  }
	}
	window.addEventListener('scroll', listener);
}

jQuery(document).on("change_item" , function(e , i, rafraichir_pub) {

	if(rafraichir_pub && window.googletag){
		dfp_refresh_all_ads();
		var dfp_ids = site_config_js.dfp_ids ;
		for (i in dfp_ids){
			var id = dfp_ids[i];

			if(typeof adblock != 'undefined' && !adblock){
				if( site_config_js.manage_tracking_ga){ 
					if( site_config_js.manage_tracking_ga.indexOf(id) != -1 ){ 
						setTimeout(function(){ 
							send_GA( "DFP", id, self.location.href);
						}, 3000);
					}
				}else{ 
					setTimeout(function(){ 
						send_GA( "DFP", id, self.location.href);
					}, 3000);
				}
			}else{
				if( !site_config_js.disable_dfp_adblocker_event ){
					setTimeout(function(){ 
						send_GA( "DFP Adblocker", id, self.location.href);
					}, 3000);
				}
			}	
			
		}

	}
});

jQuery(document).ready(function() {
	if(window.googletag){
		googletag.cmd.push(function() {
		    googletag.pubads().addEventListener('slotRenderEnded', function(event) {
		        // Récupérer l'id du div contenant la pub
		        var id_div = event.slot.getSlotId().getDomId();
		        $('#' + id_div).addClass('filled-with-pub');
		    });
		});
	}
});
	