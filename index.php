<?php
/**
 * Kenyans Decision - PHP Front Controller & Router Entry Point
 */

declare(strict_types=1);

// PSR-4 Style Lightweight Class Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_strict_mode', '1');
    session_start();
}

use App\Core\Router;
use App\Core\Request;
use App\Middleware\CsrfMiddleware;
use App\Middleware\RateLimitMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;

$router = new Router();
$request = new Request();

// Generate CSRF Token for Forms
$csrfToken = CsrfMiddleware::generateToken();

// --- PUBLIC HTML & VIEW ROUTES ---
$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/polls', [\App\Controllers\PollController::class, 'index']);
$router->get('/polls/{id}', [\App\Controllers\PollController::class, 'show']);
$router->get('/polls/{id}/history', [\App\Controllers\PollController::class, 'history']);
$router->get('/discussions', [\App\Controllers\DiscussionController::class, 'index']);
$router->get('/discussions/{id}', [\App\Controllers\DiscussionController::class, 'show']);
$router->get('/login', [\App\Controllers\AuthController::class, 'login']);
$router->get('/register', [\App\Controllers\AuthController::class, 'register']);
$router->get('/admin', [\App\Controllers\AdminController::class, 'dashboard'], [AdminMiddleware::class]);

// Static Information & Legal Pages
$router->get('/about', [\App\Controllers\InfoController::class, 'about']);
$router->get('/privacy', [\App\Controllers\InfoController::class, 'privacy']);
$router->get('/terms', [\App\Controllers\InfoController::class, 'terms']);
$router->get('/cookies', [\App\Controllers\InfoController::class, 'cookies']);
$router->get('/methodology', [\App\Controllers\InfoController::class, 'methodology']);
$router->get('/faq', [\App\Controllers\InfoController::class, 'faq']);
$router->get('/contact', [\App\Controllers\InfoController::class, 'contact']);
$router->post('/contact', [\App\Controllers\InfoController::class, 'contact']);

// SEO Dynamic Sitemap & Robots.txt
$router->get('/sitemap.xml', [\App\Controllers\SitemapController::class, 'index']);
$router->get('/robots.txt', [\App\Controllers\SitemapController::class, 'robots']);

// --- API ROUTES ---
$router->get('/api/polls', [\App\Controllers\PollController::class, 'index']);
$router->get('/api/polls/{id}', [\App\Controllers\PollController::class, 'show']);
$router->get('/api/polls/{id}/history', [\App\Controllers\PollController::class, 'history']);
$router->post('/api/polls', [\App\Controllers\PollController::class, 'create'], [AuthMiddleware::class]);

$router->post('/api/vote', [\App\Controllers\VoteController::class, 'submit'], [RateLimitMiddleware::class]);
$router->get('/api/voted-status', [\App\Controllers\VoteController::class, 'status']);
$router->get('/api/results/{id}', [\App\Controllers\VoteController::class, 'results']);

$router->get('/api/discussions', [\App\Controllers\DiscussionController::class, 'index']);
$router->get('/api/discussions/{id}', [\App\Controllers\DiscussionController::class, 'show']);
$router->post('/api/discussions', [\App\Controllers\DiscussionController::class, 'create'], [AuthMiddleware::class]);
$router->post('/api/discussions/{id}/like', [\App\Controllers\DiscussionController::class, 'like']);
$router->post('/api/discussions/{id}/comments', [\App\Controllers\DiscussionController::class, 'addComment'], [AuthMiddleware::class]);

$router->post('/api/login', [\App\Controllers\AuthController::class, 'login']);
$router->post('/api/register', [\App\Controllers\AuthController::class, 'register']);
$router->post('/api/logout', [\App\Controllers\AuthController::class, 'logout']);
$router->get('/api/me', [\App\Controllers\AuthController::class, 'me']);

$router->get('/api/admin/dashboard', [\App\Controllers\AdminController::class, 'dashboard'], [AdminMiddleware::class]);
$router->post('/api/admin/polls/{id}', [\App\Controllers\AdminController::class, 'updatePoll'], [AdminMiddleware::class]);

// Dispatch Route
$router->dispatch($request);
