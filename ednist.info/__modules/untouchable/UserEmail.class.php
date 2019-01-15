
<?php
class UserEmail {

    /**
     * функция генереирует письмо приветсвия новому пользователю
     * 
     */
    public static function emailActivation($email,$db_salt){ 
            
            $to= "<{$email}>" ;
            $subject = "Добро пожаловать на ednist.info!";

            require(Config::getAdminRoot()."/views/mail/mailActivation.view.php");
            $headers= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
            $headers .= "From: <info@ednist.info>\r\n";
            mail($to,'=?utf-8?B?'.base64_encode($subject).'?=', $message, $headers);               
    }  
    /**
     * генерирует строку подтверждения эмейла
     * @return string
     */
    public static function emailGenerateConfirm($db_salt){
        $string="http://".$_SERVER['SERVER_NAME']."/admin/account/confirm/?hash=".$db_salt;
        return $string;           
    }  
    
     /**
     * Генереирует письмо изменения пароля
     */
     public static function emailPassReset($password,$email){ 
            $to= "<{$email}>" ;
            $subject = "Изменение пароля на ednist.info!";

            require(Config::getAdminRoot()."/views/mail/passReset.view.php");
            $headers= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
            $headers .= "From: <info@ednist.info>\r\n";
            mail($to,'=?utf-8?B?'.base64_encode($subject).'?=', $message, $headers);               
    }    
    /**
     * генерируест строку сброса пароля
     * @return string
     */
    public static function emailGenerateReset(){
	    $string="http://".$_SERVER['SERVER_NAME']."/admin/account";
	    return $string;           
	}      
        
            /**
     * функция генереирует письмо 
     * 
     */
    public static function sendEmailAbout($email,$themes,$message){ 

            global $settings;
            $message.='<br /> Відправник: '.$email;
            $email='message@ednist.info';
            $to= $settings['settingsEmail'];
            $headers= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
            $headers .= "From: <".$email.">\r\n";
            $headers .= "Message-ID:<" . uniqid() . "@ednist.info>";
            mail($to,'=?utf-8?B?'.base64_encode($themes).'?=', $message, $headers); 
    }
}