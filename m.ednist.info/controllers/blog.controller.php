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


/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */
if(isset($_POST['action']) and $_POST['action']=='get_more') {

    $rows = (int)$_POST['rows'];
    $in_row = (int)$_POST['in_row'];
    $limit_from = (int)$_POST['limit_from'];

    $limit = $rows*$in_row;

    $items = $news->getNews( ' WHERE `newsStatus`=4 AND `newsType`=2 ORDER BY `newsTimePublic` DESC LIMIT '.$limit_from.','.$limit );
    $more = $news->getNews( '  WHERE `newsStatus`=4 AND `newsType`=2 ORDER BY `newsTimePublic` DESC LIMIT  '.($limit_from+$limit).',1' )!=false ? true : false ;

    $html = '';
    if( !empty($items) ) {
        foreach ($items as $item) {
            include "../views/block/blogLineBlock.ajax.php";
        }
    }

    echo json_encode(array('html'=>$html,'more'=>$more,'item'=>count($items)));

    die();
}


/**
 * Обработка $_GET запроса текущей страницы
 */

$page_data = [];
if (!$id) {
    
    $page_data['blogs'] = $news->getNews(' WHERE `newsStatus`=4 AND `newsType`=2 ORDER BY `newsTimePublic` DESC LIMIT 12 ');
    $page_data['check_news_count'] =$news->getNews(' WHERE `newsStatus`=4 AND `newsType`=2 ORDER BY `newsTimePublic` DESC LIMIT 12,1 ') !=false ? true : false ;    
}else{
     Auth::checkAuth(false);
    //если preview просмотр админом
    if (isset($_GET['preview']) and $_GET['preview'] == 1 and isset($_SESSION['user']['role'])) {
        $page_data['material'] = $news->getBlogByIdPreview($id);
    } else {
        $page_data['material'] = $news->getBlogById($id);
    }

    if( !$page_data['material'] ) {
        http_response_code(404);
        include Config::getRoot()."/views/errors/error_404.php";
        die();
    }
    $page_data['popular_news'] = $news->getPopularNews();
    $page_data['category_news'] = $news->getNewsByTranslit('war');
    $page_data['last_news'] = $news->getNews(' WHERE `newsStatus`=4 AND `newsType`<2 ORDER BY `newsTimePublic` DESC LIMIT 15 ');
}

$sliders = [];
$sliders['popular_news'] = $news->getPopularNews();
$sliders['important'] = $news->getNews(' WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC  LIMIT 8');