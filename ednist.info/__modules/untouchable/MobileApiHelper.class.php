<?

/**
 * Вспомогательный функционал для мобильного АПИ
 * Class MobileApiHelper
 */

class MobileApiHelper {

    // Отправка новости на пуш сервер
    public static function pushNotificationSend( $someNews ) {

        $settings['parse_app_id'] = "9PnyT4THXdCj38tzJwEupBRyaQdWv95TZVdP7nbZ";
        $settings['parse_rest_api_key'] = "Lnj8rwhT31oEgukjBrpdmmOZkcbCsEyRtwINLWgz";
        $content['data']['alert'] = $someNews['newsHeader'];
        $content['data']['news_id'] = $someNews['newsId'];
        $content['where']['channels'] = "global";
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header'=>"Content-Type: application/json\r\n".
                    "X-Parse-Application-Id: ".$settings['parse_app_id']."\r\n".
                    "X-Parse-REST-API-Key: ".$settings['parse_rest_api_key']."\r\n",
                'content' => json_encode($content)
            )
        );
        $context = stream_context_create($opts);
        $result = file_get_contents('https://api.parse.com/1/push', false, $context);
    }

}


