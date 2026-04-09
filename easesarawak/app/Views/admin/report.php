<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/report.css') ?>">

<div class="container-fluid mt-4">

    <!-- Page header -->
    <div class="page-header">
        <h3 class="fw-bold mb-1">Analytics &amp; Reports</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="<?= base_url('/admin/dashboard'); ?>"><i class="fa fa-home"></i></a></li>
            <li class="separator"><i class="fa fa-angle-right"></i></li>
            <li class="nav-item">Reports</li>
        </ul>
    </div>

    <!-- ══════════════════════════════════════
         ROW 1 — KPI Stat Cards
    ══════════════════════════════════════ -->
    <div class="section-label">Overview</div>
    <div class="row g-3 mb-4">

        <div class="col-sm-6 col-md-4">
            <div class="card stat-card p-0">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="stat-icon"><i class="fas fa-coins"></i></div>
                    <div>
                        <div class="stat-value">RM <?= number_format($totalRevenue, 2) ?></div>
                        <p class="stat-label">Total Revenue &bull; All Time</p>
                    </div>
                </div>
                <div class="stat-divider"></div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="card stat-card p-0">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
                    <div>
                        <div class="stat-value"><?= number_format($totalOrders) ?></div>
                        <p class="stat-label">Total Orders &bull; All Time</p>
                    </div>
                </div>
                <div class="stat-divider"></div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="card stat-card p-0">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="stat-icon"><i class="fas fa-receipt"></i></div>
                    <div>
                        <div class="stat-value">RM <?= $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 2) : '0.00' ?></div>
                        <p class="stat-label">Avg. Order Value &bull; All Time</p>
                    </div>
                </div>
                <div class="stat-divider"></div>
            </div>
        </div>

    </div>

    <!-- ══════════════════════════════════════
         ROW 2 — Charts (left) + Sidebar (right)
    ══════════════════════════════════════ -->
    <div class="row g-3 align-items-start">

        <!-- LEFT COLUMN: Revenue chart -->
        <div class="col-lg-8">
            <div class="section-label">Revenue Analysis</div>
            <div class="card rpt-card">

                <!-- Header with controls -->
                <div class="rpt-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-chart-line"></i>
                        <span class="rpt-title">Revenue Breakdown</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="chart-toggle">
                            <button type="button" id="btnLine" class="ct-btn active" title="Line Chart">
                                <i class="fas fa-chart-line me-1"></i>Line
                            </button>
                            <button type="button" id="btnBar" class="ct-btn" title="Bar Chart">
                                <i class="fas fa-chart-bar me-1"></i>Bar
                            </button>
                        </div>
                        <select id="serviceType" class="report-select">
                            <option value="all">All Services</option>
                            <option value="storage">Storage</option>
                            <option value="delivery">Delivery</option>
                        </select>
                        <select id="timeframe" class="report-select">
                            <option value="day">Day</option>
                            <option value="week">Week</option>
                            <option value="month" selected>Month</option>
                        </select>
                    </div>
                </div>

                <!-- Chart area -->
                <div class="card-body p-3" style="position:relative; min-height:280px;">
                    <div id="revenueLoading" style="display:none; position:absolute; inset:0; background:rgba(255,255,255,0.85); z-index:10; align-items:center; justify-content:center;">
                        <div class="text-center">
                            <div class="spinner-border" style="color:var(--gold);" role="status"></div>
                            <div style="font-size:.8rem; color:#888; margin-top:8px;">Loading…</div>
                        </div>
                    </div>
                    <div id="revenueEmpty" class="chart-empty">
                        <i class="fas fa-chart-line"></i>
                        <p class="fw-semibold">No revenue data found</p>
                        <p style="font-size:.78rem;">Try changing the service type or timeframe.</p>
                    </div>
                    <canvas id="revenueChart"></canvas>
                </div>

                <!-- Summary strip -->
                <div class="rpt-footer d-flex justify-content-around text-center">
                    <div>
                        <div class="sf-label">Highest</div>
                        <div class="sf-val" id="statHigh">—</div>
                    </div>
                    <div>
                        <div class="sf-label">Lowest</div>
                        <div class="sf-val" id="statLow">—</div>
                    </div>
                    <div>
                        <div class="sf-label">Average</div>
                        <div class="sf-val" id="statAvg">—</div>
                    </div>
                    <div>
                        <div class="sf-label">Periods</div>
                        <div class="sf-val" id="statCount">—</div>
                    </div>
                </div>

            </div>
        </div><!-- /col-lg-8 -->

        <!-- RIGHT COLUMN: Export panel + Peak times -->
        <div class="col-lg-4 d-flex flex-column gap-3">

            <!-- Export Panel -->
            <div>
                <div class="section-label">Export</div>
                <div class="card rpt-card">
                    <div class="rpt-card-header d-flex align-items-center gap-2">
                        <i class="fas fa-file-export"></i>
                        <span class="rpt-title">Export Invoice</span>
                    </div>
                    <div class="card-body p-3">
                        <form id="exportForm" action="<?= base_url('report/export') ?>" method="GET" target="_blank">

                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-1" style="font-size:.8rem;">Quick Range</label>
                                <select id="quickRange" class="report-select w-100" style="padding:7px 30px 7px 10px;">
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
                                    <label class="form-label fw-semibold mb-1" style="font-size:.8rem;">From</label>
                                    <input type="date" name="start_date" id="exportStart"
                                           class="form-control rpt-input" value="<?= date('Y-m-01') ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1" style="font-size:.8rem;">To</label>
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

            <!-- Peak Booking Times -->
            <div>
                <div class="section-label">Booking Patterns</div>
                <div class="card rpt-card">
                    <div class="rpt-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-clock"></i>
                            <span class="rpt-title">Peak Booking Times</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <select id="peakService" class="report-select">
                                <option value="all">All Services</option>
                                <option value="storage">Storage</option>
                                <option value="delivery">Delivery</option>
                            </select>
                            <select id="peakRange" class="report-select">
                                <option value="all">All Time</option>
                                <option value="this-month" selected>This Month</option>
                                <option value="last-3">Last 3 Months</option>
                                <option value="this-year">This Year</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                    </div>
                    <!-- Custom date range (shown + enabled only when Custom Range is selected) -->
                    <div id="peakCustomWrap" style="display:none; padding:8px 16px 0; gap:8px;" class="d-flex align-items-center flex-wrap">
                        <div class="d-flex align-items-center gap-2">
                            <label style="font-size:.78rem; font-weight:600; color:#888; white-space:nowrap;">From</label>
                            <input type="date" id="peakStart" class="form-control rpt-input" style="max-width:145px;" value="<?= date('Y-m-01') ?>" disabled>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label style="font-size:.78rem; font-weight:600; color:#888; white-space:nowrap;">To</label>
                            <input type="date" id="peakEnd" class="form-control rpt-input" style="max-width:145px;" value="<?= date('Y-m-d') ?>" disabled>
                        </div>
                    </div>
                    <div class="card-body p-3" style="position:relative; height:260px;">
                        <div id="peakLoading" style="display:none; position:absolute; inset:0; background:rgba(255,255,255,0.85); z-index:10; align-items:center; justify-content:center;">
                            <div class="text-center">
                                <div class="spinner-border" style="color:var(--gold);" role="status"></div>
                                <div style="font-size:.8rem; color:#888; margin-top:8px;">Loading…</div>
                            </div>
                        </div>
                        <div id="peakEmpty" class="chart-empty">
                            <i class="fas fa-clock"></i>
                            <p class="fw-semibold">No booking data yet</p>
                            <p style="font-size:.78rem;">Try a different service or date range.</p>
                        </div>
                        <canvas id="peakTimesChart"></canvas>
                    </div>
                    <div class="rpt-footer text-center">
                        <i class="fas fa-info-circle me-1" style="color:var(--brown);"></i>
                        <span class="sf-label">Orders by hour of day (24 h)</span>
                    </div>
                </div>
            </div>

        </div><!-- /col-lg-4 -->

    </div><!-- /row -->

