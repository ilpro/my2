<?php

class Search {

    //Проверка на соответствие введеного запроса патермну возможних значений
    function separate($string) {
        $pattern = "#.*?([A-Za-zА-Яа-я0-9ієї._]+).*?#u";
        $rep = "+$1* ";
        return preg_replace($pattern, $rep, $string);
    }

    //По типу введеного запроса формируем необходимий поиск
    function headerString($headerString, $excludeId = 0) {
        $type = $this->typeOfString($headerString);
        switch ($type) {
            case 'url':
                $this->searchUrl($headerString, $excludeId);
                break;
            case 'int':
                $this->searchId($headerString, $excludeId);
                break;
            case 'name':
                $this->searchName($headerString, $excludeId);
                //echo "i это пирог";
                break;
        }
    }

    //Узнаем тип введеного запроса
    function typeOfString($string) {
        preg_match("/^http/", $string, $url);
        preg_match("/[0-9]+/u", $string, $int);
        preg_match("/[а-яА-Яa-zA-Z]+/u", $string, $name);
        if (is_array($url) and ! empty($url)) {
            return 'url';
            exit;
        }
        if (is_array($int) and ! empty($int)) {
            return 'int';
            exit;
        }
        if (is_array($name) and ! empty($name)) {
            return 'string';
            exit;
        }
    }

    //Узнаем язик введеного запроса
    function layout($string) {
        if (preg_match_all("/[а-яА-Я][іїґєІЇҐЄ]+/u", $string) > 0) {
            return 'ua';
        }
        if (preg_match_all("/[а-яА-Я]+/u", $string) > 0) {
            return 'ru';
        } elseif (preg_match_all("/[a-zA-Z]+/u", $string) > 0) {
            return 'en';
        }
    }

    public static function mkSearchDate($date = '') { // формирование даты в строку поиска
        $m = date("m");
        if ($date != '')
            $m = date("m", $date);

        switch ($m) {
            case '01':
                $myString = ' январь січень зима';
                break;

            case '02':
                $myString = ' февраль лютий зима';
                break;

            case '03':
                $myString = ' март березень весна';
                break;

            case '04':
                $myString = ' апрель квітень весна';
                break;

            case '05':
                $myString = ' май травень весна';
                break;

            case '06':
                $myString = ' июнь червень лето літо';
                break;

            case '07':
                $myString = ' июль липень лето літо';
                break;

            case '08':
                $myString = ' август серпень лето літо';
                break;

            case '09':
                $myString = ' сентябрь вересень осень осінь';
                break;

            case '10':
                $myString = ' октябрь жовтень осень осінь';
                break;

            case '11':
                $myString = ' ноябрь листопад осень осінь';
                break;

            case '12':
                $myString = ' декабрь грудень зима';
                break;
        }
        $mydate = 'dd' . date("d") . ' mm' . date("m") . ' yy' . date("Y") . ' ' . date("Y") . $myString;
        if ($date != '') {
            $mydate = 'dd' . date("d", $date) . ' mm' . date("m", $date) . ' yy' . date("Y", $date) . ' ' . date("Y", $date) . $myString;
        }

        return $mydate;
    }

    //Поиск новстей по словам/буквам
    function searchString($find, $newsType, $userId) {
        $type = $this->typeOfString($find);

        switch ($type) {
            case 'int':
                return $this->searchId($find, $newsType, $userId);
                break;
            case 'string':
                return $this->searchAdminNews($find, $newsType, $userId);
                break;
        }
    }

    //Поиск новостей по Id в админке
    public function searchId($id, $newsType, $userId) {

        $newsObj = new NewsAdmin();
        $res[] = $newsObj->getNewsByIdAdmin($id);

        if ($res[0] != false) {
            if (isset($newsType) && $newsType != '') {
                if ($res[0]['newsType'] != $newsType)
                    $res = false;
            }
            if (isset($userId) && $userId != '') {
                if ($res[0]['userId'] != $userId)
                    $res = false;
            }
        }
        else {
            $res = false;
        }
        return $res;
    }

