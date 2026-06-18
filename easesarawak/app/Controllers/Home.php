<?php

namespace App\Controllers;

use App\Models\MessageModel;
use App\Services\BookingService;
use App\Services\PromoService;
use App\Services\RefundService;

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
        $rules = [
            'email'   => 'required|valid_email|max_length[255]',
            'phone'   => 'required|regex_match[/^[+0-9][0-9\s\-()]{6,19}$/]|max_length[20]',
            'subject' => 'required|max_length[200]',
            'message' => 'required|max_length[2000]',
        ];
        $messages = [
            'phone' => ['regex_match' => 'Please enter a valid phone number.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->to('/#contact')
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data = [
            'email'        => $this->request->getPost('email'),
            'phone'        => $this->request->getPost('phone'),
            'subject'      => strip_tags($this->request->getPost('subject')),
            'msg'          => strip_tags($this->request->getPost('message')),
            'status'       => 'new',
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

        $deliveryService = $serviceModel->where('service_type', 'delivery')->first() ?? [];
        $storageService = $serviceModel->where('service_type', 'storage')->first() ?? [];

        $deliveryPrice = (int)($deliveryService['base_price'] ?? 24);
        $storagePrice = (int)($storageService['base_price'] ?? 18);
        $deliveryExtraRate = (int)($deliveryService['extra_rate'] ?? 6);
        $storageExtraRate = (int)($storageService['extra_rate'] ?? 6);

        return view('bookingdetail', [
            'deliveryPrice' => $deliveryPrice,
            'storagePrice' => $storagePrice,
            'deliveryExtraRate' => $deliveryExtraRate,
            'storageExtraRate' => $storageExtraRate,
        ]);
    }

    public function bookingcustomerdetail(): string
    {
        $serviceModel = new \App\Models\ServiceManagementModel();

        $deliveryService = $serviceModel->where('service_type', 'delivery')->first() ?? [];
        $storageService = $serviceModel->where('service_type', 'storage')->first() ?? [];

        return view('bookingcustomerdetail', [
            'deliveryPrice' => (int)($deliveryService['base_price'] ?? 24),
            'storagePrice' => (int)($storageService['base_price'] ?? 18),
            'deliveryExtraRate' => (int)($deliveryService['extra_rate'] ?? 6),
            'storageExtraRate' => (int)($storageService['extra_rate'] ?? 6),
        ]);
    }

    public function booking_confirmation(): string
    {
        // Consume the one-time session value written by saveOrder().
        // Ignoring ?order_id= from GET prevents IDOR enumeration.
        $orderId = session()->getTempdata('confirmed_order_id');

        return view('booking_confirmation', ['order_id' => $orderId ?: null]);
    }

    public function payment()
    {
        $email = trim((string) $this->request->getPost('email'));

        if ($email !== '' && ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Invalid email address provided.');
        }

        $serviceModel = new \App\Models\ServiceManagementModel();
        $deliveryService = $serviceModel->where('service_type', 'delivery')->first() ?? [];
        $storageService = $serviceModel->where('service_type', 'storage')->first() ?? [];

        return view('payment', [
            'receiptEmail' => $email, // 传给 view
            'deliveryExtraRate' => (int)($deliveryService['extra_rate'] ?? 6),
            'storageExtraRate' => (int)($storageService['extra_rate'] ?? 6),
        ]);
    }

    public function saveOrder()
    {
        log_message('info', 'saveOrder method called');

        $bookingService = new BookingService();
        $result         = $bookingService->saveOrder($this->request);

        if ($result['success'] ?? false) {
            // Bind the confirmed order ID to the session so booking_confirmation
            // can display it without exposing it via a guessable GET parameter.
            session()->setTempdata('confirmed_order_id', (int) $result['order_id'], 1800);
        }

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
                    'valid'   => false,
                    'message' => 'Invalid request data',
                ]);
            }

            $promoService = new PromoService();
            $result       = $promoService->validate(trim($jsonData['promo_code'] ?? ''));

            if (! $result['valid'] && ($jsonData['promo_code'] ?? '') === '') {
                return $this->response->setJSON([
                    'success' => false,
                    'valid'   => false,
                    'message' => $result['message'],
                ]);
            }

            return $this->response->setJSON($promoService->toApiResponse($result));
        } catch (\Exception $e) {
            log_message('error', 'Exception in checkPromoCode: ' . $e->getMessage());

            return $this->response->setJSON([
                'success' => false,
                'valid'   => false,
                'message' => 'Server error occurred',
            ]);
        }
    }
    
    public function submitRefund()
    {
        $rules = [
            'full_name'           => 'required|max_length[100]',
            'email'               => 'required|valid_email|max_length[255]',
            'phone_number'        => 'required|regex_match[/^[+0-9][0-9\s\-()]{6,19}$/]|max_length[20]',
            'order_id'            => 'required|max_length[50]',
            'date_of_purchase'    => 'required',
            'service_type'        => 'required|in_list[Town Delivery,Luggage Storage]',
            'bank_name'           => 'permit_empty|max_length[100]',
            'account_holder_name' => 'permit_empty|max_length[100]',
            'account_number'      => 'permit_empty|regex_match[/^\d{5,20}$/]',
            'reason_for_refund'   => 'permit_empty|max_length[1000]',
            'declaration'         => 'required',
        ];
        $messages = [
            'phone_number'   => ['regex_match' => 'Please enter a valid phone number.'],
            'account_number' => ['regex_match' => 'Account number must contain only digits (5–20 digits).'],
            'declaration'    => ['required'    => 'You must agree to the declaration before submitting.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->to(base_url('/#refund-form'))
                ->with('refund_status', 'error')
                ->with('refund_message', implode(' ', $this->validator->getErrors()))
                ->with('refund_open', 1);
        }

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
            'reason_for_refund'   => strip_tags(trim((string) $this->request->getPost('reason_for_refund'))),
            'declaration'         => $this->request->getPost('declaration') ? 1 : 0,
        ];

        $refundService = new RefundService();
        $result        = $refundService->submit($data);

        if (! ($result['success'] ?? false)) {
            return redirect()->to(base_url('/#refund-form'))
                ->with('refund_status', 'error')
                ->with('refund_message', $result['message'] ?? 'Refund form submission failed.')
                ->with('refund_open', 1);
        }

        return redirect()->to(base_url('/#refund-form'))
            ->with('refund_status', 'success')
            ->with('refund_message', 'Refund form submitted successfully.')
            ->with('refund_open', 1)
            ->with('refund_view_token', $result['access_token'] ?? '')
            ->with('refund_view_id', $result['refund_id'] ?? 0);
    }

    public function viewRefundPdf($refundId)
    {
        $token         = $this->request->getGet('token');
        $refundService = new RefundService();

        if (! $refundService->verifyAccess((int) $refundId, $token)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Refund record not found or access denied.');
        }

        $pdfFile = $refundService->getPdfFilePath((int) $refundId);

        if (! file_exists($pdfFile)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('PDF file not found.');
        }

        return $this->response->download($pdfFile, null)->setFileName('refund_' . $refundId . '.pdf');
    }
}
