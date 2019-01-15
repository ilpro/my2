<?php

$htmlString = "<div class='item' tabindex=\"200\" data-img-sprite='{\"imgid\":\"$item[imgId]\", \"imgdesc\":\"$item[imgDesc]\", \"imgpath\":\"" . $ini['url.media'] . "brand/$brandId/\"}'>

    <img src='" . $ini['url.media'] . "brand/$brandId/gallery/$item[imgName]?" . time() . "' width='178' />

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