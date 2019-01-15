<?php //ini_set('error_reporting', E_ALL);

    /**
     * Для AJAX-запросов
     */
    if( !$ini ) {
      require_once '../config/iniParse.class.php';
      require_once Config::getRoot()."/config/header.inc.php";
    }

    // Шаблон для вывода страницы !обязательно
    $layout = 'main';

    // Модель текущего ресурса, или других используемых
    $category = new Category();
    $tags = new Tag();
    $news = new News();
$brands = new Brands();


/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */

if(isset($_POST['action']) and $_POST['action']=='get_more') {
    $current_count = Clear::dataI($_POST['limit_from']);
    $load_count = Clear::dataI($_POST['rows']) * Clear::dataI($_POST['in_row']);

    $res = $news->getNews(' WHERE `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT '.$current_count.','.$load_count);

    if( !empty($res) )
        foreach( $res as $item)
            include "../views/sprite/newsRight.view.php";

    die();
}


/**
 * Обработка $_GET запроса текущей страницы
 */

$static_data = [];
$static_data['categories'] = $category->getCategories();
$static_data['header_tags'] = json_decode(file_get_contents(Config::getRoot().'/populartags.txt'), TRUE);
$page_data['rand_brand'] = $brands->getRandBrand();

$sliders = [];
$sliders['popular_news'] = $news->getPopularNews();
$sliders['important'] = $news->getNews(' WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC  LIMIT 8');