<?php
if (!empty($page_data['news']))
    foreach ($page_data['news'] as $item) {

        $newsHeader = $item['newsHeader'];
        $newsSubHeader = $item['newsSubheader'];
        if (strlen($newsHeader) > 100)
        {
            $offset = (100 - 3) - strlen($newsHeader);
            $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
        }
        if (strlen($newsSubHeader) > 150)
        {
            $offset = (150 - 3) - strlen($newsSubHeader);
            $newsSubHeader = substr($newsSubHeader, 0, strrpos($newsSubHeader, ' ', $offset)) . '...';
        }
        ?>
        <a href="/<?=($item['newsType']==0)?'news':'article';?>/<?=$item['newsId'];?>" class="interview-item">
            <figure class="interview-img">
                <img src="<?=$ini['url.media'] . "images/" . $item['newsId'] . "/main/240.jpg"?>" alt="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" title="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>">
            </figure>

            <div class="interview-info">

                <div class="time">
                    <?= $item['newsTimePublic'] ?>
                </div>
                <cite class="interview-cite">
                    <i class="ico ico-quote "></i>
                    <?=$newsHeader;?></cite>

                <p class="interview-desc"><?=$newsSubHeader;?></p>

                <span class="interview-readmore">читати далi...</span>
            </div>
            <div class="clearfix" style="clear: both"></div>
        </a>
    <?}?>