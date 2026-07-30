<?php
namespace App\Middleware;

use App\Core\Request;

class SecurityMiddleware {
    public function handle(Request $request): void {
        // Enforce Strict Security Headers
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Content Security Policy (allows Tailwind CDN & Google fonts/icons safely)
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com; " .
               "style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://fonts.googleapis.com; " .
               "font-src 'self' https://fonts.gstatic.com data:; " .
               "img-src 'self' data: https:; " .
               "connect-src 'self' https:; " .
               "frame-ancestors 'self' http://localhost:* https://*.run.app https://*.studio.google; " .
               "object-src 'none';";
        header("Content-Security-Policy: {$csp}");

        // Strict-Transport-Security for HTTPS connections
        if ($request->isHttps()) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        }
    }
}
