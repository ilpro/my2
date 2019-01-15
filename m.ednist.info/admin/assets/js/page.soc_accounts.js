function win_open(url) {

    var signinWin = window.open(url, "SignIn", "width=780,height=410,toolbar=0,scrollbars=0,status=0,resizable=0,location=0,menuBar=0");

    signinWin.focus();

}


$(document).ready(function () {


    $('body').on('click', '.auth', function () {


        var data = {};

        if ($(this).data('soc') == 'tw') {

            data['tw_log_url'] = 1;

        }

        if ($(this).data('soc') == 'fb') {

            data['fb_log_url'] = 1;

        }


        $.ajax({

            url: '/admin/controllers/crosspost.controller.php',

            data: data,

            async: false,

            success: function (data) {

                if (data != false) {

                    win_open(data);

                }

                else {

                    console.log('already connected');

                    return false;

                }

            }

        });


        return false;

    });


    $('body').on('click', '.deny_auth', function () {


        var data = {};

        if ($(this).data('soc') == 'tw') {

            data['tw_deny'] = 1;

        }

        if ($(this).data('soc') == 'fb') {

            data['fb_deny'] = 1;

        }

        if ($(this).data('soc') == 'vk') {

            data['vk_deny'] = 1;

        }


        $.ajax({

            url: '/admin/controllers/crosspost.controller.php',

            data: data,

            async: false,

            success: function (data) {

                location.reload();

            }

        });


        return false;

    });


    $('#send_vk_token').click(function () {


        var url = $('input[name=vk_access_token]').val();


        $.ajax({

            url: '/admin/controllers/crosspost.controller.php',

            data: {url: url, use_method: 'get_access_token_vk'},

            async: false,

            success: function (data) {

                location.reload();

            }

        });

    });


    $('#save_settings').click(function () {


        var form_data = $('#config_form').serialize();

        form_data += '&use_method=save_settings';


        $.ajax({

            url: '/admin/controllers/crosspost.controller.php',

            data: form_data,

            async: false,

            success: function (data) {

                $('#config_form').append(data);

            }

        });

    });


});