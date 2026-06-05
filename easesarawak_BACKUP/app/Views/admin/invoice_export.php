<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EASE Sarawak – Revenue Invoice <?= esc(date('M Y', strtotime($startDate))) ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/invoice_export.css') ?>">
</head>
<body>

<!-- Screen toolbar -->
<div class="toolbar">
    <button class="btn-back" onclick="window.location.href='<?= base_url('report') ?>'">&#8592; Back to Reports</button>
    <a href="<?= base_url('report/export?format=csv&start_date=' . esc($startDate) . '&end_date=' . esc($endDate)) ?>" style="text-decoration:none;">
        <button class="btn-csv">&#8595; Download CSV</button>
    </a>
    <button class="btn-print" onclick="window.print()">&#128438; Print / Save as PDF</button>
</div>

<div class="page">

    <!-- ── Gold accent bar ── -->
    <div class="inv-header-bar"></div>

    <!-- ── Invoice Header ── -->
    <div class="inv-header">
        <div class="brand">
            <img src="<?= base_url('assets/images/Ease_PNG_File-01-1.png') ?>" alt="EASE Sarawak">
            <p>Luggage Storage &amp; Delivery Service<br>Sarawak, Malaysia</p>
        </div>
        <div class="inv-title">
            <h1>Revenue Invoice</h1>
            <div class="inv-badge">OFFICIAL DOCUMENT</div>
            <p>Ref: ESR-<?= date('Ym', strtotime($startDate)) ?>-<?= str_pad($totalOrders, 3, '0', STR_PAD_LEFT) ?></p>
            <p>Generated: <?= esc($generatedAt) ?></p>
        </div>
    </div>

    <!-- ── Meta strip ── -->
    <div class="inv-meta">
        <div class="meta-item">
            <label>Invoice Period</label>
            <span><?= date('d M Y', strtotime($startDate)) ?> &ndash; <?= date('d M Y', strtotime($endDate)) ?></span>
        </div>
        <div class="meta-item">
            <label>Total Orders</label>
            <span><?= $totalOrders ?></span>
        </div>
        <div class="meta-item">
            <label>Total Revenue</label>
            <span>RM <?= number_format($totalRevenue, 2) ?></span>
        </div>
        <div class="meta-item">
            <label>Currency</label>
            <span>MYR (Malaysian Ringgit)</span>
        </div>
    </div>

    <!-- ── Service Summary ── -->
    <div class="section-title">Service Summary</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Service Type</th>
                <th>No. of Orders</th>
                <th>Revenue (RM)</th>
                <th>% of Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($byService as $svc => $info): ?>
            <tr>
                <td><?= esc($svc) ?></td>
                <td><?= $info['count'] ?></td>
                <td>RM <?= number_format($info['total'], 2) ?></td>
                <td><?= $totalRevenue > 0 ? number_format(($info['total'] / $totalRevenue) * 100, 1) : '0.0' ?>%</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>TOTAL</td>
                <td><?= $totalOrders ?></td>
                <td>RM <?= number_format($totalRevenue, 2) ?></td>
                <td>100%</td>
            </tr>
        </tfoot>
    </table>

    <!-- ── Order Details ── -->
    <div class="section-title">Order Details</div>

    <?php if (empty($orders)): ?>
        <p style="color:#999; font-style:italic; margin-bottom:20px;">No orders found for the selected period.</p>
    <?php else: ?>
    <table class="order-table">
        <thead>
            <tr>
                <th class="col-num">#</th>
                <th class="col-id">Order ID</th>
                <th class="col-date">Date</th>
                <th class="col-customer">Customer</th>
                <th class="col-email">Email</th>
                <th class="col-service">Service</th>
                <th class="col-payment">Payment</th>
                <th class="col-promo">Promo</th>
                <th class="col-status">Status</th>
                <th class="col-amount">Amount (RM)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $statusMap  = [0 => 'Pending', 1 => 'In Progress', 2 => 'Completed'];
            $badgeClass = [0 => 'badge-pending', 1 => 'badge-progress', 2 => 'badge-completed'];
            $rowNum = 1;
            foreach ($orders as $order):
                $statusLabel = $statusMap[$order['status']] ?? 'Unknown';
                $badgeCls    = $badgeClass[$order['status']] ?? 'badge-pending';
            ?>
            <tr>
                <td class="col-num"><?= $rowNum++ ?></td>
                <td class="col-id"><strong>#<?= esc($order['order_id']) ?></strong></td>
                <td class="col-date"><?= date('d M Y', strtotime($order['created_date'])) ?></td>
                <td class="col-customer"><?= esc(trim($order['first_name'] . ' ' . $order['last_name'])) ?></td>
                <td class="col-email"><?= esc($order['email']) ?></td>
                <td class="col-service"><?= strtoupper(esc($order['service_type'] ?? '-')) ?></td>
                <td class="col-payment"><?= esc($order['payment_method'] ?? '-') ?></td>
                <td class="col-promo"><?= esc($order['promo_code'] ?: '-') ?></td>
                <td class="col-status"><span class="badge <?= $badgeCls ?>"><?= $statusLabel ?></span></td>
                <td class="col-amount"><?= number_format($order['amount'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="grand-total">
                <td colspan="9" style="text-align:right; text-transform:uppercase; letter-spacing:.5px; font-size:11px;">Grand Total</td>
                <td class="col-amount">RM <?= number_format($totalRevenue, 2) ?></td>
            </tr>
        </tfoot>
    </table>
    <?php endif; ?>

    <!-- ── Footer ── -->
    <div class="inv-footer">
        <span><strong>EASE Sarawak</strong> &mdash; Luggage Storage &amp; Delivery Service, Sarawak, Malaysia</span>
        <span>Generated by Admin Portal &bull; <?= esc($generatedAt) ?></span>
    </div>

</div><!-- /.page -->
</body>
</html>
