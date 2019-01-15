<?php

$type = ($item['newsType']==0) ? 'news' : 'article';

$html .= '<div class="news-list-item">
    <a href="/'.$type.'/'.$item['newsId'].'">';

        if( isset($item['images']['main']['main240']) ) {
            $html .= '<img src="'.$item['images']['main']['main240'].'" height="120px">';
        }

$html .= '<div class="news-list-item-content">
            <div class="small-date">'.$item['newsTimePublic'].'</div>
            <div class="list-title">'.$item['newsHeader'].'</div>
            <div class="news-list-item-desc">
                '.$item['newsSubheader'].'
            </div>
        </div>

    </a>
    <div class="absolute">
        <div class="news-them">
            <a href="/category/'.$item['categoryTranslit'].'">'.$item['categoryName'].'</a>
        </div>
    </div>

</div>';
