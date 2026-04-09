<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\MessageModel;

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
}
