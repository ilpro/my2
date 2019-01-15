
<?
$i=0;
foreach($page_data['main_news'] as $item) {
    $newsHeader = $item['newsHeader'];
    $newsSubHeader = $item['newsSubheader'];
    if (strlen($newsHeader) > 110)
    {
        $offset = (110 - 3) - strlen($newsHeader);
        $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
    }
    if (strlen($newsSubHeader) > 200)
    {
        $offset = (200 - 3) - strlen($newsSubHeader);
        $newsSubHeader = substr($newsSubHeader, 0, strrpos($newsSubHeader, ' ', $offset)) . '...';
    }
?>

    <div class="item<? if($i==0){$i++;echo " active";}?>">
      <a class="main-news" href="/news/<?=$item['newsId']?>">
        <div class="main-news-info">
          <div class="time">
              <?=$item['newsTimePublic']?>
          </div>
          <div class="main-news-title"><?=$newsHeader?></div>
          <p><?=$newsSubHeader?></p>
        </div>
          <img src="<?=$item['images']['main']['big'];?>" alt="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" title="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>"/>
      </a>
        <div class="absolute">
            <a title="<?=$item['categoryName']?>" href="/category/<?=$item['categoryTranslit']?>" class="news-them" ><?=$item['categoryName']?></a>
            <?if($item['newsMain'] > 0) echo '<a href="/important" title="Важливо!"><i class="ico ico-importantBig"></i></a>'?>
            <div class="clearfix"></div>
        </div>
    </div>

<?
}