<?php

/**
 * Class ParserNews
 * Парсинг в новости с rss-лент, работа с rss-лентами для новостей
 */

class ParserNews extends Parser {

    private $table = '`ctbl_feed`';
    private $table_news = '`tbl_news`';

    /**
     * Добавление нового источника
     * @param $link
     * @return bool
     */

    public function addNewFeed( $link ) {

        $link = trim($link);

        if( $this->checkFeed( $link ) === false )
            return false;

        $feedFieldsArr = $this->getFeedFieldsByLink( $link );

        $feedFields = serialize( $feedFieldsArr );

        db::sql("CALL addNewFeed(_1,_2)");
        $params=array($link, $feedFields);
        db::addParameters($params);
        $result=db::execute();

        if( $result ) {
            echo json_encode(['saved'=>date("H:i d.m.y",time())]);
            return true;
        }
        else return false;

    }

    /**
     * Удаляем источник
     * @param $feed_id
     */

    public function deleteFeed( $feed_id ) {

        db::sql("DELETE FROM $this->table WHERE feedId =".$feed_id);
        db::execute();

    }

    /**
     * Отключаем источник
     * @param $hide
     * @param $feed_id
     */

    public function hideFeed( $hide, $feed_id ) {

        $result=db::sql("UPDATE $this->table SET `feedHide`=".$hide." WHERE `feedId`=".$feed_id);
        $result=db::execute();

    }

    /**
     * Сохраняет источник
     * @param $array
     * @param $feed_id
     * @return bool
     */

    public function saveFeed( $array, $feed_id ) {

        $update_args = array();

        if( isset( $array['feedNewsFields'] ) ) {
            $array['feedNewsFields'] = serialize($array['feedNewsFields']);
            $update_args[] = " `feedNewsFields`='".$array['feedNewsFields']."' ";
        }

        if( isset( $array['feedNewsSelectors'] ) ) {
            $array['feedNewsSelectors'] = serialize($array['feedNewsSelectors']);
            $update_args[] = " `feedNewsSelectors`='".$array['feedNewsSelectors']."' ";
        }
        else $update_args[] = " `feedNewsSelectors`='' ";

        $update_str = implode(',',$update_args);


        if( $update_str != '' ) {
            $result=db::sql("UPDATE $this->table SET $update_str WHERE `feedId`=".$feed_id);
            $result=db::execute();
            return true;
        }

        return false;
    }


    /**
     * Получаем источник по ID с баззы
     * @param $feed_id
     * @return mixed
     */

    public function getFeed( $feed_id ) {

        $result=db::sql("SELECT * FROM $this->table WHERE `feedId`=".$feed_id);
        $result=db::query();

        $result[0]['feedFields'] = unserialize($result[0]['feedFields']);
        $result[0]['feedNewsFields'] = unserialize($result[0]['feedNewsFields']);
        $result[0]['feedNewsSelectors'] = unserialize($result[0]['feedNewsSelectors']);

        return $result[0];
    }

    /**
     * Получаем все источники
     * @return array|bool|string|void
     */

    public function getFeedsList() {

        $result=db::sql("SELECT * FROM $this->table ORDER BY `feedId` DESC");
        $result=db::query();

        foreach( $result as $key=>$feed ){
            $result[$key]['feedFields'] = unserialize($feed['feedFields']);
            $result[$key]['feedNewsFields'] = unserialize($feed['feedNewsFields']);
            $result[$key]['feedNewsSelectors'] = unserialize($feed['feedNewsSelectors']);
        }

        return $result;
    }

    /**
     * Обновление полей источника
     * @param $feedId
     * @return bool
     */

    public function refreshFeedFields( $feedId ) {

        $curr_feed = $this->getFeed($feedId);
        $new_fields = serialize($this->getFeedFieldsByLink($curr_feed['feedLink']));

        $result=db::sql("UPDATE $this->table SET `feedFields`='".$new_fields."' WHERE `feedId`=".$feedId);
        $result=db::execute();

        if($result==true) return true;
        else return false;
    }

    /**
     * Сохраняем спарсенную новость с задаными параметрами
     * @param $newsArr
     * @return string
     */

    private function insertNews( $newsArr ) {

        $update_arr = array();
        foreach($newsArr as $field=>$val) {
            $val = addslashes($val);
            $update_arr[] = " `{$field}`='{$val}' ";
        }
        $update_str = implode(',', $update_arr );

        db::sql("INSERT INTO $this->table_news SET $update_str , `newsStatus`=0");
        $news_id=db::execute();

        if( isset($newsArr['newsImg']) && (int)$news_id ) {

            $img_id = NewsUpload::linkNewsImgs( $newsArr['newsImg'], $news_id, 'id' );
            NewsUpload::mainNewsImgs( $img_id );
        }

        return $news_id;
    }

    /**
     * Парсим и сохраняем в базу элементы источников
     */

    public function parseNews( $check_time_arr ) {

        $feeds = $this->getFeedsList();

        if( empty( $feeds ) ) return false;

        foreach( $feeds as $feed ) {

            if( empty($feed['feedNewsFields']) || $feed['feedHide'] == 1 ) continue;

            $feedItems = $this->parseFeed(
                $feed['feedLink'],
                $feed['feedNewsFields'],
                $feed['feedNewsSelectors'],
                $check_time_arr
            );

            foreach( $feedItems as $feedItem ) {
                $this->insertNews($feedItem);
            }
        }

        return true;
    }

    /**
     * Удаляем с базы спарсенные новости
     */

    public function deleteParsedNews() {

        db::sql("delete from $this->table_news where `newsStatus`=0");
        $result=db::execute();

        return $result;
    }

}
