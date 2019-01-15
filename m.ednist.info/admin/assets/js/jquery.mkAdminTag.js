/*! Copyright (c) 2014 Max Kulyaev (Максим Куляев) zlada.net@gmail.com

 * Licensed under the MIT License (LICENSE.txt).

 * jquery.mkAdminNews

 * Version: 1.0

 */

;
(function ($, window, document, undefined) {

    var pluginName = 'mkAdmintag',

        defaults = {

            tagId: 0,

            scrollTop: 0

        };

    // конструктор плагина

    function Plugin(element, options) {

        var widget = this;

        widget.element = element;

        widget.metadata = widget.element.data('mktag');

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

            $.bbq.pushState({'tag': parseInt(widget.config.tagId)});

        });
        
          //поиск(появления хеша в урл)
         $('.search-icon').unbind().bind('click', function (e) {
             var value=$('#tags').val();
            $.bbq.pushState({
                'find': value
            });
        });

        // для инпута поиска
        $('#tags').unbind().bind('keydown', function (e) {
           if (e.keyCode == 13) {
            var value=$('#tags').val();
            $.bbq.pushState({
                'find': value
            });
	}
        });
        
         //сброс поиска(задаем пустое поле и потом ево ловим и даем все значения)
         $('.resetSearch .reset').unbind().bind('click', function (e) {
            var value='';
            $.bbq.pushState({
                'find': value
            });
             
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