    //Страховка на случай ввода не тим язиком(на клвавиатуре)
    function changeLayout($string) {
        $layout = $this->layout($string);
        if ($layout == 'en') {
            $arrReplace1 = array('q' => 'й', 'w' => 'ц', 'e' => 'у', 'r' => 'к', 't' => 'е', 'y' => 'н', 'u' => 'г', 'i' => 'ш', 'o' => 'щ', 'p' => 'з', '[' => 'х', ']' => 'ъ', 'a' => 'ф', 's' => 'ы', 'd' => 'в', 'f' => 'а', 'g' => 'п', 'h' => 'р', 'j' => 'о', 'k' => 'л', 'l' => 'д', ';' => 'ж', "'" => 'э', 'z' => 'я', 'x' => 'ч', 'c' => 'с', 'v' => 'м', 'b' => 'и', 'n' => 'т', 'm' => 'ь', ',' => 'б', '.' => 'ю', '/' => '.', '`' => 'ё', 'Q' => 'Й', 'W' => 'Ц', 'E' => 'У', 'R' => 'К', 'T' => 'Е', 'Y' => 'Н', 'U' => 'Г', 'I' => 'Ш', 'O' => 'Щ', 'P' => 'З', '{' => 'Х', '}' => 'Ъ', 'A' => 'Ф', 'S' => 'Ы', 'D' => 'В', 'F' => 'А', 'G' => 'П', 'H' => 'Р', 'J' => 'О', 'K' => 'Л', 'L' => 'Д', ':' => 'Ж', '"' => 'Э', '|' => '/', 'Z' => 'Я', 'X' => 'Ч', 'C' => 'С', 'V' => 'М', 'B' => 'И', 'N' => 'Т', 'M' => 'Ь', '<' => 'Б', '>' => 'Ю', '?' => ',', '~' => 'Ё', '@' => '"', '#' => '№', '$' => ';', '^' => ':', '&' => '?');
            $arrReplace2 = array('q' => 'й', 'w' => 'ц', 'e' => 'у', 'r' => 'к', 't' => 'е', 'y' => 'н', 'u' => 'г', 'i' => 'ш', 'o' => 'щ', 'p' => 'з', '[' => 'х', ']' => 'ї', 'a' => 'ф', 's' => 'і', 'd' => 'в', 'f' => 'а', 'g' => 'п', 'h' => 'р', 'j' => 'о', 'k' => 'л', 'l' => 'д', ';' => 'ж', "'" => 'є', 'z' => 'я', 'x' => 'ч', 'c' => 'с', 'v' => 'м', 'b' => 'и', 'n' => 'т', 'm' => 'ь', ',' => 'б', '.' => 'ю', '/' => '.', '`' => 'ё', 'Q' => 'Й', 'W' => 'Ц', 'E' => 'У', 'R' => 'К', 'T' => 'Е', 'Y' => 'Н', 'U' => 'Г', 'I' => 'Ш', 'O' => 'Щ', 'P' => 'З', '{' => 'Х', '}' => 'Ї', 'A' => 'Ф', 'S' => 'І', 'D' => 'В', 'F' => 'А', 'G' => 'П', 'H' => 'Р', 'J' => 'О', 'K' => 'Л', 'L' => 'Д', ':' => 'Ж', '"' => 'Є', '|' => 'Ґ', 'Z' => 'Я', 'X' => 'Ч', 'C' => 'С', 'V' => 'М', 'B' => 'И', 'N' => 'Т', 'M' => 'Ь', '<' => 'Б', '>' => 'Ю', '?' => ',', '~' => 'Ё', '@' => '"', '#' => '№', '$' => ';', '^' => ':', '&' => '?');
        } elseif ($layout == 'ru') {
            $arrReplace1 = array('й' => 'q', 'ц' => 'w', 'у' => 'e', 'к' => 'r', 'е' => 't', 'н' => 'y', 'г' => 'u', 'ш' => 'i', 'щ' => 'o', 'з' => 'p', 'х' => '[', 'ъ' => ']', 'ф' => 'a', 'ы' => 's', 'в' => 'd', 'а' => 'f', 'п' => 'g', 'р' => 'h', 'о' => 'j', 'л' => 'k', 'д' => 'l', 'ж' => ';', 'э' => "&#039;", 'я' => 'z', 'ч' => 'x', 'с' => 'c', 'м' => 'v', 'и' => 'b', 'т' => 'n', 'ь' => 'm', 'б' => ',', 'ю' => '.', '.' => '/', 'ё' => '`', 'Й' => 'Q', 'Ц' => 'W', 'У' => 'E', 'К' => 'R', 'Е' => 'T', 'Н' => 'Y', 'Г' => 'U', 'Ш' => 'I', 'Щ' => 'O', 'З' => 'P', 'Х' => '{', 'Ъ' => '}', 'Ф' => 'A', 'Ы' => 'S', 'В' => 'D', 'А' => 'F', 'П' => 'G', 'Р' => 'H', 'О' => 'J', 'Л' => 'K', 'Д' => 'L', 'Ж' => ':', 'Э' => '"', '/' => '|', 'Я' => 'Z', 'Ч' => 'X', 'С' => 'C', 'М' => 'V', 'И' => 'B', 'Т' => 'N', 'Ь' => 'M', 'Б' => '<', 'Ю' => '>', ',' => '?', 'Ё' => '~', '"' => '@', '№' => '#', ';' => '$', ':' => '^', '?' => '&');
            $arrReplace2 = array('й' => 'й', 'ц' => 'ц', 'у' => 'у', 'к' => 'к', 'е' => 'е', 'н' => 'н', 'г' => 'г', 'ш' => 'ш', 'щ' => 'щ', 'з' => 'з', 'х' => 'х', 'ъ' => 'ї', 'ф' => 'ф', 'ы' => 'і', 'в' => 'в', 'а' => 'а', 'п' => 'п', 'р' => 'р', 'о' => 'о', 'л' => 'л', 'д' => 'д', 'ж' => 'ж', 'э' => "є", 'я' => 'я', 'ч' => 'ч', 'с' => 'с', 'м' => 'ь', 'и' => 'и', 'т' => 'т', 'ь' => 'ь', 'б' => 'б', 'ю' => 'ю', '.' => '.', 'ё' => "'", 'Й' => 'Й', 'Ц' => 'Ц', 'У' => 'У', 'К' => 'К', 'Е' => 'Е', 'Н' => 'Н', 'Г' => 'Г', 'Ш' => 'Ш', 'Щ' => 'Щ', 'З' => 'З', 'Х' => 'Х', 'Ъ' => 'Ї', 'Ф' => 'Ф', 'Ы' => 'І', 'В' => 'В', 'А' => 'А', 'П' => 'П', 'Р' => 'Р', 'О' => 'О', 'Л' => 'Л', 'Д' => 'Д', 'Ж' => 'Ж', 'Э' => 'Є', '/' => 'Ґ', 'Я' => 'Я', 'Ч' => 'Ч', 'С' => 'С', 'М' => 'М', 'И' => 'И', 'Т' => 'Т', 'Ь' => 'Ь', 'Б' => 'Б', 'Ю' => 'Ю', ',' => ',', 'Ё' => "₴", '"' => '"', '№' => '№', ';' => ';', ':' => ':', '?' => '?');
        } elseif ($layout == 'ua') {
            $arrReplace1 = array('й' => 'q', 'ц' => 'w', 'у' => 'e', 'к' => 'r', 'е' => 't', 'н' => 'y', 'г' => 'u', 'ш' => 'i', 'щ' => 'o', 'з' => 'p', 'х' => '[', 'ї' => ']', 'ф' => 'a', 'і' => 's', 'в' => 'd', 'а' => 'f', 'п' => 'g', 'р' => 'h', 'о' => 'j', 'л' => 'k', 'д' => 'l', 'ж' => ';', 'є' => "&#039;", 'я' => 'z', 'ч' => 'x', 'с' => 'c', 'м' => 'v', 'и' => 'b', 'т' => 'n', 'ь' => 'm', 'б' => ',', 'ю' => '.', '.' => '/', "'" => '`', 'Й' => 'Q', 'Ц' => 'W', 'У' => 'E', 'К' => 'R', 'Е' => 'T', 'Н' => 'Y', 'Г' => 'U', 'Ш' => 'I', 'Щ' => 'O', 'З' => 'P', 'Х' => '{', 'Ї' => '}', 'Ф' => 'A', 'І' => 'S', 'В' => 'D', 'А' => 'F', 'П' => 'G', 'Р' => 'H', 'О' => 'J', 'Л' => 'K', 'Д' => 'L', 'Ж' => ':', 'Э' => '"', 'Ґ' => '|', 'Я' => 'Z', 'Ч' => 'X', 'С' => 'C', 'М' => 'V', 'И' => 'B', 'Т' => 'N', 'Ь' => 'M', 'Б' => '<', 'Ю' => '>', ',' => '?', "₴" => "~", '"' => '@', '№' => '#', ';' => '$', ':' => '^', '?' => '&');
            $arrReplace2 = array('й' => 'й', 'ц' => 'ц', 'у' => 'у', 'к' => 'к', 'е' => 'е', 'н' => 'н', 'г' => 'г', 'ш' => 'ш', 'щ' => 'щ', 'з' => 'з', 'х' => 'х', 'ї' => 'ъ', 'ф' => 'ф', 'і' => 'ы', 'в' => 'в', 'а' => 'а', 'п' => 'п', 'р' => 'р', 'о' => 'о', 'л' => 'л', 'д' => 'д', 'ж' => 'ж', 'є' => "э", 'я' => 'я', 'ч' => 'ч', 'с' => 'с', 'м' => 'ь', 'и' => 'и', 'т' => 'т', 'ь' => 'ь', 'б' => 'б', 'ю' => 'ю', '.' => '.', "'" => "ё", 'Й' => 'Й', 'Ц' => 'Ц', 'У' => 'У', 'К' => 'К', 'Е' => 'Е', 'Н' => 'Н', 'Г' => 'Г', 'Ш' => 'Ш', 'Щ' => 'Щ', 'З' => 'З', 'Х' => 'Х', 'Ї' => 'Ъ', 'Ф' => 'Ф', 'І' => 'Ы', 'В' => 'В', 'А' => 'А', 'П' => 'П', 'Р' => 'Р', 'О' => 'О', 'Л' => 'Л', 'Д' => 'Д', 'Ж' => 'Ж', 'Э' => 'Є', 'Ґ' => '/', 'Я' => 'Я', 'Ч' => 'Ч', 'С' => 'С', 'М' => 'М', 'И' => 'И', 'Т' => 'Т', 'Ь' => 'Ь', 'Б' => 'Б', 'Ю' => 'Ю', ',' => ',', '₴' => "Ё", '"' => '"', '№' => '№', ';' => ';', ':' => ':', '?' => '?');
        } else {
            return $string;
            exit;
        }
        $arr = [];
        $arr1 = strtr($string, $arrReplace1);
        $arr2 = strtr($string, $arrReplace2);
        $arr[] = $arr1;
        $arr[] = $arr2;
        return $arr;
    }

