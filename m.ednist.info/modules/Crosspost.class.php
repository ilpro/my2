<?php

/**
 *  Кросспостинг в соц. сети
 *  Оптимизация:
 *  - В таблице поля, форме инпуты, классе свойства - назвать одинаково.
 */

/**
 *  Libraries: tw - php 5.3+ | vk - php 5.3+ | fb - php 5.4+
 */

require_once('libs/facebook/src/Facebook/HttpClients/FacebookHttpable.php');
require_once('libs/facebook/src/Facebook/HttpClients/FacebookCurl.php');
require_once('libs/facebook/src/Facebook/HttpClients/FacebookCurlHttpClient.php');

require_once('libs/facebook/src/Facebook/Entities/AccessToken.php');
require_once('libs/facebook/src/Facebook/Entities/SignedRequest.php');

require_once('libs/facebook/src/Facebook/FacebookSession.php');
require_once('libs/facebook/src/Facebook/FacebookRedirectLoginHelper.php');
require_once('libs/facebook/src/Facebook/FacebookRequest.php');
require_once('libs/facebook/src/Facebook/FacebookResponse.php');
require_once('libs/facebook/src/Facebook/FacebookSDKException.php');
require_once('libs/facebook/src/Facebook/FacebookRequestException.php');
require_once('libs/facebook/src/Facebook/FacebookOtherException.php');
require_once('libs/facebook/src/Facebook/FacebookAuthorizationException.php');
require_once('libs/facebook/src/Facebook/GraphObject.php');
require_once('libs/facebook/src/Facebook/GraphSessionInfo.php');

require_once('libs/tmhOAuth/tmhOAuth.php');

require_once('libs/vkontakte/src/Vkontakte.php');


/**
 *  Namespaces
 */

use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;

use Facebook\Entities\AccessToken;
use Facebook\Entities\SignedRequest;

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;


class Crosspost {

    private $post_content = array();
    private $apps_options = array();
    private $user_settings = array();
    private $access_tokens = array();
    private $callback_url = '';

    private $table = "`tbl_social`";
    private $news_table = "`tbl_news`";

    private $tmhOAuth;
    private $fb_helper;
    private $fb_session;
    private $vkAPI;

    public function __construct( $post_content = array(), $apps_options = array(), $user_settings = array() ) {
        $this->post_content = $post_content;
        $this->apps_options = $apps_options;
        $this->user_settings = $user_settings;

        $this->callback_url = "http://".$_SERVER['SERVER_NAME']."/admin/controllers/crosspost.controller.php";

        if( empty( $apps_options ) && empty( $user_settings ) ) $this->load_settings();

    }

    public function load_settings() {

        $result=db::sql("SELECT * FROM $this->table WHERE `soc_accountsId`=1");
        $result=db::query();
        $result = $result[0];

        //Facebook setup data
        $this->apps_options['fb']['app_id'] = $result['soc_accountsFbAppId'];
        $this->apps_options['fb']['app_secret'] = $result['soc_accountsFbAppSecret'];
        $this->user_settings['fb']['page_id'] = $result['soc_accountsFbPageId'];
        $this->access_tokens['fb']['access_token'] = $result['soc_accountsFbToken'];

        //Twitter setup data
        $this->apps_options['tw']['api_key'] = $result['soc_accountsTwApiKey'];
        $this->apps_options['tw']['api_secret'] = $result['soc_accountsTwApiSecret'];
        $this->access_tokens['tw']['access_token'] = $result['soc_accountsTwToken'];
        $this->access_tokens['tw']['access_token_secret'] = $result['soc_accountsTwTokenSecret'];

        //Vkontakte setup data
        $this->apps_options['vk']['app_id'] = $result['soc_accountsVkAppId'];
        $this->user_settings['vk']['group_id'] = $result['soc_accountsVkGroupId'];
        $this->access_tokens['vk']['access_token'] = $result['soc_accountsVkToken'];
        $this->access_tokens['vk']['user_id'] = $result['soc_accountsVkUserId'];

        if( $this->apps_options['tw']['api_key'] != '' && $this->apps_options['tw']['api_secret']!='' && $this->apps_options['fb']['app_id'] != 0 && $this->apps_options['fb']['app_secret']!='' ) {

            $this->tmhOAuth = new tmhOAuth( array(
                'consumer_key' => $this->apps_options['tw']['api_key'],
                'consumer_secret' => $this->apps_options['tw']['api_secret']
            ));

            FacebookSession::setDefaultApplication( $this->apps_options['fb']['app_id'] , $this->apps_options['fb']['app_secret'] );
            $this->fb_helper = new FacebookRedirectLoginHelper( $this->callback_url . '?callback_fb=1' );

            return true;
        }
        else echo "Apps settings are required!";

        return false;
    }

