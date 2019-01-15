
<!-- <script type="text/javascript" src="http://vk.com/js/api/share.js?90" charset="windows-1251"></script> -->
<meta property="og:title" content="<?= htmlspecialchars($title); ?>"/>
<meta property="og:type" content="article"/>
<?

$soc_info = [];
$type = "";
if ((isset($page_data['material']) && $id)) {
    $type = "news";
}
if (isset($page_data['brand']) && $id) {
    $type = "brand";
}

$width = 800;
$height = 400;
if ($type !== "") {
    $address = "http://" . $_SERVER['HTTP_HOST'] . "/media/socimage_" . $type . "_" . $id . ".jpg";
} else {
    $width = 400;
    $address = $ini['url.media'] . "settings/soclogo.jpg";
}
?>
<link rel="image_src" href="<?= $address ?>"/>
<meta property="og:image" content="<?= $address ?>"/>
<meta property="og:image:type" content="image/jpeg"/>
<meta property="og:image:width" content="<?= $width ?>"/>
<meta property="og:image:height" content="<?= $height ?>"/>
<meta property="og:site_name" content="Єдність.інфо"/>
<meta property="og:description" content="<?= $description ?>"/>




