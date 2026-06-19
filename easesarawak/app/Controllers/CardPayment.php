<?php

namespace App\Controllers;

use App\Models\PaymentModel;
use App\Services\PaymentService;
use Stripe\Webhook;

class CardPayment extends BaseController
{
    protected PaymentService $paymentService;
    protected PaymentModel $payments;

    public function __construct()
    {
        $this->paymentService = new PaymentService();
        $this->payments       = new PaymentModel();
    }

    /**
     * POST /card-payment/intent
     * Body: { order_id: 123, receipt_email?: "...", metadata?: {} }
     */
    public function createIntent()
    {
        $body    = $this->request->getJSON(true) ?? $this->request->getPost();
        $orderId = (int) ($body['order_id'] ?? 0);

        if ($orderId <= 0) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['error' => 'Missing or invalid order_id']);
        }

        $receiptEmail = trim((string) ($body['receipt_email'] ?? ''));
        $metadata     = is_array($body['metadata'] ?? null) ? $body['metadata'] : [];

        if ($receiptEmail !== '') {
            if (! filter_var($receiptEmail, FILTER_VALIDATE_EMAIL)) {
                return $this->response
                    ->setStatusCode(422)
                    ->setJSON(['error' => 'Invalid receipt email address']);
            }

            $domain = substr(strrchr($receiptEmail, '@'), 1);
            if (! checkdnsrr($domain, 'MX') && ! checkdnsrr($domain, 'A')) {
                return $this->response
                    ->setStatusCode(422)
                    ->setJSON(['error' => 'Receipt email domain does not exist']);
            }
        }

        try {
            $result = $this->paymentService->createIntentForOrder($orderId, $receiptEmail, $metadata);

            return $this->response->setJSON($result);
        } catch (\InvalidArgumentException $e) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['error' => $e->getMessage()]);
        } catch (\Throwable $e) {
            log_message('error', 'Stripe createIntent error: {msg}', ['msg' => $e->getMessage()]);

            return $this->response
                ->setStatusCode(500)
                ->setJSON(['error' => 'Unable to create payment intent.']);
        }
    }

    /**
     * POST /card-payment/store
     * Body: { payment_intent_id: "pi_xxx" }
     */
    public function store()
    {
        $body = $this->request->getJSON(true) ?? [];
        $piId = $body['payment_intent_id'] ?? null;

        if (! $piId) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['error' => 'Missing payment_intent_id']);
        }

        $this->paymentService->initStripe();

        try {
            $pi = \Stripe\PaymentIntent::retrieve($piId);

            if (! $this->paymentService->recordPaymentIntent($pi)) {
                return $this->response
                    ->setStatusCode(500)
                    ->setJSON([
                        'error'   => 'DB insert failed',
                        'details' => $this->payments->errors(),
                    ]);
            }

            $orderId = (int) ($pi->metadata->order_id ?? 0);
            if ($orderId > 0 && ($pi->status ?? '') === 'succeeded') {
                $this->paymentService->markOrderPaid($orderId, $pi->id, false);
            }

            return $this->response->setJSON(['ok' => true]);
        } catch (\Throwable $e) {
            log_message('error', 'Stripe store error: {msg}', ['msg' => $e->getMessage()]);

            return $this->response
                ->setStatusCode(500)
                ->setJSON(['error' => 'Payment storage failed.']);
        }
    }

    /** Stripe Webhook */
    public function webhook()
    {
        $payload    = file_get_contents('php://input');
        $sigHeader  = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        $endpointSecret = getenv('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            log_message('error', 'Stripe webhook invalid payload: {msg}', ['msg' => $e->getMessage()]);

            return $this->response->setStatusCode(400)->setBody('Invalid payload');
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            log_message('error', 'Stripe webhook invalid signature: {msg}', ['msg' => $e->getMessage()]);

            return $this->response->setStatusCode(400)->setBody('Invalid signature');
        }

        $this->paymentService->handleWebhookEvent($event);

        return $this->response->setStatusCode(200)->setBody('OK');
    }
}
