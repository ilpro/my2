<div id="popular-sidebar" class="pull-right popular">
    <a title="Популярне за добу" href="/popular" class="head">
        <i class="ico ico-popular"></i>
        Популярне за добу
    </a>
    <div class="news-list">
        <?
        $i = 0;
        if (!empty($page_data['popular_news']))
            foreach ($page_data['popular_news'] as $item)
                include Config::getRoot()."/views/sprite/popular.main.view.php";
        ?>
    </div>
</div>