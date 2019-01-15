<!DOCTYPE html>

<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link rel="stylesheet" type="text/css" href="/admin/assets/css/global.css"/>
    <link rel="stylesheet" type="text/css" href="/admin/assets/css/jquery.datetimepicker.css"/>
    <link rel="stylesheet" type="text/css" href="/admin/assets/css/jquery.Jcrop.css"/>
    <link rel="stylesheet" type="text/css" href="/admin/assets/css/page.<?= $page; ?>.css"/>

    <script type="text/javascript" src="/admin/assets/js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="/admin/assets/js/jquery-ui-1.10.4.js"></script>
    <script type="text/javascript" src="/admin/assets/js/jquery.datetimepicker.js"></script>
    <script type="text/javascript" src="/admin/assets/js/jquery.dropdown.js"></script>
    <script type="text/javascript" src="/admin/assets/js/jquery.Jcrop.js"></script>
    <script type="text/javascript" src="/admin/assets/js/tinymce/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="/admin/assets/js/select2.js"></script>
    <script type="text/javascript" src="/admin/assets/js/jquery.ba-bbq.min.js"></script>
    <script type="text/javascript" src="/admin/assets/js/md5.js"></script>
</head>
<body>

<div class="wrapper">

    <? require_once $view_page; ?>

</div>

<div class="clear"></div>
<div class="helper"></div>
<a id="condition" title='закрыть'>
</a>

<? include Config::getAdminRoot() . "/views/block/popup.view.php"; ?>


<script type="text/javascript" src="/admin/assets/js/jquery.mkCondition.js"></script>
<script type="text/javascript" src="/admin/assets/js/app.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkUpload.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkLogin.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminNews.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminNewsInlay.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminNewsInlayImg.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminNewsInlaySetters.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminBrand.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminBrandInlay.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminBrandConnectInlay.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminThemes.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminThemesInlay.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminCategory.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminCategoryInlay.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminTagInlay.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminTag.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminRegionInlay.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminRegion.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminAuthorInlay.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminAuthor.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminSettingInlay.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkBlink.min.js"></script>
<script type="text/javascript" src="/admin/assets/js/jquery.mkAdminLazyLoad.js"></script>
<script type="text/javascript" src="/admin/assets/js/page.<?= $page; ?>.js"></script>


</body>
</html>