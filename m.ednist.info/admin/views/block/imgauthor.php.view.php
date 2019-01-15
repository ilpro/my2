<?php

$htmlString = "<div class='item' tabindex=\"200\" data-img-sprite='{ \"imgname\":\"$imgName\", \"imgpath\":\"" . $ini['url.media'] . "author/$authorId/\"}'>

    <img src='" . $ini['url.media'] . "author/" . $authorId . "/$imgName?" . time() . "' width='178' />

    <a href='#' class='del' title='Удалить'><i></i></a>

    <a href='#' class='show' title='Показать'><i></i></a>

    <a href='#' class='cut' title='Вырезать'><i></i></a>

</div>";