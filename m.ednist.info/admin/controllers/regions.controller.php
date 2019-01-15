<?php

if (!$ini) {
    require_once '../../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
Auth::checkAuth();

$region = new Region();
$search = new Search();

    if (isset($_POST['getregionItem'])) {//Наполнение
        $region = new Region();
        echo json_encode($region->getRegionItem(Clear::dataI($_POST['getregionItem'])));
        die();
    }


    if (isset($_POST['saveAll'])) {//Сохранить
    
        $result = $region->saveAll(
            Clear::dataI($_POST['saveAll']),
            Clear::dataS($_POST['regionName']),
            Clear::dataS($_POST['regionSearch'])
        );
        echo json_encode($result);
        die();
    }

    if (isset($_POST['addNewRegion'])) {//Создать новость

        $region->addNewRegion();
        die();
    }

    if (isset($_POST['deleteRegion']) and is_array($_POST['deleteRegion'])) {//Наполнение новости

        foreach ($_POST['deleteRegion'] as $k) {
            $result = $region->deleteRegion(Clear::dataI($k));
            if ($result)
                $arr[] = $result;
        }
        echo json_encode(array('clean' => 'ok'));
        die();
    }
    
    // получить все региони с поиска
    if (isset($_GET['search'])) {
        //если пустое поле поиска
        if($_GET['find']==''){
            $result=$region->getRegions( ' ORDER BY `regionId` DESC ' );
        }
        else{
            $result = $search->searchRegions($_GET['find']);
        }
        if (isset($result) && $result != NULL) {
            foreach ($result as $item) {
                include Config::getAdminRoot() . '/views/region/regionStripe.view.php';
            }
        }
        else {
            include Config::getAdminRoot() . '/views/region/regionStripeEmptySearch.view.php';
        }
        die();
    }

    if ('GET' == $_SERVER['REQUEST_METHOD']) {
        $page_data['regions'] = $region->getRegions( ' ORDER BY `regionId` DESC ' );
    }
