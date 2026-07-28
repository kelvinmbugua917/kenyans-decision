<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Vote {
    public static function submitVote(array $params): array {
        $db = Database::getInstance();
        $config = require __DIR__ . '/../../config/config.php';
        $hmacKey = $config['security']['vote_hmac_key'] ?? 'kd_default_hmac_key';

        $pollId = $params['pollId'];
        $optionId = $params['optionId'];
        $ip = $params['ip'];
        $deviceToken = $params['deviceToken'];
        $county = $params['county'] ?? 'Nairobi';
        $ageGroup = $params['ageGroup'] ?? '25-34';
        $userId = $params['userId'] ?? null;

        $poll = Poll::findByIdOrSlug($pollId);
        if (!$poll) {
            throw new \Exception('Poll not found.');
        }
        if ($poll['status'] === 'closed') {
            throw new \Exception('This poll is closed.');
        }

        // Keyed HMAC IP Digest Generation (mitigating raw IP exposure and brute force)
        $ipHmac = hash_hmac('sha256', $ip, $hmacKey);
        // Irreversible composite voter hash
        $voterHash = hash_hmac('sha256', $ipHmac . '_' . $deviceToken . '_' . $poll['id'], $hmacKey);

        $db->beginTransaction();

        try {
            // Check rate limiting & risk scoring
            $recentStmt = $db->prepare("
                SELECT COUNT(*) FROM votes 
                WHERE ip_hmac = :ip_hmac AND created_at > DATE_SUB(NOW(), INTERVAL 1 MINUTE)
            ");
            $recentStmt->execute(['ip_hmac' => $ipHmac]);
            $recentCount = (int)$recentStmt->fetchColumn();

            $riskScore = 'normal';
            if ($recentCount > 8) {
                $riskScore = 'suspicious';
            } elseif ($recentCount > 25) {
                $riskScore = 'blocked';
            } elseif ($userId || strlen($deviceToken) > 10) {
                $riskScore = 'trusted';
            }

            // Check if existing vote exists for this poll + voterHash
            $checkStmt = $db->prepare("
                SELECT id, option_id, risk_score FROM votes 
                WHERE poll_id = :poll_id AND voter_hash = :voter_hash LIMIT 1
            ");
            $checkStmt->execute(['poll_id' => $poll['id'], 'voter_hash' => $voterHash]);
            $existingVote = $checkStmt->fetch();

            if ($existingVote) {
                if (!$poll['allowVoteChange']) {
                    $db->commit();
                    return [
                        'success' => true,
                        'message' => 'You have already voted in this poll.',
                        'riskScore' => $existingVote['risk_score'],
                        'selectedOptionId' => $existingVote['option_id'],
                        'results' => self::getPollResults($poll['id'])
                    ];
                } else {
                    // Update vote choice atomically
                    $updateStmt = $db->prepare("
                        UPDATE votes 
                        SET option_id = :option_id, county = :county, age_group = :age_group, created_at = NOW()
                        WHERE id = :id
                    ");
                    $updateStmt->execute([
                        'option_id' => $optionId,
                        'county' => $county,
                        'age_group' => $ageGroup,
                        'id' => $existingVote['id']
                    ]);

                    $db->commit();
                    return [
                        'success' => true,
                        'message' => 'Your vote choice has been updated.',
                        'riskScore' => $existingVote['risk_score'],
                        'selectedOptionId' => $optionId,
                        'results' => self::getPollResults($poll['id'])
                    ];
                }
            }

            // Insert new vote
            $voteId = 'v_' . bin2hex(random_bytes(8));
            $insertStmt = $db->prepare("
                INSERT INTO votes (id, poll_id, option_id, voter_hash, ip_hmac, device_token, user_id, county, age_group, risk_score, created_at)
                VALUES (:id, :poll_id, :option_id, :voter_hash, :ip_hmac, :device_token, :user_id, :county, :age_group, :risk_score, NOW())
            ");

            $insertStmt->execute([
                'id' => $voteId,
                'poll_id' => $poll['id'],
                'option_id' => $optionId,
                'voter_hash' => $voterHash,
                'ip_hmac' => $ipHmac,
                'device_token' => $deviceToken,
                'user_id' => $userId,
                'county' => $county,
                'age_group' => $ageGroup,
                'risk_score' => $riskScore
            ]);

            $db->commit();

            return [
                'success' => true,
                'message' => 'Your vote has been counted.',
                'riskScore' => $riskScore,
                'selectedOptionId' => $optionId,
                'results' => self::getPollResults($poll['id'])
            ];
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function hasVoted(string $pollId, string $ip, string $deviceToken): array {
        $db = Database::getInstance();
        $config = require __DIR__ . '/../../config/config.php';
        $hmacKey = $config['security']['vote_hmac_key'] ?? 'kd_default_hmac_key';

        $ipHmac = hash_hmac('sha256', $ip, $hmacKey);
        $voterHash = hash_hmac('sha256', $ipHmac . '_' . $deviceToken . '_' . $pollId, $hmacKey);

        $stmt = $db->prepare("
            SELECT option_id FROM votes 
            WHERE poll_id = :poll_id AND voter_hash = :voter_hash AND risk_score != 'blocked' 
            LIMIT 1
        ");
        $stmt->execute(['poll_id' => $pollId, 'voter_hash' => $voterHash]);
        $row = $stmt->fetch();

        return [
            'hasVoted' => (bool)$row,
            'selectedOptionId' => $row['option_id'] ?? null
        ];
    }

    public static function getPollResults(string $pollId): array {
        $db = Database::getInstance();
        $poll = Poll::findByIdOrSlug($pollId);
        if (!$poll) {
            throw new \Exception('Poll not found');
        }

        $options = Poll::getOptions($poll['id']);

        // Fetch overall vote counts excluding blocked votes
        $stmt = $db->prepare("
            SELECT option_id, COUNT(*) as vote_count 
            FROM votes 
            WHERE poll_id = :poll_id AND risk_score != 'blocked'
            GROUP BY option_id
        ");
        $stmt->execute(['poll_id' => $poll['id']]);
        $counts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $totalVotes = array_sum($counts);

        $optionResults = [];
        foreach ($options as $opt) {
            $votes = (int)($counts[$opt['id']] ?? 0);
            $percentage = $totalVotes > 0 ? round(($votes / $totalVotes) * 100, 1) : 0;

            $optionResults[] = [
                'optionId' => $opt['id'],
                'name' => $opt['name'],
                'party' => $opt['party'],
                'avatarColor' => $opt['avatarColor'],
                'votes' => $votes,
                'percentage' => $percentage
            ];
        }

        // Sort options by votes descending
        usort($optionResults, fn($a, $b) => $b['votes'] <=> $a['votes']);

        // County Breakdown Matrix
        $countyStmt = $db->prepare("
            SELECT county, option_id, COUNT(*) as vote_count 
            FROM votes 
            WHERE poll_id = :poll_id AND risk_score != 'blocked' AND county IS NOT NULL
            GROUP BY county, option_id
        ");
        $countyStmt->execute(['poll_id' => $poll['id']]);
        $countyRows = $countyStmt->fetchAll();

        $countyBreakdown = [];
        foreach ($countyRows as $row) {
            $c = $row['county'];
            $o = $row['option_id'];
            $cnt = (int)$row['vote_count'];
            if (!isset($countyBreakdown[$c])) {
                $countyBreakdown[$c] = [];
            }
            $countyBreakdown[$c][$o] = $cnt;
        }

        // Age Group Breakdown
        $ageStmt = $db->prepare("
            SELECT age_group, option_id, COUNT(*) as vote_count 
            FROM votes 
            WHERE poll_id = :poll_id AND risk_score != 'blocked' AND age_group IS NOT NULL
            GROUP BY age_group, option_id
        ");
        $ageStmt->execute(['poll_id' => $poll['id']]);
        $ageRows = $ageStmt->fetchAll();

        $ageBreakdown = [];
        foreach ($ageRows as $row) {
            $a = $row['age_group'];
            $o = $row['option_id'];
            $cnt = (int)$row['vote_count'];
            if (!isset($ageBreakdown[$a])) {
                $ageBreakdown[$a] = [];
            }
            $ageBreakdown[$a][$o] = $cnt;
        }

        return [
            'pollId' => $poll['id'],
            'totalVotes' => $totalVotes,
            'updatedAt' => date('Y-m-d H:i:s'),
            'optionResults' => $optionResults,
            'countyBreakdown' => $countyBreakdown,
            'ageBreakdown' => $ageBreakdown
        ];
    }
}
