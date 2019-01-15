<?php

/**
 * Работа с новостями
 */
class News {

    use NewsTrait;

    protected $table = '`vtbl_news`';                            // основная вью
    protected $table_list_client = '`vtbl_newsListClient`';     // вью вывода списка клиентских новостей
    protected $table_client = '`vtbl_newsClient`';              // вью вывода одной клиентской новости

    function __construct() {}

    /**
     * Выборка с базы новостей, согласно заданым условиям
     * @param string $conditions
     * @param array $connects
     * @return array|bool
     */
    public function getNews( $conditions = '', $connects = [], $preview=false) {

        $category = new Category();

        $result = [];
        //если preview просмотр то другая вюшка с бд
        if($preview){
            $vtbl='`vtbl_newsListAdmin`';
        }
        else{
            $vtbl=$this->table_list_client;
        }

        db::sql("SELECT * FROM ".$vtbl." ".$conditions);
        $query_res = db::query();

        if( !empty($query_res) && is_array($query_res) )
            foreach($query_res as $news) {

                $news['newsTimeOrigin'] = $news['newsTimePublic'];
                $news['newsTimePublic'] = NewsHelper::getNewDate($news['newsTimePublic']);

                if( in_array('tags',$connects) ) {
                    $news['tags'] = $this->getConnected($news['newsId'], ['tags'])['tags'];
                }
                if( in_array('regions',$connects) ) {
                    $news['regions'] = $this->getConnected($news['newsId'], ['regions'])['regions'];
                }
                if( in_array('brands',$connects) ) {
                    $news['brands'] = $this->getConnected($news['newsId'], ['brands'])['brands'];
                }
                if( in_array('news',$connects) ) {
                    $news['connectedNews'] = $this->getConnected($news['newsId'], ['newsClient'])['connectedNews'];
                }
                $news['widgetTag']=$this->getWidgetTag($news['newsId']);
                $cat_tmp = $category->getCategoryById( $news['categoryId'] );
                if( !empty($cat_tmp) ) {
                    $news = $news + $cat_tmp;
                }

                $news['images'] = $this->getNewsImgs( $news['newsId'] );

                $result[] = $news;
            }
        else {
            return false;
        }

        return $result;
    }

    /**
     * Получить новость по ID
     * @param $id - ID новости
     * @return bool
     */
    public function getNewsById( $id ) {

        $res = $this->getNews( ' WHERE `newsId`='.$id, ['tags','regions','brands','news'])[0];

        if( $res == false || $res['newsStatus']!=4 ) return false;

        $res['next'] = $this->getNews( ' WHERE `newsTimePublic`>\''.$res['newsTimeOrigin'].'\' ORDER BY `newsTimePublic` ASC LIMIT 1 ')[0];
        $res['prev'] = $this->getNews( ' WHERE `newsTimePublic`<\''.$res['newsTimeOrigin'].'\' ORDER BY `newsTimePublic` DESC LIMIT 1 ')[0];
        
        if(strlen($res['newsVideo']) > 0) {
            $res['youtube'] = NewsHelper::getYouTube($res['newsVideo'],'100%');
        }

        if( $res ) return $res;
        else return false;
    }
        /**
     * Получить новость по ID(preview)
     * @param $id - ID новости
     * @return bool
     */
    public function getNewsByIdPreview( $id ) {

        $res = $this->getNews( ' WHERE `newsId`='.$id, ['tags','regions','brands','news'],true)[0];

        if( $res == false) return false;

        $res['next'] = $this->getNews( ' WHERE `newsTimePublic`>\''.$res['newsTimeOrigin'].'\' ORDER BY `newsTimePublic` ASC LIMIT 1 ')[0];
        $res['prev'] = $this->getNews( ' WHERE `newsTimePublic`<\''.$res['newsTimeOrigin'].'\' ORDER BY `newsTimePublic` DESC LIMIT 1 ')[0];

        if(strlen($res['newsVideo']) > 0) {
            $res['youtube'] = NewsHelper::getYouTube($res['newsVideo'],'100%');
        }

        if( $res ) return $res;
        else return false;
    }
    /**
     * Последние новости с видео
     * @param int $limit - сколько новостей вернуть
     * @return array
     */
    public function getLastVideo( $limit=6 ) {

        global $ini;

        $res = $this->getNews(" WHERE `newsIsVideo`=1 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT ".$limit);

        if( !empty($res) ) {
            foreach( $res as $k=>$item ) {
                if( isset($item['images']['main']['big']) ) {
                    $res[$k]['newsImgVideo'] = $item['images']['main']['big'];
                }
                else {
                    $res[$k]['newsImgVideo'] = NewsHelper::getYouTube_src_img($item['newsVideo']);
                }
            }
        }


        return $res;
    }
    
    
    //Получить тег(опроса) по Id новости
 public function getWidgetTag( $newsId ) {

        $result = [];

        db::sql('SELECT * FROM `ctbl_widgettag` WHERE `newsId`='.$newsId);
        $query_res = db::query()[0];
        
        if(isset($query_res)){
           return $query_res['tagId']; 
        }
       

        return false;
    }
    
