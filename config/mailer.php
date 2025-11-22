<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../vendor/autoload.php';

function getMailer(): PHPMailer {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = env_mode('SMTP_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = env_mode('SMTP_USERNAME');
        $mail->Password = env_mode('SMTP_PASSWORD');
        $mail->SMTPSecure = 'tls';
        $mail->Port = env_mode('SMTP_PORT');

        $mail->setFrom(env_mode('SMTP_FROM_EMAIL'), env_mode('SMTP_FROM_NAME'));
        $mail->isHTML(true);
    } catch (Exception $e) {
        die('Mailer Error: ' . $e->getMessage());
    }

    return $mail;
}
