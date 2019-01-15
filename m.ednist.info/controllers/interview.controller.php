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
$page_data = [];
$_SESSION['curr_sidebar'] = 0;
$page_data['rand_brand'] = $brands->getRandBrand();

$page_data['news'] = $news->getNews(" WHERE `newsStatus`= 4 AND `newsType` = 3 ORDER BY `newsTimePublic` DESC" );


$sliders = [];
$sliders['popular_news'] = $news->getPopularNews();
$sliders['important'] = $news->getNews(' WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC  LIMIT 8');