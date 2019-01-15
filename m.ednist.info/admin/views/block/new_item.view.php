<?

$href = " onclick=\"$('#auth2').addClass('show');\" ";

?>

<div class="row all cla">

    <div class="check">

        <input type="checkbox" id="c<?= $item['newsId'] ?>" name="myPublication"/>

        <label for="c<?= $item['newsId'] ?>"><span></span></label>

    </div>

    <div class="avatar"<?= $href; ?>><img src="<?= $ini['url.assets'] ?>img/avatar.png" alt=""/></div>

    <div class="identifier"<?= $href; ?>><p><?= $item['newsId'] ?></p></div>

    <div class="intro"<?= $href; ?>><p><?= $item['newsHeader'] ?></p></div>

    <div class="date"<?= $href; ?>><p><?= date('d/m/y H:i', strtotime($item['newsDate'])) ?></p></div>

    <div class="sourceId"<?= $href; ?>><p>12</p></div>

    <div class="web"<?= $href; ?>><p><a href="">lenta.ru</a></p></div>

    <div class="theme"<?= $href; ?>><p><?= $news->getCategories($item['newsCategory']) ?></div>

    <div class="icon-type"><i class="list"></i></div>

    <div class="icon-status"><img
            src="<?= $ini['url.assets'] ?>img/img_<?= $newsStatusIcons[$item['newsStatus']] ?>.png"/></div>

    <div class="clear"></div>

</div>