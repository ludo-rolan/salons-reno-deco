// using jquery from wordpress.. 
// $ is not defined
var $=jQuery;
var item_index = catch_item() ;
var item_count = $("#large_diapo_rw ._left").data('count') ;
var dfp_taboola_display = false;
var dfp_taboola_mobile_display = false;
if (site_config_js.diapo_monetisation_mobile && item_index == item_count) {
	$("#dmm-content").removeClass("hidden");
}
$( "#scroll_arrows .arrow_previous , #dmm_scroll_arrows .arrow_previous" ).click(function() {
	$(".block_diapo .visu_block>.btn-navs._left").trigger("click");
	ancre_monetisation(site_config_js.ancre_diapo_monetisation);
});
$( "#scroll_arrows .arrow_next , #dmm_scroll_arrows .arrow_next" ).click(function() {
	$(".block_diapo .visu_block>.btn-navs._right").trigger("click");
	ancre_monetisation(site_config_js.ancre_diapo_monetisation);
});

function ancre_monetisation(selector){
	selector = selector || "#container" ;
	$('html, body').animate({ scrollTop: $(selector).offset().top+10 }, 300);
}

function disable_taboola(){
	if(typeof homemade_html !== 'undefined' && !dfp_taboola_display){
		dfp_taboola_display = true;
		$('div[id^="taboola"]').parent().remove();
		if($(".homemade").length === 0){
			$sharer_after_comment = $('.sharer_after_comment');
			$insert_after = $sharer_after_comment.length ? $sharer_after_comment : $('#results article[id^=post-]');
			$("<div id='dfp_taboola'></div>").insertAfter($insert_after);
			$("#dfp_taboola").html(homemade_html);
		}
	}
}

function disable_taboola_mobile(){
	if(typeof homemade_mobile_html !== 'undefined' && !dfp_taboola_mobile_display){
		dfp_taboola_mobile_display = true;
		$('div[id^="taboola"]').remove();
		if($(".homemade-mobile").length === 0){
			$insert_after = $('#results');
			$("<div id='dfp_taboola'></div>").insertAfter($insert_after);
			$("#dfp_taboola").html(homemade_mobile_html);
		}
	}
}

if(site_config_js.devs.changement_diapo_avec_fleches_clavier_118101879){
	$(document).keydown(function(event) {
		if(event.keyCode == 37){
			$(".block_diapo .visu_block>.btn-navs._left").trigger("click");
		}else if(event.keyCode == 39){
			$(".block_diapo .visu_block>.btn-navs._right").trigger("click");
		}
	});
}

function catch_item() {
    var regEx = /#item=([0-9]+)/i;
    var matches = regEx.exec(window.location.href);
    return (matches && matches[1]) ? matches[1] : 1;
}


var cache = [];
function preLoadImages(_this) {
      var cacheImage = document.createElement('img');
      cacheImage.src = jQuery(_this).data("src");
      cacheImage.alt = "";
      cacheImage.className="pinit-here";
    return cacheImage;
}

