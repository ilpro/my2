<div class="right-block">

    <a href="/category/war" title='Переглянути новини у вигляді стрічки' class="list-link">
        ВІЙНА
    </a>

    <div class="right-list">

        <? if( !empty($page_data['category_news']) ) { ?>
            <? foreach($page_data['category_news'] as $item) { ?>


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


            <? } ?>
        <? } ?>


    </div>

</div>