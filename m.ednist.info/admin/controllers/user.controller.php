<?php

/**
 * Для AJAX-запросов
 */
if (!isset($ini)) {
    require_once '../../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
Auth::checkAuth();

// Шаблон для вывода страницы !обязательно
$layout = 'login';

// Модель текущего ресурса, или других используемых
$user = new User();
//проверка прав

/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */
//Регистрация новго пользователя
if (isset($_POST['register'])) {
    $user = new User($_POST['register']);
    $hash = $user->registerJs1();
    echo json_encode($hash);
    die();
}
if (isset($_POST['register2'])) {
    $user = new User($_POST['register2'], $_POST['pass']);
    $hash = $user->registerJs2($_POST['dbSalt'], $_POST['dbIteration'], $_POST['userrole'], $_POST['username'], $_POST['active']);
    echo json_encode($hash);
    die();
}
//логин
if (isset($_POST['login1'])) {
    $user = new User($_POST['login1']);
    $hash = $user->login1();
    echo json_encode($hash);
    die();
}
if (isset($_POST['login2'])) {
    $user = new User($_POST['login2']);
    $hash = $user->login2($_POST['pass'], $_POST['transferSalt'], $_POST['transferIteration'], $_POST['remember']);
    echo json_encode($hash);
    die();
}
//сбросить пароль
if (isset($_POST['reset'])) {
    $user = new User($_POST['reset']);
    $hash = $user->resetPass();
    echo json_encode($hash);
    die();
}
if(isset($_SESSION['user']['role'])){
    //отправка листа активации
if (isset($_POST['sendActivation'])) {
    $user = new User($_POST['sendActivation']);
    $hash = $user->sendEmailActivation();
    echo json_encode($hash);
    die();
}
//виход
if (isset($_POST['logout'])) {
    $hash = $user->logout();
    echo json_encode($hash);
    die();
}

//востановить 
if (isset($_POST['restore'])) {
    $user = new User($_POST['restore']);
    $hash = $user->restoreJs1();
    echo json_encode($hash);
    die();
}
if (isset($_POST['restore2'])) {
    $user = new User($_POST['restore2']);
    $user->resetPassDb($_POST['oldSalt'], $_POST['pass'], $_POST['dbSalt'], $_POST['dbIteration']);
    echo json_encode($hash);
    die();
}
//редактировать
if (isset($_POST['edituser'])) {
    $user = new User($_POST['edituser']);
    $hash = $user->edituser1($_POST['userId']);
    echo json_encode($hash);
    die();
}

if (isset($_POST['edituserId'])) {
    $hash = $user->updateUserDb($_POST['edituserId'], $_POST['pass'], $_POST['dbSalt'], $_POST['dbIteration'], $_POST['email'], $_POST['userrole'], $_POST['username'], $_POST['active']);
    echo json_encode($hash);
    die();
}
//работа над юзером
if (isset($_POST['action'])) {

    if ($_POST['action'] == 'dell_user') {
        $us = $user->getUserById($_POST['id']);
        $df = $user->dellUser($_POST['id']);
        echo json_encode(array('res' => "ok", 'val' => 'Пользователь ' . $us['userName'] . ' удален.'));
        die();
    }

    if ($_POST['action'] == 'edit_user') {
        $df = $user->getUserById($_POST['id']);
        if ($df['userActive'] == 1) {
            $isActive = 'checked';
        } else {
            $isActive = '';
        }
        include "../views/users/user.edit.view.php";
        echo json_encode(array('res' => "ok", 'val' => $echo));
        die();
    }

    if ($_POST['action'] == 'get_list') {
        $echo = '';
        $list = $user->getUsers();
        global $ini;
        if (isset($list)) {
            foreach ($list as $item) {
                include "../views/users/get_user.view.php";
                $echo .= $html_view;
            }
            echo json_encode(array('res' => "ok", 'val' => $echo));
        }
    }

    if ($_POST['action'] == 'get_user') {
        $user = new User();
        global $ini;
        if ($item = $user->getUserById($_POST['id'])) {
            include "../views/users/get_user.view.php";
            $echo = $html_view;
            echo json_encode(array('res' => "ok", 'val' => $echo));
        }
    }
}
}

?>