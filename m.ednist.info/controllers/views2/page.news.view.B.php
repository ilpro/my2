<?php
if(isset($page_data['material'])){
    $item = $page_data['material'];
  include "block/fullnew.view.php";
}
else{ ?>
   <div class="center-block articles full-width">
    <div class="block-title">Новини</div>
   <div class="six-block" id="append">
            <?
                  include "block/newsBlockPageNews.view.php";
            ?>
            </div>

</div>

<? 
      if( $page_data['check_news_count'] )
      include "btn/btn.more_news.view.php";
} ?>
