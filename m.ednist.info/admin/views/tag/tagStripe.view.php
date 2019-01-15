

<div class="tagStripe row all cla" data-mktag='{"tagId":"<?= $item['tagId'] ?>"}'>

    <div class="check">

        <input type="checkbox" id='b<?= $item['tagId'] ?>'/>

        <label for='b<?= $item['tagId'] ?>'><span></span></label>

    </div>


    <div class="avatar go"></div>

    <div class="identifier go"><p><?= $item['tagId'] ?></p></div>

    <div class="intro go"><p><?= $item['tagName'] ?></p></div>

    <div class="intro go"><p><?= $item['tagSearch'] ?></p></div>


    <div class="clear"></div>


</div>