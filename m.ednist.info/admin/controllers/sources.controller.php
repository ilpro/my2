<?php
if (!$ini) {
    require_once '../../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
Auth::checkAuth();

    if ('POST' == $_SERVER['REQUEST_METHOD']) {


        if (!isset($ini)) {
            require_once "../../config/iniParse.class.php";
            require_once Config::getRoot() . "/config/header.inc.php";
        }

    }

    if ('GET' == $_SERVER['REQUEST_METHOD']) {
        $source = new Source();
        $page_data['sources'] = $source->getSources();
    }