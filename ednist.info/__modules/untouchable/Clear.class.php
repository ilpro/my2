<?php
class Clear{
    static function dataS($data){
                $data=strip_tags($data);
                $data=preg_replace( "'<[^>]+>'U", "", $data);
//                $data=htmlspecialchars($data, ENT_QUOTES);
               return  $data=str_replace('\\','',quotemeta(trim(strip_tags($data))));       
    }
    static function dataI($data){
                $result=abs($data*1);
                if($result!=0){
                   return $result;                    
                }
                else{
                    if($data===0 or $data==='0'){
                        $result=0;
                        return $result;
                        
                    }
                }

    }
    static function arrayS($array){
        if(is_array($array)){
            $result=array();
            foreach($array as $k=>$arr){
                 $result[$k]=self::dataS($arr);
            }
            if(!empty($result)) return $result;              
        }                      
    }
    static function arrayI($array){
        if(is_array($array)){
            $result=array();
            foreach($array as $k=>$arr){
                if(self::dataI($arr)!=null){
                    $result[$k]=self::dataI($arr);                
                }
            }
            if(!empty($result)) return $result;             
        }       
    }
   static function wordHTML($text, $allowed_tags = '<b><i><sup><sub><em><strong><u><br>')
    {
        mb_regex_encoding('UTF-8');
        //replace MS special characters first
        $search = array('/&lsquo;/u', '/&rsquo;/u', '/&ldquo;/u', '/&rdquo;/u', '/&mdash;/u');
        $replace = array('\'', '\'', '"', '"', '-');
        $text = preg_replace($search, $replace, $text);
        //make sure _all_ html entities are converted to the plain ascii equivalents - it appears
        //in some MS headers, some html entities are encoded and some aren't
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        //try to strip out any C style comments first, since these, embedded in html comments, seem to
        //prevent strip_tags from removing html comments (MS Word introduced combination)
        if(mb_stripos($text, '/*') !== FALSE){
            $text = mb_eregi_replace('#/\*.*?\*/#s', '', $text, 'm');
        }
        //introduce a space into any arithmetic expressions that could be caught by strip_tags so that they won't be
        //'<1' becomes '< 1'(note: somewhat application specific)
        $text = preg_replace(array('/<([0-9]+)/'), array('< $1'), $text);
        $text = strip_tags($text, $allowed_tags);
        //eliminate extraneous whitespace from start and end of line, or anywhere there are two or more spaces, convert it to one
        $text = preg_replace(array('/^\s\s+/', '/\s\s+$/', '/\s\s+/u'), array('', '', ' '), $text);
        //strip out inline css and simplify style tags
        $search = array('#<(strong|b)[^>]*>(.*?)</(strong|b)>#isu', '#<(em|i)[^>]*>(.*?)</(em|i)>#isu', '#<u[^>]*>(.*?)</u>#isu');
        $replace = array('<b>$2</b>', '<i>$2</i>', '<u>$1</u>');
        $text = preg_replace($search, $replace, $text);
        //on some of the ?newer MS Word exports, where you get conditionals of the form 'if gte mso 9', etc., it appears
        //that whatever is in one of the html comments prevents strip_tags from eradicating the html comment that contains
        //some MS Style Definitions - this last bit gets rid of any leftover comments */
        $num_matches = preg_match_all("/\<!--/u", $text, $matches);
        if($num_matches){
              $text = preg_replace('/\<!--(.)*--\>/isu', '', $text);
        }
        return $text;
    }
    static function symbols($string){
        
$string = strtolower(preg_replace("/[^a-zA-ZА-Яа-я0-9\s]/","",$string));
//$string = iconv('cp1251','UTF-8',$string);        
        
     //   $text = iconv('cp1251','UTF-8',$text);
     //  $result= preg_replace ("/[^a-zA-ZА-Яа-я0-9\s]/","",$text);
       return $string;
    }    
    
static function preposition($str){ 
setlocale(LC_COLLATE, 'ru_RU');
$str = strtolower($str);
$pattern = '/\s+(в|без|до|из|к|на|он|она|оно|его|ее|ему|ей|ним|вы|ты|я|мне|мое|твое|наш|ваш|их|как|на|мне|вам|нам|по|о|от|перед|при|но|через|с|у|и|нет|за|над|для|об|под|про|та|є|з|і|що|без|близ|в|вместо|вне|для|до|за|из|из-за|из-под|к|ко|кроме|между|меж|на|над|надо|о|об|обо|от|ото|перед|передо|пред|предо|пo|под|подо|при|про|ради|с|со|сквозь|среди|у|через|чрез)\s+/i';
$str = htmlspecialchars_decode($str);
$str = preg_replace($pattern,' ', $str);
$str = preg_replace("/[^А-Яа-я0-9ієї\s]/iu", " ", $str);
$str = preg_replace("/s(w+s)1/i", "$1", $str);//повторяющиеся слова
$str = preg_replace('|\s+|', ' ', $str);
//$pattern = '/[^\d]+/g'; 
//$str = preg_replace($pattern,'', $str);
return $str;
}        
    
}
?>