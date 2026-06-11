<?php

use App\Services\PricingService;
use App\Services\PromoService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class PricingServiceTest extends CIUnitTestCase
{
    private function makePricingService(array $serviceRow): PricingService
    {
        $serviceModel = new class ($serviceRow) extends \App\Models\ServiceManagementModel {
            public function __construct(private array $row)
            {
            }

            public function where($key, $value = null, $escape = null)
            {
                return $this;
            }

            public function first()
            {
                return $this->row;
            }
        };

        return new PricingService($serviceModel, new PromoService());
    }

    public function testCalculateDeliveryBasePrice(): void
    {
        $pricing = $this->makePricingService(['base_price' => 24, 'extra_rate' => 6]);

        $result = $pricing->calculateFromBookingData([
            'service'           => 'delivery',
            'quantity'          => 1,
            'dropoffDate'       => '2026-06-11',
            'dropoffTime'       => '10:00',
            'pickupDate'        => '2026-06-11',
            'pickupTime'        => '12:00',
            'insuranceSelected' => false,
            'promoCode'         => '',
        ]);

        $this->assertSame(24.0, $result['total_rm']);
        $this->assertSame(2400, $result['total_cents']);
    }

    public function testCalculateStorageWithInsurance(): void
    {
        $pricing = $this->makePricingService(['base_price' => 18, 'extra_rate' => 6]);

        $result = $pricing->calculateFromBookingData([
            'service'           => 'storage',
            'quantity'          => 2,
            'dropoffDate'       => '2026-06-11',
            'dropoffTime'       => '10:00',
            'pickupDate'        => '2026-06-11',
            'pickupTime'        => '12:00',
            'insuranceSelected' => true,
            'promoCode'         => '',
        ]);

        $this->assertSame(42.0, $result['total_rm']);
    }
}
