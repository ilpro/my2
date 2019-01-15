<?php
if($item['newsType']==1){
    $newsType='article';
}
else{
    $newsType='news';
}
$newsHeader = $item['newsHeader'];
if (strlen($newsHeader) > 95)
{
    $offset = (95 - 3) - strlen($newsHeader);
    $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
}
$banner .='
<a href="/'.$newsType.'/'.$item["newsId"].'" class="small-list-item">
    <div class="pull-left img">
        <img src="'.$ini['url.media'] . 'images/' . $item['newsId'] . '/main/60.jpg" alt="'.htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES).'" title="'.htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES).'">
    </div>
    <div class="pull-left desc">
        <div class="time">' . $item["newsTimePublic"] . '</div>
        <div class="title">' . $newsHeader . '</div>
    </div>
    <div class="clearfix"></div>
</a>
';
