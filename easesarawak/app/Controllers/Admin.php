<?php

namespace App\Controllers;

use App\Models\Order_model;
use App\Models\PaymentModel;
use App\Models\User_model;
use App\Models\ActivityLogModel;
use App\Models\MessageModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Admin extends BaseController
{
    public $data = [];
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $session = session();
        $access = $session->get('access');
        $role = $session->get('role');

        if (empty($access) || ($role !== '1' && $role !== '0')) {
            header('Location: ' . base_url('login'));
            exit;
        } else {
            $this->data['username'] = $session->get('username');
        }
    }

    public function index(): string
    {
        $order_model = new Order_model();
        $user_model = new User_model();
        $transaction_model = new PaymentModel();
        $messageModel = new MessageModel();

        $order = $order_model->where('is_deleted', 0)->countAllResults();
        $user  = $user_model->where('is_deleted', 0)->countAllResults();
        $sales = $order_model
            ->selectSum('amount')
            ->where('is_deleted', 0)
            ->get()
            ->getRow()
            ->amount ?? 0;
        $totalOrders = $order_model
            ->where('is_deleted', 0)
            ->countAllResults();

        // Fetch all pending orders
        $pending_orders = $order_model
            ->where('status', 'pending')
            ->findAll();

        $transaction = $transaction_model->orderBy('created_at', 'DESC')->findAll();

        $customer = $order_model->select('first_name, created_date')
            ->orderBy('created_date', 'DESC')
            ->limit(5)
            ->findAll();

        $messages = $messageModel->orderBy('created_date', 'DESC')
            ->limit(4)
            ->findAll();

        $data = [ 'order_count'    => $order,
                  'user_count'     => $user,
                  'sales'          => $sales,
                  'orders'         => $totalOrders,
                  'pending_orders' => $pending_orders,
                  'transactions'   => $transaction,
                  'new_customers'  => $customer,
                  'messages'        => $messages
                ];

        return $this->render('admin/dashboard', $data);
    }

    public function contact()
    {
        $messageModel = new MessageModel();
        $perPage = 15;
        $filter = $this->request->getGet('filter');
        $messageId = $this->request->getGet('message_id');

        if ($messageId) {
            $messageModel->update($messageId, ['status' => 'read']);
        }

        $query = $messageModel->where('is_deleted', 0);

        // Apply status filter
        if ($filter === 'new') {
            $query->where('status', 'new');
        } elseif ($filter === 'read') {
            $query->where('status', 'read');
        }

        $messages = $query
            ->orderBy('created_date', 'DESC')
            ->paginate($perPage, 'group1');

        $pager = $messageModel->pager;

        return $this->render('admin/contact', [
            'messages' => $messages,
            'pager'    => $pager,
        ]);
    }

    public function markMessageRead($msg_id)
    {
        $messageModel = new MessageModel();
        $messageModel->update($msg_id, ['status' => 'read']);
        return $this->response->setJSON(['success' => true]);
    }

    public function markAllMessagesRead()
    {
        $messageModel = new MessageModel();
        $messageModel->where('is_deleted', 0)
            ->where('status !=', 'read')
            ->set(['status' => 'read'])
            ->update();

        return $this->response->setJSON(['success' => true]);
    }

    public function getMessage($msg_id)
    {
        $messageModel = new MessageModel();
        $message = $messageModel->find($msg_id);
        if ($message) {
            return $this->response->setJSON(['success' => true, 'message' => $message]);
        }
        return $this->response->setJSON(['success' => false]);
    }

    public function report()
    {
        $order_model = new Order_model();

        // Total Revenue (sum of all order amounts)
        $totalRevenue = $order_model
            ->selectSum('amount')
            ->where('is_deleted', 0)
            ->get()
            ->getRow()
            ->amount ?? 0;

        // Total Orders (count)
        $totalOrders = $order_model
            ->where('is_deleted', 0)
            ->countAllResults();

        // Revenue Breakdown (Last 6 Months)
        $db = \Config\Database::connect();
        $builder = $db->table('order');

        // select only aggregated or grouped expressions — no plain columns
        $builder->select("DATE_FORMAT(MIN(created_date), '%b %Y') AS month, SUM(amount) AS total");
        $builder->where('is_deleted', 0);
        $builder->where('created_date >=', date('Y-m-01', strtotime('-5 months')));
        $builder->groupBy("YEAR(created_date), MONTH(created_date)");
        $builder->orderBy("YEAR(created_date)", 'ASC');
        $builder->orderBy("MONTH(created_date)", 'ASC');

        $revenueQuery = $builder->get()->getResultArray();

        $months   = array_column($revenueQuery, 'month');
        $revenues = array_map('floatval', array_column($revenueQuery, 'total'));

        // Peak Booking Times (based on created_date hour)
        $builder2 = $db->table('order');
        $builder2->select("HOUR(created_date) AS hour, COUNT(order_id) AS count");
        $builder2->where('is_deleted', 0);
        $builder2->groupBy('HOUR(created_date)');
        $builder2->orderBy('hour', 'ASC');
        $timeQuery = $builder2->get()->getResultArray();

        $hours = array_column($timeQuery, 'hour');
        $hourCounts = array_column($timeQuery, 'count');

        // Pass data to view
        $data = [
            'totalRevenue' => $totalRevenue,
            'totalOrders'  => $totalOrders,
            'months'       => $months,
            'revenues'     => $revenues,
            'hours'        => $hours,
            'hourCounts'   => $hourCounts,
        ];

        return $this->render('admin/report', $data);
    }

    public function getRevenueData()
    {
        $service   = $this->request->getGet('service');
        $timeframe = $this->request->getGet('timeframe');

        $db      = \Config\Database::connect();
        $builder = $db->table('`order`');
        $builder->where('is_deleted', 0);

        if ($service !== 'all') {
            $builder->where('service_type', $service);
        }

        if ($timeframe === 'day') {
            $builder->select('DATE(created_date) as label, SUM(amount) as total');
            $builder->groupBy(['DATE(created_date)']);
            $builder->orderBy('DATE(created_date)', 'ASC');
        } elseif ($timeframe === 'week') {
            // Use YEARWEEK without the mode argument to avoid a comma inside the
            // function call — CI4's query builder splits select/groupBy strings on
            // commas, which would break "YEARWEEK(created_date, 1)" into two invalid
            // SQL fragments.  Mode-0 week numbering is close enough for reporting.
            $builder->select('YEARWEEK(created_date) as label, SUM(amount) as total');
            $builder->groupBy(['YEARWEEK(created_date)']);
            $builder->orderBy('YEARWEEK(created_date)', 'ASC');
        } else { // month
            $builder->select('YEAR(created_date) as y, MONTH(created_date) as label, SUM(amount) as total');
            $builder->groupBy(['YEAR(created_date)', 'MONTH(created_date)']);
            $builder->orderBy('YEAR(created_date)', 'ASC');
            $builder->orderBy('MONTH(created_date)', 'ASC');
        }

        $results = $builder->get()->getResultArray();

        $labels = [];
        $values = [];

        foreach ($results as $row) {
            if ($timeframe === 'day') {
                $labels[] = date('M d', strtotime($row['label']));
            } elseif ($timeframe === 'week') {
                $year = substr($row['label'], 0, 4);
                $week = substr($row['label'], 4);
                $labels[] = 'Week ' . (int)$week . ' (' . $year . ')';
            } else {
                $labels[] = date('M', mktime(0, 0, 0, $row['label'], 10)) . ' ' . $row['y'];
            }
            $values[] = (float) $row['total'];
        }

        return $this->response->setJSON([
            'labels' => $labels,
            'values' => $values
        ]);
    }

    public function getPeakTimesData()
    {
        $service = $this->request->getGet('service');
        $range   = $this->request->getGet('range');

        $db      = \Config\Database::connect();
        $builder = $db->table('`order`');
        $builder->select('HOUR(created_date) AS hour, COUNT(order_id) AS count');
        $builder->where('is_deleted', 0);

        if ($service !== 'all') {
            $builder->where('service_type', $service);
        }

        switch ($range) {
            case 'this-month':
                $builder->where('created_date >=', date('Y-m-01 00:00:00'));
                $builder->where('created_date <=', date('Y-m-t 23:59:59'));
                break;
            case 'last-3':
                $builder->where('created_date >=', date('Y-m-01 00:00:00', strtotime('-2 months')));
                break;
            case 'this-year':
                $builder->where('created_date >=', date('Y-01-01 00:00:00'));
                break;
            case 'custom':
                $start = $this->request->getGet('start');
                $end   = $this->request->getGet('end');
                if ($start) $builder->where('DATE(created_date) >=', $start);
                if ($end)   $builder->where('DATE(created_date) <=', $end);
                break;
            // 'all' → no date filter
        }

        $builder->groupBy(['HOUR(created_date)']);
        $builder->orderBy('hour', 'ASC');

        $results = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'hours'  => array_map('intval', array_column($results, 'hour')),
            'counts' => array_map('intval', array_column($results, 'count')),
        ]);
    }

    public function exportRevenue()
    {
        $format    = $this->request->getGet('format') ?? 'pdf';
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date')   ?? date('Y-m-t');

        $db      = \Config\Database::connect();
        $builder = $db->table('`order`');
        $builder->where('is_deleted', 0);
        $builder->where('DATE(created_date) >=', $startDate);
        $builder->where('DATE(created_date) <=', $endDate);
        $builder->orderBy('created_date', 'ASC');
        $orders = $builder->get()->getResultArray();

        $totalRevenue = array_sum(array_column($orders, 'amount'));
        $totalOrders  = count($orders);

        $byService = [];
        foreach ($orders as $o) {
            $svc = ucfirst(strtolower($o['service_type'] ?? 'Other'));
            if (!isset($byService[$svc])) {
                $byService[$svc] = ['count' => 0, 'total' => 0];
            }
            $byService[$svc]['count']++;
            $byService[$svc]['total'] += $o['amount'];
        }

        if ($format === 'csv') {
            return $this->_exportCsv($orders, $startDate, $endDate, $byService, $totalRevenue);
        }

        // PDF: render a print-friendly branded invoice view
        $data = [
            'orders'       => $orders,
            'startDate'    => $startDate,
            'endDate'      => $endDate,
            'totalRevenue' => $totalRevenue,
            'totalOrders'  => $totalOrders,
            'byService'    => $byService,
            'generatedAt'  => date('d M Y, h:i A'),
        ];

        return view('admin/invoice_export', $data);
    }

    private function _exportCsv(array $orders, string $startDate, string $endDate, array $byService, float $totalRevenue)
    {
        $filename = 'EASE-Sarawak-Revenue-' . $startDate . '-to-' . $endDate . '.csv';
        $tmp = fopen('php://temp', 'w+');

        // UTF-8 BOM so Excel opens it correctly
        fwrite($tmp, "\xEF\xBB\xBF");

        fputcsv($tmp, ['EASE Sarawak - Revenue Invoice Report']);
        fputcsv($tmp, ['Period: ' . date('d M Y', strtotime($startDate)) . ' to ' . date('d M Y', strtotime($endDate))]);
        fputcsv($tmp, ['Generated: ' . date('d M Y, h:i A')]);
        fputcsv($tmp, []);

        // Service summary block
        fputcsv($tmp, ['--- Service Summary ---']);
        fputcsv($tmp, ['Service', 'Orders', 'Revenue (RM)']);
        foreach ($byService as $svc => $info) {
            fputcsv($tmp, [$svc, $info['count'], number_format($info['total'], 2)]);
        }
        fputcsv($tmp, ['TOTAL', count($orders), number_format($totalRevenue, 2)]);
        fputcsv($tmp, []);

        // Order detail headers
        fputcsv($tmp, ['--- Order Details ---']);
        fputcsv($tmp, [
            'Order ID', 'Order Date', 'Customer Name', 'Email', 'Phone',
            'Service Type', 'Payment Method', 'Promo Code', 'Status', 'Amount (RM)'
        ]);

        $statusMap = [0 => 'Pending', 1 => 'In Progress', 2 => 'Completed'];
        foreach ($orders as $order) {
            fputcsv($tmp, [
                '#' . $order['order_id'],
                date('d M Y', strtotime($order['created_date'])),
                trim($order['first_name'] . ' ' . $order['last_name']),
                $order['email'],
                $order['phone'],
                strtoupper($order['service_type'] ?? '-'),
                $order['payment_method'] ?? '-',
                $order['promo_code'] ?: '-',
                $statusMap[$order['status']] ?? 'Unknown',
                number_format($order['amount'], 2),
            ]);
        }

        fputcsv($tmp, []);
        fputcsv($tmp, ['', '', '', '', '', '', '', '', 'TOTAL', number_format($totalRevenue, 2)]);

        rewind($tmp);
        $csvContent = stream_get_contents($tmp);
        fclose($tmp);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csvContent);
    }

    public function order()
    {
        helper('form');
        
        $status = $this->request->getGet('status');
        $service = $this->request->getGet('service_type');
        $start = $this->request->getGet('start_date');
        $end   = $this->request->getGet('end_date');

        $orderModel = new Order_model();

        $orderModel->where('1=1'); // base

        if ($status !== null && $status !== '') {
            $orderModel->where('status', $status);
        }

        if ($service !== null && $service !== '') {
            $orderModel->where('service_type', $service);
        }

        if (!empty($start)) {
            $orderModel->where('DATE(created_date) >=', $start);
        }

        if (!empty($end)) {
            $orderModel->where('DATE(created_date) <=', $end);
        }

        $data['orders'] = $orderModel->where('is_deleted', 0)->paginate(10, 'group1');
        $data['pager'] = $orderModel->pager;

        return $this->render('admin/order', $data);
    }

    public function order_activity_log($order_id)
    {
        $logModel = new ActivityLogModel();

        $logs = $logModel->where('order_id', $order_id)
            ->orderBy('modified_date', 'DESC')
            ->findAll();

        return $this->render('admin/activity_log_table', ['logs' => $logs]);
    }

    public function change_status($order_id)
    {
        $orderModel = new Order_model();
        $logModel = new ActivityLogModel();
        $session = session();
        $userId = $session->get('user_id');

        $order = $orderModel->find($order_id);

        if ($order) {
            $statusText = [
                0 => 'Pending',
                1 => 'In Progress',
                2 => 'Completed'
            ];

            // Cycle through status: 0 → 1 → 2 → 0
            $newStatus = ($order['status'] + 1) % 3;
            $orderModel->update($order_id, ['status' => $newStatus,
                    'modified_by' => $userId,
                    'modified_date' => date('Y-m-d H:i:s')]);

            $logModel->insert([
                'order_id' => $order_id,
                'user_id' => $userId,
                'username' => session()->get('username'),
                'action' => 'Changed order status to ' . $statusText[$newStatus],
                'modified_date' => date('Y-m-d H:i:s')
            ]);

            session()->setFlashdata('success', 'Order status updated successfully.');
        } else {
            session()->setFlashdata('error', 'Order not found.');
        }

        return redirect()->to(base_url('/order'));
    }

    public function user()
    {
        $user_model = new User_model();
        $perPage = 10;
        $users = $user_model->where('is_deleted', 0)->paginate($perPage, 'group1');
        $pager = $user_model->pager;

        $data = [
            'users' => $users,
            'pager' => $pager
        ];
        // print_r($users);exit;
        return $this->render('admin/user', $data);
    }

    public function create_user()
    {
        $userModel = new User_model();

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'role'          => $this->request->getPost('role'),
                'username'      => $this->request->getPost('username'),
                'email'      => $this->request->getPost('email'),
                'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'created_date'  => date('Y-m-d H:i:s'),
            ];

            $userModel->insert($data);
            return redirect()->to(base_url('/user'))->with('success', 'User created successfully!');
        }

        return $this->render('admin/create_user');
    }

    public function getDetails($order_id)
    {
        $orderModel = new Order_model();
        $order = $orderModel->getOrderWithUserById($order_id);

        if ($order) {
            return $this->response->setJSON([
                'success' => true,
                'order' => $order
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found.'
            ]);
        }
    }

    public function save_note()
    {
        $orderId = $this->request->getPost('order_id');
        $note = $this->request->getPost('note');
        $userId = session()->get('user_id');

        $orderModel = new Order_model();
        $orderModel->update($orderId, ['comment' => $note,
                'modified_date' => date('Y-m-d H:i:s'),
                'modified_by' => session()->get('user_id')]);

        $logModel = new ActivityLogModel();
        $logModel->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'username' => session()->get('username'),
            'action' => 'Changed note to "' . $note . '"',
            'modified_date' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function edit($user_id)
    {
        $userModel = new User_model();
        $user = $userModel->find($user_id);

        if (!$user) {
            return redirect()->to(base_url('admin/user'))->with('error', 'User not found.');
        }
        return $this->render('admin/edit_user', ['user' => $user]);
    }

    public function update($user_id)
    {
        $userModel = new User_model();

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
            'modified_date' => date('Y-m-d H:i:s')
        ];

        // Optional: add validation
        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required|min_length[3]',
            'email'    => 'required|valid_email',
            'role'     => 'required|in_list[0,1]',
        ]);

        if (!$validation->run($data)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userModel->update($user_id, $data);
        return redirect()->to(base_url('/user'))->with('message', "User $user_id updated successfully.");
    }

    public function delete($user_id)
    {
        $userModel = new User_model();

        $user = $userModel->find($user_id);
        if (!$user) {
            return redirect()->to(base_url('admin/user'))->with('error', 'User not found.');
        }

        // Soft delete: set is_deleted to 1
        $userModel->update($user_id, ['is_deleted' => 1,
                                    'modified_date' => date('Y-m-d H:i:s')]);

        return redirect()->to(base_url('/user'))->with('message', "User $user_id deleted successfully.");
    }
    
    public function service_management()
    {
        $model = new \App\Models\ServiceManagementModel();
        $services = $model->findAll();
        return $this->render('admin/service_management', ['services' => $services]);
    }

    public function update_service_price($id)
    {
        $model = new \App\Models\ServiceManagementModel();
        $base_price = $this->request->getPost('base_price');
        $model->update($id, ['base_price' => $base_price]);
        return redirect()->to('/admin/service_management')->with('success', 'Base price updated!');
    }
}
