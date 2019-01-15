
<?
foreach($page_data['last_gallery'] as $k=>$item){
?>

    <div class="item<? if($k==0) echo " active";?>">
        <a href="/news/<?=$item['newsId']?>">
<!--            <img src="--><?//=$ini['url.media']?><!--images/--><?//=$item['newsId']?><!--/big/--><?//=$item['newsImg']?><!--" title="--><?//=$item['newsHeader']?><!--" alt="--><?//=$item['newsHeader']?><!--" width="400" />-->
            <img src="<?=$item['images']['main']['big'];?>" title="<?=$item['newsHeader']?>" alt="<?=$item['newsHeader']?>" width="400" />
            <div class="last-new-title">
               <div class="small-date"><?=$item['newsTimePublic']?></div>
               <div class="news-title"><?=$item['newsHeader']?></div>
            </div>
        </a>
    </div>

<?
}