$(document).ready(function(){
    var speed = 450;
    $('.left-phone').hover(function(){
        animStart($(this), '-160px');
    },
    function(){
        animStop($(this), '-110px');
    });

    $('.right-phone').hover(function(){
        animStart($(this), '926px');
    },
    function(){
        animStop($(this), '876px');
    });

    function animStart(elem, left){
        elem.stop(true).animate({'margin-left' : left}, speed ,'swing');
        var span = elem.find('span');
        span.stop(true).animate({
            'background-size' : '100%',
            'opacity' : '1'
        }, speed, 'swing');
    }

    function animStop(elem,left){
        elem.stop(true).animate({'margin-left' : left}, speed, 'swing');
        var span = elem.find('span');
        span.stop(true).animate({
            'background-size' : '70%',
            'opacity' : '0.7'
        }, speed, 'swing');
    }
});