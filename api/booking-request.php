<?php
// api/booking-request.php - Complete booking request handler with email notifications

session_start();

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
    // Check database connection
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception('Database connection failed');
    }

    // Get form data
    $runner_id = isset($_POST['runner_id']) ? intval($_POST['runner_id']) : 0;
    $service_description = trim($_POST['service_description'] ?? '');
    $service_location = trim($_POST['service_location'] ?? '');
    $preferred_date = $_POST['preferred_date'] ?? '';
    $budget = isset($_POST['budget']) ? floatval($_POST['budget']) : null;
    $additional_details = trim($_POST['additional_details'] ?? '');
    $estimated_hours = isset($_POST['estimated_hours']) ? intval($_POST['estimated_hours']) : 1;

    // Validate required fields
    if (empty($runner_id)) {
        throw new Exception('Invalid runner ID');
    }

    if (empty($service_description) || strlen($service_description) < 10) {
        throw new Exception('Service description must be at least 10 characters');
    }

    if (strlen($service_description) > 1000) {
        throw new Exception('Service description cannot exceed 1000 characters');
    }

    if (empty($service_location)) {
        throw new Exception('Please provide the service location');
    }

    if (strlen($service_location) > 500) {
        throw new Exception('Service location cannot exceed 500 characters');
    }

    if (empty($preferred_date)) {
        throw new Exception('Please select a preferred date');
    }

    // Validate date format
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $preferred_date)) {
        throw new Exception('Invalid date format');
    }

    // Validate date is not in the past
    if (strtotime($preferred_date) < strtotime('today')) {
        throw new Exception('Preferred date must be today or later');
    }

    // Validate date is not too far in future (max 6 months)
    $max_future = strtotime('+6 months');
    if (strtotime($preferred_date) > $max_future) {
        throw new Exception('Preferred date cannot be more than 6 months in the future');
    }

    // Validate estimated hours
    if ($estimated_hours < 1 || $estimated_hours > 24) {
        throw new Exception('Estimated hours must be between 1 and 24');
    }

    // Validate budget if provided
    if (!empty($budget)) {
        if ($budget < 0) {
            throw new Exception('Budget cannot be negative');
        }
        if ($budget > 10000000) { // 10 million naira max
            throw new Exception('Budget exceeds maximum allowed amount');
        }
    }

    // Validate additional details length
    if (strlen($additional_details) > 2000) {
        throw new Exception('Additional details cannot exceed 2000 characters');
    }

    // Validate runner exists and is active
    $stmt = $conn->prepare("
        SELECT u.id, u.status, u.email, up.first_name, up.last_name, rp.hourly_rate
        FROM users u
        JOIN user_profiles up ON u.id = up.user_id
        JOIN runner_profiles rp ON u.id = rp.user_id
        WHERE u.id = ? AND u.role = 'runner' AND u.status = 'active'
        LIMIT 1
    ");
    
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $runner_id);
    $stmt->execute();
    $runnerResult = $stmt->get_result();

    if ($runnerResult->num_rows === 0) {
        throw new Exception('Runner not found or is inactive');
    }

    $runner = $runnerResult->fetch_assoc();
    $stmt->close();

    // Get requester ID from session
    $requester_id = $_SESSION['user_id'] ?? null;

    // If not logged in, get from POST (for API calls)
    if (empty($requester_id) && !empty($_POST['requester_id'])) {
        $requester_id = intval($_POST['requester_id']);
    }

    // If still no requester ID, prompt to login
    if (empty($requester_id)) {
        throw new Exception('Please log in to request a booking');
    }

    // Validate requester exists and is active
    $stmt = $conn->prepare("
        SELECT u.id, u.status, u.email, up.first_name, up.last_name
        FROM users u
        JOIN user_profiles up ON u.id = up.user_id
        WHERE u.id = ? AND u.status = 'active'
        LIMIT 1
    ");
    
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $requester_id);
    $stmt->execute();
    $requesterResult = $stmt->get_result();

    if ($requesterResult->num_rows === 0) {
        throw new Exception('Your account is not active');
    }

    $requester = $requesterResult->fetch_assoc();
    $stmt->close();

    // Prevent self-booking
    if ($requester_id === $runner_id) {
        throw new Exception('You cannot request a booking from yourself');
    }

    // Check for duplicate recent bookings (prevent spam)
    $stmt = $conn->prepare("
        SELECT COUNT(*) as recent_bookings
        FROM bookings
        WHERE requester_id = ? 
        AND runner_id = ?
        AND status IN ('pending', 'accepted')
        AND created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)
        LIMIT 1
    ");
    
    if ($stmt) {
        $stmt->bind_param("ii", $requester_id, $runner_id);
        $stmt->execute();
        $dupResult = $stmt->get_result();
        $dupCheck = $dupResult->fetch_assoc();
        $stmt->close();

        if ($dupCheck['recent_bookings'] > 0) {
            throw new Exception('You already have a pending booking with this runner. Please wait for a response.');
        }
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Calculate amount based on hourly rate and estimated time
        $amount = $runner['hourly_rate'] * $estimated_hours;

        // If budget is provided and higher, use it
        if (!empty($budget) && $budget > $amount) {
            $amount = $budget;
        }

        // Insert booking
        $stmt = $conn->prepare("
            INSERT INTO bookings 
            (requester_id, runner_id, service_description, service_location, budget, preferred_date, additional_details, amount, status, payment_status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', NOW())
        ");

        if (!$stmt) {
            throw new Exception('Database error: ' . $conn->error);
        }

        $stmt->bind_param(
            "iissssd",
            $requester_id,
            $runner_id,
            $service_description,
            $service_location,
            $budget,
            $preferred_date,
            $additional_details,
            $amount
        );

        if (!$stmt->execute()) {
            throw new Exception('Failed to create booking request');
        }

        $booking_id = $conn->insert_id;
        $stmt->close();

        // Log the booking activity
        $stmt = $conn->prepare("
            INSERT INTO registration_logs (user_id, step, ip_address, created_at) 
            VALUES (?, ?, ?, NOW())
        ");
        if ($stmt) {
            $step = 'booking_request_created';
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $stmt->bind_param("iss", $requester_id, $step, $ip_address);
            $stmt->execute();
            $stmt->close();
        }

        // Commit transaction
        $conn->commit();

        // Send notifications (after successful commit)
        $notification_sent_runner = false;
        $notification_sent_requester = false;

        try {
            $notification_sent_runner = sendBookingNotificationToRunner(
                $runner_id,
                $booking_id,
                $runner['email'],
                $runner['first_name'],
                $requester['first_name'] . ' ' . $requester['last_name'],
                $service_description,
                $preferred_date,
                $amount
            );
        } catch (Exception $e) {
            error_log("Failed to send runner notification: " . $e->getMessage());
        }

        try {
            $notification_sent_requester = sendBookingConfirmationToRequester(
                $requester_id,
                $booking_id,
                $requester['email'],
                $requester['first_name'],
                $runner['first_name'] . ' ' . $runner['last_name'],
                $service_description,
                $preferred_date,
                $amount
            );
        } catch (Exception $e) {
            error_log("Failed to send requester confirmation: " . $e->getMessage());
        }

        // Prepare success response
        $response = [
            'success' => true,
            'message' => 'Booking request sent successfully! ' . htmlspecialchars($runner['first_name']) . ' will review and respond within 24 hours.',
            'booking_id' => $booking_id,
            'runner_name' => htmlspecialchars($runner['first_name'] . ' ' . $runner['last_name']),
            'estimated_amount' => $amount,
            'preferred_date' => $preferred_date,
            'notifications_sent' => [
                'runner' => $notification_sent_runner,
                'requester' => $notification_sent_requester
            ],
            'redirect' => './bookings/'
        ];

        echo json_encode($response);

    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }

} catch (Exception $e) {
    error_log("Booking request error: " . $e->getMessage());

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

/**
 * Send booking notification email to runner
 * 
 * @param int $runner_id
 * @param int $booking_id
 * @param string $runner_email
 * @param string $runner_first_name
 * @param string $requester_name
 * @param string $service_description
 * @param string $preferred_date
 * @param float $amount
 * @return bool
 */
function sendBookingNotificationToRunner($runner_id, $booking_id, $runner_email, $runner_first_name, $requester_name, $service_description, $preferred_date, $amount) {
    
    if (empty($runner_email) || !filter_var($runner_email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $subject = "New Booking Request - " . htmlspecialchars($requester_name);
    
    $date_formatted = date('l, F j, Y', strtotime($preferred_date));
    
    $body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border-radius: 8px; }
            .header { background-color: #3b82f6; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
            .content { background-color: white; padding: 20px; border-radius: 0 0 8px 8px; }
            .booking-details { background-color: #f0f4f8; padding: 15px; border-left: 4px solid #3b82f6; margin: 20px 0; border-radius: 4px; }
            .detail-row { margin: 10px 0; }
            .label { font-weight: bold; color: #3b82f6; width: 140px; display: inline-block; }
            .button { display: inline-block; background-color: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; margin-top: 20px; }
            .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Booking Request</h2>
            </div>
            <div class='content'>
                <p>Hello " . htmlspecialchars($runner_first_name) . ",</p>
                
                <p>You have received a new booking request from <strong>" . htmlspecialchars($requester_name) . "</strong>.</p>
                
                <div class='booking-details'>
                    <div class='detail-row'>
                        <span class='label'>Service:</span>
                        " . htmlspecialchars($service_description) . "
                    </div>
                    <div class='detail-row'>
                        <span class='label'>Preferred Date:</span>
                        " . $date_formatted . "
                    </div>
                    <div class='detail-row'>
                        <span class='label'>Estimated Amount:</span>
                        ₦" . number_format($amount, 2) . "
                    </div>
                    <div class='detail-row'>
                        <span class='label'>Booking ID:</span>
                        #" . $booking_id . "
                    </div>
                </div>
                
                <p>Please log in to your Runaz account to review the full details and respond to this booking request.</p>
                
                <a href='" . getBaseUrl() . "/bookings/' class='button'>Review Booking</a>
                
                <div class='footer'>
                    <p>This is an automated email. Please do not reply directly to this message.</p>
                    <p>&copy; " . date('Y') . " Runaz. All rights reserved.</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";

    return sendEmail($runner_email, $subject, $body);
}

/**
 * Send booking confirmation email to requester
 * 
 * @param int $requester_id
 * @param int $booking_id
 * @param string $requester_email
 * @param string $requester_first_name
 * @param string $runner_name
 * @param string $service_description
 * @param string $preferred_date
 * @param float $amount
 * @return bool
 */
function sendBookingConfirmationToRequester($requester_id, $booking_id, $requester_email, $requester_first_name, $runner_name, $service_description, $preferred_date, $amount) {
    
    if (empty($requester_email) || !filter_var($requester_email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $subject = "Booking Request Confirmed - " . htmlspecialchars($runner_name);
    
    $date_formatted = date('l, F j, Y', strtotime($preferred_date));
    
    $body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border-radius: 8px; }
            .header { background-color: #10b981; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
            .content { background-color: white; padding: 20px; border-radius: 0 0 8px 8px; }
            .booking-details { background-color: #f0fdf4; padding: 15px; border-left: 4px solid #10b981; margin: 20px 0; border-radius: 4px; }
            .detail-row { margin: 10px 0; }
            .label { font-weight: bold; color: #10b981; width: 140px; display: inline-block; }
            .button { display: inline-block; background-color: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; margin-top: 20px; }
            .info-box { background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 4px; }
            .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Booking Request Confirmed</h2>
            </div>
            <div class='content'>
                <p>Hello " . htmlspecialchars($requester_first_name) . ",</p>
                
                <p>Your booking request has been successfully submitted to <strong>" . htmlspecialchars($runner_name) . "</strong>.</p>
                
                <div class='booking-details'>
                    <div class='detail-row'>
                        <span class='label'>Service Provider:</span>
                        " . htmlspecialchars($runner_name) . "
                    </div>
                    <div class='detail-row'>
                        <span class='label'>Service:</span>
                        " . htmlspecialchars($service_description) . "
                    </div>
                    <div class='detail-row'>
                        <span class='label'>Preferred Date:</span>
                        " . $date_formatted . "
                    </div>
                    <div class='detail-row'>
                        <span class='label'>Estimated Amount:</span>
                        ₦" . number_format($amount, 2) . "
                    </div>
                    <div class='detail-row'>
                        <span class='label'>Booking ID:</span>
                        #" . $booking_id . "
                    </div>
                </div>
                
                <div class='info-box'>
                    <strong>What happens next?</strong>
                    <p>" . htmlspecialchars($runner_name) . " will review your request and respond within 24 hours. You'll receive an email notification once they accept or decline.</p>
                </div>
                
                <p>You can track your booking status in your Runaz dashboard:</p>
                
                <a href='" . getBaseUrl() . "/bookings/' class='button'>View My Bookings</a>
                
                <div class='footer'>
                    <p>Questions? Contact our support team or visit our help center.</p>
                    <p>&copy; " . date('Y') . " Runaz. All rights reserved.</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";

    return sendEmail($requester_email, $subject, $body);
}

/**
 * Send email using PHP mail() function
 * 
 * @param string $to
 * @param string $subject
 * @param string $body (HTML)
 * @return bool
 */
function sendEmail($to, $subject, $body) {
    
    // Email configuration
    $from_email = getenv('SMTP_FROM_EMAIL') ?? 'noreply@runaz.com';
    $from_name = 'Runaz Platform';
    
    // Email headers
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . $from_name . " <" . $from_email . ">\r\n";
    $headers .= "Reply-To: " . $from_email . "\r\n";
    $headers .= "X-Mailer: Runaz Booking System\r\n";
    
    // Sanitize subject (prevent header injection)
    $subject = str_replace(["\r", "\n"], "", $subject);
    
    // Attempt to send email
    try {
        $result = @mail($to, $subject, $body, $headers);
        
        if ($result) {
            error_log("Email sent successfully to: " . $to);
            return true;
        } else {
            error_log("Failed to send email to: " . $to);
            return false;
        }
    } catch (Exception $e) {
        error_log("Email error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get base URL for email links
 * 
 * @return string
 */
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = $protocol . $_SERVER['HTTP_HOST'];
    
    // Remove port if it's standard
    if (($_SERVER['SERVER_PORT'] == 80 && $protocol === 'http://') || 
        ($_SERVER['SERVER_PORT'] == 443 && $protocol === 'https://')) {
        // Port is standard, don't include
    }
    
    return $url;
}
?>