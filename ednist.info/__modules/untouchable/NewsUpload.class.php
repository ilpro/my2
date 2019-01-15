<?

class NewsUpload extends Upload
{

  private function __construct()
  {
  }


  public static function multiNewsImgs($imgfiles, $newsId, $targetpath)
  {
    global $ini;

    if ($imgfiles) { // Загрузка фото из папки

      $html = '';

      foreach ($imgfiles['error'] as $key => $error) {

        if ($error == UPLOAD_ERR_OK) { //print_r($key);
          self::mkFolder($targetpath . '/' . $newsId);
          self::mkFolder($targetpath . '/' . $newsId . '/main');
          self::mkFolder($targetpath . '/' . $newsId . '/gallery');
          self::mkFolder($targetpath . '/' . $newsId . '/big');
          self::mkFolder($targetpath . '/' . $newsId . '/raw');
          $name = md5(microtime(true));

          self::createImage($imgfiles['tmp_name'][$key], $targetpath . '/' . $newsId . "/gallery/" . $name, 'jpg', 178, false);
          self::createImage($imgfiles['tmp_name'][$key], $targetpath . '/' . $newsId . "/big/" . $name, 'jpg', 400);
          move_uploaded_file($imgfiles['tmp_name'][$key], $ini['path.media'] . $targetpath . '/' . $newsId . "/raw/" . $name . ".jpg");

          $imgId = self::addImg($name . '.jpg', $newsId);
          $item = array(
            'imgId' => $imgId,
            'newsId' => $newsId,
            'imgMain' => 0,
            'imgDesc' => '',
            'imgName' => $name . '.jpg'
          );

          $htmlString = '';
          include Config::getAdminRoot() . "/views/block/img.php.view.php";
          $html .= $htmlString;
        }
      }
      return $html;
    } else return false;
  }


  public static function multiBrandImgs($imgfiles, $brandId, $targetpath)
  {

    global $ini;

    if ($imgfiles) { // Загрузка фото из папки

      $html = '';

      foreach ($imgfiles['error'] as $key => $error) {

        if ($error == UPLOAD_ERR_OK) { //print_r($key);
          self::mkFolder($targetpath . '/' . $brandId);
          self::mkFolder($targetpath . '/' . $brandId . '/main');
          self::mkFolder($targetpath . '/' . $brandId . '/gallery');
          self::mkFolder($targetpath . '/' . $brandId . '/big');
          self::mkFolder($targetpath . '/' . $brandId . '/raw');
          $name = md5(microtime(true));

          self::createImage($imgfiles['tmp_name'][$key], $targetpath . '/' . $brandId . "/gallery/" . $name, 'jpg', 178, false);
          copy($imgfiles['tmp_name'][$key], $ini['path.media'] . $targetpath . '/' . $brandId . "/big/" . $name . '.jpg');
          move_uploaded_file($imgfiles['tmp_name'][$key], $ini['path.media'] . $targetpath . '/' . $brandId . "/raw/" . $name . ".jpg");

          $imgId = self::addBrandImg($name . '.jpg', $brandId);
          $item = array(
            'imgId' => $imgId,
            'brandId' => $brandId,
            'imgMain' => 0,
            'imgDesc' => '',
            'imgName' => $name . '.jpg'
          );

          $htmlString = '';
          include Config::getAdminRoot() . "/views/block/imgbrandpage.php.view.php";
          $html .= $htmlString;
        }
      }
      return $html;
    } else return false;
  }

