<?php
if (!isset($ini)) {
    include "config/iniParse.class.php";
    require_once Config::getRoot() . "/config/header.inc.php";
}

include "modules/libs/WideImage/WideImage.php";
$type = $_GET['type'];
$id = $_GET['id'];

$ini = IniParse::getInstance();

$newsArr = [];
$brandsArr = [];
$newsModel = new News();
$brandsModel = new Brands();
//$logo = WideImage::load(Config::getRoot() . "/assets/img/LogoSoc400.png");  //resized to 400x400 logo

switch ($type) {
    case "news":
        $newsArr = $newsModel->getNewsById($id);
        $image = WideImage::load($newsArr['images']['main']['big']);
        break;
    case "brand";
        $brandsArr = $brandsModel->getBrands("WHERE brandId = " . $id);
        $image = WideImage::load($ini['url.media'] . "brand/" . $id . "/big/".$brandsArr[0]['imgMain']);
        break;

}

$width = 800; // width of resulting block
$width_half = $width / 2;
$height = 400; // height of resulting block
$quality = 65; // quality of jpeg
if ($image->getHeight() > $height || $image->getWidth() > $width_half) {
    $image = $image->resize($width_half, $height, "outside", "down");
}
if ($image->getHeight() < $height || $image->getWidth() < $width_half) {
    $image = $image->resize($width_half, $height, "outside", "up");
}


//$logo = $logo->resize($width_half, $height, "outside", "down");

$resultImg = WideImage::createTrueColorImage($width, $height);
$logo->copyTo($resultImg, 0, 0);
$image->copyTo($resultImg, $width_half, 0);
$resultImg->output("jpg", $quality);
