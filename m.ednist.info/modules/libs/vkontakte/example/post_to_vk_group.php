<?php

require_once('../src/Vkontakte.php');

$accessToken = 'your access token';
$vkAPI = new \BW\Vkontakte(['access_token' => $accessToken]);

if ($vkAPI->postToPublic(70941690, "Привет Хабр!", '/tmp/habr.png', ['вконтакте api', 'автопостинг', 'первые шаги'])) {

    echo "Ура! Всё работает, пост добавлен\n";

} else {

    echo "Фейл, пост не добавлен(( ищите ошибку\n";
}

