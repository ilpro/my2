$(function () {

    $('.tagStripe').unbind().mkAdmintag();


    $(window).bind('hashchange', function (e) {//навигация и история

        if (null != $.bbq.getState('tag')) {

            $('#tagInlay').mkAdminTagInlay({tagId: parseInt(e.getState('tag'))});

        }
         else if (null != $.bbq.getState('find')) {

                var find = $.bbq.getState('find');
                
                getSearchTags(find);
        }

        else {

            $('#tagInlay').removeClass('show');

        }

    });
    
    if (null != $.bbq.getState('tag'))
        $(window).trigger('hashchange');
     //если уже задано поиск (после обновления страници)
    if ($.bbq.getState('find')!=null) {
        $(window).trigger('hashchange');
    }
    $(window).trigger('hashchange');

    $('#newsGallery').mkUpload({inputName: 'tagimgfile[]', multiple: false});

    $('#imgAddFromPath').click(function () {

        $(this).next().trigger('click');

    });


    // создание бренда

    $('#addTag').unbind().bind('click', function () {

        $.ajax({

            type: "POST",

            url: '/admin/controllers/tags.controller.php',

            //dataType:'json',

            data: {addNewTag: true}

        }).done(function (data) {

            if (null != data) {

                console.log(data);

                $('#all-flavors').prepend(data);

                $('.tagStripe').unbind().mkAdmintag();
                $('.tagStripe').first().find('.go').trigger('click');
            }

            else {

                $('#condition').mkCondition({
                    condition: 'tooltip',
                    text: 'Ответ сервера не соответсвует ожидаемому',
                    action: 'show',
                    color: 'red'
                });

            }

        }).fail(function () {

            $('#condition').mkCondition({condition: 'tooltip', text: 'Ошибка сервера', action: 'show', color: 'red'});

        });

    });

// удаление бренда

    $('.trash').unbind().bind('click', function () {

        var newsids = [];

        $(".check :checked").each(function (e, elem) {

            obj = $(this).parent().parent().data('mktag');

            newsids.push(obj.tagId);

            console.log(newsids);

        })

        if (newsids.length > 0) {

            if (confirm("Удалить выбранные записи?")) {

                $.ajax({

                    type: "POST",

                    url: '/admin/controllers/tags.controller.php',

                    dataType: 'json',

                    data: {deleteTag: newsids}

                }).done(function (data) {

                    if ('ok' == data.clean) {

                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: "Выбранные бренды удалены",
                            action: 'fade',
                            color: 'green',
                            fadeout: 2000
                        });


                        newsids.forEach(function (el) {

                            $('[data-mktag="{"tagId":"' + el + '"}"]').remove();

                        })

                    }

                    else {

                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Не удалось удалить выбранные бренды',
                            action: 'show',
                            color: 'red'
                        });

                    }

                }).fail(function () {

                    $('#condition').mkCondition({
                        condition: 'tooltip',
                        text: 'Ошибка сервера',
                        action: 'show',
                        color: 'red'
                    });

                });

            }

        } else {

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

//поиск по тегам
function getSearchTags(find){
    //здесь ловим сбрасивания поиска и удаляем хеш
    if(find==''){
          $.bbq.removeState(['find']);
      }
    $.ajax({
        type: "GET",
        url: '/admin/controllers/tags.controller.php',
        dataType: 'html',
        data: {search: 'search',find:find}
    }).done(function (data) {
         if (null != data) {
            $('#all-flavors').html(data);
            //обнуляем строку поиска
            $('#tags').val('');
            success_tip('Пошук удачно закончен');
            $('.tagStripe').unbind().mkAdmintag();
        }
        else {
            error_tip('Ответ сервера не соответсвует ожидаемому');
        } 
    }).fail(function () {
        error_tip('Ошибка сервера');
    });
}