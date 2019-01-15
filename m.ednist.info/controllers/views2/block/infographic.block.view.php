<div id="slideshow1" class="infographics news-slider">
    <div class="block-head">
        <div class="slider-nav pull-left">
            <div class="arrow prev">
                <i class="ico ico-arrowPrev"></i>
            </div>
            <div class="sep">
                |
            </div>
            <div class="arrow next">
                <i class="ico ico-arrowNext"></i>
            </div>
        </div>
        <a href="/category/infografika" title="Інфографіка" class="pull-left">
            <i class="ico ico-infographics"></i>
            Інфографіка
        </a>
        <div class="clearfix"></div>
    </div>
    <ul class="slides">
        <?
        if (!empty($page_data['infografics'])) {
            $i = 0;
            echo '<li class="slide">';
            foreach ($page_data['infografics'] as $item) {
                include Config::getRoot()."/views/sprite/news.slider.view.php";
                $i++;
            }
            echo '</li>';
        }
        ?>
    </ul>
    <div class="clearfix"></div>
</div>