<div id="exclusive" class="content">
    <div class="head">
        Результати пошуку «<?=$_GET['find']?>»:
    </div>
    <div class="news-list clearfix" id="append" data-controller="search">
        <?include "block/lineSidebar.view.php";?>
        <?
        if (!empty($page_data['news_bottom']))
            foreach ($page_data['news_bottom'] as $item){
                $item['newsTimePublic'] =  NewsHelper::getNewDate($item['newsTimePublic']);
                include "block/exclusiveBlock.view.php";
            }
        ?>
        <? if( empty($page_data['news_bottom']) && empty($page_data['brands_bottom']) ) { ?>
            <? echo "<div class='error-search'>По запиту '".$_GET['find']."' нічого не знайдено</div>"; ?>
        <? } ?>
    </div>
    <div class="more-news">
        <button class="show-more">
            Ще новини
        </button>
    </div>
</div>
