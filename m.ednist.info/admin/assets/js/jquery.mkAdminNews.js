/*! Copyright (c) 2014 Max Kulyaev (Максим Куляев) zlada.net@gmail.com
 * Licensed under the MIT License (LICENSE.txt).
 * jquery.mkAdminNews
 * Version: 1.0
 */
;
(function ($, window, document, undefined) {
    var pluginName = 'mkAdminNews',
        defaults = {
            newsId: 0,
            scrollTop: 0
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
        //показиваем все новости
     //   $('.newsStripe').show();
        // переходим в редактирование новости
         $('.go', widget.element).unbind().bind('click', function (e) {
            $.bbq.pushState({
                'news': parseInt(widget.config.newsId)
            });
        });
        // переходим в редактирование новости
    /*    $('.go', widget.element).unbind().bind('click', function (e) {
             $.ajax({
                type: "POST",
                url: '/admin/controllers/news.controller.php',
                dataType: 'json',
                data: {action: "openNews",newsId:parseInt(widget.config.newsId)}
            }).done(function (data) {
                if(data.result){                 
                     $.bbq.pushState({
                     'news': parseInt(widget.config.newsId)
                     });
                }
                else{
                     error_tip('Ви не можете зайти на ету новость. Недостаточно прав!!!');
                }
                
            }).fail(function () {
                error_tip('Ошибка сервера');
            });
           

        });*/
        //фильтри и поиск(появления хеша в урл)
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
     

    
        $('.list_users').unbind().bind('click', function (e) {
          //Знаходим какой юзер вибран(value.userId-ід,valueName-имя)
            var value = $(this).closest('.list_users').data('mknews');
            var valueName=$(this).attr('value');
            $('#filterUsers').html(valueName+'<i class="caret"></i>');
                $.bbq.pushState({
                'userId': value.userId
            });
             widget.filters();
         
        });
        
         $('.list_news').unbind().bind('click', function (e) {
          //Знаходим какой материал вибран(value.newsType-ід,valueMaterial-имя)
            var value = $(this).closest('.list_news').data('mknews');
            var valueMaterial=$(this).attr('value');
           $('#filterMaterials').html(valueMaterial+'<i class="caret"></i>');
                 $.bbq.pushState({
                'newsType': value.newsType
            });
            widget.filters();
             
        });
        
         //сброс поиска(задаем пустое поле и потом ево ловим и даем все значения)
         $('.resetSearch .reset').unbind().bind('click', function (e) {
          //  var value='';
            $.bbq.pushState({
                'find': 'reset',
                'userId':'reset',
                'newsType': 'reset'
            });
             
        });

    };
     Plugin.prototype.filters = function () {

        var widget = this;
        var newsType = $.bbq.getState('newsType');
        var userId = $.bbq.getState('userId');
         //прячем всех юзеров
           $('.newsStripe').hide();
            if(newsType==null&&userId==null)
            $('.newsStripe').show();
           //знаходим все новости на странице по їхньому класу
           var links = $('.newsStripe');
           //перебираемо все новoсти 
           links.each(function() {
               if (newsType!=''&&newsType!=null) {
                    if (($(this).closest('.newsStripe').data('mknews')).newsType != newsType){
                        return true;
                    }
                        
                }

                if (userId!=''&&userId!=null) {
                    if (($(this).closest('.newsStripe').data('mknews')).userId!= userId){  
                        return true;
                    }
                        
                }
                $(this).show();
               
            });
    };

    Plugin.prototype.init = function () {
        var widget = this;
        widget.filters();
        widget.bindAll();
    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            new Plugin($(this), options);
        });
    }
})(jQuery, window, document);