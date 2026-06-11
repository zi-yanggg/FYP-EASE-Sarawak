<?php

namespace App\Controllers\Admin;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\User_model;
use App\Models\ActivityLogModel;
use App\Models\MessageModel;
class ReportController extends BaseAdminController
{
    public function report()
    {
        $order_model = new OrderModel();

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
            ->groupBy('email, first_name')
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
            $detailBuilder->where('created_date >=', $baseDate . ' 00:00:00');
            $detailBuilder->where('created_date <=', $baseDate . ' 23:59:59');
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
            $detailBuilder->where('created_date >=', $startDate . ' 00:00:00');
            $detailBuilder->where('created_date <=', $endDate . ' 23:59:59');
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

            $detailBuilder->where('created_date >=', $monthStart . ' 00:00:00');
            $detailBuilder->where('created_date <=', $monthEnd . ' 23:59:59');
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

            $detailBuilder->where('created_date >=', $rangeStart . ' 00:00:00');
            $detailBuilder->where('created_date <=', $rangeEnd . ' 23:59:59');
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
                if ($start) {
                    $builder->where('created_date >=', $start . ' 00:00:00');
                }
                if ($end) {
                    $builder->where('created_date <=', $end . ' 23:59:59');
                }
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
        $builder->where('created_date >=', $startDate . ' 00:00:00');
        $builder->where('created_date <=', $endDate . ' 23:59:59');
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
            return $this->exportCsv($orders, $startDate, $endDate, $byService, $totalRevenue);
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

}