  public static function multiThemesImgs($imgfiles, $themesId, $targetpath)
  {

    global $ini;

    if ($imgfiles) { // Загрузка фото из папки

      $html = '';

      foreach ($imgfiles['error'] as $key => $error) {

        if ($error == UPLOAD_ERR_OK) { //print_r($key);
          self::mkFolder($targetpath . '/' . $themesId);
          self::mkFolder($targetpath . '/' . $themesId . '/main');
          self::mkFolder($targetpath . '/' . $themesId . '/gallery');
          self::mkFolder($targetpath . '/' . $themesId . '/big');
          self::mkFolder($targetpath . '/' . $themesId . '/raw');
          $name = md5(microtime(true));

          self::createImage($imgfiles['tmp_name'][$key], $targetpath . '/' . $themesId . "/gallery/" . $name, 'jpg', 178, false);
          copy($imgfiles['tmp_name'][$key], $ini['path.media'] . $targetpath . '/' . $themesId . "/big/" . $name . '.jpg');
          move_uploaded_file($imgfiles['tmp_name'][$key], $ini['path.media'] . $targetpath . '/' . $themesId . "/raw/" . $name . ".jpg");

          $imgId = self::addThemesImg($name . '.jpg', $themesId);
          $item = array(
            'imgId' => $imgId,
            'themesId' => $themesId,
            'imgMain' => 0,
            'imgDesc' => '',
            'imgName' => $name . '.jpg'
          );

          $htmlString = '';
          include Config::getAdminRoot() . "/views/block/imgthemespage.php.view.php";
          $html .= $htmlString;
        }
      }
      return $html;
    } else return false;
  }

  // Загрузка фото из папки
  public static function multiSettingImgs($imgfiles, $targetpath, $name)
  {
    global $ini;

    if ($imgfiles) {

      $html = '';

      foreach ($imgfiles['error'] as $key => $error)

        if ($error == UPLOAD_ERR_OK) { //print_r($key);
          //         self::mkFolder($targetpath . '/', true);
          if ($name == 'watermark') {
            self::createImage($imgfiles['tmp_name'][$key], $targetpath . '/' . $name, 'png', 178, false);
            $imgName = $name . '.png';
          } else {
            self::createImage($imgfiles['tmp_name'][$key], $targetpath . '/' . $name, 'jpg', 440, false);
            $imgName = $name . '.jpg';
          }

          $htmlString = '';

          include Config::getAdminRoot() . "/views/block/imgsetting.php.view.php";

          $html .= $htmlString;
        }
      return $html;
    } else return false;
  }

  public static function multiAuthorImgs($imgfiles, $authorId, $targetpath)
  {

    global $ini;

    if ($imgfiles) { // Загрузка фото из папки

      $html = '';

      foreach ($imgfiles['error'] as $key => $error)

        if ($error == UPLOAD_ERR_OK) { //print_r($key);
          self::mkFolder($targetpath . '/' . $authorId, true);
          $name = md5(microtime(true));
          self::createImage($imgfiles['tmp_name'][$key], $targetpath . '/' . $authorId . "/" . $name, 'jpg', 178, false);
          self::createImage($imgfiles['tmp_name'][$key], $targetpath . '/' . $authorId . "/60", 'jpg', 60, false);
          self::createImage($imgfiles['tmp_name'][$key], $targetpath . '/' . $authorId . "/big", 'jpg', '');
          move_uploaded_file($imgfiles['tmp_name'][$key], $ini['path.media'] . $targetpath . '/' . $authorId . "/orig" . ".jpg");

          $imgId = self::addImgAuthor($name . '.jpg', $authorId);
          $imgName = $name . '.jpg';
          $htmlString = '';

          include Config::getAdminRoot() . "/views/block/imgauthor.php.view.php";

          $html .= $htmlString;
        }
      return $html;
    } else return false;
  }


  public static function multiNewsDocs($imgfiles, $newsId)
  {
    global $ini;

    if ($imgfiles) {

      $html = '';

      foreach ($imgfiles['error'] as $key => $error)

        if ($error == UPLOAD_ERR_OK) {

          self::mkFolder('docs/' . $newsId);

          $docname = $imgfiles['name'][$key];

          if (!file_exists($ini['path.media'] . 'docs/' . $newsId . "/" . $docname)) {

            if (move_uploaded_file($imgfiles['tmp_name'][$key], $ini['path.media'] . 'docs/' . $newsId . "/" . $docname)) {
              $htmlString = "";
              include Config::getAdminRoot() . "/views/block/doc.php.view.php";
              $html .= $htmlString;
            }

          } else $html .= 'duplicate';
        }
      return $html;
    } else return false;
  }


