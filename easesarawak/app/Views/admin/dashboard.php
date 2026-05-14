<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/report.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/admin/dashboard.css') ?>">

<?php
$hour        = (int)date('G');
$greeting    = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
$sessionUser   = session()->get('username') ?? 'Admin';
$dshQueueLimit = 6;

function dshSvcPill(string $type): string {
    $l         = strtolower($type);
    $isStorage = $l === 'storage';
    $cls       = $isStorage ? 'dsh-svc-pill--storage' : 'dsh-svc-pill--delivery';
    $icon      = $isStorage ? 'fa-warehouse' : 'fa-truck';
    $label     = htmlspecialchars($isStorage ? 'Storage' : 'Delivery');
    return '<span class="dsh-svc-pill ' . $cls . '">'
        . '<span class="dsh-svc-pill__ico" aria-hidden="true"><i class="fas ' . $icon . '"></i></span>'
        . $label
        . '</span>';
}
function dshCountActive(array $orders): int {
    return count(array_filter($orders, static fn($o) => (int)($o['status'] ?? 0) < 2));
}
function dshAvColor(int $orderId): string {
    $palette = ['#5B532C', '#B8860B', '#0A0A0A', '#1A6CB0', '#2BA869', '#6A4FBB'];
    return $palette[$orderId % count($palette)];
}
function dshRenderQueueRow(array $ord, string $timeLabel): void {
    $r      = dshParseRoute($ord);
    $oid    = (int)$ord['order_id'];
    $status = (int)$ord['status'];
    $url    = ease_route('order_details', $oid);
    $name   = trim(($ord['first_name'] ?? '') . ' ' . ($ord['last_name'] ?? ''));
    $avBg   = dshAvColor($oid);
    $avFg   = ($avBg === '#0A0A0A') ? '#F2BE00' : '#fff';
    ?>
    <a class="dsh-qrow <?= dshStatusRowCls($status) ?>" href="<?= esc($url, 'attr') ?>">
        <div class="dsh-qrow__cell dsh-qrow__time">
            <span class="dsh-qrow__hm"><?= esc($r['time']) ?></span>
            <small class="dsh-qrow__type"><?= esc($timeLabel) ?></small>
        </div>
        <div class="dsh-qrow__cell dsh-qrow__who">
            <div class="dsh-av" style="background:<?= esc($avBg) ?>;color:<?= esc($avFg) ?>"><?= dshInitials($name) ?></div>
            <div class="dsh-qrow__who-text">
                <div class="dsh-qrow__nm"><?= esc($ord['first_name']) ?> <?= esc($ord['last_name']) ?></div>
                <div class="dsh-qrow__meta-row">
                    <span class="dsh-qrow__oid">#<?= esc($oid) ?></span>
                    <?= dshSvcPill($ord['service_type'] ?? '') ?>
                </div>
            </div>
        </div>
        <div class="dsh-qrow__cell dsh-qrow__route-col">
            <span class="dsh-qrow__rtlbl"><?= esc($r['leg1_label']) ?></span>
            <span class="dsh-qrow__rtval"><?= esc($r['leg1_value']) ?></span>
            <?php if (!empty($r['leg1_meta'])): ?>
                <span class="dsh-qrow__rtmeta"><?= esc($r['leg1_meta']) ?></span>
            <?php endif; ?>
        </div>
        <div class="dsh-qrow__cell dsh-qrow__route-col <?= !empty($r['is_storage']) ? 'dsh-qrow__route-col--store' : 'dsh-qrow__route-col--to' ?>">
            <span class="dsh-qrow__rtlbl"><?= esc($r['leg2_label']) ?></span>
            <span class="dsh-qrow__rtval"><?= esc($r['leg2_value']) ?></span>
            <?php if (!empty($r['leg2_meta'])): ?>
                <span class="dsh-qrow__rtmeta"><?= esc($r['leg2_meta']) ?></span>
            <?php endif; ?>
        </div>
        <div class="dsh-qrow__cell dsh-qrow__total">RM <?= number_format((float)$ord['amount'], 2) ?></div>
        <div class="dsh-qrow__cell dsh-qrow__status" onclick="event.preventDefault(); event.stopPropagation()">
            <?= dshStatusBadge($status, $oid) ?>
        </div>
    </a>
    <?php
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
function dshExtractQueueTime(string $raw): string {
    $raw = trim($raw);
    if ($raw === '' || strcasecmp($raw, 'Null') === 0) {
        return '';
    }
    $atPos = stripos($raw, ' at ');
    if ($atPos !== false) {
        return trim(substr($raw, $atPos + 4));
    }
    if (preg_match('/^\d{1,2}:\d{2}/', $raw)) {
        return $raw;
    }
    return '';
}

function dshQueueTimeForOrder(array $ord, bool $usePickup): string {
    $d     = @json_decode($ord['order_details_json'] ?? '{}', true);
    $d     = is_array($d) ? $d : [];
    $field = $usePickup ? 'Pickup DateTime' : 'Drop-off DateTime';
    $time  = dshExtractQueueTime(trim($d[$field] ?? ''));
    if ($time !== '') {
        return $time;
    }
    $fallback = $usePickup ? ($ord['_pickup_time'] ?? '') : ($ord['_dropoff_time'] ?? '');
    $time     = dshExtractQueueTime((string) $fallback);
    if ($time !== '') {
        return $time;
    }
    $legacy = $usePickup
        ? trim($d['pickupTime'] ?? '')
        : trim($d['dropoffTime'] ?? '');
    return dshExtractQueueTime($legacy) ?: (preg_match('/^\d{1,2}:\d{2}/', $legacy) ? $legacy : '');
}

function dshCleanDetailValue(string $v): string {
    $v = trim($v);
    return ($v === '' || strcasecmp($v, 'Null') === 0) ? '' : $v;
}

function dshFormatQueueDateTime(string $raw): string {
    $raw = dshCleanDetailValue($raw);
    if ($raw === '') {
        return '';
    }
    $atPos = stripos($raw, ' at ');
    if ($atPos !== false) {
        $datePart = trim(substr($raw, 0, $atPos));
        $timePart = trim(substr($raw, $atPos + 4));
        $ts       = strtotime($datePart);
        if ($ts !== false) {
            return date('d M Y', $ts) . ($timePart !== '' ? ', ' . $timePart : '');
        }
    }
    $ts = strtotime($raw);
    return $ts !== false ? date('d M Y, H:i', $ts) : $raw;
}

function dshParseRoute(array $ord): array {
    $d   = @json_decode($ord['order_details_json'] ?? '{}', true);
    $d   = is_array($d) ? $d : [];
    $svc = strtolower($ord['service_type'] ?? '');

    if ($svc === 'storage') {
        $storageLoc = dshCleanDetailValue($d['Storage Location'] ?? '')
            ?: dshCleanDetailValue($ord['_pickup_location'] ?? '');
        $originLoc = dshCleanDetailValue($d['Origin Location'] ?? $d['Origin Address'] ?? '');
        $dropoffRaw = dshCleanDetailValue($d['Drop-off DateTime'] ?? $ord['_dropoff_time'] ?? '');
        $pickupRaw  = dshCleanDetailValue($d['Pickup DateTime'] ?? $ord['_pickup_time'] ?? '');
        $dropoffFmt = dshFormatQueueDateTime($dropoffRaw);
        $pickupFmt  = dshFormatQueueDateTime($pickupRaw);
        $duration   = ($dropoffFmt && $pickupFmt)
            ? $dropoffFmt . ' → ' . $pickupFmt
            : ($dropoffFmt ?: $pickupFmt);

        return [
            'is_storage'  => true,
            'time'        => dshQueueTimeForOrder($ord, true) ?: '—',
            'leg1_label'  => 'From',
            'leg1_value'  => $originLoc ?: $storageLoc ?: '—',
            'leg1_meta'   => $dropoffFmt !== '' ? 'Drop-off · ' . $dropoffFmt : '',
            'leg2_label'  => 'Store',
            'leg2_value'  => $storageLoc ?: '—',
            'leg2_meta'   => $duration !== '' ? $duration : '',
            'leg2_class'  => 'dsh-qrow__leg--store',
        ];
    }

    $from = dshCleanDetailValue($d['origin'] ?? $d['originAddress'] ?? '')
        ?: dshCleanDetailValue($ord['_pickup_location'] ?? '');
    $to   = dshCleanDetailValue($d['destination'] ?? $d['destinationAddress'] ?? '')
        ?: dshCleanDetailValue($ord['_dropoff_location'] ?? '');

    return [
        'is_storage'  => false,
        'time'        => dshQueueTimeForOrder($ord, false) ?: '—',
        'leg1_label'  => 'From',
        'leg1_value'  => $from ?: '—',
        'leg1_meta'   => '',
        'leg2_label'  => 'To',
        'leg2_value'  => $to ?: '—',
        'leg2_meta'   => '',
        'leg2_class'  => 'dsh-qrow__leg--to',
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
        <a href="<?= ease_route('order') ?>" class="dsh-shead__meta">View all orders →</a>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         2-COLUMN: QUEUE + CALENDAR
    ════════════════════════════════════════════════════════ -->
    <div class="dsh-ops-grid">

        <!-- ── Queue card ──────────────────────────────────── -->
        <div class="dsh-card dsh-card--queue">
            <div class="dsh-card__head">
                <span class="dsh-card__pill" id="opsQueuePill"><span class="dsh-card__dot"></span><span id="opsQueuePillText">Pending orders</span></span>
                <div class="dsh-tabs" id="opsTabStrip">
                    <button type="button" class="dsh-tab act" data-tab="pending" data-pill="Pending orders" data-active="<?= dshCountActive(!empty($pending_orders_display) ? $pending_orders_display : ($pendingFallbackOrders ?? [])) ?>">
                        Pending<?php if ($pendingCount): ?>&nbsp;<span class="dsh-tab__num"><?= $pendingCount ?></span><?php endif; ?>
                    </button>
                    <button type="button" class="dsh-tab" data-tab="storage" data-pill="Storage orders" data-active="<?= dshCountActive($storageOrders ?? []) ?>">
                        Storage<?php if (!empty($storageOrders)): ?>&nbsp;<span class="dsh-tab__num"><?= count($storageOrders) ?></span><?php endif; ?>
                    </button>
                    <button type="button" class="dsh-tab" data-tab="delivery" data-pill="Delivery orders" data-active="<?= dshCountActive($deliveryOrders ?? []) ?>">
                        Delivery<?php if (!empty($deliveryOrders)): ?>&nbsp;<span class="dsh-tab__num"><?= count($deliveryOrders) ?></span><?php endif; ?>
                    </button>
                </div>
                <span class="dsh-queue-active" id="opsQueueMeta">TODAY &middot; <?= dshCountActive(!empty($pending_orders_display) ? $pending_orders_display : ($pendingFallbackOrders ?? [])) ?> ACTIVE</span>
            </div>

            <div class="dsh-card__scroll dsh-card__scroll--queue">

                <!-- Column header -->
                <div class="dsh-queue-head">
                    <div>Time</div>
                    <div>Customer</div>
                    <div>From</div>
                    <div id="opsRouteCol2">To</div>
                    <div>Total</div>
                    <div>Status</div>
                </div>

                <div class="dsh-queue-body">
                <!-- Pending -->
                <div class="dsh-tab-pane dsh-tab-pane--queue active" id="tab-pending">
                    <?php $displayOrders = !empty($pending_orders_display) ? $pending_orders_display : ($pendingFallbackOrders ?? []); ?>
                    <?php if (!empty($pendingFallbackDate)): ?>
                        <div class="dsh-fallback-notice">
                            <i class="fas fa-info-circle me-1"></i>No pending orders today. Next scheduled: <strong><?= date('d M Y', strtotime($pendingFallbackDate)) ?></strong>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($displayOrders)): ?>
                        <div class="dsh-queue-pane-inner" style="--dsh-queue-rows: <?= min(count($displayOrders), $dshQueueLimit) ?>">
                            <div class="dsh-queue">
                            <?php foreach (array_slice($displayOrders, 0, $dshQueueLimit) as $ord):
                                $timeLabel = strtolower($ord['service_type'] ?? '') === 'storage' ? 'Pickup' : 'Drop-off';
                                dshRenderQueueRow($ord, $timeLabel);
                            endforeach; ?>
                            </div>
                        </div>
                        <div class="dsh-more-link dsh-more-link--foot">
                            <a href="<?= ease_route('order') ?>">
                                <?php if (count($displayOrders) > $dshQueueLimit): ?>+ <?= count($displayOrders) - $dshQueueLimit ?> more &mdash; <?php endif; ?>View all orders
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="dsh-empty">
                            <i class="fas fa-check-circle" style="color:#1E8E3E;opacity:1;"></i>
                            <p>All caught up — no pending orders.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Storage -->
                <div class="dsh-tab-pane dsh-tab-pane--queue" id="tab-storage">
                    <?php if (!empty($storageOrders)): ?>
                        <div class="dsh-queue-pane-inner" style="--dsh-queue-rows: <?= min(count($storageOrders), $dshQueueLimit) ?>">
                            <div class="dsh-queue">
                                <?php foreach (array_slice($storageOrders, 0, $dshQueueLimit) as $ord):
                                    $timeLabel = 'Pickup';
                                    dshRenderQueueRow($ord, $timeLabel);
                                endforeach; ?>
                            </div>
                        </div>
                        <div class="dsh-more-link dsh-more-link--foot">
                            <a href="<?= ease_route('order') ?>">
                                <?php if (count($storageOrders) > $dshQueueLimit): ?>+ <?= count($storageOrders) - $dshQueueLimit ?> more &mdash; <?php endif; ?>View all orders
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="dsh-empty"><i class="fas fa-warehouse"></i><p>No active storage orders.</p></div>
                    <?php endif; ?>
                </div>

                <!-- Delivery -->
                <div class="dsh-tab-pane dsh-tab-pane--queue" id="tab-delivery">
                    <?php if (!empty($deliveryOrders)): ?>
                        <div class="dsh-queue-pane-inner" style="--dsh-queue-rows: <?= min(count($deliveryOrders), $dshQueueLimit) ?>">
                            <div class="dsh-queue">
                                <?php foreach (array_slice($deliveryOrders, 0, $dshQueueLimit) as $ord):
                                    $timeLabel = 'Drop-off';
                                    dshRenderQueueRow($ord, $timeLabel);
                                endforeach; ?>
                            </div>
                        </div>
                        <div class="dsh-more-link dsh-more-link--foot">
                            <a href="<?= ease_route('order') ?>">
                                <?php if (count($deliveryOrders) > $dshQueueLimit): ?>+ <?= count($deliveryOrders) - $dshQueueLimit ?> more &mdash; <?php endif; ?>View all orders
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="dsh-empty"><i class="fas fa-truck"></i><p>No active delivery orders.</p></div>
                    <?php endif; ?>
                </div>
                </div><!-- /dsh-queue-body -->

            </div><!-- /dsh-card__scroll -->
        </div><!-- /queue card -->

        <!-- ── Calendar card ────────────────────────────────── -->
        <div class="dsh-card dsh-card--calendar">
            <div class="dsh-card__head">
                <span class="dsh-card__pill"><span class="dsh-card__dot" style="background:#ECE2B4;"></span>Order calendar</span>
                <div class="d-flex align-items-center gap-1 ms-auto">
                    <button type="button" class="dsh-cal-nav-btn" id="calPrev" aria-label="Previous month"><i class="fas fa-chevron-left"></i></button>
                    <span id="calMonthLabel" class="dsh-card__meta dsh-cal-month-label"></span>
                    <button type="button" class="dsh-cal-nav-btn" id="calNext" aria-label="Next month"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="dsh-cal-wrap">
                <div id="calendarGrid" class="dsh-cal"></div>
                <div class="dsh-cal-legend">
                    <span class="dsh-cal-legend-swatch" style="background:#EEF2F7;"></span>None
                    <span class="dsh-cal-legend-swatch" style="background:#FFE9A6;"></span>1–2 due
                    <span class="dsh-cal-legend-swatch" style="background:#FFD95E;"></span>3–5
                    <span class="dsh-cal-legend-swatch" style="background:#F2BE00;"></span>6–10
                    <span class="dsh-cal-legend-swatch" style="background:#D7A300;"></span>11+
                </div>
                <div id="calDetailPanel" class="dsh-cal-detail">
                    <div class="dsh-cal-detail-title">
                        <span id="calDetailDate"></span>
                    </div>
                    <div id="calDetailBody"></div>
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
        <span class="dsh-shead__meta">Latest 6</span>
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
                    'link'    => ease_route('contact'),
                ];
            }
            if ($pendingRefunds > 0) {
                $feedItems[] = [
                    'time'    => date('Y-m-d H:i:s'),
                    'ico'     => 'fas fa-undo-alt',
                    'ico_cls' => 'dsh-activity-icon--message',
                    'title'   => $pendingRefunds.' pending refund'.($pendingRefunds !== 1 ? 's' : ''),
                    'desc'    => 'Awaiting admin action',
                    'link'    => ease_route('refund'),
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
                    'link'    => ease_route('order'),
                ];
            }
            foreach ($recentOrders as $ord) {
                $feedItems[] = [
                    'time'    => $ord['created_date'],
                    'ico'     => 'fas fa-shopping-bag',
                    'ico_cls' => 'dsh-activity-icon--order',
                    'title'   => 'New '.strtoupper(esc($ord['service_type'])).' order placed',
                    'desc'    => esc($ord['first_name']).' '.esc($ord['last_name']).' · RM '.number_format((float)$ord['amount'],2),
                    'link'    => ease_route('order'),
                ];
            }
            foreach ($recentMessages as $msg) {
                $feedItems[] = [
                    'time'    => $msg['created_date'],
                    'ico'     => 'fas fa-envelope',
                    'ico_cls' => 'dsh-activity-icon--message',
                    'title'   => 'Message: '.esc($msg['subject'] ?? '(no subject)'),
                    'desc'    => 'From '.esc($msg['email'] ?? '—'),
                    'link'    => ease_route('contact'),
                ];
            }
            usort($feedItems, static fn($a,$b) => strtotime($b['time']) - strtotime($a['time']));
            $feedItems = array_slice($feedItems, 0, 6);
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
            <?php else: ?>
                <div class="text-center py-3" style="color:#9CA3AF;font-size:.76rem;font-family:'Oxanium',sans-serif;">No recent activity</div>
            <?php endif; ?>
        </div>
    </div><!-- /activity feed -->

