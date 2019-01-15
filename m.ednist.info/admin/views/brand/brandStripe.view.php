<div class="brandStripe row all cla" data-mkbrand='{"brandId":"<?= $item['brandId'] ?>"}'>

    <div class="check">

        <input type="checkbox" id='b<?= $item['brandId'] ?>'/>

        <label for='b<?= $item['brandId'] ?>'><span></span></label>

    </div>



    <div class="avatar go">
        <img src="<?= (isset($item['images']['main']['main60'])) ? $item['images']['main']['main60'] : ''; ?>" alt=""/>
    </div>
    <div class="identifier go"><p><?= $item['brandId'] ?></p></div>

    <div class="intro go"><p><?= $item['brandName'] ?></p></div>

    <div class="clear"></div>


</div>