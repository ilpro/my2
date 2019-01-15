<div id="vacancy" class="content">
    <div class="head">
        RSS
    </div>
    <div class="rss-container">
        <ul>
            <li><a href="http://<?=$page_data['serv'];?>/rss/important">Головні новини</a></li>
            <li><a href="http://<?=$page_data['serv'];?>/rss/news">Новини</a></li>
            <li><a href="http://<?=$page_data['serv'];?>/rss/public">Публікації</a></li>
            <li><a href="http://<?=$page_data['serv'];?>/rss/dossier">Досьє</a></li>
            <li><a href="http://<?=$page_data['serv'];?>/rss/ukrNet">Укр.Нет</a></li>
            <li><a href="http://<?=$page_data['serv'];?>/rss/partner">Яндекс</a></li>
            <!--<li><a href="http://<?=$page_data['serv'];?>/rss/tags">Теги</a></li>

            <li><a href="http://<?=$page_data['serv'];?>/rss/categories">Категорії</a></li>-->

            <? foreach($page_data['categories'] as $category) { ?>
                <li><a href="http://<?=$page_data['serv'];?>/rss/category_<?=$category['categoryTranslit'];?>"><?=$category['categoryName'];?></a></li>
            <? } ?>
        </ul>
    </div>
</div>