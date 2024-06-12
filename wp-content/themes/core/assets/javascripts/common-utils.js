// using jquery from wordpress.. 
// $ is not defined
var $ = jQuery;
var GA_EVENT_DM  = site_config_js.devs.ga_event_dm_149424965  ; 
var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer, Inc/.test(navigator.vendor);
var isFireFox = navigator.userAgent.indexOf('Firefox');


var play_on_nav_diapo = false;

if(is_desktop){
    /*if( site_config_js.devs.lancement_player_sticke_153138287 && navigator.userAgent.indexOf("Edge/") > -1 ){
        play_on_nav_diapo = true;
    }else*/ if( site_config_js.devs.lancement_player_sticke_firefox_153138287 && isFireFox > -1 ){
        play_on_nav_diapo = true;
    }else if( site_config_js.devs.lancement_player_sticke_chrome_153138287 && isChrome ){
        play_on_nav_diapo = true;
    }/*else if( site_config_js.devs.lancement_player_sticke_ie_153138287 &&  isIE( navigator.userAgent) ){
        play_on_nav_diapo = true;
    }*/else if( site_config_js.devs.lancement_player_sticke_safari_153138287 && isSafari ){
        play_on_nav_diapo = true;
    }
}


function targetBlank() {
    $("footer a").unbind();
    $("footer a").bind("click", function() {
        this.target = '_blank';
    });

}
var indexCaroussel = 0;


function autoCaroussel() {
    var liCaroussel = $(".carroussel .navs_absolute").children();
    if (indexCaroussel < liCaroussel.length - 1)
        indexCaroussel += 1;
    else
        indexCaroussel = 0;
    $(liCaroussel[indexCaroussel]).click();
}

function navCaroussel() {
    $(".carroussel .navs li").each(function(i, obj) {
        $(obj).unbind();
        $(obj).bind("click", function() {
            clearInterval(window.timerCaroussel);
            window.timerCaroussel = setInterval(autoCaroussel, 8000);
            navCarousselAnimate(i);
        });
    });

    $("#content .carroussel .nav_next").on('click.caroussel',function() {
        width_event = (self.site_config_js && self.site_config_js.thumbnail_min_carroussel) ? self.site_config_js.thumbnail_min_carroussel : 174;
        var $caroussel = $(this).parents('.carroussel'),
        $nav_abs = $caroussel.find('.navs .navs_absolute'),
        $nav_rel = $caroussel.find('.navs .navs_relative');
        left = $nav_abs.position().left;
        navs_relative_width = $nav_rel.width();
        navs_total_width = $nav_abs.find("li").length * width_event;
        if (navs_relative_width - left - navs_total_width < 0) {
            $nav_abs.stop().animate({
                "left": left - width_event
            }, 500);
        } else {
            clearInterval(window.timerCaroussel);
            window.timerCaroussel = setInterval(autoCaroussel, 8000);
            $($(".carroussel .navs li")[0]).click();
            $nav_abs.stop().animate({
                "left": 0
            }, 500);
            clearInterval(window.timerCaroussel);
            window.timerCaroussel = setInterval(autoCaroussel, 8000);
        }
        return false;
    });

    $("#content .carroussel .nav_prev").on('click.caroussel',function() {
        width_event = (self.site_config_js && self.site_config_js.thumbnail_min_carroussel) ? self.site_config_js.thumbnail_min_carroussel : 174;
        var $caroussel = $(this).parent('.carroussel'),
        $nav_abs = $caroussel.find('.navs .navs_absolute'),
        $nav_rel = $caroussel.find('.navs .navs_relative'),
        left = $nav_abs.position().left;
        if (left < 0) {
            $nav_abs.stop().animate({
                "left": left + width_event
            }, 500);
        } else {
            navs_relative_width = $nav_rel.width();
            navs_total_width = $nav_abs.find("li").length * width_event;
            $nav_abs.stop().animate({
                "left": navs_relative_width - navs_total_width
            }, 500);
        }
        return false;
    });
}


function navCarousselAnimate(i) {
    $(".carroussel .items").stop().animate({
        left: -(i * 654)
    }, 1000, function() {
        $(".carroussel .navs li").removeClass("current");
        $($(".carroussel .navs li").get(i)).addClass("current");
        indexCaroussel = i;
    });
}

function desactiveClickOnMenu() {
    $("nav ul#menu-menu_header>li.menu-item>a,footer .center .menus ul#menu-menu_header-1>.menu-item>a").unbind();
    $("nav ul#menu-menu_header>li.menu-item>a,footer .center .menus ul#menu-menu_header-1>.menu-item>a").bind("click", function() { //alert("-----");
        if (!$(this).parent().hasClass("sagachanel"))
            return false;
    });
}

function showSocialLink() {

}


function getUrlVars() {
    var vars = [],
        hash;
    var hashes = window.location.href.split('/');
    hashes = hashes[hashes.length - 1].split('?');
    hashes = hashes[0];
    hashes = hashes.split('#');
    return hashes[1];
}


function clickArticles() {
    $(".linkArticle").unbind();
    $(".linkArticle").bind("click", function() {
        $(this).find(".readmore").click();
    });

}
var indexDiaporama = 0;
try {
    indexDiaporama = Number($("#content .diaporama .navs li.current").attr("index"));
} catch (e) {}

function autoDiaporama() { //alert("---");
    var liDiaporama = $(".diaporama .navs_absolute").children();
    if (indexDiaporama < liDiaporama.length - 1)
        indexDiaporama += 1;
    else
        indexDiaporama = 0;
    $(liDiaporama[indexDiaporama]).click();
}

function centerImgDiaporama(photo, el) {
    height_diaporama = (self.site_config_js && self.site_config_js.height_diaporama) ? self.site_config_js.height_diaporama : 465;
    height = $(el).height();
    $(el).css("top", (height_diaporama - height) / 2);
}

function centerImgCarroussel(photo, el) {
    height = $(el).height();
    $(el).css("margin-top", (((height - 356) / 2) * (-1)));
}

function navDiaporamaAnimate(index) {
    $(".diaporama .items").stop().animate({
        left: -(index * 648)
    }, 1000, function() {
        $(".diaporama .navs li").removeClass("current");
        $($("#content .diaporama .navs li")[index]).addClass("current");
        indexDiaporama = Number(index);
        $("#blockRight .widgetLegende .title").text(diaporamaPicturelists[indexDiaporama].alttext);
        $("#blockRight .widgetLegende .excerpt").text(diaporamaPicturelists[indexDiaporama].description);
        if (indexDiaporama + 1 == diaporamaPicturelists.length) {
            $(".homeMoreArticles").fadeIn("Slow");
        } else {
            $(".homeMoreArticles").fadeOut("Slow");
        }
        try {
            window.history.pushState(null, "", $($("#content .diaporama ul li")[index]).find("a").attr("href"));
        } catch (e) {}
    });
}

function navDiaporama() {

    $("#content .diaporama .nav_menu_next").click(function() {
        var item = $("#content .diaporama .navs .navs_absolute li").first();
        item.hide("slow", function() {
            $("#content .diaporama .navs .navs_absolute").append(item);
            item.show();
        });
        return false;
    });
    $("#content .diaporama .nav_menu_prev").click(function() {
        var item = $("#content .diaporama .navs .navs_absolute li").last();
        item.hide();
        $("#content .diaporama .navs .navs_absolute").prepend(item);
        item.show("solw");
        return false;
    });
}

function paginationHome() {
    $(".paginationHome span").each(function(i, obj) {
        $(obj).unbind();
        $(obj).bind("click", function() {
            $(".paginationHome span").removeClass("current");
            $(this).addClass("current");
            $("#homeBody .articles").hide();
            $("#homeBody .articles.item_" + $(this).html()).fadeIn(700);

            //Amine LAAROUSSI
            $("#results .articles li a .visu img, .homeMoreArticles .item .visu a img").each(function(index) {
                $(this).css("margin-top", ((($(this).height() - $(this).closest(".visu").height()) / 2) * (-1)));
            });
        });

    });
}

function paginationRubrique() {
    $(".pagination_rubrique li a").each(function(i, obj) {
        $(obj).unbind();
        $(obj).bind("click", function() {
            $(".pagination_rubrique li").removeClass("active");
            $(this).parent().addClass("active");
            $("#homeBody .articles").hide();
            $("#homeBody .articles.item_" + $(this).html()).fadeIn(700);
            jQuery('html, body').animate({
                scrollTop: jQuery(".items-posts").offset().top - jQuery(".navbar-site #principal-menu").height() - jQuery("#wpadminbar").height()
            }, 700);

            $(".number_page .active_page").html($(this).html());
            return false;
        });

    });
}

