<?
/*
    //Config::incStyleCss('jquery.multyselect.css');
    Config::incStyleCss('jquery.dropdown.css');
    Config::incStyleCss('global.css');
    //Config::incStyleCss('ui/jquery-ui.custom.min.css');
    //Config::incStyleCss('jquery.datetimepicker.css');
    //Config::incStyleCss('jquery.Jcrop.css');
    //Config::incStyleCss('mkSelect.css');
    Config::incStyleCss('page.news.css');

    Config::incJavaScript('jquery-1.8.2.min.js');
    //Config::incJavaScript('jquery-ui-1.10.4.js');
    //Config::incJavaScript('functions.js');
    //Config::incJavaScript('jquery.datetimepicker.js');

    //Config::incJavaScript('jquery.multiselect.js');
    Config::incJavaScript('jquery.dropdown.js');    
    //Config::incJavaScript('jquery.Jcrop.js');    
    //Config::incJavaScript('jquery.mkBlink.min.js');
    //Config::incJavaScript('jquery.mkSelect.js');
    //Config::incJavaScript('jquery.mkUpload.js');

    //Config::incJavaScript('tinymce/js/tinymce/tinymce.min.js');

    //Config::incJavaScript('md5.js');
    //Config::incJavaScript('select2.js');
    //Config::incJavaScript('select2_locale_ru.js');
    //Config::incJavaScript('functions.js');
    
    
    //Config::incJavaScript('jquery.autocomplete.js','bottom');
    //Config::incJavaScript('jquery.ba-bbq.min.js','bottom');    //MAX
    
    Config::incJavaScript('page.soc_accounts.js');
    //Config::incJavaScript('page.newspage.js');
    
    //Config::incJavaScript('jquery.mkCondition.js');*/
require_once "head.view.php";
?>
    <div class="head">
        <div class="clear"></div> 
        <? 
        include $adminPath."/views/block/logo_item.view.php";            
        include $adminPath."/views/block/menu_item.view.php";
        ?>
        <div class="filter floatRight">
            <div class="exit" id="logout">
                <i></i>
            </div>
        </div>
    </div>
    <div class="content soc_accounts">
 <br/>   
<h2>Настройки приложений и страниц:</h2>
<br/>
<form id="config_form">
<div class="inputblock">
    <label>
        <h3>Twitter Api Key:</h3>
        <input type="text" class="newsarea" name="tw_api_key" value="<?=$crosspost->get_par('apps_options','tw','api_key');?>" />
    </label>
</div>
<div class="inputblock">
    <label>
        <h3>Twitter Api Secret:</h3>
        <input type="text" class="newsarea" name="tw_api_secret" value="<?=$crosspost->get_par('apps_options','tw','api_secret');?>" />
    </label>
</div>
<div class="inputblock">
    <label>
        <h3>Facebook App ID:</h3>
        <input type="text" class="newsarea" name="fb_app_id"  value="<?=$crosspost->get_par('apps_options','fb','app_id');?>" />
    </label>
</div>
<div class="inputblock">
    <label>
        <h3>Facebook App Secret:</h3>
        <input type="text" class="newsarea" name="fb_app_secret"  value="<?=$crosspost->get_par('apps_options','fb','app_secret');?>" />
    </label>
</div>
<div class="inputblock">
    <label>
        <h3>Vk App ID:</h3>
        <input type="text" class="newsarea" name="vk_app_id" value="<?=$crosspost->get_par('apps_options','vk','app_id');?>" />
    </label>
</div>
<div class="inputblock">
    <label>
        <h3>ID страницы FB:</h3>
        <input type="text" class="newsarea" name="fb_page_id" value="<?=$crosspost->get_par('user_settings','fb','page_id');?>" />
    </label>
</div>
<div class="inputblock">
    <label>
        <h3>ID группы VK:</h3>
        <input type="text" class="newsarea" name="vk_group_id" value="<?=$crosspost->get_par('user_settings','vk','group_id');?>" />
    </label>
</div>
<div class="inputblock">
    <label>
        <h3></h3>
        <input value="Сохранить настройки" class="btn low" type="button" id="save_settings" style="width: auto;" /> 
    </label>
</div>
</form>

<br/><br/>
</div>
<div class="content clear">
<p>Подключить аккаунты:</p>

<div class="col-3">
    <h4>Facebook</h4>
    <?
    /******************** FACEBOOK **************************/

    if( empty( $user_data['fb'] ) ) {
    ?>
        <a href="" class="auth btn" data-soc="fb">Подключить аккаунт</a>
    <?
    } else {
    ?>
        <a href="" class="deny_auth btn" data-soc="fb">Отключить аккаунт</a>
        <p class="namedata"><? echo( $user_data['fb']['name'] ); ?></p>
    <?
    }
    ?>
</div>
<div class="col-3">

    <h4>Twitter</h4>
    <?
    /******************** TWITTER **************************/
    if( empty( $user_data['tw'] ) ) {
    ?>
        <a href="" class="auth btn" data-soc="tw">Подключить аккаунт</a>
    <?
    } else {
    ?>
        <a href="" class="deny_auth btn" data-soc="tw">Отключить аккаунт</a>
        <p class="namedata"><? echo( $user_data['tw']->name ); ?></p>
    <?
    }
    ?>
</div>
<div class="col-3">
    <h4>Vkontakte</h4>
    <?
    /******************** VKONTAKTE **************************/
    if( empty( $user_data['vk'] ) ) {
    ?>
        <a class="btn" id="send_vk_token">Подключить аккаунт</a>
        <p>Разрешите доступ Вконтакте, перейдя по сслыке: <a href="<?=$crosspost->get_auth_url( array('vk'=>true) );?>" target="_blank">Vkontakte</a></p>
        <p>После этого:</p>
        <p>Скопируйте адрес из адресной строки браузера и вставьте сюда: </p>
        <input type="text" class="newsarea" name="vk_access_token" />
        
    <?
    } else {

        if( $user_data['vk']->error ) {

            echo 'Error: '.$user_data['vk']->error->error_msg.'<br/>';
            echo 'ID: '.$user_data['vk']->error->captcha_sid.'<br/>';
            echo 'Код: <img src="'.$user_data['vk']->error->captcha_img.'"/><br/>';

            echo '<input type="text" id="captcha_vk" class="newsarea" /><input class="btn low" value="Отправить" id="submit_captcha_vk" /><br/><br/>';
        }

    ?>
        <a href="" class="deny_auth btn" data-soc="vk">Отключить аккаунт</a>

    <?
        if( !$user_data['vk']->error ) {
    ?>
        <p class="namedata"><? echo( $user_data['vk'][0]->first_name.' '.$user_data['vk'][0]->last_name ); ?></p>
    <?
        }
    }
    ?>
</div>
<br /><br />
</div>
<?  
include "foot.view.php";
?>