    public function save_settings( $args=array() ) {

        if( !empty($args) ) {
            foreach( $args as $key=>$val) {

                if( $key == 'tw_api_key' ) $this->apps_options['tw']['api_key'] = ($val!='')?$val:'';
                if( $key == 'tw_api_secret' ) $this->apps_options['tw']['api_secret'] = ($val!='')?$val:'';

                if( $key == 'fb_app_id' ) $this->apps_options['fb']['app_id'] = ($val!=0)?$val:0;
                if( $key == 'fb_app_secret' ) $this->apps_options['fb']['app_secret'] = ($val!='')?$val:'';
                if( $key == 'fb_page_id' ) $this->user_settings['fb']['page_id'] = ($val!=0)?$val:0;

                if( $key == 'vk_app_id' ) $this->apps_options['vk']['app_id'] = ($val!=0)?$val:0;
                if( $key == 'vk_group_id' ) $this->user_settings['vk']['group_id'] = ($val!=0)?$val:0;
            }

            $result=db::sql("UPDATE $this->table SET
                `soc_accountsFbAppId`=".$this->apps_options['fb']['app_id'].",
                `soc_accountsFbAppSecret`='".$this->apps_options['fb']['app_secret']."',
                `soc_accountsFbPageId`=".$this->user_settings['fb']['page_id'].",
                `soc_accountsTwApiKey`='".$this->apps_options['tw']['api_key']."',
                `soc_accountsTwApiSecret`='".$this->apps_options['tw']['api_secret']."',
                `soc_accountsVkAppId`=".$this->apps_options['vk']['app_id'].",
                `soc_accountsVkGroupId`=".$this->user_settings['vk']['group_id']."
                 WHERE `soc_accountsId`=1");
            $result=db::execute();

            return $result;
        }

        return false;
    }

    public function get_par( $type, $soc, $name ) {

        foreach( $this->$type as $key=>$val ) {
            if( $key == $soc ) return $val[$name];
        }

        return false;
    }

    public function set_par( $type_name, $content ) {

        if( isset( $this->$type_name ) ) {

            $this->$type_name = $content;
            return true;
        }

        return false;
    }

    public function get_auth_url( $args = array() ) {

        if( isset( $args['tw'] ) ) {

            if( isset( $this->access_tokens['tw']['access_token'] ) && $this->access_tokens['tw']['access_token']!='' ) {

                return false;
            }
            else {
                $callback = isset($_REQUEST['oob']) ? 'oob' : $this->callback_url . '?callback_tw=1';
                $params = array(
                    'oauth_callback'     => $callback
                );

                $params['x_auth_access_type'] = 'write';

                $code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url('oauth/request_token', ''), $params);

                if ($code == 200) {
                    $_SESSION['tw']['oauth'] = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
                    $method = 'authorize'; //authenticate
                    $force  = ''; //&force_login=1
                    $authurl = $this->tmhOAuth->url("oauth/{$method}", '') . "?oauth_token={$_SESSION['tw']['oauth']['oauth_token']}{$force}";

                    return $authurl ;
                }
            }
        }

        if( isset( $args['fb'] ) ) {

            if( isset( $this->access_tokens['fb']['access_token'] ) && $this->access_tokens['fb']['access_token']!='' ) {

                return false;
            }
            else {

                return $this->fb_helper->getLoginUrl( array('publish_actions', 'publish_stream', 'manage_pages', 'user_groups', 'user_status', 'user_photos') );
            }
        }

        if( isset( $args['vk'] ) ) {

            if( isset( $this->access_tokens['vk']['access_token'] ) && $this->access_tokens['vk']['access_token']!='' ) {

                return false;
            }
            else if( isset($this->apps_options['vk']['app_id']) ) {

                return "http://api.vk.com/oauth/authorize?client_id={$this->apps_options['vk']['app_id']}&scope=wall,groups,offline,photos,audio,status,video&display=page&redirect_uri=http://api.vk.com/blank.html&response_type=token";
            }
        }

        return false;
    }

    public function get_access_token( $args ) {

        if( isset( $args['tw'] ) ) {

            $this->tmhOAuth->config['user_token']  = $_SESSION['tw']['oauth']['oauth_token'];
            $this->tmhOAuth->config['user_secret'] = $_SESSION['tw']['oauth']['oauth_token_secret'];

            $code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url('oauth/access_token', ''), array(
                'oauth_verifier' => $_REQUEST['oauth_verifier']
            ));

            if ($code == 200) {

                $access_token = $this->tmhOAuth->extract_params($this->tmhOAuth->response["response"]);
                db::sql("UPDATE $this->table SET `soc_accountsTwToken`='".$access_token['oauth_token']."',`soc_accountsTwTokenSecret`='".$access_token['oauth_token_secret']."' WHERE `soc_accountsId`=1");
                db::execute();

                $this->verify_access_token(['tw'=>true]);

                unset($_SESSION['tw']['oauth']);
                header("Location: {$this->callback_url}?win_close=1");
            } else {
                return $this->tmhOAuth;
            }
        }

