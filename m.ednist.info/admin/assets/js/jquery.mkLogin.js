/*! Copyright (c) 2014 Max Kulyaev (Максим Куляев) zlada.net@gmail.com
 * Licensed under the MIT License (LICENSE.txt).
 * jquery.mkLogin
 * Version: 1.0
 */
;
(function ($, window, document, undefined) {
    var pluginName = 'mkLogin',
        defaults = {
            email: '',
            username: '',
            userrole: 0,
            pass: '',
            dbSalt: '',
            oldSalt: '',
            dbIteration: 0,
            transferSalt: '',
            transferIteration: 0,
            remember: 0,
            register: 0,
            reset: 0,
            restore: 0,
            active: 0
        };

    // конструктор плагина
    function Plugin(element, options) {
        var widget = this;
        widget.element = element;
        widget.config = $.extend({}, defaults, options);
        widget._defaults = defaults;
        widget._name = pluginName;
        $.each(widget.config, function (key, val) {
            if (typeof val === 'function') {
                widget.element.on(key + '.' + widget._name, function () {
                    return val(widget)
                });
            }
        });
        this.init();
    }

    //START message
    Plugin.prototype.message = function (message, type) {
        showMessage(message, type);
    };
    ////END OF message 

    //START logged
    Plugin.prototype.logged = function () {
        window.location.href = '/admin/news';
    };
    //END OF logged 

    //START unLogged
    Plugin.prototype.unLogged = function () {
        //window.history.go(0); return false;
        window.location.href = '/';
    };
    //END OF unLogged

    //START getSaltedHash
    //засолка пароля
    Plugin.prototype.getSaltedHash = function (password, salt, iterationCount) {
        var saltedHash = password;
        if (iterationCount < 1)
            iterationCount = 1;
        for (var i = 0; i < iterationCount; i++)
            saltedHash = hex_md5(salt + saltedHash);
        return saltedHash;
    };
    //END OF getSaltedHash     

    //START sendActivate
    //отправка сообщения об активации
    Plugin.prototype.sendActivate = function () {
        var widget = this;
        $.ajax({
            type: "POST",
            url: '/admin/controllers/user.controller.php',
            dataType: 'json',
            data: {
                sendActivation: widget.config.email
            }
        }).done(function (data) {
            if ('1' == data.send) {
                widget.message('На Ваш email повторно отправлено сообщение об активации!', 'ok');
            }
            else {
                widget.message('Не удалось отправить сообщение об активации');
            }
        }).fail(function () {
        });
    };
    //END OF sendActivate     

    //START filled
    //Проверка заполнения полей
    Plugin.prototype.filled = function () {
        var widget = this;
        widget.config.register = ($('#register').prop('checked')) ? 1 : 0;
        widget.config.email = $.trim($('#email').val());


        if ($('#showPass').prop('checked')) {
            widget.config.pass = $.trim($('#passShow').val());
        }
        else {
            widget.config.pass = $.trim($('#pass').val());
        }

        if (1 == widget.config.register) {
            widget.config.username = $.trim($('#userName').val());
            widget.config.userrole = $.trim($('#userRole').val());
            if ('' == widget.config.email || '' == widget.config.pass || '' == widget.config.userrole || '' == widget.config.username) {
                return false;
            }
            else {
                return true;
            }
        } else if ('' == widget.config.email || '' == widget.config.pass) {
            if (0 == widget.config.reset) {
                return false;
            } else {
                return true;
            }
        }
        else {
            return true;
        }
    };
    //END OF filled 

    //START validateEmail
    //Проверка емейла регулярним виражением
    Plugin.prototype.validateEmail = function (email) { //Эмейл похож на настоящий?
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    };
    //END OF validateEmail   

    //START go
    Plugin.prototype.go = function (e) {
        var widget = this, email, pass;
        if (widget.filled()) {
            if (!widget.validateEmail($.trim($('#email').val()))) {
                widget.message('Это не похоже на эмейл!');
            }
            else {
                widget.message('');
                //ПРОВЕРКА ПРОЙДЕНА!                    
                widget.config.remember = ($('#remember').prop('checked')) ? 1 : 0;
                widget.config.register = ($('#register').prop('checked')) ? 1 : 0;
                if (1 == widget.config.register) {//регистрация
                    widget.register();
                }
                else if (1 == widget.config.reset) {
                    widget.resetPass();
                }
                else if (1 == widget.config.restore) {
                    widget.restore();
                }
                else {
                    widget.login();
                }
            }
        }
        else {
            widget.message('Заполните все поля!');
            if ('' == widget.config.email)
                $('#email').mkBlink({interval: 100, timeout: 900});
            if ('' == widget.config.pass)
                $('#pass').mkBlink({interval: 100, timeout: 900});
            if ('' == widget.config.userrole)
                $('#userRole').mkBlink({interval: 100, timeout: 900});
            if ('' == widget.config.username)
                $('#userName').mkBlink({interval: 100, timeout: 900});
        }
    };
    //END OF go

    //START register
    //Регистрация (проверка)
    Plugin.prototype.register = function () {
        var widget = this;
        $.ajax({
            type: "POST",
            url: '/admin/controllers/user.controller.php',
            dataType: 'json',
            data: {
                register: widget.config.email
            }
        }).done(function (data) {
            if ('exists' != data.db_iteration) {
                widget.config.dbSalt = data.db_salt;
                widget.config.dbIteration = data.db_iteration;
                widget.config.pass = widget.getSaltedHash(widget.config.pass, widget.config.dbSalt, widget.config.dbIteration);//Засолка пароля
                console.log(widget.config.pass);

                widget.register2();
            }
            else {
                widget.message('Пользователь с таким именем уже существует!');
            }
        }).fail(function () {
        });
    };
    //END OF register   

    //START register2
    // Регистрация (сохранения)
    Plugin.prototype.register2 = function () {
        var widget = this;
        widget.config.active = ($('#active').prop('checked')) ? 1 : 0;
        $.ajax({
            type: "POST",
            url: '/admin/controllers/user.controller.php',
            dataType: 'json',
            data: {
                register2: widget.config.email,
                pass: widget.config.pass,
                dbSalt: widget.config.dbSalt,
                dbIteration: widget.config.dbIteration,
                username: widget.config.username,
                userrole: widget.config.userrole,
                active: widget.config.active
            }
        }).done(function (data) {
            if ('0' != data.register) {
                if ('0' == data.active)
                    widget.message('На email ' + widget.config.email + ' высланы инструкции для потверждения ', 'ok');
                else
                    widget.message('Ви удачно зарегестрировались', 'ok');
                widget.config.userId = data.register;
                $('#active').prop('checked', false);
                if (widget.config.register == 1)
                    getUserList();
            }
            else {
                widget.message('Ошибка');
            }
        }).fail(function () {
        });
    };
    //END OF register2   

    //START restore
    //Востановления (проверка)
    Plugin.prototype.restore = function () { //
        var widget = this;
        $.ajax({
            type: "POST",
            url: '/admin/controllers/user.controller.php',
            dataType: 'json',
            data: {
                restore: widget.config.email
            }
        }).done(function (data) {
            if ('' != data.db_iteration) {
                widget.config.dbSalt = data.db_salt;
                widget.config.dbIteration = data.db_iteration;
                widget.config.pass = widget.getSaltedHash(widget.config.pass, widget.config.dbSalt, widget.config.dbIteration);//Засолка пароля
                widget.restore2();
            }
        }).fail(function () {
        });
    };
    //END OF restore

    //START restore2
    Plugin.prototype.restore2 = function () { //  
        var widget = this;
        $.ajax({
            type: "POST",
            url: '/admin/controllers/user.controller.php',
            //dataType:'json',
            data: {
                restore2: widget.config.email,
                pass: widget.config.pass,
                dbSalt: widget.config.dbSalt,
                dbIteration: widget.config.dbIteration,
                oldSalt: widget.config.oldSalt,
                username: widget.config.username,
                userrole: widget.config.userrole
            }
        }).done(function (data) {
            if ('' != data) {
                widget.config.restore = 0;
                widget.go();
            }
            else {
                widget.message('Ошибка');
            }
        }).fail(function () {
        });
    };
    //END OF restore2   

    //START login
    //Вход
    Plugin.prototype.login = function () {
        var widget = this;
        $.ajax({
            type: "POST",
            url: '/admin/controllers/user.controller.php',
            dataType: 'json',
            data: {
                login1: widget.config.email
            }
        }).done(function (data) {
            if ('notExists' != data.db_iteration) {
                widget.config.dbSalt = data.db_salt;
                widget.config.dbIteration = data.db_iteration;
                widget.config.transferSalt = data.transfer_salt;
                widget.config.transferIteration = data.transfer_iteration;
                widget.config.active = data.user_active;
                if (0 == widget.config.active) {
                    widget.message('E-MAIL не подтвержден! <a id="sendActivate" href="#">Выслать форму подтверждения повторно?</a>', 'err');
                    $('#sendActivate').bind('click', function () {
                        widget.sendActivate();
                        return false;
                    })
                }
                else {
                    widget.config.pass = widget.getSaltedHash(widget.config.pass, widget.config.dbSalt, widget.config.dbIteration);//Засолка пароля
                    widget.config.pass = widget.getSaltedHash(widget.config.pass, widget.config.transferSalt, widget.config.transferIteration);//Засолка пароля2
                    $.ajax({
                        type: "POST",
                        url: '/admin/controllers/user.controller.php',
                        dataType: 'json',
                        data: {
                            login2: widget.config.email,
                            pass: widget.config.pass,
                            transferSalt: widget.config.transferSalt,
                            transferIteration: widget.config.transferIteration,
                            remember: widget.config.remember
                        }
                    }).done(function (data) {
                        if (1 == data.login) {
                            widget.logged();
                        }
                        else {
                            widget.message('неправильный пароль');
                        }
                    }).fail(function () {
                    });
                }
            }
            else {
                widget.message('пользователя с таким E-MAIL не существует');
            }
        }).fail(function () {
        });
    };
    //END OF login

    //START logout
    //Виход
    Plugin.prototype.logout = function () {
        var widget = this;
        $.ajax({
            type: "POST",
            url: '/admin/controllers/user.controller.php',
            dataType: 'json',
            data: {
                logout: 1
            }
        }).done(function (data) {
            //console.log();
            if ('1' == data.logout) {
                widget.unLogged();
            }
            else {
                widget.logged();
            }
        }).fail(function () {
        });
    };
    //END OF logout     

    //START resetPass
    //Сбросить пароль
    Plugin.prototype.resetPass = function () {
        
        var widget = this, email = $.trim($('#email').val());
        if ('' != email) {
            if (widget.validateEmail(email)) {
                $.ajax({
                    type: "POST",
                    url: '/admin/controllers/user.controller.php',
                    dataType: 'json',
                    data: {
                        reset: email
                    }
                }).done(function (data) {
                    
                    if (1 == data.reset) {
                        widget.message('Новий пароль отправлен на Ваш email', 'ok');
                    }
                    else {
                        widget.message('Ошибка');
                    }
                }).fail(function () {
                });
            }
            else {
                widget.message('Это не похоже на эмейл!');
            }
        }
    };
    //END OF resetPass

    //START changeFields
    Plugin.prototype.changeFields = function () {
        if ($('#showPass').prop('checked')) {
            $('#passShow').attr('value', $('#pass').val()).show().focus();
            $('#pass').hide();
        }
        else {
            $('#pass').attr('value', $('#passShow').val()).show().focus();
            $('#passShow').hide();
        }
    };
    //END changeFields

    //START clearPassField
    //Очистка поля пароля
    Plugin.prototype.clearPassField = function () {
        $('#pass').val('');
        $('#passShow').val('');
    };
    //END clearPassField 

    Plugin.prototype.bindAll = function () {
        var widget = this;
        $('#showPass').unbind().bind('change', function (e) { //показать пароль
            widget.changeFields();
        });
        //Очистка полей(ESC) или вход(ENTER)            
        $('#email').unbind().bind('keyup', function (e) {
            if (e.which == 27) {//esc                 
                $(this).val('');
            }
            else if (e.which == 13) {//enter
                widget.go();
            }
        }).bind('blur', function (e) { //проверить эмэйл
            if (!widget.validateEmail($.trim($('#email').val()))) {
                widget.message('Это не похоже на эмейл!');
                if ($.trim($('#email').val()) != '')
                    $('#email').focus();
            }
            else {
                widget.message('');
            }
        });
        $('#pass').unbind().bind('keyup', function (e) {
            if (e.which == 27) {//esc                 
                widget.clearPassField();
            }
            else if (e.which == 13) {//enter
                widget.go();
            }
        });
        $('#passShow').unbind().bind('keyup', function (e) {
            if (e.which == 27) {//esc                 
                widget.clearPassField();
            }
            else if (e.which == 13) {//enter
                widget.go();
            }
        });
        $('#login').unbind().bind('click', function (e) {
            widget.go();
        });
        $('#logout').unbind().bind('click', function (e) {
            widget.logout();
        });
        $('#resetPass').unbind().bind('click', function (e) {
            widget.resetPass();
        });
    };
    //END OF Plugin.prototype.bindAll  

    //START init
    Plugin.prototype.init = function () {
        var widget = this;
        widget.element.trigger('before.' + widget._name);
        widget.bindAll();//Привязка всех элементов
        widget.element.trigger('callback.' + widget._name);
    };
    //END OF INIT


    $.fn[pluginName] = function (options) {
        return this.each(function () {
            new Plugin($(this), options);
        });
    }

})(jQuery, window, document);