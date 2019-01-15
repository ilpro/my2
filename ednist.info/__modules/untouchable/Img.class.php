<?php
class Img{

    private function __construct() {}   
    public static function imgUrlExists($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        $is404 = curl_getinfo($ch, CURLINFO_HTTP_CODE) != 404;
        curl_close($ch);
        return $is404;
    }
    public static function get_data($url) {
      $ch = curl_init();
      $timeout = 5;
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
    }    
    public static function downloadFromPath($orderId,$path){       

        self::createImages($orderId,$path,800,320,50);
        return true;
    }

    public static function testSizeImg($image,$sizeX,$sizeY){ 
        $im = new Imagick($image); 
        $imageprops = $im->getImageGeometry();
        if($imageprops['width']<$sizeX and $imageprops['height']<$sizeY)
        return false;
        if($imageprops['width']<$sizeX)
        return 'smallX';
        if($imageprops['height']<$sizeY)
        return 'smallY';
        return true;
    }
    
    public static function showImg($type,$id,$img){
        global $ini;
        $file = $ini['url.media']."images/$id/big/$img";
        if(self::imgUrlExists($file)){
            //$img = new Imagick($file);
            $img1 = imagecreatefromjpeg($file);
            $img2 = imagecreatefrompng($ini['path.media']."settings/soclogo.jpg");
            
            $dest = imagecreatetruecolor(imagesx($img1)+imagesy($img1), imagesy($img1));

            imagecopyresampled($dest, $img2, 0, 0, 0, 0, imagesy($img1), imagesy($img1), imagesy($img2), imagesy($img2));
            imagecopy ($dest, $img1, imagesy($img1), 0, 0, 0, imagesx($img1), imagesy($img1));

            //imagecopy ($dest, $img2, 0, 0, 0, 0, imagesx($img2), imagesy($img2));
            
            header('Content-Type: image/jpg'."\n");
            imagejpeg($dest);
            
        }else echo "no image";
    }

     
     
    
}
?>