<?php

if (!empty($page_data['articles']))
     foreach ($page_data['articles'] as $item) {
          $newsHeader = $item['newsHeader'];
          if (strlen($newsHeader) > 250) {
               $offset = (250 - 3) - strlen($newsHeader);
               $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
          }
          ?>

          <div class="container-news">
               <a href="/<?=($item['newsType']==0)?'news':'article';?>/<?=$item['newsId'];?>"><div class="news">
                         <?php
                         $img = empty($item['images'][0]['main240'])?'/assets/img/about-logo.jpg':$item['images'][0]['main240'];
                         ?>
                         <img src="<?= $img?>" class="news__img">

                         <div class="mini">
                             <span class="news__type"><?= $item['categoryName']?></span>
                             <span class="news__time"><?= $item['newsTimePublic'] ?></span>
                              <p class="mini__title"><?=$newsHeader?></p>
                              <p class="mini__text"><?=$item['newsSubheader']?></p>
                         </div>
                    </div>
               </a>

          </div>
     <?}?>