<?php

/**
 * Работа с новостями в админке
 */
class NewsAdmin {

    use NewsTrait;

    protected $table = '`vtbl_news`';
    protected $table_light = '`vtbl_newsListAdmin`';
    protected $table_table = '`tbl_news`';

    function __construct() {}

    /**
     * Выборка с базы новостей, согласно заданым условиям
     * @param string $conditions - условия для запроса
     * @return array - массив
     */
     public function getNewsAdmin($conditions = '', $connects = [] ) {

        $result = [];

        db::sql("SELECT * FROM ".$this->table_light." ".$conditions);
        $query_res = db::query();

        if( !empty($query_res) && is_array($query_res) )
            foreach($query_res as $news) {
                $news['newsTime'] = NewsHelper::getNewDateAdmin($news['newsTime']);
                $news['newsTimePublic'] = NewsHelper::getNewDateAdmin($news['newsTimePublic']);
                $news['newsTimeUpdate'] = NewsHelper::getNewDateAdmin($news['newsTimeUpdate']);
                $news['images'] = $this->getNewsImgs( $news['newsId'] );

                $result[] = $news;
            }
        else return false;

        return $result;
    }
        
    /*
     * Виборка статистики просмотров новостей
     */
       public function getNewsAdminStatistics($conditions = '') {

        $result = [];

        db::sql("SELECT COUNT(*) AS countNews,SUM(`countVisits`) AS countVisits FROM ".$this->table_light." ".$conditions);
        $query_res = db::query();

        if( !empty($query_res) && is_array($query_res) ){
            return $query_res;
        }else{
            return false;
        }


    }

    //добавления новой новсти
    public function addNewNews() {
        $userId = $_SESSION['user']['id'];
        db::sql("CALL insertNew($userId)");
        $result=db::query();
        if(!isset($result[0]['saved']))
            return ['error'=>'error'];
        else
            return $result[0]['id'];
    }
    
    /*
     * Получить новость по Id в админке
     */
    public function getNewsByIdAdmin( $id ) {

        $res = $this->getNewsAdmin( ' WHERE `newsId`='.$id )[0];

        if(strlen($res['newsVideo']) > 0) {
            $res['youtube'] = NewsHelper::getYouTube($res['newsVideo'],'100%');
        }

        if( $res ) return $res;
        else return false;
    }

    /*
     * Удалить новость(массив новостей/1 новость)
     */
    public function deleteNews($arr) {
         $userId = $_SESSION['user']['id'];
         $adminRole = $_SESSION['user']['role'];
        foreach ($arr as $newsId) {
            $someNews = $this->getNewsByIdAdmin($newsId);
            if ($adminRole == 1 || $someNews['userId'] == $userId) {
                db::sql("CALL newsDel(_1)");
                $params = array($newsId);
                db::addParameters($params);
                $result = db::query();

                if (is_array($result)){
                    return false;
                }
            }
            else{
                return false;
            }
        }
        return true;
    }

    /*
     //Проверка чи ктото уже редактирует ету новость(не чорновик)
    public function isUserInNews($userId,$newsId){
        db::sql("SELECT * FROM ctbl_usereditnews WHERE `userId`!=".$userId. " AND `newsId`=".$newsId." AND `userId`!=0");
        $query_res = db::query();
        if($query_res){
            //если ктото уже редактирует ету новость
            return true;
        }
        else {
            return false;
        }
    }
    //Витягиваем имя юзера, что сейчас редактирует ету новость(не чорновик)
    public function getUsernameInNews($newsId){
        db::sql("SELECT * FROM ctbl_usereditnews WHERE `newsId`=".$newsId);
        $query_res = db::query();
        if($query_res){
            $user=new User();
            $result=$user->getUsers('WHERE `userId`='.$query_res[0]['userId']);
            return $result[0]['userName'];
        }
        else {
            return false;
        }
    }
    
     //заносим значения юзера и новости в бд(если новость не редактируетса другим редактором)
    public function adminUserEditNews($userId,$newsId) {
        $newsId=Clear::dataI($newsId);
        $date = date('Y-m-d H:i:s');
        db::sql("CALL adminUserEditNews(_1,_2,_3)");
        $params = array($userId,$newsId,$date);
        db::addParameters($params);
        $result=db::execute();
        return $result;
    }
    
     // после завершение редактирования обнуляем userId(не чорновик)
    public function adminUserEditNewsClear($newsId) {
        $newsId = Clear::dataI($newsId);
        db::sql("UPDATE ctbl_usereditnews SET userId=0 WHERE newsId =".$newsId);
        $result = db::execute();
        return $result;
    }
    */
    
