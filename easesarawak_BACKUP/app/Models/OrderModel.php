<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'order';
    protected $primaryKey = 'order_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'service_type',
        'first_name',
        'last_name',
        'id_num',
        'email',
        'phone',
        'social',
        'social_num',
        'upload',
        'special',
        'special_note',
        'order_details_json',
        'promo_code',
        'status',
        'amount',
        'payment_method',
        'is_deleted',
        'created_date',
        'modified_date',
        'insurance_selected',
        'insurance_amount'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_date';
    protected $updatedField = 'modified_date';

    // Validation
    protected $validationRules = [
        'service_type' => 'required|max_length[255]',
        'first_name' => 'required|max_length[255]',
        'last_name' => 'required|max_length[255]',
        'id_num' => 'required|max_length[50]',
        'email' => 'required|valid_email|max_length[255]',
        'phone' => 'required|max_length[20]',
        'social' => 'required|integer',
        'social_num' => 'required|max_length[100]',
        'order_details_json' => 'required'
    ];

    protected $validationMessages = [
        'service_type' => [
            'required' => 'Service type is required',
            'max_length' => 'Service type cannot exceed 255 characters'
        ],
        'first_name' => [
            'required' => 'First name is required',
            'max_length' => 'First name cannot exceed 255 characters'
        ],
        'last_name' => [
            'required' => 'Last name is required',
            'max_length' => 'Last name cannot exceed 255 characters'
        ],
        'id_num' => [
            'required' => 'Identification number is required',
            'max_length' => 'Identification number cannot exceed 50 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'max_length' => 'Email cannot exceed 255 characters'
        ],
        'phone' => [
            'required' => 'Phone number is required',
            'max_length' => 'Phone number cannot exceed 20 characters'
        ],
        'social' => [
            'required' => 'Social contact type is required',
            'integer' => 'Social contact type must be a number'
        ],
        'social_num' => [
            'required' => 'Social contact number is required',
            'max_length' => 'Social contact number cannot exceed 100 characters'
        ],
        'order_details_json' => [
            'required' => 'Order details are required'
        ]
    ];

    // Skip validation
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setDefaultValues'];
    protected $beforeUpdate = ['setModifiedDate'];

    /**
     * Set default values before insert
     */
    protected function setDefaultValues(array $data)
    {
        if (!isset($data['data']['status'])) {
            $data['data']['status'] = 0;
        }
        if (!isset($data['data']['amount'])) {
            $data['data']['amount'] = 0;
        }
        if (!isset($data['data']['is_deleted'])) {
            $data['data']['is_deleted'] = 0;
        }
        if (!isset($data['data']['upload'])) {
            $data['data']['upload'] = '';
        }
        if (!isset($data['data']['promo_code'])) {
            $data['data']['promo_code'] = '';
        }
        if (!isset($data['data']['payment_method'])) {
            $data['data']['payment_method'] = 'pending';
        }
        $data['data']['created_date'] = date('Y-m-d H:i:s');
        return $data;
    }

    /**
     * Set modified date before update
     */
    protected function setModifiedDate(array $data)
    {
        $data['data']['modified_date'] = date('Y-m-d H:i:s');
        return $data;
    }

    /**
     * Convert social platform name to ID
     */
    private function getSocialTypeId($socialType)
    {
        $socialTypes = [
            'whatsapp' => 1,
            'wechat' => 2,
            'line' => 3
        ];
        return isset($socialTypes[strtolower($socialType)]) ? $socialTypes[strtolower($socialType)] : 0;
    }

    /**
     * Handle file upload
     */
    private function handleFileUpload($file)
    {
        $uploadedFilePath = '';
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadDir = 'assets/upload/';
            $uploadPath = FCPATH . $uploadDir;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
                log_message('info', 'Created upload directory: ' . $uploadPath);
            }
            $fileName = uniqid() . '_' . time() . '.' . $file->getClientExtension();
            if ($file->move($uploadPath, $fileName)) {
                $uploadedFilePath = $uploadDir . $fileName;
                log_message('info', 'File uploaded successfully: ' . $uploadedFilePath);
            } else {
                log_message('error', 'Failed to move uploaded file');
            }
        } else if ($file && !$file->isValid()) {
            log_message('error', 'Invalid file upload: ' . $file->getErrorString());
        }
        return $uploadedFilePath;
    }

    /**
     * Format order details JSON
     */
    private function formatOrderDetailsJson($bookingData)
    {
        $orderDetails = [
            'Service Type' => ucfirst($bookingData['service'] ?? 'Null'),
            'Origin Location' => !empty($bookingData['origin']) ? $bookingData['origin'] : 'Null',
            'Origin Address' => !empty($bookingData['originAddress']) ? $bookingData['originAddress'] : 'Null',
            'Destination Location' => !empty($bookingData['destination']) ? $bookingData['destination'] : 'Null',
            'Destination Address' => !empty($bookingData['destinationAddress']) ? $bookingData['destinationAddress'] : 'Null',
            'Drop-off DateTime' => !empty($bookingData['dropoffDate']) && !empty($bookingData['dropoffTime']) 
                ? $bookingData['dropoffDate'] . ' at ' . $bookingData['dropoffTime'] 
                : 'Null',
            'Pickup DateTime' => !empty($bookingData['pickupDate']) && !empty($bookingData['pickupTime']) 
                ? $bookingData['pickupDate'] . ' at ' . $bookingData['pickupTime'] 
                : 'Null',
            'Quantity' => !empty($bookingData['quantity']) ? $bookingData['quantity'] . ' item(s)' : 'Null',
            'Base Price' => !empty($bookingData['basePrice']) ? 'RM ' . $bookingData['basePrice'] : 'Null',
            'Promo Code' => !empty($bookingData['promoCode']) ? $bookingData['promoCode'] : 'Null',
            'Promo Discount' => !empty($bookingData['promoDiscount'])
                ? ($bookingData['promoType'] === 'amount'
                    ? $bookingData['promoDiscount'] . 'RM'
                    : $bookingData['promoDiscount'] . '%')
                : 'Null',
            'Total Price' => !empty($bookingData['totalPrice']) ? 'RM ' . $bookingData['totalPrice'] : 'Null',
            'Insurance Selected' => !empty($bookingData['insuranceSelected']) ? 'Yes' : 'No',
            'Insurance Amount' => isset($bookingData['insuranceAmount']) ? 'RM ' . number_format($bookingData['insuranceAmount'], 2) : 'RM 0.00'
        ];
        if ($bookingData['service'] === 'storage') {
            $orderDetails = [
                'Service Type' => 'Storage',
                'Storage Location' => !empty($bookingData['storageLocation']) ? $bookingData['storageLocation'] : 'Null',
                'Drop-off DateTime' => !empty($bookingData['dropoffDate']) && !empty($bookingData['dropoffTime']) 
                    ? $bookingData['dropoffDate'] . ' at ' . $bookingData['dropoffTime'] 
                    : 'Null',
                'Pickup DateTime' => !empty($bookingData['pickupDate']) && !empty($bookingData['pickupTime']) 
                    ? $bookingData['pickupDate'] . ' at ' . $bookingData['pickupTime'] 
                    : 'Null',
                'Quantity' => !empty($bookingData['quantity']) ? $bookingData['quantity'] . ' item(s)' : 'Null',
                'Base Price' => !empty($bookingData['basePrice']) ? 'RM ' . $bookingData['basePrice'] : 'Null',
                'Promo Code' => !empty($bookingData['promoCode']) ? $bookingData['promoCode'] : 'Null',
                'Promo Discount' => !empty($bookingData['promoDiscount'])
                    ? ($bookingData['promoType'] === 'amount'
                        ? $bookingData['promoDiscount'] . 'RM'
                        : $bookingData['promoDiscount'] . '%')
                    : 'Null',
                'Total Price' => !empty($bookingData['totalPrice']) ? 'RM ' . $bookingData['totalPrice'] : 'Null',
                'Insurance Selected' => !empty($bookingData['insuranceSelected']) ? 'Yes' : 'No',
                'Insurance Amount' => isset($bookingData['insuranceAmount']) ? 'RM ' . number_format($bookingData['insuranceAmount'], 2) : 'RM 0.00'
            ];
        }
        return json_encode($orderDetails, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Process and save complete order - MAIN METHOD
     */
    public function processAndSaveOrder($request)
    {
        try {
            log_message('info', 'OrderModel: processAndSaveOrder called');
            
            // Get booking data
            $bookingDataJson = $request->getPost('bookingData');
            log_message('info', 'OrderModel: Received booking data JSON: ' . $bookingDataJson);

            $bookingData = json_decode($bookingDataJson, true);

            if (!$bookingData) {
                log_message('error', 'OrderModel: Failed to decode booking data JSON');
                return [
                    'success' => false,
                    'message' => 'Invalid booking data'
                ];
            }

            // Get customer data
            $firstName = $request->getPost('firstName');
            $lastName = $request->getPost('lastName');
            $identificationNumber = $request->getPost('identificationNumber');
            $email = $request->getPost('email');
            $phone = $request->getPost('phone');
            $socialContactType = $request->getPost('socialContactType');
            $socialContactValue = $request->getPost('socialContactValue');

            // Get special luggage data
            $specialLuggage = $request->getPost('specialLuggage');
            $specialLuggageNote = $request->getPost('specialLuggageNote');

            log_message('info', 'OrderModel: Customer data - Name: ' . $firstName . ' ' . $lastName . ', Email: ' . $email);
            log_message('info', 'OrderModel: Special luggage: ' . $specialLuggage . ', Note: ' . $specialLuggageNote);

            // Handle file upload
            $uploadedFilePaths = [];
            $files = $request->getFileMultiple('documentUpload');
            if ($files) {
                foreach ($files as $file) {
                    $path = $this->handleFileUpload($file);
                    if ($path) {
                        $uploadedFilePaths[] = $path;
                    }
                }
            }

            // Format order details JSON
            $orderDetailsJson = $this->formatOrderDetailsJson($bookingData);

            // Handle special luggage logic
            if ($specialLuggage === '1' && !empty($specialLuggageNote)) {
                $special = 1;
                $specialNote = trim($specialLuggageNote);
                log_message('info', 'OrderModel: Special luggage detected with note: ' . $specialNote);
            } else {
                $special = 0;
                $specialNote = null;
                log_message('info', 'OrderModel: No special luggage');
            }

            // Map social contact type
            $social = '0'; // default
            switch (strtolower($socialContactType)) {
                case 'whatsapp':
                    $social = '1';
                    break;
                case 'wechat':
                    $social = '2';
                    break;
                case 'line':
                    $social = '3';
                    break;
                default:
                    $social = '0';
                    break;
            }

            // Prepare data for insert
            $data = [
                'service_type' => $bookingData['service'],
                'first_name' => $firstName,
                'last_name' => $lastName,
                'id_num' => $identificationNumber,
                'email' => $email,
                'phone' => $phone,
                'social' => $social,
                'social_num' => $socialContactValue,
                'upload' => json_encode($uploadedFilePaths),
                'special' => $special,
                'special_note' => $specialNote,
                'order_details_json' => $orderDetailsJson,
                'promo_code' => $bookingData['promoCode'] ?? '',
                'status' => 0,
                'amount' => $bookingData['totalPrice'],
                'payment_method' => 'pending',
                'is_deleted' => 0,
                'created_date' => date('Y-m-d H:i:s'),
                'insurance_selected' => !empty($bookingData['insuranceSelected']) ? 1 : 0,
                'insurance_amount' => !empty($bookingData['insuranceAmount']) ? $bookingData['insuranceAmount'] : 0.00
            ];

            // Use CodeIgniter's Query Builder for insert
            $db = \Config\Database::connect();
            $builder = $db->table($this->table);
            $insertResult = $builder->insert($data);

            if ($insertResult) {
                $orderId = $db->insertID();
                log_message('info', 'OrderModel: Order inserted successfully with ID: ' . $orderId);

                return [
                    'success' => true,
                    'order_id' => $orderId,
                    'message' => 'Order saved successfully',
                    'uploaded_file' => json_encode($uploadedFilePaths)
                ];
            } else {
                log_message('error', 'OrderModel: Failed to save order');
                return [
                    'success' => false,
                    'message' => 'Failed to save order'
                ];
            }
        } catch (\Exception $e) {
            log_message('error', 'OrderModel: Exception in processAndSaveOrder: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error occurred: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get order by ID
     */
    public function getOrderById($orderId)
    {
        return $this->where('order_id', $orderId)
                    ->where('is_deleted', 0)
                    ->first();
    }

    /**
     * Get orders by status
     */
    public function getOrdersByStatus($status)
    {
        return $this->where('status', $status)
                    ->where('is_deleted', 0)
                    ->findAll();
    }

    /**
     * Get orders by email
     */
    public function getOrdersByEmail($email)
    {
        return $this->where('email', $email)
                    ->where('is_deleted', 0)
                    ->orderBy('created_date', 'DESC')
                    ->findAll();
    }

    /**
     * Update order status
     */
    public function updateOrderStatus($orderId, $status)
    {
        return $this->update($orderId, ['status' => $status]);
    }

    /**
     * Soft delete order
     */
    public function softDeleteOrder($orderId)
    {
        return $this->update($orderId, ['is_deleted' => 1]);
    }
}