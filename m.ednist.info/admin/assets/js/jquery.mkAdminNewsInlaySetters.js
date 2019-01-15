(function ($, window, document, undefined) {
    var pluginName = 'mkAdminNewsInlaySetters',
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

    // Setters

    Plugin.prototype.setNewsParam = function (param, value, cb_error, cb_success) {

        var widget = this;
        var updateTime = false;
        if (param == 'newsStatus' && value == 4) {
            updateTime = confirm("Обновить час публикации на текущий?");
        }
        $.ajax({
            type: "POST",
            url: '/admin/controllers/news.controller.php',
            dataType: 'json',
            data: {
                action: 'setParam',
                newsId: widget.config.newsId,
                paramName: param,
                paramValue: value,
                updateTime: updateTime
            }
        }).done(function (data) {

            if (null != data.saved) {
                cb_success();
                success_tip(data.saved);
                $('#dateupdate', widget.element).val(data.saved); //Обновляем дату модификации!
                if (data.newsType == 0) {
                    var type = 'news';
                }
                else if (data.newsType == 1) {
                    var type = 'article';
                }
                else if (data.newsType == 2) {
                    var type = 'blog';
                }
                if (data.newsStatus == 4) {
                    $('#newsInlayId').html("<a id=newsInlayIdSulka target=_blank href=http://" + window.location.hostname + "/" + type + "/" + widget.config.newsId + "></a>");
                    if (updateTime) {
                        $('#datepublic').val(data.saved);
                    }
                }
                else {
                    //  $('#newsInlayId', widget.element).text(widget.config.newsId);
                    $('#newsInlayId').html("<a id=newsInlayIdSulka target=_blank href=http://" + window.location.hostname + "/" + type + "/" + widget.config.newsId + "?preview=1></a>");
                }

                $('#newsInlayIdSulka', widget.element).text(widget.config.newsId);
            }
            else {
                cb_error();
                error_tip("Ответ сервера не соответсвует ожидаемому.\n" + data);
            }
        }).fail(function () {
            cb_error();
            error_tip('Ошибка сервера');
        });

    };

    // Включить виджет
    Plugin.prototype.setWidget = function (e, obj) {
        var widget = this, newsWidget;

        newsWidgetStart = (obj.prop("checked")) ? 1 : 0;

        widget.setNewsParam('newsWidgetStart', newsWidgetStart,
                function () {
                    (0 == isGallery) ? obj.prop("checked", true) : obj.prop("checked", false);
                },
                function () {
                }
        );
    };

    // Установить новость главной
    Plugin.prototype.setNewsMain = function (e, obj) {
        var widget = this, newsMain;
        var text = tinyMCE.activeEditor.getContent();
        var header = $('#newsHeader').val();
        var subheader = $('#newsSubheader').val();
        
            newsMain = (obj.prop('checked') === true) ? 1 : 0;
        if (text !== '' && header !== '' & subheader !== '' && $('select[name=newsStatus]').val() > 2) {
            console.log('sssssssss');
                widget.setNewsParam('newsMain', newsMain,
                        function () {
                            obj.prop('checked', obj.prop('checked') === true);
                        },
                        function () {
                        }
                );
         }else{
               alert('Заполните основние поля!!!');
              (1 == newsMain) ? $('.flag').prop('checked', false) : $('.flag').prop('checked', true);
         }
            
    };


    // Установить новость галереей
    Plugin.prototype.setNewsIsGallery = function (e, obj) {
        var widget = this, isGallery;
        isGallery = (obj.prop("checked")) ? 1 : 0;

        widget.setNewsParam('newsIsGallery', isGallery,
                function () {
                    (0 == isGallery) ? obj.prop("checked", true) : obj.prop("checked", false);
                },
                function () {
                }
        );
    };

    // Установить овость как видео-материал
    Plugin.prototype.setNewsIsVideo = function (e, obj) {
        var widget = this;
        isVideo = (obj.prop("checked")) ? 1 : 0;

        widget.setNewsParam('newsIsVideo', isVideo,
                function () {
                    (0 == isVideo) ? obj.prop("checked", true) : obj.prop("checked", false);
                },
                function () {
                }
        );
    };

    // Установить новость как UkrNet
    Plugin.prototype.setUkrNetNews = function (e, obj) {
        var widget = this;
        ukrNet = (obj.prop("checked")) ? 1 : 0;

        widget.setNewsParam('ukrNet', ukrNet,
                function () {
                    (0 == ukrNet) ? obj.prop("checked", true) : obj.prop("checked", false);
                },
                function () {
                }
        );
    };
    
    // Установить новость как Exclusive
    Plugin.prototype.setExclusive = function (e, obj) {
        var widget = this;
        value = (obj.prop("checked")) ? 1 : 0;

        widget.setNewsParam('newsExclusive', value,
                function () {
                    (0 == value) ? obj.prop("checked", true) : obj.prop("checked", false);
                },
                function () {
                }
        );
    };

    // Установить категорию новости
    Plugin.prototype.setCategory = function (e, obj) {
        var widget = this;

        widget.setNewsParam('categoryId', obj.val(),
                function () {
                },
                function () {
                    catName = obj.find('option:selected').text();
                    //Обновляем название в списке новостей
                    $('[data-mknews="{"newsId":"' + widget.config.newsId + '"}"] .theme p').text(catName);
                }
        );
    };

    // Установить тип новости
    Plugin.prototype.setNewsType = function (e, obj) {
        var widget = this;
        
            widget.setNewsParam('newsType', obj.val(),
                    function () {
                    },
                    function () {
                        // меняем иконку в списке
                        $('[data-mknews="{"newsId":"' + widget.config.newsId + '"}"] [class^="typenew"]').attr('class', 'typenew' + obj.val());
                    }
            );
    };

    // Установить статус новости
    Plugin.prototype.setNewsStatus = function (e, obj) {
        var widget = this;
        var allow = false;
        var text = tinyMCE.activeEditor.getContent();
        var header = $('#newsHeader').val();
        var subheader = $('#newsSubheader').val();
        if (obj.val()>2) {
            allow=false;
            if (text !== '' && header !== '' & subheader !== '') {
                allow=true;
            }
        }else{
            allow=true;
        }
        if (allow) {
        widget.setNewsParam('newsStatus', obj.val(),
                function () {
                },
                function () {
                    // меняем иконку в списке
                    $('[data-mknews="{"newsId":"' + widget.config.newsId + '"}"] [class^="status"]').attr('class', 'status' + obj.val());
                    widget.config.newsStatus=$('select[name=newsStatus]').val(obj.val());
                }
        );
        } else {
            alert('Заполните основние поля!!!');
            $('select[name=newsStatus]').val(2);
        }

    };

    // Кросспостинг - отметить отправку в соц. сети
    Plugin.prototype.setNewsPostToSoc = function (e, obj) {
        var widget = this;
        isPostToSoc = (obj.prop("checked")) ? 1 : 0;
        socName = obj.attr('id').substr(3);
        if (socName == 'Vkontakte')
            socName = 'newsPostVk';
        if (socName == 'Twitter')
            socName = 'newsPostTw';
        if (socName == 'Facebook')
            socName = 'newsPostFb';

        widget.setNewsParam(socName, isPostToSoc,
                function () {
                    (0 == isPostToSoc) ? obj.prop("checked", true) : obj.prop("checked", false);
                },
                function () {
                }
        );
    };

    // Кросспостинг - установка времени отправки
    Plugin.prototype.setNewsSocTime = function (e, obj) {
        var widget = this, isGallery;
        newsSocTime = $.trim(obj.val());
        if ('' != newsSocTime) {
            $('#timePublicSocial .close').show();
        } else {
            $('#timePublicSocial .close').hide();
        }

        widget.setNewsParam('newsSocTime', newsSocTime,
                function () {
                },
                function () {
                }
        );
    };


    // Кросспостинг - отправка
    Plugin.prototype.sendToSoc = function (e, obj) {
        var widget = this;
        var socText = $('#articleSocial').val();

        $.ajax({
            type: "POST",
            url: '/admin/controllers/crosspost.controller.php',
            dataType: 'json',
            data: {
                newsId: widget.config.newsId,
                socText: socText,
                act: 'sendMessage'
            }
        }).done(function (data) {
            if (null != data.saved) {//ОК!
                success_tip('Пост отправленно');
                $('#dateupdate', widget.element).val(data.saved); //Обновляем дату модификации!
            }
            else {
                error_tip("Ответ сервера не соответсвует ожидаемому\n" + data);
            }
        }).fail(function () {
            error_tip('Ошибка сервера');
        });
    };

    Plugin.prototype.bindAll = function () {

        var widget = this;

        $('.flag', widget.element).unbind().bind('click', function (e) {
            widget.setNewsMain(e, $(this));
        });

        $('#inpGallery', widget.element).unbind().bind('change', function (e) {
            widget.setNewsIsGallery(e, $(this));
        });
        $('#inpVideo', widget.element).unbind().bind('change', function (e) {
            widget.setNewsIsVideo(e, $(this));
        });
        $('#ukrNet', widget.element).unbind().bind('change', function (e) {
            widget.setUkrNetNews(e, $(this));
        });
        $('#isExclusive', widget.element).unbind().bind('change', function (e) {
            widget.setExclusive(e, $(this));
        });
        $('#inpWidget', widget.element).unbind().bind('change', function (e) {
            widget.setWidget(e, $(this));
        });
        $('#categoryName', widget.element).unbind().bind('change', function (e) {// ------Категории  
            widget.setCategory(e, $(this));
        });
        $('#newsType', widget.element).unbind('change').bind('change', function (e) {// ----Тип новости
            widget.setNewsType(e, $(this));
        });
        $('#newsStatus', widget.element).unbind('change').bind('change', function (e) {// ----Статус новости
            if ($('select[name=newsStatus]').val() > 2 && $('select[name=categoryId]').val() == 0) {
                alert('Укажите категорию! И сохраните еще раз!');
            }
            else {
                widget.setNewsStatus(e, $(this));
            }
        });
        $('#timePublicSocial input.timetext', widget.element).unbind('change').bind('change', function (e) {
            widget.setNewsSocTime(e, $(this));
        }).datetimepicker({format: ' H:i d.m.Y', lang: 'ru', step: 10});
        $('#publicNow', widget.element).unbind().bind('click', function (e) {
            widget.sendToSoc(e, $(this));
        });
		$('#fbpixelId', widget.element).unbind().bind('change', function (e) {// ------fbpixelId  
            widget.setNewsParam("fbpixelId", $(this).val(), function(){}, function(){});
		});
    };

    // инициализация плагина
    Plugin.prototype.init = function () {
        var widget = this;

        widget.bindAll();


    };

    // Добавляем плагин к jQuery
    $.fn[pluginName] = function (options) {
        return this.each(function () {
            new Plugin($(this), options);
        });
    }

})(jQuery, window, document);
