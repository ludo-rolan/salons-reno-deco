$(document).ready(function () {
    shave_sizes_desktop();
    $('#submit_nl').click(function(){
        email_nl = $('#email_nl').val();
        location.href = "https://mondial.paris/inscription-newsletter?email_newsletter="+ email_nl;
    });
})

function shave_sizes_desktop(){
    if( $.fn.shave ){
        $('.widget-area .most_popular .thumbnail-item .media .exposant_title').shave(60);
    }
}
