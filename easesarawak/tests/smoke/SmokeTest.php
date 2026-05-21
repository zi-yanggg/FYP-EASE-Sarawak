<?php

namespace Tests\Smoke;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Smoke suite — runs in <60s, verifies the system is alive.
 * Run first in CI before any other suite.
 */
final class SmokeTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    // ── Public pages ────────────────────────────────────────────────────────

    public function testHomePageLoads(): void
    {
        $result = $this->call('GET', '/');
        $result->assertStatus(200);
    }

    public function testAboutPageLoads(): void
    {
        $result = $this->call('GET', '/about');
        $result->assertStatus(200);
    }

    public function testBookingPageLoads(): void
    {
        $result = $this->call('GET', '/booking');
        $result->assertStatus(200);
    }

    public function testLoginPageLoads(): void
    {
        $result = $this->call('GET', '/login');
        $result->assertStatus(200);
    }

    public function testForgotPasswordPageLoads(): void
    {
        $result = $this->call('GET', '/forgot_password');
        $result->assertStatus(200);
    }

    // ── Admin redirects unauthenticated users ────────────────────────────────

    public function testAdminRedirectsToLoginWhenUnauthenticated(): void
    {
        $result = $this->call('GET', '/admin');
        $result->assertRedirectTo(base_url('login'));
    }

    public function testReportRedirectsToLoginWhenUnauthenticated(): void
    {
        $result = $this->call('GET', '/report');
        $result->assertRedirectTo(base_url('login'));
    }

    public function testPromoCodeRedirectsToLoginWhenUnauthenticated(): void
    {
        $result = $this->call('GET', '/admin/promo_code');
        $result->assertRedirectTo(base_url('login'));
    }

    public function testRefundPdfRedirectsToLoginWhenUnauthenticated(): void
    {
        $result = $this->call('GET', '/refund/view/1');
        $result->assertRedirectTo(base_url('login'));
    }

    // ── Payment endpoint rejects invalid input ────────────────────────────────

    public function testPaymentIntentRejectsZeroAmount(): void
    {
        $result = $this->withBodyFormat('json')
                       ->call('POST', '/card-payment/intent', ['amount' => 0]);
        $result->assertStatus(422);
    }

    public function testPaymentIntentRejectsNegativeAmount(): void
    {
        $result = $this->withBodyFormat('json')
                       ->call('POST', '/card-payment/intent', ['amount' => -500]);
        $result->assertStatus(422);
    }

    // ── Webhook rejects bad signature ────────────────────────────────────────

    public function testWebhookRejectsMissingSignature(): void
    {
        $result = $this->call('POST', '/webhook', [], [], [], ['HTTP_STRIPE_SIGNATURE' => '']);
        $result->assertStatus(400);
    }
}
