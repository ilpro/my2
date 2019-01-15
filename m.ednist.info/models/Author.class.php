<?

/**
 * Работа с  авторами
 */
class Author
{

    protected $table = '`tbl_author`';

    public function __construct()
    {

    }

    /**
     * Выборка с базы новостей, согласно заданым условиям
     * @param string $conditions - условия для запроса
     * @return array - массив
     */
    public function getAuthors($conditions = '')
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

    /**
     * @param int $authorId
     */
    public function get_stripes($authorId = 0)
    {

        global $ini;

        $this->sql = "SELECT * FROM tbl_author ";

        if ($authorId != 0)

            $this->sql .= " WHERE authorId=$authorId";

        $this->sql .= " ORDER BY authorName";

        db::sql($this->sql);

        $this->result = db::query();


        if (is_array($this->result))

            foreach ($this->result as $item) {

                require Config::getAdminRoot() . '/views/author/authorStripe.view.php';

            }


    }


    public function getAuthorItem($itemId)
    {

        $this->sql = "select * from tbl_author WHERE authorId=$itemId";

        db::sql($this->sql);

        $result = db::query();

        if ($result) {

            $result = $this->getauthorImages($itemId, $result);

        } else {

            $result == false;

        }

        return $result;

    }


    public function getauthorImages($authorId, $authorArr)
    {

        global $ini;

        $tagsHtml = '';
        $htmlString = '';

        if ('' != $authorArr[0]['authorImg']) {

            $imgName = $authorArr[0]['authorImg'];

            require Config::getAdminRoot() . '/views/block/imgauthor.php.view.php';

            $tagsHtml .= $htmlString;

        }

        $authorArr[0]['authorImgsHTML'] = $tagsHtml;

        return $authorArr;

    }

    public function deleteAuthor($authorId)
    {

        global $ini;

        Upload::delFolder($ini['path.media'] . 'author/' . $authorId);

        db::sql("CALL authorDel(_1)");

        $params = array($authorId);

        db::addParameters($params);

        $result = db::execute();

        return $result;

    }


    function addNewAuthor()
    {

        db::sql("INSERT INTO tbl_author SET authorName=''");

        $result = db::execute();

        if (!$result) {

            echo json_encode(array('error' => 'error'));

        } else {

            $this->get_stripes($result);

        }

    }

    function saveAll(
        $AuthorId,
        $AuthorName,
        $AuthorDesc,
        $AuthorSearch
    )
    {
        db::sql("CALL saveAuthor(_1,_2,_3,_4)");

        $params = array(
            $AuthorId,
            $AuthorName,
            $AuthorDesc,
            $AuthorSearch);

        db::addParameters($params);

        db::execute();

        $now = new DateTime();
        $response = array('saved' => $now->format('Y-m-d H:i:s'));

        return $response;

    }

}
