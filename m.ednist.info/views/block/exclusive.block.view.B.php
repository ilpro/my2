<div class="exclusive">
    <a href="/exclusive" title="Ексклюзив!" class="block-head">
            <i class="ico ico-exclusiveBig"></i>
            Ексклюзив!
    </a>
    <?
    if (!empty($page_data['exclusive'])) {
        $item = $page_data['exclusive'][0];
        include Config::getRoot()."/views/sprite/exclusive.big.view.php";
    }
    ?>
    <div class="pull-left small-items">
        <?
        if (!empty($page_data['exclusive'])) {
            $html = '';
            foreach ($page_data['exclusive'] as $i => $item) {
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