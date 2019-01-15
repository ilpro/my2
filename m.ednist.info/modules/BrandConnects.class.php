<?php

class BrandConnects {

//-----------------------------------------------------Связь

    function connectSearch($string, $exclude) {
        $search = new Search;
        $type = $search->typeOfString($string);
        if ('string' == $type) {
            $layout = $search->changeLayout($string);
            db::sql("CALL fulltextAdmin(_1,_2,_3,_4)");
            $string = $search->separate($string);
            $layout = $search->separate($layout);
            $params = array($string, $layout[0], $layout[1],10);
            db::addParameters($params);
            $result = db::query();
        } else if ('int' == $type) {
            $newsObj = new NewsAdmin();
            $result[] = $newsObj->getNewsByIdAdmin($string);
            if ($result[0] == false) {
                $result = false;
            }
        }


        if (isset($result) and is_array($result)) {
            global $ini;
            foreach ($result as $connect) {
                $exists = false;
                foreach ($exclude as $id) {
                    if ($id == $connect['newsId']) {
                        $exists = true;
                    }
                }
                if (false == $exists) {
                    require Config::getAdminRoot() . '/views/card/connect.view.php';
                }
            }
        } else {
            echo 'notExists';
        }
    }

    function connectSave($brandId, $connectId) {
        db::sql("CALL connectBrandSave(_1,_2)");
        $params = array($brandId, $connectId);
        db::addParameters($params);
        $result = db::query();
        if (!isset($result[0]['saved'])) {
            $response = array('error' => 'error');
        } else {
            $response = array('saved' => Time::timestampToTimezone($result[0]['saved']));
        }
        echo json_encode($response);
    }

    function connectRemove($brandId, $connectId) {
        db::sql("CALL connectBrandRemove(_1,_2)");
        $params = array($brandId, $connectId);
        db::addParameters($params);
        $result = db::query();
        if (!isset($result[0]['saved'])) {
            $response = array('error' => 'error');
        } else {
            $response = array('saved' => Time::timestampToTimezone($result[0]['saved']));
        }
        echo json_encode($response);
    }

//===========================================================Конец связи		
    //-----------------------------------------------------Бренды
    function brandSearch($string, $exclude) {
        $search = new Search;
        $layout = $search->changeLayout($string);  // поиск по 1 из 2 шаблонов поиска
        db::sql("CALL brandSearch(_1,_2,_3)");
        $params = array($string, $layout[0], $layout[1]);
        db::addParameters($params);
        $result = db::query();
        db::sql("SELECT * FROM `tbl_brandconnect");
        $query_res = db::query();
        if (isset($result) and is_array($result)) {

            foreach ($result as $brand) {

                $exists = false;

                if (!empty($exclude))
                    foreach ($exclude as $id) {
                        if ($id == $brand['brandId']) {
                            $exists = true;
                        }
                    }

                if (false == $exists) {
                    $exists = require Config::getAdminRoot() . '/views/block/brand.view.php';
                }
            }
        } else {
            echo 'notExists';
        }
    }
    
       function brandSearchConnect($string, $exclude) {
        $search = new Search;
        $layout = $search->changeLayout($string);  // поиск по 1 из 2 шаблонов поиска
        db::sql("CALL brandSearch(_1,_2,_3)");
        $params = array($string, $layout[0], $layout[1]);
        db::addParameters($params);
        $result = db::query();
        db::sql("SELECT * FROM `tbl_brandconnect");
        $query_res = db::query();
        if (isset($result) and is_array($result)) {

            foreach ($result as $brand) {

                $exists = false;

                if (!empty($exclude))
                    foreach ($exclude as $id) {
                        if ($id == $brand['brandId']) {
                            $exists = true;
                        }
                    }

                if (false == $exists) {
                    $exists = require Config::getAdminRoot() . '/views/block/brandConnect.view.php';
                }
            }
        } else {
            echo 'notExists';
        }
    }

    function brandSave($brandId, $brandConnect) {
        $brandSmile=0;
        db::sql("CALL brandSaveBrand(_1,_2,_3)");
        $params = array($brandId, $brandConnect, $brandSmile);
        db::addParameters($params);
        $result = db::query();
        if (!isset($result[0]['saved'])) {
            $response = array('error' => 'error');
        } else {
            $response = array('saved' => Time::timestampToTimezone($result[0]['saved']));
        }
        echo json_encode($response);
    }

    function brandRemove($brandId, $brandConnect) {
        db::sql("CALL brandRemoveBrand(_1,_2)");
        $params = array($brandId, $brandConnect);
        db::addParameters($params);
        $result = db::query();
        if (!isset($result[0]['saved'])) {
            $response = array('error' => 'error');
        } else {
            $response = array('saved' => Time::timestampToTimezone($result[0]['saved']));
        }
        echo json_encode($response);
    }

