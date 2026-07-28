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
                error_log('Database Connection Error: ' . $e->getMessage());

                $isApi = strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') !== false;
                
                if ($isApi) {
                    http_response_code(500);
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => 'Database connection unavailable',
                        'message' => (($config['env'] ?? 'production') === 'development') ? $e->getMessage() : 'Please check MySQL host and credentials in config/config.php.'
                    ]);
                    exit;
                }

                // Render friendly HTML setup & troubleshooting card for browser requests
                http_response_code(500);
                echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup Required - Kenyans Decision</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-6 font-[\'Plus_Jakarta_Sans\']">
    <div class="max-w-xl w-full bg-slate-800/90 border border-slate-700/80 p-8 rounded-3xl shadow-2xl backdrop-blur-md space-y-6">
        <div class="inline-flex items-center gap-2 bg-amber-500/20 text-amber-400 border border-amber-500/30 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
            Database Setup / Connection Checklist
        </div>
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">Database Connection Pending 🇰🇪</h1>
            <p class="text-slate-300 text-sm leading-relaxed">
                The application could not connect to the MySQL database host (<code class="text-amber-300 bg-slate-900/80 px-2 py-0.5 rounded">' . htmlspecialchars($db['host']) . '</code>).
            </p>
        </div>

        <div class="bg-slate-900/90 border border-slate-700 p-4 rounded-2xl text-xs space-y-2 font-mono text-slate-300">
            <div class="text-rose-400 font-bold mb-1">PDO Connection Message:</div>
            <div class="bg-slate-950 p-2.5 rounded text-rose-300 overflow-x-auto">' . htmlspecialchars($e->getMessage()) . '</div>
        </div>

        <div class="space-y-3 text-xs text-slate-300">
            <h3 class="font-bold text-white text-sm">How to fix this error on InfinityFree hosting:</h3>
            <ol class="list-decimal list-inside space-y-2 leading-relaxed">
                <li><strong class="text-emerald-400">Remote MySQL Blocked:</strong> Free web hosts (like InfinityFree) block external connections to <code class="text-amber-300">sql212.infinityfree.com</code> from outside servers. The code must run directly inside InfinityFree htdocs.</li>
                <li><strong class="text-emerald-400">Import Database Schema:</strong> Open phpMyAdmin on InfinityFree, select database <code class="text-amber-300">' . htmlspecialchars($db['name']) . '</code>, and import <code class="text-amber-300">database/schema.sql</code> & <code class="text-amber-300">database/seed.sql</code>.</li>
                <li><strong class="text-emerald-400">Check Credentials in Control Panel:</strong> Verify that the MySQL User (<code class="text-amber-300">' . htmlspecialchars($db['user']) . '</code>) and Database Name match your InfinityFree MySQL panel details.</li>
            </ol>
        </div>

        <div class="pt-2 border-t border-slate-700/80 flex justify-between items-center text-xs text-slate-400">
            <span>Kenyans Decision &copy; 2027</span>
            <a href="?" class="text-emerald-400 hover:underline font-semibold">🔄 Retry Connection</a>
        </div>
    </div>
</body>
</html>';
                exit;
            }
        }

        return self::$instance;
    }
}
