<?php
// api/login.php
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
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) && $_POST['remember'] === '1';

    // Validate required fields
    if (empty($login) || empty($password)) {
        throw new Exception('Please provide both email/phone and password');
    }

    // Determine if login is email or phone
    $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);
    
    // Prepare SQL based on login type
    if ($isEmail) {
        $stmt = $conn->prepare("
            SELECT u.id, u.email, u.phone, u.password_hash, u.role, u.status, 
                   u.email_verified, u.phone_verified,
                   p.first_name, p.last_name, p.avatar_url
            FROM users u
            LEFT JOIN user_profiles p ON u.id = p.user_id
            WHERE u.email = ?
            LIMIT 1
        ");
        $stmt->bind_param("s", $login);
    } else {
        // Clean phone number (remove spaces, dashes, etc.)
        $cleanPhone = preg_replace('/[^0-9+]/', '', $login);
        
        $stmt = $conn->prepare("
            SELECT u.id, u.email, u.phone, u.password_hash, u.role, u.status, 
                   u.email_verified, u.phone_verified,
                   p.first_name, p.last_name, p.avatar_url
            FROM users u
            LEFT JOIN user_profiles p ON u.id = p.user_id
            WHERE u.phone = ? OR u.phone = ?
            LIMIT 1
        ");
        $stmt->bind_param("ss", $login, $cleanPhone);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Invalid email/phone or password');
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        // Log failed login attempt
        $stmt = $conn->prepare("
            INSERT INTO login_attempts (user_id, ip_address, success, created_at) 
            VALUES (?, ?, 0, NOW())
        ");
        if ($stmt) {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $stmt->bind_param("is", $user['id'], $ipAddress);
            $stmt->execute();
            $stmt->close();
        }
        
        throw new Exception('Invalid email/phone or password');
    }

    // Check account status
    if ($user['status'] === 'suspended') {
        throw new Exception('Your account has been suspended. Please contact support.');
    }

    if ($user['status'] === 'banned') {
        throw new Exception('Your account has been banned. Please contact support.');
    }

    if ($user['status'] === 'pending') {
        throw new Exception('Your account is pending verification. Please check your email.');
    }

    // Log successful login
    $stmt = $conn->prepare("
        INSERT INTO login_attempts (user_id, ip_address, success, created_at) 
        VALUES (?, ?, 1, NOW())
    ");
    if ($stmt) {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $stmt->bind_param("is", $user['id'], $ipAddress);
        $stmt->execute();
        $stmt->close();
    }

    // Update last login
    $stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        $stmt->close();
    }

    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['phone'] = $user['phone'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['status'] = $user['status'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['avatar_url'] = $user['avatar_url'];
    $_SESSION['logged_in'] = true;
    $_SESSION['login_time'] = time();

    // Set remember me cookie if requested
    if ($remember) {
        // Generate a secure token
        $token = bin2hex(random_bytes(32));
        $expiry = time() + (30 * 24 * 60 * 60); // 30 days
        
        // Store token in database
        $stmt = $conn->prepare("
            INSERT INTO remember_tokens (user_id, token, expires_at, created_at) 
            VALUES (?, ?, FROM_UNIXTIME(?), NOW())
            ON DUPLICATE KEY UPDATE 
            token = VALUES(token), 
            expires_at = VALUES(expires_at)
        ");
        if ($stmt) {
            $stmt->bind_param("isi", $user['id'], $token, $expiry);
            $stmt->execute();
            $stmt->close();
        }
        
        // Set cookie
        setcookie('runaz_remember', $token, $expiry, '/', '', true, true);
    }

    // Determine redirect based on role
    $redirect = '../dashboard/';
    if ($user['role'] === 'runner') {
        $redirect = '../runner/dashboard/';
    } elseif ($user['role'] === 'requester') {
        $redirect = '../requester/dashboard/';
    }

    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Login successful! Welcome back, ' . htmlspecialchars($user['first_name'] ?? 'User') . '!',
        'redirect' => $redirect,
        'user' => [
            'id' => $user['id'],
            'name' => trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')),
            'email' => $user['email'],
            'role' => $user['role']
        ]
    ]);

} catch (Exception $e) {
    // Log error
    error_log("Login error: " . $e->getMessage());
    
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
?>