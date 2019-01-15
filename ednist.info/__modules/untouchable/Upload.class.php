<?php

class Upload
{
  private function __construct()
  {
  }

  public static function get_data($url, $doclink)
  {

    if ($fp = fopen($doclink, 'w+') == true) {

      $ch = curl_init(str_replace(" ", "%20", $url));
      $timeout = 50;
      curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $fp = fopen($doclink, 'w+');
      curl_setopt($ch, CURLOPT_FILE, $fp);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
      $data = curl_exec($ch);
      curl_close($ch);

      return $data;
    } else
      return false;
  }

  public static function addImg($name, $newsId)
  {
    db::sql("INSERT INTO `ctbl_img` SET `newsId` = $newsId, `imgName` = '$name'");
    $result = db::execute();
    return $result;
  }

  public static function addbrandImg($name, $brandId)
  {
    db::sql("INSERT INTO `ctbl_brandimg` SET `brandId` = $brandId, `imgName` = '$name'");
    $result = db::execute();
    return $result;
  }

  public static function addthemesImg($name, $themesId)
  {
    db::sql("INSERT INTO `ctbl_themesimg` SET `themesId` = $themesId, `imgName` = '$name'");
    $result = db::execute();
    return $result;
  }

  public static function addImgBrand($name, $brandId)
  {
    db::sql("UPDATE `tbl_brand` SET `brandImg` = '$name' WHERE `brandId` = $brandId");
    $result = db::execute();
    return $result;
  }

  public static function addImgThemes($name, $themesId)
  {
    db::sql("UPDATE `tbl_themes` SET `themesImg` = '$name' WHERE `themesId` = $themesId");
    $result = db::execute();
    return $result;
  }

  public static function addImgAuthor($name, $authorId)
  {
    db::sql("UPDATE `tbl_author` SET `authorImg` = '$name' WHERE `authorId` = $authorId");
    $result = db::execute();
    return $result;
  }

  public static function copyFile($file, $newfile)
  {
    if (!copy($file, $newfile)) {
      return false;
    } else {
      return true;
    }
  }

  public static function copyFolder($source, $target)
  {
    if (is_dir($source)) {
      @mkdir($target);
      $d = dir($source);
      while (FALSE !== ($entry = $d->read())) {
        if ($entry == '.' || $entry == '..') continue;
        $Entry = $source . '/' . $entry;
        if (is_dir($Entry)) self::copyFolder($Entry, $target . '/' . $entry);
        else copy($Entry, $target . '/' . $entry);
      }
      $d->close();
    } else copy($source, $target);
  }


  public static function readFolder($path)
  {
    global $ini;
    if (is_dir($ini['path.media'] . $path))
      if ($handle = opendir($ini['path.media'] . $path)) {
        while (false !== ($entry = readdir($handle))) {
          if ($entry != "." && $entry != "..") {
            $arr[] = $ini['url.media'] . $path . '/' . $entry;
          }
        }
        closedir($handle);
        return $arr;
      }

  }

  public static function testSizeImg($image, $sizeX, $sizeY)
  {
    $im = new Imagick($image);
    $imageprops = $im->getImageGeometry();
    if ($imageprops['width'] < $sizeX or $imageprops['height'] < $sizeY)
      return false;
    return true;
  }

  public static function issetFile($url)
  {
    $Headers = @get_headers($url);
    // проверяем ли ответ от сервера с кодом 200 - ОК
    //$ret = preg_match("|200|", $Headers[0]);// echo gettype($ret) ."\n";
    if (preg_match("|200|", $Headers[0])) {
      return true;
    } else {
      return false;
    }
  }

  //public static function createImageFullname($image,$name,$size=''){
  public static function createImage($image, $name, $format = '', $size = '', $wm = false)
  {
    if ($format != '') {
      self::createImageFullname($image, $name . "." . $format, $size, $wm);
    } else {
      self::createImageFullname($image, $name . ".jpg", $size, $wm);
    }

  }

