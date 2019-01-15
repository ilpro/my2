<?php
    $block = '<div class="clearfix"></div>';
$block .= '<div class="exclusive">
    <a href="/exclusive" title="Ексклюзив!" class="block-head">
        <i class="ico ico-exclusiveBig"></i>
        Ексклюзив!
    </a>';
    if (!empty($exclusive)) {
        $last = 0;
        foreach($exclusive as $item)
            $last++;
        $last = $last - 1;
        $item = $exclusive[$last];
        include "sprite/exclusive.big.view.php";
    }
$block .= '<div class="pull-left small-items">';
        if (!empty($exclusive)) {
            $last = 0;
            foreach($exclusive as $item)
                $last++;
            foreach ($exclusive as $i => $item) {
                $i++;
                if($i < $last)
                    include "sprite/exclusive.view.php";
            }
        }
$block .= '</div>
    <div class="clearfix"></div>
</div>';