<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Report {
    public static function create(string $targetType, string $targetId, string $reason, ?string $reporterId = null, ?string $details = null): array {
        $db = Database::getInstance();
        $id = 'rep_' . bin2hex(random_bytes(8));

        $stmt = $db->prepare("
            INSERT INTO reports (id, target_type, target_id, reason, reporter_id, status, details, created_at)
            VALUES (:id, :target_type, :target_id, :reason, :reporter_id, 'pending', :details, NOW())
        ");

        $stmt->execute([
            'id' => $id,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'reason' => trim($reason),
            'reporter_id' => $reporterId,
            'details' => $details
        ]);

        return [
            'id' => $id,
            'targetType' => $targetType,
            'targetId' => $targetId,
            'reason' => $reason,
            'status' => 'pending',
            'createdAt' => date('Y-m-d H:i:s')
        ];
    }

    public static function getAll(): array {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM reports ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public static function updateStatus(string $id, string $status): void {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE reports SET status = :status WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $id]);
    }
}
