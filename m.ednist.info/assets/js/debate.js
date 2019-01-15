$(document).ready(function(){

    // debates timer
    var end = new Date('11/12/2015 8:00 PM');

    var _second = 1000;
    var _minute = _second * 60;
    var _hour = _minute * 60;
    var _day = _hour * 24;
    var timer;

    function showRemaining() {
        var now = new Date();
        var distance = end - now;
        if (distance < 0) {
            clearInterval(timer);
            document.getElementById('timeout').style.display = 'none';
            getDebate();
            return;
        }
        document.getElementById('timeout').style.display = 'block';
        document.getElementById('debates').style.display = 'none';
        var days = Math.floor(distance / _day);
        var hours = Math.floor((distance % _day) / _hour);
        var minutes = Math.floor((distance % _hour) / _minute);
        var seconds = Math.floor((distance % _minute) / _second);

        document.getElementById('timer').innerHTML = '';
        if(days > 0)
            document.getElementById('timer').innerHTML += '<div class="pull-left"><div>' + days + ': </div><div class="word">дні</div></div>';
        document.getElementById('timer').innerHTML += '<div class="pull-left"><div>' + hours + ': </div><div class="word">год.</div></div>';
        document.getElementById('timer').innerHTML += '<div class="pull-left"><div>' + minutes + ': </div><div class="word">хв.</div></div>';
        document.getElementById('timer').innerHTML += '<div class="pull-left"><div>' + seconds + '</div><div class="word">сек.</div></div>';
    }

    timer = setInterval(showRemaining, 1000);
    //setTimeout(function(){ $('#debate_5').trigger('click'); }, 500);

    function getDebate(){
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: '/debate/getDebate'
        })
            .done(function (obj) {
                $('#debates').append(obj).show();
                $('#debate_5').trigger('click');
                $('.debate-content').find('.question').click(function(){
                    var row = $(this).parent(),
                        openQuestion = $('.open-question');
                    if(!row.hasClass('open-question')){
                        openQuestion.removeClass('open-question').find('.answer').slideUp(150);
                        row.addClass('open-question');
                        row.find('.answer').slideDown(150);
                        setTimeout( function(){ $('html, body').animate({ scrollTop: row.offset().top - 100 }, 150);}, 150);
                    }
                    else{
                        $(this).parent().removeClass('open-question').find('.answer').slideUp(300);
                    }
                });
            })
            .fail(function (obj) {
            });
    }

    $('.debate-list').find('a').click(function () {
        var id = $(this).attr('id'),
            button = $(this),
            container = $(this).parent().parent(),
            tab = $('#' + id + '_tab');
        if(!button.hasClass("active-debate")){
            button.parent().find(".active-debate").removeClass("active-debate");
            button.addClass("active-debate");
            container.find('.active-debate-container').removeClass('active-debate-container');
            tab.addClass('active-debate-container');
            //tab.find('.debate-body-row:first-child').find('.question').trigger('click');
        }
    });

});