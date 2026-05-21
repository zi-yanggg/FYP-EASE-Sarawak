<?php

namespace Tests\Security;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Authentication bypass and privilege escalation tests.
 * Verifies that the adminauth filter correctly blocks all admin routes.
 */
final class AuthBypassTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    // ── Every admin route must redirect unauthenticated users ────────────────

    /** @dataProvider adminRouteProvider */
    public function testAdminRouteRedirectsUnauthenticatedUser(string $method, string $route): void
    {
        $result = $this->call($method, $route);
        $this->assertSame(302, $result->response()->getStatusCode(),
            "Route {$method} /{$route} did not redirect unauthenticated user");
    }

    public static function adminRouteProvider(): array
    {
        return [
            ['GET',  'admin'],
            ['GET',  'report'],
            ['GET',  'order'],
            ['GET',  'user'],
            ['GET',  'admin/promo_code'],
            ['GET',  'admin/promo_code/create'],
            ['POST', 'admin/promo_code/store'],
            ['GET',  'admin/promo_code/delete/1'],
            ['GET',  'admin/refund_request'],
            ['GET',  'transaction_history'],
            ['GET',  'admin/calendar'],
            ['GET',  'admin/contact'],
            ['GET',  'admin/service_management'],
            ['GET',  'admin/getRevenueData'],
            ['GET',  'admin/getPeakTimesData'],
            ['GET',  'change_status/1'],
            ['GET',  'delete_user/1'],
            ['GET',  'edit_user/1'],
            ['GET',  'refund/view/1'],
        ];
    }

    // ── Tampered session must not grant admin access ──────────────────────────

    public function testSessionWithInvalidRoleIsBlocked(): void
    {
        // role = '2' is not a valid admin role
        $result = $this->withSession(['access' => 1, 'role' => '2', 'user_id' => 99])
                       ->call('GET', '/admin');
        $result->assertRedirectTo(base_url('login'));
    }

    public function testSessionWithAccessZeroIsBlocked(): void
    {
        $result = $this->withSession(['access' => 0, 'role' => '1', 'user_id' => 99])
                       ->call('GET', '/admin');
        $result->assertRedirectTo(base_url('login'));
    }

    public function testEmptySessionIsBlocked(): void
    {
        $result = $this->withSession([])->call('GET', '/admin');
        $result->assertRedirectTo(base_url('login'));
    }

    // ── AJAX requests to admin endpoints must return 401, not redirect ────────

    public function testAjaxRequestToAdminEndpointReturns401WhenUnauthenticated(): void
    {
        $result = $this->call(
            'GET',
            '/admin/getRevenueData',
            [],
            [],
            [],
            ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest']
        );

        $result->assertStatus(401);
    }

    // ── Password brute force — same password does not produce same hash ───────

    public function testDifferentHashesForSamePassword(): void
    {
        $hash1 = password_hash('password123', PASSWORD_DEFAULT);
        $hash2 = password_hash('password123', PASSWORD_DEFAULT);

        // bcrypt generates different salts — hashes must differ
        $this->assertNotSame($hash1, $hash2);
        // But both must verify correctly
        $this->assertTrue(password_verify('password123', $hash1));
        $this->assertTrue(password_verify('password123', $hash2));
    }

    // ── Remember-me token must not bypass role check ──────────────────────────

    public function testExpiredRememberMeTokenDoesNotGrantAccess(): void
    {
        // Cookie with a token that does not match any user
        $result = $this->withCookie('remember_me', 'nonexistenttoken12345')
                       ->call('GET', '/admin');

        $result->assertRedirectTo(base_url('login'));
    }
}
