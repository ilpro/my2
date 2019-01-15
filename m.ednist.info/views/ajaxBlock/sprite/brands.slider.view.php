<?php
$name = explode( ' ', $item['brandName']);
$lName = $name[0];
$fName = $name[1] . ' ' . $name[2];
if (($i % 3) == 0&&$i!=0) {
$block .= '<div class="clearfix"></div>
</li>
<li class="slide">';
    }
$block .= '<a href="/dossier/' . $item['brandId'] . '" class="item pull-left">';
$block .= '<img title="' . $item['brandName'] . '" alt="'.htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES).'" title="'.htmlentities($item['images']['main']['desc'] != ''?$item['images']['main']['desc']:$item['newsHeader'], ENT_QUOTES).'" src="' . $ini['url.media'].'brand/'.$item['brandId'].'/main/240.jpg' . '" class="pull-left">';
$block .= '<div class="description pull-left">
            <div class="l-name">' . $lName . '</div>
            <div class="f-name">' . $fName . '</div>
        </div>
    </a>';