    /**
     * Последнте новости с галереями
     * @param int $limit - сколько новостей вернуть
     * @return array
     */
    public function getLastGallery( $limit=6 ) {

        $res = $this->getNews(" WHERE `newsIsGallery`=1 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT ".$limit);
        return $res;
    }

    /**
     * Получить массив новостей для вывода в таблицу с 3-мя вариантами отображения
     * @param $rows - количество строк в таблице
     * @param $in_row - количество столбцов в таблице
     * @param int $start - с какой новисти начать выборку
     * @param int $limit - лимит для выборки
     * @return array
     */
    public function getNewsMainBlock( $rows, $in_row, $start=0, $limit=15, $material_type='' ) {

        $result = [];                   // результат
        $jump_over = '-1';              // перепрыгиваем первые 4 новости с отметкой главная

        $addSql = '';
        if( $material_type == 'news' ) $addSql .= ' AND `newsType`=0 ';

        $res = $this->getNews(' WHERE `newsMain`=1 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 4 ');
        if( !empty($res) ) {
            foreach ($res as $tmp)
                $jump_over .= ',' . $tmp['newsId'];
        }
        $jump_over = substr($jump_over,0,-1);

        $res = $this->getNews(" WHERE `newsId` NOT IN(".$jump_over.") AND `newsStatus`=4 ".$addSql." ORDER BY `newsTimePublic` DESC LIMIT ".$start.",".$limit );

        if( !empty($res) )
            $result = $this->newsBlocksRand($rows, $in_row, $res);
        else
            return false;

        return $result;
    }
    
    /**
     * Получить массив новостей для вывода в таблицу с 3-мя вариантами отображения /news
     * @param $rows - количество строк в таблице
     * @param $in_row - количество столбцов в таблице
     * @param int $start - с какой новисти начать выборку
     * @param int $limit - лимит для выборки
     * @return array
     */
    public function getNewsBlock( $rows, $in_row, $start=0, $limit=15 ) {

        $result = [];                   // результат

        $res = $this->getNews(" WHERE `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT ".$start.",".$limit );

        if( !empty($res) )
            $result = $this->newsBlocksRand($rows, $in_row, $res);
        else
            return false;

        return $result;
    }



     /**
     * Получить массив статей для вывода в таблицу с 3-мя вариантами отображения /news
     * @param $rows - количество строк в таблице
     * @param $in_row - количество столбцов в таблице
     * @param int $start - с какой новисти начать выборку
     * @param int $limit - лимит для выборки
     * @return array
     */
    public function getArticleBlock( $rows, $in_row, $start=0, $limit=15 ) {

        $result = [];                   // результат

        $res = $this->getNews(" WHERE `newsType`=1 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT ".$start.",".$limit );

        if( !empty($res) )
            $result = $this->newsBlocksRand($rows, $in_row, $res);
        else
            return false;

        return $result;
    }
     /**
     * Получить массив новостей позначених как видео
     * @return array
     */
    public function getVideoNews($rows, $in_row){

        $res = $this->getNews(" WHERE `newsIsVideo`=1 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC " );

        if( !empty($res) )
            $result = $this->newsBlocksRand($rows, $in_row, $res);
        else
            return false;

        return $result;
    }

   /**
     * Получить массив новостей позначених как фото
     * @return array
     */
    public function getPhotoNews($rows, $in_row){

        $res = $this->getNews(" WHERE `newsIsGallery`=1 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC " );

        if( !empty($res) )
            $result = $this->newsBlocksRand($rows, $in_row, $res);
        else
            return false;

        return $result;
    }

       /**
     * Получить массив новостей позначених как важние
     * @return array
     */
    public function getImportantNews($rows, $in_row){

        $result = [];

        $res = $this->getNews(" WHERE `newsMain`=1 AND `newsType`=0 AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC " );

        if( !empty($res) )
            $result = $this->newsBlocksRand($rows, $in_row, $res);
        else
            return false;

        return $result;
    }


