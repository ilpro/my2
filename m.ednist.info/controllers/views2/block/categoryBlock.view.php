<?
if($item['newsType']==1){
    $newsType='article';
}
else{
    $newsType='news';
}
$newsHeader = $item['newsHeader'];
$newsSubHeader = $item['newsSubheader'];
if (strlen($newsHeader) > 80)
{
    $offset = (80 - 3) - strlen($newsHeader);
    $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
}
if (strlen($newsSubHeader) > 100)
{
    $offset = (100 - 3) - strlen($newsSubHeader);
    $newsSubHeader = substr($newsSubHeader, 0, strrpos($newsSubHeader, ' ', $offset)) . '...';
}
?>
<div class="list-item">
    <a href="<?='/'.$newsType.'/'.$item["newsId"]?>" class="image pull-left">
        <img src="<?=$ini['url.media'] . "images/" . $item['newsId'] . "/main/240.jpg"?>" alt="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" title="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>">
    </a>
    <div class="description pull-left">
        <div class="time pull-left">
            <?= $item['newsTimePublic'] ?>
        </div>
        <div class="icons pull-left">
            <? if($item['newsExclusive'] == 1) echo '<i class="ico ico-exclusive" title="Ексклюзив"></i>'?>
            <? if($item['newsIsGallery'] == 1) echo '<i class="ico ico-photo" title="Фото"></i>'?>
            <? if($item['newsIsVideo'] == 1) echo '<i class="ico ico-video" title="Відео"></i>'?>
        </div>
        <div class="clearfix"></div>
        <a href="<?='/'.$newsType.'/'.$item["newsId"]?>" class="link">
            <div class="news-title">
                <?=$newsHeader;?>
            </div>
            <div class="news-subtitle">
                <?=$newsSubHeader;?>
            </div>
        </a>
    </div>
    <div class="clearfix"></div>
</div>