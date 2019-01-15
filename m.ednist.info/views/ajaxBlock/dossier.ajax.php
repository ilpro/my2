<?
$block = '<div class="clearfix"></div>';
$block .= '<div id="slideshow2" class="dossier news-slider">';
$block .= '<div class="block-head">';
$block .= '<a href="/dossier" title="Досьє" class="pull-left">
            <i class="ico ico-dossier"></i> Досьє
            </a>';
$block .= '<div class="clearfix"></div>
    </div>';
    $block .= '<ul class="slides">';
        if (!empty($dossier)){
            $i = 0;
            $block .= '<li class="slide">';
            foreach ($dossier as $item) {
                if ($item['brandStatus'] == 4) {
                    include "sprite/brands.slider.view.php";
                    $i++;
                }
            }
        }
$block .= '</li>';
$block .= '</ul>
<div class="clearfix"></div>
</div>';