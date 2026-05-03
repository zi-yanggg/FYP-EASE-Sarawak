<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class Receipt extends Controller
{
    /**
* POST /send-receipt

* Parameters:

* - email

* - amount_cents (integer, in cents, e.g., 7000 = RM70.00)

* - currency (myr)

* - status (succeeded, etc.)

* - payment_intent_id (optional)
     */
    public function send()
    {
        $email           = $this->request->getPost('email');
        $amountCents     = (int) $this->request->getPost('amount_cents');
        $currency        = $this->request->getPost('currency') ?: 'myr';
        $status          = $this->request->getPost('status') ?: 'succeeded';
        $paymentIntentId = $this->request->getPost('payment_intent_id') ?? '';
        $orderId = $this->request->getPost('order_id') ?? '';

        if (empty($email)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['error' => 'Missing email']);
        }

        $amountMajor   = $amountCents / 100;
        $amountDisplay = number_format($amountMajor, 2);

        $message = "
            <h2>EASE Sarawak Payment Receipt</h2>
            <p>Thank you for your payment.</p>
        ";

        if ($orderId) {
            $message .= "<p><strong>Order ID:</strong> #" . esc($orderId) . "</p>";
        }

        $message .= "
            <p><strong>Amount:</strong> " . strtoupper($currency) . " {$amountDisplay}</p>
            <p><strong>Status:</strong> {$status}</p>
        ";

        if ($paymentIntentId) {
            $message .= "<p><strong>Payment Intent ID:</strong> {$paymentIntentId}</p>";
        }

        $message .= "<p>If you have any questions, please contact our support.</p>";

        $emailService = Services::email();
        $emailService->setTo($email);
        $emailService->setSubject('EASE Sarawak Payment Receipt');
        $emailService->setMessage($message);
        $emailService->setMailType('html');

        if (! $emailService->send()) {
            $debug = $emailService->printDebugger(['headers', 'subject', 'body']);
            log_message('error', 'Failed to send receipt email: {debug}', ['debug' => $debug]);

            return $this->response
                ->setStatusCode(500)
                ->setJSON(['error' => 'Failed to send email']);
        }

        return $this->response->setJSON(['ok' => true]);
    }
}
