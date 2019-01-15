function baseName(str) {
    var base = new String(str).substring(str.lastIndexOf('/') + 1);
    return base;
}
//вивести сообщение
function showMessage(message, type) {
    if (message == '') {
        $('#condition').mkCondition({action: 'fade'});
    } else {
        if (type == 'ok') {
            $('#condition').mkCondition({
                condition: 'tooltip',
                text: message,
                action: 'fade',
                color: 'green',
                fadeout: 2500
            });
        } else {
            $('#condition').mkCondition({condition: 'tooltip', text: message, action: 'show', color: 'red'});
        }
    }
}
//обнулить
function hideMessage() {
    showMessage('', '');
}
//генериуем salt
function getSaltedHash(password, salt, iterationCount) {
    var saltedHash = password;
    if (iterationCount < 1)
        iterationCount = 1;
    for (var i = 0; i < iterationCount; i++)
        saltedHash = hex_md5(salt + saltedHash);
    return saltedHash;
}
//проверка валидности емейла
function validateEmail(email) { //Эмейл похож на настоящий?
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}
;
//список юзеров
function getUserList() {
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: '/admin/controllers/user.controller.php',
        data: {action: 'get_list', page: '', search: ''},
    }).done(function (obj) {
        if (obj.res == 'ok') {
            $('#listing').stop(true, true).html(obj.val);
            $('.add-line').fadeOut(400);
            $('#email').val('');
            $('#pass').val('');
            $('.mkSelect').val('');
            $('#userName').val('');
            $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
        }else if(obj.res=='notenaught') {
            showMessage('Недостаточно прав!!!');
        }
        else {
          //  showMessage('Ответ сервера не соответствует ожидаемому');
        }

    }).fail(function () {
    });
}
//начало редактирования юзера, фокус
function getEditUser(id) {
    $('#condition').mkCondition({action: 'blink', color: 'yellow'});
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: '/admin/controllers/user.controller.php',
        data: {action: 'edit_user', id: id},
    }).done(function (obj) {
        if (obj.res == 'ok') {
            $('#line' + id).stop(true, true).mkBlink().addClass('filter-user').html(obj.val);
            $('#line' + id).find('.userName').focus();
            $('#line' + id).find('.userName').select();
            hideMessage();
        } else {
            showMessage('Ответ сервера не соответствует ожидаемому');
        }
    }).fail(function () {
        showMessage('Ошибка сервера');
    });
}
//редактирование юзера
function editUser(id) {
    var email = $.trim($('#line' + id + ' .email').val()),
        pass = $.trim($('#line' + id + ' .password').val()),
        userrole = $.trim($('#line' + id + ' .userRole').val()),
        username = $.trim($('#line' + id + ' .userName').val());
    $('#condition').mkCondition({action: 'blink', color: 'yellow'});
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: '/admin/controllers/user.controller.php',
        data: {edituser: email, userId: id},
    }).done(function (data) {
        active = ($('#active' + id).prop('checked')) ? 1 : 0;
        if (data.db_iteration != 'exists') {
            dbSalt = data.db_salt;
            dbIteration = data.db_iteration;
            if (userrole != '' && username != '' && email != '') {
                if (!validateEmail(email)) {
                    showMessage('Это не похоже на email');
                } else {
                    if (pass != '')
                        pass = getSaltedHash(pass, dbSalt, dbIteration);//Засолка пароля
                    $.ajax({
                        type: "POST",
                        url: '/admin/controllers/user.controller.php',
                        dataType: 'json',
                        data: {
                            edituserId: id,
                            email: email,
                            pass: pass,
                            dbSalt: dbSalt,
                            dbIteration: dbIteration,
                            username: username,
                            userrole: userrole,
                            active: active
                        }
                    }).done(function (data) {
                        if ('1' == data.restore) {
                            getUser(id);
                            showMessage('Пользователь ' + username + ' сохранен.', 'ok');
                        }
                        else {
                            showMessage('Ошибка');
                        }
                    }).fail(function () {
                    });
                }
            } else {
                showMessage('Заполните все поля, пожалуйста');
            }
        } else {
            showMessage('Такой email уже существует');
        }
    }).fail(function () {
        showMessage('Ошибка сервера');
    });
}
//получить конкретного юзера
function getUser(id) {
    $('#condition').mkCondition({action: 'blink', color: 'yellow'});
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: '/admin/controllers/user.controller.php',
        data: {action: 'get_user', id: id},
    }).done(function (obj) {
        if (obj.res == 'ok') {
            $('#line' + id).stop(true, true).mkBlink().removeClass('filter-user').html(obj.val);
            hideMessage();
        } else {
            showMessage('Ответ сервера не соответствует ожидаемому');
        }
    }).fail(function () {
        showMessage('Ошибка сервера');
    });
}
//удалить юзера
function deleteUser(id) {
    if (confirm("Вы уверены?")) {
        $('#condition').mkCondition({action: 'blink', color: 'yellow'});
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: '/admin/controllers/user.controller.php',
            data: {action: 'dell_user', id: id},
        }).done(function (obj) {
            if (obj.res == 'ok') {
                $('#line' + id).mkBlink().slideUp(400).remove();
                showMessage(obj.val, 'ok');
            } else {
                showMessage('Ответ сервера не соответствует ожидаемому');
            }
        }).fail(function () {
            showMessage('Ошибка сервера');
        });
    }
    return false;
}

$(document).ready(function () {
    getUserList();
    $(window).mkLogin();
    $('#clear').bind('click.mkBlink', function (e) {
        $('.add-line').mkBlink();
        $('#email').val('');
        $('#pass').val('');
        $('.mkSelect').val('');
        $('#userName').val('');
        $('.add').trigger('click');
    });
    $('.add-line').hide();
    $('.add').click(function () {
        $('.add-line').show();
        hideMessage();
    });
});