    //Проверка чи ктото уже редактирует ету новость
    public function isNewsEdit($userId,$newsId){
        db::sql("SELECT * FROM ctbl_autosavenews WHERE `userId`!=".$userId. " AND `newsId`=".$newsId." AND `userId`!=0");
        $query_res = db::query();

        if($query_res){
            //если ктото уже редактирует ету новость, но не делал автосейв на протяжении 15хв, то ево ід зануляем и впускаем другово редактора
           $date = strtotime(date('Y-m-d H:i:s'));
           $date_db = strtotime($query_res[0]['autosaveTime'])+900;
           if($date>$date_db){
             $newsId = Clear::dataI($newsId);
             db::sql("UPDATE ctbl_autosavenews SET userId=0 WHERE newsId =".$newsId);
             db::execute();  
             return false;
           }
            return true;
        }
        else {
            return false;
        }
    }
    
    //Проверка чи ктото уже редактирует ету новость(тоже для червновика)
    public function getUserAutosave($newsId){
        db::sql("SELECT * FROM ctbl_autosavenews WHERE `newsId`=".$newsId);
        $query_res = db::query();
        if($query_res){
            $user=new User();
            $result=$user->getUsers('WHERE `userId`='.$query_res[0]['userId']);
            return $result[0]['userName'];
        }
        else {
            return false;
        }
    }
    
    //autosave каждие 20 сек
    public function autoSaveNews($userId,$newsId,$data) {
        $newstext = $data['newstext'];
        $newsHeader=$data['newsHeader'];
        $newsSubheader=$data['newsSubheader'];
        $newsId=Clear::dataI($newsId);
        $date = date('Y-m-d H:i:s');
        db::sql("CALL adminAutoSave(_1,_2,_3,_4,_5,_6)");
        $params = array($userId,$newsId, $newstext,$newsHeader,$newsSubheader,$date);
        db::addParameters($params);
        $result=db::execute();
        return $result;
    }

    //autosave обновления после завершение редактирования обнуляем userId
    public function autoSaveNewsClear($newsId) {
        $newsId = Clear::dataI($newsId);
        db::sql("UPDATE ctbl_autosavenews SET userId=0 WHERE newsId =".$newsId);
        $result = db::execute();
        return $result;
    }

    //получить новость с чорновика
    public function getAutoSaveNews($id){
        $id=Clear::dataI($id);
        db::sql("SELECT * FROM `ctbl_autosavenews` WHERE `newsId`=".$id)[0];
        $query_res = db::query();
        if(!empty($query_res) && is_array($query_res) ){
            return $query_res;
        }
        else{
            return false;
        }
    }

    //получить текст новости с чорновика
    public  function getNewsTextFromNotepad($id){
        return $this->getAutoSaveNews($id);
    }
    
    //удаляем текст новости с чорновика
    public function deleteTextAutoSaveNews($id) {
        $newsId=Clear::dataI($id);
        db::sql("UPDATE ctbl_autosavenews SET `newstext`='',`newsHeader`='',`newsSubheader`='' WHERE newsId='$newsId'");
        $result=db::execute();
        if($result!=null){
            return true;
        }
        else{
            return false;
        }
    }
    
    //получить текст новости с бази даних
    public function getNewsTextFromDB($id){
        $id=Clear::dataI($id);
        db::sql("SELECT * FROM `vtbl_news` WHERE `newsId`=".$id)[0];
        $query_res = db::query();
        if( !empty($query_res) && is_array($query_res) )
            return $query_res;
        else
            return false;
    }
   
