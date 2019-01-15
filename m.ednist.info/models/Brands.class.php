<?

/**
 * Работа с брендами и их зависимостями
 */
class Brands {

    protected $table = '`vtbl_brand`';

    public function __construct() {
        
    }

      /**
     * Выборка с базы брендов, согласно заданым условиям
     * @param string $conditions - условия для запроса
     * @return array - массив
     */ 
     public function getBrands( $conditions = '' ) {

        $result = [];

        db::sql("SELECT * FROM $this->table " . $conditions);
        $query_res = db::query();

        if (!empty($query_res)){
              foreach($query_res as $brand) {
                    $brand['images'] = $this->getBrandImgs( $brand );
                    $result[] = $brand;
            }
        }
        else{
            return false;
        }
        
        return $result;

    }
    /**
     * Проверка и присвоенние картинок бренда по ID
     * @param $news
     * @return array
     */
     public function getBrandImgs( $brand ) {
        global $ini;
        $res = [];

        if( $brand['imgNames']!='' ) {

            $images = explode('|',$brand['imgNames']);
            $images_desc = explode('|',$brand['imgDescs']);

            foreach( $images as $key => $img_name ) {

                $brand_id = $brand['brandId'];

                if( isset($images_desc[$key]) && $images_desc[$key] != '' )
                    $res[$key]['desc'] = $images_desc[$key];
                else
                    $res[$key]['desc'] = '';

                if( $brand['imgMainDesc'] != '' ) $res['main']['desc'] = $brand['imgMainDesc'];

                $img['main60'] = "brand/".$brand_id."/main/60.jpg";         // админка - иконки в списке
                $img['main240'] = "brand/".$brand_id."/main/240.jpg";       // плитка на клиенте
                $img['main400'] = "brand/".$brand_id."/main/400.jpg";       // слайдеры, публикации на клиенте
                $img['gallery'] = "brand/".$brand_id."/gallery/".$img_name; // галерея на клиенте, иконка в редактировании новости
                $img['big'] = "brand/".$brand_id."/big/".$img_name;         // для клиента, основная страница материала
                $img['raw'] = "brand/".$brand_id."/raw/".$img_name;         // оригинал, с можно резать картинку для осн. стр.

                foreach ($img  as $img_key => $img_path)
                    if( FilesHelper::checkServerFile( $ini['path.media']. $img_path) ) {

                        $res[$key][$img_key] = $ini['url.media'].$img_path;

                        if( $brand['imgMain'] != '' && $brand['imgMain'] == $img_name )
                            $res['main'][$img_key] = $ini['url.media'].$img_path;
                    }
            }
        }

        return $res;
    }

    /**
     * Получить бренд по ID
     * @param $id
     * @return bool
     */
    public function getBrandById($id, $type='') {

        $brand = $this->getBrands(' WHERE `brandId`=' . $id)[0];
        if( !$brand ) return false;
        if( $type == 'client' && $brand['brandStatus']!=4 ) return false;
        $brand['brandImgsHTML'] = $this->getBrandsImages($brand['brandId']);
        $brand['newsBrandsHTML'] = $this->getNewsBrandsConnect($brand['brandId']);
        return $brand;
    }

    public function getRandBrand() {

        db::sql("SELECT MAX(brandId) FROM $this->table");
        $query_res = db::query();
        do{
            $n = rand(0, $query_res[0]['MAX(brandId)']);
            $res = $this->getBrandById($n);
        }while(!$res);

        return $res;
    }
    
     public function getNewsBrandsConnect($brandId) {
        db::sql("CALL brandGetBrand(_1)");
        db::addParameters([$brandId]);
        $result = db::query();

        $brandsHtml = '';
        db::sql("SELECT * FROM `tbl_brandconnect");
        $query_res = db::query();
        if (isset($result) and is_array($result)) {

            foreach ($result as $brand) {
                require Config::getAdminRoot() . '/views/block/brandConnect.php.view.php';
                $brandsHtml.=$htmlString;

            }
        }
        return $brandsHtml;
    }
    
    //получить все изображения для бренда
     public function getBrandsImages($brandId){
        global $ini;
        db::sql("CALL imgbrandGet(_1)");
        db::addParameters([$brandId]);
        $result=db::query();

        $imgsHtml='';
        $htmlString='';
        if(isset($result) and is_array($result)){

            foreach($result as $item){
 
                require Config::getAdminRoot().'/views/block/imgbrandpage.php.view.php';
                $imgsHtml.=$htmlString;
            }
        }
        return $imgsHtml;
    }
    
    /** Сохранить бренд
     * @param $BrandId - id
     * @param $BrandName - название
     * @param $BrandDesc - описание
     * @param $BrandSearch -
     * @param $BrandType -
     * @param $BrandSortName - имя для сортировки
     * @param $BrandActive
     * @return array
     */
    function saveAll(
        $BrandId,
        $BrandName,
        $BrandDesc,
        $BrandSearch,
        $BrandType,
        $BrandSortName,
        $BrandActive)
    {

        db::sql("CALL saveBrand(_1,_2,_3,_4,_5,_6,_7)");

        $params = array(
            $BrandId,
            $BrandName,
            $BrandDesc,
            $BrandSearch,
            $BrandType,
            $BrandSortName,
            $BrandActive);

        db::addParameters($params);
        $res = db::execute();

        if( $res == 0 )
            $response = array('saved' => date('Y-m-d H:i:s', time()));
        else
            $response = ['error'=>'error'];

        return $response;

    }


    /** Удалить бренд по id
     * @param $brandId - id
     * @return string
     */
    public function deleteBrand($brandId)
    {

        global $ini;

        Upload::delFolder($ini['path.media'] . 'brand/' . $brandId);

        db::sql("CALL brandDel(_1)");

        $params = array($brandId);

        db::addParameters($params);

        $result = db::execute();

        return $result;

    }

    /**
     * Получить изображение
     * @param $brandId
     * @param $brandArr
     * @return mixed
     */
    public function getBrandImages($brandId)
    {
        global $ini;
        $res = [];

        $img['brand60'] = "brand/" . $brandId . "/60.jpg";
        $img['orig'] = "brand/" . $brandId . "/orig.jpg";
        $img['big'] = "brand/" . $brandId . "/big.jpg";

        foreach( $img as $key=>$val ) {
            if( file_exists($ini['path.media'].$val) )
                $res[$key] = $ini['url.media'].$val;
        }

        return $res;
    }

    /**
     * Добавить новый бренд
     */
    function addNewBrand()
    {

        db::sql("INSERT INTO tbl_brand SET brandName=''");

        $result = db::execute();

        if (!$result) {

            echo json_encode(array('error' => 'error'));

        } else {

            $this->get_stripes($result);

        }
    }

    /**
     * Добавить новый бренд
     */
    function setBrandStatus( $status, $brand_id )
    {

        db::sql("UPDATE tbl_brand SET brandStatus=".$status." WHERE brandId=".$brand_id);
        $result = db::execute();

        if ($result!=0) {
            echo json_encode(array('error' => 'error'));
        } else {
            echo json_encode(array('saved' => date('H:i d.m.Y',time())));
        }
    }


    /** Отрисовать бренды
     * @param int $brandId
     */
    public function get_stripes($brandId = 0)
    {

        global $ini;

        if ($brandId != 0)
            $brands = $this->getBrands(' WHERE `brandId`=' . $brandId);
        else
            $brands = $this->getBrands();

        if (is_array($brands))
            
            foreach ($brands as $item) {

                require Config::getAdminRoot() . '/views/brand/brandStripe.view.php';

            }

    }
   
}
