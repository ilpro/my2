<?php

$html = '';
if (!empty($newsList))
     foreach ($newsList as $item) {
          $newsHeader = $item['newsHeader'];
          if (strlen($newsHeader) > 140) {
               $offset = (140 - 3) - strlen($newsHeader);
               $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
          }



          $href = $item['newsType'] == 0 ? "news" : "article";
          $html .= '<a href="/' . $href . '/' . $item['newsId'] . '"><div class="news">';
          if (!empty($item['images'])) {
               $img = !empty($item['images'][0]['gallery'])? $item['images'][0]['gallery']:'/assets/img/about-logo.jpg';
          }
          $html .= '<img src="' . $img . '" class="news__img">';
          $new =  $item['categoryName'];
          $html .= '<div class="mini">';
          $html .= '<span class="news__type">' . $new . '</span>';
          $html .= '<span class="news__time">' . $item['newsTimePublic'] . '</span>';
          $html .= '<p class="mini__title">' . $newsHeader . '</p>';
          $html .= '<p class="mini__text">' . $item['newsSubheader'] . '</p>';
          $html .= '</div>';
          $html .= '</div>';
          $html .= '</a>';

     }

?>