    //статистика- чи кто с редакторов есть в новости
    public function setInformer($userId,$newsId,$newsName){
        $users=new User();
        $redactor=$users->getUserById($userId);
        $date = date('Y-m-d H:i:s');
        if(isset($redactor)&&$newsId!=NULL&&$newsName!=NULL){
              db::sql("CALL redactorInNews(_1,_2,_3,_4)");
               $params = array(Clear::dataI($userId),
                                Clear::dataI($newsId),
                                Clear::dataS($newsName),
                                $date);
               db::addParameters($params);
              $result = db::execute();
        }
    }
    
    //удаляем редактора с новости
    public function delInformer($userId,$newsId){
         db::sql("CALL delRedactorInNews(_1,_2)");
               $params = array(Clear::dataI($userId),
                                Clear::dataI($newsId));
               db::addParameters($params);
               $result = db::execute();
    }
    
 //получаем новости на которих находитса редактор(конкретний юзер)
    public function getInformer($userId){
         db::sql("SELECT * FROM `ctbl_redactorinnews` WHERE (`updateTime` > (now() - interval 20 SECOND)) AND `userId`=".$userId);
         $query_res = db::query();
         if(!empty($query_res)){
             return $query_res;
         }else{
             return false;
         }
    }
    
        //получаем новости на которих находитса юзер(асинхронка)
        //если юзер на новости, то оновляем время
    public function updateInformer($userId,$newsId){
          db::sql("UPDATE `ctbl_redactorinnews` SET `updateTime`=now() WHERE `newsId`=".$newsId.' AND `userId`='.$userId);
            $res=db::execute();
         if($res==0){
             return true;
         }else{
             return false;
         }
    }

    //Сохранение внутри редактирования новсти(дискетка)
    public function saveAll( $newsData, $newsId){

        if( is_string($newsData) ) {
            $newsData = json_decode($newsData);
            foreach( $newsData as $key=>$val ) {
                $newsData[$key] = (array)$val;
            }
        }

        $search = new Search();
        $isGallery='';$isVideo='';
        $newsDataArr = [];
        foreach( $newsData as $dataItem )
            $newsDataArr[$dataItem['name']] = $dataItem['value'];
        //проверяем чи активние чекбокси, и передаем значенния
        if(isset($newsDataArr['newsIsGallery'])){
            $isGallery='галерея фото';
        }
        if(isset($newsDataArr['newsIsVideo'])){
            $isVideo='відео видео';
        }
        $date = date('Y-m-d H:i:s',strtotime( Clear::dataS($newsDataArr['newsTimePublic']) ) );
        $search = $search->getSearchText( Clear::dataI($newsId),$isVideo,$isGallery);

		$newsDataArr['newsHeader'] = preg_replace('/"\s*(.*)\s*"/iU', "«$1»", $newsDataArr['newsHeader']);
        $newsSeoTitle = $newsDataArr['newsHeader'];
        $newsSeoDesc = $newsDataArr['newsSubheader'];
        $newsSeoKeywords = $this->getNewsSeoKeywords($newsId);

        db::sql("CALL saveAll(_1,_2,_3,_4,_5,_6,_7,_8,_9,_10,_11,_12,_13,_14,_15,_16)");
        $params = array(
            Clear::dataI($newsId),
            Clear::dataS($newsDataArr['newsHeader']),
            Clear::dataS($newsDataArr['newsSubheader']),
            $newsDataArr['newsText'],
            Clear::dataS($newsSeoDesc),
            Clear::dataS($newsSeoKeywords),
            Clear::dataS($newsSeoTitle),
            Clear::dataS($newsDataArr['newsUrl']),
            Clear::dataS($newsDataArr['newsVideo']),
            Clear::dataS($newsDataArr['newsVideoDesc']),
            $search,
            Clear::dataI($newsDataArr['categoryId']),
            $date,
            Clear::dataI($newsDataArr['newsStatus']),
            Clear::dataS($newsDataArr['newsSocText']),
            Clear::dataS($newsDataArr['newsType'])
          //  Clear::dataS($newsDataArr['newsWidgetTag'])
        );
        db::addParameters($params);
        $result=db::query();
        if(!isset($result[0]['saved'])){
            $response = array('error'=>'error');
        }
        else{
            $response = array('saved'=>Time::timestampToTimezone($result[0]['saved']));
        }
        return $response;
    }
    
