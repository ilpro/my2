
$(document).ready(function () {
    $('#settingsInlay').mkAdminSettingInlay();
    $('#settingsInlay').mkLogin();
    fillSettingAjax();
    //отправка форми на странице настройок по клику
    $('#save-settings').click(function () {
        var settingsParser = $("#settingsParser").is(":checked"),
                //  logo1=$("#logo1").val(),
                //    logo2=$("#logo2").val(),
                settingsParserInterval = $("#settingsParserInterval").val(),
                settingsParserDelete = $("#settingsParserDelete").val(),
                settingsSiteName = $("#settingsSiteName").val(),
                settingsSiteTitle = $("#settingsSiteTitle").val(),
                settingsDescription = $("#settingsDescription").val(),
                settingsTimeZona = $("#settingsTimeZona").val(),
                settingsEmail = $("#settingsEmail").val(),
                settingsPageFb = $("#settingsPageFb").val(),
                settingsPageTw = $("#settingsPageTw").val(),
                settingsPageVk = $("#settingsPageVk").val(),
                settingsPageGl = $("#settingsPageGl").val(),
                settingsPageIn = $("#settingsPageIn").val(),
                settingsPageOk = $("#settingsPageOk").val(),
                settingsPageYou = $("#settingsPageYou").val(),
                settingsPageRss = $("#settingsPageRss").val(),
                settingsCounter = $("#settingsCounter").val(),
                settingsCopyText = $("#settingsCopyText").val(),
                settingsTechWork = $("#settingsTechWork").is(":checked"),
                settingsPasteByCursor=$("#settingsPasteByCursor").val();
        $.ajax({
            type: "POST",
            url: '/admin/controllers/settings.controller.php',
            dataType: 'json',
            data: {action: "open_settings",
                settingsParser: settingsParser,
                settingsParserInterval: settingsParserInterval,
                settingsParserDelete: settingsParserDelete,
                settingsSiteName: settingsSiteName,
                settingsSiteTitle: settingsSiteTitle,
                settingsDescription: settingsDescription,
                settingsTimeZona: settingsTimeZona,
                settingsEmail: settingsEmail,
                settingsPageFb: settingsPageFb,
                settingsPageTw: settingsPageTw,
                settingsPageVk: settingsPageVk,
                settingsPageGl:settingsPageGl,
                settingsPageIn:settingsPageIn,
                settingsPageOk:settingsPageOk,
                settingsPageYou:settingsPageYou,
                settingsPageRss:settingsPageRss,
                settingsCounter: settingsCounter,
                settingsCopyText: settingsCopyText,
                settingsTechWork: settingsTechWork,
                settingsPasteByCursor:settingsPasteByCursor
            }
        }).done(function (data) {
            if (data) {
                success_tip('Удачное сохранения');
            }
            else {
                error_tip('Ошибка');
            }
        });
    });
    //Проверяем что есть на странице и загружаем если чтото есть(дание об картинках, поля форми с бд)
    function fillSettingAjax() {
        $.ajax({
            type: "POST",
            url: '/admin/controllers/settings.controller.php',
            dataType: 'json',
            data: {action2: "fill_settings"
            }
        }).done(function (data) {
            //тут витаскиваем дание заполнения форм
            if (data.ob == true) {
                var p = data.result;
                for (var key in p) {
                    var a = p[key];
                    for (var key2 in a) {
                        $('#' + key2).val(a[key2]);
                        if ($('#' + key2).is(":checkbox")) {
                            (1 == a[key2]) ? $('#' + key2).prop('checked', true) : $('#' + key2).prop('checked', false);
                        }
                    }
                }
                success_tip('Страница получена');
            }
            else {

            }
            if (data.ob2 == true) {
                $('#gallery').append(data.logo1);
                $('#gallery2').append(data.logo2);
            }
            else {

            }
        });
    }


});
//отправка картинки из папки
$('#newsGallery').mkUpload({inputName: 'settingimgfile[]', multiple: false});
$('#imgAddFromPath').click(function () {
    $(this).next().trigger('click');
});
$('#newsGallery2').mkUpload({inputName: 'settingimgfile2[]', multiple: false});
$('#imgAddFromPath2').click(function () {
    $(this).next().trigger('click');
});