  //public static function createImage($image,$name,$format,$size='',$wm=true){
  public static function createImageFullname($image, $name, $size = '', $wm = false)
  {
    global $ini;
    $im = new Imagick($image);
    if ($size == '') {
      $imageprops = $im->getImageGeometry();
      $newsize = $imageprops['width'] > $imageprops['height'] ? $imageprops['height'] : $imageprops['width'];
      $size = $newsize;
    }
    if ($size > 1000) {
      $size = 1000;
    }
    if ($size > 0)
      $im->cropThumbnailImage($size, $size);
    $im->setImageFormat('jpg');

    $iWidth = $im->getImageWidth();
    $iHeight = $im->getImageHeight();
    if ($wm) {
      $watermark = new Imagick();
      $watermark->readImage($ini['path.media'] . "settings/watermark.png");
      $wWidth = $watermark->getImageWidth();
      $wHeight = $watermark->getImageHeight();
      // resize the watermark
      if ($iHeight < $wHeight || $iWidth < $wWidth) {
        $watermark->scaleImage($iWidth, $iHeight);
        // get new size
        $wWidth = $watermark->getImageWidth();
        $wHeight = $watermark->getImageHeight();
      }
      $x = $iWidth - $wWidth - 2;
      $y = $iHeight - $wHeight - 2;

      $im->compositeImage($watermark, imagick::COMPOSITE_OVER, $x, $y);
    }
    $im->writeImage($ini['path.media'] . $name);
    $im->destroy();
  }

