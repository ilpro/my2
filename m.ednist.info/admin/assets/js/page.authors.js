$(function () {

    $('.authorStripe').unbind().mkAdminauthor();


    $(window).bind('hashchange', function (e) {//навигация и история

        if (null != $.bbq.getState('author')) {
            $('#authorInlay').mkAdminAuthorInlay({authorId: parseInt(e.getState('author'))});
        }

        else {
            $('#authorInlay').removeClass('show');
        }

    });

    $(window).trigger('hashchange');

    $('#newsGallery').mkUpload({inputName: 'authorimgfile[]', multiple: false});

    $('#imgAddFromPath').click(function () {

        $(this).next().trigger('click');

    });


    // создание бренда

    $('#addAuthor').unbind().bind('click', function () {

        $('#condition').mkCondition({action: 'blink', color: 'yellow'});

        $.ajax({

            type: "POST",

            url: '/admin/controllers/authors.controller.php',

            //dataType:'json',

            data: {addNewAuthor: true}

        }).done(function (data) {

            if (null != data) {
//@@todo change to success tip
                $('#condition').mkCondition({
                    condition: 'tooltip',
                    text: data.saved,
                    action: 'fade',
                    action: 'fade',
                    color: 'green',
                    fadeout: 1000
                });

                $('#all-flavors').prepend(data);

                $('.authorStripe').unbind().mkAdminauthor();

                $('.authorStripe').first().find('.go').trigger('click');

            }

            else {
//@@todo change to success tip

                $('#condition').mkCondition({
                    condition: 'tooltip',
                    text: 'Ответ сервера не соответсвует ожидаемому',
                    action: 'show',
                    color: 'red'
                });

            }

        }).fail(function () {
//@@todo change to success tip

            $('#condition').mkCondition({condition: 'tooltip', text: 'Ошибка сервера', action: 'show', color: 'red'});

        });

    });

// удаление бренда

    $('.trash').unbind().bind('click', function () {

        var newsids = [];

        $(".check :checked").each(function (e, elem) {

            obj = $(this).parent().parent().data('mkauthor');

            newsids.push(obj.authorId);

            console.log(newsids);

        })

        if (newsids.length > 0) {

            if (confirm("Удалить выбранные записи?")) {

                $('#condition').mkCondition({action: 'blink', color: 'yellow'});

                $.ajax({

                    type: "POST",

                    url: '/admin/controllers/authors.controller.php',

                    dataType: 'json',

                    data: {deleteAuthor: newsids}

                }).done(function (data) {

                    if ('ok' == data.clean) {
//@@todo change to success tip

                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: "Выбранные бренды удалены",
                            action: 'fade',
                            color: 'green',
                            fadeout: 2000
                        });


                        newsids.forEach(function (el) {

                            $('[data-mkauthor="{"authorId":"' + el + '"}"]').remove();

                        })

                    }

                    else {
//@@todo change to success tip

                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Не удалось удалить выбранные бренды',
                            action: 'show',
                            color: 'red'
                        });

                    }

                }).fail(function () {
//@@todo change to success tip

                    $('#condition').mkCondition({
                        condition: 'tooltip',
                        text: 'Ошибка сервера',
                        action: 'show',
                        color: 'red'
                    });

                });

            }

        } else {
//@@todo change to success tip

            $('#condition').mkCondition({
                condition: 'tooltip',
                text: 'Нужно отметить хотя бы один бренд',
                action: 'fade',
                color: 'red',
                fadeout: 2500
            });

        }

    });


    // всплывашки

    ;
    $(function () {

        p = $('.popap__overlay')

        p.click(function (event) {

            e = event || window.event

            if (e.target == this) {

                $(p).css('display', 'none')

            }

        })

        $('.popap__overlay .close').bind('click', function () {

            p.css('display', 'none');

            return false;

        });

    });

    $(window).mkLogin();

});//Конец ready