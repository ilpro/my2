<?php

/**
 * Для AJAX-запросов
 */
if (!$ini) {
    require_once '../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}


// Шаблон для вывода страницы !обязательно
$layout = 'main';

// Модель текущего ресурса, или других используемых
$captcha = new Captcha();
$brands = new Brands();
require Config::getRoot() . '/modules/mail/PHPMailerAutoload.php';


/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */
if (isset($_POST['action']) and $_POST['action'] == 'sendLetter') {
    $result=false;
    if (isset($_POST['captcha']) && $_POST['captcha'] == $_SESSION['keystring']) {
        $email=  Clear::dataS($_POST['email']);
        $themes=  Clear::dataS($_POST['themes']);
        $message=  Clear::dataS($_POST['message']);
        $mail = new PHPMailer;
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = "ednist.send@gmail.com";
        $mail->Password = "y1y1y1y1";
        $mail->setFrom('ednist.send@gmail.com', 'Ednist.info');
        $mail->addReplyTo($email, $email);
        $mail->addAddress('ednist.info@gmail.com', 'Ednist.info');
        $mail->Subject = $themes;
        $mail->msgHTML($message);
        $mail->AltBody = 'This is a plain-text message body';
        if (!$mail->send()) {
            $result=false;
        } else {
            $result=true;
        }
        unset($_SESSION['keystring']);
        echo json_encode(array('result' => $result));
    } else {
       echo json_encode(array('result' => $result));
    }
    die();
}

if (isset($_POST['action']) and $_POST['action'] == 'generateCaptcha') {
    $result=$captcha->draw();
    echo json_encode(array('result' => $result));
    die();
}

/**
 * Обработка $_GET запроса текущей страницы
 */


$page_data['rand_brand'] = $brands->getRandBrand();

$sliders = [];
$sliders['popular_news'] = $news->getPopularNews();
$sliders['important'] = $news->getNews(' WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC  LIMIT 8');