  public static function linkNewsDocs($link, $newsId)
  {
    global $ini;

    if (strlen($link) > 0) {

      $html = '';
      $docname = basename($link);
      self::mkFolder('docs/' . $newsId);
      $doclink = $ini['path.media'] . 'docs/' . $newsId . '/' . $docname;

      if (!file_exists($doclink)) {

        $data = self::get_data($link, $doclink);
        if ($data == false) return 'Bad file link';

        if ($data == 1) {
          $htmlString = "";
          include Config::getAdminRoot() . "/views/block/doc.php.view.php";
          $html .= $htmlString;
        }

      } else $html .= 'duplicate';

      return $html;
    } else return false;
  }


  public static function linkNewsImgs($link, $newsId, $returnType = 'html')
  {

    global $ini;

    if (strlen($link) > 0) {

      $html = '';
      self::mkFolder('images/' . $newsId);
      self::mkFolder('images/' . $newsId . '/main');
      self::mkFolder('images/' . $newsId . '/gallery');
      self::mkFolder('images/' . $newsId . '/big');
      self::mkFolder('images/' . $newsId . '/orig');
      self::mkFolder('images/' . $newsId . '/raw');

      $name = md5(microtime(true));

      $doclink = $ini['path.media'] . 'images/' . $newsId . "/raw/" . $name . ".jpg";

      $data = self::get_data($link, $doclink);

      if ($data == 1) {

        self::createImage($doclink, 'images/' . $newsId . "/gallery/" . $name, 'jpg', 178, false);
        self::createImage($doclink, 'images/' . $newsId . "/big/" . $name, 'jpg', '');

        $imgId = self::addImg($name . '.jpg', $newsId);

        $item = array(
          'imgId' => $imgId,
          'newsId' => $newsId,
          'imgMain' => 0,
          'imgDesc' => '',
          'imgName' => $name . '.jpg'
        );
        $htmlString = '';

        include Config::getAdminRoot() . "/views/block/img.php.view.php";

        $html .= $htmlString;
      }

      if ($returnType == 'html') return $html;
      if ($returnType == 'id') return $imgId;
    } else return false;
  }


  public static function linkBrandImgs($link, $brandId, $returnType = 'html')
  {
    global $ini;

    if (strlen($link) > 0) {

      $html = '';
      self::mkFolder('brand/' . $brandId);
      self::mkFolder('brand/' . $brandId . '/main');
      self::mkFolder('brand/' . $brandId . '/gallery');
      self::mkFolder('brand/' . $brandId . '/big');
      self::mkFolder('brand/' . $brandId . '/orig');
      self::mkFolder('brand/' . $brandId . '/raw');

      $name = md5(microtime(true));
      $doclink = $ini['path.media'] . 'brand/' . $brandId . "/raw/" . $name . ".jpg";

      $data = self::get_data($link, $doclink);

      if ($data == 1) {

        self::createImage($doclink, 'brand/' . $brandId . "/gallery/" . $name, 'jpg', 178, false);
        self::createImage($doclink, 'brand/' . $brandId . "/big/" . $name, 'jpg', '');

        $imgId = self::addbrandImg($name . '.jpg', $brandId);

        $item = array(
          'imgId' => $imgId,
          'brandId' => $brandId,
          'imgMain' => 0,
          'imgDesc' => '',
          'imgName' => $name . '.jpg'
        );
        $htmlString = '';

        include Config::getAdminRoot() . "/views/block/imgbrandpage.php.view.php";

        $html .= $htmlString;
      }

      if ($returnType == 'html') return $html;
      if ($returnType == 'id') return $imgId;
    } else return false;
  }

  public static function linkThemesImgs($link, $themesId, $returnType = 'html')
  {
    global $ini;

    if (strlen($link) > 0) {

      $html = '';
      self::mkFolder('themes/' . $themesId);
      self::mkFolder('themes/' . $themesId . '/main');
      self::mkFolder('themes/' . $themesId . '/gallery');
      self::mkFolder('themes/' . $themesId . '/big');
      self::mkFolder('themes/' . $themesId . '/orig');
      self::mkFolder('themes/' . $themesId . '/raw');

      $name = md5(microtime(true));
      $doclink = $ini['path.media'] . 'themes/' . $themesId . "/raw/" . $name . ".jpg";

      $data = self::get_data($link, $doclink);

      if ($data == 1) {

        self::createImage($doclink, 'themes/' . $themesId . "/gallery/" . $name, 'jpg', 178, false);
        self::createImage($doclink, 'themes/' . $themesId . "/big/" . $name, 'jpg', '');

        $imgId = self::addthemesImg($name . '.jpg', $themesId);

        $item = array(
          'imgId' => $imgId,
          'themesId' => $themesId,
          'imgMain' => 0,
          'imgDesc' => '',
          'imgName' => $name . '.jpg'
        );
        $htmlString = '';

        include Config::getAdminRoot() . "/views/block/imgthemespage.php.view.php";

        $html .= $htmlString;
      }

      if ($returnType == 'html') return $html;
      if ($returnType == 'id') return $imgId;
    } else return false;
  }