    /*
     * Метод для изменений значений многих елементов внутри редактирования новсти
     * (є видео, є главной, и т. д.)
     */
    public function setParam( $param, $value, $news_id, $updateTime ){

        db::sql("CALL setNewsParam(_1,_2,_3)");
        $params = array(
            Clear::dataS($param),
            Clear::dataS($value),
            Clear::dataI($news_id)
        );
        db::addParameters($params);
        $result = db::query();
        if ($param == 'newsMain' && $value == 1) {
            $someNews = $this->getNewsByIdAdmin($news_id);
            MobileApiHelper::pushNotificationSend($someNews);
        }
        if($updateTime=='true'){
            db::sql("UPDATE tbl_news SET newsTimePublic=newsTimeUpdate WHERE newsId=".$news_id);
            $res=db::execute();
        }
        if(!isset($result[0]['saved'])) $response = array('error'=>'error');
        else{
            $res=$this->getAdminItemById($news_id);
            $response = array('saved'=>Time::timestampToTimezone($result[0]['saved']),'newsType'=>$res['newsType'],'newsStatus'=>$res['newsStatus']);

        }

        return $response;
    }
    
    /*
     * Получить SEO слова конкретной новости
     */
    public function getNewsSeoKeywords( $news_id ){
        db::sql("CALL tagGet(_1)");
        db::addParameters([$news_id]);
        $result=db::query();
        $tagNames = [];
        if(is_array($result))
            foreach($result as $tag){
                $tagNames[]=$tag['tagName'];
            }
        return implode(' ',$tagNames);
    }
    
    /*
     * Сброс даты публикации до реального значения первичного добавления новости
     */
    public function dataReset($newsId){
        $newsId=  Clear::dataI($newsId);
        $result=$this->getNewsAdmin(' WHERE `newsId`='.$newsId )[0];
        if(isset($result)){
            db::sql("UPDATE tbl_news SET newsTimePublic=newsTime WHERE newsId=".$newsId);
            $res=db::execute();
            return $result['newsTime'];
        }
        else {
            return false;
        }
    }
    
    /*
     * Получить новость для редактирования в админке
     */
    public function getAdminItemById( $id )
    {

        $news = $this->getNewsAdmin(' WHERE `newsId`=' . $id)[0];

        if ($news != false) {
            $news['newsTagsHTML'] = $this->getNewsTags($news['newsId']);
            $news['newsRegionsHTML'] = $this->getNewsRegions($news['newsId']);
            $news['newsBrandsHTML'] = $this->getNewsBrands($news['newsId']);
            $news['newsThemesHTML'] = $this->getNewsThemes($news['newsId']);
            if (0 != $news['sourceId']) $news['newsSourceHTML'] = $this->getNewsSource($news['newsId']);
            if (0 != $news['authorId']) $news['newsAuthorHTML'] = $this->getNewsAuthor($news['newsId']);
            $news['newsConnectHTML'] = $this->getNewsConnect($news['newsId']);
            $news['newsImgsHTML'] = $this->getNewsImages($news['newsId']);
            $news['newsDocsHTML'] = $this->getNewsDocs($news['newsId']);
            $news['newsWidgetTagsHTML'] = $this->getNewsWidgetTags($news['newsId']);

            return $news;
        }
        else {
            return false;
        }
    }
    
    /*
     * Получить привязание теги к новости
     */
    public function getNewsTags($newsId){
        db::sql("CALL tagGet(_1)");
        db::addParameters([$newsId]);
        $result = db::query();
        $tagsHtml = '';
        if(isset($result) and is_array($result)){

            foreach($result as $tag){
                require Config::getAdminRoot().'/views/block/tag.php.view.php';
                $tagsHtml .= $htmlString;
            }
        }
        return $tagsHtml;
    }
    
    //Получить тег(опросника) по Id новости (в админке)
    public function getNewsWidgetTags($newsId){
        db::sql("CALL tagWidgetGet(_1)");
        db::addParameters([$newsId]);
        $result = db::query();
        $tagsHtml = '';
        if(isset($result) and is_array($result)){

            foreach($result as $tag){
                require Config::getAdminRoot().'/views/block/widgettag.php.view.php';
                $tagsHtml .= $htmlString;
            }
        }
        return $tagsHtml;
    }
    
