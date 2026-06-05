<?php

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\User_model;

/**
 * Tests for User_model — password hashing, allowed fields, and soft-delete flag.
 *
 * These tests do NOT require a running database. They exercise model
 * configuration and the hashPassword callback directly.
 *
 * @internal
 */
final class UserModelTest extends CIUnitTestCase
{
    private User_model $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new User_model();
    }

    // ── Configuration ──────────────────────────────────────────────

    public function testTableName(): void
    {
        $this->assertSame('user', $this->model->table);
    }

    public function testPrimaryKey(): void
    {
        $this->assertSame('user_id', $this->model->primaryKey);
    }

    public function testAllowedFieldsContainsRequiredColumns(): void
    {
        $allowed = $this->model->allowedFields;

        foreach (['role', 'username', 'password', 'email', 'is_deleted'] as $field) {
            $this->assertContains(
                $field,
                $allowed,
                "Expected '{$field}' in allowedFields"
            );
        }
    }

    public function testBeforeInsertHookIsRegistered(): void
    {
        $this->assertContains('hashPassword', $this->model->beforeInsert);
    }

    public function testBeforeUpdateHookIsRegistered(): void
    {
        $this->assertContains('hashPassword', $this->model->beforeUpdate);
    }

    // ── hashPassword callback ───────────────────────────────────────

    public function testHashPasswordReplacesPlaintextOnInsert(): void
    {
        $data = [
            'data' => [
                'username' => 'testuser',
                'password' => 'MySecret123',
                'email'    => 'test@example.com',
            ],
        ];

        $result = $this->invokeHashPassword($data);

        $hashed = $result['data']['password'];

        // Must no longer be plaintext
        $this->assertNotSame('MySecret123', $hashed);

        // Must be a valid bcrypt hash that verifies correctly
        $this->assertTrue(
            password_verify('MySecret123', $hashed),
            'Hashed password should verify against original plaintext'
        );
    }

    public function testHashPasswordDoesNothingWhenPasswordAbsent(): void
    {
        $data = [
            'data' => [
                'username' => 'testuser',
                'email'    => 'test@example.com',
            ],
        ];

        $result = $this->invokeHashPassword($data);

        // Data should be unchanged
        $this->assertSame($data['data'], $result['data']);
    }

    public function testHashPasswordProducesDifferentHashEachTime(): void
    {
        $data = ['data' => ['password' => 'SamePassword']];

        $hash1 = $this->invokeHashPassword($data)['data']['password'];
        $hash2 = $this->invokeHashPassword($data)['data']['password'];

        // bcrypt uses a random salt, so two hashes of the same string differ
        $this->assertNotSame($hash1, $hash2);

        // But both must still verify
        $this->assertTrue(password_verify('SamePassword', $hash1));
        $this->assertTrue(password_verify('SamePassword', $hash2));
    }

    // ── Helper ─────────────────────────────────────────────────────

    /**
     * Calls the protected hashPassword method via reflection.
     */
    private function invokeHashPassword(array $data): array
    {
        $ref    = new \ReflectionMethod(User_model::class, 'hashPassword');
        $ref->setAccessible(true);
        return $ref->invoke($this->model, $data);
    }
}
