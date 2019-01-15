<?php
$name = explode( ' ', $item['brandName']);
$lName = $name[0];
$fName = $name[1] . ' ' . $name[2];
$description = $item['brandDesc'];
if (strlen($description) > 130)
{
    $offset = (130 - 3) - strlen($description);
    $description = substr($description, 0, strrpos($description, ' ', $offset)) . '...';
}
?>
<div class="dossier-small">
    <a href="/dossier/<?=$item['brandId']?>" class="item">
        <div class="pull-left icon">
            <i class="ico ico-clip"></i>
        </div>
        <img title='<?=$item['brandName']?>' alt="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" title="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" src="<?=$ini['url.media']."brand/".$item['brandId']."/main/240.jpg";?>" class="pull-left">
        <div class="description pull-left">
            <div class="title pull-left">
                Дось'є:
            </div>
            <div class="name pull-left">
                <?=$fName?>
                <span><?=$lName?></span>
            </div>
            <div class="clearfix"></div>
            <div class="text">
                <?=$description?>
            </div>
        </div>
        <div class="clearfix"></div>
    </a>
    <div class="clearfix"></div>
</div>
