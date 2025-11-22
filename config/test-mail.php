<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = $smtpHost;
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtpUser;
    $mail->Password   = $smtpPass;
    $mail->SMTPSecure = 'tls';
    $mail->Port       = $smtpPort;

    $mail->setFrom($smtpFrom, $smtpName);
    $mail->addAddress('youremail@example.com', 'Mail Test'); // 👈 change this

    $mail->isHTML(true);
    $mail->Subject = '✅ PHPMailer Test (' . strtoupper($appEnv) . ')';
    $mail->Body    = "This is a test email sent from the <b>$appEnv</b> environment of Runaz.";
    $mail->AltBody = "This is a test email from $appEnv environment.";

    $mail->send();
    echo "<h3 style='color:green;'>Email sent successfully from <b>$appEnv</b> environment!</h3>";
} catch (Exception $e) {
    echo "<h3 style='color:red;'>Email failed: {$mail->ErrorInfo}</h3>";
}
