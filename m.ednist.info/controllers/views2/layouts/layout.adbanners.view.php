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
        var src="http://counter.mediastealer.com";
        document.write('<sc' + 'ript async src="' + src + '">' + '</sc' + 'ript>');
    </script>

  <script async src="https://cdn.onthe.io/io.js/zWdJcZt4iT5c"></script>

  <script src="//i.holder.com.ua/t/holder.js" type="text/javascript"></script>

    <!--ADoffer add ------------------------------------------------------------------------------------------>
    <!--Adoffer Styles-->
    <style>
        /* Style minor reset*/
        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            margin: 0 auto;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, serif;

        }

        p {
            margin: 0;
            padding: 0;
        }


        a {
            text-decoration: none;
        }


        /* Main style*/
        .wrapper {
            width: 960px;
            margin: 0 auto;
        }

        .main-content {
            background-color: #f1f1f1;
        }

        .flexrow {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -webkit-flex-direction: row;
            -ms-flex-direction: row;
            flex-direction: row;
        }

        .flexcol {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .flexend {
            -webkit-align-self: flex-end;
            -ms-flex-item-align: end;
            align-self: flex-end;
        }

        .common-lh-ls {
            line-height : 18.7px;
            letter-spacing : 0.51px;
        }




        /* Header menu style*/
        .ad-name {
            text-align: center;
            font-weight : bold;
            font-size : 20px;
            line-height : 41.14px;
            letter-spacing : 0.6px;
            text-transform : uppercase;
            color : #FFFFFF;
            height: 44px;
            width: 100%;
            background-color: #64709e;
            z-index: 1000;
        }

        .menu1 {
            background-color: #3f476e;
            width: 100%;
            height: 105px;
            z-index: 1000;
        }

        .menu-item {
            text-align: center;
        }

        .menu-name {
            font-size : 18px;
            color : #A9B2D6;
        }

        .menu-name.active {
            color : #F5F6FA;
        }

        .sprite-wrapper {
            display: inline-block;
            vertical-align: middle;
            background: url("<?=$ini['url.assets']?>/img/adoffer.sprite.png") no-repeat;
            margin: 0 auto;
        }


        /* Map menu-item style*/
        .map-ref {
            padding: 20px 30px 0 35px;
            margin: 0 auto;
        }

        .map-wrapper {
            width: 64px;
            height: 45px;
        }

        .header-map             { background-position: -1px -1px;}
        .header-map-active      { background-position: -1px -47px;}



        /* People menu-item style*/
        .people-ref {
            padding: 20px 15px 0 15px;
            margin: 0 auto;
        }

        .people-wrapper {
            width: 39px;
            height: 45px;
        }

        .header-people          { background-position: -71px -1px;}
        .header-people-active   { background-position: -71px -47px;}



        /* Facebook menu-item style*/
        .fb-ref {
            padding: 20px 25px 0 25px;
            margin: 0 auto;
        }

        .fb-wrapper{
            width: 39px;
            height: 45px;
        }

        .header-fb              { background-position: -115px -1px;}
        .header-fb-active       { background-position:  -115px -47px;}



        /* Placement menu-item style*/
        .pc-ref {
            padding: 20px 45px 0 25px;
            margin: 0 auto;
        }

        .pc-wrapper {
            width: 57px;
            height: 45px;
        }

        .header-pc              { background-position: -158px -1px;}
        .header-pc-active       { background-position: -158px -47px;}

        /* Menu animation style*/
        .map-ref,
        .people-ref,
        .fb-ref,
        .pc-ref {
            -webkit-transition: all .3s ease;
            transition: all .3s ease;
        }

        .map-ref:hover,
        .people-ref:hover,
        .fb-ref:hover,
        .pc-ref:hover {
            opacity: 0.65;
        }

        .crosss{
            position: absolute;
            top: 0;
            right: 0;
            width: 25px;
            height: 25px;
            border: 1px solid black;
            cursor: pointer;
        }

        .cross-high {
            margin: 10px 0 0 0;
            height: 3px;
            background-color: black;
            transform: rotate(135deg);
            width: 100%;
        }

        .cross-low {
            margin: -3px 0 0 0;
            height: 3px;
            background-color: black;
            transform: rotate(225deg);
            width: 100%;
        }


        /* Common style*/
        iframe {
            border: none;
        }

        #advertising {
            height: 250px;
        }




        /* Submenu*/
        .submenu {
            height: 40px;
            background-color: #929bc4;
            width: 960px;
            z-index: 1000;
            position: relative;
        }

        .ad {
            padding: 0 10px;
            margin: 0 auto;
        }

        .ad1  {
            margin-left: 23px;
        }

        .ad4 {
            margin-right: 25px;
        }

        .ad-text {
            font-weight : bold;
            font-size : 15px;
            line-height : 40px;
            letter-spacing : 0.48px;
            color : #353B57;
            text-transform: uppercase;
            -webkit-transition: all .3s ease;
            transition: all .3s ease;
        }

        .ad-text.active {
            color: white;
        }

        .ad-text:hover {
            opacity: 0.65;
        }



        /* Main Content */
        .main-content {
            color : #3F476E;
            background-color: transparent;
        }

        .ad-places-text {
            font-size : 27px;
            line-height : 40px;
            letter-spacing : 0.48px;
            color : white;
            align-self: center;
            margin: 0 auto;
        }


        .adware-main-top {
            width: 960px;
            height: 250px;
            background-color: #f63435;
            z-index: 1000;
            position: relative;
        }



        .ad-indent {
            margin: 7px 0 7px 0;
        }


        .adware-main-middle-top {
            width: 728px;
            height: 90px;
            background-color: #f63435;
            z-index: 1000;
            position: relative;
        }

        .empty {
            width: 229px;
            height: 90px;
            background-color: black;
        }




        .adware-main-middle-bottom {
            width: 300px;
            height: 250px;
            background-color: #f63435;
            z-index: 1000;
            position: relative;
        }



        /* Publish page style*/

        .video-container {
            background: #ECEDED;
            width: 653px;
            height: 487px;
            margin: 30px 0 0 0;
        }

        .adware-publish-video {
            width: 640px;
            height: 480px;
            background-color: #f63435;
            margin: 0 auto;
            z-index: 1000;
            position: relative;
        }

        .video-min {
            width: 580px;
            height: 360px;
            background-color: darkred;
            align-self: center;
            margin: 0 auto;
        }
        /* Publish page  end*/




        .adware-main-bottom {
            width: 960px;
            height: 250px;
            background-color: #f63435;
            z-index: 1000;
            position: relative;
        }


        .adware-main-footer {
            width: 100%;
            height: 240px;
            background-color: #f63435;
            z-index: 1000;
            position: fixed;
            bottom: 0;
        }



    </style>
    <!--ADoffer add END------------------------------------------------------------------------------------------>

