<?php

/**
 * Для AJAX-запросов
 */
if (!$ini) {
    require_once '../../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
Auth::checkAuth();

    if (isset($_POST['getauthorItem'])) {//Наполнение новости
        $author = new Author();
        $id = $_POST['getauthorItem'];
        echo json_encode($author->getAuthorItem(Clear::dataI($id))[0]);
        die();
    }

    if (isset($_POST['saveAll'])) {//Сохранить категорию

        $author = new Author();
        $result = $author->saveAll(
            Clear::dataI($_POST['saveAll']),
            Clear::dataS($_POST['authorName']),
            $_POST['authorDesc'],
            Clear::dataS($_POST['authorSearch'])

        );

        echo json_encode($result);
        die();
    }

    if (isset($_POST['addNewAuthor'])) {//Создать новость
        $author = new Author();
        $author->addNewAuthor();
        die();
    }

    if (isset($_POST['deleteAuthor']) and is_array($_POST['deleteAuthor'])) {
        $author = new Author();
        foreach ($_POST['deleteAuthor'] as $k) {
            $result = $author->deleteAuthor(Clear::dataI($k));
            if ($result)
                $arr[] = $result;
        }

        echo json_encode(array('clean' => 'ok'));
        die();
    }


    if ('GET' == $_SERVER['REQUEST_METHOD']) {
        $author = new Author();
        $page_data['authors'] = $author->getAuthors( ' ORDER BY `authorId` DESC ' );
    }
