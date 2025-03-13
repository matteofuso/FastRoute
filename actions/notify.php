<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
$config = include '../config.php';

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = $config['mail_smtp'];
    $mail->SMTPAuth = $config['mail_auth'];
    $mail->Username = $config['mail_user'];
    $mail->Password = $config['mail_password'];
    $mail->SMTPSecure = $config['mail_smtps'] ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $config['mail_port'];

    $mail->setFrom($config['mail_user'], $config['mail_name']);
    $mail->addAddress('mfuso011@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Test mail subject';
    $mail->Body = '<h1>Test mail with html</h1>';
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->send();
} catch (Exception $e) {
    echo json_encode(['error' => $mail->ErrorInfo]);
}