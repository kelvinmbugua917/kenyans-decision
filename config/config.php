<?php
/**
 * Kenyans Decision - Runtime Configuration
 */

if (file_exists(__DIR__ . '/config.local.php')) {
    return require __DIR__ . '/config.local.php';
}

return require __DIR__ . '/config.example.php';
