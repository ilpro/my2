<?php
if (!empty($static_data['header_tags'])) {
     $tags = array_chunk($static_data['header_tags'], 4);
     array_pop($tags);
     foreach ($tags as $n => $tag) {
          if ($n == 0) {
               echo '<div class="panel__tags">';
          }elseif($n == 1){
               echo '<div class="panel__tags panel__tags-line">';
          }elseif ($n == 2){
               echo '<div class="panel__tags panel__tags-line">';
          }else{
               break;
          }
          foreach ($tag as $t) {?>
               <a href="/tag/<?= $t['tagId']?>" class="tag"><?= $t['tagName']?></a>

          <?php }?>
          </div>
          <?php
     }
} ?>