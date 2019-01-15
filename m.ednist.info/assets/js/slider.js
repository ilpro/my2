$("#myCarousel").carousel({
    interval : 0
});
$("#myCarousel2").carousel({
    interval : 4700
});
$("#myCarousel4").carousel({
    interval : 3000
});

function sliders(){
    var totWidth1= 0;
    var positions1 = new Array();
    $('#slideshow1 .slides li').each(function(i){
        positions1[i]= totWidth1;
        totWidth1 += $(this).width();
    });
    $('#slideshow1').find('.slides').width(totWidth1);
    var cur_1 = 0;
    $('#slideshow1 .prev').click(function(){
        cur_1--;
        cur_1 = (cur_1 < 0) ? 2 : cur_1;
        $('#slideshow1 .slides').stop().animate({marginLeft:-positions1[cur_1]+'px'}, 600, 'easeOutCubic');
    });
    $('#slideshow1 .next').click(function(){
        cur_1++;
        cur_1 = (cur_1 > 2) ? 0 : cur_1;
        $('#slideshow1 .slides').stop().animate({marginLeft:-positions1[cur_1]+'px'}, 600, 'easeOutCubic');
    });

    var totWidth2= 0;
    var positions2 = new Array();
    $('#slideshow2 .slides li').each(function(i){
        positions2[i]= totWidth2;
        totWidth2 += $(this).width();
    });
    $('#slideshow2').find('.slides').width(totWidth1);
    var cur_2 = 0;
    $('#slideshow2 .prev').click(function(){
        cur_2--;
        cur_2 = (cur_2 < 0) ? 2 : cur_2;
        $('#slideshow2 .slides').stop().animate({marginLeft:-positions2[cur_2]+'px'}, 600, 'easeOutCubic');
    });
    $('#slideshow2 .next').click(function(){
        cur_2++;
        cur_2 = (cur_2 > 2) ? 0 : cur_2;
        $('#slideshow2 .slides').stop().animate({marginLeft:-positions2[cur_2]+'px'}, 600, 'easeOutCubic');
    });
}
sliders();