function strip_tags(input, allowed) {

    allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    return input.replace(commentsAndPhpTags, '').replace(tags, function($0, $1) {
        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}

var youtube_players=[];
function show_video(id_block, url_template, pub_ads, type_provider, src_splash, autoPlay, config_params) {

	var mutevideo = config_params.mutevideo ;
	var top = 0 ;
    if (config_params.youtube_id && jQuery.browser.mobile ) { 
        var height = jQuery("#" + id_block).height();
        var width = jQuery("#" + id_block).width();
        if(config_params.diapo_full){
        	width = jQuery("#" + id_block).parent().parent().outerWidth();
        	var height_parent = jQuery("#" + id_block).parent().parent().outerHeight();
        	height = width * 390/460 ;
        	top = (height_parent - height) /2 ;
        }
        
        // ajouter les youtube à la liste de youtubes players
        var yt_player = {'id_block':id_block, 'yt_id':config_params.youtube_id, 'width':width, 'height':height};
        youtube_players.push(yt_player);
        if(!script_is_loaded) {
            show_all_youtube(youtube_players);
        } else {
            createPlayer(yt_player);
        }

        return;
    }


	if(type_provider == 'dailymotion'){
		var params = {
			"video" : config_params.dailymotion_id ,
			"width" : "100%"
		} ;

		if(config_params.width){
			params.width = config_params.width ;
		}else{
			params.width = jQuery("#" + id_block).parent().width();  		
		}
		if(config_params.width){
			params.width = config_params.width ;
		}else{
			params.width = jQuery("#" + id_block).parent().width();  		
		}

		if(config_params.height){
			params.height = config_params.height ;
		}else{
			params.height = params.width * 390/460 ;	
		}

        if((config_params.params !== undefined && config_params.params.autoplay) || autoPlay ){
            params.params = {};
            params.params.autoplay = true;
        }	

		show_dai_video(id_block, params, pub_ads) ;
		return ;
	}else if(config_params.mediaid.indexOf('adways.com') > -1 ){

		var params = {
			"width" : "100%",
			"src" : config_params.mediaid
		} ;

		if(config_params.width){
			params.width = config_params.width ;
		}else{
			params.width = jQuery("#" + id_block).parent().width();  		
		}
		if(config_params.width){
			params.width = config_params.width ;
		}else{
			params.width = jQuery("#" + id_block).parent().width();  		
		}

		if(config_params.height){
			params.height = config_params.height ;
		}else{
			params.height = params.width * 390/460 ;	
		}		

		show_adways_player(id_block, params) ;
		return ;

	}


    var clip_fp;
    if (type_provider == 'youtube') {
        clip_fp = {
            autoPlay: autoPlay,
            autoBuffering: false,
            provider: 'youtube',
            urlResolvers: ['youtube', 'bitrateselect']
        };
    } else if (type_provider == 'dailymotion') {
        clip_fp = {
            video_id:config_params.dailymotion_id,
            autoPlay: autoPlay,
            autoBuffering: false,
            provider: 'dailymotion',
            urlResolvers: ['dailymotion', 'bitrateselect']
        };
    } else {
        clip_fp = {
            autoPlay: autoPlay,
            autoBuffering: false
        };
    }
    if (config_params.url) {
        clip_fp.url = config_params.url;
    }
    var mute_ = false;
	if(typeof mutevideo === 'undefined' ){
		mutevideo = false;
	}
	if(mutevideo){
		mute_ = true;
	}

    clip_fp.eventCategory = 'Video player';
    var params = {
        clip: clip_fp,
        onStart: function(e) {
            sendEvent(e, "Start");
            $f().getPlugin("adswizz").hide();
        },
        onLoad : function(){
        	if(mute_){
        		this.mute();
        	}
        },
        // onPause: function(e) {
        //     sendEvent(e, "Pause");
        // },
        // onResume: function(e) {
        //     sendEvent(e, "Resume");
        // },
        // onSeek: function(e) {
        //     sendEvent(e, "Resume");
        // },
        onStop: function(e) {
            sendEvent(e, "Stop");
        },
        onFinish: function(e) {
            sendEvent(e, "Finish");
        },
        // Track ads playing
        onAdsLoadComplete : function() { 
        	console.log( 'AdsLoadComplete event ') ;
        	sendEvent('adwizz', "AdsLoadComplete");
        } ,
        onAdsLoadFailed : function() { 
	        console.log( 'AdsLoadFailed event') ;  
			sendEvent('adwizz', "AdsLoadFailed");
     	} ,
        onAdStarted : function() { 
        	console.log( 'AdStarted event') ;
			sendEvent('adwizz', "AdStarted");

        } ,
        onAdComplete : function() { 
        	console.log( 'AdComplete') ; 
			sendEvent('adwizz', "AdComplete");
        } ,
        onAdsComplete : function() { 
        	console.log( 'onAdsComplete event') ;  
	        sendEvent('adwizz', "AdsComplete");
        } ,
        onBeforeFinish: function (clip) {
        	if(config_params.autoloop && config_params.autoloop.toLowerCase() == 'yes'){
				show_video(id_block, url_template, pub_ads, type_provider, src_splash, autoPlay, config_params, mutevideo);
	        	return false;
        	}
            var setup = '';
            if (config_params.playlist) {
                setup = { "playlist" : config_params.playlist };
            }
            playlist(id_block, setup, config_params, type_provider, pub_ads);
      	},
        plugins: {
            controls: {
                autoHide: "always"
            },
            
            // bandwidth detection plugin
            bitrateselect: {
                url: url_template + "/assets/flowplayer/flowplayer.bitrateselect.swf",
                onStreamSwitchBegin: function(newItem, currentItem) {
                    $f().getPlugin('content').setHtml("Will switch to: " + newItem.streamName +
                        " from " + currentItem.streamName);
                },
                onStreamSwitch: function(newItem) {
                    $f().getPlugin('content').setHtml("Switched to: " + newItem.streamName);
                }

            }
            // ,
            // /***end youtube**/
            // gatracker: {
            //     url: url_template + "/assets/flowplayer/flowplayer.analytics-3.2.9.swf",

            //     // track all possible events. By default only Start and Stop
            //     // are tracked with their corresponding playhead time.
            //     events: {
            //         all: true
            //     },
            //     debug: false,
            //     accountId: site_config_js.google_analytics_id // your Google Analytics id here
            // }

        }

    };
    if (type_provider == 'dailymotion') {
        params.plugins.dailymotion = {
            url: url_template + "/assets/flowplayer/flowplayer.dailymotion-3.2.12.swf",
            qualityLabels: {
                "hd720": "HD 720p",
                "hq": "Large 480p",
                "sd": "Medium 320p",
                "ld": "Small"
            },
            bitratesOnStart: false,
        };
    }else if (type_provider == 'youtube'){
	    params.plugins.youtube = {
	        url: url_template + "/assets/flowplayer/flowplayer.youtube-3.2.11.swf",
	        hd: true,
	        defaultQuality: "large",
	        hdLevel: "hd720",
	        loadOnStart: true,
	        enableGdata: false,
	        onVideoRemoved: function() {
	            console.log("Video Removed");
	        },
	        onVideoError: function() {
	            console.log("Incorrect Video ID");
	        },
	        onEmbedError: function() {
	            console.log("Embed Not Allowed");
	        },
	        onApiData: function(data) {

	            // use the function defined above to show the related clips
	            console.log("Received data");
	            // use the function defined above to show the related clips
	            showInfo(data, "#playlist1");

	            //load the related list for the first clip only
	            if (shownRelatedList) return;
	            showRelatedList(data, "#playlist1");
	            shownRelatedList = true;
	        },
	        displayErrors: false
	    };   
    }

    if (pub_ads && !pub_ads.type) {
        params.plugins.adswizz = {
            url: url_template + "/assets/flowplayer/AdswizzVIP-Flowplayer-3.2.4.swf",
            server: "ads.stickyadstv.com",
            loadTimeout: 10000,
            ads: pub_ads,
            adTimeDisplay: {
                text: "Your video will start in [time] seconds"
            }
        };
    }
    if(pub_ads && pub_ads.type == 'liverail'){
    	if(pub_ads.liverail){
		    params.plugins.liverail = pub_ads.liverail ;
	    	params.plugins.liverail.url = url_template + "/assets/flowplayer/LiveRailPlugin327.swf";
    	}
    	if(pub_ads.liverail2){
		    params.plugins.liverail2 = pub_ads.liverail2 ;
	    	params.plugins.liverail2.url = url_template + "/assets/flowplayer/LiveRailPlugin327.swf";
    	}
    	if(pub_ads.liverail3){
		    params.plugins.liverail3 = pub_ads.liverail3 ;
	    	params.plugins.liverail3.url = url_template + "/assets/flowplayer/LiveRailPlugin327.swf";
    	}
    }
   
    if (type_provider == 'dailymotion') {
        play_dailymotion(id_block, params);
    } else {
    	
    	var player_params = {src:url_template + "/assets/flowplayer/flowplayer-3.2.16.swf", wmode: "transparent"} ;
    	// Other flash fit
	    if ('flashfit' in config_params ) {
	        player_params.flashfit = config_params.flashfit;
	    }
	    // adaptive ratio
	    if ( 'adaptiveratio' in config_params ) {
	        player_params.adaptiveRatio = config_params.adaptiveRatio;
	    }
	    // personal ratio
	    if ( 'ratio' in config_params ) {
	        player_params.ratio = config_params.ratio;
	    }

        flowplayer(id_block, player_params , params);

        $f(id_block).onCuepoint([1000], function(clip, cuepoint) {
	        // put some logging information into firebug console
	        var duration =  clip.duration ; 
	        p25 =  duration *1000 /8 ;
	        p50 =  duration *1000 /2 ;
	        p75 =  duration *1000 * 3/4 ;
	        if(clip.cuepoints.length <2){
	        	this.onCuepoint([{time:p25, title:'lecture à 25% '}, {time:p50, title:'lecture à 50% '}, {time:p75, title:'lecture à 75% '}], function(clip, cuepoint) {
                    send_GA( 'Video player', cuepoint.title, self.location.href);
	       		 });
	        }

	    });
    }
    
}

var first_clickToPlay = true;

function force_play_by_api(player, type){
    if(first_clickToPlay){
        if(type === 'jwp7' && player.getState() !== 'playing'){
            player.play();
            first_clickToPlay = false;
        }else if(type === 'videojs' && player.paused() ){
            player.play();
            first_clickToPlay = false;
        }else if(type === 'api_daily'){
            player.play();
            first_clickToPlay = false;
        }
    }
}

/**
 * Forcer le play lors d'un clic sur le botton "cliquez pour lire la suite"
 * @param  {[object]}  player         [le player en cours]
 * @param  {Boolean} is_right_video [true si le player existe dans la sidebar]
 */
function play_on_click(player, is_right_video, type){
    if( typeof player !== "undefined" ){
        var $btn_more = $('#morebtn');
        if($btn_more.length){
            $btn_more.on('click', function(){
                force_play_by_api(player, type);
                $('#all_content').removeClass('hidden');
                $( ".article_body_content *:first-child + p,.article_body_content > p").addClass('first_paragraph');
                $btn_more.remove();
            });
        }
        if( play_on_nav_diapo ){
            if(is_right_video){
                var $btn_navs = $('#scroll_arrows .btn-navs');
                if($btn_navs.length > 0){
                    $btn_navs.on('click', function(){
                        force_play_by_api(player, type);
                    });
                }
            }
        }
    }
}

// Param global pour identifier que c'est une playlist sidebar sur IE
isIE_pl = false;




function show_dai_video(id_block, params, pub_ads){


	scroll_video_ready = window.scroll_video_ready ||{} ;

	$_window = window.$_window ||  jQuery(window) ;

	if( Object.keys(scroll_video_ready).length == 0){
		$_window.scroll(function(){ 
			if( ! scroll_video_ready[id_block] && $_window.scrollTop() > 201 ){
				scroll_video_ready[id_block] = true ;
				show_dai_video_origin(id_block, params, pub_ads);	
			}
	  	});

	}else{
		show_dai_video_origin(id_block, params, pub_ads);	
	}



}

function show_dai_video_origin(id_block, params, pub_ads){

	
    var origin_params = params;

    if( is_desktop && ( (isChrome && site_config_js.devs.desactiver_autoplay_inchrome_153133569) || 
        (isSafari && site_config_js.devs.desactiver_autoplay_insafari_153879194) ) ){
        params.params.autoplay = false;
        params.scroll = false;
    }

    var videoPlayer = 'Video dailymotion';
    var tracking_Params = {
        'videoId' : params.video,
        'videoName' : '',
        'videoPlayer' : videoPlayer,
        'type_provider' : 'Dailymotion'
    };

    if( play_on_nav_diapo ){
        if(params.right_video){
            params.params.autoplay = false;
        }
    }
    //Disable autoplay dailymotion if true to enable the vidcoin param autoplay
    if(site_config_js.devs.mise_en_place_vidcoin_151485991){
		var vidcoin_params = vidcoin_params_converter(params);
	}

	if(is_mobile && site_config_js.devs.autoplay_sites_mobiles_156367647){
		params.params.playsinline=true;
		params.params.muted= true;
	}

 	console.log(params);
 	console.log(params);

	if( /*isChrome &&*/ is_desktop &&  (params.params.autoplay || params.scroll ) && site_config_js.devs.autoplay_ko_sur_le_player_dm_158247682){
		params.params.muted= true;
	}

    if(site_config_js.remove_logo_dm_player) {
        params.params["ui-logo"] = false;
    }
    if(site_config_js.dm_ads_params){
		params.params.ads_params= site_config_js.dm_ads_params;
    }

    var player = DM.player(document.getElementById(id_block), params);
    if(params.loop){
    	player.loop = true;
    }


    if(site_config_js.devs.corriger_autoplay_des_videos_dm_sur_ie_0815 && (isIE( navigator.userAgent)  || navigator.userAgent.indexOf("Edge/") > -1 ) && origin_params.params.autoplay){
    	player.addEventListener('apiready', function (e) {
			player.play();
		});
    }


    if(site_config_js.devs.autoplay_firefox_155394464 && isFireFox > -1 && origin_params.params.autoplay){
        player.addEventListener('apiready', function (e) {
            player.play();
        });
    }

    //Start code added for tagage 1000mercis
    var prsentPoint = [];
    nextPoint = 0;
    player.addEventListener("durationchange", function(){
        if(prsentPoint.length === 0){
	        videoDuration = this.duration;
	        for(j=0; j<100; j=j+25){
	            prsentPoint.push(Array(j, videoDuration*(j/100),false));
	        }
        }
    });
    player.addEventListener('seeking', function(){
        currentTime = this.currentTime;
        for(j=0; j<prsentPoint.length; j++){
              if( currentTime > prsentPoint[j][1]){
                prsentPoint[j][2] = true;
                nextPoint = j+1;
            }else{
                prsentPoint[j][2] = false;
            }
          }
        if( ! GA_EVENT_DM ){
	        tracking_Params.event = 'Seek start';
	        $(document).trigger('video_state_tracking', tracking_Params);
	    }
    });

    if( ! GA_EVENT_DM ){
    	player.addEventListener('seeked', function(){
	        tracking_Params.event = 'Seek end';
	        $(document).trigger('video_state_tracking', tracking_Params);
	    });
    }
    
    player.addEventListener('ad_start', function(){
        tracking_Params.event = 'ad_start';
        $(document).trigger('video_state_tracking', tracking_Params);
    });

    player.addEventListener('ad_end', function(){
        tracking_Params.event = 'ad_end';
        $(document).trigger('video_state_tracking', tracking_Params);
        $(document).trigger('ads_player_ended', [id_block]);

    });

    if( ! GA_EVENT_DM ){
	    player.addEventListener('ad_play', function(){
	        tracking_Params.event = 'ad_play';
	        $(document).trigger('video_state_tracking', tracking_Params);
	    });
	}



    player.addEventListener('start', function(){
		if( params.params.hide_play_icon ){
			if( $('.special-block-video').length > 0 ){
				$("#"+id_block).parents().find('.special-block-video').addClass('video_started');
			}
		}
        /*if( is_mobile && site_config_js.devs.network_mobile_sticker_video_131559695 ){
            player.setFullscreen(true);
        }*/
    });
    


    function play_video_on_scroll(){
        if(params.scroll === true && (is_desktop || site_config_js.devs.autoplay_sites_mobiles_156367647)){
            $playlist_iframe = $('.playlist_ iframe');
            if($( window ).scrollTop() >= ( $playlist_iframe.length && $playlist_iframe.offset().top - 650 )){
                player.play();
                if(typeof player_right !== 'undefined'){                   
                    player_right.pause();
                }
            }
        }
    }

    if(!site_config_js.devs.gestion_autoplay_2videos_155518075){
        if( params.right_video ){
            player_right = player;
        }
    }

    player.addEventListener('apiready', function (e){ 
        if(params.scroll === true ){
        	if(is_desktop || site_config_js.devs.autoplay_sites_mobiles_156367647){
            	$( window ).bind('scroll', play_video_on_scroll);
        	}else{
				var $el = $(player) ;
				mobile_first_play (player, $el, 'dailymotion') ;
        	}
        }

        if(isIE(window.navigator.userAgent) && isIE_pl == false ){
            if(params.params.autoplay === true && is_desktop){
                $( window ).bind('scroll', function(){
                    player.play();
                    isIE_pl = true;
              });
            }
        }

	    if(params.params.autoplay){
			var $el = $(player) ;
			mobile_first_play (player, $el, 'dailymotion') ;
	    }

        play_on_click(this, params.right_video,'api_daily');
        if( ((typeof params.firtVideoPl !== 'undefined' && params.firtVideoPl === false) || isIE_pl) && is_desktop){
            player.play();
        }

    });

    player.addEventListener("timeupdate", function(){
        currentTime = this.currentTime;
        if(currentTime > 1){
			window.first_autoplay_mobile =  true ;
        }
        if(prsentPoint[nextPoint]+"" != "undefined" && currentTime > prsentPoint[nextPoint][1] && !prsentPoint[nextPoint][2]){
            prsentPoint[nextPoint][2] = true;
            var currentPrsentPoint = prsentPoint[nextPoint][0];
            //Trigger params (video_state_tracking_event, currentPrsentPoint, currentVideoId, currentVideoName)
            tracking_Params.event = currentPrsentPoint;
            $(document).trigger('video_state_tracking', tracking_Params);
            nextPoint++;
        }
    });
    player.addEventListener("ended", function(){
        tracking_Params.event = 'End';
        $(document).trigger('video_state_tracking', tracking_Params);
        if(window.playlist){
        	playlist(id_block, '', params, "dailymotion", pub_ads);
        }

        if( params.next === true ){
            next_video_playlist(id_block);
        }
    });

    // //End code for tagage 1000mercis
    if( ! GA_EVENT_DM ){
	    player.addEventListener("play", function(){
	        tracking_Params.event = 'Play';
            $(document).trigger('video_play', player);
	        $(document).trigger('video_state_tracking', tracking_Params);
	    });
	 }

    player.addEventListener("pause", function(){
    	if( ! GA_EVENT_DM ){
	        tracking_Params.event = 'Pause';
	        $(document).trigger('video_state_tracking', tracking_Params);
	    }
        if(params.scroll === true){
            if(is_desktop || site_config_js.devs.autoplay_sites_mobiles_156367647){
            	$( window ).bind('scroll', play_video_on_scroll);
        	}else{
				var $el = $(player) ;
				mobile_first_play (player, $el, 'dailymotion') ;
        	}
        }
    });

    player.addEventListener("volumechange", function(){
    	if( ! GA_EVENT_DM ){
	        tracking_Params.event = 'Volume changed';
	        $(document).trigger('video_state_tracking', tracking_Params);
	    }
        $(document).trigger('player_controle', [id_block, "volumechange"]);
    });

    player.addEventListener("error", function(){
        tracking_Params.event = 'Error';
        $(document).trigger('video_state_tracking', tracking_Params);
    });

    player.addEventListener("ad_end", function(){
        var volume = player.volume;
        var muted = player.muted;
        if(!muted && volume === 0){
            player.setVolume(0.5);
        }
        window.ad_playing = false;
    });

    function force_dai_player_mute(){
        if(params.params !== undefined && params.params.mute){
            player.setMuted(true);
            //$(document).trigger('player_controle', [id_block, "volumechange"]);
        }
    }
    player.addEventListener("video_start", force_dai_player_mute);

    player.addEventListener("video_start", function(){
        $(document).trigger('ads_player_ended', [id_block]);
    });


    player.addEventListener("ad_play", function(){
        if(params.params !== undefined && params.params.mute){
            if(!site_config_js.devs.player_sticke_ko_160582058){
            	player.setVolume(0);
            }

            $(document).trigger('player_controle', [id_block, "volumechange"]);
            player.removeEventListener("video_start", force_dai_player_mute);
        }
        window.ad_playing = true;
    });
    if(site_config_js.devs.mise_en_place_vidcoin_151485991){
    	add_vidcoin_player(id_block, vidcoin_params);
    }
    //Demande de Rahal 05/10/2017
    return player;
}

function vidcoin_params_converter(params){
	var vidcoin_params = {};
    if(typeof VidcoinPlayer !== "undefined" && (params.params.autoplay === true || params.params.autoplay === 'true')){
    	params.params.autoplay = false;
    	vidcoin_params.autoplay = true;
    }else{
    	vidcoin_params.autoplay = params.params.autoplay;
    }
    return vidcoin_params;
}

function add_vidcoin_player(id_block, params){
	var vidcoin = VidcoinPlayer({
		appId: "836709907177407",
		placementCodes: ["4fvn282b7604kccsgc408s04sok4cso"],
		player: {
			type: "dailymotion",
			id: id_block,
			autoplay: params.autoplay
		},
	});
}

function show_videojs(id_block, setup_origin, url_template, pub_ads, type_provider, config_params) {

    var setup = jQuery.extend( {}, setup_origin ); 

	if(is_mobile && site_config_js.devs.autoplay_sites_mobiles_156367647){
		setup.playsinline=true;
		setup.muted= true;
	}

    if( is_desktop && ( (isChrome && site_config_js.devs.desactiver_autoplay_inchrome_153133569) || 
        (isSafari && site_config_js.devs.desactiver_autoplay_insafari_153879194) ) ){
        setup.autoplay = false;
        config_params.scroll = false;
        setup_origin.autoplay = false;
    }

    if( play_on_nav_diapo ){
        if(config_params.right_video){
            setup.autoplay = false;
            setup_origin.autoplay = false;
        }
    }

	if(type_provider == 'dailymotion'){
		var params = {
			"video" : config_params.dailymotion_id ,
			"width" : "100%"
		} ;
		if(setup.autoplay) {
			params['params'] = {autoplay:true};
		}

		if(setup.width){
    		setup.width = setup.width +"" ;
    		params.width = setup.width.replace('px','');  		
    	}
    	if(setup.height){
    		setup.height = setup.height +"" ;
    		params.height =  setup.height.replace('px','');	   		
    	}

        if( config_params.next === true ){
            params.next = true;
        }

		show_dai_video(id_block, params, pub_ads) ;
		return ;
	}else if(config_params.mediaid.indexOf('adways.com') > -1){

		var params = {
			"width" : "100%",
			"src" : config_params.mediaid
		} ;

		if(setup.width){
			params.width = setup.width ;
		}else{
			params.width = jQuery("#" + id_block).parent().width();  		
		}
		if(setup.width){
			params.width = setup.width ;
		}else{
			params.width = jQuery("#" + id_block).parent().width();  		
		}

		if(setup.height){
			params.height = setup.height ;
		}else{
			params.height = params.width * 390/460 ;	
		}		

		show_adways_player(id_block, params) ;
		return ;
	}
    
    if(site_config_js.devs.wideonet_double_appel_146686499 && is_desktop){
    	setup.autoplay = false ; 
    }


	if(typeof config_params.src !=='undefined'){
		setup['poster']=config_params.src;
	}
	if(typeof setup.plugins ==='undefined'){
		setup.plugins={};
	}
    if(pub_ads && pub_ads.type == 'liverail'){
    	console.log('liverail: ',pub_ads);
    	if(pub_ads.liverail!=='undefined'){
		    setup.plugins.LiveRail = pub_ads.liverail;
    	}
    }

    if(setup.cat_event){
		var cat_event = setup.cat_event ;
    }else{
		var cat_event = "Video player js";

    }
	

    var player = videojs(id_block, setup, function() {
        var videoId = '';
        if(type_provider == "youtube"){
            videoId = config_params.youtube_id;
        }
        var tracking_Params = {
            'videoId' : videoId,
            'videoName' : '',
            'videoPlayer' : cat_event,
            'type_provider' : type_provider
        };

        var activUri = document.location.href ;
        //Start code added for tagage 1000mercis
        
        var prsentPoint = [];
        nextPoint = 0;
        this.on("loadedmetadata", function(){
        	if(prsentPoint.length === 0){
	            videoDuration = this.duration();
	            for(j=0; j<100; j=j+25){
	                prsentPoint.push(Array(j, videoDuration*(j/100),false));
	            }
            	tracking_Params.event = 'loadedmetadata';
            	$(document).trigger('video_state_tracking', tracking_Params);
        	}
            if(config_params.scroll === true  ){

	            if(is_desktop || site_config_js.devs.autoplay_sites_mobiles_156367647){
                	$( window ).bind('scroll', play_video_on_scroll_js);
	        	}else{
					var $el = $(player.el()) ;
					mobile_first_play (player, $el, 'videojs');
	        	}


            }
            play_on_click(this, config_params.right_video,'videojs');

            if(typeof config_params.firtVideoPl !== 'undefined' && config_params.firtVideoPl === false && is_desktop){
                this.play();
            }

        });

        
        player_ = this;

        // Start youtube video at the time set in the param 'start_at'
        if(type_provider == 'youtube' && (typeof setup.start_at !== 'undefined' && setup.start_at)) {
            player_.currentTime(parseInt(setup.start_at));
        }

        function play_video_on_scroll_js(){
            if(config_params.scroll === true && (is_desktop || site_config_js.devs.autoplay_sites_mobiles_156367647) ){
                if($( window ).scrollTop() >= ( $('.playlist_ iframe').offset().top - 650 )){
                    player_.play();
                    if(typeof player_right !== 'undefined'){
                        player_right.pause();
                    }
                }
            }
        }
        if(!site_config_js.devs.gestion_autoplay_2videos_155518075){
            if( config_params.right_video ){
               player_right = this;
           }
       }



        this.on("play", function(){

			if( setup_origin.hide_play_icon ){
				if( $('.special-block-video').length > 0 ){
					$("#"+id_block).parents().find('.special-block-video').addClass('video_started');
				}
			}

            /*if( is_mobile && site_config_js.devs.network_mobile_sticker_video_131559695){
                this.requestFullscreen();
            }*/
            /*tracking_Params.event = 'play';
            $(document).trigger('video_state_tracking', tracking_Params);*/
        });
		//'vast-ready', 'adscanceled', 'adclick' , 'vast-preroll-ready'        

		/*this.on("call_cdn_ads", function(){
			tracking_Params.event = 'Call Tag Ads';
			$(document).trigger('video_state_tracking', tracking_Params);
        });*/
        

     /*   this.on("adclick", function(){
            tracking_Params.event = 'adclick';
            $(document).trigger('video_state_tracking', tracking_Params);
        });*/

		this.on("error", function(){
            tracking_Params.event = 'error';
            $(document).trigger('video_state_tracking', tracking_Params);
        });

        this.on("pause", function(){
            if(config_params.scroll === true){
	            if(is_desktop || site_config_js.devs.autoplay_sites_mobiles_156367647){
                	$( window ).bind('scroll', play_video_on_scroll_js);
	        	}else{
					var $el = $(player.el()) ;
					mobile_first_play (player, $el, 'videojs');
	        	}

            }
        });

        this.on("error", function(){
            tracking_Params.event = 'error';
            $(document).trigger('video_state_tracking', tracking_Params);
        });

        this.on("timeupdate", function(){
            var myPlayer = this;
            currentTime = myPlayer.currentTime();
            if(prsentPoint[nextPoint]+"" != "undefined" && currentTime > prsentPoint[nextPoint][1] && !prsentPoint[nextPoint][2]){
                prsentPoint[nextPoint][2] = true;
                var currentPrsentPoint = prsentPoint[nextPoint][0];
                //Trigger params (video_state_tracking_event, currentPrsentPoint, currentVideoId, currentVideoName)
                tracking_Params.event = currentPrsentPoint;
            $(document).trigger('video_state_tracking', tracking_Params);
                nextPoint++;
            }
        });

        this.on("ended", function(){ 
        	//console.log('ended'  + this.duration() + ' ' +  this.currentTime() ) ;
            //var drt = this.duration();
            //var ct = this.currentTime();
            if( this.currentSrc() == setup.src && this.finished === undefined){
            	tracking_Params.event = 'end';
            	$(document).trigger('video_state_tracking', tracking_Params);
            	this.finished  = true ;
                playlist(id_block, setup_origin, config_params, type_provider, pub_ads);
                if( config_params.next === true ){
                    next_video_playlist( id_block );
                }
            }
        });

        //End code for tagage 1000mercis

        
        this.on('ready',function(){
            if(typeof setup.muted !=='undefined' && setup.muted){
                /*tracking_Params.event = 'Mute';
                $(document).trigger('video_state_tracking', tracking_Params);*/
            }
        });

		
        if (pub_ads && (pub_ads.type == 'vast' || pub_ads['prerollZoneId'])) {

            var player_width = config_params.width ? config_params.width : '730',
            player_height = config_params.height ? config_params.height : '360';
        	var urlVast = (pub_ads.type == 'vast') ? pub_ads.url.replace("SOURCE_PAGE_URL", encodeURIComponent(activUri)): site_config_js.SITE_SCHEME + '://ads.stickyadstv.com/www/delivery/swfIndex.php?reqType=AdsSetup&protocolVersion=2.0&zoneId='+pub_ads['prerollZoneId'];
            urlVast = urlVast.replace('PLAYER_WIDTH', player_width);
            urlVast = urlVast.replace('PLAYER_HEIGHT', player_height);
            pub_ads.url = urlVast ;
            pub_ads.type = 'vast' ;
        }


        if (!site_config_js.devs.pouvoir_diffuser_vast_vpaid_135592715 && pub_ads && (pub_ads.type == 'vast' || pub_ads['prerollZoneId'])) {

			var objVast = {
                url:pub_ads.url
            };

            if(pub_ads.skip){
                objVast.skip = pub_ads.skip ? pub_ads.skip : 5;
            }
            var op ={};
            if(site_config_js.prerollTimeout){
            	op.prerollTimeout = site_config_js.prerollTimeout ;
            }
            this.ads(op);
            this.vast(objVast);

	        this.on("vast-ready", function(){
				tracking_Params.event = 'call tag ads';
				$(document).trigger('video_state_tracking', tracking_Params);

				tracking_Params.event = 'vast-ready';
				$(document).trigger('video_state_tracking', tracking_Params);
	        });
	        this.on("adscanceled", function(){
				tracking_Params.event = 'call tag ads';
				$(document).trigger('video_state_tracking', tracking_Params);

				tracking_Params.event = 'adscanceled';
				$(document).trigger('video_state_tracking', tracking_Params);

	        	$(document).trigger('ads_player_ended', [id_block]);

	        });

	        this.on("vast-preroll-removed", function(){
        		$(document).trigger('ads_player_ended', [id_block]);
			});

	        this.on("vast-preroll-ready", function(){
	            tracking_Params.event = 'vast-preroll-ready';
	            $(document).trigger('video_state_tracking', tracking_Params);
	            
	            
	        });


        }else if(pub_ads && (pub_ads.type == 'vpaid' || (pub_ads.type == 'vast' && site_config_js.devs.pouvoir_diffuser_vast_vpaid_135592715 ))){

        	var vast_params = {
		        "adTagUrl": pub_ads.url,
		        "adCancelTimeout": 10000,
		        "responseTimeout" : 10000,
		        "timeout" : 10000,
		        "iosPrerollCancelTimeout" : 10000,
		        "adsEnabled": true,
		        'vpaidFlashLoaderPath':'/wp-content/themes/reworldmedia/assets/videojs-plugins/videojs-vast-vpaid/VPAIDFlash.swf',
		        'verbosity':4
			};
        	
        	if(pub_ads.preroll_and_postroll){
        		vast_params.postroll = true ;
        	}

	        var vast_ad = this.vastClient(vast_params);


	        this.on("vast.call_tag_ads", function(){
				tracking_Params.event = 'call tag ads';
				$(document).trigger('video_state_tracking', tracking_Params);

	        });


	        this.on("vast.vast-ready", function(){
				tracking_Params.event = 'vast-ready';
				$(document).trigger('video_state_tracking', tracking_Params);

	        });
	 
	        this.on("vast.adsCancel", function(){

				tracking_Params.event = 'adscanceled';
				$(document).trigger('video_state_tracking', tracking_Params);

	        	$(document).trigger('ads_player_ended', [id_block]);

	        });


	        this.on('vast.adStart', function(){
				var video = this.el().querySelector('video.vjs-tech.yt');
				if(video){
					video.style.display = 'block';
				}

				tracking_Params.event = 'vast-preroll-ready';
	            $(document).trigger('video_state_tracking', tracking_Params);


			});

			this.on('vast.adEnd', function(){
				var video = this.el().querySelector('video.vjs-tech.yt');
				if(video){
					video.style.display = 'none';
				}
	        	$(document).trigger('ads_player_ended', [id_block]);

			});



        }

        if(!pub_ads){
        	$(document).trigger('ads_player_ended', [id_block]);
        }

        if(type_provider=='youtube' && typeof config_params.youtube_id !=='undefined'){
            this.src(
              {
                type: "video/youtube"   
              }
            );
        }
	   
	    if( site_config_js.devs.wideonet_double_appel_146686499 && setup_origin.autoplay && is_desktop){ 
	        this.play() ;
	    }
	});


	if(is_mobile && site_config_js.devs.autoplay_sites_mobiles_156367647){
	    player.on('play', function(){
	    	player.muted(true) ;
	    });

		player.on("vast.adStart", function(){
			setTimeout(function(){
				if(player.paused()){
					player.muted(true);
					player.play();
				}
			}, 500);

		});
	}



    if(setup_origin.autoplay){ 
		var $el = $(player.el()) ;
		mobile_first_play (player, $el, 'videojs');
		
		player.on("timeupdate", function(){
            currentTime = player.currentTime();
            if(currentTime >1 ){
				window.first_autoplay_mobile =  true ;
            } 
        });

    }
    return player ;
}

function mobile_first_play ($player, $el, type){ 
		try{
		    if( !isMobile.apple.device  &&  (is_mobile || is_tablet) && window.first_autoplay_mobile === undefined){

				$("body").on({touchmove:function(a){
					if( window.first_autoplay_mobile === undefined && $(window).scrollTop() + $(window).height()  >= $el.offset().top){
		    			$player.play(); 
		    			window.first_autoplay_mobile =  true ;
		    				
		    				//lancer le player Mute par défaut
		    				if(site_config_js.mobile_autoplay_mute){
		    					if(type == 'videojs'){
		    						$player.muted(true);
		    					}else if(type == 'dailymotion'){
		    						$player.setVolume(0) ;	
		    					}

							}
		    		}

				}});

	        }
		}catch(e){

		}

}

function play_dailymotion(id_block, player_params) {
    var rand = Math.floor((Math.random() * 100) + 1);
    var params = { allowScriptAccess: "always", allowFullScreen:"true", wmode: "transparent" };
    var atts = { id: "mydmplayer"/*, allowfullscreen:"true"*/ };
    video_id= player_params.clip.video_id;
    var width = $("#"+id_block).width();
    if($("#"+id_block).parent(".block_video_gallery").length > 0){
        width = $("#"+id_block).parent(".block_video_gallery").width();
    }
    
    var height = $("#"+id_block).height();
    if($("#"+id_block).parent(".block_video_gallery").length > 0){
        height = $("#"+id_block).parent(".block_video_gallery").height();
    }

    $("#"+id_block).html('<div id="dmapiplayer'+rand+'"></div>');

    $("#"+id_block).attr({"href":"#","onclick":"return false;"});

    swfUrlStr = site_config_js.SITE_SCHEME + "://www.dailymotion.com/swf/"+video_id+"&enableApi=1&playerapiid=mydmplayer&autoplay=1&wmode=transparent";
    swfobject.embedSWF(swfUrlStr, "dmapiplayer"+rand, width, height, "9", null, null, params, atts);
}

function play_dailymotion_homepage(selector, video_id) {
    var params = { allowScriptAccess: "always", allowFullScreen:"true" };
    var atts = { id: "mydmplayer", allowfullscreen:"true" };
    // video_id= player_params.video_id;
    var width = $(selector).width();
    if($(selector).parent(".item").length > 0){
        width = $(selector).parent(".item").width();
    }
    
    var height = $(selector).height();
    if($(selector).parent(".item").length > 0){
        height = $(selector).parent(".item").height();
    }

    $(selector).html('<div id="dmapiplayer"></div>');

    swfUrlStr = site_config_js.SITE_SCHEME + "://www.dailymotion.com/swf/"+video_id+"&enableApi=1&playerapiid=dmplayer&autoplay=1";
    swfobject.embedSWF(swfUrlStr, "dmapiplayer", width, height, "9", null, null, params, atts);
}

function sendEvent(e, event_name) {
    if ( e == 'adwizz' || e.provider == "youtube" || e.provider == "dailymotion" ) {
        //$f().getPlugin("gatracker").trackEvent(event_name);
		var videoPlayer  = 'Video player';
		var tracking_Params = {
			'event' : event_name,
	        'videoPlayer'  : videoPlayer
		};

        $(document).trigger('video_state_tracking', tracking_Params);
    }
}

function seo() {

    if ( 'seo_bubble_links' in self.site_config_js ) {
    	// seo bubble code
        var $wrapper_cls = 'body';
        if( typeof site_config_js.devs != 'undefined' && site_config_js.devs.liens_non_cliquables_161442981 ){
            $wrapper_cls = '#page';
        }
        $($wrapper_cls).on('click' , 'a,img' , function(){
    		var $target = $(this) ; 
    		var $origin = $(this) ; 
    		// a
    		var href = '' ;

    		// img
    		if ( href = $target.data('ihref')){
    			window.location =href;
    			return;
    		}
    		if ( $target.is('img') || $target.is('span'))   {
    			$target = $target.parents('a');
    		}
    		if ( href = $target.data('href')){
    			$target.attr('href' , href );
    		}
    			
    			
    		$(document).trigger('bubble_click', [$origin] ) ;

    		
    	});

    }  


    $("[data-link-class]").each(function(i, index) {
        var class_name = $(index).attr("data-link-class");
        var parent = $(index).parents("." + class_name);
        var link = parent.find("a").attr("href");
        var a = "<a href='" + link + "' >" + $(index).html() + "</a>";
        $(index).html(a);
    });

    $("span[data-href]").each(function(i, index) {
        var target;
        var $href_html;
        $(index).attr("href", $(index).attr("data-href"));
        $(index).attr("data-href", null);
        target = $(index).data('target');
        $href_html = $(index)[0].outerHTML;
        if( target && typeof target !== 'undefined'){
            $href_html = $href_html.replace("data-target", "target");
        }
        $(index)[0].outerHTML = $href_html.replace("<span", "<a").replace("</span", "</a");
    });

    $("img[data-ihref]").each(function(i, index) {
        $(this).click(function(){
	        link = $(this).attr("data-ihref");
	        window.location =link;
        });
    });

}

function setDefaultImage(source) {
    if (jQuery(source).attr("data-default-src")) {
        var cpyImg = new Image();
        cpyImg.src = source.src;
        if (cpyImg.width <= 120 && source.src != jQuery(source).attr("data-default-src")) {
            source.src = jQuery(source).attr("data-default-src");
        }
    }
}

function seo_menu() {
    if (self.menu_items && (site_config_js.version == 1 || !site_config_js.version )) {
        for (i in menu_items) {
            var menu_item = menu_items[i];
            if (menu_item.menu_item_parent !== 0) {
                var item_parent = jQuery("footer .menu-item-" + menu_item.menu_item_parent);
                if (item_parent.length) {
                    if (item_parent.find(".sub-menu").length === 0) {
                        item_parent.append("<ul class='sub-menu' />");
                    }
                    html = '<li class="' + ((menu_item.classes) ? menu_item.classes.join(' ') : '') + ' menu-item menu-item-type-taxonomy menu-item-object-category menu-item-' + menu_item.ID + '">\
					<a href="' + menu_item.url + '">' + menu_item.title + '</a></li>';
                    item_parent.find(".sub-menu").append(html);
                }
            }
        }

        for (i in menu_items) {
            var menu_item = menu_items[i];
            if (menu_item.menu_item_parent !== 0) {
                var item_parent = jQuery("nav .menu-item-" + menu_item.menu_item_parent);
                if (item_parent.length) {
                    if (item_parent.find(".sub-menu").length === 0) {
                        item_parent.append("<ul class='sub-menu' />");
                    }
                    if (jQuery("nav .menu-item-" + menu_item.ID).length === 0) {
                        html = '<li class="' + ((menu_item.classes) ? menu_item.classes.join(' ') : '') + ' menu-item menu-item-type-taxonomy menu-item-object-category menu-item-' + menu_item.ID + '">\
						<a href="' + menu_item.url + '">' + menu_item.title + '</a></li>' ;
                        item_parent.find(".sub-menu").append(html);
                    }

                }
            }
        }
    }


}

function playHomeVideo(e, id, type, video_id) {
    param = {};
    if (type == 'youtube') {
        param = {
            youtube_id: video_id,
            mediaid : 'https://www.youtube.com/watch?v='+video_id

        };
    } else if (type == 'dailymotion') {
        param = {
            dailymotion_id: video_id,
            mediaid : site_config_js.SITE_SCHEME + '://dai.ly/'+video_id,
            params : {
				autoplay : true
			}
        };
    }

    var url_template = site_config_js.url_template;
    var id_pub = site_config_js.id_pub;
    e.preventDefault();

    var height = jQuery("#post-" + id + " .thumbnail .thumbnail-visu").height();
    jQuery("#post-" + id + " .thumbnail .thumbnail-visu").hide();
    jQuery("#post-" + id + " #video_" + id).css({
        "display": "block",
        'height': height
    });

	if(site_config_js.devs.passage_du_player_sur_jw6_111776366){
		var setup = {
			autoplay:true
		};
    	if(height){ 
    		setup.height = height ;
    	}
    	show_jw_player("video_" + id, setup, url_template, id_pub, type, param);	
    }else if(site_config_js.devs.Passage_du_player_sur_videojs){

    	var $elem =  jQuery("#video_" + id).parent().find('.thumbnail-visu') ;

		var width = $elem.outerWidth();
		var height = $elem.outerHeight();

        var setup = {
            autoplay: true,
			width: '100%',
   			height: height + 'px',
            src: param.mediaid,
            techOrder: ["youtube","html5","flash"],
            ytcontrols: 1,
        };
	    jQuery("#video_" + id).addClass('video-js').addClass('vjs-default-skin').addClass('vjs-big-play-centered');
    	show_videojs("video_" + id, setup, url_template, id_pub, type, param);	
    }else {
    	show_video("video_" + id, url_template, id_pub, type, "", true, param);	
    }


    return false;
}


function top_intro_gallery() {
    if (jQuery('#top_intro_article').html() !== '' && jQuery('#top_intro_gallery').length > 0) {
        html = jQuery('#top_intro_article').html();
        jQuery('#top_intro_gallery').html(html);
        jQuery('#top_intro_article').html('');
    }
}
if(!site_config_js.devs.duplication_formats_pubs_mobile_143585947){
	top_intro_gallery();
}


function effet_bloc_sommaire(){
	$("#content #results .thecontent .bloc_sommaire ul li.title").unbind().click(function(){
		var ul_child = $(this).parent();
		var post_child = $(ul_child).children('.post-child,.folder-link');
		
        var delayTime = 50;
		if(!$(ul_child).hasClass('in')) {
			for (var i = 0; i <= post_child.length - 1; i++) {
				$(post_child[i]).delay(i * delayTime).fadeIn('fast','swing');
			}
			$(ul_child).addClass('in');
		} else {
			for (var j = post_child.length - 1; j >= 0; j--) {
				$(post_child[j]).delay((post_child.length-j) * delayTime).fadeOut('fast','swing');
			}
			$(ul_child).removeClass('in');
		}
	});	
}


/* ======================  google analytics player ========================== */
function show_youtube(player, videoId, width, height){
	self.param_youtube = { 'player':player, 'videoId':videoId, 'width':width, 'height':height};
	var tag = document.createElement('script');
  	tag.src = "https://www.youtube.com/iframe_api";
  	var firstScriptTag = document.getElementsByTagName('script')[0];
  	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

}

// to stock all player
var playerInfoList =[] ;

var playersList = {};

var script_is_loaded = false;
// to init script  
function show_all_youtube(youtube_players){
  if(!script_is_loaded) {
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    playerInfoList = youtube_players;

  } else {
    var length = youtube_players.length;
    createPlayer(youtube_players[length-1]);
  }
  
}

// to create iframe for each player
function onYouTubeIframeAPIReady() {
    if(typeof playerInfoList === 'undefined')
        return; 

    for(var i = 0; i < playerInfoList.length;i++) {
        var curplayer = createPlayer(playerInfoList[i]);
    }  

    script_is_loaded = true; 
}

function createPlayer(playerInfo) {

    var curplayer =  new YT.Player(playerInfo.id_block, {
    height: playerInfo.height,
    width: playerInfo.width,
    videoId: playerInfo.yt_id,
    events: {
      'onReady': onPlayerReady,
      'onStateChange': onPlayerStateChange, 
      'onVolumeChange': onPlayerSolumeChange, 
    }
    });
    playersList[playerInfo.id_block] = curplayer;
    return curplayer;
}


// 4. The API will call this function when the video player is ready.
function onPlayerReady(event) {
	send_stats_youtube('Start') ;
}

// 5. The API calls this function when the player's state changes.
//    The function indicates that when playing a video (state=1),
//    the player should play for six seconds and then stop.
var youtube_ga_done = false;
function onPlayerStateChange(event) {
	console.log(event );
	if (event.data == YT.PlayerState.PAUSED){
		send_stats_youtube('Pause') ;
	}else if(event.data == YT.PlayerState.ENDED){
		send_stats_youtube('Finish') ;
	}

    if (event.data == YT.PlayerState.PLAYING && !youtube_ga_done) {
    	if(event.target.A.currentTime > 0){
    		send_stats_youtube('Resume') ;      			
    	}

    	
    // setTimeout(stopVideo, 6000);
    //done = true;
    }
}

function stopVideo() {
    youtube_ga_player.stopVideo();
}

function onPlayerSolumeChange(event) {
  	console.log(event );
  	if(event.data.volume === undefined && event.data.muted === false){
  		//send_stats_youtube('Mute') ;
  	}

}

function send_stats_youtube(label){
	console.log(label);
    setTimeout(function(){ 
        send_GA( 'Video player YT', label, self.location.href);
    }, 3000);
}

function start_current_video_diapo(obj) {
	var player_id = $(obj).parent().find('.video_post .fwplayer_block ').attr("id");
	
	if(playersList!== null && playersList[player_id] !== null) {
		playersList[player_id].playVideo(); 
	}
    return player_id;
}

/* ====================== END : google analytics player ====================== */

function ninja_form_add_img(){
	var element = jQuery(".ninja-forms-form-wrap .ajouter-une-image").last();
	if(element.length){
		element.show();
		element.click(function(){
			jQuery(".ninja-forms-form-wrap .field-wrap.hidden_field:hidden").first().show(300) ;
			if(jQuery(".ninja-forms-form-wrap .field-wrap.hidden_field:hidden").length === 0){
				jQuery(this).hide("slow");
			}
		});
	}
}


function isScriptAlreadyIncluded(src){
    var scripts = document.getElementsByTagName("script");
    for(var i = 0; i < scripts.length; i++) 
       if(scripts[i].getAttribute('src') == src) return true;
    return false;
}

function initPlayerMostPopularVideo() {
    $(".thumb-video .second-article").on("click", function(){
        $(".thumb-video .second-article.first").parents(".thumb-video").removeClass("hide");
        $(".thumb-video .second-article.first").removeClass("first");
        $(this).parents(".thumb-video").addClass('hide');
        $(this).addClass('first');

        // change href and title of the current video.
        $(".main-article-title").text($(this).attr("title"));
        $(".main-article-title").parents("a:first").attr("href", $(this).data("link"));
        
        // get type and id of the current video
        var type = $(this).data("type");
        var video_id = $(this).data("video");

        // reload video on player
        playVideoOnPopularArticle("player_most_popular", type, video_id);
        return false;
    });

    $(".thumb-video .second-article.first").click();
}
/**
 * gather video details and play video on player
 */
function playVideoOnPopularArticle( block_id, type, video_id) {
    param = {};
    if (type == 'youtube') {
        param = {
            youtube_id: video_id
        };
        $("#"+block_id).attr("href", "api:"+video_id);
    } else if (type == 'dailymotion') {
        param = {
            dailymotion_id: video_id,
            url : site_config_js.SITE_SCHEME + '://dai.ly/'+video_id
        };
    }
    
    var url_template = site_config_js.url_template; 
    var id_pub = site_config_js.id_pub;
    
    $("#"+block_id).html("");
    show_video(block_id, url_template, id_pub, type, "", true, param);
    
    return false;
}


function load_video() {

	$('.widget_most_popular_videos .row .col-xs-6 .img-title').unbind();
	$('.widget_most_popular_videos .row .col-xs-6 .img-title').bind("click", function() {
		var video_html = $(this).parents('.col-xs-6').find('.items-posts').html();
		$('.widget_most_popular_videos .large-player').html('<div class="items-posts">'+video_html+'</div>');
		$('.widget_most_popular_videos .row .col-xs-6').removeClass('hidden');
		$(this).parents('.col-xs-6').addClass('hidden');
	});

	$("#blockRight .list-ms-item>aside").each(function(i,obj){
		if($(obj).find('.textwidget>div').hasClass('widget_most_popular_videos')) {
			var list_videos = $('.widget_most_popular_videos .row').children('.col-xs-6');
			$(list_videos[0]).find('.img-title').click();
			return false;
		} 
	});
}


function send_GA(category, action, lebel, value){
    if (typeof site_config_js["partners"] !== 'undefined' && site_config_js["partners"].analytics == true) {
        if(window._gaq){
        	if(value){
            	_gaq.push(['_trackEvent', category, action, lebel, value]);

        	}else{
            	_gaq.push(['_trackEvent', category, action, lebel]);
        	}
            
        }else if(window.gtag){
            if(value){
                gtag('event', action, { 'event_category': category, 'event_label': lebel, 'value': value });
                if(site_config_js.other_google_analytics_ids){
                    jQuery.each(site_config_js.other_google_analytics_ids, function(index, value1) {
                        gtag('event', action, {'send_to': value1, 'event_category': category, 'event_label': lebel, 'value': value });
                    });
                }
            }else{
                gtag('event', action, { 'event_category': category, 'event_label': lebel });
                if(site_config_js.other_google_analytics_ids){
                    jQuery.each(site_config_js.other_google_analytics_ids, function(index, value1) {
                        gtag('event', action, {'send_to': value1, 'event_category': category, 'event_label': lebel });
                    });
                }
            }
        }else{
        	if(value){
            	ga('send', 'event', category, action, lebel, value);
            	if(site_config_js.other_google_analytics_ids){
            		for (var ga_name in site_config_js.other_google_analytics_ids) {
            			ga(ga_name + '.send', 'event', category, action, lebel, value);
            		}
            	}
        	}else{
            	ga('send', 'event', category, action, lebel);
				if(site_config_js.other_google_analytics_ids){
            		for (var ga_name in site_config_js.other_google_analytics_ids) {
            			ga(ga_name +'.send', 'event', category, action, lebel);

            		}
            	}
        	}
        }
    }

}
function pageview_GA(only_path_and_hash){

	if(window._gaq){
    	window._gaq.push( ['_trackPageview', only_path_and_hash]);
    }else if(window.gtag){

        gtag('event', 'page_view', { 'send_to': site_config_js.google_analytics_id });
        if(site_config_js.other_google_analytics_ids){
            jQuery.each(site_config_js.other_google_analytics_ids, function(index, value1) {
        		gtag('event', 'page_view', { 'send_to': value1 });
            });
        }

    }else{
    	if (typeof site_config_js["partners"] !== 'undefined' && site_config_js["partners"].analytics == true) {
        	ga('send', 'pageview', only_path_and_hash);

			if(site_config_js.other_google_analytics_ids){
        		for (var ga_name in site_config_js.other_google_analytics_ids) {
        			ga( ga_name + '.send', 'pageview', only_path_and_hash);
        		}
        	}
        }
	}
}


function calc_new_sharer_size() {
	$block_share = $('.sharer_after_comment .share-buttons');
	var width_em = $block_share.find('.total-shares em').width();
	var width_1 = $block_share.find('.total-shares').width();
	var width_2 = $block_share.find('.blockShare_horizontal').width();
	$block_share.width(width_1+width_2+15);
}

function show_jw_player(id_block, setup, url_template, pub_ads, type_provider, config_params ) {

	if(type_provider == 'dailymotion'){
		var params = {
			"video" : config_params.dailymotion_id ,
			"width" : "100%"
		} ;
		if(setup.autoplay) {
			params['params'] = {autoplay:true};
		}

		if(setup.width){
    		setup.width = setup.width +"" ;
    		params.width = setup.width.replace('px','');  		
    	}
    	if(setup.height){
    		setup.height = setup.height +"" ;
    		params.height =  setup.height.replace('px','');	   		
    	}

        if( config_params.next === true ){
            params.next = true;
        }

		show_dai_video(id_block, params, pub_ads) ;
		return ;
	}else if(config_params.mediaid.indexOf('adways.com') > -1){

		var params = {
			"width" : "100%",
			"src" : config_params.mediaid
		} ;

		if(setup.width){
			params.width = setup.width ;
		}else{
			params.width = jQuery("#" + id_block).parent().width();  		
		}
		if(setup.width){
			params.width = setup.width ;
		}else{
			params.width = jQuery("#" + id_block).parent().width();  		
		}

		if(setup.height){
			params.height = setup.height ;
		}else{
			params.height = params.width * 390/460 ;	
		}		

		show_adways_player(id_block, params) ;
		return ;
	}

	jwplayer.key= site_config_js.jwplayer_key;

    var videoPlayer = 'Jw video player';
    var videoId = '';
    if(type_provider == "youtube"){
        videoId = config_params.youtube_id;
    }
    var tracking_Params = {
        'videoId' : videoId,
        'videoName' : '',
        'videoPlayer' : videoPlayer,
        'type_provider' : type_provider
    };

    playerInstance = jwplayer(id_block);
	var plugin_liverail = url_template + '/assets/jwplayer/LiveRail.AdManager.JWPlayer-6.8.1.plugin.js' ;
	var plugin_sticky = site_config_js.SITE_SCHEME + "://cdn.stickyadstv.com/plugins/jw/StickyJW6Plugin.js" ;
	plugins = {} ;

	if (pub_ads && !pub_ads.type) {
        
       plugins[plugin_sticky] = {
       		remainingAdTime:"This advertisement runs for [sec] seconds",
   			ads:{
   				zones:{
   					preroll: pub_ads.prerollZoneId || -1 ,
   					midroll:  pub_ads.midrollZoneId || -1 ,
   					postroll:  pub_ads.postrollZoneId || -1 ,
   				},
   			}
   		} ;
        
    }else if(pub_ads && pub_ads.liverail){
       	plugins[plugin_liverail] = pub_ads.liverail;
	}

	var setup_player = {
		 plugins: plugins ,
		 flashplayer: url_template +'/assets/jwplayer/jwplayer.flash.swf',
	     file:  config_params.mediaid,
	     width: '100%'
	};
	if(!setup.noflash){
	     setup_player.primary =  'flash';
	}

	if(setup.autoplay){
		setup_player.autostart = true ;
	}
	if(setup.repeat){
		setup_player.repeat = true;
	}

	if(setup.width){
		setup.width = setup.width +"" ;
		setup_player.width = setup.width.replace('px','');  		
	}
	
	if(setup.aspectratio){
		setup_player.aspectratio = setup.aspectratio ;
	}else if(setup.height){
		setup.height = setup.height +"" ;
		setup_player.height =  setup.height.replace('px','');	   		
	}
	if(setup.src_img){
		setup_player.image =  setup.src_img;	   		
	}
	if(setup.muted){
		setup_player.mute =  true;	   		
	}

	playerInstance.setup(setup_player);

    //Start code added for tracking video
    jwplayer(id_block).onReady( function(){
        var prsentPoint = [];
        nextPoint = 0;
        jwplayer(id_block).onPlay( function(event){
            if(!prsentPoint.length){
                videoDuration = this.getDuration();
                prsentPoint.push({'prsent':0, 'time':0, 'state':false});
                prsentPoint.push({'prsent':25, 'time':videoDuration*(0.25), 'state':false});
                prsentPoint.push({'prsent':50, 'time':videoDuration*(0.50), 'state':false});
                prsentPoint.push({'prsent':75, 'time':videoDuration*(0.75), 'state':false});
            }else{
                tracking_Params.event = 'Play';
                $(document).trigger('video_state_tracking', tracking_Params);
            }
        });

        jwplayer(id_block).onSeek( function(event){
            currentTime = event.offset;
            for(j=0; j<prsentPoint.length; j++){
                if( currentTime > prsentPoint[j].time){
                    prsentPoint[j].state = true;
                    nextPoint = j+1;
                }else{
                    prsentPoint[j].state = false;
                }
            }
            tracking_Params.event = 'Seek';
            $(document).trigger('video_state_tracking', tracking_Params);
        });

        jwplayer(id_block).onTime( function(event){
            var currentTime = event.position;
            if(prsentPoint[nextPoint]+"" != "undefined" && currentTime > prsentPoint[nextPoint].time && !prsentPoint[nextPoint].state){
                prsentPoint[nextPoint].state = true;
                var currentPrsentPoint = prsentPoint[nextPoint].prsent;
                tracking_Params.event = currentPrsentPoint;
                $(document).trigger('video_state_tracking', tracking_Params);
                nextPoint++;
            }
        });

        jwplayer(id_block).onPause( function(){
            tracking_Params.event = 'Pause';
            $(document).trigger('video_state_tracking', tracking_Params);
        });

        jwplayer(id_block).onComplete( function(){
            tracking_Params.event = 'End';
            $(document).trigger('video_state_tracking', tracking_Params);
			if(setup.autoloop){
				show_jw_player(id_block, setup, url_template, pub_ads, type_provider, config_params);
			}
            if( config_params.next === true ){
                next_video_playlist(id_block);
            }
        });

        /*jwplayer(id_block).onMute( function(event){
            if(event.mute){
                tracking_Params.event = 'Mute';
                $(document).trigger('video_state_tracking', tracking_Params);
            }
        });*/

        jwplayer(id_block).onError( function(){
            tracking_Params.event = 'Error';
            $(document).trigger('video_state_tracking', tracking_Params);
        });
    });
            //End code added for tracking video
}

jQuery(document).on( "video_state_tracking", function(event, tracking_Params) {
	var StateMessage = tracking_Params.event;
    var videoPlayer = tracking_Params.videoPlayer;
    var prsentPoints = [25, 50, 75];
    if(StateMessage === 0){
        StateMessage = 'Start';
    }else if( prsentPoints.indexOf(StateMessage) != -1 ){
        StateMessage = 'lecture à '+StateMessage+'% ';
    }
    if(site_config_js.is_preprod){
    	console.log(StateMessage) ;
    }
    setTimeout(function(){ 
        send_GA( videoPlayer, StateMessage, self.location.href);
    }, 3000);
});

function show_adways_player(id_block, params){

	var width = params.width || '1OO%' ;
	var height = params.height || '100%' ;	
	var autoplay = params.autoplay || false;	
	var src = params.src  ;	
	if(autoplay){
		src += '?autoplay=true' 
	}else{
		src += '?autoplay=false' 
	}
	var $ifram = jQuery('<iframe>');
	$ifram.attr({
		'width' :width,
		'height' :height,
		'src' :src
	}) ;
	jQuery('#' + id_block).html($ifram) ;

}


function menu_link_mouseover(this_){
	$this_ = $(this_) ;
	var color = $this_.data('color-typo-hover') || '' ;
	$this_.css('color',color , 'important' );
	var bg_color = $this_.data('color-fond-hover') || '';

	$this_.each(function () {
	    this.style.setProperty( 'color',color , 'important' );
		this.style.setProperty('background-color',bg_color,'important' );
	});
}

function menu_link_onmouseout(this_){
	$this_ = $(this_) ;
	var color = $this_.data('color-typo')  || '';
	var bg_color = $this_.data('color-fond') || '' ;
	$this_.each(function () {
	    this.style.setProperty( 'color',color , 'important' );
		this.style.setProperty('background-color',bg_color,'important' );
	});
}

function pause_carousel_in_playin_video(){
    $("#carousel-gallery-generic").carousel('pause');
    return false;
}

function play_carousel_video(e, id, type, video_id, video_url) {

    var carousel_gallery = $("#carousel-gallery-generic");
    carousel_gallery.on('slide.bs.carousel', pause_carousel_in_playin_video);
    window.current_carousel_video_playing_id = id;
    $('#carousel-gallery-generic .carousel-content ol li').click(function () {
        carousel_gallery.unbind('slide.bs.carousel', pause_carousel_in_playin_video);
        carousel_gallery.carousel('cycle');
        var current_id = window.current_carousel_video_playing_id;
        if(Object.keys(DM.Player._INSTANCES).length && DM.Player._INSTANCES[current_id]){
            var player = DM.Player._INSTANCES[current_id];
            player.addEventListener("play", function(){
                window.current_carousel_video_playing_id = current_id;
                carousel_gallery.on('slide.bs.carousel', pause_carousel_in_playin_video);
            });
            player.pause();
        }else if(typeof(videojs) != 'undefined' && videojs.players[current_id]){
            var player = videojs.players[current_id];
            player.on("play", function(){
                window.current_carousel_video_playing_id = current_id;
                carousel_gallery.on('slide.bs.carousel', pause_carousel_in_playin_video);
            });
            player.pause();
        }

    });

    $(e.target).next('div.carousel-caption').hide();
    $(e.target).hide();

    param = {
                mediaid : video_url
            };
    var height = $(e.target).height();
    var width = $(e.target).width();

    if (type == 'youtube') {
        param = {
            youtube_id: video_id,
            mediaid : 'https://www.youtube.com/watch?v='+video_id 
        }
        var setup = {
            autoplay: true,
            height: height,
            width: width,
            src : video_url,
            techOrder: ["youtube","flash"],
            playsinline:true,
            ytcontrols:1,
        };
    } else if (type == 'dailymotion') {
        param = {
            dailymotion_id: video_id,
            mediaid : site_config_js.SITE_SCHEME + '://dai.ly/'+video_id,
            height: height,
            width: width, 
            params : {
                autoplay : true
            }
        }
    }

    var url_template = site_config_js.url_template;
    var id_pub = site_config_js.id_pub;
    e.preventDefault();
    e.stopImmediatePropagation();

    if(site_config_js.devs.passage_du_player_sur_jw6_111776366){
        var setup = {
            autoplay:true
        };
        if(height){ 
            setup.height = height ;
        }
        show_jw_player(id, setup, url_template, id_pub, type, param);    
    }else if (type == 'youtube' && site_config_js.devs.Passage_du_player_sur_videojs){
        $("#"+id).addClass('video-js vjs-default-skin vjs-big-play-centered');
        show_videojs(id, setup, url_template, id_pub, type, param); 
    }else{
        show_video(id, url_template, id_pub, type, "", true, param);
    }
    
    return false;
}


function show_default_sharer( id ,  url , title , description , image ){
    if( typeof Share == 'function' ){
    	window.share_object = new Share( id, {
    		url: url,
    		title: title ,
    		description: description,
    		image : image,
    		ontoggle:false , 
    		inject_css:false, 
    		inject_html:false,
    		ui : { button_font: false,icon_font: false },
    		networks :{
    			twitter :{
    				before : function(elem){
    					var twObj = $(elem).find('.social-network[data-network=twitter]');
    					this.url = $(twObj).data('url');
    					this.description = this.text = $(twObj).data('text');
    					var hashtags = $(twObj).data('hashtags');
    					var via = $(twObj).data('via');
    					if(hashtags && hashtags != ''){
    						this.url +='&hashtags='+hashtags;
    					}
    					if(via && via != ''){
    						this.url +='&via='+via;
    					}
    				}
    			}
    		}
    		
    	});	
    }
}
/* Sticky menu with social button */
function fix_nav_scroll(){
	var $share_to_fix = $('.sharer_below_title .share-buttons'),
	$share_sticky = $('.blockShare_fixed'),
	$wind = $(window);
	if($share_to_fix.length>0){
		$(document).scroll(function(){
			if(!$share_sticky.hasClass('navbar-fixed-top'))
				offset_navbar = $share_to_fix.offset();
			if(offset_navbar.top<=$(this).scrollTop())
				$share_sticky.addClass('navbar-fixed-top');
			else
				$share_sticky.removeClass('navbar-fixed-top');
		});
	}
}


(function( $ ) {
    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null){
            return '';
        }
        else{
            return results[1] || 0;
        }
    }
}( jQuery ));
 
