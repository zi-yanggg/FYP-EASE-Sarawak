<?php

namespace App\Controllers\Admin;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\User_model;
use App\Models\ActivityLogModel;
use App\Models\MessageModel;
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

}
