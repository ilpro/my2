function mkTitleInitialize() {

    $('.mkTitle').each(function () {

        var element = $('.mkTitle_element', this);

        var dir = element.attr('dir');

        var width = parseInt(element.attr('width'));
        if (!width)width = 200;

        var eWidth = parseInt(element.width());

        var offset = (eWidth - width) / 2;

        var text = element.attr('mkTitle');
        if (text == '')return;

        var myString = "<div class='mkTitle_vertical w" + width + "' direction='up'><div class='top'></div><div class='mkTitle_text'>" + text + "</div><div class='bottom'></div><div class='arrowDown'></div></div>";

        switch (dir) {

            case 'up':

                myString = "<div class='mkTitle_vertical w" + width + "' direction='up'><div class='top'></div><div class='mkTitle_text'>" + text + "</div><div class='bottom'></div><div class='arrowDown'></div></div>";

                break;


            case 'down':

                myString = "<div class='mkTitle_vertical w" + width + "' direction='down'><div class='arrowUp'></div><div class='top'></div><div class='mkTitle_text'>" + text + "</div><div class='bottom'></div></div>";

                break;


            case 'right':

                myString = "<div class='mkTitle_horizontal w" + width + "' direction='right'><div class='arrowLeft'></div><div class='con_title'><div class='top'></div><div class='mkTitle_text'>" + text + "</div><div class='bottom'></div></div></div>";

                break;

        }

        element.after(myString);

        $('.mkTitle_vertical', this).css({left: offset});

    })

    //--------Проявить подсказки для элементов обязательных для заполнения

    $('.importantTitle').each(function () {

        if ($(this).attr('value') == '') {

            mkTitleShow($(this).parent().next());

        }

    });


}


function mkTitleShow(title) {

    var direction = title.attr('direction');

    if (direction == 'up') {

        title.stop().css({'margin-bottom': '40px', opacity: '0', zIndex: '50'}).animate({
            'margin-bottom': '0',
            opacity: '1'
        }, 500);

    }

    if (direction == 'down') {

        title.stop().css({'top': '170%', opacity: '0', zIndex: '50'}).animate({'top': '100%', opacity: '1'}, 500);

    }

    else if (direction == 'right') {

        title.stop().css({'left': '170%', opacity: '0', zIndex: '50'}).animate({'left': '100%', opacity: '1'}, 500);

    }

}

function mkTitleHide(title) {

    var direction = title.attr('direction');

    if (direction == 'up') {

        title.stop().animate({'margin-bottom': '40px', opacity: '0'}, 500, "linear", function () {
            title.css({zIndex: '-1'})
        });

    }

    else if (direction == 'down') {

        title.stop().animate({'top': '170%', opacity: '0'}, 500, "linear", function () {
            title.css({zIndex: '-1'})
        });

    }

    else if (direction == 'right') {

        title.stop().animate({'left': '170%', opacity: '0'}, 500, "linear", function () {
            title.css({'left': '100%', zIndex: '-1'})
        });

    }


}