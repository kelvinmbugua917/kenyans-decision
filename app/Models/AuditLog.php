<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class AuditLog {
    public static function log(string $adminEmail, string $action, string $target, ?string $beforeState = null, ?string $afterState = null): void {
        $db = Database::getInstance();
        $id = 'log_' . bin2hex(random_bytes(8));

        // Get last log entry for hash chaining
        $lastStmt = $db->query("SELECT log_hash FROM admin_audit_logs ORDER BY timestamp DESC, id DESC LIMIT 1");
        $prevHash = $lastStmt->fetchColumn() ?: 'genesis_hash_2027';

        $timestamp = date('Y-m-d H:i:s');
        $rawString = "{$id}|{$adminEmail}|{$action}|{$target}|{$prevHash}|{$timestamp}";
        $logHash = hash('sha256', $rawString);

        $stmt = $db->prepare("
            INSERT INTO admin_audit_logs (id, admin_email, action, target, before_state, after_state, prev_hash, log_hash, timestamp)
            VALUES (:id, :admin_email, :action, :target, :before_state, :after_state, :prev_hash, :log_hash, :timestamp)
        ");

        $stmt->execute([
            'id' => $id,
            'admin_email' => $adminEmail,
            'action' => $action,
            'target' => $target,
            'before_state' => $beforeState,
            'after_state' => $afterState,
            'prev_hash' => $prevHash,
            'log_hash' => $logHash,
            'timestamp' => $timestamp
        ]);
    }

    public static function getAll(): array {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM admin_audit_logs ORDER BY timestamp DESC");
        return $stmt->fetchAll();
    }
}
