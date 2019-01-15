<?php

/**
 * Работа с категориями
 * Class Category
 */
class Category {

    private $table = '`tbl_category`';

    /**
     * @return array|bool|string
     */
    public function getCategories(){

        db::sql("SELECT * FROM $this->table ORDER BY `categoryId` DESC");
        $result = db::query();

        return $result;
    }


    /** Отрисовать все категории
     * @param int $CategoryId
     */
    public function get_stripes($CategoryId = 0)
    {
        global $ini;
        $this->sql = "SELECT * FROM tbl_category ";

        if ($CategoryId != 0)

            $this->sql .= " WHERE categoryId=$CategoryId";

        $this->sql .= " ORDER BY categoryName";

        db::sql($this->sql);

        $this->result = db::query();


        if (is_array($this->result))

            foreach ($this->result as $item) {

                require Config::getAdminRoot() . '/views/category/categoryStripe.view.php';

            }

    }


    /** Получить по id
     * @param $id
     * @return bool
     */
    public function getCategoryById( $id ){

        db::sql("SELECT * FROM ".$this->table." WHERE `categoryId`=".$id);
        $res = db::query();

        return $res[0];
    }

    /** Получить по translit
     * @param $id
     * @return bool
     */
    public function getCategoryByTranslit( $id ){

        db::sql("SELECT * FROM $this->table WHERE `categoryTranslit`='".$id."'");
        $res = db::query();

        if( $res[0] ) return $res[0];
        else return false;
    }


    /** Удалить по id
     * @param $CategoryId
     * @return string
     */
    public function deleteCategory($CategoryId)
    {

        global $ini;

        db::sql("CALL categoryDel(_1)");

        $params = array($CategoryId);

        db::addParameters($params);

        $result = db::execute();

        return $result;

    }


    /**
     * Добавить новую категорию
     */
    function addNewCategory()
    {
        db::sql("INSERT INTO tbl_category SET categoryName=''");

        $result = db::execute();

        if (!$result) {
            echo json_encode(array('error' => 'error'));
        } else {
            $this->get_stripes($result);
        }

    }


    /** Сохранить все
     * @param $categoryId
     * @param $categoryName
     * @param $categoryTranslit
     * @param $categoryDesc
     * @param $categorySearch
     * @return array
     */
    function saveAll(
        $categoryId,
        $categoryName,
        $categoryTranslit,
        $categoryDesc,
        $categorySearch)
    {
        db::sql("CALL saveCategory(_1,_2,_3,_4,_5)");

        $params = array(
            $categoryId,
            $categoryName,
            $categoryTranslit,
            $categoryDesc,
            $categoryName . ' ' . $categorySearch);

        db::addParameters($params);

        db::execute();

        $now = new DateTime();
        $response = array('saved' => $now->format('Y-m-d H:i:s'));

        return $response;

    }

}