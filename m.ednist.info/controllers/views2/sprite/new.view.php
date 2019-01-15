<div class="news-read" title='<?=$item['newsHeader'];?>'>
    <div class="shadow">
        <a href="/news/<?=$item['newsId'];?>" >
            <img title='<?=$item['newsHeader'];?>' alt='<?=$item['newsHeader'];?>' src="<?=$ini['url.media'].'images/'.$item['newsId'].'/main/400.jpg'?>" style="width: 320px;">
            <div class="article-info">
                <div class="small-date"><?=$item['newsTimePublic'];?></div>
                <div  class="news-title"><?=$item['newsHeader'];?></div>
            </div>
        </a>
    </div>
</div>