    /**
     * Новости для страницы категории, сразу рандомизированы для плитки 3х3, потом *х4
     * @param $category_id
     * @return array|bool
     */
    public function getNewsByCategoryId($category_id, $type='client'){

        if( $type == 'sitemap' ) {
            $res = $this->getNews(" WHERE `categoryId`=".$category_id." AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC LIMIT 100 " );
            return $res;
        }

        $result = [];

        $res = $this->getNews(" WHERE `categoryId`=".$category_id." AND `newsStatus`=4 ORDER BY `newsTimePublic` DESC " );

        if( !empty($res) ) {

            $result = $res;
        }
        else
            return false;

        return $result;
    }

    /**
     * Новости по транслитирации категории
     * @param $slug
     * @param int $limit
     * @return array|bool
     */
    public function getNewsByTranslit( $slug, $limit = 15 ){
        $res = [];

        $category = new Category();
        $cat = $category->getCategoryByTranslit($slug);
        if( $cat['categoryId'] != 0 ) {
            $res = $this->getNews(' WHERE `newsStatus`=4 AND `categoryId`=\''.$cat['categoryId'].'\' ORDER BY `newsTimePublic` DESC LIMIT '.$limit);
        }

        return $res;
    }

    /*
    * Получить новсти по Id тега
    */
    public function getTagBlockByTagId($tag_id, $start = 0, $limit = 5) {

        $res = [];

        db::sql('SELECT GROUP_CONCAT(newsId SEPARATOR \',\') as newsIds FROM ' . $this->ctbl_tags . ' WHERE `tagId`=' . $tag_id);
        $query_res = db::query()[0];

        if (isset($query_res['newsIds']) && $query_res['newsIds'] != '') {
            $condition = ' WHERE `newsStatus`=4 AND `newsId` IN (' . $query_res['newsIds'] . ') ORDER BY `newsTimePublic` DESC ';
            $res = $this->getNews($condition . " LIMIT " . $start . "," . $limit);
        }
        if (!empty($res))
            return $res;
        else
            return false;
    }

    /**
     * Популярние новости за сегодня
     */
      public function getPopularNews(){
    
          $list_news = $this->getNews(' WHERE (`newsTimePublic` > (now() - interval 1 day)) AND `newsStatus`=4 ORDER BY `countVisits` DESC LIMIT 20 ');
          if(!empty($list_news)){
              foreach ($list_news as $key =>$value) {
                $list_news[$key]['newsHeaderClip']=  NewsHelper::clipTitle($value['newsHeader']);
              }
            return $list_news;
          }else{
              return false;
          }
    }

    /**
     * Рандомизация плиточного вывода новостей (!вынести в модули)
     * @param $rows - строк
     * @param $in_row - колонок
     * @param $news_arr - массив новостей
     * @return array
     */
    public function newsBlocksRand($rows, $in_row, $news_arr){

        $result = [];
        $limit_cells = $rows*$in_row;   // количество доступных клеточек в таблице

        if( !empty($news_arr) ) {

            $filled_cells = 0;      // заполненные клеточки
            $row_cells = 0;         // заполненные в строке клеточки

            foreach($news_arr as $news_item){

                if($filled_cells < $limit_cells){

                    $rand = rand(0,1);     // случайный вариант отображения, на 1 или 2 клетки

                    if( $rand == 0 && $row_cells < $in_row-1 ) {
                        $news_item['variant'] = $rand; // блок c картинкой развернутый
                        $row_cells += 2;
                    }
                    else {
                        $rand = rand(1,2);     // пересчитываем без 0
                        $news_item['variant'] = $rand; // 2 - блок c картинкой свернутый, 1 - блок без картинки
                        $row_cells++;
                    }

                    if( $row_cells == $in_row ) {       // когда полная строчка
                        $filled_cells += $row_cells;    // увеличиваем счетчик клеточек
                        $row_cells = 0;                 // обнуляем когда строка заполнена
                    }

                    $result[] = $news_item;
                }
            }
        }


        return $result;
    }
    
    /*
     * Времений метод для счетчика заходов на новсть(js)
     */
    public function updateSeetargetVisits($type,$newsId){
        if($type=='news'||$type=='article'){
            db::sql("UPDATE `tbl_news` SET `jsVisits`=`jsVisits`+1 WHERE `newsId`=".$newsId);
            $res=db::execute();
         if($res==0){
               db::sql("SELECT `jsVisits` FROM `tbl_news` WHERE `newsId`=".$newsId);
                $query_res = db::query()[0];
                return $query_res;
         }else{
             return false;
         } 
    }
    }


}