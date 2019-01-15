
<?
if(isset($page_data['material'])){?>
<div class="z-index-for-blocks">
    <div class="center-block-inner">
        <?
        $item = $page_data['material'];
        include "block/fullblog.view.php";
        ?>

    </div>

    <div class="absolut-news-list">

        <? include "right_block.view.php"; ?>

    </div>

    <div class="absolut-left-list">

        <? include "block/left_absolut_list.view.php"; ?>

    </div>
    <?
    //include "right_block.view.php";
   // include "block/social.view.php";
    ?>

    <?
    if (isset($page_data['material']['brands']) && $items = $page_data['material']['brands']):
        ?>
        <? if ($items[0]): ?>
        <div style="clear: both;"></div>

        <h5 class="social-title">Досьє:</h5>
        <div class="news-preview">

            <div class="carousel slide" data-ride="carousel">
                <!-- Carousel items -->
                <div class="carousel-inner">
                    <div class="item active">
                        <?
                        $i = 0;
                        foreach ($items as $item)
                            include "sprite/brand.view.php";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <? endif; ?>
    <? endif; ?>
        <?php
        
  //Виджет
 if ((isset($page_data['material'])) && $page_data['material']['newsWidgetStart'] == 1) { ?>
        <div class="clear"/>
    <!-- VK Widget -->
    <!-- <script type="text/javascript" src="http://dev1.seetarget.com/widgets.js"></script>
    <div class="seetargetVote" data-id="2" data-tagId="<?= $page_data['material']['widgetTag'] ?>"> </div> -->
<? }
?>

    <!---add comments --->
    <?
    include "block/blockComments.view.php";
    ?>
    <!--- --------------->
    <? include "block/newsSeoBlock.view.php"; ?>
</div>


<?}
else{ ?>
<div class="center-block full-width">
    <div class="block-title">БЛОГИ</div>
    <div class="news-block" id="append">


        <? if( !empty($page_data['blogs']) ) { ?>
            <? foreach($page_data['blogs'] as $item) { ?>

                <? include "sprite/blogLineBlock.php"; ?>

            <? } ?>
        <? } ?>


    </div>

    <?
    if( $page_data['check_news_count'] ) {
        include "btn/btn.more_blogLines.view.php";
    }
    ?>

</div>

<? 
} ?>
