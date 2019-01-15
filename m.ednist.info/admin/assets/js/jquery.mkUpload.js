/*! Copyright (c) 2013 Max Kulyaev (Максим Куляев) 1audiodesign@gmail.com
 * Licensed under the MIT License (LICENSE.txt).
 * jquery.mkUpload
 * Version: 1.0
 */
;
(function ($, window, document, undefined) {
    var pluginName = 'mkUpload',
        defaults = {
            buttonTitle: 'upload',
            dropboxTitle: 'drop',
            formClass: 'mkUploadForm5',
            inputClass: 'image-file',
            dropboxClass: 'mkUploadDropbox',
            dropboxHoverClass: 'hover',
            inputName: 'file[]',
            filetypes: 'all',
            controller: '../admin/controllers/upload.controller.php',
            multiple: true,
            serial: true,
            serverData: '',
            prohibedFile: '',
            rename: '',
            options: '',
            newsId: 0,
            divGal: ""
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

    Plugin.prototype.build = function () {
        var widget = this,
            multiple = (widget.config.multiple == true) ? "multiple='multiple'" : '';

    };

    Plugin.prototype.showDropbox = function () {
        var widget = this;
        $('.' + widget.config.dropboxClass, widget.element).show();
    };

    Plugin.prototype.hideDropbox = function () {
        var widget = this;
        $('.' + widget.config.dropboxClass).hide();
    };

    Plugin.prototype.leaveWindow = function (e) {
        e = window.event || e;
        var relatedTarget = document.elementFromPoint(e.clientX, e.clientY);
        if (!relatedTarget || e.clientX < 1 || e.clientY < 1 || e.clientX > window.innerWidth || e.clientY > window.innerHeight) {
            return true;
        }
        else {
            return false;
        }
    };

    Plugin.prototype.ignoreDrag = function (e) {
        e.originalEvent.stopPropagation();
        e.originalEvent.preventDefault();
    };

    Plugin.prototype.drop = function (e) {
        var widget = this,
            dt = e.originalEvent.dataTransfer,
            files = dt.files;
        widget.config.newsId = parseInt($('.numberId').text());
        files = widget.cleanFiletypes(files);
        widget.ignoreDrag(e);
        widget.sendFiles(e, files);
        widget.hideDropbox();
        //return false;
    };

    Plugin.prototype.sendFiles = function (e, files) {
        //console.log(files);
        var widget = this, formdata;
        if (0 != files.length) {
            if (widget.config.serial == true) {
                //i=0;
                //while()
                for (i = 0; i < files.length; i += 1) {
                    formData = new FormData();
                    formData.append(widget.config.inputName, files[i]);
                    if ('' != widget.config.rename) {
                        formData.append('rename', widget.config.rename);
                    }
                    if ('' != widget.config.newsId) {
                        formData.append('newsId', widget.config.newsId);
                    }
                    if ('' != widget.config.options) {
                        formData.append('options', widget.config.options);
                    }
                    widget.upload(e, formData, files[i].name);
                }
            }
            else {
                formData = new FormData();
                for (var i = 0; i < files.length; i += 1) {
                    formData.append(widget.config.inputName, files[i].name);
                    if ('' != widget.config.rename) {
                        formData.append('rename', widget.config.rename);
                    }
                    if ('' != widget.config.newsId) {
                        formData.append('newsId', widget.config.newsId);
                    }
                    if ('' != widget.config.options) {
                        formData.append('options', widget.config.options);
                    }
                    widget.upload(e, formData);
                }

            }

        }
    };

    Plugin.prototype.cleanFiletypes = function (files) {
        var widget = this,
            filesArr = [],
            prohibedArr = [];
        if ('all' != widget.config.filetypes) {
            for (var i = 0; i < files.length; i += 1) {
                for (var j = 0; j < widget.config.filetypes.length; j += 1) {
                    if (files[i].name.substr(-3).toLowerCase() == widget.config.filetypes[j]) {
                        filesArr.push(files[i]);

                    }
                    else {
                        //Запихиваем запрещенные файлы в массив
                        prohibedArr.push(files[i].name);
                        /*widget.config.prohibedFile=files[i].name;*/

                    }
                }
            }
            //убираем разрешенные из запрещенного массива
            for (var i = 0; i < prohibedArr.length; i += 1) {
                for (var j = 0; j < widget.config.filetypes.length; j += 1) {
                    if (prohibedArr[i].substr(-3).toLowerCase() == widget.config.filetypes[j]) {
                        prohibedArr[i] = '';
                    }
                }
            }
            if (prohibedArr != '') {
                console.log(prohibedArr);
                //widget.element.trigger('prohibedType.'+widget._name);                                  //prohibedType
            }


        }
        else {
            filesArr = files;
        }
        return filesArr;
    };

    Plugin.prototype.displayGalleryData = function (data) {
        var widget = this;
        if (typeof data != 'undefined') {
            $('#gallery').append(data);
         //   $('#gallery').trigger('callimgs.mkAdminNewsInlay');
              $('#gallery').trigger('callimgs.mkAdminNewsInlayImg');
        }
    };

    Plugin.prototype.displayDocsData = function (data) {
        var widget = this;
        if (typeof data != 'undefined') {
            if (data == 'duplicate') {
                //alert('Файл с таким именем уже существует');
                $('#condition').mkCondition({
                    condition: 'tooltip',
                    text: 'Файл с таким именем уже существует',
                    action: 'show',
                    color: 'red'
                });
            } else {
                $('#blockDocsWrap').append(data);
            }
          //  $('#blockDocsWrap').trigger('calldocs.mkAdminNewsInlay');
              $('#blockDocsWrap').trigger('calldocs.mkAdminNewsInlayImg');
        }
    };

    Plugin.prototype.displayImgBrandData = function (data) {
        var widget = this;
        if (typeof data != 'undefined') {
            $('#gallery').append(data);
            $('#gallery').trigger('callimgs.mkAdminBrandInlay');
        }
    };
    Plugin.prototype.displayImgThemesData = function (data) {
        var widget = this;
        if (typeof data != 'undefined') {
            $('#gallery').append(data);
            $('#gallery').trigger('callimgs.mkAdminThemesInlay');
        }
    };
    Plugin.prototype.displayImgSettingData = function (data) {
        var widget = this;
        if (typeof data != 'undefined') {
            $('#gallery').html(data);

            $('#gallery').trigger('callimgs.mkAdminSettingInlay');
        }
    };
    Plugin.prototype.displayImgSettingData2 = function (data) {
        var widget = this;
        if (typeof data != 'undefined') {
            $('#gallery2').html(data);

            $('#gallery2').trigger('callimgs.mkAdminSettingInlay');
        }
    };
    Plugin.prototype.displayImgAuthorData = function (data) {
        var widget = this;
        if (typeof data != 'undefined') {
            $('#gallery').html(data);

            imgInList = $('[data-mkauthor="{"authorId":"' + widget.config.newsId + '"}"] .avatar');
            //console.log(imgInList);
            if (imgInList.find('img').length) {
                src = imgInList.find('img').attr('src');
                imgInList.find('img').attr('src', src + '?' + (new Date()).getTime());
            } else {
                vall = $('#gallery .item').data('img-sprite');
                imgInList.html($('<img/>').attr('src', vall.imgpath + '60.jpg?' + (new Date()).getTime()));
            }
            $('#gallery').trigger('callimgs.mkAdminAuthorInlay');
        }
    };

    Plugin.prototype.delImg = function (imgId) {
        var widget = this;
        $('#condition').mkCondition({action: 'blink', color: 'yellow'});
        $.ajax({
            type: "POST",
            url: widget.config.controller,
            data: {dellImgId: imgId},
            cache: false,
            contentType: false,
            processData: false
        }).done(function (data) {
            $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
        }).fail(function () {
            $('#condition').mkCondition({condition: 'tooltip', text: 'Ошибка сервера', action: 'show', color: 'red'});                                                //clientFail
        });
    };

    Plugin.prototype.upload = function (e, formData, flname) {
        var widget = this;
        $('#condition').mkCondition({action: 'blink', color: 'yellow'});

        $.ajax({
            type: "POST",
            url: widget.config.controller,
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        }).done(function (data) {
            widget.config.serverData = data;
            if (widget.config.inputName == 'imgfile[]') {// Если картинки
                $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                widget.displayGalleryData(data);
            } else if (widget.config.inputName == 'brandimgfile[]') {// Если картинка бренда
                $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                widget.displayImgBrandData(data);
            }else if (widget.config.inputName == 'themesimgfile[]') {// Если картинка теми
                $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                widget.displayImgThemesData(data);
            } else if (widget.config.inputName == 'authorimgfile[]') {// Если картинка бренда
                $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                widget.displayImgAuthorData(data);
            } else if (widget.config.inputName == 'settingimgfile[]') {// Если картинка настройок1
              $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                widget.displayImgSettingData(data);
            } else if (widget.config.inputName == 'settingimgfile2[]') {// Если картинка настройок2
              $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                widget.displayImgSettingData2(data);
            }else if (widget.config.inputName == 'docfile[]') {// Если документы
                $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
                widget.displayDocsData(data);
            }
        }).fail(function () {
            $('#condition').mkCondition({condition: 'tooltip', text: 'Ошибка сервера', action: 'show', color: 'red'});                                               //clientFail
        });
    };

    Plugin.prototype.init = function () {
        var widget = this;

        widget.element.trigger('before.' + widget._name);

        widget.build();

        $('.' + widget.config.dropboxClass, widget.element).unbind().bind('dragover', function (e) {
            widget.ignoreDrag(e);
            $(this).addClass(widget.config.dropboxHoverClass);
        }).bind('dragleave', function (e) {
            widget.ignoreDrag(e);
            $(this).removeClass(widget.config.dropboxHoverClass);
            widget.hideDropbox();
        }).bind('drop', function (e) {
            widget.drop(e);
        }).bind('click', function () {
            widget.hideDropbox();
        });

        $(window).bind('dragover', function (e) {
            widget.showDropbox();
        });

        $(window).bind('dragleave', function (e) {
            if (widget.leaveWindow(e)) {
                widget.hideDropbox();
            }
        });

        $('.' + widget.config.inputClass, widget.element).unbind().bind('change', function (e) {
            var files = this.files;
            widget.config.newsId = parseInt($('.numberId').text());
            files = widget.cleanFiletypes(files);
            widget.sendFiles(e, files);            
        });
         
        widget.element.trigger('callback.' + widget._name);
    };

    $.fn[pluginName] = function (options) {
        //return this.each(function () {
        return new Plugin($(this), options);
        //});
    }
})(jQuery, window, document);
