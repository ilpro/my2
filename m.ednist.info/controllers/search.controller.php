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
    $search = new Search();
    $news = new News();
    $brands = new Brands();


/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */

/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */
if(isset($_POST['action']) and $_POST['action']=='get_more') {

    $limit_from = (int)$_POST['limit_from'];
    $limit = 8;

    $newsList = $search->searchNews($_SESSION['search'],$limit_from,$limit);
    $more = $search->searchNews($_SESSION['search'],$limit_from + $limit,$limit)!=false ? true : false;
    include "../views/block/searchBlock.ajax.php";
    $banner = NewsLineBanner::getRandBanner($news, $ini, 'search');
    $res = $banner . $html;
    echo json_encode(array('html'=>$res,'more'=>$more));
    die();
}
/**
 * Обработка $_GET запроса текущей страницы
 */

$page_data = [];
$_SESSION['search']=$_GET['find'];
$_SESSION['curr_sidebar'] = 0;
$page_data['rand_brand'] = $brands->getRandBrand();
$page_data['news_bottom'] = $search->searchNews($_GET['find'],0,8);