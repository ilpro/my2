<?php
$html='';
if($newsList)
   foreach($newsList as $i=>$item){
        if($item['newsType']==1){
            $newsType='article';
        }
        else{
            $newsType='news';
        }
        $newsHeader = $item['newsHeader'];
        $newsSubHeader = $item['newsSubheader'];
        if (strlen($newsHeader) > 80)
        {
            $offset = (80 - 3) - strlen($newsHeader);
            $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
        }
        if (strlen($newsSubHeader) > 100)
        {
            $offset = (100 - 3) - strlen($newsSubHeader);
            $newsSubHeader = substr($newsSubHeader, 0, strrpos($newsSubHeader, ' ', $offset)) . '...';
        }
       $html .= '<div class="list-item">';
       $html .= '<a href="/'.$newsType.'/'.$item["newsId"].'" class="image pull-left">';
       $html .= '<img src="'.$ini['url.media'] . 'images/' . $item['newsId'] . '/main/240.jpg" alt="'.htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES).'" title="'.htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES).'">';
       $html .= '</a>';
       $html .= '<div class="description pull-left">';
       $html .= '<div class="time pull-left">' . $item['newsTimePublic'] . '</div>';
       $html .= '<div class="icons pull-left">';
       if($item['newsExclusive'] == 1) $html .= '<i class="ico ico-exclusive" title="Ексклюзив"></i>';
       if($item['newsIsGallery'] == 1) $html .= '<i class="ico ico-photo" title="Фото"></i>';
       if($item['newsIsVideo'] == 1) $html .= '<i class="ico ico-video" title="Відео"></i>';
       $html .= '</div>';
       $html .= '<a title="До категорії «'.$item['categoryName'].'»" href="/category/'.$item['categoryTranslit'].'" class="category pull-left">' . $item['categoryName'] . '</a>';
       $html .= '<div class="clearfix"></div>';
       $html .= '<a href="/'.$newsType.'/'.$item["newsId"] .'" class="link">';
       $html .= '<div class="news-title">' . $newsHeader . '</div>';
       $html .= '<div class="news-subtitle">' . $newsSubHeader . '</div>';
       $html .= '</a>';
       $html .= '</div>';
       $html .= '<div class="clearfix"></div>';
       $html .= '</div>';
}
?>