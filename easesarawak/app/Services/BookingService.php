<?php

namespace App\Services;

use App\Models\OrderModel;
use CodeIgniter\HTTP\IncomingRequest;

class BookingService
{
    public function __construct(
        private ?OrderModel $orderModel = null,
        private ?PromoService $promoService = null,
        private ?NotificationService $notificationService = null,
    ) {
        $this->orderModel           = $this->orderModel ?? new OrderModel();
        $this->promoService         = $this->promoService ?? new PromoService();
        $this->notificationService  = $this->notificationService ?? new NotificationService();
    }

    /**
     * Process booking submission with server-side price validation in one transaction.
     *
     * @return array<string, mixed>
     */
    public function saveOrder(IncomingRequest $request): array
    {
        $result = $this->orderModel->processAndSaveOrder($request);

        if ($result['success'] ?? false) {
            $bookingData = json_decode((string) $request->getPost('bookingData'), true) ?? [];

            $promoCode = trim((string) ($bookingData['promoCode'] ?? ''));
            if ($promoCode !== '') {
                $this->promoService->consume($promoCode);
            }

            $email        = trim((string) $request->getPost('email'));
            $firstName    = trim((string) $request->getPost('firstName'));
            $lastName     = trim((string) $request->getPost('lastName'));
            $customerName = trim($firstName . ' ' . $lastName) ?: 'Valued Customer';

            if ($email !== '') {
                $this->notificationService->sendBookingConfirmation(
                    $email,
                    $customerName,
                    (int) $result['order_id'],
                    $bookingData
                );
            }
        }

        return $result;
    }
}
