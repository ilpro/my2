<?

include "category/editcategory.view.php";

?>

    <div class="wrapper box" id="auth">

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

            <div class="filter floatRight">

                <div class="trash"><i></i></div>

                <div class="add" id="addCategory"><i></i></div>

                <div class="exit" id="logout"><i></i></div>

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
            foreach ($page_data['categories'] as $item) {

                require Config::getAdminRoot() . '/views/category/categoryStripe.view.php';

            }
            ?>

        </div>

    </div>

