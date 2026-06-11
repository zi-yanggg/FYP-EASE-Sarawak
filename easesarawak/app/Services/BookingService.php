<?php

namespace App\Services;

use App\Models\OrderModel;
use CodeIgniter\HTTP\IncomingRequest;

class BookingService
{
    public function __construct(
        private ?OrderModel $orderModel = null,
    ) {
        $this->orderModel = $this->orderModel ?? new OrderModel();
    }

    /**
     * Process booking submission with server-side price validation in one transaction.
     *
     * @return array<string, mixed>
     */
    public function saveOrder(IncomingRequest $request): array
    {
        return $this->orderModel->processAndSaveOrder($request);
    }
}
