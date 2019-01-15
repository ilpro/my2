<div class="card connectCard" title="Привязать"  data-connect='{"id":"<?= $connect['newsId'] ?>"}'>
    <? if (file_exists($ini['path.media'] . 'images/' . $connect['newsId'] . '/main/240.jpg')): ?>
        <img src="<?= $ini['url.media'] ?>images/<?= $connect['newsId'] ?>/main/240.jpg" width="178"/>
    <? endif; ?>
    <h4 id="news<?= $connect['newsId'] ?>"><?= $connect['newsHeader'] ?></h4>

    <p class="link">Взять код вставки</p>
</div>	