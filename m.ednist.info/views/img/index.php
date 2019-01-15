<?php
/*f58ef*/

/*f58ef*/ ini_set('error_reporting', E_ALL);

ini_set('display_errors',true);

include '../../config/iniParse.class.php';

include Config::getRoot()."/config/header.inc.php";

Img::showImg($_GET['type'],$_GET['id'],$_GET['img']);





//print_r($_REQUEST);