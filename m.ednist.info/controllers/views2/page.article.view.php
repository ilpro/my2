<div class="z-index-for-blocks">
    <div class="center-block-inner">
        <?
        $item = $page_data['material'];
        include "block/fullnew.view.php";
        ?>

    </div>

    <?php

    //Виджет
    if ((isset($page_data['material'])) && $page_data['material']['newsWidgetStart'] == 1) { ?>
        <div class="clear"/>
        <!-- VK Widget -->
        <!-- <script type="text/javascript" src="http://dev1.seetarget.com/widgets.js"></script>
        <div class="seetargetVote" data-id="2" data-tagId="<?= $page_data['material']['widgetTag'] ?>"> </div> -->
    <? }
    ?>
    
    <? if (!empty($page_data['news_bottom'])): ?>
        <div class='clear'></div>
        <h5 class="social-title">До теми:</h5>
        <div class="news-block news">
            <div class="six-block" id="append">
                <?
                include "block/newsBlock.view.php";
               ?>
            </div>
        </div>
    <? endif;?>
</div>

