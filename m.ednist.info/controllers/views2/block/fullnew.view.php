
<div class="novelty">
<?php if(!empty($item['images']['main']['main240'])){?>

    <img src="<?= $item['images']['main']['main240']; ?>" width="100%" class="novelty__img">
<?php }?>
    <span class="novelty__type"><?= $item['categoryName'] ?></span>
    <span class="novelty__time"><?= $item['newsTimePublic'] ?></span>


    <div class="novelty__title">
         <?= $item['newsHeader'] ?>
        </div>

            <div class="novelty__subtitle">
         <?= $item['newsSubheader'] ?>
        </div>
            <div class="novelty__text">
                 <?= html_entity_decode($item['newsText']) ?>
        </div>


    <!--  <p class="novelty__text novelty__text-indent"></p>
      <p class="novelty__text novelty__text-margin novelty__text-indent"></p>
      <p class="novelty__text novelty__text-margin"></p>
      <p class="novelty__text novelty__text-margin"></p>-->
             <? if(count($item['images'])>2): ?>
                 <div class="gallery">
                      <? foreach($item['images'] as $k=>$img) {
                           if($k==='main') continue; /// пофіксити в моделі
                           ?>
                          <a class='galleryImg' href="<?=$img['raw'];?>" data-caption="<?=$img['desc'];?>">
                              <img alt="<?=$img['desc'];?>" title="<?=$img['desc'];?>" src="<?=$img['gallery'];?>">
                          </a>
                      <? } ?>
                     <div class="clearfix"></div>
                 </div>
             <? endif; ?>


    <div class="novelty__tags">
         <? //
         if (is_array($item['tags'])) {
              foreach ($item['tags'] as $tag) {
                   echo "<a href=\"/tag/" . $tag['tagId'] . "\" class=\"tag\">#$tag[tagName]</a>";
              }
         }
         ?>
    </div>


     <?php if (!empty($item['connectedNews']) && is_array($item['connectedNews'])) {
     ?>


            <div id="msCommentsContainer"></div>
            <script src="https://widget.mediastealer.com/comments/commentsLauncher.js"></script>
            <script>commentsLauncher({
                    container: "msCommentsContainer",
                    css: "width: 300px;border: none; box-shadow: none",
                    userId: 464044,
                    lang: "ua",
                    smiles: true,
                    comments: true
                })</script>

    <span class="novelty__type">До теми</span>

    <div class="novelty__themes">
         <?php
         foreach ($item['connectedNews'] as $it) {
              if (strlen($it['newsHeader']) > 250) {
                   $offset = (250 - 3) - strlen($it['newsHeader']);
                   $it['newsHeader'] = mb_substr($it['newsHeader'], 0, strrpos($it['newsHeader'], ' ', $offset)) . '...';
              }

              ?>
             <a href="/news/<?= $it['newsId'] ?>">
                 <div class="theme">
                     <img src="<?= !empty($it['images'][0]['main240']) ? $it['images'][0]['main240'] : '' ?>"
                          width="120" height="120" class="theme__img">
                     <div class="info">
                         <p class="theme__time"><?= $it['newsTimePublic'] ?></p>
                         <p class="theme__title"><?= $it['newsHeader'] ?></p>
                         <p class="theme__text"><?= $it['newsSubheader'] ?></p>
                     </div>
                 </div>
             </a>
         <?php } ?>

    </div>

            <script async src="https://widget.mediastealer.com/dating/datingLauncher.js"></script>
            <div class="msCustomMedia" data-ms-custom-media-id="236"></div>


            <div id="msNewsContainer"></div>
            <script src="https://widget.mediastealer.com/news/newsLauncher.js"></script>
            <script>newsLauncher({
                    container: "msNewsContainer",
                    width: "260px",
                    height: "400px",
                    userId: 464044,
                    lang: "ua",
                    bgColor: "5e6fa2",
                    logo: "light",
                    newsTime: true,
                    newsVisits: true,
                    newsOnline: true,
                    newsLikes: "true",
                    newsSelected: "online"
                })</script>


</div>

<?php } ?>




