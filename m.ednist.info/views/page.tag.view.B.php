<div id="exclusive" class="content">
    <div class="head">
        #<?=$page_data['tag']['tagName'];?>
    </div>
    <div class="news-list" id="append" data-controller="tag" data-category="<?=$page_data['tag']['tagId'];?>">
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