<?php
$html = '';
foreach($newsList as $item){
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

    $html .= '<a href="/'.$newsType.'/'.$item["newsId"].'" class="small-list-item">';
    $html .= '<div class="time">'.$item["newsTimePublic"].'</div>
    <div class="title">'.$newsHeader.'</div>
</a>';
}
