<?php
namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class CsrfMiddleware {
    public static function generateToken(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function handle(Request $request): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $method = $request->getMethod();
        if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
            $token = $request->getParam('csrf_token') ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            $sessionToken = $_SESSION['csrf_token'] ?? null;

            if (!$token || !$sessionToken || !hash_equals($sessionToken, $token)) {
                // Return 403 CSRF failure
                Response::error('Invalid or expired CSRF security token.', 403);
            }
        }
    }
}
