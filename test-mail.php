<?php
require_once __DIR__ . './config/mail.php';

try {
    $mail->addAddress('tomiwaadeagbo7@gmail.com', 'Runaz Tester');
    $mail->isHTML(true);
    $mail->Subject = '✅ Test Email from Runaz App (' . ($_ENV['APP_ENV'] ?? 'unknown') . ')';
    $mail->Body = '<h2>PHPMailer Test Successful!</h2><p>Your environment is working correctly.</p>';

    $mail->send();
    echo '✅ Test mail sent successfully using ' . ($_ENV['APP_ENV'] ?? 'unknown') . ' environment.';
} catch (Exception $e) {
    echo "❌ Mail Error: {$mail->ErrorInfo}";
    
}
