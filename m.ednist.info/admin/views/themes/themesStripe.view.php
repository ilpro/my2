<div class="themesStripe row all cla" data-mkthemes='{"themesId":"<?= $item['themesId'] ?>"}'>

    <div class="check">

        <input type="checkbox" id='b<?= $item['themesId'] ?>'/>

        <label for='b<?= $item['themesId'] ?>'><span></span></label>

    </div>



    <div class="avatar go">
        <img src="<?= (isset($item['images']['main']['main60'])) ? $item['images']['main']['main60'] : ''; ?>" alt=""/>
    </div>
    <div class="identifier go"><p><?= $item['themesId'] ?></p></div>

    <div class="intro go"><p><?= $item['themesName'] ?></p></div>

    <div class="clear"></div>


</div>