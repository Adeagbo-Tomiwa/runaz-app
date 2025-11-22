<?php
// api/logout.php
session_start();

// Include database config for token cleanup
require_once __DIR__ . '/config/database.php';

// Remove remember token from database if exists
if (isset($_COOKIE['runaz_remember']) && isset($conn)) {
    $token = $_COOKIE['runaz_remember'];
    $stmt = $conn->prepare("DELETE FROM remember_tokens WHERE token = ?");
    if ($stmt) {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->close();
    }
}

// Clear remember cookie
if (isset($_COOKIE['runaz_remember'])) {
    setcookie('runaz_remember', '', time() - 3600, '/', '', true, true);
    unset($_COOKIE['runaz_remember']);
}

// Destroy session
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Close database connection
if (isset($conn)) {
    $conn->close();
}

// Redirect to login page
header('Location: ../login/?success=' . urlencode('You have been logged out successfully'));
exit();
?>