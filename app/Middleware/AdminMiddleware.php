<?php
namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class AdminMiddleware {
    public function handle(Request $request): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = $_SESSION['user'] ?? null;

        if (!$user || ($user['role'] ?? '') !== 'admin') {
            if ($request->isAjax()) {
                Response::json(['error' => 'Access denied: Administrative privileges required.'], 403);
            } else {
                Response::error('Access denied: Administrative privileges required.', 403);
            }
        }
    }
}
