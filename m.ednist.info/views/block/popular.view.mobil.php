<?php if (!empty($sliders['popular_news'])) { ?>

     <?php foreach ($sliders['popular_news'] as $pop_news) {?>
          <?php
          $mage = !empty($pop_news['images'][0]['big'])?$pop_news['images'][0]['big']:'/assets/img/about-logo.jpg';
          $newsHeader = $pop_news['newsHeader'];
          if (strlen($newsHeader) > 250) {
               $offset = (250 - 3) - strlen($newsHeader);
               $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
          }
          ?>
        <div class="slide">
            <a href="/<?=($pop_news['newsType']==0)?'news':'article';?>/<?=$pop_news['newsId'];?>">

                <img src="<?= $mage?>" width="65" height="65" class="slide__img">
                <div class="img-grad"></div>
                <span class="slide__time"><?= $pop_news['newsTimePublic']?></span>
                <span class="slide__title"><?= $newsHeader?></span> </a>
        </div>
     <?php } ?>
<?php }?>




