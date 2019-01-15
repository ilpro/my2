<?php

$html = '';

foreach( $items as $item ) {

    $html .= '<a class="dossier" href="/dossier/'.$item['brandId'].'">
                <img title="'.$item['brandName'].'" alt="'.$item['brandName'].'" src="'.$ini['url.media'].'brand/'.$item['brandId'].'/main/240.jpg" style="width: 240px; height: 240px">
                <div class="name-title">'.$item['brandName'].'</div>
                <div class="rectangle-u"></div>
            </a>';

}
