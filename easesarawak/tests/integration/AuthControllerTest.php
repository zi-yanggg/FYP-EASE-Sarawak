<?php

namespace Tests\Integration;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Integration tests for login, logout, and password reset flows.
 * Requires the easesarawak_test database to be seeded.
 */
final class AuthControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    // ── Login ────────────────────────────────────────────────────────────────

    public function testLoginWithValidCredentialsRedirectsToAdmin(): void
    {
        // Seed a known admin user
        $this->db->table('user')->insert([
            'username'   => 'testadmin',
            'email'      => 'admin@test.com',
            'password'   => password_hash('Password1!', PASSWORD_DEFAULT),
            'role'       => '1',
            'is_deleted' => 0,
        ]);

        $result = $this->call('POST', '/login_submit', [
            'email'    => 'admin@test.com',
            'password' => 'Password1!',
        ]);

        $result->assertRedirectTo(base_url('/admin'));
    }

    public function testLoginWithWrongPasswordShowsError(): void
    {
        $result = $this->call('POST', '/login_submit', [
            'email'    => 'admin@test.com',
            'password' => 'wrongpassword',
        ]);

        $result->assertSessionHas('error');
    }

    public function testLoginWithNonExistentEmailShowsError(): void
    {
        $result = $this->call('POST', '/login_submit', [
            'email'    => 'nobody@test.com',
            'password' => 'anything',
        ]);

        $result->assertSessionHas('error');
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function testLogoutDestroysSessionAndRedirects(): void
    {
        $result = $this->withSession(['access' => 1, 'role' => '1', 'user_id' => 999])
                       ->call('GET', '/logout');

        $result->assertRedirectTo(base_url('/login'));
    }

    // ── Password reset ────────────────────────────────────────────────────────

    public function testForgotPasswordWithUnknownEmailShowsError(): void
    {
        $result = $this->call('POST', '/forgot_password', [
            'email' => 'nobody@nowhere.com',
        ]);

        $result->assertSessionHas('error');
    }

    public function testForgotPasswordWithInvalidEmailShowsValidationError(): void
    {
        $result = $this->call('POST', '/forgot_password', [
            'email' => 'not-an-email',
        ]);

        // Validation fails — redirected back
        $result->assertStatus(302);
    }

    public function testResetPasswordWithExpiredTokenRedirects(): void
    {
        $result = $this->call('GET', '/reset_password/invalidtoken123');
        $result->assertRedirect();
    }

    public function testResetPasswordHashesPasswordCorrectly(): void
    {
        $token   = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600);

        $userId = $this->db->table('user')->insert([
            'username'      => 'resetuser',
            'email'         => 'reset@test.com',
            'password'      => password_hash('OldPass1!', PASSWORD_DEFAULT),
            'role'          => '0',
            'is_deleted'    => 0,
            'reset_token'   => $token,
            'reset_expires' => $expires,
        ]);

        $this->call('POST', "/reset_password/{$token}", [
            'password'         => 'NewPass1!',
            'confirm_password' => 'NewPass1!',
        ]);

        $user = $this->db->table('user')->where('email', 'reset@test.com')->get()->getRowArray();

        // Stored value must be a bcrypt hash, never plain text
        $this->assertTrue(password_verify('NewPass1!', $user['password']));
        $this->assertNull($user['reset_token']);
    }

    public function testPasswordTooShortFailsValidation(): void
    {
        $token   = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600);

        $this->db->table('user')->insert([
            'username'      => 'shortpw',
            'email'         => 'short@test.com',
            'password'      => password_hash('OldPass1!', PASSWORD_DEFAULT),
            'role'          => '0',
            'is_deleted'    => 0,
            'reset_token'   => $token,
            'reset_expires' => $expires,
        ]);

        $result = $this->call('POST', "/reset_password/{$token}", [
            'password'         => 'abc',   // 3 chars — must fail min_length[8]
            'confirm_password' => 'abc',
        ]);

        // Should redirect back with validation error, not update the password
        $result->assertStatus(302);
        $user = $this->db->table('user')->where('email', 'short@test.com')->get()->getRowArray();
        $this->assertFalse(password_verify('abc', $user['password']));
    }
}
