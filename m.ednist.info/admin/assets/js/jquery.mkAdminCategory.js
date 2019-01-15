/*! Copyright (c) 2014 Max Kulyaev (Максим Куляев) zlada.net@gmail.com

 * Licensed under the MIT License (LICENSE.txt).

 * jquery.mkAdminNews

 * Version: 1.0

 */

;
(function ($, window, document, undefined) {

    var pluginName = 'mkAdminCategory',

        defaults = {

            categoryId: 0,

            scrollTop: 0

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


    Plugin.prototype.bindAll = function () {

        var widget = this;

        $('.go', widget.element).unbind().bind('click', function (e) {

            $.bbq.pushState({'category': parseInt(widget.config.categoryId)});

        });


    };//END OF Plugin.prototype.bindAll                                 

    Plugin.prototype.init = function () {

        var widget = this;

        widget.bindAll();

    };//END OF INIT


    $.fn[pluginName] = function (options) {

        return this.each(function () {

            new Plugin($(this), options);

        });

    }

})(jQuery, window, document);