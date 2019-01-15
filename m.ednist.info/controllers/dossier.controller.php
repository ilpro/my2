<?php

/**
 * Для AJAX-запросов
 */
if (!$ini) {
    require_once '../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}


// Шаблон для вывода страницы !обязательно
$layout = 'main';

// Модель текущего ресурса, или других используемых
$brands = new Brands();
$news = new News();


/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */
if (isset($_POST['action']) and $_POST['action'] == 'get_more') {

    $rows = (int)$_POST['rows'];
    $in_row = (int)$_POST['in_row'];
    $limit_from = (int)$_POST['limit_from'];

    $limit = $rows * $in_row;

    $items = $brands->getBrands(' WHERE `brandStatus`=4 ORDER BY `brandSortName` LIMIT ' . $limit_from . ',' . $limit);
    $more = $brands->getBrands(' WHERE `brandStatus`=4 ORDER BY `brandSortName` LIMIT ' . ($limit_from + $limit) . ',1') != false ? true : false;

    include "../views/block/dossierBlock.ajax.php";

    echo json_encode(array('html' => $html, 'more' => $more, 'item' => count($items)));
    die();
}


/**
 * Обработка $_GET запроса текущей страницы
 */

$page_data = [];

if (!$id) {
    $page_data['brands_all'] = $brands->getBrands(' WHERE `brandStatus`=4 ORDER BY `brandSortName` ');
    $page_data['brands'] = $brands->getBrands(' WHERE `brandStatus`=4 ORDER BY `brandSortName` LIMIT 8 ');
    $page_data['check_brands_count'] = count($brands->getBrands(' WHERE `brandStatus`=4 LIMIT 8 ')) > 0 ? true : false;
} else {
    $page_data['brand'] = $brands->getBrandById($id, 'client');
  //  var_dump($page_data['brand']['images']);die;
    if( !$page_data['brand'] ) {
        http_response_code(404);
        include Config::getRoot()."/views/errors/error_404.php";
        die();
    }
    $page_data['news_bottom'] = $news->getNewsByBrand($id);
    $page_data['news_bottom'] = $news->newsBlocksRand(3, 4, $page_data['news_bottom']);
    $page_data['check_news_count'] = $news->getNewsByBrand($id, count($page_data['news_bottom']));
}
$page_data['last_video'] = $news->getLastVideo(5);
$page_data['rand_brand'] = $brands->getRandBrand();
$page_data['popular_news'] = $news->getPopularNews();
$page_data['last_gallery'] = $news->getLastGallery(5);
$page_data['resonans']=$news->getNews(' WHERE `newsStatus`=4 AND `categoryId`=15 ORDER BY `newsTimePublic` DESC LIMIT 15');
$page_data['last_news'] = $news->getNews(' WHERE `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 15 ');

$sliders = [];
$sliders['popular_news'] = $news->getPopularNews();
$sliders['important'] = $news->getNews(' WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC  LIMIT 8');