function diaporama_galerie(){
	var url_page=(window.location).toString();
	var li_visus=$(".block_diapo .list_thumbs ul").children();
	var lists_legend=$("ul.lists_legend").children();
	$(".block_diapo .visu_block>.carousel-indicators li").each(function(i,obj){
		$(obj).unbind();
		$(obj).bind("click",function(){
			$(".block_diapo .visu_block>.carousel-indicators li").removeClass('active');
			$(obj).addClass('active');
			li_visus[i].click();
		});
	});
	$(".block_diapo .list_thumbs ul li").each(function(i,obj){
		$(obj).unbind();
		$(obj).bind("click",function(){
			if(!is_desktop && site_config_js &&  site_config_js.sync_ads_refresh_mobile){
				var str_split="#item=";
				var index_page=i+1;
				url_page_tab=url_page.split(str_split);
				var url_page_new=url_page_tab[0]+"#item="+index_page;
				window.location.href = url_page_new ;
				window.location.reload();

			}else{
				if(site_config_js && site_config_js.reworld_async_ads == 0){
					var str_split="#item=";
					var index_page=i+1;
					url_page_tab=url_page.split(str_split);
					var url_page_new=url_page_tab[0]+"#item="+index_page;
					window.location.href = url_page_new ;
					window.location.reload(true);

				}else{
					change_item_diapo(i);
				}
				if( !site_config_js.devs.decalage_mpu_haut_1482 && typeof getDimensionBlockDiapo == 'function' ){
					getDimensionBlockDiapo();
				}
			}
		});	
	});
	

	var $nav_btn_obj = $(".block_diapo .visu_block>.btn-navs, .block_diapo .slide_nb .caption-navs, .widgetLegende .legend_picture .info_play .legend_icon");
	$nav_btn_obj.unbind();
	$nav_btn_obj.bind("click",function(){
		var _this=this;
		var item_index = $(_this).data('index');
		var item_count = $(_this).data('count');
		if (site_config_js.sync_ads_refresh_mobile !=true && site_config_js.diapo_monetisation_mobile) {
			if (item_index +1 == item_count) {
				$("#dmm-content").removeClass("hidden");
			}
			else{
				$("#dmm-content").addClass("hidden");
			}
		}
		if(item_index==item_count){
			if (site_config_js.diapo_redirection != null && window.gallery_url != null){
				if(!site_config_js.diapo_monetisation_mobile){
					window.open(gallery_url, "_self");
					return;
				}
			}
			if($( ".related_posts_hide" ).length){
				$(".block_diapo .visu_block figure").html("<div class='related_posts'>"+$(".block_diapo").find('.related_posts_hide').html()+"</div>");
				$(".block_diapo .visu_block>.btn-navs, .block_diapo .slide_nb .caption-navs").data("index",$(_this).data("index")-$(_this).data('count'));
				$("ul.lists_legend li").removeClass("active");
				$("#container .widgetLegende").hide();
			}
		}else{
			$("#container .widgetLegende").show();
			$(li_visus[$(_this).data('index')]).click();
		}
	});

	
	var $popup_diapo = $('#popup_diapo');
	if( $popup_diapo.length ){
		$('#popup_diapo .btn-navs, #popup_diapo .info_play .legend_icon').on('click', function(){
			
			if( !$popup_diapo.hasClass('open') ){
				$popup_diapo.addClass('open');
				$('body').addClass('noscroll');
			}

			if( $('body').hasClass('has_gallery') && $(window).width()>1000 && typeof site_config_js.version != 'undefined' && site_config_js.version == 2 ) {
				var legendeHeight = $("#large_diapo_rw .widgetLegende").innerHeight();
				$("#popup_diapo_rightbanner").css({ 'margin-top' : legendeHeight+10 });
			}

		});
		$('#close_popup_diapo').on('click', function(){
			if( $popup_diapo.hasClass('open') ){
				$popup_diapo.removeClass('open');
				$('body').removeClass('noscroll');
				$(li_visus[0]).click();
			}
		});
	}


	$(".block_diapo .list_thumbs .navs>span").unbind();
	$(".block_diapo .list_thumbs .navs>span").bind("click",function(){
		if($(this).data('index')<=$(this).data('count')-1 && $(this).data('count')-$(this).data('index')>4){
			var thumbnail_gallery = site_config_js.thumbnail_gallery || 104 ;
			var val_pos= -($(this).data('index')* thumbnail_gallery );
			if($(this).hasClass('_left') && ($(this).parent().find("._left").data('index')>0)){
				$(this).parent().find("._right").data("index",$(this).data("index"));
				$(this).data("index",$(this).data("index")-1);
			}else if($(this).hasClass('_right')){
				
				$(this).parent().find("._left").data("index",$(this).data("index")-1);
				$(this).data("index",$(this).data("index")+1);

			}
			$(".block_diapo .list_thumbs .list_block").find('ul').animate({left : val_pos},1000);
		}

	});

	$(document).trigger('diaporama_ready' );
	 
}


function getUrlVars_() {    
  var vars = [], hash;
  var hashes = window.location.href.split('#');
  hashes = ((hashes.length>1) ? hashes[1] : '');
  return hashes;
}

