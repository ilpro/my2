<?php if (!empty($page_data['last_news'])) { ?>

     <?php foreach ($page_data['last_news'] as $last) {?>
          <?php
          $mage = !empty($last['images'][0]['big'])?$last['images'][0]['big']:'/assets/img/about-logo.jpg';
          $newsHeader = $last['newsHeader'];
          if (strlen($newsHeader) > 250) {
               $offset = (250 - 3) - strlen($newsHeader);
               $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
          }
          ?>
          <div class="slide">
               <a href="/<?=($last['newsType']==0)?'news':'article';?>/<?=$last['newsId'];?>">

                    <img src="<?= $mage?>" width="65" height="65" class="slide__img">
                    <div class="img-grad"></div>
                    <span class="slide__time"><?= $last['newsTimePublic']?></span>
                    <span class="slide__title"><?= $newsHeader?></span> </a>
          </div>
     <?php } ?>
<?php }?>
