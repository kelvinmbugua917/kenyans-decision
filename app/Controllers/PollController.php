<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Poll;
use App\Models\Vote;

class PollController {
    public function index(Request $request): void {
        $category = $request->getParam('category');
        $creatorType = $request->getParam('creatorType');
        $polls = Poll::getAll($category, $creatorType);

        if ($request->isAjax()) {
            Response::json($polls);
        } else {
            Response::render('polls/index', ['polls' => $polls, 'currentCategory' => $category ?? 'All'], 'Opinion Polls - Kenyans Decision');
        }
    }

    public function show(Request $request, array $params): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user = $_SESSION['user'] ?? null;
        $id = $params['id'] ?? $request->getParam('id');
        $poll = Poll::findByIdOrSlug($id);

        if (!$poll) {
            Response::error('Poll not found', 404);
        }

        $results = Vote::getPollResults($poll['id']);
        $votedStatus = Vote::hasVoted($poll['id'], $request->getClientIp(), $request->getDeviceToken(), $user['id'] ?? null);

        if ($request->isAjax()) {
            Response::json([
                'poll' => $poll,
                'results' => $results,
                'hasVoted' => $votedStatus['hasVoted'],
                'selectedOptionId' => $votedStatus['selectedOptionId']
            ]);
        } else {
            Response::render('polls/show', [
                'poll' => $poll,
                'results' => $results,
                'hasVoted' => $votedStatus['hasVoted'],
                'selectedOptionId' => $votedStatus['selectedOptionId']
            ], $poll['title'] . ' - Kenyans Decision');
        }
    }

    public function create(Request $request): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            Response::json(['error' => 'You must be signed in to launch a public opinion poll.'], 401);
        }

        $title = $request->getParam('title');
        $description = $request->getParam('description');
        $category = $request->getParam('category', 'General Kenya');
        $allowVoteChange = $request->getParam('allowVoteChange', true);
        $options = $request->getParam('options', []);

        if (empty($title) || count($options) < 2) {
            Response::json(['error' => 'A poll title and at least 2 voting options are required.'], 400);
        }

        try {
            $poll = Poll::create([
                'title' => $title,
                'description' => $description,
                'category' => $category,
                'allowVoteChange' => $allowVoteChange,
                'options' => $options
            ], $user);

            Response::json(['success' => true, 'poll' => $poll]);
        } catch (\Exception $e) {
            Response::json(['error' => $e->getMessage()], 500);
        }
    }
}
