<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/report.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/admin/dashboard.css') ?>">

<?php
$hour        = (int)date('G');
$greeting    = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
$sessionUser = session()->get('username') ?? 'Admin';

function dshSvcPill(string $type): string {
    $l   = strtolower($type);
    $cls = $l === 'storage' ? 'dsh-svc-pill--storage' : 'dsh-svc-pill--delivery';
    return '<span class="dsh-svc-pill '.$cls.'">'.htmlspecialchars(ucfirst($l)).'</span>';
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
function dshInitials(string $name): string {
    $parts = preg_split('/\s+/', trim($name));
    $i = strtoupper(substr($parts[0] ?? '', 0, 1));
    if (isset($parts[1])) $i .= strtoupper(substr($parts[1], 0, 1));
    return $i ?: '??';
}
function dshStatusRowCls(int $s): string {
    return ['dsh-qrow--pending', 'dsh-qrow--progress', 'dsh-qrow--done'][$s] ?? 'dsh-qrow--pending';
}
function dshParseRoute(array $ord): array {
    $d    = @json_decode($ord['order_details_json'] ?? '{}', true);
    $d    = is_array($d) ? $d : [];
    $from = trim($d['origin'] ?? ($d['originAddress'] ?? ''));
    $to   = trim($d['destination'] ?? ($d['destinationAddress'] ?? ''));
    $svc  = strtolower($ord['service_type'] ?? '');
    $time = $svc === 'storage'
        ? trim($d['pickupTime'] ?? ($ord['_pickup_time'] ?? '—'))
        : trim($d['dropoffTime'] ?? ($ord['_dropoff_time'] ?? '—'));
    return [
        'from' => $from ?: ($ord['_pickup_location']  ?? '—'),
        'to'   => $to   ?: ($ord['_dropoff_location'] ?? '—'),
        'time' => $time ?: '—',
    ];
}
?>

<div class="dsh-page ease-dir">

    <!-- ═══════════════════════════════════════════════════════
         PAGE HEAD
    ════════════════════════════════════════════════════════ -->
    <div class="ease-page-head">
        <div>
            <div class="ease-crumb">EASE Admin &middot; <b>Dashboard</b></div>
            <h1 class="dsh-greeting"><?= esc($greeting) ?>, <b><?= esc($sessionUser) ?></b></h1>
            <div class="dsh-page-meta">
                <span><i class="fas fa-calendar-alt"></i><?= date('l, d F Y') ?></span>
                <span><i class="fas fa-clock"></i><span id="live-time"></span></span>
                <span class="dsh-live-badge"><span class="dsh-pulse"></span>Live &middot; <?= $inProgressCount ?> in progress</span>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         KPI GRID (4 cycling cards)
    ════════════════════════════════════════════════════════ -->
    <div class="dsh-kpis">

        <!-- Revenue: Today / This Week / This Month -->
        <div class="dsh-kpi">
            <div class="dsh-kpi-slide kpi-active">
                <div class="dsh-kpi__eb">
                    <span class="dsh-kpi__ic"><i class="fas fa-coins"></i></span>
                    <span>Today's Revenue</span>
                </div>
                <div class="dsh-kpi__v"><span class="dsh-kpi__cur">RM</span><?= number_format($todayRevenue, 2) ?></div>
                <div class="dsh-kpi__m">
                    <?php if ($todayRevenueDelta > 0): ?>
                        <span class="dsh-kpi__dt">▲ <?= abs($todayRevenueDelta) ?>%</span> vs yesterday
                    <?php elseif ($todayRevenueDelta < 0): ?>
                        <span class="dsh-kpi__dt dsh-kpi__dt--dn">▼ <?= abs($todayRevenueDelta) ?>%</span> vs yesterday
                    <?php else: ?>
                        Same as yesterday
                    <?php endif; ?>
                </div>
            </div>
            <div class="dsh-kpi-slide">
                <div class="dsh-kpi__eb">
                    <span class="dsh-kpi__ic"><i class="fas fa-coins"></i></span>
                    <span>This Week</span>
                </div>
                <div class="dsh-kpi__v"><span class="dsh-kpi__cur">RM</span><?= number_format($weekRevenue, 2) ?></div>
                <div class="dsh-kpi__m">Weekly revenue</div>
            </div>
            <div class="dsh-kpi-slide">
                <div class="dsh-kpi__eb">
                    <span class="dsh-kpi__ic"><i class="fas fa-coins"></i></span>
                    <span>This Month</span>
                </div>
                <div class="dsh-kpi__v"><span class="dsh-kpi__cur">RM</span><?= number_format($monthRevenue, 2) ?></div>
                <div class="dsh-kpi__m">Monthly revenue</div>
            </div>
            <div class="dsh-kpi-dots">
                <span class="dsh-kpi-dot kpi-dot-act"></span>
                <span class="dsh-kpi-dot"></span>
                <span class="dsh-kpi-dot"></span>
            </div>
        </div>

        <!-- Orders: Today / This Month / All Time -->
        <div class="dsh-kpi">
            <div class="dsh-kpi-slide kpi-active">
                <div class="dsh-kpi__eb">
                    <span class="dsh-kpi__ic"><i class="fas fa-shopping-bag"></i></span>
                    <span>Today's Orders</span>
                </div>
                <div class="dsh-kpi__v"><?= $todayOrders ?></div>
                <div class="dsh-kpi__m">
                    <?php if ($todayOrdersDelta > 0): ?>
                        <span class="dsh-kpi__dt">▲ <?= abs($todayOrdersDelta) ?>%</span> vs yesterday
                    <?php elseif ($todayOrdersDelta < 0): ?>
                        <span class="dsh-kpi__dt dsh-kpi__dt--dn">▼ <?= abs($todayOrdersDelta) ?>%</span> vs yesterday
                    <?php else: ?>
                        Same as yesterday
                    <?php endif; ?>
                </div>
            </div>
            <div class="dsh-kpi-slide">
                <div class="dsh-kpi__eb">
                    <span class="dsh-kpi__ic"><i class="fas fa-shopping-bag"></i></span>
                    <span>This Month</span>
                </div>
                <div class="dsh-kpi__v"><?= $monthOrders ?></div>
                <div class="dsh-kpi__m">Orders this month</div>
            </div>
            <div class="dsh-kpi-slide">
                <div class="dsh-kpi__eb">
                    <span class="dsh-kpi__ic"><i class="fas fa-shopping-bag"></i></span>
                    <span>Total Orders</span>
                </div>
                <div class="dsh-kpi__v"><?= $totalOrders ?></div>
                <div class="dsh-kpi__m">All time</div>
            </div>
            <div class="dsh-kpi-dots">
                <span class="dsh-kpi-dot kpi-dot-act"></span>
                <span class="dsh-kpi-dot"></span>
                <span class="dsh-kpi-dot"></span>
            </div>
        </div>

        <!-- Queue: Pending / Completed -->
        <div class="dsh-kpi">
            <div class="dsh-kpi-slide kpi-active">
                <div class="dsh-kpi__eb">
                    <span class="dsh-kpi__ic"><i class="fas fa-hourglass-half"></i></span>
                    <span>Pending</span>
                </div>
                <div class="dsh-kpi__v"><?= $pendingCount ?></div>
                <div class="dsh-kpi__m"><?= $inProgressCount ?> in progress</div>
            </div>
            <div class="dsh-kpi-slide">
                <div class="dsh-kpi__eb">
                    <span class="dsh-kpi__ic"><i class="fas fa-check-circle"></i></span>
                    <span>Completed</span>
                </div>
                <div class="dsh-kpi__v"><?= $completedCount ?></div>
                <div class="dsh-kpi__m">Orders fulfilled</div>
            </div>
            <div class="dsh-kpi-dots">
                <span class="dsh-kpi-dot kpi-dot-act"></span>
                <span class="dsh-kpi-dot"></span>
            </div>
        </div>

        <!-- Storage: Active Holds / Deliveries -->
        <div class="dsh-kpi">
            <div class="dsh-kpi-slide kpi-active">
                <div class="dsh-kpi__eb">
                    <span class="dsh-kpi__ic"><i class="fas fa-warehouse"></i></span>
                    <span>Storage Holds</span>
                </div>
                <div class="dsh-kpi__v"><?= $activeStorageHolds ?></div>
                <div class="dsh-kpi__m"><?= count($storageOrders) ?> awaiting pickup</div>
            </div>
            <div class="dsh-kpi-slide">
                <div class="dsh-kpi__eb">
                    <span class="dsh-kpi__ic"><i class="fas fa-truck"></i></span>
                    <span>Deliveries</span>
                </div>
                <div class="dsh-kpi__v"><?= count($deliveryOrders) ?></div>
                <div class="dsh-kpi__m">Active delivery orders</div>
            </div>
            <div class="dsh-kpi-dots">
                <span class="dsh-kpi-dot kpi-dot-act"></span>
                <span class="dsh-kpi-dot"></span>
            </div>
        </div>

    </div><!-- /dsh-kpis -->

    <!-- ═══════════════════════════════════════════════════════
         SECTION HEAD
    ════════════════════════════════════════════════════════ -->
    <div class="dsh-shead">
        <span class="dsh-shead__ttl">Today's operations</span>
        <span class="dsh-shead__rule"></span>
        <a href="<?= base_url('/order') ?>" class="dsh-shead__meta">View all orders →</a>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         2-COLUMN: QUEUE + CALENDAR
    ════════════════════════════════════════════════════════ -->
    <div class="dsh-ops-grid">

        <!-- ── Queue card ──────────────────────────────────── -->
        <div class="dsh-card">
            <div class="dsh-card__head">
                <span class="dsh-card__pill"><span class="dsh-card__dot"></span>Live queue</span>
                <div class="dsh-tabs" id="opsTabStrip">
                    <button class="dsh-tab act" data-tab="pending">
                        Pending<?php if ($pendingCount): ?>&nbsp;<span class="dsh-tab__num"><?= $pendingCount ?></span><?php endif; ?>
                    </button>
                    <button class="dsh-tab" data-tab="storage">
                        Storage<?php if (!empty($storageOrders)): ?>&nbsp;<span class="dsh-tab__num"><?= count($storageOrders) ?></span><?php endif; ?>
                    </button>
                    <button class="dsh-tab" data-tab="delivery">
                        Delivery<?php if (!empty($deliveryOrders)): ?>&nbsp;<span class="dsh-tab__num"><?= count($deliveryOrders) ?></span><?php endif; ?>
                    </button>
                </div>
                <span class="dsh-queue-active">TODAY &middot; <?= $pendingCount + $inProgressCount ?> ACTIVE</span>
            </div>

            <div class="dsh-card__scroll">

                <!-- Column header -->
                <div class="dsh-queue-head">
                    <div>Time</div>
                    <div>Customer</div>
                    <div>Route</div>
                    <div>Total</div>
                    <div>Status</div>
                </div>

                <!-- Pending -->
                <div class="dsh-tab-pane active" id="tab-pending">
                    <?php $displayOrders = !empty($pending_orders_display) ? $pending_orders_display : ($pendingFallbackOrders ?? []); ?>
                    <?php if (!empty($pendingFallbackDate)): ?>
                        <div class="dsh-fallback-notice">
                            <i class="fas fa-info-circle me-1"></i>No pending orders today. Next scheduled: <strong><?= date('d M Y', strtotime($pendingFallbackDate)) ?></strong>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($displayOrders)): ?>
                        <div class="dsh-queue">
                            <?php foreach (array_slice($displayOrders, 0, 10) as $ord): $r = dshParseRoute($ord); ?>
                                <div class="dsh-qrow <?= dshStatusRowCls((int)$ord['status']) ?>"
                                    onclick="window.location='<?= base_url('/admin/order_details/'.esc($ord['order_id'])) ?>'"
                                    data-pickup-time="<?= esc($r['time']) ?>"
                                    data-pickup-loc="<?= esc($r['from']) ?>"
                                    data-dropoff-time="<?= esc($r['time']) ?>"
                                    data-dropoff-loc="<?= esc($r['to']) ?>"
                                    onmouseenter="dshShowTip(event,this)" onmouseleave="dshHideTip()" onmousemove="dshMoveTip(event)">
                                    <div class="dsh-qrow__time">
                                        <span class="dsh-qrow__hm"><?= esc($r['time']) ?></span>
                                        <small class="dsh-qrow__type"><?= strtolower($ord['service_type']) === 'storage' ? 'Pickup' : 'Drop-off' ?></small>
                                    </div>
                                    <div class="dsh-qrow__who">
                                        <div class="dsh-av"><?= dshInitials(trim($ord['first_name'].' '.$ord['last_name'])) ?></div>
                                        <div>
                                            <div class="dsh-qrow__nm"><?= esc($ord['first_name']) ?> <?= esc($ord['last_name']) ?></div>
                                            <div class="dsh-qrow__sub">#<?= esc($ord['order_id']) ?> &middot; <?= dshSvcPill($ord['service_type']) ?></div>
                                        </div>
                                    </div>
                                    <div class="dsh-qrow__route">
                                        <div class="dsh-qrow__rtleg">
                                            <span class="dsh-qrow__rtdot dsh-qrow__rtdot--from"></span>
                                            <div>
                                                <div class="dsh-qrow__rtlbl">FROM</div>
                                                <div class="dsh-qrow__rtval"><?= esc($r['from']) ?></div>
                                            </div>
                                        </div>
                                        <div class="dsh-qrow__rtleg">
                                            <span class="dsh-qrow__rtdot dsh-qrow__rtdot--to"></span>
                                            <div>
                                                <div class="dsh-qrow__rtlbl">TO</div>
                                                <div class="dsh-qrow__rtval"><?= esc($r['to']) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dsh-qrow__total">RM <?= number_format((float)$ord['amount'], 2) ?></div>
                                    <div class="dsh-qrow__status" onclick="event.stopPropagation()">
                                        <?= dshStatusBadge((int)$ord['status'], (int)$ord['order_id']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($displayOrders) > 10): ?>
                            <div class="dsh-more-link"><a href="<?= base_url('/order?status=0') ?>">+ <?= count($displayOrders) - 10 ?> more — View all pending</a></div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="dsh-empty">
                            <i class="fas fa-check-circle" style="color:#1E8E3E;opacity:1;"></i>
                            <p>All caught up — no pending orders.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Storage -->
                <div class="dsh-tab-pane" id="tab-storage">
                    <?php if (!empty($storageOrders)): ?>
                        <div class="dsh-queue">
                            <?php foreach (array_slice($storageOrders, 0, 10) as $ord): $r = dshParseRoute($ord); ?>
                                <div class="dsh-qrow <?= dshStatusRowCls((int)$ord['status']) ?>"
                                    onclick="window.location='<?= base_url('/admin/order_details/'.esc($ord['order_id'])) ?>'"
                                    data-pickup-time="<?= esc($r['time']) ?>"
                                    data-pickup-loc="<?= esc($r['from']) ?>"
                                    data-dropoff-time="<?= esc($r['time']) ?>"
                                    data-dropoff-loc="<?= esc($r['to']) ?>"
                                    onmouseenter="dshShowTip(event,this)" onmouseleave="dshHideTip()" onmousemove="dshMoveTip(event)">
                                    <div class="dsh-qrow__time">
                                        <span class="dsh-qrow__hm"><?= esc($r['time']) ?></span>
                                        <small class="dsh-qrow__type">Pickup</small>
                                    </div>
                                    <div class="dsh-qrow__who">
                                        <div class="dsh-av"><?= dshInitials(trim($ord['first_name'].' '.$ord['last_name'])) ?></div>
                                        <div>
                                            <div class="dsh-qrow__nm"><?= esc($ord['first_name']) ?> <?= esc($ord['last_name']) ?></div>
                                            <div class="dsh-qrow__sub">#<?= esc($ord['order_id']) ?> &middot; <?= dshSvcPill($ord['service_type']) ?></div>
                                        </div>
                                    </div>
                                    <div class="dsh-qrow__route">
                                        <div class="dsh-qrow__rtleg">
                                            <span class="dsh-qrow__rtdot dsh-qrow__rtdot--from"></span>
                                            <div>
                                                <div class="dsh-qrow__rtlbl">FROM</div>
                                                <div class="dsh-qrow__rtval"><?= esc($r['from']) ?></div>
                                            </div>
                                        </div>
                                        <div class="dsh-qrow__rtleg">
                                            <span class="dsh-qrow__rtdot dsh-qrow__rtdot--to"></span>
                                            <div>
                                                <div class="dsh-qrow__rtlbl">TO</div>
                                                <div class="dsh-qrow__rtval"><?= esc($r['to']) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dsh-qrow__total">RM <?= number_format((float)$ord['amount'], 2) ?></div>
                                    <div class="dsh-qrow__status" onclick="event.stopPropagation()">
                                        <?= dshStatusBadge((int)$ord['status'], (int)$ord['order_id']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($storageOrders) > 10): ?>
                            <div class="dsh-more-link"><a href="<?= base_url('/order?service_type=storage') ?>">+ <?= count($storageOrders) - 10 ?> more</a></div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="dsh-empty"><i class="fas fa-warehouse"></i><p>No active storage orders.</p></div>
                    <?php endif; ?>
                </div>

                <!-- Delivery -->
                <div class="dsh-tab-pane" id="tab-delivery">
                    <?php if (!empty($deliveryOrders)): ?>
                        <div class="dsh-queue">
                            <?php foreach (array_slice($deliveryOrders, 0, 10) as $ord): $r = dshParseRoute($ord); ?>
                                <div class="dsh-qrow <?= dshStatusRowCls((int)$ord['status']) ?>"
                                    onclick="window.location='<?= base_url('/admin/order_details/'.esc($ord['order_id'])) ?>'"
                                    data-pickup-time="<?= esc($r['time']) ?>"
                                    data-pickup-loc="<?= esc($r['from']) ?>"
                                    data-dropoff-time="<?= esc($r['time']) ?>"
                                    data-dropoff-loc="<?= esc($r['to']) ?>"
                                    onmouseenter="dshShowTip(event,this)" onmouseleave="dshHideTip()" onmousemove="dshMoveTip(event)">
                                    <div class="dsh-qrow__time">
                                        <span class="dsh-qrow__hm"><?= esc($r['time']) ?></span>
                                        <small class="dsh-qrow__type">Drop-off</small>
                                    </div>
                                    <div class="dsh-qrow__who">
                                        <div class="dsh-av"><?= dshInitials(trim($ord['first_name'].' '.$ord['last_name'])) ?></div>
                                        <div>
                                            <div class="dsh-qrow__nm"><?= esc($ord['first_name']) ?> <?= esc($ord['last_name']) ?></div>
                                            <div class="dsh-qrow__sub">#<?= esc($ord['order_id']) ?> &middot; <?= dshSvcPill($ord['service_type']) ?></div>
                                        </div>
                                    </div>
                                    <div class="dsh-qrow__route">
                                        <div class="dsh-qrow__rtleg">
                                            <span class="dsh-qrow__rtdot dsh-qrow__rtdot--from"></span>
                                            <div>
                                                <div class="dsh-qrow__rtlbl">FROM</div>
                                                <div class="dsh-qrow__rtval"><?= esc($r['from']) ?></div>
                                            </div>
                                        </div>
                                        <div class="dsh-qrow__rtleg">
                                            <span class="dsh-qrow__rtdot dsh-qrow__rtdot--to"></span>
                                            <div>
                                                <div class="dsh-qrow__rtlbl">TO</div>
                                                <div class="dsh-qrow__rtval"><?= esc($r['to']) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dsh-qrow__total">RM <?= number_format((float)$ord['amount'], 2) ?></div>
                                    <div class="dsh-qrow__status" onclick="event.stopPropagation()">
                                        <?= dshStatusBadge((int)$ord['status'], (int)$ord['order_id']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($deliveryOrders) > 10): ?>
                            <div class="dsh-more-link"><a href="<?= base_url('/order?service_type=delivery') ?>">+ <?= count($deliveryOrders) - 10 ?> more</a></div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="dsh-empty"><i class="fas fa-truck"></i><p>No active delivery orders.</p></div>
                    <?php endif; ?>
                </div>

            </div><!-- /dsh-card__scroll -->
        </div><!-- /queue card -->

        <!-- ── Calendar card ────────────────────────────────── -->
        <div class="dsh-card">
            <div class="dsh-card__head">
                <span class="dsh-card__pill"><span class="dsh-card__dot" style="background:#ECE2B4;"></span>Order Calendar</span>
                <div class="d-flex align-items-center gap-1 ms-auto">
                    <button class="dsh-cal-nav-btn" id="calPrev"><i class="fas fa-chevron-left"></i></button>
                    <span id="calMonthLabel" class="dsh-card__meta" style="margin-left:0;"></span>
                    <button class="dsh-cal-nav-btn" id="calNext"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            <div style="padding: 12px 14px 10px;">
                <div id="calendarGrid" class="dsh-cal mb-2"></div>
                <div class="dsh-cal-legend justify-content-end mb-2">
                    <span class="dsh-cal-legend-swatch" style="background:#EEF2F7;"></span>None
                    <span class="dsh-cal-legend-swatch" style="background:#FFE9A6;"></span>1–2
                    <span class="dsh-cal-legend-swatch" style="background:#FFD95E;"></span>3–5
                    <span class="dsh-cal-legend-swatch" style="background:#F2BE00;"></span>6–10
                    <span class="dsh-cal-legend-swatch" style="background:#D7A300;"></span>11+
                </div>
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
        </div><!-- /calendar card -->

    </div><!-- /dsh-ops-grid -->

    <!-- ═══════════════════════════════════════════════════════
         SECTION HEAD — RECENT ACTIVITY
    ════════════════════════════════════════════════════════ -->
    <div class="dsh-shead">
        <span class="dsh-shead__ttl">Recent activity</span>
        <span class="dsh-shead__rule"></span>
        <span class="dsh-shead__meta">Last 30 events</span>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         ACTIVITY FEED
    ════════════════════════════════════════════════════════ -->
    <div class="dsh-card" style="margin-bottom: 32px;">
        <div class="dsh-card__head">
            <span class="dsh-card__pill"><span class="dsh-card__dot"></span>Activity feed</span>
        </div>
        <div style="padding: 4px 0;">
            <?php
            $feedItems = [];
            if ($unreadMessages > 0) {
                $feedItems[] = [
                    'time'    => date('Y-m-d H:i:s'),
                    'ico'     => 'fas fa-envelope',
                    'ico_cls' => 'dsh-activity-icon--message',
                    'title'   => $unreadMessages.' unread message'.($unreadMessages !== 1 ? 's' : ''),
                    'desc'    => 'Customer enquiries awaiting reply',
                    'link'    => base_url('/admin/contact'),
                ];
            }
            if ($pendingRefunds > 0) {
                $feedItems[] = [
                    'time'    => date('Y-m-d H:i:s'),
                    'ico'     => 'fas fa-undo-alt',
                    'ico_cls' => 'dsh-activity-icon--message',
                    'title'   => $pendingRefunds.' pending refund'.($pendingRefunds !== 1 ? 's' : ''),
                    'desc'    => 'Awaiting admin action',
                    'link'    => base_url('/admin/refund_request'),
                ];
            }
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
            $feedItems = array_slice($feedItems, 0, 30);
            ?>
            <?php if (!empty($feedItems)): ?>
                <div id="dshFeedList">
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
                </div>
                <div id="dshFeedNav" class="dsh-feed-nav">
                    <button id="dshFeedPrev" class="dsh-feed-nav-btn"><i class="fas fa-chevron-left me-1"></i>Prev</button>
                    <span id="dshFeedInfo" class="dsh-feed-nav-info"></span>
                    <button id="dshFeedNext" class="dsh-feed-nav-btn">Next<i class="fas fa-chevron-right ms-1"></i></button>
                </div>
            <?php else: ?>
                <div class="text-center py-3" style="color:#9CA3AF;font-size:.76rem;font-family:'Oxanium',sans-serif;">No recent activity</div>
            <?php endif; ?>
        </div>
    </div><!-- /activity feed -->

