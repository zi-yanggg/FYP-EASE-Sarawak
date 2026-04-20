<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\MessageModel;

use TCPDF;

class Home extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $services = $db->table('service_management')->get()->getResultArray();

        // Set default prices in case table is empty
        $prices = [
            'storage' => 18,
            'delivery' => 24,
        ];
        foreach ($services as $service) {
            if (strtolower($service['service_type']) === 'storage') {
                $prices['storage'] = $service['base_price'];
            } elseif (strtolower($service['service_type']) === 'delivery') {
                $prices['delivery'] = $service['base_price'];
            }
        }

        return view('home', ['prices' => $prices]);
    }

    public function message()
    {
        $data = [
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'subject' => $this->request->getPost('subject'),
            'msg' => $this->request->getPost('message'),
            'status' => 'new',
            'created_date' => date('Y-m-d H:i:s'),
        ];

        $messageModel = new MessageModel();
        $messageModel->insert($data);

        return redirect()->to('/#contact')->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    public function about(): string
    {
        return view('about');
    }

    public function policy(): string
    {
        return view('policy');
    }

    public function tnc(): string
    {
        return view('tnc');
    }

    public function booking(): string
    {
        return view('booking');
    }

    public function bookingdetail(): string
    {
        $serviceModel = new \App\Models\ServiceManagementModel();
        $deliveryPrice = $serviceModel->where('service_type', 'delivery')->first()['base_price'] ?? 24;
        $storagePrice = $serviceModel->where('service_type', 'storage')->first()['base_price'] ?? 18;
        return view('bookingdetail', [
            'deliveryPrice' => $deliveryPrice,
            'storagePrice' => $storagePrice,
        ]);
    }

    public function bookingcustomerdetail(): string
    {
        return view('bookingcustomerdetail');
    }

    public function booking_confirmation(): string
    {
        $data = [
            'order_id' => $this->request->getGet('order_id')
        ];

        return view('booking_confirmation', $data);
    }

    public function payment()
    {
        // from POST get email
        $email = $this->request->getPost('email');

        return view('payment', [
            'receiptEmail' => $email,   // 传给 view
        ]);
    }

    public function saveOrder()
    {
        // Add CORS headers for debugging
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');

        log_message('info', 'saveOrder method called');

        // Load the OrderModel and process the entire order
        $orderModel = new \App\Models\OrderModel();
        $result = $orderModel->processAndSaveOrder($this->request);

        // Return the result directly from the model
        return $this->response->setJSON($result);
    }

    public function checkPromoCode()
    {
        $this->response->setContentType('application/json');

        try {
            $rawInput = $this->request->getBody();
            $jsonData = json_decode($rawInput, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->response->setJSON([
                    'success' => false,
                    'valid' => false,
                    'message' => 'Invalid request data'
                ]);
            }

            $promoCode = trim($jsonData['promo_code'] ?? '');

            if (empty($promoCode)) {
                return $this->response->setJSON([
                    'success' => false,
                    'valid' => false,
                    'message' => 'Please enter a promo code'
                ]);
            }

            $db = \Config\Database::connect();
            $builder = $db->table('promo_code');
            $promoData = $builder
                ->where('code', $promoCode)
                ->where('is_deleted', 0)
                ->get()
                ->getRowArray();

            if (!$promoData) {
                return $this->response->setJSON([
                    'success' => true,
                    'valid' => false,
                    'message' => 'Invalid promo code'
                ]);
            }

            $currentDateTime = date('Y-m-d H:i:s');

            // Check if promo code is active
            if ($promoData['validation_date'] > $currentDateTime) {
                return $this->response->setJSON([
                    'success' => true,
                    'valid' => false,
                    'message' => 'This promo code is not yet active'
                ]);
            }

            // Check if promo code is not expired
            if ($promoData['expired_date'] < $currentDateTime) {
                return $this->response->setJSON([
                    'success' => true,
                    'valid' => false,
                    'message' => 'This promo code has expired'
                ]);
            }

            // Determine discount type and value
            $discountType = $promoData['discount_type'];
            $discount = 0;
            if ($discountType === 'amount') {
                $discount = floatval($promoData['discount_amount']);
            } elseif ($discountType === 'percentage') {
                $discount = intval($promoData['discount_percentage']);
            }

            return $this->response->setJSON([
                'success' => true,
                'valid' => true,
                'discount' => $discount,
                'discount_type' => $discountType,
                'message' => 'Promo code applied successfully!'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Exception in checkPromoCode: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'valid' => false,
                'message' => 'Server error occurred'
            ]);
        }
    }
    
        public function submitRefund()
        {
            $db = \Config\Database::connect();

            $data = [
                'full_name'           => trim((string) $this->request->getPost('full_name')),
                'email'               => trim((string) $this->request->getPost('email')),
                'phone_number'        => trim((string) $this->request->getPost('phone_number')),
                'order_id'            => trim((string) $this->request->getPost('order_id')),
                'date_of_purchase'    => $this->request->getPost('date_of_purchase'),
                'service_type'        => trim((string) $this->request->getPost('service_type')),
                'bank_name'           => trim((string) $this->request->getPost('bank_name')),
                'account_holder_name' => trim((string) $this->request->getPost('account_holder_name')),
                'account_number'      => trim((string) $this->request->getPost('account_number')),
                'reason_for_refund'   => trim((string) $this->request->getPost('reason_for_refund')),
                'declaration'         => $this->request->getPost('declaration') ? 1 : 0,
            ];

            try {
                $refundTable = $db->table('refund_form');
                $insert      = $refundTable->insert($data);

                if (! $insert) {
                    return redirect()->to(base_url('/#refund-form'))
                        ->with('refund_status', 'error')
                        ->with('refund_message', 'Refund form submission failed. Please try again.')
                        ->with('refund_open', 1);
                }

                $refundId = $db->insertID();
                $refundRow = $refundTable->where('id', $refundId)->get()->getRowArray();
                $createdAt = $refundRow['created_at'] ?? date('Y-m-d H:i:s');

                $pdfDirectory = FCPATH . 'uploads/refunds/';
                if (! is_dir($pdfDirectory)) {
                    mkdir($pdfDirectory, 0777, true);
                }

                $fileName = 'refund_' . $refundId . '.pdf';
                $pdfFullPath     = $pdfDirectory . $fileName;
                $pdfRelativePath = 'uploads/refunds/' . $fileName;

                $pdf = new \TCPDF();
                $pdf->SetCreator('EASE Sarawak');
                $pdf->SetAuthor('EASE Sarawak');
                $pdf->SetTitle('Ease Sarawak|Refund Form #' . $refundId);
                $pdf->SetSubject('Refund Form Submission');
                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);
                $pdf->SetMargins(15, 15, 15);
                $pdf->SetAutoPageBreak(true, 15);
                $pdf->AddPage();
                $pdf->SetFont('helvetica', '', 11);

                $html = '
                    <h2 style="text-align:center;">EASE SARAWAK REFUND FORM</h2>
                    <table border="1" cellpadding="6">
                        <tr><td width="35%"><b>Refund ID</b></td><td width="65%">' . esc((string) $refundId) . '</td></tr>
                        <tr><td><b>Full Name</b></td><td>' . esc($data['full_name']) . '</td></tr>
                        <tr><td><b>Email</b></td><td>' . esc($data['email']) . '</td></tr>
                        <tr><td><b>Phone Number</b></td><td>' . esc($data['phone_number']) . '</td></tr>
                        <tr><td><b>Order ID</b></td><td>' . esc($data['order_id']) . '</td></tr>
                        <tr><td><b>Date of Purchase</b></td><td>' . esc((string) $data['date_of_purchase']) . '</td></tr>
                        <tr><td><b>Service Type</b></td><td>' . esc($data['service_type']) . '</td></tr>
                        <tr><td><b>Bank Name</b></td><td>' . esc($data['bank_name']) . '</td></tr>
                        <tr><td><b>Account Holder Name</b></td><td>' . esc($data['account_holder_name']) . '</td></tr>
                        <tr><td><b>Account Number</b></td><td>' . esc($data['account_number']) . '</td></tr>
                        <tr><td><b>Reason for Refund</b></td><td>' . nl2br(esc($data['reason_for_refund'])) . '</td></tr>
                        <tr><td><b>Declaration</b></td><td>' . ($data['declaration'] ? 'Agreed' : 'Not agreed') . '</td></tr>
                        <tr><td><b>Created At</b></td><td>' . esc($createdAt) . '</td></tr>
                    </table>
                ';

                $pdf->writeHTML($html, true, false, true, false, '');
                $pdf->Output($pdfFullPath, 'F');

                $pdfRelativePath = 'uploads/refunds/' . $fileName;
                $viewUrl = base_url('refund/view/' . $refundId);

                $refundTable->where('id', $refundId)->update([
                    'pdf_path' => $viewUrl
                ]);

                return redirect()->to(base_url('/#refund-form'))
                    ->with('refund_status', 'success')
                    ->with('refund_message', 'Refund form submitted successfully.')
                    ->with('refund_open', 1);

            } catch (\Throwable $e) {
                log_message('error', 'Refund submit error: ' . $e->getMessage());

                return redirect()->to(base_url('/#refund-form'))
                    ->with('refund_status', 'error')
                    ->with('refund_message', 'Submission failed: ' . $e->getMessage())
                    ->with('refund_open', 1);
            }
        }

        public function viewRefundPdf($refundId)
        {
            $db = \Config\Database::connect();

            $refund = $db->table('refund_form')
                ->where('id', $refundId)
                ->get()
                ->getRowArray();

            if (! $refund) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Refund record not found.');
            }

            $pdfFile = FCPATH . 'uploads/refunds/refund_' . $refundId . '.pdf';

            if (! file_exists($pdfFile)) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('PDF file not found.');
            }

            $pdfUrl = base_url('uploads/refunds/refund_' . $refundId . '.pdf');

            return view('refund-view', [
                'refundId' => $refundId,
                'pdfUrl'   => $pdfUrl,
            ]);
        }
}
