<?php
if($item['newsType']==1){
    $newsType='article';
}
else{
    $newsType='news';
}
$newsHeader = $item['newsHeader'];
if (strlen($newsHeader) > 150)
{
    $offset = (150 - 3) - strlen($newsHeader);
    $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
}
$newsSubHeader = $item['newsSubheader'];
if (strlen($newsSubHeader) > 200)
{
    $offset = (200 - 3) - strlen($newsSubHeader);
    $newsSubHeader = substr($newsSubHeader, 0, strrpos($newsSubHeader, ' ', $offset)) . '...';
}
?>
<a href="<?='/'.$newsType.'/'.$item['newsId'];?>" class="pull-left big-item">
    <img src='<?=$ini["url.media"]."images/".$item["newsId"]."/main/240.jpg";?>' alt="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" title="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" class="pull-left">
    <div class="description pull-left">
        <div class="time">
            <?=$item['newsTimePublic'];?>
        </div>
        <div class="news-header">
            <?=$newsHeader;?>
        </div>
        <div class="news-sub-header">
            <?=$newsSubHeader;?>
        </div>
    </div>
</a>