</div><!-- /dsh-page -->

<script>
var DSH_ROUTES = <?= json_encode([
    'changeStatus' => ease_route('change_status') . '/',
    'orderDetails' => ease_route('order_details') . '/',
], JSON_THROW_ON_ERROR) ?>;
var CHANGE_STATUS_BASE = DSH_ROUTES.changeStatus;
var ORDER_DETAIL_BASE  = DSH_ROUTES.orderDetails;
var heatData             = <?= json_encode($calendarHeatmap,  JSON_THROW_ON_ERROR) ?>;
var calOrders            = <?= json_encode($calendarOrders,   JSON_THROW_ON_ERROR) ?>;

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
    var btn = document.querySelector('#opsTabStrip .dsh-tab[data-tab="' + tabId + '"]');
    if (btn) {
        var pill = document.getElementById('opsQueuePillText');
        var meta = document.getElementById('opsQueueMeta');
        if (pill) pill.textContent = btn.dataset.pill || tabId;
        if (meta) meta.textContent = 'TODAY · ' + (btn.dataset.active || '0') + ' ACTIVE';
    }
    var routeCol2 = document.getElementById('opsRouteCol2');
    if (routeCol2) routeCol2.textContent = tabId === 'storage' ? 'Store' : 'To';
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
var calYear     = <?= (int)date('Y') ?>;
var calMonth    = <?= (int)date('m') ?>;
var calTodayStr = '<?= date('Y-m-d') ?>';
var MONTHS      = ['January','February','March','April','May','June','July','August','September','October','November','December'];
var DAYS        = ['Su','Mo','Tu','We','Th','Fr','Sa'];

