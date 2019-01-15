<div id="new-year__evacuation" class="new-year__category">
    <article class="left-content pull-left">
        <header class="head">
            <a href="/newyear/<?=$page_data['category']['categoryTranslit']?>"><?=$page_data['category']['categoryName']?></a>
        </header>
        <?include Config::getRoot()."/views/newyear/newsItems.view.php";?>
    </article>

    <div class="right-content pull-right">
        <div class="pull-right last-media" id="ms-widget">
          <?include Config::getRoot()."/views/block/mediastealerWidget.view.php"; ?>
        </div>
        <?include Config::getRoot()."/views/banners/300x250.view.php";?>
    </div>
    <div class="clearfix"></div>
</div>