function playlist(id, s, p, prov, pub_ads) {
    if( (s != '' && s.playlist == true) || (p.params != undefined && p.params.playlist == "true")){
    index_pl = window.index_pl || 0;
        index_pl ++;
        var params = p;
        var force_play_type = p.force_play_type;
        if (force_play_type === undefined ) {
            force_play_type = false;
        }
        if(p.other_videos != 'false' ){   
            var mediaid_ = p.other_videos[0]['url'];
            var video_id = p.other_videos[0]['video_id']
            var type  = p.other_videos[0]['type']; 
            var response ={post_video:'',mediaid: mediaid_,video_id: video_id, provider: type };
            p.other_videos.splice(0,1); 
            if(p.other_videos.length==0){  
                p.other_videos='false';
            } 
            play_selected_video(response,prov,p,s,id, pub_ads);            
        }else{   
            $.ajax({
            url : '/',
            type : 'GET',
            dataType: 'json',
            data : {
                action : 'ajax_random_post_video',
                force_play_type : force_play_type,
                call: index_pl,
            },
            success : function( response ) {
                if (response) {   
                    play_selected_video(response,prov,p,s,id, pub_ads);
                } 
                return false;
            },
            });
        }
    }
    return false;
}

if( site_config_js.new_share_bottons_mobile ){
    $( "#social_share_mobile #illustrate" ).on( "click", function() {
      $( "#social_share_mobile .blockShare_horizontal ul" ).toggleClass( "show_sharer" );
      $( this ).toggleClass( "close_share" );
  });
}

