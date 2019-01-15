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
    $crosspost = new Crosspost();

if(isset($_SESSION['user']['role'])){

    $crosspost = new Crosspost();

    if (isset($_REQUEST['use_method']) && $_REQUEST['use_method']=='save_settings' ) {
        $crosspost->save_settings( $_REQUEST );
    }

    if( isset($_REQUEST['tw_log_url']) ) {
        echo $crosspost->get_auth_url( array('tw'=>true) );
    }
    if( isset($_REQUEST['fb_log_url']) ) {
        echo $crosspost->get_auth_url( array('fb'=>true) );
    }

    if (isset($_REQUEST['oauth_verifier']) && isset($_REQUEST['callback_tw'])) {
        $crosspost->get_access_token( array('tw'=>true) );
    }
    if (isset($_REQUEST['callback_fb'])) {
        var_dump($crosspost->get_access_token( array('fb'=>true) ));
    }
    if (isset($_REQUEST['use_method']) && $_REQUEST['use_method']=='get_access_token_vk' ) {
        $crosspost->get_access_token( array('vk'=>true, 'url'=>$_REQUEST['url']) );
    }

    if (isset($_REQUEST['tw_deny'])) {
        $crosspost->deny_account(['tw'=>true]);
    }
    if (isset($_REQUEST['fb_deny'])) {
        $crosspost->deny_account(['fb'=>true]);
    }
    if (isset($_REQUEST['vk_deny'])) {
        $crosspost->deny_account(['vk'=>true]);
    }

    if (isset($_REQUEST['act']) && $_REQUEST['act']=='sendMessage') { 
        $crosspost->send_message($_REQUEST['newsId'], $_REQUEST['socText']);
    }

    if (isset($_REQUEST['win_close'])) {
        ?>
        <script>window.close();</script>
    <?
    }


}