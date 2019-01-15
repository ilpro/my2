<?php

if (!empty($page_data['news_bottom']))
    foreach ($page_data['news_bottom'] as $item) {
        $newsHeader = $item['newsHeader'];
        if (strlen($newsHeader) > 140) {
            $offset = (140 - 3) - strlen($newsHeader);
            $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
        }
        ?>

        <a href="/<?=($item['newsType']==0)?'news':'article';?>/<?=$item['newsId'];?>" class="list-item <?if($item['newsMain'] > 0) echo"important";?>">
            <div class="pull-left time">
                <?= $item['newsTimePublic'] ?>
            </div>
            <div class="pull-left news-head">
                <?if($item['newsMain'] > 0) echo'<i class="ico ico-important"></i>';?>
                <?=$newsHeader?>
            </div>
            <div class="icons pull-right">
                <? if($item['newsExclusive'] == 1) echo '<i class="ico ico-exclusive" title="Ексклюзив"></i>'?>
                <? if($item['newsIsGallery'] == 1) echo '<i class="ico ico-photo" title="Фото"></i>'?>
                <? if($item['newsIsVideo'] == 1) echo '<i class="ico ico-video" title="Відео"></i>'?>
            </div>
            <div class="clearfix"></div>
        </a>
<?}?>