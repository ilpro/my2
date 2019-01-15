<?
$newsHeader = $item['newsHeader'];
if (strlen($newsHeader) > 95) {
    $offset = (95 - 3) - strlen($newsHeader);
    $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
}
?>
<a href="/<?=($item['newsType']==0)?'news':'article';?>/<?=$item['newsId'];?>" class="list-item <?if($item['newsMain'] > 0) echo"important";?>">
    <div class="pull-left time">
        <?= $item['newsTimePublic'] ?>
        <div class="icons">
            <? if($item['newsExclusive'] == 1) echo '<i class="ico ico-exclusive" title="Ексклюзив"></i>'?>
            <? if($item['newsIsGallery'] == 1) echo '<i class="ico ico-photo" title="Фото"></i>'?>
            <? if($item['newsIsVideo'] == 1) echo '<i class="ico ico-video" title="Відео"></i>'?>
        </div>
    </div>
    <div class="pull-left image">
        <img src="<?=$ini['url.media'] . "images/" . $item['newsId'] . "/main/60.jpg"?>" alt="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" title="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>">
    </div>
    <div class="pull-left news-head">
        <?if($item['newsMain'] > 0) echo'<i class="ico ico-important"></i>';?>
        <?= $newsHeader ?>
    </div>
    <div class="clearfix"></div>
</a>