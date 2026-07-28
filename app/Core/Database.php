<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/config.php';
            $db = $config['db'];

            $dsn = sprintf(
                "mysql:host=%s;port=%s;dbname=%s;charset=%s",
                $db['host'],
                $db['port'] ?? '3306',
                $db['name'],
                $db['charset'] ?? 'utf8mb4'
            );

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$instance = new PDO($dsn, $db['user'], $db['password'], $options);
            } catch (PDOException $e) {
                // Return clean error in production without exposing raw DB password
                if (($config['env'] ?? 'production') === 'development') {
                    die('Database Connection Error: ' . $e->getMessage());
                } else {
                    error_log('Database Connection Error: ' . $e->getMessage());
                    http_response_code(500);
                    echo json_encode(['error' => 'Database connection unavailable']);
                    exit;
                }
            }
        }

        return self::$instance;
    }
}
