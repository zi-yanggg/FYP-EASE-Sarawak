<?php

namespace App\Services;

use Config\Services;

class NotificationService
{
    /**
     * Send payment receipt email.
     */
    public function sendReceipt(
        string $email,
        int $amountCents,
        string $currency = 'myr',
        string $status = 'succeeded',
        string $paymentIntentId = '',
        string $orderId = ''
    ): bool {
        if ($email === '') {
            return false;
        }

        $emailService  = Services::email();
        $amountMajor   = $amountCents / 100;
        $amountDisplay = number_format($amountMajor, 2);
        $logoPath      = FCPATH . 'assets/images/Ease_PNG_File-01.png';
        $logoHtml      = '';

        if (file_exists($logoPath)) {
            $emailService->attach($logoPath, 'inline');
            $logoCid = $emailService->setAttachmentCID($logoPath);

            if ($logoCid) {
                $logoHtml = "
                    <div style='text-align:left; margin-bottom:20px;'>
                        <img src='cid:{$logoCid}'
                            alt='EASE Sarawak Logo'
                            style='width:180px; height:auto; display:block; margin:0;'>
                    </div>
                ";
            }
        }

        $message = "
            <div style='font-family: Arial, sans-serif; color:#222; max-width:600px;'>
                {$logoHtml}
                <h2 style='color:#000;'>EASE Sarawak Payment Receipt</h2>
                <p>Thank you for your payment.</p>
        ";

        if ($orderId !== '') {
            $message .= '<p><strong>Order ID:</strong> #' . esc($orderId) . '</p>';
        }

        $message .= "
                <p><strong>Amount:</strong> " . strtoupper($currency) . " {$amountDisplay}</p>
                <p><strong>Status:</strong> {$status}</p>
        ";

        if ($paymentIntentId !== '') {
            $message .= "<p><strong>Payment Intent ID:</strong> {$paymentIntentId}</p>";
        }

        $message .= "
                <p>If you have any questions, please contact our support.</p>
                <hr>
                <p style='font-size:12px; color:#777;'>EASE Baggage Storage & Delivery</p>
            </div>
        ";

        $emailService->setTo($email);
        $emailService->setSubject('EASE Sarawak Payment Receipt');
        $emailService->setMessage($message);
        $emailService->setMailType('html');

        if (! $emailService->send()) {
            $debug = $emailService->printDebugger(['headers', 'subject', 'body']);
            log_message('error', 'Failed to send receipt email: {debug}', ['debug' => $debug]);

            return false;
        }

        return true;
    }
}
