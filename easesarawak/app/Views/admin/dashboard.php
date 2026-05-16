<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/report.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/admin/dashboard.css') ?>">
<style>
.wrapper .main-panel { overflow: hidden; }
.rpt-page            { height: 100%; }
</style>

<?php
$hour        = (int)date('G');
$greeting    = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
$sessionUser = session()->get('username') ?? 'Admin';

function dshSvcPill(string $type): string {
    $l   = strtolower($type);
    $cls = $l === 'storage' ? 'dsh-svc-pill--storage' : 'dsh-svc-pill--delivery';
    return '<span class="dsh-svc-pill '.$cls.'">'.strtoupper(htmlspecialchars($type)).'</span>';
}
function dshStatusBadge(int $s, int $orderId): string {
    $map = [
        0 => ['dsh-status--pending',   'Pending'],
        1 => ['dsh-status--progress',  'In Progress'],
        2 => ['dsh-status--completed', 'Completed'],
    ];
    [$cls, $label] = $map[$s] ?? ['', 'Unknown'];
    return '<span class="dsh-status-badge '.$cls.'" data-status="'.$s.'" data-order="'.$orderId.'"
        onclick="cycleStatus(this)" title="Click to advance status">'.$label.'</span>';
}
function dshTimeAgo(string $dt): string {
    $diff = time() - strtotime($dt);
    if ($diff < 60)    return 'just now';
    if ($diff < 3600)  return (int)($diff/60).'m ago';
    if ($diff < 86400) return (int)($diff/3600).'h ago';
    return (int)($diff/86400).'d ago';
}
?>

