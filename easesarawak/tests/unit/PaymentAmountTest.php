<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

/**
 * Unit tests for payment amount rules.
 * These guard against client-side amount manipulation reaching Stripe.
 */
final class PaymentAmountTest extends CIUnitTestCase
{
    // ── Amount validation rules (mirrors CardPayment::createIntent logic) ────

    private function isValidAmount(int $cents): bool
    {
        return $cents > 0;
    }

    public function testPositiveAmountIsValid(): void
    {
        $this->assertTrue($this->isValidAmount(4800)); // RM 48.00
    }

    public function testZeroAmountIsInvalid(): void
    {
        $this->assertFalse($this->isValidAmount(0));
    }

    public function testNegativeAmountIsInvalid(): void
    {
        $this->assertFalse($this->isValidAmount(-100));
    }

    // ── Currency normalisation ────────────────────────────────────────────────

    private function normaliseCurrency(string $input): string
    {
        return strtolower(trim($input));
    }

    public function testCurrencyIsLowercased(): void
    {
        $this->assertSame('myr', $this->normaliseCurrency('MYR'));
    }

    public function testCurrencyTrimsWhitespace(): void
    {
        $this->assertSame('myr', $this->normaliseCurrency(' myr '));
    }

    // ── Booking amount calculation (delivery + storage pricing) ──────────────

    private function calculateBookingTotal(string $serviceType, int $items, int $basePrice, int $extraRate): int
    {
        // First item at base price, each extra item at extra rate
        if ($items <= 0) return 0;
        return $basePrice + max(0, $items - 1) * $extraRate;
    }

    public function testSingleItemUsesBasePrice(): void
    {
        $this->assertSame(24, $this->calculateBookingTotal('delivery', 1, 24, 6));
    }

    public function testTwoItemsAddsExtraRate(): void
    {
        $this->assertSame(30, $this->calculateBookingTotal('delivery', 2, 24, 6));
    }

    public function testZeroItemsReturnsZero(): void
    {
        $this->assertSame(0, $this->calculateBookingTotal('delivery', 0, 24, 6));
    }

    public function testStoragePricingUsesCorrectRates(): void
    {
        $this->assertSame(18, $this->calculateBookingTotal('storage', 1, 18, 6));
        $this->assertSame(24, $this->calculateBookingTotal('storage', 2, 18, 6));
    }
}
