<?php


/**
 * Class NewsTrait - примесь для новостей
 */
trait NewsTrait {

    // таблицы, связки many to many
    protected $ctbl_brand = '`ctbl_brand`';
    protected $ctbl_tags = '`ctbl_tag`';
    protected $ctbl_region = '`ctbl_region`';
    protected $ctbl_news = '`ctbl_connect`';

    // картинки, связка one to many
    protected $ctbl_img = '`ctbl_img`';

    // таблицы сущностей
    protected $tbl_brand = '`tbl_brand`';
    protected $tbl_tags = '`tbl_tag`';
    protected $tbl_region = '`tbl_region`';

    /**
     * Проверка и присвоенние картинок новости по ID
     * @param $news_id
     * @return array
     */
    public function getNewsImgs( $news_id ) {
        global $ini;
        $res = [];

        db::sql("SELECT * FROM " . $this->ctbl_img . " WHERE `newsId`=" . $news_id );
        $tmp_res['imgs'] = db::query();

        if( !empty($tmp_res['imgs']) ) {

            foreach( $tmp_res['imgs'] as $key=>$img ) {

                $res[$key]['desc'] = ($img['imgDesc'] != '') ? $img['imgDesc'] : '';

                $img_name = $img['imgName'];
                $img['main60'] = "images/".$news_id."/main/60.jpg";         // админка - иконки в списке
                $img['main240'] = "images/".$news_id."/main/240.jpg";       // плитка на клиенте
//                $img['main400'] = "images/".$news_id."/main/400.jpg";       // слайдеры, публикации на клиенте
                $img['gallery'] = "images/".$news_id."/gallery/".$img_name; // галерея на клиенте, иконка в редактировании новости
                $img['big'] = "images/".$news_id."/big/".$img_name;         // для клиента, основная страница материала
                $img['raw'] = "images/".$news_id."/raw/".$img_name;         // оригинал, с можно резать картинку для осн. стр.

                foreach ($img  as $img_key => $img_path) {
                    if (FilesHelper::checkServerFile($ini['path.media'] . $img_path)) {

                        $res[$key][$img_key] = $ini['url.media'] . $img_path;

                        if ($img['imgMain'] != 0 && $img['imgName'] == $img_name) {
                            $res['main']['desc'] = $res[$key]['desc'];
                            $res['main'][$img_key] = $ini['url.media'] . $img_path;
                        }
                    }
                }
            }
        }

        return $res;
    }

    /**
     * Получить привязанные к новости: теги, регионы, бренды, новости
     * @param int $newsId
     * @param array $connections
     * @return array
     */
    public function getConnected( $newsId = 0, $connections = [] )
    {
        $res = [];

        if ($newsId != 0) {

            // ТЕГИ
            if (in_array('tags', $connections)) {
                $res['tags'] = [];

                db::sql("SELECT GROUP_CONCAT(tagId SEPARATOR ',') as tagIds FROM " . $this->ctbl_tags . " WHERE `newsId`=" . $newsId);
                $query_res = db::query()[0];

                if ( isset($query_res['tagIds']) && $query_res['tagIds']!= '' ) {
                    db::sql("SELECT * FROM " . $this->tbl_tags . " WHERE `tagId` IN (" . $query_res['tagIds'] . ")");
                    $res['tags'] = db::query();
                }
            }

            // РЕГИОНЫ
            if (in_array('regions', $connections)) {
                $res['regions'] = [];

                db::sql("SELECT GROUP_CONCAT(regionId SEPARATOR ',') as regionIds FROM " . $this->ctbl_region . " WHERE `newsId`=" . $newsId);
                $query_res = db::query()[0];

                if ( isset($query_res['regionIds']) && $query_res['regionIds']!= '' ) {
                    db::sql("SELECT * FROM " . $this->tbl_region . " WHERE `regionId` IN (" . $query_res['regionIds'] . ")");
                    $res['regions'] = db::query();
                }
            }

            // БРЕНДЫ
            if (in_array('brands', $connections)) {
                $res['brands'] = [];

                db::sql("SELECT GROUP_CONCAT(brandId SEPARATOR ',') as brandIds FROM " . $this->ctbl_brand . " WHERE `newsId`=" . $newsId);
                $query_res = db::query()[0];

                if ( isset($query_res['brandIds']) && $query_res['brandIds']!= '' ) {
                    db::sql("SELECT * FROM " . $this->tbl_brand . " WHERE `brandId` IN (" . $query_res['brandIds'] . ")");
                    $res['brands'] = db::query();
                }
            }

            // НОВОСТИ
            if (in_array('newsClient', $connections)) {
                $res['connectedNews'] = [];

                db::sql("SELECT GROUP_CONCAT(newsConnect SEPARATOR ',') as newsIds FROM " . $this->ctbl_news . " WHERE `newsId`=" . $newsId);
                $query_res = db::query()[0];

                if ( isset($query_res['newsIds']) && $query_res['newsIds']!= '' ) {
                    $res = $this->getNews( " WHERE `newsStatus`=4 AND `newsId` IN (" . $query_res['newsIds'] . ") ORDER BY `newsTimePublic` DESC" );
                    $res = $this->newsBlocksRand(3,4,$res);
                    $res['connectedNews'] = $res;
                }
            }
        }

        return $res;
    }

