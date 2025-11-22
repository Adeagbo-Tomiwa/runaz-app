// Use PHPMailer or similar library
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your@email.com';
    $mail->Password = 'password';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    
    $mail->setFrom('noreply@runaz.com', 'Runaz');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;
    
    return $mail->send();
}