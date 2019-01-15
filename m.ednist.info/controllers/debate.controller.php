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
$news = new News();

if($id == 'getDebate'){
    include Config::getRoot().'/views/block/debate.ajax.php';
    echo json_encode($html);
    die();
}
$page_data['popular_news'] = $news->getPopularNews();

$sliders = [];
$sliders['popular_news'] = $news->getPopularNews();
$sliders['important'] = $news->getNews(' WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC  LIMIT 8');