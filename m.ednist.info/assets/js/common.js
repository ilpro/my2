$(".hamburger, .side-menu-close-cover").bind("tap", function () {
    $('body').toggleClass('sidemenu-opened');
});

$('.slider').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: false,
  dots: true
});

