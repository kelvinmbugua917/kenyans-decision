<?php
/**
 * Kenyans Decision - Configuration Example
 * Copy this file to config.php and update with your production database & security settings.
 */

return [
    'app_name' => 'Kenyans Decision',
    'app_url' => 'http://localhost', // e.g. https://kenyansdecision.co.ke
    'env' => 'production', // 'development' or 'production'

    'db' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'name' => 'kenyans_decision_db',
        'user' => 'kd_db_user',
        'password' => 'DB_Password_Change_Me_2027!',
        'charset' => 'utf8mb4',
    ],

    'security' => [
        'app_key' => 'kd_secret_app_key_84920492019402940294_2027',
        'vote_hmac_key' => 'kd_vote_hmac_secret_key_993848201948291_2027',
        'session_lifetime' => 86400 * 7, // 7 days
    ],

    'defaults' => [
        'admin_email' => 'admin@kenyansdecision.co.ke',
    ]
];
