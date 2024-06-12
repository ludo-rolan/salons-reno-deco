$(document).ready(function() {

    $(".navbar-toggle").click(function(){
        $("body").toggleClass("show-menu");
        $("header .menu-site").toggleClass("hidden-xs hidden-sm");
    });

    $("body").hide();
    $(function() {
        var $container = $('#results>.items');
        if( $container.length > 0 ){
            $container.imagesLoaded(function() {
                $container.masonry({
                    itemSelector: '.item',
                    columnWidth: 1,
                    isAnimated: true
                });
            });
        }

    });

    if( $("body").hasClass('single') && $(".diaporama_newsletter").length){
        $('.diaporama_newsletter').slick({
           slidesToScroll: 1,
           draggable: true,
           infinite: false,
       });
    }

    $(function() {
        var $container = $('footer .center .menus .nav-menu');
        if( $container.length > 0 ){
            $container.imagesLoaded(function() {
                $container.masonry({
                    itemSelector: '.nav-menu>.menu-item',
                    columnWidth: function() {
                        if ($("body").hasClass("home"))
                            return 6;
                        else
                            return 4;
                    },
                    isAnimated: true
                });
            });
        }

    });
    $(function() {
        var $container = $('#content #result .entry-content .nav-menu');
        if( $container.length > 0 ){
            $container.imagesLoaded(function() {
                $container.masonry({
                    itemSelector: '.nav-menu>.menu-item',
                    columnWidth: function(containerWidth) {
                        return containerWidth / 2;
                    },
                    isAnimated: true
                });
            });
        }

    });
    $(function() {
        var $container = $('#content #results .thecontent .gellery ul');
        if( $container.length > 0 ){
            $container.imagesLoaded(function() {
                $container.masonry({
                    itemSelector: 'li',
                    columnWidth: 1,
                    isAnimated: true
                });
            });
        }

    });
    navCaroussel();
    navDiaporama();

    if ($("div.carroussel").length )
        window.timerCaroussel = setInterval("autoCaroussel()", 8000);

    if (getUrlVars() == "frame") {
        $("body").hide();
        var contentpage = $("footer").html();
        var jsfooter = '<script type="text/javascript">$(function(){var $container = $("footer .center .menus .nav-menu");$container.imagesLoaded(function(){$container.masonry({itemSelector : ".nav-menu>.menu-item",columnWidth : function( ) {return 4;},isAnimated: true});});});</script>';
        $("body").html("");
        $("body").html('<footer>' + contentpage + '</footer>' + jsfooter);
        $("body").show();
        targetBlank();
    } else {
        $("body").show();
    }

    paginationHome();
    paginationRubrique();
    
    seo();
    seo_menu();
    effet_bloc_sommaire();

    ninja_form_add_img();

    initPlayerMostPopularVideo();

    load_video();
    calc_new_sharer_size();


    // Quizz
    if ( typeof $.datepicker !== 'undefined' && $.isFunction( $.datepicker ) ) {
        $( ".datepicker_quizz" ).datepicker({
            dateFormat: 'dd/mm/yy',
            changeYear: true,
            yearRange : "1920:Now"
        });
    }

	if($.prototype.twentytwenty){
 		$(".block_before_after").twentytwenty({'move_on_hover':true});
	}


	$('#button_accept_cookies').click(function(){
		$.cookie( "eccept_cookie" , 1  , { expires: 365,  path: '/' } );
		$("#rw_alert_cookies").hide("slow");
	});

	$('#bandeau-partners-carousel').slick({
		slidesToShow: 5,
		slidesToScroll: 4,
		infinite: true,
		draggable: true,
		autoplay: true,
		autoplaySpeed: 3000,
		arrows: false,
		responsive: [
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 3
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
		]
	});

    var navbar = $("header.v1");
    var offset_navbar = navbar.offset();
    function navbar_fixed_top() {
        if(!navbar.hasClass('navbar-fixed-top'))
            offset_navbar = navbar.offset();
        if(offset_navbar.top<$(window).scrollTop())
            navbar.addClass('navbar-fixed-top');
        else
            navbar.removeClass('navbar-fixed-top');
    }

    if( navbar.length ){
        $(window).scroll(function(){
            if( $(window).width()>767 || (is_mobile && site_config_js.header_sticky_on_mobile) ){
                navbar_fixed_top();
            }else{
                navbar.removeClass('navbar-fixed-top');
            }
        });
    }

    if( $.fn.shave ){
        if( is_mobile ){
            $('.carousel-exposant-name').shave(30);
        }
        $('.post_title a').shave(45);
        $('.post_excerpt').shave(60);
    }

    
    var exposant_filters = $('#exposants_filter input[type="checkbox"]');
    var url = window.location.origin + window.location.pathname;
    exposant_filters.change(function(e){
        var exposant_cats = [];
        exposant_filters.each(function() {
            if( $(this).is(":checked") ) {
                exposant_cats.push($(this).val());
            }
        });
        if( exposant_cats.length>0 ){
            url = url+'?filters='+exposant_cats;
        }
        window.location.href = url;
    });

    var $contact_form = $('.exposant-forms');
    if( $contact_form.length ){
        $('#scroll_to_contact').on('click', function(){
                var form_pos = $contact_form.offset().top;
                $("html, body").stop().animate({scrollTop:form_pos-20}, 500, 'swing');
        });
    }

    $('#show_exposant_phone').on('click', function(){
        var $phone_input = $(this).next('#exposant_phone');
        if( $phone_input.length && $phone_input.hasClass('hidden') ){
            var phone_number = $phone_input.val();
            $phone_input.removeClass('hidden');
            send_GA( "Numéro téléphone exposant", 'affichage', self.location.href, phone_number);
        }
    });

    $(function() {
        var toggle = $('.arrow');
        toggle.on('click', function() {
            var children_cat = $('.list-children-cat');
            var element_id = $(this).parent().attr('id');
            if ($(this).hasClass('up') && children_cat.hasClass('down') ) {
                $(this).removeClass('up').addClass('down');
                $('#' +element_id +' > .list-children-cat').removeClass('down').addClass('up');
             }else {
                $(this).removeClass('down').addClass('up');
                $('#' +element_id +' > .list-children-cat').removeClass('up').addClass('down');
            }

        });
    });

});

$(window).load(function() {

    var url_hash = window.location.hash;
    var url_pathname = window.location.pathname;
    if( !url_hash && url_pathname.indexOf('/plan_salon/page/')>=0 ){
        url_hash = 'plan_salons_filters';
    }
    var $hash_target = $('[data-hash-target="'+url_hash+'"]');
    if( $hash_target.length>0 ){
        var spacer = ($('header').innerHeight()*2)+30;
        var hash_target_pos = $hash_target.offset().top;
        $("html, body").stop().animate({scrollTop:hash_target_pos-spacer}, 700, 'swing');
    }

    $("#content #results .thecontent .diaporama ul li.item img").each(function(index, el) {
        var photo = new Image();
        photo.url = el.src;
        $(photo).bind('load', centerImgDiaporama(photo, el));
    });
    $("#content .carroussel ul li a.pimg img").each(function(index, el) {
        var photo = new Image();
        photo.url = el.src;
        $(photo).bind('load', centerImgCarroussel(photo, el));
    });
    indexDiaporama = Number($("#content .diaporama .navs li.current").attr("index"));
});
