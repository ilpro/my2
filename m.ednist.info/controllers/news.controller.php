<?php

/**
 * Для AJAX-запросов
 */
if (!isset($ini)) {
    require_once '../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}


// Шаблон для вывода страницы !обязательно
$layout = 'main';

// Модель текущего ресурса, или других используемых
$news = new News();
$brands = new Brands();
$fbpixel = new FBPixel();

/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */
if (isset($_POST['action']) and $_POST['action'] == 'get_more') {

    $rows = (int)$_POST['rows'];
    $in_row = (int)$_POST['in_row'];
    $limit_from = (int)$_POST['limit_from'];
    $limit = $rows * $in_row;
    $items = $news->getNewsBlock($rows, $in_row, $limit_from);
    $more = $news->getNewsBlock($rows, $in_row, $limit_from + 1) != false ? true : false;

    $html = '';
    include "../views/block/newsBlock.ajax.php";
    
    echo json_encode(array('html' => $html, 'more' => $more, 'item' => count($items)));

    die();
}

if (isset($_POST['action']) and $_POST['action'] == 'getNewsNew') {

    $items = $news->getNews(' WHERE `newsStatus`=4 AND `newsMain`=1 ORDER BY `newsTimePublic` DESC LIMIT 1 ');

    echo json_encode($items);

    die();
}


/**
 * Времений метод
 * js счетчик количества просмотров новости
 */
if (isset($_POST['action']) and $_POST['action'] == 'jsVisits') {

    $type = $_POST['page'];
    $id = (int)$_POST['pageId'];
    $res=$news->updateSeetargetVisits($type, $id);
    
    echo json_encode(array('res' => $res));

    die();
}


if (isset($_POST['action']) and $_POST['action'] == 'load-related') {

    $more = true;
    $rows = (int)$_POST['rows'];
    $in_row = (int)$_POST['in_row'];
    $limit_from = (int)$_POST['limit_from'];
    $articleId = (int)$_POST['article'];
    $limit = $rows * $in_row;
    $items = $news->getNewsByBrand($articleId);

    $html = '';
    $items = array_slice($items,$limit_from,$limit);
    $more = count($items);

    $items = $news->newsBlocksRand($rows, $in_row, $items);

    include "../views/block/newsBlock.ajax.php";


    echo json_encode(array('html' => $html, 'more' => $more, 'item' => count($items)));

    die();
}

/**
 * Обработка $_GET запроса текущей страницы
 */

$page_data = [];

if (!empty($id)) {
    Auth::checkAuth(false);
    //если preview просмотр админом
    if (isset($_GET['preview']) and $_GET['preview'] == 1 and isset($_SESSION['user']['role'])) {
        $page_data['material'] = $news->getNewsByIdPreview($id);
    } else {
        $page_data['material'] = $news->getNewsById($id);
		$page_data['material']['fbpixelCode'] = $fbpixel->getId($page_data['material']['fbpixelId']);
        //вставляємо счотчик просмотров на сайте(за сутку)
    /*    $date = date('Y-m-d');
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
     //вставляємо счотчик просмотров на сайте(за все время)
    $result2 = db::sql("UPDATE `tbl_news` SET `countVisits`=`countVisits`+1  WHERE `newsId`='$id'");
    $result2 = db::execute();
    
    $page_data['popular_news'] = $news->getPopularNews();
    $page_data['news_bottom'] = $page_data['material']['connectedNews'];
    $page_data['last_news'] = $news->getNews(' WHERE `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 15 ');
    $page_data['category_news'] = $news->getNewsByTranslit('war');
} else {
    $page_data['news'] = $news->getNewsBlock(3, 4);
    $page_data['check_news_count'] = count($news->getNewsBlock(3, 4, 12)) > 0 ? true : false;
}
$page_data['last_video'] = $news->getLastVideo(5);
$page_data['rand_brand'] = $brands->getRandBrand();
$page_data['rand_brand2'] = $brands->getRandBrand();
$page_data['last_gallery'] = $news->getLastGallery(5);
$page_data['resonans']=$news->getNews(' WHERE `newsStatus`=4 AND `categoryId`=15 ORDER BY `newsTimePublic` DESC LIMIT 15');
$page_data['last_news'] = $news->getNews(' WHERE `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 15 ');

$sliders = [];
$sliders['popular_news'] = $news->getPopularNews();
$sliders['important'] = $news->getNews(' WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC  LIMIT 8');
if(!empty($id)){
     $page_data['this_news'] = $news->getNewsById($id);
}