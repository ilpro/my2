/*! Copyright (c) 2014 Max Kulyaev (Максим Куляев) 1audiodesign@gmail.com

 * Licensed under the MIT License (LICENSE.txt).

 * jquery.mkAdminregionInlay

 * Version: 1.0

 */

;
(function ($, window, document, undefined) {

    var pluginName = 'mkAdminRegionInlay',

        defaults = {

            action: 'ajax',

            regionId: 0,

            regionDesc: ''

        };

    // конструктор плагина


    function Plugin(element, options) {

        var widget = this;


        widget.element = element;

        widget.metadata = widget.element.data('mkregion');

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

    Plugin.prototype.showregionInlay = function () {

        var widget = this;

        if (!$('#regionInlay').is(':visible')) {

            $('#regionInlay').addClass('show');

        }

    };//END OF Plugin.prototype.showregionInlay 

    Plugin.prototype.hideregionInlay = function () {

        var widget = this;

        if ($('#regionInlay').is(':visible')) {

            $('#regionInlay').removeClass('show');

            $.bbq.removeState(['region']);//удаляем хэш 	

        }

    };//END OF Plugin.prototype.hideregionInlay

    Plugin.prototype.initRedactor = function () {

        tinymce.init({

            selector: "textarea#maincontent",

            height: 500,

            language: 'ru',

            apply_source_formatting: true,

            plugins: [

                "advlist autolink lists link image charmap print preview anchor",

                "searchreplace visualblocks code fullscreen",

                //  "insertdatetime media table contextmenu paste moxiemanager"

            ],

            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"

        });

    };//END OF Plugin.prototype.initRedactor


    Plugin.prototype.fillregionAjax = function () {

        var widget = this;

        $('#condition').mkCondition({action: 'blink', color: 'yellow'});

        $.ajax({

            type: "POST",

            url: '/admin/controllers/regions.controller.php',

            dataType: 'json',

            data: {getregionItem: widget.config.regionId}

        }).done(function (data) {

            if (null != data) {

                $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});

                widget.fillregionData(data);

                console.log(data);

            }

            else {

                $('#condition').mkCondition({
                    condition: 'tooltip',
                    text: 'Ответ сервера не соответсвует ожидаемому',
                    action: 'show',
                    color: 'red'
                });

            }

        }).fail(function () {

            $('#condition').mkCondition({condition: 'tooltip', text: 'Ошибка сервера', action: 'show', color: 'red'});

        });

    };//END OF Plugin.prototype.fillOrderAjax 


    Plugin.prototype.fillregionData = function (data) {

        var widget = this;

        $('#regionInlayId', widget.element).text(widget.config.regionId);

        $('#regionName', widget.element).val(data.regionName);

        $('#regionSearch', widget.element).val(data.regionSearch);

        $('#gallery', widget.element).html(data.regionImgsHTML);


        tinyMCE.execCommand('mceSetContent', false, data.regionDesc);


        widget.showregionInlay();

        widget.bindAll();

    };//END OF Plugin.prototype.fillOrderData


    Plugin.prototype.getPrevNext = function (e, prevNext, regionId) {

        var widget = this;

        if ('prev' == prevNext) {// show prev region

            $('.regionStripe').each(function () {


                if (parseInt($(this).data('mkregion').regionId) == regionId) {

                    if (null != $(this).next().data('mkregion')) {

                        $.bbq.pushState({region: parseInt($(this).next().data('mkregion').regionId)});

                        ///Если будет фильтрация

                        /*

                         if($(this).next().is(':visible')){

                         console.log(regionId);

                         //$.bbq.pushState({region:parseInt($(this).next().data('mkregion').regionId)});

                         }

                         else{

                         //	widget.getPrevNext(e,'prev',parseInt($(this).next().data('mkregion').regionId));

                         }

                         */

                    }

                    else {

                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Больше тегов нет',
                            action: 'fade',
                            fadeout: 1500,
                            color: 'red'
                        });

                    }

                }

            });

        }

        else if ('next' == prevNext) {// show next region

            $('.regionStripe').each(function () {

                if ($(this).data('mkregion').regionId == regionId) {

                    if (null != $(this).prev().data('mkregion')) {

                        $.bbq.pushState({region: parseInt($(this).prev().data('mkregion').regionId)});

                        /*

                         if($(this).prev().is(':visible')){

                         $.bbq.pushState({order:parseInt($(this).prev().data('mkorder').orderId)});

                         }

                         else{

                         widget.getPrevNext(e,'next',parseInt($(this).prev().data('mkorder').orderId));

                         }

                         */

                    }

                    else {

                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Нет тегов новее',
                            action: 'fade',
                            fadeout: 1500,
                            color: 'red'
                        });

                    }

                }

            });

        }

    };//END OF Plugin.prototype.getPrevNext    

    // удалить новость
    Plugin.prototype.deleteRegion = function () {

        var widget = this, ids = [];
        ids.push(widget.config.regionId);

        if (ids.length > 0 && confirm("Удалить новость?")) {

            $.ajax({
                type: "POST",
                url: '/admin/controllers/regions.controller.php',
                dataType: 'json',
                data: {deleteRegion: ids}
            }).done(function (data) {
                if ('ok' == data.clean) {
                    success_tip("Новость удалена");

                    $('[data-mkregion="{"regionId":"' + ids + '"}"]').remove();

                    widget.hideregionInlay();
                }
                else {
                    error_tip("Не удалось удалить выбранные новости\n" + data);
                }
            }).fail(function () {
                error_tip("Ошибка сервера");
            });
        } else {
            error_tip("Нужно отметить хотя бы одну новость");
        }
    };

    Plugin.prototype.bindAll = function () {

        var widget = this, search_timeout;


        $('.delete', widget.element).unbind().bind('click', function (e) {
            widget.deleteRegion();
        });

        $('.close', widget.element).unbind().bind('click', function (e) {

            widget.hideregionInlay();

        });

        $('.update', widget.element).unbind().bind('click', function (e) {

            widget.fillregionAjax();

        });

        $('.switch-left').unbind().bind('click', function (e) {

            widget.getPrevNext(e, 'prev', widget.config.regionId);

        });

        $('.switch-right').unbind().bind('click', function (e) {

            widget.getPrevNext(e, 'next', widget.config.regionId);

        });


        $('.save', widget.element).unbind().bind('click', function (e) {

            widget.saveAll();

        });

        $('#gallery').trigger('callimgs.' + widget._name);

    };//END OF Plugin.prototype.bindAll

    Plugin.prototype.bindimgs = function () {

        var widget = this;

        $('#gallery .del').unbind().bind('click', function () { // удалить картинку

            if (confirm("Удалить?")) {

                var element = $(this);


                $('#condition').mkCondition({action: 'blink', color: 'yellow'});

                $.ajax({

                    type: "POST",

                    url: '/admin/controllers/upload.controller.php',

                    dataType: 'json',

                    data: {delImgRegionId: widget.config.regionId}

                }).done(function (data) {

                    if (data = true) {

                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});

                        $('[data-mkregion="{"regionId":"' + widget.config.regionId + '"}"] .avatar img').remove();

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

        $('#gallery .show').unbind().bind('click', function () { // посмотреть/увеличить картинку

            var link = $(this).parent().data('img-sprite').imgpath + 'big.jpg', d = new Date();

            $('#showImageWindow .inputs').html($('<img />').attr('src', link + '?' + d.getTime()));

            $('#showImageWindow').show();


            return false;

        })

        $('#gallery .link').unbind().bind('click', function () { // взять ссылку на картинку

            var link = $(this).parent().find('img').attr('src'), d = new Date();

            link = link.replace('gallery', 'big');

            var getlink = prompt('Скопируйте ссылку для дальнейшего использования.', link);


            return false;

        })

        $('#gallery .cut').unbind().bind('click', function () { // обрезка картинки

            var link = $(this).parent().data('img-sprite').imgpath + 'orig.jpg',

                element = $(this), d = new Date(),

                val = $(this).parent().data('img-sprite');


            $('#showImageCropWindow .inputs').html($('<img />').attr('src', link + '?' + d.getTime()).addClass('target'));

            $('#showImageCropWindow').unbind().show();

            //setTimeout(function(){

            $('#showImageCropWindow img').Jcrop({

                onSelect: function (c) {
                    window.c = c;
                },

                //onChange: showCoords,

                aspectRatio: 1,

                minSize: [200, 200],

                boxWidth: 1000, boxHeight: 600,//

                setSelect: [0, 0, 200, 200],

                bgOpacity: 0.5,

                bgColor: 'black',

                addClass: 'jcrop-dark'

            }, function () {

                jcrop_api = this;

            });

            $('.savecrop').unbind().bind('click', function () { // сохранение фрагмента картинки


                $('#condition').mkCondition({action: 'blink', color: 'yellow'});

                $.ajax({

                    type: "POST",

                    url: '/admin/controllers/upload.controller.php',

                    data: {cropImgRegionId: widget.config.regionId, imgName: val.imgname, c: window.c}

                }).done(function (data) {

                    if (data != 'error') {

                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});

                        element.parent().find('img').attr('src', data);

                        $('#showImageCropWindow .close').trigger('click');


                        imgInList = $('[data-mkregion="{"regionId":"' + widget.config.regionId + '"}"] .avatar img');

                        src = imgInList.attr('src');

                        imgInList.attr('src', src + '?' + (new Date()).getTime());


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

            })


            return false;

        })

    };//END OF Plugin.prototype.bindimgs  

    Plugin.prototype.saveAll = function () {

        var widget = this;

        widget.config.regionName = $.trim($('#regionName', widget.element).val());

        //  tinyMCE.activeEditor.save();

        //widget.config.regionDesc=tinyMCE.activeEditor.getContent();

        widget.config.regionSearch = $.trim($('#regionSearch', widget.element).val());


        $('#condition').mkCondition({action: 'blink', color: 'yellow'});

        $.ajax({

            type: "POST",

            url: '/admin/controllers/regions.controller.php',

            dataType: 'json',

            data: {
                saveAll: widget.config.regionId,

                regionName: widget.config.regionName,

                /*regionTranslit:widget.config.regionTranslit,

                 regionDesc:widget.config.regionDesc,*/

                regionSearch: widget.config.regionSearch

            }

        }).done(function (data) {

            console.log(data);

            if (null != data) {

                if (null != data.error) { //ошибка!

                    $('#condition').mkCondition({
                        condition: 'tooltip',
                        text: 'Сервер ответил ошибкой',
                        action: 'show',
                        color: 'red'
                    });

                }

                else if (null != data.saved) {//ОК!

                    $('#condition').mkCondition({
                        condition: 'tooltip',
                        text: data.saved,
                        action: 'fade',
                        action: 'fade',
                        color: 'green',
                        fadeout: 1000
                    });

                    $('#dateupdate', widget.element).val(data.saved); //Обновляем дату модификации!

                }

                else {

                    $('#condition').mkCondition({
                        condition: 'tooltip',
                        text: 'Ответ сервера не соответсвует ожидаемому',
                        action: 'show',
                        color: 'red'
                    });

                }

            }

            else {

                $('#condition').mkCondition({
                    condition: 'tooltip',
                    text: 'Сервер прислал пустой ответ',
                    action: 'show',
                    color: 'red'
                });

            }

        }).fail(function () {

            $('#condition').mkCondition({condition: 'tooltip', text: 'Ошибка сервера', action: 'show', color: 'red'});

        });


    };//END OF Plugin.prototype.saveAll                                              

    Plugin.prototype.init = function () {

        var widget = this;

        if ('ajax' == widget.config.action) {//запаолнение по аджакс-запросу

            widget.fillregionAjax();

        }

        else if ('data' == widget.config.action) {//Заполнение по предоставляемой информации


            widget.fillregionData();

        }

        // обработчики кнопок картинок

        $('#gallery').bind('callimgs.mkAdminRegionInlay', function () {

            widget.bindimgs();

        });

        //widget.initRedactor();

        widget.bindAll();


    };//END OF INIT

    $.fn[pluginName] = function (options) {

        return this.each(function () {

            new Plugin($(this), options);

        });

    }

})(jQuery, window, document);

