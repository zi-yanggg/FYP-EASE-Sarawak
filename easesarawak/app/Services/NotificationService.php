<?php

namespace App\Services;

use Config\Services;

class NotificationService
{
    /**
     * Send booking-received confirmation to the customer.
     *
     * @param array<string, mixed> $bookingData  Raw wizard payload from the POST
     */
    public function sendBookingConfirmation(
        string $email,
        string $customerName,
        int    $orderId,
        array  $bookingData
    ): bool {
        if ($email === '') {
            return false;
        }

        $service      = strtolower((string) ($bookingData['service'] ?? ''));
        $serviceLabel = $service === 'storage' ? 'Luggage Storage' : 'In-Town Delivery';
        $quantity     = (int) ($bookingData['quantity'] ?? 1);
        $totalPrice   = number_format((float) ($bookingData['totalPrice'] ?? 0), 2);
        $dropoff      = trim(($bookingData['dropoffDate'] ?? '') . ' ' . ($bookingData['dropoffTime'] ?? ''));
        $pickup       = trim(($bookingData['pickupDate'] ?? '') . ' ' . ($bookingData['pickupTime'] ?? ''));

        if ($service === 'delivery') {
            $locationLine = 'From: ' . esc($bookingData['origin'] ?? '—')
                . ' → To: ' . esc($bookingData['destination'] ?? '—');
        } else {
            $locationLine = 'Storage: ' . esc($bookingData['storageLocation'] ?? '—');
        }

        $emailService = Services::email();
        $logoPath     = FCPATH . 'assets/images/Ease_PNG_File-01.png';
        $logoHtml     = '';

        if (file_exists($logoPath)) {
            $emailService->attach($logoPath, 'inline');
            $logoCid = $emailService->setAttachmentCID($logoPath);
            if ($logoCid) {
                $logoHtml = "<div style='margin-bottom:20px;'>"
                    . "<img src='cid:{$logoCid}' alt='EASE Sarawak' style='width:180px;height:auto;display:block;'>"
                    . "</div>";
            }
        }

        $message = "
            <div style='font-family:Arial,sans-serif;color:#222;max-width:600px;'>
                {$logoHtml}
                <h2 style='color:#000;'>Booking Confirmed – Order #{$orderId}</h2>
                <p>Hi " . esc($customerName) . ",</p>
                <p>We have received your booking. Please complete payment to finalise it.</p>
                <table style='border-collapse:collapse;width:100%;margin:1em 0;'>
                    <tr><td style='padding:6px 0;color:#555;'>Service</td>
                        <td style='padding:6px 0;font-weight:bold;'>{$serviceLabel}</td></tr>
                    <tr><td style='padding:6px 0;color:#555;'>Location</td>
                        <td style='padding:6px 0;font-weight:bold;'>{$locationLine}</td></tr>
                    <tr><td style='padding:6px 0;color:#555;'>Quantity</td>
                        <td style='padding:6px 0;font-weight:bold;'>{$quantity} item(s)</td></tr>
                    <tr><td style='padding:6px 0;color:#555;'>Drop-off</td>
                        <td style='padding:6px 0;font-weight:bold;'>" . esc($dropoff) . "</td></tr>
                    <tr><td style='padding:6px 0;color:#555;'>Pick-up</td>
                        <td style='padding:6px 0;font-weight:bold;'>" . esc($pickup) . "</td></tr>
                    <tr><td style='padding:6px 0;color:#555;'>Total</td>
                        <td style='padding:6px 0;font-weight:bold;color:#B8860B;'>MYR {$totalPrice}</td></tr>
                </table>
                <p>If you have any questions, please contact our support.</p>
                <hr>
                <p style='font-size:12px;color:#777;'>EASE Baggage Storage &amp; Delivery</p>
            </div>
        ";

        $emailService->setTo($email);
        $emailService->setSubject("EASE Sarawak – Booking Received #$orderId");
        $emailService->setMessage($message);
        $emailService->setMailType('html');

        if (! $emailService->send()) {
            log_message('error', 'Failed to send booking confirmation email for order {id}: {debug}', [
                'id'    => $orderId,
                'debug' => $emailService->printDebugger(['headers', 'subject']),
            ]);
            return false;
        }

        return true;
    }

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
