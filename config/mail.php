<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$env = $_ENV['APP_ENV'] ?? 'development';
$isDev = ($env === 'development');

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = $isDev ? $_ENV['SMTP_HOST_DEV'] : $_ENV['SMTP_HOST_PROD'];
    $mail->SMTPAuth = true;
    $mail->Username = $isDev ? $_ENV['SMTP_USERNAME_DEV'] : $_ENV['SMTP_USERNAME_PROD'];
    $mail->Password = $isDev ? $_ENV['SMTP_PASSWORD_DEV'] : $_ENV['SMTP_PASSWORD_PROD'];
    $mail->SMTPSecure = $isDev ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $isDev ? $_ENV['SMTP_PORT_DEV'] : $_ENV['SMTP_PORT_PROD'];

    $mail->setFrom(
        $isDev ? $_ENV['SMTP_FROM_EMAIL_DEV'] : $_ENV['SMTP_FROM_EMAIL_PROD'],
        $isDev ? $_ENV['SMTP_FROM_NAME_DEV'] : $_ENV['SMTP_FROM_NAME_PROD']
    );
} catch (Exception $e) {
    exit("❌ Mail configuration failed: {$e->getMessage()}");
}
