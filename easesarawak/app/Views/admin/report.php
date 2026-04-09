<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/report.css') ?>">

<div class="rpt-page container-fluid py-3">

    <!-- ── Page Header ── -->
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-0 rpt-h3">Analytics &amp; Reports</h3>
            <ul class="breadcrumbs mb-0">
                <li class="nav-home"><a href="<?= base_url('/admin/dashboard') ?>"><i class="fa fa-home"></i></a></li>
                <li class="separator"><i class="fa fa-angle-right"></i></li>
                <li class="nav-item">Reports</li>
            </ul>
        </div>
        <!-- Export collapsed into dropdown -->
        <div class="dropdown">
            <button class="btn rpt-export-btn dropdown-toggle" type="button" id="exportDropdownBtn"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                <i class="fas fa-file-export me-1"></i>Export Report
            </button>
            <div class="dropdown-menu dropdown-menu-end rpt-export-dropdown p-3" aria-labelledby="exportDropdownBtn">
                <form id="exportForm" action="<?= base_url('report/export') ?>" method="GET" target="_blank">
                    <div class="mb-2">
                        <label class="export-label">Quick Range</label>
                        <select id="quickRange" class="export-range-select">
                            <option value="">— Select —</option>
                            <option value="this-month" selected>This Month</option>
                            <option value="last-month">Last Month</option>
                            <option value="last-3">Last 3 Months</option>
                            <option value="last-6">Last 6 Months</option>
                            <option value="this-year">This Year</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="export-label">From</label>
                            <input type="date" name="start_date" id="exportStart"
                                   class="form-control rpt-input" value="<?= date('Y-m-01') ?>">
                        </div>
                        <div class="col-6">
                            <label class="export-label">To</label>
                            <input type="date" name="end_date" id="exportEnd"
                                   class="form-control rpt-input" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="format" value="pdf" id="btnPdf"
                                class="btn btn-export-pdf flex-fill">
                            <i class="fas fa-file-pdf me-1"></i>PDF
                        </button>
                        <button type="submit" name="format" value="csv" id="btnCsv"
                                class="btn btn-export-csv flex-fill">
                            <i class="fas fa-file-csv me-1"></i>CSV
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════
         ROW 1 — KPI Stat Cards
    ══════════════════════════════════════ -->
    <div class="row g-2 mb-3">

        <div class="col-6 col-lg-3">
            <div class="rpt-kpi-card kpi-loop-card" data-kpi="revenue" title="Click to switch KPI">
                <div class="rpt-kpi-top">
                    <div class="rpt-kpi-label" data-kpi-label>Total Revenue</div>
                    <div class="rpt-kpi-icon rpt-kpi-icon--gold"><i class="fas fa-coins" data-kpi-icon></i></div>
                </div>
                <div class="rpt-kpi-value" data-kpi-value>RM <?= number_format($totalRevenue, 2) ?></div>
                <div class="rpt-kpi-foot">
                    <span class="rev-growth-badge <?= $weekRevenueGrowthPct >= 0 ? 'up' : 'down' ?>" data-kpi-badge>
                        <i class="fas fa-arrow-<?= $weekRevenueGrowthPct >= 0 ? 'up' : 'down' ?>"></i><span data-kpi-improvement><?= number_format(abs($weekRevenueGrowthPct), 1) ?>%</span>
                    </span>
                    <span class="rpt-kpi-sub" data-kpi-sub>vs last week</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="rpt-kpi-card kpi-loop-card" data-kpi="orders" title="Click to switch KPI">
                <div class="rpt-kpi-top">
                    <div class="rpt-kpi-label" data-kpi-label>Total Orders</div>
                    <div class="rpt-kpi-icon rpt-kpi-icon--brown"><i class="fas fa-shopping-bag" data-kpi-icon></i></div>
                </div>
                <div class="rpt-kpi-value" data-kpi-value><?= number_format($totalOrders) ?></div>
                <div class="rpt-kpi-foot">
                    <span class="rev-growth-badge <?= $weekOrderGrowthPct >= 0 ? 'up' : 'down' ?>" data-kpi-badge>
                        <i class="fas fa-arrow-<?= $weekOrderGrowthPct >= 0 ? 'up' : 'down' ?>"></i><span data-kpi-improvement><?= number_format(abs($weekOrderGrowthPct), 1) ?>%</span>
                    </span>
                    <span class="rpt-kpi-sub rpt-kpi-sub--neutral" data-kpi-sub>vs last week</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="rpt-kpi-card kpi-loop-card" data-kpi="aov" title="Click to switch KPI">
                <div class="rpt-kpi-top">
                    <div class="rpt-kpi-label" data-kpi-label>Average Order Value</div>
                    <div class="rpt-kpi-icon rpt-kpi-icon--blue"><i class="fas fa-receipt" data-kpi-icon></i></div>
                </div>
                <div class="rpt-kpi-value" data-kpi-value>RM <?= number_format($avgOrderValue ?? ($totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0), 2) ?></div>
                <div class="rpt-kpi-foot">
                    <span class="rev-growth-badge up" data-kpi-badge>
                        <i class="fas fa-arrow-up"></i><span data-kpi-improvement>0.0%</span>
                    </span>
                    <span class="rpt-kpi-sub rpt-kpi-sub--neutral" data-kpi-sub>vs last week</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="rpt-kpi-card kpi-loop-card" data-kpi="customer" title="Click to switch KPI">
                <div class="rpt-kpi-top">
                    <div class="rpt-kpi-label" data-kpi-label>Unique Customer</div>
                    <div class="rpt-kpi-icon rpt-kpi-icon--green"><i class="fas fa-users" data-kpi-icon></i></div>
                </div>
                <div class="rpt-kpi-value" data-kpi-value><?= number_format($uniqueCustomers) ?></div>
                <div class="rpt-kpi-foot">
                    <span class="rev-growth-badge <?= ($repeatCustomerGrowthPct ?? 0) >= 0 ? 'up' : 'down' ?>" data-kpi-badge>
                        <i class="fas fa-arrow-<?= ($repeatCustomerGrowthPct ?? 0) >= 0 ? 'up' : 'down' ?>"></i><span data-kpi-improvement><?= number_format(abs($repeatCustomerGrowthPct ?? 0), 1) ?>%</span>
                    </span>
                    <span class="rpt-kpi-sub rpt-kpi-sub--neutral" data-kpi-sub>vs last week</span>
                </div>
            </div>
        </div>

    </div>

    <!-- ══════════════════════════════════════
         ROW 2 — Revenue Chart + Calendar
    ══════════════════════════════════════ -->
    <div class="row g-2 mb-3">

        <!-- Revenue Chart -->
        <div class="col-lg-8">
            <div class="card rpt-card h-100">
                <!-- Controls header -->
                <div class="rpt-card-header rpt-main-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-chart-line" style="color:var(--gold);"></i>
                        <span class="rpt-title">Revenue Breakdown</span>
                    </div>
                    <div class="rpt-control-wrap d-flex align-items-center gap-2 flex-wrap">
                        <select id="serviceType" class="report-select">
                            <option value="all">Combined Revenue</option>
                            <option value="split">Storage + Delivery</option>
                            <option value="storage">Storage Only</option>
                            <option value="delivery">Delivery Only</option>
                        </select>
                        <div class="chart-toggle" id="chartToggle">
                            <button type="button" class="ct-btn active" data-val="line">Graph</button>
                            <button type="button" class="ct-btn" data-val="bar">Bar</button>
                        </div>
                        <div class="period-toggle" id="periodToggle">
                            <button type="button" class="pt-btn" data-val="day">Day</button>
                            <button type="button" class="pt-btn" data-val="week">Week</button>
                            <button type="button" class="pt-btn active" data-val="month">Month</button>
                            <button type="button" class="pt-btn" data-val="range">Range</button>
                            <button type="button" class="pt-btn" data-val="year">Year</button>
                        </div>
                        <input type="date" id="dayFocusDate" class="rpt-date-pick"
                               value="<?= esc($reportAnchorDate ?? date('Y-m-d')) ?>" style="display:none;"
                               title="Select a specific date">
                        <div class="rpt-range-wrap" id="rangePickerWrap" style="display:none;">
                            <input type="date" id="rangeStartDate" class="rpt-date-pick"
                                   value="<?= esc(date('Y-m-01', strtotime($reportAnchorDate ?? date('Y-m-d')))) ?>"
                                   title="Range start date">
                            <input type="date" id="rangeEndDate" class="rpt-date-pick"
                                   value="<?= esc($reportAnchorDate ?? date('Y-m-d')) ?>"
                                   title="Range end date">
                        </div>
                        <select id="monthPicker" class="report-select" title="Select month" style="display:none;">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= esc($m) ?>" <?= (int)$m === (int)date('n', strtotime($reportAnchorDate ?? date('Y-m-d'))) ? 'selected' : '' ?>>
                                    <?= esc(date('M', mktime(0, 0, 0, $m, 1, 2000))) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                        <select id="weekPicker" class="report-select" title="Select week" style="display:none;"></select>
                        <select id="yearPicker" class="report-select" title="Select year">
                            <?php foreach (($reportYears ?? [date('Y')]) as $yr): ?>
                                <option value="<?= esc($yr) ?>" <?= (int)$yr === (int)($defaultReportYear ?? date('Y')) ? 'selected' : '' ?>>
                                    <?= esc($yr) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Chart canvas -->
                <div class="card-body p-2 rpt-revenue-canvas-wrap">
                    <div id="revenueLoading" style="display:none; position:absolute; inset:0; background:rgba(255,255,255,0.88); z-index:10; align-items:center; justify-content:center; border-radius:0 0 var(--radius-card) var(--radius-card);">
                        <div class="text-center">
                            <div class="spinner-border" style="color:var(--gold); width:1.6rem; height:1.6rem;" role="status"></div>
                            <div style="font-size:.75rem; color:#888; margin-top:6px;">Loading…</div>
                        </div>
                    </div>
                    <div id="revenueEmpty" class="chart-empty">
                        <i class="fas fa-chart-line"></i>
                        <p class="fw-semibold">No revenue data found</p>
                        <p style="font-size:.78rem;">Try changing the service type or timeframe.</p>
                    </div>
                    <canvas id="revenueChart"></canvas>
                </div>

                <!-- Bar detail panel (shown on bar click only) -->
                <div id="revBarDetail" class="rev-bar-detail mx-2 mb-2">
                    <div class="rbd-icon"><i class="fas fa-calendar-day"></i></div>
                    <div class="rbd-info">
                        <div class="rbd-date" id="rbdDate">—</div>
                        <div class="rbd-revenue" id="rbdRevenue">—</div>
                        <div class="rbd-meta" id="rbdMeta">Select a bar to view details.</div>
                        <div class="rbd-split-row">
                            <table class="rbd-table" aria-label="Storage and Delivery details">
                                <thead>
                                    <tr>
                                        <th>Storage</th>
                                        <th>Delivery</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td id="rbdStorage">RM 0.00</td>
                                        <td id="rbdDelivery">RM 0.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <button class="rbd-close" id="rbdClose" title="Dismiss">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div id="chartExportPanel" class="chart-export-panel mb-2">
                    <form id="inlineExportForm" action="<?= base_url('report/export') ?>" method="GET" target="_blank">
                        <input type="hidden" name="start_date" id="inlineExportStart" value="<?= date('Y-m-01') ?>">
                        <input type="hidden" name="end_date" id="inlineExportEnd" value="<?= date('Y-m-d') ?>">
                        <div class="chart-export-grid">
                            <button type="submit" name="format" value="pdf" class="btn btn-export-pdf btn-export-pdf-inline">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div><!-- /col-lg-8 -->

        <!-- Calendar / Date Navigator -->
        <div class="col-lg-4">
            <div class="card rpt-card h-100">
                <div class="rpt-card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-calendar-alt" style="color:var(--gold);"></i>
                        <span class="rpt-title">Date Navigator</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="sf-label" id="calSelectedLabel">Click a day</span>
                        <button class="cal-clear-btn" id="calClearRange" title="Clear selected date or range" style="display:none;">
                            Clear
                        </button>
                    </div>
                </div>
                <div class="cal-card-body">
                    <div class="cal-nav">
                        <button class="cal-nav-btn" id="calPrev" title="Previous month">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span class="cal-nav-title" id="calMonthTitle">—</span>
                        <button class="cal-nav-btn" id="calNext" title="Next month">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <div class="cal-grid" id="calGrid"></div>
                    <div class="cal-metric-box">
                        <div class="cmb-icon"><i class="fas fa-chart-bar"></i></div>
                        <div class="min-w-0" style="flex:1;">
                            <div class="cmb-value" id="calDayRevenue">RM —</div>
                            <div class="cmb-label" id="calDayLabel">Select a day to view revenue</div>
                        </div>
                        <span class="cmb-badge" id="calDayBadge" style="display:none;"></span>
                    </div>
                </div>
            </div>
        </div><!-- /col-lg-4 -->

    </div><!-- /row 2 -->

    <!-- ══════════════════════════════════════
         ROW 3 — Stats Strip
    ══════════════════════════════════════ -->
    <div class="row g-2 mb-3">
        <div class="col-12">
            <div class="card rpt-card">
                <div class="rpt-stats-strip">
                    <div class="rpt-stat-item">
                        <div class="sf-label">Highest Period</div>
                        <div class="sf-val" id="statHigh">—</div>
                    </div>
                    <div class="rpt-stat-divider"></div>
                    <div class="rpt-stat-item">
                        <div class="sf-label">Lowest Period</div>
                        <div class="sf-val" id="statLow">—</div>
                    </div>
                    <div class="rpt-stat-divider"></div>
                    <div class="rpt-stat-item">
                        <div class="sf-label">Average</div>
                        <div class="sf-val" id="statAvg">—</div>
                    </div>
                    <div class="rpt-stat-divider"></div>
                    <div class="rpt-stat-item">
                        <div class="sf-label">Periods</div>
                        <div class="sf-val" id="statCount">—</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════
         ROW 4 — Peak Times + Donut + Top Customers
    ══════════════════════════════════════ -->
    <div class="row g-2">

        <!-- Peak Booking Times -->
        <div class="col-lg-4 col-md-6">
            <div class="card rpt-card h-100">
                <div class="rpt-card-header d-flex justify-content-between align-items-center flex-wrap gap-1">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-clock" style="color:var(--gold);"></i>
                        <span class="rpt-title">Peak Booking Times</span>
                    </div>
                    <div class="d-flex align-items-center gap-1 flex-wrap">
                        <select id="peakService" class="report-select rpt-sm-select">
                            <option value="all">All</option>
                            <option value="storage">Storage</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-2" style="position:relative; height:200px;">
                    <div id="peakLoading" style="display:none; position:absolute; inset:0; background:rgba(255,255,255,0.85); z-index:10; align-items:center; justify-content:center;">
                        <div class="spinner-border" style="color:var(--gold); width:1.5rem; height:1.5rem;" role="status"></div>
                    </div>
                    <div id="peakEmpty" class="chart-empty">
                        <i class="fas fa-clock"></i>
                        <p class="fw-semibold" style="margin:0 0 2px;">No booking data yet</p>
                        <p style="font-size:.75rem; margin:0;">Try a different filter.</p>
                    </div>
                    <canvas id="peakTimesChart"></canvas>
                </div>
                <div class="rpt-footer text-center">
                    <span class="sf-label"><i class="fas fa-info-circle me-1" style="color:var(--brown);"></i>Orders by hour of day (24 h)</span>
                </div>
            </div>
        </div>

        <!-- Revenue by Category Donut -->
        <div class="col-lg-4 col-md-6">
            <div class="card rpt-card h-100">
                <div class="rpt-card-header d-flex align-items-center justify-content-start gap-2">
                    <i class="fas fa-chart-pie" style="color:var(--gold);"></i>
                    <span class="rpt-title">Revenue by Category</span>
                </div>
                <div class="card-body p-2 d-flex flex-column align-items-center justify-content-center" style="min-height:200px;">
                    <canvas id="categoryDonutChart" style="max-width:190px; max-height:190px;"></canvas>
                </div>
                <div class="rpt-footer d-flex justify-content-start text-start gap-4">
                    <div>
                        <div class="sf-label">Storage</div>
                        <div class="sf-val">RM <?= number_format($storageRevenue, 2) ?></div>
                    </div>
                    <div>
                        <div class="sf-label">Delivery</div>
                        <div class="sf-val">RM <?= number_format($deliveryRevenue, 2) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Customers -->
        <div class="col-lg-4">
            <div class="card rpt-card h-100">
                <div class="rpt-card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-crown" style="color:var(--gold);"></i>
                        <span class="rpt-title">Top Customers</span>
                    </div>
                    <span class="sf-label">By Total Spend</span>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($topCustomers)): ?>
                        <?php foreach ($topCustomers as $i => $cust): ?>
                        <div class="rpt-cust-row <?= $i < count($topCustomers) - 1 ? 'rpt-cust-border' : '' ?>">
                            <span class="rpt-rank-badge <?= $i === 0 ? 'rpt-rank-1' : ($i === 1 ? 'rpt-rank-2' : ($i === 2 ? 'rpt-rank-3' : 'rpt-rank-n')) ?>">
                                <?= $i + 1 ?>
                            </span>
                            <div class="rpt-cust-info">
                                <div class="rpt-cust-name"><?= esc($cust['first_name'] ?: 'Unknown') ?></div>
                                <div class="rpt-cust-email"><?= esc($cust['email'] ?: '—') ?></div>
                            </div>
                            <div class="rpt-cust-right">
                                <div class="rpt-cust-amount">RM <?= number_format((float)$cust['total_revenue'], 2) ?></div>
                                <div class="rpt-cust-orders"><?= (int)$cust['order_count'] ?> order<?= (int)$cust['order_count'] !== 1 ? 's' : '' ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4" style="font-size:.82rem; color:#9CA3AF;">No customer data available.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div><!-- /row 4 -->

