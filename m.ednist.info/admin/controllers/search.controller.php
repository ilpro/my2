<?php

if (!$ini) {
    require_once '../../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
Auth::checkAuth();

if ('POST' == $_SERVER['REQUEST_METHOD'] ) {
    if (isset($_POST['searchTag'])) {//Наполнение новости
        $search = new Search;
        $search->searchTag(Clear::dataS($_POST['searchTag']), Clear::arrayI($_POST['exclude']));
    }
}
