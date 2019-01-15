<?php
date_default_timezone_set('Etc/UTC');
require 'mail/PHPMailerAutoload.php';

class SMTPMail{
    public static function sendMail($email, $themes, $message){
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = "ednist.send@gmail.com";
        $mail->Password = "y1y1y1y1";
        $mail->setFrom('ednist.send@gmail.com', 'Ednist.info');
        $mail->addReplyTo('ednist.info@gmail.com', 'Посититель');
        $mail->addAddress('ednist.info@gmail.com', 'Посититель');
        $mail->Subject = $themes;
        $mail->msgHTML($message);
        $mail->AltBody = 'This is a plain-text message body';
    }
}
