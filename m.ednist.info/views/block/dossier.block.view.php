<div id="slideshow2" class="dossier news-slider">
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
        <a href="/dossier" title="Досье" class="pull-left">
            <i class="ico ico-dossier"></i>
            Досьє
        </a>
        <div class="clearfix"></div>
    </div>
    <ul class="slides">
        <?
        if (!empty($page_data['brands']))
            $i = 0;
        echo '<li class="slide">';
        foreach ($page_data['brands'] as $item) {
            if ($item['brandStatus'] == 4) {
                include Config::getRoot()."/views/sprite/brands.slider.view.php";
                $i++;
            }
        }
        echo '</li>';
        ?>
    </ul>
    <div class="clearfix"></div>
</div>