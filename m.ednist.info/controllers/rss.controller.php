<?php

/**
 * Для AJAX-запросов
 */
if( !$ini ) {
    require_once '../config/iniParse.class.php';
    require_once Config::getRoot()."/config/header.inc.php";
}

$category = new Category();
$brands = new Brands();

/**
 * Обработка $_GET запроса текущей страницы
 */

$page_data = [];

$page_data['serv'] = $_SERVER['HTTP_HOST'];
$page_data['categories'] = $category->getCategories();
$page_data['rand_brand'] = $brands->getRandBrand();