function play_selected_video(response,prov,p,s,id, pub_ads){ 
    var mediaid_ = response.mediaid;
    var url_template = site_config_js.url_template;
    var id_pub = pub_ads;
    var video_id = response.video_id;
    var type = response.provider;
    var auto_play = true;
    var firtVideoPl = true;
    if( play_on_nav_diapo ){
        auto_play = false;
        firtVideoPl = false;
    }
    var params_ = [];
    index_pl = window.index_pl || 0;
    index_pl ++;
    var params = p;
    var force_play_type = p.force_play_type;
    if (force_play_type === undefined ) {
        force_play_type = false;
    }
    if(prov == 'youtube'){
        if(type == 'youtube'){   
            setup = s;
            p.mediaid = mediaid_;
            p.youtube_id = video_id;
            params_ = p;
            params_.firtVideoPl = firtVideoPl;
            setup.src = mediaid_;
        }else{  
            params_ = { 
                video: video_id,
                height: s.height,
                width: s.width,
                force_play_type : force_play_type, 
                other_videos : p.other_videos,
                firtVideoPl:firtVideoPl,
                right_video: true,
                params : {
                    autoplay : auto_play,
                    mute : s.muted,
                    other_videos : p.other_videos,
                    playlist:"true",
                }
            };
        }
    }else{
        if(type == 'dailymotion'){ 
            p.video = response.video_id;
            params_ = p;
            params_.firtVideoPl = firtVideoPl;
        }else{ 
            var setup = {
                autoplay: auto_play,
                height: "169px",
                width: "300",
                src : mediaid_,
                techOrder: ["youtube","flash"],
                playsinline:"true",
                ytcontrols:1,
                playlist:true,
            };
            params_ = {
                autoplay: "yes",
                height: "169px",
                mediaid: mediaid_,
                mutevideo: params.params.mute,
                width: "300",
                youtube_id: video_id,
                other_videos : p.other_videos,
                force_play_type : force_play_type,
                playlist : true,
                right_video: true,
                firtVideoPl : firtVideoPl
            };
        }
    }
    if(type == 'youtube'){
        if(site_config_js.devs.mise_en_place_jw7_151445890){
            show_jw7_player(id, setup, url_template, id_pub, type, params_);
        }else if(site_config_js.devs.Passage_du_player_sur_videojs){
            var video_url = mediaid_;
            var $div_parent = $('#'+id).parent();
            $('#'+id).remove();

            id = id+video_id;
            $div_parent.html('<div id="'+id+'"></div>');
            $("#"+id).addClass('video-js vjs-default-skin vjs-big-play-centered'); 
            show_videojs(id, setup, url_template, id_pub, type, params_); 
        }else{
            //Traitement spécifique a FLOWPLAYER, si besoin, il faut ajouter le traitement pour d'autre player
            var $balise_video = $('#'+id);
            var href = 'api:'+response.video_id;
            if( $balise_video.is('a') ){
                $balise_video.attr('href', href);
            }else{
                id = id+video_id;
                var new_balise_video = '<a class="fwplayer_block" href="'+ href + '" style="display:block;width:100%; text-align: center;margin-bottom: 4px;height:169px;" id="' + id + '" >';
                $balise_video.before(new_balise_video);
                $balise_video.remove();
            } 
            show_video(id, url_template, id_pub, type, "", true, params_);
        }
    }else{
        show_dai_video(id, params_, pub_ads);
    }
}
 
