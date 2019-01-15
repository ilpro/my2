<?php

/**
 * Для AJAX-запросов
 */
if (!$ini) {
    require_once '../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
// Модель текущего ресурса, или других используемых
$news = new News();
$brands = new Brands();
$category = new Category();
$feeds = new ParserNews();
$regions = new Region();
$search = new Search();
$tags = new Tag();

if (isset($_GET['apiVersion']) && $_GET['apiVersion'] == 1) {

//Получения новости для (мобильного апи)
    if (isset($_GET['action']) && $_GET['action'] == 'getNews') {

        $condition = ' WHERE `newsStatus`=4 ';

        if (isset($_GET['category']) && $_GET['category'] != 0) {
            $categ = Clear::dataS($_GET['category']);
            $arr = explode(",", trim($categ));
            if (count($arr) > 1) {
                $condition.=" AND (";
            } else {
                $condition.=" AND ";
            }

            for ($i = 0; $i < count($arr); $i++) {
                $condition.=" `categoryId`=" . $arr[$i];
                if ($i < count($arr) - 1) {
                    $condition.=" OR ";
                }
            }
            if (count($arr) > 1) {
                $condition.=" ) ";
            }
        }
        if (isset($_GET['limitFrom']) && $_GET['limitFrom'] != 0) {
            $limit_from = Clear::dataI($_GET['limitFrom']);
        } else {
            $limit_from = 0;
        }
        if (isset($_GET['limitTo']) && $_GET['limitTo'] != 0) {
            $limit_to = Clear::dataI($_GET['limitTo']);
        } else {
            $limit_to = 25;
        }
        if (isset($_GET['newsId']) && $_GET['newsId'] != 0) {
            $newsId = Clear::dataI($_GET['newsId']);
            $condition.=' AND `newsId`=' . $newsId . ' ';
        }
        $condition.=' ORDER BY `newsTimePublic` DESC LIMIT ' . $limit_from . ',' . $limit_to;
        $items = $news->getNews($condition,['tags','regions','brands','news']);
        if (!empty($items)) {
            $result = [];
            foreach ($items as $new) {
                $result[] = $new;
            }
        } else {
            $result = false;
        }

        echo json_encode(array('result' => $result));
        die();
    }


    if (isset($_GET['action']) && $_GET['action'] == 'getCategories') {
        if (isset($_GET['category']) && ($_GET['category'] != '')) {
            $category_id = Clear::dataI($_GET['category']);
            $category_list = $category->getCategoryById($category_id);
        } else {
            $category_list = $category->getCategories();
        }

        if (!empty($category_list)) {
            $result = $category_list;
        } else {
            $result = false;
        }
        echo json_encode(array('result' => $result));
        die();
    }

    if (isset($_GET['action']) && $_GET['action'] == 'getFind') {
        if (isset($_GET['find']) && $_GET['find'] != '') {
            if (isset($_GET['limitFrom']) && $_GET['limitFrom'] != 0) {
                $limit_from = Clear::dataI($_GET['limitFrom']);
            } else {
                $limit_from = 0;
            }
            if (isset($_GET['limitTo']) && $_GET['limitTo'] != 0) {
                $limit_to = Clear::dataI($_GET['limitTo']);
            } else {
                $limit_to = 10;
            }
            $news_bottom = $search->searchNews($_GET['find'], $limit_from, $limit_to);

            $brand_bottom = $search->searchBrands($_GET['find']);
            $result['news']=$news_bottom;
            $result['brands']=$brand_bottom;
            if(!empty($result['brands'])){
                foreach ($result['brands'] as $key=>$value) {
                    $result['brands'][$key]['imgMainLink']=$ini['url.media']."brand/".$value['brandId']."/main/400.jpg";
                }            
            }
            if(!empty($result['news'])){
                foreach ($result['news'] as $key=>$value) {
                    $result['news'][$key]['imgMain']=$ini['url.media']."images/".$value['newsId']."/main/240.jpg";
                }
            }
            echo json_encode(array('result' => $result));
        }else{
            $result['news']=false;
            $result['brands']=false;
            echo json_encode(array('result' => $result));
        }     
        die();
    }
    
     if (isset($_GET['action']) && $_GET['action'] == 'getTagNews') {
        if (isset($_GET['tagId']) && ($_GET['tagId'] != '')) {
            $tag_id = Clear::dataI($_GET['tagId']);
            $news_list = $news->getNewsByTag($tag_id);


            if (!empty($news_list)) {
                $result = $news_list;
                foreach ($result as $key => $value) {
                    $result[$key]['imgMain'] = $ini['url.media'] . "images/" . $value['newsId'] . "/main/240.jpg";
                }
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }
        echo json_encode(array('result' => $result));
        die();
    }

    if (isset($_GET['action']) && $_GET['action'] == 'getPopularTags') {
        
        $tag_list = $tags->getPopularTags(12);
        if (!empty($tag_list)) {
            $result = $tag_list;
        } else {
            $result = false;
        }
        echo json_encode(array('result' => $result));
        die();
    }
    
    if (isset($_GET['action']) && $_GET['action'] == 'getPopularNews') {
        
        $news_list = $news->getPopularNews();
        if (!empty($news_list)) {
            $result = $news_list;
             foreach ($result as $key=>$value) {
                    $result[$key]['imgMain']=$ini['url.media']."images/".$value['newsId']."/main/240.jpg";
                }
        } else {
            $result = false;
        }
        echo json_encode(array('result' => $result));
        die();
    }
    
       if (isset($_GET['action']) && $_GET['action'] == 'getImportantNews') {
        
        $news_list = $news->getImportantNews(18,3);
        if (!empty($news_list)) {
            $result = $news_list;
             foreach ($result as $key=>$value) {
                    $result[$key]['imgMain']=$ini['url.media']."images/".$value['newsId']."/main/240.jpg";
                }
        } else {
            $result = false;
        }
        echo json_encode(array('result' => $result));
        die();
    }
    
      if (isset($_GET['action']) && $_GET['action'] == 'getArticle') {

        $condition = ' WHERE `newsStatus`=4 AND `newsType`=1 ';

        if (isset($_GET['category']) && $_GET['category'] != 0) {
            $categ = Clear::dataS($_GET['category']);
            $arr = explode(",", trim($categ));
            if (count($arr) > 1) {
                $condition.=" AND (";
            } else {
                $condition.=" AND ";
            }

            for ($i = 0; $i < count($arr); $i++) {
                $condition.=" `categoryId`=" . $arr[$i];
                if ($i < count($arr) - 1) {
                    $condition.=" OR ";
                }
            }
            if (count($arr) > 1) {
                $condition.=" ) ";
            }
        }
        if (isset($_GET['limitFrom']) && $_GET['limitFrom'] != 0) {
            $limit_from = Clear::dataI($_GET['limitFrom']);
        } else {
            $limit_from = 0;
        }
        if (isset($_GET['limitTo']) && $_GET['limitTo'] != 0) {
            $limit_to = Clear::dataI($_GET['limitTo']);
        } else {
            $limit_to = 25;
        }
        if (isset($_GET['articleId']) && $_GET['articleId'] != 0) {
            $newsId = Clear::dataI($_GET['articleId']);
            $condition.=' AND `newsId`=' . $newsId . ' ';
        }
        $condition.=' ORDER BY `newsTimePublic` DESC LIMIT ' . $limit_from . ',' . $limit_to;
        $items = $news->getNews($condition,['tags','regions','brands','news']);
        if (!empty($items)) {
            $result = [];
            foreach ($items as $new) {
                $result[] = $new;
            }
        } else {
            $result = false;
        }
     
        echo json_encode(array('result' => $result));
        die();
    }
    
       if (isset($_GET['action']) && $_GET['action'] == 'getPhoto') {
        
        $news_list = $news->getPhotoNews(33,3);
        if (!empty($news_list)) {
            $result = $news_list;
             foreach ($result as $key=>$value) {
                    $result[$key]['imgMain']=$ini['url.media']."images/".$value['newsId']."/main/240.jpg";
                }
        } else {
            $result = false;
        }

        echo json_encode(array('result' => $result));
        die();
    }
    
     if (isset($_GET['action']) && $_GET['action'] == 'getVideo') {
        
        $news_list =$news->getVideoNews(33,3);
        if (!empty($news_list)) {
            $result = $news_list;
             foreach ($result as $key=>$value) {
                    $result[$key]['imgMain']=$ini['url.media']."images/".$value['newsId']."/main/240.jpg";
                }
        } else {
            $result = false;
        }
        echo json_encode(array('result' => $result));
        die();
    }
    
      if (isset($_GET['action']) && $_GET['action'] == 'getDossier') {

        if (isset($_GET['dossierId']) && $_GET['dossierId'] != 0) {
            $brandId = Clear::dataI($_GET['dossierId']);
            $brand = $brands->getBrandById($brandId, 'client');
            if(!empty($brand)){
                 $brand['imgMainLink']=$ini['url.media']."brand/".$brand['brandId']."/main/400.jpg";
                 $brand['listNews']=$news->getNewsByBrand($brandId);
                 $result=$brand;
            }else{
                $result=false;
            }
        } else {
            $brand_list = $brands->getBrands(' WHERE `brandStatus`=4 ORDER BY `brandSortName` ');
            if (!empty($brand_list)) {
                $result = $brand_list;
                 foreach ($result as $key=>$value) {
                    $result[$key]['imgMainLink']=$ini['url.media']."brand/".$value['brandId']."/main/400.jpg";
                }  
            } else {
                $result = false;
            }
        }
        echo json_encode(array('result' => $result));
        die();
    }
}