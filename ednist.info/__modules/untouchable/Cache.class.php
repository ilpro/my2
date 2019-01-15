<?php
class Cache{
	 public function getLastModified($last_modified_unix,$expires=false){
                header("Cache-Control: public");
                $last_modified = gmdate("D, d M Y H:i:s ",$last_modified_unix);
                header('Last-Modified: '.$last_modified.'GMT');                          
                if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
                     $if_modified_sinse=$_SERVER['HTTP_IF_MODIFIED_SINCE'];
                }
                if($expires){                      
                    header("Expires:".gmdate("D, d M Y H:i:s ",$expires).'GMT');    	               
                }                                
                if (isset($if_modified_sinse)){
                    $if_modified_sinse = strtotime(substr($if_modified_sinse, 5));
                }
                                    
                if (isset($if_modified_sinse) && $if_modified_sinse >= $last_modified_unix) {
                    header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');                    
                    exit;
                }                                            
	 }  
}
?>