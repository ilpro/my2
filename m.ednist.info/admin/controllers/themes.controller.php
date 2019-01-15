<?php

/**
 * Для AJAX-запросов
 */
if (!$ini) {
    require_once '../../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
Auth::checkAuth();

    $themes = new Themes();
    $search = new Search();

/**
 * AJAX
 */
    
if (isset($_POST['getthemesItem'])) {
    
    $id = $_POST['getthemesItem'];
    $thm = $themes->getThemesById(Clear::dataI($id));

    echo json_encode($thm);

    die();
}


if (isset($_POST['saveAll'])) {//Сохранить категорию
        $result = $themes->saveAll(
        Clear::dataI($_POST['saveAll']),
        Clear::dataS($_POST['themesName']),
        $_POST['themesDesc'],
        Clear::dataS($_POST['themesSearch']),
        Clear::dataS($_POST['themesType']),
        Clear::dataS($_POST['themesSortName']),
        Clear::dataS($_POST['themesActive']));
    echo json_encode($result);
    die();
}

if (isset($_POST['addNewThemes'])) {//Создать новость

    $themes->addNewThemes();
    die();
}

if (isset($_POST['setThemesStatus'])) {//Изменить статус

    $themes->setThemesStatus( $_POST['setThemesStatus'] ,$_POST['themesId'] );
    die();
}

if (isset($_POST['deleteThemes']) and is_array($_POST['deleteThemes'])) {

    foreach ($_POST['deleteThemes'] as $k) {
        $result = $themes->deleteThemes(Clear::dataI($k));
        if ($result)
            $arr[] = $result;
    }

    echo json_encode(array('clean' => 'ok'));
    die();
}

// получить все теми с поиска
    if (isset($_GET['search'])) {
        //если пустое поле поиска
        if($_GET['find']==''){
            $result=$themes->getThemes( ' ORDER BY `themesId` DESC ' );
        }
        else{
            $result = $search->searchThemes($_GET['find']);
        }
        if (isset($result) && $result != NULL) {
            foreach ($result as $item) {
                include Config::getAdminRoot() . '/views/themes/themesStripe.view.php';
            }
        }
        else {
            include Config::getAdminRoot() . '/views/themes/themesStripeEmptySearch.view.php';
        }
        die();
    }


    if ('GET' == $_SERVER['REQUEST_METHOD']) {
        $page_data['themes'] = $themes->getThemes( ' ORDER BY `themesId` DESC ' );
    }



