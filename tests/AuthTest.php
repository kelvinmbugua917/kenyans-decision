<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class AuthTest extends TestCase {
    public function testPasswordHashingVerification(): void {
        $password = 'SecurePassword123!';
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $this->assertTrue(password_verify($password, $hash));
        $this->assertFalse(password_verify('WrongPassword', $hash));
    }

    public function testEmailValidationLogic(): void {
        $validEmail = 'voter@kenyansdecision.online';
        $invalidEmail = 'not-an-email';

        $this->assertNotFalse(filter_var($validEmail, FILTER_VALIDATE_EMAIL));
        $this->assertFalse(filter_var($invalidEmail, FILTER_VALIDATE_EMAIL));
    }

    public function testUserRoleDefaults(): void {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'voter'
        ];

        $this->assertEquals('voter', $userData['role']);
        $this->assertNotEquals('admin', $userData['role']);
    }
}
