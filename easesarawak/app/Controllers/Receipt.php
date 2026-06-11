<?php

namespace App\Controllers;

use App\Services\NotificationService;
use App\Services\PaymentService;
use App\Services\PricingService;
use CodeIgniter\Controller;

class Receipt extends Controller
{
    /**
     * POST /send-receipt
     */
    public function send()
    {
        $email           = $this->request->getPost('email');
        $paymentIntentId = $this->request->getPost('payment_intent_id') ?? '';
        $orderId         = (int) ($this->request->getPost('order_id') ?? 0);

        if (empty($email)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['error' => 'Missing email']);
        }

        $amountCents = (int) $this->request->getPost('amount_cents');
        $currency    = $this->request->getPost('currency') ?: 'myr';
        $status      = $this->request->getPost('status') ?: 'succeeded';

        if ($orderId > 0) {
            try {
                $pricing     = (new PricingService())->calculateFromOrderId($orderId);
                $amountCents = $pricing['total_cents'];
            } catch (\Throwable $e) {
                log_message('warning', 'Receipt: could not verify order amount: {msg}', ['msg' => $e->getMessage()]);
            }
        }

        if ($paymentIntentId !== '') {
            try {
                (new PaymentService())->initStripe();
                $pi = \Stripe\PaymentIntent::retrieve($paymentIntentId);
                if (($pi->status ?? '') === 'succeeded') {
                    $amountCents = (int) ($pi->amount_received ?? $amountCents);
                }
            } catch (\Throwable $e) {
                log_message('warning', 'Receipt: could not verify payment intent: {msg}', ['msg' => $e->getMessage()]);
            }
        }

        $notification = new NotificationService();
        $sent         = $notification->sendReceipt(
            $email,
            $amountCents,
            $currency,
            $status,
            $paymentIntentId,
            $orderId > 0 ? (string) $orderId : ''
        );

        if (! $sent) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['error' => 'Failed to send email']);
        }

        return $this->response->setJSON(['ok' => true]);
    }
}
