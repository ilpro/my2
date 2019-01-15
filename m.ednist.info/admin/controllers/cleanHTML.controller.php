<?php

if (!$ini) {
    require_once '../../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
Auth::checkAuth();

if (isset($_POST["html"])) {

    if ($_POST["method"] == 'word') {

        $word = @$_POST['html'];

        $cleaner = new HTMLCleaner();

        $cleaner->Options['UseTidy'] = true;

        $cleaner->Options['OutputXHTML'] = false;

        $cleaner->html = $word;

        $cleanHTML = $cleaner->cleanUp('utf8');

        echo $cleanHTML;

    }

    if ($_POST["method"] == 'all') {

        echo strip_tags($_POST["html"]);

    }

}
