<?php

$src = file_get_contents(__DIR__ . '/../app/Controllers/Admin.php');

// Remove initController auth block
$src = preg_replace(
    '/    public function initController\(RequestInterface \$request, ResponseInterface \$response, LoggerInterface \$logger\)\s*\{[\s\S]*?\n    \}\n\n/',
    '',
    $src,
    1
);

// Remove private helper methods (now in BaseAdminController)
foreach (['buildCalendarEventsFromOrders', 'parseOrderDetailDateTime', 'calendarColorForStatus', '_exportCsv'] as $fn) {
    $src = preg_replace(
        '/    (?:\/\*\*[\s\S]*?\*\/\s*)?private function ' . $fn . '\([\s\S]*?\n    \}\n\n/',
        '',
        $src,
        1
    );
}

preg_match_all(
    '/    ((?:public|private) function \w+\([^)]*\)(?:\s*:\s*\w+)?\s*\{)/',
    $src,
    $headers,
    PREG_OFFSET_CAPTURE
);

$methodMap = [];
$count = count($headers[0]);

for ($i = 0; $i < $count; $i++) {
    $start = $headers[0][$i][1];
    $end   = ($i + 1 < $count) ? $headers[0][$i + 1][1] : strrpos($src, "\n}");
    $chunk = substr($src, $start, $end - $start);

    if (preg_match('/function (\w+)\(/', $chunk, $m)) {
        $methodMap[$m[1]] = rtrim($chunk) . "\n";
    }
}

$groups = [
    'DashboardController'   => ['index'],
    'ContactController'     => ['contact', 'markMessageRead', 'markAllMessagesRead', 'getMessage'],
    'ReportController'      => ['report', 'getRevenueData', 'getPeakTimesData', 'exportRevenue'],
    'OrderController'       => ['order', 'order_activity_log', 'change_status', 'getDetails', 'order_details', 'save_note'],
    'UserController'        => ['user', 'create_user', 'edit', 'update', 'delete'],
    'ServiceController'     => ['service_management', 'update_service_price'],
    'TransactionController' => ['transaction_history'],
    'CalendarController'    => ['calendar'],
    'RefundController'      => ['refund_request', 'change_refund_status'],
];

$uses = <<<'PHP'
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\User_model;
use App\Models\ActivityLogModel;
use App\Models\MessageModel;

PHP;

$outDir = __DIR__ . '/../app/Controllers/Admin/';

foreach ($groups as $class => $methods) {
    $body = '';
    foreach ($methods as $m) {
        if (! isset($methodMap[$m])) {
            fwrite(STDERR, "Missing method: $m for $class\n");
            continue;
        }
        $body .= $methodMap[$m] . "\n";
    }

    $body = str_replace('Order_model', 'OrderModel', $body);
    $body = str_replace('new \\App\\Models\\OrderModel()', 'new OrderModel()', $body);
    $body = str_replace('$this->_exportCsv', '$this->exportCsv', $body);

    $content = "<?php\n\nnamespace App\\Controllers\\Admin;\n\n{$uses}class {$class} extends BaseAdminController\n{\n{$body}}\n";
    file_put_contents($outDir . $class . '.php', $content);
    echo "Created {$class}.php\n";
}
