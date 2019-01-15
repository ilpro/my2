<style>
    .ad3 .menu-item .ad-text {
        color: white;
    }
</style>

<div id="exclusive" class="content">
  <div class="head">
    Публікації
  </div>
  <div class="news-list clearfix" id="append" data-controller="public">
    <!--		--><? //include "block/adLineSidebar.view.php";?>

    <div class="pull-right line-sidebar">
      <? include Config::getRoot() . "/views/block/mediastealerWidget.view.php"; ?>

      <div style="margin-bottom: 3px">
        <? include Config::getRoot() . "/views/banners/300x250.adoffer.view.php"; ?>
      </div>
    </div>
    <?
    if (!empty($page_data['articles']))
      foreach ($page_data['articles'] as $item)
        include "block/exclusiveBlock.view.php";
    ?>
  </div>
  <div class="more-news">
    <button class="show-more">
      Ще новини
    </button>
  </div>
</div>