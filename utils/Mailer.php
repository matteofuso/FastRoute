<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

class Mailer{
    static ?PHPMailer $mail = null;
    static function init($config){
        self::$mail = new PHPMailer(true);
        try {
            self::$mail->SMTPDebug = 2;
            self::$mail->isSMTP();
            self::$mail->Host = $config['mail_smtp'];
            self::$mail->SMTPAuth = $config['mail_auth'];
            self::$mail->Username = $config['mail_user'];
            self::$mail->Password = $config['mail_password'];
            self::$mail->SMTPSecure = $config['mail_smtps'] ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            self::$mail->Port = $config['mail_port'];
            self::$mail->setFrom($config['mail_user'], $config['mail_name']);
        } catch (Exception $e) {
            self::$mail = null;
        }
        return self::$mail;
    }
    static function send($to, $subject, $body){
        if (self::$mail == null){
            return false;
        }
        try {
            self::$mail->addAddress($to);
            self::$mail->isHTML(true);
            self::$mail->Subject = $subject;
            self::$mail->Body = $body;
            self::$mail->CharSet = 'UTF-8';
            self::$mail->Encoding = 'base64';
            self::$mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }

    }
}