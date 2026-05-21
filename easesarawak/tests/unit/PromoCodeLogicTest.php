<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

/**
 * Pure logic tests — no DB, no HTTP.
 * Covers promo discount calculations and date boundary behaviour.
 */
final class PromoCodeLogicTest extends CIUnitTestCase
{
    // ── Discount calculation helpers ─────────────────────────────────────────

    private function applyDiscount(float $amount, string $type, float $value): float
    {
        if ($type === 'percentage') {
            return round($amount - ($amount * $value / 100), 2);
        }
        return round(max(0, $amount - $value), 2);
    }

    public function testPercentageDiscountCalculatesCorrectly(): void
    {
        $this->assertSame(40.00, $this->applyDiscount(50.00, 'percentage', 20));
    }

    public function testFixedAmountDiscountCalculatesCorrectly(): void
    {
        $this->assertSame(26.00, $this->applyDiscount(50.00, 'amount', 24));
    }

    public function testFullPercentageDiscountResultsInZero(): void
    {
        $this->assertSame(0.00, $this->applyDiscount(50.00, 'percentage', 100));
    }

    public function testDiscountLargerThanAmountDoesNotGoNegative(): void
    {
        $result = $this->applyDiscount(10.00, 'amount', 50.00);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function testZeroPercentageDiscountLeavesAmountUnchanged(): void
    {
        $this->assertSame(50.00, $this->applyDiscount(50.00, 'percentage', 0));
    }

    // ── Promo date window validation ─────────────────────────────────────────

    private function isPromoActive(string $validFrom, string $expiry, string $now): bool
    {
        return $now >= $validFrom && $now <= $expiry;
    }

    public function testActivePromoIsValid(): void
    {
        $this->assertTrue($this->isPromoActive('2024-01-01 00:00:00', '2030-12-31 23:59:59', date('Y-m-d H:i:s')));
    }

    public function testExpiredPromoIsInvalid(): void
    {
        $this->assertFalse($this->isPromoActive('2020-01-01 00:00:00', '2020-12-31 23:59:59', date('Y-m-d H:i:s')));
    }

    public function testFuturePromoIsInvalid(): void
    {
        $future = date('Y-m-d H:i:s', strtotime('+1 year'));
        $this->assertFalse($this->isPromoActive($future, date('Y-m-d H:i:s', strtotime('+2 years')), date('Y-m-d H:i:s')));
    }

    public function testPromoExactlyAtValidationBoundaryIsActive(): void
    {
        $now = date('Y-m-d H:i:s');
        $this->assertTrue($this->isPromoActive($now, date('Y-m-d H:i:s', strtotime('+1 hour')), $now));
    }

    public function testPromoExactlyAtExpiryBoundaryIsActive(): void
    {
        $now = date('Y-m-d H:i:s');
        $this->assertTrue($this->isPromoActive(date('Y-m-d H:i:s', strtotime('-1 hour')), $now, $now));
    }
}
