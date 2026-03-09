<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../code/helpers.php';

class HelpersTest extends TestCase
{
    public function test_validate_email_accepts_valid_email(): void
    {
        $this->assertTrue(validate_email('user@example.com'));
        $this->assertTrue(validate_email('test+tag@domain.org'));
    }

    public function test_validate_email_rejects_invalid_email(): void
    {
        $this->assertFalse(validate_email(''));
        $this->assertFalse(validate_email('not-an-email'));
        $this->assertFalse(validate_email('@nodomain.com'));
    }

    public function test_csrf_token_generates_and_reuses_token(): void
    {
        // Start from a clean session array for this test.
        $_SESSION = [];

        $token1 = csrf_token();
        $this->assertNotSame('', $token1);
        $this->assertSame($token1, $_SESSION['csrf_token']);

        // Calling again should return the same token for this session.
        $token2 = csrf_token();
        $this->assertSame($token1, $token2);
    }

    public function test_csrf_verify_returns_true_for_matching_token(): void
    {
        $_SESSION['csrf_token'] = 'abc123';
        $_POST['csrf_token'] = 'abc123';

        $this->assertTrue(csrf_verify());
    }

    public function test_csrf_verify_returns_false_for_missing_or_mismatched_token(): void
    {
        $_SESSION['csrf_token'] = 'abc123';
        $_POST['csrf_token'] = 'different';
        $this->assertFalse(csrf_verify());

        $_POST = [];
        $this->assertFalse(csrf_verify());
    }
}
