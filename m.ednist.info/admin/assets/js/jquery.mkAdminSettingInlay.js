
(function ($, window, document, undefined) {
    var pluginName = 'mkAdminSettingInlay',
        defaults = {
            action: 'ajax',
            settingId: 0,
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


    Plugin.prototype.bindAll = function () {

        var widget = this, search_timeout;

        $('#imgAddFromLink').unbind().bind('click', function () {
            widget.imgAddFromLink();
        });
        $('#imgAddFromLink2').unbind().bind('click', function () {
            widget.imgAddFromLink2();
        });
        
        $('#gallery').trigger('callimgs.' + widget._name);
        $('#gallery2').trigger('callimgs.' + widget._name);
        
    };//END OF Plugin.prototype.bindAll


    
    //загрузка фото по ссилке
    Plugin.prototype.imgAddFromLink = function () {
        var widget = this;
        if (link = prompt('Введите ссылку на картику')) {

            $.ajax({
                type: "POST",
                url: '/admin/controllers/upload.controller.php',
                data: {imgSettingAddLink: link}
            }).done(function (data) {
                if (typeof data != 'undefined') {
                    $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                    $('#gallery').html(data);

                    $('#gallery').trigger('callimgs.mkAdminSettingInlay');
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
    
     Plugin.prototype.imgAddFromLink2 = function () {
        var widget = this;
        if (link = prompt('Введите ссылку на картику')) {

            $.ajax({
                type: "POST",
                url: '/admin/controllers/upload.controller.php',
                data: {imgSettingAddLink2: link}
            }).done(function (data) {
                if (typeof data != 'undefined') {
                    $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                    $('#gallery2').html(data);

                    $('#gallery2').trigger('callimgs.mkAdminSettingInlay');
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
    //удаляем картинку по клику на крестику
    Plugin.prototype.bindimgs = function (e) {

        var widget = this;
         $('#settingsInlay').unbind();
     // удалить картинку
        $('#settingsInlay').on('click','#gallery .del',function(e){
            e.preventDefault();
            if (confirm("Удалить?")) {
                var element = $(this);
                widget.config.name='watermark.png';
                $.ajax({
                    type: "POST",
                    url: '/admin/controllers/upload.controller.php',
                    dataType: 'json',
                    data: {delImgSetting: widget.config.name}
                }).done(function (data) {
                    if (data = true) {
                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
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
        
         $('#settingsInlay').on('click','#gallery2 .del',function(e){
             e.preventDefault();
            if (confirm("Удалить?")) {
                var element = $(this);
                widget.config.name='soclogo.jpg';
                $.ajax({
                    type: "POST",
                    url: '/admin/controllers/upload.controller.php',
                    dataType: 'json',
                    data: {delImgSetting: widget.config.name}
                }).done(function (data) {
                    if (data = true) {
                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
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
        
        

       
    };//END OF Plugin.prototype.bindimgs
    
    Plugin.prototype.init = function () {
        var widget = this;
        // обработчики кнопок картинок

        $('#gallery').bind('callimgs.mkAdminSettingInlay', function () {
            widget.bindimgs();
        });
         $('#gallery2').bind('callimgs.mkAdminSettingInlay', function () {
            widget.bindimgs();
        });
        widget.bindAll();
    };//END OF INIT

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            new Plugin($(this), options);
        });
    }
})(jQuery, window, document);
