jQuery(document).ready(function ($) {

    function promo_media_upload(button_class, i) {
        var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;
        $('body').on('click', button_class, function (e) {
            var button_id = '#' + $(this).attr('id');
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = $(button_id);
            _custom_media = true;
            wp.media.editor.send.attachment = function (props, attachment) {
                if (_custom_media) {
                    console.log('#promo_option_img_' + i);
                    $('#promo_option_img_' + i).val(attachment.id);
                    $('#promo_option_wrapper_' + i).html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                    $('#promo_option_wrapper_' + i + ' .custom_media_image').attr('src', attachment.url).css('display', 'block');
                } else {
                    return _orig_send_attachment.apply(button_id, [props, attachment]);
                }
            }
            wp.media.editor.open(button);
            return false;
        });
    }
    function promo_media_remove(i) {
        $('body').on('click', '.ct_tax_media_remove_' + i, function () {
            $('#promo_option_img_' + i).val('');
            $('#promo_option_wrapper_' + i).html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
        });
        $(document).ajaxComplete(function (event, xhr, settings) {
            var queryStringArr = settings.data.split('&');
            if ($.inArray('action=add-tag', queryStringArr) !== -1) {
                var xml = xhr.responseXML;
                $response = $(xml).find('term_id').text();
                if ($response != "") {
                    // Clear the thumb image
                    $('#promo_option_wrapper_' + i).html('');
                }
            }
        });
    }
    promo_media_upload('.ct_tax_media_button_1.button', 1);
    promo_media_upload('.ct_tax_media_button_2.button', 2);
    promo_media_remove(1);
    promo_media_remove(2);
    $('input[name="promo_option_date"]').width("300px");


    $('input[name="promo_option_date"]').daterangepicker({
        minDate: new Date(2021, 10),
        opens: 'left'
    }, function (start, end, label) {
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });


});
