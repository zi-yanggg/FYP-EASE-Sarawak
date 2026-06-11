<?php

namespace App\Services;

/**
 * Normalizes booking data across raw wizard JSON, legacy display JSON, and order_booking rows.
 */
class OrderDetailsService
{
    /**
     * @param array<string, mixed> $bookingData Raw wizard payload from the frontend.
     * @return array{
     *   dropoff_at: ?string,
     *   pickup_at: ?string,
     *   origin: ?string,
     *   destination: ?string,
     *   storage_location: ?string,
     *   quantity: int
     * }
     */
    public function extractIndexedFields(array $bookingData): array
    {
        $service  = strtolower((string) ($bookingData['service'] ?? ''));
        $quantity = max(1, (int) ($bookingData['quantity'] ?? 1));

        $dropoffAt = $this->combineDateTime(
            (string) ($bookingData['dropoffDate'] ?? ''),
            (string) ($bookingData['dropoffTime'] ?? '')
        );
        $pickupAt = $this->combineDateTime(
            (string) ($bookingData['pickupDate'] ?? ''),
            (string) ($bookingData['pickupTime'] ?? '')
        );

        $origin      = null;
        $destination = null;
        $storageLoc  = null;

        if ($service === 'storage') {
            $storageLoc = $this->nullIfEmpty($bookingData['storageLocation'] ?? null);
        } else {
            $origin      = $this->nullIfEmpty($bookingData['origin'] ?? $bookingData['originAddress'] ?? null);
            $destination = $this->nullIfEmpty($bookingData['destination'] ?? $bookingData['destinationAddress'] ?? null);
        }

        return [
            'dropoff_at'       => $dropoffAt,
            'pickup_at'        => $pickupAt,
            'origin'           => $origin,
            'destination'      => $destination,
            'storage_location' => $storageLoc,
            'quantity'         => $quantity,
        ];
    }

    /**
     * Resolve raw booking data for an order (prefers order_booking, falls back to legacy JSON).
     *
     * @param array<string, mixed>      $order
     * @param array<string, mixed>|null $bookingRow
     * @return array<string, mixed>
     */
    public function resolveBookingData(array $order, ?array $bookingRow = null): array
    {
        if ($bookingRow !== null && ! empty($bookingRow['booking_json'])) {
            $decoded = json_decode((string) $bookingRow['booking_json'], true);

            return is_array($decoded) ? $decoded : [];
        }

        $legacy = json_decode((string) ($order['order_details_json'] ?? '{}'), true);
        if (! is_array($legacy)) {
            return [];
        }

        if ($this->isRawBookingData($legacy)) {
            return $legacy;
        }

        return $this->legacyDisplayToBookingData($order, $legacy);
    }

    /**
     * Human-readable key/value rows for admin detail views.
     *
     * @param array<string, mixed>      $order
     * @param array<string, mixed>|null $bookingRow
     * @return array<string, mixed>
     */
    public function displayDetails(array $order, ?array $bookingRow = null): array
    {
        $booking = $this->resolveBookingData($order, $bookingRow);
        if ($booking === []) {
            return [];
        }

        if ($this->isRawBookingData($booking)) {
            return $this->formatDisplayDetails($booking);
        }

        return $booking;
    }

