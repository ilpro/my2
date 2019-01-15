
<a href="/news/<?=$item['newsId']?>" <?=$item['newsMain']>0 ? "class='important'" : '';?> >
    <div class="small-date">
        <?=$item['newsTimePublic'];?>
        <div class="icons">
            <? if($item['newsExclusive'] == 1) echo '<i class="ico ico-exclusive" title="Ексклюзив"></i>'?>
            <? if($item['newsIsGallery'] == 1) echo '<i class="ico ico-photo" title="Фото"></i>'?>
            <? if($item['newsIsVideo'] == 1) echo '<i class="ico ico-video" title="Відео"></i>'?>
        </div>
    </div>
    <div class="list-title"><?=$item['newsHeader'];?></div>
</a>