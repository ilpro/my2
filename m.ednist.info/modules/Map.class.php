<?php


class Map{

    private static $type = 'sitemap';

    private function __construct() {}

    public static function genxml($page){

        global $ini;
        $accessPagesArr = ['interviews','blogs','public','news','tags','dossier','important','categories','ukrNet','partner'];

        if( strpos($page,'category_')!==false || in_array($page,$accessPagesArr) ) {

            echo "<?xml version=\"1.0\"?>
            <urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";

            if( strpos($page,'category_')!==false )
                self::category($page);
            else
                switch( $page ){
                    case 'categories': self::categories(); break;
                    case 'important': self::important(); break;
                    case 'dossier': self::dossier(); break;
                    case 'tags': self::tags(); break;
                    case 'news': self::news(); break;
                    case 'ukrNet': self::ukrNet(); break;
                    case 'partner': self::partner(); break;
                    case 'public': self::articles(); break;
                    case 'blogs': self::blogs(); break;
                    case 'interviews': self::interviews(); break;
            }

            echo "
            </urlset>";
        }
        else http_response_code(404);
    }

    public static function genRss($act){

        global $ini;
        self::$type = 'rss';

        $sectionName = '';
        if( $act == 'news' ) $sectionName = 'Новини';
        if( $act == 'ukrNet' ) $sectionName = 'Укр.Нет';
        if( $act == 'partner' ) $sectionName = 'Яндекс';
        if( $act == 'categories' ) $sectionName = 'Категорії';
        if( $act == 'important' ) $sectionName = 'Головні новини';
        if( $act == 'dossier' ) $sectionName = 'Досьє';
        if( $act == 'public' ) $sectionName = 'Публікацї';
        if( $act == 'blogs' ) $sectionName = 'Блоги';
        if( $act == 'interviews' ) $sectionName = 'Інтервью';
        if( strpos($act,'category_') !== false ) {

            $category = new Category();
            $cat_translit = explode('_',$act)[1];
            $curr_cat = $category->getCategoryByTranslit($cat_translit);
            $sectionName = 'Категорії: '.$curr_cat['categoryName'];
        }

        if( $sectionName!='' ) {
            if($act == 'partner')
                echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
                <rss xmlns:yandex=\"http://news.yandex.ru\" xmlns:media=\"http://search.yahoo.com/mrss/\" version=\"2.0\">

                <channel>
                  <title>ЄДНІСТЬ | ".$sectionName." | Оперативно та достовірно про головні події в Україні та світі</title>
                  <link>http://www.ednist.info</link>
                  <description>Висвітлення актуальних новин дня України та світу. Єдність – свіжі новини, аналітика, ексклюзивні коментарі, фото та відео, інфографіка, рейтинги</description>
                  <description> Ежедневная иллюстрированная московская общественно-политическая газета.</description>
                    <yandex:logo>http://www.ednist.info/assets/img/logo2.png</yandex:logo>
                    <yandex:logo type='square'>http://www.ednist.info/assets/img/logoSquare.png</yandex:logo>";

            else
            echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
                <rss version=\"2.0\" xmlns:yandex=\"http://news.yandex.ru\">

                <channel>
                  <title>ЄДНІСТЬ | ".$sectionName." | Оперативно та достовірно про головні події в Україні та світі</title>
                  <link>http://www.ednist.info</link>
                  <description>Висвітлення актуальних новин дня України та світу. Єдність – свіжі новини, аналітика, ексклюзивні коментарі, фото та відео, інфографіка, рейтинги</description>";

            if( strpos($act,'category_')!==false )
                self::category($act);
            else
                switch( $act ){
                    case 'categories': self::categories(); break;
                    case 'important': self::important(); break;
                    case 'dossier': self::dossier(); break;
                    case 'news': self::news(); break;
                    case 'ukrNet': self::ukrNet(); break;
                    case 'partner': self::partner(); break;
                    case 'public': self::articles(); break;
                    case 'blogs': self::blogs(); break;
                    case 'interviews': self::interviews(); break;
            }

            echo "</channel>
                </rss>";
        }
        else http_response_code(404);

    }

    public static function xmlText($text){

        $text = htmlspecialchars( strip_tags( html_entity_decode($text)));
        preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $text);

