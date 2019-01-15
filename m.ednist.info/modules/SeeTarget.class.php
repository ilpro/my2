<?php

class SeeTarget {
    /*
     * Получить количество просмотра новостей с сиитаргет для каждой новости с списка
     * $newsList-список новостей для которих ищем просмотри на сиитаргет
     * return array
     */

    public function getSeeTargetUsers($newsList) {
        $arr = '';
        $seeTargetList = [];
        //Перебираем список новстей и формируем с их Id урл 
        foreach ($newsList as $key => $value) {
            $newsType = ($value['newsType'] == 0) ? 'news' : 'article';
            $newsId = $value['newsId'];
            $server_path = $_SERVER['SERVER_NAME'];
            $arr.='"http://' . $server_path . '/' . $newsType . '/' . $newsId . '"';
            if (COUNT($newsList) - 1 > $key) {
                $arr.=' , ';
            }
        }
        //штоби на локалке работало(только для тестов на локалке, штоби работать с подставной бд)
        if ($_SERVER['SERVER_NAME'] == 'ednist') {
            $user = 'root'; // заданное вами имя пользователя, либо определенное провайдером
            $pass = ''; // заданный вами пароль
            $dbname = 'devednist';
        } else {
            $user = 'root'; // заданное вами имя пользователя, либо определенное провайдером
            $pass = 'FEWz4h3E'; // заданный вами пароль
            $dbname = 'devseetarget';
        }

        //Подключаемся к бд сиитаргет и витаскиваем просмотри(для етово формировали список урл)
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=' . $dbname, $user, $pass);
            $domain = 0;
            foreach ($dbh->query('SELECT * from tbl_domain WHERE `domain`="' . $server_path . '"') as $key => $value) {
                $domain = $value[$key];
            }
            if ($dbh->query('SELECT * from tbl_pageStats_whole WHERE `domainId`=' . $domain . ' AND `pageUrl` in (' . $arr . ')') != false) {
                foreach ($dbh->query('SELECT * from tbl_pageStats_whole WHERE `domainId`=' . $domain . ' AND `pageUrl` in (' . $arr . ')') as $row) {
                    $seeTargetList[] = array('name' => $row['pageUrl'], 'count' => $row['pageSeen']);
                }
            }
           
            $dbh = null;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }


        $result = [];
        if (is_array($seeTargetList)) {

            foreach ($newsList as $key => $news) {
                $result[$key] = 0;
                $newsType = ($news['newsType'] == 0) ? 'news' : 'article';
                $newsId = $news['newsId'];
                $server_path = $_SERVER['SERVER_NAME'];
                $arr = 'http://' . $server_path . '/' . $newsType . '/' . $newsId;
                if (!empty($seeTargetList)) {
                    foreach ($seeTargetList as $key2 => $value) {
                        if ($arr == $value['name']) {
                            $result[$key] = $value['count'];
                        }
                    }
                }
            }
        }
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /*
     * Получить количество просмотра новостей с сиитаргет для одной новости
     * $new-одна новость
     * return array
     */

    public function getOneSeeTargetUser($news) {
        $arr = '';
        $seeTargetList = [];
        $seeTargetList2 = [];
        $newsType = ($news['newsType'] == 0) ? 'news' : 'article';
        $newsId = $news['newsId'];
        $server_path = $_SERVER['SERVER_NAME'];
        $arr.='"http://' . $server_path . '/' . $newsType . '/' . $newsId . '"';
        //штоби на локалке работало
        if ($_SERVER['SERVER_NAME'] == 'ednist') {
            $user = 'root'; // заданное вами имя пользователя, либо определенное провайдером
            $pass = ''; // заданный вами пароль
            $dbname = 'devednist';
        } else {
            $user = 'root'; // заданное вами имя пользователя, либо определенное провайдером
            $pass = 'FEWz4h3E'; // заданный вами пароль
            $dbname = 'devseetarget';
        }

        try {
            $dbh = new PDO('mysql:host=localhost;dbname=' . $dbname, $user, $pass);
            $domain = 0;
            foreach ($dbh->query('SELECT * from tbl_domain WHERE `domain`="' . $server_path . '"') as $key => $value) {
                $domain = $value[$key];
            }
            if ($dbh->query('SELECT * from tbl_pageStats_whole WHERE `domainId`=' . $domain . ' AND `pageUrl` in (' . $arr . ')') != false) {
                foreach ($dbh->query('SELECT * from tbl_pageStats_whole WHERE `domainId`=' . $domain . ' AND `pageUrl` in (' . $arr . ')') as $row) {
                    $seeTargetList[] = array('name' => $row['pageUrl'], 'count' => $row['pageSeen']);
                }
            }
            $dbh = null;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        $result = [];
        if (is_array($seeTargetList)) {
            $result[0] = 0;
            $newsType = ($news['newsType'] == 0) ? 'news' : 'article';
            $newsId = $news['newsId'];
            $server_path = $_SERVER['SERVER_NAME'];
            $arr = 'http://' . $server_path . '/' . $newsType . '/' . $newsId;
            if (!empty($seeTargetList)) {
                if ($arr == $seeTargetList[0]['name']) {
                    $result[0] = $seeTargetList[0]['count'];
                }
            }
          
        }


        if (!empty($result)) {
            return $result;
        } else {
            return 0;
        }
    }

}

?>
