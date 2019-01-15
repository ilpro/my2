<?php

$htmlString = '<span class="themes in" data-themes=\'{"id":"' . $themes['themesId'] . '","smile":"' . $themes['themesSmile'] . '"}\'><span>' . $themes['themesName'] . '</span><ul class="smile selected">';

$htmlString .= '<li data-smile=\'1\' class="a1';
if ($themes['themesSmile'] == 1) $htmlString .= ' select';
$htmlString .= '"></li>';

$htmlString .= '<li data-smile=\'0\' class="a2';
if ($themes['themesSmile'] == 0) $htmlString .= ' select';
$htmlString .= '"></li>';

$htmlString .= '<li data-smile=\'2\' class="a3';
if ($themes['themesSmile'] == 2) $htmlString .= ' select';
$htmlString .= '"></li>';

$htmlString .= '</ul></span>';