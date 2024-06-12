jQuery(document).ready(function() {
	function bindEvent(element, eventName, eventHandler) {
	    if (element.addEventListener) {
	        element.addEventListener(eventName, eventHandler, false);
	    }
	}
    // Parse the URL and get Param by name
    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&amp;]" + name + "=([^&amp;#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }
    //get the utm_source
    var source = getParameterByName('utm_source');
    //create cookie if user is coming from NL or Mail
    if (source == "EMAIL" || source == "EMV") {
        jQuery.cookie("nl_cookie", 1, {
            expires: 365,
            path: "/"
        });
    }

    bindEvent(window, "message", function(e) {
        if (e.data == "sub_nl_cookie") {
            jQuery.cookie("nl_cookie", 1, {
                expires: 365,
                path: "/"
            });
        } else if (e.data == "close_sticky_footer") {
            jQuery("#newsletter_bar, .newsletter-inscription-iframe").hide("slow");
        }
    });

    var cookieValue = $.cookie("nl_cookie", {
        path: "/"
    });
    if (!cookieValue) {
        jQuery("#newsletter_bar, .newsletter-inscription-iframe").removeClass("hidden");
    }
});