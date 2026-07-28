<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Poll {
    public static function getAll(?string $category = null, ?string $creatorType = null): array {
        $db = Database::getInstance();
        $sql = "SELECT p.*, COUNT(v.id) AS total_votes 
                FROM polls p 
                LEFT JOIN votes v ON p.id = v.poll_id AND v.risk_score != 'blocked'
                WHERE 1=1";
        $params = [];

        if ($category && $category !== 'All') {
            $sql .= " AND p.category = :category";
            $params['category'] = $category;
        }

        if ($creatorType && $creatorType !== 'all') {
            $sql .= " AND p.creator_type = :creator_type";
            $params['creator_type'] = $creatorType;
        }

        $sql .= " GROUP BY p.id ORDER BY p.created_at DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $polls = $stmt->fetchAll();

        foreach ($polls as &$poll) {
            $poll['options'] = self::getOptions($poll['id']);
            $poll['allowVoteChange'] = (bool)$poll['allow_vote_change'];
            $poll['isFeatured'] = (bool)$poll['is_featured'];
            $poll['totalVotes'] = (int)$poll['total_votes'];
        }

        return $polls;
    }

    public static function findByIdOrSlug(string $identifier): ?array {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT p.*, COUNT(v.id) AS total_votes 
            FROM polls p 
            LEFT JOIN votes v ON p.id = v.poll_id AND v.risk_score != 'blocked'
            WHERE p.id = :id OR p.slug = :slug
            GROUP BY p.id LIMIT 1
        ");
        $stmt->execute(['id' => $identifier, 'slug' => $identifier]);
        $poll = $stmt->fetch();

        if (!$poll) return null;

        $poll['options'] = self::getOptions($poll['id']);
        $poll['allowVoteChange'] = (bool)$poll['allow_vote_change'];
        $poll['isFeatured'] = (bool)$poll['is_featured'];
        $poll['totalVotes'] = (int)$poll['total_votes'];

        return $poll;
    }

    public static function getFeatured(): ?array {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT id FROM polls WHERE is_featured = 1 AND status = 'active' LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row) {
            return self::findByIdOrSlug($row['id']);
        }

        // Fallback to latest active official poll
        $stmt = $db->prepare("SELECT id FROM polls WHERE status = 'active' ORDER BY created_at DESC LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch();

        return $row ? self::findByIdOrSlug($row['id']) : null;
    }

    public static function getOptions(string $pollId): array {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT id, poll_id, name, party, party_short AS partyShort, avatar_color AS avatarColor, photo_url AS photoUrl 
            FROM poll_options 
            WHERE poll_id = :poll_id 
            ORDER BY sort_order ASC
        ");
        $stmt->execute(['poll_id' => $pollId]);
        return $stmt->fetchAll();
    }

    public static function create(array $data, ?array $user = null): array {
        $db = Database::getInstance();
        $pollId = $data['slug'] ?? ('poll_' . bin2hex(random_bytes(8)));
        $slug = $pollId;

        $db->beginTransaction();

        try {
            $creatorType = ($user && $user['role'] === 'admin') ? 'official' : 'community';
            $creatorName = $user ? $user['display_name'] : 'Anonymous Community Member';
            $isFeatured = ($user && $user['role'] === 'admin' && !empty($data['isFeatured'])) ? 1 : 0;

            if ($isFeatured) {
                $db->exec("UPDATE polls SET is_featured = 0");
            }

            $stmt = $db->prepare("
                INSERT INTO polls (id, slug, title, description, category, creator_type, creator_name, creator_id, allow_vote_change, closing_date, status, is_featured, created_at, updated_at)
                VALUES (:id, :slug, :title, :description, :category, :creator_type, :creator_name, :creator_id, :allow_vote_change, :closing_date, 'active', :is_featured, NOW(), NOW())
            ");

            $stmt->execute([
                'id' => $pollId,
                'slug' => $slug,
                'title' => trim($data['title']),
                'description' => trim($data['description'] ?? ''),
                'category' => $data['category'] ?? 'General Kenya',
                'creator_type' => $creatorType,
                'creator_name' => $creatorName,
                'creator_id' => $user['id'] ?? null,
                'allow_vote_change' => isset($data['allowVoteChange']) ? (int)$data['allowVoteChange'] : 1,
                'closing_date' => !empty($data['closingDate']) ? $data['closingDate'] : null,
                'is_featured' => $isFeatured
            ]);

            // Options insert
            if (!empty($data['options']) && is_array($data['options'])) {
                $optStmt = $db->prepare("
                    INSERT INTO poll_options (id, poll_id, name, party, party_short, avatar_color, sort_order)
                    VALUES (:id, :poll_id, :name, :party, :party_short, :avatar_color, :sort_order)
                ");

                foreach ($data['options'] as $idx => $opt) {
                    $optId = $opt['id'] ?? ('opt_' . bin2hex(random_bytes(6)));
                    $optStmt->execute([
                        'id' => $optId,
                        'poll_id' => $pollId,
                        'name' => trim($opt['name']),
                        'party' => trim($opt['party'] ?? 'Community Option'),
                        'party_short' => trim($opt['partyShort'] ?? ''),
                        'avatar_color' => $opt['avatarColor'] ?? '#16a34a',
                        'sort_order' => $idx + 1
                    ]);
                }
            }

            $db->commit();
            return self::findByIdOrSlug($pollId);
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
