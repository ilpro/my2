/*! Copyright (c) 2014 Max Kulyaev (Максим Куляев) 1audiodesign@gmail.com
 * Licensed under the MIT License (LICENSE.txt).
 * jquery.mkAdminbrandInlay
 * Version: 1.0
 */
;
(function ($, window, document, undefined) {
    var pluginName = 'mkAdminBrandInlay',
        defaults = {
            action: 'ajax',
            brandId: 0,
            brandStatus: 0,
            brandDesc: '',
            brandActive: false
        };
    // конструктор плагина

    function Plugin(element, options) {
        var widget = this;

        widget.element = element;
        widget.metadata = widget.element.data('mkbrand');
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

    Plugin.prototype.showbrandInlay = function () {
        var widget = this;
        if (!$('#brandInlay').is(':visible')) {
            $('#brandInlay').addClass('show');
        }
    };//END OF Plugin.prototype.showbrandInlay 
    Plugin.prototype.hidebrandInlay = function () {
        var widget = this;
        if ($('#brandInlay').is(':visible')) {
            $('#brandInlay').removeClass('show');
            $.bbq.removeState(['brand']);//удаляем хэш 	
        }
    };//END OF Plugin.prototype.hidebrandInlay
    Plugin.prototype.initRedactor = function () {
        var widget = this;
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
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            setup: function (editor) {
                editor.addMenuItem('myitem', {
                    text: 'Отчистка Word тегов',
                    context: 'tools',
                    onclick: function () {
                        widget.cleanHTML('word');
                    }
                }),
                    editor.addMenuItem('myitem2', {
                        text: 'Отчистка от всех тегов',
                        context: 'tools',
                        onclick: function () {
                            widget.cleanHTML('all');
                        }
                    })
            }
        });
    };//END OF Plugin.prototype.initRedactor
    Plugin.prototype.cleanHTML = function (method) {
        var widget = this;
        var textt = tinyMCE.activeEditor.getContent();
        //var textt = $('#maincontent').val();
        $('#condition').mkCondition({action: 'blink', color: 'yellow'});
        $.ajax({
            type: "POST",
            url: '/admin/controllers/cleanHTML.controller.php',
            data: {html: textt, method: method}
        }).done(function (data) {
            tinyMCE.execCommand('mceSetContent', false, data);

            $('#condition').mkCondition({
                condition: 'tooltip',
                text: 'Текст успешно очищен от тегов',
                action: 'fade',
                color: 'green',
                fadeout: 2500
            });
        }).fail(function () {
            $('#condition').mkCondition({condition: 'tooltip', text: 'Ошибка сервера', action: 'show', color: 'red'});
        });
    };//END OF Plugin.prototype.cleanHTML
    Plugin.prototype.publish = function () {

    };
    Plugin.prototype.fillbrandAjax = function () {
        var widget = this;

        $.ajax({
            type: "POST",
            url: '/admin/controllers/brands.controller.php',
            dataType: 'json',
            data: {getbrandItem: widget.config.brandId}
        }).done(function (data) {

            if (null != data) {
                widget.fillbrandData(data);
                console.log(data);
                success_tip('Бренд #'+widget.config.brandId+' получен');
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

    Plugin.prototype.fillbrandData = function (data) {
        var widget = this;
        //@@ TODO : change style of publish button , depending on brand state
        if (data.brandActive == "1") {
            $('.publish', widget.element).attr('title', 'Не публиковать');
        }
        else {
            $('.publish', widget.element).attr('title', 'Публиковать');
        }
        $('#brandInlayId', widget.element).text(widget.config.brandId);
        $('#brandType', widget.element).val(data.brandType);
        $('#brandSortName', widget.element).val(data.brandSortName);
        $('#brandName', widget.element).val(data.brandName);
        $('#newsStatus', widget.element).val(data.brandStatus);
        $('#brandSearch', widget.element).val(data.brandSearch);
        $('#gallery', widget.element).html(data.brandImgsHTML);
        $('#blockBrandWrap', widget.element).html(data.newsBrandsHTML);

        $('#maincontent').val(data.brandDesc);
        tinyMCE.execCommand('mceSetContent', false, data.brandDesc);

        widget.showbrandInlay();
        widget.bindAll();
    };//END OF Plugin.prototype.fillOrderData

    Plugin.prototype.getPrevNext = function (e, prevNext, brandId) {
        var widget = this;
        if ('prev' == prevNext) {// show prev brand
            $('.brandStripe').each(function () {

                if (parseInt($(this).data('mkbrand').brandId) == brandId) {
                    if (null != $(this).next().data('mkbrand')) {
                        $.bbq.pushState({brand: parseInt($(this).next().data('mkbrand').brandId)});

                    }
                    else {
                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Больше брендов нет',
                            action: 'fade',
                            fadeout: 1500,
                            color: 'red'
                        });
                    }
                }
            });
        }
        else if ('next' == prevNext) {// show next brand
            $('.brandStripe').each(function () {
                if ($(this).data('mkbrand').brandId == brandId) {
                    if (null != $(this).prev().data('mkbrand')) {
                        $.bbq.pushState({brand: parseInt($(this).prev().data('mkbrand').brandId)});

                    }
                    else {
                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Нет брендов новее',
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
    Plugin.prototype.deleteBrand = function () {

        var widget = this, ids = [];
        ids.push(widget.config.brandId);

        if (ids.length > 0 && confirm("Удалить новость?")) {

            $.ajax({
                type: "POST",
                url: '/admin/controllers/brands.controller.php',
                dataType: 'json',
                data: {deleteBrand: ids}
            }).done(function (data) {
                if ('ok' == data.clean) {
                    success_tip("Новость удалена");

                    $('[data-mkbrand="{"brandId":"' + ids + '"}"]').remove();

                    widget.hidebrandInlay();
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
    
    //------------------------------------------------------------------------------Бренды

    Plugin.prototype.searchBrand = function () {
        var widget = this, save = '', ids = [0],
                searchBrand = $.trim($('#blockBrandInput', widget.element).val());
        if ('' != searchBrand) {
            $('.brand', '#blockBrandWrap').each(function () {
                if ($(this).hasClass('in')) {
                    ids.push($(this).data('brand').id);
                }
            });
            $('#blockBrandLoading', widget.element).show();
            $('#condition').mkCondition({action: 'blink', color: 'yellow'});
            $.ajax({
                type: "POST",
                url: '/admin/controllers/brands.controller.php',
                data: {brandSearch: $.trim($('#blockBrandInput', widget.element).val()), exclude: ids}
            }).done(function (data) {
                ids = [];
                if (null != data) {
                    if ('notExists' == data) { //ошибка!           	                      
                       error_tip('Ничего не найдено!');

                        $('.keyword', '#blockBrandWrap').each(function () {
                            if (!$(this).hasClass('in')) {
                                $(this).remove();
                            }
                        });
                        $('#blockBrandNew').show();
                        $('#blockBrandLoading', widget.element).hide();
                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                    }
                    else {//ОК
                        $('.brand', '#blockBrandWrap').each(function () {
                            if ($(this).hasClass('in')) {
                                save += $('<div>').append($(this).clone()).html();
                            }
                        });
                        $('#blockBrandWrap', widget.element).html(save + data);
                        $('#blockBrandLoading', widget.element).hide();
                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                        widget.bindAll();
                    }
                }
                else {
                    error_tip('Сервер прислал пустой ответ');
                }
            }).fail(function () {
                error_tip('Ошибка сервера');
            });
        }
    };


    Plugin.prototype.saveBrand = function (e, obj) {
        var widget = this, Brand;
        if (obj.hasClass('in')) {
            $.ajax({
                type: "POST",
                url: '/admin/controllers/brands.controller.php',
                dataType: 'json',
                data: {brandRemove: widget.config.brandId, brandId: obj.data('brand').id}
            }).done(function (data) {
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
                        obj.toggleClass('in');
                        $('#blockBrandInput', widget.element).val('').focus();
                    }
                    else {
                        error_tip('Ответ сервера не соответсвует ожидаемому');
                    }
                }
                else {
                    error_tip('Сервер прислал пустой ответ');
                }
            }).fail(function () {
                error_tip('Ошибка сервера');
            });
        }
        else {
            obj.toggleClass('in');
            brand = 1;
            $('#condition').mkCondition({action: 'blink', color: 'yellow'});
            $.ajax({
                type: "POST",
                url: '/admin/controllers/brands.controller.php',
                dataType: 'json',
                data: {
                    brandSave: widget.config.brandId,
                    brandId: obj.data('brand').id
                }
            }).done(function (data) {
                if (null != data) {
                    if (null != data.error) { //ошибка!              	            	         	                      
                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Сервер ответил ошибкой',
                            action: 'show',
                            color: 'red'
                        });
                        obj.toggleClass('in');
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
                        widget.removeBrands(obj.closest('.inputblock'));
                        $('#blockBrandInput', widget.element).val('').focus();
                    }
                    else {
                        error_tip('Ответ сервера не соответсвует ожидаемому');
                        obj.toggleClass('in');
                    }
                }
                else {
                    error_tip('Сервер прислал пустой ответ');
                    obj.toggleClass('in');
                }
            }).fail(function () {
                error_tip('Ошибка сервера');
                obj.toggleClass('in');
            });
        }
    };


    Plugin.prototype.removeBrands = function (obj) {
        var widget = this;
        $('.brand', obj).each(function () {
            if (!$(this).hasClass('in')) {
                $(this).remove();
            }

        });
    };

    Plugin.prototype.newBrandWindowShow = function () {
        var widget = this;
        $('#newBrandWindow').show();
        $('#blockBrandNew').hide();
        $('#brandName2').val('');
        $('#brandSearch2').val('');
        $('#brandName2').focus();
    };
    
     // Сохранения нового бренда
    Plugin.prototype.newBrandSave = function () {
        var widget = this, save = '',
                brandName = $.trim($('#brandName2').val()),
                brandSearch = $.trim($('#brandSearch2').val()),
                brandSort = $.trim($('#brandSortName2').val()),
                brandType = $.trim($('#brandType2').val());
                    console.log(brandName);
        if ('' != brandName) {

            $('#newBrandWindow').hide();
            $('#condition').mkCondition({action: 'blink', color: 'yellow'});
            $.ajax({
                type: "POST",
                url: '/admin/controllers/brands.controller.php',
                data: {brandSaveNewName: brandName, brandSaveNewSearch: brandSearch,brandSort:brandSort,brandType:brandType}
            }).done(function (data) {
                if ('error' != data) {
                    if ('exists' != data) {
                        $('.keyword', '#blockBrandWrap').each(function () {
                            if ($(this).hasClass('in')) {
                                save += $('<div>').append($(this).clone()).html();
                            }
                        });
                        $('#blockBrandWrap', widget.element).html(save + data);
                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                        widget.bindAll();
                        console.log(data);
                    }
                    else {
                        $('#condition').mkCondition({
                            condition: 'tooltip',
                            text: 'Такой тег уже существует!',
                            action: 'show',
                            color: 'red'
                        });
                        widget.newBrandWindowShow();
                        //$('#tagName').focus(); 
                    }

                }
                else {
                    error_tip('Сервер прислал пустой ответ');
                }
            }).fail(function () {
                error_tip('Ошибка сервера');
            });
        }
    };

//=======================================================================================Конец Брендов
    
    Plugin.prototype.bindAll = function () {

        var widget = this, search_timeout;

        $('.delete', widget.element).unbind().bind('click', function (e) {
            widget.deleteBrand();
        });
        $('.publish', widget.element).unbind().bind('click', function (e) {
            widget.config.brandActive = (!widget.config.brandActive) ? 1 : 0;
            if ($('.publish', widget.element).attr('title') == 'Не публиковать') {
                $('.publish', widget.element).attr('title', 'Публиковать');
            }
            else {
                $('.publish', widget.element).attr('title', 'Не публиковать');
            }


        });
        $('.close', widget.element).unbind().bind('click', function (e) {
            widget.hidebrandInlay();
        });
        $('.update', widget.element).unbind().bind('click', function (e) {
            widget.fillbrandAjax();
        });
        $('.switch-left').unbind().bind('click', function (e) {
            widget.getPrevNext(e, 'prev', widget.config.brandId);
        });
        $('.switch-right').unbind().bind('click', function (e) {
            widget.getPrevNext(e, 'next', widget.config.brandId);
        });

        $('#imgAddFromLink').unbind().bind('click', function () {
            widget.imgAddFromLink();
        });

        $('.save', widget.element).unbind().bind('click', function (e) {
            widget.saveAll();
        });
        $('#gallery').trigger('callimgs.' + widget._name);

        $('#newsStatus', widget.element).unbind('change').bind('change', function (e) {// ----Статус новости
            widget.setBrandStatus(e, $(this));
        });

        $("#newsStatus").select2({
            formatResult: function (state) {
                var originalOption = state.element;
                return "<span class='status" + $(originalOption).data('foo') + "' title='" + $(originalOption).data('title') + "' ></span>" + state.text;
            },
            formatSelection: function (state) {
                var originalOption = state.element;
                return "<span class='status" + $(originalOption).data('foo') + "' title='" + $(originalOption).data('title') + "' ></span>" + state.text;
            },
            escapeMarkup: function (m) {
                return m;
            }
        });
        
        
        $('#blockBrandInput', widget.element).unbind().bind('keyup', function (e) {
            if (e.which == 27) {//esc                 
                clearTimeout(search_timeout);
                $(this).val('');
                widget.removeBrands($(this).closest('.inputblock'));
            }
            else if (e.which == 13) {//enter
                clearTimeout(search_timeout);
                widget.searchBrand();
            }
            else {
                if (search_timeout != undefined) {
                    clearTimeout(search_timeout);
                }
                search_timeout = setTimeout(function () {
                    search_timeout = undefined;
                    widget.searchBrand();
                }, 500);
            }
        });

        $('.brand > span', '#blockBrand').unbind().bind('click', function (e) {
            widget.saveBrand(e, $(this).parent());
        });

        $('.smile', '#blockBrand').unbind().bind('click', function (e) {
            var val = $(this).parent().data("brand");
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                $(this).addClass('selected').find('.select').removeClass('select');
                $(e.target).addClass('select');
                val.smile = $(e.target).data("smile");
                $(this).parent().data("brand", val);
                if ($(this).parent().hasClass('in')) {
                    $(this).parent().removeClass('in');
                } else {
                    //$(this).parent().addClass('in');
                }
                widget.saveBrand(e, $(this).parent());
            }
        });
          $('.keyword', '#blockBrand').unbind().bind('click', function (e) {
            widget.saveBrand(e, $(this));
        });

        $('#blockBrandNewInsert', widget.element).unbind().bind('click', function (e) {
            widget.newBrandWindowShow();
        });
        $('#brandSaveNew').unbind().bind('click', function (e) {
            widget.newBrandSave();
            return false;
        });
        $('.newbrandsave').unbind().bind('keyup', function (e) {
            if (e.which == 27) {//esc                 
                $(this).val('');
            }
            else if (e.which == 13) {//enter
                widget.newBrandSave();
            }
        });
        $('.brand .select_connect', widget.element).unbind().bind('change', function (e) {
            widget.changeConnect($(this).val(),($(this).parent()).data('brand').id);
        });
        
    };//END OF Plugin.prototype.bindAll
    
     Plugin.prototype.changeConnect = function (value,id) {
        var widget = this;
        console.log(widget.config.brandId);
        console.log(id);
        console.log(value);
            $.ajax({
                type: "POST",
                url: '/admin/controllers/brands.controller.php',
                dataType: 'json',
                data: {
                    action:'changeConnect',
                    brandId: widget.config.brandId,
                    connectBrandId:id,
                    value: value
                }
            }).done(function (data) {
               if(data!=false){
                   success_tip('изменено');
               }else{
                   error_tip('ERROR');
               }
            }).fail(function () {
                error_tip('Ошибка сервера');
            });
    }


    Plugin.prototype.setBrandStatus = function (e, obj) {
        var widget = this;

        $.ajax({
            type: "POST",
            url: '/admin/controllers/brands.controller.php',
            dataType: 'json',
            data: {setBrandStatus: obj.val(), brandId: widget.config.brandId}
        }).done(function (data) {
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
                        color: 'green',
                        fadeout: 1000
                    });
                    $('#dateupdate', widget.element).val(data.saved); //Обновляем дату модификации!
                    $('[data-mknews="{"newsId":"' + widget.config.newsId + '"}"] [class^="status"]').attr('class', 'status' + obj.val()); // меняем иконку в списке
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
    };//END OF Plugin.prototype.setNewsStatus

    Plugin.prototype.imgAddFromLink = function () {
        var widget = this;
        if (link = prompt('Введите ссылку на картику')) {
            $('#condition').mkCondition({action: 'blink', color: 'yellow'});
            $.ajax({
                type: "POST",
                url: '/admin/controllers/upload.controller.php',
                data: {imgBrandAddLink: link, brandId: widget.config.brandId}
            }).done(function (data) {
                 if (typeof data != 'undefined') {
                    $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                    $('#gallery').append(data);
                    $('#gallery').trigger('callimgs.mkAdminBrandInlay');
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


        $('#gallery .sill').unbind().bind('click', function () { // Главная ккартинка
            var element = $(this),
                val = element.parent().data('img-sprite');
            $('#condition').mkCondition({action: 'blink', color: 'yellow'});
            $.ajax({
                type: "POST",
                url: '/admin/controllers/upload.controller.php',
                dataType: 'json',
                data: {setMainBrandImgId: val.imgid}
            }).done(function (data) {

                if (data == '0') {
                    success_tip('Установленно успешно');

                    $('#gallery .main').removeClass('main');
                    element.addClass('main');
                    imgInList = $('[data-mkbrand="{"brandId":"' + widget.config.brandId + '"}"] .avatar');
                    imgInList.html($('<img/>').attr('src', val.imgpath + 'main/60.jpg?' + (new Date()).getTime()));
                }
                else {
                    error_tip('Ответ сервера не соответсвует ожидаемому');
                }
            }).fail(function () {
                error_tip('Ошибка сервера');
            });
        })

        $('#gallery .del').unbind().bind('click', function () { // удалить картинку
            if (confirm("Удалить?")) {
                var element = $(this);
                val = element.parent().data('img-sprite');
                $('#condition').mkCondition({action: 'blink', color: 'yellow'});
                $.ajax({
                    type: "POST",
                    url: '/admin/controllers/upload.controller.php',
                    dataType: 'json',
                    data: {delBrandImgId: val.imgid}
                }).done(function (data) {
                    if (data == '0') {
                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                        if (element.parent().find('.main').length) {
                            $('[data-mkbrand="{"brandId":"' + widget.config.brandId + '"}"] .avatar img').remove();
                        }
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
            var link = $(this).parent().find('img').attr('src'), d = new Date();
            link = link.replace('gallery', 'big');
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
            var link = $(this).parent().find('img').attr('src'),
                element = $(this), d = new Date(),
                val = $(this).parent().data('img-sprite');
            link = link.replace('gallery', 'raw');
            $('#showImageCropWindow .inputs').html($('<img />').attr('src', link + '?' + d.getTime()).addClass('target'));
            $('#showImageCropWindow').unbind().show();
            //setTimeout(function(){
            $('#showImageCropWindow img').Jcrop({
                onSelect: function (c) {
                    window.c = c;
                },
                //onChange: showCoords,
                aspectRatio: 1,
                minSize: [400, 400],
                boxWidth: 1000, boxHeight: 600,//
                setSelect: [0, 0, 400, 400],
                bgOpacity: 0.5,
                bgColor: 'black',
                addClass: 'jcrop-dark'
            }, function () {
                jcrop_api = this;
            });
            $('.savecrop').unbind().bind('click', function () { // сохранение фрагмента картинки

                $.ajax({
                    type: "POST",
                    url: '/admin/controllers/upload.controller.php',
                    data: {cropImgBrandId: val.imgid, c: window.c}
                }).done(function (data) {
                    if (data != 'error') {
                        $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                        element.parent().find('img').attr('src', data);
                        $('#showImageCropWindow .close').trigger('click');
                        if (element.parent().find('.main').length) {
                            imgInList = $('[data-mkbrand="{"brandId":"' + widget.config.brandId + '"}"] .avatar img');
                            src = imgInList.attr('src');
                            imgInList.attr('src', src + '?' + (new Date()).getTime());
                        }
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
    };

    Plugin.prototype.saveAll = function () {
        var widget = this;
        widget.config.brandName = $.trim($('#brandName', widget.element).val());
        tinyMCE.activeEditor.save();
        widget.config.brandDesc = tinyMCE.activeEditor.getContent();
        widget.config.brandSearch = $.trim($('#brandSearch', widget.element).val());
        widget.config.brandSortName = $.trim($('#brandSortName', widget.element).val());
        widget.config.brandType = $.trim($('#brandType', widget.element).val());


        $.ajax({
            type: "POST",
            url: '/admin/controllers/brands.controller.php',
            dataType: 'json',
            data: {
                saveAll: widget.config.brandId,
                brandName: widget.config.brandName,
                brandDesc: widget.config.brandDesc,
                brandSearch: widget.config.brandSearch,
                brandType: widget.config.brandType,
                brandSortName: widget.config.brandSortName,
                brandActive: widget.config.brandActive
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
            widget.fillbrandAjax();
        }
        else if ('data' == widget.config.action) {//Заполнение по предоставляемой информации

            widget.fillbrandData();
        }
        // обработчики кнопок картинок

        $('#gallery').bind('callimgs.mkAdminBrandInlay', function () {
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
