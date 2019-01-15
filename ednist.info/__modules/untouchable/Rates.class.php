<?
class Rates{
    public static function getRates(){
        return 0;
    }
    public static function getWeather(){
        return json_decode(file_get_contents('http://api.openweathermap.org/data/2.5/weather?q=Kyiv,ua&units=metric&lang=ru&appid=c15cc65c4116dc1b1f19145f6cc6fea7'),true);
    }
}