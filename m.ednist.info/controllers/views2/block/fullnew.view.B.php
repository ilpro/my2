<script>
       $.ajax({
            type: "POST",
            dataType: 'json',
            url: '/controllers/news.controller.php',
            data: {
                action:'jsVisits',
                page: page,
                pageId: pageId
            }
        }).done(function (obj) {

        });
</script>

<div id="view-news" class="content">
    <div class="left-content pull-left">
        <div class="head">
            <? if($page=='news'):?>
                <? if(isset($item['categoryTranslit'])):?>
                    <a href="/category/<?=$item['categoryTranslit']?>"><span itemprop="articleSection"><?=$item['categoryName']?></span></a>
                <? endif;?>
            <? elseif($page=='article'):?>
                <span itemprop="articleSection"><a href="/public">Стаття</a></span>
            <? endif;?>
        </div>
        <div class="news-header">
            <div class="image pull-left">
                <? if(isset($item['images']['main'])):?>
                    <div class="main-img-block">
                        <a href="<?=$item['images']['main']['raw'];?>" data-caption="<?=$item['newsHeader']?>">
                            <img itemprop="image" src="<?=$item['images']['main']['big'];?>" alt="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" title="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>">
                        </a>
                    </div>
                <? endif;?>
            </div>
            <div class="news-description">
                <div class="pull-left date">
                    <div class="pull-left time" itemprop="datePublished" content="<?=$item['newsTimePublic']?>">
                        <?=$item['newsTimePublic']?>
                    </div>
                    <div class="pull-left icons">
                        <? if($item['newsExclusive'] == 1) echo '<a href="/exclusive"><i class="ico ico-exclusive" title="Ексклюзив"></i></a>'?>
                        <? if($item['newsIsGallery'] == 1) echo '<a href="/photo"><i class="ico ico-photo" title="Фото"></i></a>'?>
                        <? if($item['newsIsVideo'] == 1) echo '<a href="/video"><i class="ico ico-video" title="Відео"></i></a>'?>
                        </div>
                    <div class="clearfix"></div>
                </div>
                <h1 itemprop="name" class="news-title">
                    <?=$item['newsHeader']?>
                </h1>
                <h2 class="news-subtitle">
                    <?=$item['newsSubheader']?>
                </h2>
                <div class="news-tags">
                    <?//
                    if(is_array($item['tags'])) {
                        foreach ($item['tags'] as $tag) {
                            echo "<a href=\"/tag/" . $tag['tagId'] . "\">#$tag[tagName]</a>";
                        }
                    }
                    ?>
                    <? if($item['newsExclusive'] == 1) echo '<div class="f-news-exclusive"><i class="ico ico-ecsclusive" title="Ексклюзив"></i></div>' ?>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="news-body" itemprop="articleBody">
            <?= html_entity_decode($item['newsText']) ?>
            <?
            if(isset($item['youtube'])):
                echo $item['youtube'];
            endif;
            ?>
        </div>
<!--        --><?// include Config::getRoot().'/views/banners/video.banner.php';?>

        <? include Config::getRoot().'/views/block/social.view.php';?>

        <? if(count($item['images'])>2): ?>
            <div class="gallery">
                <? foreach($item['images'] as $k=>$img) {
                    if($k==='main') continue; /// пофіксити в моделі
                    ?>
                    <a class='galleryImg' href="<?=$img['raw'];?>" data-caption="<?=$img['desc'];?>">
                        <img alt="<?=$img['desc'];?>" title="<?=$img['desc'];?>" src="<?=$img['gallery'];?>">
                    </a>
                <? } ?>
                <div class="clearfix"></div>
            </div>
        <? endif; ?>

        <? include Config::getRoot()."/views/block/blockComments.view.php"; ?>

        <?
        if(!empty($page_data['news_bottom']))
            $i=0;
            foreach($page_data['news_bottom'] as $item){
                include Config::getRoot()."/views/sprite/readToo.view.php";
            }
        ?>
        <?
        if(!empty($page_data['material']['brands']))
            foreach($page_data['material']['brands'] as $item)
                include Config::getRoot()."/views/sprite/brand.view.php";
        ?>

        <!-- start ad banner-->
