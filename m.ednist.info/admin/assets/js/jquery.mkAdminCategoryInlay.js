/*! Copyright (c) 2014 Max Kulyaev (Максим Куляев) 1audiodesign@gmail.com

 * Licensed under the MIT License (LICENSE.txt).

 * jquery.mkAdmincategoryInlay

 * Version: 1.0

 */

;
(function ($, window, document, undefined) {

    var pluginName = 'mkAdminCategoryInlay',

        defaults = {

            action: 'ajax',

            categoryId: 0,

            categoryDesc: ''

        };

    // конструктор плагина


    function Plugin(element, options) {

        var widget = this;


        widget.element = element;

        widget.metadata = widget.element.data('mkcategory');

        widget._defaults = defaults;

        widget.config = $.extend({}, defaults, options, widget.metadata);

        widget._name = pluginName;

        $.each(widget.config, function (key, val) {

            if (typeof val === 'function') {

                widget.element.one(key + '.' + widget._name, function () {
                    return val(widget.element)
                });

            }

        });

        this.init();

    }

    Plugin.prototype.showcategoryInlay = function () {

        var widget = this;

        if (!$('#categoryInlay').is(':visible')) {

            $('#categoryInlay').addClass('show');

        }

    };//END OF Plugin.prototype.showcategoryInlay 

    Plugin.prototype.hidecategoryInlay = function () {

        var widget = this;

        if ($('#categoryInlay').is(':visible')) {

            $('#categoryInlay').removeClass('show');

            $.bbq.removeState();//удаляем хэш 	

        }

    };//END OF Plugin.prototype.hidecategoryInlay

    Plugin.prototype.initRedactor = function () {

        tinymce.init({

            selector: "textarea#maincontent",

            height: 500,

            language: 'ru',

            apply_source_formatting: true,

            plugins: [

                "advlist autolink lists link image charmap print preview anchor",

                "searchreplace visualblocks code fullscreen",

                //  "insertdatetime media table contextmenu paste moxiemanager"

            ],

            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"

        });

    };//END OF Plugin.prototype.initRedactor


    Plugin.prototype.fillcategoryAjax = function () {

        var widget = this;

        $.ajax({
            type: "POST",
            url: '/admin/controllers/categories.controller.php',
            dataType: 'json',
            data: {getCategoryItem: widget.config.categoryId}
        }).done(function (data) {

            if (null != data) {
                widget.fillcategoryData(data);
                success_tip('Категория #'+widget.config.categoryId+' получена');
                console.log(data);
            }
            else {
                error_tip('Ответ сервера не соответсвует ожидаемому');
            }

        }).fail(function () {
            error_tip('Ошибка сервера');
        });

    };//END OF Plugin.prototype.fillOrderAjax 


    Plugin.prototype.fillcategoryData = function (data) {

        var widget = this;

        $('#categoryInlayId', widget.element).text(widget.config.categoryId);
        $('#categoryName', widget.element).val(data.categoryName);
        $('#categoryTranslit', widget.element).val(data.categoryTranslit);
        $('#categorySearch', widget.element).val(data.categorySearch);
        tinyMCE.execCommand('mceSetContent', false, data.categoryDesc);
        widget.showcategoryInlay();
        widget.bindAll();

    };//END OF Plugin.prototype.fillOrderData


    Plugin.prototype.getPrevNext = function (e, prevNext, categoryId) {

        var widget = this;

        if ('prev' == prevNext) {// show prev category

            $('.categoryStripe').each(function () {


                if (parseInt($(this).data('mkcategory').categoryId) == categoryId) {

                    if (null != $(this).next().data('mkcategory')) {

                        $.bbq.pushState({category: parseInt($(this).next().data('mkcategory').categoryId)});

                    }

                    else {

                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Больше новостей нет',
                            action: 'fade',
                            fadeout: 1500,
                            color: 'red'
                        });

                    }

                }

            });

        }

        else if ('next' == prevNext) {// show next category

            $('.categoryStripe').each(function () {

                if ($(this).data('mkcategory').categoryId == categoryId) {

                    if (null != $(this).prev().data('mkcategory')) {

                        $.bbq.pushState({category: parseInt($(this).prev().data('mkcategory').categoryId)});

                    }

                    else {

                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Нет новостей новее',
                            action: 'fade',
                            fadeout: 1500,
                            color: 'red'
                        });

                    }

                }

            });

        }

    };//END OF Plugin.prototype.getPrevNext    


