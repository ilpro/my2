<div class="newsline-sidebar pull-right">
    <div class="head">
        <a href="/newsline" title="Стрічка новин">
            <i class="ico ico-newsline"></i>
            Стрічка новин
        </a>
    </div>
    <div class="news-list newsline-items-right" id="append-right">
        <?
        foreach($page_data['last_news'] as $item){
            include 'views/sprite/newslineItem.view.php';
        }
        ?>
    </div>
</div>