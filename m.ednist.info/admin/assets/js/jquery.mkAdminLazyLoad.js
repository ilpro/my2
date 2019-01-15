/**
 * Lazy Load plugin
 */
;
(function ($, window, document, undefined) {
    var pluginName = 'mkAdminLazyLoad',
        defaults = { //задем переменные здесь
            controller: '',
            scrollWait: false,
            newsEmpty: false,
            limitFrom: 0,
            limit: 0,
            action: "get_more"
        };

    // конструктор плагина
    function Plugin(element, options) {
        var widget = this;
        widget.element = element;
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

    Plugin.prototype.getSprite = function () {

        var widget = this;

        widget.config.scrollWait = true;

        $('.loadingField').show();
        setTimeout(function(){
        $.ajax({
            type: "POST",
            dataType: 'HTML',
            url: '/admin/controllers/' + widget.config.controller + '.controller.php',
            data: {
                action: widget.config.action,
                limit_from: widget.config.limitFrom,
                limit: widget.config.limit
            }
        }).done(function (obj) {
            widget.config.scrollWait = false;

            $('.loadingField').hide();

            if (obj !== '') {
                $("#all-flavors").unbind().append(obj);

                // работа с hash
                routing();

                widget.bindAll();

                success_tip('Подгруженно успешно');
            }

            if (obj === '') {
                $('.show-mor').hide();
                widget.config.newsEmpty = true;
                error_tip('Элементов для подгрузки больше нет');
            }

        }).fail(function (obj) {
            error_tip('Ошибка при подгрузке');
            $('.show-mor').hide();
        });},1000);
    };


    Plugin.prototype.startLazyLoad = function (obj) {
        var widget = this;

        if( currentNewsId === 0 && isNeedlazy) {

            widget.config.controller = obj.attr('data-controller');          // куда обращаться
            widget.config.limitFrom = $(obj.attr('data-itemsClass')).length; // сколько пропустить
            widget.config.limit = obj.attr('data-limit');                    // сколько столбцов в строке
            widget.config.action = obj.attr('action') || "get_more";
            widget.getSprite();
        }
    };

    Plugin.prototype.bindAll = function (e) { //метод для привязки всех событий
        var widget = this;
        var obj = $('.show-mor');

        if (obj.length) {

            $(window).on('hashchange', function() {
                if (
                    obj.offset().top < $(window).height() &&
                    widget.config.scrollWait == false &&
                    widget.config.newsEmpty == false
                ) {
                    widget.startLazyLoad(obj);
                }
            });

            if (
                obj.offset().top < $(window).height() &&
                widget.config.scrollWait == false &&
                widget.config.newsEmpty == false
            ) {
                widget.startLazyLoad(obj);
            }


            $(window, widget.element).bind('scroll', function (e) {

                if (
                    $(window).scrollTop() + $(window).height() + 100 >= obj.offset().top &&
                    widget.config.scrollWait == false &&
                    widget.config.newsEmpty == false
                ) {
                    widget.startLazyLoad(obj);
                }
                if( currentNewsId === 0 ) {
                    listScroll = $(window).scrollTop();
                }
            });

        }
        else
        {
            console.log('element with class .show-mor doesn\'t exists');
        }


    };//END OF Plugin.prototype.bindAll


    Plugin.prototype.init = function () { //метод инициализации. В него зашиваем все,что нужно на старте работы плагина
        var widget = this;
        widget.element.trigger('before.' + widget._name);  //триггер срабаотывания по имени 'before'
        widget.bindAll();
    };//END OF INIT

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            new Plugin($(this), options);
        });
    }
})(jQuery, window, document);