<?php

namespace App\Services;

use App\Models\OrderModel;
use App\Models\ServiceManagementModel;

class PricingService
{
    private const INSURANCE_PER_ITEM = 3;

    public function __construct(
        private ?ServiceManagementModel $serviceModel = null,
        private ?PromoService $promoService = null,
        private ?OrderModel $orderModel = null,
    ) {
        $this->serviceModel = $this->serviceModel ?? new ServiceManagementModel();
        $this->promoService = $this->promoService ?? new PromoService();
        $this->orderModel   = $this->orderModel ?? new OrderModel();
    }

    /**
     * Calculate total from booking wizard payload (matches bookingdetail.js logic).
     *
     * @return array{total_rm: float, total_cents: int, breakdown: array<string, float>}
     */
    public function calculateFromBookingData(array $bookingData): array
    {
        $service  = strtolower((string) ($bookingData['service'] ?? ''));
        $quantity = max(1, (int) ($bookingData['quantity'] ?? 1));

        if (! in_array($service, ['storage', 'delivery'], true)) {
            throw new \InvalidArgumentException('Invalid service type.');
        }

        $serviceRow = $this->serviceModel->where('service_type', $service)->first();
        $basePrice  = (int) ($serviceRow['base_price'] ?? ($service === 'storage' ? 18 : 24));
        $extraRate  = (int) ($serviceRow['extra_rate'] ?? 6);

        $subtotal = $basePrice * $quantity;

        $dropoffDate = (string) ($bookingData['dropoffDate'] ?? '');
        $dropoffTime = (string) ($bookingData['dropoffTime'] ?? '');
        $pickupDate  = (string) ($bookingData['pickupDate'] ?? '');
        $pickupTime  = (string) ($bookingData['pickupTime'] ?? '');

        $startTs = strtotime(trim($dropoffDate . ' ' . $dropoffTime));
        $endTs   = strtotime(trim($pickupDate . ' ' . $pickupTime));

        $extraCharge = 0.0;
        if ($startTs !== false && $endTs !== false && $endTs > $startTs) {
            $diffHours   = (int) ceil(($endTs - $startTs) / 3600);
            $baseHours   = $service === 'delivery' ? 24 : 12;
            $extraBlocks = max(0, (int) ceil(($diffHours - $baseHours) / 12));
            $extraCharge = $extraBlocks * $extraRate * $quantity;
        }

        $insuranceSelected = filter_var(
            $bookingData['insuranceSelected'] ?? false,
            FILTER_VALIDATE_BOOLEAN
        );
        $insuranceCharge = $insuranceSelected ? self::INSURANCE_PER_ITEM * $quantity : 0.0;

        $promoCode = trim((string) ($bookingData['promoCode'] ?? ''));
        $discount  = 0.0;
        if ($promoCode !== '') {
            $promo = $this->promoService->validate($promoCode);
            if ($promo['valid']) {
                $discount = $this->promoService->calculateDiscount(
                    $subtotal + $extraCharge + $insuranceCharge,
                    $promo
                );
            }
        }

        $totalRm = max(0, $subtotal + $extraCharge + $insuranceCharge - $discount);

        return [
            'total_rm'    => round($totalRm, 2),
            'total_cents' => (int) round($totalRm * 100),
            'breakdown'   => [
                'subtotal'          => (float) $subtotal,
                'extra_charge'      => (float) $extraCharge,
                'insurance_charge'  => (float) $insuranceCharge,
                'discount'          => (float) $discount,
            ],
        ];
    }

    /**
     * Authoritative price for an existing order row.
     */
    public function calculateFromOrderId(int $orderId): array
    {
        $order = $this->orderModel
            ->where('order_id', $orderId)
            ->where('is_deleted', 0)
            ->first();

        if (! $order) {
            throw new \RuntimeException('Order not found.');
        }

        $storedAmount = (float) ($order['amount'] ?? 0);
        if ($storedAmount > 0) {
            return [
                'total_rm'    => $storedAmount,
                'total_cents' => (int) round($storedAmount * 100),
                'breakdown'   => ['stored_amount' => $storedAmount],
            ];
        }

        $details = json_decode($order['order_details_json'] ?? '{}', true);
        if (! is_array($details)) {
            throw new \RuntimeException('Order has no pricing data.');
        }

        $bookingData = $this->orderDetailsToBookingData($order, $details);

        return $this->calculateFromBookingData($bookingData);
    }

    /**
     * @param array<string, mixed> $order
     * @param array<string, mixed> $details
     */
    private function orderDetailsToBookingData(array $order, array $details): array
    {
        $service = strtolower((string) ($order['service_type'] ?? 'delivery'));

        $quantityRaw = (string) ($details['Quantity'] ?? '1');
        preg_match('/(\d+)/', $quantityRaw, $m);
        $quantity = max(1, (int) ($m[1] ?? 1));

        $dropoff = $this->parseDetailDateTime($details['Drop-off DateTime'] ?? '');
        $pickup  = $this->parseDetailDateTime($details['Pickup DateTime'] ?? '');

        $insuranceSelected = stripos((string) ($details['Insurance Selected'] ?? ''), 'yes') !== false;

        $promoCode = (string) ($details['Promo Code'] ?? '');
        if (strcasecmp($promoCode, 'Null') === 0 || $promoCode === '') {
            $promoCode = (string) ($order['promo_code'] ?? '');
        }

        return [
            'service'           => $service,
            'quantity'          => $quantity,
            'dropoffDate'       => $dropoff['date'] ?? '',
            'dropoffTime'       => $dropoff['time'] ?? '',
            'pickupDate'        => $pickup['date'] ?? '',
            'pickupTime'        => $pickup['time'] ?? '',
            'insuranceSelected' => $insuranceSelected,
            'promoCode'         => $promoCode,
        ];
    }

    /**
     * @return array{date: string, time: string}|array{}
     */
    private function parseDetailDateTime(mixed $value): array
    {
        $value = trim((string) $value);
        if ($value === '' || strcasecmp($value, 'Null') === 0) {
            return [];
        }

        if (preg_match('/^(.+?)\s+at\s+(.+)$/u', $value, $m)) {
            return ['date' => trim($m[1]), 'time' => trim($m[2])];
        }

        return [];
    }
}
