
$(document).ready(function () {
       $('#statisticsSelect').unbind().bind('change', function (e) {
           var value=$(this).val();
           if(value==1){
               $('.statistics-row-count').html('Статей за день');
                $('.statistics-row-see').html('Просмотров за день');
                 $('.statistics-row-efficiency').html('Ефективность за день');
           }else if(value==2){
               $('.statistics-row-count').html('Статей за вчера');
               $('.statistics-row-see').html('Просмотров за вчера');
                 $('.statistics-row-efficiency').html('Ефективность за вчера');
           }else if(value==3){
               $('.statistics-row-count').html('Статей за неделю');
                  $('.statistics-row-see').html('Просмотров за неделю');
                 $('.statistics-row-efficiency').html('Ефективность за неделю');
           }else if(value==4){
              $('.statistics-row-count').html('Статей за месяц'); 
                 $('.statistics-row-see').html('Просмотров за месяц');
                 $('.statistics-row-efficiency').html('Ефективность за месяц');
           }
        $.ajax({
            type: "POST",
            url: '/admin/controllers/statistics.controller.php',
            dataType: 'html',
            data: {action: 'getStatistics',value:value}
        }).done(function (data) {
            if(data!=null){
                $('.main_block_statistics').html(data);
                 success_tip('Статистика получена');
            }else{
                 error_tip('Пусто');
            }
        }).fail(function () {
            error_tip('Ошибка сервера');
        });
    });


});