</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.4&appId=719939668077719";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>
<div class="main">













    <!--ADoffer add ------------------------------------------------------------------------------------------>
    <!-- Header -->
    <div class="flexcol">

        <div class="ad-name">Реклама на нашому сайті</div>

        <div class="menu1 flexrow">

            <a href="views/adoffer/territory_coverage.html" class="map-ref">
                <div class="menu-item flexcol">
                    <div class="sprite-wrapper map-wrapper header-map"></div>
                    <p class="menu-name">Територіальне охоплення</p>
                </div>
            </a>

            <a href="views/adoffer/audience_core.html" class="people-ref">
                <div class="menu-item flexcol">
                    <div class="sprite-wrapper people-wrapper header-people"></div>
                    <p class="menu-name">Цільова аудиторія</p>
                </div>
            </a>

            <a href="views/adoffer/facebook_audience.html" class="fb-ref">
                <div class="menu-item flexcol">
                    <div class="fb-wrapper sprite-wrapper header-fb"></div>
                    <p class="menu-name">Аудиторія Facebook</p>
                </div>
            </a>

            <a href="#" class="pc-ref">
                <div class="menu-item flexcol">
                    <div class="sprite-wrapper pc-wrapper header-pc-active"></div>
                    <p class="menu-name active">Розміщення</p>
                </div>
            </a>

        </div><!-- Menu end-->


        <!-- Submenu-->
        <div class="submenu flexrow">

            <a href="//ednist.info/adoffer" class="ad ad1">
                <div class="menu-item">
                    <p class="ad-text">Головна сторінка</p>
                </div>
            </a>

            <a href="//ednist.info/adfullnews" class="ad ad2">
                <div class="menu-item">
                    <p class="ad-text">Сторінка публікації</p>
                </div>
            </a>

            <a href="//ednist.info/adpublic" class="ad ad3">
                <div class="menu-item">
                    <p class="ad-text">Сторінка списку публікацій</p>
                </div>
            </a>

            <a href="views/adoffer/adware_placement_brand.html" class="ad ad4">
                <div class="menu-item">
                    <p class="ad-text">Брендінги</p>
                </div>
            </a>


        </div>

    </div><!-- Header end-->

    <div class="adware-main-top flexrow">
        <p class="ad-places-text">
            Ширина: 960px <br>
            Висота: до 250px
        </p>
    </div>
    <!--ADoffer add END------------------------------------------------------------------------------------------>











    <div class="main-content">

        <!--ADoffer add ------------------------------------------------------------------------------------------>
    <? include "views/top_block.adoffer.view.php"; ?>
        <!--ADoffer add END------------------------------------------------------------------------------------------>


    <? // контентные страницы типа "/views/page.$page.view.php" !название переменной $view_page не менять
    include $view_page;
    ?>

    <? include "views/block/footer.view.php"; ?>


  </div>
</div>



<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="head">
        Повідомлення для редакції
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