<div class="rpt-page">
<div class="page-inner dsh-one-screen">

    <!-- ═══════════════════════════════════════════════════════
         GREETING HEADER
    ════════════════════════════════════════════════════════ -->
    <div class="dsh-one-screen__head ease-page-head d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="ease-crumb">EASE Admin &middot; <b>Dashboard</b></div>
            <div class="dsh-greeting-title"><?= esc($greeting) ?>, <b><?= esc($sessionUser) ?></b></div>
            <div class="dsh-greeting-sub">
                <i class="fas fa-calendar-alt me-1"></i><?= date('l, d F Y') ?>
                &nbsp;·&nbsp;
                <i class="fas fa-clock me-1"></i><span id="live-time"></span>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         KPI CYCLING CARDS
    ════════════════════════════════════════════════════════ -->
    <div class="dsh-one-screen__kpis">
        <div class="dsh-kpi-row">

            <!-- Card 1 · Revenue (4 slides) -->
            <div class="rpt-kpi-card dsh-kpi-card" onclick="cycleKpi(this)">
                <div class="kpi-slide active">
                    <div class="rpt-kpi-top">
                        <div>
                            <div class="rpt-kpi-label">Today's Revenue</div>
                            <div class="rpt-kpi-value">RM <?= number_format($todayRevenue, 2) ?></div>
                        </div>
                        <div class="rpt-kpi-icon"><i class="fas fa-coins"></i></div>
                    </div>
                    <div class="rpt-kpi-foot">
                        <?php if ($todayRevenueDelta > 0): ?>
                            <span class="rev-growth-badge up"><i class="fas fa-arrow-up"></i><?= abs($todayRevenueDelta) ?>%</span>
                            <span class="rpt-kpi-sub">vs yesterday</span>
                        <?php elseif ($todayRevenueDelta < 0): ?>
                            <span class="rev-growth-badge down"><i class="fas fa-arrow-down"></i><?= abs($todayRevenueDelta) ?>%</span>
                            <span class="rpt-kpi-sub">vs yesterday</span>
                        <?php else: ?>
                            <span class="rpt-kpi-sub">Same as yesterday</span>
                        <?php endif; ?>
                    </div>
                    <a href="<?= base_url('/report') ?>" class="dsh-kpi-cta" onclick="event.stopPropagation()"><i class="fas fa-chart-bar"></i> View Report</a>
                </div>
                <div class="kpi-slide">
                    <div class="rpt-kpi-top">
                        <div>
                            <div class="rpt-kpi-label">This Week</div>
                            <div class="rpt-kpi-value">RM <?= number_format($weekRevenue, 2) ?></div>
                        </div>
                        <div class="rpt-kpi-icon"><i class="fas fa-calendar-week"></i></div>
                    </div>
                    <div class="rpt-kpi-foot"><span class="rpt-kpi-sub"><?= date('d M', strtotime('monday this week')) ?> – <?= date('d M') ?></span></div>
                    <a href="<?= base_url('/report') ?>" class="dsh-kpi-cta" onclick="event.stopPropagation()"><i class="fas fa-chart-bar"></i> Weekly Report</a>
                </div>
                <div class="kpi-slide">
                    <div class="rpt-kpi-top">
                        <div>
                            <div class="rpt-kpi-label">This Month</div>
                            <div class="rpt-kpi-value">RM <?= number_format($monthRevenue, 2) ?></div>
                        </div>
                        <div class="rpt-kpi-icon"><i class="fas fa-calendar-alt"></i></div>
                    </div>
                    <div class="rpt-kpi-foot"><span class="rpt-kpi-sub"><?= date('F Y') ?></span></div>
                    <a href="<?= base_url('/report') ?>" class="dsh-kpi-cta" onclick="event.stopPropagation()"><i class="fas fa-chart-bar"></i> Monthly Report</a>
                </div>
                <div class="kpi-slide">
                    <div class="rpt-kpi-top">
                        <div>
                            <div class="rpt-kpi-label">Total Revenue</div>
                            <div class="rpt-kpi-value">RM <?= number_format($totalRevenue, 2) ?></div>
                        </div>
                        <div class="rpt-kpi-icon"><i class="fas fa-piggy-bank"></i></div>
                    </div>
                    <div class="rpt-kpi-foot"><span class="rpt-kpi-sub">All-time earnings</span></div>
                    <a href="<?= base_url('/report') ?>" class="dsh-kpi-cta" onclick="event.stopPropagation()"><i class="fas fa-chart-bar"></i> Full Report</a>
                </div>
                <div class="kpi-dots">
                    <span class="kpi-dot active"></span><span class="kpi-dot"></span>
                    <span class="kpi-dot"></span><span class="kpi-dot"></span>
                </div>
            </div>

            <!-- Card 2 · Orders -->
            <div class="rpt-kpi-card dsh-kpi-card" onclick="cycleKpi(this)">
                <div class="kpi-slide active">
                    <div class="rpt-kpi-top">
                        <div>
                            <div class="rpt-kpi-label">Today's Orders</div>
                            <div class="rpt-kpi-value"><?= $todayOrders ?></div>
                        </div>
                        <div class="rpt-kpi-icon"><i class="fas fa-shopping-bag"></i></div>
                    </div>
                    <div class="rpt-kpi-foot">
                        <?php if ($todayOrdersDelta > 0): ?>
                            <span class="rev-growth-badge up"><i class="fas fa-arrow-up"></i><?= abs($todayOrdersDelta) ?>%</span><span class="rpt-kpi-sub">vs yesterday</span>
                        <?php elseif ($todayOrdersDelta < 0): ?>
                            <span class="rev-growth-badge down"><i class="fas fa-arrow-down"></i><?= abs($todayOrdersDelta) ?>%</span><span class="rpt-kpi-sub">vs yesterday</span>
                        <?php else: ?><span class="rpt-kpi-sub">Same as yesterday</span><?php endif; ?>
                    </div>
                    <a href="<?= base_url('/order?start_date='.date('Y-m-d').'&end_date='.date('Y-m-d')) ?>" class="dsh-kpi-cta" onclick="event.stopPropagation()"><i class="fas fa-list"></i> Today's Orders</a>
                </div>
                <div class="kpi-slide">
                    <div class="rpt-kpi-top">
                        <div>
                            <div class="rpt-kpi-label">Pending Orders</div>
                            <div class="rpt-kpi-value"><?= $pendingCount ?></div>
                        </div>
                        <div class="rpt-kpi-icon"><i class="fas fa-hourglass-half"></i></div>
                    </div>
                    <div class="rpt-kpi-foot"><span class="rpt-kpi-sub"><?= $inProgressCount ?> currently in progress</span></div>
                    <a href="<?= base_url('/order?status=0') ?>" class="dsh-kpi-cta" onclick="event.stopPropagation()"><i class="fas fa-clock"></i> View Pending</a>
                </div>
                <div class="kpi-slide">
                    <div class="rpt-kpi-top">
                        <div>
                            <div class="rpt-kpi-label">Total Orders</div>
                            <div class="rpt-kpi-value"><?= number_format($totalOrders) ?></div>
                        </div>
                        <div class="rpt-kpi-icon"><i class="fas fa-box"></i></div>
                    </div>
                    <div class="rpt-kpi-foot"><span class="rpt-kpi-sub"><?= $completedCount ?> completed all-time</span></div>
                    <a href="<?= base_url('/order') ?>" class="dsh-kpi-cta" onclick="event.stopPropagation()"><i class="fas fa-list"></i> All Orders</a>
                </div>
                <div class="kpi-dots">
                    <span class="kpi-dot active"></span><span class="kpi-dot"></span><span class="kpi-dot"></span>
                </div>
            </div>

            <!-- Card 3 · Operations -->
            <div class="rpt-kpi-card dsh-kpi-card" onclick="cycleKpi(this)">
                <div class="kpi-slide active">
                    <div class="rpt-kpi-top">
                        <div>
                            <div class="rpt-kpi-label">Storage Orders</div>
                            <div class="rpt-kpi-value"><?= count($storageOrders) ?></div>
                        </div>
                        <div class="rpt-kpi-icon"><i class="fas fa-warehouse"></i></div>
                    </div>
                    <div class="rpt-kpi-foot"><span class="rpt-kpi-sub"><?= $activeStorageHolds ?> active / not yet collected</span></div>
                    <button class="dsh-kpi-cta" onclick="event.stopPropagation();switchTab('storage')"><i class="fas fa-eye"></i> View Storage</button>
                </div>
                <div class="kpi-slide">
                    <div class="rpt-kpi-top">
                        <div>
                            <div class="rpt-kpi-label">Delivery Orders</div>
                            <div class="rpt-kpi-value"><?= count($deliveryOrders) ?></div>
                        </div>
                        <div class="rpt-kpi-icon"><i class="fas fa-truck"></i></div>
                    </div>
                    <div class="rpt-kpi-foot"><span class="rpt-kpi-sub">Active delivery orders</span></div>
                    <button class="dsh-kpi-cta" onclick="event.stopPropagation();switchTab('delivery')"><i class="fas fa-eye"></i> View Delivery</button>
                </div>
                <div class="kpi-slide">
                    <div class="rpt-kpi-top">
                        <div>
                            <div class="rpt-kpi-label">Orders This Month</div>
                            <div class="rpt-kpi-value"><?= $monthOrders ?></div>
                        </div>
                        <div class="rpt-kpi-icon"><i class="fas fa-calendar-alt"></i></div>
                    </div>
                    <div class="rpt-kpi-foot"><span class="rpt-kpi-sub"><?= date('F Y') ?></span></div>
                    <a href="<?= base_url('/order') ?>" class="dsh-kpi-cta" onclick="event.stopPropagation()"><i class="fas fa-list"></i> All Orders</a>
                </div>
                <div class="kpi-dots">
                    <span class="kpi-dot active"></span><span class="kpi-dot"></span><span class="kpi-dot"></span>
                </div>
            </div>

            <!-- Card 4 · Alerts -->
            <div class="rpt-kpi-card dsh-kpi-card" onclick="cycleKpi(this)">
                <div class="kpi-slide active">
                    <div class="rpt-kpi-top">
                        <div>
                            <div class="rpt-kpi-label">Unread Messages</div>
                            <div class="rpt-kpi-value"><?= $unreadMessages ?></div>
                        </div>
                        <div class="rpt-kpi-icon"><i class="fas fa-envelope"></i></div>
                    </div>
                    <div class="rpt-kpi-foot"><span class="rpt-kpi-sub">Customer enquiries</span></div>
                    <a href="<?= base_url('/admin/contact') ?>" class="dsh-kpi-cta" onclick="event.stopPropagation()"><i class="fas fa-inbox"></i> View Messages</a>
                </div>
                <div class="kpi-slide">
                    <div class="rpt-kpi-top">
                        <div>
                            <div class="rpt-kpi-label">Pending Refunds</div>
                            <div class="rpt-kpi-value"><?= $pendingRefunds ?></div>
                        </div>
                        <div class="rpt-kpi-icon"><i class="fas fa-undo-alt"></i></div>
                    </div>
                    <div class="rpt-kpi-foot"><span class="rpt-kpi-sub">Awaiting action</span></div>
                    <a href="<?= base_url('/admin/refund_request') ?>" class="dsh-kpi-cta" onclick="event.stopPropagation()"><i class="fas fa-undo-alt"></i> Manage Refunds</a>
                </div>
                <div class="kpi-slide">
                    <div class="rpt-kpi-top">
                        <div>
                            <div class="rpt-kpi-label">Staff Accounts</div>
                            <div class="rpt-kpi-value"><?= $userCount ?></div>
                        </div>
                        <div class="rpt-kpi-icon"><i class="fas fa-users-cog"></i></div>
                    </div>
                    <div class="rpt-kpi-foot"><span class="rpt-kpi-sub">Admin &amp; super-admin</span></div>
                    <a href="<?= base_url('/user') ?>" class="dsh-kpi-cta" onclick="event.stopPropagation()"><i class="fas fa-users"></i> Manage Users</a>
                </div>
                <div class="kpi-dots">
                    <span class="kpi-dot active"></span><span class="kpi-dot"></span><span class="kpi-dot"></span>
                </div>
            </div>

        </div><!-- /dsh-kpi-row -->
    </div><!-- /dsh-one-screen__kpis -->

    <!-- ═══════════════════════════════════════════════════════
         MAIN 3-COLUMN GRID
    ════════════════════════════════════════════════════════ -->
    <div class="dsh-one-screen__main">

        <!-- ── COL 1: Activity Calendar ─────────────────────── -->
        <div class="dsh-main-col-cal">
            <div class="rpt-card" style="flex:1;min-height:0;">

                <div class="rpt-card-header">
                    <span class="rpt-title"><i class="fas fa-calendar-alt me-2"></i>Order Calendar</span>
                    <div class="d-flex align-items-center gap-1">
                        <button class="cal-nav-btn" id="calPrev"><i class="fas fa-chevron-left"></i></button>
                        <span id="calMonthLabel" style="font-size:.78rem;font-weight:700;color:var(--gold);min-width:96px;text-align:center;font-family:'Oxanium',sans-serif;"></span>
                        <button class="cal-nav-btn" id="calNext"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>

                <div class="dsh-card-scroll" style="padding: 10px 12px 6px;">
                    <div id="calendarGrid" class="dsh-cal mb-2"></div>

                    <!-- Legend -->
                    <div class="dsh-cal-legend justify-content-end mb-2">
                        <span class="dsh-cal-legend-swatch" style="background:#EEF2F7;"></span>None
                        <span class="dsh-cal-legend-swatch" style="background:#FFE9A6;"></span>1–2
                        <span class="dsh-cal-legend-swatch" style="background:#FFD95E;"></span>3–5
                        <span class="dsh-cal-legend-swatch" style="background:#F2BE00;"></span>6–10
                        <span class="dsh-cal-legend-swatch" style="background:#D7A300;"></span>11+
                    </div>

                    <!-- Day-click detail -->
                    <div id="calDetailPanel" class="dsh-cal-detail">
                        <div class="dsh-cal-detail-title">
                            <span id="calDetailDate"></span>
                            <button onclick="document.getElementById('calDetailPanel').classList.remove('visible')"
                                style="border:none;background:rgba(0,0,0,.08);border-radius:50%;width:20px;height:20px;
                                display:flex;align-items:center;justify-content:center;font-size:.62rem;cursor:pointer;flex-shrink:0;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div id="calDetailBody"></div>
                        <a id="calDetailLink" href="#" class="rpt-export-btn d-inline-block mt-2" style="font-size:.68rem;padding:4px 11px;">
                            <i class="fas fa-list me-1"></i>View Orders for this Day
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- ── COL 2: Tabbed Operations ─────────────────────── -->
        <div class="dsh-main-col-ops">
            <div class="rpt-card" style="flex:1;min-height:0;">

                <div class="rpt-card-header">
                    <span class="rpt-title"><i class="fas fa-tasks me-2"></i>Pending Orders</span>
                    <a href="<?= base_url('/order') ?>" class="rpt-export-btn" style="font-size:.7rem;padding:5px 12px;">
                        <i class="fas fa-external-link-alt me-1"></i>All Orders
                    </a>
                </div>

                <!-- Tab strip -->
                <div class="dsh-tab-strip" id="opsTabStrip">
                    <button class="dsh-tab-pill active" data-tab="pending">
                        <i class="fas fa-hourglass-half me-1"></i>Pending
                        <?php if ($pendingCount): ?><span style="background:rgba(0,0,0,.12);padding:1px 5px;margin-left:3px;font-size:.6rem;"><?= $pendingCount ?></span><?php endif; ?>
                    </button>
                    <button class="dsh-tab-pill" data-tab="storage">
                        <i class="fas fa-warehouse me-1"></i>Storage
                        <?php if (!empty($storageOrders)): ?><span style="background:rgba(0,0,0,.12);padding:1px 5px;margin-left:3px;font-size:.6rem;"><?= count($storageOrders) ?></span><?php endif; ?>
                    </button>
                    <button class="dsh-tab-pill" data-tab="delivery">
                        <i class="fas fa-truck me-1"></i>Delivery
                        <?php if (!empty($deliveryOrders)): ?><span style="background:rgba(0,0,0,.12);padding:1px 5px;margin-left:3px;font-size:.6rem;"><?= count($deliveryOrders) ?></span><?php endif; ?>
                    </button>
                </div>

                <!-- Tab content -->
                <div class="dsh-card-scroll">

                    <!-- Pending Orders -->
                    <div class="dsh-tab-pane active" id="tab-pending">
                        <?php if ($pendingFallbackDate): ?>
                            <div class="dsh-fallback-notice">
                                <i class="fas fa-info-circle me-1"></i>No pending orders. Next scheduled:
                                <strong><?= date('d M Y', strtotime($pendingFallbackDate)) ?></strong>
                            </div>
                        <?php endif; ?>
                        <?php $displayOrders = !empty($pending_orders_display) ? $pending_orders_display : $pendingFallbackOrders; ?>
                        <?php if (!empty($displayOrders)): ?>
                            <div class="table-responsive">
                                <table class="table dsh-table mb-0">
                                    <thead><tr>
                                        <th class="ps-3">Order</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Service</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-end pe-3">Status</th>
                                    </tr></thead>
                                    <tbody>
                                        <?php foreach (array_slice($displayOrders, 0, 20) as $ord): ?>
                                            <tr style="cursor:pointer;"
                                                data-pickup-time="<?= esc($ord['_pickup_time'] ?? '') ?>"
                                                data-pickup-loc="<?= esc($ord['_pickup_location'] ?? '') ?>"
                                                data-dropoff-time="<?= esc($ord['_dropoff_time'] ?? '') ?>"
                                                data-dropoff-loc="<?= esc($ord['_dropoff_location'] ?? '') ?>"
                                                onclick="window.location='<?= base_url('/admin/order_details/'.esc($ord['order_id'])) ?>'"
                                                onmouseenter="dshShowTip(event,this)" onmouseleave="dshHideTip()" onmousemove="dshMoveTip(event)">
                                                <td class="ps-3">
                                                    <a href="<?= base_url('/admin/order_details/'.esc($ord['order_id'])) ?>" class="dsh-order-link" onclick="event.stopPropagation()">#<?= esc($ord['order_id']) ?></a>
                                                </td>
                                                <td><?= esc($ord['first_name']) ?> <?= esc($ord['last_name']) ?></td>
                                                <td style="color:#6B7280;font-size:.68rem;"><?= esc($ord['_created_date_fmt'] ?? substr($ord['created_date'] ?? '', 0, 10)) ?></td>
                                                <td><?= dshSvcPill($ord['service_type']) ?></td>
                                                <td class="text-end" style="font-weight:700;">RM <?= number_format((float)$ord['amount'], 2) ?></td>
                                                <td class="text-end pe-3" onclick="event.stopPropagation()"><?= dshStatusBadge((int)$ord['status'], (int)$ord['order_id']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (count($displayOrders) > 20): ?>
                                <div class="text-center p-2 border-top">
                                    <a href="<?= base_url('/order?status=0') ?>" class="rpt-export-btn" style="font-size:.7rem;padding:4px 12px;">
                                        +<?= count($displayOrders) - 20 ?> more — View All
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="dsh-empty">
                                <i class="fas fa-check-circle" style="color:#2e7d32;opacity:1;"></i>
                                <p>All caught up — no pending orders.</p>
                                <a href="<?= base_url('/order') ?>" class="rpt-export-btn d-inline-block mt-2" style="font-size:.7rem;padding:4px 12px;">View All Orders</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Storage Orders -->
                    <div class="dsh-tab-pane" id="tab-storage">
                        <?php if (!empty($storageOrders)): ?>
                            <div class="table-responsive">
                                <table class="table dsh-table mb-0">
                                    <thead><tr>
                                        <th class="ps-3">Order</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-end pe-3">Status</th>
                                    </tr></thead>
                                    <tbody>
                                        <?php foreach (array_slice($storageOrders, 0, 20) as $ord): ?>
                                            <tr style="cursor:pointer;"
                                                data-pickup-time="<?= esc($ord['_pickup_time'] ?? '') ?>"
                                                data-pickup-loc="<?= esc($ord['_pickup_location'] ?? '') ?>"
                                                data-dropoff-time="<?= esc($ord['_dropoff_time'] ?? '') ?>"
                                                data-dropoff-loc="<?= esc($ord['_dropoff_location'] ?? '') ?>"
                                                onclick="window.location='<?= base_url('/admin/order_details/'.esc($ord['order_id'])) ?>'"
                                                onmouseenter="dshShowTip(event,this)" onmouseleave="dshHideTip()" onmousemove="dshMoveTip(event)">
                                                <td class="ps-3"><a href="<?= base_url('/admin/order_details/'.esc($ord['order_id'])) ?>" class="dsh-order-link" onclick="event.stopPropagation()">#<?= esc($ord['order_id']) ?></a></td>
                                                <td><?= esc($ord['first_name']) ?> <?= esc($ord['last_name']) ?></td>
                                                <td style="color:#6B7280;font-size:.68rem;"><?= esc($ord['_created_date_fmt'] ?? substr($ord['created_date'] ?? '', 0, 10)) ?></td>
                                                <td class="text-end" style="font-weight:700;">RM <?= number_format((float)$ord['amount'], 2) ?></td>
                                                <td class="text-end pe-3" onclick="event.stopPropagation()"><?= dshStatusBadge((int)$ord['status'], (int)$ord['order_id']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (count($storageOrders) > 20): ?>
                                <div class="text-center p-2 border-top">
                                    <a href="<?= base_url('/order?service_type=storage') ?>" class="rpt-export-btn" style="font-size:.7rem;padding:4px 12px;">
                                        +<?= count($storageOrders) - 20 ?> more — View All
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="dsh-empty"><i class="fas fa-warehouse"></i><p>No active storage orders.</p></div>
                        <?php endif; ?>
                    </div>

                    <!-- Delivery Orders -->
                    <div class="dsh-tab-pane" id="tab-delivery">
                        <?php if (!empty($deliveryOrders)): ?>
                            <div class="table-responsive">
                                <table class="table dsh-table mb-0">
                                    <thead><tr>
                                        <th class="ps-3">Order</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-end pe-3">Status</th>
                                    </tr></thead>
                                    <tbody>
                                        <?php foreach (array_slice($deliveryOrders, 0, 20) as $ord): ?>
                                            <tr style="cursor:pointer;"
                                                data-pickup-time="<?= esc($ord['_pickup_time'] ?? '') ?>"
                                                data-pickup-loc="<?= esc($ord['_pickup_location'] ?? '') ?>"
                                                data-dropoff-time="<?= esc($ord['_dropoff_time'] ?? '') ?>"
                                                data-dropoff-loc="<?= esc($ord['_dropoff_location'] ?? '') ?>"
                                                onclick="window.location='<?= base_url('/admin/order_details/'.esc($ord['order_id'])) ?>'"
                                                onmouseenter="dshShowTip(event,this)" onmouseleave="dshHideTip()" onmousemove="dshMoveTip(event)">
                                                <td class="ps-3"><a href="<?= base_url('/admin/order_details/'.esc($ord['order_id'])) ?>" class="dsh-order-link" onclick="event.stopPropagation()">#<?= esc($ord['order_id']) ?></a></td>
                                                <td><?= esc($ord['first_name']) ?> <?= esc($ord['last_name']) ?></td>
                                                <td style="color:#6B7280;font-size:.68rem;"><?= esc($ord['_created_date_fmt'] ?? substr($ord['created_date'] ?? '', 0, 10)) ?></td>
                                                <td class="text-end" style="font-weight:700;">RM <?= number_format((float)$ord['amount'], 2) ?></td>
                                                <td class="text-end pe-3" onclick="event.stopPropagation()"><?= dshStatusBadge((int)$ord['status'], (int)$ord['order_id']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (count($deliveryOrders) > 20): ?>
                                <div class="text-center p-2 border-top">
                                    <a href="<?= base_url('/order?service_type=delivery') ?>" class="rpt-export-btn" style="font-size:.7rem;padding:4px 12px;">
                                        +<?= count($deliveryOrders) - 20 ?> more — View All
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="dsh-empty"><i class="fas fa-truck"></i><p>No active delivery orders.</p></div>
                        <?php endif; ?>
                    </div>

                </div><!-- /dsh-card-scroll -->
            </div>
        </div>

        <!-- ── COL 3: Quick Stats + Activity Feed ────────────── -->
        <div class="dsh-main-col-side">

            <!-- Today at a Glance -->
            <div class="rpt-card dsh-side-stats">
                <div class="rpt-card-header">
                    <span class="rpt-title"><i class="fas fa-tachometer-alt me-2"></i>Today</span>
                    <span style="font-size:.7rem;color:#9CA3AF;font-family:'Oxanium',sans-serif;"><?= date('d M Y') ?></span>
                </div>
                <div style="padding: 8px 12px;">
                    <div class="dsh-quick-row">
                        <span class="dsh-quick-label"><i class="fas fa-coins"></i>Revenue Today</span>
                        <span class="dsh-quick-value">RM <?= number_format($todayRevenue, 2) ?></span>
                    </div>
                    <div class="dsh-quick-row">
                        <span class="dsh-quick-label"><i class="fas fa-calendar-alt"></i>Revenue This Month</span>
                        <span class="dsh-quick-value">RM <?= number_format($monthRevenue, 2) ?></span>
                    </div>
                    <div class="dsh-quick-row">
                        <span class="dsh-quick-label"><i class="fas fa-shopping-bag"></i>Orders This Month</span>
                        <span class="dsh-quick-value"><a href="<?= base_url('/order') ?>"><?= $monthOrders ?></a></span>
                    </div>
                    <div class="dsh-quick-row">
                        <span class="dsh-quick-label"><i class="fas fa-hourglass-half"></i>Pending</span>
                        <span class="dsh-quick-value"><a href="<?= base_url('/order?status=0') ?>"><?= $pendingCount ?></a></span>
                    </div>
                    <div class="dsh-quick-row">
                        <span class="dsh-quick-label"><i class="fas fa-sync-alt"></i>In Progress</span>
                        <span class="dsh-quick-value"><a href="<?= base_url('/order?status=1') ?>"><?= $inProgressCount ?></a></span>
                    </div>
                    <div class="dsh-quick-row">
                        <span class="dsh-quick-label"><i class="fas fa-warehouse"></i>Active Storage</span>
                        <span class="dsh-quick-value"><a href="<?= base_url('/order?service_type=storage') ?>"><?= $activeStorageHolds ?></a></span>
                    </div>

                    <!-- Notifications -->
                    <div class="dsh-notif-section-title"><i class="fas fa-bell me-1"></i>Notifications</div>
                    <?php if ($unreadMessages > 0): ?>
                    <div class="dsh-notif-item">
                        <div class="dsh-notif-icon dsh-notif-icon--msg"><i class="fas fa-envelope"></i></div>
                        <div>
                            <div class="dsh-notif-text"><a href="<?= base_url('/admin/contact') ?>" style="color:inherit;text-decoration:none;"><?= $unreadMessages ?> unread message<?= $unreadMessages !== 1 ? 's' : '' ?></a></div>
                            <div class="dsh-notif-sub">Customer enquiries awaiting reply</div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($pendingRefunds > 0): ?>
                    <div class="dsh-notif-item">
                        <div class="dsh-notif-icon dsh-notif-icon--refund"><i class="fas fa-undo-alt"></i></div>
                        <div>
                            <div class="dsh-notif-text"><a href="<?= base_url('/admin/refund_request') ?>" style="color:inherit;text-decoration:none;"><?= $pendingRefunds ?> pending refund<?= $pendingRefunds !== 1 ? 's' : '' ?></a></div>
                            <div class="dsh-notif-sub">Awaiting admin action</div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($unreadMessages === 0 && $pendingRefunds === 0): ?>
                    <div class="dsh-notif-item">
                        <div class="dsh-notif-icon dsh-notif-icon--ok"><i class="fas fa-check"></i></div>
                        <div>
                            <div class="dsh-notif-text">All clear</div>
                            <div class="dsh-notif-sub">No pending notifications</div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Activity Feed -->
            <div class="dsh-side-feed">
                <div class="rpt-card">
                    <div class="rpt-card-header">
                        <span class="rpt-title"><i class="fas fa-bolt me-2"></i>Activity Feed</span>
                    </div>
                    <div class="dsh-card-scroll" style="padding: 5px 10px 6px;">
                        <?php
                        $feedItems = [];
                        foreach ($recentActivity as $log) {
                            $oName = trim(($log['first_name'] ?? '').' '.($log['last_name'] ?? ''));
                            $feedItems[] = [
                                'time'    => $log['modified_date'],
                                'ico'     => 'fas fa-pencil-alt',
                                'ico_cls' => 'dsh-activity-icon--activity',
                                'title'   => esc($log['action']),
                                'desc'    => 'by '.esc($log['username']).($oName ? ' · '.esc($oName) : ''),
                                'link'    => base_url('/order'),
                            ];
                        }
                        foreach ($recentOrders as $ord) {
                            $feedItems[] = [
                                'time'    => $ord['created_date'],
                                'ico'     => 'fas fa-shopping-bag',
                                'ico_cls' => 'dsh-activity-icon--order',
                                'title'   => 'New '.strtoupper(esc($ord['service_type'])).' order placed',
                                'desc'    => esc($ord['first_name']).' '.esc($ord['last_name']).' · RM '.number_format((float)$ord['amount'],2),
                                'link'    => base_url('/order'),
                            ];
                        }
                        foreach ($recentMessages as $msg) {
                            $feedItems[] = [
                                'time'    => $msg['created_date'],
                                'ico'     => 'fas fa-envelope',
                                'ico_cls' => 'dsh-activity-icon--message',
                                'title'   => 'Message: '.esc($msg['subject'] ?? '(no subject)'),
                                'desc'    => 'From '.esc($msg['email'] ?? '—'),
                                'link'    => base_url('/admin/contact'),
                            ];
                        }
                        usort($feedItems, static fn($a,$b) => strtotime($b['time']) - strtotime($a['time']));
                        $feedTotal = count($feedItems);
                        $feedItems = array_slice($feedItems, 0, 15);
                        ?>
                        <?php if (!empty($feedItems)): ?>
                            <?php foreach ($feedItems as $fi): ?>
                                <a href="<?= $fi['link'] ?>" class="dsh-activity-item">
                                    <div class="dsh-activity-icon <?= $fi['ico_cls'] ?>"><i class="<?= $fi['ico'] ?>"></i></div>
                                    <div class="dsh-activity-body">
                                        <div class="dsh-activity-title"><?= $fi['title'] ?></div>
                                        <div class="dsh-activity-desc"><?= $fi['desc'] ?></div>
                                    </div>
                                    <div class="dsh-activity-time"><?= dshTimeAgo($fi['time']) ?></div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-3" style="color:#9CA3AF;font-size:.76rem;font-family:'Oxanium',sans-serif;">No recent activity</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div><!-- /dsh-main-col-side -->

    </div><!-- /dsh-one-screen__main -->

</div><!-- /dsh-one-screen -->
</div><!-- /rpt-page -->

<div id="dshRowTooltip"></div>

<script>
var CHANGE_STATUS_BASE  = '<?= base_url('/change_status/') ?>';
var ORDER_BASE          = '<?= base_url('/order') ?>';
var ORDER_DETAILS_BASE  = '<?= base_url('/admin/order_details/') ?>';
var heatData           = <?= json_encode($calendarHeatmap,  JSON_THROW_ON_ERROR) ?>;
var calOrders          = <?= json_encode($calendarOrders,   JSON_THROW_ON_ERROR) ?>;

// ── Live clock ─────────────────────────────────────────────────────────
(function () {
    function pad(n) { return String(n).padStart(2, '0'); }
    function tick() {
        var d = new Date(), h = d.getHours(), m = d.getMinutes(), s = d.getSeconds();
        var ap = h >= 12 ? 'PM' : 'AM'; h = h % 12 || 12;
        var el = document.getElementById('live-time');
        if (el) el.textContent = pad(h)+':'+pad(m)+':'+pad(s)+' '+ap;
    }
    tick(); setInterval(tick, 1000);
})();

// ── KPI cycling (manual only, no auto-rotate) ─────────────────────────
function cycleKpi(card) {
    var slides = card.querySelectorAll('.kpi-slide');
    var dots   = card.querySelectorAll('.kpi-dot');
    var cur = 0;
    slides.forEach(function(s,i) { if (s.classList.contains('active')) cur = i; });
    var nxt = (cur + 1) % slides.length;
    slides[cur].classList.remove('active'); slides[nxt].classList.add('active');
    dots[cur].classList.remove('active');   dots[nxt].classList.add('active');
}
document.querySelectorAll('.dsh-kpi-card').forEach(function(card) {
    card.querySelectorAll('.kpi-dot').forEach(function(dot, i) {
        dot.addEventListener('click', function(e) {
            e.stopPropagation();
            var slides = card.querySelectorAll('.kpi-slide');
            var dots   = card.querySelectorAll('.kpi-dot');
            slides.forEach(function(s){ s.classList.remove('active'); });
            dots.forEach(function(d)  { d.classList.remove('active'); });
            slides[i].classList.add('active');
            dots[i].classList.add('active');
        });
    });
});

// ── Tab switching ──────────────────────────────────────────────────────
function switchTab(tabId) {
    document.querySelectorAll('#opsTabStrip .dsh-tab-pill').forEach(function(p) {
        p.classList.toggle('active', p.dataset.tab === tabId);
    });
    document.querySelectorAll('.dsh-tab-pane').forEach(function(p) { p.classList.remove('active'); });
    var t = document.getElementById('tab-' + tabId);
    if (t) t.classList.add('active');
}
document.querySelectorAll('#opsTabStrip .dsh-tab-pill').forEach(function(pill) {
    pill.addEventListener('click', function() { switchTab(this.dataset.tab); });
});

// ── Inline status cycle (AJAX) ─────────────────────────────────────────
var STATUS_LABELS  = ['Pending', 'In Progress', 'Completed'];
var STATUS_CLASSES = ['dsh-status--pending', 'dsh-status--progress', 'dsh-status--completed'];

function cycleStatus(el) {
    var orderId    = el.dataset.order;
    var cur        = parseInt(el.dataset.status, 10);
    var nxtPreview = (cur + 1) % 3;
    var curLabel   = STATUS_LABELS[cur];
    var nxtLabel   = STATUS_LABELS[nxtPreview];

    swal({
        title: 'Change order status?',
        text:  'Order ID: ' + orderId + '\nCurrent status: ' + curLabel + '\nNew status: ' + nxtLabel,
        icon:  'warning',
        buttons: ['Cancel', 'Yes, change it'],
        dangerMode: true
    }).then(function(willChange) {
        if (!willChange) return;
        fetch(CHANGE_STATUS_BASE + orderId, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                var nxt = data.new_status;
                el.dataset.status = nxt;
                el.textContent    = STATUS_LABELS[nxt];
                el.className      = 'dsh-status-badge ' + STATUS_CLASSES[nxt];
                swal({
                    title: 'Status Updated!',
                    text:  'Order #' + orderId + ' is now ' + STATUS_LABELS[nxt] + '.',
                    icon:  'success',
                    button: 'OK'
                });
            } else {
                swal('Error', 'Could not update status. Please try again.', 'error');
            }
        })
        .catch(function(e) {
            console.error('Status change failed', e);
            swal('Error', 'A network error occurred. Please try again.', 'error');
        });
    });
}

// ── Calendar heatmap ───────────────────────────────────────────────────
var calYear  = <?= (int)date('Y') ?>;
var calMonth = <?= (int)date('m') ?>;
var MONTHS   = ['January','February','March','April','May','June',
                'July','August','September','October','November','December'];
var DAYS     = ['Su','Mo','Tu','We','Th','Fr','Sa'];

function heatClass(total) {
    if (!total)      return '';
    if (total <= 2)  return 'cal-has-data cal-has-data-l1';
    if (total <= 5)  return 'cal-has-data cal-has-data-l2';
    if (total <= 10) return 'cal-has-data cal-has-data-l3';
    return 'cal-has-data cal-has-data-l4';
}

function renderCal(year, month) {
    document.getElementById('calMonthLabel').textContent = MONTHS[month-1]+' '+year;
    var first  = new Date(year, month-1, 1).getDay();
    var days   = new Date(year, month, 0).getDate();
    var today  = new Date().toISOString().slice(0,10);

    var html = '<div class="cal-grid">';
    DAYS.forEach(function(d){ html += '<div class="dsh-cal-dow">'+d+'</div>'; });
    for (var i = 0; i < first; i++) html += '<div class="cal-day cal-blank"></div>';
    for (var day = 1; day <= days; day++) {
        var ds   = year+'-'+String(month).padStart(2,'0')+'-'+String(day).padStart(2,'0');
        var info = heatData[ds] || {created:0,pickups:0,dropoffs:0,total:0};
        var hc   = heatClass(info.total);
        var cls  = 'cal-day'+(ds===today?' cal-today':'')+(hc?' '+hc:'')+(info.total>0?' cal-clickable':'');
        var tip  = info.total===0 ? 'No activity' :
            info.total+' activities — '+info.created+' new, '+info.pickups+' pickups, '+info.dropoffs+' drop-offs';
        html += '<div class="'+cls+'" title="'+ds+': '+tip+'"'
             + (info.total>0 ? ' onclick="showCalDetail(\''+ds+'\')"' : '')+'>'
             + day+'</div>';
    }
    html += '</div>';
    document.getElementById('calendarGrid').innerHTML = html;
}

function showCalDetail(ds) {
    var info   = heatData[ds] || {created:0,pickups:0,dropoffs:0,total:0};
    var panel  = document.getElementById('calDetailPanel');
    var title  = document.getElementById('calDetailDate');
    var body   = document.getElementById('calDetailBody');
    var link   = document.getElementById('calDetailLink');

    var parts  = ds.split('-');
    var dto    = new Date(+parts[0], +parts[1]-1, +parts[2]);
    title.textContent = dto.toLocaleDateString('en-MY',{weekday:'long',day:'numeric',month:'long',year:'numeric'});

    // Summary row
    var html = '';
    if (info.total) {
        html += '<div class="dsh-cal-detail-row" style="font-size:.7rem;margin-bottom:6px;">';
        if (info.created)  html += '<span style="margin-right:10px;"><strong>'+info.created+'</strong> new</span>';
        if (info.pickups)  html += '<span style="margin-right:10px;"><strong>'+info.pickups+'</strong> pickups</span>';
        if (info.dropoffs) html += '<span><strong>'+info.dropoffs+'</strong> drop-offs</span>';
        html += '</div>';
    }

    // Order cards from calOrders
    var matched = calOrders.filter(function(o) {
        return o.created === ds || o.pickup === ds || o.dropoff === ds;
    });

    if (matched.length > 0) {
        matched.slice(0, 6).forEach(function(o) {
            var svcCls = o.service && o.service.toLowerCase() === 'storage' ? 'dsh-svc-pill--storage' : 'dsh-svc-pill--delivery';
            var stMap  = {0:'dsh-status--pending',1:'dsh-status--progress',2:'dsh-status--completed'};
            var stLbl  = ['Pending','In Progress','Completed'];
            html += '<div class="dsh-cal-order-card">'
                  + '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:3px;">'
                  + '<strong style="font-size:.74rem;">#'+o.id+' — '+o.name+'</strong>'
                  + '<span class="dsh-svc-pill '+svcCls+'">'+o.service+'</span>'
                  + '</div>';
            if (o.pickup)  html += '<div style="font-size:.68rem;color:#6B7280;"><i class="fas fa-truck me-1" style="color:#F2BE00;"></i>Pickup: '+o.pickup+(o.pickup_t ? ' at '+o.pickup_t : '')+'</div>';
            if (o.dropoff) html += '<div style="font-size:.68rem;color:#6B7280;"><i class="fas fa-box-open me-1" style="color:#F2BE00;"></i>Drop-off: '+o.dropoff+(o.dropoff_t ? ' at '+o.dropoff_t : '')+'</div>';
            html += '<div style="margin-top:3px;"><span class="dsh-status-badge '+stMap[o.status]+'" style="pointer-events:none;cursor:default;">'+stLbl[o.status]+'</span>'
                  + '<span style="font-size:.68rem;color:#6B7280;margin-left:8px;">RM '+o.amount.toFixed(2)+'</span></div>'
                  + '</div>';
        });
        if (matched.length > 6) {
            html += '<div style="font-size:.68rem;color:#9CA3AF;margin-top:4px;">+ '+(matched.length-6)+' more orders</div>';
        }
    } else if (!info.total) {
        html = '<div style="font-size:.74rem;color:#9CA3AF;">No activity on this date.</div>';
    }

    body.innerHTML = html;
    link.href = ORDER_BASE + '?start_date=' + ds + '&end_date=' + ds;
    panel.classList.add('visible');

    // Highlight selected day
    document.querySelectorAll('#calendarGrid .cal-selected').forEach(function(e){ e.classList.remove('cal-selected'); });
    document.querySelectorAll('#calendarGrid .cal-day.cal-clickable').forEach(function(e){
        if (e.title && e.title.startsWith(ds)) e.classList.add('cal-selected');
    });
}

renderCal(calYear, calMonth);

document.getElementById('calPrev').addEventListener('click', function(){
    calMonth--; if (calMonth<1){ calMonth=12; calYear--; }
    renderCal(calYear, calMonth);
    document.getElementById('calDetailPanel').classList.remove('visible');
});
document.getElementById('calNext').addEventListener('click', function(){
    calMonth++; if (calMonth>12){ calMonth=1; calYear++; }
    renderCal(calYear, calMonth);
    document.getElementById('calDetailPanel').classList.remove('visible');
});

// ── Row tooltip ────────────────────────────────────────────────────────
var _dshTip = document.getElementById('dshRowTooltip');

function dshShowTip(e, row) {
    var pt = row.dataset.pickupTime  || '—';
    var pl = row.dataset.pickupLoc   || '—';
    var dt = row.dataset.dropoffTime || '—';
    var dl = row.dataset.dropoffLoc  || '—';
    _dshTip.innerHTML =
        '<div class="dsh-tt-row"><i class="fas fa-truck"></i><span class="dsh-tt-label">Pickup</span><span class="dsh-tt-val">'+pt+'</span></div>'
      + '<div class="dsh-tt-row"><i class="fas fa-map-marker-alt"></i><span class="dsh-tt-label">Location</span><span class="dsh-tt-val">'+pl+'</span></div>'
      + '<div style="margin-top:5px;" class="dsh-tt-row"><i class="fas fa-box-open"></i><span class="dsh-tt-label">Drop-off</span><span class="dsh-tt-val">'+dt+'</span></div>'
      + '<div class="dsh-tt-row"><i class="fas fa-map-marker-alt"></i><span class="dsh-tt-label">Location</span><span class="dsh-tt-val">'+dl+'</span></div>';
    _dshTip.style.display = 'block';
    dshMoveTip(e);
}
function dshMoveTip(e) {
    _dshTip.style.left = (e.clientX + 14) + 'px';
    _dshTip.style.top  = (e.clientY + 14) + 'px';
}
function dshHideTip() {
    _dshTip.style.display = 'none';
}
</script>

<?= $this->include('admin/footer'); ?>
