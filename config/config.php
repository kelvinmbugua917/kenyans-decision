<?php
/**
 * Kenyans Decision - Production & Local Configuration
 */

// Allow local overrides via config.local.php if present
if (file_exists(__DIR__ . '/config.local.php')) {
    return require __DIR__ . '/config.local.php';
}

$httpHost = $_SERVER['HTTP_HOST'] ?? '';
$isLocal = in_array($httpHost, ['localhost', '127.0.0.1', 'localhost:8000', 'localhost:3000']) 
    || strpos($httpHost, 'ais-dev') !== false;

return [
    'app_name' => 'Kenyans Decision',
    'app_url' => $isLocal ? 'http://localhost/' : 'https://kenyansdecision.online/',
    'env' => $isLocal ? 'development' : 'production',

    'db' => [
        'host' => $isLocal ? '127.0.0.1' : (getenv('DB_HOST') ?: 'db_host'),
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => $isLocal ? 'kenyans_decision_db' : (getenv('DB_NAME') ?: 'Db_name'),
        'user' => $isLocal ? 'root' : (getenv('DB_USER') ?: 'db_usrname'),
        'password' => $isLocal ? '' : (getenv('DB_PASSWORD') ?: 'pass'),
        'charset' => 'utf8mb4',
    ],

    'security' => [
        'app_key' => getenv('APP_KEY') ?: 'secret_key',
        'vote_hmac_key' => getenv('VOTE_HMAC_KEY') ?: 'secret_key',
        'session_lifetime' => 86400 * 7,
    ],

    'defaults' => [
        'admin_email' => 'admin@kenyansdecision.co.ke',
    ]
];


