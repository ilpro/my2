

$(function () {

    $('.categoryStripe').unbind().mkAdminCategory();

    // отслеживаем изменение hash
    $(window).unbind().bind('hashchange', function (e) {

        if (null != $.bbq.getState('category')) {
            $('#categoryInlay').mkAdminCategoryInlay({
                categoryId: parseInt(e.getState('category'))
            });
        }
        else {
            $('#categoryInlay').removeClass('show');
        }
    });

    // если категория уже задана
    if (null != $.bbq.getState('category')) {
        $(window).trigger('hashchange');
    }


    // создание категории

    $('#addCategory').unbind().bind('click', function () {

        $.ajax({
            type: "POST",
            url: '/admin/controllers/categories.controller.php',
            data: {addNewCategory: true}
        }).done(function (data) {

            if (null != data) {

                success_tip('Создано '+data.saved);
                $('#all-flavors').prepend(data);
                $('.categoryStripe').unbind().mkAdminCategory();
                $('.categoryStripe').first().find('.go').trigger('click');
            }

            else {
                error_tip('Ответ сервера не соответсвует ожидаемому');
            }
        }).fail(function () {
            error_tip('Ошибка сервера');
        });

    });

// удаление категории

    $('.trash').unbind().bind('click', function () {

        var newsids = [];

        $(".check :checked").each(function (e, elem) {

            obj = $(this).parent().parent().data('mkcategory');

            newsids.push(obj.categoryId);

            //console.log(newsids);

        })

        if (newsids.length > 0) {

            if (confirm("Удалить выбранные записи?")) {
                $.ajax({

                    type: "POST",
                    url: '/admin/controllers/categories.controller.php',
                    dataType: 'json',
                    data: {deleteCategory: newsids}

                }).done(function (data) {

                    if ('ok' == data.clean) {

                        success_tip('Выбранные категории удалены');


                        newsids.forEach(function (el) {

                            $('[data-mkcategory="{"categoryId":"' + el + '"}"]').remove();

                        })

                    }

                    else {
                        error_tip('Ответ сервера не соответсвует ожидаемому');
                    }
                }).fail(function () {
                    error_tip('Ошибка сервера');
                });

            }

        } else {
            error_tip('Нужно отметить хотя бы одну категорию');
        }

    });


    $(window).mkLogin();


});//Конец ready