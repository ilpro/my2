//Асинхроное оновления редакторов на новости(информер) каждие 10 секунд
setInterval(function () {
	$.ajax({
		type: "POST",
		url: '/admin/controllers/news.controller.php',
		dataType: 'html',
		data: {action: 'updateInformer'}
	}).done(function (data) {
		$('.menu_informer').html(data);
		$('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});

	}).fail(function () {
		$('#condition').mkCondition({action: 'fade', color: 'red', fadeout: 1500});
	});
}, 10000);

/*
 * Оновления списка новостей
 */
function refreshNewsList(success_text, callback) {

	$.ajax({
		type: "GET",
		url: '/admin/controllers/news.controller.php',
		dataType: 'html',
		data: {getNewsList: true}
	}).done(function (data) {
		if (null != data) {

			$('#all-flavors').html(data);
			$('.show-mor').show();
			success_tip(success_text);
			callback();
		}
		else {
			error_tip('Ответ сервера не соответсвует ожидаемому');
		}
	}).fail(function () {
		error_tip('Ошибка сервера');
	});
}

/*
 * Оновления конкретной новости с списка
 */
function refreshNewsListId(success_text, callback) {

	$.ajax({
		type: "GET",
		url: '/admin/controllers/news.controller.php',
		dataType: 'html',
		data: {getNewsListId: currentNewsId}
	}).done(function (data) {
		if (null != data) {

			$('#newsStripe' + currentNewsId).html(data);
			$('.show-mor').show();
			success_tip(success_text);
			getSeeTargetStatistics($('#newsStripe' + currentNewsId));
			callback();
			
		}
		else {
			error_tip('Ответ сервера не соответсвует ожидаемому');
		}
	}).fail(function () {
		error_tip('Ошибка сервера');
	});
}

/*
 * Вивод новостей( в зависимости от фильтров и поискового слова)
 */
function getSearchNews(find, newsType, userId) {
	if (find == 'reset' && newsType == 'reset' && userId == 'reset') {
		$.bbq.removeState(['find']);
		$.bbq.removeState(['newsType']);
		$.bbq.removeState(['userId']);
		$('#filterUsers').html("Все редакторы" + '<i class="caret"></i>');
		$('#filterMaterials').html("Все Материали" + '<i class="caret"></i>');
	}
	$.ajax({
		type: "GET",
		url: '/admin/controllers/news.controller.php',
		dataType: 'html',
		data: {search: 'search', find: find, newsType: newsType, userId: userId}
	}).done(function (data) {
		if (null != data) {
			$('#all-flavors').html(data);
			//обнуляем строку поиска
			$('#tags').val('');
			success_tip('Пошук удачно закончен');
			$('.newsStripe').unbind().mkAdminNews();
			getSeeTargetStatistics($('.newsStripe'));
		}
		else {
			error_tip('Ответ сервера не соответсвует ожидаемому');
		}
	}).fail(function () {
		error_tip('Ошибка сервера');
	});
}

/*
 * Метод создания новости
 */
function createNews() {

	$.ajax({
		type: "POST",
		url: '/admin/controllers/news.controller.php',
		dataType: 'json',
		data: {addNewNews: true}
	}).done(function (data) {
		if (null != data && !data.error) {

			refreshNewsList('Создано успешно', function () {
				$('.newsStripe').unbind().mkAdminNews();
				$('.newsStripe[data-newsid=' + data + ']').first().find('.go').trigger('click');
			});
		}
		else {
			error_tip('Ответ сервера не соответсвует ожидаемому');
		}
	}).fail(function () {
		error_tip('Ошибка сервера');
	});
}

/*
 * Метод удаления новостей
 */
function deleteNews() {

	var newsids = [];

	$(".check :checked").each(function (e, elem) {
		var obj = $(this).parent().parent().attr('data-newsId');
		newsids.push(obj);
	});

	if (newsids.length > 0) {
		if (confirm("Удалить выбранные новости?")) {

			$.ajax({
				type: "POST",
				url: '/admin/controllers/news.controller.php',
				dataType: 'json',
				data: {deleteNews: newsids}
			}).done(function (data) {
				if ('ok' == data.clean) {

					success_tip("Выбранные новости удалены");

					newsids.forEach(function (el) {
						$('[data-newsId="' + el + '"]').remove();
					})
				}
				else if (data.clean == 'error') {
					error_tip('Не достаточно прав!!!');
				}
				else {
					error_tip('Ответ сервера не соответсвует ожидаемому');
				}
			}).fail(function () {
				error_tip('Ошибка сервера');
			});
		}
	} else {
		error_tip('Нужно отметить хотя бы одну новость');
	}
}

//Витягиваем статистику посещений новости з F/S
function getSeeTargetStatistics(obj){
	var data = obj;
	data.each(function () {
		var url="http://" + window.location.hostname;
		var type=($(this).data('mknews').newsType==0)?'news':'article';
		var id=$(this).data('mknews').newsId;
		var path=url+'/'+type+'/'+id;
		// path='http://www.ednist.info/news/15768';
		
		// $.ajax({
			// type: "GET",
			// url: 'http://dev1.seetarget.com/api/v1/counter/getVisits',
			// data: {pageUrl: path}
		// }).done(function (data) {
			// var data1 = JSON.parse(data);
			// if (data1.result != 'Wrong url') {			   
				// $('#newsStripe' + id).find('.s_statistics').html('S:' + data1.result);
				// $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
			// } else {
				// $('#newsStripe' + id).find('.s_statistics').html('S:X');
				// error_tip('Ошибка получения статистики SeeTarget');
			// }
		// }).fail(function () {
			// error_tip('Ошибка сервера');
		// });

		// $.ajax({
			// type: "GET",
			// url: 'http://www.follownews.info/api/v1/counter/getVisits?',
			// data: {pageUrl: path}
		// }).done(function (data) {
			// var data1 = JSON.parse(data);
			// if (data1.result != "Page doesn't exist") {
				// $('#newsStripe' + id).find('.f_statistics').html('F:' + data1.result);
				// $('#condition').mkCondition({action: 'fade', color: 'green', fadeout: 1500});
			// } else {
				// $('#newsStripe' + id).find('.f_statistics').html('F:X');
				// error_tip('Ошибка получения статистики FollowNews');
			// }
		// }).fail(function () {
			// error_tip('Ошибка сервера');
		// });
	});
}

// работа с изменением хеша
function routing() {
	// Вешаем на новости плагин работы с hash
	$('.newsStripe').unbind().mkAdminNews();
	getSeeTargetStatistics($('.newsStripe'));
	// отслеживаем изменение hash
	$(window).unbind().bind('hashchange', function (e) {
		if (null != $.bbq.getState('news')) {
			currentNewsId = parseInt(e.getState('news'));

			$('#newsInlay').mkAdminNewsInlay({
				newsId: parseInt(e.getState('news')),
				startKeyup: false
			});


		}
		else if (null != $.bbq.getState('find')) {
			//параметри поиска и фильтрации
			if (null != $.bbq.getState('find')) {
				var find = $.bbq.getState('find');
			}
			else {
				var find = null;
			}
			if (null != $.bbq.getState('newsType')) {
				var newsType = $.bbq.getState('newsType');
			}
			else {
				var newsType = null;
			}
			if (null != $.bbq.getState('userId')) {
				var userId = $.bbq.getState('userId');
			}
			else {
				var userId = null;
			}
			//для возвращения к новости(список)
			if (find == 'reset' && newsType == 'reset' && userId == 'reset')
				isNeedlazy = true;
			else {
				isNeedlazy = false;
				$('.show-mor').hide();
			}
			getSearchNews(find, newsType, userId);
			currentNewsId = 0;
			$('html, body').scrollTop(listScroll);
		}
		else if (null != $.bbq.getState('newsType')) {
			currentNewsId = 0;
			$('.newsStripe').unbind().mkAdminNews();
			$('html, body').scrollTop(listScroll);
		}
		else if (null != $.bbq.getState('userId')) {
			currentNewsId = 0;
			$('.newsStripe').unbind().mkAdminNews();
			$('html, body').scrollTop(listScroll);
		}
		else {
			//проверка чи з чорновика(якщо тру, то автосейв визвемо)
			$('#newsInlay').mkAdminNewsInlay({
				saveAtExit: true,
				newsId: currentNewsId
			});

			refreshNewsListId('Новость оновлена', function () {
				$('#newsInlay').removeClass('show');
				clearIntervals(false);
				currentNewsId = 0;
				$('.newsStripe').unbind().mkAdminNews();
				$('html, body').scrollTop(listScroll);
			});
		}
	});

	// если новость уже задана
	if (null != $.bbq.getState('news')) {
		$(window).trigger('hashchange');
	}
	//если уже задано поиск (после обновления страници)
	if ($.bbq.getState('find') != null) {
		$(window).trigger('hashchange');
	}

}

/**
 * Работа с интервалами для автосохранения
 */
var isNeedlazy = true;
var listScroll = 0;
var currentNewsId = 0;
var intervals = []; // ID интервалов автосохранения
function clearIntervals(pause) {
	intervals.forEach(function (currentValue, index) {
		clearInterval(currentValue);
		intervals.splice(index, 1);
	});

	//  widget.config.pause=pause;
	if (pause) {
		{
			success_tip('Автосохранение остановленно!');
		}
	}
	else {
		$.ajax({
			type: "POST",
			url: '/admin/controllers/news.controller.php',
			dataType: 'json',
			data: {newsId2: currentNewsId}
		}).done(function (data) {
			success_tip('Автосохранение остановленно!');
		});
	}
}

/*
 * Удаляем редактора с новости
 */
function delInformer(news_id) {
	var newsId = $.bbq.getState('news');
	if (news_id != newsId) {
		$.ajax({
			type: "POST",
			url: '/admin/controllers/news.controller.php',
			dataType: 'json',
			data: {delInformer: news_id}
		}).done(function (data) {
		});
	}
}  
	 
$(function () {

	routing();
	
	//Инициализация календаря
	$("#datepublic").datetimepicker({
		format: 'H:i d.m.Y',
		lang: 'ru',
		step: 10
	});

	// создание новости
	$('#addNews').unbind().bind('click', function () {
		createNews();
	});
	// удаление новости
	$('.trash').unbind().bind('click', function () {
		deleteNews();
	});

	// Загрузка изображений
	$('#newsGallery').mkUpload({inputName: 'imgfile[]'});
	$('#imgAddFromPath').click(function () {
		$(this).next().trigger('click');
	});

	// Загрузка документов
	$('#blockDocs').mkUpload({inputName: 'docfile[]'});
	$('#docAddFromPath').click(function () {
		$(this).next().trigger('click');
	})

	// создание новости
	$('#button_informer').unbind().bind('click', function () {
		var value = $('#button_informer').attr('data-show');
		$('.menu_informer').toggle("slide", {direction: "right"}, 500);


	});
	// всплывашки
	;
	$(function () {
		p = $('.popap__overlay')
		p.click(function (event) {
			e = event || window.event
			if (e.target == this) {
				$(p).css('display', 'none')
			}
		})
		$('.popap__overlay .close').bind('click', function () {
			p.css('display', 'none');
			return false;
		});
	});

	// logout
	$(window).mkLogin();

	$(window).mkAdminLazyLoad();

});
