
function fetch() {
    if (jQuery('#keyword').val() && jQuery('#keyword').val().length > 2) {
        jQuery.ajax({
            url: data.ajax_url,
            type: 'post',
            data: {
                action: 'data_fetch',
                keyword: jQuery('#keyword').val(),
                type: jQuery('#type').val()
            },
            success: function(data) {
                jQuery('#datafetch').html(data);
            }
        });
    } else if (jQuery('#keyword').val() && jQuery('#keyword').val().length == 0) {
        jQuery('#datafetch').html("Résultats de recherche apparaîtront ici ...");
    }

}

function copy_paste_to_clipboard(id) {
    var $temp = jQuery("<input>");
    jQuery("body").append($temp);
    $temp.val(id).select();
    document.execCommand("copy");
    $temp.remove();
}