<?php

namespace App\Listeners;

use App\Models\OrderModel;

class OrderPaidListener
{
    public static function handle(int $orderId, string $paymentIntentId): void
    {
        log_message('info', 'Order {id} paid via {pi}', [
            'id' => $orderId,
            'pi' => $paymentIntentId,
        ]);

        $orderModel = new OrderModel();
        $order      = $orderModel->find($orderId);

        if (! $order || empty($order['email'])) {
            return;
        }

        $notification = new \App\Services\NotificationService();
        $amountCents  = (int) round(((float) ($order['amount'] ?? 0)) * 100);

        $notification->sendReceipt(
            $order['email'],
            $amountCents,
            'myr',
            'succeeded',
            $paymentIntentId,
            (string) $orderId
        );
    }
}
