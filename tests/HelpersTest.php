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
}