  //загрузка фото по ссилке
  public static function linkSettingImgs($link, $name, $returnType = 'html')
  {

    global $ini;

    if (strlen($link) > 0) {

      $html = '';

      //   self::mkFolder('settings/', true);
      if ($name == 'watermark') {
        $doclink = $ini['path.media'] . 'settings/' . $name . '.png';
      } else {
        $doclink = $ini['path.media'] . 'settings/' . $name . '.jpg';
      }
      //    $file = file_get_contents($link);
      //  file_put_contents($doclink, $file);
      $data = self::get_data($link, $doclink);

      if ($data == 1) {
        if ($name == 'watermark') {
          self::createImage($doclink, 'settings/' . $name, 'png', 178, false);
          $imgName = $name . '.png';
        } else {
          self::createImage($doclink, 'settings/' . $name, 'jpg', 440, false);
          $imgName = $name . '.jpg';
        }

        $htmlString = '';
        include Config::getAdminRoot() . "/views/block/imgsetting.php.view.php";
        $html .= $htmlString;

      }

      return $html;
    } else return false;
  }

  public static function linkAuthorImgs($link, $authorId, $returnType = 'html')
  {
    global $ini;

    if (strlen($link) > 0) {

      $html = '';

      self::mkFolder('author/' . $authorId, true);

      $name = md5(microtime(true));

      $doclink = $ini['path.media'] . 'author/' . $authorId . "/orig.jpg";

      $data = self::get_data($link, $doclink);

      if ($data == 1) {

        self::createImage($doclink, 'author/' . $authorId . "/" . $name, 'jpg', 178, false);
        self::createImage($doclink, 'author/' . $authorId . "/60", 'jpg', 60, false);
        self::createImage($doclink, 'author/' . $authorId . "/big", 'jpg', '');

        $imgId = self::addImgAuthor($name . '.jpg', $authorId);
        $imgName = $name . '.jpg';

        $htmlString = '';
        include Config::getAdminRoot() . "/views/block/imgauthor.php.view.php";
        $html .= $htmlString;
      }
      if ($returnType == 'html') return $html;
      if ($returnType == 'id') return $imgId;
    } else return false;
  }


  public static function descNewsImgs($desc, $newsId)
  {
    db::sql("CALL imgSetDesc(_1,_2)");
    $params = array($newsId, $desc);
    db::addParameters($params);
    $result = db::execute();
    return $result;
  }


  public static function mainNewsImgs($imgId)
  {
    global $ini;

    db::sql("CALL imgGetId(_1)");
    db::addParameters([$imgId]);
    $result = db::query();

    $result = $result[0];

    db::sql("CALL imgSetMain(_1,_2)");
    db::addParameters([$imgId, $result['newsId']]);
    $res = db::execute();

    self::mkFolder('images/' . $result['newsId'] . '/main', true);
//            self::createImage($ini['path.media'] . 'images/' . $result['newsId'] . "/raw/" . $result['imgName'], 'images/' . $result['newsId'] . "/main/400", 'jpg', 400);
    self::createImage($ini['path.media'] . 'images/' . $result['newsId'] . "/big/" . $result['imgName'], 'images/' . $result['newsId'] . "/main/240", 'jpg', 240, false);
    self::createImage($ini['path.media'] . 'images/' . $result['newsId'] . "/big/" . $result['imgName'], 'images/' . $result['newsId'] . "/main/60", 'jpg', 60, false);

    return $res;
  }