</div><!-- /rpt-page -->

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ── Revenue chart state ──
    let revenueChart;
    let currentTimeframe = 'month';
    let currentService   = 'all';
    let currentChartMode = 'line';
    let selectedBarIdx   = -1;
    let chartGesture = { startX: null, startY: null, moved: false };
    let latestRevenuePayload = {
        labels: [],
        values: [],
        rawKeys: [],
        periodDetails: {},
        serviceSeries: { storage: [], delivery: [], other: [] }
    };
    const GOLD  = '#F2BE00';
    const BROWN = '#1A1A1A';
    const SERVICE_COLORS = {
        storage:  '#F2BE00',
        delivery: '#1A1A1A',
        other:    '#9CA3AF'
    };
    const revenueFetchCache = new Map();
    let revenueFetchAbort = null;
    let revenueRequestSeq = 0;
    const REVENUE_DEBUG = false;

    function revenueDebugLog(step, payload = null) {
        if (!REVENUE_DEBUG) return;
        if (payload === null) {
            console.log(`[RevenueDebug] ${step}`);
            return;
        }
        console.log(`[RevenueDebug] ${step}`, payload);
    }

    function fmtHour(h) {
        if (h === 0)  return '12 AM';
        if (h === 12) return '12 PM';
        return h < 12 ? h + ' AM' : (h - 12) + ' PM';
    }

    function fmtRM(val) {
        return 'RM ' + Number(val).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function safeNum(val) {
        const n = Number(val);
        return Number.isFinite(n) ? n : 0;
    }

    function nonNegativeNum(val) {
        return Math.max(0, safeNum(val));
    }

    function rgba(hex, alpha) {
        const n = hex.replace('#', '');
        const r = parseInt(n.substring(0, 2), 16);
        const g = parseInt(n.substring(2, 4), 16);
        const b = parseInt(n.substring(4, 6), 16);
        return `rgba(${r},${g},${b},${alpha})`;
    }

    function updateStats(values, labels) {
        if (!values || values.length === 0) {
            ['statHigh','statLow','statAvg','statCount'].forEach(id => document.getElementById(id).textContent = '—');
            return;
        }
        const max    = Math.max(...values);
        const min    = Math.min(...values);
        const avg    = values.reduce((a, b) => a + b, 0) / values.length;
        const maxIdx = values.indexOf(max);
        const minIdx = values.indexOf(min);
        document.getElementById('statHigh').textContent  = fmtRM(max) + (labels[maxIdx] ? ' (' + labels[maxIdx] + ')' : '');
        document.getElementById('statLow').textContent   = fmtRM(min) + (labels[minIdx] ? ' (' + labels[minIdx] + ')' : '');
        document.getElementById('statAvg').textContent   = fmtRM(avg);
        document.getElementById('statCount').textContent = values.length;
    }

    function showRevenueEmpty(show, message = null) {
        document.getElementById('revenueEmpty').style.display = show ? 'flex' : 'none';
        document.getElementById('revenueChart').style.display = show ? 'none' : 'block';
        if (message) {
            const msgNode = document.querySelector('#revenueEmpty p:last-child');
            if (msgNode) msgNode.textContent = message;
        }
    }

    function clearChartTooltip() {
        if (!revenueChart || !revenueChart.tooltip) return;
        try {
            if (typeof revenueChart.tooltip.setActiveElements === 'function') {
                revenueChart.tooltip.setActiveElements([], { x: 0, y: 0 });
            } else if (Array.isArray(revenueChart.tooltip._active)) {
                // Fallback for older Chart.js tooltip internals.
                revenueChart.tooltip._active = [];
            }
            revenueChart.update('none');
        } catch (e) {
            // Never let tooltip cleanup break chart filter loading flow.
        }
    }

    function hideBarDetail() {
        const panel = document.getElementById('revBarDetail');
        panel.style.display = 'none';
        panel.style.position = '';
        panel.style.left = '';
        panel.style.top = '';
        panel.style.zIndex = '';
        document.getElementById('chartExportPanel').style.display = 'block';
        selectedBarIdx = -1;
        document.getElementById('rbdStorage').textContent = `${fmtRM(0)} • 0 orders`;
        document.getElementById('rbdDelivery').textContent = `${fmtRM(0)} • 0 orders`;
        clearChartTooltip();
        if (latestRevenuePayload.values?.length) {
            updateStats(latestRevenuePayload.values, latestRevenuePayload.labels);
        }
    }

    function showBarDetail(label, revenue, details = null, index = null, selectedSeries = 'total') {
        const safeLabel = (typeof label === 'string' && label.trim() !== '')
            ? label
            : (details?.period_key || (index !== null && index >= 0 ? `Period ${index + 1}` : 'Selected Period'));
        const safeRevenue = safeNum(revenue);
        let metaText = 'Total Orders: 0';
        let storageRevenue = 0;
        let deliveryRevenue = 0;
        let storageOrders = 0;
        let deliveryOrders = 0;
        let selectedOrders = 0;
        const hasOrders = details && Number(details.total_orders || 0) > 0;
        if (hasOrders) {
            storageRevenue  = safeNum(details.storage_revenue || 0);
            deliveryRevenue = safeNum(details.delivery_revenue || 0);
            storageOrders   = Number(details.storage_orders || 0);
            deliveryOrders  = Number(details.delivery_orders || 0);
            if (selectedSeries === 'storage') {
                selectedOrders = storageOrders;
            } else if (selectedSeries === 'delivery') {
                selectedOrders = deliveryOrders;
            } else {
                selectedOrders = Number(details.total_orders || 0);
            }
            metaText = `Total Orders: ${selectedOrders}`;
        } else {
            metaText = 'No revenue records for this selected period.';
        }
        const labelSuffix = selectedSeries === 'storage'
            ? ' • Storage'
            : (selectedSeries === 'delivery' ? ' • Delivery' : '');
        document.getElementById('rbdDate').textContent     = safeLabel + labelSuffix;
        document.getElementById('rbdRevenue').textContent  = fmtRM(safeRevenue);
        document.getElementById('rbdMeta').textContent     = metaText;
        const splitRow = document.querySelector('#revBarDetail .rbd-split-row');
        if (splitRow) splitRow.style.display = 'flex';
        document.getElementById('rbdStorage').textContent  = `${fmtRM(storageRevenue)} • ${storageOrders} order${storageOrders === 1 ? '' : 's'}`;
        document.getElementById('rbdDelivery').textContent = `${fmtRM(deliveryRevenue)} • ${deliveryOrders} order${deliveryOrders === 1 ? '' : 's'}`;
        document.getElementById('chartExportPanel').style.display = 'block';
        const panel = document.getElementById('revBarDetail');
        panel.style.display = 'flex';
        panel.style.position = '';
        panel.style.left = '';
        panel.style.top = '';
        panel.style.zIndex = '';
        if (index !== null && index >= 0) {
            document.getElementById('statHigh').textContent  = fmtRM(safeRevenue) + ' (' + label + ')';
            document.getElementById('statLow').textContent   = fmtRM(safeRevenue) + ' (' + label + ')';
            document.getElementById('statAvg').textContent   = fmtRM(safeRevenue);
            document.getElementById('statCount').textContent = 1;
        }
    }

    function applyBarHighlight(values, idx) {
        if (!revenueChart) return;
        revenueChart.data.datasets.forEach((ds) => {
            const baseColor = ds.__baseColor || GOLD;
            if (revenueChart.config.type === 'bar') {
                const defaultBg = rgba(baseColor, 0.82);
                const selectedBg = '#111111';
                const selectedBorder = '#000000';
                ds.backgroundColor = values.map((_, i) =>
                    idx === -1 ? defaultBg : (i === idx ? selectedBg : defaultBg)
                );
                ds.borderColor = values.map((_, i) =>
                    idx === -1 ? rgba(baseColor, 0.95) : (i === idx ? selectedBorder : rgba(baseColor, 0.95))
                );
                ds.borderWidth  = values.map((_, i) => (idx !== -1 && i === idx ? 4 : 1));
                ds.borderRadius = values.map((_, i) => (idx !== -1 && i === idx ? 12 : 8));
            } else {
                ds.pointRadius      = values.map((_, i) => idx !== -1 && i === idx ? 8 : 3.5);
                ds.pointHoverRadius = values.map((_, i) => idx !== -1 && i === idx ? 10 : 6);
                ds.pointBorderWidth = values.map((_, i) => idx !== -1 && i === idx ? 3 : 1.4);
                ds.pointBackgroundColor = ds.__pointColor || baseColor;
                ds.pointBorderColor     = ds.__pointBorderColor || '#ffffff';
            }
        });
        revenueChart.update();
    }

    function toValidIndex(val) {
        const n = Number(val);
        return Number.isInteger(n) && n >= 0 ? n : -1;
    }

    function pickIndexFromElement(el) {
        if (!el) return -1;
        return toValidIndex(
            (typeof el.index !== 'undefined') ? el.index
            : ((typeof el._index !== 'undefined') ? el._index : -1)
        );
    }

    function pickDatasetIndexFromElement(el) {
        if (!el) return -1;
        return toValidIndex(
            (typeof el.datasetIndex !== 'undefined') ? el.datasetIndex
            : ((typeof el._datasetIndex !== 'undefined') ? el._datasetIndex : -1)
        );
    }

    function pickIndexFromTooltipItem(item) {
        if (!item) return -1;
        return toValidIndex(
            (typeof item.dataIndex !== 'undefined') ? item.dataIndex
            : ((typeof item.index !== 'undefined') ? item.index : -1)
        );
    }

    function getChartSelection(event) {
        if (!revenueChart) return { index: -1, datasetIndex: -1 };
        if (revenueChart.config.type === 'bar') {
            const bars = revenueChart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);
            return bars && bars.length
                ? { index: pickIndexFromElement(bars[0]), datasetIndex: pickDatasetIndexFromElement(bars[0]) }
                : { index: -1, datasetIndex: -1 };
        }
        const nearest = revenueChart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);
        if (nearest && nearest.length) {
            const element = nearest[0].element;
            const ex = typeof element?.x === 'number' ? element.x : null;
            const ey = typeof element?.y === 'number' ? element.y : null;
            const px = event?.x;
            const py = event?.y;
            if (ex !== null && ey !== null && typeof px === 'number' && typeof py === 'number') {
                const dist = Math.hypot(px - ex, py - ey);
                if (dist <= 18) {
                    return { index: pickIndexFromElement(nearest[0]), datasetIndex: pickDatasetIndexFromElement(nearest[0]) };
                }
                return { index: -1, datasetIndex: -1 };
            }
            return { index: pickIndexFromElement(nearest[0]), datasetIndex: pickDatasetIndexFromElement(nearest[0]) };
        }
        return { index: -1, datasetIndex: -1 };
    }

    function markGestureStart(evt) { chartGesture = { startX: evt.offsetX, startY: evt.offsetY, moved: false }; }
    function markGestureMove(evt) {
        if (chartGesture.startX === null || chartGesture.startY === null) return;
        if (Math.hypot(evt.offsetX - chartGesture.startX, evt.offsetY - chartGesture.startY) > 10) chartGesture.moved = true;
    }
    function clearGesture() { chartGesture = { startX: null, startY: null, moved: false }; }

    function buildRevenueDatasets(data, isBar) {
        const safeTotals = (data.values || []).map(nonNegativeNum);
        if (currentService === 'split') {
            const defs = [
                { key: 'storage',  label: 'Storage' },
                { key: 'delivery', label: 'Delivery' }
            ];
            return defs.map(def => {
                const color = SERVICE_COLORS[def.key];
                const safeSeries = (data.service_series?.[def.key] || []).map(nonNegativeNum);
                return {
                    label: def.label,
                    data: safeSeries,
                    borderColor: color,
                    borderWidth: 3,
                    backgroundColor: isBar ? safeTotals.map(() => rgba(color, 0.82)) : rgba(color, 0.16),
                    hoverBackgroundColor: isBar ? safeTotals.map(() => rgba(color, 0.95)) : rgba(color, 0.16),
                    fill: !isBar,
                    tension: isBar ? 0 : 0.25,
                    cubicInterpolationMode: 'monotone',
                    pointStyle: 'circle',
                    pointBackgroundColor: '#111111',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: isBar ? 0 : 4.5,
                    pointHoverRadius: isBar ? 0 : 7,
                    borderRadius: isBar ? 8 : 0,
                    borderSkipped: false,
                    __baseColor: color,
                    __pointColor: '#111111',
                    __pointBorderColor: '#111111'
                };
            });
        }

        const color = SERVICE_COLORS[currentService] || GOLD;
        const gradientCtx = document.getElementById('revenueChart').getContext('2d');
        const gradient = gradientCtx.createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, rgba(color, 0.4));
        gradient.addColorStop(1, rgba(color, 0.02));

        return [{
            label: 'Revenue (RM)',
            data: safeTotals,
            borderColor: isBar ? safeTotals.map(() => rgba(color, 0.95)) : color,
            borderWidth: isBar ? 1 : 3,
            backgroundColor: isBar ? safeTotals.map(() => rgba(color, 0.82)) : gradient,
            hoverBackgroundColor: isBar ? safeTotals.map(() => rgba(color, 0.95)) : gradient,
            fill: !isBar,
            tension: isBar ? 0 : 0.25,
            cubicInterpolationMode: 'monotone',
            pointStyle: 'circle',
            pointBackgroundColor: '#111111',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2.2,
            pointRadius: isBar ? 0 : 5,
            pointHoverRadius: isBar ? 0 : 7.5,
            borderRadius: isBar ? 8 : 0,
            borderSkipped: false,
            __baseColor: color,
            __pointColor: '#111111',
            __pointBorderColor: '#111111'
        }];
    }

    function renderRevenueChart(data) {
        const isBar = currentChartMode === 'bar';
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const safeValues = (data.values || []).map(nonNegativeNum);
        const maxVal = Math.max(0, ...safeValues);
        const yMaxFallback = maxVal === 0 ? 1 : Math.ceil(maxVal * 1.1);
        if (revenueChart) revenueChart.destroy();

        revenueChart = new Chart(ctx, {
            type: isBar ? 'bar' : 'line',
            data: {
                labels: data.labels,
                datasets: buildRevenueDatasets(data, isBar)
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                animation: { duration: 650, easing: 'easeInOutQuart' },
                onClick: function(event) {
                    if (revenueChart && revenueChart.config.type !== 'bar') {
                        return;
                    }
                    if (chartGesture.moved) { clearGesture(); return; }
                    const selection = getChartSelection(event);
                    const idx = toValidIndex(selection.index);
                    if (idx < 0) {
                        hideBarDetail();
                        applyBarHighlight(data.values, -1);
                        clearGesture();
                        return;
                    }
                    selectedBarIdx = idx;
                    applyBarHighlight(data.values, idx);
                    const rawKey = data.raw_keys?.[idx] || null;
                    const detail = rawKey ? (data.period_details?.[rawKey] || null) : null;
                    let selectedSeries = 'total';
                    let revenueForDetail = detail ? safeNum(detail.total_revenue) : safeNum(data.values[idx]);
                    if (currentService === 'split') {
                        const dsIdx = toValidIndex(selection.datasetIndex);
                        if (dsIdx === 0) {
                            selectedSeries = 'storage';
                            revenueForDetail = detail ? safeNum(detail.storage_revenue) : safeNum(data.service_series?.storage?.[idx] ?? 0);
                        } else if (dsIdx === 1) {
                            selectedSeries = 'delivery';
                            revenueForDetail = detail ? safeNum(detail.delivery_revenue) : safeNum(data.service_series?.delivery?.[idx] ?? 0);
                        }
                    }
                    const detailLabel = (data.labels && data.labels[idx]) || (data.raw_keys && data.raw_keys[idx]) || `Period ${idx + 1}`;
                    showBarDetail(detailLabel, revenueForDetail, detail, idx, selectedSeries);
                    clearGesture();
                },
                plugins: {
                    legend: {
                        display: false,
                        labels: {
                            generateLabels: () => [],
                            filter: () => false,
                            boxWidth: 0,
                            boxHeight: 0,
                            padding: 0
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(242,190,0,0.96)',
                        padding: 14,
                        cornerRadius: 10,
                        displayColors: false,
                        titleColor: '#2f2a12',
                        bodyColor: '#2f2a12',
                        borderColor: 'rgba(91,83,44,0.35)',
                        borderWidth: 1,
                        titleFont: { size: 16, weight: '700' },
                        bodyFont: { size: 15, weight: '600' },
                        xAlign: 'center',
                        yAlign: 'bottom',
                        callbacks: {
                            title: items => {
                                const idx = pickIndexFromTooltipItem(items?.[0]);
                                if (idx < 0) return 'Selected Period';
                                return (data.labels?.[idx] || data.raw_keys?.[idx] || `Period ${idx + 1}`);
                            },
                            label: ctx => `Total Revenue: ${fmtRM(ctx.parsed.y)}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        suggestedMin: 0,
                        max: yMaxFallback,
                        grid:   { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                        border: { display: false },
                        ticks:  { callback: val => 'RM ' + val.toLocaleString('en-MY'), color: '#999', font: { size: 11 } }
                    },
                    x: {
                        grid:   { display: false },
                        border: { display: false },
                        ticks:  { color: '#999', font: { size: 11 }, maxRotation: 0 }
                    },
                    // Backward-compat for Chart.js v2-style config (prevents negative default range).
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            min: 0,
                            suggestedMin: 0,
                            max: yMaxFallback,
                            callback: val => 'RM ' + Number(val).toLocaleString('en-MY')
                        },
                        gridLines: { color: 'rgba(0,0,0,0.05)', drawBorder: false }
                    }],
                    xAxes: [{
                        gridLines: { display: false, drawBorder: false },
                        ticks: { maxRotation: 0 }
                    }]
                }
            }
        });
    }

    function loadRevenueData(serviceType = 'all', timeframe = 'year', selectedDate = null, selectedYear = null) {
        const requestSeq = ++revenueRequestSeq;
        const loadingEl = document.getElementById('revenueLoading');
        revenueDebugLog('load:start', { requestSeq, serviceType, timeframe, selectedDate, selectedYear });
        loadingEl.style.display = 'flex';
        showRevenueEmpty(false);
        hideBarDetail();
        const finishLoading = () => {
            if (requestSeq === revenueRequestSeq) {
                loadingEl.style.display = 'none';
                revenueDebugLog('load:finish', { requestSeq, currentSeq: revenueRequestSeq });
            } else {
                revenueDebugLog('load:finish-skipped-seq-mismatch', { requestSeq, currentSeq: revenueRequestSeq });
            }
        };

        let url = `<?= base_url('admin/getRevenueData'); ?>?service=${serviceType}&timeframe=${timeframe}`;
        if (selectedDate) url += `&selected_date=${encodeURIComponent(selectedDate)}`;
        if (selectedYear) url += `&selected_year=${encodeURIComponent(selectedYear)}`;
        if (timeframe === 'range') {
            const rangeStart = document.getElementById('rangeStartDate').value;
            const rangeEnd = document.getElementById('rangeEndDate').value;
            if (rangeStart) url += `&range_start=${encodeURIComponent(rangeStart)}`;
            if (rangeEnd) url += `&range_end=${encodeURIComponent(rangeEnd)}`;
        }
        syncExportByCurrentFilter(timeframe, selectedDate, selectedYear);
        const cacheKey = url;
        if (revenueFetchCache.has(cacheKey)) {
            revenueDebugLog('cache:hit', { requestSeq, cacheKey });
            const data = revenueFetchCache.get(cacheKey);
            if (requestSeq !== revenueRequestSeq) { finishLoading(); return; }
            finishLoading();
            latestRevenuePayload = {
                labels: data.labels || [],
                values: data.values || [],
                rawKeys: data.raw_keys || [],
                periodDetails: data.period_details || {},
                serviceSeries: data.service_series || { storage: [], delivery: [], other: [] }
            };
            if (!data.values || data.values.length === 0) {
                if (revenueChart) { revenueChart.destroy(); revenueChart = null; }
                showRevenueEmpty(true);
                updateStats([], []);
                return;
            }
            showRevenueEmpty(false);
            renderRevenueChart(data);
            updateStats(data.values, data.labels);
            return;
        }
        revenueDebugLog('cache:miss', { requestSeq, cacheKey });
        if (revenueFetchAbort) revenueFetchAbort.abort();
        revenueFetchAbort = new AbortController();
        const requestController = revenueFetchAbort;
        const requestTimeout = setTimeout(() => {
            if (requestController === revenueFetchAbort) requestController.abort();
        }, 45000);

        fetch(url, { signal: requestController.signal })
            .then(r => {
                revenueDebugLog('fetch:response', {
                    requestSeq,
                    ok: r.ok,
                    status: r.status,
                    redirected: r.redirected,
                    contentType: r.headers.get('content-type')
                });
                if (!r.ok) throw new Error(`HTTP ${r.status}`);
                return r.json();
            })
            .then(data => {
                revenueDebugLog('fetch:json-ok', {
                    requestSeq,
                    points: Array.isArray(data?.values) ? data.values.length : 'n/a'
                });
                if (requestSeq !== revenueRequestSeq) return;
                revenueFetchCache.set(cacheKey, data);
                latestRevenuePayload = {
                    labels: data.labels || [],
                    values: data.values || [],
                    rawKeys: data.raw_keys || [],
                    periodDetails: data.period_details || {},
                    serviceSeries: data.service_series || { storage: [], delivery: [], other: [] }
                };

                if (!data.values || data.values.length === 0) {
                    if (revenueChart) { revenueChart.destroy(); revenueChart = null; }
                    showRevenueEmpty(true);
                    updateStats([], []);
                    return;
                }

                showRevenueEmpty(false);
                renderRevenueChart(data);
                updateStats(data.values, data.labels);
                updateNavigatorByCurrentFilter(data, timeframe, selectedDate, selectedYear);

                // Update calendar metric box when in day mode
                if (currentTimeframe === 'day' && typeof window.updateCalendarMetric === 'function') {
                    const dayTotal  = (data.values || []).reduce((a, b) => a + b, 0);
                    const dayOrders = Object.values(data.period_details || {})
                        .reduce((sum, pd) => sum + (pd.total_orders || 0), 0);
                    window.updateCalendarMetric(dayTotal, selectedDate, dayOrders);
                }
            })
            .catch((err) => {
                revenueDebugLog('fetch:error', {
                    requestSeq,
                    name: err?.name,
                    message: err?.message,
                    stack: err?.stack
                });
                if (requestSeq !== revenueRequestSeq) { finishLoading(); return; }
                if (err && err.name === 'AbortError') return;
                const hasLastData = Array.isArray(latestRevenuePayload.values) && latestRevenuePayload.values.length > 0;
                if (hasLastData) {
                    showRevenueEmpty(false);
                    renderRevenueChart({
                        labels: latestRevenuePayload.labels || [],
                        values: latestRevenuePayload.values || [],
                        raw_keys: latestRevenuePayload.rawKeys || [],
                        period_details: latestRevenuePayload.periodDetails || {},
                        service_series: latestRevenuePayload.serviceSeries || { storage: [], delivery: [], other: [] }
                    });
                    updateStats(latestRevenuePayload.values, latestRevenuePayload.labels);
                    return;
                }
                showRevenueEmpty(true, 'Unable to load chart right now. Please try again.');
                updateStats([], []);
                updateNavigatorByCurrentFilter(null, timeframe, selectedDate, selectedYear);
            })
            .finally(() => {
                clearTimeout(requestTimeout);
                if (requestController === revenueFetchAbort) revenueFetchAbort = null;
                finishLoading();
                revenueDebugLog('fetch:finally', { requestSeq, currentSeq: revenueRequestSeq });
            });
    }

    function coerceDateToYear(dateStr, targetYear) {
        if (!dateStr || !/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) return `${targetYear}-01-01`;
        const parts = dateStr.split('-');
        const month = Number(parts[1]);
        const day = Number(parts[2]);
        const maxDay = new Date(Number(targetYear), month, 0).getDate();
        const safeDay = Math.min(day, maxDay);
        return `${targetYear}-${String(month).padStart(2, '0')}-${String(safeDay).padStart(2, '0')}`;
    }

    function toYmdDate(d) {
        return d.getFullYear()
            + '-' + String(d.getMonth() + 1).padStart(2, '0')
            + '-' + String(d.getDate()).padStart(2, '0');
    }

    function getWeeksInYear(year) {
        const dec31 = new Date(year, 11, 31);
        const dayOfWeek = (dec31.getDay() + 6) % 7;
        const dayOfYear = Math.floor((dec31 - new Date(year, 0, 1)) / 86400000) + 1;
        return Math.floor((dayOfYear - dayOfWeek + 10) / 7);
    }

    function isoWeekToSunday(year, week) {
        const jan4 = new Date(year, 0, 4);
        const jan4Dow = (jan4.getDay() + 6) % 7;
        const mondayWeek1 = new Date(year, 0, 4 - jan4Dow);
        const mondayTarget = new Date(mondayWeek1);
        mondayTarget.setDate(mondayTarget.getDate() + (week - 1) * 7);
        const sundayTarget = new Date(mondayTarget);
        sundayTarget.setDate(mondayTarget.getDate() + 6);
        return toYmdDate(sundayTarget);
    }

    function getIsoWeekFromDate(dateStr) {
        const d = new Date(`${dateStr}T00:00:00`);
        if (Number.isNaN(d.getTime())) return 1;
        const tmp = new Date(d);
        const dayNum = (tmp.getDay() + 6) % 7;
        tmp.setDate(tmp.getDate() - dayNum + 3);
        const jan4 = new Date(tmp.getFullYear(), 0, 4);
        return 1 + Math.round((((tmp - jan4) / 86400000) - 3 + ((jan4.getDay() + 6) % 7)) / 7);
    }

    function rebuildWeekPicker(year, selectedWeek = null) {
        const weekPicker = document.getElementById('weekPicker');
        const totalWeeks = getWeeksInYear(Number(year));
        weekPicker.innerHTML = '';
        const safeWeek = Math.min(Math.max(Number(selectedWeek || 1), 1), totalWeeks);
        for (let w = 1; w <= totalWeeks; w++) {
            const opt = document.createElement('option');
            opt.value = String(w);
            opt.textContent = `Week ${w}`;
            if (w === safeWeek) opt.selected = true;
            weekPicker.appendChild(opt);
        }
    }

    function getSelectedDateForTimeframe(timeframe) {
        const year = Number(document.getElementById('yearPicker').value);
        if (timeframe === 'month') {
            const month = Number(document.getElementById('monthPicker').value || 1);
            return `${year}-${String(month).padStart(2, '0')}-01`;
        }
        if (timeframe === 'week') {
            const week = Number(document.getElementById('weekPicker').value || 1);
            return isoWeekToSunday(year, week);
        }
        return document.getElementById('dayFocusDate').value;
    }

    function updateTimeframeControls() {
        const tf = currentTimeframe;
        const focusPicker = document.getElementById('dayFocusDate');
        const yearPicker  = document.getElementById('yearPicker');
        const rangeWrap   = document.getElementById('rangePickerWrap');
        const monthPicker = document.getElementById('monthPicker');
        const weekPicker  = document.getElementById('weekPicker');
        focusPicker.style.display = tf === 'day' ? '' : 'none';
        rangeWrap.style.display   = tf === 'range' ? 'flex' : 'none';
        monthPicker.style.display = tf === 'month' ? '' : 'none';
        weekPicker.style.display  = tf === 'week' ? '' : 'none';
        yearPicker.style.display  = (tf === 'year' || tf === 'month' || tf === 'week') ? '' : 'none';
        if (tf === 'week') {
            const inferredWeek = getIsoWeekFromDate(focusPicker.value || toYmdDate(new Date()));
            rebuildWeekPicker(yearPicker.value, inferredWeek);
        }
    }

    function syncExportByCurrentFilter(timeframe, selectedDate, selectedYear) {
        const now = new Date();
        const pad = (n) => String(n).padStart(2, '0');
        const toYmd = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
        let start;
        let end;
        if (timeframe === 'day') {
            const base = selectedDate || document.getElementById('dayFocusDate').value || toYmd(now);
            start = base;
            end = base;
        } else if (timeframe === 'week') {
            const endDate = new Date(selectedDate || document.getElementById('dayFocusDate').value || toYmd(now));
            const startDate = new Date(endDate);
            startDate.setDate(endDate.getDate() - 6);
            start = toYmd(startDate);
            end = toYmd(endDate);
        } else if (timeframe === 'month') {
            const base = new Date(selectedDate || document.getElementById('dayFocusDate').value || toYmd(now));
            start = toYmd(new Date(base.getFullYear(), base.getMonth(), 1));
            end = toYmd(new Date(base.getFullYear(), base.getMonth() + 1, 0));
        } else if (timeframe === 'range') {
            start = document.getElementById('rangeStartDate').value || toYmd(now);
            end = document.getElementById('rangeEndDate').value || start;
        } else {
            const year = Number(selectedYear || document.getElementById('yearPicker').value || now.getFullYear());
            start = `${year}-01-01`;
            end = `${year}-12-31`;
        }
        ['exportStart', 'inlineExportStart'].forEach((id) => { const el = document.getElementById(id); if (el) el.value = start; });
        ['exportEnd', 'inlineExportEnd'].forEach((id) => { const el = document.getElementById(id); if (el) el.value = end; });
    }

    function updateNavigatorByCurrentFilter(data, timeframe, selectedDate, selectedYear) {
        const values = data?.values || [];
        const total = values.reduce((a, b) => a + Number(b || 0), 0);
        const orders = Object.values(data?.period_details || {}).reduce((sum, pd) => sum + Number(pd.total_orders || 0), 0);
        const labelEl = document.getElementById('calSelectedLabel');
        const year = selectedYear || document.getElementById('yearPicker').value;
        if (timeframe === 'day') {
            labelEl.textContent = selectedDate || document.getElementById('dayFocusDate').value || 'Day';
            if (typeof window.syncCalendarToDate === 'function') window.syncCalendarToDate(selectedDate || document.getElementById('dayFocusDate').value);
        } else if (timeframe === 'week') {
            const endDate = selectedDate || document.getElementById('dayFocusDate').value;
            labelEl.textContent = `Week ending ${endDate || '—'}`;
            if (typeof window.syncCalendarToDate === 'function') window.syncCalendarToDate(endDate);
        } else if (timeframe === 'month') {
            const monthDate = selectedDate || document.getElementById('dayFocusDate').value;
            labelEl.textContent = `Month view (${monthDate || '—'})`;
            if (typeof window.syncCalendarToDate === 'function') window.syncCalendarToDate(monthDate);
        } else if (timeframe === 'range') {
            const rs = document.getElementById('rangeStartDate').value;
            const re = document.getElementById('rangeEndDate').value;
            labelEl.textContent = `${rs || '—'} to ${re || '—'}`;
            if (typeof window.syncCalendarToDate === 'function') window.syncCalendarToDate(re || rs);
        } else {
            labelEl.textContent = `Year ${year}`;
            if (typeof window.syncCalendarToDate === 'function') window.syncCalendarToDate(`${year}-01-01`);
        }
        if (typeof window.updateCalendarMetric === 'function') {
            window.updateCalendarMetric(total, labelEl.textContent, orders);
        }
    }

    // Period pill toggle
    document.querySelectorAll('#periodToggle .pt-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('#periodToggle .pt-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentTimeframe = this.dataset.val;
            updateTimeframeControls();
            const selectedDate = getSelectedDateForTimeframe(currentTimeframe);
            if ((currentTimeframe === 'week' || currentTimeframe === 'month') && selectedDate) {
                document.getElementById('dayFocusDate').value = selectedDate;
            }
            loadRevenueData(currentService, currentTimeframe, selectedDate, document.getElementById('yearPicker').value);
        });
    });

    // Chart type toggle
    document.querySelectorAll('#chartToggle .ct-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('#chartToggle .ct-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentChartMode = this.dataset.val;
            loadRevenueData(
                currentService, currentTimeframe,
                getSelectedDateForTimeframe(currentTimeframe),
                document.getElementById('yearPicker').value
            );
        });
    });

    document.getElementById('serviceType').addEventListener('change', function() {
        currentService = this.value;
        loadRevenueData(
            currentService, currentTimeframe,
            getSelectedDateForTimeframe(currentTimeframe),
            document.getElementById('yearPicker').value
        );
    });

    document.getElementById('dayFocusDate').addEventListener('change', function() {
        if (currentTimeframe !== 'day') return;
        loadRevenueData(currentService, 'day', this.value, document.getElementById('yearPicker').value);
    });

    document.getElementById('yearPicker').addEventListener('change', function() {
        const focusPicker = document.getElementById('dayFocusDate');
        if (currentTimeframe === 'year') {
            loadRevenueData(currentService, 'year', focusPicker.value, this.value);
            return;
        }
        if (currentTimeframe === 'week' || currentTimeframe === 'month') {
            if (currentTimeframe === 'week') {
                rebuildWeekPicker(this.value, Number(document.getElementById('weekPicker').value || 1));
            }
            const selectedDate = getSelectedDateForTimeframe(currentTimeframe);
            focusPicker.value = selectedDate;
            loadRevenueData(currentService, currentTimeframe, selectedDate, this.value);
        }
    });
    document.getElementById('monthPicker').addEventListener('change', function() {
        if (currentTimeframe !== 'month') return;
        const selectedDate = getSelectedDateForTimeframe('month');
        document.getElementById('dayFocusDate').value = selectedDate;
        loadRevenueData(currentService, 'month', selectedDate, document.getElementById('yearPicker').value);
    });
    document.getElementById('weekPicker').addEventListener('change', function() {
        if (currentTimeframe !== 'week') return;
        const selectedDate = getSelectedDateForTimeframe('week');
        document.getElementById('dayFocusDate').value = selectedDate;
        loadRevenueData(currentService, 'week', selectedDate, document.getElementById('yearPicker').value);
    });
    function loadRangeRevenueData() {
        if (currentTimeframe !== 'range') return;
        const start = document.getElementById('rangeStartDate').value;
        const end = document.getElementById('rangeEndDate').value;
        if (!start || !end) return;
        if (start > end) {
            document.getElementById('revenueLoading').style.display = 'none';
            return;
        }
        loadRevenueData(currentService, 'range', document.getElementById('dayFocusDate').value, document.getElementById('yearPicker').value);
    }
    document.getElementById('rangeStartDate').addEventListener('change', loadRangeRevenueData);
    document.getElementById('rangeEndDate').addEventListener('change', loadRangeRevenueData);

    document.getElementById('rbdClose').addEventListener('click', function() {
        hideBarDetail();
        if (revenueChart) applyBarHighlight(latestRevenuePayload.values || [], -1);
    });

    loadRevenueData(
        currentService, currentTimeframe,
        getSelectedDateForTimeframe(currentTimeframe),
        document.getElementById('yearPicker').value
    );
    updateTimeframeControls();

    const revenueCanvasEl = document.getElementById('revenueChart');
    if (revenueCanvasEl) {
        revenueCanvasEl.addEventListener('pointerdown',  markGestureStart);
        revenueCanvasEl.addEventListener('pointermove',  markGestureMove);
        revenueCanvasEl.addEventListener('pointerup',    clearGesture);
        revenueCanvasEl.addEventListener('pointercancel', clearGesture);
        revenueCanvasEl.addEventListener('pointerleave', clearGesture);
    }

    // ── Export panel ──
    function fmt(d) {
        return d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
    }
    document.getElementById('quickRange').addEventListener('change', function() {
        const now = new Date();
        let start, end;
        switch (this.value) {
            case 'this-month': start = new Date(now.getFullYear(), now.getMonth(), 1);
                               end   = new Date(now.getFullYear(), now.getMonth()+1, 0); break;
            case 'last-month': start = new Date(now.getFullYear(), now.getMonth()-1, 1);
                               end   = new Date(now.getFullYear(), now.getMonth(), 0); break;
            case 'last-3':     start = new Date(now.getFullYear(), now.getMonth()-2, 1);
                               end   = new Date(now.getFullYear(), now.getMonth()+1, 0); break;
            case 'last-6':     start = new Date(now.getFullYear(), now.getMonth()-5, 1);
                               end   = new Date(now.getFullYear(), now.getMonth()+1, 0); break;
            case 'this-year':  start = new Date(now.getFullYear(), 0, 1);
                               end   = new Date(now.getFullYear(), 11, 31); break;
            default: return;
        }
        document.getElementById('exportStart').value = fmt(start);
        document.getElementById('exportEnd').value   = fmt(end);
    });
    document.getElementById('btnCsv').addEventListener('click', function() {
        document.getElementById('exportForm').removeAttribute('target');
    });
    document.getElementById('btnPdf').addEventListener('click', function() {
        document.getElementById('exportForm').setAttribute('target', '_blank');
    });
    // ── Peak Booking Times ──
    let peakChart = null;

    function showPeakEmpty(show) {
        document.getElementById('peakEmpty').style.display      = show ? 'flex' : 'none';
        document.getElementById('peakTimesChart').style.display = show ? 'none' : 'block';
    }

    function loadPeakData(service = 'all') {
        const url = `<?= base_url('admin/getPeakTimesData'); ?>?service=${service}&range=all`;

        document.getElementById('peakLoading').style.display = 'flex';
        showPeakEmpty(false);

        fetch(url)
            .then(r => r.json())
            .then(data => {
                document.getElementById('peakLoading').style.display = 'none';
                if (!data.hours || data.hours.length === 0) {
                    if (peakChart) { peakChart.destroy(); peakChart = null; }
                    showPeakEmpty(true);
                    return;
                }
                showPeakEmpty(false);
                const canvas  = document.getElementById('peakTimesChart');
                if (peakChart) peakChart.destroy();

                const peakMax = Math.max(...data.counts) || 1;
                const peakColors = data.counts.map(c => {
                    const t = c / peakMax;
                    const r = Math.round(237 + (180 - 237) * t);
                    const g = Math.round(218 + (141 - 218) * t);
                    const b = Math.round(120 + (0   - 120) * t);
                    return `rgb(${r},${g},${b})`;
                });
                const peakHover = data.counts.map(c => {
                    const t = c / peakMax;
                    return `rgba(${Math.round(227+(180-227)*t)},${Math.round(184+(130-184)*t)},${Math.round(56+(0-56)*t)},0.85)`;
                });

                peakChart = new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: data.hours.map(fmtHour),
                        datasets: [{
                            label: 'Orders',
                            data: data.counts,
                            backgroundColor: peakColors,
                            hoverBackgroundColor: peakHover,
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        animation: { duration: 650, easing: 'easeInOutQuart' },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(30,22,0,0.88)',
                                padding: 10, cornerRadius: 8,
                                titleColor: '#F2BE00', bodyColor: '#fff',
                                callbacks: {
                                    title: items => fmtHour(data.hours[items[0].dataIndex]),
                                    label: ctx  => '  ' + ctx.parsed.y + ' order' + (ctx.parsed.y !== 1 ? 's' : '')
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid:   { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                                border: { display: false },
                                ticks:  { stepSize: 1, precision: 0, color: '#999', font: { size: 11 }, callback: v => Number.isInteger(v) ? v : null }
                            },
                            x: {
                                grid:   { display: false },
                                border: { display: false },
                                ticks:  { color: '#999', font: { size: 10 }, maxRotation: 45, minRotation: 0 }
                            }
                        }
                    }
                });
            })
            .catch(() => {
                document.getElementById('peakLoading').style.display = 'none';
                showPeakEmpty(true);
            });
    }

    document.getElementById('peakService').addEventListener('change', function() {
        loadPeakData(this.value);
    });

    loadPeakData();

    // ── KPI Card looping (click to cycle) ──
    (function() {
        const KPI_CONFIG = {
            revenue: [
                { label: 'Total Revenue', value: 'RM <?= number_format((float)$totalRevenue, 2) ?>', icon: 'fa-coins', improvement: <?= json_encode((float)($weekRevenueGrowthPct ?? 0)) ?>, sub: 'vs last week' },
                { label: 'Monthly Revenue', value: 'RM <?= number_format((float)($thisMonthRevenue ?? 0), 2) ?>', icon: 'fa-calendar-alt', improvement: <?= json_encode((float)$growthPct) ?>, sub: 'vs last month' },
                { label: 'Weekly Revenue', value: 'RM <?= number_format((float)($thisWeekRevenue ?? 0), 2) ?>', icon: 'fa-calendar-week', improvement: <?= json_encode((float)$weekRevenueGrowthPct) ?>, sub: 'vs last week' },
            ],
            orders: [
                { label: 'Total Orders', value: '<?= number_format((int)$totalOrders) ?>', icon: 'fa-shopping-bag', improvement: <?= json_encode((float)$weekOrderGrowthPct) ?>, sub: 'vs last week' },
                { label: 'Yearly Order No.', value: '<?= number_format((int)($thisYearOrders ?? 0)) ?>', icon: 'fa-calendar', improvement: <?= json_encode((float)$yearOrderGrowthPct) ?>, sub: 'vs last year' },
                { label: 'Monthly Order No.', value: '<?= number_format((int)($thisMonthOrders ?? 0)) ?>', icon: 'fa-calendar-day', improvement: <?= json_encode((float)$monthOrderGrowthPct) ?>, sub: 'vs last month' },
                { label: 'Weekly Order No.', value: '<?= number_format((int)($thisWeekOrders ?? 0)) ?>', icon: 'fa-calendar-week', improvement: <?= json_encode((float)$weekOrderGrowthPct) ?>, sub: 'vs last week' },
            ],
            aov: [
                { label: 'Average Order Value', value: 'RM <?= number_format((float)($avgOrderValue ?? 0), 2) ?>', icon: 'fa-receipt', improvement: 0, sub: 'overall' },
                { label: 'Average Storage Order Value', value: 'RM <?= number_format((float)($avgStorageOrderValue ?? 0), 2) ?>', icon: 'fa-warehouse', improvement: 0, sub: 'storage orders' },
                { label: 'Average Delivery Order Value', value: 'RM <?= number_format((float)($avgDeliveryOrderValue ?? 0), 2) ?>', icon: 'fa-truck', improvement: 0, sub: 'delivery orders' },
            ],
            customer: [
                { label: 'Unique Customer', value: '<?= number_format((int)$uniqueCustomers) ?>', icon: 'fa-users', improvement: 0, sub: 'all-time' },
                { label: 'Repeating Customer', value: '<?= number_format((int)($repeatingCustomers ?? 0)) ?>', icon: 'fa-user-check', improvement: <?= json_encode((float)($repeatCustomerGrowthPct ?? 0)) ?>, sub: 'all-time' },
            ],
        };

        function applyKpiState(card, cfg) {
            card.querySelector('[data-kpi-label]').textContent = cfg.label;
            card.querySelector('[data-kpi-value]').textContent = cfg.value;
            const iconEl = card.querySelector('[data-kpi-icon]');
            iconEl.className = `fas ${cfg.icon}`;
            const badge = card.querySelector('[data-kpi-badge]');
            const imp = Number(cfg.improvement || 0);
            badge.classList.remove('up', 'down');
            badge.classList.add(imp >= 0 ? 'up' : 'down');
            badge.querySelector('i').className = `fas fa-arrow-${imp >= 0 ? 'up' : 'down'}`;
            badge.querySelector('[data-kpi-improvement]').textContent = `${Math.abs(imp).toFixed(1)}%`;
            card.querySelector('[data-kpi-sub]').textContent = cfg.sub || 'vs last week';
            card.classList.remove('kpi-jump');
            window.requestAnimationFrame(() => card.classList.add('kpi-jump'));
        }

        document.querySelectorAll('.kpi-loop-card').forEach((card) => {
            const key = card.dataset.kpi;
            const list = KPI_CONFIG[key] || [];
            let idx = 0;
            if (list.length) applyKpiState(card, list[0]);
            card.addEventListener('click', () => {
                if (!list.length) return;
                idx = (idx + 1) % list.length;
                applyKpiState(card, list[idx]);
            });
        });
    })();

    // ── Revenue by Category Donut Chart ──
    (function() {
        const storageRevenue  = <?= json_encode((float)$storageRevenue) ?>;
        const deliveryRevenue = <?= json_encode((float)$deliveryRevenue) ?>;
        const ctx = document.getElementById('categoryDonutChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Storage', 'Delivery'],
                datasets: [{
                    data: [storageRevenue, deliveryRevenue],
                    backgroundColor: ['#F2BE00', '#1A1A1A'],
                    hoverBackgroundColor: ['#d4a700', '#000000'],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '65%',
                animation: { duration: 650, easing: 'easeInOutQuart' },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#555', font: { size: 12 }, padding: 14 }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30,22,0,0.88)',
                        padding: 12, cornerRadius: 8,
                        titleColor: '#F2BE00', bodyColor: '#fff',
                        callbacks: { label: ctx => '  ' + fmtRM(ctx.parsed) }
                    }
                }
            }
        });
    })();

    // ── Mini Calendar (Date Navigator) ──
    window.updateCalendarMetric = function(revenue, dateStr, orders) {
        const safeRevenue = Number.isFinite(Number(revenue)) ? Number(revenue) : 0;
        const safeOrders = Number.isFinite(Number(orders)) ? Number(orders) : 0;
        document.getElementById('calDayRevenue').textContent = fmtRM(safeRevenue);
        document.getElementById('calDayLabel').textContent   = dateStr || 'Selected Day';
        const badge = document.getElementById('calDayBadge');
        if (safeOrders > 0) {
            badge.textContent = safeOrders + ' order' + (safeOrders !== 1 ? 's' : '');
            badge.className   = 'cmb-badge yellow';
            badge.style.display = 'inline-flex';
        } else {
            badge.style.display = 'none';
        }
    };

    (function() {
        const orderDateCounts = <?= json_encode($orderDateCounts ?? []) ?>;
        const orderDateSet = new Set(Object.keys(orderDateCounts || {}));
        const todayObj = new Date();
        let calYear  = todayObj.getFullYear();
        let calMonth = todayObj.getMonth(); // 0-indexed
        let calSelected = null;
        let calDragStart = null;
        let calDragCurrent = null;
        let isDraggingRange = false;
        let rangeDragAnchor = null;

        const MONTH_NAMES = ['January','February','March','April','May','June',
                             'July','August','September','October','November','December'];
        const DOW_LABELS  = ['M','T','W','T','F','S','S'];

        const todayStr = todayObj.getFullYear()
            + '-' + String(todayObj.getMonth()+1).padStart(2,'0')
            + '-' + String(todayObj.getDate()).padStart(2,'0');

        function toYmdFromDate(d) {
            return d.getFullYear()
                + '-' + String(d.getMonth() + 1).padStart(2, '0')
                + '-' + String(d.getDate()).padStart(2, '0');
        }

        function normalizeRange(a, b) {
            if (!a || !b) return [a, b];
            return a <= b ? [a, b] : [b, a];
        }

        function clampShortRange(startStr, endStr, maxDays = 366) {
            let [start, end] = normalizeRange(startStr, endStr);
            const startDt = new Date(start + 'T00:00:00');
            const endDt = new Date(end + 'T00:00:00');
            const spanDays = Math.floor((endDt - startDt) / 86400000) + 1;
            if (spanDays <= maxDays) return [start, end];
            const clampedEnd = new Date(startDt);
            clampedEnd.setDate(clampedEnd.getDate() + (maxDays - 1));
            return [start, toYmdFromDate(clampedEnd)];
        }

        function inRange(dateStr, startStr, endStr) {
            if (!startStr || !endStr) return false;
            const [s, e] = normalizeRange(startStr, endStr);
            return dateStr >= s && dateStr <= e;
        }

        function getOrderDensityClass(orderCount) {
            const n = Number(orderCount || 0);
            if (n <= 0) return '';
            if (n <= 2) return 'cal-has-data-l1';
            if (n <= 5) return 'cal-has-data-l2';
            if (n <= 9) return 'cal-has-data-l3';
            return 'cal-has-data-l4';
        }

        function setRangeAndLoad(startStr, endStr) {
            const [start, end] = clampShortRange(startStr, endStr, 366);
            document.getElementById('rangeStartDate').value = start;
            document.getElementById('rangeEndDate').value = end;
            document.getElementById('calSelectedLabel').textContent = `${start} to ${end}`;
            document.querySelectorAll('#periodToggle .pt-btn').forEach(b => b.classList.remove('active'));
            const rangeBtn = document.querySelector('#periodToggle .pt-btn[data-val="range"]');
            if (rangeBtn) rangeBtn.classList.add('active');
            currentTimeframe = 'range';
            const focusPicker = document.getElementById('dayFocusDate');
            const yearPicker  = document.getElementById('yearPicker');
            focusPicker.style.display = 'none';
            yearPicker.style.display  = 'none';
            calSelected = null;
            rangeDragAnchor = null;
            updateTimeframeControls();
            renderCalendar();
            loadRevenueData(currentService, 'range', focusPicker.value, yearPicker.value);
        }

        function renderCalendar() {
            const grid  = document.getElementById('calGrid');
            const title = document.getElementById('calMonthTitle');
            const clearBtn = document.getElementById('calClearRange');
            title.textContent = MONTH_NAMES[calMonth] + ', ' + calYear;
            grid.innerHTML = '';
            const rangeStartVal = document.getElementById('rangeStartDate').value || null;
            const rangeEndVal = document.getElementById('rangeEndDate').value || null;
            const hoverStart = calDragStart;
            const hoverEnd = calDragCurrent;
            if (clearBtn) {
                const hasRange = !!(rangeStartVal && rangeEndVal);
                clearBtn.style.display = (hasRange || currentTimeframe === 'day' || !!calSelected) ? 'inline-flex' : 'none';
            }

            // Day-of-week headers (Monday first)
            DOW_LABELS.forEach(d => {
                const el = document.createElement('div');
                el.className = 'cal-dow';
                el.textContent = d;
                grid.appendChild(el);
            });

            // Blank cells before the 1st
            let startDow = new Date(calYear, calMonth, 1).getDay(); // 0=Sun
            startDow = (startDow === 0) ? 6 : startDow - 1;        // convert to Mon-first
            for (let i = 0; i < startDow; i++) {
                const el = document.createElement('div');
                el.className = 'cal-day cal-blank';
                grid.appendChild(el);
            }

            const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = calYear
                    + '-' + String(calMonth + 1).padStart(2,'0')
                    + '-' + String(d).padStart(2,'0');
                const el = document.createElement('div');
                el.className  = 'cal-day';
                el.textContent = d;
                el.dataset.date = dateStr;

                if (dateStr === todayStr)    el.classList.add('cal-today');
                if (dateStr === calSelected) el.classList.add('cal-selected');
                if (dateStr < todayStr)      el.classList.add('cal-past');
                if (orderDateSet.has(dateStr)) {
                    el.classList.add('cal-has-data');
                    const densityClass = getOrderDensityClass(orderDateCounts?.[dateStr] ?? 0);
                    if (densityClass) el.classList.add(densityClass);
                }
                if (currentTimeframe === 'range' && inRange(dateStr, rangeStartVal, rangeEndVal)) {
                    el.classList.add('cal-in-range');
                    if (dateStr === rangeStartVal) el.classList.add('cal-range-start');
                    if (dateStr === rangeEndVal) el.classList.add('cal-range-end');
                }
                if (isDraggingRange && inRange(dateStr, hoverStart, hoverEnd)) {
                    el.classList.add('cal-range-preview');
                }

                el.addEventListener('click', function() {
                    if (isDraggingRange) return;
                    calSelected = this.dataset.date;
                    renderCalendar();
                    activateDayMode(calSelected);
                });
                el.addEventListener('mousedown', function(evt) {
                    if (evt.button !== 0) return;
                    isDraggingRange = true;
                    calDragStart = rangeDragAnchor || this.dataset.date;
                    calDragCurrent = this.dataset.date;
                    rangeDragAnchor = calDragStart;
                    renderCalendar();
                    evt.preventDefault();
                });
                el.addEventListener('mouseenter', function() {
                    if (!isDraggingRange) return;
                    calDragCurrent = this.dataset.date;
                    renderCalendar();
                });
                grid.appendChild(el);
            }
        }

        document.addEventListener('mouseup', function(evt) {
            if (!isDraggingRange) return;
            const start = calDragStart;
            const end = calDragCurrent || calDragStart;
            isDraggingRange = false;
            calDragStart = null;
            calDragCurrent = null;
            const releasedOnNav = !!evt?.target?.closest?.('#calPrev, #calNext');
            if (releasedOnNav && start) {
                // Keep anchor so drag selection can continue across month pages.
                rangeDragAnchor = start;
                renderCalendar();
                return;
            }
            if (start && end && start !== end) {
                setRangeAndLoad(start, end);
                return;
            }
            if (start) {
                rangeDragAnchor = null;
                calSelected = start;
                renderCalendar();
                activateDayMode(start);
                return;
            }
            renderCalendar();
        });

        window.syncCalendarToDate = function(dateStr) {
            if (!dateStr || !/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) return;
            const d = new Date(dateStr + 'T00:00:00');
            if (Number.isNaN(d.getTime())) return;
            calYear = d.getFullYear();
            calMonth = d.getMonth();
            renderCalendar();
        };

        function activateDayMode(dateStr) {
            // Update label
            document.getElementById('calSelectedLabel').textContent = dateStr;
            // Update metric box placeholder
            document.getElementById('calDayRevenue').textContent = 'RM —';
            document.getElementById('calDayLabel').textContent   = 'Loading…';
            document.getElementById('calDayBadge').style.display = 'none';

            // Switch chart controls to Day mode
            document.querySelectorAll('#periodToggle .pt-btn').forEach(b => b.classList.remove('active'));
            const dayBtn = document.querySelector('#periodToggle .pt-btn[data-val="day"]');
            if (dayBtn) dayBtn.classList.add('active');
            currentTimeframe = 'day';

            const focusPicker = document.getElementById('dayFocusDate');
            const yearPicker  = document.getElementById('yearPicker');
            focusPicker.value        = dateStr;
            document.getElementById('rangeStartDate').value = '';
            document.getElementById('rangeEndDate').value = '';
            updateTimeframeControls();

            loadRevenueData(currentService, 'day', dateStr, yearPicker.value);
        }

        function clearCalendarSelection() {
            calSelected = null;
            document.getElementById('rangeStartDate').value = '';
            document.getElementById('rangeEndDate').value = '';
            document.getElementById('calSelectedLabel').textContent = 'Click a day';
            document.getElementById('calDayRevenue').textContent = 'RM —';
            document.getElementById('calDayLabel').textContent = 'Select a day to view revenue';
            document.getElementById('calDayBadge').style.display = 'none';

            document.querySelectorAll('#periodToggle .pt-btn').forEach(b => b.classList.remove('active'));
            const monthBtn = document.querySelector('#periodToggle .pt-btn[data-val="month"]');
            if (monthBtn) monthBtn.classList.add('active');
            currentTimeframe = 'month';
            updateTimeframeControls();

            const selectedDate = getSelectedDateForTimeframe('month');
            document.getElementById('dayFocusDate').value = selectedDate;
            renderCalendar();
            loadRevenueData(currentService, 'month', selectedDate, document.getElementById('yearPicker').value);
        }

        document.getElementById('calClearRange').addEventListener('click', function() {
            clearCalendarSelection();
        });

        document.getElementById('calPrev').addEventListener('click', function() {
            calMonth--;
            if (calMonth < 0) { calMonth = 11; calYear--; }
            renderCalendar();
        });
        document.getElementById('calNext').addEventListener('click', function() {
            calMonth++;
            if (calMonth > 11) { calMonth = 0; calYear++; }
            renderCalendar();
        });

        renderCalendar();
    })();
</script>

<?= $this->include('admin/footer'); ?>
