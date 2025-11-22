<?php
// /config/bootstrap.php

require_once __DIR__ . '/helpers.php';

// Determine current environment
$app_env = strtolower(env('APP_ENV', 'development'));

// Force HTTPS in production
if ($app_env === 'production') {
    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
        $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: $redirect");
        exit();
    }
}

// inside bootstrap.php, below the HTTPS redirect section
set_exception_handler(function ($e) use ($app_env) {
    if ($app_env === 'production'
) {
        error_log($e->getMessage());
        http_response_code(500);
        include __DIR__ . '/../error500.php';
        exit();
    } else {
        throw $e;
    }
});


// Configure error handling
if ($app_env === 'production') {
    // Disable error display
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);

    // Create a logs directory if missing
    $logDir = __DIR__ . '/../storage/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    // Log all errors to file
    $logFile = $logDir . '/error_' . date('Y-m-d') . '.log';
    ini_set('log_errors', 1);
    ini_set('error_log', $logFile);
} else {
    // Development: show all errors on screen
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
