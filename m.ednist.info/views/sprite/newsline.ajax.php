<?php
$html='';
if($newsList)
    foreach($newsList as $i=>$item) {
        if ($item['newsType'] == 1) {
            $newsType = 'article';
        } else {
            $newsType = 'news';
        }
        $newsHeader = $item['newsHeader'];
        if (strlen($newsHeader) > 130) {
            $offset = (130 - 3) - strlen($newsHeader);
            $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
        }
        $html .= '<a href="/' . $newsType . '/' . $item["newsId"] . '" class="list-item';
        if ($item['newsMain'] > 0) $html .= " important";
        $html .= '">';
        $html .= '<div class="time pull-left">' . $item["newsTimePublic"] . '</div>';
        $html .= '<div class="icons">';
        if($item['newsExclusive'] == 1) $html .= '<i class="ico ico-exclusive" title="Ексклюзив"></i>';
        if($item['newsIsGallery'] == 1) $html .= '<i class="ico ico-photo" title="Фото"></i>';
        if($item['newsIsVideo'] == 1) $html .= '<i class="ico ico-video" title="Відео"></i>';
        $html .= '</div>';
        $html .= '<div class="clearfix"></div>';
        $html .= '<div class="title">';
        if ($item['newsMain'] > 0) $html .= '<i class="ico ico-important"></i>';
        $html .= $newsHeader . '</div>';
        $html .= '</a>';
    }
?>