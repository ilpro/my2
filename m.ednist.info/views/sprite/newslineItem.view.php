<?php
if($item['newsType']==1){
    $newsType='article';
}
else{
    $newsType='news';
}
$newsHeader = $item['newsHeader'];
if (strlen($newsHeader) > 130)
{
    $offset = (130 - 3) - strlen($newsHeader);
    $newsHeader = substr($newsHeader, 0, strrpos($newsHeader, ' ', $offset)) . '...';
}
?>
<a href="<?='/'.$newsType.'/'.$item["newsId"]?>" class="list-item <?if($item['newsMain'] > 0) echo"important";?>">
    <div class="time pull-left">
        <?=$item["newsTimePublic"];?>
    </div>
    <div class="icons">
        <? if($item['newsExclusive'] == 1) echo '<i class="ico ico-exclusive" title="Ексклюзив"></i>'?>
        <? if($item['newsIsGallery'] == 1) echo '<i class="ico ico-photo" title="Фото"></i>'?>
        <? if($item['newsIsVideo'] == 1) echo '<i class="ico ico-video" title="Відео"></i>'?>
    </div>
    <div class="clearfix"></div>
    <div class="title">
        <?if($item['newsMain'] > 0) echo'<i class="ico ico-important"></i>';?>
        <?=$newsHeader;?>
    </div>
</a>