</div><!-- /dsh-page -->

<div id="dshRowTooltip"></div>

<script>
var CHANGE_STATUS_BASE = '<?= base_url('/change_status/') ?>';
var ORDER_BASE         = '<?= base_url('/order') ?>';
var heatData           = <?= json_encode($calendarHeatmap,  JSON_THROW_ON_ERROR) ?>;
var calOrders          = <?= json_encode($calendarOrders,   JSON_THROW_ON_ERROR) ?>;

// ── Live clock ──────────────────────────────────────────────────────────
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

// ── Tab switching ───────────────────────────────────────────────────────
function switchTab(tabId) {
    document.querySelectorAll('#opsTabStrip .dsh-tab').forEach(function(p) {
        p.classList.toggle('act', p.dataset.tab === tabId);
    });
    document.querySelectorAll('.dsh-tab-pane').forEach(function(p) { p.classList.remove('active'); });
    var t = document.getElementById('tab-' + tabId);
    if (t) t.classList.add('active');
}
document.querySelectorAll('#opsTabStrip .dsh-tab').forEach(function(pill) {
    pill.addEventListener('click', function() { switchTab(this.dataset.tab); });
});

// ── Inline status cycle (AJAX) ──────────────────────────────────────────
var STATUS_LABELS  = ['Pending', 'In Progress', 'Completed'];
var STATUS_CLASSES = ['dsh-status--pending', 'dsh-status--progress', 'dsh-status--completed'];
var ROW_CLASSES    = ['dsh-qrow--pending',   'dsh-qrow--progress',   'dsh-qrow--done'];

