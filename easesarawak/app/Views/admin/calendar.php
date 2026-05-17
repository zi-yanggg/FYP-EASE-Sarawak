<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/calendar.css') ?>">

<div class="container mt-4">
    <div class="page-inner">
        <div class="ease-page-head d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="ease-crumb">EASE Admin &middot; <b>Booking Calendar</b></div>
                <h1 class="ease-page-title">Booking <b>Calendar</b></h1>
                <div class="cal-page-meta">
                    <span><i class="fas fa-calendar-alt"></i><?= date('l, d F Y') ?></span>
                    <span><i class="fas fa-shopping-bag"></i><?= count(json_decode($calendar_events_json, true) ?? []) ?> bookings loaded</span>
                </div>
            </div>
            <a href="<?= base_url('/order'); ?>" class="btn rpt-export-btn">
                <i class="fas fa-list me-1"></i> Order List
            </a>
        </div>

        <div class="admin-booking-cal-wrap cal-card">

            <!-- ── Card Head ──────────────────────────────────── -->
            <div class="cal-card-head">
                <div class="cal-head-left">
                    <span class="cal-head-pill">
                        <span class="cal-head-dot"></span>
                        Schedule
                    </span>
                </div>
                <div class="cal-head-right">
                    <!-- View Chips -->
                    <div class="cal-view-pills">
                        <button class="cal-pill-btn" data-cal-view="dayGridMonth">Month</button>
                        <button class="cal-pill-btn" data-cal-view="timeGridWeek">Week</button>
                        <button class="cal-pill-btn" data-cal-view="timeGridDay">Day</button>
                        <button class="cal-pill-btn" data-cal-view="multiMonthYear">Year</button>
                    </div>
                    <!-- Legend -->
                    <div class="cal-legend">
                        <span class="cal-legend-pill cal-legend-pill--delivery">Delivery</span>
                        <span class="cal-legend-pill cal-legend-pill--storage">Storage</span>
                    </div>
                </div>
            </div>

            <!-- ── Card Body ──────────────────────────────────── -->
            <div class="cal-body">
                <div id="admin-booking-calendar"></div>

                <!-- Heatmap legend -->
                <div class="cal-heatmap-legend">
                    <span class="cal-hm-label">Heatmap</span>
                    <span class="cal-hm-swatch cal-hm-none">None</span>
                    <span class="cal-hm-swatch cal-hm-1">1–2</span>
                    <span class="cal-hm-swatch cal-hm-2">3–5</span>
                    <span class="cal-hm-swatch cal-hm-3">6–10</span>
                    <span class="cal-hm-swatch cal-hm-4">11+</span>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Event Modal -->
