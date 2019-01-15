
<div class="row flavor all adv item<?= $item['sourceId']; ?>">

    <div class="check">


    </div>


    <div class="link-src"><input type="text" value="<?= $item['sourceLink']; ?>" disabled
                                 placeholder="Ссылка на источник"></div>

    <div class="pull-right">

        <div class="check on-off">

            <input onchange="changeStatus(<?= $item['sourceId']; ?>);"
                   type="checkbox"<?= ($item['sourceStatus'] == 1) ? " checked" : "" ?>  id="s<?= $item['sourceId']; ?>"
                   name="sw<?= $item['sourceId']; ?>"/>

            <label for="s<?= $item['sourceId']; ?>"><span></span></label>

        </div>


        <div class="edit"><a href="javascript: editsrc(<?= $item['sourceId']; ?>);"><i></i></a></div>

        <div class="delete"><a href="javascript: deletesrc(<?= $item['sourceId']; ?>);"><i></i></a></div>

    </div>


    <div class="clear"></div>

</div>