    //Поиск в админке(поиск+сортировка)
    public function searchAdminNews($find, $newsType, $userId) {

        $newsObj = new NewsAdmin();
        $res = [];

        if ($find != '') {
            $res = $this->searchAdminFulltext(Clear::dataS($find));
        } else {
            $res = $newsObj->getNewsAdmin(' ORDER BY `newsTimePublic` DESC ');
        }

        if ($res != false) {

            $tmpRes = [];
            foreach ($res as $material) {

                if (isset($newsType) && $newsType != '') {
                    if ($material['newsType'] != $newsType)
                        continue;
                }

                if (isset($userId) && $userId != '') {
                    if ($material['userId'] != $userId)
                        continue;
                }

                $tmpRes[] = $newsObj->getNewsByIdAdmin($material['newsId']);
            }

            $res = $tmpRes;
        }

        return $res;
    }

    //Полнотекстовий админский поиск
    public function searchAdminFulltext($string) {
        $params = [];
        $layout = $this->changeLayout($string);
        foreach ($layout as $key => $material) {
            $params[] = $this->separate($material);
        }
        $params[] = $this->separate($string);
        db::sql("CALL fulltextAdmin(_1,_2,_3)");
        db::addParameters($params);
        $result = db::query();
        return $result;
    }

    //Полнотекстовий клиентский поиск
    public function searchFulltext($string,$start=0,$count=10) {

        $params = [];
       // $layout = $this->changeLayout($string);
       /* foreach ($layout as $key => $material) {
            $params[] = $this->separate($material);
        }*/
        $params[] = $this->separate($string);
        $params[]=$start;
        $params[]=$count;
       // db::sql("CALL fulltextClient(_1,_2,_3)");
        db::sql("CALL fulltextClientLight(_1,_2,_3)");
        //db::addParameters($params);
        db::addParameters($params);
        $result = db::query();
        return $result;
    }

