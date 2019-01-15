<?php

/**
 * Работа с регионами
 */
class Region
{

    private $table = '`tbl_region`';

    /** отрисовать один регион
     * @param int $regionId
     */
    public function get_stripes($regionId = 0)
    {
        global $ini;
        $this->sql = "SELECT * FROM tbl_region ";
        if ($regionId != 0)
            $this->sql .= " WHERE regionId=$regionId";
        $this->sql .= " ORDER BY regionId DESC";
        db::sql($this->sql);

        $this->result = db::query();

        if (is_array($this->result))
            foreach ($this->result as $item) {
                require Config::getAdminRoot() . '/views/region/regionStripe.view.php';
            }
    }


    /** Получить один регион
     * @param $itemId
     * @return array|bool|string
     */
    public function getRegionItem($itemId)
    {

        $this->sql = "select * from $this->table WHERE regionId=$itemId";
        db::sql($this->sql);
        $result = db::query()[0];
        if ($result) {
            $result = $this->getregionImages($itemId, $result);
        } else {
            $result == false;
        }

        return $result;

    }

    /**
     * Выборка с базы новостей, согласно заданым условиям
     * @param string $conditions - условия для запроса
     * @return array - массив
     */
    public function getRegions($conditions = '')
    {

        $result = [];

        db::sql("SELECT * FROM $this->table " . $conditions);
        $result = db::query();
        if (!empty($result))
            return $result;
        else
            return [];
    }

    /** Добависть к масиву изображение
     * @param $regionId
     * @param $regionArr
     * @return mixed
     */
    public function getRegionImages($regionId, $regionArr)
    {

        global $ini;

        $regionsHtml = '';
        $htmlString = '';

        if (isset($regionArr['regionImg'])) {
            $imgName = $regionArr['regionImg'];
            require Config::getRoot() . '/views/block/imgregion.php.view.php';
            $regionsHtml .= $htmlString;
        }

        $regionArr['regionImgsHTML'] = $regionsHtml;

        return $regionArr;

    }

    /** Удалить
     * @param $regionId
     * @return string
     */
    public function deleteRegion($regionId)
    {
        global $ini;

        db::sql("CALL regionDel(_1)");
        $params = array($regionId);
        db::addParameters($params);
        $result = db::execute();
        return $result;

    }


    /**
     *Добавить новый регион
     */
    function addNewRegion()
    {
        db::sql("INSERT INTO tbl_region SET regionName=''");

        $result = db::execute();

        if (!$result) {
            echo json_encode(array('error' => 'error'));
        } else {
            $this->get_stripes($result);
        }

    }

    /** Сохранить регион
     * @param $RegionId
     * @param $RegionName
     * @param $RegionSearch
     */
    function saveAll(
        $RegionId,
        $RegionName,
        $RegionSearch
    )
    {
        db::sql("CALL saveRegion(_1,_2,_3)");

        $params = array(
            $RegionId,
            $RegionName,
            $RegionSearch);

        db::addParameters($params);

        db::execute();
        $now = new DateTime();
        $response = array('saved' => $now->format('Y-m-d H:i:s'));

        return $response;
    }

}