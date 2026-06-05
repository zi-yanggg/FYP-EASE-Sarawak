<?php

use CodeIgniter\Test\CIUnitTestCase;

/**
 * Tests for CodeIgniter's validation service using the rules that EASE Sarawak
 * relies on across its forms: required, valid_email, min_length, max_length,
 * alpha_numeric, integer, and matches.
 *
 * No database is required — all validation is run in-memory.
 *
 * @internal
 */
final class FormValidationTest extends CIUnitTestCase
{
    private \CodeIgniter\Validation\ValidationInterface $validation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validation = service('validation');
    }

    protected function tearDown(): void
    {
        $this->validation->reset();
        parent::tearDown();
    }

    // ── Email field ─────────────────────────────────────────────────

    public function testValidEmailPasses(): void
    {
        $this->validation->setRules(['email' => 'required|valid_email']);
        $this->assertTrue($this->validation->run(['email' => 'user@example.com']));
    }

    public function testInvalidEmailFails(): void
    {
        $this->validation->setRules(['email' => 'required|valid_email']);
        $this->assertFalse($this->validation->run(['email' => 'not-an-email']));
    }

    public function testEmptyEmailFails(): void
    {
        $this->validation->setRules(['email' => 'required|valid_email']);
        $this->assertFalse($this->validation->run(['email' => '']));
    }

    public function testMissingEmailFails(): void
    {
        $this->validation->setRules(['email' => 'required|valid_email']);
        $this->assertFalse($this->validation->run([]));
    }

    // ── Password field ──────────────────────────────────────────────

    public function testPasswordMeetsMinLength(): void
    {
        $this->validation->setRules(['password' => 'required|min_length[8]']);
        $this->assertTrue($this->validation->run(['password' => '12345678']));
    }

    public function testPasswordTooShortFails(): void
    {
        $this->validation->setRules(['password' => 'required|min_length[8]']);
        $this->assertFalse($this->validation->run(['password' => '1234567']));
    }

    public function testPasswordMaxLength(): void
    {
        $this->validation->setRules(['password' => 'required|max_length[100]']);
        $this->assertTrue($this->validation->run(['password' => str_repeat('a', 100)]));
    }

    public function testPasswordExceedsMaxLengthFails(): void
    {
        $this->validation->setRules(['password' => 'required|max_length[100]']);
        $this->assertFalse($this->validation->run(['password' => str_repeat('a', 101)]));
    }

    // ── Confirm password (matches rule) ─────────────────────────────

    public function testPasswordConfirmMatchesPasses(): void
    {
        $this->validation->setRules([
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ]);

        $this->assertTrue($this->validation->run([
            'password'         => 'securepass',
            'password_confirm' => 'securepass',
        ]));
    }

    public function testPasswordConfirmMismatchFails(): void
    {
        $this->validation->setRules([
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ]);

        $this->assertFalse($this->validation->run([
            'password'         => 'securepass',
            'password_confirm' => 'different',
        ]));
    }

    // ── Username / text fields ──────────────────────────────────────

    public function testRequiredFieldEmptyFails(): void
    {
        $this->validation->setRules(['username' => 'required']);
        $this->assertFalse($this->validation->run(['username' => '']));
    }

    public function testRequiredFieldPresentPasses(): void
    {
        $this->validation->setRules(['username' => 'required']);
        $this->assertTrue($this->validation->run(['username' => 'admin']));
    }

    public function testMaxLengthExactBoundaryPasses(): void
    {
        $this->validation->setRules(['username' => 'required|max_length[50]']);
        $this->assertTrue($this->validation->run(['username' => str_repeat('a', 50)]));
    }

    public function testMaxLengthOverBoundaryFails(): void
    {
        $this->validation->setRules(['username' => 'required|max_length[50]']);
        $this->assertFalse($this->validation->run(['username' => str_repeat('a', 51)]));
    }

    // ── Integer field (social / promo) ──────────────────────────────

    public function testIntegerValuePasses(): void
    {
        $this->validation->setRules(['social' => 'required|integer']);
        $this->assertTrue($this->validation->run(['social' => '1']));
    }

    public function testNonIntegerFails(): void
    {
        $this->validation->setRules(['social' => 'required|integer']);
        $this->assertFalse($this->validation->run(['social' => 'abc']));
    }

    // ── Phone number ────────────────────────────────────────────────

    public function testPhoneRequiredFails(): void
    {
        $this->validation->setRules(['phone' => 'required|max_length[20]']);
        $this->assertFalse($this->validation->run(['phone' => '']));
    }

    public function testPhoneWithinMaxLengthPasses(): void
    {
        $this->validation->setRules(['phone' => 'required|max_length[20]']);
        $this->assertTrue($this->validation->run(['phone' => '+601234567890']));
    }

    public function testPhoneTooLongFails(): void
    {
        $this->validation->setRules(['phone' => 'required|max_length[20]']);
        $this->assertFalse($this->validation->run(['phone' => str_repeat('1', 21)]));
    }

    // ── URL (baseURL check) ──────────────────────────────────────────

    public function testValidUrlPasses(): void
    {
        $this->validation->setRules(['url' => 'required|valid_url']);
        $this->assertTrue($this->validation->run(['url' => 'http://localhost/']));
    }

    public function testInvalidUrlFails(): void
    {
        $this->validation->setRules(['url' => 'required|valid_url']);
        $this->assertFalse($this->validation->run(['url' => 'not a url']));
    }
}
