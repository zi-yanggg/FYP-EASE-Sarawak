<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/report.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/admin/calendar.css') ?>">

<div class="rpt-page container-fluid">
    <div class="page-inner">

        <!-- ── Page Header ── -->
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

        <!-- ── Two-Column Layout ── -->
        <div class="row g-3 align-items-start">

            <!-- ── Main Calendar ── -->
            <div class="col-lg-8 col-12">
                <div class="cal-card admin-booking-cal-wrap">
                    <div class="cal-card-head">
                        <div class="cal-head-left">
                            <span class="cal-head-pill">
                                <span class="cal-head-dot"></span>
                                Schedule
                            </span>
                        </div>
                        <div class="cal-head-right">
                            <div class="cal-view-pills">
                                <button class="cal-pill-btn" data-cal-view="dayGridMonth">Month</button>
                                <button class="cal-pill-btn" data-cal-view="timeGridWeek">Week</button>
                                <button class="cal-pill-btn" data-cal-view="timeGridDay">Day</button>
                                <button class="cal-pill-btn" data-cal-view="multiMonthYear">Year</button>
                            </div>
                        </div>
                    </div>

                    <div class="cal-body">
                        <div id="admin-booking-calendar"></div>
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

            <!-- ── Right Sidebar ── -->
            <div class="col-lg-4 col-12 d-flex flex-column gap-3">

                <!-- Mini Calendar -->
                <div class="cal-card">
                    <div class="cal-card-head">
                        <span class="cal-head-pill">
                            <span class="cal-head-dot"></span>
                            Overview
                        </span>
                    </div>
                    <div class="cal-body cal-mini-body">
                        <div id="mini-calendar"></div>
                    </div>
                </div>

                <!-- Order Activity Panel -->
                <div class="cal-card">
                    <div class="cal-card-head">
                        <span class="cal-head-pill">
                            <span class="cal-head-dot"></span>
                            <span id="cal-day-label">Order Activity</span>
                        </span>
                        <span id="cal-day-count-badge" class="cal-day-count-badge" hidden></span>
                    </div>
                    <div class="cal-body cal-day-body">
                        <div id="cal-day-orders">
                            <p class="cal-day-empty">Click any date to view bookings.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- ── Event Popup — no backdrop, no body lock, X on top-left ── -->
