<?php
namespace App\Core;

class Request {
    private array $get;
    private array $post;
    private array $server;
    private array $jsonBody;

    public function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;

        $rawInput = file_get_contents('php://input');
        if (!empty($rawInput)) {
            $decoded = json_decode($rawInput, true);
            $this->jsonBody = is_array($decoded) ? $decoded : [];
        } else {
            $this->jsonBody = [];
        }
    }

    public function getMethod(): string {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function getPath(): string {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH);
        return rtrim($path, '/') ?: '/';
    }

    public function getParam(string $key, $default = null) {
        if (array_key_exists($key, $this->jsonBody)) {
            return $this->jsonBody[$key];
        }
        if (array_key_exists($key, $this->post)) {
            return $this->post[$key];
        }
        if (array_key_exists($key, $this->get)) {
            return $this->get[$key];
        }
        return $default;
    }

    public function all(): array {
        return array_merge($this->get, $this->post, $this->jsonBody);
    }

    public function getClientIp(): string {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ];

        foreach ($headers as $header) {
            if (!empty($this->server[$header])) {
                $ips = explode(',', $this->server[$header]);
                $ip = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return $this->server['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    public function getUserAgent(): string {
        return $this->server['HTTP_USER_AGENT'] ?? 'Unknown Agent';
    }

    public function getDeviceToken(): string {
        // Look for custom header or cookie or generate one
        if (!empty($this->server['HTTP_X_DEVICE_TOKEN'])) {
            return trim($this->server['HTTP_X_DEVICE_TOKEN']);
        }
        if (!empty($_COOKIE['kd_voter_token'])) {
            return trim($_COOKIE['kd_voter_token']);
        }
        return $this->getParam('fingerprint', 'fp_default_token');
    }

    public function getBearerToken(): ?string {
        $authHeader = $this->server['HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/Bearer\s+(\S+)/i', $authHeader, $matches)) {
            return $matches[1];
        }
        return $_SESSION['auth_token'] ?? null;
    }

    public function isAjax(): bool {
        return isset($this->server['HTTP_X_REQUESTED_WITH']) &&
               strtolower($this->server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ||
               str_contains($this->server['HTTP_ACCEPT'] ?? '', 'application/json');
    }
}