        if( isset( $args['fb'] ) ) {

            if ( !isset( $this->fb_session ) || $this->fb_session === null ) {
                // no session exists

                try {
                    $this->fb_session = $this->fb_helper->getSessionFromRedirect();
                } catch( FacebookRequestException $ex ) {
                    // When Facebook returns an error
                    // handle this better in production code
                    print_r( $ex );
                } catch( Exception $ex ) {
                    // When validation fails or other local issues
                    // handle this better in production code
                    print_r( $ex );
                }

            }

            // see if we have a session
            if ( isset( $this->fb_session ) ) {

                // держать в базе
                $access_token = $this->fb_session->getToken();
                db::sql("UPDATE $this->table SET `soc_accountsFbToken`='".$access_token."' WHERE `soc_accountsId`=1");
                db::execute();

                // create a session using saved token or the new one we generated at login
                $this->fb_session = new FacebookSession( $this->fb_session->getToken() );

                header("Location: {$this->callback_url}?win_close=1");
            }
        }

        if( isset( $args['vk'] ) && isset( $args['url'] ) && $args['url']!='' ) {

            $url_arr = parse_url( $args['url'] );
            parse_str($url_arr['fragment'], $tmp_arr);

            foreach( $tmp_arr as $key=>$val ) {
                if( $key == 'access_token' ) $this->access_tokens['vk']['access_token'] = $val;
                if( $key == 'user_id' ) $this->access_tokens['vk']['user_id'] = $val;
            }

            if( isset($this->access_tokens['vk']['user_id']) && isset( $this->access_tokens['vk']['access_token'] ) ) {

                $this->vkAPI = new \BW\Vkontakte(['access_token' => $this->access_tokens['vk']['access_token']]);

                if( $user_data = $this->vkAPI->api('users.get',['uids'=>$this->access_tokens['vk']['user_id']])) {

                    db::sql("UPDATE $this->table SET
                        `soc_accountsVkToken`='".$this->access_tokens['vk']['access_token']."',
                        `soc_accountsVkUserId`='".$this->access_tokens['vk']['user_id']."'
                         WHERE `soc_accountsId`=1");
                    db::execute();

                    return true;
                }
            }
        }

        return false;
    }


    public function verify_access_token($args=array()) {

        if( isset( $args['tw'] ) ) {

            $this->tmhOAuth->config["user_token"] = $this->access_tokens['tw']['access_token'];
            $this->tmhOAuth->config["user_secret"] = $this->access_tokens['tw']['access_token_secret'];

            $this->tmhOAuth->request("GET", $this->tmhOAuth->url("1.1/account/verify_credentials"));

            // профиль юзера
            return $this->tmhOAuth->response["response"];
        }

        if( isset( $args['fb'] ) ) {

            // see if a existing session exists
            if ( isset( $this->access_tokens['fb']['access_token'] ) ) {
                // create new session from saved access_token
                $this->fb_session = new FacebookSession( $this->access_tokens['fb']['access_token'] );

                // validate the access_token to make sure it's still valid
                try {
                    if ( !$this->fb_session->validate() ) {
                        $session = null;
                    }
                } catch ( Exception $e ) {
                    // catch any exceptions
                    $this->fb_session = null;
                }
            }
            return true;
        }

        if( isset( $args['vk'] ) ) {

            if( isset( $this->access_tokens['vk']['access_token'] ) && $this->access_tokens['vk']['access_token'] != '' ) {

                $this->vkAPI = new \BW\Vkontakte(['access_token' => $this->access_tokens['vk']['access_token']]);
            }
            return true;
        }

        return false;
    }

    public function get_user_data( $args = array() ) {

        if( isset( $args['tw'] ) ) {

            return json_decode($this->verify_access_token(['tw'=>true]));
        }
        if( isset( $args['fb'] ) ) {

            $this->verify_access_token(['fb'=>true]);

            // graph api request for user data
            $request = new FacebookRequest( $this->fb_session, 'GET', '/me' );
            $response = $request->execute();
            // get response
            return $response->getGraphObject()->asArray();
        }
        if( isset( $args['vk'] ) ) {

            $this->verify_access_token(['vk'=>true]);

            $user_id = $this->access_tokens['vk']['user_id'];

            if( $user_data = $this->vkAPI->api('users.get',['uids'=>$user_id])) {
                return $user_data;
            }
        }

        return false;
    }

