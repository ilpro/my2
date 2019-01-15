$(function () {

    $('.regionStripe').unbind().mkAdminregion();


    $(window).bind('hashchange', function (e) {//навигация и история

        if (null != $.bbq.getState('region')) {

            $('#regionInlay').mkAdminRegionInlay({regionId: parseInt(e.getState('region'))});

        }
        else if (null != $.bbq.getState('find')) {

                var find = $.bbq.getState('find');
                
                getSearchRegions(find);
        }

        else {

            $('#regionInlay').removeClass('show');

        }

    });
     //если уже задано поиск (после обновления страници)
    if ($.bbq.getState('find')!=null) {
        $(window).trigger('hashchange');
    }

    $(window).trigger('hashchange');

    $('#newsGallery').mkUpload({inputName: 'regionimgfile[]', multiple: false});

    $('#imgAddFromPath').click(function () {

        $(this).next().trigger('click');

    });


    // создание бренда

    $('#addRegion').unbind().bind('click', function () {

        $('#condition').mkCondition({action: 'blink', color: 'yellow'});

        $.ajax({

            type: "POST",

            url: '/admin/controllers/regions.controller.php',

            //dataType:'json',

            data: {addNewRegion: true}

        }).done(function (data) {

            if (null != data) {

                $('#condition').mkCondition({
                    condition: 'tooltip',
                    text: data.saved,
                    action: 'fade',
                    action: 'fade',
                    color: 'green',
                    fadeout: 1000
                });

                $('#all-flavors').prepend(data);

                $('.regionStripe').unbind().mkAdminregion();

                $('.regionStripe').first().find('.go').trigger('click');

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

            obj = $(this).parent().parent().data('mkregion');

            newsids.push(obj.regionId);

            console.log(newsids);

        })

        if (newsids.length > 0) {

            if (confirm("Удалить выбранные записи?")) {

                $('#condition').mkCondition({action: 'blink', color: 'yellow'});

                $.ajax({

                    type: "POST",

                    url: '/admin/controllers/regions.controller.php',

                    dataType: 'json',

                    data: {deleteRegion: newsids}

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

                            $('[data-mkregion="{"regionId":"' + el + '"}"]').remove();

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


//поиск по брендам
function getSearchRegions(find){
    //здесь ловим сбрасивания поиска и удаляем хеш
    if(find==''){
          $.bbq.removeState(['find']);
      }
    $.ajax({
        type: "GET",
        url: '/admin/controllers/regions.controller.php',
        dataType: 'html',
        data: {search: 'search',find:find}
    }).done(function (data) {
         if (null != data) {
            $('#all-flavors').html(data);
            //обнуляем строку поиска
            $('#regions').val('');
                success_tip('Пошук удачно закончен');
            $('.regionStripe').unbind().mkAdminregion();
        }
        else {
            error_tip('Ответ сервера не соответсвует ожидаемому');
        } 
    }).fail(function () {
        error_tip('Ошибка сервера');
    });
}