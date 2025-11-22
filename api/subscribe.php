<?php
// api/subscribe.php - Newsletter subscription handler

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Include database config
require_once __DIR__ . '/config/database.php';

try {
    // Get and validate email
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        throw new Exception('Email address is required');
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Please enter a valid email address');
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("
        SELECT id, status, created_at 
        FROM newsletter_subscribers 
        WHERE email = ? 
        LIMIT 1
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $existing = $result->fetch_assoc();
        $stmt->close();
        
        if ($existing['status'] === 'active') {
            throw new Exception('This email is already subscribed to our newsletter');
        } elseif ($existing['status'] === 'unsubscribed') {
            // Reactivate subscription
            $stmt = $conn->prepare("
                UPDATE newsletter_subscribers 
                SET status = 'active', 
                    resubscribed_at = NOW(),
                    updated_at = NOW()
                WHERE email = ?
            ");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->close();
            
            echo json_encode([
                'success' => true,
                'message' => '🎉 Welcome back! You\'ve been resubscribed to our newsletter.'
            ]);
            exit();
        }
    } else {
        $stmt->close();
    }
    
    // Generate verification token
    $token = bin2hex(random_bytes(32));
    
    // Get IP address and user agent
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    // Get referrer source
    $source = $_SERVER['HTTP_REFERER'] ?? 'direct';
    $sourcePage = 'website';
    
    if (strpos($source, '/categories/') !== false) {
        $sourcePage = 'categories';
    } elseif (strpos($source, '/runner/') !== false) {
        $sourcePage = 'runner_dashboard';
    } elseif (strpos($source, '/requester/') !== false) {
        $sourcePage = 'requester_dashboard';
    }
    
    // Insert new subscriber
    $stmt = $conn->prepare("
        INSERT INTO newsletter_subscribers 
        (email, verification_token, status, source, ip_address, user_agent, created_at) 
        VALUES (?, ?, 'active', ?, ?, ?, NOW())
    ");
    
    $status = 'active'; // Can be 'pending' if you want email verification
    $stmt->bind_param("sssss", $email, $token, $sourcePage, $ipAddress, $userAgent);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to subscribe. Please try again.');
    }
    
    $subscriberId = $conn->insert_id;
    $stmt->close();
    
    // Optional: Send welcome email
    if (function_exists('sendWelcomeEmail')) {
        sendWelcomeEmail($email, $token);
    }
    
    // Log the subscription
    error_log("New newsletter subscriber: $email (ID: $subscriberId, Source: $sourcePage)");
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => '🎉 Successfully subscribed! Welcome to the Runaz community.',
        'subscriber_id' => $subscriberId
    ]);
    
} catch (Exception $e) {
    // Log error
    error_log("Newsletter subscription error: " . $e->getMessage());
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}

// Optional: Send welcome email function
function sendWelcomeEmail($email, $token) {
    // This is a placeholder - implement your email sending logic
    // You can use PHPMailer, SendGrid, Mailgun, etc.
    
    $subject = "Welcome to Runaz Newsletter!";
    $message = "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            <h2>Welcome to Runaz! 🎉</h2>
            <p>Thank you for subscribing to our newsletter.</p>
            <p>You'll now receive:</p>
            <ul>
                <li>Latest service requests in your area</li>
                <li>Featured providers and success stories</li>
                <li>Exclusive deals and promotions</li>
                <li>Tips for getting the most out of Runaz</li>
            </ul>
            <p>Stay tuned for great updates!</p>
            <p>If you didn't sign up for this, you can unsubscribe anytime.</p>
            <p>
                <a href='https://runaz.app/unsubscribe?token=$token' 
                   style='color: #3b82f6; text-decoration: underline;'>
                   Unsubscribe
                </a>
            </p>
        </body>
        </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Runaz <no-reply@runaz.app>" . "\r\n";
    
    // Uncomment to actually send email
    // mail($email, $subject, $message, $headers);
}
?>