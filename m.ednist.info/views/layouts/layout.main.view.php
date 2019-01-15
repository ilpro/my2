<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#372A6E">
    <link rel="stylesheet" href="<?= $ini['url.assets'] ?>sass/main.css">
    <link rel="stylesheet" type="text/css" href="<?= $ini['url.assets'] ?>css/slick.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $ini['url.assets'] ?>css/slick-theme.css"/>
    <link rel="stylesheet" href="<?= $ini['url.assets'] ?>css/4.0.style.css">
     <? include "views/block/meta.view.php"; ?>
     <? include "views/block/socialheaders.view.php"; ?>
     <? include "views/block/analytics.view.php"; ?>



    <script>
        var src="https://counter.mediastealer.com";
        document.write('<sc' + 'ript async src="' + src + '">' + '</sc' + 'ript>');
    </script>

</head>
<body>
<div class="header-mobile ">
    <div class="header-logo">

    </div>
<?php
$arrayPages = ['important'=>'Важливо',
    'exclusive'=>'Ексклюзив',
    'interview'=>'IНТЕРВ’Ю',
    'analytics'=>'АНАЛIТИКА',
    'resonans'=>'Резонанс',
    'public'=>'Публікації',
    'popular'=>'Популярне',
    'infografika'=>'Інфографіка',
    'dossier'=>'Досьє'];
if($page == 'category'){
     $thisPage = !empty($arrayPages[$id])?$arrayPages[$id]:'';
}else{
     $thisPage = !empty($arrayPages[$page])?$arrayPages[$page]:'';
}

?>

    <div class="hamburger">
        <div class="h-line h-top"></div>
        <div class="h-line h-middle"></div>
        <div class="h-line h-bottom"></div>
    </div>
    <div class="center-content">
        <div class="page-head news">
            <a href="/"><div class="page-name">
                    <svg class="logo-ico" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                        <path d="M0,0V30H30V0ZM15.484,25.033a9.709,9.709,0,0,1-9.829-9.687,9.864,9.864,0,0,1,9.829-9.872A10.041,10.041,0,0,1,25.091,13.2H20.262a5.324,5.324,0,0,0-4.778-3.187A5.314,5.314,0,0,0,10.671,13.2H15.6v3.6H10.666a4.606,4.606,0,0,0,4.818,3.288c2.214,0,4.092-.888,4.846-3.288h4.8A9.815,9.815,0,0,1,15.484,25.033Z"
                              fill="#45549c"/>
                    </svg>
                    ЄДНІСТЬ <div class="place"><?= !empty($thisPage)?'<span>/</span>'.$thisPage:''?></div>  </div></a>
        </div>
    </div>

    <div class="side-menu panel">
        <a href="/">
            <div class="header">
                <div class="logo-title">
                    <svg class="logo-ico" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                        <path d="M0,0V30H30V0ZM15.484,25.033a9.709,9.709,0,0,1-9.829-9.687,9.864,9.864,0,0,1,9.829-9.872A10.041,10.041,0,0,1,25.091,13.2H20.262a5.324,5.324,0,0,0-4.778-3.187A5.314,5.314,0,0,0,10.671,13.2H15.6v3.6H10.666a4.606,4.606,0,0,0,4.818,3.288c2.214,0,4.092-.888,4.846-3.288h4.8A9.815,9.815,0,0,1,15.484,25.033Z"
                              fill="#45549c"/>
                    </svg>
                    ЄДНІСТЬ  </div>

            </div>
        </a>
        <p class="panel__header">Важливо!</p>
        <div class="panel__line"></div>

        <div class="slider">
            <?php include "views/block/exclusive.view.mobil.php";?>

        </div>
        <p class="panel__header panel__header-popular">Популярне</p>
        <div class="panel__line"></div>

        <div class="slider">

             <?php include "views/block/popular.view.mobil.php";?>

        </div>

        <p class="panel__header panel__header-popular">топ теми</p>
        <div class="panel__line"></div>

        <?php include 'views/sprite/tag.view.mobile.php'?>


        <div class="info">
            <a href="#"><span class="info__text">Про нас</span>
                <svg class="info__img" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                    <path d="M9,15h2V9H9ZM10,0A10,10,0,1,0,20,10,10.033,10.033,0,0,0,10,0Zm0,18a8,8,0,1,1,8-8,8.019,8.019,0,0,1-8,8ZM9,7h2V5H9ZM9,7"
                          fill="#fff"/>
                </svg>
        </div>

        <p class="panel__header">категорії</p>
        <div class="panel__line"></div>
        <div class="category">
            <ul>
                <a title='Головні, важливі новини' href="/important"><li <?=$page=='important'?" class='active'":'';?>>
                   Важливо!
                </li></a>
                <a title='Ексклюзив!' href="/exclusive"> <li <?=$page=='exclusive'?" class='active'":'';?>>
                    Ексклюзив!
                </li></a>
                <a title='Ексклюзив!' href="/interview">  <li <?=$page=='exclusive'?" class='active'":'';?>>
                    IНТЕРВ’Ю
                </li></a>
                <a title='Ексклюзив!' href="/analytics"> <li <?=$page=='exclusive'?" class='active'":'';?>>
                   АНАЛIТИКА
                </li></a>
                <a title='Резонанс' href="/category/resonans"><li <?=($id=='resonans')?" class='active'":'';?>>
                    Резонанс
                </li></a>
                <a title='Публікації' href="/public"><li <?=$page=='public'?" class='active'":'';?>>
                    Публікації
                </li></a>
                <a title='Популярне' href="/popular"> <li <?=$page=='popular'?" class='active'":'';?>>
                    Популярне
                </li></a>
                <a title='Інфографіка' href="/category/infografika"><li <?=($id=='infografika')?" class='active'":'';?>>
                    Інфографіка
                </li></a>
                <a title='Досье, біографії' href="/dossier">   <li <?=$page=='dossier'?" class='active'":'';?>>
                   Досьє
                </li></a>
            </ul>
            <div class="clearfix"></div>
        </div>

        <!--<div class="settings">
            <a href="#"><span class="settings__text">Налаштування</span>
                <img src="img/settings.png" width="20" height="20" class="settings__img"></a>
        </div>-->
    </div>

    <div class="side-menu-close-cover"></div>

