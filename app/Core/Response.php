<?php
namespace App\Core;

class Response {
    public static function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public static function render(string $viewPath, array $data = [], string $title = 'Kenyans Decision'): void {
        extract($data);
        $fullPath = __DIR__ . '/../../views/' . ltrim($viewPath, '/') . '.php';

        if (!file_exists($fullPath)) {
            self::json(['error' => "View template [{$viewPath}] not found."], 500);
        }

        // Render page within standard layout header and footer
        require __DIR__ . '/../../views/layouts/header.php';
        require $fullPath;
        require __DIR__ . '/../../views/layouts/footer.php';
        exit;
    }

    public static function redirect(string $url): void {
        header("Location: {$url}");
        exit;
    }

    public static function error(string $message, int $statusCode = 400): void {
        http_response_code($statusCode);
        if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
            self::json(['error' => $message], $statusCode);
        } else {
            self::render('errors/generic', ['message' => $message, 'code' => $statusCode], "Error {$statusCode}");
        }
    }
}
