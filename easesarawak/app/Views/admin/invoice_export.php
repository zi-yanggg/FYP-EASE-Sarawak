<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EASE Sarawak – Revenue Invoice <?= esc(date('M Y', strtotime($startDate))) ?></title>
    <style>
        /* ── Theme ── */
        :root {
            --gold:       #f2be00;
            --gold-dark:  #c99d00;
            --gold-light: #fff9e0;
            --gold-mid:   #fdf0b0;
            --brown:      #5B532C;
            --text:       #1a1a1a;
        }

        /* ── Base ── */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: var(--text);
            background: #f0ede4;
        }

        /* ── Page wrapper ── */
        .page {
            width: 210mm;
            min-height: 297mm;
            background: #fff;
            margin: 20px auto;
            padding: 20mm 18mm 18mm;
            box-shadow: 0 0 16px rgba(0,0,0,.12);
        }

        /* ── Screen-only toolbar ── */
        .toolbar {
            width: 210mm;
            margin: 16px auto 0;
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }
        .toolbar button, .toolbar a button {
            padding: 8px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
        }
        .btn-print {
            background: var(--gold);
            color: var(--brown);
            border: 2px solid var(--gold-dark) !important;
        }
        .btn-print:hover { background: var(--gold-dark); color: #fff; }
        .btn-back  { background: #e9ecef; color: #333; border: 2px solid #ccc !important; }
        .btn-back:hover  { background: #d5d8db; }
        .btn-csv   {
            background: #fff;
            color: var(--brown);
            border: 2px solid var(--gold-dark) !important;
        }
        .btn-csv:hover { background: var(--gold-light); }

        /* ── Header accent bar ── */
        .inv-header-bar {
            height: 6px;
            background: linear-gradient(90deg, var(--gold), var(--gold-dark));
            border-radius: 3px 3px 0 0;
            margin-bottom: 0;
        }

        /* ── Header ── */
        .inv-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid var(--gold);
            padding-bottom: 14px;
            margin-bottom: 18px;
            padding-top: 14px;
        }
        .inv-header .brand img { height: 56px; }
        .inv-header .brand p  { font-size: 11px; color: #666; margin-top: 4px; }
        .inv-header .inv-title { text-align: right; }
        .inv-header .inv-title h1 {
            font-size: 22px;
            color: var(--brown);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 900;
        }
        .inv-header .inv-title .inv-badge {
            display: inline-block;
            background: var(--gold);
            color: var(--brown);
            font-size: 10px;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 20px;
            margin-top: 4px;
            letter-spacing: .5px;
        }
        .inv-header .inv-title p { font-size: 11px; color: #888; margin-top: 4px; }

        /* ── Meta strip ── */
        .inv-meta {
            display: flex;
            justify-content: space-between;
            background: var(--gold-light);
            border: 1.5px solid var(--gold);
            border-radius: 6px;
            padding: 10px 16px;
            margin-bottom: 20px;
        }
        .inv-meta .meta-item label {
            display: block;
            font-size: 9.5px;
            color: var(--brown);
            text-transform: uppercase;
            letter-spacing: .6px;
            font-weight: 700;
        }
        .inv-meta .meta-item span {
            font-size: 13px;
            font-weight: 800;
            color: var(--text);
        }

        /* ── Section heading ── */
        .section-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--brown);
            border-left: 4px solid var(--gold);
            padding-left: 8px;
            margin-bottom: 8px;
            background: var(--gold-light);
            padding: 5px 8px;
            border-radius: 0 4px 4px 0;
        }

        /* ── Summary table ── */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }
        .summary-table th {
            background: var(--gold);
            color: var(--brown);
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .summary-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #ececec;
            font-size: 12px;
        }
        .summary-table tr:nth-child(even) td { background: var(--gold-light); }
        .summary-table .total-row td {
            font-weight: 800;
            background: var(--gold-mid);
            border-top: 2px solid var(--gold-dark);
            color: var(--brown);
        }

        /* ── Order table ── */
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
            font-size: 10.5px;
            table-layout: fixed;   /* enforce column widths */
        }
        .order-table th {
            background: var(--brown);
            color: var(--gold);
            padding: 7px 6px;
            text-align: left;
            white-space: nowrap;
            font-size: 10px;
            letter-spacing: .3px;
            overflow: hidden;
        }
        .order-table td {
            padding: 5px 6px;
            border-bottom: 1px solid #e8e8e8;
            vertical-align: middle;
            overflow: hidden;
            word-break: break-word;
        }
        /* Fixed column widths — total = ~174mm usable on A4 */
        .order-table .col-num      { width: 18px;  }   /* #       */
        .order-table .col-id       { width: 30px;  }   /* Order ID */
        .order-table .col-date     { width: 28px;  }   /* Date    */
        .order-table .col-customer { width: 36px;  }   /* Customer */
        .order-table .col-email    { width: 44px;  }   /* Email   */
        .order-table .col-service  { width: 24px;  }   /* Service */
        .order-table .col-payment  { width: 28px;  }   /* Payment */
        .order-table .col-promo    { width: 30px;  max-width: 30px; word-break: break-all; } /* Promo */
        .order-table .col-status   { width: 26px;  }   /* Status  */
        .order-table .col-amount   { width: 30px;  text-align: right; font-weight: 700; }   /* Amount */

        .order-table tr:nth-child(even) td { background: var(--gold-light); }
        .order-table .grand-total td {
            font-weight: 800;
            background: var(--gold-mid);
            border-top: 2px solid var(--gold-dark);
            color: var(--brown);
        }

        /* ── Status badges ── */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 700;
        }
        .badge-pending   { background: #fff3cd; color: #856404; }
        .badge-progress  { background: #cce5ff; color: #004085; }
        .badge-completed { background: #d4edda; color: #155724; }

        /* ── Footer ── */
        .inv-footer {
            margin-top: 20px;
            border-top: 2px solid var(--gold);
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #888;
        }
        .inv-footer strong { color: var(--brown); }

        /* ── Print overrides ── */
        @media print {
            body { background: #fff; }
            .toolbar { display: none !important; }
            .page {
                margin: 0;
                box-shadow: none;
                padding: 12mm 14mm;
            }
            .order-table { font-size: 10px; }
            .inv-header-bar { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .summary-table th, .order-table th,
            .inv-meta, .section-title,
            .summary-table tr:nth-child(even) td,
            .order-table tr:nth-child(even) td,
            .summary-table .total-row td,
            .order-table .grand-total td {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
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
