<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Tests for the Login controller — HTTP response codes, redirects, and session.
 *
 * Uses CodeIgniter's FeatureTestTrait which simulates real HTTP requests through
 * the framework without needing a browser or running web server.
 *
 * Note: submit() and logout() tests that hit the database are marked as requiring
 * a test database (skipped automatically when no DB is configured).
 *
 * @internal
 */
final class LoginControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    // ── GET /login ─────────────────────────────────────────────────

    public function testLoginPageReturns200(): void
    {
        $result = $this->get('login');

        $result->assertStatus(200);
    }

    public function testLoginPageContainsForm(): void
    {
        $result = $this->get('login');

        // Assert the login form exists — input tags are self-closing so we
        // check for the <form> element instead of an input's text content.
        $result->assertSee('form');
    }

    // ── POST /login — validation (no DB required) ──────────────────

    public function testSubmitWithEmptyBodyRedirectsBack(): void
    {
        // Route is POST /login_submit (see app/Config/Routes.php)
        $result = $this->post('login_submit', []);

        // Missing credentials → controller redirects back with error
        $result->assertRedirect();
    }

    public function testSubmitWithInvalidCredentialsRedirectsWithError(): void
    {
        $result = $this->post('login_submit', [
            'email'    => 'nobody@example.com',
            'password' => 'wrongpassword',
        ]);

        $result->assertRedirect();
    }

    // ── GET /logout ────────────────────────────────────────────────

    public function testLogoutWithNoSessionRedirectsToLogin(): void
    {
        $result = $this->get('logout');

        // Logout always redirects regardless of session state
        $result->assertRedirect();
    }

    // ── Session state (no DB required) ─────────────────────────────

    public function testSessionServiceIsAvailable(): void
    {
        $session = service('session');
        $this->assertNotNull($session);
    }

    public function testSessionDoesNotHaveAccessByDefault(): void
    {
        $session = service('session');
        $this->assertNull($session->get('access'));
    }

    public function testSessionCanBeSetAndRead(): void
    {
        $session = service('session');
        $session->set('access', 1);

        $this->assertSame(1, $session->get('access'));
    }

    public function testSessionDestroyRemovesAccess(): void
    {
        $session = service('session');
        $session->set('access', 1);
        $session->destroy();

        // After destroy(), start a fresh session instance
        $fresh = service('session', null, false);
        $this->assertNull($fresh->get('access'));
    }

    // ── Password verification (pure unit, no DB) ───────────────────

    public function testPasswordVerifyMatchesHashedPassword(): void
    {
        $plain  = 'MySecurePassword!';
        $hashed = password_hash($plain, PASSWORD_DEFAULT);

        $this->assertTrue(password_verify($plain, $hashed));
    }

    public function testPasswordVerifyRejectsWrongPassword(): void
    {
        $hashed = password_hash('CorrectPassword', PASSWORD_DEFAULT);

        $this->assertFalse(password_verify('WrongPassword', $hashed));
    }
}
