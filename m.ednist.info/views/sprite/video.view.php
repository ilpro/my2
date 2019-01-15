<div class="last-video">
    <div id="myCarousel4" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <?
            $i=0;
            foreach($page_data['last_video'] as $k=>$item) {
                $i++;
                $newsHeader = $item['newsHeader'];
                if (strlen($newsHeader) > 140) {
                    $offset = (140 - 3) - strlen($newsHeader);
                    $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
                }
                ?>
                <div class="item<? if($i==1) echo " active";?>">
                    <a href="/news/<?=$item['newsId']?>">
                        <? if( $item['newsImgVideo'] != '' ) { ?>
                            <img width="360" height="360" title='<?=$item['newsHeader']?>' alt='<?=$item['newsHeader']?>' src="<?=$ini['url.media']."images/".$item['newsId']."/main/240.jpg";?>"/>
                        <? } ?>
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
                foreach($page_data['last_video'] as $k=>$item) {
                    echo "<li data-target='#myCarousel4' data-slide-to='$k'";
                    if($k==0) echo " class='active'";
                    echo "></li>";
                }
                ?>
            </ol>
            <a href="/video" title="Відео" class="news-them">
                <i class="ico ico-lastVideo"></i>
            </a>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
