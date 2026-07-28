<?php
namespace App\Core;

class Router {
    private array $routes = [];

    public function add(string $method, string $path, $handler, array $middlewares = []): void {
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $this->convertPathToRegex($path),
            'originalPath' => $path,
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function get(string $path, $handler, array $middlewares = []): void {
        $this->add('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, $handler, array $middlewares = []): void {
        $this->add('POST', $path, $handler, $middlewares);
    }

    public function dispatch(Request $request): void {
        $method = $request->getMethod();
        $path = $request->getPath();

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $path, $matches)) {
                // Extract named URL parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Run route middleware stack
                foreach ($route['middlewares'] as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    $middleware->handle($request);
                }

                // Execute Handler
                $handler = $route['handler'];
                if (is_array($handler)) {
                    [$class, $action] = $handler;
                    $controller = new $class();
                    call_user_func_array([$controller, $action], [$request, $params]);
                } elseif (is_callable($handler)) {
                    call_user_func_array($handler, [$request, $params]);
                }
                return;
            }
        }

        Response::error('404 Page Not Found', 404);
    }

    private function convertPathToRegex(string $path): string {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $path);
        return '#^' . $pattern . '$#';
    }
}