    public function searchNews($find,$start=0,$count=10) {

        $newsObj = new News();

        $lenght = mb_strlen(str_replace(" ", "", $find), 'UTF-8');
        if ($lenght > 2) {
            $search_res = $this->searchFulltext(Clear::dataS($find),$start,$count);
            if (is_array($search_res)) {
                $i = 0;
                foreach ($search_res as $key => $material) {
                    $i++;
                    $search_res[$key]['variant'] = ($i % 2 == 1 || $i == 1) ? 1 : 2;
                    $search_res[$key]['newsTime'] = NewsHelper::getNewDate($material['newsTime']);
                }
                $search_res = $newsObj->newsBlocksRand(99, 3, $search_res);

                return $search_res;
            } else
                return false;
        } else
            return false;
    }

    /**
     * Визов процедури brandSearch для поиска досье
     * @param string $string - условия для запроса
     * @return array - массив
     */
    public function searchBrandstext($string) {
     /*   $layout = $this->changeLayout($string);
        $params = [];
        foreach ($layout as $key => $material) {
            $params[] = $material;
        }
        $params[] = $string;*/
     //   db::sql("CALL brandSearch(_1,_2,_3)");
      //  db::addParameters($params);
          db::sql("CALL brandSearchLight(_1)");
        db::addParameters($string);
        $result = db::query();
        return $result;
    }

