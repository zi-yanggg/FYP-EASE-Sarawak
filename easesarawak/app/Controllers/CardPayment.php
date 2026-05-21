<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PaymentModel;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;

class CardPayment extends BaseController
{
    protected $payments;

    public function __construct()
    {
        $this->payments = new PaymentModel();
    }

    protected function initStripe(): void
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    /**
     * POST /card-payment/intent
     */
    public function createIntent()
    {
        $body = $this->request->getJSON(true) ?? $this->request->getPost();
        $amount = intval($body['amount'] ?? 0);    // sen / cents

        if ($amount <= 0) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['error' => 'Invalid amount']);
        }

        $currency = strtolower($body['currency'] ?? 'myr');
        $metadata = $body['metadata'] ?? [];
        $receiptEmail = trim((string)($body['receipt_email'] ?? ''));

        $this->initStripe();

        try {
        $paymentIntentData = [
            'amount'   => $amount,
            'currency' => $currency,
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => $metadata,
        ];

        if (filter_var($receiptEmail, FILTER_VALIDATE_EMAIL)) {
            $paymentIntentData['receipt_email'] = $receiptEmail;
        }

        $pi = PaymentIntent::create($paymentIntentData);

            return $this->response->setJSON([
                'client_secret' => $pi->client_secret,
                'payment_intent_id' => $pi->id,
            ]);
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
     *  Stripe take PaymentIntent field，write in payments database
     */
   public function store()
{
    //  payment_intent_id take from payment
    $body = $this->request->getJSON(true) ?? [];
    $piId = $body['payment_intent_id'] ?? null;

    if (!$piId) {
        return $this->response
            ->setStatusCode(422)
            ->setJSON(['error' => 'Missing payment_intent_id']);
    }

    // Stripe
    $this->initStripe();

    try {
        // Check the PaymentIntent again from Stripe to get the amount, currency, status, and charge ID.
        $pi = PaymentIntent::retrieve($piId);

        //Retrieve the charge ID (in some scenarios, latest_charge is a string, and in others it is an object).
        $chargeId = null;
        if (!empty($pi->latest_charge)) {
            $chargeId = is_string($pi->latest_charge)
                ? $pi->latest_charge
                : ($pi->latest_charge->id ?? null);
        }

        // Idempotency: return early if this PaymentIntent was already recorded
        if ($this->payments->where('payment_intent_id', $pi->id)->first()) {
            return $this->response->setJSON(['ok' => true]);
        }

        $data = [
            'stripe_payment_id' => $chargeId,
            'payment_intent_id' => $pi->id,
            'amount_cents'      => $pi->amount_received ?? $pi->amount ?? 0,
            'currency'          => $pi->currency ?? 'myr',
            'status'            => $pi->status ?? 'unknown',
        ];

        if (! $this->payments->insert($data)) {
            // If model validation fails or database insertion fails, an error array will be returned here.
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'error'   => 'DB insert failed',
                    'details' => $this->payments->errors(),
                ]);
        }

        return $this->response->setJSON(['ok' => true]);

    } catch (\Throwable $e) {
            log_message('error', 'Stripe store error: {msg}', ['msg' => $e->getMessage()]);
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['error' => 'Unable to record payment. Please contact support.']);
        }
    }
        /** Stripe Webhook  */
    public function webhook()
    {
        // Read the original body and Stripe signature.
        $payload = file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        // Copy the line `whsec_xxx` from the `stripe listen` output.
        $endpointSecret = getenv('STRIPE_WEBHOOK_SECRET'); 

        // Verify the signature and construct the Event object.
        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            // Payload 
            log_message('error', 'Stripe webhook invalid payload: {msg}', ['msg' => $e->getMessage()]);
            return $this->response->setStatusCode(400)->setBody('Invalid payload');
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Signature error
            log_message('error', 'Stripe webhook invalid signature: {msg}', ['msg' => $e->getMessage()]);
            return $this->response->setStatusCode(400)->setBody('Invalid signature');
        }

        // Handle according to event type
        switch ($event->type) {
            // Payment successful
            case 'payment_intent.succeeded':
                $pi = $event->data->object; // \Stripe\PaymentIntent

                // Try to get the ID of the first charge (if there is no charge, use null).
                $stripePaymentId = null;
                if (isset($pi->charges->data[0])) {
                    $stripePaymentId = $pi->charges->data[0]->id;
                }

                // update the payments table
                $this->payments->save([
                    'payment_intent_id' => $pi->id,
                    'stripe_payment_id' => $stripePaymentId,
                    'amount_cents'      => $pi->amount_received, 
                    'currency'          => $pi->currency,
                    'status'            => $pi->status,          // succeeded
                ]);

                log_message('info', 'Stripe webhook handled payment_intent.succeeded: {id}', ['id' => $pi->id]);
                break;

            // Other scenarios such as failure/cancellation can be added as needed.
            case 'payment_intent.payment_failed':
                $pi = $event->data->object;

                $this->payments->save([
                    'payment_intent_id' => $pi->id,
                    'amount_cents'      => $pi->amount,
                    'currency'          => $pi->currency,
                    'status'            => $pi->status, // payment_failed
                ]);

                log_message('info', 'Stripe webhook handled payment_intent.payment_failed: {id}', ['id' => $pi->id]);
                break;

            default:
                // Other events will only be logged for now.
                log_message('info', 'Stripe webhook event received: {type}', ['type' => $event->type]);
        }

        // Return 200 to Stripe to tell them "Received".
        return $this->response->setStatusCode(200)->setBody('OK');
    }

}
