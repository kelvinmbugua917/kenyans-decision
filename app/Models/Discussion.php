<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Discussion {
    public static function getAll(?string $category = null): array {
        $db = Database::getInstance();
        $sql = "SELECT d.*, 
                   d.likes_count AS likesCount, 
                   d.comments_count AS commentsCount, 
                   d.author_name AS authorName, 
                   d.author_id AS authorId, 
                   d.created_at AS createdAt 
                FROM discussions d WHERE 1=1";
        $params = [];

        if ($category && $category !== 'All') {
            $sql .= " AND d.category = :category";
            $params['category'] = $category;
        }

        $sql .= " ORDER BY d.created_at DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function findById(string $id): ?array {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT d.*, 
                   d.likes_count AS likesCount, 
                   d.comments_count AS commentsCount, 
                   d.author_name AS authorName, 
                   d.author_id AS authorId, 
                   d.created_at AS createdAt 
            FROM discussions d 
            WHERE d.id = :id LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $disc = $stmt->fetch();
        return $disc ?: null;
    }

    public static function create(string $title, string $content, string $category, array $user): array {
        $db = Database::getInstance();
        $id = 'disc_' . bin2hex(random_bytes(8));
        $authorName = $user['display_name'] . ' (' . ($user['county'] ?? 'Kenya') . ')';

        $stmt = $db->prepare("
            INSERT INTO discussions (id, title, content, category, author_id, author_name, likes_count, comments_count, created_at)
            VALUES (:id, :title, :content, :category, :author_id, :author_name, 0, 0, NOW())
        ");

        $stmt->execute([
            'id' => $id,
            'title' => trim($title),
            'content' => trim($content),
            'category' => $category,
            'author_id' => $user['id'],
            'author_name' => $authorName
        ]);

        return self::findById($id);
    }

    public static function like(string $id): int {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE discussions SET likes_count = likes_count + 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $check = $db->prepare("SELECT likes_count FROM discussions WHERE id = :id");
        $check->execute(['id' => $id]);
        return (int)$check->fetchColumn();
    }

    public static function delete(string $id): void {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM discussions WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
