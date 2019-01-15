<?php

class NewsConnects{

//-----------------------------------------------------Теги
    function tagSearch($string,$exclude){
    	$search=new Search;
		$layout=$search->changeLayout($string)[0];
		db::sql("CALL tagSearch(_1,_2)");
		$params=array($string,$layout);      
		db::addParameters($params);
		$result=db::query();
		if(isset($result) and is_array($result)){

			foreach ($result as $tag){

				$exists=false;

                if(!empty($exclude))
                    foreach($exclude as $id){
                        if($id==$tag['tagId']){
                            $exists=true;
                        }
                    }

				if(false==$exists){
					$exists=require Config::getAdminRoot().'/views/block/tag.view.php';
				}				
			}			
		}
		else{
			echo 'notExists';
		}
	}
    function tagSave($newsId,$tagId){
		db::sql("CALL tagSave(_1,_2)");
		$params=array($newsId,$tagId);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}	
    function tagRemove($newsId,$tagId){
		db::sql("CALL tagRemove(_1,_2)");
		$params=array($newsId,$tagId);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}


    // создание нового тега со строки поиска, уточнить или так )
    function tagSaveNew($tagName,$tagSearch){
    	$tagSearch=$tagName.' '.$tagSearch;
		db::sql("CALL tagSaveNew(_1,_2)");
		$params=array($tagName,$tagSearch);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
		    if(isset($result[0]['exists'])){		    
		       echo 'exists';      
		    }
		    else{
			    echo 'error';
		    }	    	             
	    }
	    else{ 
	    	$tag['tagId']=$result[0]['saved'];
	    	$tag['tagName']=$tagName;
	   		require Config::getAdminRoot().'/views/block/tag.view.php';
	    }
	}
	
//===========================================================Конец тегов
        
        
        //-----------------------------------------------------Теги опроса(виджета)
   
    function tagWidgetSave($newsId,$tagId,$tagName){
		db::sql("CALL tagWidgetSave(_1,_2,_3)");
		$params=array($newsId,$tagId,$tagName);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}	
    function tagWidgetRemove($newsId,$tagId){
		db::sql("CALL tagWidgetRemove(_1,_2)");
		$params=array($newsId,$tagId);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}


  
//===========================================================Конец тегов опроса(виджета)
	
	
//-----------------------------------------------------Регионы
    function regionSearch($string,$exclude){
    	$search=new Search;
		$layout=$search->changeLayout($string)[0];
		db::sql("CALL regionSearch(_1,_2)");
		$params=array($string,$layout);      
		db::addParameters($params);
		$result=db::query();
		if(isset($result) and is_array($result)){

			foreach ($result as $region){
				$exists=false;

                if( !empty($exclude) )
                    foreach($exclude as $id){
                        if($id==$region['regionId']){
                            $exists=true;
                        }
                    }

				if(false==$exists){
					$exists=require Config::getAdminRoot().'/views/block/region.view.php';
				}				
			}			
		}
		else{
			echo 'notExists';
		}
	}

    function regionSave($newsId,$regionId){
		db::sql("CALL regionSave(_1,_2)");
		$params=array($newsId,$regionId);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}

    function regionRemove($newsId,$regionId){
		db::sql("CALL regionRemove(_1,_2)");
		$params=array($newsId,$regionId);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}

    // создание нового региона со строки поиска, уточнить или так )
    function regionSaveNew($regionName,$regionSearch){
    	$regionSearch=$regionName.' '.$regionSearch;
		db::sql("CALL regionSaveNew(_1,_2)");
		$params=array($regionName,$regionSearch);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
		    if(isset($result[0]['exists'])){		    
		       echo 'exists';      
		    }
		    else{
			    echo 'error';
		    }	    	             
	    }
	    else{ 
	    	$region['regionId']=$result[0]['saved'];
	    	$region['regionName']=$regionName;
	   		require Config::getAdminRoot().'/views/block/region.view.php';
	    }
	}
	
//===========================================================Конец регионов		
	
	
//-----------------------------------------------------Бренды
    function brandSearch($string,$exclude){
    	$search=new Search;
		$layout=$search->changeLayout($string);  // поиск по 1 из 2 шаблонов поиска
		db::sql("CALL brandSearch(_1,_2,_3)");
		$params=array($string,$layout[0],$layout[1]);
		db::addParameters($params);
		$result=db::query();
		if(isset($result) and is_array($result)){

			foreach ($result as $brand){

				$exists=false;

                if(!empty($exclude))
                    foreach($exclude as $id){
                        if($id==$brand['brandId']){
                            $exists=true;
                        }
                    }

				if(false==$exists){
					$exists=require Config::getAdminRoot().'/views/block/brand.view.php';
				}				
			}			
		}
		else{
			echo 'notExists';
		}
	}
    function brandSave($newsId,$brandId,$brandSmile){
		db::sql("CALL brandSave(_1,_2,_3)");
		$params=array($newsId,$brandId,$brandSmile);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}	
    function brandRemove($newsId,$brandId){
		db::sql("CALL brandRemove(_1,_2)");
		$params=array($newsId,$brandId);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}


    // процедуры brandSaveNew - не существует.
    // создание нового бренда со строки поиска, уточнить или так )

