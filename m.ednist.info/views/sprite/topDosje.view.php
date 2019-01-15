<?
$i++;
if($i%3==1 and $i!=1):
?>
</div><div class="item">
<? endif;?>
<div title='<?=$item['brandName'];?>' class="news-read">
    <div class="shadow">
         <a href="/dosje/<?=$item['brandId'];?>" >
         <img title='<?=$item['brandName'];?>' alt='<?=$item['brandName'];?>' src="<?=$ini['url.media']."brand/".$item['brandId']."/".$item['brandImg'];?>" style="width: 320px;">
         <div class="article-info">
             <div  class="news-title"><?=$item['brandName'];?></div>
         </div>
     </a>
  </div>
</div>