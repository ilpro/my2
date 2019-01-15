/**
 * Lazy Load plugin
 */
;
(function ($, window, document, undefined) {
    var pluginName = 'mkLazyLoad',
        defaults = { //задем переменные здесь
            controller: '',
            scrollWait: false,
            scrollWaitS: false,
            newsEmpty: false,
            categoryNewsEmpty: false,
            limitFrom: 0,
            rows: 0,
            inRow: 0,
            action: "get_more",
            article: ""
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
    
    /**
     * Лейзи-лоад новостей
     * 
     */
    Plugin.prototype.getNews = function () {
        var widget = this;
        widget.config.scrollWait = true;
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: '/controllers/' + widget.config.controller + '.controller.php',
            data: {
                action: widget.config.action,
                limit_from: widget.config.limitFrom,
                category: widget.config.category,
                limit: widget.config.limit
            }
        }).done(function (obj) {
            widget.config.scrollWait = false;
            if (obj.html !== '') {
                $("#append").append(obj.html);
            }
            if (obj.more != true) {
                widget.config.newsEmpty = true;
                $('.show-more').hide();
            }
        }).fail(function (obj) {
            console.log('false load');
        });
    };

    /**
     * Лейзи-лоад новостей правый сайдбар
     *
     */
    Plugin.prototype.getSpriteRight = function () {

        var widget = this;

        widget.config.scrollWait = true;

        $.ajax({
            type: "POST",
            dataType: 'json',
            url: '/controllers/' + widget.config.controller + '.controller.php',
            data: {
                action: 'get_more_newsline',
                limit_from: widget.config.limitFrom,
                limit: widget.config.limit
            }
        }).done(function (obj) {
            widget.config.scrollWait = false;
            if (obj.html !== '') {
                $("#append-right").append(obj.html);
            }

            if (obj.more != true) {
                widget.config.newsEmpty = true;
            }

        }).fail(function (obj) {
            console.log('false load');
        });
    };
    /**
     * Лейзи-лоад новостей правый сайдбар
     *
     */
    Plugin.prototype.getCategoryRight = function () {

        var widget = this;

        widget.config.scrollWait = true;

        $.ajax({
            type: "POST",
            dataType: 'json',
            url: '/controllers/' + widget.config.controller + '.controller.php',
            data: {
                action: 'get_more_sidebar',
                limit_from: widget.config.limitFrom,
                limit: widget.config.limit
            }
        }).done(function (obj) {
            widget.config.scrollWait = false;
            if (obj.html !== '') {
                $("#append-category").append(obj.html);
            }

            if (obj.more != true) {
                widget.config.categoryNewsEmpty = true;
            }

        }).fail(function (obj) {
            console.log('false load');
        });
    };


    Plugin.prototype.bindAll = function (e) { //метод для привязки всех событий
        var widget = this;

        var obj = $('.show-more');
        var append = $('#append');
        if (obj.length) {
            $(document).scroll(function (e) {
                if (
                    $(window).scrollTop() + $(window).height() + 100 >= obj.offset().top &&
                    widget.config.scrollWait == false &&
                    widget.config.newsEmpty == false
                ) {
                    widget.config.action = 'get_more';
                    widget.config.controller = append.attr('data-controller');
                    widget.config.category = append.attr('data-category');
                    widget.config.limitFrom = $('.list-item', append).length;
                    widget.config.limit = 16;
                    console.log(1);
                    widget.getNews();
                }
                else
                {
                    console.log('element with class .show-more doesn\'t exists');
                }
            });
        }

        obj.click(function(){
            if (
                widget.config.scrollWait == false &&
                widget.config.newsEmpty == false
            ) {
                widget.config.action = 'get_more';
                widget.config.controller = append.attr('data-controller');
                widget.config.category = append.attr('data-category');
                widget.config.limitFrom = $('.list-item', append).length;
                widget.config.limit = 16;
                console.log(1);
                widget.getNews();
            }
            else
            {
                console.log('element with class .show-more doesn\'t exists');
            }
        });
// для бокового блока
        var $box = $(".newsline-items-right");

        if ($box.length) {

            $box.scroll(function () {
                console.log(0);
                if (widget.config.scrollWait == false) {

                    var topPosition = $(this).scrollTop(),              //how much has been scrolled
                        scrolledPosition = this.scrollHeight,           //height of the content of the element
                        boxHeight = $(this).innerHeight();              // inner height of the element

                    if (topPosition + boxHeight + 30 >= scrolledPosition) {
                        widget.config.controller = 'newsline';              // куда обращаться
                        widget.config.limitFrom = $('a', $box).length;   // сколько пропустить
                        widget.config.limit = 15;
                        widget.getSpriteRight();
                    }
                }
            });
        }
// для бокового блока
        var $right_category = $("#append-category");

        if ($right_category.length) {

            $right_category.scroll(function () {
                if ((widget.config.scrollWait == false) || (widget.config.categoryNewsEmpty = false)) {

                    var topPosition = $(this).scrollTop(),              //how much has been scrolled
                        scrolledPosition = this.scrollHeight,           //height of the content of the element
                        boxHeight = $(this).innerHeight();              // inner height of the element

                    if (topPosition + boxHeight + 30 >= scrolledPosition) {
                        widget.config.controller = 'category';              // куда обращаться
                        widget.config.limitFrom = $('.list-item', $right_category).length;   // сколько пропустить
                        widget.config.limit = 15;
                        widget.getCategoryRight();
                    }
                }
            });
        }
// для ленты новостей на главной
        var $newsline = $(".main-newsline");

        if ($newsline.length) {
            var $butt = $newsline.parent().find('.more-main')
            $butt.click(function () {
                if (widget.config.scrollWait == false) {
                    widget.config.controller = 'main';
                    widget.config.limitFrom = $('a', $newsline).length;
                    widget.config.limit = 15;
                    widget.getNews();
                }
            });
        }


        $(window, widget.element).unbind().bind('scroll', function (e) {

        });

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