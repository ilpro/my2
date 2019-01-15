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
$block .= '<a href="/'.$newsType.'/'.$item['newsId'] .'" class="block-list-item">';
$block .= '<img src="'.$ini["url.media"]."images/".$item["newsId"].'/main/240.jpg" alt="'.htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES).'" title="'.htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES).'" class="pull-left">';
$block .= '<div class="desc pull-left">
        <div class="time">' . $item['newsTimePublic'] . '</div>
        <div class="text">' . $newsHeader . '</div>
    </div>
    <div class="clearfix"></div>
</a>';