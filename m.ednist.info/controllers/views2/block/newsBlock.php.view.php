<?php

$html = '';
if (!empty($newsList))
     foreach ($newsList as $item) {
          $newsHeader = $item['newsHeader'];
          if (strlen($newsHeader) > 250) {
               $offset = (250 - 3) - strlen($newsHeader);
               $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
          }


          $html .= '<div class="container-news">';
          $href = $item['newsType'] == 0 ? "news" : "article";
          $html .= '<a href="/' . $href . '/' . $item['newsId'] . '"><div class="news">';
          if (!empty($item['images'])) {
               $img = !empty($item['images'][0]['main240'])? $item['images'][0]['main240']:'/assets/img/about-logo.jpg';
          }
          $html .= '<img src="' . $img . '" class="news__img">';
          $new = $item['categoryName'];
          $html .= '<span class="news__type">' . $new . '</span><span class="news__time">' . $item['newsTimePublic'] . '</span>';
          $html .= '<div class="mini">';
          $html .= '<p class="mini__title">' . $newsHeader . '</p>';
          $html .= '<p class="mini__text">' . $item['newsSubheader'] . '</p>';
          $html .= '</div>';
          $html .= '</div>';
          $html .= '</a>';

          $html .= '</div>';
     }

?>