<?php //ini_set('error_reporting', E_ALL);

    /**
     * Для AJAX-запросов
     */
    if(!isset($ini)) {
        require_once '../config/iniParse.class.php';
        require_once Config::getRoot()."/config/header.inc.php";
    }

    // Шаблон для вывода страницы !обязательно
    $layout = 'main';

    // Модель текущего ресурса, или других используемых
    $news = new News();
    $brands = new Brands();
    $category = new Category();


/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */
if(isset($_POST['action']) and $_POST['action']=='get_more') {

    $limit_from = (int)$_POST['limit_from'];
    $limit = (int)$_POST['limit'];
    $newsList = $news->getNews( ' WHERE `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT '.$limit_from.','.$limit );
    $more = $news->getNews( '  WHERE `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT  '.($limit_from+$limit).',1' )!=false ? true : false ;

    include "../views/block/newsBlockMain.ajax.php";
    echo json_encode(array('html'=>$html,'more'=>$more));
    die();
}


/**
 * Обработка $_GET запроса текущей страницы
 */

$page_data = [];

$page_data['main_news'] = $news->getNews(' WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 4 ');
$page_data['last_news'] = $news->getNews(' WHERE `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 15 ');
$page_data['infografics'] = $news->getNewsByTranslit('infografika',15);
$page_data['brands'] = $brands->getBrands( ' ORDER BY `brandId` DESC LIMIT 9' );
$page_data['rand_brand'] = $brands->getRandBrand();
$page_data['rand_brand2'] = $brands->getRandBrand();
$page_data['news_bottom'] = $news->getNews(' WHERE `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 23 ');
$page_data['popular_news'] = $news->getPopularNews();
$page_data['exclusive'] = $news->getNews(' WHERE `newsStatus`=4 AND `newsExclusive`=1 ORDER BY `newsTimePublic` DESC LIMIT 4 ');
$page_data['resonans']=$news->getNews(' WHERE `newsStatus`=4 AND `categoryId`=15 ORDER BY `newsTimePublic` DESC LIMIT 4');
$page_data['interview']=$news->getNews(' WHERE `newsStatus`=4 AND `newsType`=3 ORDER BY `newsTimePublic` DESC LIMIT 6');

$sliders = [];
$sliders['popular_news'] = $news->getPopularNews();
$sliders['important'] = $news->getNews(' WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC  LIMIT 8');