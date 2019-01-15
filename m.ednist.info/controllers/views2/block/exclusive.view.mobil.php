<?php if (!empty($page_data['exclusive'])) { ?>

     <?php foreach ($page_data['exclusive'] as $item) { ?>
<?php
$mage = !empty($item['images'][0]['main240'])?$item['images'][0]['main240']:'/assets/img/about-logo.jpg';
          $newsHeader = $item['newsHeader'];
          if (strlen($newsHeader) > 250) {
               $offset = (250 - 3) - strlen($newsHeader);
               $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
          }
          ?>
        <div class="slide">
          <a href="/<?=($item['newsType']==0)?'news':'article';?>/<?=$item['newsId'];?>">
            <img src="<?= $mage?>" width="65" height="65" class="slide__img">
            <span class="slide__time"><?= $item['newsTimePublic']?></span>
              <span class="slide__title"><?= $newsHeader?></span></a>
        </div>
     <?php } ?>
<?php } ?>