/**
 * Bring posts from feed and reload posts div content
 * @param int feed's paged
 * @return false
 */
 
function get_more_posts(paged){   
     
    if (window.stored_posts && window.stored_posts.length != 0 ){ 
      
        $.each(window.stored_posts, function(key, value) { 
            if(value['id'] != site_config_js.post_id){  
                 $("#posts-ajax").append(post_template(value));
            }  
        });
        window.stored_posts = [];
        window.load_more_content = true;  
        return false;

    } else { 
        
        var  data = {   
            feed : 'json2'
        };

        if(paged > 1){
            data.paged = paged ;
        }

        $.ajax({
            url : '/',
            type : 'GET',
            dataType: 'json', 
            data : data,
            success : function( response ) {  
                window.stored_posts = [];
                if (response) {   
                    var i = 1; 
                    $.each(response['posts'], function(key, value) {
                        if ( i < 6 ) { 
                            if(value['id'] != site_config_js.post_id){  
                                 $("#posts-ajax").append(post_template(value));
                            } 
                            i++;

                        }else{
                            window.stored_posts.push(value); 
                        }
                        
                    });
                }
                $('#paged').val(parseInt(paged)+1); 
                window.load_more_content = true; 
                return false;
            },
        }); 

    } 
       
}

/**
 * Prepare template for a given post
 * @param post
 * @return string
 */

