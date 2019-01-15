<?php

/**
 * Вспомогательные функцыи для новостей
 * Class NewsHelper
 */
class NewsHelper {

    public static function getYouTube_src_img($url){
        $url = str_replace("//www.youtube.com/v/","",$url);
        $url = str_replace("http://youtu.be/","",$url);
        $ex = explode('?',$url);
        $url= $ex[0];
        return "http://img.youtube.com/vi/$url/0.jpg";
    }

    public static function getNewDate($time){  // обработка формата даты/времени
        $time =  strtotime($time);

        if(date('Y-m-d') == date('Y-m-d',$time))
            return self::timestampToTimezone($time,"Сьогодні о H:i") ;
        else
            return self::timestampToTimezone($time,"d.m.Y о H:i") ;
    }

    public static function getNewDateAdmin($time){  // обработка формата даты/времени
        $time =  strtotime($time);

        return date('H:i d.m.Y',$time);
    }

    public static function timestampToTimezone($timestamp,$mode='H:i d.m.Y'){
        $dt = new DateTime();
        $dt->setTimestamp($timestamp);
        $dt->setTimezone(new DateTimeZone("Europe/Kiev"));
        return $dt->format($mode);
    }

    public static function getYouTube($url,$width=640,$height=480){

        $url = str_replace("//www.youtube.com/v/","",$url);
        $url = str_replace("https://youtu.be/","",$url);
        $ex=explode('?v=',$url);$url= $ex[0];
        if(count($ex)>1){
             $url= $ex[1];
        }
        return "<iframe width=\"$width\" height=\"$height\"  src=\"//www.youtube.com/embed/$url?rel=0\" frameborder=\"0\" allowfullscreen></iframe>";
    }
     public static function isImage($path){
		if(is_array(getimagesize($path))){
		    return true;
		} 
		else {
		    return false;
		}

    }   
    //обрезка строки
    public static function clipTitle(
            $string, 
            $length = 45,
            $etc = '...',
            $charset='UTF-8',
            $break_words = false,
            $middle = false) {

    if ($length == 0) return '';
 
    if (strlen($string) > $length) {
        $length -= min($length, strlen($etc));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', 
                             mb_substr($string, 0, $length+1, $charset));
        }
        if(!$middle) {
            return mb_substr($string, 0, $length, $charset) . $etc;
        } else {
            return mb_substr($string, 0, $length/2, $charset) . 
                             $etc . 
                             mb_substr($string, -$length/2, $charset);
        }
    } else {
        return $string;
    }
}

}