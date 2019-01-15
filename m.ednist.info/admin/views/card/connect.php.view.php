<?php
$htmlString = '<div class="card connectCard attached" title="Привязать" data-connect=\'{"id":"' . $connect['newsId'] . '"}\'>';
if (file_exists($ini['path.media'] . 'images/' . $connect['newsId'] . '/main/240.jpg'))
    $htmlString .= '<img  src="' . $ini['url.media'] . 'images/' . $connect['newsId'] . '/main/240.jpg" width="178" />';
$htmlString .= '<h4 id="news' . $connect['newsId'] . '" >' . $connect['newsHeader'] . '</h4>
<p class="link">Взять код вставки</p>
</div>';