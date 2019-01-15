<?
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
<div class="pull-left list-item">
    <a href="<?='/'.$newsType.'/'.$item["newsId"]?>" class="image">
        <img src="<?=$ini['url.media'] . "images/" . $item['newsId'] . "/main/240.jpg"?>" alt="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>" title="<?=htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES)?>">
    </a>
    <div class="description">
        <div class="time pull-left">
            <?= $item['newsTimePublic'] ?>
        </div>
        <a title="До категорії «<?= $item['categoryName'] ?>»" href="/category/<?= $item['categoryTranslit'] ?>" class="category pull-right">
            <?= $item['categoryName'] ?>
        </a>
        <div class="clearfix"></div>
        <a href="<?='/'.$newsType.'/'.$item["newsId"]?>" class="title">
            <?=$newsHeader;?>
        </a>
    </div>
</div>