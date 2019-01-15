<div id="newsline" class="content">
    <div class="head">
        Стрічка новин
    </div>
    <div class="news-list clearfix" id="append" data-controller="newsline">
        <div class="pull-right line-sidebar">
            <div class="bottom-space">
                <?include Config::getRoot()."/views/banners/300x250.view.php";?>
            </div>
            <div class="pull-right last-media">
              <?include Config::getRoot()."/views/block/mediastealerWidget.view.php"; ?>
            </div>

            <?include Config::getRoot()."/views/block/twitter.view.php";?>
        </div>

        <? if( !empty($page_data['news']) ) { ?>
            <? foreach($page_data['news'] as $item) { ?>

                <? include "sprite/newsLineBlock.php"; ?>

            <? } ?>
        <? } ?>
    </div>
    <div class="more-news">
        <button class="show-more">
            Ще новини
        </button>
    </div>
</div>