function heatClass(t) {
    if (!t)     return '';
    if (t <= 2) return 'cal-has-data cal-has-data-l1';
    if (t <= 5) return 'cal-has-data cal-has-data-l2';
    if (t <= 10)return 'cal-has-data cal-has-data-l3';
    return 'cal-has-data cal-has-data-l4';
}
function calDayTip(info) {
    if (!info.total) return 'No pickups or drop-offs scheduled';
    var parts = [];
    if (info.pickups)  parts.push(info.pickups + ' pickup' + (info.pickups !== 1 ? 's' : ''));
    if (info.dropoffs) parts.push(info.dropoffs + ' drop-off' + (info.dropoffs !== 1 ? 's' : ''));
    return parts.join(', ');
}
function renderCal(year, month) {
    document.getElementById('calMonthLabel').textContent = MONTHS[month - 1] + ' ' + year;
    var first = new Date(year, month - 1, 1).getDay();
    var days  = new Date(year, month, 0).getDate();
    var today = new Date().toISOString().slice(0, 10);
    var html  = '<div class="cal-grid">';
    DAYS.forEach(function(d) { html += '<div class="dsh-cal-dow">' + d + '</div>'; });
    for (var i = 0; i < first; i++) html += '<div class="cal-day cal-blank"></div>';
    for (var day = 1; day <= days; day++) {
        var ds   = year + '-' + String(month).padStart(2, '0') + '-' + String(day).padStart(2, '0');
        var info = heatData[ds] || { pickups: 0, dropoffs: 0, total: 0 };
        var hc   = heatClass(info.total);
        var cls  = 'cal-day cal-clickable' + (ds === today ? ' cal-today' : '') + (hc ? ' ' + hc : '');
        var tip  = calDayTip(info);
        html += '<div class="' + cls + '" title="' + ds + ': ' + tip + '" onclick="showCalDetail(\'' + ds + '\')">' + day + '</div>';
    }
    html += '</div>';
    document.getElementById('calendarGrid').innerHTML = html;
    if (year === <?= (int)date('Y') ?> && month === <?= (int)date('m') ?>) {
        showCalDetail(calTodayStr);
    } else {
        showCalDetailHint();
    }
}
function showCalDetailHint() {
    document.getElementById('calDetailDate').textContent = 'Select a date';
    document.getElementById('calDetailBody').innerHTML =
        '<p class="dsh-cal-detail-hint">Click a day to see orders due for pickup or drop-off.</p>';
}
function showCalDetail(ds) {
    var parts = ds.split('-');
    var dto   = new Date(+parts[0], +parts[1] - 1, +parts[2]);
    document.getElementById('calDetailDate').textContent = dto.toLocaleDateString('en-MY', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
    });

    var html  = '';
    var items = [];
    calOrders.forEach(function(o) {
        if (o.pickup === ds) {
            items.push({
                id: o.id, name: o.name, kind: 'Pickup',
                time: o.pickup_t || '—', from: o.from, to: o.to
            });
        }
        if (o.dropoff === ds) {
            items.push({
                id: o.id, name: o.name, kind: 'Drop-off',
                time: o.dropoff_t || '—', from: o.from, to: o.to
            });
        }
    });

    if (items.length === 0) {
        html = '<p class="dsh-cal-detail-hint">No pickups or drop-offs scheduled this day.</p>';
    } else {
        items.forEach(function(item) {
            html += '<a class="dsh-cal-order-link" href="' + ORDER_DETAIL_BASE + item.id + '">'
                + '<span class="dsh-cal-order-link__head"><strong>#' + item.id + '</strong> ' + item.name + '</span>'
                + '<span class="dsh-cal-order-link__meta">' + item.kind + ' · ' + item.time + '</span>'
                + '<span class="dsh-cal-order-link__route">' + item.from + ' → ' + item.to + '</span>'
                + '</a>';
        });
    }
    document.getElementById('calDetailBody').innerHTML = html;
}
renderCal(calYear, calMonth);
document.getElementById('calPrev').addEventListener('click', function(){
    calMonth--; if (calMonth<1){ calMonth=12; calYear--; }
    renderCal(calYear, calMonth);
});
document.getElementById('calNext').addEventListener('click', function(){
    calMonth++; if (calMonth>12){ calMonth=1; calYear++; }
    renderCal(calYear, calMonth);
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
    });
    // Staggered auto-cycle
    setTimeout(function () {
        setInterval(function () { cycleKpi(card); }, 5000);
    }, cardIdx * 1300);
});

        // Card click anywhere: advance to next slide
        card.addEventListener('click', function() {
            goTo((idx + 1) % slides.length);
        });
    });
})();

</script>

<?= $this->include('admin/footer'); ?>