function cycleStatus(el) {
    var orderId    = el.dataset.order;
    var cur        = parseInt(el.dataset.status, 10);
    var nxtPreview = (cur + 1) % 3;
    swal({
        title: 'Change order status?',
        text:  'Order #'+orderId+'\n'+STATUS_LABELS[cur]+' → '+STATUS_LABELS[nxtPreview],
        icon:  'warning',
        buttons: ['Cancel', 'Yes, change it'],
        dangerMode: true
    }).then(function(ok) {
        if (!ok) return;
        fetch(CHANGE_STATUS_BASE + orderId, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                var nxt = data.new_status;
                el.dataset.status = nxt;
                el.textContent    = STATUS_LABELS[nxt];
                el.className      = 'dsh-status-badge ' + STATUS_CLASSES[nxt];
                var row = el.closest('.dsh-qrow');
                if (row) {
                    row.classList.remove('dsh-qrow--pending', 'dsh-qrow--progress', 'dsh-qrow--done');
                    row.classList.add(ROW_CLASSES[nxt]);
                }
                swal({ title: 'Updated!', text: 'Order #'+orderId+' is now '+STATUS_LABELS[nxt]+'.', icon: 'success', button: 'OK' });
            } else {
                swal('Error', 'Could not update status.', 'error');
            }
        })
        .catch(function() { swal('Error', 'Network error.', 'error'); });
    });
}

