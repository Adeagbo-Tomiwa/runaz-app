<?php
// config/email.php - Email Configuration and Sender

class EmailService {
    private $mailer;
    private $usePHPMailer;
    
    // Email configuration
    private $config = [
        // SMTP Settings (for PHPMailer)
        'smtp_host' => 'smtp.gmail.com',  // Gmail SMTP
        'smtp_port' => 587,
        'smtp_username' => 'runazapp@gmail.com',  // CHANGE THIS
        'smtp_password' => 'cdsabqxvcteaovkb',     // CHANGE THIS (use App Password for Gmail)
        'smtp_secure' => 'tls',
        
        // Sender Details
        'from_email' => 'noreply@runaz.app',
        'from_name' => 'Runaz Team',
        
        // Options
        'use_phpmailer' => true  // Set to false to use PHP mail() function
    ];
    
    public function __construct() {
        $this->usePHPMailer = $this->config['use_phpmailer'] && class_exists('PHPMailer\PHPMailer\PHPMailer');
        
        if ($this->usePHPMailer) {
            $this->initializePHPMailer();
        }
    }
    
    /**
     * Initialize PHPMailer
     */
    private function initializePHPMailer() {
        require_once __DIR__ . '/../vendor/autoload.php';
        
        $this->mailer = new PHPMailer\PHPMailer\PHPMailer(true);
        
        try {
            // SMTP Configuration
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['smtp_host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->config['smtp_username'];
            $this->mailer->Password = $this->config['smtp_password'];
            $this->mailer->SMTPSecure = $this->config['smtp_secure'];
            $this->mailer->Port = $this->config['smtp_port'];
            
            // Sender
            $this->mailer->setFrom($this->config['from_email'], $this->config['from_name']);
            $this->mailer->isHTML(true);
            
        } catch (Exception $e) {
            error_log("PHPMailer initialization error: " . $e->getMessage());
            $this->usePHPMailer = false;
        }
    }
    
    /**
     * Send welcome email after registration
     */
    public function sendWelcomeEmail($toEmail, $name, $role) {
        $subject = "Welcome to Runaz! 🎉";
        $message = $this->getWelcomeEmailTemplate($name, $role);
        
        return $this->send($toEmail, $subject, $message);
    }
    
    /**
     * Send verification email
     */
    public function sendVerificationEmail($toEmail, $name, $verificationLink) {
        $subject = "Verify Your Runaz Account";
        $message = $this->getVerificationEmailTemplate($name, $verificationLink);
        
        return $this->send($toEmail, $subject, $message);
    }
    
    /**
     * Send KYC approval email
     */
    public function sendKYCApprovedEmail($toEmail, $name, $role) {
        $subject = "Your Runaz Account is Now Active! ✅";
        $message = $this->getKYCApprovedTemplate($name, $role);
        
        return $this->send($toEmail, $subject, $message);
    }
    
    /**
     * Send KYC rejection email
     */
    public function sendKYCRejectedEmail($toEmail, $name, $reason) {
        $subject = "Action Required: KYC Verification Issue";
        $message = $this->getKYCRejectedTemplate($name, $reason);
        
        return $this->send($toEmail, $subject, $message);
    }
    
    /**
     * Main send function
     */
    private function send($to, $subject, $htmlMessage) {
        if ($this->usePHPMailer) {
            return $this->sendWithPHPMailer($to, $subject, $htmlMessage);
        } else {
            return $this->sendWithMailFunction($to, $subject, $htmlMessage);
        }
    }
    
    /**
     * Send email using PHPMailer
     */
    private function sendWithPHPMailer($to, $subject, $htmlMessage) {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $htmlMessage;
            $this->mailer->AltBody = strip_tags($htmlMessage);
            
            $this->mailer->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Email send error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send email using PHP mail() function
     */
    private function sendWithMailFunction($to, $subject, $htmlMessage) {
        $headers = "From: " . $this->config['from_name'] . " <" . $this->config['from_email'] . ">\r\n";
        $headers .= "Reply-To: " . $this->config['from_email'] . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        
        return mail($to, $subject, $htmlMessage, $headers);
    }
    
    /**
     * Welcome email template
     */
    private function getWelcomeEmailTemplate($name, $role) {
        $roleTitle = ucfirst($role);
        $roleBenefits = $role === 'runner' 
            ? "Start accepting jobs and building your reputation"
            : "Start requesting services from verified providers";
        
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .header { background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); color: white; padding: 40px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 40px 30px; }
        .content h2 { color: #3B82F6; margin-top: 0; }
        .badge { display: inline-block; background: #FCD34D; color: #78350F; padding: 8px 16px; border-radius: 20px; font-weight: bold; font-size: 14px; margin: 20px 0; }
        .info-box { background: #F3F4F6; border-left: 4px solid #3B82F6; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .button { display: inline-block; background: #3B82F6; color: white; text-decoration: none; padding: 14px 32px; border-radius: 8px; margin: 20px 0; font-weight: bold; }
        .button:hover { background: #2563EB; }
        .footer { background: #F9FAFB; padding: 30px; text-align: center; color: #6B7280; font-size: 14px; border-top: 1px solid #E5E7EB; }
        .steps { list-style: none; padding: 0; counter-reset: step-counter; }
        .steps li { counter-increment: step-counter; margin: 15px 0; padding-left: 40px; position: relative; }
        .steps li:before { content: counter(step-counter); position: absolute; left: 0; top: 0; background: #3B82F6; color: white; width: 28px; height: 28px; border-radius: 50%; text-align: center; line-height: 28px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Welcome to Runaz!</h1>
        </div>
        
        <div class="content">
            <h2>Hi $name,</h2>
            
            <p>Thank you for registering with Runaz! We're excited to have you join our community.</p>
            
            <div style="text-align: center;">
                <span class="badge">Registered as: $roleTitle</span>
            </div>
            
            <div class="info-box">
                <strong>📋 What happens next?</strong>
                <ol class="steps">
                    <li>Our team will review your KYC documents</li>
                    <li>You'll receive an email once your account is verified</li>
                    <li>This process typically takes 24-48 hours</li>
                    <li>$roleBenefits</li>
                </ol>
            </div>
            
            <p><strong>Your Account Details:</strong></p>
            <ul>
                <li><strong>Role:</strong> $roleTitle</li>
                <li><strong>Status:</strong> Pending Verification</li>
                <li><strong>Registration Date:</strong> " . date('F j, Y') . "</li>
            </ul>
            
            <p>While you wait, you can:</p>
            <ul>
                <li>✅ Check your email for updates</li>
                <li>✅ Review our <a href="https://runaz.app/guidelines">Community Guidelines</a></li>
                <li>✅ Learn about <a href="https://runaz.app/safety">Safety Tips</a></li>
            </ul>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="https://runaz.app/login" class="button">Go to Dashboard</a>
            </div>
            
            <p><strong>Need help?</strong><br>
            If you have any questions, our support team is here to help at <a href="mailto:support@runaz.app">support@runaz.app</a></p>
        </div>
        
        <div class="footer">
            <p><strong>Runaz - Connecting Service Providers & Requesters</strong></p>
            <p>You received this email because you registered an account on Runaz.</p>
            <p style="margin-top: 20px;">
                <a href="https://runaz.app" style="color: #3B82F6; text-decoration: none;">Visit Website</a> | 
                <a href="https://runaz.app/help" style="color: #3B82F6; text-decoration: none;">Help Center</a> | 
                <a href="https://runaz.app/contact" style="color: #3B82F6; text-decoration: none;">Contact Us</a>
            </p>
            <p style="font-size: 12px; color: #9CA3AF; margin-top: 20px;">
                © 2024 Runaz. All rights reserved.<br>
                Lagos, Nigeria
            </p>
        </div>
    </div>
</body>
</html>
HTML;
    }
    
    /**
     * Verification email template
     */
    private function getVerificationEmailTemplate($name, $verificationLink) {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .header { background: #3B82F6; color: white; padding: 40px 20px; text-align: center; }
        .content { padding: 40px 30px; }
        .button { display: inline-block; background: #3B82F6; color: white; text-decoration: none; padding: 14px 32px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verify Your Email</h1>
        </div>
        <div class="content">
            <h2>Hi $name,</h2>
            <p>Please click the button below to verify your email address:</p>
            <div style="text-align: center;">
                <a href="$verificationLink" class="button">Verify Email Address</a>
            </div>
            <p>Or copy and paste this link into your browser:</p>
            <p style="word-break: break-all; color: #3B82F6;">$verificationLink</p>
            <p>This link will expire in 24 hours.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
    
    /**
     * KYC Approved email template
     */
    private function getKYCApprovedTemplate($name, $role) {
        $nextSteps = $role === 'runner'
            ? "complete your profile, set your availability, and start accepting jobs"
            : "browse verified service providers and request services";
            
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .header { background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white; padding: 40px 20px; text-align: center; }
        .content { padding: 40px 30px; }
        .button { display: inline-block; background: #10B981; color: white; text-decoration: none; padding: 14px 32px; border-radius: 8px; margin: 20px 0; }
        .success-icon { font-size: 48px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="success-icon">✅</div>
            <h1>Account Verified!</h1>
        </div>
        <div class="content">
            <h2>Congratulations, $name!</h2>
            <p>Your KYC verification has been approved. Your Runaz account is now <strong>ACTIVE</strong>!</p>
            <p>You can now $nextSteps.</p>
            <div style="text-align: center;">
                <a href="https://runaz.app/login" class="button">Get Started Now</a>
            </div>
            <p>Welcome to the Runaz community! 🎉</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
    
    /**
     * KYC Rejected email template
     */
    private function getKYCRejectedTemplate($name, $reason) {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .header { background: #EF4444; color: white; padding: 40px 20px; text-align: center; }
        .content { padding: 40px 30px; }
        .info-box { background: #FEF2F2; border-left: 4px solid #EF4444; padding: 20px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>KYC Verification Issue</h1>
        </div>
        <div class="content">
            <h2>Hi $name,</h2>
            <p>We were unable to verify your KYC documents at this time.</p>
            <div class="info-box">
                <strong>Reason:</strong><br>
                $reason
            </div>
            <p>Please contact our support team at <a href="mailto:support@runaz.app">support@runaz.app</a> to resolve this issue.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
?>