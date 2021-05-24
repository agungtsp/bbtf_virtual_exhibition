$(document).ready(function(){
    $('.owl-index').owlCarousel({
        loop: true,
        margin: 0,
        items: 1,
        nav: false,
        dots: true,
        dotsData: true,
        autoplay: true,
    });
    $('.owl-platinum').owlCarousel({
        loop: true,
        margin: 0,
        items: 1,
        nav: false,
        autoplay: true,
    });
    $('.owl-logo').owlCarousel({
        loop: false,
        margin: 16,
        autoplay: true,
        nav: true,
        navText:["<div class='nav-btn prev-slide'></div>","<div class='nav-btn next-slide'></div>"],
        responsive: {
            0: {
                items:5
            },
            600: {
                items:10
            },
            1000: {
                items:12
            }
        }
    });
    $('[data-title="tooltip"]').tooltip();
});
