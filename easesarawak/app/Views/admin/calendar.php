<?= $this->include('admin/header'); ?>

<style>
    .admin-booking-cal-wrap .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .admin-booking-cal-wrap .fc-button-primary {
        background-color: #1572e8;
        border-color: #1572e8;
    }

    .admin-booking-cal-wrap .fc-button-primary:not(:disabled):active,
    .admin-booking-cal-wrap .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #1266d0;
        border-color: #1266d0;
    }

    /* Layout */
    .calendar-container {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .mini-cal-column {
        flex: 0 0 300px;
    }

    .main-cal-column {
        flex: 1;
        min-width: 0;
    }

    /* Mini Calendar */
    #mini-calendar {
        width: 100%;
        max-width: 280px;
        /* border: 1px solid #e0e0e0;
        border-radius: 8px; */
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    #mini-calendar .fc-toolbar {
        padding: 8px 12px;
    }

    #mini-calendar .fc-toolbar-title {
        font-size: 1rem !important;
        font-weight: 600;
    }

    #mini-calendar .fc-button {
        padding: 3px 7px !important;
        font-size: 0.85rem !important;
    }

    #mini-calendar .fc-daygrid-day {
        cursor: pointer;
        font-size: 0.85rem;
    }

    #mini-calendar .fc-daygrid-day:hover {
        background-color: #f8f9fa;
    }

    #mini-calendar .fc-day-today {
        background-color: #e3f2fd !important;
    }

    /* Hide event dots on mini calendar to keep it clean */
    #mini-calendar .fc-daygrid-event {
        display: none !important;
    }

    /* Show a small dot indicator instead */
    #mini-calendar .fc-daygrid-day.has-events .fc-daygrid-day-number::after {
        content: '';
        display: inline-block;
        width: 5px;
        height: 5px;
        background: #1572e8;
        border-radius: 50%;
        margin-left: 3px;
        vertical-align: middle;
    }

    /* Main Calendar - Hover & Today */
    .fc .fc-daygrid-day {
        cursor: pointer;
        transition: background-color 0.15s ease;
    }

    .fc .fc-daygrid-day:hover {
        background-color: #f0f8ff !important;
    }

    .fc .fc-day-today {
        background-color: #fff3cd !important;
    }

    .fc .fc-daygrid-day-number {
        padding: 6px 8px;
        font-size: 0.95rem;
    }

    .cal-filter-btn.active {
        background-color: #9a9da0 !important;
        color: #fff !important;
        border-color: #9a9da0 !important;
    }

    /* Dropdown styles */
    .admin-booking-cal-wrap .dropdown-toggle {
        min-width: 90px;
        text-align: left;
        border: 1px solid #000;
        border-radius: 20px;
    }

    .admin-booking-cal-wrap .dropdown-toggle::after {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
    }

    .admin-booking-cal-wrap .dropdown-menu {
        min-width: 100px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .admin-booking-cal-wrap .dropdown-item {
        padding: 8px 14px;
        font-size: 0.875rem;
    }

    .admin-booking-cal-wrap .dropdown-item:hover,
    .admin-booking-cal-wrap .dropdown-item:focus {
        background-color: #f0f0f0;
    }

    .admin-booking-cal-wrap .dropdown-item.active {
        background-color: #9fa2a5;
        color: #fff;
    }

    .cal-legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 2px;
        display: inline-block;
        vertical-align: middle;
    }

    .fc-event-time {
        color: black;
    }

    .fc-event-title {
        color: black;
    }

    /* Year view tweaks */
    .fc-multiMonthYear-view .fc-multimonth-title {
        font-weight: 600;
        font-size: 0.95rem;
    }
</style>

<div class="container mt-4">
    <div class="page-inner" style="padding-top: 80px;">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1">Booking Calendar</h3>
            </div>
            <a href="<?= base_url('/order'); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-list me-1"></i> Order List
            </a>
        </div>

        <div class="card shadow-sm border-0 rounded-3 admin-booking-cal-wrap">
            <div class="card-header bg-softblue text-white d-flex flex-wrap align-items-center justify-content-between gap-3 py-3">
                <div>
                    <h5 class="card-title mb-0 fw-semibold">Schedule</h5>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <!-- View Filter Dropdown -->
                    <div class="d-flex align-items-center gap-2">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="viewDropdown">
                                Month
                            </button>
                            <ul class="dropdown-menu dropdown-menu-light">
                                <li><a class="dropdown-item cal-filter-btn" href="#" data-cal-view="dayGridMonth">Month</a></li>
                                <li><a class="dropdown-item cal-filter-btn" href="#" data-cal-view="timeGridWeek">Week</a></li>
                                <li><a class="dropdown-item cal-filter-btn" href="#" data-cal-view="timeGridDay">Day</a></li>
                                <li><a class="dropdown-item cal-filter-btn" href="#" data-cal-view="multiMonthYear">Year</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="d-flex align-items-center gap-2 small">
                        <span style="color: black;"><span class="cal-legend-dot" style="background:#ffc107;"></span> Pending</span>
                        <span style="color: black;"><span class="cal-legend-dot" style="background:#0d6efd;"></span> In progress</span>
                        <span style="color: black;"><span class="cal-legend-dot" style="background:#198754;"></span> Completed</span>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="calendar-container">
                    <!-- Mini Calendar -->
                    <div class="mini-cal-column">
                        <div id="mini-calendar"></div>
                    </div>

                    <!-- Main Calendar -->
                    <div class="main-cal-column">
                        <div id="admin-booking-calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Modal -->
