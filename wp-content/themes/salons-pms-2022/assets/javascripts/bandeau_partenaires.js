$(document).ready(function () {
    $('.bandeau_partners_logos').slick({
        infinite: true,
        dots: true,
        arrows: false,
        autoplay: true,
	    autoplaySpeed: 3000,
        slidesToShow: 7,
        slidesToScroll: 7,
        responsive: [
            {
              breakpoint: 992,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: true
              }
            },
            {
              breakpoint: 768,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 2
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
          ]
    });
});
$(document).ready(function(){
  $('.carousel-item').slick({
  slidesToShow: 2,
  // infinite: fale,
  // dots:true,
  centerMode: true,
  prevArrow:"<button  class='slick-prev'><i class='fa fa-arrow-left' aria-hidden='true'></i></button>",
  nextArrow:"<button  class='slick-next'><i class='fa fa-arrow-right' aria-hidden='true'></i></button>"
  });
});
