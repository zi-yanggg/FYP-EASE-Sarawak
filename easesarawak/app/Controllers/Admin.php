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
        $user_model        = new User_model();
        $transaction_model = new PaymentModel();
        $messageModel      = new MessageModel();
        $db                = \Config\Database::connect();

        $today      = date('Y-m-d');
        $yesterday  = date('Y-m-d', strtotime('-1 day'));
        $weekStart  = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $monthStart = date('Y-m-01 00:00:00');

        // ── Order counts by status ───────────────────────────────────────
        $totalOrders     = (int)$db->table('`order`')->where('is_deleted', 0)->countAllResults();
        $pendingCount    = (int)$db->table('`order`')->where('is_deleted', 0)->where('status', 0)->countAllResults();
        $inProgressCount = (int)$db->table('`order`')->where('is_deleted', 0)->where('status', 1)->countAllResults();
        $completedCount  = (int)$db->table('`order`')->where('is_deleted', 0)->where('status', 2)->countAllResults();
        $userCount       = (int)$user_model->where('is_deleted', 0)->countAllResults();

        // ── Revenue metrics ──────────────────────────────────────────────
        $todayRevenue = (float)($db->table('`order`')->selectSum('amount')
            ->where('is_deleted', 0)->where('DATE(created_date)', $today)
            ->get()->getRow()->amount ?? 0);
        $yesterdayRevenue = (float)($db->table('`order`')->selectSum('amount')
            ->where('is_deleted', 0)->where('DATE(created_date)', $yesterday)
            ->get()->getRow()->amount ?? 0);
        $weekRevenue = (float)($db->table('`order`')->selectSum('amount')
            ->where('is_deleted', 0)->where('created_date >=', $weekStart)
            ->get()->getRow()->amount ?? 0);
        $monthRevenue = (float)($db->table('`order`')->selectSum('amount')
            ->where('is_deleted', 0)->where('created_date >=', $monthStart)
            ->get()->getRow()->amount ?? 0);
        $totalRevenue = (float)($db->table('`order`')->selectSum('amount')
            ->where('is_deleted', 0)->get()->getRow()->amount ?? 0);

        $todayRevenueDelta = $yesterdayRevenue > 0
            ? round(($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue * 100, 1) : 0;

        // ── Today's order count ──────────────────────────────────────────
        $todayOrders     = (int)$db->table('`order`')->where('is_deleted', 0)
            ->where('DATE(created_date)', $today)->countAllResults();
        $yesterdayOrders = (int)$db->table('`order`')->where('is_deleted', 0)
            ->where('DATE(created_date)', $yesterday)->countAllResults();
        $todayOrdersDelta = $yesterdayOrders > 0
            ? round(($todayOrders - $yesterdayOrders) / $yesterdayOrders * 100, 1) : 0;

        // ── Active storage holds (not yet completed) ─────────────────────
        $activeStorageHolds = (int)$db->table('`order`')->where('is_deleted', 0)
            ->where('service_type', 'storage')->where('status !=', 2)->countAllResults();

        // ── Unread messages ──────────────────────────────────────────────
        $unreadMessages = (int)$db->table('message')->where('is_deleted', 0)
            ->where('status', 'new')->countAllResults();

        // ── Pending refunds ──────────────────────────────────────────────
        $pendingRefunds = 0;
        try {
            $pendingRefunds = (int)$db->table('refund_form')->where('status', 'pending')->countAllResults();
        } catch (\Exception $e) { /* table may not exist in all deployments */ }

        // ── All orders for JSON-based pickup / drop-off date parsing ─────
        $allActiveOrders = $db->table('`order`')
            ->select('order_id, first_name, last_name, service_type, status, amount, order_details_json, created_date')
            ->where('is_deleted', 0)
            ->get()->getResultArray();

        $todayPickups     = [];
        $todayDropoffs    = [];
        $calendarPickups  = [];
        $calendarDropoffs = [];

        foreach ($allActiveOrders as $ord) {
            $details = @json_decode($ord['order_details_json'] ?? '{}', true);
            if (!is_array($details)) continue;

            $pickupRaw  = trim($details['Pickup DateTime']    ?? '');
            $dropoffRaw = trim($details['Drop-off DateTime'] ?? '');

            if ($pickupRaw && $pickupRaw !== 'Null') {
                $pickupDate = substr($pickupRaw, 0, 10);
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $pickupDate)) {
                    $calendarPickups[$pickupDate] = ($calendarPickups[$pickupDate] ?? 0) + 1;
                    if ($pickupDate === $today) {
                        $atPos        = strpos($pickupRaw, 'at ');
                        $todayPickups[] = $ord + ['event_time' => $atPos !== false ? trim(substr($pickupRaw, $atPos + 3)) : ''];
                    }
                }
            }

            if ($dropoffRaw && $dropoffRaw !== 'Null') {
                $dropoffDate = substr($dropoffRaw, 0, 10);
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dropoffDate)) {
                    $calendarDropoffs[$dropoffDate] = ($calendarDropoffs[$dropoffDate] ?? 0) + 1;
                    if ($dropoffDate === $today) {
                        $atPos         = strpos($dropoffRaw, 'at ');
                        $todayDropoffs[] = $ord + ['event_time' => $atPos !== false ? trim(substr($dropoffRaw, $atPos + 3)) : ''];
                    }
                }
            }
        }

        // ── Calendar heatmap (pickup + drop-off dates only) ───────────────
        $allCalDates = array_unique(array_merge(
            array_keys($calendarPickups),
            array_keys($calendarDropoffs)
        ));
        sort($allCalDates);
        $calendarHeatmap = [];
        foreach ($allCalDates as $d) {
            $pickups  = $calendarPickups[$d]  ?? 0;
            $dropoffs = $calendarDropoffs[$d] ?? 0;
            $calendarHeatmap[$d] = [
                'pickups'  => $pickups,
                'dropoffs' => $dropoffs,
                'total'    => $pickups + $dropoffs,
            ];
        }

        // ── Pending orders (status = 0) with next-day fallback ───────────
        $pending_orders = $db->table('`order`')
            ->where('is_deleted', 0)->where('status', 0)
            ->orderBy('created_date', 'ASC')
            ->get()->getResultArray();

        $pendingFallbackDate   = null;
        $pendingFallbackOrders = [];
        if (empty($pending_orders)) {
            $nextDate = null;
            foreach ($allActiveOrders as $ord) {
                $details = @json_decode($ord['order_details_json'] ?? '{}', true);
                if (!is_array($details)) continue;
                foreach (['Pickup DateTime', 'Drop-off DateTime'] as $k) {
                    $raw = trim($details[$k] ?? '');
                    if ($raw && $raw !== 'Null') {
                        $date = substr($raw, 0, 10);
                        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && $date > $today) {
                            if ($nextDate === null || $date < $nextDate) $nextDate = $date;
                        }
                    }
                }
            }
            if ($nextDate) {
                $pendingFallbackDate = $nextDate;
                $seen = [];
                foreach ($allActiveOrders as $ord) {
                    if (isset($seen[$ord['order_id']])) continue;
                    $details = @json_decode($ord['order_details_json'] ?? '{}', true);
                    if (!is_array($details)) continue;
                    foreach (['Pickup DateTime', 'Drop-off DateTime'] as $k) {
                        $raw = trim($details[$k] ?? '');
                        if ($raw && $raw !== 'Null' && substr($raw, 0, 10) === $nextDate) {
                            $pendingFallbackOrders[]     = $ord;
                            $seen[$ord['order_id']] = true;
                            break;
                        }
                    }
                }
            }
        }

        // ── Calendar order data for JS day list (pickup / drop-off only) ─
        $calendarOrders = array_map(static function ($o) {
            $d = @json_decode($o['order_details_json'] ?? '{}', true);
            $d = is_array($d) ? $d : [];
            $pickupRaw  = trim($d['Pickup DateTime']    ?? '');
            $dropoffRaw = trim($d['Drop-off DateTime'] ?? '');
            if ($pickupRaw === 'Null')  $pickupRaw  = '';
            if ($dropoffRaw === 'Null') $dropoffRaw = '';
            $atP = strpos($pickupRaw,  'at ');
            $atD = strpos($dropoffRaw, 'at ');
            $from = trim($d['Origin Location'] ?? $d['Origin Address'] ?? $d['Storage Location'] ?? '');
            $to   = trim($d['Destination Location'] ?? $d['Destination Address'] ?? '');
            return [
                'id'        => (int)$o['order_id'],
                'name'      => trim(($o['first_name'] ?? '') . ' ' . ($o['last_name'] ?? '')),
                'pickup'    => $pickupRaw  ? substr($pickupRaw,  0, 10) : null,
                'dropoff'   => $dropoffRaw ? substr($dropoffRaw, 0, 10) : null,
                'pickup_t'  => $atP !== false ? trim(substr($pickupRaw,  $atP + 3)) : null,
                'dropoff_t' => $atD !== false ? trim(substr($dropoffRaw, $atD + 3)) : null,
                'from'      => $from ?: '—',
                'to'        => $to   ?: '—',
            ];
        }, $allActiveOrders);

        // ── Month orders count ──────────────────────────────────────────
        $monthOrders = (int)$db->table('`order`')->where('is_deleted', 0)
            ->where('created_date >=', $monthStart)->countAllResults();

        // ── Parse orders for tooltip display data ────────────────────────
        // Actual JSON keys (from OrderModel::formatOrderDetailsJson):
        //   Pickup location  → 'Origin Location' / 'Origin Address' (delivery)
        //                      'Storage Location' (storage)
        //   Dropoff location → 'Destination Location' / 'Destination Address' (delivery)
        $parseForDisplay = static function (array $orders): array {
            return array_map(static function ($o) {
                $d = @json_decode($o['order_details_json'] ?? '{}', true);
                $d = is_array($d) ? $d : [];
                $pickupRaw  = trim($d['Pickup DateTime']    ?? '');
                $dropoffRaw = trim($d['Drop-off DateTime'] ?? '');
                // 'Null' string means the field was not filled in
                if ($pickupRaw  === 'Null') $pickupRaw  = '';
                if ($dropoffRaw === 'Null') $dropoffRaw = '';
                $pickupLoc  = trim($d['Origin Location']      ?? $d['Origin Address']      ?? $d['Storage Location']     ?? '');
                $dropoffLoc = trim($d['Destination Location'] ?? $d['Destination Address'] ?? '');
                return $o + [
                    '_pickup_time'      => $pickupRaw,   // full "YYYY-MM-DD at HH:MM" string
                    '_pickup_location'  => $pickupLoc,
                    '_dropoff_time'     => $dropoffRaw,  // full "YYYY-MM-DD at HH:MM" string
                    '_dropoff_location' => $dropoffLoc,
                    '_created_date_fmt' => substr($o['created_date'] ?? '', 0, 10),
                ];
            }, $orders);
        };

        $storageOrders          = $parseForDisplay(array_values(array_filter($allActiveOrders, fn($o) => strtolower($o['service_type'] ?? '') === 'storage')));
        $deliveryOrders         = $parseForDisplay(array_values(array_filter($allActiveOrders, fn($o) => strtolower($o['service_type'] ?? '') === 'delivery')));
        $pending_orders_display = $parseForDisplay($pending_orders);

        // ── News / activity feed ─────────────────────────────────────────
        $recentActivity = $db->table('activity_log al')
            ->select('al.log_id, al.order_id, al.username, al.action, al.modified_date, o.first_name, o.last_name, o.service_type')
            ->join('`order` o', 'o.order_id = al.order_id', 'left')
            ->orderBy('al.modified_date', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        $recentOrders = $db->table('`order`')
            ->where('is_deleted', 0)->orderBy('created_date', 'DESC')
            ->limit(5)->get()->getResultArray();

        $recentMessages = $messageModel->where('is_deleted', 0)
            ->orderBy('created_date', 'DESC')->limit(5)->findAll();

        // ── Transactions ─────────────────────────────────────────────────
        $transactions = $transaction_model->orderBy('created_at', 'DESC')->limit(10)->findAll();

        $data = [
            'totalOrders'           => $totalOrders,
            'pendingCount'          => $pendingCount,
            'inProgressCount'       => $inProgressCount,
            'completedCount'        => $completedCount,
            'userCount'             => $userCount,
            'todayRevenue'          => $todayRevenue,
            'yesterdayRevenue'      => $yesterdayRevenue,
            'weekRevenue'           => $weekRevenue,
            'monthRevenue'          => $monthRevenue,
            'totalRevenue'          => $totalRevenue,
            'todayRevenueDelta'     => $todayRevenueDelta,
            'todayOrders'           => $todayOrders,
            'todayOrdersDelta'      => $todayOrdersDelta,
            'activeStorageHolds'    => $activeStorageHolds,
            'unreadMessages'        => $unreadMessages,
            'pendingRefunds'        => $pendingRefunds,
            'todayPickups'          => $todayPickups,
            'todayDropoffs'         => $todayDropoffs,
            'calendarHeatmap'       => $calendarHeatmap,
            'pending_orders'        => $pending_orders,
            'pendingFallbackDate'   => $pendingFallbackDate,
            'pendingFallbackOrders' => $pendingFallbackOrders,
            'recentActivity'        => $recentActivity,
            'recentOrders'          => $recentOrders,
            'recentMessages'        => $recentMessages,
            'transactions'          => $transactions,
            'calendarOrders'        => $calendarOrders,
            'monthOrders'           => $monthOrders,
            'storageOrders'         => $storageOrders,
            'deliveryOrders'        => $deliveryOrders,
            'pending_orders_display'=> $pending_orders_display,
            // legacy aliases kept for compatibility
            'user_count'            => $userCount,
            'sales'                 => number_format($totalRevenue, 2),
            'orders'                => $totalOrders,
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

        $hours      = array_column($timeQuery, 'hour');
        $hourCounts = array_column($timeQuery, 'count');

        // Month-over-month revenue growth
        $thisMonthRevenue = (float)($db->table('`order`')
            ->selectSum('amount')
            ->where('is_deleted', 0)
            ->where('created_date >=', date('Y-m-01 00:00:00'))
            ->get()->getRow()->amount ?? 0);

        $lastMonthRevenue = (float)($db->table('`order`')
            ->selectSum('amount')
            ->where('is_deleted', 0)
            ->where('created_date >=', date('Y-m-01 00:00:00', strtotime('-1 month')))
            ->where('created_date <=', date('Y-m-t 23:59:59', strtotime('-1 month')))
            ->get()->getRow()->amount ?? 0);

        $growthPct = $lastMonthRevenue > 0
            ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : ($thisMonthRevenue > 0 ? 100.0 : 0.0);

        // Service breakdown (all-time) for donut chart
        $storageRevenue = (float)($db->table('`order`')
            ->selectSum('amount')
            ->where('is_deleted', 0)
            ->where('service_type', 'storage')
            ->get()->getRow()->amount ?? 0);

        $deliveryRevenue = (float)($db->table('`order`')
            ->selectSum('amount')
            ->where('is_deleted', 0)
            ->where('service_type', 'delivery')
            ->get()->getRow()->amount ?? 0);

        $dateRevenue = (float)($db->table('`order`')
            ->selectSum('amount')
            ->where('is_deleted', 0)
            ->where('service_type', 'date')
            ->get()->getRow()->amount ?? 0);

        // Order status breakdown
        $activeOrders    = (int)$db->table('`order`')->where('is_deleted', 0)->where('status', 1)->countAllResults();
        $completedOrders = (int)$db->table('`order`')->where('is_deleted', 0)->where('status', 2)->countAllResults();

        // Unique customers by email
        $uniqueCustomers = (int)($db->table('`order`')
            ->select('COUNT(DISTINCT email) AS cnt')
            ->where('is_deleted', 0)
            ->get()->getRow()->cnt ?? 0);

        // Top 5 customers by total revenue
        $topCustomers = $db->table('`order`')
            ->select('first_name, email, SUM(amount) AS total_revenue, COUNT(order_id) AS order_count')
            ->where('is_deleted', 0)
            ->groupBy('email')
            ->orderBy('total_revenue', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // All dates with daily order counts for calendar intensity indicators.
        $orderDateRows = $db->table('`order`')
            ->select('DATE(created_date) AS order_date, COUNT(order_id) AS order_count')
            ->where('is_deleted', 0)
            ->groupBy('DATE(created_date)')
            ->get()
            ->getResultArray();
        $orderDateCounts = [];
        foreach ($orderDateRows as $row) {
            $k = (string)($row['order_date'] ?? '');
            if ($k === '') {
                continue;
            }
            $orderDateCounts[$k] = (int)($row['order_count'] ?? 0);
        }
        $orderDates = array_keys($orderDateCounts);

        // ── Weekly revenue (Mon–Sun) ──────────────────────────────────
        $thisWeekStart   = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $lastWeekStart   = date('Y-m-d 00:00:00', strtotime('monday last week'));
        $lastWeekEnd     = date('Y-m-d 23:59:59', strtotime('sunday last week'));

        $thisWeekRevenue = (float)($db->table('`order`')
            ->selectSum('amount')->where('is_deleted', 0)
            ->where('created_date >=', $thisWeekStart)
            ->get()->getRow()->amount ?? 0);

        $lastWeekRevenue = (float)($db->table('`order`')
            ->selectSum('amount')->where('is_deleted', 0)
            ->where('created_date >=', $lastWeekStart)
            ->where('created_date <=', $lastWeekEnd)
            ->get()->getRow()->amount ?? 0);

        $weekRevenueGrowthPct = $lastWeekRevenue > 0
            ? (($thisWeekRevenue - $lastWeekRevenue) / $lastWeekRevenue) * 100
            : ($thisWeekRevenue > 0 ? 100.0 : 0.0);

        // ── This-year revenue ─────────────────────────────────────────
        $thisYearRevenue = (float)($db->table('`order`')
            ->selectSum('amount')->where('is_deleted', 0)
            ->where('created_date >=', date('Y-01-01 00:00:00'))
            ->get()->getRow()->amount ?? 0);

        // ── Order counts by period ────────────────────────────────────
        $thisYearOrders  = (int)$db->table('`order`')->where('is_deleted', 0)
            ->where('created_date >=', date('Y-01-01 00:00:00'))->countAllResults();

        $lastYearOrders  = (int)$db->table('`order`')->where('is_deleted', 0)
            ->where('YEAR(created_date)', (int)date('Y') - 1)->countAllResults();

        $thisMonthOrders = (int)$db->table('`order`')->where('is_deleted', 0)
            ->where('created_date >=', date('Y-m-01 00:00:00'))->countAllResults();

        $lastMonthOrders = (int)$db->table('`order`')->where('is_deleted', 0)
            ->where('created_date >=', date('Y-m-01 00:00:00', strtotime('-1 month')))
            ->where('created_date <',  date('Y-m-01 00:00:00'))->countAllResults();

        $thisWeekOrders  = (int)$db->table('`order`')->where('is_deleted', 0)
            ->where('created_date >=', $thisWeekStart)->countAllResults();

        $lastWeekOrders  = (int)$db->table('`order`')->where('is_deleted', 0)
            ->where('created_date >=', $lastWeekStart)
            ->where('created_date <=', $lastWeekEnd)->countAllResults();

        $yearOrderGrowthPct  = $lastYearOrders  > 0
            ? (($thisYearOrders  - $lastYearOrders)  / $lastYearOrders)  * 100
            : ($thisYearOrders  > 0 ? 100.0 : 0.0);
        $monthOrderGrowthPct = $lastMonthOrders > 0
            ? (($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100
            : ($thisMonthOrders > 0 ? 100.0 : 0.0);
        $weekOrderGrowthPct  = $lastWeekOrders  > 0
            ? (($thisWeekOrders  - $lastWeekOrders)  / $lastWeekOrders)  * 100
            : ($thisWeekOrders  > 0 ? 100.0 : 0.0);

        // ── Per-service order counts ──────────────────────────────────
        $storageOrderCount  = (int)$db->table('`order`')
            ->where('is_deleted', 0)->where('service_type', 'storage')->countAllResults();
        $deliveryOrderCount = (int)$db->table('`order`')
            ->where('is_deleted', 0)->where('service_type', 'delivery')->countAllResults();

        // ── Repeat customers (ordered more than once) ─────────────────
        $repeatingCustomers = (int)($db->query(
            'SELECT COUNT(*) AS cnt FROM (SELECT email FROM `order` WHERE is_deleted = 0 GROUP BY email HAVING COUNT(*) > 1) AS rpt'
        )->getRow()->cnt ?? 0);

        // Year dropdown + default anchors should follow actual data, not current date.
        $yearRows = $db->table('`order`')
            ->select('YEAR(created_date) AS year_val')
            ->where('is_deleted', 0)
            ->groupBy('YEAR(created_date)')
            ->orderBy('year_val', 'DESC')
            ->get()
            ->getResultArray();
        $reportYears = array_values(array_filter(array_map(
            static fn($r) => (int)($r['year_val'] ?? 0),
            $yearRows
        )));
        if (empty($reportYears)) {
            $reportYears = [(int)date('Y')];
        }

        $latestOrderDateTime = (string)($db->table('`order`')
            ->select('MAX(created_date) AS max_created')
            ->where('is_deleted', 0)
            ->get()->getRow()->max_created ?? '');
        $reportAnchorDate = $latestOrderDateTime
            ? date('Y-m-d', strtotime($latestOrderDateTime))
            : date('Y-m-d');
        $defaultReportYear = (int)date('Y', strtotime($reportAnchorDate));

        // Pass data to view
        $data = [
            'totalRevenue'     => $totalRevenue,
            'totalOrders'      => $totalOrders,
            'months'           => $months,
            'revenues'         => $revenues,
            'hours'            => $hours,
            'hourCounts'       => $hourCounts,
            'thisMonthRevenue' => $thisMonthRevenue,
            'lastMonthRevenue' => $lastMonthRevenue,
            'growthPct'        => $growthPct,
            'storageRevenue'   => $storageRevenue,
            'deliveryRevenue'  => $deliveryRevenue,
            'dateRevenue'      => $dateRevenue,
            'activeOrders'     => $activeOrders,
            'completedOrders'  => $completedOrders,
            'uniqueCustomers'  => $uniqueCustomers,
            'reportYears'      => $reportYears,
            'defaultReportYear'=> $defaultReportYear,
            'reportAnchorDate' => $reportAnchorDate,
            'topCustomers'        => $topCustomers,
            'orderDates'          => $orderDates,
            'orderDateCounts'     => $orderDateCounts,
            'thisWeekRevenue'     => $thisWeekRevenue,
            'weekRevenueGrowthPct'=> $weekRevenueGrowthPct,
            'thisYearRevenue'     => $thisYearRevenue,
            'thisYearOrders'      => $thisYearOrders,
            'thisMonthOrders'     => $thisMonthOrders,
            'thisWeekOrders'      => $thisWeekOrders,
            'yearOrderGrowthPct'  => $yearOrderGrowthPct,
            'monthOrderGrowthPct' => $monthOrderGrowthPct,
            'weekOrderGrowthPct'  => $weekOrderGrowthPct,
            'storageOrderCount'   => $storageOrderCount,
            'deliveryOrderCount'  => $deliveryOrderCount,
            'repeatingCustomers'  => $repeatingCustomers,
        ];

        return $this->render('admin/report', $data);
    }

    public function getRevenueData()
    {
        $service      = $this->request->getGet('service');
        $timeframe    = $this->request->getGet('timeframe') ?: 'year';
        $selectedDate = $this->request->getGet('selected_date');
        $db = \Config\Database::connect();
        $latestDateTime = (string)($db->table('`order`')
            ->select('MAX(created_date) AS max_created')
            ->where('is_deleted', 0)
            ->get()->getRow()->max_created ?? '');
        $latestDate = $latestDateTime ? date('Y-m-d', strtotime($latestDateTime)) : date('Y-m-d');

        $selectedYear = (int)($this->request->getGet('selected_year') ?? date('Y', strtotime($latestDate)));

        $isValidDate = $selectedDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate);
        $baseDate    = $isValidDate ? $selectedDate : $latestDate;
        if ($selectedYear < 2000 || $selectedYear > 2100) {
            $selectedYear = (int)date('Y', strtotime($latestDate));
        }

        $compareMode = ($service === 'split');
        $labels        = [];
        $values        = [];
        $rawKeys       = [];
        $periodDetails = [];
        $serviceSeries = [
            'storage' => [],
            'delivery' => [],
            'other' => [],
        ];

        $detailBuilder = $db->table('`order`');
        $detailBuilder->where('is_deleted', 0);
        if (!empty($service) && $service !== 'all' && !$compareMode) {
            $detailBuilder->where('service_type', $service);
        }

        if ($timeframe === 'day') {
            // Specific date, grouped by hour (time scale labels)
            $detailBuilder->where('DATE(created_date)', $baseDate);
            $detailBuilder->select('HOUR(created_date) AS period_hour');
            $detailBuilder->select('service_type');
            $detailBuilder->select('SUM(amount) AS total');
            $detailBuilder->select('COUNT(order_id) AS orders');
            $detailBuilder->groupBy(['HOUR(created_date)', 'service_type']);
            $detailBuilder->orderBy('period_hour', 'ASC');

            // Pre-seed 24 hourly buckets so chart always shows time scale clearly.
            for ($h = 0; $h < 24; $h++) {
                $rawKey = str_pad((string)$h, 2, '0', STR_PAD_LEFT) . ':00';
                $displayHour = ($h === 0) ? '12 AM' : (($h < 12) ? ($h . ' AM') : (($h === 12) ? '12 PM' : (($h - 12) . ' PM')));
                $rawKeys[] = $rawKey;
                $labels[] = $displayHour;
                $values[] = 0.0;
                $periodDetails[$rawKey] = [
                    'total_revenue'    => 0.0,
                    'total_orders'     => 0,
                    'avg_order'        => 0.0,
                    'storage_revenue'  => 0.0,
                    'storage_orders'   => 0,
                    'delivery_revenue' => 0.0,
                    'delivery_orders'  => 0,
                    'other_revenue'    => 0.0,
                    'other_orders'     => 0,
                ];
                $serviceSeries['storage'][] = 0.0;
                $serviceSeries['delivery'][] = 0.0;
                $serviceSeries['other'][] = 0.0;
            }
            $periodMap = array_flip($rawKeys);
        } elseif ($timeframe === 'week') {
            $endDate   = $baseDate;
            $startDate = date('Y-m-d', strtotime($endDate . ' -6 days'));
            $detailBuilder->where('DATE(created_date) >=', $startDate);
            $detailBuilder->where('DATE(created_date) <=', $endDate);
            $detailBuilder->select('DATE(created_date) AS period_key');
            $detailBuilder->select('service_type');
            $detailBuilder->select('SUM(amount) AS total');
            $detailBuilder->select('COUNT(order_id) AS orders');
            $detailBuilder->groupBy(['DATE(created_date)', 'service_type']);
            $detailBuilder->orderBy('period_key', 'ASC');

            $periodMap = [];
            for ($i = 0; $i < 7; $i++) {
                $d = date('Y-m-d', strtotime($startDate . " +{$i} days"));
                $rawKeys[] = $d;
                $labels[]  = date('M d', strtotime($d));
                $values[]  = 0.0;
                $periodMap[$d] = $i;
                $periodDetails[$d] = [
                    'total_revenue'    => 0.0,
                    'total_orders'     => 0,
                    'avg_order'        => 0.0,
                    'storage_revenue'  => 0.0,
                    'storage_orders'   => 0,
                    'delivery_revenue' => 0.0,
                    'delivery_orders'  => 0,
                    'other_revenue'    => 0.0,
                    'other_orders'     => 0,
                ];
                $serviceSeries['storage'][] = 0.0;
                $serviceSeries['delivery'][] = 0.0;
                $serviceSeries['other'][] = 0.0;
            }
        } elseif ($timeframe === 'month') {
            $monthStart = date('Y-m-01', strtotime($baseDate));
            $monthEnd   = date('Y-m-t', strtotime($baseDate));
            $daysInMonth = (int)date('t', strtotime($baseDate));

            $detailBuilder->where('DATE(created_date) >=', $monthStart);
            $detailBuilder->where('DATE(created_date) <=', $monthEnd);
            $detailBuilder->select('DATE(created_date) AS period_key');
            $detailBuilder->select('service_type');
            $detailBuilder->select('SUM(amount) AS total');
            $detailBuilder->select('COUNT(order_id) AS orders');
            $detailBuilder->groupBy(['DATE(created_date)', 'service_type']);
            $detailBuilder->orderBy('period_key', 'ASC');

            $periodMap = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $dateKey = date('Y-m-', strtotime($baseDate)) . str_pad((string)$d, 2, '0', STR_PAD_LEFT);
                $rawKeys[] = $dateKey;
                $labels[]  = date('M d', strtotime($dateKey));
                $values[]  = 0.0;
                $periodMap[$dateKey] = $d - 1;
                $periodDetails[$dateKey] = [
                    'total_revenue'    => 0.0,
                    'total_orders'     => 0,
                    'avg_order'        => 0.0,
                    'storage_revenue'  => 0.0,
                    'storage_orders'   => 0,
                    'delivery_revenue' => 0.0,
                    'delivery_orders'  => 0,
                    'other_revenue'    => 0.0,
                    'other_orders'     => 0,
                ];
                $serviceSeries['storage'][] = 0.0;
                $serviceSeries['delivery'][] = 0.0;
                $serviceSeries['other'][] = 0.0;
            }
        } elseif ($timeframe === 'range') {
            $rangeStart = $this->request->getGet('range_start') ?? date('Y-m-01');
            $rangeEnd   = $this->request->getGet('range_end')   ?? date('Y-m-d');
            $startTs    = strtotime($rangeStart);
            $endTs      = strtotime($rangeEnd);
            if ($endTs < $startTs) { $endTs = $startTs; $rangeEnd = $rangeStart; }
            if (($endTs - $startTs) > 366 * 86400) {
                $endTs    = $startTs + 366 * 86400;
                $rangeEnd = date('Y-m-d', $endTs);
            }

            $detailBuilder->where('DATE(created_date) >=', $rangeStart);
            $detailBuilder->where('DATE(created_date) <=', $rangeEnd);
            $detailBuilder->select('DATE(created_date) AS period_key');
            $detailBuilder->select('service_type');
            $detailBuilder->select('SUM(amount) AS total');
            $detailBuilder->select('COUNT(order_id) AS orders');
            $detailBuilder->groupBy(['DATE(created_date)', 'service_type']);
            $detailBuilder->orderBy('period_key', 'ASC');

            $periodMap = [];
            $cur = $startTs;
            while ($cur <= $endTs) {
                $dateKey   = date('Y-m-d', $cur);
                $rawKeys[] = $dateKey;
                $labels[]  = date('M d', $cur);
                $values[]  = 0.0;
                $periodMap[$dateKey] = count($rawKeys) - 1;
                $periodDetails[$dateKey] = [
                    'total_revenue'    => 0.0, 'total_orders'     => 0,  'avg_order'        => 0.0,
                    'storage_revenue'  => 0.0, 'storage_orders'   => 0,
                    'delivery_revenue' => 0.0, 'delivery_orders'  => 0,
                    'other_revenue'    => 0.0, 'other_orders'     => 0,
                ];
                $serviceSeries['storage'][]  = 0.0;
                $serviceSeries['delivery'][] = 0.0;
                $serviceSeries['other'][]    = 0.0;
                $cur = strtotime('+1 day', $cur);
            }
        } else { // year (default)
            $detailBuilder->where('YEAR(created_date)', $selectedYear);
            $detailBuilder->select('MONTH(created_date) AS period_month');
            $detailBuilder->select('service_type');
            $detailBuilder->select('SUM(amount) AS total');
            $detailBuilder->select('COUNT(order_id) AS orders');
            $detailBuilder->groupBy(['MONTH(created_date)', 'service_type']);
            $detailBuilder->orderBy('period_month', 'ASC');

            $periodMap = [];
            for ($m = 1; $m <= 12; $m++) {
                $rawKey = $selectedYear . '-' . str_pad((string)$m, 2, '0', STR_PAD_LEFT);
                $rawKeys[] = $rawKey;
                $labels[]  = date('M', mktime(0, 0, 0, $m, 1, $selectedYear)) . ' ' . $selectedYear;
                $values[]  = 0.0;
                $periodMap[$rawKey] = $m - 1;
                $periodDetails[$rawKey] = [
                    'total_revenue'    => 0.0,
                    'total_orders'     => 0,
                    'avg_order'        => 0.0,
                    'storage_revenue'  => 0.0,
                    'storage_orders'   => 0,
                    'delivery_revenue' => 0.0,
                    'delivery_orders'  => 0,
                    'other_revenue'    => 0.0,
                    'other_orders'     => 0,
                ];
                $serviceSeries['storage'][] = 0.0;
                $serviceSeries['delivery'][] = 0.0;
                $serviceSeries['other'][] = 0.0;
            }
        }

        $detailRows = $detailBuilder->get()->getResultArray();
        foreach ($detailRows as $row) {
            if ($timeframe === 'day') {
                $periodKey = str_pad((string)((int)$row['period_hour']), 2, '0', STR_PAD_LEFT) . ':00';
            } elseif ($timeframe === 'year') {
                $periodKey = $selectedYear . '-' . str_pad((string)((int)$row['period_month']), 2, '0', STR_PAD_LEFT);
            } else {
                $periodKey = (string)$row['period_key'];
            }

            if (!array_key_exists($periodKey, $periodMap)) {
                continue;
            }

            $serviceType = strtolower((string)($row['service_type'] ?? 'other'));
            if (!array_key_exists($serviceType, $serviceSeries)) {
                $serviceType = 'other';
            }

            $amount = (float)($row['total'] ?? 0);
            $count  = (int)($row['orders'] ?? 0);
            $idx    = $periodMap[$periodKey];

            $serviceSeries[$serviceType][$idx] = $amount;
            $periodDetails[$periodKey]['total_revenue'] += $amount;
            $periodDetails[$periodKey]['total_orders']  += $count;
            $periodDetails[$periodKey][$serviceType . '_revenue'] += $amount;
            $periodDetails[$periodKey][$serviceType . '_orders'] += $count;
        }

        // Final total series + averages
        foreach ($rawKeys as $i => $key) {
            $values[$i] = (float)($periodDetails[$key]['total_revenue'] ?? 0);
            $orders = (int)($periodDetails[$key]['total_orders'] ?? 0);
            $periodDetails[$key]['avg_order'] = $orders > 0
                ? ((float)$periodDetails[$key]['total_revenue'] / $orders)
                : 0.0;
        }

        return $this->response->setJSON([
            'labels'         => $labels,
            'values'         => $values,
            'raw_keys'       => $rawKeys,
            'period_details' => $periodDetails,
            'service_series' => $serviceSeries,
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

    public function order($id = null)
    {
        helper('form');
        
        $status = $this->request->getGet('status');
        $service = $this->request->getGet('service_type');
        $start = $this->request->getGet('start_date');
        $end   = $this->request->getGet('end_date');

        $orderModel = new Order_model();

        // If specific order ID is provided, show that order
        if ($id !== null) {
            $order = $orderModel->find($id);
            if ($order) {
                $data['orders'] = [$order];
                $data['pager'] = null;
                $data['single_order'] = true;
                return $this->render('admin/order', $data);
            }
        }

        // Otherwise, show the order list with filters
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
        $data['single_order'] = false;

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
            
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'     => true,
                'new_status'  => $newStatus,
                'status_text' => $statusText[$newStatus],
            ]);
        }

        session()->setFlashdata('order_status_success', [
            'order_id' => $order_id,
            'status'   => $statusText[$newStatus],
            'username' => session()->get('username') ?: 'Unknown'
        ]);
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Order not found.']);
            }
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

    public function order_details($order_id)
    {
        $orderModel = new \App\Models\Order_model();
        $order = $orderModel->getOrderWithUserById($order_id);

        if (!$order) {
            return redirect()->to(base_url('/admin/refund_request'))
                ->with('error', 'Order not found.');
        }

        $details = json_decode($order['order_details_json'] ?? '{}', true);

        if (!is_array($details)) {
            $details = [];
        }

        return $this->render('admin/order_details', [
            'order'   => $order,
            'details' => $details
        ]);
    }

    public function save_note()
    {
        $orderId  = $this->request->getPost('order_id');
        $note     = trim($this->request->getPost('note') ?? '');
        $userId   = session()->get('user_id');

        $orderModel = new Order_model();
        $existing   = $orderModel->find($orderId);
        $prevNote   = trim($existing['comment'] ?? '');

        $orderModel->update($orderId, [
            'comment'       => $note,
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_by'   => $userId,
        ]);

        if ($note === '') {
            $action     = 'Deleted note';
            $actionType = 'deleted';
        } elseif ($prevNote === '') {
            $action     = 'Added note: "' . $note . '"';
            $actionType = 'added';
        } else {
            $action     = 'Edited note: "' . $note . '"';
            $actionType = 'edited';
        }

        $logModel = new ActivityLogModel();
        $logModel->insert([
            'order_id'      => $orderId,
            'user_id'       => $userId,
            'username'      => session()->get('username'),
            'action'        => $action,
            'modified_date' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['status' => 'success', 'action' => $actionType]);
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
        $base_price = $this->request->getPost('base_price');
        $extra_rate = $this->request->getPost('extra_rate');

        if (!is_numeric($base_price) || (int)$base_price < 1) {
            return redirect()->to('/admin/service_management')
                ->with('error', 'Base price must be greater than zero.');
        }

        if (!is_numeric($extra_rate) || (int)$extra_rate < 1) {
            return redirect()->to('/admin/service_management')
                ->with('error', 'Extra rate must be greater than zero.');
        }

        $model = new \App\Models\ServiceManagementModel();
        $model->update($id, [
            'base_price' => (int)$base_price,
            'extra_rate' => (int)$extra_rate,
        ]);

        return redirect()->to('/admin/service_management')
            ->with('success', 'Service pricing updated!');
    }

    public function transaction_history()
    {
        $paymentModel = new PaymentModel();
        // Fetch all transactions, you can add filters or pagination as needed
        $transactions = $paymentModel->orderBy('created_at', 'DESC')->findAll();
        $db = \Config\Database::connect();

        $data = [
            'transactions' => $transactions
        ];
        $refunds = $db->table('refund_form')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->render('admin/transaction_history', $data);
        return $this->render('admin/refund_request', [
            'refunds' => $refunds
        ]);
    }

    public function calendar(): string
    {
        $orderModel = new Order_model();
        $orders = $orderModel
            ->select('order_id, first_name, last_name, service_type, status, order_details_json')
            ->where('is_deleted', 0)
            ->orderBy('created_date', 'DESC')
            ->findAll();

        $events = $this->buildCalendarEventsFromOrders($orders);

        $initialView = $this->request->getGet('view');
        $allowedViews = ['dayGridMonth', 'timeGridWeek', 'timeGridDay'];
        if (! in_array($initialView, $allowedViews, true)) {
            $initialView = 'dayGridMonth';
        }

        return $this->render('admin/calendar', [
            'calendar_events_json' => json_encode($events, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP),
            'initial_view'           => $initialView,
        ]);
    }

    /**
     * @param list<array<string, mixed>> $orders
     * @return list<array<string, mixed>>
     */
    private function buildCalendarEventsFromOrders(array $orders): array
    {
        $events = [];

        foreach ($orders as $order) {
            $raw = $order['order_details_json'] ?? '';
            if ($raw === null || $raw === '') {
                continue;
            }

            $details = json_decode($raw, true);
            if (! is_array($details)) {
                continue;
            }

            $dropoff = $details['Drop-off DateTime'] ?? null;
            $pickup  = $details['Pickup DateTime'] ?? null;

            $customer = trim(($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? ''));
            $service  = (string) ($order['service_type'] ?? '');
            $orderId  = (int) ($order['order_id'] ?? 0);
            $status   = (int) ($order['status'] ?? 0);

            foreach (['dropoff' => $dropoff, 'pickup' => $pickup] as $type => $dtStr) {
                $startIso = $this->parseOrderDetailDateTime($dtStr);
                if ($startIso === null) {
                    continue;
                }

                $startTs = strtotime($startIso);
                if ($startTs === false) {
                    continue;
                }

                // Visible time block (drop-off / pickup are scheduled points)
                $endIso = date('c', $startTs + 45 * 60);

                $label = $type === 'dropoff' ? 'Drop-off' : 'Pickup';
                $color = $this->calendarColorForStatus($status);

                $events[] = [
                    'id'              => $orderId . '-' . $type,
                    'title'           => '#' . $orderId . ' · ' . $label . ' — ' . ($service !== '' ? strtoupper($service) : 'Order'),
                    'start'           => $startIso,
                    'end'             => $endIso,
                    'backgroundColor' => $color['bg'],
                    'borderColor'     => $color['border'],
                    'textColor'       => $color['text'],
                    'extendedProps'   => [
                        'orderId'  => $orderId,
                        'kind'     => $type,
                        'customer' => $customer,
                        'status'   => $status,
                        'service'  => $service,
                    ],
                ];
            }
        }

        return $events;
    }

    /**
     * Values are stored like "Y-m-d at H:i" (see OrderModel::formatOrderDetailsJson).
     */
    private function parseOrderDetailDateTime(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = trim((string) $value);
        if (strcasecmp($value, 'Null') === 0) {
            return null;
        }

        if (preg_match('/^(.+?)\s+at\s+(.+)$/u', $value, $m)) {
            $combined = trim($m[1]) . ' ' . trim($m[2]);
        } else {
            $combined = $value;
        }

        $ts = strtotime($combined);
        if ($ts === false) {
            return null;
        }

        return date('c', $ts);
    }
    
    public function refund_request()
    {
        $db = \Config\Database::connect();

        $refunds = $db->table('refund_form rf')
            ->select('rf.*, u.username AS status_updated_username')
            ->join('user u', 'u.user_id = rf.status_updated_by', 'left')
            ->orderBy('rf.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->render('admin/refund_request', [
            'refunds' => $refunds
        ]);
    }

    public function change_refund_status()
    {
        $refundId  = (int) $this->request->getPost('refund_id');
        $newStatus = (int) $this->request->getPost('new_status');

        $statusMap = [
            0 => 'In Progress',
            1 => 'Agreed',
            2 => 'Rejected',
        ];

        if (!$refundId || !isset($statusMap[$newStatus]) || $newStatus === 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid refund status.'
            ]);
        }

        $db = \Config\Database::connect();

        $refund = $db->table('refund_form')
            ->where('id', $refundId)
            ->get()
            ->getRowArray();

        if (!$refund) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Refund record not found.'
            ]);
        }

        $userId   = session()->get('user_id');
        $username = session()->get('username') ?: 'Unknown';
        $now      = date('Y-m-d H:i:s');

        $updated = $db->table('refund_form')
            ->where('id', $refundId)
            ->update([
                'status_progress'   => $newStatus,
                'status_updated_by' => $userId,
                'status_updated_at' => $now,
            ]);

        if (!$updated) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unable to update refund status.'
            ]);
        }

        return $this->response->setJSON([
            'success'      => true,
            'status_label' => $statusMap[$newStatus],
            'username'     => $username,
            'updated_at'   => $now,
        ]);
    }

    /**
     * @return array{bg: string, border: string, text: string}
     */
    private function calendarColorForStatus(int $status): array
    {
        if ($status === 2) {
            return ['bg' => '#198754', 'border' => '#146c43', 'text' => '#ffffff'];
        }
        if ($status === 1) {
            return ['bg' => '#0d6efd', 'border' => '#0a58ca', 'text' => '#ffffff'];
        }

        return ['bg' => '#ffc107', 'border' => '#cc9a06', 'text' => '#212529'];
    }
}
