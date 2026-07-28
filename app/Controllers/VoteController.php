<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Vote;

class VoteController {
    public function submit(Request $request): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $pollId = $request->getParam('pollId');
        $optionId = $request->getParam('optionId');
        $county = $request->getParam('county', 'Nairobi');
        $ageGroup = $request->getParam('ageGroup', '25-34');
        $deviceToken = $request->getDeviceToken();
        $ip = $request->getClientIp();
        $user = $_SESSION['user'] ?? null;

        if (empty($pollId) || empty($optionId)) {
            Response::json(['error' => 'Missing required poll or option identifier.'], 400);
        }

        try {
            $result = Vote::submitVote([
                'pollId' => $pollId,
                'optionId' => $optionId,
                'ip' => $ip,
                'deviceToken' => $deviceToken,
                'county' => $county,
                'ageGroup' => $ageGroup,
                'userId' => $user['id'] ?? null
            ]);

            Response::json($result);
        } catch (\Exception $e) {
            Response::json(['error' => $e->getMessage()], 400);
        }
    }

    public function status(Request $request, array $params): void {
        $pollId = $params['pollId'] ?? $request->getParam('pollId');
        if (empty($pollId)) {
            Response::json(['error' => 'Poll ID required'], 400);
        }

        $ip = $request->getClientIp();
        $deviceToken = $request->getDeviceToken();

        $status = Vote::hasVoted($pollId, $ip, $deviceToken);
        Response::json($status);
    }

    public function results(Request $request, array $params): void {
        $pollId = $params['pollId'] ?? $request->getParam('pollId');
        if (empty($pollId)) {
            Response::json(['error' => 'Poll ID required'], 400);
        }

        try {
            $results = Vote::getPollResults($pollId);
            Response::json($results);
        } catch (\Exception $e) {
            Response::json(['error' => $e->getMessage()], 404);
        }
    }
}
