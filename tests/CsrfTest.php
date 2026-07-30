<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Middleware\CsrfMiddleware;

class CsrfTest extends TestCase {
    public function testTokenGenerationReturns32ByteHex(): void {
        $token = CsrfMiddleware::generateToken();
        $this->assertNotEmpty($token);
        $this->assertEquals(64, strlen($token)); // 32 bytes hex = 64 characters
    }

    public function testTokenConsistencyInSession(): void {
        $token1 = CsrfMiddleware::generateToken();
        $token2 = CsrfMiddleware::generateToken();
        $this->assertEquals($token1, $token2, 'CSRF token should remain constant during session');
    }

    public function testHashEqualsValidation(): void {
        $token = CsrfMiddleware::generateToken();
        $validToken = $token;
        $invalidToken = 'invalid_csrf_token_hash_value_12345';

        $this->assertTrue(hash_equals($token, $validToken));
        $this->assertFalse(hash_equals($token, $invalidToken));
    }
}
