<?php

$html = '';

foreach( $items as $item ) {

    $html .= '<a class="dossier" href="/bloger/'.$item['authorId'].'">
                <img title="'.$item['authorName'].'" alt="'.$item['authorName'].'" src="'.$ini['url.media'].'author/'.$item['authorId'].'/big.jpg" style="width: 240px; height: 240px">
                <div class="name-title">'.$item['authorName'].'</div>
                <div class="rectangle-u"></div>
            </a>';

}
