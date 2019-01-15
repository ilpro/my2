
<?
$i++;
$newsType = '/news';
if ($value['newsType'] == 1) {
    $newsType = '/article';
}
if ($i % 3 == 1 and $i != 1):
    ?>
    </div><div class="item">
    <? endif; ?>

    <a href="<?= $newsType; ?>/<?= $value['newsId'] ?>" class="proposal-post">
        <img src="<?=$ini['url.media']."images/".$value['newsId']."/main/240.jpg"; ?>" width="80px">
        <div class="proposal-title"><?= $value['newsHeaderClip']; ?></div>
    </a>