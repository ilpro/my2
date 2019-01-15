/*! Copyright (c) 2014 Max Kulyaev (Максим Куляев) 1audiodesign@gmail.com

 * Licensed under the MIT License (LICENSE.txt).

 * jquery.mkAdminauthorInlay

 * Version: 1.0

 */

;
(function ($, window, document, undefined) {

    var pluginName = 'mkAdminAuthorInlay',

        defaults = {

            action: 'ajax',

            authorId: 0,

            authorDesc: ''

        };

    // конструктор плагина


    function Plugin(element, options) {

        var widget = this;


        widget.element = element;

        widget.metadata = widget.element.data('mkauthor');

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

    Plugin.prototype.showauthorInlay = function () {

        var widget = this;

        if (!$('#authorInlay').is(':visible')) {

            $('#authorInlay').addClass('show');

        }

    };//END OF Plugin.prototype.showauthorInlay 

    Plugin.prototype.hideauthorInlay = function () {

        var widget = this;

        if ($('#authorInlay').is(':visible')) {

            $('#authorInlay').removeClass('show');

            $.bbq.removeState();//удаляем хэш 	

        }

    };//END OF Plugin.prototype.hideauthorInlay

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


    Plugin.prototype.fillauthorAjax = function () {

        var widget = this;

        $('#condition').mkCondition({action: 'blink', color: 'yellow'});

        $.ajax({

            type: "POST",

            url: '/admin/controllers/authors.controller.php',

            dataType: 'json',

            data: {getauthorItem: widget.config.authorId}

        }).done(function (data) {

            if (null != data) {

                $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});

                widget.fillauthorData(data);

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


    Plugin.prototype.fillauthorData = function (data) {

        var widget = this;

        $('#authorInlayId', widget.element).text(widget.config.authorId);

        $('#authorName', widget.element).val(data.authorName);

        $('#authorSearch', widget.element).val(data.authorSearch);

        $('#gallery', widget.element).html(data.authorImgsHTML);


        $('#maincontent').val(data.authorDesc);

        tinyMCE.execCommand('mceSetContent', false, data.authorDesc);


        widget.showauthorInlay();

        widget.bindAll();

    };//END OF Plugin.prototype.fillOrderData


    Plugin.prototype.getPrevNext = function (e, prevNext, authorId) {

        var widget = this;

        if ('prev' == prevNext) {// show prev author

            $('.authorStripe').each(function () {


                if (parseInt($(this).data('mkauthor').authorId) == authorId) {

                    if (null != $(this).next().data('mkauthor')) {

                        $.bbq.pushState({author: parseInt($(this).next().data('mkauthor').authorId)});


                    }

                    else {

                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Больше авторов нет',
                            action: 'fade',
                            fadeout: 1500,
                            color: 'red'
                        });

                    }

                }

            });

        }

        else if ('next' == prevNext) {// show next author

            $('.authorStripe').each(function () {

                if ($(this).data('mkauthor').authorId == authorId) {

                    if (null != $(this).prev().data('mkauthor')) {

                        $.bbq.pushState({author: parseInt($(this).prev().data('mkauthor').authorId)});


                    }

                    else {

                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Нет авторов новее',
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
    Plugin.prototype.deleteAuthor = function () {

        var widget = this, ids = [];
        ids.push(widget.config.authorId);

        if (ids.length > 0 && confirm("Удалить новость?")) {

            $.ajax({
                type: "POST",
                url: '/admin/controllers/authors.controller.php',
                dataType: 'json',
                data: {deleteAuthor: ids}
            }).done(function (data) {
                if ('ok' == data.clean) {
                    success_tip("Новость удалена");

                    $('[data-mkauthor="{"authorId":"' + ids + '"}"]').remove();

                    widget.hideauthorInlay();
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
            widget.deleteAuthor();
        });



        $('.close', widget.element).unbind().bind('click', function (e) {

            widget.hideauthorInlay();

        });

        $('.update', widget.element).unbind().bind('click', function (e) {

            widget.fillauthorAjax();

        });

        $('.switch-left').unbind().bind('click', function (e) {

            widget.getPrevNext(e, 'prev', widget.config.authorId);

        });

        $('.switch-right').unbind().bind('click', function (e) {

            widget.getPrevNext(e, 'next', widget.config.authorId);

        });


        $('.save', widget.element).unbind().bind('click', function (e) {

            widget.saveAll();

        });

        $('#imgAddFromLink').unbind().bind('click', function () {

            widget.imgAddFromLink();

        });

        $('#gallery').trigger('callimgs.' + widget._name);

    };//END OF Plugin.prototype.bindAll

    Plugin.prototype.imgAddFromLink = function () {

        var widget = this;

        if (link = prompt('Введите ссылку на картику')) {

            $('#condition').mkCondition({action: 'blink', color: 'yellow'});

            $.ajax({

                type: "POST",

                url: '/admin/controllers/upload.controller.php',

                //dataType:'json',

                data: {imgAuthorAddLink: link, authorId: widget.config.authorId}

            }).done(function (data) {

                if (typeof data != 'undefined') {

                    $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});

                    $('#gallery').html(data);

                    imgInList = $('[data-mkauthor="{"authorId":"' + widget.config.authorId + '"}"] .avatar');

                    if (imgInList.find('img').length) {

                        src = imgInList.find('img').attr('src');

                        imgInList.find('img').attr('src', src + '?' + (new Date()).getTime());

                    } else {

                        vall = $('#gallery .item').data('img-sprite');

                        imgInList.html($('<img/>').attr('src', vall.imgpath + '60.jpg?' + (new Date()).getTime()));

                    }


                    $('#gallery').trigger('callimgs.mkAdminAuthorInlay');

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

                    data: {delImgAuthorId: widget.config.authorId}

                }).done(function (data) {

                    if (data = true) {

                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});

                        $('[data-mkauthor="{"authorId":"' + widget.config.authorId + '"}"] .avatar img').remove();

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

                    data: {cropImgAuthorId: widget.config.authorId, imgName: val.imgname, c: window.c}

                }).done(function (data) {

                    if (data != 'error') {

                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});

                        element.parent().find('img').attr('src', data);

                        $('#showImageCropWindow .close').trigger('click');


                        imgInList = $('[data-mkauthor="{"authorId":"' + widget.config.authorId + '"}"] .avatar img');

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

        widget.config.authorName = $.trim($('#authorName', widget.element).val());

        tinyMCE.activeEditor.save();

        widget.config.authorDesc = tinyMCE.activeEditor.getContent();

        widget.config.authorSearch = $.trim($('#authorSearch', widget.element).val());


        $('#condition').mkCondition({action: 'blink', color: 'yellow'});

        $.ajax({

            type: "POST",

            url: '/admin/controllers/authors.controller.php',

            dataType: 'json',

            data: {
                saveAll: widget.config.authorId,

                authorName: widget.config.authorName,

                authorDesc: widget.config.authorDesc,

                authorSearch: widget.config.authorSearch

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

            widget.fillauthorAjax();

        }

        else if ('data' == widget.config.action) {//Заполнение по предоставляемой информации


            widget.fillauthorData();

        }

        // обработчики кнопок картинок

        $('#gallery').bind('callimgs.mkAdminAuthorInlay', function () {

            widget.bindimgs();

        });

        widget.initRedactor();

        widget.bindAll();


    };//END OF INIT

    $.fn[pluginName] = function (options) {

        return this.each(function () {

            new Plugin($(this), options);

        });

    }

})(jQuery, window, document);