// ── Calendar ────────────────────────────────────────────────────────────
var calYear  = <?= (int)date('Y') ?>;
var calMonth = <?= (int)date('m') ?>;
var MONTHS   = ['January','February','March','April','May','June','July','August','September','October','November','December'];
var DAYS     = ['Su','Mo','Tu','We','Th','Fr','Sa'];

function heatClass(t) {
    if (!t)     return '';
    if (t <= 2) return 'cal-has-data cal-has-data-l1';
    if (t <= 5) return 'cal-has-data cal-has-data-l2';
    if (t <= 10)return 'cal-has-data cal-has-data-l3';
    return 'cal-has-data cal-has-data-l4';
}
function renderCal(year, month) {
    document.getElementById('calMonthLabel').textContent = MONTHS[month-1]+' '+year;
    var first = new Date(year, month-1, 1).getDay();
    var days  = new Date(year, month, 0).getDate();
    var today = new Date().toISOString().slice(0,10);
    var html  = '<div class="cal-grid">';
    DAYS.forEach(function(d){ html += '<div class="dsh-cal-dow">'+d+'</div>'; });
    for (var i = 0; i < first; i++) html += '<div class="cal-day cal-blank"></div>';
    for (var day = 1; day <= days; day++) {
        var ds   = year+'-'+String(month).padStart(2,'0')+'-'+String(day).padStart(2,'0');
        var info = heatData[ds] || {created:0,pickups:0,dropoffs:0,total:0};
        var hc   = heatClass(info.total);
        var cls  = 'cal-day cal-clickable'+(ds===today?' cal-today':'')+(hc?' '+hc:'');
        var tip  = info.total===0?'No activity':info.total+' activities — '+info.created+' new, '+info.pickups+' pickups, '+info.dropoffs+' drop-offs';
        html += '<div class="'+cls+'" title="'+ds+': '+tip+'" onclick="showCalDetail(\''+ds+'\')">'+day+'</div>';
    }
    html += '</div>';
    document.getElementById('calendarGrid').innerHTML = html;
}
function showCalDetail(ds) {
    var info  = heatData[ds] || {created:0,pickups:0,dropoffs:0,total:0};
    var panel = document.getElementById('calDetailPanel');
    var parts = ds.split('-');
    var dto   = new Date(+parts[0], +parts[1]-1, +parts[2]);
    document.getElementById('calDetailDate').textContent = dto.toLocaleDateString('en-MY',{weekday:'long',day:'numeric',month:'long',year:'numeric'});
    var html  = '';
    if (info.total) {
        html += '<div class="dsh-cal-detail-row" style="font-size:.7rem;margin-bottom:6px;">';
        if (info.created)  html += '<span style="margin-right:10px;"><strong>'+info.created+'</strong> new</span>';
        if (info.pickups)  html += '<span style="margin-right:10px;"><strong>'+info.pickups+'</strong> pickups</span>';
        if (info.dropoffs) html += '<span><strong>'+info.dropoffs+'</strong> drop-offs</span>';
        html += '</div>';
    }
    var matched = calOrders.filter(function(o){ return o.created===ds||o.pickup===ds||o.dropoff===ds; });
    if (matched.length > 0) {
        matched.slice(0,6).forEach(function(o){
            var svcCls = o.service&&o.service.toLowerCase()==='storage'?'dsh-svc-pill--storage':'dsh-svc-pill--delivery';
            var stMap  = {0:'dsh-status--pending',1:'dsh-status--progress',2:'dsh-status--completed'};
            var stLbl  = ['Pending','In Progress','Completed'];
            html += '<div class="dsh-cal-order-card">'
                  + '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:3px;">'
                  + '<strong style="font-size:.74rem;">#'+o.id+' — '+o.name+'</strong>'
                  + '<span class="dsh-svc-pill '+svcCls+'">'+o.service+'</span></div>';
            if (o.pickup)  html += '<div style="font-size:.68rem;color:#6B7280;"><i class="fas fa-truck me-1" style="color:#F2BE00;"></i>Pickup: '+o.pickup+(o.pickup_t?' at '+o.pickup_t:'')+'</div>';
            if (o.dropoff) html += '<div style="font-size:.68rem;color:#6B7280;"><i class="fas fa-box-open me-1" style="color:#F2BE00;"></i>Drop-off: '+o.dropoff+(o.dropoff_t?' at '+o.dropoff_t:'')+'</div>';
            html += '<div style="margin-top:3px;"><span class="dsh-status-badge '+stMap[o.status]+'" style="pointer-events:none;cursor:default;">'+stLbl[o.status]+'</span>'
                  + '<span style="font-size:.68rem;color:#6B7280;margin-left:8px;">RM '+o.amount.toFixed(2)+'</span></div></div>';
        });
        if (matched.length > 6) html += '<div style="font-size:.68rem;color:#9CA3AF;margin-top:4px;">+ '+(matched.length-6)+' more</div>';
    } else if (!info.total) {
        html = '<div style="font-size:.74rem;color:#9CA3AF;">No activity on this date.</div>';
    }
    document.getElementById('calDetailBody').innerHTML = html;
    document.getElementById('calDetailLink').href = ORDER_BASE+'?start_date='+ds+'&end_date='+ds;
    panel.classList.add('visible');
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

// ── KPI cycling ─────────────────────────────────────────────────────────
(function() {
    document.querySelectorAll('.dsh-kpi').forEach(function(card) {
        var slides = card.querySelectorAll('.dsh-kpi-slide');
        var dots   = card.querySelectorAll('.dsh-kpi-dot');
        if (slides.length <= 1) return;
        var idx = 0;

        function goTo(i) {
            slides[idx].classList.remove('kpi-active');
            dots[idx].classList.remove('kpi-dot-act');
            idx = i;
            slides[idx].classList.add('kpi-active');
            dots[idx].classList.add('kpi-dot-act');
        }

        // Dot click: jump to specific slide
        dots.forEach(function(dot, i) {
            dot.addEventListener('click', function(e) {
                e.stopPropagation();
                goTo(i);
            });
        });

        // Card click anywhere: advance to next slide
        card.addEventListener('click', function() {
            goTo((idx + 1) % slides.length);
        });
    });
})();

// ── Activity feed pagination ─────────────────────────────────────────────
(function() {
    var items    = Array.from(document.querySelectorAll('#dshFeedList .dsh-activity-item'));
    var perPage  = 10;
    var page     = 0;
    var total    = Math.ceil(items.length / perPage);
    var prevBtn  = document.getElementById('dshFeedPrev');
    var nextBtn  = document.getElementById('dshFeedNext');
    var infoEl   = document.getElementById('dshFeedInfo');
    var nav      = document.getElementById('dshFeedNav');

    function render() {
        var start = page * perPage;
        items.forEach(function(el, i) {
            el.style.display = (i >= start && i < start + perPage) ? '' : 'none';
        });
        if (prevBtn) prevBtn.disabled = page === 0;
        if (nextBtn) nextBtn.disabled = page >= total - 1;
        if (infoEl)  infoEl.textContent = (page + 1) + ' / ' + total;
    }
    if (items.length <= perPage && nav) nav.style.display = 'none';
    if (prevBtn) prevBtn.addEventListener('click', function() { if (page > 0) { page--; render(); } });
    if (nextBtn) nextBtn.addEventListener('click', function() { if (page < total - 1) { page++; render(); } });
    render();
})();

// ── Row tooltip ─────────────────────────────────────────────────────────
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
function dshMoveTip(e) { _dshTip.style.left=(e.clientX+14)+'px'; _dshTip.style.top=(e.clientY+14)+'px'; }
function dshHideTip()  { _dshTip.style.display='none'; }
</script>

<?= $this->include('admin/footer'); ?>
