<?php

$htmlString = "<div class='item' tabindex=\"200\" data-img-desc=\"".htmlspecialchars($item['imgDesc'])."\" data-img-sprite='{\"imgid\":\"$item[imgId]\",\"imgpath\":\"" . $ini['url.media'] . "images/$newsId/\"}'>

    <img src='" . $ini['url.media'] . "images/$newsId/gallery/$item[imgName]?" . time() . "' width='178' />

    <a href='#' class='del' title='Удалить'><i></i></a>

    <a href='#' class='show' title='Показать'><i></i></a>

    <a href='#' class='link' title='Скопировать ссылку'><i></i></a>

    <a href='#' class='cut' title='Вырезать'><i></i></a>

    <span class='sill";

if ($item['imgMain'] == 1) {
    $htmlString .= " main";
};

$htmlString .= "'>Главное</span>

</div>";