function change_item_diapo(i, rafraichir_pub ){ 
	rafraichir_pub = typeof rafraichir_pub !== 'undefined' ? rafraichir_pub : true ;
	var url_page=(window.location).toString();
	var li_visus=$(".block_diapo .list_thumbs ul").children();
	var lists_legend=$("ul.lists_legend").children();
	var _this = $(".block_diapo .list_thumbs ul li").get(i) ;
	$("ul.lists_legend li").removeClass("active");
	$(lists_legend[i]).addClass('active');
	// activate carousel indicators
	if($(".carousel-indicators") != null) {
		var li_indicators=$(".carousel-indicators").children();
		if(li_indicators.length > 0) {
			$(".carousel-indicators li").removeClass("active");
			$(li_indicators[i]).addClass('active');
		}
	}
	$(".block_diapo .list_thumbs ul li").removeClass("active");
	$(_this).addClass("active");
	$(".block_diapo .visu_block figure").append("<span class='loading'>&nbsp;</span>");

	var $content_figure = preLoadImages( _this );
	jQuery( $content_figure ).load( function( e ) {
		var custom_url = jQuery( _this ).data( "custom_url" );
		if ( custom_url != undefined ) {
			var $custom_url = jQuery( '<a href="' + custom_url + '" target="_blank"></a>' );
			$content_figure = $custom_url.html( $content_figure );
		}
		
		/**
		* 	Entourer l'image diapo par un lien pour l'agrandir via zoombox
		*	ticket #160311298 : **AUTO MOTO** | Pouvoir agrandir les images des diaporamas
		* 	By bouhou@webpick.info
		*/
		else if(site_config_js.pouvoir_agrandir_images_diaporama && $.zoombox) {
			var $img_full = jQuery(_this).data("full");
			if($img_full) {
				var $link_big_img = jQuery('<a class="big_img_link" href="' + $img_full +'"></a>');
				try {
					$link_big_img.zoombox();
				} catch (e) {}
				$content_figure = $link_big_img.html($content_figure);
			}
		}
		/**
		* 	End ticket #160311298
		**/

		if( site_config_js.devs.decalage_mpu_haut_1482 ){
			$( ".block_diapo .visu_block figure" ).html( $content_figure ).promise().done(function(){
				if( typeof getDimensionBlockDiapo == 'function' ){
					getDimensionBlockDiapo();
				}
			});
		}else{
			$( ".block_diapo .visu_block figure" ).html( $content_figure );
		}
		$(".block_diapo .visu_block>.btn-navs._left, .block_diapo .slide_nb .caption-navs._left").data('index',i-1);
		$(".block_diapo .visu_block>.btn-navs._right, .block_diapo .slide_nb .caption-navs._right").data('index',i+1);

		if($(li_visus[i]).data('video')!="") {
			if($(".block_diapo .visu_block .figure").length == 0){
				$(".block_diapo .visu_block ").append('<div class="figure" >');
			}

			$(".block_diapo .visu_block").removeClass('insta_post');
			$(".block_diapo .visu_block .figure").show().html('<div class="block_video_gallery"><div class="video" id="video_'+i+'_gallery" href="api:'+$(li_visus[i]).data('video_id')+'"></div></div>');
			$(".block_diapo .visu_block figure").hide() ;
		
			var $li  = $(li_visus[i]) ;
			var height = $(".block_diapo .visu_block").height()
			var mediaid = $(li_visus[i]).data('video');
			var autoplay = $(li_visus[i]).data('autoplay');
			
			playVideodiapo(e, i+"_gallery", $li.data('video_type'), $li.data('video_id'), height, mediaid, autoplay) ;
		}else if($(li_visus[i]).data('insta') != "" && typeof $(li_visus[i]).data('insta') !== 'undefined' && site_config_js.devs.instagram_post_in_gallery_112502917){
			var $insta_link = $(li_visus[i]).data('insta') + '/embed';
			if($(".block_diapo .visu_block .figure").length == 0){
				$(".block_diapo .visu_block ").append('<div class="figure" >');
			}
			$(".block_diapo .visu_block .figure").show().html('<iframe class="instagram-media instagram-media-rendered" id="instagram-embed-0" src="'+ $insta_link +'" allowtransparency="true" frameborder="0" height="580" data-instgrm-payload-id="instagram-media-payload-0" scrolling="no" style="border: 0px; margin: 1px; max-width: 500px; width: calc(100% - 2px); border-radius: 4px; box-shadow: rgba(0, 0, 0, 0.498039) 0px 0px 1px 0px, rgba(0, 0, 0, 0.14902) 0px 1px 10px 0px; display: block; padding: 0px; background: rgb(255, 255, 255);"></iframe>');
			$(".block_diapo .visu_block figure").hide() ;
			$(".block_diapo .visu_block").addClass('insta_post');

		}else{
			$(".block_diapo .visu_block").removeClass('insta_post');
			$(".block_diapo .visu_block figure").show() ;
			if($(".block_diapo .visu_block .figure").length > 0){
				$(".block_diapo .visu_block .figure").hide() ;
			}
		}
	});

	if(!is_desktop && site_config_js.sync_ads_refresh_mobile){
		$(document).ready(function(){
			$('html, body').animate({
				scrollTop: $(".large-diapo").offset().top
			}, 1000);
		});
	}
	var str_split="#item=";
	var index_page=i+1;

	if (site_config_js && site_config_js.move_gallery_thumbs_nav) {
		gallery_nav_thumbs(index_page);
	}

	url_page_tab=url_page.split(str_split);
	if( is_mobile && site_config_js.gallery_popin_mobile && !window.location.hash ){
		var url_page_new=url_page_tab[0];
	}else{
		var url_page_new=url_page_tab[0]+"#item="+index_page;
	}
	if ("pushState" in history) {
		history.pushState(null, "Diaporama Article", url_page_new);
	}

	 
	// and google analytics tracking 
	if (site_config_js && site_config_js.reworld_async_ads == 1 && rafraichir_pub) {
    	var a = document.createElement('a');
    	a.href = url_page_new;
    	var only_path_and_hash = a.pathname+a.hash;
	    if(window._gaq){
	    	window._gaq.push( ['_trackPageview', only_path_and_hash]);
	    }else{
	    	if (typeof site_config_js["partners"] !== 'undefined' && site_config_js["partners"].analytics == true) {
	        	/*ga('send', 'pageview', only_path_and_hash);

				if(site_config_js.other_google_analytics_ids){
            		for (var ga_name in site_config_js.other_google_analytics_ids) {
	        			ga( ga_name + '.send', 'pageview', only_path_and_hash);
            		}
            	}*/
            	pageview_GA(only_path_and_hash);

	        }
	    }
    }



	if ( typeof refresh_ads == 'function'  && rafraichir_pub && window.last_refres !== i ) {
		try{
			refresh_ads();
		}catch(e){
			
		}
		window.last_refres = i ;
	}
    // hook to be used by interstitiel
    $(document).trigger('change_item' , [i, rafraichir_pub]);  
    $("#meta-refresh").attr("content","300; url="+window.location.href );
}

