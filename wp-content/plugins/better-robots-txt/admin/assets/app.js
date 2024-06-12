jQuery(document).ready(function () {
    jQuery('.rt-alert').on('click', '.closebtn', function () {
        jQuery(this).closest('.rt-alert').fadeOut(); //.css('display', 'none');
    });
    jQuery('.rt-notice button.notice-dismiss').text("dismiss");

    jQuery('.rt-boost-alt-label input').on('click', function() { 
        jQuery('.rt-boost-alt').slideToggle();
    });
    jQuery('.rt-backlinks-label input').on('click', function() { 
        jQuery('.rt-backlinks').slideToggle(); 
    });
    jQuery('.rt-mobi-label input').on('click', function() { 
        jQuery('.rt-mobi').slideToggle();
    });
    jQuery('.rt-bigta-label input').on('click', function() { 
        jQuery('.rt-bigta').slideToggle();
    });
    jQuery('.rt-meta-label input').on('click', function() { 
        jQuery('.rt-meta').slideToggle();
    });
    jQuery('.rt-vidseo-label input').on('click', function() { 
        jQuery('.rt-vidseo').slideToggle();
    });
    jQuery('.rt-multisite-label input').on('click', function() { 
        jQuery('.rt-multisite').slideToggle(); 
    });

    if (jQuery('#sitemap').val() == "custom") {
        jQuery('#sitemap_file').show();
    }
    // console.log(jQuery('#sitemap').val())

    jQuery('#sitemap').on('change', function() { 
        if (this.value == "custom") {
            jQuery('#sitemap_file').show();
        } else {
            jQuery('#sitemap_file').hide();
        }
    });

    jQuery('.rt-growth').masonry({
        // options
        itemSelector: 'ul',
        //horizontalOrder: true
    });

    jQuery(window).scroll(function() {
        if (jQuery(document).scrollTop() > 700) {
            jQuery(".rt-submit").addClass("enabled");
        } else {
            jQuery(".rt-submit").removeClass("enabled");
        }
    });

    jQuery("#fs_connect button[type=submit]").on("click", function(e) {
        console.log("open verify window")
        window.open('https://better-robots.com/subscribe.php?plugin=better-robots','better-robots','resizable,height=400,width=700');
    });

});
