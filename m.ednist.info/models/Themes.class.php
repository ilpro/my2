<?

/**
 * Работа с темами и их зависимостями
 */
class Themes {

    protected $table = '`vtbl_themes`';

    public function __construct() {
        
    }

      /**
     * Выборка с базы темов, согласно заданым условиям
     * @param string $conditions - условия для запроса
     * @return array - массив
     */ 
     public function getThemes( $conditions = '' ) {

        $result = [];

        db::sql("SELECT * FROM $this->table " . $conditions);
        $query_res = db::query();

        if (!empty($query_res)){
              foreach($query_res as $themes) {
                    $themes['images'] = $this->getThemesImgs( $themes );
                    $result[] = $themes;
            }
        }
        else{
            return false;
        }
        
        return $result;

    }
    /**
     * Проверка и присвоенние картинок тема по ID
     * @param $news
     * @return array
     */
     public function getThemesImgs( $themes ) {
        global $ini;
        $res = [];

        if( $themes['imgNames']!='' ) {

            $images = explode('|',$themes['imgNames']);
            $images_desc = explode('|',$themes['imgDescs']);

            foreach( $images as $key => $img_name ) {

                $themes_id = $themes['themesId'];

                if( isset($images_desc[$key]) && $images_desc[$key] != '' )
                    $res[$key]['desc'] = $images_desc[$key];
                else
                    $res[$key]['desc'] = '';

                if( $themes['imgMainDesc'] != '' ) $res['main']['desc'] = $themes['imgMainDesc'];

                $img['main60'] = "themes/".$themes_id."/main/60.jpg";         // админка - иконки в списке
                $img['main240'] = "themes/".$themes_id."/main/240.jpg";       // плитка на клиенте
                $img['main400'] = "themes/".$themes_id."/main/400.jpg";       // слайдеры, публикации на клиенте
                $img['gallery'] = "themes/".$themes_id."/gallery/".$img_name; // галерея на клиенте, иконка в редактировании новости
                $img['big'] = "themes/".$themes_id."/big/".$img_name;         // для клиента, основная страница материала
                $img['raw'] = "themes/".$themes_id."/raw/".$img_name;         // оригинал, с можно резать картинку для осн. стр.

                foreach ($img  as $img_key => $img_path)
                    if( FilesHelper::checkServerFile( $ini['path.media']. $img_path) ) {

                        $res[$key][$img_key] = $ini['url.media'].$img_path;

                        if( $themes['imgMain'] != '' && $themes['imgMain'] == $img_name )
                            $res['main'][$img_key] = $ini['url.media'].$img_path;
                    }
            }
        }

        return $res;
    }

    /**
     * Получить тему по ID
     * @param $id
     * @return bool
     */
    public function getThemesById($id, $type='') {

        $themes = $this->getThemes(' WHERE `themesId`=' . $id)[0];
        if( !$themes ) return false;
        if( $type == 'client' && $themes['themesStatus']!=4 ) return false;
        $themes['themesImgsHTML'] = $this->getThemesImages($themes['themesId']);
        return $themes;
    }
    
    //получить все изображения для теми
     public function getThemesImages($themesId){
        global $ini;
        db::sql("CALL imgthemesGet(_1)");
        db::addParameters([$themesId]);
        $result=db::query();

        $imgsHtml='';
        $htmlString='';
        if(isset($result) and is_array($result)){

            foreach($result as $item){
 
                require Config::getAdminRoot().'/views/block/imgthemespage.php.view.php';
                $imgsHtml.=$htmlString;
            }
        }
        return $imgsHtml;
    }
    
    /** Сохранить тему
     * @param $ThemesId - id
     * @param $ThemesName - название
     * @param $ThemesDesc - описание
     * @param $ThemesSearch -
     * @param $ThemesType -
     * @param $ThemesSortName - имя для сортировки
     * @param $ThemesActive
     * @return array
     */
    function saveAll(
        $ThemesId,
        $ThemesName,
        $ThemesDesc,
        $ThemesSearch,
        $ThemesType,
        $ThemesSortName,
        $ThemesActive)
    {

        db::sql("CALL saveThemes(_1,_2,_3,_4,_5,_6,_7)");

        $params = array(
            $ThemesId,
            $ThemesName,
            $ThemesDesc,
            $ThemesSearch,
            $ThemesType,
            $ThemesSortName,
            $ThemesActive);

        db::addParameters($params);
        $res = db::execute();

        if( $res == 0 )
            $response = array('saved' => date('Y-m-d H:i:s', time()));
        else
            $response = ['error'=>'error'];

        return $response;

    }


    /** Удалить тему по id
     * @param $themesId - id
     * @return string
     */
    public function deleteThemes($themesId)
    {

        global $ini;

        Upload::delFolder($ini['path.media'] . 'themes/' . $themesId);

        db::sql("CALL themesDel(_1)");

        $params = array($themesId);

        db::addParameters($params);

        $result = db::execute();

        return $result;

    }

    /**
     * Получить изображение
     * @param $themesId
     * @param $themesArr
     * @return mixed
     */
 /*   public function getOneThemesImages($themesId)
    {
        global $ini;
        $res = [];

        $img['themes60'] = "themes/" . $themesId . "/60.jpg";
        $img['orig'] = "themes/" . $themesId . "/orig.jpg";
        $img['big'] = "themes/" . $themesId . "/big.jpg";

        foreach( $img as $key=>$val ) {
            if( file_exists($ini['path.media'].$val) )
                $res[$key] = $ini['url.media'].$val;
        }

        return $res;
    }*/

    /**
     * Добавить новую тему
     */
    function addNewThemes()
    {

        db::sql("INSERT INTO tbl_themes SET themesName=''");

        $result = db::execute();

        if (!$result) {

            echo json_encode(array('error' => 'error'));

        } else {

            $this->get_stripes($result);

        }
    }

    /**
     * Добавить новую тему
     */
    function setThemesStatus( $status, $themes_id )
    {

        db::sql("UPDATE tbl_themes SET themesStatus=".$status." WHERE themesId=".$themes_id);
        $result = db::execute();

        if ($result!=0) {
            echo json_encode(array('error' => 'error'));
        } else {
            echo json_encode(array('saved' => date('H:i d.m.Y',time())));
        }
    }


    /** Отрисовать тему
     * @param int $themesId
     */
    public function get_stripes($themesId = 0)
    {

        global $ini;

        if ($themesId != 0)
            $result = $this->getThemes(' WHERE `themesId`=' . $themesId);
        else
            $result = $this->getThemes();

        if (is_array($result))
            
            foreach ($result as $item) {

                require Config::getAdminRoot() . '/views/themes/themesStripe.view.php';

            }

    }
   
}
