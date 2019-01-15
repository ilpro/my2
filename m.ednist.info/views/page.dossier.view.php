
<? if (!$id) {?>
     <div id="dossier" class="content">
          <div class="head">
           
          </div>
          <div class="dossier-list">
               <? include Config::getRoot()."/views/block/dossier.view.php"; ?>
               <div class="clearfix"></div>
          </div>
     </div>


     <?
} else if($page_data['brand']['brandStatus'] == 4) {
     ?>

<div class="novelty">
     <?php if(!empty($page_data['brand']['images']['main']['big'])){?>
          <img src="<?= $page_data['brand']['images']['main']['big']; ?>" width="100%" class="novelty__img">
     <?php }?>
     <h1 class="news-title">
          <?= $page_data['brand']['brandName'] ?>
     </h1>

               <div class="novelty__text">
                    <?= html_entity_decode($page_data['brand']['brandDesc']) ?>
                    <div>

               <!--  <p class="novelty__text novelty__text-indent"></p>
                 <p class="novelty__text novelty__text-margin novelty__text-indent"></p>
                 <p class="novelty__text novelty__text-margin"></p>
                 <p class="novelty__text novelty__text-margin"></p>-->

               <?  if(count($page_data['brand']['images'])>2): ?>
                    <div class="gallery">
                         <? foreach($page_data['brand']['images'] as $k=>$img) {
                              if( $k === 'main' ){
                                   continue;
                              }
                              ?>
                              <a href="<?=$img['raw'];?>">
                                   <img  src="<?=$img['gallery'];?>">
                              </a>
                         <? } ?>
                         <div class="clearfix"></div>
                    </div>
               <? endif; ?>

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


          <?
}
?>
