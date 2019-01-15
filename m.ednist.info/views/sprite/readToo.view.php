<div class="read-too">
    <a href="/news/<?= $item['newsId'] ?>" class="item">
        <div class="pull-left icon">
            До теми
        </div>
        <img src="<?=$ini["url.media"].'images/'.$item["newsId"].'/main/240.jpg'?>" class="pull-left" alt="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" title="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>">
        <div class="description pull-left">
            <?= $item['newsHeader'] ?>
        </div>
        <div class="clearfix"></div>
    </a>
</div>