    public function set_soc_text( $news_id, $soc_text ) {

        db::sql("UPDATE $this->news_table SET `newsSocText`='".Clear::dataS($soc_text)."' WHERE `newsId`=".$news_id);
        db::execute();
    }

    public function send_message( $news_id, $soc_text='' ) {

        global $ini;

        if( $soc_text!='' ) $this->set_soc_text( $news_id, $soc_text );

        $this->sql = "SELECT * FROM `vtbl_news` WHERE `newsId`=".$news_id;
        db::sql($this->sql);
        $result=db::query();

        $this->post_content = [
            'link' => 'http://'.$_SERVER['SERVER_NAME'].'/new/'.$news_id,
            'message' => $result[0]['newsSocText'],
            'description' => $result[0]['newsSubheader'],
            'caption' => $result[0]['newsHeader']
        ];

        if( file_exists( $_SERVER['DOCUMENT_ROOT']."/media/images/".$news_id."/main/400.jpg" ) ) {
            $this->post_content['pic_tw'] = file_get_contents( $_SERVER['DOCUMENT_ROOT']."/media/images/".$news_id."/main/400.jpg" );
            $this->post_content['pic_fb'] = $ini['url.media']."images/".$news_id."/main/400.jpg?".time();
            $this->post_content['pic_vk'] = $_SERVER['DOCUMENT_ROOT']."/media/images/".$news_id."/main/400.jpg";
        }
        else { echo'Загрузите картинку'; return false; }

        if( $result[0]['newsPostTw']==1 ) {

            $this->verify_access_token(['tw'=>true]);

            $params = array(
                'status'   => $this->post_content['message'] . "\n" . $this->post_content['link'],
                'media[]' => $this->post_content['pic_tw']
            );

            $code = $this->tmhOAuth->request( 'POST', 'https://api.twitter.com/1.1/statuses/update_with_media.json',
                $params,
                true, // use auth
                true  // multipart
            );

            if ( $code == 200 ) {
//                echo "Twitter - success!";
            }else{
//                echo "Twitter error code = " . $code;
            }
        }

        if( $result[0]['newsPostFb']==1 ) {

            $this->verify_access_token(['fb'=>true]);

            // get profile public pages
            $request = new FacebookRequest( $this->fb_session, 'GET', '/me/accounts' );
            $response = $request->execute();
            $graphObject = $response->getGraphObject()->asArray();

            // set in page token for public page we need
            $page_token = '';
            foreach( $graphObject['data'] as $item ) {
                if($item->id == $this->user_settings['fb']['page_id']) $page_token = $item->access_token;
            }

            $params = array(
                'access_token'=> $page_token,
//                'name'=> $this->post_content['title'],
                'link' => $this->post_content['link'],
                'picture' => $this->post_content['pic_fb'],
                'description'=> $this->post_content['description'],
                'caption' => $this->post_content['caption'],
                'message' => $this->post_content['message']
            );

            // start posting request
            $request = new FacebookRequest(
                $this->fb_session,
                'POST',
                '/'.$this->user_settings['fb']['page_id'].'/feed',
                $params
            );

            $response = $request->execute();
            // get response
            $graphObject = $response->getGraphObject()->asArray();

            // print profile data
//            echo '<pre>' . print_r( $graphObject, 1 ) . '</pre>';

        }

        if( $result[0]['newsPostVk']==1 ) {

            $this->verify_access_token(['vk'=>true]);

            if ($this->vkAPI->postToPublic(
                $this->user_settings['vk']['group_id'],
                $this->post_content['message'],
                $this->post_content['pic_vk'],
                $this->post_content['link'],
                $this->post_content['publish_time']
            )) {

//                echo "Ура! Всё работает, пост добавлен\n";
            } else {

//                echo "Фейл, пост не добавлен(( ищите ошибку\n";
            }
        }

        $result=db::sql("UPDATE $this->news_table SET `newsSocTime`='0000-00-00 00:00:00', `newsSocTimePosted`=NOW() WHERE `newsId`=".$news_id);
        $result=db::execute();

        echo json_encode(['saved'=>date("H:i d.m.y",time())]);
    }

    public function deny_account( $args=array() ) {

        if( isset( $args['tw'] ) ) {

            db::sql("UPDATE $this->table SET `soc_accountsTwToken`='', `soc_accountsTwTokenSecret`='' WHERE `soc_accountsId`=1");
            db::execute();
        }
        if( isset( $args['fb'] ) ) {

            db::sql("UPDATE $this->table SET `soc_accountsFbToken`='' WHERE `soc_accountsId`=1");
            db::execute();
        }
        if( isset( $args['vk'] ) ) {

            db::sql("UPDATE $this->table SET `soc_accountsVkToken`='', `soc_accountsVkUserId`='' WHERE `soc_accountsId`=1");
            db::execute();
        }

    }


}