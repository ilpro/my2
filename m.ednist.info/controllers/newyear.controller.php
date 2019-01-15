<?php

/**
 * ��� AJAX-��������
 */
if( !$ini ) {
    require_once '../config/iniParse.class.php';
    require_once Config::getRoot()."/config/header.inc.php";
}


// ������ ��� ������ �������� !�����������
$layout = 'main';

// ������ �������� �������, ��� ������ ������������
$news = new News();


/**
 * ��������� AJAX-��������, � ����� ������� ����������� ������� die();
 */
/**
 * ��������� $_GET ������� ������� ��������
 */
if(!empty($id)){
    $page_data = [];
    $_SESSION['curr_sidebar'] = 0;
    $page_data['rand_brand'] = $brands->getRandBrand();
    $page_data['category'] = $category->getCategoryByTranslit($id);

    if (!$page_data['category']) {
        http_response_code(404);
        include Config::getRoot() . "/views/errors/error_404.php";
        die();
    }
    $page_data['news'] = $news->getNews(" WHERE `newsStatus`=4 AND `categoryId`=" . $page_data['category']['categoryId'] . " ORDER BY `newsTimePublic` DESC " );

}

$sliders = [];
$sliders['popular_news'] = $news->getPopularNews();
$sliders['important'] = $news->getNews(' WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC  LIMIT 8');