<div id="exclusive" class="content">
    <div class="head">
        <?=$page_data['category']['categoryName'];?>
    </div>
    <div class="news-list clearfix" data-controller="category" data-category="<?=$page_data['category']['categoryId'];?>" id="append">
        <?include "block/lineSidebar.view.php";?>
        <?
        if (!empty($page_data['news_bottom']))
            foreach ($page_data['news_bottom'] as $item)
                include "block/categoryBlock.view.php";
        ?>
    </div>
    <div class="more-news">
        <button class="show-more">
            Ще новини
        </button>
    </div>
</div>