</div>



<div class="body-mobile">

   <!-- <div class="container-news" style="display:none;">


        <a href="#"><div class="news">
                <img src="img/img1.png" class="news__img">
                <h2 class="news__type">Події<span class="news__time">Сьогодні о 16:50</span></h2>		<div class="mini">
                    <p class="mini__title">Трамп ответил назвавшему Путина убийцей ведущему</p>
                    <p class="mini__text">Президент США Дональд Трамп проигнорировал утверждение журналиста о том</p>
                </div>
            </div>
        </a>

        <a href="#"><div class="news">
                <img src="img/img1.png" class="news__img">
                <h2 class="news__type">Події<span class="news__time">Сьогодні о 16:50</span></h2>		<div class="mini">
                    <p class="mini__title">Трамп ответил назвавшему Путина убийцей ведущему</p>
                    <p class="mini__text">Президент США Дональд Трамп проигнорировал утверждение журналиста о том</p>
                </div>
            </div>
        </a>


    </div>-->
    <div class="container-news">
<? // контентные страницы типа "/views/page.$page.view.php" !название переменной $view_page не менять
/*echo $page;
echo $view_page;die;*/

include $view_page;

?>
    </div>
    <div class="footer">
        <ul>
            <li><a href="/about" class="about">Про нас</a></li>
            <li><a href="/regulations" class="use-material">правила використання матеріалів</a></li>
            <li><a href="/job" class="jobs">вакансії</a></li>
            <li><a href="/contacts" class="contacts">контакти</a></li>
            <li><a href="/about" class="write-us">написати нам</a></li>
        </ul>
        <p class="text">
            Копіювання, публікації та інше поширення інформації, розміщеної без письмового дозволу, забороняється. Допускається цитування матеріалів без отримання попередньої згоди за умови розміщення у тексті обов'язкового посилання на цей ресурс. Для інтернет-видань є обов'язковим розміщення прямого, відкритого для пошукових систем гіперпосилання на цитовану статтю не нижче другого абзацу у тексті.
            <br>Редакція може не поділяти думку авторів і не несе відповідальність за достовірність інформації.
        </p>
        <div class="soc">
            <ul>
                <li>
                    <a href="https://www.facebook.com/ednist.info/">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12.976 24">
                            <defs>
                                <style>
                                    .cls-1 {
                                        fill: #fff;
                                        }
                                </style>
                            </defs>
                            <path id="Path_17" data-name="Path 17" class="cls-1" d="M34.564,0,31.452,0C27.955,0,25.7,2.318,25.7,5.906V8.63H22.566a.489.489,0,0,0-.489.49v3.946a.489.489,0,0,0,.489.489H25.7v9.956a.489.489,0,0,0,.489.489h4.083a.489.489,0,0,0,.489-.489V13.554h3.659a.489.489,0,0,0,.489-.489V9.119a.49.49,0,0,0-.49-.49h-3.66V6.321c0-1.11.264-1.673,1.71-1.673h2.1a.489.489,0,0,0,.489-.489V.494A.49.49,0,0,0,34.564,0Z" transform="translate(-22.077)"/>
                        </svg>
                    </a>
                </li>       
                <li>
                    <a href="https://www.youtube.com/channel/UCcgAcE6mWhLMsOz6jtny0tw">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 24">
                            <defs>
                                <style>
                                    .cls-1 {
                                        fill: #fff;
                                    }
                                </style>
                            </defs>
                        <g id="flaticon1533751215-svg" transform="translate(-7.5)">
                            <path id="YouTube" class="cls-1" d="M24.417,17.555H23.1l.006-.765a.621.621,0,0,1,.62-.618h.084a.622.622,0,0,1,.622.618Zm-4.94-1.639a.563.563,0,0,0-.607.5v3.721a.62.62,0,0,0,1.217,0V16.415A.564.564,0,0,0,19.477,15.916ZM27.5,13.834v7.078A3.193,3.193,0,0,1,24.228,24H10.772A3.193,3.193,0,0,1,7.5,20.913V13.834a3.193,3.193,0,0,1,3.272-3.087H24.228A3.193,3.193,0,0,1,27.5,13.834ZM11.67,21.681V14.225h1.668v-1.1L8.89,13.114V14.2l1.388,0v7.477Zm5-6.346H15.279v3.982a7.057,7.057,0,0,1,0,.966c-.113.309-.621.637-.819.033a7.924,7.924,0,0,1,0-.971l-.006-4.009H13.065l0,3.946c0,.6-.014,1.056,0,1.261.034.362.022.784.358,1.025a1.68,1.68,0,0,0,2.125-.711l0,.822H16.67V15.336ZM21.119,19.9l0-3.314c0-1.263-.946-2.019-2.228-1l.006-2.464-1.389,0-.007,8.5,1.142-.017.1-.53C20.2,22.419,21.121,21.5,21.119,19.9Zm4.352-.439-1.043.006c0,.041,0,.089,0,.141v.582a.569.569,0,0,1-.57.565h-.2a.569.569,0,0,1-.57-.565v-1.53h2.388v-.9a14.179,14.179,0,0,0-.071-1.689c-.171-1.188-1.838-1.376-2.68-.768a1.57,1.57,0,0,0-.583.785,4.421,4.421,0,0,0-.177,1.4v1.976C21.957,22.746,25.947,22.281,25.471,19.456ZM20.122,8.729a.947.947,0,0,0,.334.422.969.969,0,0,0,.569.158.885.885,0,0,0,.533-.166,1.278,1.278,0,0,0,.39-.5l-.026.546h1.55V2.6h-1.22V7.728a.508.508,0,0,1-1.016,0V2.6H19.962V7.044c0,.566.01.944.027,1.135A1.911,1.911,0,0,0,20.122,8.729Zm-4.7-3.723a5.486,5.486,0,0,1,.158-1.483A1.587,1.587,0,0,1,17.2,2.342a1.923,1.923,0,0,1,.922.208,1.537,1.537,0,0,1,.595.541,2.134,2.134,0,0,1,.287.686,5.475,5.475,0,0,1,.078,1.079V6.524a9.026,9.026,0,0,1-.072,1.347,2.135,2.135,0,0,1-.306.8,1.449,1.449,0,0,1-.6.548,1.9,1.9,0,0,1-.841.177,2.336,2.336,0,0,1-.9-.151A1.23,1.23,0,0,1,15.8,8.79a2.029,2.029,0,0,1-.291-.739,7.124,7.124,0,0,1-.086-1.3V5.005Zm1.214,2.619a.619.619,0,1,0,1.232,0V4.115a.619.619,0,1,0-1.232,0ZM12.349,9.4h1.462l0-5.056L15.541.009h-1.6l-.919,3.217L12.091,0H10.508l1.838,4.342Z"/>
                        </g>
                    </svg>
                    </a>
                </li>
                <li>
                    <a href="https://twitter.com/Ednist_info">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29.546 24">
                            <defs>
                                <style>
                                    .cls-1 {
                                        fill: #fff;
                                    }
                                </style>
                            </defs>
                            <g id="flaticon1533751195-svg" transform="translate(-0.001 -57.441)">
                            <g id="Group_7" data-name="Group 7" transform="translate(0.001 57.441)">
                            <path id="Path_18" data-name="Path 18" class="cls-1" d="M29.547,60.281a12.1,12.1,0,0,1-3.48.955,6.086,6.086,0,0,0,2.664-3.351,12.219,12.219,0,0,1-3.851,1.472,6.066,6.066,0,0,0-10.328,5.526A17.208,17.208,0,0,1,2.06,58.549a6.068,6.068,0,0,0,1.876,8.09,6.062,6.062,0,0,1-2.745-.761v.076A6.067,6.067,0,0,0,6.051,71.9a6.139,6.139,0,0,1-1.6.212A5.818,5.818,0,0,1,3.313,72a6.064,6.064,0,0,0,5.661,4.21A12.161,12.161,0,0,1,1.447,78.8,12.881,12.881,0,0,1,0,78.714a17.133,17.133,0,0,0,9.291,2.727c11.15,0,17.244-9.235,17.244-17.244l-.02-.785A12.1,12.1,0,0,0,29.547,60.281Z" transform="translate(-0.001 -57.441)"/>
                            </g>
                            </g>
                        </svg>
                    </a>
                </li>
                <li>
                    <a href="/rss">
                        <svg id="flaticon1533751252-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <defs>
                            <style>
                            .cls-1 {
                                fill: #fff;
                            }
                            </style>
                            </defs>
                            <g id="Group_10" data-name="Group 10">
                            <g id="Group_9" data-name="Group 9">
                            <g id="Group_8" data-name="Group 8">
                            <path id="Path_19" data-name="Path 19" class="cls-1" d="M.182,0V4.6A19.411,19.411,0,0,1,19.559,24h4.614A24.026,24.026,0,0,0,.182,0Z" transform="translate(-0.173)"/>
                            <path id="Path_20" data-name="Path 20" class="cls-1" d="M.061,165.221v4.6a11.142,11.142,0,0,1,7.932,3.294,11.175,11.175,0,0,1,3.288,7.953H15.9A15.863,15.863,0,0,0,.061,165.221Z" transform="translate(-0.058 -157.069)"/>
                            <path id="Path_21" data-name="Path 21" class="cls-1" d="M3.2,356.647a3.186,3.186,0,1,0,3.2,3.187A3.2,3.2,0,0,0,3.2,356.647Z" transform="translate(0 -339.049)"/>
                            </g>
                            </g>
                            </g>
                        </svg>
                    </a>
                </li>
                <li>
                    <a href="/about">
                        <svg id="flaticon1533751465-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <defs>
                                <style>
                                .cls-1 {
                                    fill: #fff;
                                }
                                </style>
                            </defs>
                            <path id="Path_22" data-name="Path 22" class="cls-1" d="M12,0A12,12,0,1,0,24,12,12.014,12.014,0,0,0,12,0Zm0,21.818A9.818,9.818,0,1,1,21.818,12,9.829,9.829,0,0,1,12,21.818Z"/>
                            <path id="Path_23" data-name="Path 23" class="cls-1" d="M146.456,70a1.455,1.455,0,1,0,1.454,1.455A1.456,1.456,0,0,0,146.456,70Z" transform="translate(-134.456 -64.909)"/>
                            <path id="Path_24" data-name="Path 24" class="cls-1" d="M151.091,140A1.091,1.091,0,0,0,150,141.091v6.545a1.091,1.091,0,0,0,2.182,0v-6.545A1.091,1.091,0,0,0,151.091,140Z" transform="translate(-139.091 -129.818)"/>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
        <p class="copy">&copy; <span class="year">
                <script>var mdate = new Date(); document.write(mdate.getFullYear());</script>
            </span> Усі права застережені.</p>
    </div>

</div>


<!-- scripts -->

<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="<?= $ini['url.assets'] ?>js/jquery.tappy.js"></script>
<script type="text/javascript" src="<?= $ini['url.assets'] ?>js/slick.min.js"></script>
<script src="<?= $ini['url.assets'] ?>js/common.js"></script>


<?php if($page == ''):?>
<script>
    $(document).ready(function() {
         count = 15;
        var loading = false;
        $(window).scroll(function(){
            //loading = false;
            if((($(window).scrollTop()+$(window).height())+250)>=$(document).height()){
                if(loading == false){
                   // loading = true;
                    var limit =16;
                    count += limit;
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: '/controllers/<?= $page ==''?'main':$page?>.controller.php',
                        data: {
                            action: 'get_more',
                            limit_from: count,
                            limit: limit
                        }
                    }).done(function (obj) {
                       // loading = true;
                        if (obj.html !== '') {
                            $(".container-news").append(obj.html);
                        }
                    }).fail(function (obj) {
                        console.log('false load');
                    });
                }
            }
        });
    });



</script>
<?php endif;?>
</body>
</html>