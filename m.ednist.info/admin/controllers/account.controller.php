<?php

/**
 * Для AJAX-запросов
 */
if (!$ini) {
    require_once '../../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
Auth::checkAuth();

$layout = 'login';

if (isset($_GET['id'])) $act = $_GET['id'];
else $act = '';
