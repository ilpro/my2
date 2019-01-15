/**
 * Lazy Load plugin
 */
;
(function ($, window, document, undefined) {
    var pluginName = 'mkCaptcha',
            defaults = {//задем переменные здесь
                email: '',
                themes: '',
                message: '',
                captcha: 0
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

    //Проверка заполнения полей
    Plugin.prototype.filled = function () {
        var widget = this;
        widget.config.email = $.trim($('#inputEmail').val());
        widget.config.themes = $.trim($('#inputTheme').val());
        widget.config.message = $.trim($('#inputText2').val());
        widget.config.captcha = $.trim($('#inputCaptcha').val());

        if (widget.config.themes == '') {
            $('#errorThemes').html('Введіть тему');
        }
        else {
            $('#errorThemes').html('');
        }
        if (widget.config.email == '') {
            $('#errorEmail').html('Введіть емейл');
        }
        else {
            $('#errorEmail').html('');
        }
        if (widget.config.message == '') {
            $('#errorMessage').html('Введіть текст повідомлення');
        }
        else {
            $('#errorMessage').html('');
        }
        if (widget.config.captcha == '') {
            $('#errorCaptcha').html('Введіть цифри з картинки');
        }
        else {
            $('#errorCaptcha').html('');
        }
        if (widget.config.themes == '' || widget.config.email == '' || widget.config.message == '' || widget.config.captcha == '') {
            return false;
        }
        else {
            return true;
        }

    };

    //Проверка емейла регулярним виражением
    Plugin.prototype.validateEmail = function (email) { //Эмейл похож на настоящий?
        console.log(email);
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    };

    Plugin.prototype.sendLetter = function (e) {
        var widget = this;
        if (widget.filled()) {
            if (!widget.validateEmail($.trim($('#inputEmail').val()))) {
                $('#errorEmail').html('Введіть коректний емейл');
            }
            else {
                $.ajax({
                    type: "POST",
                    url: '/controllers/about.controller.php',
                    dataType: 'json',
                    data: {action: 'sendLetter',
                        email: widget.config.email,
                        themes: widget.config.themes,
                        message: widget.config.message,
                        captcha: widget.config.captcha
                    }
                }).done(function (data) {
                    if (data.result) {
                         $("button.close").trigger("click");
                         alert("Повідомлення успішно відправлено!");
                    } else {
                        $('#errorCaptcha').html('Неправильні цифри');
                    }

                }).fail(function () {

                });
            }
        }
        else {
            console.log('aaa');
        }


    };
     //очистка форми
    Plugin.prototype.clearModal = function () { //Эмейл похож на настоящий?
             $('#errorThemes').html('');
             $('#errorEmail').html('');
             $('#errorMessage').html('');
             $('#errorCaptcha').html('');
             document.getElementById('inputEmail').value = '';
             document.getElementById('inputTheme').value = '';
             document.getElementById('inputText2').value = '';
             document.getElementById('inputCaptcha').value = '';
    };

     //генерайия капчи
    Plugin.prototype.generateCaptcha = function () { //Эмейл похож на настоящий?
         $.ajax({
                    type: "POST",
                    url: '/controllers/about.controller.php',
                    dataType: 'json',
                    data: {action: 'generateCaptcha'
                    }
                }).done(function (data) {
                    if (data.result) {
                       $('#captcha').html(data.result);
                    } else {
                        
                    }

                }).fail(function () {

                });
    };
    
    Plugin.prototype.bindAll = function (e) { //метод для привязки всех событий
        var widget = this;

        $('#sendMessage').unbind().bind('click', function (e) {
            widget.sendLetter();
        });
        $('#myModal').on('hidden.bs.modal',function(e){
            widget.clearModal();
        });
        $('#myModal').on('show.bs.modal',function(e){
            widget.generateCaptcha();
        });
        

      
               
}; //END OF Plugin.prototype.bindAll


        Plugin.prototype.init = function () { //метод инициализации. В него зашиваем все,что нужно на старте работы плагина
            var widget = this;

            widget.bindAll();
        };//END OF INIT

$.fn[pluginName] = function (options) {
    return this.each(function () {
        new Plugin($(this), options);
    });
    }
}
)(jQuery, window, document);