    /**
     * @param array<string, mixed> $bookingData
     * @return array<string, mixed>
     */
    public function formatDisplayDetails(array $bookingData): array
    {
        $service = strtolower((string) ($bookingData['service'] ?? ''));

        $promoDiscount = '';
        if (! empty($bookingData['promoDiscount'])) {
            $promoDiscount = ($bookingData['promoType'] ?? 'amount') === 'amount'
                ? $bookingData['promoDiscount'] . 'RM'
                : $bookingData['promoDiscount'] . '%';
        }

        $dropoff = $this->formatDateTimeLabel(
            (string) ($bookingData['dropoffDate'] ?? ''),
            (string) ($bookingData['dropoffTime'] ?? '')
        );
        $pickup = $this->formatDateTimeLabel(
            (string) ($bookingData['pickupDate'] ?? ''),
            (string) ($bookingData['pickupTime'] ?? '')
        );

        if ($service === 'storage') {
            return [
                'Service Type'       => 'Storage',
                'Storage Location'   => $this->labelOrNull($bookingData['storageLocation'] ?? null),
                'Drop-off DateTime'  => $dropoff,
                'Pickup DateTime'    => $pickup,
                'Quantity'           => ! empty($bookingData['quantity']) ? $bookingData['quantity'] . ' item(s)' : 'Null',
                'Base Price'         => ! empty($bookingData['basePrice']) ? 'RM ' . $bookingData['basePrice'] : 'Null',
                'Promo Code'         => $this->labelOrNull($bookingData['promoCode'] ?? null),
                'Promo Discount'     => $promoDiscount !== '' ? $promoDiscount : 'Null',
                'Total Price'        => ! empty($bookingData['totalPrice']) ? 'RM ' . $bookingData['totalPrice'] : 'Null',
                'Insurance Selected' => ! empty($bookingData['insuranceSelected']) ? 'Yes' : 'No',
                'Insurance Amount'   => isset($bookingData['insuranceAmount'])
                    ? 'RM ' . number_format((float) $bookingData['insuranceAmount'], 2)
                    : 'RM 0.00',
            ];
        }

        return [
            'Service Type'          => ucfirst($service !== '' ? $service : 'delivery'),
            'Origin Location'       => $this->labelOrNull($bookingData['origin'] ?? null),
            'Origin Address'        => $this->labelOrNull($bookingData['originAddress'] ?? null),
            'Destination Location'  => $this->labelOrNull($bookingData['destination'] ?? null),
            'Destination Address'   => $this->labelOrNull($bookingData['destinationAddress'] ?? null),
            'Drop-off DateTime'     => $dropoff,
            'Pickup DateTime'       => $pickup,
            'Quantity'              => ! empty($bookingData['quantity']) ? $bookingData['quantity'] . ' item(s)' : 'Null',
            'Base Price'            => ! empty($bookingData['basePrice']) ? 'RM ' . $bookingData['basePrice'] : 'Null',
            'Promo Code'            => $this->labelOrNull($bookingData['promoCode'] ?? null),
            'Promo Discount'        => $promoDiscount !== '' ? $promoDiscount : 'Null',
            'Total Price'           => ! empty($bookingData['totalPrice']) ? 'RM ' . $bookingData['totalPrice'] : 'Null',
            'Insurance Selected'    => ! empty($bookingData['insuranceSelected']) ? 'Yes' : 'No',
            'Insurance Amount'      => isset($bookingData['insuranceAmount'])
                ? 'RM ' . number_format((float) $bookingData['insuranceAmount'], 2)
                : 'RM 0.00',
        ];
    }

    /**
     * @param array<string, mixed> $order
     * @param array<string, mixed> $legacy
     * @return array<string, mixed>
     */
    public function legacyDisplayToBookingData(array $order, array $legacy): array
    {
        $service = strtolower((string) ($order['service_type'] ?? 'delivery'));

        $quantityRaw = (string) ($legacy['Quantity'] ?? '1');
        preg_match('/(\d+)/', $quantityRaw, $m);
        $quantity = max(1, (int) ($m[1] ?? 1));

        $dropoff = $this->parseDetailDateTime($legacy['Drop-off DateTime'] ?? '');
        $pickup  = $this->parseDetailDateTime($legacy['Pickup DateTime'] ?? '');

        $insuranceSelected = stripos((string) ($legacy['Insurance Selected'] ?? ''), 'yes') !== false;

        $promoCode = (string) ($legacy['Promo Code'] ?? '');
        if (strcasecmp($promoCode, 'Null') === 0 || $promoCode === '') {
            $promoCode = (string) ($order['promo_code'] ?? '');
        }

        $data = [
            'service'           => $service,
            'quantity'          => $quantity,
            'dropoffDate'       => $dropoff['date'] ?? '',
            'dropoffTime'       => $dropoff['time'] ?? '',
            'pickupDate'        => $pickup['date'] ?? '',
            'pickupTime'        => $pickup['time'] ?? '',
            'insuranceSelected' => $insuranceSelected,
            'promoCode'         => $promoCode,
        ];

        if ($service === 'storage') {
            $data['storageLocation'] = $this->cleanLegacyValue($legacy['Storage Location'] ?? '');
        } else {
            $data['origin']      = $this->cleanLegacyValue($legacy['Origin Location'] ?? $legacy['Origin Address'] ?? '');
            $data['destination'] = $this->cleanLegacyValue($legacy['Destination Location'] ?? $legacy['Destination Address'] ?? '');
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $order
     * @param array<string, mixed> $bookingRow
     */
    public function dropoffIso(array $order, array $bookingRow = []): ?string
    {
        if (! empty($bookingRow['dropoff_at'])) {
            $ts = strtotime((string) $bookingRow['dropoff_at']);

            return $ts === false ? null : date('c', $ts);
        }

        $booking = $this->resolveBookingData($order, $bookingRow);
        if ($booking !== []) {
            $combined = trim(($booking['dropoffDate'] ?? '') . ' ' . ($booking['dropoffTime'] ?? ''));
            if ($combined !== '') {
                $ts = strtotime($combined);

                return $ts === false ? null : date('c', $ts);
            }
        }

        $legacy = json_decode((string) ($order['order_details_json'] ?? '{}'), true);
        if (is_array($legacy)) {
            return $this->parseOrderDetailDateTime($legacy['Drop-off DateTime'] ?? null);
        }

        return null;
    }

    /**
     * @param array<string, mixed> $order
     * @param array<string, mixed> $bookingRow
     */
    public function pickupIso(array $order, array $bookingRow = []): ?string
    {
        if (! empty($bookingRow['pickup_at'])) {
            $ts = strtotime((string) $bookingRow['pickup_at']);

            return $ts === false ? null : date('c', $ts);
        }

        $booking = $this->resolveBookingData($order, $bookingRow);
        if ($booking !== []) {
            $combined = trim(($booking['pickupDate'] ?? '') . ' ' . ($booking['pickupTime'] ?? ''));
            if ($combined !== '') {
                $ts = strtotime($combined);

                return $ts === false ? null : date('c', $ts);
            }
        }

        $legacy = json_decode((string) ($order['order_details_json'] ?? '{}'), true);
        if (is_array($legacy)) {
            return $this->parseOrderDetailDateTime($legacy['Pickup DateTime'] ?? null);
        }

        return null;
    }

    /**
     * @param array<string, mixed> $orders
     * @return array<int, array<string, mixed>>
     */
    public function mapBookingsByOrderId(array $orders): array
    {
        if ($orders === []) {
            return [];
        }

        $ids = array_values(array_unique(array_map(static fn ($o) => (int) ($o['order_id'] ?? 0), $orders)));
        $ids = array_filter($ids, static fn ($id) => $id > 0);
        if ($ids === []) {
            return [];
        }

        $db = \Config\Database::connect();
        if (! $db->tableExists('order_booking')) {
            return [];
        }

        $rows = $db->table('order_booking')
            ->whereIn('order_id', $ids)
            ->get()
            ->getResultArray();

        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row['order_id']] = $row;
        }

        return $map;
    }

