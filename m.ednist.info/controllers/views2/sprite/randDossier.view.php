<?
$name = explode( ' ', $page_data['rand_brand']['brandName']);
$lName = $name[0];
$fName = $name[1] . ' ' . $name[2];
?>
<a href="/dossier/<?=$page_data['rand_brand']['brandId']?>" class="rand_dossier" style="margin-right: 6px;">
    <div class="photo">
        <img title='<?=$page_data['rand_brand']['brandName']?>' alt="<?=htmlentities($page_data['rand_brand']['images']['main']['desc'] != ''?$page_data['rand_brand']['images']['main']['desc']:$page_data['rand_brand']['newsHeader'], ENT_QUOTES)?>" title="<?=htmlentities($page_data['rand_brand']['images']['main']['desc'] != ''?$page_data['rand_brand']['images']['main']['desc']:$page_data['rand_brand']['newsHeader'], ENT_QUOTES)?>" src="<?=$ini['url.media']."brand/".$page_data['rand_brand']['brandId']."/main/240.jpg";?>" class="pull-left">
    </div>
    <div class="description">
        <div class="head">
            Досье
        </div>
        <div class="name">
            <span><?=$lName?></span>
            <?=$fName?>
        </div>
    </div>
    <div class="clearfix"></div>
</a>