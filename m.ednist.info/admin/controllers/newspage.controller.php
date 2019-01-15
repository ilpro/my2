<?php

require_once '../config/iniParse.class.php';

require_once Config::getRoot() . "/config/header.inc.php";

//$root_query = "";

$news = new Sort;

//if(isset($_REQUEST['typeField'])){ $typeField = $_REQUEST['typeField']; $_SESSION['newsType']=$_REQUEST['typeField'];}

if (isset($_REQUEST['name'])) $String = ucfirst($_REQUEST['name']);

if (isset($_REQUEST['name2'])) $String2 = $_REQUEST['name2']; else $String2 = '';

if (isset($_REQUEST['action'])) {

    if ($_REQUEST['action'] == 'addnew-Tegs') {

        $result = $news->addTag(trim($String), trim($String2));

        if ($result > 0)

            echo json_encode($result);

        else echo 'error';

    } elseif ($_REQUEST['action'] == 'addnew-Region') {

        $result = $news->addRegion(trim($String));

        if ($result > 0)

            echo json_encode($result);

        else echo 'error';

    } elseif ($_REQUEST['action'] == 'addnew-Synonym') {

        $result = $news->addSynonym(trim($String), trim($String2));

        if ($result > 0)

            echo json_encode($result);

        else echo 'error';

    } elseif ($_REQUEST['action'] == 'update_new') {

        $result = $_POST;

        if ($result > 0)

            echo json_encode($result);

        else echo 'error';

    }


}

      