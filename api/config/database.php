<?php
// api/config/database.php - Database configuration file

// Load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        die("❌ .env file not found at: " . $path);
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments and empty lines
        if (strpos(trim($line), '#') === 0 || empty(trim($line))) {
            continue;
        }
        
        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Store in $_ENV
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

// Load .env file (adjust path based on your structure)
$envPath = __DIR__ . '/../../.env';  // Go up 2 levels from api/config/
loadEnv($envPath);

// Determine environment
$appEnv = $_ENV['APP_ENV'] ?? 'development';

// Set database credentials based on environment
if ($appEnv === 'production') {
    define('DB_HOST', $_ENV['DB_HOST_PROD'] ?? 'localhost');
    define('DB_NAME', $_ENV['DB_NAME_PROD'] ?? 'runaz_live');
    define('DB_USER', $_ENV['DB_USER_PROD'] ?? 'runaz_user');
    define('DB_PASS', $_ENV['DB_PASS_PROD'] ?? '');
} else {
    define('DB_HOST', $_ENV['DB_HOST_DEV'] ?? 'localhost');
    define('DB_NAME', $_ENV['DB_NAME_DEV'] ?? 'runaz_app');
    define('DB_USER', $_ENV['DB_USER_DEV'] ?? 'root');
    define('DB_PASS', $_ENV['DB_PASS_DEV'] ?? '');
}

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    error_log("Database Connection Failed: " . $conn->connect_error);
    error_log("Attempted to connect to: " . DB_HOST . " / " . DB_NAME . " as " . DB_USER);
    die("❌ Database configuration missing. Please check your .env file.");
}

// Set charset to utf8mb4 for emoji and special character support
if (!$conn->set_charset("utf8mb4")) {
    error_log("Error loading character set utf8mb4: " . $conn->error);
}

// Set timezone for Nigeria (WAT - West Africa Time)
$conn->query("SET time_zone = '+01:00'");

// Debug mode (only in development)
if ($appEnv === 'development') {
    // Uncomment for debugging
    // echo "✅ Database connected: " . DB_NAME . "<br>";
}

// Prevent direct access
define('DB_CONNECTED', true);

?>