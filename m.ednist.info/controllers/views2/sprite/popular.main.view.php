<?php
if($item['newsType']==1){
    $newsType='article';
}
else{
    $newsType='news';
}
$newsHeader = $item['newsHeader'];
if (strlen($newsHeader) > 90)
{
    $offset = (90 - 3) - strlen($newsHeader);
    $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
}
?>
<a href="<?='/'.$newsType.'/'.$item['newsId'];?>" class="list-item">
    <img alt="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" title="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" src="<?=$ini['url.media']."images/".$item['newsId']."/main/60.jpg";?>" class="pull-left">
    <div class="description pull-left">
        <div class="time">
            <?=$item['newsTimePublic'];?>
        </div>
        <div class="text">
            <?=$newsHeader;?>
        </div>
    </div>
    <div class="clearfix"></div>
</a>