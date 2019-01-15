$(window).on('load', function() {
    $('.preloader-screen').find('.preloader-element').fadeOut().end().delay(400).fadeOut('slow');
});

$(".hamburger, .side-menu-close-cover").bind("tap", function () {
	$('body').toggleClass('sidemenu-opened');
});

$(".info-show").click(function(){
    $(".dropdown-menu.info").toggleClass("active");
});