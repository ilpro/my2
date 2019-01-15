<div id="newsline" class="content">
    <div class="head">
        Новини за сьогодні
    </div>
    <div class="news-list clearfix" id="append" data-controller="todaynews">
        <div class="pull-right line-sidebar">
            <?include Config::getRoot()."/views/block/mediastealerWidget.view.php"; ?>
            <div class="bottom-space">
                <?include Config::getRoot()."/views/banners/300x250.view.php";?>
            </div>
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