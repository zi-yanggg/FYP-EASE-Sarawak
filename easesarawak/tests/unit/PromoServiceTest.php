<?php

use App\Services\PromoService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class PromoServiceTest extends CIUnitTestCase
{
    public function testCalculatePercentageDiscount(): void
    {
        $service = new PromoService();
        $discount = $service->calculateDiscount(100.0, [
            'discount_type' => 'percentage',
            'discount'      => 10,
        ]);

        $this->assertSame(10.0, $discount);
    }

    public function testCalculateAmountDiscountCappedAtSubtotal(): void
    {
        $service = new PromoService();
        $discount = $service->calculateDiscount(20.0, [
            'discount_type' => 'amount',
            'discount'      => 50,
        ]);

        $this->assertSame(20.0, $discount);
    }
}
