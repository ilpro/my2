<?php

/**
 * Для AJAX-запросов
 */
if (!$ini) {
    require_once '../../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
Auth::checkAuth();

$brandsCon = new BrandConnects();
    
if (isset($_POST['action']) and $_POST['action']=='createBrandConnect') {
    $result=$brandsCon->createBrandConnect(Clear::dataS($_POST['name']));
    echo json_encode(array('result'=>$result));
    die();
}   

if (isset($_POST['action']) and $_POST['action']=='getBrandConnect') {
    $result=$brandsCon->getBrandConnect();
     if( !empty($result) ) {
        foreach ($result as $item) {
            include Config::getAdminRoot() . '/views/brand/brandConnect.view.php';
        }
    }

    die();
}  
if (isset($_POST['action']) and $_POST['action']=='editBrandConnect') {
    $result=$brandsCon->editBrandConnect(Clear::dataS($_POST['name']),Clear::dataI($_POST['id']));
    echo json_encode(array('result'=>$result));
    die();
} 

if (isset($_POST['action']) and $_POST['action']=='deleteBrandConnect') {
    $result=$brandsCon->deleteBrandConnect(Clear::dataI($_POST['id']));
    echo json_encode(array('result'=>$result));
    die();
} 



