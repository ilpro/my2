<?
$block = '<div class="clearfix"></div>';
$block .= '<div class="resonance">
    <a href="/category/resonans" title="Резонанс" class="block-head">
        <i class="ico ico-resonance"></i>
        Резонанс
    </a>';
    if (!empty($resonans)) {
        $last = 0;
        foreach($resonans as $item)
            $last++;
        $last = $last - 1;
        $item = $resonans[$last];
        include "sprite/exclusive.big.view.php";
    }
$block .= '<div class="pull-left small-items">';
        if (!empty($resonans)) {
            $last = 0;
            foreach($resonans as $item)
                $last++;
            foreach ($resonans as $i => $item) {
                $i++;
                if($i < $last)
                    include "sprite/exclusive.view.php";
            }
        }
$block .= '</div>
    <div class="clearfix"></div>
</div>';