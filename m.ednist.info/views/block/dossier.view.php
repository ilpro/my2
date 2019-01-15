<?php


foreach ($page_data['brands_all'] as $brand) {
     $sortField = ($brand['brandSortName']!='') ? $brand['brandSortName'] : $brand['brandName'] ;
     ?>
     <div class="novelty__themes">
          
               <a href="/dossier/<?=$brand['brandId'];?>">
                    <div class="theme">
                         <img src="<?= !empty($brand['images']['main']['big']) ? $brand['images'][0]['big'] : '' ?>"
                              width="120" height="120" class="theme__img">
                         <div class="info">
                              <p class="theme__text"><?= $sortField ?></p>
                         </div>
                    </div>
               </a>


     </div>
<?php
}