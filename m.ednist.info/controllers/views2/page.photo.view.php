<div id="media" class="content">
    <div class="head">
        Фото
    </div>
    <div class="media-container clearfix" id="append" data-controller="photo">
        <?include "block/lineSidebarMedia.view.php";?>
        <div class="media-row">
            <?php
            $i = 0;
            if (!empty($page_data['news_bottom']))
                foreach ($page_data['news_bottom'] as $item) {
                    include "block/mediaBlock.view.php";
                    $i++;
                    if($i % 3 == 0)
                        echo '<div class="clearfix"></div></div><div class="media-row">';
                }
            ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="more-news">
        <button class="show-more">
            Ще новини
        </button>
    </div>
</div>