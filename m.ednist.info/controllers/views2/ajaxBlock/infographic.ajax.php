<?
$block = '<div class="clearfix"></div>';
$block .= '<div id="slideshow1" class="infographics news-slider">
    <div class="block-head">
        <a href="/category/infografika" title="Інфографіка" class="pull-left">
            <i class="ico ico-infographics"></i>
            Інфографіка
        </a>
        <div class="clearfix"></div>
    </div>
    <ul class="slides">';
        if (!empty($infographics)) {
            $i = 0;
            $block .= '<li class="slide">';
            foreach ($infographics as $item) {
                include "sprite/news.slider.view.php";
                $i++;
            }
            $block .= '</li>';
        }
    $block .= '</ul>
    <div class="clearfix"></div>
</div>';