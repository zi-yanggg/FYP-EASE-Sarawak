<?php

namespace App\Services;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentService
{
    public function __construct(
        private ?PaymentModel $paymentModel = null,
        private ?OrderModel $orderModel = null,
        private ?PricingService $pricingService = null,
    ) {
        $this->paymentModel   = $this->paymentModel ?? new PaymentModel();
        $this->orderModel     = $this->orderModel ?? new OrderModel();
        $this->pricingService = $this->pricingService ?? new PricingService();
    }

    public function initStripe(): void
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    /**
     * Create a PaymentIntent using server-side pricing for the given order.
     *
     * @return array{client_secret: string, payment_intent_id: string, amount_cents: int}
     */
    public function createIntentForOrder(int $orderId, string $receiptEmail = '', array $metadata = []): array
    {
        $pricing = $this->pricingService->calculateFromOrderId($orderId);

        if ($pricing['total_cents'] <= 0) {
            throw new \InvalidArgumentException('Invalid order amount.');
        }

        $this->initStripe();

        $paymentIntentData = [
            'amount'                    => $pricing['total_cents'],
            'currency'                  => 'myr',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata'                  => array_merge($metadata, [
                'order_id' => (string) $orderId,
            ]),
        ];

        if (filter_var($receiptEmail, FILTER_VALIDATE_EMAIL)) {
            $paymentIntentData['receipt_email'] = $receiptEmail;
        }

        $pi = PaymentIntent::create($paymentIntentData);

        return [
            'client_secret'     => $pi->client_secret,
            'payment_intent_id' => $pi->id,
            'amount_cents'      => $pricing['total_cents'],
        ];
    }

    /**
     * Idempotent store/update of payment record from Stripe PaymentIntent.
     */
    public function recordPaymentIntent(object $pi): bool
    {
        $chargeId = null;
        if (! empty($pi->latest_charge)) {
            $chargeId = is_string($pi->latest_charge)
                ? $pi->latest_charge
                : ($pi->latest_charge->id ?? null);
        } elseif (isset($pi->charges->data[0])) {
            $chargeId = $pi->charges->data[0]->id;
        }

        $orderId = null;
        if (! empty($pi->metadata->order_id)) {
            $orderId = (int) $pi->metadata->order_id;
        }

        $data = [
            'payment_intent_id' => $pi->id,
            'stripe_payment_id' => $chargeId,
            'amount_cents'      => $pi->amount_received ?? $pi->amount ?? 0,
            'currency'          => $pi->currency ?? 'myr',
            'status'            => $pi->status ?? 'unknown',
        ];

        if ($orderId > 0) {
            $data['order_id'] = $orderId;
        }

        $existing = $this->paymentModel->find($pi->id);

        if ($existing) {
            return (bool) $this->paymentModel->update($pi->id, $data);
        }

        return (bool) $this->paymentModel->insert($data);
    }

    /**
     * Mark order as paid when payment succeeds.
     */
    public function markOrderPaid(int $orderId, string $paymentIntentId, bool $notify = false): void
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $this->orderModel->update($orderId, [
            'payment_method' => 'stripe',
            'modified_date'  => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            log_message('error', 'Failed to mark order {id} paid for PI {pi}', [
                'id' => $orderId,
                'pi' => $paymentIntentId,
            ]);

            return;
        }

        if ($notify) {
            \CodeIgniter\Events\Events::trigger('order.paid', $orderId, $paymentIntentId);
        }
    }

    public function handleWebhookEvent(object $event): void
    {
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $pi = $event->data->object;
                $this->recordPaymentIntent($pi);

                $orderId = (int) ($pi->metadata->order_id ?? 0);
                if ($orderId > 0) {
                    $this->markOrderPaid($orderId, $pi->id, true);
                }

                log_message('info', 'Stripe webhook handled payment_intent.succeeded: {id}', ['id' => $pi->id]);
                break;

            case 'payment_intent.payment_failed':
                $pi = $event->data->object;
                $this->recordPaymentIntent($pi);
                log_message('info', 'Stripe webhook handled payment_intent.payment_failed: {id}', ['id' => $pi->id]);
                break;

            default:
                log_message('info', 'Stripe webhook event received: {type}', ['type' => $event->type]);
        }
    }
}