    // создание нового бренда со строки поиска
    function brandSaveNew($brandName, $brandSearch, $brandSort, $brandType) {
        $brandSearch = $brandName . ' ' . $brandSearch;
        db::sql("CALL brandSaveNew(_1,_2,_3,_4)");
        $params = array($brandName, $brandSearch, $brandSort, $brandType);
        db::addParameters($params);
        $result = db::query();
        if (!isset($result[0]['saved'])) {
            if (isset($result[0]['exists'])) {
                echo 'exists';
            } else {
                echo 'error';
            }
        } else {
            $brand['brandId'] = $result[0]['saved'];
            $brand['brandName'] = $brandName;
            require Config::getAdminRoot() . '/views/block/brand.view.php';
        }
    }
    //изменения селекта связи брендов
     function brandchangeConnect($brandId, $brandConnect, $value) {
         db::sql("SELECT `connectId` FROM `tbl_brandconnect` WHERE `connectName`='".$value."'");
        $result = db::query()[0];
        if(!empty($result)){
                db::sql("UPDATE `ctbl_brandtobrand` SET `connectId`=".$result['connectId']." WHERE `brandId`=".$brandId." AND `brandConnect`=".$brandConnect);
                $result1=db::execute();

                db::sql("UPDATE `ctbl_brandtobrand` SET `connectId`=".$result['connectId']." WHERE `brandId`=".$brandConnect." AND `brandConnect`=".$brandId);
                $result2=db::execute();
                $response=true;
        }else if($value=='Нет связи'){
                db::sql("UPDATE `ctbl_brandtobrand` SET `connectId`=0 WHERE `brandId`=".$brandId." AND `brandConnect`=".$brandConnect);
                $result1=db::execute();

                db::sql("UPDATE `ctbl_brandtobrand` SET `connectId`=0 WHERE `brandId`=".$brandConnect." AND `brandConnect`=".$brandId);
                $result2=db::execute();
                $response=true;
        }else{
            $response=false;
        }
         echo json_encode($response);

    }
    //Сохраняем бренди для автоматической привязки
    function brandBindSave($brandId) {
        db::sql("SELECT * FROM `tbl_settings` WHERE `settingsId`=1");
        $result = db::query();
        if(!empty($result[0]['settingsBrandBind'])){
            $settingsBrandBind = $result[0]['settingsBrandBind'].",".$brandId;
        }else{
            $settingsBrandBind = $brandId;
        }
        db::sql("UPDATE `tbl_settings` SET `settingsBrandBind`='$settingsBrandBind'");
        $result2=db::execute();
        if($result2==0){
            $response['saved']=true;
        }else{
            $response['error']=true;
        }
        echo json_encode($response);
    }
        //удаляем бренди для автоматической привязки
       function brandBindRemove($brandId) {
        db::sql("SELECT * FROM `tbl_settings` WHERE `settingsId`=1");
        $result = db::query();
        $list_brands = explode(",", $result[0]['settingsBrandBind']);
        $list="";
        if(count($list_brands)>0){
            $j=0;
             for ($i = 0; $i < count($list_brands); $i++) {
                 if($brandId==$list_brands[$i]){
                     continue;
                 }
                 $list.=$list_brands[$i];
                 if($j<count($list_brands)-2){
                     $list.=",";
                 }
                 $j++;
            }
            db::sql("UPDATE `tbl_settings` SET `settingsBrandBind`='$list'");
            $result2=db::execute();
            $response['saved']=true;
        }else{
            $response['error']=true; 
        }
        echo json_encode($response);
    }
    
    //Создать связь для брендов для вибора
    public function createBrandConnect($name){
        if($name!=''){
           db::sql("INSERT INTO tbl_brandconnect SET connectName='".$name."'");
           $result = db::execute(); 

           if(!empty($result)){
              return $result; 
           }else{
               return false;
           }
        }else{
            return false;
        }
    }
    
    //получить все возможние связи для бренда
    public function getBrandConnect(){
        db::sql("SELECT * FROM `tbl_brandconnect` ORDER BY `connectId` DESC");
        $result = db::query();
        if(!empty($result)){
            return $result;
        }else{
            return false;
        }
    }
    
        //редактировать связь для брендов 
    public function editBrandConnect($name,$id){
        if($name!=''){
          db::sql("UPDATE `tbl_brandconnect` SET `connectName`='$name' WHERE `connectId`=".$id);
           $result=db::execute(); 
           return true; 

        }else{
            return false;
        }
    }
    
          //редактировать связь для брендов 
    public function deleteBrandConnect($id){
        if($id>0){
          db::sql("DELETE FROM `tbl_brandconnect`  WHERE `connectId`=".$id);
           $result=db::execute(); 
           return true; 
        }else{
            return false;
        }
    }
    

}

?>