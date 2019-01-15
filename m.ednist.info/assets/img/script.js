
function addLink() {
    //Get the selected text and append the extra info
    var selection = window.getSelection(),
        pagelink = '<br /><br /> '+addLinkText+' ' + document.location.href,
        copytext = selection + pagelink,
        newdiv = document.createElement('div');

    //hide the newly created container
    newdiv.style.position = 'absolute';
    newdiv.style.left = '-99999px';

    //insert the container, fill it with the extended text, and define the new selection
    document.body.appendChild(newdiv);
    newdiv.innerHTML = copytext;
    selection.selectAllChildren(newdiv);

    window.setTimeout(function () {
        document.body.removeChild(newdiv);
    }, 100);
}

document.addEventListener('copy', addLink);

$(document).ready(function(){

    $("#myCarousel").carousel({
        interval : 0
    });
    $("#myCarousel2").carousel({
        interval : 4700
    });
    $("#myCarousel4").carousel({
        interval : 3000
    });

    // image gallery
    baguetteBox.run('.gallery, .main-img-block', {
        // Custom options
    });

    $(document).mkLazyLoad();

//запуск сбора событий google analytics
        $(window).mkGa();

        //работа с капча
        $(window).mkCaptcha();
        var start_pos=$('.top-menus').offset().top;

        $(window).scroll(function(){
            if ($(window).scrollTop()>=start_pos) {
                if ($('.top-menus').hasClass('top')==false)
                    $('.top-menus').addClass('top');
            }
            else $('.top-menus').removeClass('top');
        });
    //анимация телефонов
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

    //реклама приложения
    $('.open-popup').click(function(){
        $('.cover, .pop-up').fadeIn(300);
    });
    $('.cover').click(function(){
        $('.cover, .pop-up').fadeOut(300);
    });
    //слайдеры
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

    //всплывающие оповещания
    var permission;
    Notification.requestPermission(function (state) {
        permission = state
    });
    getNewsId();
    function getNews() {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: '/controllers/news.controller.php',
            data: {
                action: 'getNewsNew'
            }
        })
            .done(function (obj) {
                show(obj);
            })
            .fail(function (obj) {
            });
    }

    function getNewsId() {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: '/controllers/news.controller.php',
            data: {
                action: 'getNewsNew'
            }
        })
            .done(function (obj) {
                setCookie('lastNews', obj[0].newsId);
            })
            .fail(function (obj) {
            });
    }

    function show(data) {
        var lastNews = getCookie('lastNews');
        console.log(lastNews);
        if(!lastNews || lastNews != data[0].newsId){
            Notification.requestPermission(newMessage);
            var mailNotification = new Notification(data[0].categoryName, {
                body: data[0].newsHeader,
                icon: data[0].images.main.big
            });

            mailNotification.onclick = function(){
                window.location.href = 'http://dev1.ednist.info/news/' + data[0].newsId;
            };

            function newMessage(permission) {
                if (permission != "granted") return false;
                mailNotification;
            }
            setCookie('lastNews', data[0].newsId);
        }
    }

    function setCookie(name, val){
        var date = new Date(new Date().getTime() + 60 * 60 * 1000);
        document.cookie = name + "=" + val + "; path=/; expires=" + date.toUTCString();
    }

    function getCookie(name) {
        var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    setInterval(function(){ getNews() }, 60000);

    flWidget({
        width: 300,
        height: 506,
        lang: 'ua',
        sVisits: 'false',
        autoLoad: 'true',
        sOnline: 'false',
        sort: 'likes'
    });
});//END of doc.ready