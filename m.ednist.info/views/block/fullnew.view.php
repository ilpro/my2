
<div class="novelty">
<?php if(!empty($page_data['this_news']['images']['main']['big'])){?>

    <img src="<?= $page_data['this_news']['images']['main']['big']; ?>" width="100%" class="novelty__img">
    <div class="img-grad"></div>
<?php }?>
    <span class="novelty__type section_type"><?= $page_data['this_news']['categoryName'] ?></span>
    <span class="novelty__time"><?= $page_data['this_news']['newsTimePublic'] ?></span>


    <div class="novelty__title">
         <?= $page_data['this_news']['newsHeader'] ?>
        </div>

            <div class="novelty__subtitle">
         <?= $page_data['this_news']['newsSubheader'] ?>
        </div>
            <div class="novelty__text">
                 <?= html_entity_decode($page_data['this_news']['newsText']) ?>
        </div>


    <!--  <p class="novelty__text novelty__text-indent"></p>
      <p class="novelty__text novelty__text-margin novelty__text-indent"></p>
      <p class="novelty__text novelty__text-margin"></p>
      <p class="novelty__text novelty__text-margin"></p>-->
             <? if(count($page_data['this_news']['images'])>2): ?>
                 <div class="gallery">
                      <? foreach($page_data['this_news']['images'] as $k=>$img) {
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
         if (is_array($page_data['this_news']['tags'])) {
              foreach ($page_data['this_news']['tags'] as $tag) {
                   echo "<a href=\"/tag/" . $tag['tagId'] . "\" class=\"tag\">#$tag[tagName]</a>";
              }
         }
         ?>
    </div>

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

     <?php if (!empty($page_data['this_news']['connectedNews']) && is_array($page_data['this_news']['connectedNews'])) {
     ?>



    <span class="novelty__type">До теми</span>

    <div class="novelty__themes">
         <?php
         foreach ($page_data['this_news']['connectedNews'] as $it) {
              if (strlen($it['newsHeader']) > 250) {
                   $offset = (250 - 3) - strlen($it['newsHeader']);
                   $it['newsHeader'] = mb_substr($it['newsHeader'], 0, strrpos($it['newsHeader'], ' ', $offset)) . '...';
              }

              ?>
             <a href="/news/<?= $it['newsId'] ?>">
                 <div class="theme" style="background: url(<?= !empty($it['images'][0]['big']) ? $it['images'][0]['big'] : '' ?>); background-size: cover; background-position: 50% 0;">
                     <div class="img-grad"></div>
                     <div class="info">
                         <p class="theme__time"><?= $it['newsTimePublic'] ?></p>
                         <p class="theme__title"><?= $it['newsHeader'] ?></p>
                         <p class="theme__text"><?= $it['newsSubheader'] ?></p>
                     </div>
                 </div>
             </a>
         <?php } ?>

    </div>


</div>

<?php } ?>

<script async src="https://widget.mediastealer.com/dating/datingLauncher.js"></script>
<div class="msCustomMedia" data-ms-custom-media-id="270"></div>


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


