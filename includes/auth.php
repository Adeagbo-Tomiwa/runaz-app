<?php
// includes/auth.php - Authentication middleware
// Include this at the top of protected pages

session_start();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Check remember me token if not logged in
function checkRememberMe($conn) {
    if (isset($_COOKIE['runaz_remember'])) {
        $token = $_COOKIE['runaz_remember'];
        
        $stmt = $conn->prepare("
            SELECT u.id, u.email, u.phone, u.role, u.status,
                   p.first_name, p.last_name, p.avatar_url,
                   rt.expires_at
            FROM remember_tokens rt
            JOIN users u ON rt.user_id = u.id
            LEFT JOIN user_profiles p ON u.id = p.user_id
            WHERE rt.token = ? AND rt.expires_at > NOW()
            LIMIT 1
        ");
        
        if ($stmt) {
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                // Restore session
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
                
                $stmt->close();
                return true;
            }
            $stmt->close();
        }
        
        // Invalid or expired token - delete it
        setcookie('runaz_remember', '', time() - 3600, '/', '', true, true);
    }
    
    return false;
}

// Require login - redirect to login page if not authenticated
function requireLogin($redirectTo = '../login/') {
    if (!isLoggedIn()) {
        // Try remember me token
        require_once __DIR__ . '/../api/config/database.php';
        if (!checkRememberMe($conn)) {
            // Store current page for redirect after login
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . $redirectTo);
            exit();
        }
    }
}

// Require specific role
function requireRole($role, $redirectTo = '../dashboard/') {
    requireLogin();
    
    if ($_SESSION['role'] !== $role) {
        header('Location: ' . $redirectTo);
        exit();
    }
}

// Get current user info
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['email'],
        'phone' => $_SESSION['phone'],
        'role' => $_SESSION['role'],
        'status' => $_SESSION['status'],
        'first_name' => $_SESSION['first_name'] ?? '',
        'last_name' => $_SESSION['last_name'] ?? '',
        'full_name' => trim(($_SESSION['first_name'] ?? '') . ' ' . ($_SESSION['last_name'] ?? '')),
        'avatar_url' => $_SESSION['avatar_url'] ?? null
    ];
}

// Check if user has specific role
function hasRole($role) {
    return isLoggedIn() && $_SESSION['role'] === $role;
}

// Logout function
function logout() {
    require_once __DIR__ . '/../api/config/database.php';
    
    // Remove remember token
    if (isset($_COOKIE['runaz_remember']) && isset($conn)) {
        $token = $_COOKIE['runaz_remember'];
        $stmt = $conn->prepare("DELETE FROM remember_tokens WHERE token = ?");
        if ($stmt) {
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    // Clear cookie
    if (isset($_COOKIE['runaz_remember'])) {
        setcookie('runaz_remember', '', time() - 3600, '/', '', true, true);
    }
    
    // Destroy session
    $_SESSION = array();
    session_destroy();
    
    if (isset($conn)) {
        $conn->close();
    }
}

// Example usage in protected pages:
// require_once '../includes/auth.php';
// requireLogin(); // Require any logged in user
// requireRole('runner'); // Require runner role specifically
// $currentUser = getCurrentUser(); // Get current user data
?>