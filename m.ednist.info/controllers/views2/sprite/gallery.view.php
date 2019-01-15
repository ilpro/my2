<div class="last-photo">
    <div id="myCarousel2" class="carousel slide" data-ride="carousel" interval="500">
        <div class="carousel-inner">
            <?
            $i=0;
            foreach($page_data['last_gallery'] as $k=>$item) {
                $i++;
                $newsHeader = $item['newsHeader'];
                if (strlen($newsHeader) > 140) {
                    $offset = (140 - 3) - strlen($newsHeader);
                    $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
                }
                ?>
                <div class="item<? if($i==1) echo " active";?>">
                    <a href="/news/<?=$item['newsId']?>">
                        <img width="360" height="360" title='<?=$item['newsHeader']?>' alt='<?=$item['newsHeader']?>' src="<?=$ini['url.media']."images/".$item['newsId']."/main/240.jpg";?>"/>
                        <div class="last-new-title">
                            <div class="small-date"><?=$item['newsTimePublic']?></div>
                            <div class="news-title"><?=$newsHeader?></div>
                        </div>
                    </a>
                </div>
                <?
            }
            ?>
        </div>
        <div class="absolute">
            <ol class="carousel-indicators">
                <?
                if(is_array($page_data['last_gallery'])){
                    foreach($page_data['last_gallery'] as $k=>$img){
                        echo "<li data-target='#myCarousel2' data-slide-to='$k'";
                        if($k==0) echo " class='active'";
                        echo "></li>";
                    }
                }
                ?>
            </ol>
            <a href="/photo" title="Фото" class="news-them pull-left">
                <i class="ico ico-lastPhoto"></i>
            </a>
        </div>
    </div>
</div>