function playVideodiapo(e, id, type, video_id, height_block, mediaid, autoplay) {
	autoplay  = (autoplay == 'no')? false : true;
    param = {
        mediaid : mediaid
    };
    if (type == 'youtube') {
        param = {
            youtube_id: video_id,
            mediaid : 'https://www.youtube.com/watch?v='+video_id
        }
    } else if (type == 'dailymotion') {
        param = {
            dailymotion_id: video_id,
            url : 'http://dai.ly/'+video_id,
            mediaid : 'http://dai.ly/'+video_id

        }
    }
    var url_template = site_config_js.url_template;
    var id_pub = site_config_js.id_pub;
    e.preventDefault();
    var height = jQuery("#post-" + id + " .thumbnail .thumbnail-visu").height()
    jQuery("#post-" + id + " .thumbnail .thumbnail-visu").hide();
    jQuery("#post-" + id + " #video_" + id).css({
        "display": "block",
        'height': height
    });

    if(site_config_js.devs.passage_du_player_sur_jw6_111776366){
		var setup = {
			'autoplay': autoplay
		};
    	if(height_block){ 
    		setup.height = height_block ;
    	}
    	show_jw_player("video_" + id, setup, url_template, id_pub, type, param);	
    }else{
    	param.height = height_block ;
    	param.width = jQuery("#" + "video_" + id).parent().width();

    	show_video("video_" + id, url_template, id_pub, type, "", autoplay, param);	
    }
    return false;
}

