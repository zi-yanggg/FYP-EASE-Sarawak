<?php

namespace App\Controllers\Admin;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\User_model;
use App\Models\ActivityLogModel;
use App\Models\MessageModel;
use App\Services\OrderDetailsService;
class DashboardController extends BaseAdminController
{
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

        // ── Order counts by status (single aggregate query) ───────────────
        $statusRows = $db->table('`order`')
            ->select('status, COUNT(*) AS cnt')
            ->where('is_deleted', 0)
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $statusMap = [0 => 0, 1 => 0, 2 => 0];
        foreach ($statusRows as $row) {
            $statusMap[(int) ($row['status'] ?? -1)] = (int) ($row['cnt'] ?? 0);
        }

        $totalOrders     = array_sum($statusMap);
        $pendingCount    = $statusMap[0];
        $inProgressCount = $statusMap[1];
        $completedCount  = $statusMap[2];
        $userCount       = (int)$user_model->where('is_deleted', 0)->countAllResults();

        // ── Revenue metrics ──────────────────────────────────────────────
        $todayRevenue = (float)($db->table('`order`')->selectSum('amount')
            ->where('is_deleted', 0)
            ->where('created_date >=', $today . ' 00:00:00')
            ->where('created_date <=', $today . ' 23:59:59')
            ->get()->getRow()->amount ?? 0);
        $yesterdayRevenue = (float)($db->table('`order`')->selectSum('amount')
            ->where('is_deleted', 0)
            ->where('created_date >=', $yesterday . ' 00:00:00')
            ->where('created_date <=', $yesterday . ' 23:59:59')
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
            ->where('created_date >=', $today . ' 00:00:00')
            ->where('created_date <=', $today . ' 23:59:59')
            ->countAllResults();
        $yesterdayOrders = (int)$db->table('`order`')->where('is_deleted', 0)
            ->where('created_date >=', $yesterday . ' 00:00:00')
            ->where('created_date <=', $yesterday . ' 23:59:59')
            ->countAllResults();
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

        $detailsService = new OrderDetailsService();

        // ── Active orders with booking schedule data when available ───────
        $orderBuilder = $db->table('`order` o')
            ->select('o.order_id, o.first_name, o.last_name, o.service_type, o.status, o.amount, o.order_details_json, o.created_date')
            ->where('o.is_deleted', 0);

        if ($db->tableExists('order_booking')) {
            $orderBuilder
                ->select('b.dropoff_at, b.pickup_at, b.origin, b.destination, b.storage_location, b.booking_json')
                ->join('order_booking b', 'b.order_id = o.order_id', 'left');
        }

        $allActiveOrders = $orderBuilder->get()->getResultArray();

        $todayPickups     = [];
        $todayDropoffs    = [];
        $calendarPickups  = [];
        $calendarDropoffs = [];

        foreach ($allActiveOrders as $ord) {
            $bookingRow = [
                'dropoff_at'   => $ord['dropoff_at'] ?? null,
                'pickup_at'    => $ord['pickup_at'] ?? null,
                'booking_json' => $ord['booking_json'] ?? null,
            ];

            $pickupDate  = ! empty($bookingRow['pickup_at']) ? substr((string) $bookingRow['pickup_at'], 0, 10) : null;
            $dropoffDate = ! empty($bookingRow['dropoff_at']) ? substr((string) $bookingRow['dropoff_at'], 0, 10) : null;

            if ($pickupDate === null || $dropoffDate === null) {
                $display   = $detailsService->displayDetails($ord, $bookingRow['booking_json'] ? $bookingRow : null);
                $pickupRaw = trim((string) ($display['Pickup DateTime'] ?? ''));
                $dropRaw   = trim((string) ($display['Drop-off DateTime'] ?? ''));

                if ($pickupDate === null && $pickupRaw !== '' && strcasecmp($pickupRaw, 'Null') !== 0) {
                    $pickupDate = substr($pickupRaw, 0, 10);
                }
                if ($dropoffDate === null && $dropRaw !== '' && strcasecmp($dropRaw, 'Null') !== 0) {
                    $dropoffDate = substr($dropRaw, 0, 10);
                }
            }

            if ($pickupDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $pickupDate)) {
                $calendarPickups[$pickupDate] = ($calendarPickups[$pickupDate] ?? 0) + 1;
                if ($pickupDate === $today) {
                    $eventTime = ! empty($bookingRow['pickup_at'])
                        ? substr((string) $bookingRow['pickup_at'], 11, 5)
                        : '';
                    $todayPickups[] = $ord + ['event_time' => $eventTime];
                }
            }

            if ($dropoffDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dropoffDate)) {
                $calendarDropoffs[$dropoffDate] = ($calendarDropoffs[$dropoffDate] ?? 0) + 1;
                if ($dropoffDate === $today) {
                    $eventTime = ! empty($bookingRow['dropoff_at'])
                        ? substr((string) $bookingRow['dropoff_at'], 11, 5)
                        : '';
                    $todayDropoffs[] = $ord + ['event_time' => $eventTime];
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
        $parseForDisplay = static function (array $orders) use ($detailsService): array {
            $bookingMap = $detailsService->mapBookingsByOrderId($orders);

            return array_map(static function ($o) use ($detailsService, $bookingMap) {
                $bookingRow = $bookingMap[(int) ($o['order_id'] ?? 0)] ?? [
                    'dropoff_at'   => $o['dropoff_at'] ?? null,
                    'pickup_at'    => $o['pickup_at'] ?? null,
                    'booking_json' => $o['booking_json'] ?? null,
                ];

                return $detailsService->enrichOrderRow($o, $bookingRow) + [
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

}
