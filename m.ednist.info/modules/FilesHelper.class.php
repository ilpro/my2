<?php


/**
 * Все операции с файлами, которые можно вынести с моделей
 * Class FilesHelper
 */
class FilesHelper {

    /**
     * Проверяем существованние файла на нашем сервере
     * @param $url - путь к файлу на сервере
     * @return bool
     */
    public static function checkServerFile($url) {

        if( file_exists($url) )
            return true;

        return false;
    }


}