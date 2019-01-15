<?php

    $a1=1;$a2=1;
    if(!empty($page_data['news']))
        foreach($page_data['news'] as $i=>$item){

            switch($item['variant'])  {
                case 1:case 2: $a2++;
             ?>       
                <div class="relative-block" title="<?=$item['newsHeader']?>" >
                    <a href="/news/<?=$item['newsId']?>">
                        <div  class="six-block-item <?=$a2%2==0?'gray2':'gray1'?>">
                            <div class="small-date"><?=$item['newsTimePublic']?></div>
                            <div class="news-title"><?=$item['newsHeader']?></div>
                        </div>
                    </a>
                    <div class="absolute">
                        <div class="news-them">
                            <a title="До категорії «<?=$item['categoryName']?>»" href="/category/<?=$item['categoryTranslit']?>" ><?=$item['categoryName']?></a>
                        </div>
                        <? if($item['newsMain']>0):?>
                            <a href = "/important" title='Важливо!' class="oclick"></a>
                        <? endif;?>
                    </div>
                </div>
             <?
                break;
                case 0: $a2++;
                $urlImg = $ini['url.media']."images/".$item['newsId']."/main/240.jpg";
             ?>       
                <div class="relative-block" title="<?=$item['newsHeader']?>">
                    <a href="/news/<?=$item['newsId']?>" >

                        <div class="six-block-item <?=$a2%2==0?'news-info1 color-news-info1':'news-info2'?> ">
                            <div class="small-date"><?=$item['newsTimePublic']?></div>
                            <div  class="news-title"><?=$item['newsHeader']?></div>
                            <div class="news-desc"><?=$item['newsSubheader']?></div>
                        </div>
                        <div class="six-block-item news-photo">
                            <img src="<?=$urlImg?>" alt="<?=$item['newsHeader']?>" title="<?=$item['newsHeader']?>" style="width: 240px; height:240px">
                            <div class="rectangle-<?=$a2%2==0?'r':'l'?> color-news-info<?=$a2%2==0?'1':'2'?>"></div>
                        </div>
                    </a>
                    <div class="absolute<?=$a2%2==0?'':' move-right'?>">
                        <div class="news-them">
                            <a title="До категорії «<?=$item['categoryName']?>»" href="/category/<?=$item['categoryTranslit']?>" ><?=$item['categoryName']?></a>
                        </div>
                        <? if($item['newsMain']>0):?>
                        <a href = "/important" title='Важливо!' class="oclick"></a>
                        <? endif;?>
                    </div>
                </div>
             <?
                break;
             }   
         }
         ?>