    /**
     * @param array<string, mixed> $order
     * @param array<string, mixed> $bookingRow
     */
    public function enrichOrderRow(array $order, array $bookingRow = []): array
    {
        $display = $this->displayDetails($order, $bookingRow !== [] ? $bookingRow : null);

        $pickupRaw  = trim((string) ($display['Pickup DateTime'] ?? ''));
        $dropoffRaw = trim((string) ($display['Drop-off DateTime'] ?? ''));

        $from = trim((string) ($display['Origin Location'] ?? $display['Storage Location'] ?? ''));
        $to   = trim((string) ($display['Destination Location'] ?? ''));

        return $order + [
            'order_details_json'  => json_encode($display, JSON_UNESCAPED_SLASHES),
            '_pickup_time'        => $pickupRaw,
            '_dropoff_time'       => $dropoffRaw,
            '_pickup_location'    => $from,
            '_dropoff_location'   => $to,
            '_booking_dropoff_at' => $bookingRow['dropoff_at'] ?? null,
            '_booking_pickup_at'  => $bookingRow['pickup_at'] ?? null,
        ];
    }

    /**
     * @param array<string, mixed> $details
     */
    private function isRawBookingData(array $details): bool
    {
        return isset($details['service'])
            || isset($details['dropoffDate'])
            || isset($details['pickupDate'])
            || isset($details['storageLocation']);
    }

    private function combineDateTime(string $date, string $time): ?string
    {
        $date = trim($date);
        $time = trim($time);
        if ($date === '' || $time === '') {
            return null;
        }

        $ts = strtotime($date . ' ' . $time);

        return $ts === false ? null : date('Y-m-d H:i:s', $ts);
    }

    private function formatDateTimeLabel(string $date, string $time): string
    {
        if ($date === '' || $time === '') {
            return 'Null';
        }

        return $date . ' at ' . $time;
    }

    private function labelOrNull(mixed $value): string
    {
        $value = trim((string) ($value ?? ''));

        return $value !== '' ? $value : 'Null';
    }

    private function nullIfEmpty(mixed $value): ?string
    {
        $value = trim((string) ($value ?? ''));

        return $value !== '' ? $value : null;
    }

    private function cleanLegacyValue(mixed $value): string
    {
        $value = trim((string) $value);

        return strcasecmp($value, 'Null') === 0 ? '' : $value;
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

    private function parseOrderDetailDateTime(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = trim((string) $value);
        if (strcasecmp($value, 'Null') === 0) {
            return null;
        }

        if (preg_match('/^(.+?)\s+at\s+(.+)$/u', $value, $m)) {
            $combined = trim($m[1]) . ' ' . trim($m[2]);
        } else {
            $combined = $value;
        }

        $ts = strtotime($combined);

        return $ts === false ? null : date('c', $ts);
    }
}