        return $text;
    }

    public static function getItemData( $arr, $type ) {
        global $ini;
        $res = [];

        if( $type == 'material' ) {

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

            $res['title'] = ($arr['newsHeader']!='') ? self::xmlText($arr['newsHeader']) : '' ;
            $res['description'] = ($arr['newsSubheader']!='') ? self::xmlText($arr['newsSubheader']) : '' ;
            $res['text'] = ($arr['newsText']!='') ? self::xmlText($arr['newsText']) : '' ;
            $res['category'] = ($arr['categoryName']!='') ? self::xmlText($arr['categoryName']) : '' ;
            $res['pubDate'] = ($arr['newsTimeOrigin']!='') ? date("D, d M Y H:i:s O", strtotime($arr['newsTimeOrigin'])) : '' ;

            $res['tags'] = (!empty($tags)) ? implode(',',$tags) : '' ;
            $res['regions'] = (!empty($regions)) ? implode(',',$regions) : '' ;

//            $res['status'] = ($ini['news.type'][$arr['newsType']]!='') ? $ini['news.type'][$arr['newsType']] : '' ;

            if( isset($arr['images']['main']) ) {
                $res['image'] = $arr['images']['main']['big'];
            }
        }
        else if( $type == 'brand' ) {
            $res['title'] = ($arr['brandName']!='') ? $arr['brandName'] : '' ;
            $res['description'] = ($arr['brandDesc']!='') ? self::xmlText($arr['brandDesc']) : '' ;
            $res['images'] = (isset($arr['imgs']['orig'])) ? $arr['imgs']['orig'] : '' ;
        }
        else if( $type == 'category' ) {
            $res['title'] = ($arr['categoryName']!='') ? $arr['categoryName'] : '' ;
            $res['description'] = ($arr['categoryDesc']!='') ? self::xmlText($arr['categoryDesc']) : '' ;
        }

        return $res;
    }


    public static function getItemDataPartner( $arr, $type ) {
        global $ini;
        $res = [];

        if( $type == 'material' ) {

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

            $res['title'] = ($arr['newsHeader']!='') ? str_replace(array('(фото)','(відео)','(ФОТО)','(ВІДЕО)','(фото, відео)','(відео, фото)','(ФОТО, ВІДЕО)','(ВІДЕО, ФОТО)'),'',self::xmlText($arr['newsHeader'])) : '' ;
            $res['description'] = ($arr['newsSubheader']!='') ? self::xmlText($arr['newsSubheader']) : '' ;
            $res['text'] = ($arr['newsText']!='') ? explode('Читайте також:', self::xmlText($arr['newsText']))[0] : '' ;
            $res['category'] = ($arr['categoryName']!='') ? self::xmlText($arr['categoryName']) : '' ;
            $res['pubDate'] = ($arr['newsTimeOrigin']!='') ? date("D, d M Y H:i:s O", strtotime($arr['newsTimeOrigin'])) : '' ;

            $res['tags'] = (!empty($tags)) ? implode(',',$tags) : '' ;
            $res['regions'] = (!empty($regions)) ? implode(',',$regions) : '' ;

//            $res['status'] = ($ini['news.type'][$arr['newsType']]!='') ? $ini['news.type'][$arr['newsType']] : '' ;

        }
        else if( $type == 'brand' ) {
            $res['title'] = ($arr['brandName']!='') ? $arr['brandName'] : '' ;
            $res['description'] = ($arr['brandDesc']!='') ? self::xmlText($arr['brandDesc']) : '' ;
            $res['images'] = (isset($arr['imgs']['orig'])) ? $arr['imgs']['orig'] : '' ;
        }
        else if( $type == 'category' ) {
            $res['title'] = ($arr['categoryName']!='') ? $arr['categoryName'] : '' ;
            $res['description'] = ($arr['categoryDesc']!='') ? self::xmlText($arr['categoryDesc']) : '' ;
        }

        return $res;
    }

    public static function category( $page ){

        global $servName;

        $category = new Category();
        $news = new News();

        $cat_translit = explode('_',$page)[1];
        $curr_cat = $category->getCategoryByTranslit($cat_translit);

        $arr = $news->getNewsByCategoryId($curr_cat['categoryId'],'sitemap');

        if( !empty($arr) ) {
            foreach($arr as $material) {

                $type = ($material['newsType']==0) ? 'news' : 'article' ;
                $itemUrl = "http://$servName/$type/".$material['newsId'];
                $itemData = self::getItemData($material,'material');

                self::url_gen($itemUrl, date("Y-m-d"), 'daily', '0.9', $itemData);
            }
        }
    }

    public static function categories(){

        global $servName;

        $category = new Category();
        $arr = $category->getCategories();
        $arr = array_slice($arr,0,99);

        if( !empty($arr) ) {
            foreach($arr as $cat) {
                $itemData = self::getItemData($cat,'category');
                self::url_gen("http://$servName/category/".$cat['categoryTranslit'], date("Y-m-d"), 'daily', '0.9',
                    $itemData);
            }
        }
    }

    public static function important(){

        global $servName;

        $news = new News();
        $arr = $news->getNews(" WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 100 " );

        if( !empty($arr) )
            foreach($arr as $material) {
                $itemData = self::getItemData($material,'material');
                self::url_gen("http://$servName/article/".$material['newsId'], date("Y-m-d"), 'daily', '0.9',
                    $itemData);
            }

    }

    public static function dossier(){

        global $servName;

        $brands = new Brands();
        $arr = $brands->getBrands( ' WHERE `brandStatus`=4 ORDER BY `brandId` DESC LIMIT 100 ' );

        if( !empty($arr) )
            foreach($arr as $brand) {
                $itemData = self::getItemData($brand,'brand');
                self::url_gen("http://$servName/dossier/".$brand['brandId'], date("Y-m-d"), 'daily', '0.9',
                    $itemData);
            }

    }

    public static function tags(){

        global $servName;

        $tags = new Tag();
        $arr = $tags->getTags(100);

        if( !empty($arr) )
            foreach($arr as $tag)
                self::url_gen("http://$servName/tag/".$tag['tagId'], date("Y-m-d"), 'daily', '0.9');

    }

    public static function news(){

        global $servName;

        $news = new News();
        $arr = $news->getNews(' WHERE `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 100 ');

        if( !empty($arr) )
            foreach($arr as $material) {
                $itemData = self::getItemData($material,'material');
                self::url_gen("http://$servName/news/".$material['newsId'], date("Y-m-d"), 'daily', '0.9',
                    $itemData);
            }

    }
    
    public static function ukrNet(){

        global $servName;

        $news = new News();
        $arr = $news->getNews(' WHERE `ukrNet`=1 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 100 ');

        if( !empty($arr) )
            foreach($arr as $material) {
                $newsType=($material['newsType']==0)?'news':'article';
                $itemData = self::getItemData($material,'material');
                self::url_gen("http://$servName/".$newsType."/".$material['newsId'], date("Y-m-d"), 'daily', '0.9',
                    $itemData);
            }

    }

    public static function partner(){

        global $servName;

        $news = new News();
        $arr = $news->getNews(' WHERE `ukrNet`=1 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 100 ');

        if( !empty($arr) )
            foreach($arr as $material) {
                $newsType=($material['newsType']==0)?'news':'article';
                $itemData = self::getItemDataPartner($material,'material');
                self::url_gen("http://$servName/".$newsType."/".$material['newsId'], date("Y-m-d"), 'daily', '0.9',
                    $itemData);
            }

    }

    public static function articles(){

        global $servName;

        $news = new News();
        $arr = $news->getNews(' WHERE `newsType`=1 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 100 ');

        if( !empty($arr) )
            foreach($arr as $material){
                $itemData = self::getItemData($material,'material');
                self::url_gen("http://$servName/article/".$material['newsId'], date("Y-m-d"), 'daily', '0.9',
                    $itemData);
            }

    }

    public static function blogs(){

        global $servName;

        $news = new News();
        $arr = $news->getNews(' WHERE `newsType`=2 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 100 ');

        if( !empty($arr) )
            foreach($arr as $material) {
                $itemData = self::getItemData($material,'material');
                self::url_gen("http://$servName/blog/".$material['newsId'], date("Y-m-d"), 'daily', '0.9',
                    $itemData);
            }

    }


    public static function interviews(){

        global $servName;
        $news = new News();
        $arr = $news->getNews(' WHERE `newsType`=3 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 100 ');

        if( !empty($arr) )
            foreach($arr as $material){
                $itemData = self::getItemData($material,'material');
                self::url_gen("http://$servName/interview/".$material['newsId'], date("Y-m-d"), 'daily', '0.9',
                    $itemData);
            }

    }




    

    private static function url_gen($url, $lastmod = '', $changefreq = '', $priority = '', $itemArr=[]){

        $res = '';

        $search = array('&', '\'', '"', '>', '<');
        $replace = array('&amp;', '&apos;', '&quot;', '&gt;', '&lt;');
        $url = str_replace($search, $replace, $url);

        if( self::$type == 'sitemap' ) {

            $lastmod = (empty($lastmod)) ? '' : '
                <lastmod>'.$lastmod.'</lastmod>';

            $changefreq = (empty($changefreq)) ? '' : '
                <changefreq>'.$changefreq.'</changefreq>';

            $priority = (empty($priority)) ? '' : '
                <priority>'.$priority.'</priority>';

            $res = '
            <url>
                <loc>'.$url.'</loc>'.$lastmod.$changefreq.$priority.'
            </url>';
        }
        else if( self::$type == 'rss' ) {

            $res = "
                    <item>\n";

            foreach($itemArr as $key=>$val) {
                if( $key == 'image' ) {
                    $res .= "<enclosure url='{$val}' />\n";
                    $res .= "<{$key}>
                                <url>{$val}</url>
                                <title>{$itemArr['title']}</title>
                                <link>{$_SERVER['SERVER_NAME']}</link>
                            </{$key}>\n";
                }
                else if(  $key == 'text'  ){
                    $res .= "<yandex:full-text>{$val}</yandex:full-text>\n";
                    $res .= "<{$key}>{$val}</{$key}>\n";
                }
                else {
                    $res .= "<{$key}>{$val}</{$key}>\n";
                }
            }

            $res .= "<link>".$url."</link>
                    </item>\n";
        }

        echo $res;

    }

}