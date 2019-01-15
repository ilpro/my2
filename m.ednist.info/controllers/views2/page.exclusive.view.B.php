<div id="exclusive" class="content">
    <div class="head">
        Ексклюзив
    </div>
    <div class="news-list clearfix" id="append" data-controller="exclusive">
        <?include "block/lineSidebar.view.php";?>
        <?
        if (!empty($page_data['news']))
            foreach ($page_data['news'] as $item)
                include "block/exclusiveBlock.view.php";
        ?>
    </div>
    <div class="more-news">
        <button class="show-more">
            Ще новини
        </button>
    </div>
</div>