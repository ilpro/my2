<?php

/**
 * Для AJAX-запросов
 */
if (!$ini) {
    require_once '../../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
Auth::checkAuth();

    $brands = new Brands();
    $search = new Search();
    $brandConnects=new BrandConnects();

/**
 * AJAX
 */
    
if (isset($_POST['getbrandItem'])) {
    
    $id = $_POST['getbrandItem'];
    $brand = $brands->getBrandById(Clear::dataI($id));

    echo json_encode($brand);

    die();
}


if (isset($_POST['saveAll'])) {//Сохранить категорию
        $result = $brands->saveAll(
        Clear::dataI($_POST['saveAll']),
        Clear::dataS($_POST['brandName']),
        $_POST['brandDesc'],
        Clear::dataS($_POST['brandSearch']),
        Clear::dataS($_POST['brandType']),
        Clear::dataS($_POST['brandSortName']),
        Clear::dataS($_POST['brandActive']));
    echo json_encode($result);
    die();
}

if (isset($_POST['addNewBrand'])) {//Создать новость

    $brands->addNewBrand();
    die();
}

if (isset($_POST['setBrandStatus'])) {//Изменить статус

    $brands->setBrandStatus( $_POST['setBrandStatus'] ,$_POST['brandId'] );
    die();
}

if (isset($_POST['deleteBrand']) and is_array($_POST['deleteBrand'])) {

    foreach ($_POST['deleteBrand'] as $k) {
        $result = $brands->deleteBrand(Clear::dataI($k));
        if ($result)
            $arr[] = $result;
    }

    echo json_encode(array('clean' => 'ok'));
    die();
}

if (isset($_POST['brandSearch'])) {//Поиск бренда
    $brandConnects->brandSearchConnect(Clear::dataS($_POST['brandSearch']), Clear::arrayI($_POST['exclude']));
    die();
}
if (isset($_POST['brandSave'])) {//Сохранение бренда
    $brandConnects->brandSave(Clear::dataI($_POST['brandSave']), Clear::dataI($_POST['brandId']));
    die();
}
if (isset($_POST['brandRemove'])) {//Удаление бренда
    $brandConnects->brandRemove(Clear::dataI($_POST['brandRemove']), Clear::dataI($_POST['brandId']));
    die();
}
if (isset($_POST['brandSaveNewName'])) {//СОхранение нового бренда
    $brandConnects->brandSaveNew(Clear::dataS($_POST['brandSaveNewName']), Clear::dataS($_POST['brandSaveNewSearch']),Clear::dataS($_POST['brandSort']),Clear::dataI($_POST['brandType']));
    die();
}
if (isset($_POST['action'])&&$_POST['action']=='changeConnect') {//Поиск бренда
    $brandConnects->brandchangeConnect(Clear::dataI($_POST['brandId']), Clear::dataI($_POST['connectBrandId']), Clear::dataS($_POST['value']));
    die();
}

// получить все досье с поиска
    if (isset($_GET['search'])) {
        //если пустое поле поиска
        if($_GET['find']==''){
            $result=$brands->getBrands( ' ORDER BY `brandId` DESC ' );
        }
        else{
            $result = $search->searchBrands($_GET['find']);
        }
        if (isset($result) && $result != NULL) {
            foreach ($result as $item) {
                include Config::getAdminRoot() . '/views/brand/brandStripe.view.php';
            }
        }
        else {
            include Config::getAdminRoot() . '/views/brand/brandStripeEmptySearch.view.php';
        }
        die();
    }


    if ('GET' == $_SERVER['REQUEST_METHOD']) {
        $page_data['brands'] = $brands->getBrands( ' ORDER BY `brandId` DESC ' );
    }



