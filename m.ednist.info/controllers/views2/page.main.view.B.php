<div id="main_page" class="content">
  <div class="left-content pull-left">
    <div class="slider">
      <div id="myCarousel" class="carousel slide" data-ride="carousel" interval="0">
        <ol class="carousel-indicators">
          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#myCarousel" data-slide-to="1"></li>
          <li data-target="#myCarousel" data-slide-to="2"></li>
          <li data-target="#myCarousel" data-slide-to="3"></li>
        </ol>
        <div class="carousel-inner">
          <?
          if (!empty($page_data['main_news']))
            include Config::getRoot() . "/views/sprite/main.view.php";
          ?>
        </div>
      </div>
    </div>
    <div class="pull-left newsline">
      <a href="/newsline" class="head" title="Стрічка новин">
        Стрічка новин
      </a>
      <div class="news-list main-newsline" id="append" data-controller="main">
        <?
        include Config::getRoot() . "/views/block/newsBlock.view.php";
        ?>
      </div>
      <a href="javascript:void(0)" title="Більше новин" class="more-main">Більше новин</a>
    </div>
    <div class="clearfix"></div>
  </div>
  <div class="right-content pull-right">
    <div id="popular-sidebar" class="pull-right popular">
      <a title="Популярне за добу" href="/popular" class="head">
        <i class="ico ico-popular"></i>
        Популярне за добу
      </a>
      <div class="news-list">
        <?
        $i = 0;
        if (!empty($page_data['popular_news']))
          foreach ($page_data['popular_news'] as $item)
            include Config::getRoot() . "/views/sprite/popular.main.view.php";
        ?>
      </div>
    </div>

    <div class="pull-right last-media" id="ms-widget">
      <? include Config::getRoot() . "/views/block/mediastealerWidget.view.php"; ?>
    </div>
 
    <!-- start  mediastealer media block-->
    <div class="msMedia pull-right" data-ms-media-id="1"></div>
    <!-- end  mediastealer media block -->

    <!-- start ad banner box-->
<!--    <div id="holder_300x250_52" class="pull-right last-media"></div>-->
<!--    <script type="text/javascript">-->
<!--      new holder("holder_300x250_52", {block: 6052});-->
<!--    </script>-->
    <!-- end ad banner box-->

    <? include Config::getRoot() . "/views/block/twitter.view.php"; ?>

    <!-- START GOOGLE BANNER 300x250-->
<!--    <div id='div-gpt-ad-1461249779400-0' style='height:250px; width:300px;' class="pull-right last-media">-->
<!--      <script type='text/javascript'>-->
<!--        googletag.cmd.push(function() { googletag.display('div-gpt-ad-1461249779400-0'); });-->
<!--      </script>-->
<!--    </div>-->
    <!-- END GOOGLE BANNER 300x250-->

  </div>
  <div class="clearfix"></div>
</div>
<div class="tags">
  <?
  $mainTag = null;
  if (is_array($static_data['header_tags']))
    foreach ($static_data['header_tags'] as $tag) {
      if ($tag['tagName'] != '')
        include Config::getRoot() . "/views/sprite/tag.view.php";
      if ($id == $tag['tagId']) {
        $mainTag = $tag;
      }
    }
  ?>
  <div class="clearfix"></div>
</div>

<? include Config::getRoot() . "/views/block/exclusive.block.view.php"; ?>

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


<!-- start banner ad-box-->
<!--<div id="holder_960x180_51" class="ad-top-banner"></div>-->
<!--<script type="text/javascript">-->
<!--  new holder("holder_960x180_51", {block: 6051});-->
<!--</script>-->
<!-- end banner ad-box -->

<? include Config::getRoot() . "/views/block/banner.view.php"; ?>

<? include Config::getRoot() . "/views/block/interview.block.view.php"; ?>

<? include Config::getRoot() . "/views/block/dossier.block.view.php"; ?>

<? include Config::getRoot() . "/views/block/resonans.block.view.php"; ?>

<? //include Config::getRoot()."/views/block/blogs.block.view.php"; ?>

<? include Config::getRoot() . "/views/block/social.block.view.php"; ?>
