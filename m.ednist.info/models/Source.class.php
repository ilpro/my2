<?php

/**
 * Работа с тегами новостей
 * Class Tag
 */
class Source
{

    private $table = 'tbl_source';

    /** отрисовка тега
     * @param int $tagId
     */
    public function get_stripes($sourceId = 0)
    {

        global $ini;

        $this->sql = "SELECT * FROM $this->table";

        if ($sourceId != 0)

            $this->sql .= " WHERE sourceId=$sourceId";

        $this->sql .= " ORDER BY sourceId DESC";

        db::sql($this->sql);


        $this->result = db::query();


        if (is_array($this->result))

            foreach ($this->result as $item) {

                require Config::getAdminRoot() . '/views/source/sourceStripe.view.php';

            }


    }


    /**
     * Выборка с базы новостей, согласно заданым условиям
     * @param string $conditions - условия для запроса
     * @return array - массив
     */
    public function getSources($conditions = '')
    {

        $result = [];

        db::sql("SELECT * FROM $this->table " . $conditions);
        $query_res = db::query();

        if (!empty($query_res))
            foreach ($query_res as $brand)
                $result[] = $brand;
        else
            return false;

        return $result;
    }
}