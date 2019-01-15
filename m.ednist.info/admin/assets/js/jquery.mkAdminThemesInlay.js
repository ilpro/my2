/*! Copyright (c) 2014 Max Kulyaev (Максим Куляев) 1audiodesign@gmail.com
 * Licensed under the MIT License (LICENSE.txt).
 * jquery.mkAdminthemesInlay
 * Version: 1.0
 */
;
(function ($, window, document, undefined) {
    var pluginName = 'mkAdminThemesInlay',
        defaults = {
            action: 'ajax',
            themesId: 0,
            themesStatus: 0,
            themesDesc: '',
            themesActive: false
        };
    // конструктор плагина

    function Plugin(element, options) {
        var widget = this;

        widget.element = element;
        widget.metadata = widget.element.data('mkthemes');
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

    Plugin.prototype.showthemesInlay = function () {
        var widget = this;
        if (!$('#themesInlay').is(':visible')) {
            $('#themesInlay').addClass('show');
        }
    };//END OF Plugin.prototype.showthemesInlay 
    Plugin.prototype.hidethemesInlay = function () {
        var widget = this;
        if ($('#themesInlay').is(':visible')) {
            $('#themesInlay').removeClass('show');
            $.bbq.removeState(['themes']);//удаляем хэш 	
        }
    };//END OF Plugin.prototype.hidethemesInlay
    Plugin.prototype.initRedactor = function () {
        var widget = this;
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
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            setup: function (editor) {
                editor.addMenuItem('myitem', {
                    text: 'Отчистка Word тегов',
                    context: 'tools',
                    onclick: function () {
                        widget.cleanHTML('word');
                    }
                }),
                    editor.addMenuItem('myitem2', {
                        text: 'Отчистка от всех тегов',
                        context: 'tools',
                        onclick: function () {
                            widget.cleanHTML('all');
                        }
                    })
            }
        });
    };//END OF Plugin.prototype.initRedactor
    Plugin.prototype.cleanHTML = function (method) {
        var widget = this;
        var textt = tinyMCE.activeEditor.getContent();
        //var textt = $('#maincontent').val();
        $('#condition').mkCondition({action: 'blink', color: 'yellow'});
        $.ajax({
            type: "POST",
            url: '/admin/controllers/cleanHTML.controller.php',
            data: {html: textt, method: method}
        }).done(function (data) {
            tinyMCE.execCommand('mceSetContent', false, data);

            $('#condition').mkCondition({
                condition: 'tooltip',
                text: 'Текст успешно очищен от тегов',
                action: 'fade',
                color: 'green',
                fadeout: 2500
            });
        }).fail(function () {
            $('#condition').mkCondition({condition: 'tooltip', text: 'Ошибка сервера', action: 'show', color: 'red'});
        });
    };//END OF Plugin.prototype.cleanHTML
    Plugin.prototype.publish = function () {

    };
    Plugin.prototype.fillthemesAjax = function () {
        var widget = this;

        $.ajax({
            type: "POST",
            url: '/admin/controllers/themes.controller.php',
            dataType: 'json',
            data: {getthemesItem: widget.config.themesId}
        }).done(function (data) {

            if (null != data) {
                widget.fillthemesData(data);
                console.log(data);
                success_tip('Тема #'+widget.config.themesId+' получена');
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
    };//END OF Plugin.prototype.fillOrderAjax 

    Plugin.prototype.fillthemesData = function (data) {
        var widget = this;
        //@@ TODO : change style of publish button , depending on themes state
        if (data.themesActive == "1") {
            $('.publish', widget.element).attr('title', 'Не публиковать');
        }
        else {
            $('.publish', widget.element).attr('title', 'Публиковать');
        }
        $('#themesInlayId', widget.element).text(widget.config.themesId);
        $('#themesType', widget.element).val(data.themesType);
        $('#themesSortName', widget.element).val(data.themesSortName);
        $('#themesName', widget.element).val(data.themesName);
        $('#newsStatus', widget.element).val(data.themesStatus);
        $('#themesSearch', widget.element).val(data.themesSearch);
        $('#gallery', widget.element).html(data.themesImgsHTML);

        $('#maincontent').val(data.themesDesc);
        tinyMCE.execCommand('mceSetContent', false, data.themesDesc);

        widget.showthemesInlay();
        widget.bindAll();
    };//END OF Plugin.prototype.fillOrderData

    Plugin.prototype.getPrevNext = function (e, prevNext, themesId) {
        var widget = this;
        if ('prev' == prevNext) {// show prev themes
            $('.themesStripe').each(function () {

                if (parseInt($(this).data('mkthemes').themesId) == themesId) {
                    if (null != $(this).next().data('mkthemes')) {
                        $.bbq.pushState({themes: parseInt($(this).next().data('mkthemes').themesId)});

                    }
                    else {
                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Больше брендов нет',
                            action: 'fade',
                            fadeout: 1500,
                            color: 'red'
                        });
                    }
                }
            });
        }
        else if ('next' == prevNext) {// show next themes
            $('.themesStripe').each(function () {
                if ($(this).data('mkthemes').themesId == themesId) {
                    if (null != $(this).prev().data('mkthemes')) {
                        $.bbq.pushState({themes: parseInt($(this).prev().data('mkthemes').themesId)});

                    }
                    else {
                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Нет брендов новее',
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
    Plugin.prototype.deleteThemes = function () {

        var widget = this, ids = [];
        ids.push(widget.config.themesId);

        if (ids.length > 0 && confirm("Удалить новость?")) {

            $.ajax({
                type: "POST",
                url: '/admin/controllers/themes.controller.php',
                dataType: 'json',
                data: {deleteThemes: ids}
            }).done(function (data) {
                if ('ok' == data.clean) {
                    success_tip("Новость удалена");

                    $('[data-mkthemes="{"themesId":"' + ids + '"}"]').remove();

                    widget.hidethemesInlay();
                }
                else {
                    error_tip("Не удалось удалить выбранные новости\n" + data);
                }
            }).fail(function () {
                error_tip("Ошибка сервера");
            });
        } else {
            error_tip("Нужно отметить хотя бы одну новость");
        }
    };

    Plugin.prototype.bindAll = function () {

        var widget = this, search_timeout;

        $('.delete', widget.element).unbind().bind('click', function (e) {
            widget.deleteThemes();
        });
        $('.publish', widget.element).unbind().bind('click', function (e) {
            widget.config.themesActive = (!widget.config.themesActive) ? 1 : 0;
            if ($('.publish', widget.element).attr('title') == 'Не публиковать') {
                $('.publish', widget.element).attr('title', 'Публиковать');
            }
            else {
                $('.publish', widget.element).attr('title', 'Не публиковать');
            }


        });
        $('.close', widget.element).unbind().bind('click', function (e) {
            widget.hidethemesInlay();
        });
        $('.update', widget.element).unbind().bind('click', function (e) {
            widget.fillthemesAjax();
        });
        $('.switch-left').unbind().bind('click', function (e) {
            widget.getPrevNext(e, 'prev', widget.config.themesId);
        });
        $('.switch-right').unbind().bind('click', function (e) {
            widget.getPrevNext(e, 'next', widget.config.themesId);
        });

        $('#imgAddFromLink').unbind().bind('click', function () {
            widget.imgAddFromLink();
        });

        $('.save', widget.element).unbind().bind('click', function (e) {
            widget.saveAll();
        });
        $('#gallery').trigger('callimgs.' + widget._name);

        $('#newsStatus', widget.element).unbind('change').bind('change', function (e) {// ----Статус новости
            widget.setThemesStatus(e, $(this));
        });

        $("#newsStatus").select2({
            formatResult: function (state) {
                var originalOption = state.element;
                return "<span class='status" + $(originalOption).data('foo') + "' title='" + $(originalOption).data('title') + "' ></span>" + state.text;
            },
            formatSelection: function (state) {
                var originalOption = state.element;
                return "<span class='status" + $(originalOption).data('foo') + "' title='" + $(originalOption).data('title') + "' ></span>" + state.text;
            },
            escapeMarkup: function (m) {
                return m;
            }
        });
    };//END OF Plugin.prototype.bindAll


    Plugin.prototype.setThemesStatus = function (e, obj) {
        var widget = this;

        $.ajax({
            type: "POST",
            url: '/admin/controllers/themes.controller.php',
            dataType: 'json',
            data: {setThemesStatus: obj.val(), themesId: widget.config.themesId}
        }).done(function (data) {
            if (null != data) {
                if (null != data.error) { //ошибка!
                    $('#condition').mkCondition({
                        condition: 'tooltip',
                        text: 'Сервер ответил ошибкой',
                        action: 'show',
                        color: 'red'
                    });
                }
                else if (null != data.saved) {//ОК!
                    $('#condition').mkCondition({
                        condition: 'tooltip',
                        text: data.saved,
                        action: 'fade',
                        color: 'green',
                        fadeout: 1000
                    });
                    $('#dateupdate', widget.element).val(data.saved); //Обновляем дату модификации!
                    $('[data-mknews="{"newsId":"' + widget.config.newsId + '"}"] [class^="status"]').attr('class', 'status' + obj.val()); // меняем иконку в списке
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
    };//END OF Plugin.prototype.setNewsStatus

    Plugin.prototype.imgAddFromLink = function () {
        var widget = this;
        if (link = prompt('Введите ссылку на картику')) {
            $('#condition').mkCondition({action: 'blink', color: 'yellow'});
            $.ajax({
                type: "POST",
                url: '/admin/controllers/upload.controller.php',
                data: {imgThemesAddLink: link, themesId: widget.config.themesId}
            }).done(function (data) {
                 if (typeof data != 'undefined') {
                    $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                    $('#gallery').append(data);
                    $('#gallery').trigger('callimgs.mkAdminThemesInlay');
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
    };//END OF Plugin.prototype.imgAddFromLink

 Plugin.prototype.bindimgs = function () {
        var widget = this;


        $('#gallery .sill').unbind().bind('click', function () { // Главная картинка
            var element = $(this),
                val = element.parent().data('img-sprite');
            $('#condition').mkCondition({action: 'blink', color: 'yellow'});
            $.ajax({
                type: "POST",
                url: '/admin/controllers/upload.controller.php',
                dataType: 'json',
                data: {setMainThemesImgId: val.imgid}
            }).done(function (data) {

                if (data == '0') {
                    success_tip('Установленно успешно');

                    $('#gallery .main').removeClass('main');
                    element.addClass('main');
                    imgInList = $('[data-mkthemes="{"themesId":"' + widget.config.themesId + '"}"] .avatar');
                    imgInList.html($('<img/>').attr('src', val.imgpath + 'main/60.jpg?' + (new Date()).getTime()));
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
                    data: {delThemesImgId: val.imgid}
                }).done(function (data) {
                    if (data == '0') {
                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                        if (element.parent().find('.main').length) {
                            $('[data-mkthemes="{"themesId":"' + widget.config.themesId + '"}"] .avatar img').remove();
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
                boxWidth: 1000, boxHeight: 600,//
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
                    data: {cropImgThemesId: val.imgid, c: window.c}
                }).done(function (data) {
                    if (data != 'error') {
                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                        element.parent().find('img').attr('src', data);
                        $('#showImageCropWindow .close').trigger('click');
                        if (element.parent().find('.main').length) {
                            imgInList = $('[data-mkthemes="{"themesId":"' + widget.config.themesId + '"}"] .avatar img');
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

    Plugin.prototype.saveAll = function () {
        var widget = this;
        widget.config.themesName = $.trim($('#themesName', widget.element).val());
        tinyMCE.activeEditor.save();
        widget.config.themesDesc = tinyMCE.activeEditor.getContent();
        widget.config.themesSearch = $.trim($('#themesSearch', widget.element).val());
        widget.config.themesSortName = $.trim($('#themesSortName', widget.element).val());
        widget.config.themesType = $.trim($('#themesType', widget.element).val());


        $.ajax({
            type: "POST",
            url: '/admin/controllers/themes.controller.php',
            dataType: 'json',
            data: {
                saveAll: widget.config.themesId,
                themesName: widget.config.themesName,
                themesDesc: widget.config.themesDesc,
                themesSearch: widget.config.themesSearch,
                themesType: widget.config.themesType,
                themesSortName: widget.config.themesSortName,
                themesActive: widget.config.themesActive
            }
        }).done(function (data) {
            console.log(data);
            if (null != data) {
                if (null != data.error) { //ошибка!
                    $('#condition').mkCondition({
                        condition: 'tooltip',
                        text: 'Сервер ответил ошибкой',
                        action: 'show',
                        color: 'red'
                    });
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
            widget.fillthemesAjax();
        }
        else if ('data' == widget.config.action) {//Заполнение по предоставляемой информации

            widget.fillthemesData();
        }
        // обработчики кнопок картинок

        $('#gallery').bind('callimgs.mkAdminThemesInlay', function () {
            widget.bindimgs();
        });
        widget.initRedactor();
        widget.bindAll();

    };//END OF INIT

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            new Plugin($(this), options);
        });
    }
})(jQuery, window, document);
