<?php

if (!$ini) {
    require_once '../../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
Auth::checkAuth();

    $tag = new Tag();
    $search = new Search();
    
    if (isset($_POST['gettagItem'])) {//Наполнение

        $id = $_POST['gettagItem'];
        $tag = $tag->getTagById(Clear::dataI($id));
        echo json_encode($tag);
        die();
    }

    if (isset($_POST['getSearchText'])) {//Получить поисковый текст

        $tag->getSearchText(Clear::dataI($_POST['getSearchText']));
        die();
    }

    if (isset($_POST['saveAll'])) {//Сохранить tag

        $result = $tag->saveAll(
            Clear::dataI($_POST['saveAll']),
            Clear::dataS($_POST['tagName']),
            Clear::dataS($_POST['tagSearch'])
        );
        echo json_encode($result);
        die();
    }

    if (isset($_POST['addNewTag'])) {//Создать tag

        $tag->addNewTag();
        die();
    }

    if (isset($_POST['deleteTag']) and is_array($_POST['deleteTag'])) {//Наполнение новости

        foreach ($_POST['deleteTag'] as $k) {
            $result = $tag->deleteTag(Clear::dataI($k));
            if ($result)
                $arr[] = $result;
        }


        echo json_encode(array('clean' => 'ok'));
        die();
    }
    
    // получить все теги с поиска
    if (isset($_GET['search'])) {
        //если пустое поле поиска
        if($_GET['find']==''){
            $result=$tag->getTags();
        }
        else{
            $result = $search->searchTags($_GET['find']);
        }
        if (isset($result) && $result != NULL) {
            foreach ($result as $item) {
                include Config::getAdminRoot() . '/views/tag/tagStripe.view.php';
            }
        }
        else {
            include Config::getAdminRoot() . '/views/tag/tagStripeEmptySearch.view.php';
        }
        die();
    }


    if ('GET' == $_SERVER['REQUEST_METHOD']) {
        $page_data['tags'] = $tag->getTags();
    }
