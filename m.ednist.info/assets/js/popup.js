$(document).ready(function(){
    $('.open-popup').click(function(){
        $('.cover, .pop-up').fadeIn(300);
    });
    $('.cover').click(function(){
        $('.cover, .pop-up').fadeOut(300);
    });
});