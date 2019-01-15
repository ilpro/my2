<?php

/**
 * Для AJAX-запросов
 */
if (!$ini) {
    require_once '../../config/iniParse.class.php';
    require_once Config::getRoot() . "/config/header.inc.php";
}
Auth::checkAuth();

// Шаблон для вывода страницы !обязательно
$layout = 'main';

// Модель текущего ресурса, или других используемых
$news = new NewsAdmin();
$brands = new Brands();
$themes = new Themes();
$category = new Category();
$newsConnects = new NewsConnects();
$search = new Search();
$users=new User();
$settings=new Settings();
$fbpixel = new FBPixel();
//$seeTarget=new SeeTarget();
//$follow=new FollowNews();

if(isset($_SESSION['user']['role'])){
/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */
    
    //начать автосохранения новости
    if (isset($_POST['action']) && $_POST['action']=='autosave') {
        $username='';
        $newsId = $_POST['newsId'];
        $userId = $_SESSION['user']['id'];
        if(!$news->isNewsEdit($userId, $newsId)){
            $result=$news->autoSaveNews($userId, $newsId,$_POST);
            echo json_encode(array('ob'=>true));
        }
        else{
            $username=$news->getUserAutosave($newsId);
            echo json_encode(array('ob'=>false,'username'=>$username));
        }
        die();
    }

    //закончить автосохранения
     if (isset($_POST['newsId2'])) {
         $userId = $_SESSION['user']['id'];
         $newsId = $_POST['newsId2'];        
         if(!$news->isNewsEdit($userId, $newsId)){
          $newsId = $_POST['newsId2'];
          $result=$news->autoSaveNewsClear($newsId);

          echo json_encode(array('ob'=>true));
         }
         else{
            echo json_encode(array('ob'=>false)); 
         }
          die();
     }
     
         if (isset($_POST['delInformer'])) {
            $userId = $_SESSION['user']['id'];
            $newsId = $_POST['delInformer'];
            //удаляем редактора с бд информера после вихода с новости
            $news->delInformer($userId, $newsId);

            echo json_encode(array('ob'=>true)); 
         
             die();
     }
     /*
     //При заходе на новость проверяем чи ее ещо не редактирует другой редактор
     if (isset($_POST['action']) and $_POST['action'] == 'inNews') {
        $userId = $_SESSION['user']['id'];
        $newsId = $_POST['newsId'];
        if (!$news->isUserInNews($userId, $newsId)) {
            //новость не редактируетса, заносим значения в бд
            $result=$news->adminUserEditNews($userId, $newsId);
            echo json_encode(array('ob' => true));
        } else {
            $username = $news->getUsernameInNews($newsId);
            echo json_encode(array('ob' => false, 'username' => $username));
        }

        die();
    }
     //При заходе на новость проверяем чи ее ещо не редактирует другой редактор
     if (isset($_POST['action']) and $_POST['action'] == 'clearUserEditNews') {
        $userId = $_SESSION['user']['id'];
        $newsId = $_POST['newsId'];
         echo json_encode(array('ob' => false, 'username' => $username));
         die();
    }
*/
  /*    if (isset($_POST['action']) and $_POST['action'] == 'openNews') {
          $result=false;
          $userId = $_SESSION['user']['id'];
          $newsId = $_POST['newsId'];
          $someUser=$users->getUserById($userId);
          $adminRole=$someUser['userRole'];
          $someNews=$news->getNewsByIdAdmin($newsId);
          if($adminRole==1||$someNews['userId']==$userId){
             $result=true; 
          }
          echo json_encode(array('result'=>$result));
          die();
      }*/
     
  // получить новость по $id
    if (isset($_POST['getNewsItem']) && $_POST['getNewsItem'] != 0) {
        $res = false;
        $userId = $_SESSION['user']['id'];
        $newsId = $_POST['getNewsItem'];
        $adminRole = $_SESSION['user']['role'];
        $someNews = $news->getNewsByIdAdmin($newsId);
        if ($adminRole == 1 || $someNews['userId'] == $userId) {
            $res = true;
            //false-дание с бд, true-с чорновика
            $notice = false;
            //если новость есть в чорновику, то вона идет по дефолту, если нету, то витаскиваем с бд
            $res_autosave = $news->getAutoSaveNews($_POST['getNewsItem']);
            $result = $news->getAdminItemById(Clear::dataI($_POST['getNewsItem']));
            //Устанавливаем, што редактор зашол на новость(не для чорновика)
            $news->setInformer($userId, $result['newsId'], $result['newsHeader']);
            
            if (!empty($res_autosave[0]['newstext']) || !empty($res_autosave[0]['newsHeader']) || !empty($res_autosave[0]['newsSubheader'])) {
                $result['newsText'] = $res_autosave[0]['newstext'];
                $result['newsHeader'] = $res_autosave[0]['newsHeader'];
                $result['newsSubheader'] = $res_autosave[0]['newsSubheader'];
                $notice = true;
            }
            echo json_encode(array('result' => $result, 'notice' => $notice,'res'=>$res));
        } else {
            echo json_encode(array('res'=>$res));
        }
        die();
    }

// удалить новости по массиву ИД-шок
if (isset($_POST['deleteNews']) and is_array($_POST['deleteNews'])) {//Наполнение новости
    $res = $news->deleteNews($_POST['deleteNews']);
    if ($res == true) echo json_encode(array('clean' => 'ok'));
    else echo json_encode(array('clean' => 'error'));
    die();
}

// добавить новую новость
if (isset($_POST['addNewNews'])) {
    echo json_encode($news->addNewNews());
    die();
}

// получить все новости
if (isset($_GET['getNewsList'])) {
    $newsList=$news->getNewsAdmin(' ORDER BY `newsTimePublic` DESC LIMIT 20 ');
   // $page_data['seeTargetUsers']=$seeTarget->getSeetargetUsers($newsList);
    //$page_data['followNewsUsers']=$follow->getFollowNewsUsers($newsList);
    foreach ($newsList as $key2=> $item){
        include Config::getAdminRoot() . '/views/news/newsStripe.view.php';
    }
    die();
}
// оновить нововсть
if (isset($_GET['getNewsListId'])) {
    //удаляем редактора с бд информера после вихода с новости
    $userId = $_SESSION['user']['id'];
    $news->delInformer($userId, Clear::dataI($_GET['getNewsListId']));
    
    $item=$news->getAdminItemById(Clear::dataI($_GET['getNewsListId']));
  //  $page_data['seeTargetUsers']=$seeTarget->getOneSeetargetUser($item);
   // $page_data['followNewsUsers']=$follow->getOneFollowNewsUser($item);
    $key2=0;
          include Config::getAdminRoot() . '/views/news/newsStripe.view.php';
    die();
}

/**
 * Обработка AJAX-запросов, в конце условия обязательно ставить die();
 */
if (isset($_POST['action']) and $_POST['action'] == 'get_more') {

    $limit_from = (int)$_POST['limit_from'];
    $limit = (int)$_POST['limit'];
    $items = $news->getNewsAdmin(" ORDER BY `newsTimePublic` DESC LIMIT ".$limit_from.",".$limit );
    $more = $news->getNewsAdmin(" ORDER BY `newsTimePublic` DESC LIMIT ".$limit_from.",".$limit+1 ) != false ? true : false;
   // $page_data['seeTargetUsers']=$seeTarget->getSeetargetUsers($items);
    //$page_data['followNewsUsers']=$follow->getFollowNewsUsers($items);
    if( !empty($items) ) {
        foreach ($items as $key2=>$item) {
            include Config::getAdminRoot() . '/views/news/newsStripe.view.php';
        }
    }

    die();
}

//Метод информера(посилает дание в бд с ід юзера и с ід новсти на которой он находитса)
if (isset($_POST['action']) and $_POST['action'] == 'redactorUpdate') {
    $userId = $_SESSION['user']['id'];
    $newsId=$_POST['newsId'];
    $res=$news->updateInformer($userId, $newsId);
    echo json_encode(array('ob' => $res));
    die();
}

// получить все новости с поиска
    if (isset($_GET['search'])) {
        //если сбросить поиск и фильтри
        if($_GET['find']=='reset'&&$_GET['newsType']=='reset'&&$_GET['userId']=='reset'){
            $result=$news->getNewsAdmin(' ORDER BY `newsTimePublic` DESC LIMIT 20 ');
        }
        //ето поиск для админа
        else{
            $result = $search->searchString($_GET['find'], $_GET['newsType'], $_GET['userId']);
        }
        if (isset($result) && $result != NULL) {
         //   $page_data['seeTargetUsers']=$seeTarget->getSeetargetUsers($result);
          //  $page_data['followNewsUsers']=$follow->getFollowNewsUsers($result);
            foreach ($result as $key2=>$item) {
                include Config::getAdminRoot() . '/views/news/newsStripe.view.php';
            }
        }
        else {
            include Config::getAdminRoot() . '/views/news/newsStripeEmptySearch.view.php';
        }
        die();
    }
// сохранить новость
if (isset($_POST['saveAll'])) {
    $res = $news->saveAll($_POST['newsData'], $_POST['saveAll']);
    //Проверяем чи имееш право ето сделать
    $userId = $_SESSION['user']['id'];
    $newsId = $_POST['saveAll'];
    if(!$news->isNewsEdit($userId, $newsId)){
          //удаляем тест новости с черновика, если нажата кнопка *сохранить*
          $news->deleteTextAutoSaveNews($_POST['saveAll']);  
        }
    echo json_encode($res);
    die();
}

//здесь узнаем какая кнопка била нажата, и отсилаем нужний текст(с чорновика или бази)
if (isset($_POST['actionNews'])) {
        //с чорновика
        if (($_POST['actionNews'] == 'notepad') && (isset($_POST['newsId']))) {
            $result = $news->getNewsTextFromNotepad($_POST['newsId']);
             if(!empty($result[0]['newstext'])||!empty($result[0]['newsHeader'])||!empty($result[0]['newsSubheader'])){
                $act = true;
                $result['newstext']=$result[0]['newstext'];
                $result['newsHeader']=$result[0]['newsHeader'];
                $result['newsSubheader']=$result[0]['newsSubheader'];
            } else
                $act = false;
            echo json_encode(array('result' => $result, 'act' => $act));
            die();
        }
        //с бази
        if (($_POST['actionNews'] == 'db') && (isset($_POST['newsId']))) {
            $result = $news->getNewsTextFromDB($_POST['newsId']);
            if (!empty($result)) {
                $act = true;
                $result['newstext']=$result[0]['newsText'];
                $result['newsHeader']=$result[0]['newsHeader'];
                $result['newsSubheader']=$result[0]['newsSubheader'];
            } else
                $act = false;
            echo json_encode(array('result' => $result, 'act' => $act));
            die();
        }
    }
    //очистить черновик(если нажата кнопка *очистить черновик*)
    if (isset($_POST['action']) && $_POST['action'] == 'clearNotice') {
    $ob=false;$result=true;$username='';
    //Проверяем чи имееш право ето сделать
    $userId = $_SESSION['user']['id'];
    $newsId = $_POST['newsId'];
    if(!$news->isNewsEdit($userId, $newsId)){
        //удаляем тест новости с черновика
        $result=$news->deleteTextAutoSaveNews($_POST['newsId']);
        $ob=true;
    }
    else{
       $username=$news->getUserAutosave($newsId);
    }
    echo json_encode(array('result'=>$result,'ob'=>$ob,'username'=>$username));
    die();
}

// сохранить новость
if (isset($_POST['action']) && $_POST['action'] == 'setParam') {
    $res = $news->setParam($_POST['paramName'], $_POST['paramValue'], $_POST['newsId'],$_POST['updateTime']);
    echo json_encode($res);
    die();
}

// сбросить дату рубликации
if (isset($_POST['action']) && $_POST['action'] == 'dataReset') {
    $res = $news->dataReset($_POST['newsId']);
    $ob=false;
    if($res!=false){
        $ob=true;
    }
    echo json_encode(array('ob'=>$ob,'time'=>$res));
    die();
}
//асинхроное оновления информера
    if (isset($_POST['action']) && $_POST['action'] == 'updateInformer') {
        $list_users = $users->getUsers();
        //для информера(знать кто на какой новости находитса)
        foreach ($list_users as $key => $value) {
            $list_users[$key]['inNews'] = $news->getInformer($value['userId']);
        }
        if (is_array($list_users)) {
            foreach ($list_users as $key => $item) {
                include Config::getAdminRoot() . '/views/users/user_informer.view.php';
            }
        }
        die();
    }
//-------------------------------------------------------Теги
if (isset($_POST['tagSearch'])) {//Поиск тега
    $newsConnects->tagSearch(Clear::dataS($_POST['tagSearch']), Clear::arrayI($_POST['exclude']));
    die();
}
if (isset($_POST['tagSave'])) {//Сохранение тега
    $newsConnects->tagSave(Clear::dataS($_POST['tagSave']), Clear::dataI($_POST['tagId']));
    die();
}
if (isset($_POST['tagRemove'])) {//Удаление тега
    $newsConnects->tagRemove(Clear::dataS($_POST['tagRemove']), Clear::dataI($_POST['tagId']));
    die();
}
if (isset($_POST['tagSaveNewName'])) {//СОхранение нового тега
    $newsConnects->tagSaveNew(Clear::dataS($_POST['tagSaveNewName']), Clear::dataS($_POST['tagSaveNewSearch']));
    die();
}
//========================================================Конец тегов

//-------------------------------------------------------Теги для опроса(виджета)

if (isset($_POST['tagWidgetSave'])) {//Сохранение тега
    $newsConnects->tagWidgetSave(Clear::dataS($_POST['tagWidgetSave']), Clear::dataI($_POST['tagWidgetId']),Clear::dataS($_POST['tagWidgetName']));
    die();
}
if (isset($_POST['tagWidgetRemove'])) {//Удаление тега
    $newsConnects->tagWidgetRemove(Clear::dataS($_POST['tagWidgetRemove']), Clear::dataI($_POST['tagWidgetId']));
    die();
}

//========================================================Конец тегов опроса(виджета)

//-------------------------------------------------------Регионы
if (isset($_POST['regionSearch'])) {//Поиск региона
    $newsConnects->regionSearch(Clear::dataS($_POST['regionSearch']), Clear::arrayI($_POST['exclude']));
    die();
}
if (isset($_POST['regionSave'])) {//Сохранение региона
    $newsConnects->regionSave(Clear::dataI($_POST['regionSave']), Clear::dataI($_POST['regionId']));
    die();
}
if (isset($_POST['regionRemove'])) {//Удаление региона
    $newsConnects->regionRemove(Clear::dataI($_POST['regionRemove']), Clear::dataI($_POST['regionId']));
    die();
}
if (isset($_POST['regionSaveNewName'])) {//СОхранение нового региона
    $newsConnects->regionSaveNew(Clear::dataS($_POST['regionSaveNewName']), Clear::dataS($_POST['regionSaveNewSearch']));
    die();
}
//========================================================Конец регионов

//-------------------------------------------------------Бренды
if (isset($_POST['brandSearch'])) {//Поиск бренда
    $newsConnects->brandSearch(Clear::dataS($_POST['brandSearch']), Clear::arrayI($_POST['exclude']));
    die();
}
if (isset($_POST['brandSave'])) {//Сохранение бренда
    $newsConnects->brandSave(Clear::dataI($_POST['brandSave']), Clear::dataI($_POST['brandId']), Clear::dataI($_POST['brandSmile']));
    die();
}
if (isset($_POST['brandRemove'])) {//Удаление бренда
    $newsConnects->brandRemove(Clear::dataI($_POST['brandRemove']), Clear::dataI($_POST['brandId']));
    die();
}
//========================================================Конец брендов

//-------------------------------------------------------Темы
if (isset($_POST['themesSearch'])) {//Поиск теми
    $newsConnects->themesSearch(Clear::dataS($_POST['themesSearch']), Clear::arrayI($_POST['exclude']));
    die();
}
if (isset($_POST['themesSave'])) {//Сохранение темы
    $newsConnects->themesSave(Clear::dataI($_POST['themesSave']), Clear::dataI($_POST['themesId']), Clear::dataI($_POST['themesSmile']));
    die();
}
if (isset($_POST['themesRemove'])) {//Удаление темы
    $newsConnects->themesRemove(Clear::dataI($_POST['themesRemove']), Clear::dataI($_POST['themesId']));
    die();
}
//========================================================Конец теми

//-------------------------------------------------------Источник
if (isset($_POST['sourceSearch'])) {//Поиск источника
    $newsConnects->sourceSearch(Clear::dataS($_POST['sourceSearch']));
    die();
}
if (isset($_POST['sourceSave'])) {//Сохранение источника
    $newsConnects->sourceSave(Clear::dataI($_POST['sourceSave']), Clear::dataI($_POST['sourceId']));
    die();
}
if (isset($_POST['sourceRemove'])) {//Удаление источника
    $newsConnects->sourceRemove(Clear::dataI($_POST['sourceRemove']), Clear::dataI($_POST['sourceId']));
    die();
}
//========================================================Конец источника
//-------------------------------------------------------Автор
if (isset($_POST['authorSearch'])) {//Поиск автора
    $newsConnects->authorSearch(Clear::dataS($_POST['authorSearch']), Clear::arrayI($_POST['exclude']));
    die();
}
if (isset($_POST['authorSave'])) {//Сохранение автора
    $newsConnects->authorSave(Clear::dataI($_POST['authorSave']), Clear::dataI($_POST['authorId']));
    die();
}
if (isset($_POST['authorRemove'])) {//Удаление автора
    $newsConnects->authorRemove(Clear::dataI($_POST['authorRemove']), Clear::dataI($_POST['authorId']));
    die();
}
//========================================================Конец автора
//-------------------------------------------------------Связь
if (isset($_POST['connectSearch'])) {//Поиск связи
    $newsConnects->connectSearch(Clear::dataS($_POST['connectSearch']), Clear::arrayI($_POST['exclude']));
    die();
}
if (isset($_POST['connectSave'])) {//Сохранение связи
    $newsConnects->connectSave(Clear::dataI($_POST['connectSave']), Clear::dataI($_POST['connectId']));
    die();
}
if (isset($_POST['connectRemove'])) {//Удаление связи
    $newsConnects->connectRemove(Clear::dataI($_POST['connectRemove']), Clear::dataI($_POST['connectId']));
    die();
}
//========================================================Конец связи

//получить ссилку на новость(взять код для вставки)
if (isset($_POST['connectHeader'])) {//вставка коду
        $id = Clear::dataI($_POST['connectId']);
        $name = $_POST['connectHeader'];
        $result = $news->getNewsByIdAdmin($id);
        $server_path = $_SERVER['SERVER_NAME'];
        $path = "<div class=insert><a href=http://$server_path/news/$id>$name</a></div>";
        if (isset($result)) {
            $res = true;
        } else {
            $res = false;
        }
        echo json_encode(array('res' => $res, 'path' => $path));
        die();
    }
}
/**
 * Обработка $_GET запроса текущей страницы
 */

$page_data = [];

$page_data['newsList'] = $news->getNewsAdmin(' ORDER BY `newsTimePublic` DESC LIMIT 20 ');
//$page_data['seeTargetUsers']=$seeTarget->getSeetargetUsers($page_data['newsList']);
//$page_data['followNewsUsers']=$follow->getFollowNewsUsers($page_data['newsList']);

$page_data['categories'] = $category->getCategories();
$page_data['users']= $users->getUsers();
//для информера(знать кто на какой новости находитса)
foreach ($page_data['users'] as $key=>$value) {
    $page_data['users'][$key]['inNews']=$news->getInformer($value['userId']);
}
//Ето вставка(для кнопки в редакторе) котороая с /settings
$page_data['settingsPasteByCursor']=htmlspecialchars($settings->getSettings()[0]['settingsPasteByCursor']);

$page_data['fbpixel']=$fbpixel->getAll();