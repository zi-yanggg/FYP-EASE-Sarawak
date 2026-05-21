<?php

namespace Tests\Security;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * IDOR (Insecure Direct Object Reference) tests.
 * Verifies that private resources cannot be accessed by unauthenticated users
 * or users who do not own the resource.
 */
final class IDORTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    // ── Refund PDF — must be admin-only ──────────────────────────────────────

    public function testAnonymousUserCannotViewRefundPdf(): void
    {
        $this->db->table('refund_form')->insert([
            'full_name'           => 'Private Person',
            'email'               => 'private@test.com',
            'phone_number'        => '0123456789',
            'order_id'            => 'ORD-999',
            'date_of_purchase'    => '2025-01-01',
            'service_type'        => 'delivery',
            'bank_name'           => 'Maybank',
            'account_holder_name' => 'Private Person',
            'account_number'      => '9999999999',
            'reason_for_refund'   => 'Test',
            'declaration'         => 1,
        ]);

        $id = $this->db->insertID();

        $result = $this->call('GET', "/refund/view/{$id}");

        // Must redirect to login, NOT serve the PDF
        $result->assertRedirectTo(base_url('login'));
    }

    public function testAnonymousUserCannotEnumerateMultipleRefundPdfs(): void
    {
        foreach (range(1, 5) as $i) {
            $result = $this->call('GET', "/refund/view/{$i}");
            // Every attempt must be a redirect, never a 200
            $this->assertNotSame(200, $result->response()->getStatusCode(),
                "Refund PDF #{$i} was accessible without authentication");
        }
    }

    public function testAdminCanViewRefundPdf(): void
    {
        $this->db->table('refund_form')->insert([
            'full_name'           => 'Admin Viewable',
            'email'               => 'viewable@test.com',
            'phone_number'        => '0123456789',
            'order_id'            => 'ORD-998',
            'date_of_purchase'    => '2025-01-01',
            'service_type'        => 'storage',
            'bank_name'           => 'CIMB',
            'account_holder_name' => 'Admin Viewable',
            'account_number'      => '1111111111',
            'reason_for_refund'   => 'Test',
            'declaration'         => 1,
            'pdf_path'            => '',
        ]);

        $id = $this->db->insertID();

        $result = $this->withSession(['access' => 1, 'role' => '1', 'user_id' => 1])
                       ->call('GET', "/refund/view/{$id}");

        // Admin gets the view (200), not a redirect
        $this->assertNotSame(302, $result->response()->getStatusCode());
    }

    // ── Order details — must be admin-only ────────────────────────────────────

    public function testAnonymousUserCannotViewOrderDetails(): void
    {
        $result = $this->call('GET', '/admin/order_details/1');
        $result->assertRedirectTo(base_url('login'));
    }

    public function testAnonymousUserCannotGetOrderDetailsJson(): void
    {
        $result = $this->call('GET', '/order/getDetails/1');
        $result->assertRedirectTo(base_url('login'));
    }

    // ── User management — must be admin-only ──────────────────────────────────

    public function testAnonymousUserCannotEditAnotherUser(): void
    {
        $result = $this->call('GET', '/edit_user/1');
        $result->assertRedirectTo(base_url('login'));
    }

    public function testAnonymousUserCannotDeleteUser(): void
    {
        $result = $this->call('GET', '/delete_user/1');
        $result->assertRedirectTo(base_url('login'));
    }

    // ── Revenue/reporting data — must be admin-only ───────────────────────────

    public function testAnonymousUserCannotAccessRevenueData(): void
    {
        $result = $this->call('GET', '/admin/getRevenueData');
        $result->assertRedirectTo(base_url('login'));
    }

    public function testAnonymousUserCannotAccessTransactionHistory(): void
    {
        $result = $this->call('GET', '/transaction_history');
        $result->assertRedirectTo(base_url('login'));
    }
}
