<?php
// /config/helpers.php

function env($key, $default = null) {
    static $env;
    if (!$env) {
        $envFile = __DIR__ . '/../.env';
        if (!file_exists($envFile)) {
            throw new Exception('.env file not found!');
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $env = [];

        foreach ($lines as $line) {
            $line = trim($line);
            // Skip comments or invalid lines
            if ($line === '' || str_starts_with($line, '#') || str_starts_with($line, ';')) {
                continue;
            }

            // Split key=value pairs
            [$name, $value] = array_pad(explode('=', $line, 2), 2, null);
            if ($name && $value !== null) {
                $value = trim($value, " \t\n\r\0\x0B\"'");
                $env[$name] = $value;
            }
        }
    }

    return $env[$key] ?? $default;
}

/**
 * Automatically choose DEV or PROD values depending on APP_ENV
 * Example: env_mode('DB_HOST') will return either DB_HOST_DEV or DB_HOST_PROD
 */
function env_mode($baseKey, $default = null) {
    $mode = strtolower(env('APP_ENV', 'development'));
    $suffix = ($mode === 'production') ? '_PROD' : '_DEV';
    return env($baseKey . $suffix, $default);
}

/**
 * Get current BASE_URL based on environment
 */
function base_url() {
    return env_mode('BASE_URL');
}
