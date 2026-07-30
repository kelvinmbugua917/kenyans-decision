<?php
namespace App\Controllers;

use App\Core\Database;
use PDO;

class SitemapController {

    public function index(): void {
        header('Content-Type: application/xml; charset=utf-8');

        $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'kenyansdecision.online';
        $baseUrl = $scheme . '://' . $host;

        $polls = [];
        $discussions = [];
        try {
            $db = Database::getInstance();
            $polls = $db->query("SELECT id, slug, updated_at, created_at FROM polls ORDER BY created_at DESC")->fetchAll() ?: [];
            $discussions = $db->query("SELECT id, updated_at, created_at FROM discussions ORDER BY created_at DESC")->fetchAll() ?: [];
        } catch (\Throwable $e) {
            // Soft fallback to static routes if DB connection is unavailable
        }

        $staticPages = [
            '/' => ['priority' => '1.0', 'changefreq' => 'hourly'],
            '/polls' => ['priority' => '0.9', 'changefreq' => 'hourly'],
            '/discussions' => ['priority' => '0.9', 'changefreq' => 'hourly'],
            '/about' => ['priority' => '0.8', 'changefreq' => 'monthly'],
            '/methodology' => ['priority' => '0.8', 'changefreq' => 'monthly'],
            '/privacy' => ['priority' => '0.7', 'changefreq' => 'monthly'],
            '/terms' => ['priority' => '0.7', 'changefreq' => 'monthly'],
            '/cookies' => ['priority' => '0.6', 'changefreq' => 'monthly'],
            '/contact' => ['priority' => '0.8', 'changefreq' => 'monthly'],
            '/faq' => ['priority' => '0.8', 'changefreq' => 'weekly'],
            '/register' => ['priority' => '0.5', 'changefreq' => 'monthly'],
            '/login' => ['priority' => '0.5', 'changefreq' => 'monthly'],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $xml .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $xml .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";

        // Static Routes
        foreach ($staticPages as $path => $meta) {
            $loc = htmlspecialchars($baseUrl . $path);
            $lastmod = date('c');
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$loc}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>{$meta['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$meta['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }

        // Polls Routes & History Routes
        foreach ($polls as $poll) {
            $pollPath = '/polls/' . urlencode((string)$poll['id']);
            $pollUrl = htmlspecialchars($baseUrl . $pollPath);
            $pollHistUrl = htmlspecialchars($baseUrl . $pollPath . '/history');

            $date = !empty($poll['updated_at']) ? strtotime($poll['updated_at']) : strtotime($poll['created_at']);
            $lastmod = date('c', $date ?: time());

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$pollUrl}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>hourly</changefreq>\n";
            $xml .= "    <priority>0.95</priority>\n";
            $xml .= "  </url>\n";

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$pollHistUrl}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>daily</changefreq>\n";
            $xml .= "    <priority>0.85</priority>\n";
            $xml .= "  </url>\n";
        }

        // Discussions Routes
        foreach ($discussions as $disc) {
            $discPath = '/discussions/' . urlencode((string)$disc['id']);
            $discUrl = htmlspecialchars($baseUrl . $discPath);

            $date = !empty($disc['updated_at']) ? strtotime($disc['updated_at']) : strtotime($disc['created_at']);
            $lastmod = date('c', $date ?: time());

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$discUrl}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>hourly</changefreq>\n";
            $xml .= "    <priority>0.85</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        echo $xml;
        exit;
    }

    public function robots(): void {
        header('Content-Type: text/plain; charset=utf-8');

        $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'kenyansdecision.online';
        $baseUrl = $scheme . '://' . $host;

        $txt = "User-agent: *\n";
        $txt .= "Allow: /\n";
        $txt .= "Disallow: /admin\n";
        $txt .= "Disallow: /api/admin\n\n";
        $txt .= "Sitemap: {$baseUrl}/sitemap.xml\n";

        echo $txt;
        exit;
    }
}
