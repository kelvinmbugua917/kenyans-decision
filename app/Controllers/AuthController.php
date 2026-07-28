<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\User;

class AuthController {
    public function register(Request $request): void {
        if ($request->getMethod() === 'GET') {
            Response::render('auth/register', [], 'Register - Kenyans Decision');
            return;
        }

        $email = $request->getParam('email');
        $password = $request->getParam('password');
        $displayName = $request->getParam('displayName');
        $county = $request->getParam('county', 'Nairobi');

        if (empty($email) || empty($password) || empty($displayName)) {
            Response::json(['error' => 'All registration fields are required.'], 400);
        }

        try {
            $user = User::create($email, $password, $displayName, $county);

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user'] = $user;

            Response::json(['success' => true, 'user' => $user]);
        } catch (\Exception $e) {
            Response::json(['error' => $e->getMessage()], 400);
        }
    }

    public function login(Request $request): void {
        if ($request->getMethod() === 'GET') {
            Response::render('auth/login', [], 'Sign In - Kenyans Decision');
            return;
        }

        $email = $request->getParam('email');
        $password = $request->getParam('password');

        if (empty($email) || empty($password)) {
            Response::json(['error' => 'Email and password are required.'], 400);
        }

        $user = User::findByEmail($email);
        if (!$user || !User::verifyPassword($user, $password)) {
            Response::json(['error' => 'Invalid email address or password.'], 401);
        }

        unset($user['password_hash']);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user'] = $user;

        Response::json(['success' => true, 'user' => $user]);
    }

    public function logout(Request $request): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['user']);
        session_destroy();

        if ($request->isAjax()) {
            Response::json(['success' => true]);
        } else {
            Response::redirect('/');
        }
    }

    public function me(Request $request): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user = $_SESSION['user'] ?? null;
        Response::json(['user' => $user]);
    }
}