<!--        <div id="holder_640x360_27"></div>-->
<!--        <script type="text/javascript">-->
<!--            new holder("holder_640x360_27",{block:6027,postview:1});-->
<!--        </script>-->
        <!-- end ad banner-->
    </div>
    <div class="right-content pull-right">

        <?include Config::getRoot()."/views/block/mediastealerWidget.view.php"; ?>


        <!-- start ad banner-->
<!--        <div id="holder_300x250_26" class="pull-right last-media"></div>-->
<!--        <script type="text/javascript">-->
<!--            new holder("holder_300x250_26",{block:6026});-->
<!--        </script>-->
        <!-- end ad banner-->

<!--        <div class="pull-right last-media">-->
<!--            <a class="pull-right last-media" href="http://www.mediastealer.com/">-->
<!--                <img src="/assets/img/mediastealer_300x250.jpg" alt="">-->
<!--            </a>-->
<!--        </div>-->

        <!-- start  mediastealer media block-->
        <div class="msMedia pull-right last-media" data-ms-media-id="1"></div>

        <!-- end  mediastealer media block -->

        <?include Config::getRoot()."/views/block/popularSidebar.view.php"; ?>

        <!-- start  mediastealer media block-->
        <div class="msMedia pull-right last-media" data-ms-media-id="4"></div>
        <!-- end  mediastealer media block -->

        <? include Config::getRoot()."/views/sprite/categorySidebar.view.php";?>

        <? include Config::getRoot()."/views/sprite/newslineSidebar.view.php";?> 


        <!-- start ad banner-->
<!--        <div id="holder_300x250_52" class="pull-right last-media"></div>-->
<!--        <script type="text/javascript">-->
<!--            new holder("holder_300x250_52",{block:6052});-->
<!--        </script>-->
        <!-- end ad banner-->


        <!-- START GOOGLE BANNER 300x250 ad-->
<!--        <div id='div-gpt-ad-1461249779400-0' style='height:250px; width:300px;' class="pull-right last-media">-->
<!--            <script type='text/javascript'>-->
<!--                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1461249779400-0'); });-->
<!--            </script>-->
<!--        </div>-->
        <!-- END GOOGLE BANNER 300x250-->

<!--        --><?//include Config::getRoot()."/views/banners/300x250.view.php";?>
<!---->
<!--        --><?//include Config::getRoot()."/views/banners/300x250.view.php";?>
<!---->
<!--        --><?//include Config::getRoot()."/views/sprite/categorySidebar.view.php";?>
<!---->
<!--        --><?//include Config::getRoot()."/views/banners/300x250.view.php";?>

<!--        --><?//include Config::getRoot()."/views/block/twitter.view.php";?>

    </div>
    <div class="clearfix"></div>
</div>

<!-- start banner ad-box-->
<!--<div class="clearfix">-->
<!--    <div id="holder_728x90_53" class="ad-menu-banner pull-left"></div>-->
<!--    <a href="/dossier/416" class="pull-right">-->
<!--        <img src="/assets/img/docije_2.jpg" alt="">-->
<!--    </a>-->
<!--</div>-->
<!--<script type="text/javascript">-->
<!--    new holder("holder_728x90_53",{block:6053});-->
<!--</script>-->
<!-- end banner ad-box-->

<div style="margin: 6px 0 0 0; height: 90px;">
    <!-- start  mediastealer media block-->
    <div class="msMedia pull-left" data-ms-media-id="3"></div> 
    <!-- end  mediastealer media block -->

    <a class="pull-right dossier-banner"
       href="http://www.ednist.info/dossier/<?= $page_data['rand_brand2']['brandId']; ?>">
        <img src="<?= $ini['url.media'] . "brand/" . $page_data['rand_brand2']['brandId'] . "/main/240.jpg"; ?>">
        <span class="dossier-banner-info">
            <span class="dossier-banner-header">Досьє</span>
            <span class="dossier-banner-surname"><?= explode(" ", $page_data['rand_brand2']['brandName'])[0]; ?></span>
            <span class="dossier-banner-name"><?= explode(" ", $page_data['rand_brand2']['brandName'])[1]; ?></span>
            <span class="dossier-banner-name"><?= explode(" ", $page_data['rand_brand2']['brandName'])[2]; ?></span>
        </span>
    </a>
</div>


<? include Config::getRoot()."/views/block/social.block.view.php"; ?>

<? include Config::getRoot()."/views/block/newsSeoBlock.view.php"; ?>