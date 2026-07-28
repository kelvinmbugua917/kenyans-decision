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

        // Fetch platform metrics
        $db = Database::getInstance();
        $totalVotes = (int)$db->query("SELECT COUNT(*) FROM votes WHERE risk_score != 'blocked'")->fetchColumn();
        $totalPolls = (int)$db->query("SELECT COUNT(*) FROM polls")->fetchColumn();
        $totalDiscussions = (int)$db->query("SELECT COUNT(*) FROM discussions")->fetchColumn();
        $totalUsers = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();

        if ($request->isAjax()) {
            Response::json([
                'featuredPoll' => $featuredPoll,
                'featuredResult' => $featuredResult,
                'trendingDiscussions' => array_slice($discussions, 0, 3),
                'analytics' => [
                    'totalVotes' => $totalVotes,
                    'totalPolls' => $totalPolls,
                    'totalDiscussions' => $totalDiscussions,
                    'totalRegisteredUsers' => $totalUsers
                ]
            ]);
        } else {
            Response::render('home/index', [
                'featuredPoll' => $featuredPoll,
                'featuredResult' => $featuredResult,
                'discussions' => array_slice($discussions, 0, 3),
                'analytics' => [
                    'totalVotes' => $totalVotes,
                    'totalPolls' => $totalPolls,
                    'totalDiscussions' => $totalDiscussions,
                    'totalRegisteredUsers' => $totalUsers
                ]
            ], 'Kenyans Decision 🇰🇪 - Public Opinion & Voting Dashboard');
        }
    }
}
