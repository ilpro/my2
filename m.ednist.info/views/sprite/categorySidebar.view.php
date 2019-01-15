<div class="category-sidebar pull-right">
    <div class="block-head">
        <a href="/category/resonans" title="Резонанс">
            РЕЗОНАНС
        </a>
    </div>
    <div class="news-list" id="append-category">
        <?
            foreach($page_data['resonans'] as $item){
                include 'views/sprite/categoryItem.view.php';
            }
        ?>
    </div>
</div>