<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Shared base for admin portal controllers.
 * Authentication is enforced by AuthFilter on route groups.
 */
abstract class BaseAdminController extends BaseController
{
    public array $data = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->data['username'] = session()->get('username');
    }

    /**
     * @return array{bg: string, border: string, text: string}
     */
    protected function calendarColorForStatus(int $status): array
    {
        if ($status === 2) {
            return ['bg' => '#198754', 'border' => '#146c43', 'text' => '#ffffff'];
        }
        if ($status === 1) {
            return ['bg' => '#0d6efd', 'border' => '#0a58ca', 'text' => '#ffffff'];
        }

        return ['bg' => '#ffc107', 'border' => '#cc9a06', 'text' => '#212529'];
    }

    protected function parseOrderDetailDateTime(mixed $value): ?string
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

        return $ts === false ? null : date('c', $ts);
    }

    /**
     * @param list<array<string, mixed>> $orders
     * @return list<array<string, mixed>>
     */
    protected function buildCalendarEventsFromOrders(array $orders): array
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

                $endIso = date('c', $startTs + 45 * 60);
                $label  = $type === 'dropoff' ? 'Drop-off' : 'Pickup';
                $color  = $this->calendarColorForStatus($status);

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

    protected function exportCsv(array $orders, string $startDate, string $endDate, array $byService, float $totalRevenue)
    {
        $filename = 'EASE-Sarawak-Revenue-' . $startDate . '-to-' . $endDate . '.csv';
        $tmp      = fopen('php://temp', 'w+');

        fwrite($tmp, "\xEF\xBB\xBF");

        fputcsv($tmp, ['EASE Sarawak - Revenue Invoice Report']);
        fputcsv($tmp, ['Period: ' . date('d M Y', strtotime($startDate)) . ' to ' . date('d M Y', strtotime($endDate))]);
        fputcsv($tmp, ['Generated: ' . date('d M Y, h:i A')]);
        fputcsv($tmp, []);

        fputcsv($tmp, ['--- Service Summary ---']);
        fputcsv($tmp, ['Service', 'Orders', 'Revenue (RM)']);
        foreach ($byService as $svc => $info) {
            fputcsv($tmp, [$svc, $info['count'], number_format($info['total'], 2)]);
        }
        fputcsv($tmp, ['TOTAL', count($orders), number_format($totalRevenue, 2)]);
        fputcsv($tmp, []);

        fputcsv($tmp, ['--- Order Details ---']);
        fputcsv($tmp, [
            'Order ID', 'Order Date', 'Customer Name', 'Email', 'Phone',
            'Service Type', 'Payment Method', 'Promo Code', 'Status', 'Amount (RM)',
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
}
