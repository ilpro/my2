/*! Copyright (c) 2014 Max Kulyaev (Максим Куляев) 1audiodesign@gmail.com
 * Licensed under the MIT License (LICENSE.txt).
 * jquery.mkAdminNewsInlay
 * Version: 1.0
 */
;
(function ($, window, document, undefined) {
	var pluginName = 'mkAdminNewsInlay',
			defaults = {
				action: 'ajax',
				newsId: 0,
				interval: false, //false-интервал еще не запущен, true-интервал уже работает
				isNeedAutoSave: false, //туда забиваем значення чекбокса *Писать в черновик*
				startKeyup: false, // разрешеть ли автосейв при нажимании клавиши(false-разрешить,true-автосейв уже включен)
				saveAtExit: false, //при виходе проверяем чи нужно сохранять(бил ли включен черновик)
				goNextNews: false, //проверяем чи можна перейти на следущую/предидущую новость
				redactorIn: true //есть ли редактор на новости
			};

	// конструктор плагина
	function Plugin(element, options) {
		var widget = this;
		widget.element = element;
		widget.metadata = widget.element.data('mknews');
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

	// показать редактирование новости
	Plugin.prototype.showNewsInlay = function () {
		var widget = this;
		if (!$('#newsInlay').is(':visible')) {
			$('#newsInlay').addClass('show');
		}
	};

	// сховать редактирование новости
	Plugin.prototype.hideNewsInlay = function () {
		var widget = this;
		if ($('#newsInlay').is(':visible')) {
			$('#newsInlay').removeClass('show');
			$.bbq.removeState(['news']); //удаляем хэш
		}
	};

	// доп. функционал редактора (!вынести)
	Plugin.prototype.cleanHTML = function (method) {
		var widget = this;
		var textt = tinyMCE.activeEditor.getContent();

		$.ajax({
			type: "POST",
			url: '/admin/controllers/cleanHTML.controller.php',
			data: {html: textt, method: method}
		}).done(function (data) {

			tinyMCE.execCommand('mceSetContent', false, data);
			success_tip('Текст успешно очищен от тегов');
		}).fail(function () {
			error_tip('Ошибка сервера')
		});
	};

	// инициализация редактора (!вынести)
	Plugin.prototype.initRedactor = function () {

		var widget = this;
		tinymce.remove();
		tinymce.init({
			selector: "textarea#maincontent",
			height: 500,
			language: 'uk',
			apply_source_formatting: true,
			extended_valid_elements: "span[!class]",
			gecko_spellcheck: true,
			rel_list: [
				{title: 'nofollow', value: 'nofollow'},
				{title: 'follow', value: 'follow'}
			],
			plugins: [
				"advlist autolink lists link image charmap print preview anchor",
				"searchreplace visualblocks code fullscreen paste"
			],
			toolbar: [
				"link | customQuote | code | myitem | myitem2 | addText",
				"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image"
			],
			/*  spellchecker_languages: "Russian=ru,Ukrainian=uk,English=en",
			 spellchecker_rpc_url: "http://speller.yandex.net/services/tinyspell",*/
			setup: function (editor) {
				editor.addButton('myitem', {
					title: 'Отчистка Word тегов',
					//image : 'img/example.gif',
					icon: 'newdocument',
					onclick: function () {
						editor.focus();
						widget.cleanHTML('word');
					}
				}),
						editor.addButton('myitem2', {
							title: 'Отчистка от всех тегов',
							icon: 'newdocument',
							onclick: function () {
								editor.focus();
								widget.cleanHTML('all');
							}
						}),
						editor.addButton('customQuote', {
							title: 'Цитата',
							icon: 'blockquote',
							onclick: function () {
								var quote = prompt('Текст цитаты');
								if (quote !== null) {
									editor.focus();
									quote = '<table class="quote"><tr><td class="qSign">&ldquo;</td><td class="qText">' + quote + '</td></tr></table>';
									editor.insertContent(quote);
								}
							}
						}),
						editor.addButton('addText', {
							title: 'Добавить текст с настройок',
							icon: 'paste',
							onclick: function () {
								text = $('#pasteValue').attr('data-paste');
								editor.focus();
								quote = '<a href="/" rel="follow">' + text + '</a>';
								editor.insertContent(quote);

							}
						}),
						widget.redactorKeyup(editor);
			}

		});

	};

	//Ловим натискание клавиши в редакторе
	Plugin.prototype.redactorKeyup = function (editor) {
		var widget = this;
		editor.on('keyup', function (e) {
			if (widget.config.startKeyup == false) {
				widget.config.startKeyup = true;
				widget.autosave();
				$('#write_in_notice').prop('checked', true);
				widget.showHideNotice(true);
				widget.startAutosave();

			}
		});

	};

	// полученные данных о странице
	Plugin.prototype.fillNewsAjax = function () {
		var widget = this;

		$.ajax({
			type: "POST",
			url: '/admin/controllers/news.controller.php',
			dataType: 'json',
			data: {getNewsItem: widget.config.newsId}
		}).done(function (data) {
			if (data.res) {
				if (null != data.result) {
					success_tip('Новость #' + data.result.newsId + ' получена');
					if (data.notice == true) {
						success_tip(' Вставлен текст новости с чорновика');
					}
					else {
						success_tip(' Вставлен текст новости с бази даних');
					}

					widget.fillNewsData(data.result);
				}
				else {
					error_tip('Ответ сервера не соответсвует ожидаемому');
				}
			} else {
				error_tip('Ви не можете зайти на эту новость. Недостаточно прав!!!');
				if ($('.news-page.show').length !== 0 ? true : false)
					$.bbq.pushState({
						news: $('#newsInlayId').val()
					});
			}
		}).fail(function () {
			error_tip('Ошибка сервера');
		});
	};

	//вставка значений с чорновика/бд
	Plugin.prototype.getNewsText = function (ob) {
		var widget = this;
		var action;
		if (ob == 'notepad') {
			action = 'notepad';
		}
		else if (ob == 'db') {
			action = 'db';
		}
		else {
			return false;
		}
		$.ajax({
			type: "POST",
			url: '/admin/controllers/news.controller.php',
			dataType: 'json',
			data: {newsId: widget.config.newsId, actionNews: action}
		}).done(function (data) {
			if (data.act == true && action == 'notepad') {
				widget.showHideNotice(true);
				tinyMCE.execCommand('mceSetContent', false, data.result.newstext);
				$('#newsHeader').val(data.result.newsHeader);
				$('#newsSubheader').val(data.result.newsSubheader);
				//розкоментовать если ми хотим, щтоби по нажатии *Получить дание с чорновика* давалась возможность вкл keyup
				/*	 if(widget.config.startKeyup==true){
				 widget.config.startKeyup=false;
				 }*/
				success_tip(' Вставлен текст новости с чорновика');
			}
			else if (data.act == true && action == 'db') {
				widget.showHideNotice(false);
				tinyMCE.execCommand('mceSetContent', false, data.result.newstext);
				$('#newsHeader').val(data.result.newsHeader);
				$('#newsSubheader').val(data.result.newsSubheader);
				$('#notice_active').attr('value', 0);
				widget.PauseAutoSaving();
				success_tip(' Вставлен текст новости с бази даних');
			}
			else if (data.act == false && action == 'notepad') {
				success_tip(' Черновик пустой!!!');
				widget.showHideNotice(true);
				tinyMCE.execCommand('mceSetContent', false, '');
				$('#newsHeader').val('');
				$('#newsSubheader').val('');
				//розкоментовать если ми хотим, щтоби по нажатии *Получить дание с чорновика* давалась возможность вкл keyup
				/*   if(widget.config.startKeyup==true){
				 widget.config.startKeyup=false;
				 }*/
			}
			else {
				error_tip('Ответ сервера не соответсвует ожидаемому');
			}
		}).fail(function () {
			error_tip('Ошибка сервера');
		});
	};

	// заполнение полей страницы
	Plugin.prototype.fillNewsData = function (data) {

		var widget = this;
		$.each(data, function (index, value) {
			$('input[name=' + index + '],textarea[name=' + index + '],select[name=' + index + ']', widget.element).val(value);

			if ($('input[name=' + index + ']').is(':checkbox')) {
				var obj = $('input[name=' + index + ']');
				(1 == value) ? obj.prop('checked', true) : obj.prop('checked', false);
			}

		});

		//задаем тип новости
		if (data.newsType == 0) {
			var type = 'news';
		}
		else if (data.newsType == 1) {
			var type = 'article';
		}
		else if (data.newsType == 2) {
			var type = 'blog';
		}
		//Если опубликована, то ссилка, если нет, то обичний div (id новости)
		if (data.newsStatus == 4) {
			$('#newsInlayId').html("<a id=newsInlayIdSulka target=_blank href=http://" + window.location.hostname + "/" + type + "/" + data.newsId + "></a>");
		}
		else {
			$('#newsInlayId').html("<a id=newsInlayIdSulka target=_blank href=http://" + window.location.hostname + "/" + type + "/" + data.newsId + "?preview=1></a>");
		}
		$('#newsInlayId').attr('value', widget.config.newsId);

		$('#newsInlayIdSulka', widget.element).text(widget.config.newsId);

		(1 == data.newsMain) ? $('.flag').prop('checked', true) : $('.flag').prop('checked', false);

		if ('' != data.newsSocTime) {
			$('#timePublicSocial .close').show();
		} else {
			$('#timePublicSocial .close').hide();
		}

		if ('' != data.newsSocTimePosted) {
			$('#newsSocTimePosted').html('Время последней отправки: ' + data.newsSocTimePosted);
		} else {
			$('#newsSocTimePosted').html('');
		}

		tinyMCE.execCommand('mceSetContent', false, data.newsText);

		widget.setYouTube(data.newsVideo);

		$('#blockTagWrap', widget.element).html(data.newsTagsHTML);
		$('#blockRegionWrap', widget.element).html(data.newsRegionsHTML);
		$('#blockBrandWrap', widget.element).html(data.newsBrandsHTML);
		$('#blockThemesWrap', widget.element).html(data.newsThemesHTML);
		$('#blockSourceWrap', widget.element).html(data.newsSourceHTML);
		$('#blockAuthorWrap', widget.element).html(data.newsAuthorHTML);
		$('#blockConnectWrap', widget.element).html(data.newsConnectHTML);
		$('#blockWidgetTagWrap', widget.element).html(data.newsWidgetTagsHTML);
		$('#gallery', widget.element).html(data.newsImgsHTML);
		$('#blockDocsWrap', widget.element).html(data.newsDocsHTML);

		widget.showNewsInlay();
		widget.bindAll();
	};

	// установка ссылки на youtube для видео
	Plugin.prototype.setYouTube = function (ytLink) {

		var widget = this;

		if (ytLink != null && (ytLink.indexOf("https://youtu.be/") == 0)) {
			ytLink = ytLink.replace('https://youtu.be/', '');

			var youTubeURL = 'http://gdata.youtube.com/feeds/api/videos/' + ytLink + '?v=2&alt=json';
			var json = (function () {
				var json = null;
				$.ajax({
					'async': false,
					'global': false,
					'url': youTubeURL,
					'dataType': "json",
					'success': function (data) {
						json = data;
					}
				});
				return json;
			})();

			if (json !== null) {
				$('#newsVideoDesc', widget.element).val(json.entry.title.$t + "\n " + json.entry.media$group.media$description.$t);
				$('#showVideo', widget.element).html('<iframe width="329" height="185" src="//www.youtube.com/embed/' + ytLink + '?rel=0" frameborder="0" allowfullscreen></iframe>');
			}
		} else if (ytLink != null && (ytLink.indexOf("https://www.youtube.com/watch?v=") == 0)) {
			ytLink = ytLink.replace('https://www.youtube.com/watch?v=', '');
			var youTubeURL = 'http://gdata.youtube.com/feeds/api/videos/' + ytLink + '?v=2&alt=json';
			var json = (function () {
				var json = null;
				$.ajax({
					'async': false,
					'global': false,
					'url': youTubeURL,
					'dataType': "json",
					'success': function (data) {
						json = data;
					}
				});
				return json;
			})();
			
			if (json !== null) {
				$('#newsVideoDesc', widget.element).val(json.entry.title.$t + "\n " + json.entry.media$group.media$description.$t);
				$('#showVideo', widget.element).html('<iframe width="329" height="185" src="//www.youtube.com/embed/' + ytLink + '?rel=0" frameborder="0" allowfullscreen></iframe>');
			}
		}
		else {
			$('#showVideo', widget.element).html('');
			$('#newsVideoDesc', widget.element).val('');
		}
	};

	// переход между новостями на странице редактирования
	Plugin.prototype.getPrevNext = function (e, prevNext, newsId) {
		var widget = this;

		if ('prev' == prevNext) {// show prev news
			var arr = new Array();
			var i = 0;
			$('.newsStripe').each(function () {
				if ($(this).css('display') == 'block') {
					arr[i] = $(this);
					i++;
				}
			});
			var a = 0;
			$(arr).each(function () {
				if (parseInt($(this).data('mknews').newsId) == newsId) {
					if (null != $(arr[a + 1]).data('mknews')) {
						$.bbq.pushState({
							news: parseInt($(arr[a + 1]).data('mknews').newsId)
						});
					}
					else {
						error_tip('Больше новостей нет');
					}
				}
				a++;
			});
		}
		else if ('next' == prevNext) {// show next news
			var arr = new Array();
			var i = 0;
			$('.newsStripe').each(function () {
				if ($(this).css('display') == 'block') {
					arr[i] = $(this);
					i++;
				}
			});
			var a = 0;
			$(arr).each(function () {
				if ($(this).data('mknews').newsId == newsId) {
					if (null != $(arr[a - 1]).data('mknews')) {
						$.bbq.pushState({
							news: parseInt($(arr[a - 1]).data('mknews').newsId)
						});
					}
					else {
						error_tip('Больше новостей нет');
					}
				}
				a++;
			});
		}
	};


	// Сохраняем новость
	Plugin.prototype.saveAll = function () {
		var widget = this;

		var data = $('#formSave').serializeArray();

		data.forEach(function (obj, i, arr) {
			if (obj['name'] == 'newsText')
				data[i]['value'] = tinyMCE.activeEditor.getContent();
		});

		if ($('select[name=newsStatus]').val() > 2 && $('select[name=categoryId]').val() == 0) {
			alert('Укажите категорию! И сохраните еще раз!');
		} else {
			widget.PauseAutoSaving(); //штоб после сохранения не сохранило в чорновик
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {
					saveAll: widget.config.newsId,
					newsData: JSON.stringify(data)
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					console.log('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
				}
			}).done(function (data) {

				if (null != data.saved) {//ОК!
					success_tip('Сохранено ' + data.saved);
					$('#dateupdate', widget.element).val(data.saved); //Обновляем дату модификации!
				}
				else {
					error_tip("Ответ сервера не соответсвует ожидаемому\n" + data);
				}
			}).fail(function () {
				error_tip("Ошибка сервера");
			});
		}
	};

	// удалить новость
	Plugin.prototype.deleteNews = function () {

		var widget = this, newsids = [];
		newsids.push(widget.config.newsId);

		if (newsids.length > 0 && confirm("Удалить новость?")) {

			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {deleteNews: newsids}
			}).done(function (data) {
				if ('ok' == data.clean) {
					success_tip("Новость удалена");

					newsids.forEach(function (el) {
						$('[data-newsid="' + el + '"]').remove();
					});
					widget.hideNewsInlay();
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

//------------------------------------------------------------------------------ ТЕГ??

	// Поиск по тегам
	Plugin.prototype.searchTag = function () {
		var widget = this, save = '', ids = [0],
				searchTag = $.trim($('#blockTagInput', widget.element).val());
		if ('' != searchTag) {
			$('.keyword', '#blockTagWrap').each(function () {
				if ($(this).hasClass('in')) {
					ids.push($(this).data('tag').id);
				}
			});
			$('#blockTagLoading', widget.element).show();

			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				data: {tagSearch: $.trim($('#blockTagInput', widget.element).val()), exclude: ids}
			}).done(function (data) {
				ids = [];
				if (null != data) {
					if ('notExists' == data) { //ошибка!

						error_tip('Ничего не найдено!');

						$('.keyword', '#blockTagWrap').each(function () {
							if (!$(this).hasClass('in')) {
								$(this).remove();
							}
						});
						$('#blockTagNew').show();
						$('#blockTagLoading', widget.element).hide();
						$('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
					}
					else {//ОК

						success_tip('Есть ррезультаты');

						$('.keyword', '#blockTagWrap').each(function () {
							if ($(this).hasClass('in')) {
								save += $('<div>').append($(this).clone()).html();
							}
						});
						// $('#blockTagNew').hide();
						$('#blockTagNew').show();
						$('#blockTagWrap', widget.element).html(save + data);
						$('#blockTagLoading', widget.element).hide();

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

	// Привязка тега
	Plugin.prototype.saveTag = function (e, obj) {
		var widget = this, tag;
		if (obj.hasClass('in')) {
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {tagRemove: widget.config.newsId, tagId: obj.data('tag').id}
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
						obj.toggleClass('in');
						$('#blockTagInput', widget.element).val('').focus();
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
			tag = 1;
			$('#condition').mkCondition({action: 'blink', color: 'yellow'});
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {tagSave: widget.config.newsId, tagId: obj.data('tag').id}
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
							color: 'green',
							fadeout: 1000
						});
						$('#dateupdate', widget.element).val(data.saved); //Обновляем дату модификации!
						widget.removeTags(obj.closest('.inputblock'));
						$('#blockTagInput', widget.element).val('').focus();
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

	// Отвязывание тега
	Plugin.prototype.removeTags = function (obj) {
		var widget = this;
		$('.keyword', obj).each(function () {
			if (!$(this).hasClass('in')) {
				$(this).remove();
			}
		});
	};

	// ????
	Plugin.prototype.newTagWindowShow = function () {
		var widget = this;
		$('#newTagWindow').show();
		$('#blockTagNew').hide();
		$('#tagName').val('');
		$('#tagSearch').val('');
		$('#tagName').focus();
	};

	// Сохранения нового тега
	Plugin.prototype.newTagSave = function () {
		var widget = this, save = '',
				tagName = $.trim($('#tagName').val()),
				tagSearch = $.trim($('#tagSearch').val());
		//  tagName = tagName.charAt(0).toUpperCase() + tagName.substr(1).toLowerCase();

		// tagName.charAt(0).toUpperCase() + tagName.substr(1).toLowerCase(); //Все теги с большой буквы
		if ('' != tagName) {
			$('#newTagWindow').hide();
			$('#condition').mkCondition({action: 'blink', color: 'yellow'});
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				data: {tagSaveNewName: tagName, tagSaveNewSearch: tagSearch}
			}).done(function (data) {
				if ('error' != data) {
					if ('exists' != data) {
						$('.keyword', '#blockTagWrap').each(function () {
							if ($(this).hasClass('in')) {
								save += $('<div>').append($(this).clone()).html();
							}
						});
						$('#blockTagWrap', widget.element).html(save + data);
						$('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
						widget.bindAll();
					}
					else {
						$('#condition').mkCondition({
							condition: 'tooltip',
							text: 'Такой тег уже существует!',
							action: 'show',
							color: 'red'
						});
						widget.newTagWindowShow();
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

//=======================================================================================Конец Тегов

//---------------------------------------------------------------------------------------------Теги опроса(для виджета)

	// Поиск по тегам
	Plugin.prototype.searchWidgetTag = function () {
		var widget = this, 
			save = '', ids = [0],
			searchTag = $.trim($('#newsWidgetTag', widget.element).val());

		if ('' != searchTag) {
			$('.keyword', '#blockWidgetTagWrap').each(function () {
				if ($(this).hasClass('in')) {
					ids.push($(this).data('id'));
				}
			});
			$('#blockWidgetTagLoading', widget.element).show();

			// $.ajax({
				// type: "GET",
				// url: 'http://dev1.seetarget.com/api/v1/tags/get',
				// data: {tagSearch: $.trim($('#newsWidgetTag', widget.element).val()), exclude: ids}
			// }).done(function (data) {
				// ids = [];
				// if (null != data) {
					// if ('["notExists"]' == data) { //ошибка!
						// error_tip('Ничего не найдено!');

						// $('.keyword', '#blockWidgetTagWrap').each(function () {
							// if (!$(this).hasClass('in')) {
								// $(this).remove();
							// }
						// });
						// $('#blockWidgetTagNew').show();
						// $('#blockWidgetTagLoading', widget.element).hide();
						// $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
					// }
					// else {//ОК
						// var data1 = JSON.parse(data);
						// var text = '';
						// data1.forEach(function (data2) {
							// text += ('<span class="keyword" data-id=' + data2.id + ' data-name=\"' + data2.tagName + '\" data-tag={"id":' + data2.id + ',"name":' + data2.tagName + '}>' + data2.tagName + '</span>');
						// });

						// success_tip('Есть ррезультаты');
						// $('.keyword', '#blockWidgetTagWrap').each(function () {
							// if ($(this).hasClass('in')) {
								// save += $('<div>').append($(this).clone()).html();
							// }
						// });
						// $('#blockWidgetTagNew').hide();
						// $('#blockWidgetTagWrap', widget.element).html(save + text);
						// $('#blockWidgetTagLoading', widget.element).hide();

						// widget.bindAll();
					// }
				// }
				// else {
					// error_tip('Сервер прислал пустой ответ');
				// }
			// }).fail(function () {
				// error_tip('Ошибка сервера');
			// });
		}
	};

	// Привязка тега
	Plugin.prototype.saveWidgetTag = function (e, obj) {
		var widget = this, tag;
		if (obj.hasClass('in')) {
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {tagWidgetRemove: widget.config.newsId, tagWidgetId: obj.data('id')}
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
						obj.toggleClass('in');
						$('#newsWidgetTag', widget.element).val('').focus();
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
			tag = 1;
			$('#condition').mkCondition({action: 'blink', color: 'yellow'});
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {tagWidgetSave: widget.config.newsId, tagWidgetId: obj.data('id'), tagWidgetName: obj.data('name')}
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
							color: 'green',
							fadeout: 1000
						});
						$('#dateupdate', widget.element).val(data.saved); //Обновляем дату модификации!
						widget.removeWidgetTags(obj.closest('.inputblock'));
						$('#newsWidgetTag', widget.element).val('').focus();
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

	// Отвязывание тега
	Plugin.prototype.removeWidgetTags = function (obj) {
		var widget = this;

		$('.keyword', obj).each(function () {
			if (!$(this).hasClass('in')) {
				$(this).remove();
			}
		});
	};

	// ????
	Plugin.prototype.newWidgetTagWindowShow = function () {
		var widget = this;
		$('#newWidgetTagWindow').show();
		$('#blockWidgetTagNew').hide();
		$('#tagWidgetName').val('');
		$('#tagWidgetSearch').val('');
		$('#tagWidgetName').focus();
	};

	// Сохранения нового тега
	Plugin.prototype.newWidgetTagSave = function () {
		var widget = this, save = '',
				tagName = $.trim($('#tagWidgetName').val()),
				tagSearch = $.trim($('#tagWidgetSearch').val());

		// tagName.charAt(0).toUpperCase() + tagName.substr(1).toLowerCase(); //Все теги с большой буквы
		if ('' != tagName) {
			$('#newWidgetTagWindow').hide();
			// $('#condition').mkCondition({action: 'blink', color: 'yellow'});
			// $.ajax({
				// type: "POST",
				// url: 'http://dev1.seetarget.com/api/v1/tags/set',
				// data: {tagName: tagName, tagSearch: tagSearch}
			// }).done(function (data) {
				// if ('error' != data) {
					// if ('exists' != data) {
						// var data2 = JSON.parse(data);
						// //  data2.tagName='sssssss';
						// var text = '';

						// text += ('<span class="keyword" data-id=' + data2.tagId + ' data-name=' + data2.tagName + ' data-tag={"id":' + data2.tagId + ',"name":' + data2.tagName + '}>' + data2.tagName + '</span>');

						// success_tip('Есть ррезультаты');
						// $('.keyword', '#blockWidgetTagWrap').each(function () {
							// if ($(this).hasClass('in')) {
								// save += $('<div>').append($(this).clone()).html();
							// }
						// });
						// $('#blockWidgetTagNew').hide();
						// $('#blockWidgetTagWrap', widget.element).html(save + text);
						// $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
						// widget.bindAll();
					// }
					// else {
						// $('#condition').mkCondition({
							// condition: 'tooltip',
							// text: 'Такой тег уже существует!',
							// action: 'show',
							// color: 'red'
						// });
						// widget.newWidgetTagWindowShow();
					// }

				// }
				// else {
					// error_tip('Сервер прислал пустой ответ');
				// }
			// }).fail(function () {
				// error_tip('Ошибка сервера');
			// });
		}
	};


//----------------------------------------------------------------------------------------------Конец тегов опроса(для виджета)

//------------------------------------------------------------------------------Регионы	
	//Поиск регионов в редактировании новости
	Plugin.prototype.searchRegion = function () {
		var widget = this, save = '', ids = [0],
				searchRegion = $.trim($('#blockRegionInput', widget.element).val());
		if ('' != searchRegion) {
			$('.keyword', '#blockRegionWrap').each(function () {
				if ($(this).hasClass('in')) {
					ids.push($(this).data('region').id);
				}
			});
			$('#blockRegionLoading', widget.element).show();

			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				data: {regionSearch: $.trim($('#blockRegionInput', widget.element).val()), exclude: ids}
			}).done(function (data) {
				ids = [];
				if (null != data) {
					if ('notExists' == data) { //ошибка!		   						  
						$('#condition').mkCondition({
							condition: 'tooltip',
							text: 'Ничего не найдено!',
							action: 'show',
							color: 'red'
						});
						$('.keyword', '#blockRegionWrap').each(function () {
							if (!$(this).hasClass('in')) {
								$(this).remove();
							}
						});
						$('#blockRegionNew').show();
						$('#blockRegionLoading', widget.element).hide();
						$('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
					}
					else {//ОК
						$('.keyword', '#blockRegionWrap').each(function () {
							if ($(this).hasClass('in')) {
								save += $('<div>').append($(this).clone()).html();
							}
						});
						$('#blockRegionWrap', widget.element).html(save + data);
						$('#blockRegionLoading', widget.element).hide();
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

	//Сохранения регионов в редактировании новости 
	Plugin.prototype.saveRegion = function (e, obj) {
		var widget = this, region;
		if (obj.hasClass('in')) {
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {regionRemove: widget.config.newsId, regionId: obj.data('region').id}
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
						obj.toggleClass('in');
						$('#blockRegionInput', widget.element).val('').focus();
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
			region = 1;
			$('#condition').mkCondition({action: 'blink', color: 'yellow'});
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {regionSave: widget.config.newsId, regionId: obj.data('region').id}
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
							color: 'green',
							fadeout: 1000
						});
						$('#dateupdate', widget.element).val(data.saved); //Обновляем дату модификации!
						widget.removeRegions(obj.closest('.inputblock'));
						$('#blockRegionInput', widget.element).val('').focus();
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

	//удаления регионов в редактировании новости
	Plugin.prototype.removeRegions = function (obj) {
		var widget = this;
		$('.keyword', obj).each(function () {
			if (!$(this).hasClass('in')) {
				$(this).remove();
			}

		});
	};

	Plugin.prototype.newRegionWindowShow = function () {
		var widget = this;
		$('#newRegionWindow').show();
		$('#blockRegionNew').hide();
		$('#regionName').val('').focus();
		$('#regionSearch').val('');
	};//END OF Plugin.prototype.newRegionWindowShow  

	//Сохранение новго региона
	Plugin.prototype.newRegionSave = function () {
		var widget = this, save = '',
				regionName = $.trim($('#regionName').val()),
				regionSearch = $.trim($('#regionSearch').val());
		regionName = regionName.charAt(0).toUpperCase() + regionName.substr(1).toLowerCase();
		if ('' != regionName) {
			$('#newRegionWindow').hide();
			$('#condition').mkCondition({action: 'blink', color: 'yellow'});
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				data: {regionSaveNewName: regionName, regionSaveNewSearch: regionSearch}
			}).done(function (data) {
				if ('error' != data) {
					if ('exists' != data) {
						$('.keyword', '#blockRegionWrap').each(function () {
							if ($(this).hasClass('in')) {
								save += $('<div>').append($(this).clone()).html();
							}
						});
						$('#blockRegionWrap', widget.element).html(save + data);
						$('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
						widget.bindAll();
					}
					else {
						$('#condition').mkCondition({
							condition: 'tooltip',
							text: 'Такой тег уже существует!',
							action: 'show',
							color: 'red'
						});
						widget.newTagWindowShow();
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

//=======================================================================================Конец Регионов

//------------------------------------------------------------------------------Бренды
//
	//Поиск бренда
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
				url: '/admin/controllers/news.controller.php',
				data: {brandSearch: $.trim($('#blockBrandInput', widget.element).val()), exclude: ids}
			}).done(function (data) {
				ids = [];
				if (null != data) {
					if ('notExists' == data) { //ошибка!		   						  
						$('#condition').mkCondition({
							condition: 'tooltip',
							text: 'Ничего не найдено!',
							action: 'show',
							color: 'red'
						});
						$('.brand', '#blockBrandWrap').each(function () {
							if (!$(this).hasClass('in')) {
								$(this).remove();
							}
						});
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

	//Сохранение бренда
	Plugin.prototype.saveBrand = function (e, obj) {
		var widget = this, Brand;
		if (obj.hasClass('in')) {
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {brandRemove: widget.config.newsId, brandId: obj.data('brand').id}
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
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {
					brandSave: widget.config.newsId,
					brandId: obj.data('brand').id,
					brandSmile: obj.data('brand').smile
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

	//Удаление бренда
	Plugin.prototype.removeBrands = function (obj) {
		var widget = this;
		$('.brand', obj).each(function () {
			if (!$(this).hasClass('in')) {
				$(this).remove();
			}

		});
	};


//=======================================================================================Конец Брендов


//------------------------------------------------------------------------------Теми

	//Поиск теми
	Plugin.prototype.searchThemes = function () {
		var widget = this, save = '', ids = [0],
				searchThemes = $.trim($('#blockThemesInput', widget.element).val());
		if ('' != searchThemes) {
			$('.themes', '#blockThemesWrap').each(function () {
				if ($(this).hasClass('in')) {
					ids.push($(this).data('themes').id);
				}
			});
			$('#blockThemesLoading', widget.element).show();
			$('#condition').mkCondition({action: 'blink', color: 'yellow'});
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				data: {themesSearch: $.trim($('#blockThemesInput', widget.element).val()), exclude: ids}
			}).done(function (data) {
				ids = [];
				if (null != data) {
					if ('notExists' == data) { //ошибка!		   						  
						$('#condition').mkCondition({
							condition: 'tooltip',
							text: 'Ничего не найдено!',
							action: 'show',
							color: 'red'
						});
						$('.themes', '#blockThemesWrap').each(function () {
							if (!$(this).hasClass('in')) {
								$(this).remove();
							}
						});
						$('#blockThemesLoading', widget.element).hide();
						$('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
					}
					else {//ОК
						$('.themes', '#blockThemesWrap').each(function () {
							if ($(this).hasClass('in')) {
								save += $('<div>').append($(this).clone()).html();
							}
						});
						$('#blockThemesWrap', widget.element).html(save + data);
						$('#blockThemesLoading', widget.element).hide();
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

	//Сохранение теми
	Plugin.prototype.saveThemes = function (e, obj) {
		var widget = this, Themes;
		if (obj.hasClass('in')) {
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {themesRemove: widget.config.newsId, themesId: obj.data('themes').id}
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
						$('#blockThemesInput', widget.element).val('').focus();
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
			themes = 1;
			$('#condition').mkCondition({action: 'blink', color: 'yellow'});
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {
					themesSave: widget.config.newsId,
					themesId: obj.data('themes').id,
					themesSmile: obj.data('themes').smile
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
						widget.removeThemes(obj.closest('.inputblock'));
						$('#blockThemesInput', widget.element).val('').focus();
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

	//Удаление теми
	Plugin.prototype.removeThemes = function (obj) {
		var widget = this;
		$('.themes', obj).each(function () {
			if (!$(this).hasClass('in')) {
				$(this).remove();
			}

		});
	};

//=======================================================================================Конец Теми

//------------------------------------------------------------------------------??сточник

	//Поиск источника
	Plugin.prototype.searchSource = function () {
		var widget = this, save = '',
				searchSource = $.trim($('#blockSourceInput', widget.element).val());
		if ('' != searchSource) {
			if (!$('.keyword', '#blockSourceWrap').length) {
				$('#blockSourceLoading', widget.element).show();

				$.ajax({
					type: "POST",
					url: '/admin/controllers/news.controller.php',
					data: {sourceSearch: $.trim($('#blockSourceInput', widget.element).val())}
				}).done(function (data) {
					ids = [];
					if (null != data) {
						if ('notExists' == data) { //ошибка!		   						  
							$('#condition').mkCondition({
								condition: 'tooltip',
								text: 'Ничего не найдено!',
								action: 'show',
								color: 'red'
							});
							$('#blockSourceNew').show();
							$('#blockSourceLoading', widget.element).hide();
							$('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
						}
						else {//ОК
							$('#blockSourceNew').hide();
							$('#blockSourceWrap', widget.element).html(data);
							$('#blockSourceLoading', widget.element).hide();
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
		}

	};

	// удаление, потом запись только 1 источника
	Plugin.prototype.saveSource = function (e, obj) {
		var widget = this, region;
		if (obj.hasClass('in')) {
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {
					sourceRemove: widget.config.newsId,
					sourceId: obj.data('source').id
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
						obj.toggleClass('in');
						$('#blockSourceInput', widget.element).val('').focus();
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
			source = 1;
			$('#condition').mkCondition({action: 'blink', color: 'yellow'});
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {
					sourceSave: widget.config.newsId,
					sourceId: obj.data('source').id
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
							color: 'green',
							fadeout: 1000
						});
						$('#dateupdate', widget.element).val(data.saved); //Обновляем дату модификации!
						widget.removeSources(obj.closest('.inputblock'));
						$('#blockSourceInput', widget.element).val('').focus();
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

	//Удаление источника
	Plugin.prototype.removeSources = function (obj) {
		var widget = this;
		$('.keyword', obj).each(function () {
			if (!$(this).hasClass('in')) {
				$(this).remove();
			}

		});
	};

//=======================================================================================Конец ??сточника

//------------------------------------------------------------------------------Автор

	//Поиск автора
	Plugin.prototype.searchAuthor = function () {

		var widget = this, save = '',
				searchAuthor = $.trim($('#blockAuthorInput', widget.element).val());
		if ('' != searchAuthor) {
			if (!$('.keyword', '#blockAuthorWrap').length) {
				$('#blockAuthorLoading', widget.element).show();
				$('#condition').mkCondition({action: 'blink', color: 'yellow'});
				$.ajax({
					type: "POST",
					url: '/admin/controllers/news.controller.php',
					data: {authorSearch: $.trim($('#blockAuthorInput', widget.element).val())}
				}).done(function (data) {
					if (null != data) {
						if ('notExists' == data) { //ошибка!		   						  
							$('#condition').mkCondition({
								condition: 'tooltip',
								text: 'Ничего не найдено!',
								action: 'show',
								color: 'red'
							});
							$('#blockAuthorNew').show();
							$('#blockAuthorLoading', widget.element).hide();
							$('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
						}
						else {//ОК
							$('#blockAuthorNew').hide();
							$('#blockAuthorWrap', widget.element).html(data);
							$('#blockAuthorLoading', widget.element).hide();
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
		}
	};

	//Сохоанение автора
	Plugin.prototype.saveAuthor = function (e, obj) {
		var widget = this, author;
		if (obj.hasClass('in')) {
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {authorRemove: widget.config.newsId, authorId: obj.data('author').id}
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
						obj.toggleClass('in');
						$('#blockAuthorInput', widget.element).val('').focus();
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
			author = 1;
			$('#condition').mkCondition({action: 'blink', color: 'yellow'});
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {authorSave: widget.config.newsId, authorId: obj.data('author').id}
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
							color: 'green',
							fadeout: 1000
						});
						$('#dateupdate', widget.element).val(data.saved); //Обновляем дату модификации!
						widget.removeAuthor(obj.closest('.inputblock'));
						$('#blockAuthorInput', widget.element).val('').focus();
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

	//Удаление автора
	Plugin.prototype.removeAuthor = function (obj) {
		var widget = this;
		$('.keyword', obj).each(function () {
			if (!$(this).hasClass('in')) {
				$(this).remove();
			}

		});
	};

//=======================================================================================Конец Автора

//------------------------------------------------------------------------------- Привязка других новостей

	//Поиск новостей для привязки
	Plugin.prototype.searchConnect = function () {

		var widget = this, save = '', ids = [widget.config.newsId],
				searchConnect = $.trim($('#blockConnectInput', widget.element).val());

		if ('' != searchConnect) {

			$('.connectCard', '#blockConnectWrap').each(function () {
				if ($(this).hasClass('attached')) {
					ids.push($(this).data('connect').id);
				}
			});

			$('#blockConnectLoading', widget.element).show();

			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				data: {
					connectSearch: $.trim($('#blockConnectInput', widget.element).val()),
					exclude: ids
				}
			}).done(function (data) {
				ids = [];
				if (null != data) {
					if ('notExists' == data) { //ошибка!		   						  
						error_tip('Ничего не найдено!');
						$('.connectCard', '#blockConnectWrap').each(function () {
							if (!$(this).hasClass('attached')) {
								$(this).remove();
							}
						});
						$('#blockConnectLoading', widget.element).hide();
					}
					else {//ОК
						$('.connectCard', '#blockConnectWrap').each(function () {
							if ($(this).hasClass('attached')) {
								save += $('<div>').append($(this).clone()).html();
							}
						});
						$('#blockConnectWrap', widget.element).html(save + data);
						$('#blockConnectLoading', widget.element).hide();
						widget.bindAll();
						success_tip('Поиск завершен');
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

	//Привязивание новостей
	Plugin.prototype.saveConnect = function (e, obj) {

		var widget = this, connect;

		if (obj.hasClass('attached')) {
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {
					connectRemove: widget.config.newsId,
					connectId: obj.data('connect').id
				}
			}).done(function (data) {
				if (null != data) {
					if (null != data.error) { //ошибка!
						error_tip('Сервер ответил ошибкой');
					}
					else if (null != data.saved) {//ОК!
						success_tip('Сохранено ' + data.saved);
						$('#dateupdate', widget.element).val(data.saved); //Обновляем дату модификации!
						obj.toggleClass('attached');
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
			connect = 1;
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {
					connectSave: widget.config.newsId,
					connectId: obj.data('connect').id
				}
			}).done(function (data) {
				if (null != data) {
					if (null != data.error) { //ошибка!			  							 						  
						error_tip('Сервер ответил ошибкой');
					}
					else if (null != data.saved) {//ОК!
						success_tip('Сохранено ' + data.saved);
						$('#dateupdate', widget.element).val(data.saved); //Обновляем дату модификации!
						obj.toggleClass('attached');
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
	};

	//Удаление привязаной новости
	Plugin.prototype.removeConnect = function (obj) {
		var widget = this;
		$('.connectCard', obj).each(function () {
			if (!$(this).hasClass('attached')) {
				$(this).remove();
			}
		});
	};


	Plugin.prototype.delConnect = function (e, obj) {
		var widget = this;

		if (obj.hasClass('attached')) {

			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {
					connectRemove: widget.config.newsId,
					connectId: obj.data('connect').id
				}
			}).done(function (data) {
				if (null != data) {
					if (null != data.error) { //ошибка!			  							 						  
						error_tip('Сервер ответил ошибкой');
					}
					else if (null != data.saved) {//ОК!

						success_tip('Сохранено ' + data.saved);
						$('#dateupdate', widget.element).val(data.saved); //Обновляем дату модификации!
						obj.toggleClass('attached');
						obj.remove();
						$('#blockConnectInput', widget.element).val('').focus();
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
			obj.remove();
		}
	};

//=============================================================================== Конец привязки других новостей

	// Прицепляем методы плагина к событиям
	Plugin.prototype.bindAll = function () {

		var widget = this, search_timeout;

		$('.close', widget.element).unbind().bind('click', function (e) {
			widget.hideNewsInlay();
		});
		$('.update', widget.element).unbind().bind('click', function (e) {
			widget.fillNewsAjax();
		});
		$('.switch-left').unbind().bind('click', function (e) {
			clearIntervals(false);
			widget.config.interval = false;
			widget.config.startKeyup = false;
			widget.getPrevNext(e, 'prev', widget.config.newsId);
			delInformer(widget.config.newsId);
		});
		$('.switch-right').unbind().bind('click', function (e) {
			clearIntervals(false);
			widget.config.interval = false;
			widget.config.startKeyup = false;
			widget.getPrevNext(e, 'next', widget.config.newsId);
			delInformer(widget.config.newsId);
		});

		$('.delete', widget.element).unbind().bind('click', function (e) {
			widget.deleteNews();
		});

		$('#timePublicSocial .close', widget.element).unbind('click').bind('click', function (e) {
			$('#timePublicSocial input.timetext').val('');
			$(this).hide();
			return false;
		});

		$('.save', widget.element).unbind().bind('click', function (e) {
			widget.saveAll();
		});
		$('.data-text-reset', widget.element).unbind().bind('click', function (e) {
			widget.dataReset();
		});
		$('#addFromNotepad', widget.element).unbind().bind('click', function (e) {
			widget.getNewsText('notepad');
		});
		$('#addFromBD', widget.element).unbind().bind('click', function (e) {
			widget.getNewsText('db');
		});

		$('#clearNotice', widget.element).unbind().bind('click', function (e) {
			widget.clearNotice();
		});

		$('.enterfield', widget.element).unbind().bind('keyup', function (e) {
			if (e.which == 27) {//esc				 
				$(this).val('');
			}
			else if (e.which == 13) {//enter
				widget.saveAll();
			}
		});
		//ловим натискания любой клавиши в хедер и субхедер
		$('.needautosave', widget.element).unbind().bind('keyup', function (e) {
			if (widget.config.startKeyup == false) {
				widget.config.startKeyup = true;
				widget.autosave();
				$('#write_in_notice').prop('checked', true);
				widget.showHideNotice(true);
				widget.startAutosave();
			}
		});
		//смена чекбокса *Писать в черновик*, если сняли галочку, то больше автосейв не включитса
		$('#write_in_notice', widget.element).unbind().bind('change', function (e) {
			widget.config.isNeedAutoSave = ($('#write_in_notice').prop('checked')) ? true : false;
			if (widget.config.isNeedAutoSave == false) {
				widget.PauseAutoSaving();
				widget.config.startKeyup = true;
			}
			else {
				widget.config.startKeyup = false;
			}
		});

// -------------------------Теги   

		$('#blockTagInput', widget.element).unbind().bind('keyup', function (e) {
			if (e.which == 27) {//esc				 
				clearTimeout(search_timeout);
				$(this).val('');
				widget.removeTags($(this).closest('.inputblock'));
			}
			else if (e.which == 13) {//enter
				clearTimeout(search_timeout);
				widget.searchTag();
			}
			else {
				if (search_timeout != undefined) {
					clearTimeout(search_timeout);
				}
				search_timeout = setTimeout(function () {
					search_timeout = undefined;
					widget.searchTag();
				}, 500);
			}
		});
		$('.keyword', '#blockTag').unbind().bind('click', function (e) {
			widget.saveTag(e, $(this));
		});

		$('#blockTagNewInsert', widget.element).unbind().bind('click', function (e) {
			widget.newTagWindowShow();
		});
		$('#tagSaveNew').unbind().bind('click', function (e) {
			widget.newTagSave();
			return false;
		});
		$('.newtagsave').unbind().bind('keyup', function (e) {
			if (e.which == 27) {//esc				 
				$(this).val('');
			}
			else if (e.which == 13) {//enter
				widget.newTagSave();
			}
		});

// ========================================================================Конец привязки тегов

//------------------------------------------------------------------------------Теги опроса(для виджета)

		$('#newsWidgetTag', widget.element).unbind().bind('keyup', function (e) {
			if (e.which == 27) {//esc				 
				clearTimeout(search_timeout);
				$(this).val('');
				widget.removeWidgetTags($(this).closest('.inputblock'));
			}
			else if (e.which == 13) {//enter
				clearTimeout(search_timeout);
				widget.searchWidgetTag();
			}
			else {
				if (search_timeout != undefined) {
					clearTimeout(search_timeout);
				}
				search_timeout = setTimeout(function () {
					search_timeout = undefined;
					widget.searchWidgetTag();
				}, 500);
			}
		});
		$('.keyword', '#blockWidget').unbind().bind('click', function (e) {
			widget.saveWidgetTag(e, $(this));
		});

		$('#blockWidgetTagNewInsert', widget.element).unbind().bind('click', function (e) {
			widget.newWidgetTagWindowShow();
		});
		$('#tagWidgetSaveNew').unbind().bind('click', function (e) {
			widget.newWidgetTagSave();
			return false;
		});
		$('.newwidgettagsave').unbind().bind('keyup', function (e) {
			if (e.which == 27) {//esc				 
				$(this).val('');
			}
			else if (e.which == 13) {//enter
				widget.newWidgetTagSave();
			}
		});

//------------------------------------------------------------------------------Конец тегов опроса

// -------------------------Регионы  

		$('#blockRegionInput', widget.element).unbind().bind('keyup', function (e) {
			if (e.which == 27) {//esc				 
				clearTimeout(search_timeout);
				$(this).val('');
				widget.removeRegions($(this).closest('.inputblock'));
			}
			else if (e.which == 13) {//enter
				clearTimeout(search_timeout);
				widget.searchRegion();
			}
			else {
				if (search_timeout != undefined) {
					clearTimeout(search_timeout);
				}
				search_timeout = setTimeout(function () {
					search_timeout = undefined;
					widget.searchRegion();
				}, 500);
			}
		});
		$('.keyword', '#blockRegion').unbind().bind('click', function (e) {
			widget.saveRegion(e, $(this));
		});

		$('#blockRegionNewInsert', widget.element).unbind().bind('click', function (e) {
			widget.newRegionWindowShow();
		});
		$('#regionSaveNew').unbind().bind('click', function (e) {
			widget.newRegionSave();
			return false;
		});
		$('.newregionsave').unbind().bind('keyup', function (e) {
			if (e.which == 27) {//esc				 
				$(this).val('');
			}
			else if (e.which == 13) {//enter
				widget.newRegionSave();
			}
		});

// ======================================================================================Конец привязки регионов

// -------------------------Бренд

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

// ====================================================================================Конец привязки брендов

// -------------------------Тема

		$('#blockThemesInput', widget.element).unbind().bind('keyup', function (e) {
			if (e.which == 27) {//esc				 
				clearTimeout(search_timeout);
				$(this).val('');
				widget.removeThemes($(this).closest('.inputblock'));
			}
			else if (e.which == 13) {//enter
				clearTimeout(search_timeout);
				widget.searchThemes();
			}
			else {
				if (search_timeout != undefined) {
					clearTimeout(search_timeout);
				}
				search_timeout = setTimeout(function () {
					search_timeout = undefined;
					widget.searchThemes();
				}, 500);
			}
		});

		$('.themes > span', '#blockThemes').unbind().bind('click', function (e) {
			widget.saveThemes(e, $(this).parent());
		});

		$('.smile', '#blockThemes').unbind().bind('click', function (e) {
			var val = $(this).parent().data("themes");
			if ($(this).hasClass('selected')) {
				$(this).removeClass('selected');
			} else {
				$(this).addClass('selected').find('.select').removeClass('select');
				$(e.target).addClass('select');
				val.smile = $(e.target).data("smile");
				$(this).parent().data("themes", val);
				if ($(this).parent().hasClass('in')) {
					$(this).parent().removeClass('in');
				} else {
					//$(this).parent().addClass('in');
				}
				widget.saveThemes(e, $(this).parent());
			}
		});

// ====================================================================================Конец привязки теми

// -------------------------??сточник  

		$('#blockSourceInput', widget.element).unbind().bind('keyup', function (e) {
			if (e.which == 27) {//esc				 
				clearTimeout(search_timeout);
				$(this).val('');
				widget.removeSources($(this).closest('.inputblock'));
			}
			else if (e.which == 13) {//enter
				clearTimeout(search_timeout);
				widget.searchSource();
			}
			else {
				if (search_timeout != undefined) {
					clearTimeout(search_timeout);
				}
				search_timeout = setTimeout(function () {
					search_timeout = undefined;
					widget.searchSource();
				}, 500);
			}
		});
		$('.keyword', '#blockSource').unbind().bind('click', function (e) {
			widget.saveSource(e, $(this));
		});


// ==================================================================================Конец привязки источника

// -------------------------Автор  

		$('#blockAuthorInput', widget.element).unbind().bind('keyup', function (e) {
			if (e.which == 27) {//esc				 
				clearTimeout(search_timeout);
				$(this).val('');
				widget.removeAuthor($(this).closest('.inputblock'));
			}
			else if (e.which == 13) {//enter
				clearTimeout(search_timeout);
				widget.searchAuthor();
			}
			else {
				if (search_timeout != undefined) {
					clearTimeout(search_timeout);
				}
				search_timeout = setTimeout(function () {
					search_timeout = undefined;
					widget.searchAuthor();
				}, 500);
			}
		});
		$('.keyword', '#blockAuthor').unbind().bind('click', function (e) {
			widget.saveAuthor(e, $(this));
		});


// ================================================================================Конец привязки автора

// -------------------------Связь  

		$('#blockConnectInput', widget.element).unbind().bind('keyup', function (e) {
			if (e.which == 27) {//esc				 
				clearTimeout(search_timeout);
				$(this).val('');
				widget.removeConnect($(this).closest('.inputblock'));
			}
			else if (e.which == 13) {//enter
				clearTimeout(search_timeout);
				widget.searchConnect();
			}
			else {
				if (search_timeout != undefined) {
					clearTimeout(search_timeout);
				}
				search_timeout = setTimeout(function () {
					search_timeout = undefined;
					widget.searchConnect();
				}, 500);
			}
		});
		$('.connectCard', '#attach').unbind().bind('click', function (e) {
			if (e.target.nodeName == 'IMG' || e.target.nodeName == 'DIV' || e.target.nodeName == 'H4')
				widget.saveConnect(e, $(this));
		});
		$('.del', '.connectCard').unbind().bind('click', function (e) {
			widget.delConnect(e, $(this).closest('.connectCard'));
		});

// =================================================================================Конец привязки связи

		$('#gallery').trigger('callimgs.' + 'mkAdminNewsInlayImg');
		$('#blockDocsWrap').trigger('calldocs.' + 'mkAdminNewsInlayImg');
		$('#newsImgAlt').html('');
		$('#imgDesc').hide();

		$('#newsVideoUrl').unbind().bind('input propertychange', function () {
			link = $.trim($(this).val());
			widget.setYouTube(link);
		});

		// код вставки
		$('.link', '.connectCard').unbind().bind('click', function (e) {
			var link = $(this).closest('.connectCard').data('connect');
			var name = $('h4#news' + link.id).html();
			var path = "<table class=\"also\"><tr class=\"aMainTr\"><td class=\"aSign\">Читайте також:</td><td class=\"aText\"><a href=http://" + window.location.hostname + "/news/" + link.id + ">" + name + "</a></td></tr><tr class=\"aTextWidth\"></tr></table>";
			prompt('Скопируйте код для вставки в редактор', path);
			$('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});

		});
		
			$('#newsUrlBtn').unbind().bind('click', function () { // перейти по ссилке.
			var link = $('#newsUrl').val();
			window.open(link, '_blank');
			return false;
		});
	};

	//сброс дати публикации на дату создания
	Plugin.prototype.dataReset = function () {
		var widget = this;
		$.ajax({
			type: "POST",
			url: '/admin/controllers/news.controller.php',
			dataType: 'json',
			data: {action: 'dataReset',
				newsId: widget.config.newsId}
		}).done(function (data) {
			if (data.ob) {
				$('#datepublic').val(data.time);
				success_tip('Дата сменена');
			}
			else {
				error_tip('Ошибка');
			}

		}).fail(function () {

		});
	};

	//очистить черновик
	Plugin.prototype.clearNotice = function () {
		var widget = this;
		$.ajax({
			type: "POST",
			url: '/admin/controllers/news.controller.php',
			dataType: 'json',
			data: {action: 'clearNotice',
				newsId: widget.config.newsId}
		}).done(function (data) {
			if (data.result == true) {
				if (data.ob == false) {
					error_tip('Новость уже редактируется ' + data.username);
				}
				else {
					widget.showHideNotice(false);
					widget.getNewsText('db');
					$('#write_in_notice').prop('checked', false);
					widget.config.interval = false;
					widget.config.startKeyup = false;
					success_tip('Черновик очищен');
				}
			}
			else {
				error_tip('Ошибка');
			}

		}).fail(function () {

		});
	};


	//показивания/скрития лейбла черновик
	Plugin.prototype.showHideNotice = function (show) {
		var widget = this;
		var myElement = document.querySelector("#notice_active");
		if (show == true) {
			myElement.style.border = "3px solid red";
			myElement.style.padding = " 0px 0px 0px 40%";
			myElement.style.color = "red";
			myElement.style.fontSize = "18px";
		}
		else {
			myElement.style.border = "3px solid #f5f9fb";
			myElement.style.padding = " 0px 0px 0px 40%";
			myElement.style.color = "#f5f9fb";
			myElement.style.fontSize = "18px";
		}
	};

	// Остановить автосохранение(usual == 'exit' если ми закриваем страницу с етой новостю)
	Plugin.prototype.stopAutoSaving = function (usual) {
		var widget = this;

		$('#notice_active').attr('value', 0);
		if (usual == 'exit') {
			refreshNewsList('Список новостей обновлен', function () {
				$('.newsStripe').unbind().mkAdminNews();
			});
		}
		else {
			clearIntervals(false);
		}
	};

	//отсюда запускаем автосейв с интервалом
	Plugin.prototype.startAutosave = function () {
		var widget = this;
		// auto-save, если открыто редактирование новости
		if (widget.config.newsId != 0) {
			if (widget.config.interval == false) {
				intervals.push(setInterval(function () {
					widget.autosave()
				}, 20000));
				widget.config.interval = true;
				widget.config.startKeyup = true;
			}
		}
	};

	//если юзер нажал с бд, то ми приостонавливаем autosave, но юзера не удаляем с ctbl_autosavenews в бд
	Plugin.prototype.PauseAutoSaving = function () {
		var widget = this;
		$('#write_in_notice').prop('checked', false);
		widget.showHideNotice(false);
		clearIntervals(true);
		widget.config.interval = false;
		widget.config.startKeyup = true;
		$('#notice_active').attr('value', 0);
	};

	//Информер,отправляет дание с редактором и новостю на которой он нахоитса(каждие 10 сек)
	Plugin.prototype.redactorInNews = function () {
		var widget = this;
		if (widget.config.redactorIn && widget.config.newsId != null) {
			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {action: 'redactorUpdate',
					newsId: widget.config.newsId}
			}).done(function (data) {
				$('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
			}).fail(function () {
				$('#condition').mkCondition({action: 'fade', color: 'red', fadeout: 1500});
			});
		}
	};

	//autosave news
	Plugin.prototype.autosave = function () {
		var widget = this;
		$('#notice_active').attr('value', 1);
		var text = tinyMCE.activeEditor.getContent();
		var newsHeader = $('#newsHeader').val();
		var newsSubheader = $('#newsSubheader').val();
		$.ajax({
			type: "POST",
			url: '/admin/controllers/news.controller.php',
			dataType: 'json',
			data: {action: 'autosave',
				newsId: widget.config.newsId,
				newstext: text,
				newsHeader: newsHeader,
				newsSubheader: newsSubheader}
		}).done(function (data) {
			if (data.ob) {
				//	$('#clearNotice').show();
				//	$('#notice_checkbox').show();
				success_tip('автосохранение');
			}
			else {
				error_tip('Новость уже редактируется ' + data.username);
			}

		}).fail(function () {

		});
	};

	// инициализация плагина
	Plugin.prototype.init = function () {
		var widget = this;
		if (widget.config.saveAtExit == true) {
			if ($('#notice_active').val() == 1) {
				widget.autosave();
				$('#notice_active').attr('value', 0);
			}
		}
		else {
			$('#notice_active').attr('value', 0);
			widget.showHideNotice(false);
			widget.fillNewsAjax();
			widget.bindAll();

			$('#newsInlay').mkAdminNewsInlayImg({
				newsId: widget.config.newsId
			});
			$('#newsInlay').mkAdminNewsInlaySetters({
				newsId: widget.config.newsId
			});

			setInterval(function () {
				widget.redactorInNews();
			}, 10000);

			widget.initRedactor();
		}
	};

	// Добавляем плагин к jQuery
	$.fn[pluginName] = function (options) {
		return this.each(function () {
			new Plugin($(this), options);
		});
	}
})(jQuery, window, document);