<div class="modal fade" id="calendarEventModal" tabindex="-1" aria-hidden="true" data-order-id="">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header cal-modal-head">
                <h5 class="modal-title" id="calendarEventModalTitle">Booking</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="calendarEventModalBody"></div>
            <div class="modal-footer border-0 flex-wrap gap-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-outline-primary btn-sm" id="calendarGoToOrder">View Order</button>
                <button type="button" class="btn btn-primary btn-sm" id="calendarFetchOrderDetails" style="display:none;">
                    <i class="fas fa-info-circle me-1"></i> Full Order Details
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->include('admin/footer'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<script>
(function () {
    'use strict';

    // ── Data from PHP ──────────────────────────────────────────────────────────
    var calendarEvents = <?= $calendar_events_json ?>;

    var viewNameMap = {
        agendaWeek: 'timeGridWeek',
        agendaDay:  'timeGridDay',
        month:      'dayGridMonth',
        basicDay:   'timeGridDay',
        multiMonthYear: 'multiMonthYear',
    };

    var rawInitial  = <?= json_encode($initial_view ?? 'dayGridMonth', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    var initialView = viewNameMap[rawInitial] || rawInitial;
    var initialDate = <?= json_encode($initial_date ?? date('Y-m-d'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

    // ── Pre-compute per-day delivery / storage counts ──────────────────────────
    var dayStats = {};
    calendarEvents.forEach(function (ev) {
        if (!ev.start) return;
        var d = ev.start.slice(0, 10);
        if (!dayStats[d]) dayStats[d] = { total: 0, delivery: 0, storage: 0 };
        dayStats[d].total++;
        var service = (ev.extendedProps && ev.extendedProps.service) || '';
        if (service === 'storage') {
            dayStats[d].storage++;
        } else {
            dayStats[d].delivery++;
        }
    });

    // ── Helpers ────────────────────────────────────────────────────────────────
    function syncFilterButtons(viewName) {
        document.querySelectorAll('.cal-pill-btn').forEach(function (btn) {
            btn.classList.toggle('active', btn.dataset.calView === viewName);
        });
    }

    function formatDt(dateStr) {
        if (!dateStr) return '—';
        var d = new Date(dateStr);
        if (isNaN(d)) return dateStr;
        return d.toLocaleString('en-GB', {
            year: 'numeric', month: '2-digit', day: '2-digit',
            hour: '2-digit', minute: '2-digit', hour12: false
        }).replace(',', '');
    }

    // ── Main Calendar ──────────────────────────────────────────────────────────
    var mainEl = document.getElementById('admin-booking-calendar');
    if (!mainEl) return;

    var mainCalendar = new FullCalendar.Calendar(mainEl, {
        initialView: initialView,
        initialDate: initialDate,

        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  ''
        },

        eventTimeFormat: {
            hour: 'numeric', minute: '2-digit', hour12: true
        },

        height: 'auto',
        nowIndicator: true,
        slotDuration: '00:30:00',
        slotMinTime: '00:00:00',
        slotMaxTime: '24:00:00',
        displayEventEnd: true,
        fixedWeekCount: false,
        showNonCurrentDates: false,
        events: calendarEvents,

        // Hide event chips in month/year views — bars + count replace them
        views: {
            dayGridMonth:   { eventDisplay: 'none' },
            multiMonthYear: { eventDisplay: 'none' },
        },

        multiMonthMaxColumns: 4,
        multiMonthMinWidth: 200,

        viewDidMount: function (info) {
            syncFilterButtons(info.view.type);
        },

        // Click a day in month view → drill into day view
        dateClick: function (info) {
            mainCalendar.gotoDate(info.date);
            mainCalendar.changeView('timeGridDay');
            syncFilterButtons('timeGridDay');
        },

        // Inject count badge + delivery/storage bars into each day cell
        dayCellDidMount: function (info) {
            var d     = info.date.toISOString().slice(0, 10);
            var stats = dayStats[d];
            var total = stats ? stats.total : 0;

            // Heatmap background
            if      (total >= 11) info.el.classList.add('cal-heat-4');
            else if (total >= 6)  info.el.classList.add('cal-heat-3');
            else if (total >= 3)  info.el.classList.add('cal-heat-2');
            else if (total >= 1)  info.el.classList.add('cal-heat-1');

            if (!stats || total === 0) return;

            // Count badge — appended into the day-top bar next to the day number
            var dayTop = info.el.querySelector('.fc-daygrid-day-top');
            if (dayTop) {
                var badge = document.createElement('span');
                badge.className = 'cal-day-count';
                badge.textContent = total;
                dayTop.appendChild(badge);
            }

            // Bar chart at bottom of cell
            var wrap = document.createElement('div');
            wrap.className = 'cal-cell-bars';

            if (stats.delivery > 0) {
                var dBar = document.createElement('div');
                dBar.className = 'cal-cell-bar cal-cell-bar--delivery';
                dBar.style.width = Math.round((stats.delivery / total) * 100) + '%';
                wrap.appendChild(dBar);
            }
            if (stats.storage > 0) {
                var sBar = document.createElement('div');
                sBar.className = 'cal-cell-bar cal-cell-bar--storage';
                sBar.style.width = Math.round((stats.storage / total) * 100) + '%';
                wrap.appendChild(sBar);
            }

            info.el.appendChild(wrap);
        },

        // eventClick — only fires in week/day views (month hides events)
        eventClick: function (info) {
            info.jsEvent.preventDefault();

            var ev = info.event;
            var p  = ev.extendedProps || {};

            var oid     = p.orderId  || '';
            var kindRaw = p.kind     || '';
            var kind    = kindRaw === 'pickup' ? 'Pickup' : 'Drop-off';
            var st      = p.status;
            var customer = p.customer || '—';
            var service  = p.service  || '—';

            var stLabel = 'Pending';
            if (st === 1 || st === '1') stLabel = 'In progress';
            if (st === 2 || st === '2') stLabel = 'Completed';

            var modalEl = document.getElementById('calendarEventModal');
            modalEl.dataset.orderId = String(oid);

            document.getElementById('calendarEventModalTitle').textContent = ev.title || 'Booking';
            document.getElementById('calendarEventModalBody').innerHTML =
                '<dl class="row mb-0">' +
                '<dt class="col-sm-4">Order ID</dt><dd class="col-sm-8">#' + (oid || '—') + '</dd>' +
                '<dt class="col-sm-4">Customer</dt><dd class="col-sm-8">' + customer + '</dd>' +
                '<dt class="col-sm-4">Service</dt><dd class="col-sm-8">' + String(service).toUpperCase() + '</dd>' +
                '<dt class="col-sm-4">Type</dt><dd class="col-sm-8">' + kind + '</dd>' +
                '<dt class="col-sm-4">Status</dt><dd class="col-sm-8">' + stLabel + '</dd>' +
                '<dt class="col-sm-4">Start</dt><dd class="col-sm-8">' + formatDt(ev.startStr || '') + '</dd>' +
                (ev.endStr ? '<dt class="col-sm-4">End</dt><dd class="col-sm-8">' + formatDt(ev.endStr) + '</dd>' : '') +
                '</dl>' +
                '<p class="text-muted small mb-0 mt-2">Tip: use <strong>Full order details</strong> for the same breakdown as Order Management.</p>';

            document.getElementById('calendarFetchOrderDetails').style.display = oid ? 'inline-block' : 'none';
            document.getElementById('calendarGoToOrder').style.display         = oid ? 'inline-block' : 'none';

            var modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    });

    mainCalendar.render();

    // ── View Filter Pills ─────────────────────────────────────────────────────
    document.querySelectorAll('.cal-pill-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var v = btn.dataset.calView;
            if (!v) return;
            mainCalendar.changeView(v);
            syncFilterButtons(v);
        });
    });

    syncFilterButtons(initialView);

    // ── Full Order Details ─────────────────────────────────────────────────────
    document.getElementById('calendarFetchOrderDetails').addEventListener('click', function () {
        var modalEl = document.getElementById('calendarEventModal');
        var id      = modalEl.dataset.orderId;
        var body    = document.getElementById('calendarEventModalBody');
        if (!id) return;

        body.innerHTML = '<div class="text-center py-3 text-muted"><i class="fa fa-spinner fa-spin me-2"></i>Loading…</div>';

        fetch('<?= base_url('/order/getDetails/'); ?>' + id)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.success || !data.order) {
                    body.innerHTML = '<div class="alert alert-danger mb-0">Could not load order.</div>';
                    return;
                }
                var o = data.order;
                var detailsRows = '';
                try {
                    var detailsObj = JSON.parse(o.order_details_json);
                    Object.keys(detailsObj).forEach(function (key) {
                        var val = detailsObj[key];
                        detailsRows += '<tr><td class="fw-semibold">' + key + '</td><td>' + (val != null ? String(val) : '—') + '</td></tr>';
                    });
                } catch (e) {
                    detailsRows = '<tr><td colspan="2">Details unavailable.</td></tr>';
                }
                var stHtml =
                    o.status == 1 ? '<span class="badge bg-primary">In progress</span>' :
                    o.status == 2 ? '<span class="badge bg-success">Completed</span>' :
                    '<span class="badge bg-warning text-dark">Pending</span>';

                body.innerHTML =
                    '<div class="row g-3">' +
                    '<div class="col-md-6"><strong>Name</strong><br>' + (o.first_name || '') + ' ' + (o.last_name || '') + '</div>' +
                    '<div class="col-md-6"><strong>Phone</strong><br>' + (o.phone || '—') + '</div>' +
                    '<div class="col-md-6"><strong>Email</strong><br>' + (o.email || '—') + '</div>' +
                    '<div class="col-md-6"><strong>Status</strong><br>' + stHtml + '</div>' +
                    '<div class="col-12"><strong>Order details</strong>' +
                    '<div class="table-responsive mt-2"><table class="table table-sm table-bordered mb-0"><tbody>' +
                    detailsRows + '</tbody></table></div></div></div>';
            })
            .catch(function () {
                body.innerHTML = '<div class="alert alert-danger mb-0">Request failed.</div>';
            });
    });

    // ── View Order Button ─────────────────────────────────────────────────────
    document.getElementById('calendarGoToOrder').addEventListener('click', function () {
        var id = document.getElementById('calendarEventModal').dataset.orderId;
        if (!id) return;
        window.location.href = '<?= base_url('/order'); ?>/' + id;
    });

}());
</script>
