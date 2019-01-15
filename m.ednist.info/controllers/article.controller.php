<?php

    /**
     * Для AJAX-запросов
     */
    if( !$ini ) {
        require_once '../config/iniParse.class.php';
        require_once Config::getRoot()."/config/header.inc.php";
    }


    // Шаблон для вывода страницы !обязательно
    $layout = 'main';

    // Модель текущего ресурса, или других используемых
    $news = new News();
    $brands = new Brands();


/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */
//if(isset($_POST['action']) and $_POST['action']=='get_more') {
//
//    die();
//}


/**
 * Обработка $_GET запроса текущей страницы
 */

$page_data = [];

Auth::checkAuth(false);
//если preview просмотр админом
if (isset($_GET['preview']) and $_GET['preview'] == 1 and isset($_SESSION['user']['role'])) {
    $page_data['material'] = $news->getNewsByIdPreview($id);
} else {
    $page_data['material'] = $news->getNewsById($id);
    //вставляємо счотчик відвідувань сторінки на сайт(по дням)
   /* $date = date('Y-m-d');
    $some_news = db::sql("SELECT * FROM `ctbl_newsshow` WHERE `newsId`='$id' AND `date`='$date'");
    $some_news = db::query();
    if ($some_news == false) {
        $count = 1;
        $res = db::sql("INSERT INTO `ctbl_newsshow`(`newsId`, `count`, `date`) VALUES ('$id','$count','$date')");
        $res = db::execute();
    } else {
        $result = db::sql("UPDATE `ctbl_newsshow` SET `count`=`count`+1  WHERE `newsId`='$id' AND `date`='$date'");
        $result = db::execute();
    }*/

}
if (!$page_data['material']) {
    http_response_code(404);
    include Config::getRoot() . "/views/errors/error_404.php";
    die();
}   
      //вставляємо счотчик відвідувань сторінки на сайт(общая сума)
     $result2 = db::sql("UPDATE `tbl_news` SET `countVisits`=`countVisits`+1  WHERE `newsId`='$id'");
     $result2 = db::execute();

$page_data['popular_news'] = $news->getPopularNews();
$page_data['category_news'] = $news->getNewsByTranslit( 'war' );
$page_data['news_bottom'] = $page_data['material']['connectedNews'];
$page_data['last_video'] = $news->getLastVideo(5);
$page_data['rand_brand'] = $brands->getRandBrand();
$page_data['last_gallery'] = $news->getLastGallery(5);
$page_data['resonans']=$news->getNews(' WHERE `newsStatus`=4 AND `categoryId`=15 ORDER BY `newsTimePublic` DESC LIMIT 15');
$page_data['last_news'] = $news->getNews(' WHERE `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 15 ');


$sliders = [];
$sliders['popular_news'] = $news->getPopularNews();
$sliders['important'] = $news->getNews(' WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC  LIMIT 8');
