<div class="newsStripe row all cla" style="width:100%" id="newsStripe<?= $item['newsId'] ?>" data-newsId="<?= $item['newsId'] ?>" data-mknews='{"newsId":"<?= $item['newsId'] ?>","userId":"<?= $item['userId'] ?>","newsType":"<?= $item['newsType'] ?>"}'>

    <div class="check">
        <input type="checkbox" id='c<?= $item['newsId'] ?>'/>
        <label for='c<?= $item['newsId'] ?>'><span></span></label>
    </div>
    <div class="avatar go">
        <img src="<?= (isset($item['images']['main'])) ? $item['images']['main']['big'] : '' ; ?>" alt=""/>
    </div>
    
   

    <div class="identifier go">
        <p><?= $item['newsId'] ?></p>
    </div>

    <div class="intro go">
        <p><?= $item['newsHeader'] ?></p>
    </div>

    <div class="date go">
<!--        <p>--><?//= NewsHelper::getNewDateAdmin($item['newsTimePublic']) ?><!--</p>-->
        <p><?= $item['newsTimePublic'] ?></p>
    </div>
    <div class="user go">
        <p>
            <p><?= $item['userName'] ?></p>
        </p>
    </div>

    <div class="web go">
            <p><? echo 'Є:'.$item['countVisits'].' ('.$item['jsVisits'].')' ?></p>
            <p class="s_statistics">S:0</p>
            <p class="f_statistics">F:0</p>

    </div>
<!--
-->

    <div class="theme go">
        <p><?= $item['categoryName'] ?></p>
    </div>

    <div class="icon-type go">
        <span class="typenew<?= $item['newsType'] ?>"><i></i></span>
    </div>

    <div class="icon-status go">
        <span class="status<?= $item['newsStatus'] ?>" title="<?= $ini['news.status'][$item['newsStatus']] ?>"></span>
    </div>
    <div class="clear"></div>
</div>