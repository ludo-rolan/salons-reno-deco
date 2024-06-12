//$(document).ready(function($) {
(function ($) {
$(window).load(function(){
    var $to_fix_select = $('#blockRight .list-ms-item');
    var $sidebar_select = $('#blockRight');
    var $start_fix_select = $('#fix_onscroll').parents('aside');
    var $second_start_fix_select = $('#second_fix_onscroll').parents('aside');
    var $start_article_fix_select = $('#fix_onscroll_article').parents('aside');
    var has_article_widget_fix = false;
    var is_se = (typeof site_config_js.is_sporever !== 'undefined' && site_config_js.is_sporever);

    //Article page fix select
    if($('body').hasClass('single') && $start_article_fix_select.length > 0){
    	$start_fix_select = $start_article_fix_select;
        has_article_widget_fix = true;
    }

    if($to_fix_select.length > 0 && $sidebar_select.length > 0 && $start_fix_select.length > 0){

        var $left_wrapper_select = $('#left_wrapper');
        var $large_diapo_select = $('.widgetLegende');
        var $main_content_select =  $('#content');
        var $diapo_content = $('#content .diapo-content');
        var $block_diapo = $('#content .block_diapo');

        if( is_se && $diapo_content.length > 0 ) {
            $main_content_select = $diapo_content;
        }
        
        var window_width = $(window).width();
        var min_window_width = 992;
        var content_height = 0;
        var old_content_height = 0;
        var end_scroll;
        var scroll_pos;
        var sidebar_start_fix; 
        var new_start_fix = 0; 
        var left_wrapper_height;
		var large_diapo_height;
		var main_content_height;
        var first_start;

        //sidebar top offset
        var sidebar_pos = $sidebar_select.offset().top;
        //ads position context : document
        var start_fix_offset = $start_fix_select.offset().top;
        //ads position context : parent
        var start_fix_pos = $start_fix_select.position().top;
        
        var sidebar_height = $sidebar_select.innerHeight();
        var sidebar_list_item;
        var to_fix_height = 0;

        var no_fixed_nav = $('#fix_onscroll').data('fixednav');
        var top_space = ($('.navbar-site').length>0) ? $('.navbar-site').innerHeight() : 0;
        if(no_fixed_nav == 'undefined' || no_fixed_nav == false){
            top_space = 0;
        }

        //second start top position & offset
        if($second_start_fix_select.length > 0 && !has_article_widget_fix){
            var fix_first_widget = true;
            var fix_second_widget = false;
            var second_start_fix_pos = $second_start_fix_select.position().top;
            var second_start_fix_offset = $second_start_fix_select.offset().top;
            var second_start = second_start_fix_offset - (top_space * 2);
            var only_in = $('#second_fix_onscroll').data('onlyin');
            var avoid_fix = true;
            if( only_in ){
                avoid_fix = false;
                if($('body').hasClass(only_in)){
	                avoid_fix = true;
	            }
            }
        }

        //sidebar default css
        var default_css = {
                position : 'relative',
                bottom : 'initial',
                top: 'initial'
            };
        //place the sidebar at the top css
        var place_top_css = {
                position : 'absolute',
                top: 0,
                bottom : 'auto'
            };
        //place the sidebar at the bottom css
        var place_bottom_css = {
                position : 'absolute',
                background : 'transparent',
                bottom : 0,
                top: 'auto'
            };

        // modifier le margin-top de la sidebar de mariefranceasia lorsque la sidebar est fixé.
        var margin_top_sidebar_fix = 0;
        if(typeof site_config_js.margin_top_sidebar_fix !== 'undefined'){
        	margin_top_sidebar_fix = parseInt(site_config_js.margin_top_sidebar_fix);
        	top_space+= margin_top_sidebar_fix;
        }
        //fix sidebar to top css
        var fix_css = {
                position : 'fixed',
                top : (top_space - $start_fix_select.position().top) + 'px',
                zIndex : 99,
                width : "inherit"
            };

        $(document).scroll(function(){
            window_width = $(window).width();
            scroll_pos = $(window).scrollTop();
            sidebar_list_item = $to_fix_select.innerHeight();

			//sidebar top offset
			sidebar_pos = $sidebar_select.offset().top;
			//ads position context : document
			// start_fix_offset = $start_fix_select.offset().top;
			//ads position context : parent
			start_fix_pos = $start_fix_select.position().top;
            
            //Calculate sidebar fix start point
            if(start_fix_offset>sidebar_pos){
                if(start_fix_offset != new_start_fix){
                    sidebar_start_fix = start_fix_offset - (top_space * 2);
                    first_start = sidebar_start_fix;
                    new_start_fix = $start_fix_select.offset().top;
                }

            }else{
                sidebar_start_fix = sidebar_pos - top_space + start_fix_pos;
            }

            if($second_start_fix_select.length > 0 && !has_article_widget_fix && avoid_fix){
                if(scroll_pos > second_start && fix_first_widget){
                    sidebar_start_fix = second_start;
                    fix_css = {
                        position : 'fixed',
                        top : top_space - second_start_fix_pos + 'px',
                        zIndex : 99,
                        width : "inherit"
                    };
                    fix_first_widget = false;
                    fix_second_widget = true;
                }

                if(scroll_pos < second_start && fix_second_widget){
                    sidebar_start_fix = first_start;
                    fix_css = {
                        position : 'fixed',
                        top : top_space - start_fix_pos + 'px',
                        zIndex : 99,
                        width : "inherit"
                    };
                    fix_first_widget = true;
                    fix_second_widget = false;
                }
            }
           
            //Check Content height
			if($left_wrapper_select.length > 0){
				left_wrapper_height = $left_wrapper_select.innerHeight();
				large_diapo_height = $large_diapo_select.innerHeight();
				//Check if left wraper height has changed in gallery articles
	            if(left_wrapper_height+large_diapo_height != content_height){
	            	content_height = left_wrapper_height;
	            	content_height += large_diapo_height;
	            }
	        }else{
                main_content_height = $main_content_select.innerHeight();
                if( is_se && $block_diapo.length > 0 ){
                    main_content_height += $block_diapo.innerHeight();
                }
	        	//Check if main height has changed
	        	if(main_content_height != content_height){
	            	content_height = main_content_height;
	        	}
	        }

            //update sidebar height if old content height has changed
            if(old_content_height != content_height){
                //disable fix scroll on small screen
                if(window_width > min_window_width && content_height > sidebar_height){
                    $sidebar_select.css({minHeight: content_height});
                    old_content_height = content_height;
		        }else{
		            $sidebar_select.css({minHeight: 'auto'});
		            $to_fix_select.css(default_css); 
		        }
	        }

            if( content_height <= sidebar_list_item && $sidebar_select.innerHeight() < sidebar_list_item ) {
                $sidebar_select.css({minHeight: sidebar_list_item});
            }

            //if(to_fix_height === 0){
            to_fix_height = $to_fix_select.innerHeight();
            //}

            //Check window width on scroll
            if(window_width > min_window_width && content_height > sidebar_height){

                if($second_start_fix_select.length > 0 && !has_article_widget_fix && avoid_fix){
                    //Calculate end of scroll for two widgets stop
                       end_scroll = (sidebar_pos + content_height) - (to_fix_height - second_start_fix_pos);
                }else{
                    //Calculate end of scroll for one widget stop
                    end_scroll = (sidebar_pos + content_height) - (to_fix_height - start_fix_pos);
                }

                if(scroll_pos < end_scroll){
                    if(scroll_pos > sidebar_start_fix){
                       $to_fix_select.css(fix_css);
                    }else{
                       $to_fix_select.css(place_top_css);
                    }               
               }else{
                    $to_fix_select.css(place_bottom_css); 
               }

            }else{
                $sidebar_select.css({minHeight: 'auto'});
                $to_fix_select.css(default_css); 
            }

        });//End Scroll
    }
});
})(jQuery);