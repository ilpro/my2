$(function () {

    $('.themesStripe').unbind().mkAdminthemes();

    $(window).unbind().bind('hashchange', function (e) {//навигация и история
        if (null != $.bbq.getState('themes')) {
            $('#themesInlay').mkAdminThemesInlay({themesId: parseInt(e.getState('themes'))});
        }
        else if (null != $.bbq.getState('find')) {

                var find = $.bbq.getState('find');
                
                getSearchThemes(find);
        }
        else {
            $('#themesInlay').removeClass('show');
        }
    });

    if (null != $.bbq.getState('themes'))
        $(window).trigger('hashchange');
     //если уже задано поиск (после обновления страници)
    if ($.bbq.getState('find')!=null) {
        $(window).trigger('hashchange');
    }


    $('#newsGallery').mkUpload({inputName: 'themesimgfile[]', multiple: false});
    $('#imgAddFromPath').click(function () {
        $(this).next().trigger('click');
    });

    // создание бренда
    $('#addThemes').unbind().bind('click', function () {

        $.ajax({
            type: "POST",
            url: '/admin/controllers/themes.controller.php',
            //dataType:'json',
            data: {addNewThemes: true}
        }).done(function (data) {
            if (null != data) {

                success_tip('Создано '+data.saved);
                $('#all-flavors').prepend(data);
                $('.themesStripe').unbind().mkAdminthemes();
                $('.themesStripe').first().find('.go').trigger('click');
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
            obj = $(this).parent().parent().data('mkthemes');
            newsids.push(obj.themesId);
            console.log(newsids);
        })
        if (newsids.length > 0) {
            if (confirm("Удалить выбранные записи?")) {

                $.ajax({
                    type: "POST",
                    url: '/admin/controllers/themes.controller.php',
                    dataType: 'json',
                    data: {deleteThemes: newsids}
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
                            $('[data-mkthemes="{"themesId":"' + el + '"}"]').remove();
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
function getSearchThemes(find){
    //здесь ловим сбрасивания поиска и удаляем хеш
    if(find==''){
          $.bbq.removeState(['find']);
      }
    $.ajax({
        type: "GET",
        url: '/admin/controllers/themes.controller.php',
        dataType: 'html',
        data: {search: 'search',find:find}
    }).done(function (data) {
         if (null != data) {
            $('#all-flavors').html(data);
            //обнуляем строку поиска
            $('#tags').val('');
            success_tip('Пошук удачно закончен');
            $('.themesStripe').unbind().mkAdminthemes();
        }
        else {
            error_tip('Ответ сервера не соответсвует ожидаемому');
        } 
    }).fail(function () {
        error_tip('Ошибка сервера');
    });
}