  public static function cropImg($id, $c, $name)
  {
    global $ini;
    $folder = $ini['path.media'] . "images/$id/big/";
    $folder2 = $ini['path.media'] . "images/$id/main/";
    $folder3 = $ini['path.media'] . "images/$id/gallery/";
    $im = new Imagick($ini['path.media'] . "images/$id/raw/$name");
    $im2 = new Imagick($ini['path.media'] . "images/$id/raw/$name");
    $im3 = new Imagick($ini['path.media'] . "images/$id/raw/$name");
    $im4 = new Imagick($ini['path.media'] . "images/$id/raw/$name");
    $im5 = new Imagick($ini['path.media'] . "images/$id/raw/$name");
    if (is_array($c)) {
      if ($im->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {
        $im->setImageFormat('jpg');
        $im->writeImage($folder . $name);
        $im->destroy();
        self::createImageFullname($folder . $name, "images/$id/big/" . $name, 0);
      }
      if ($im2->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {
        $im2->setImageFormat('jpg');
        $im2->writeImage($folder2 . '60.jpg');
        $im2->destroy();
        self::createImageFullname($folder2 . '60.jpg', "images/$id/main/" . '60.jpg', 60);
      }
      if ($im3->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {
        $im3->setImageFormat('jpg');
        $im3->writeImage($folder2 . '240.jpg');
        $im3->destroy();
        self::createImageFullname($folder2 . '240.jpg', "images/$id/main/" . '240.jpg', 240);
      }
      if ($im4->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {
        $im4->setImageFormat('jpg');
        $im4->writeImage($folder2 . '400.jpg');
        $im4->destroy();
        self::createImageFullname($folder2 . '400.jpg', "images/$id/main/" . '400.jpg', 400);
      }
      if ($im5->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {
        $im5->setImageFormat('jpg');
        $im5->writeImage($folder3 . $name);
        $im5->destroy();
        self::createImageFullname($folder3 . $name, "images/$id/gallery/" . $name, 178, false);
        return true;
      }
    }

  }

  public static function cropBrandImg($id, $c, $name)
  {
    global $ini;
    $folder = $ini['path.media'] . "brand/$id/big/";
    $folder2 = $ini['path.media'] . "brand/$id/main/";
    $folder3 = $ini['path.media'] . "brand/$id/gallery/";
    $im = new Imagick($ini['path.media'] . "brand/$id/raw/$name");
    $im2 = new Imagick($ini['path.media'] . "brand/$id/raw/$name");
    $im3 = new Imagick($ini['path.media'] . "brand/$id/raw/$name");
    $im4 = new Imagick($ini['path.media'] . "brand/$id/raw/$name");
    $im5 = new Imagick($ini['path.media'] . "brand/$id/raw/$name");
    if (is_array($c)) {
      if ($im->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {
        $im->setImageFormat('jpg');
        $im->writeImage($folder . $name);
        $im->destroy();
        self::createImageFullname($folder . $name, "brand/$id/big/" . $name, 0);
      }
      if ($im2->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {
        $im2->setImageFormat('jpg');
        $im2->writeImage($folder2 . '60.jpg');
        $im2->destroy();
        self::createImageFullname($folder2 . '60.jpg', "brand/$id/main/" . '60.jpg', 60);
      }
      if ($im3->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {
        $im3->setImageFormat('jpg');
        $im3->writeImage($folder2 . '240.jpg');
        $im3->destroy();
        self::createImageFullname($folder2 . '240.jpg', "brand/$id/main/" . '240.jpg', 240);
      }
      if ($im4->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {
        $im4->setImageFormat('jpg');
        $im4->writeImage($folder2 . '400.jpg');
        $im4->destroy();
        self::createImageFullname($folder2 . '400.jpg', "brand/$id/main/" . '400.jpg', 400);
      }

      if ($im5->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {
        $im5->setImageFormat('jpg');
        $im5->writeImage($folder3 . $name);
        $im5->destroy();
        self::createImageFullname($folder3 . $name, "brand/$id/gallery/" . $name, 178, false);
        return true;
      }

    }
  }

  public static function cropThemesImg($id, $c, $name)
  {
    global $ini;
    $folder = $ini['path.media'] . "themes/$id/big/";
    $folder2 = $ini['path.media'] . "themes/$id/main/";
    $folder3 = $ini['path.media'] . "themes/$id/gallery/";
    $im = new Imagick($ini['path.media'] . "themes/$id/raw/$name");
    $im2 = new Imagick($ini['path.media'] . "themes/$id/raw/$name");
    $im3 = new Imagick($ini['path.media'] . "themes/$id/raw/$name");
    $im4 = new Imagick($ini['path.media'] . "themes/$id/raw/$name");
    $im5 = new Imagick($ini['path.media'] . "themes/$id/raw/$name");
    if (is_array($c)) {
      if ($im->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {
        $im->setImageFormat('jpg');
        $im->writeImage($folder . $name);
        $im->destroy();
        self::createImageFullname($folder . $name, "themes/$id/big/" . $name, 0);
      }
      if ($im2->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {
        $im2->setImageFormat('jpg');
        $im2->writeImage($folder2 . '60.jpg');
        $im2->destroy();
        self::createImageFullname($folder2 . '60.jpg', "themes/$id/main/" . '60.jpg', 60);
      }
      if ($im3->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {
        $im3->setImageFormat('jpg');
        $im3->writeImage($folder2 . '240.jpg');
        $im3->destroy();
        self::createImageFullname($folder2 . '240.jpg', "themes/$id/main/" . '240.jpg', 240);
      }
      if ($im4->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {
        $im4->setImageFormat('jpg');
        $im4->writeImage($folder2 . '400.jpg');
        $im4->destroy();
        self::createImageFullname($folder2 . '400.jpg', "themes/$id/main/" . '400.jpg', 400);
      }

      if ($im5->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {
        $im5->setImageFormat('jpg');
        $im5->writeImage($folder3 . $name);
        $im5->destroy();
        self::createImageFullname($folder3 . $name, "themes/$id/gallery/" . $name, 178, false);
        return true;
      }

    }
  }

  public static function cropImgAuthor($id, $c, $name)
  {
    global $ini;
    $folder = $ini['path.media'] . "author/$id/";
    $im = new Imagick($ini['path.media'] . "author/$id/orig.jpg");
    //$imageprops = $im->getImageGeometry();
    if (is_array($c))
      if ($im->cropImage($c['w'], $c['h'], $c['x'], $c['y'])) {

        $im->setImageFormat('jpg');
        $im->writeImage($folder . $name);
        $im->destroy();
        return true;
      }
  }

  public static function mkFolder($dir, $need_dell = false)
  { // $need_dell = true - отчистка папки
    global $ini;
    if (!is_dir($ini['path.media'] . $dir)) {
      mkdir($ini['path.media'] . $dir, 0777);
    } elseif ($need_dell) {
      self::delFolder($ini['path.media'] . $dir);
      mkdir($ini['path.media'] . $dir, 0777);
    }
    //return $dir.'/';
  }

  public static function delFolder($dir)
  {
    if (is_dir($dir)) {
      foreach (glob($dir . '/*') as $file) {
        if (is_dir($file))
          self::delFolder($file);
        else
          unlink($file);
      }
      rmdir($dir);
    }
  }

  public static function delSessionFolder()
  {
    global $ini;
    self::delFolder($ini['path.media'] . 'tmp/' . session_id());
  }

  public static function mkNewsFolder($id)
  {
    global $ini;
    self::mkFolder('images/' . $id);
    //self::mkFolder('images/'.$id.'/gallery');
  }

  public static function mkSessionFolder($need_dell = false)
  {
    global $ini;
    self::mkFolder('tmp/' . session_id(), $need_dell);
    self::mkFolder('tmp/' . session_id() . "/images", $need_dell);
    return $ini['path.media'] . 'tmp/' . session_id() . "/images/";
  }

  public static function delNewsImgSlider($Id)
  {
    global $ini;
    unlink($ini['path.media'] . 'images/' . $Id . '/slideimg.jpg');
    unlink($ini['path.media'] . 'images/' . $Id . '/slidetumb.jpg');
  }

  public static function delNewsImgMain($Id)
  {
    global $ini;
    unlink($ini['path.media'] . 'images/' . $Id . '/bigtumb.jpg');
    unlink($ini['path.media'] . 'images/' . $Id . '/tumb.jpg');
    unlink($ini['path.media'] . 'images/' . $Id . '/new.jpg');
  }


}

?>