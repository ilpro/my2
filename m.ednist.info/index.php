<?php
include 'config/iniParse.class.php';
include Config::getRoot()."/config/header.inc.php";
include Config::getRoot()."/config/appHandler.php";

if($_SERVER['HTTP_REFERER'] == "http://www.mediastealer.com")
	file_put_contents(Config::getRoot() . "/visits.log", $_SERVER['REMOTE_ADDR'] . "\n", FILE_APPEND);
