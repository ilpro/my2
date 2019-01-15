<?php
if($item['newsType']==1){
    $newsType='article';
}
else{
    $newsType='news';
}
?>
<a href="<?='/'.$newsType.'/'.$item['newsId'];?>" class="block-list-item">
    <img src="<?=$ini["url.media"]."images/".$item["newsId"]."/main/60.jpg";?>" alt="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" title="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" class="pull-left">
    <div class="desc pull-left">
        <div class="time">
            <?=$item['newsTimePublic'];?>
        </div>
        <div class="text">
            <?=$item['newsHeader'];?>
        </div>
    </div>
    <div class="clearfix"></div>
</a>