</div><!-- /container-fluid -->

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let revenueChart;
    let currentChartType = 'line';

    function fmtHour(h) {
        if (h === 0)  return '12 AM';
        if (h === 12) return '12 PM';
        return h < 12 ? h + ' AM' : (h - 12) + ' PM';
    }

    function fmtRM(val) {
        return 'RM ' + Number(val).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function buildBarColors(values, hex, minOpacity = 0.35) {
        const max = Math.max(...values) || 1;
        const r = parseInt(hex.slice(1,3), 16);
        const g = parseInt(hex.slice(3,5), 16);
        const b = parseInt(hex.slice(5,7), 16);
        return values.map(v => {
            const op = (minOpacity + (1 - minOpacity) * (v / max)).toFixed(2);
            return `rgba(${r},${g},${b},${op})`;
        });
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

    function showRevenueEmpty(show) {
        document.getElementById('revenueEmpty').style.display  = show ? 'flex' : 'none';
        document.getElementById('revenueChart').style.display  = show ? 'none' : 'block';
    }

    function loadRevenueData(serviceType = 'all', timeframe = 'month') {
        document.getElementById('revenueLoading').style.display = 'flex';
        showRevenueEmpty(false);

        fetch(`<?= base_url('admin/getRevenueData'); ?>?service=${serviceType}&timeframe=${timeframe}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('revenueLoading').style.display = 'none';

                if (!data.values || data.values.length === 0) {
                    if (revenueChart) { revenueChart.destroy(); revenueChart = null; }
                    showRevenueEmpty(true);
                    updateStats([], []);
                    return;
                }

                showRevenueEmpty(false);
                const ctx = document.getElementById('revenueChart').getContext('2d');
                if (revenueChart) revenueChart.destroy();

                const isLine = currentChartType === 'line';
                const GOLD   = '#f2be00';
                const BROWN  = '#5B532C';

                const gradient = ctx.createLinearGradient(0, 0, 0, 260);
                gradient.addColorStop(0, 'rgba(242,190,0,0.40)');
                gradient.addColorStop(1, 'rgba(242,190,0,0.00)');

                const barColors = buildBarColors(data.values, GOLD, 0.40);
                const barHover  = buildBarColors(data.values, '#c99d00', 0.65);

                revenueChart = new Chart(ctx, {
                    type: currentChartType,
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Revenue (RM)',
                            data: data.values,
                            borderColor: BROWN, borderWidth: 2.5,
                            backgroundColor:      isLine ? gradient : barColors,
                            hoverBackgroundColor: isLine ? gradient : barHover,
                            fill: true, tension: 0.4,
                            pointBackgroundColor: GOLD,
                            pointBorderColor:     BROWN, pointBorderWidth: 2,
                            pointRadius:      isLine ? 5 : 0,
                            pointHoverRadius: isLine ? 7 : 0,
                            borderRadius:  isLine ? 0 : 7,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: true,
                        interaction: { mode: 'index', intersect: false },
                        animation:   { duration: 650, easing: 'easeInOutQuart' },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(30,22,0,0.88)',
                                padding: 12, cornerRadius: 8,
                                titleColor: '#f2be00', bodyColor: '#fff',
                                titleFont: { weight: '700' },
                                callbacks: { label: ctx => '  ' + fmtRM(ctx.parsed.y) }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid:   { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                                border: { display: false },
                                ticks:  { callback: val => 'RM ' + val.toLocaleString('en-MY'), color: '#999', font: { size: 11 } }
                            },
                            x: {
                                grid:   { display: false },
                                border: { display: false },
                                ticks:  { color: '#999', font: { size: 11 } }
                            }
                        }
                    }
                });

                updateStats(data.values, data.labels);
            })
            .catch(() => {
                document.getElementById('revenueLoading').style.display = 'none';
                showRevenueEmpty(true);
                updateStats([], []);
            });
    }

    // Chart type toggle
    document.getElementById('btnLine').addEventListener('click', function() {
        currentChartType = 'line';
        this.classList.add('active');
        document.getElementById('btnBar').classList.remove('active');
        loadRevenueData(document.getElementById('serviceType').value, document.getElementById('timeframe').value);
    });
    document.getElementById('btnBar').addEventListener('click', function() {
        currentChartType = 'bar';
        this.classList.add('active');
        document.getElementById('btnLine').classList.remove('active');
        loadRevenueData(document.getElementById('serviceType').value, document.getElementById('timeframe').value);
    });

    document.getElementById('serviceType').addEventListener('change', function() {
        loadRevenueData(this.value, document.getElementById('timeframe').value);
    });
    document.getElementById('timeframe').addEventListener('change', function() {
        loadRevenueData(document.getElementById('serviceType').value, this.value);
    });

    loadRevenueData();

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

    function loadPeakData(service = 'all', range = 'this-month') {
        // Show/hide and enable/disable the custom date inputs
        const isCustom = range === 'custom';
        const customWrap = document.getElementById('peakCustomWrap');
        customWrap.style.display = isCustom ? 'flex' : 'none';
        document.getElementById('peakStart').disabled = !isCustom;
        document.getElementById('peakEnd').disabled   = !isCustom;

        // For custom range, validate and only fetch when both dates are filled and valid
        let url = `<?= base_url('admin/getPeakTimesData'); ?>?service=${service}&range=${range}`;
        if (range === 'custom') {
            const startEl = document.getElementById('peakStart');
            const endEl   = document.getElementById('peakEnd');
            const start   = startEl.value;
            const end     = endEl.value;
            if (!start || !end) return;
            if (start > end) {
                startEl.classList.add('is-invalid');
                endEl.classList.add('is-invalid');
                endEl.setCustomValidity('End date must be on or after the start date.');
                endEl.reportValidity();
                return;
            }
            startEl.classList.remove('is-invalid');
            endEl.classList.remove('is-invalid');
            endEl.setCustomValidity('');
            url += `&start=${start}&end=${end}`;
        }

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
                const canvas = document.getElementById('peakTimesChart');
                if (peakChart) peakChart.destroy();

                const peakMax = Math.max(...data.counts) || 1;
                const peakColors = data.counts.map(c => {
                    const t = c / peakMax;
                    const r = Math.round(242 + (201 - 242) * t);
                    const g = Math.round(224 + (157 - 224) * t);
                    const b = Math.round(128 + (0   - 128) * t);
                    return `rgb(${r},${g},${b})`;
                });
                const peakHover = data.counts.map(c => {
                    const t = c / peakMax;
                    return `rgba(${Math.round(242+(180-242)*t)},${Math.round(190+(130-190)*t)},0,0.8)`;
                });

                peakChart = new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: data.hours.map(fmtHour),
                        datasets: [{
                            label: 'Orders',
                            data: data.counts,
                            backgroundColor: peakColors, hoverBackgroundColor: peakHover,
                            borderRadius: 6, borderSkipped: false,
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
                                titleColor: '#f2be00', bodyColor: '#fff',
                                callbacks: {
                                    title: items => fmtHour(data.hours[items[0].dataIndex]),
                                    label: ctx => '  ' + ctx.parsed.y + ' order' + (ctx.parsed.y !== 1 ? 's' : '')
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid:   { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                                border: { display: false },
                                ticks:  { stepSize: 1, precision: 0, color: '#999', font: { size: 11 }, callback: (v) => Number.isInteger(v) ? v : null }
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
        loadPeakData(this.value, document.getElementById('peakRange').value);
    });
    document.getElementById('peakRange').addEventListener('change', function() {
        loadPeakData(document.getElementById('peakService').value, this.value);
    });
    document.getElementById('peakStart').addEventListener('change', function() {
        if (document.getElementById('peakRange').value === 'custom')
            loadPeakData(document.getElementById('peakService').value, 'custom');
    });
    document.getElementById('peakEnd').addEventListener('change', function() {
        if (document.getElementById('peakRange').value === 'custom')
            loadPeakData(document.getElementById('peakService').value, 'custom');
    });

    loadPeakData();
</script>

<?= $this->include('admin/footer'); ?>
