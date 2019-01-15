<div id="exclusive" class="content">
    <div class="head">
        Публікації
    </div>
    <div class="news-list clearfix" id="append" data-controller="public">
        <?include "block/lineSidebar.view.php";?>
        <?
        if (!empty($page_data['articles']))
            foreach ($page_data['articles'] as $item)
                include "block/exclusiveBlock.view.php";
        ?>
    </div>
    <div class="more-news">
        <button class="show-more">
            Ще новини
        </button>
    </div>
</div>