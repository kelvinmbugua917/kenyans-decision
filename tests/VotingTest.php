<?php
namespace Tests;

use PHPUnit\Framework\TestCase;

class VotingTest extends TestCase {
    public function testHmacDigestGeneration(): void {
        $ip = '197.237.1.5';
        $hmacKey = 'secret_hmac_test_key_123';

        $ipHmac = hash_hmac('sha256', $ip, $hmacKey);
        $this->assertEquals(64, strlen($ipHmac));
        
        // Ensure IP is irreversibly masked
        $this->assertStringNotContainsString($ip, $ipHmac);
    }

    public function testVoterFingerprintCompositeHash(): void {
        $ipHmac = hash_hmac('sha256', '197.237.1.5', 'key');
        $deviceToken = 'fp_device_abc123';
        $pollId = 'poll_presidential_2027';
        $hmacKey = 'key';

        $voterHash1 = hash_hmac('sha256', $ipHmac . '_' . $deviceToken . '_' . $pollId, $hmacKey);
        $voterHash2 = hash_hmac('sha256', $ipHmac . '_' . $deviceToken . '_' . $pollId, $hmacKey);

        $this->assertEquals($voterHash1, $voterHash2, 'Composite voter hash must be deterministic for identical credentials');
    }

    public function testPercentageCalculation(): void {
        $votesOptionA = 60;
        $votesOptionB = 40;
        $totalVotes = $votesOptionA + $votesOptionB;

        $pctA = round(($votesOptionA / $totalVotes) * 100, 1);
        $pctB = round(($votesOptionB / $totalVotes) * 100, 1);

        $this->assertEquals(60.0, $pctA);
        $this->assertEquals(40.0, $pctB);
        $this->assertEquals(100.0, $pctA + $pctB);
    }
}
