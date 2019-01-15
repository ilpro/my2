<div class="resonance">
    <a href="/category/resonans" class="block-head">
        <i class="ico ico-resonance"></i>
        Резонанс
    </a>
    <?
    if (!empty($page_data['resonans'])) {
        $item = $page_data['resonans'][0];
        include Config::getRoot()."/views/sprite/exclusive.big.view.php";
    }
    ?>
    <div class="pull-left small-items">
        <?
        if (!empty($page_data['resonans'])) {
            $html = '';
            foreach ($page_data['resonans'] as $i => $item) {
                $i++;
                if($i > 1)
                    include Config::getRoot()."/views/sprite/exclusive.view.php";
            }
            echo $html;
        }
        ?>
    </div>
    <div class="clearfix"></div>
</div>