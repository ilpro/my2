<?php

if (!empty($page_data['news']))
    foreach ($page_data['news'] as $item) {?>
        <a href="/<?=($item['newsType']==0)?'news':'article';?>/<?=$item['newsId'];?>" class="pull-left news-item">
            <img src="<?=$ini['url.media'] . "images/" . $item['newsId'] . "/main/240.jpg"?>" alt="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" title="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>">
            <div class="description pull-left">
                <div class="time"><?= $item['newsTimePublic'] ?></div>
                <div class="news-header"><?=$item['newsHeader'];?></div>
                <div class="news-sub-header"><?=$item['newsSubheader'];?></div>
            </div>
        </a>
    <?}?>