//    function brandSaveNew($brandName,$brandSearch){
//    	$brandSearch=$brandName.' '.$brandSearch;
//		db::sql("CALL brandSaveNew(_1,_2)");
//		$params=array($brandName,$brandSearch);
//		db::addParameters($params);
//		$result=db::query();
//	    if(!isset($result[0]['saved'])){
//		    if(isset($result[0]['exists'])){
//		       echo 'exists';
//		    }
//		    else{
//			    echo 'error';
//		    }
//	    }
//	    else{
//	    	$brand['brandId']=$result[0]['saved'];
//	    	$brand['brandName']=$brandName;
//	   		require Config::getAdminRoot().'/views/block/brand.view.php';
//	    }
//	}
	
//===========================================================Конец брендов		
	
//-----------------------------------------------------Тема
    function themesSearch($string,$exclude){
    	$search=new Search;
		$layout=$search->changeLayout($string);  // поиск по 1 из 2 шаблонов поиска
		db::sql("CALL themesSearch(_1,_2,_3)");
		$params=array($string,$layout[0],$layout[1]);
		db::addParameters($params);
		$result=db::query();
		if(isset($result) and is_array($result)){

			foreach ($result as $themes){

				$exists=false;

                if(!empty($exclude))
                    foreach($exclude as $id){
                        if($id==$themes['themesId']){
                            $exists=true;
                        }
                    }

				if(false==$exists){
					$exists=require Config::getAdminRoot().'/views/block/themes.view.php';
				}				
			}			
		}
		else{
			echo 'notExists';
		}
	}
    function themesSave($newsId,$themesId,$themesSmile){
		db::sql("CALL themesSave(_1,_2,_3)");
		$params=array($newsId,$themesId,$themesSmile);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}	
    function themesRemove($newsId,$themesId){
		db::sql("CALL themesRemove(_1,_2)");
		$params=array($newsId,$themesId);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}

	
//===========================================================Конец темы		        
        
//-----------------------------------------------------Источники
    function sourceSearch($string){
    	$search=new Search;
		$layout=$search->changeLayout($string)[0];
		db::sql("CALL sourceSearch(_1,_2)");
		$params=array($string,$layout);      
		db::addParameters($params);
		$result=db::query();
		if(isset($result) and is_array($result)){		
			foreach ($result as $source){
				require Config::getAdminRoot().'/views/block/source.view.php';
			}			
		}
		else{
			echo 'notExists';
		}
	}
    function sourceSave($newsId,$sourceId){
		db::sql("CALL sourceSave(_1,_2)");
		$params=array($newsId,$sourceId);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}	
    function sourceRemove($newsId,$sourceId){
		db::sql("CALL sourceRemove(_1,_2)");
		$params=array($newsId,$sourceId);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}	
	
//===========================================================Конец источников		

//-----------------------------------------------------Автор
    function authorSearch($string){
    	$search=new Search;
		$layout = $search->changeLayout($string)[0];
		db::sql("CALL authorSearch(_1,_2)");
		$params=array($string,$layout);      
		db::addParameters($params);
		$result=db::query();
		if(isset($result) and is_array($result)){		
			foreach ($result as $author){
				require Config::getAdminRoot().'/views/block/author.view.php';
			}			
		}
		else{
			echo 'notExists';
		}
	}
    function authorSave($newsId,$authorId){
		db::sql("CALL authorSave(_1,_2)");
		$params=array($newsId,$authorId);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}	
    function authorRemove($newsId,$authorId){
		db::sql("CALL authorRemove(_1,_2)");
		$params=array($newsId,$authorId);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}	
	
//===========================================================Конец автора	


//-----------------------------------------------------Связь


    function connectSearch($string,$exclude){
    	$search=new Search;
    	$type=$search->typeOfString($string);
    	if('string'==$type) {
			$layout = $search->changeLayout($string);
			db::sql("CALL fulltextAdmin(_1,_2,_3,_4)");
			$string = $search->separate($string);
			$layout = $search->separate($layout);
			$params = array($string,$layout[0],$layout[1],10);
			db::addParameters($params);
			$result=db::query(); 	
    	}
    	else if('int'==$type) {
            $newsObj = new NewsAdmin();
            $result[] = $newsObj->getNewsByIdAdmin($string);
            if($result[0]==false){
                 $result=false;
            }
    	}
   

		if(isset($result) and is_array($result)){
		  global $ini;
			foreach ($result as $connect){
				$exists=false;
				foreach($exclude as $id){					
					if($id==$connect['newsId']){
						$exists=true;
					} 
				}
				if(false==$exists){
					require Config::getAdminRoot().'/views/card/connect.view.php';
				}				
			}			
		}
		else{
			echo 'notExists';
		}
	}

    function connectSave($newsId,$connectId){
		db::sql("CALL connectSave(_1,_2)");
		$params=array($newsId,$connectId);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}
		
    function connectRemove($newsId,$connectId){
		db::sql("CALL connectRemove(_1,_2)");
		$params=array($newsId,$connectId);      
		db::addParameters($params);
		$result=db::query();
	    if(!isset($result[0]['saved'])){
	        $response=array('error'=>'error');        
	    }
	    else{ 
	        $response=array('saved'=>Time::timestampToTimezone($result[0]['saved']));      
	    }
	    echo json_encode($response); 
	}	
	
//===========================================================Конец связи		
	
}

                   
?>