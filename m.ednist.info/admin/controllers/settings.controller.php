<?php

/**
 * Для AJAX-запросов
 */
if (!$ini) {
    require_once '../../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
Auth::checkAuth();


// Шаблон для вывода страницы !обязательно
$layout = 'main';

// Модель текущего ресурса, или других используемых
$settings = new Settings();

if(isset($_SESSION['user']['role'])&&$_SESSION['user']['role']==1){
    /**
     * Обработка AJAX-запросов, в конце условия обязательно ставить die();
     */
    //редактировия даних на странице настройок
    if (isset($_POST['action']) == 'open_settings') {
        $result = $settings->update($_POST);
        if ($result == false) {
            echo json_encode(array('ob' => false));
        } else {
            echo json_encode(array('ob' => true));
        }
        die();
    }
    //автозаполнения страници при откривании
    if (isset($_POST['action2']) == 'fill_settings') {
        global $ini;
        $logo1 = false;
        $logo2 = false;
        $result = $settings->getSettings();
        $result_img = $settings->getSettingImgsHTML();
        if (!empty($result_img)) {
            foreach ($result_img as $value) {
                if ($value == 'watermark.png') {
                    $logo1 = '';
                    $htmlString = '';
                    $imgName = 'watermark.png';
                    include "../views/block/imgsetting.php.view.php";
                    $logo1.=$htmlString;
                } else {
                    $logo2 = '';
                    $htmlString = '';
                    $imgName = 'soclogo.jpg';
                    include "../views/block/imgsetting.php.view.php";
                    $logo2.=$htmlString;
                }
            }
            $ob2 = true;
        } else {
            $ob2 = false;
        }
        if (!isset($result)) {
            $ob = false;
        } else {
            $ob = true;
        }
        //'ob'-ответ текстових даних,'ob2'-ответ присутности картинок
        echo json_encode(array('ob' => $ob, 'result' => $result, 'ob2' => $ob2, 'logo1' => $logo1, 'logo2' => $logo2));
        die();
    }
    //автозаполнения картинок на странице
    if (isset($_POST['action3']) == 'fill_img') {
        global $ini;
        $result_img = $settings->getSettingImgsHTML();
        if (!empty($result_img)) {
            foreach ($result as $value) {
                if ($value == 'watermark.png') {
                    $logo1 = '';
                    $htmlString = '';
                    $imgName = 'watermark.png';
                    include "../views/block/imgsetting.php.view.php";
                    $logo1.=$htmlString;
                } else {
                    $logo2 = '';
                    $htmlString = '';
                    $imgName = 'soclogo.jpg';
                    include "../views/block/imgsetting.php.view.php";
                    $logo2.=$htmlString;
                }
            }
            echo json_encode(array('ob' => true, 'logo1' => $logo1, 'logo2' => $logo2));
        } else {
            echo json_encode(array('ob' => false));
        }
        die();
    }
}
else{
    exit();
}


/**
 * Обработка $_GET запроса текущей страницы
 */
$page_data = [];