    /**
     * Выборка с базы досье, согласно заданым условиям
     * @param string $find - условия для запроса
     * @return array - массив
     */
    public function searchBrands($find) {
        $lenght = mb_strlen(str_replace(" ", "", $find), 'UTF-8');
        if ($lenght > 2) {
            $search_res = $this->searchBrandstext(Clear::dataS($find));

            if (!empty($search_res)) {
                return $search_res;
            } else
                return false;
        } else
            return false;
    }

    /**
     * Визов процедури themesSearch для поиска тем
     * @param string $string - условия для запроса
     * @return array - массив
     */
    public function searchThemestext($string) {
        $layout = $this->changeLayout($string);
        $params = [];
        foreach ($layout as $key => $material) {
            $params[] = $material;
        }
        $params[] = $string;
        db::sql("CALL themesSearch(_1,_2,_3)");
        db::addParameters($params);
        $result = db::query();
        return $result;
    }

    /**
     * Выборка с базы тем, согласно заданым условиям
     * @param string $find - условия для запроса
     * @return array - массив
     */
    public function searchThemes($find) {
        $lenght = mb_strlen(str_replace(" ", "", $find), 'UTF-8');
        if ($lenght > 2) {
            $search_res = $this->searchThemestext(Clear::dataS($find));

            if (!empty($search_res)) {
                return $search_res;
            } else
                return false;
        } else
            return false;
    }

    /**
     * Выборка с базы тегов, согласно заданым условиям
     * @param string $find - условия для запроса
     * @return array - массив
     */
    public function searchTags($find) {
        $lenght = mb_strlen(str_replace(" ", "", $find), 'UTF-8');
        if ($lenght > 2) {
            $params[] = $find;
            db::sql("CALL tagAdminSearch(_1)");
            db::addParameters($params);
            $result = db::query();

            if (!empty($result)) {
                return $result;
            } else
                return false;
        } else
            return false;
    }

    /**
     * Выборка с базы регионов, согласно заданым условиям
     * @param string $find - условия для запроса
     * @return array - массив
     */
    public function searchRegions($find) {
        $lenght = mb_strlen(str_replace(" ", "", $find), 'UTF-8');
        if ($lenght > 2) {
            $params[] = $find;
            db::sql("CALL regionAdminSearch(_1)");
            db::addParameters($params);
            $result = db::query();

            if (!empty($result)) {
                return $result;
            } else
                return false;
        } else
            return false;
    }

    /**
     * Получить поисковый текст с новости по ID
     * @param $newsId
     * @return string
     */
    function getSearchText($newsId, $isVideo, $isGallery) {

        db::sql("CALL getSearchText(_1)");
        db::addParameters([$newsId]);
        $result = db::query();

        $result[0]['newsText'] = Clear::wordHTML($result[0]['newsText'], '');
        $result[0]['newsText'] = Clear::preposition($result[0]['newsText']);
        $textik = implode(' ', $result[0]);
        $result = Search::mkSearchDate() . ' ' . $textik;
        if ($isVideo != '') {
            $result = $result . ' ' . $isVideo;
        }
        if ($isGallery != '') {
            $result = $result . ' ' . $isGallery;
        }
        return $result;
    }

}

?>