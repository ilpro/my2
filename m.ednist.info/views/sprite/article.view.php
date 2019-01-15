<?
$i++;
if($i%3==1 and $i!=1):
?>
</li><li class="slide">
<? endif;?>

<div class="news-read" title='<?=$item['newsHeader'];?>'>
    <div class="shadow">
        <a href="/article/<?=$item['newsId'];?>" >
            <img title='<?=$item['newsHeader'];?>' alt='<?=$item['newsHeader'];?>' src="<?=$ini['url.media']."images/".$item['newsId']."/main/240.jpg";?>" style="width: 320px;">
            <div class="article-info">
                <div class="small-date"><?=$item['newsTimePublic'];?></div>
                <div  class="news-title"><?=$item['newsHeader'];?></div>
                <div  class="news-desc"><?=$item['newsSubheader'];?></div>
            </div>
        </a>
    </div>
</div>
