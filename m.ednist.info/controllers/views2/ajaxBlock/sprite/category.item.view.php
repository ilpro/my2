<?php
if($item['newsType']==1){
    $newsType='article';
}
else{
    $newsType='news';
}
$newsHeader = $item['newsHeader'];
if (strlen($newsHeader) > 130)
{
    $offset = (130 - 3) - strlen($newsHeader);
    $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
}
$banner .='
<a href="/'.$newsType.'/'.$item["newsId"].'" class="list-item">
    <div class="pull-left image">
        <img src="'.$ini['url.media'] . 'images/' . $item['newsId'] . '/main/60.jpg" alt="'.htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES).'" title="'.htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES).'">
    </div>
    <div class="pull-left image">
        <div class="time">' . $item["newsTimePublic"] . '</div>
        <div class="title">' . $newsHeader . '</div>
    </div>
</a>
';
