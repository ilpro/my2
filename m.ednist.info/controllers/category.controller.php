<?php

    /**
     * Для AJAX-запросов
     */
    if( !$ini ) {
        require_once '../config/iniParse.class.php';
        require_once Config::getRoot()."/config/header.inc.php";
    }


    // Шаблон для вывода страницы !обязательно
    $layout = 'line';

    // Модель текущего ресурса, или других используемых
    $news = new News();
    $category = new Category();
    $brands = new Brands();


/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */
if(isset($_POST['action']) and $_POST['action']=='get_more') {

    $limit_from = (int)$_POST['limit_from'];
    $limit = 8;
    $categoryId = (int)$_POST['category'];
    $newsList = $news->getNews(" WHERE `newsStatus`=4 AND `categoryId`=" . $categoryId . " ORDER BY `newsTimePublic` DESC LIMIT ".$limit_from.','.$limit );
    $more = $news->getNews(" WHERE `newsStatus`=4 AND `categoryId`=" . $categoryId . " ORDER BY `newsTimePublic` DESC LIMIT ".($limit_from+$limit).',1' )!=false ? true : false ;
    //include "../views/block/categoryBlock.php.view.php";
    include "../views/block/exclusiveBlock.php.view.php";

   // $banner = NewsLineBanner::getRandBanner($news, $ini, 'category_'.$categoryId);
    //$res = $banner . $html;
    echo json_encode(array('html'=>$html,'more'=>$more));
    //echo json_encode(array('html'=>$res,'more'=>$more));
    die();
}
if(isset($_POST['action']) and $_POST['action']=='get_more_sidebar') {

    $limit_from = (int)$_POST['limit_from'];
    $limit = (int)$_POST['limit'];
    $newsList = $news->getNews(" WHERE `newsStatus`=4 AND `categoryId`=15 ORDER BY `newsTimePublic` DESC LIMIT ".$limit_from.','.$limit );
    $more = $news->getNews(" WHERE `newsStatus`=4 AND `categoryId`=15 ORDER BY `newsTimePublic` DESC LIMIT ".($limit_from+$limit).',1' )!=false ? true : false ;
    include "../views/block/categorySidebar.ajax.php";

    echo json_encode(array('html'=>$html,'more'=>$more));
    die();
}


/**
 * Обработка $_GET запроса текущей страницы
 */

$page_data = [];
$_SESSION['curr_sidebar'] = 0;
$page_data['category'] = $category->getCategoryByTranslit($id);
$page_data['news_bottom'] = $news->getNews(" WHERE `newsStatus`=4 AND `categoryId`=" . $page_data['category']['categoryId'] . " ORDER BY `newsTimePublic` DESC LIMIT 8" );
$page_data['rand_brand'] = $brands->getRandBrand();

$sliders = [];
$sliders['popular_news'] = $news->getPopularNews();
$sliders['important'] = $news->getNews(' WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC  LIMIT 8');