if(site_config_js.sync_ads_refresh_mobile)
{
	function launchDiapo(item){
		diaporama_galerie();
		if(item>0){
			var li_visus_load=$(".block_diapo .list_thumbs ul").children();
			item = (item-1)%li_visus_load.length;
			if(item<li_visus_load.length){
				change_item_diapo(item, false);
			}

		}
		if( typeof getDimensionBlockDiapo == 'function' ){
			getDimensionBlockDiapo();
		}
	}
	var the_item = catch_item();
	if(jQuery){
		launchDiapo(the_item);
	}else{
		$(function(){
			launchDiapo(the_item);
		});
	}
	
}else{
	$(document).ready(function(){
		diaporama_galerie();

		var item_nb = catch_item()-1;
		if( item_nb>= 0){
			change_item_diapo(item_nb, false);
		}

		if( typeof getDimensionBlockDiapo == 'function' ){
			getDimensionBlockDiapo();
		}

		$(window).on('hashchange', function(e) {
		    var item = catch_item();
		    item = parseInt( item) -1;
		    if(item>=0){
				change_item_diapo(item);
		    }
		});

	});
}

function gallery_nav_thumbs(idx){
	var count_items = $(".thumb_list li").length;
	var items_width = $(".thumb_list li").outerWidth(); 
	idx = idx-1;
	
	if(idx==0){
		$(".thumb_list").animate({
			left : items_width,
		});
	}else if(count_items-2 <= idx){
		$(".thumb_list").animate({
			left : (items_width * -(count_items-2))+items_width,
		});
	}else{
		if(count_items>0){
			$(".thumb_list").animate({
				left : (items_width * (-idx))+items_width,
			});
		}
	}
}
/**
 * GA : les elements d'une diapo
 * @param   e  : event
 * @param   i  : index du diapo
 */
if(site_config_js.devs.evenement_article_diapo && !site_config_js.disable_viewed_diapo_event ) {
	$(document).on("change_item" , function(e , i) {
		setTimeout(function(){
			send_GA( 'viewed_diapo', url_page_tab[0], 'item-'+i);
		}, 3000);		
	});
}

if( is_mobile && site_config_js.gallery_popin_mobile ){
	$('body').addClass('diapo_mobile');
	var data_count = $('#large_diapo_rw .btn-navs').data('count');
	var close_popin = '<div class="top_diapo"><span class="paginate_diapo"><span class="active_slide">index_diapo</span>/'+data_count+'</span><span class="close_full">X</span></div>';
	var height = $("#megabanner_top").innerHeight();
	var top_popup = 55 + height;
	$dp = $('.diapo_mobile #large_diapo_rw');
	
	$('#btn-show-fullscreen').click(function(){
	  	$("html, body").animate({ scrollTop: 0 }, "slow");
		$dp.append(close_popin.replace('index_diapo',catch_item()));
		$dp.css('display', 'block');
		$('.block_diapo').css('top',(top_popup+30)+'px');
		$('.top_diapo').css('top',(top_popup)+'px');
		$('body').addClass('fulldiapo_mode');
	});

	$(document).on('click','.close_full',function(){
		history.pushState("", document.title, window.location.pathname);
		$dp.css('display', 'none');
		change_item_diapo(0,false);
		$('.top_diapo').remove();
		$('body').removeClass('fulldiapo_mode');
	});

	$(document).ready(function(){
		if(window.location.hash){
			$("html, body").animate({ scrollTop: 0 }, "slow");
			$dp.append(close_popin.replace('index_diapo',catch_item()));
			$dp.css('display', 'block');
			$('.block_diapo').css('top',(top_popup+30)+'px');
			$('.top_diapo').css('top',(top_popup)+'px');
			$('body').addClass('fulldiapo_mode');
		}
	});
}


