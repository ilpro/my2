<?php

$htmlString = "<div class='item' tabindex=\"200\" data-img-sprite='{ \"imgname\":\"$imgName\", \"imgpath\":\"" . $ini['url.media'] . "settings/\"}'>

    <img src='" . $ini['url.media'] . "settings/$imgName?" . time() . "' width='178'/>

    <a href='#' class='del' title='Удалить'><i></i></a>

</div>";