$(document).ready(function () {
    shave_sizes_desktop();
})

function shave_sizes_desktop(){
    if( $.fn.shave ){
        $('.widget-area .most_popular .thumbnail-item .media .exposant_title').shave(60);
    }
}