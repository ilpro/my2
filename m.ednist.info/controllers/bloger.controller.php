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
$author = new Author();
$news = new News();


/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */
if (isset($_POST['action']) and $_POST['action'] == 'get_more') {

    $rows = (int)$_POST['rows'];
    $in_row = (int)$_POST['in_row'];
    $limit_from = (int)$_POST['limit_from'];

    $limit = $rows * $in_row;

    $items = $author->getAuthors(' ORDER BY `authorId` LIMIT ' . $limit_from . ',' . $limit);
    $more = $author->getAuthors(' ORDER BY `authorId` LIMIT ' . ($limit_from + $limit) . ',1') != false ? true : false;

    include "../views/block/blogerBlock.ajax.php";

    echo json_encode(array('html' => $html, 'more' => $more, 'item' => count($items)));
    die();
}


/**
 * Обработка $_GET запроса текущей страницы
 */

$page_data = [];

if (!$id) {
    $page_data['authors_all'] = $author->getAuthors();
    $page_data['authors'] = $author->getAuthors(' ORDER BY `authorId` LIMIT 8 ');

    $page_data['check_author_count'] = $author->getAuthors(' ORDER BY `authorId` LIMIT 8,1 ')!=false ? true : false;

} else {
    $page_data['author'] = $author->getAuthorById($id);

    if( !$page_data['author'] ) {
        http_response_code(404);
        include Config::getRoot()."/views/errors/error_404.php";
        die();
    }
    $page_data['news_bottom'] = $news->getNewsByAuthor($id);
    $page_data['news_bottom'] = $news->newsBlocksRand(3, 4, $page_data['news_bottom']);
    $page_data['check_news_count'] = $news->getNewsByAuthor($id, count($page_data['news_bottom']));
}

$sliders = [];
$sliders['popular_news'] = $news->getPopularNews();
$sliders['important'] = $news->getNews(' WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC  LIMIT 8');