<div class="modal fade" id="calendarEventModal" tabindex="-1" aria-hidden="true" data-order-id="">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header bg-softblue text-white">
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
    (function() {
        'use strict';

        // ── Data from PHP ──────────────────────────────────────────────────────────
        const calendarEvents = <?= $calendar_events_json ?>;

        // Map any legacy v3 view names that might come from PHP
        const viewNameMap = {
            agendaWeek: 'timeGridWeek',
            agendaDay: 'timeGridDay',
            month: 'dayGridMonth',
            basicDay: 'timeGridDay',
            multiMonthYear: 'multiMonthYear',
        };

        const rawInitial = <?= json_encode($initial_view ?? 'dayGridMonth', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        const initialView = viewNameMap[rawInitial] || rawInitial;

        // View name to display text mapping
        const viewNameToText = {
            dayGridMonth: 'Month',
            timeGridWeek: 'Week',
            timeGridDay: 'Day',
            multiMonthYear: 'Year'
        };

        // ── Helpers ────────────────────────────────────────────────────────────────
        function syncFilterButtons(viewName) {
            // Update dropdown button text
            const dropdownBtn = document.getElementById('viewDropdown');
            if (dropdownBtn) {
                dropdownBtn.textContent = viewNameToText[viewName] || viewName;
            }

            // Update active state on dropdown items
            document.querySelectorAll('.cal-filter-btn').forEach(function(btn) {
                btn.classList.toggle('active', btn.dataset.calView === viewName);
            });
        }

        function formatDt(dateStr) {
            if (!dateStr) return '—';
            const d = new Date(dateStr);
            if (isNaN(d)) return dateStr;
            return d.toLocaleString('en-GB', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            }).replace(',', '');
        }

        // ── Main Calendar ──────────────────────────────────────────────────────────
        const mainEl = document.getElementById('admin-booking-calendar');
        if (!mainEl) return;

        const mainCalendar = new FullCalendar.Calendar(mainEl, {
            // v6 plugin bundles are included via index.global — all plugins available
            initialView: initialView,

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: '' // custom buttons rendered above
            },

            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            },

            height: 'auto',
            contentHeight: 620,
            nowIndicator: true,
            slotDuration: '00:30:00',
            slotMinTime: '00:00:00',
            slotMaxTime: '24:00:00',
            displayEventEnd: true,
            events: calendarEvents,

            // Year view config (v6 native multiMonth)
            multiMonthMaxColumns: 4,
            multiMonthMinWidth: 200,
            fixedWeekCount: false,
            showNonCurrentDates: false,

            // viewDidMount replaces v3 viewRender
            viewDidMount: function(info) {
                syncFilterButtons(info.view.type);
            },

            // dateClick replaces v3 dayClick
            dateClick: function(info) {
                mainCalendar.gotoDate(info.date);
                mainCalendar.changeView('timeGridDay');
                syncFilterButtons('timeGridDay');
            },

            // eventClick — v6 passes info.event with .extendedProps
            eventClick: function(info) {
                info.jsEvent.preventDefault();

                const ev = info.event;
                const p = ev.extendedProps || {};

                const oid = p.orderId || '';
                const kindRaw = p.kind || '';
                const kind = kindRaw === 'pickup' ? 'Pickup' : 'Drop-off';
                const st = p.status;
                const customer = p.customer || '—';
                const service = p.service || '—';

                let stLabel = 'Pending';
                if (st === 1 || st === '1') stLabel = 'In progress';
                if (st === 2 || st === '2') stLabel = 'Completed';

                const startStr = ev.startStr || '';
                const endStr = ev.endStr || '';

                const modalEl = document.getElementById('calendarEventModal');
                modalEl.dataset.orderId = String(oid);

                document.getElementById('calendarEventModalTitle').textContent = ev.title || 'Booking';
                document.getElementById('calendarEventModalBody').innerHTML =
                    '<dl class="row mb-0">' +
                    '<dt class="col-sm-4">Order ID</dt><dd class="col-sm-8">#' + (oid || '—') + '</dd>' +
                    '<dt class="col-sm-4">Customer</dt><dd class="col-sm-8">' + customer + '</dd>' +
                    '<dt class="col-sm-4">Service</dt><dd class="col-sm-8">' + String(service).toUpperCase() + '</dd>' +
                    '<dt class="col-sm-4">Type</dt><dd class="col-sm-8">' + kind + '</dd>' +
                    '<dt class="col-sm-4">Status</dt><dd class="col-sm-8">' + stLabel + '</dd>' +
                    '<dt class="col-sm-4">Start</dt><dd class="col-sm-8">' + formatDt(startStr) + '</dd>' +
                    (endStr ? '<dt class="col-sm-4">End</dt><dd class="col-sm-8">' + formatDt(endStr) + '</dd>' : '') +
                    '</dl>' +
                    '<p class="text-muted small mb-0 mt-2">Tip: use <strong>Full order details</strong> for the same breakdown as Order Management.</p>';

                document.getElementById('calendarFetchOrderDetails').style.display = oid ? 'inline-block' : 'none';
                document.getElementById('calendarGoToOrder').style.display = oid ? 'inline-block' : 'none';

                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        });

        mainCalendar.render();

        // ── Mini Calendar ──────────────────────────────────────────────────────────
        const miniEl = document.getElementById('mini-calendar');
        let miniCalendar = null;

        function buildMiniCalendar(date) {
            if (miniCalendar) {
                miniCalendar.destroy();
            }

            miniCalendar = new FullCalendar.Calendar(miniEl, {
                initialView: 'dayGridMonth',
                initialDate: date || new Date(),

                headerToolbar: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },

                height: 'auto',
                contentHeight: 'auto',
                events: calendarEvents,

                // Hide event bars — we just want dot indicators via CSS class
                eventDisplay: 'none',

                // Mark days that have events for dot indicator CSS
                dayCellDidMount: function(info) {
                    const cellDateStr = info.date.toISOString().slice(0, 10);
                    const hasEvent = calendarEvents.some(function(ev) {
                        if (!ev.start) return false;
                        return ev.start.slice(0, 10) === cellDateStr;
                    });
                    if (hasEvent) {
                        info.el.classList.add('has-events');
                    }
                },

                dateClick: function(info) {
                    mainCalendar.gotoDate(info.date);
                    mainCalendar.changeView('timeGridDay');
                    syncFilterButtons('timeGridDay');
                }
            });

            miniCalendar.render();
        }

        buildMiniCalendar();

        // ── View Filter Dropdown ──────────────────────────────────────────────────
        document.querySelectorAll('.cal-filter-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const v = btn.dataset.calView;
                if (!v) return;
                mainCalendar.changeView(v);
                syncFilterButtons(v);
            });
        });

        // Set initial active button
        syncFilterButtons(initialView);

        // ── Full Order Details ─────────────────────────────────────────────────────
        document.getElementById('calendarFetchOrderDetails').addEventListener('click', function() {
            const modalEl = document.getElementById('calendarEventModal');
            const id = modalEl.dataset.orderId;
            const body = document.getElementById('calendarEventModalBody');
            if (!id) return;

            body.innerHTML = '<div class="text-center py-3 text-muted"><i class="fa fa-spinner fa-spin me-2"></i>Loading…</div>';

            fetch('<?= base_url('/order/getDetails/'); ?>' + id)
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    if (!data.success || !data.order) {
                        body.innerHTML = '<div class="alert alert-danger mb-0">Could not load order.</div>';
                        return;
                    }

                    const o = data.order;
                    let detailsRows = '';

                    try {
                        const detailsObj = JSON.parse(o.order_details_json);
                        Object.keys(detailsObj).forEach(function(key) {
                            const val = detailsObj[key];
                            detailsRows += '<tr><td class="fw-semibold">' + key + '</td><td>' + (val != null ? String(val) : '—') + '</td></tr>';
                        });
                    } catch (e) {
                        detailsRows = '<tr><td colspan="2">Details unavailable.</td></tr>';
                    }

                    const stHtml =
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
                        detailsRows +
                        '</tbody></table></div></div>' +
                        '</div>';
                })
                .catch(function() {
                    body.innerHTML = '<div class="alert alert-danger mb-0">Request failed.</div>';
                });
        });

        // ── View Order Button ─────────────────────────────────────────────────────
        document.getElementById('calendarGoToOrder').addEventListener('click', function() {
            const modalEl = document.getElementById('calendarEventModal');
            const id = modalEl.dataset.orderId;
            if (!id) return;

            // Navigate to order page with specific order ID
            window.location.href = '<?= base_url('/order'); ?>/' + id;
        });

    })();
</script>