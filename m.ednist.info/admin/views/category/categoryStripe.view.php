
<div class="categoryStripe row all cla" data-mkcategory='{"categoryId":"<?= $item['categoryId'] ?>"}'>

    <div class="check">

        <input type="checkbox" id='c<?= $item['categoryId'] ?>' name="myPublication"/>

        <label for='c<?= $item['categoryId'] ?>'><span></span></label>

    </div>


    <div class="avatar go"></div>

    <div class="identifier go"><p><?= $item['categoryId'] ?></p></div>

    <div class="intro go"><p><?= $item['categoryName'] ?></p></div>


    <div class="clear"></div>


</div>