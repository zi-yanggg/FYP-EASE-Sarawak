<?php

namespace App\Services;

use App\Models\OrderModel;
use CodeIgniter\HTTP\IncomingRequest;

class BookingService
{
    public function __construct(
        private ?OrderModel $orderModel = null,
        private ?PricingService $pricingService = null,
    ) {
        $this->orderModel     = $this->orderModel ?? new OrderModel();
        $this->pricingService = $this->pricingService ?? new PricingService();
    }

    /**
     * Process booking submission with server-side price validation.
     *
     * @return array<string, mixed>
     */
    public function saveOrder(IncomingRequest $request): array
    {
        $result = $this->orderModel->processAndSaveOrder($request);

        if (! ($result['success'] ?? false) || empty($result['order_id'])) {
            return $result;
        }

        $orderId = (int) $result['order_id'];

        try {
            $bookingDataJson = $request->getPost('bookingData');
            $bookingData     = json_decode($bookingDataJson ?? '', true);

            if (is_array($bookingData)) {
                $pricing = $this->pricingService->calculateFromBookingData($bookingData);
                $this->orderModel->update($orderId, [
                    'amount' => (int) round($pricing['total_rm']),
                ]);
                $result['validated_amount'] = $pricing['total_rm'];
            }
        } catch (\Throwable $e) {
            log_message('warning', 'BookingService: price validation failed for order {id}: {msg}', [
                'id'  => $orderId,
                'msg' => $e->getMessage(),
            ]);
        }

        return $result;
    }
}