  public static function mainBrandsImgs($imgId)
  {
    global $ini;

    db::sql("CALL imgBrandGetId(_1)");
    db::addParameters([$imgId]);
    $result = db::query();

    $result = $result[0];

    db::sql("CALL imgSetBrandMain(_1,_2)");
    db::addParameters([$imgId, $result['brandId']]);
    $res = db::execute();

    self::mkFolder('brand/' . $result['brandId'] . '/main', true);
    self::createImage($ini['path.media'] . 'brand/' . $result['brandId'] . "/raw/" . $result['imgName'], 'brand/' . $result['brandId'] . "/main/400", 'jpg', 400);
    self::createImage($ini['path.media'] . 'brand/' . $result['brandId'] . "/raw/" . $result['imgName'], 'brand/' . $result['brandId'] . "/main/240", 'jpg', 240, false);
    self::createImage($ini['path.media'] . 'brand/' . $result['brandId'] . "/raw/" . $result['imgName'], 'brand/' . $result['brandId'] . "/main/60", 'jpg', 60, false);

    return $res;
  }

  public static function mainThemesImgs($imgId)
  {
    global $ini;

    db::sql("CALL imgThemesGetId(_1)");
    db::addParameters([$imgId]);
    $result = db::query();

    $result = $result[0];

    db::sql("CALL imgSetThemesMain(_1,_2)");
    db::addParameters([$imgId, $result['themesId']]);
    $res = db::execute();

    self::mkFolder('themes/' . $result['themesId'] . '/main', true);
    self::createImage($ini['path.media'] . 'themes/' . $result['themesId'] . "/raw/" . $result['imgName'], 'themes/' . $result['themesId'] . "/main/400", 'jpg', 400);
    self::createImage($ini['path.media'] . 'themes/' . $result['themesId'] . "/raw/" . $result['imgName'], 'themes/' . $result['themesId'] . "/main/240", 'jpg', 240, false);
    self::createImage($ini['path.media'] . 'themes/' . $result['themesId'] . "/raw/" . $result['imgName'], 'themes/' . $result['themesId'] . "/main/60", 'jpg', 60, false);

    return $res;
  }


  public static function delNewsImgs($imgId)
  {

    global $ini;

    db::sql("CALL imgGetId(_1)");
    $params = array($imgId);
    db::addParameters($params);
    $result = db::query();
    $result = $result[0];

    if (unlink($ini['path.media'] . "images/" . $result['newsId'] . "/gallery/" . $result['imgName'])) {

      unlink($ini['path.media'] . "images/" . $result['newsId'] . "/big/" . $result['imgName']);
      unlink($ini['path.media'] . "images/" . $result['newsId'] . "/raw/" . $result['imgName']);

      if ($result['imgMain'] > 0)
        self::mkFolder('images/' . $result['newsId'] . '/main', true);

      db::sql("CALL imgDel(_1)");
      $params = array($imgId);
      db::addParameters($params);
      $result = db::execute();

      return $result;
    } else {

      return "error";
    }
  }

  public static function delBrandImgs($imgId)
  {

    global $ini;

    db::sql("CALL imgBrandGetId(_1)");
    $params = array($imgId);
    db::addParameters($params);
    $result = db::query();
    $result = $result[0];

    if (unlink($ini['path.media'] . "brand/" . $result['brandId'] . "/gallery/" . $result['imgName'])) {

      unlink($ini['path.media'] . "brand/" . $result['brandId'] . "/big/" . $result['imgName']);
      unlink($ini['path.media'] . "brand/" . $result['brandId'] . "/raw/" . $result['imgName']);

      if ($result['imgMain'] > 0)
        self::mkFolder('brand/' . $result['brandId'] . '/main', true);

      db::sql("CALL imgBrandDel(_1)");
      $params = array($imgId);
      db::addParameters($params);
      $result = db::execute();

      return $result;
    } else {

      return "error";
    }
  }

  public static function delThemesImgs($imgId)
  {

    global $ini;

    db::sql("CALL imgThemesGetId(_1)");
    $params = array($imgId);
    db::addParameters($params);
    $result = db::query();
    $result = $result[0];

    if (unlink($ini['path.media'] . "themes/" . $result['themesId'] . "/gallery/" . $result['imgName'])) {

      unlink($ini['path.media'] . "themes/" . $result['themesId'] . "/big/" . $result['imgName']);
      unlink($ini['path.media'] . "themes/" . $result['themesId'] . "/raw/" . $result['imgName']);

      if ($result['imgMain'] > 0)
        self::mkFolder('themes/' . $result['themesId'] . '/main', true);

      db::sql("CALL imgThemesDel(_1)");
      $params = array($imgId);
      db::addParameters($params);
      $result = db::execute();

      return $result;
    } else {

      return "error";
    }
  }

