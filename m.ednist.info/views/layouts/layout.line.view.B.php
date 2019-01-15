<!DOCTYPE HTML >
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml" >
<head>
    <meta charset="utf-8">
    <? include "views/block/meta.view.php"; ?>
    <? include "views/block/socialheaders.view.php"; ?>
    <link rel="stylesheet" type="text/css" href="<?=$ini['url.assets']?>css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?=$ini['url.assets']?>css/baguetteBox.min.css">
    <link rel="stylesheet" type="text/css" href="<?=$ini['url.assets']?>css/sprite.css" />
    <link rel="stylesheet" type="text/css" href="<?=$ini['url.assets']?>css/style.css" />
    <script>
        var page = '<?=$page;?>', pageId = '<?=$id;?>', addLinkText = '<?=$settings['settingsCopyText'];?>', pageUrl = '<?=$_SERVER["REQUEST_URI"]?>';
    </script>
    <script src="<?=$ini['url.assets']?>js/jquery-1.10.1.min.js"></script>

    <? include "views/block/analytics.view.php"; ?>
    <script>
        var src="https://counter.mediastealer.com";
        document.write('<sc' + 'ript async src="' + src + '">' + '</sc' + 'ript>');
    </script>
    <script async src="https://cdn.onthe.io/io.js/zWdJcZt4iT5c"></script>

    <!-- start script for banner ad-boxes-->
    <script src="//i.holder.com.ua/t/holder.js" type="text/javascript"></script>
    <!-- end script for banner ad-boxes-->

    <!-- START GOOGLE BANNERS -->
    <script type='text/javascript'>
        var googletag = googletag || {};
        googletag.cmd = googletag.cmd || [];
        (function() {
            var gads = document.createElement('script');
            gads.async = true;
            gads.type = 'text/javascript';
            var useSSL = 'https:' == document.location.protocol;
            gads.src = (useSSL ? 'https:' : 'http:') +
              '//www.googletagservices.com/tag/js/gpt.js';
            var node = document.getElementsByTagName('script')[0];
            node.parentNode.insertBefore(gads, node);
        })();
    </script>

    <script type='text/javascript'>
        googletag.cmd.push(function() {
            googletag.defineSlot('/17774365/ednist.info', [300, 250], 'div-gpt-ad-1461249779400-0').addService(googletag.pubads());
            googletag.pubads().enableSingleRequest();
            googletag.enableServices();
        });
    </script>
    <!-- END GOOGLE BANNERS -->
</head>
<body>

<!--ad branding-->
<div id="holder_5x5_40"></div><script type="text/javascript">
    new holder("holder_5x5_40",{block:6040});
</script>
<!--ad branding-->

<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.4&appId=719939668077719";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<div class="main">
    <!--<div class="phone-container">
        <a href="https://itunes.apple.com/us/app/ednist-operativno-ta-dostovirno/id999540645?ls=1&mt=8" target="_blank" class="left-phone">
            <span class="app-store"></span>
        </a>
        <a href="https://play.google.com/store/apps/details?id=ednist.info" target="_blank" class="right-phone">
            <span class="google-play"></span>
        </a>
    </div>-->

    <div class="main-content">


        <!-- start banner ad-box -->
        <div id="holder_960x180_24" class="ad-top-banner"></div>
        <script type="text/javascript">
            new holder("holder_960x180_24",{block:6024});
        </script>
        <!-- end banner ad-box-->

        <? include "views/top_block.view.php"; ?>

        <!-- start banner ad-box-->
        <div class="clearfix">
            <div id="holder_728x90_25" class="ad-menu-banner pull-left"></div>
            <a href="/dossier/416" class="pull-right">
                <img src="/assets/img/docije_1.jpg" alt="">
            </a>
        </div>
        <script type="text/javascript">
            new holder("holder_728x90_25",{block:6025});
        </script>
        <!-- end banner ad-box-->

        <? // контентные страницы типа "/views/page.$page.view.php" !название переменной $view_page не менять
        include $view_page;
        ?>

    </div>
</div>

<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="head">
                Повідовмлення для редакції
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ico ico-close"></i>
                </button>
            </div>
            <div class="fields">
                <div class="form-row">
                    <div id="errorEmail">

                    </div>
                    <label for="inputEmail">Ваш Email</label>
                    <input id="inputEmail" type="text">
                </div>
                <div class="form-row">
                    <div id="errorThemes">

                    </div>
                    <label for="inputTheme">Тема повідомлення</label>
                    <input id="inputTheme" type="text">
                </div>
                <div class="form-row">
                    <div id="errorMessage">

                    </div>
                    <label for="inputText2">Введіть повідомлення</label>
                    <textarea id="inputText2"></textarea>
                </div>
                <div class="form-row captcha">
                    <div id="errorCaptcha">

                    </div>
                    <label for="inputCaptcha">Введіть цифри</label>
                    <input type="text" id="inputCaptcha">
                    <div class="c-img" id="captcha">

                    </div>
                    <button id="sendMessage">Відправити</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?=$ini['url.assets']?>js/jquery-ui-1.10.4.js"></script>
<script src="<?=$ini['url.assets']?>js/jquery.easing.min.js"></script>
<script src="<?=$ini['url.assets']?>js/bootstrap.min.js"></script>
<script src="<?=$ini['url.assets']?>js/baguetteBox.min.js"></script>
<script src="<?=$ini['url.assets']?>js/jquery.mkLazyLoad.js"></script>
<script src="<?=$ini['url.assets']?>js/jquery.socialBtns.js"></script>
<script src="<?=$ini['url.assets']?>js/jquery.mkGa.js"></script>
<script src="<?=$ini['url.assets']?>js/jquery.mkCaptcha.js"></script>
<script src="//cdn.jsdelivr.net/jquery.marquee/1.3.1/jquery.marquee.min.js" type="text/javascript"></script>
<script src="<?=$ini['url.assets']?>js/script.js"></script>
<? if($page == 'debate' ) echo '<script src="'.$ini['url.assets'].'js/debate.js"></script>';?>
</body>
</html>