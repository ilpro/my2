<?php

    /**
     * Для AJAX-запросов
     */
    if( !$ini ) {
        require_once '../config/iniParse.class.php';
        require_once Config::getRoot()."/config/header.inc.php";
    }


    // Шаблон для вывода страницы !обязательно
    $layout = 'line';

    // Модель текущего ресурса, или других используемых
    $tags = new Tag();
    $news = new News();
    $brands = new Brands();


/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */
if(isset($_POST['action']) and $_POST['action']=='get_more') {

    $limit_from = (int)$_POST['limit_from'];
    $limit = (int)$_POST['limit'];
    $tagId = (int)$_POST['category'];
    $newsList = $news->getTagBlockByTagId($tagId,$limit_from,$limit);
    $more = $news->getTagBlockByTagId($tagId,$limit_from+$limit,$limit)!=false ? true : false ;
     /*мой блок*/
    include "../views/block/tagBlock.ajax.php";
     echo json_encode(array('html'=>$html));
     die();
     /* конец мой блок*/
    include "../views/block/exclusiveBlock.php.view.php";

    $show = rand(0,1);
    if($show != 0){
        $n = rand(1,6);
        switch($n){
            case 1:{
                $exclusive = $news->getNews(' WHERE `newsStatus`=4 AND `newsExclusive`=1 ORDER BY `newsTimePublic` DESC LIMIT 4 ');
                include "../views/ajaxBlock/exclusive.ajax.php";
                break;
            }
            case 2:{
                $resonans = $news->getNews(' WHERE `newsStatus`=4 AND `categoryId`=15 ORDER BY `newsTimePublic` DESC LIMIT 4');
                include "../views/ajaxBlock/resonans.ajax.php";
                break;
            }
            case 3:{
                include "../views/ajaxBlock/banner.ajax.php";
                break;
            }
            case 4:{
                $infographics = $news->getNewsByTranslit('infografika',2);
                include "../views/ajaxBlock/infographic.ajax.php";
                break;
            }
            case 5:{
                include "../views/ajaxBlock/blogs.ajax.php";
                break;
            }
            case 6:{
                $dossier = $brands->getBrands( ' ORDER BY `brandId` DESC LIMIT 3' );
                include "../views/ajaxBlock/dossier.ajax.php";
                break;
            }
        }
    }

    $res = $block . $html;
    echo json_encode(array('html'=>$res,'more'=>$more));
    die();
}


/**
 * Обработка $_GET запроса текущей страницы
 */

$page_data = [];
$page_data['tag'] = $tags->getTagById( $id );
$page_data['rand_brand'] = $brands->getRandBrand();
$page_data['news_bottom'] = $news->getTagBlockByTagId($id,0,15);
$page_data['last_news'] = $news->getNews(' WHERE `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 15 ');
$page_data['popular_news'] = $news->getPopularNews();

$sliders = [];
$sliders['popular_news'] = $news->getPopularNews();
$sliders['important'] = $news->getNews(' WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC  LIMIT 8');