     /**
      * Получить все новости привязанные к ID бренда, ДЛЯ FRONTEND-a!
     * @param $brand_id - ID бренда
     * @return array
     */
    public function getNewsByBrand( $brand_id, $limit=0 ) {

        $result = [];

//        db::sql("SELECT GROUP_CONCAT(newsId SEPARATOR ',') as newsIds FROM ".$this->ctbl_brand." WHERE `brandId`=".$brand_id);
//        $query_res = db::query()[0];
//        if( isset($query_res['newsIds']) && $query_res['newsIds'] != '' ) {
//            $result = $this->getNews( " WHERE `newsType`<2 AND `newsStatus`=4 AND `newsId` IN (".$query_res['newsIds'].") ORDER BY `newsTimePublic` DESC " );
//        }

        $result = $this->getNews(' INNER JOIN ctbl_brand ON ctbl_brand.newsId = vtbl_newsListClient.newsId
             WHERE `newsStatus`=4 AND `newsType`<2 AND `brandId`='.$brand_id.' ORDER BY `newsTimePublic` DESC ');

        if( $limit!=0 && count($result) < $limit+1 ) return false;

        return $result;
    }

    /**
     * Получить все новости привязанные к ID тега, ДЛЯ FRONTEND-a!
     * @param $tag_id
     * @param int $limit
     * @return bool
     */
    public function getNewsByTag( $tag_id, $limit=0 ) {

        $result = [];

//        db::sql('SELECT GROUP_CONCAT(newsId SEPARATOR \',\') as newsIds FROM '.$this->ctbl_tags.' WHERE `tagId`='.$tag_id);
//        $query_res = db::query()[0];
//
//        if( isset($query_res['newsIds']) && $query_res['newsIds'] != '' ) {

            $result = $this->getNews(' INNER JOIN ctbl_tag ON ctbl_tag.newsId = vtbl_newsListClient.newsId
             WHERE `newsStatus`=4 AND `newsType`<2 AND `tagId`='.$tag_id.' ORDER BY `newsTimePublic` DESC ');

//            if (!empty($result))
//                $result = $this->newsBlocksRand(100, 3, $result);
//        }

        if( $limit!='' && count($result) < $limit+1 ) {
            return false;
        }

        return $result;
    }
    
     /**
      * Получить все новости привязанные к ID автора, ДЛЯ FRONTEND-a!
     * @param $author_id - ID автора
     * @return array
     */
    public function getNewsByAuthor( $author_id, $limit=0 ) {

        $result = $this->getNews( " WHERE `newsStatus`=4 AND `authorId`=".$author_id." ORDER BY `newsTimePublic` DESC " );

        if( $limit!=0 && count($result) < $limit+1 ) return false;

        return $result;
    }







}