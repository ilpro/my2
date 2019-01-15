
(function ($, window, document, undefined) {
    var pluginName = 'mkAdminBrandConnectInlay',
        defaults = {
            action: 'ajax',
            name:''
        };
    // конструктор плагина

    function Plugin(element, options) {
        var widget = this;

        widget.element = element;
        widget.metadata = widget.element.data('mksetting');
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
    
     // полученные данных о странице
    Plugin.prototype.fillNewsAjax = function () {
        var widget = this;

        $.ajax({
            type: "POST",
            url: '/admin/controllers/brandconnect.controller.php',
            dataType: 'HTML',
            data: {action: 'getBrandConnect'}
        }).done(function (data) {
                success_tip('Страница получена');
                $('#list_brand_connects').append(data);
                widget.bindAll();           
        }).fail(function () {
            error_tip('Ошибка сервера');
        });
    };
    
    //создать связь
    Plugin.prototype.createBrandConnect = function () {
        var widget = this;
        var value=$('#connectName').val();
        $.ajax({
            type: "POST",
            url: '/admin/controllers/brandconnect.controller.php',
            dataType: 'json',
            data: {action: 'createBrandConnect',name:value}
        }).done(function (data) {
            if (data.result!=false) {
                var conn='';
                conn+='<tr id="tr_main'+data.result+'"><td><input type="text" id="brand_connect_input'+data.result+'" value="'+value+'"/></td><td><div class="edit_connect" data-value="'+data.result+'">Сохранить</div></td>';
                conn+='<td><div class="delete_connect" data-value="'+data.result+'">Удалить</div></td></tr>';   
                $('#list_brand_connects').append(conn);
                widget.bindAll();
                success_tip('Добавлена успешно');
            }
            else {
                error_tip('Ошибка');
            }
        }).fail(function () {
            error_tip('Ошибка сервера');
        });
    };
    
       Plugin.prototype.editBrandConnect = function (obj) {
        var widget = this;
        var value=$('#brand_connect_input'+obj).attr('value');
        console.log(value);
        $.ajax({
            type: "POST",
            url: '/admin/controllers/brandconnect.controller.php',
            dataType: 'json',
            data: {action: 'editBrandConnect',name:value,id:obj}
        }).done(function (data) {
            if (data.result!=false) {
                success_tip('изменено успешно');
            }
            else {
                error_tip('Ошибка');
            }
        }).fail(function () {
            error_tip('Ошибка сервера');
        });
    };
    
        Plugin.prototype.deleteBrandConnect = function (obj) {
        var widget = this;
        $.ajax({
            type: "POST",
            url: '/admin/controllers/brandconnect.controller.php',
            dataType: 'json',
            data: {action: 'deleteBrandConnect',id:obj}
        }).done(function (data) {
            if (data.result!=false) {
                $('#brandconnectInlay #tr_main'+obj).remove();
                success_tip('удалено успешно');
            }
            else {
                error_tip('Ошибка');
            }
        }).fail(function () {
            error_tip('Ошибка сервера');
        });
    };


    Plugin.prototype.bindAll = function () {

        var widget = this, search_timeout;
        
         $('#newBrandConnect', widget.element).unbind().bind('click', function (e) {
            widget.createBrandConnect();
        });
        $('.edit_connect', widget.element).unbind().bind('click', function (e) {
            widget.editBrandConnect($(this).attr('data-value'));
        });
         $('.delete_connect', widget.element).unbind().bind('click', function (e) {
            widget.deleteBrandConnect($(this).attr('data-value'));
        });
     
    };//END OF Plugin.prototype.bindAll


    
    
    
    Plugin.prototype.init = function () {
        var widget = this;

        widget.fillNewsAjax();
        widget.bindAll();
    };//END OF INIT

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            new Plugin($(this), options);
        });
    }
})(jQuery, window, document);
