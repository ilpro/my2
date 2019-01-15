<?include Config::getAdminRoot() . "/views/block/informer.view.php";?>
<? include "block/editnew.view.php"; ?>

<div class="wrapper box news" id="auth">
<div class="head">
<div class="clear"></div>

<?
include Config::getAdminRoot() . "/views/block/logo_item.view.php";
include Config::getAdminRoot() . "/views/block/menu_item.view.php";

?>

<div class="search ui-widget">
    <i class="search-icon"></i>
    <input type="text" name="country" id="tags" placeholder="Поиск">
</div>
<div class="resetSearch">
    <div class="reset">reset search</div>
</div>
<div class="filter">
    <div title='Фильтрация материалов по имени добавляющего редактора' class="people" data-dropdown="#dropdown-2">
        <p id="filterUsers">Все редакторы<i class="caret"></i></p>
    </div>
    <div title='Фильтрация материалов по типу материала' class="newsCategory" data-dropdown="#dropdown-3">
        <p id="filterMaterials">Все материалы <i class="caret"></i></p>
    </div>
    <div class="trash">
        <i></i>
    </div>
    <!---
    <div class="sort" onclick="toggle('a');">
        <i></i>
    </div>
    --->
    <div class="add" id="addNews">
        <i></i>
    </div>
    <div class="exit" id="logout">
        <i></i>
    </div>
</div>
<div id="dropdown-2" class="dropdown dropdown-tip dropdown-anchor-right dropdown-relative">
    <ul class="dropdown-menu">
        <li><a title='Сброс фильра' class="list_users" value="Все редакторы" data-mknews='{"userId":""}'>Все редакторы</a></li>
         <?
        foreach ($page_data['users'] as $user) { ?>
            <li><a class="list_users" value="<? echo $user['userName'];?>" data-mknews='{"userId":"<?= $user['userId'] ?>"}'><? echo $user['userName'];?></a></li>
       <? }?>
    </ul>
</div>

<div id="dropdown-3" class="dropdown dropdown-tip dropdown-news-right dropdown-relative">
    <ul class="dropdown-menu">
        <li><a  title='Сброс фильра' class="list_news" value="Все материалы" data-mknews='{"newsType":""}'>Все материалы</a></li>
         <?
        foreach ($ini['news.type'] as $k => $v) { ?>
            <li><a class="list_news" value="<? echo $v;?>" data-mknews='{"newsType":"<? echo $k;?>"}'><? echo $v;?></a></li>
        <?}?>
    </ul>
</div>

<div class="clear"></div>

<div id="a" class="dropdown-nav-new">

    <div class="check">
        <input type="checkbox" id="c2" name="myPublication"/>
        <label for="c2"><span></span></label>
    </div>

    <div class="all_news" data-dropdown="#dropdown-3"><i></i>

        <p>Все материалы </p><i class="caret_down"></i>
    </div>

    <div id="dropdown-3" class="dropdown dropdown-tip dropdown-anchor-right dropdown-relative">
        <ul class="dropdown-menu">
            <li>
                <a href="#">
                    <div class="img-down"><i></i></div>
                    <p rel="all">Все материалы </p>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="icon-holder">
                        <div class="icon-status red">
                            <span class="new-icon"></span>
                            <i class="checked"></i>
                        </div>
                    </div>
                    <div class="cat_name"><p rel="all">Новые </p></div>
                </a>

                <div class="clear"></div>
            </li>
            <li>
                <a href="#">
                    <div class="icon-holder">
                        <div class="icon-status grey">
                            <span class="status-icon"></span>
                            <i class="checked"></i>
                        </div>
                    </div>
                    <div class="cat_name"><p rel="tea">Важные </p></div>
                </a>

                <div class="clear"></div>
            </li>
            <li>
                <a href="#">
                    <div class="icon-holder">
                        <div class="icon-status blue">
                            <span class="checked-icon"></span>
                            <i class="checked"></i>
                        </div>
                    </div>
                    <div class="cat_name"><p rel="tea">Обработанные </p></div>
                </a>


                <div class="clear"></div>

            </li>


            <li>

                <a href="#">

                    <div class="icon-holder">

                        <div class="icon-status yellow">

                            <span class="clock-icon"></span>

                            <i class="checked"></i>

                        </div>

                    </div>

                    <div class="cat_name"><p rel="adv">Ожидают публикации </p></div>

                </a>


                <div class="clear"></div>

            </li>

            <li>

                <a href="#">

                    <div class="icon-holder">

                        <div class="icon-status green">

                            <span class="checkin-icon"></span>

                            <i class="checked"></i>

                        </div>

                    </div>

                    <div class="cat_name"><p rel="cla">Опубликованные </p></div>


                </a>

                <div class="clear"></div>

            </li>


        </ul>

    </div>

    <div class="myCheckbox">

        <select id="example2" name="exampl2e" multiple="multiple">

            <option value="1">efwe</option>

            <option value="2">O234</option>

            <option value="3">qwr</option>

            <option value="4">Opwer4</option>

            <option value="5">Option 5</option>

        </select>

    </div>


    <div class="myCheckbox">

        <select id="example" name="example" multiple="multiple">

            <option value="1">Все разделы</option>

            <option value="1">efwe</option>

            <option value="2">O234</option>

            <option value="3">qwr</option>

            <option value="4">Opwer4</option>

            <option value="5">Option 5</option>

            <option value="6">efwe</option>

            <option value="7">O234</option>

            <option value="8">qwr</option>

            <option value="9">Opwer4</option>

            <option value="10">Option 5</option>

        </select>

    </div>

    <!--<div class="cats"><p>5 разделов </p><i class="caret_down"></i></div>-->


    <div class="publications">


        <div class="check">

            <input type="checkbox" id="c1" name="myPublication"/>

            <label for="c1"><span></span>Мои публикации</label>

        </div>

    </div>

    <div class="close"><i onclick="toggle('a');" class="close"><span></span></i></div>


</div>

</div>

<div class="clear"></div>

<div class="content" id="all-flavors">

    <?

    if (is_array($page_data['newsList']))
        foreach ($page_data['newsList'] as $key2=>$item) {

            include Config::getAdminRoot() . '/views/news/newsStripe.view.php';
        }

    ?>

</div>

    <button class="show-mor" data-controller="news" data-itemsClass=".newsStripe" data-limit="10">
        БІЛЬШЕ НОВИН
        <img class="loadingField" src="<?=$ini['url.assets'];?>img/loadField.gif" />
    </button>

</div>



<?

include "window/tag.window.view.php";

include "window/widgetTag.window.view.php";

include "window/brand.window.view.php";

include "window/themes.window.view.php";

include "window/region.window.view.php";

include "window/sourse.window.view.php";

include "window/author.window.view.php";

include "window/image.window.view.php";

include "window/imageCrop.window.view.php";

?>