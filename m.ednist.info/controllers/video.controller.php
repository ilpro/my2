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
    $brands = new Brands();


/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */
if(isset($_POST['action']) and $_POST['action']=='get_more') {

    $limit_from = (int)$_POST['limit_from'];
    $limit = 9;
    $newsList = $news->getNews( ' WHERE `newsIsVideo`=1 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT '.$limit_from.','.$limit );
    $more = $news->getNews( '  WHERE `newsIsVideo`=1 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT  '.($limit_from+$limit).',1' )!=false ? true : false ;
    include "../views/block/mediaBlock.ajax.php";
    $banner = NewsLineBanner::getRandBanner($news, $ini, 'public');
    $res = $banner . $html;
    echo json_encode(array('html'=>$res,'more'=>$more));
    die();
}


/**
 * Обработка $_GET запроса текущей страницы
 */

$page_data = [];

$page_data['news_bottom'] = $news->getNews(" WHERE `newsIsVideo`=1 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 9" );
$page_data['rand_brand'] = $brands->getRandBrand();

$sliders = [];
$sliders['popular_news'] = $news->getPopularNews();
$sliders['exclusive'] = $news->getNews(' WHERE `newsStatus`=4 AND `newsExclusive`=1 ORDER BY `newsTimePublic` DESC LIMIT 4 ');

//$page_data['check_articles_count'] = count( $news->getNews(' WHERE `newsType`=1 ORDER BY `newsTimePublic` DESC LIMIT 6,6 ') ) > 0 ? true : false ;