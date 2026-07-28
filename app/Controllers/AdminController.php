<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\AuditLog;
use App\Models\Poll;
use App\Models\Report;
use App\Core\Database;

class AdminController {
    public function dashboard(Request $request): void {
        $db = Database::getInstance();

        $auditLogs = AuditLog::getAll();
        $reports = Report::getAll();
        $polls = Poll::getAll();

        $stats = [
            'totalVotes' => (int)$db->query("SELECT COUNT(*) FROM votes WHERE risk_score != 'blocked'")->fetchColumn(),
            'totalPolls' => (int)$db->query("SELECT COUNT(*) FROM polls")->fetchColumn(),
            'totalDiscussions' => (int)$db->query("SELECT COUNT(*) FROM discussions")->fetchColumn(),
            'pendingReports' => (int)$db->query("SELECT COUNT(*) FROM reports WHERE status = 'pending'")->fetchColumn(),
            'suspiciousVotes' => (int)$db->query("SELECT COUNT(*) FROM votes WHERE risk_score = 'suspicious'")->fetchColumn(),
        ];

        if ($request->isAjax()) {
            Response::json([
                'stats' => $stats,
                'auditLogs' => $auditLogs,
                'reports' => $reports,
                'polls' => $polls
            ]);
        } else {
            Response::render('admin/dashboard', [
                'stats' => $stats,
                'auditLogs' => $auditLogs,
                'reports' => $reports,
                'polls' => $polls
            ], 'Admin Audit & Moderation Portal - Kenyans Decision');
        }
    }

    public function updatePoll(Request $request, array $params): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $adminUser = $_SESSION['user'] ?? null;
        if (!$adminUser || ($adminUser['role'] ?? '') !== 'admin') {
            Response::json(['error' => 'Admin required'], 403);
        }

        $pollId = $params['id'] ?? $request->getParam('pollId');
        $action = $request->getParam('action'); // 'feature', 'close', 'reopen'

        $db = Database::getInstance();

        if ($action === 'feature') {
            $db->exec("UPDATE polls SET is_featured = 0");
            $db->prepare("UPDATE polls SET is_featured = 1 WHERE id = :id")->execute(['id' => $pollId]);
            AuditLog::log($adminUser['email'], 'FEATURE_POLL', $pollId);
        } elseif ($action === 'close') {
            $db->prepare("UPDATE polls SET status = 'closed' WHERE id = :id")->execute(['id' => $pollId]);
            AuditLog::log($adminUser['email'], 'CLOSE_POLL', $pollId);
        } elseif ($action === 'reopen') {
            $db->prepare("UPDATE polls SET status = 'active' WHERE id = :id")->execute(['id' => $pollId]);
            AuditLog::log($adminUser['email'], 'REOPEN_POLL', $pollId);
        }

        Response::json(['success' => true]);
    }
}
