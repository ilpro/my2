<?php

class FollowNews {
    /*
     * Получить количество просмотра новостей с фолловневс для каждой новости с списка
     * $newsList-список новостей для которих ищем просмотри на сиитаргет
     * return array
     */

    public function getFollowNewsUsers($newsList) {
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
            $dbname = 'follownews';
        }
        //Подключаемся к бд сиитаргет и витаскиваем просмотри(для етово формировали список урл)
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=' . $dbname, $user, $pass);
           
            if ($dbh->query('SELECT `newsUrl`,`newsVisits` from tbl_news WHERE `sourceId`=227 AND `newsUrl` in (' . $arr . ')') != false) {
                foreach ($dbh->query('SELECT `newsUrl`,`newsVisits` from tbl_news WHERE `sourceId`=227 AND `newsUrl` in (' . $arr . ')') as $row) {
                    $seeTargetList[] = array('name' => $row['newsUrl'], 'count' => $row['newsVisits']);
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
     * Получить количество просмотра новостей с фолловневс для одной новости
     * $new-одна новость
     * return array
     */

    public function getOneFollowNewsUser($news) {
        $arr = '';
        $seeTargetList = [];
        $seeTargetList2 = [];
        $newsType = ($news['newsType'] == 0) ? 'news' : 'article';
        $newsId = $news['newsId'];
        $server_path = $_SERVER['SERVER_NAME'];
        $arr.='"http://' . $server_path . '/' . $newsType . '/' . $newsId . '"';
   //штоби на локалке работало(только для тестов на локалке, штоби работать с подставной бд)
        if ($_SERVER['SERVER_NAME'] == 'ednist') {
            $user = 'root'; // заданное вами имя пользователя, либо определенное провайдером
            $pass = ''; // заданный вами пароль
            $dbname = 'devednist';
        } else {
            $user = 'root'; // заданное вами имя пользователя, либо определенное провайдером
            $pass = 'FEWz4h3E'; // заданный вами пароль
            $dbname = 'follownews';
        }
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=' . $dbname, $user, $pass);
           
            if ($dbh->query('SELECT `newsUrl`,`newsVisits` from tbl_news WHERE `newsUrl` in (' . $arr . ')') != false) {
                foreach ($dbh->query('SELECT `newsUrl`,`newsVisits` from tbl_news WHERE `newsUrl` in (' . $arr . ')') as $row) {
                    $seeTargetList[] = array('name' => $row['newsUrl'], 'count' => $row['newsVisits']);
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
