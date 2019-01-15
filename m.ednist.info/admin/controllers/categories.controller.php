<?php

if (!$ini) {
    require_once '../../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
Auth::checkAuth();

if (isset($_SESSION['user']['role'])) {

    $category = new Category();


    if (isset($_POST['getCategoryItem'])) {//Получить конкретную категорию

        $id = $_POST['getCategoryItem'];
        echo json_encode($category->getCategoryById(Clear::dataI($id)));
        die();
    }


    if (isset($_POST['saveAll'])) {//Сохранить категорию

        $result = $category->saveAll(
            Clear::dataI($_POST['saveAll']),
            Clear::dataS($_POST['categoryName']),
            Clear::dataS($_POST['categoryTranslit']),
            Clear::dataS($_POST['categoryDesc']),
            Clear::dataS($_POST['categorySearch'])
        );
        echo json_encode($result);
        die();

    }

    if (isset($_POST['addNewCategory'])) {//Создать категорию

        $category->addNewCategory();
        die();
    }

    if (isset($_POST['deleteCategory']) and is_array($_POST['deleteCategory'])) {//удалить

        foreach ($_POST['deleteCategory'] as $k) {
            $result = $category->deleteCategory(Clear::dataI($k));
            if ($result)
                $arr[] = $result;
        }

        echo json_encode(array('clean' => 'ok'));
        die();
    }

    $page_data['categories'] = $category->getCategories();
}