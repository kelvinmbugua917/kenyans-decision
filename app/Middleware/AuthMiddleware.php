<?php
namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class AuthMiddleware {
    public function handle(Request $request): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user'])) {
            if ($request->isAjax()) {
                Response::json(['error' => 'Authentication required. Please sign in.'], 401);
            } else {
                Response::redirect('/login');
            }
        }
    }
}
