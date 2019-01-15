<div id="exclusive" class="content">
    <div class="head">
        Важливо
    </div>
    <div class="news-list clearfix" id="append" data-controller="important">
        <?include "block/lineSidebar.view.php";?>
        <?
        if (!empty($page_data['news_bottom']))
            foreach ($page_data['news_bottom'] as $item)
                include "block/exclusiveBlock.view.php";
        ?>
    </div>
    <div class="more-news">
        <button class="show-more">
            Ще новини
        </button>
    </div>
</div>