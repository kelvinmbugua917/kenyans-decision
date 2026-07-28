<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Comment {
    public static function getByDiscussionId(string $discussionId): array {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT c.*, 
                   c.author_name AS authorName, 
                   c.author_id AS authorId, 
                   c.discussion_id AS discussionId, 
                   c.created_at AS createdAt 
            FROM comments c 
            WHERE c.discussion_id = :discussion_id 
            ORDER BY c.created_at ASC
        ");
        $stmt->execute(['discussion_id' => $discussionId]);
        return $stmt->fetchAll();
    }

    public static function create(string $discussionId, string $content, array $user): array {
        $db = Database::getInstance();
        $id = 'cmnt_' . bin2hex(random_bytes(8));
        $authorName = $user['display_name'] . ' (' . ($user['county'] ?? 'Kenya') . ')';

        $db->beginTransaction();

        try {
            $stmt = $db->prepare("
                INSERT INTO comments (id, discussion_id, author_id, author_name, content, created_at)
                VALUES (:id, :discussion_id, :author_id, :author_name, :content, NOW())
            ");
            $stmt->execute([
                'id' => $id,
                'discussion_id' => $discussionId,
                'author_id' => $user['id'],
                'author_name' => $authorName,
                'content' => trim($content)
            ]);

            $upStmt = $db->prepare("UPDATE discussions SET comments_count = comments_count + 1 WHERE id = :id");
            $upStmt->execute(['id' => $discussionId]);

            $db->commit();

            return [
                'id' => $id,
                'discussionId' => $discussionId,
                'authorId' => $user['id'],
                'authorName' => $authorName,
                'content' => trim($content),
                'createdAt' => date('Y-m-d H:i:s')
            ];
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function delete(string $id): void {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT discussion_id FROM comments WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $discId = $stmt->fetchColumn();

        if ($discId) {
            $db->prepare("DELETE FROM comments WHERE id = :id")->execute(['id' => $id]);
            $db->prepare("UPDATE discussions SET comments_count = GREATEST(0, comments_count - 1) WHERE id = :id")->execute(['id' => $discId]);
        }
    }
}
