/*! Copyright (c) 2014 Max Kulyaev (Максим Куляев) zlada.net@gmail.com

 * Licensed under the MIT License (LICENSE.txt).

 * jquery.mkCondition

 * Version: 2.0

 */

;
(function ($, window, document, undefined) {

    var pluginName = 'mkCondition',

        timerHandle,

        defaults = {

            condition: 'lamp',

            text: '',

            action: 'blink',

            color: 'green',

            interval: 50,

            fadeout: 1500

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

    Plugin.prototype.blink = function (e) {

        var widget = this;

        timerHandle = setInterval(function () {
            widget.element.toggle();
        }, widget.config.interval);

    };//END OF Plugin.prototype.blink


    Plugin.prototype.show = function (e) {

        var widget = this;

        widget.element.css({display: 'block', opacity: 1});

    };//END OF Plugin.prototype.show  


    Plugin.prototype.hide = function (e) {

        var widget = this;

        widget.element.hide();

    };//END OF Plugin.prototype.show                        

    Plugin.prototype.fade = function (e) {

        var widget = this;

        widget.element.show().animate({opacity: 0}, widget.config.fadeout, function () {

            widget.element.css({display: 'none', opacity: 1});

        });

    };//END OF Plugin.prototype.fade                          

    Plugin.prototype.init = function () {

        var widget = this;

        if ('lamp' == widget.config.condition) {

            widget.element.text('').removeClass('tooltip').addClass('lamp');

        }

        if ('tooltip' == widget.config.condition) {

            widget.element.removeClass('lamp').addClass('tooltip').text(widget.config.text);

        }

        widget.element.stop().animate({opacity: 1}, 0);

        window.clearInterval(timerHandle);

        widget.element.removeClass('red').removeClass('green').removeClass('yellow').removeClass('blue').addClass(widget.config.color);


        if ('blink' == widget.config.action) {

            widget.blink();

        }

        else if ('hide' == widget.config.action) {

            widget.hide();

        }

        else if ('show' == widget.config.action) {

            widget.show();

        }

        else if ('fade' == widget.config.action) {

            widget.fade();

        }

        widget.element.unbind().bind('click', function () {

            window.clearInterval(timerHandle);

            widget.hide();

        });

    };//END OF INIT

    $.fn[pluginName] = function (options) {

        return this.each(function () {

            new Plugin($(this), options);

        });

    }

})(jQuery, window, document);