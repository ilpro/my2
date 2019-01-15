<?php
if($item['newsType']==1){
    $newsType='article';
}
else{
    $newsType='news';
}
$newsHeader = $item['newsHeader'];
$newsSubHeader = $item['newsSubheader'];
if (strlen($newsHeader) > 130)
{
    $offset = (130 - 3) - strlen($newsHeader);
    $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
}
if (strlen($newsSubHeader) > 250)
{
    $offset = (250 - 3) - strlen($newsSubHeader);
    $newsSubHeader = substr($newsSubHeader, 0, strrpos($newsSubHeader, ' ', $offset)) . '...';
}
?>
<?if (($i % 2) == 0&&$i!=0) {?>
    <div class="clearfix"></div>
    </li>
    <li class="slide">
<?}?>

<a href="<?='/'.$newsType.'/'.$item["newsId"]?>" class="item pull-left">
    <img src="<?=$ini["url.media"].'images/'.$item["newsId"].'/main/240.jpg'?>" class="pull-left" alt="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" title="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>">
    <div class="description pull-left">
        <div class="time"><?=$item["newsTimePublic"];?></div>
        <div class="news-header"><?=$newsHeader;?></div>
        <div class="news-sub-header"><?=$newsSubHeader;?></div>
    </div>
</a>