function post_template (post) {

    var html =  "";

    html += "<div class='col-xs-12 col-sm-6 item-post'><div class='thumbnail'>";
                                  
    html += "<div class='thumbnail-visu mobile'><a href='"+post['permalink'] +"'><img id='img_more_post' class='lazy-loaded img-responsive zoom-it wp-post-image' src='"+post['thumbnail_medium']+"' ></a></div>";
    html += '<h2 class="title"><a href="'+post['permalink'] +'">'+post['title'] +'</a></h2>';
    html += '<div class="addthis_toolbox" addthis:url="'+post['permalink'] +
    '" addthis:title="'+post['title'] +'" addthis:description="'+post['excerpt'] +
    '"></div>';
    html += "</div></div>"; 
    return html;
}



function show_jw7_player(id_block, setup, url_template, pub_ads, type_provider, config_params ) {
    
    if( is_desktop && ( (isChrome && site_config_js.devs.desactiver_autoplay_inchrome_153133569) || 
        (isSafari && site_config_js.devs.desactiver_autoplay_insafari_153879194) ) ){
        setup.autoplay = false;
    }

    if( play_on_nav_diapo ){
        if(config_params.right_video){
            setup.autoplay = false;
        }
    }

	if(type_provider == 'dailymotion'){
		var params = {
			"video" : config_params.dailymotion_id ,
			"width" : "100%"
		} ;
		if(setup.autoplay) {
			params['params'] = {autoplay:true};
		}

		if(setup.width){
    		setup.width = setup.width +"" ;
    		params.width = setup.width.replace('px','');  		
    	}
    	if(setup.height){
    		setup.height = setup.height +"" ;
    		params.height =  setup.height.replace('px','');	   		
    	}

        if( config_params.next === true ){
            params.next = true;
        }

		show_dai_video(id_block, params, pub_ads) ;
		return ;
	}else if(config_params.mediaid.indexOf('adways.com') > -1){

		var params = {
			"width" : "100%",
			"src" : config_params.mediaid
		} ;

		if(setup.width){
			params.width = setup.width ;
		}else{
			params.width = jQuery("#" + id_block).parent().width();  		
		}
		if(setup.width){
			params.width = setup.width ;
		}else{
			params.width = jQuery("#" + id_block).parent().width();  		
		}

		if(setup.height){
			params.height = setup.height ;
		}else{
			params.height = params.width * 390/460 ;	
		}		

		show_adways_player(id_block, params) ;
		return ;
	}

	jwplayer.key =  jwplayer.key || site_config_js.jwplayer7_key;

    var videoPlayer = 'Jw player 7';
    var videoId = '';
    if(type_provider == "youtube"){
        videoId = config_params.youtube_id;
    }
    var tracking_Params = {
        'videoId' : videoId,
        'videoName' : '',
        'preload': 'auto',
        'videoPlayer' : videoPlayer,
        'type_provider' : type_provider
    };

    playerInstance = jwplayer(id_block);
	var plugin_liverail = url_template + '/assets/jwplayer/LiveRail.AdManager.JWPlayer-6.8.1.plugin.js' ;
	var plugin_sticky = site_config_js.SITE_SCHEME + "://cdn.stickyadstv.com/plugins/jw/StickyJW6Plugin.js" ;
	var plugins = {} ;
	var advertising = false ;
	if (pub_ads && !pub_ads.type) { 
 	      plugins[plugin_sticky] = {
       		remainingAdTime:"This advertisement runs for [sec] seconds",
   			ads:{
   				zones:{
   					preroll: pub_ads.prerollZoneId || -1 ,
   					midroll:  pub_ads.midrollZoneId || -1 ,
   					postroll:  pub_ads.postrollZoneId || -1 ,
   				},
   			}
   		} ;
		/*if(site_config_js.jw_mode_client == 'vast'){
			var jw_mode_client = 'vast' ;
		}else{
			var jw_mode_client = 'googima' ;
		}
		advertising = {
			//client: "vast",
			client: jw_mode_client,
			vpaidmode:"enabled",
			tag: "http://ads.stickyadstv.com/www/delivery/swfIndex.php?reqType=AdsSetup&protocolVersion=2.0&zoneId=" + pub_ads.prerollZoneId
		}
		*/

        
    }else if(pub_ads && pub_ads.liverail){
       	plugins[plugin_liverail] = pub_ads.liverail;
	}

	var setup_player = {
		plugins: plugins ,
		file:  config_params.mediaid,
		width: '100%',
 		aspectratio: "16:9",
 		primary:"flash",
		base: '/wp-content/themes/reworldmedia/assets/jwplayer7/'

	};

	if(site_config_js.jw_mode_flash ){ 
		setup_player.primary = "flash" ;
	}

	if(advertising !== false){
		setup_player.advertising = advertising ;
	}
	if(setup.autoplay && !is_mobile){
		setup_player.autostart = true ;
	}

	if(setup.repeat){
		setup_player.repeat = true;
	}

	if(setup.width){
		setup.width = setup.width +"" ;
		setup_player.width = setup.width.replace('px','');  		
	}

	if(setup.src_img){
		setup_player.image =  setup.src_img;	   		
	}
	if(setup.muted || (setup.autoplay && is_mobile)){
		setup_player.mute =  true;	   		
	}

    if(setup.stretching === true ){
        setup_player.stretching = "exactfit";
    }

    if(setup.height){
        setup_player.height = setup.height+'px';
    }

	playerInstance.setup(setup_player);

    //Start code added for tracking video
    playerInstance.onReady( function(){
        var prsentPoint = [];
        nextPoint = 0;
        playerInstance.onBeforePlay( function(event){ 
            if(!prsentPoint.length){
                videoDuration = this.getDuration();
                prsentPoint.push({'prsent':0, 'time':0, 'state':false});
                prsentPoint.push({'prsent':25, 'time':videoDuration*(0.25), 'state':false});
                prsentPoint.push({'prsent':50, 'time':videoDuration*(0.50), 'state':false});
                prsentPoint.push({'prsent':75, 'time':videoDuration*(0.75), 'state':false});
            }
        });

        play_on_click(this, config_params.right_video,'jwp7');
        if(typeof config_params.firtVideoPl !== 'undefined' && config_params.firtVideoPl === false && is_desktop){
            playerInstance.play();
        }

       /* playerInstance.onPlay( function(event){
			tracking_Params.event = 'Play';
            $(document).trigger('video_state_tracking', tracking_Params);
        });*/
        var $el = $("#"+id_block) ;
        mobile_first_play( playerInstance, $el, 'jwplayer7' );
       
        playerInstance.onAdError( function(event){
			tracking_Params.event = 'adscanceled';
            $(document).trigger('video_state_tracking', tracking_Params);


        });
		playerInstance.onAdPlay( function(event){
			tracking_Params.event = 'vast-preroll-ready';
            $(document).trigger('video_state_tracking', tracking_Params);
        });		


		playerInstance.on('adStarted', function(event){
			tracking_Params.event = 'vast-ready';
            $(document).trigger('video_state_tracking', tracking_Params);
        });


        playerInstance.on('adRequest', function(event){
			tracking_Params.event = 'call tag ads';
            $(document).trigger('video_state_tracking', tracking_Params);
        });



        playerInstance.onSeek( function(event){
            currentTime = event.offset;
            for(j=0; j<prsentPoint.length; j++){
                if( currentTime > prsentPoint[j].time){
                    prsentPoint[j].state = true;
                    nextPoint = j+1;
                }else{
                    prsentPoint[j].state = false;
                }
            }
        });

        playerInstance.onTime( function(event){
            var currentTime = event.position;
            if(prsentPoint[nextPoint]+"" != "undefined" && currentTime > prsentPoint[nextPoint].time && !prsentPoint[nextPoint].state){
                prsentPoint[nextPoint].state = true;
                var currentPrsentPoint = prsentPoint[nextPoint].prsent;
                tracking_Params.event = currentPrsentPoint;
                $(document).trigger('video_state_tracking', tracking_Params);
                nextPoint++;
            }
        });


        playerInstance.onComplete( function(){
            tracking_Params.event = 'End';
            $(document).trigger('video_state_tracking', tracking_Params);
			if(setup.autoloop){
				setup.autoplay = true ;
				show_jw7_player(id_block, setup, url_template, pub_ads, type_provider, config_params);
				
			}

            playlist(id_block, setup, config_params, type_provider, pub_ads);

            if( config_params.next === true ){
                next_video_playlist(id_block);
            }
        });

        playerInstance.onError( function(){
            tracking_Params.event = 'Error';
            $(document).trigger('video_state_tracking', tracking_Params);
        });
    });

    return playerInstance ;
            //End code added for tracking video
}