if(site_config_js.gallery_auto_slide) {
$auto_slide_timer = setInterval(function(){
	$(document).ready(function(){
		$nav_right = $("._right");
		$current_item = catch_item();
		$diapo_count = $nav_right.data('count');

		if( $current_item < $diapo_count ){
			$nav_right.trigger("click");
		}else{
			clearInterval($auto_slide_timer);
		}
	});
}, 10000);
}


if ( site_config_js.devs.network_pinterest_epingler_chaque_image_diapo_148329911 &&
	typeof site_config_js["partners"] !== 'undefined' &&
	site_config_js["partners"].pinit_img == true  ) {

	function init_pinterest_img_to_share( index ) {
		var element = $( '.list_thumbs ul.list-inline li' ).get( index );
		var image = '';

		if ( element ) {
			image = $( element ).data( 'src' );
			if( typeof window.share_object !== 'undefined' ){
				window.share_object.config.networks.pinterest.image = image;
				var $shares = $( 'div.share-buttons' ).has( '.social-network' );
				$shares.each( function( i ) {
					var $id = $( this ).attr( 'id' ); // récupère l'id du sharer
					window.share_object.setup_instance( '#' + $id, 0 );
				} );
			}
		}
	}

	$( document ).ready( function() {
		init_pinterest_img_to_share( catch_item() - 1 );

		$( document ).on( 'change_item', function( e , i, rafraichir_pub ) {
			init_pinterest_img_to_share( i );
		} );
	} );
}
if ( site_config_js.gallery_in_ajax ) {
	$(document).ready(function() {
	    load_gallery();
	});
}
function load_gallery(){
    var $url = window.location.href.split('?')[0],
    $image_top_single = $('.image-une-gallery'),
    $attr = $image_top_single.data('attr');
    $.ajax({
        url : $url,
        type : 'GET',
        data : {
            'gallery_ajax' : 1,
            'attr': $attr,
        },
        success : function( response ) {
            if ( response ) {   
                $image_top_single.replaceWith(response);
                diaporama_galerie();
                $('ul.lists_legend li').first().addClass('active');
            }
            return false;
        },
    });
}

//Scroll to first diapo ticket pvt 157692931

$( document ).ready(function() {
	if(window.location.pathname.indexOf("mamans-202951.html") > 0){
		
		var item_to_scroll_top = 0;
		if(is_mobile){
			item_to_scroll_top = $(".diapo_linear").offset().top;
		}else{
			item_to_scroll_top = $("#large_diapo_rw").offset().top;
		}

		window.scrollTo(0, item_to_scroll_top);
	}


	if( site_config_js.devs.changement_diapo_avec_fleches_clavier_118101879 && site_config_js.show_gallery_keyboard_nav ) {
		var item = catch_item() == 1 ? 0 : catch_item();
		toggle_keyboard_nav(item);
		$(document).on("change_item" , function(e , i) {
			toggle_keyboard_nav(i);
		});
	}
	function toggle_keyboard_nav(i){
		var $key_van = $('.keyboard_nav');
		if( i>0 ){
			if( !$key_van.hasClass('hide') ){
				$key_van.addClass('hide');
			}
		}else if( $key_van.hasClass('hide') ){
			$key_van.removeClass('hide');
		}
	}

});

if( site_config_js.devs.nouveau_template_modification_feed_taboola_7063){
	$( document ).on( 'change_item', function( e , i, rafraichir_pub ) {
		if(rafraichir_pub){
			disable_taboola();				
		}
	});
}

if( site_config_js.devs.monetisation_feed_taboola_mobile_7918){
	// page is reloaded
	if((typeof window.performance.navigation.type !== "undefined") && window.performance.navigation.type == 1){
		disable_taboola_mobile();
	}
}