// удалить новость
    Plugin.prototype.deleteCategory = function () {

        var widget = this, ids = [];
        ids.push(widget.config.categoryId);

        if (ids.length > 0 && confirm("Удалить новость?")) {

            $.ajax({
                type: "POST",
                url: '/admin/controllers/categories.controller.php',
                dataType: 'json',
                data: {deleteCategory: ids}
            }).done(function (data) {
                if ('ok' == data.clean) {
                    success_tip("Категория удалена");

                    $('[data-mkcategory="{"categoryId":"' + ids + '"}"]').remove();

                    widget.hidecategoryInlay();
                }
                else {
                    error_tip("Не удалось удалить выбранные категории\n" + data);
                }
            }).fail(function () {
                error_tip("Ошибка сервера");
            });
        } else {
            error_tip("Нужно отметить хотя бы одну категорию");
        }
    };

    Plugin.prototype.bindAll = function () {

        var widget = this, search_timeout;

        $('.delete', widget.element).unbind().bind('click', function (e) {
            widget.deleteCategory();
        });


        $('.close', widget.element).unbind().bind('click', function (e) {

            widget.hidecategoryInlay();

        });

        $('.update', widget.element).unbind().bind('click', function (e) {

            widget.fillcategoryAjax();

        });

        $('.switch-left').unbind().bind('click', function (e) {

            widget.getPrevNext(e, 'prev', widget.config.categoryId);

        });

        $('.switch-right').unbind().bind('click', function (e) {

            widget.getPrevNext(e, 'next', widget.config.categoryId);

        });


        $('.save', widget.element).unbind().bind('click', function (e) {

            widget.saveAll();

        });


    };//END OF Plugin.prototype.bindAll

    Plugin.prototype.saveAll = function () {

        var widget = this;

        widget.config.categoryName = $.trim($('#categoryName', widget.element).val());

        widget.config.categoryTranslit = $.trim($('#categoryTranslit', widget.element).val());

        tinyMCE.activeEditor.save();

        widget.config.categoryDesc = tinyMCE.activeEditor.getContent();

        widget.config.categorySearch = $.trim($('#categorySearch', widget.element).val());

        $.ajax({

            type: "POST",

            url: '/admin/controllers/categories.controller.php',

            dataType: 'json',

            data: {
                saveAll: widget.config.categoryId,

                categoryName: widget.config.categoryName,

                categoryTranslit: widget.config.categoryTranslit,

                categoryDesc: widget.config.categoryDesc,

                categorySearch: widget.config.categorySearch

            }

        }).done(function (data) {

            console.log(data);

            if (null != data) {

                if (null != data.error) { //ошибка!
                    error_tip('Сервер ответил ошибкой');
                }
                else if (null != data.saved) {//ОК!

                    success_tip('Сохранено '+data.saved);

                    $('#dateupdate', widget.element).val(data.saved); //Обновляем дату модификации!
                }
                else {

                    $('#condition').mkCondition({
                        condition: 'tooltip',
                        text: 'Ответ сервера не соответсвует ожидаемому',
                        action: 'show',
                        color: 'red'
                    });

                }

            }

            else {

                $('#condition').mkCondition({
                    condition: 'tooltip',
                    text: 'Сервер прислал пустой ответ',
                    action: 'show',
                    color: 'red'
                });

            }

        }).fail(function () {

            $('#condition').mkCondition({condition: 'tooltip', text: 'Ошибка сервера', action: 'show', color: 'red'});

        });


    };//END OF Plugin.prototype.saveAll                                              

    Plugin.prototype.init = function () {

        var widget = this;

        if ('ajax' == widget.config.action) {//запаолнение по аджакс-запросу
            widget.fillcategoryAjax();
        }
        else if ('data' == widget.config.action) {//Заполнение по предоставляемой информации
            widget.fillcategoryData();
        }

        widget.initRedactor();

        widget.bindAll();


    };//END OF INIT

    $.fn[pluginName] = function (options) {

        return this.each(function () {

            new Plugin($(this), options);

        });

    }

})(jQuery, window, document);

