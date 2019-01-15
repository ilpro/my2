<?php
	require_once 'config/iniParse.class.php';
    require_once 'models/NewsTrait.class.php';
	require_once Config::getRoot()."/config/header.inc.php";
header("Content-type: text/xml");

 $conditions = '';
 $connects = [];
 $News= new News();
$result = [];
$category = new Category();

	db::sql("SELECT * FROM `vtbl_newsListClient` WHERE `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 5");
$query_res = db::query();

    foreach($query_res as $news) {

        $news['newsTimeOrigin'] = $news['newsTimePublic'];
        $news['newsTimePublic'] = NewsHelper::getNewDate($news['newsTimePublic']);

        if( in_array('tags',$connects) ) {
            $news['tags'] = $News->getConnected($news['newsId'], ['tags'])['tags'];
        }
        if( in_array('regions',$connects) ) {
            $news['regions'] = $News->getConnected($news['newsId'], ['regions'])['regions'];
        }
        if( in_array('brands',$connects) ) {
            $news['brands'] = $News->getConnected($news['newsId'], ['brands'])['brands'];
        }
        if( in_array('news',$connects) ) {
            $news['connectedNews'] = $News->getConnected($news['newsId'], ['newsClient'])['connectedNews'];
        }
        $news['widgetTag']=$News->getWidgetTag($news['newsId']);
        $cat_tmp = $category->getCategoryById( $news['categoryId'] );
        if( !empty($cat_tmp) ) {
            $news = $news + $cat_tmp;
        }

        $news['images'] = $News->getNewsImgs( $news['newsId'] );

        $result[] = $news;
    }


    echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
                <rss version=\"2.0\" xmlns:content=\"http://purl.org/rss/1.0/modules/content/\">

                <channel>
                  <title>ЄДНІСТЬ | Новини | Оперативно та достовірно про головні події в Україні та світі</title>
                  <link>http://www.ednist.info</link>
                  <description>Висвітлення актуальних новин дня України та світу. Єдність – свіжі новини, аналітика, ексклюзивні коментарі, фото та відео, інфографіка, рейтинги</description>
                  <language>uk-UA</language>";

    if( !empty($result) )
    foreach($result as $material) {
        $itemData = getItemData($material);
        url_gen("http://www.ednist.info/news/".$material['newsId'], $itemData);
    }

    echo "</channel>
                </rss>";


function url_gen($url, $itemArr=[]){

    $search = array('&', '\'', '"', '>', '<');
    $replace = array('&amp;', '&apos;', '&quot;', '&gt;', '&lt;');
    $url = str_replace($search, $replace, $url);

    $res = "
                    <item>\n";

    $iaTop = "<![CDATA[<!doctype html>
                <html lang=\"uk-UA\" prefix=\"op: http://media.facebook.com/op#\">
                <head>
                    <meta charset=\"utf-8\">
                    <link rel=\"canonical\" href=\"".$url."\">
                    <meta content=\"v1.0\" property=\"op:markup_version\">
                </head>
                <body>";
    $iaText = '';
    $iaTitle = '';
    $iaImage = '';

    foreach($itemArr as $key=>$val) {
//        print_r($key."\n");
        if($key == 'description') {
            $res .= "<link>".$url."</link>\n
            <pubDate>{$itemArr['pubDate']}</pubDate>\n
            <{$key}>{$val}</{$key}>\n";
        }
        else if( $key == 'image' ) {
            $iaImage .= "<figure>
                            <img src=\"{$val}\"/>
                            <figcaption>{$itemArr['title']}</figcaption>
                        </figure>\n";
        }
        else if(  $key == 'text'  ){


            if (strlen(get_first_sentence($val)[0]) > 0) {
                $iaText .= "<p>".get_first_sentence($val)[0]."</p>\n";
            }
            if (strlen(get_first_sentence($val)[1]) > 0) {
                $iaText .= "<h2> </h2><p>".get_first_sentence($val)[1]."</p>\n";
            }
        }
        else if(  $key == 'title'  ){
            $res .= "<{$key}>{$val}</{$key}>\n";
            $iaTitle.= '<h1>'.$val.'</h1>';
        }
        else {

        }
    }


        $res .= "<content:encoded>\n
                {$iaTop}
                    <article>
                        <header>
                            {$iaTitle}
                            {$iaImage}
                        </header>
                        {$iaText}
                        <footer>
                        <small>© Ednist</small>
                        </footer>
                    </article>]]>
             </content:encoded>
         </item>\n";
    echo $res;
}


function getItemData( $arr ) {
//    print_r($arr);
    $tags = [];
    if( !empty($arr['tags']) ) {
        foreach ($arr['tags'] as $tag) {
            $tags[] = $tag['tagName'];
        }
    }

    $regions = [];
    if( !empty($arr['regions']) ) {
        foreach ($arr['regions'] as $reg) {
            $regions[] = $reg['regionName'];
        }
    }

    $res['title'] = ($arr['newsHeader']!='') ? xmlText($arr['newsHeader']) : '' ;
    $res['description'] = ($arr['newsSubheader']!='') ? xmlText($arr['newsSubheader']) : '' ;
    $res['text'] = ($arr['newsText']!='') ? xmlText($arr['newsText']) : '' ;
    $res['category'] = ($arr['categoryName']!='') ? xmlText($arr['categoryName']) : '' ;
    $res['pubDate'] = ($arr['newsTimeOrigin']!='') ? date("D, d M Y H:i:s O", strtotime($arr['newsTimeOrigin'])) : '' ;

    $res['tags'] = (!empty($tags)) ? implode(',',$tags) : '' ;
    $res['regions'] = (!empty($regions)) ? implode(',',$regions) : '' ;

//            $res['status'] = ($ini['news.type'][$arr['newsType']]!='') ? $ini['news.type'][$arr['newsType']] : '' ;

    if( isset($arr['images']['main']) ) {
        $res['image'] = $arr['images']['main']['big'];
    }

    return $res;
}

function xmlText($text){
    $text = htmlspecialchars( strip_tags( html_entity_decode($text)));
    preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $text);

    return $text;
}

function get_first_sentence($string) {
    $array = preg_split('/(^.*\w+.*[\.\?!][\s])/', $string, -1, PREG_SPLIT_DELIM_CAPTURE);
    // You might want to count() but I chose not to, just add
//    return trim($array[0] . $array[1]);
    return [$array[0].$array[1],$array[2]];
}

