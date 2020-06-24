$('.banner-slider').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    dots:false,
    autoplay:true,
	autoplayTimeout:4000,
	autoplayHoverPause:true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:1
        }
    }
});
//wow init
new WOW().init();
//How It Work Slider
// $('.how-work-slider-img').slick({
//         slidesToShow: 1,
//         slidesToScroll: 1,
//         fade: true,
//         infinite: false,
//         arrows: true,
//         asNavFor: '.how-work-slider'
// });
// $('.how-work-slider').slick({
//         slidesToShow: 3,
//         slidesToScroll: 1,
//         asNavFor: '.how-work-slider-img',
//         vertical: true,
//         arrows: false,
//         verticalSwiping: true,
//         dots: false,
//         centerMode: false,
//         infinite: false,
//         centerPadding: '0px',
//         focusOnSelect: true
// });

$('.screenshot-slider-section').owlCarousel({
    loop:true,
    margin:30,
    nav:true,
    dots:false,
    autoplay:true,
    autoplayTimeout:3000,
    autoplayHoverPause:false,
    responsive:{
        0:{
            items:1
        },
        601:{
            items:3
        },
        992:{
            items:5
        }
    }
});
    
    $(window).scroll(function() {
var scroll = $(window).scrollTop();
if (scroll >= 50) {
$(".header-inner").addClass("darkHeader");
}
else{
$(".header-inner").removeClass("darkHeader");
}
});

 $(document).ready(function () {
            //Smooth scrolling when click to nav
            $('.snip1226 > li > a.menu-link').click(function (e) {
                e.preventDefault();
                var curLink = $(this);
                var scrollPoint = $(curLink.attr('href')).position().top - 50;
                $('body,html').animate({
                    scrollTop: scrollPoint
                }, 500);
            })

            $(window).scroll(function () {
                onScrollHandle();
            });

            function onScrollHandle() {

                //Get current scroll position
                var currentScrollPos = $(document).scrollTop();

                //Iterate through all node
                $('.snip1226 > li > a').each(function () {
                    var curLink = $(this);
                    var refElem = $(curLink.attr('href'));
                    //Compare the value of current position and the every section position in each scroll
                    if (refElem.position().top <= currentScrollPos+60 && refElem.position().top + refElem.height() > currentScrollPos+60) {
                        //Remove class active in all nav
                        $('.snip1226 > li').removeClass("current");
                        //Add class active
                        curLink.parent().addClass("current");
                    }
                    else {
                        curLink.parent().removeClass("current");
                    }
                });
            }
        });
