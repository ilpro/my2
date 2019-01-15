<?php //error_reporting(E_ALL);

/**
 * Для AJAX-запросов
 */
if (!$ini) {
    require_once '../../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
Auth::checkAuth();

/**
 * Ловим загрузку фото из папки/по ссилке
 */
if (isset($_FILES['imgfile']) and isset($_POST['newsId'])) {
    echo NewsUpload::multiNewsImgs($_FILES['imgfile'], Clear::dataI($_POST['newsId']), 'images');
    die();
}

if (isset($_FILES['brandimgfile']) and isset($_POST['newsId'])) {
    echo NewsUpload::multiBrandImgs($_FILES['brandimgfile'], Clear::dataI($_POST['newsId']), 'brand');
    die();
}
if (isset($_FILES['themesimgfile']) and isset($_POST['newsId'])) {
    echo NewsUpload::multiThemesImgs($_FILES['themesimgfile'], Clear::dataI($_POST['newsId']), 'themes');
    die();
}

if (isset($_FILES['settingimgfile'])) {
  echo NewsUpload::multiSettingImgs($_FILES['settingimgfile'], 'settings','watermark');
    die();
}
if (isset($_FILES['settingimgfile2'])) {
    echo NewsUpload::multiSettingImgs($_FILES['settingimgfile2'], 'settings','soclogo');
    die();
}
if (isset($_FILES['authorimgfile']) and isset($_POST['newsId'])) {
    echo NewsUpload::multiAuthorImgs($_FILES['authorimgfile'], Clear::dataI($_POST['newsId']), 'author');
    die();
}

if (isset($_FILES['docfile']) and isset($_POST['newsId'])) {
    echo NewsUpload::multiNewsDocs($_FILES['docfile'], Clear::dataI($_POST['newsId']));
    die();
}

if (isset($_POST['docAddLink']) and isset($_POST['newsId'])) {
    echo NewsUpload::linkNewsDocs(Clear::dataS($_POST['docAddLink']), Clear::dataI($_POST['newsId']));
    die();
}

if (isset($_POST['imgAddLink']) and isset($_POST['newsId'])) {
    echo NewsUpload::linkNewsImgs(Clear::dataS($_POST['imgAddLink']), Clear::dataI($_POST['newsId']));
    die();
}

if (isset($_POST['imgBrandAddLink']) and isset($_POST['brandId'])) {
    echo NewsUpload::linkBrandImgs(Clear::dataS($_POST['imgBrandAddLink']), Clear::dataI($_POST['brandId']));
    die();
}

if (isset($_POST['imgThemesAddLink']) and isset($_POST['themesId'])) {
    echo NewsUpload::linkThemesImgs(Clear::dataS($_POST['imgThemesAddLink']), Clear::dataI($_POST['themesId']));
    die();
}

if (isset($_POST['imgSettingAddLink'])) {
    echo NewsUpload::linkSettingImgs(Clear::dataS($_POST['imgSettingAddLink']),'watermark');
    die();
}
if (isset($_POST['imgSettingAddLink2'])) {
    echo NewsUpload::linkSettingImgs(Clear::dataS($_POST['imgSettingAddLink2']),'soclogo');
    die();
}
if (isset($_POST['imgAuthorAddLink']) and isset($_POST['authorId'])) {
    echo NewsUpload::linkAuthorImgs(Clear::dataS($_POST['imgAuthorAddLink']), Clear::dataI($_POST['authorId']));
    die();
}

if (isset($_POST['desc']) and isset($_POST['setDescImgId'])) {
    echo NewsUpload::descNewsImgs(Clear::dataS($_POST['desc']), Clear::dataI($_POST['setDescImgId']));
    die();
}

if (isset($_POST['setMainImgId'])) {
    echo NewsUpload::mainNewsImgs(Clear::dataI($_POST['setMainImgId']));
    die();
}
if (isset($_POST['setMainBrandImgId'])) {
    echo NewsUpload::mainBrandsImgs(Clear::dataI($_POST['setMainBrandImgId']));
    die();
}
if (isset($_POST['setMainThemesImgId'])) {
    echo NewsUpload::mainThemesImgs(Clear::dataI($_POST['setMainThemesImgId']));
    die();
}

if (isset($_POST['delImgId'])) {
    echo NewsUpload::delNewsImgs(Clear::dataI($_POST['delImgId']));
    die();
}
if (isset($_POST['delBrandImgId'])) {
    echo NewsUpload::delBrandImgs(Clear::dataI($_POST['delBrandImgId']));
    die();
}
if (isset($_POST['delThemesImgId'])) {
    echo NewsUpload::delThemesImgs(Clear::dataI($_POST['delThemesImgId']));
    die();
}

if (isset($_POST['delDocItem']) and isset($_POST['newsId'])) {
    echo NewsUpload::delNewsDocs(Clear::dataS($_POST['delDocItem']), Clear::dataI($_POST['newsId']));
    die();
}

if (isset($_POST['delImgBrandId'])) {
    echo NewsUpload::delBrandImgs(Clear::dataI($_POST['delImgBrandId']));
    die();
}
if (isset($_POST['delImgThemesId'])) {
    echo NewsUpload::delThemesImgs(Clear::dataI($_POST['delImgThemesId']));
    die();
}
if (isset($_POST['delImgSetting'])) {
    echo NewsUpload::delSettingImgs($_POST['delImgSetting']);
    die();
}

if (isset($_POST['delImgAuthorId'])) {
    echo NewsUpload::delAuthorImgs(Clear::dataI($_POST['delImgAuthorId']));
    die();
}

if (isset($_POST['cropImgId'])) {
    echo NewsUpload::cropNewsImgs(Clear::dataI($_POST['cropImgId']), $_POST['c']);

        db::sql("SELECT * FROM `ctbl_img` WHERE `imgId`=" . $_POST['cropImgId'] );
        $tmp_res = db::query();
        if($tmp_res[0]['imgMain'] == 1) NewsUpload::mainNewsImgs($_POST['cropImgId']);

    die();
}

if (isset($_POST['cropImgBrandId'])) {
    echo NewsUpload::cropBrandImgs(Clear::dataI($_POST['cropImgBrandId']), $_POST['c']);
    die();
}
if (isset($_POST['cropImgThemesId'])) {
    echo NewsUpload::cropThemesImgs(Clear::dataI($_POST['cropImgThemesId']), $_POST['c']);
    die();
}

if (isset($_POST['cropImgAuthorId'])) {
    echo NewsUpload::cropAuthorImgs(Clear::dataI($_POST['cropImgAuthorId']), $_POST['c'], Clear::dataS($_POST['imgName']));
    die();
}
