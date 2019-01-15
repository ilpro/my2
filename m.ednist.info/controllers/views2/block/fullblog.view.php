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
<!--a href="/important" class="oclick"></a-->
<div class="article-slider">

    <!--кнопки гортання новин-->
    <!--кнопки гортання новин-->
    <? if(isset($item['prev'])):?>
        <a href="/blog/<?=$item['prev']['newsId']?>" class="news-prev" >
            <div class="right-arrow"></div>
            <div class="news-button"><?=$item['prev']['newsTimePublic']?></div>
            <div class="news-button"><?=$item['prev']['newsHeader']?></div>
        </a>
    <? endif;?>
    <? if(isset($item['next'])):?>
        <a href="/blog/<?=$item['next']['newsId']?>" class="news-next">
            <div class="left-arrow"></div>
            <div class="news-button"><?=$item['next']['newsTimePublic']?></div>
            <div class="news-button"><?=$item['next']['newsHeader']?></div>
        </a>
    <? endif;?>
</div>



<div class="small-category">
<a href="/blog"><span itemprop="articleSection">Блог</span></a>
</div>

<div  itemprop="datePublished" content="<?=$item['newsTimePublic']?>" class="small-date"><?=$item['newsTimePublic']?></div>
<h1 itemprop="name" class="title"><?=$item['newsHeader']?></h1>
<h2 class="subtitle"><?=$item['newsSubheader']?></h2>
    <div class="thems">
     <?//
     if(is_array($item['tags'])) {
         foreach ($item['tags'] as $tag) {
             echo "<div class=\"them\"><a href=\"/tag/" . $tag['tagId'] . "\">$tag[tagName]</a></div>";
         }
     }
     ?>
     <div style="clear: both;"></div>    
    </div>

    <? if(isset($item['images']['main'])):?>
        <div class="main-img-block">
            <a href="<?=$item['images']['main']['raw'];?>" data-caption="<?=$item['newsHeader']?>">
                <img itemprop="image" class="mainimg" src="<?=$item['images']['main']['big'];?>" alt="<?=$item['newsHeader']?>">
            </a>
        </div>
    <? endif;?>
	
	<span itemprop="articleBody"><?= html_entity_decode($item['newsText']) ?></span>

<?
if(isset($item['youtube'])):
    echo $item['youtube'];
 endif;
?>


<? if(count($item['images'])>2): ?>
    <div class="gallery">
        <? foreach($item['images'] as $k=>$img) {
            if($k==='main') continue; /// пофіксити в моделі
        ?>
            <a class='galleryImg' href="<?=$img['raw'];?>" data-caption="<?=$img['desc'];?>">
                <img alt="<?=$img['desc'];?>" title="<?=$img['desc'];?>" src="<?=$img['gallery'];?>">
            </a>
        <? } ?>
    </div>
<? endif; ?>

 <div class="clear" />    
