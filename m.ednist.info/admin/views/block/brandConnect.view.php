<span class="brand"
      data-brand='{"id":"<?= $brand['brandId']; ?>"}'><span><?= $brand['brandName']; ?></span>

    
<select class="select_connect">
    <? if (!empty($query_res)) { ?>
        <option>Нет связи</option>
        <? foreach ($query_res as $value) { ?>
            <option data-value="<?=$value['connectId']?>"><?=$value['connectName']?></option>

        <? }
    }
    ?>

</select>
</span>
