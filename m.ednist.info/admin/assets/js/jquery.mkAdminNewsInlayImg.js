(function ($, window, document, undefined) {
    var pluginName = 'mkAdminNewsInlayImg',
            defaults = {
                action: 'ajax',
                newsId: 0
            };

    // конструктор плагина
    function Plugin(element, options) {
        var widget = this;
        widget.element = element;
        widget.metadata = widget.element.data('mknews');
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

    Plugin.prototype.bindAll = function () {

        var widget = this;

        $('#imgAddFromLink').unbind().bind('click', function () {
            widget.imgAddFromLink();
        });

        $('#docAddFromLink').unbind().bind('click', function () {
            widget.docAddFromLink();
        });

    };
    Plugin.prototype.bindimgs = function () {
        var widget = this;

        $('#gallery .item').unbind().bind('click', function (e) { // клик на саму картинку
            var element = $(this),
                    val = element.data('img-sprite');
                    val_desc=element.data('img-desc');
            element.parent().find('.focus').removeClass('focus');
            element.addClass('focus');

            if (e.target.nodeName == 'IMG') {
                $('#newsImgAlt').val(val_desc).focus();
                $('#imgDesc').show();
                $('#imgBtnDesc').unbind().bind('click', function () {
                    var imgdesc = $('#newsImgAlt').val();
                  //  val.imgdesc = imgdesc;
                    element.data("img-sprite", val);
                    $('#condition').mkCondition({action: 'blink', color: 'yellow'});
                    $.ajax({
                        type: "POST",
                        url: '/admin/controllers/upload.controller.php',
                        dataType: 'json',
                        data: {setDescImgId: val.imgid, desc: imgdesc}
                    }).done(function (data) {
                        if (data == '0') {
                            $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                        } else {
                            $('#condition').mkCondition({
                                condition: 'tooltip',
                                text: 'Ответ сервера не соответсвует ожидаемому',
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
                })
            }
        })

        $('#gallery .sill').unbind().bind('click', function () { // Главная ккартинка
            var element = $(this),
                    val = element.parent().data('img-sprite');
            $('#condition').mkCondition({action: 'blink', color: 'yellow'});
            $.ajax({
                type: "POST",
                url: '/admin/controllers/upload.controller.php',
                dataType: 'json',
                data: {setMainImgId: val.imgid}
            }).done(function (data) {

                if (data == '0') {
                    success_tip('Установленно успешно');

                    $('#gallery .main').removeClass('main');
                    element.addClass('main');
                    imgInList = $('[data-mknews="{"newsId":"' + widget.config.newsId + '"}"] .avatar');

                    if (imgInList.find('img').length) {
                        src = imgInList.find('img').attr('src');
                        imgInList.find('img').attr('src', src + '?' + (new Date()).getTime());
                    }
                    else {
                        imgInList.html($('<img/>').attr('src', val.imgpath + 'main/60.jpg?' + (new Date()).getTime()));
                    }
                }
                else {
                    error_tip('Ответ сервера не соответсвует ожидаемому');
                }
            }).fail(function () {
                error_tip('Ошибка сервера');
            });
        })

        $('#gallery .del').unbind().bind('click', function () { // удалить картинку
            if (confirm("Удалить?")) {
                var element = $(this);
                val = element.parent().data('img-sprite');
                $('#condition').mkCondition({action: 'blink', color: 'yellow'});
                $.ajax({
                    type: "POST",
                    url: '/admin/controllers/upload.controller.php',
                    dataType: 'json',
                    data: {delImgId: val.imgid}
                }).done(function (data) {
                    if (data == '0') {
                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                        if (element.parent().find('.main').length) {
                            $('[data-mknews="{"newsId":"' + widget.config.newsId + '"}"] .avatar img').remove();
                        }
                        element.parent().remove();
                    } else {
                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Ответ сервера не соответсвует ожидаемому',
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
            return false;
        });

        $('#gallery .show').unbind().bind('click', function () { // посмотреть/увеличить картинку
            var link = $(this).parent().find('img').attr('src'), d = new Date();
            link = link.replace('gallery', 'big');
            $('#showImageWindow .inputs').html($('<img />').attr('src', link + '?' + d.getTime()));
            $('#showImageWindow').show();

            return false;
        })

        $('#newsUrlBtn').unbind().bind('click', function () { // посмотреть/увеличить картинку
            var link = $('#newsUrl').val()
            window.open(link, '_blank');
            return false;
        })

        $('#gallery .link').unbind().bind('click', function () { // взять ссылку на картинку
            var link = $(this).parent().find('img').attr('src'), d = new Date();
            link = link.replace('gallery', 'big');
            var getlink = prompt('Скопируйте ссылку для дальнейшего использования.', link);

            return false;
        })

        $('#gallery .cut').unbind().bind('click', function () { // обрезка картинки
            var link = $(this).parent().find('img').attr('src'),
                    element = $(this), d = new Date(),
                    val = $(this).parent().data('img-sprite');
            link = link.replace('gallery', 'raw');
            $('#showImageCropWindow .inputs').html($('<img />').attr('src', link + '?' + d.getTime()).addClass('target'));
            $('#showImageCropWindow').unbind().show();
            //setTimeout(function(){
            $('#showImageCropWindow img').Jcrop({
                onSelect: function (c) {
                    window.c = c;
                },
                //onChange: showCoords,
                aspectRatio: 1,
                minSize: [400, 400],
                boxWidth: 1000, boxHeight: 600, //
                setSelect: [0, 0, 400, 400],
                bgOpacity: 0.5,
                bgColor: 'black',
                addClass: 'jcrop-dark'
            }, function () {
                jcrop_api = this;
            });
            $('.savecrop').unbind().bind('click', function () { // сохранение фрагмента картинки

                $.ajax({
                    type: "POST",
                    url: '/admin/controllers/upload.controller.php',
                    data: {cropImgId: val.imgid, c: window.c}
                }).done(function (data) {
                    if (data != 'error') {
                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                        element.parent().find('img').attr('src', data);
                        $('#showImageCropWindow .close').trigger('click');
                        if (element.parent().find('.main').length) {
                            imgInList = $('[data-mknews="{"newsId":"' + widget.config.newsId + '"}"] .avatar img');
                            src = imgInList.attr('src');
                            imgInList.attr('src', src + '?' + (new Date()).getTime());
                        }
                    } else {
                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Ответ сервера не соответсвует ожидаемому',
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
            })

            return false;
        })
    };

    // Сохранить картинку по ссылке
    Plugin.prototype.imgAddFromLink = function () {
        var widget = this;
        if (link = prompt('Введите ссылку на картику')) {
            $('#condition').mkCondition({action: 'blink', color: 'yellow'});
            $.ajax({
                type: "POST",
                url: '/admin/controllers/upload.controller.php',
                //dataType:'json',
                data: {imgAddLink: link, newsId: widget.config.newsId}
            }).done(function (data) {
                if (typeof data != 'undefined') {
                    $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                    $('#gallery').append(data);
                    $('#gallery').trigger('callimgs.mkAdminNewsInlayImg');
                } else {
                    $('#condition').mkCondition({
                        condition: 'tooltip',
                        text: 'Ссылка недействительна',
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
    };

    // Сохранить документ по ссылке
    Plugin.prototype.docAddFromLink = function () {
        var widget = this, link = $('.linkdocadd').val();
        if ($('.linkdocadd').val() == '') {
            $('.linkdocadd').focus();
        } else {
            $('#condition').mkCondition({action: 'blink', color: 'yellow'});
            $.ajax({
                type: "POST",
                url: '/admin/controllers/upload.controller.php',
                //dataType:'json',
                data: {docAddLink: link, newsId: widget.config.newsId}
            }).done(function (data) {
                if (typeof data != 'undefined') {
                    $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                    if (data == 'duplicate') {
                        //alert('Файл с таким именем уже существует');
                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Файл с таким именем уже существует',
                            action: 'show',
                            color: 'red'
                        });
                    } else {
                        $('#blockDocsWrap').append(data);
                    }
                    $('#blockDocsWrap').trigger('calldocs.mkAdminNewsInlayImg');
                } else {
                    $('#condition').mkCondition({
                        condition: 'tooltip',
                        text: 'Ссылка недействительна',
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
    };

    // удаление документа
    Plugin.prototype.bindDocItems = function () {
        var widget = this;
        $('#blockDocsWrap .docitem .close').unbind().bind('click', function (e) {

            if (confirm("Удалить?")) {
                var element = $(this).parent(),
                        val = element.data('doc-sprite');
                $('#condition').mkCondition({action: 'blink', color: 'yellow'});
                $.ajax({
                    type: "POST",
                    url: '/admin/controllers/upload.controller.php',
                    dataType: 'json',
                    data: {delDocItem: val.docname, newsId: widget.config.newsId}
                }).done(function (data) {
                    if (data == '0') {
                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                        element.remove();
                    } else {
                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Ответ сервера не соответсвует ожидаемому',
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
            return false;
        });
    };

    // инициализация плагина
    Plugin.prototype.init = function () {
        var widget = this;

        widget.bindAll();

        $('#gallery').bind('callimgs.' + widget._name, function () {
            widget.bindimgs();
        });

        $('#blockDocsWrap').bind('calldocs.' + widget._name, function () {
            widget.bindDocItems();
        });


    };

    // Добавляем плагин к jQuery
    $.fn[pluginName] = function (options) {
        return this.each(function () {
            new Plugin($(this), options);
        });
    }

})(jQuery, window, document);