    /*
     * Получить привязание региони к новости
     */
    public function getNewsRegions($newsId){
        db::sql("CALL regionGet(_1)");
        db::addParameters([$newsId]);
        $result=db::query();
        $regionsHtml='';
        if(isset($result) and is_array($result)){

            foreach($result as $region){
                require Config::getAdminRoot().'/views/block/region.php.view.php';
                $regionsHtml.=$htmlString;
            }
        }
        return $regionsHtml;
    }
    
    /*
     * Получить привязание бренди к новости
     */
    public function getNewsBrands($newsId){
        db::sql("CALL brandGet(_1)");
        db::addParameters([$newsId]);
        $result=db::query();
        $brandsHtml='';
        if(isset($result) and is_array($result)){

            foreach($result as $brand){
                require Config::getAdminRoot().'/views/block/brand.php.view.php';
                $brandsHtml.=$htmlString;
            }
        }
        return $brandsHtml;
    }
    
     /*
     * Получить привязание теми к новости
     */
     public function getNewsThemes($newsId){
        db::sql("CALL themesGet(_1)");
        db::addParameters([$newsId]);
        $result=db::query();
        $themesHtml='';
        if(isset($result) and is_array($result)){

            foreach($result as $themes){
                require Config::getAdminRoot().'/views/block/themes.php.view.php';
                $themesHtml.=$htmlString;
            }
        }
        return $themesHtml;
    }
    
     /*
     * Получить источник новости
     */
    public function getNewsSource($newsId){
        db::sql("CALL sourceGet(_1)");
        db::addParameters([$newsId]);
        $result=db::query();
        $source=$result[0];
        require Config::getAdminRoot().'/views/block/source.php.view.php';

        return $htmlString;
    }
    
     /*
     * Получить автора новости
     */
    public function getNewsAuthor($newsId){
        db::sql("CALL authorGet(_1)");
        db::addParameters([$newsId]);
        $result=db::query();
        $author=$result[0];
        require Config::getAdminRoot().'/views/block/author.php.view.php';

        return $htmlString;
    }
    
     /*
     * Получить привязание новости к новости
     */
    public function getNewsConnect($newsId){
        global $ini;
        db::sql("CALL connectGet(_1)");
        db::addParameters([$newsId]);
        $result=db::query();
        $connectHtml='';
        if(isset($result) and is_array($result)){

            foreach($result as $connect){
                require Config::getAdminRoot().'/views/card/connect.php.view.php';
                $connectHtml.=$htmlString;
            }
        }

        return $connectHtml;
    }
    
     /*
     * Получить изображения новости
     */
    public function getNewsImages($newsId){
        global $ini;
        db::sql("CALL imgGet(_1)");
        db::addParameters([$newsId]);
        $result=db::query();
        $imgsHtml='';
        $htmlString='';
        if(isset($result) and is_array($result)){

            foreach($result as $item){
                require Config::getAdminRoot().'/views/block/img.php.view.php';
                $imgsHtml.=$htmlString;
            }
        }
        return $imgsHtml;
    }
    
     /*
     * Получить привязание документи к новости
     */
    public function getNewsDocs($newsId){
        global $ini;

        $docsHtml='';
        $path = $ini['path.media'].'docs/'.$newsId;

        if( file_exists($path) && is_dir($path) ) {
            $files = scandir($path);
            if(is_array($files)){
                foreach($files as $docname)
                    if($docname!='.' and $docname!='..'){
                        $htmlString='';
                        require Config::getAdminRoot().'/views/block/doc.php.view.php';
                        $docsHtml.=$htmlString;
                    }
            }
        }

        return $docsHtml;
    }

    /*
     * Изменить статус новсти на опубликованая
     */
    public function updateStatuses()
    {
        db::sql(" UPDATE ".$this->table_table."
            SET `newsStatus`=4
            WHERE `newsTimePublic` < NOW()
            AND `newsStatus`=3 ");
        $res = db::execute();

        return $res;
    }



}