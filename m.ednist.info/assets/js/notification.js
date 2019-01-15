window.onload = function () {
    var permission;
    Notification.requestPermission(function (state) {
        permission = state
    });
    getNewsId()
};

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