  public static function delNewsDocs($docName, $newsId)
  {

    global $ini;

    if (unlink($ini['path.media'] . "docs/$newsId/" . $docName)) {
      return "0";
    } else {
      return "error";
    }
  }

  /*
      public static function delBrandImgs($brandId)
      {
          global $ini;

          self::addImgBrand('', $brandId);
          self::mkFolder('brand/' . $brandId, true);
          return true;

      }*/
  public static function delSettingImgs($name)
  {
    global $ini;
    unlink(Config::getRoot() . "/media/settings/" . $name);
    //   self::mkFolder('settings/'.$name, true);
    return true;

  }

  public static function delAuthorImgs($AuthorId)
  {
    global $ini;

    self::addImgAuthor('', $AuthorId);
    self::mkFolder('author/' . $AuthorId, true);
    return true;
  }


  public static function cropNewsImgs($imgId, $c)
  {

    global $ini;

    db::sql("CALL imgGetId(_1)");
    $params = array($imgId);
    db::addParameters($params);
    $result = db::query();

    $result = $result[0];

    if (self::cropImg($result['newsId'], $c, $result['imgName'])) {

      // self::createImageFullname($ini['path.media'] . 'images/' . $result['newsId'] . '/raw/' . $result['imgName'], 'images/' . $result['newsId'] . "/gallery/" . $result['imgName'], 178, false);
      /*
                  if ($result['imgMain'] > 0) {

                      self::mkFolder('images/' . $result['newsId'] . '/main', true);
                      self::createImage($ini['path.media'] . 'images/' . $result['newsId'] . "/raw/" . $result['imgName'], 'images/' . $result['newsId'] . "/main/400", 'jpg', 400);
                      self::createImage($ini['path.media'] . 'images/' . $result['newsId'] . "/raw/" . $result['imgName'], 'images/' . $result['newsId'] . "/main/240", 'jpg', 240, false);
                      self::createImage($ini['path.media'] . 'images/' . $result['newsId'] . "/raw/" . $result['imgName'], 'images/' . $result['newsId'] . "/main/60", 'jpg', 60, false);

                  }
             */
      return $ini['url.media'] . 'images/' . $result['newsId'] . '/gallery/' . $result['imgName'] . '?' . time();
    } else {
      return "error";
    }
  }


  public static function cropBrandImgs($imgId, $c)
  {

    global $ini;

    db::sql("CALL imgBrandGetId(_1)");
    $params = array($imgId);
    db::addParameters($params);
    $result = db::query();

    $result = $result[0];

    if (self::cropBrandImg($result['brandId'], $c, $result['imgName'])) {
      return $ini['url.media'] . 'brand/' . $result['brandId'] . '/gallery/' . $result['imgName'] . '?' . time();
    } else {
      return "error";
    }
  }

  public static function cropThemesImgs($imgId, $c)
  {

    global $ini;

    db::sql("CALL imgThemesGetId(_1)");
    $params = array($imgId);
    db::addParameters($params);
    $result = db::query();

    $result = $result[0];

    if (self::cropThemesImg($result['themesId'], $c, $result['imgName'])) {
      return $ini['url.media'] . 'themes/' . $result['themesId'] . '/gallery/' . $result['imgName'] . '?' . time();
    } else {
      return "error";
    }
  }

  public static function cropAuthorImgs($authorId, $c, $imgName)
  {
    global $ini;

    if (self::cropImgAuthor($authorId, $c, 'big.jpg')) {

      self::createImageFullname($ini['path.media'] . 'author/' . $authorId . '/big.jpg', 'author/' . $authorId . "/" . $imgName, 178);
      self::createImageFullname($ini['path.media'] . 'author/' . $authorId . '/big.jpg', 'author/' . $authorId . "/60.jpg", 60);
      return $ini['url.media'] . 'author/' . $authorId . '/' . $imgName . '?' . time();

    } else {
      return "error";
    }
  }


}