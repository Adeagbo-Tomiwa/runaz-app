<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/var/log/php/errors.log');

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$env = $_ENV['APP_ENV'] ?? 'development';
$isDev = ($env === 'development');

// Select database credentials based on environment
$dbHost = $isDev ? $_ENV['DB_HOST_DEV'] : $_ENV['DB_HOST_PROD'];
$dbName = $isDev ? $_ENV['DB_NAME_DEV'] : $_ENV['DB_NAME_PROD'];
$dbUser = $isDev ? $_ENV['DB_USER_DEV'] : $_ENV['DB_USER_PROD'];
$dbPass = $isDev ? $_ENV['DB_PASS_DEV'] : $_ENV['DB_PASS_PROD'];

// Validate configuration
if (empty($dbHost) || empty($dbName) || empty($dbUser)) {
    exit("❌ Database configuration missing. Check your .env file and APP_ENV setting.");
}

try {
    $pdo = new PDO(
        "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
        $dbUser,
        $dbPass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Optional success message for testing
    // echo "✅ Connected to " . ($isDev ? "Development" : "Production") . " Database successfully!";
} catch (PDOException $e) {
    exit("❌ Connection failed: " . $e->getMessage());
}
