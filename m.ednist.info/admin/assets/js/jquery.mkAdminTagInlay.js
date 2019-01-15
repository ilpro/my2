/*! Copyright (c) 2014 Max Kulyaev (Максим Куляев) 1audiodesign@gmail.com

 * Licensed under the MIT License (LICENSE.txt).

 * jquery.mkAdmintagInlay

 * Version: 1.0

 */

;
(function ($, window, document, undefined) {

    var pluginName = 'mkAdminTagInlay',

        defaults = {

            action: 'ajax',

            tagId: 0,

            tagDesc: ''

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

    Plugin.prototype.showtagInlay = function () {

        var widget = this;

        if (!$('#tagInlay').is(':visible')) {

            $('#tagInlay').addClass('show');

        }

    };//END OF Plugin.prototype.showtagInlay 

    Plugin.prototype.hidetagInlay = function () {

        var widget = this;

        if ($('#tagInlay').is(':visible')) {

            $('#tagInlay').removeClass('show');

            $.bbq.removeState(['tag']);//удаляем хэш 	

        }

    };//END OF Plugin.prototype.hidetagInlay

    Plugin.prototype.initRedactor = function () {

        tinymce.init({

            selector: "textarea#maincontent",

            height: 500,

            language: 'ru',

            apply_source_formatting: true,

            plugins: [

                "advlist autolink lists link image charmap print preview anchor",

                "searchreplace visualblocks code fullscreen",


            ],

            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"

        });

    };//END OF Plugin.prototype.initRedactor


    Plugin.prototype.filltagAjax = function () {

        var widget = this;

        $.ajax({

            type: "POST",

            url: '/admin/controllers/tags.controller.php',

            dataType: 'json',

            data: {gettagItem: widget.config.tagId}

        }).done(function (data) {

            if (data) {
                widget.filltagData(data);
                success_tip('Тег #'+widget.config.tagId+' получен');
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


    Plugin.prototype.filltagData = function (data) {

        var widget = this;

        $('#tagInlayId', widget.element).text(widget.config.tagId);

        $('#tagName', widget.element).val(data.tagName);

        $('#tagSearch', widget.element).val(data.tagSearch);

        $('#gallery', widget.element).html(data.tagImgsHTML);


        tinyMCE.execCommand('mceSetContent', false, data.tagDesc);

        widget.showtagInlay();

        widget.bindAll();

    };//END OF Plugin.prototype.fillOrderData


    Plugin.prototype.getPrevNext = function (e, prevNext, tagId) {

        var widget = this;

        if ('prev' == prevNext) {// show prev tag

            $('.tagStripe').each(function () {


                if (parseInt($(this).data('mktag').tagId) == tagId) {

                    if (null != $(this).next().data('mktag')) {

                        $.bbq.pushState({tag: parseInt($(this).next().data('mktag').tagId)});

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

        else if ('next' == prevNext) {// show next tag

            $('.tagStripe').each(function () {

                if ($(this).data('mktag').tagId == tagId) {

                    if (null != $(this).prev().data('mktag')) {

                        $.bbq.pushState({tag: parseInt($(this).prev().data('mktag').tagId)});

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
    Plugin.prototype.deleteTag = function () {

        var widget = this, ids = [];
        ids.push(widget.config.tagId);

        if (ids.length > 0 && confirm("Удалить новость?")) {

            $.ajax({
                type: "POST",
                url: '/admin/controllers/tags.controller.php',
                dataType: 'json',
                data: {deleteTag: ids}
            }).done(function (data) {
                if ('ok' == data.clean) {
                    success_tip("Тег удален");

                    $('[data-mktag="{"tagId":"' + ids + '"}"]').remove();

                    widget.hidetagInlay();
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

        $('.close', widget.element).unbind().bind('click', function (e) {

            widget.hidetagInlay();

        });
        $('.delete', widget.element).unbind().bind('click', function (e) {
            widget.deleteTag();
        });

        $('.update', widget.element).unbind().bind('click', function (e) {

            widget.filltagAjax();

        });

        $('.switch-left').unbind().bind('click', function (e) {

            widget.getPrevNext(e, 'prev', widget.config.tagId);

        });

        $('.switch-right').unbind().bind('click', function (e) {

            widget.getPrevNext(e, 'next', widget.config.tagId);

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

                    data: {delImgTagId: widget.config.tagId}

                }).done(function (data) {

                    if (data = true) {

                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});

                        $('[data-mktag="{"tagId":"' + widget.config.tagId + '"}"] .avatar img').remove();

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

                    data: {cropImgTagId: widget.config.tagId, imgName: val.imgname, c: window.c}

                }).done(function (data) {

                    if (data != 'error') {

                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});

                        element.parent().find('img').attr('src', data);

                        $('#showImageCropWindow .close').trigger('click');


                        imgInList = $('[data-mktag="{"tagId":"' + widget.config.tagId + '"}"] .avatar img');

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

        widget.config.tagName = $.trim($('#tagName', widget.element).val());

        //  tinyMCE.activeEditor.save();

        // widget.config.tagDesc=tinyMCE.activeEditor.getContent();

        widget.config.tagSearch = $.trim($('#tagSearch', widget.element).val());


        $('#condition').mkCondition({action: 'blink', color: 'yellow'});

        $.ajax({

            type: "POST",

            url: '/admin/controllers/tags.controller.php',

            dataType: 'json',

            data: {
                saveAll: widget.config.tagId,

                tagName: widget.config.tagName,

                /*tagTranslit:widget.config.tagTranslit,

                 tagDesc:widget.config.tagDesc,*/

                tagSearch: widget.config.tagSearch

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

                    success_tip('Сохранено '+data.saved);

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

            widget.filltagAjax();

        }

        else if ('data' == widget.config.action) {//Заполнение по предоставляемой информации


            widget.filltagData();

        }

        // обработчики кнопок картинок

        $('#gallery').bind('callimgs.mkAdminTagInlay', function () {

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

