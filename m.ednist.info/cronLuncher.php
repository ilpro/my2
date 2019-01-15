<?php

require_once 'config/iniParse.class.php';
require_once Config::getRoot()."/config/header.inc.php";

$cron = new Cron();

// Проверка отложенных новостей и смена их статуса
$cron->checkPreparedNews();





die('Cron tasks are done.');