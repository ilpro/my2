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
    <script src="<?= $ini['url.assets'] ?>js/jquery-1.10.1.min.js"></script>


    <script>
        var src="https://counter.mediastealer.com";
        document.write('<sc' + 'ript async src="' + src + '">' + '</sc' + 'ript>');
    </script>

     <title>title</title>
</head>
<body>

<div class="header-mobile ">
    <div class="header-logo">

    </div>
     <?php
     $arrayPages = ['important'=>'Важливо',
         'exclusive'=>'Ексклюзив',
         'interview'=>'IНТЕРВ\’Ю',
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
            <a href="/">
                <div class="page-name">
                    <svg class="logo-ico" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                        <path d="M0,0V30H30V0ZM15.484,25.033a9.709,9.709,0,0,1-9.829-9.687,9.864,9.864,0,0,1,9.829-9.872A10.041,10.041,0,0,1,25.091,13.2H20.262a5.324,5.324,0,0,0-4.778-3.187A5.314,5.314,0,0,0,10.671,13.2H15.6v3.6H10.666a4.606,4.606,0,0,0,4.818,3.288c2.214,0,4.092-.888,4.846-3.288h4.8A9.815,9.815,0,0,1,15.484,25.033Z"
                              fill="#45549c"/>
                    </svg>
                    ЄДНІСТЬ <div class="place"><?= !empty($thisPage)?  '<span>/</span>'   .$thisPage:''?></div> </div>
            </a>
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
                    ЄДНІСТЬ </div>

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

<!--


    <div class="container-news" style="display:none;">


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


-->
    <div class="container-news">
<? // контентные страницы типа "/views/page.$page.view.php" !название переменной $view_page не менять
/*echo $page;
echo $view_page;die;*/
//print_r($sliders['important']);
include $view_page;
?>
</div>
    <div class="footer">
        <a href="/about" class="about">Про нас</a>
        <a href="#" class="use-material">правила використання матеріалів</a>
        <a href="#" class="jobs">вакансії</a>
        <a href="#" class="contacts">контакти</a>
        <a href="#" class="write-us">написати нам</a>
        <div class="soc">
            <a href="https://www.facebook.com/pages/%D0%84%D0%B4%D0%BD%D1%96%D1%81%D1%82%D1%8C/1640600432838075">
                <img class="fb-ico" src="/assets/img/fb-icon.png" height="30" width="30">
            </a>
        </div>
        <p class="text">
            Копіювання, публікації та інше поширення інформації, розміщеної без письмового дозволу, забороняється. Допускається цитування матеріалів без отримання попередньої згоди за умови розміщення у тексті обов'язкового посилання на цей ресурс. Для інтернет-видань є обов'язковим розміщення прямого, відкритого для пошукових систем гіперпосилання на цитовану статтю не нижче другого абзацу у тексті.
            <br>Редакція може не поділяти думку авторів і не несе відповідальність за достовірність інформації.
        </p>
        <p class="copy">&copy; <span class="year">
                <script>var mdate = new Date(); document.write(mdate.getFullYear());</script>
            </span> Усі права застережені.</p>
    </div>

</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="<?= $ini['url.assets'] ?>js/jquery.tappy.js"></script>
<script type="text/javascript" src="<?= $ini['url.assets'] ?>js/slick.min.js"></script>
<script src="<?= $ini['url.assets'] ?>js/common.js"></script>
<!-- scripts -->

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
                            url: '/controllers/<?= $page ==''?'tag':$page?>.controller.php',
                            data: {
                                <?php if($page =='exclusive'){?>
                                action: 'get_more',
                                limit_from: count,
                                limit: limit
                            <?php }else{?>
                                action: 'get_more',
                                limit_from: count,
                                 <?= !empty($page_data['tag']['tagId'])?'category: '.$page_data['tag']['tagId'].',':''?>
                                limit: limit
                            <?php }?>
                            }
                        }).done(function (obj) {
                            // loading = true;

                            if(obj == null){
                                return;
                            }
                            if ( obj.html !== '') {

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

</body>
</html>