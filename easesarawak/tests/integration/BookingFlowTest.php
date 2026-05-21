<?php

namespace Tests\Integration;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Integration tests for the customer-facing booking flow.
 * Covers: booking pages load, order save, refund submission, message contact.
 */
final class BookingFlowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    // ── Booking pages ─────────────────────────────────────────────────────────

    public function testBookingPageLoads(): void
    {
        $this->call('GET', '/booking')->assertStatus(200);
    }

    public function testBookingDetailPageLoads(): void
    {
        $this->call('GET', '/bookingdetail')->assertStatus(200);
    }

    public function testBookingCustomerDetailPageLoads(): void
    {
        $this->call('GET', '/bookingcustomerdetail')->assertStatus(200);
    }

    // ── Refund submission ─────────────────────────────────────────────────────

    public function testRefundSubmissionCreatesRecord(): void
    {
        $result = $this->call('POST', '/refund/submit', [
            'full_name'           => 'Test User',
            'email'               => 'test@user.com',
            'phone_number'        => '0123456789',
            'order_id'            => 'ORD-001',
            'date_of_purchase'    => '2025-01-01',
            'service_type'        => 'delivery',
            'bank_name'           => 'Maybank',
            'account_holder_name' => 'Test User',
            'account_number'      => '1234567890',
            'reason_for_refund'   => 'Service not delivered',
            'declaration'         => '1',
        ]);

        $result->assertRedirect();
        $this->seeInDatabase('refund_form', ['email' => 'test@user.com']);
    }

    public function testRefundExceptionMessageIsNotExposedToUser(): void
    {
        // Submit with empty required fields to trigger a DB error
        $result = $this->call('POST', '/refund/submit', []);
        // If it redirects with an error, the error message must be generic
        if ($result->response()->getStatusCode() === 302) {
            $message = session()->getFlashdata('refund_message') ?? '';
            $this->assertStringNotContainsString('SQLSTATE', $message);
            $this->assertStringNotContainsString('Exception', $message);
        }
    }

    // ── Contact message ───────────────────────────────────────────────────────

    public function testContactMessageIsStoredInDatabase(): void
    {
        $result = $this->call('POST', '/message', [
            'email'   => 'contact@test.com',
            'phone'   => '0112345678',
            'subject' => 'Test enquiry',
            'message' => 'Hello, this is a test message.',
        ]);

        $result->assertRedirect();
        $this->seeInDatabase('message', ['email' => 'contact@test.com']);
    }

    // ── Order status transitions ──────────────────────────────────────────────

    public function testAdminCanChangeOrderStatus(): void
    {
        $this->db->table('order')->insert([
            'first_name'   => 'Jane',
            'last_name'    => 'Doe',
            'email'        => 'jane@test.com',
            'status'       => 0,
            'amount'       => 48,
            'is_deleted'   => 0,
            'created_date' => date('Y-m-d H:i:s'),
        ]);

        $orderId = $this->db->insertID();

        $result = $this->withSession(['access' => 1, 'role' => '1', 'user_id' => 1])
                       ->call('GET', "/change_status/{$orderId}");

        $result->assertRedirect();
        $order = $this->db->table('order')->where('order_id', $orderId)->get()->getRowArray();
        $this->assertSame(1, (int)$order['status']); // 0 → 1 (pending → in-progress)
    }

    public function testUnauthenticatedUserCannotChangeOrderStatus(): void
    {
        $result = $this->call('GET', '/change_status/1');
        $result->assertRedirectTo(base_url('login'));
    }
}