function flash_active(){
	if( navigator.mimeTypes.length > 0 )
	{
		return navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin != null;
	}
	else if( window.ActiveXObject )
	{
		try
		{
			new ActiveXObject( "ShockwaveFlash.ShockwaveFlash" );
			return true;
		}
		catch( oError )
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function show_video_brightcove(id_block, params){
    
	var width=  params.width ;
	var height=  params.height ;

	var default_player=  params.default_player ;
	var mobile_player=  params.mobile_player ;
	var html5_player=  params.html5_player ;
	var force_player=  params.force_player ;
	var video_id=  params.video_id ;

	var player  = params.default_player ;
	var autoplay  = params.autoplay ;
	var mutevideo  = params.mutevideo ;

	if(force_player){
		player  = force_player ;
	}else if(mobile_player && ( is_mobile || is_tablet ) ){
		player  = mobile_player ;
	}else if(html5_player && !flash_active() ){
		player  = html5_player ;
	}

	var src = "//players.brightcove.net/5354193441001/" + player + "_default/index.html?videoId=" + video_id ;


	if( autoplay ){
		src += '&autoplay=true' ;
	}

	if(mutevideo){
		src += '&mute=true' ;
	}


	var html = '<iframe src= "'+ src +'" \
scrolling="no"  frameborder="0" class="embeded" style="border:none;" width="'+ width +'" height="'+  height +'"  allowTransparency="true"\
></iframe>'
	$('#' + id_block).html(html) ; 

}


function second_tracking(url) {
    window.open(url);
}



/**
* Responsive Iframe/iframes
*/
 window.addEventListener( "message", function(e) {
 	var  data = e.data ;
 	if(data && data.indexOf){
		if(data.indexOf('iframe_height') > -1 && data.indexOf('iframe_id') > -1){
 			/** For multi iframes
 			* 1- Include the following Script in the partner pages 
 			* <script type='text/javascript' src='//www.viepratique.fr/wp-content/themes/reworldmedia/assets/javascripts/multi-iframe-autosize.js'></script>
 			* 2- Define iframe path as an id (ex: xxxx.com/yyyy.html => id="yyyy")
 			* 3- Enjoy! ;)
 			*/
			var iframe = JSON.parse(e.data);
			if(iframe.iframe_id){
				var iframe_obj = document.getElementById(iframe.iframe_id.replace('/',''));
				if(iframe_obj) iframe_obj.style.height = iframe.iframe_height+"px";
			}
		}else if(data.indexOf('height_iframe_') > -1){
 			/** For one iframe
 			* 1- Include the following Script in the partner page
 			* <script type='text/javascript' src='//www.viepratique.fr/wp-content/themes/reworldmedia/assets/javascripts/iframe-autosize.js'></script>
 			*/
			var height = data.substr(14);
			var frame = document.getElementById('iframe-autosize');
			frame.style.height = height + "px";
		}
 	}
 });


window.addEventListener("DOMContentLoaded", function(e) {
	var $refresh_duration = 300;
	if( site_config_js.devs.refresh_manager_153552908 && site_config_js.refresh_meta_duration !== 'undefined'){
		$refresh_duration = site_config_js.refresh_meta_duration;
	}
	if( site_config_js.enable_refresh_meta !== 'undefined' && site_config_js.enable_refresh_meta){
		jQuery('head').append('<meta id="meta-refresh" http-equiv="refresh" content="'+ $refresh_duration +'">');
	}
});




function isIE(userAgent) {
  userAgent = userAgent || navigator.userAgent;
  return userAgent.indexOf("MSIE ") > -1 || userAgent.indexOf("Trident/") > -1 ;
}


$("#carousel-gallery-generic, #custom-full-carousel-type-1").on('slide.bs.carousel', function (e) { 
	window.imgc= e ;
	var img = $(e.relatedTarget).find("img.lazy-load"); 
	if(img.length){
		img.attr("src", img.data("src")).removeClass('lazy-load').addClass("lazy-loaded");
	}

	var img2 = $(e.relatedTarget).next(".item").find("img.lazy-load"); 
	if(img2.length){
		img2.attr("src", img2.data("src")).removeClass('lazy-load').addClass("lazy-loaded");
	}

});




$("#archive_carousel").on('slide.bs.carousel', function (e) { 
    window.imgc = e ;

    $current_item_img = $(e.relatedTarget).find("img.lazy-load");
    var img = $current_item_img[0];
    var img_exposant = $current_item_img[1];

    if( $(img).length ){
        $(img).attr("src", $(img).data("src")).removeClass('lazy-load').addClass("lazy-loaded");
    }
    if( $(img_exposant).length ){
        $(img_exposant).attr("src", $(img_exposant).data("src")).removeClass('lazy-load').addClass("lazy-loaded");
    }

    $next_item_img = $(e.relatedTarget).next(".item").find("img.lazy-load");
    var img2 = $next_item_img[0];
    var img2_exposant = $next_item_img[1];

    if( $(img2).length ){
        $(img2).attr("src", $(img2).data("src")).removeClass('lazy-load').addClass("lazy-loaded");
    }
    if( $(img2_exposant).length ){
        $(img2_exposant).attr("src", $(img2_exposant).data("src")).removeClass('lazy-load').addClass("lazy-loaded");
    }

});

