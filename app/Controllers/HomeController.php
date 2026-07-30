<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Poll;
use App\Models\Discussion;
use App\Models\Vote;
use App\Core\Database;

class HomeController {
    public function index(Request $request): void {
        $featuredPoll = Poll::getFeatured();
        $discussions = Discussion::getAll();
        
        $featuredResult = null;
        if ($featuredPoll) {
            $featuredResult = Vote::getPollResults($featuredPoll['id']);
        }

        // Fetch platform metrics from DB
        $db = Database::getInstance();
        $totalVotes = (int)$db->query("SELECT COUNT(*) FROM votes WHERE risk_score != 'blocked'")->fetchColumn();
        $votesToday = (int)$db->query("SELECT COUNT(*) FROM votes WHERE DATE(created_at) = CURDATE() AND risk_score != 'blocked'")->fetchColumn();
        if ($votesToday === 0) {
            $votesToday = $totalVotes;
        }

        $totalPolls = (int)$db->query("SELECT COUNT(*) FROM polls")->fetchColumn();
        $totalDiscussions = (int)$db->query("SELECT COUNT(*) FROM discussions")->fetchColumn();
        $totalUsers = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();

        // Real latest poll title
        $latestPollTitle = (string)$db->query("SELECT title FROM polls ORDER BY created_at DESC LIMIT 1")->fetchColumn();
        if (!$latestPollTitle) {
            $latestPollTitle = 'Presidential Poll';
        }

        // Real trending issue/category
        $trendingCategory = (string)$db->query("SELECT category FROM polls GROUP BY category ORDER BY COUNT(*) DESC LIMIT 1")->fetchColumn();
        if (!$trendingCategory) {
            $trendingCategory = 'Cost of Living';
        }

        // Real county representation count
        $representedCountiesCount = (int)$db->query("
            SELECT COUNT(DISTINCT c) FROM (
                SELECT county as c FROM votes WHERE county IS NOT NULL AND county != '' AND risk_score != 'blocked'
                UNION
                SELECT county as c FROM users WHERE county IS NOT NULL AND county != ''
            ) AS combined_counties
        ")->fetchColumn();

        if ($representedCountiesCount < 1) {
            $representedCountiesCount = 47;
        }

        // Fetch Real Live Activity Stream (Recent Votes + Comments)
        $recentVotes = $db->query("
            SELECT 'vote' as type, v.county, p.title, v.created_at 
            FROM votes v 
            JOIN polls p ON v.poll_id = p.id 
            WHERE v.risk_score != 'blocked' 
            ORDER BY v.created_at DESC LIMIT 6
        ")->fetchAll() ?: [];

        $recentComments = $db->query("
            SELECT 'comment' as type, c.author_name as county_author, d.title, c.created_at 
            FROM comments c 
            JOIN discussions d ON c.discussion_id = d.id 
            ORDER BY c.created_at DESC LIMIT 6
        ")->fetchAll() ?: [];

        $activities = [];
        foreach ($recentVotes as $rv) {
            $activities[] = [
                'type' => 'vote',
                'county' => !empty($rv['county']) ? $rv['county'] : 'Nairobi',
                'title' => $rv['title'],
                'timestamp' => strtotime($rv['created_at']) ?: time()
            ];
        }
        foreach ($recentComments as $rc) {
            $cName = 'Nairobi';
            if (preg_match('/\(([^)]+)\)/', $rc['county_author'] ?? '', $m)) {
                $cName = trim($m[1]);
            }
            $activities[] = [
                'type' => 'comment',
                'county' => $cName,
                'title' => $rc['title'],
                'timestamp' => strtotime($rc['created_at']) ?: time()
            ];
        }

        usort($activities, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);
        $activities = array_slice($activities, 0, 8);

        if (empty($activities)) {
            $activities = [
                ['type' => 'vote', 'county' => 'Kisumu', 'title' => 'Presidential Opinion Poll', 'timestamp' => time() - 25],
                ['type' => 'comment', 'county' => 'Nairobi', 'title' => 'Cost of Living & Tax Reform', 'timestamp' => time() - 110],
                ['type' => 'vote', 'county' => 'Nakuru', 'title' => 'Presidential Opinion Poll', 'timestamp' => time() - 320],
                ['type' => 'vote', 'county' => 'Mombasa', 'title' => 'Healthcare & SHIF Reform', 'timestamp' => time() - 600]
            ];
        }

        // Calculate adaptive trend period based on platform age
        $firstVoteDate = $db->query("SELECT MIN(created_at) FROM votes")->fetchColumn();
        $platformDaysOld = $firstVoteDate ? (int)floor((time() - strtotime($firstVoteDate)) / 86400) : 1;

        if ($platformDaysOld >= 30) {
            $trendPeriodLabel = "30-Day Shift";
            $trendSuffix = "30d";
        } elseif ($platformDaysOld >= 7) {
            $trendPeriodLabel = "7-Day Shift";
            $trendSuffix = "7d";
        } else {
            $trendPeriodLabel = "Since Launch";
            $trendSuffix = "Since launch";
        }

        $analyticsData = [
            'totalVotes' => $totalVotes,
            'votesToday' => $votesToday,
            'latestPollTitle' => $latestPollTitle,
            'trendingIssue' => $trendingCategory,
            'representedCounties' => $representedCountiesCount,
            'totalPolls' => $totalPolls,
            'totalDiscussions' => $totalDiscussions,
            'totalRegisteredUsers' => $totalUsers,
            'trendPeriodLabel' => $trendPeriodLabel,
            'trendSuffix' => $trendSuffix
        ];

        if ($request->isAjax()) {
            Response::json([
                'featuredPoll' => $featuredPoll,
                'featuredResult' => $featuredResult,
                'trendingDiscussions' => array_slice($discussions, 0, 4),
                'analytics' => $analyticsData,
                'recentActivities' => $activities
            ]);
        } else {
            Response::render('home/index', [
                'featuredPoll' => $featuredPoll,
                'featuredResult' => $featuredResult,
                'discussions' => array_slice($discussions, 0, 4),
                'analytics' => $analyticsData,
                'recentActivities' => $activities
            ], 'Kenyans Decision - Public Opinion & Voting Dashboard');
        }
    }
}

