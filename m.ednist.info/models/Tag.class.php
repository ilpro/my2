<?php

/**
 * Работа с тегами новостей
 * Class Tag
 */
class Tag
{

    private $table = 'tbl_tag';

    /**
     * @param int $limit
     * @return array|bool|string
     */
    public function getTags($limit = 1000)
    {

//        db::sql("CALL getTagsAll(_1)");
        db::sql("SELECT * FROM `tbl_tag` ORDER BY `tagId` DESC LIMIT ".$limit);
        $params = array($limit);
        db::addParameters($params);
        $result = db::query();

        return $result;
    }

    /**
     * @param int $limit
     * @return array|bool|string
     */
    public function getTagsCond($condition)
    {

        db::sql("SELECT * FROM `tbl_tag` ".$condition);
        $result = db::query();

        return $result;
    }

    /** отрисовка тега
     * @param int $tagId
     */
    public function get_stripes($tagId = 0)
    {

        global $ini;

        $this->sql = "SELECT * FROM tbl_tag ";

        if ($tagId != 0)

            $this->sql .= " WHERE tagId=$tagId";

        $this->sql .= " ORDER BY tagId DESC";

        db::sql($this->sql);


        $this->result = db::query();


        if (is_array($this->result))

            foreach ($this->result as $item) {

                require Config::getAdminRoot() . '/views/tag/tagStripe.view.php';

            }


    }

    /**
     *Добавление нового тега
     */
    function addNewTag()
    {

        db::sql("INSERT INTO tbl_tag SET tagName='New Tag'");

        $result = db::execute();

        if (!$result) {

            echo json_encode(array('error' => 'error'));

        } else {

            $this->get_stripes($result);

        }

    }

    /**
     * @param int $limit
     * @return array|bool|string
     */
    public function getPopularTags($limit = 20)
    {

        db::sql("CALL getPopularTags(_1)");
        $params = array($limit);
        db::addParameters($params);
        $result = db::query();

        return $result;
    }


    /**Получить тег по id
     * @param $id
     * @return bool
     */
    public function getTagById($id)
    {

        db::sql("SELECT * FROM $this->table WHERE `tagId`=" . $id);
        $result = db::query();

        if ($result[0]) return $result[0];
        else return false;
    }

    /** Сохранить полностю тег
     * @param $TagId
     * @param $TagName
     * @param $TagSearch
     */
    function saveAll(
        $TagId,
        $TagName,
        $TagSearch)
    {
        db::sql("CALL saveTag(_1,_2,_3)");

        $params = array(
            $TagId,
            $TagName,
            $TagSearch);

        db::addParameters($params);
        db::execute();

        $now = new DateTime();
        $response = array('saved' => $now->format('Y-m-d H:i:s'));

        return $response;

    }


    /** Удаление по id
     * @param $tagId
     * @return string
     */
    public function deleteTag($tagId)
    {

        global $ini;

        db::sql("CALL tagDel(_1)");

        $params = array($tagId);

        db::addParameters($params);

        $result = db::execute();

        return $result;

    }
}