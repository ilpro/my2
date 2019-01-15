<?php

if (!empty($page_data['exclusive']))
     foreach ($page_data['exclusive'] as $item) {
          $newsHeader = $item['newsHeader'];
          if (strlen($newsHeader) > 250) {
               $offset = (250 - 3) - strlen($newsHeader);
               $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
          }
          ?>


               <a href="/<?=($item['newsType']==0)?'news':'article';?>/<?=$item['newsId'];?>"><div class="news">
                         <?php
                         $img = empty($item['images'][0]['big'])?'/assets/img/about-logo.jpg':$item['images'][0]['big'];
                         ?>
                         <img src="<?= $img?>" class="news__img">
                         <div class="img-grad"></div>
                         <h2 class="news__type"><?= $item['categoryName']?><span class="news__time"><?= $item['newsTimePublic'] ?></span></h2>
                         <div class="mini">
                              <p class="mini__title"><?=$newsHeader?></p>
                              <p class="mini__text"><?=$item['newsSubheader']?></p>
                         </div>
                    </div>
               </a>


     <?}?>