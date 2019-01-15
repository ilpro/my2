<?php

$htmlString = '<span class="brand in" data-brand=\'{"id":"' . $brand['brandId'] . '"}\'><span>' . $brand['brandName'] . '</span>';


$htmlString.='<select class="select_connect">';
if ($brand['connectId'] == 0) {
    $htmlString.='<option data-value=0 selected>Нет связи</option>';
} else {
    $htmlString.='<option data-value=0>Нет связи</option>';
}
if (!empty($query_res)) {
    foreach ($query_res as $value) {
        if ($brand['connectId'] == $value['connectId']) {
            $htmlString.='<option data-value="' . $value['connectId'] . '" selected>' . $value['connectName'] . '</option>';
        } else {
            $htmlString.='<option data-value="' . $value['connectId'] . '">' . $value['connectName'] . '</option>';
        }
    }
}
$htmlString.='</select>';
$htmlString .='</span>';
