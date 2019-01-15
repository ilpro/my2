<?php

$htmlString = '<span class="brand in" data-brand=\'{"id":"' . $brand['brandId'] . '","smile":"' . $brand['brandSmile'] . '"}\'><span>' . $brand['brandName'] . '</span><ul class="smile selected">';

$htmlString .= '<li data-smile=\'1\' class="a1';
if ($brand['brandSmile'] == 1) $htmlString .= ' select';
$htmlString .= '"></li>';

$htmlString .= '<li data-smile=\'0\' class="a2';
if ($brand['brandSmile'] == 0) $htmlString .= ' select';
$htmlString .= '"></li>';

$htmlString .= '<li data-smile=\'2\' class="a3';
if ($brand['brandSmile'] == 2) $htmlString .= ' select';
$htmlString .= '"></li>';

$htmlString .= '</ul></span>';