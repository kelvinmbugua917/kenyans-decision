<?php
namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class RateLimitMiddleware {
    public function handle(Request $request): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $ip = $request->getClientIp();
        $key = 'rate_limit_' . md5($ip);
        $currentTime = time();

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 1, 'start_time' => $currentTime];
            return;
        }

        // 1-minute window reset
        if ($currentTime - $_SESSION[$key]['start_time'] > 60) {
            $_SESSION[$key] = ['count' => 1, 'start_time' => $currentTime];
            return;
        }

        $_SESSION[$key]['count']++;

        // Threshold: maximum 60 requests per minute
        if ($_SESSION[$key]['count'] > 60) {
            Response::error('Rate limit exceeded. Please wait a minute before retrying.', 429);
        }
    }
}
