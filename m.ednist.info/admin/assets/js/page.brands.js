$(function () {

    $('.brandStripe').unbind().mkAdminbrand();

    $(window).unbind().bind('hashchange', function (e) {//навигация и история
        if (null != $.bbq.getState('brand')) {
            $('#brandInlay').mkAdminBrandInlay({brandId: parseInt(e.getState('brand'))});
        }
        else if (null != $.bbq.getState('find')) {

                var find = $.bbq.getState('find');
                
                getSearchBrands(find);
        }
        else {
            $('#brandInlay').removeClass('show');
        }
    });

    if (null != $.bbq.getState('brand'))
        $(window).trigger('hashchange');
     //если уже задано поиск (после обновления страници)
    if ($.bbq.getState('find')!=null) {
        $(window).trigger('hashchange');
    }


    $('#newsGallery').mkUpload({inputName: 'brandimgfile[]', multiple: false});
    $('#imgAddFromPath').click(function () {
        $(this).next().trigger('click');
    });

    // создание бренда
    $('#addBrand').unbind().bind('click', function () {

        $.ajax({
            type: "POST",
            url: '/admin/controllers/brands.controller.php',
            //dataType:'json',
            data: {addNewBrand: true}
        }).done(function (data) {
            if (null != data) {

                success_tip('Создано '+data.saved);
                $('#all-flavors').prepend(data);
                $('.brandStripe').unbind().mkAdminbrand();
                $('.brandStripe').first().find('.go').trigger('click');
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
            obj = $(this).parent().parent().data('mkbrand');
            newsids.push(obj.brandId);
            console.log(newsids);
        })
        if (newsids.length > 0) {
            if (confirm("Удалить выбранные записи?")) {

                $.ajax({
                    type: "POST",
                    url: '/admin/controllers/brands.controller.php',
                    dataType: 'json',
                    data: {deleteBrand: newsids}
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
                            $('[data-mkbrand="{"brandId":"' + el + '"}"]').remove();
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
function getSearchBrands(find){
    //здесь ловим сбрасивания поиска и удаляем хеш
    if(find==''){
          $.bbq.removeState(['find']);
      }
    $.ajax({
        type: "GET",
        url: '/admin/controllers/brands.controller.php',
        dataType: 'html',
        data: {search: 'search',find:find}
    }).done(function (data) {
         if (null != data) {
            $('#all-flavors').html(data);
            //обнуляем строку поиска
            $('#tags').val('');
            success_tip('Пошук удачно закончен');
            $('.brandStripe').unbind().mkAdminbrand();
        }
        else {
            error_tip('Ответ сервера не соответсвует ожидаемому');
        } 
    }).fail(function () {
        error_tip('Ошибка сервера');
    });
}