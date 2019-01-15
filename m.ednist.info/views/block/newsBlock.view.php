<?php

if (!empty($page_data['news_bottom']))
     foreach ($page_data['news_bottom'] as $item) {
          $newsHeader = $item['newsHeader'];
          if (strlen($newsHeader) > 140) {
               $offset = (140 - 3) - strlen($newsHeader);
               $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
          }
          
          ?>

               <a href="/<?=($item['newsType']==0)?'news':'article';?>/<?=$item['newsId'];?>"><div class="news">
                         <?php if(!empty($item['images'])){
                              $img =  $item['images'][0]['big'];
                         }?>
                         <img src="<?= $img?>" class="news__img">
                         <div class="img-grad"></div>
                         <div class="mini">
                              <span class="news__type"><?= $item['categoryName']?></span>
                              <span class="news__time"><?= $item['newsTimePublic'] ?></span>
                              <p class="mini__title"><?=$newsHeader?></p>
                              <p class="mini__text"><?=$item['newsSubheader']?></p>
                         </div>
                    </div>
               </a>


     <?}?>