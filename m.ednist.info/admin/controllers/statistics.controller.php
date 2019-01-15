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
$newsAdmin = new NewsAdmin();
$user=new User();

if(isset($_SESSION['user']['role'])&&$_SESSION['user']['role']==1){
  
 if (isset($_POST['action'])&&$_POST['action'] == 'getStatistics') {
     $value=  Clear::dataI($_POST['value']);
     $statistics_users=$user->getUsersStatistics($value);
     $statistics_all=$user->getUsersStatisticsAll($value);
     if(!empty($statistics_users)){
         foreach ($statistics_users as $item) {
           include Config::getAdminRoot() . '/views/statistics/statisticsStripe.view.php';  
         }
     }
      if(!empty($statistics_all)){
           include Config::getAdminRoot() . '/views/statistics/statisticsStripeAjaxAll.view.php';  

     }
     die();
 }   
    
    /**
 * Обработка $_GET запроса текущей страницы
 */
$page_data = [];
$page_data['users']=$user->getUsersStatistics(1);
$page_data['all_users']=$user->getUsersStatisticsAll(1);

}
else{
    exit();
}






