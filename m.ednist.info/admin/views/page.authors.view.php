<?
include "author/editAuthor.view.php";

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

                <div class="add" id="addAuthor"><i></i></div>

                <div class="exit" id="logout"><i></i></div>

            </div>

            <div class="clear"></div>

        </div>

        <div class="clear"></div>

        <div class="content" id="all-flavors">

            <?

            foreach ($page_data['authors'] as $item) {

                require Config::getAdminRoot() . '/views/author/authorStripe.view.php';

            }

            ?>

        </div>

    </div>

<?

include "window/image.window.view.php";

include "window/imageCrop.window.view.php";

?>