<div id="calEventPopup" class="cal-popup-wrap" hidden>
    <div class="cal-popup-box">
        <button class="cal-popup-close" id="calPopupClose" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
        <div class="cal-popup-head">
            <h5 class="cal-popup-title" id="calPopupTitle">Booking</h5>
        </div>
        <div class="cal-popup-body" id="calPopupBody"></div>
        <div class="cal-popup-foot">
            <button type="button" class="btn btn-sm rpt-export-btn" id="calendarGoToOrder">View Order</button>
            <button type="button" class="btn btn-sm rpt-export-btn" id="calendarFetchOrderDetails">
                <i class="fas fa-info-circle me-1"></i> Full Details
            </button>
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
        agendaWeek:     'timeGridWeek',
        agendaDay:      'timeGridDay',
        month:          'dayGridMonth',
        basicDay:       'timeGridDay',
        multiMonthYear: 'multiMonthYear',
    };

    var rawInitial  = <?= json_encode($initial_view ?? 'dayGridMonth', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    var initialView = viewNameMap[rawInitial] || rawInitial;
    var initialDate = <?= json_encode($initial_date ?? date('Y-m-d'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

    // ── Pre-compute per-day counts ─────────────────────────────────────────────
    var dayStats = {};
    calendarEvents.forEach(function (ev) {
        if (!ev.start) return;
        var d = ev.start.slice(0, 10);
        if (!dayStats[d]) dayStats[d] = { total: 0, delivery: 0, storage: 0 };
        dayStats[d].total++;
        var service = (ev.extendedProps && ev.extendedProps.service) || '';
        if (service === 'storage') { dayStats[d].storage++; } else { dayStats[d].delivery++; }
    });

    // ── Helpers ────────────────────────────────────────────────────────────────
    function syncPills(viewName) {
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

    function formatTime(dateStr) {
        if (!dateStr) return '';
        var d = new Date(dateStr);
        if (isNaN(d)) return '';
        return d.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', hour12: true });
    }

    // ── Mini calendar heatmap helper ───────────────────────────────────────────
    function applyMiniHeat(info) {
        var d     = info.date.toISOString().slice(0, 10);
        var stats = dayStats[d];
        var total = stats ? stats.total : 0;
        if      (total >= 6) info.el.classList.add('cal-heat-3');
        else if (total >= 3) info.el.classList.add('cal-heat-2');
        else if (total >= 1) info.el.classList.add('cal-heat-1');
    }

    // ── Daily Activity Panel ───────────────────────────────────────────────────
    function showDayOrders(dateStr) {
        var panel = document.getElementById('cal-day-orders');
        var label = document.getElementById('cal-day-label');
        var badge = document.getElementById('cal-day-count-badge');

        var d = new Date(dateStr + 'T00:00:00');
        if (label) {
            label.textContent = d.toLocaleDateString('en-GB', {
                weekday: 'short', day: 'numeric', month: 'short', year: 'numeric'
            });
        }

        var dayEvents = calendarEvents.filter(function (ev) {
            return ev.start && ev.start.slice(0, 10) === dateStr;
        });

        if (badge) {
            if (dayEvents.length) {
                badge.innerHTML = 'No. of Orders: <strong>' + dayEvents.length + '</strong>';
                badge.hidden = false;
            } else {
                badge.hidden = true;
            }
        }

        if (!dayEvents.length) {
            panel.innerHTML = '<p class="cal-day-empty">No bookings on this date.</p>';
            return;
        }

        dayEvents.sort(function (a, b) {
            return (a.start || '').localeCompare(b.start || '');
        });

        var html = dayEvents.map(function (ev) {
            var p        = ev.extendedProps || {};
            var kind     = p.kind === 'pickup' ? 'Pickup' : 'Drop-off';
            var st       = p.status;
            var stClass  = st == 2 ? 'done' : st == 1 ? 'progress' : 'pending';
            var stLabel  = st == 2 ? 'Completed' : st == 1 ? 'In progress' : 'Pending';
            var dotColor = ev.borderColor || ev.backgroundColor || ev.color || '#F2BE00';
            var time     = formatTime(ev.start);

            return [
                '<div class="cal-day-item fc-event fc-daygrid-event fc-daygrid-dot-event"',
                    ' style="--fc-event-border-color:' + dotColor + '"',
                    ' data-oid="' + (p.orderId || '') + '"',
                    ' data-service="' + (p.service || '') + '">',
                '<div class="fc-daygrid-event-dot"></div>',
                '<div class="cal-day-item-body">',
                    '<div class="cal-day-item-top">',
                        (time ? '<span class="fc-event-time">' + time + '</span>' : ''),
                        '<span class="cal-day-oid">#' + (p.orderId || '—') + '</span>',
                        '<span class="cal-day-st cal-day-st--' + stClass + '">' + stLabel + '</span>',
                    '</div>',
                    '<div class="cal-day-name">' + (p.customer || '—') + '</div>',
                    '<div class="cal-day-meta">',
                        String(p.service || '—').toUpperCase(),
                        '<span class="cal-dot-sep">·</span>',
                        kind,
                    '</div>',
                '</div>',
                '</div>',
            ].join('');
        }).join('');

        panel.innerHTML = html;

        // Wire click → open popup
        panel.querySelectorAll('.cal-day-item').forEach(function (item) {
            item.addEventListener('click', function () {
                var oid   = item.dataset.oid;
                var match = calendarEvents.find(function (ev) {
                    return ev.extendedProps && String(ev.extendedProps.orderId) === String(oid);
                });
                if (match) openPopup(match);
            });

            // Wire hover → highlight self + matching calendar event
            item.addEventListener('mouseenter', function () {
                item.classList.add('cal-highlight');
                var oid = item.dataset.oid;
                document.querySelectorAll('.fc-event[data-oid="' + oid + '"]')
                    .forEach(function (el) { el.classList.add('cal-highlight'); });
            });
            item.addEventListener('mouseleave', function () {
                item.classList.remove('cal-highlight');
                document.querySelectorAll('.fc-event.cal-highlight')
                    .forEach(function (el) { el.classList.remove('cal-highlight'); });
            });
        });
    }

    // ── Custom Event Popup ─────────────────────────────────────────────────────
    function openPopup(ev) {
        var p       = ev.extendedProps || {};
        var oid     = p.orderId  || '';
        var kind    = p.kind === 'pickup' ? 'Pickup' : 'Drop-off';
        var st      = p.status;
        var stLabel = st == 2 ? 'Completed' : st == 1 ? 'In progress' : 'Pending';

        var popup = document.getElementById('calEventPopup');
        popup.dataset.oid = String(oid);

        document.getElementById('calPopupTitle').textContent = ev.title || 'Booking';
        document.getElementById('calPopupBody').innerHTML =
            '<dl class="row mb-0">' +
            '<dt class="col-sm-4">Order ID</dt><dd class="col-sm-8">#' + (oid || '—') + '</dd>' +
            '<dt class="col-sm-4">Customer</dt><dd class="col-sm-8">' + (p.customer || '—') + '</dd>' +
            '<dt class="col-sm-4">Service</dt><dd class="col-sm-8">'  + String(p.service || '—').toUpperCase() + '</dd>' +
            '<dt class="col-sm-4">Type</dt><dd class="col-sm-8">'     + kind + '</dd>' +
            '<dt class="col-sm-4">Status</dt><dd class="col-sm-8">'   + stLabel + '</dd>' +
            '<dt class="col-sm-4">Start</dt><dd class="col-sm-8">'    + formatDt(ev.start || '') + '</dd>' +
            (ev.end ? '<dt class="col-sm-4">End</dt><dd class="col-sm-8">' + formatDt(ev.end) + '</dd>' : '') +
            '</dl>' +
            '<p class="text-muted small mb-0 mt-2">Use <strong>Full Details</strong> for the full order breakdown.</p>';

        var fetchBtn = document.getElementById('calendarFetchOrderDetails');
        var orderBtn = document.getElementById('calendarGoToOrder');
        if (fetchBtn) fetchBtn.style.display = oid ? 'inline-block' : 'none';
        if (orderBtn) orderBtn.style.display  = oid ? 'inline-block' : 'none';

        popup.hidden = false;
    }

    function closePopup() {
        var popup = document.getElementById('calEventPopup');
        if (popup) popup.hidden = true;
    }

    document.getElementById('calPopupClose').addEventListener('click', closePopup);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closePopup(); });

    // ── Declare miniCalendar before main (guards against datesSet firing early) ─
    var miniCalendar = null;

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

        eventTimeFormat: { hour: 'numeric', minute: '2-digit', hour12: true },

        allDayText: 'All Day',
        height: 'auto',
        nowIndicator: true,
        slotDuration: '00:30:00',
        slotMinTime: '00:00:00',
        slotMaxTime: '24:00:00',
        displayEventEnd: true,
        slotEventOverlap: false,
        fixedWeekCount: false,
        showNonCurrentDates: false,
        events: calendarEvents,

        views: {
            dayGridMonth:   { eventDisplay: 'none' },
            multiMonthYear: { eventDisplay: 'none' },
        },

        multiMonthMaxColumns: 4,
        multiMonthMinWidth: 200,

        // Sync pills and mini calendar on every date/view change
        datesSet: function (info) {
            syncPills(info.view.type);
            if (miniCalendar) miniCalendar.gotoDate(mainCalendar.getDate());
            // Inject "TIME" label into the top-left axis cell (timeGrid views only)
            var axisEl = mainEl.querySelector('.fc-col-header .fc-timegrid-axis');
            if (axisEl) {
                var frame = axisEl.querySelector('.fc-timegrid-axis-frame');
                if (frame) frame.innerHTML = '<span class="cal-axis-time-lbl">TIME</span>';
            }
        },

        // Click a day → drill into day view + sync mini + show day orders
        dateClick: function (info) {
            mainCalendar.gotoDate(info.date);
            mainCalendar.changeView('timeGridDay');
            syncPills('timeGridDay');
            if (miniCalendar) miniCalendar.gotoDate(info.date);
            showDayOrders(info.dateStr);
        },

        // Heatmap + summary block (total / delivery / storage)
        dayCellDidMount: function (info) {
            var d     = info.date.toISOString().slice(0, 10);
            var stats = dayStats[d];
            var total = stats ? stats.total : 0;

            // timeGrid all-day row uses order-table light styling — skip heatmap classes.
            var isTimeGrid = info.view.type.indexOf('timeGrid') === 0;
            if (!isTimeGrid) {
                if      (total >= 11) info.el.classList.add('cal-heat-4');
                else if (total >= 6)  info.el.classList.add('cal-heat-3');
                else if (total >= 3)  info.el.classList.add('cal-heat-2');
                else if (total >= 1)  info.el.classList.add('cal-heat-1');
            }

            if (!stats || total === 0) return;

            var frame = info.el.querySelector('.fc-daygrid-day-frame');
            if (!frame) return;

            var delLine = stats.delivery
                ? '<div class="cal-day-sum-line cal-day-sum-del">' +
                      '<span class="cal-day-sum-lbl">Delivery</span>' +
                      '<span class="cal-day-sum-val">' + stats.delivery + '</span>' +
                  '</div>'
                : '';
            var stoLine = stats.storage
                ? '<div class="cal-day-sum-line cal-day-sum-sto">' +
                      '<span class="cal-day-sum-lbl">Storage</span>' +
                      '<span class="cal-day-sum-val">' + stats.storage + '</span>' +
                  '</div>'
                : '';

            var block = document.createElement('div');
            block.className = 'cal-day-summary';
            block.innerHTML =
                '<div class="cal-day-sum-line">' +
                    '<span class="cal-day-sum-lbl">Orders</span>' +
                    '<span class="cal-day-sum-val">' + total + '</span>' +
                '</div>' +
                delLine + stoLine;
            frame.appendChild(block);
        },

        // Stamp data-oid and data-service on each rendered event + its harness
        eventDidMount: function (info) {
            var p       = info.event.extendedProps || {};
            var oid     = p.orderId;
            var service = p.service;
            if (!oid) return;
            info.el.dataset.oid = String(oid);
            if (service) info.el.dataset.service = service;
            if (info.el.parentElement) {
                info.el.parentElement.dataset.oid = String(oid);
                if (service) info.el.parentElement.dataset.service = service;
            }
        },

        // Calendar event hovered → highlight itself AND matching panel row
        eventMouseEnter: function (info) {
            var oid = (info.event.extendedProps || {}).orderId;
            if (!oid) return;
            info.el.classList.add('cal-highlight');
            document.querySelectorAll('.cal-day-item[data-oid="' + oid + '"]')
                .forEach(function (el) { el.classList.add('cal-highlight'); });
        },

        // Calendar event un-hovered → clear own + panel highlights
        eventMouseLeave: function (info) {
            info.el.classList.remove('cal-highlight');
            document.querySelectorAll('.cal-day-item.cal-highlight')
                .forEach(function (el) { el.classList.remove('cal-highlight'); });
        },

        // Event click in week/day view — open popup
        eventClick: function (info) {
            info.jsEvent.preventDefault();
            var ev = info.event;
            openPopup({
                title: ev.title,
                start: ev.startStr,
                end:   ev.endStr,
                extendedProps: ev.extendedProps || {}
            });
        }
    });

    mainCalendar.render();

    // ── Mini Calendar (init after main so datesSet guard works) ───────────────
    var miniEl = document.getElementById('mini-calendar');
    if (miniEl) {
        miniCalendar = new FullCalendar.Calendar(miniEl, {
            initialView:        'dayGridMonth',
            initialDate:        initialDate,
            headerToolbar:      { left: 'prev', center: 'title', right: 'next' },
            height:             'auto',
            fixedWeekCount:     false,
            showNonCurrentDates: false,
            eventDisplay:       'none',
            events:             calendarEvents,
            dayCellDidMount:    applyMiniHeat,

            // Click mini day → navigate main to day view + show day orders
            dateClick: function (info) {
                mainCalendar.gotoDate(info.date);
                mainCalendar.changeView('timeGridDay');
                syncPills('timeGridDay');
                showDayOrders(info.dateStr);
            }
        });
        miniCalendar.render();
    }

    // ── View Filter Pills ─────────────────────────────────────────────────────
    document.querySelectorAll('.cal-pill-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var v = btn.dataset.calView;
            if (!v) return;
            mainCalendar.changeView(v);
            syncPills(v);
        });
    });

    syncPills(initialView);

    // ── Full Order Details ─────────────────────────────────────────────────────
    document.getElementById('calendarFetchOrderDetails').addEventListener('click', function () {
        var popup = document.getElementById('calEventPopup');
        var id    = popup.dataset.oid;
        var body  = document.getElementById('calPopupBody');
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
                var rows = '';
                try {
                    var det = JSON.parse(o.order_details_json);
                    Object.keys(det).forEach(function (key) {
                        var val = det[key];
                        rows += '<tr><td class="fw-semibold">' + key + '</td><td>' + (val != null ? String(val) : '—') + '</td></tr>';
                    });
                } catch (e) {
                    rows = '<tr><td colspan="2">Details unavailable.</td></tr>';
                }
                var stHtml =
                    o.status == 1 ? '<span class="badge bg-primary">In progress</span>'     :
                    o.status == 2 ? '<span class="badge bg-success">Completed</span>'        :
                                    '<span class="badge bg-warning text-dark">Pending</span>';

                body.innerHTML =
                    '<div class="row g-3">' +
                    '<div class="col-md-6"><strong>Name</strong><br>'   + (o.first_name || '') + ' ' + (o.last_name || '') + '</div>' +
                    '<div class="col-md-6"><strong>Phone</strong><br>'  + (o.phone  || '—') + '</div>' +
                    '<div class="col-md-6"><strong>Email</strong><br>'  + (o.email  || '—') + '</div>' +
                    '<div class="col-md-6"><strong>Status</strong><br>' + stHtml + '</div>' +
                    '<div class="col-12"><strong>Order details</strong>' +
                    '<div class="table-responsive mt-2"><table class="table table-sm table-bordered mb-0"><tbody>' +
                    rows + '</tbody></table></div></div></div>';
            })
            .catch(function () {
                body.innerHTML = '<div class="alert alert-danger mb-0">Request failed.</div>';
            });
    });

    // ── View Order button ─────────────────────────────────────────────────────
    document.getElementById('calendarGoToOrder').addEventListener('click', function () {
        var id = document.getElementById('calEventPopup').dataset.oid;
        if (!id) return;
        window.location.href = '<?= base_url('/order'); ?>/' + id;
    });

}());
</script>
