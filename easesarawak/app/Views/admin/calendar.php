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
    .admin-booking-cal-wrap #admin-booking-calendar {
        min-height: 560px;
    }
    .cal-filter-btn.active {
        background-color: #1572e8 !important;
        color: #fff !important;
        border-color: #1572e8 !important;
    }
    .cal-legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 2px;
        display: inline-block;
        vertical-align: middle;
    }
</style>

<div class="container mt-4">
    <div class="page-inner" style="padding-top: 80px;">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1"><i class="far fa-calendar-alt me-2"></i>Booking calendar</h3>
                <p class="text-muted mb-0">Scheduled drop-offs and pickups from each order. Use the view filters or the toolbar to switch by month, week, or day.</p>
            </div>
            <a href="<?= base_url('/order'); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-list me-1"></i> Order list
            </a>
        </div>

        <div class="card shadow-sm border-0 rounded-3 admin-booking-cal-wrap">
            <div class="card-header bg-softblue text-white d-flex flex-wrap align-items-center justify-content-between gap-3 py-3">
                <div>
                    <h5 class="card-title mb-0 fw-semibold">Schedule</h5>
                    <!-- <small class="opacity-75">Colours follow order status: pending, in progress, completed</small> -->
                </div>
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <!-- <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="small text-white-50 text-nowrap">View</span>
                        <div class="btn-group btn-group-sm" role="group" aria-label="Calendar view filters">
                            <button type="button" class="btn btn-light btn-sm cal-filter-btn" data-cal-view="month">Month</button>
                            <button type="button" class="btn btn-light btn-sm cal-filter-btn" data-cal-view="agendaWeek">Week</button>
                            <button type="button" class="btn btn-light btn-sm cal-filter-btn" data-cal-view="agendaDay">Day</button>
                        </div>
                    </div> -->
                    <div class="d-flex align-items-center gap-2 small">
                        <span style="color: black;"><span class="cal-legend-dot" style="background:#ffc107;"></span> Pending</span>
                        <span style="color: black;"><span class="cal-legend-dot" style="background:#0d6efd;"></span> In progress</span>
                        <span style="color: black;"><span class="cal-legend-dot" style="background:#198754;"></span> Completed</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="admin-booking-calendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Summary modal (event click) -->
<div class="modal fade" id="calendarEventModal" tabindex="-1" aria-hidden="true" data-order-id="">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header bg-softblue text-white">
                <h5 class="modal-title" id="calendarEventModalTitle">Booking</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="calendarEventModalBody">
            </div>
            <div class="modal-footer border-0 flex-wrap gap-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <a href="<?= base_url('/order'); ?>" class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener">Order list</a>
                <button type="button" class="btn btn-primary btn-sm" id="calendarFetchOrderDetails" style="display:none;">
                    <i class="fas fa-info-circle me-1"></i> Full order details
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->include('admin/footer'); ?>

<script src="<?= base_url('assets/js/admin/plugin/moment/moment.min.js') ?>"></script>
<script src="<?= base_url('assets/js/admin/plugin/fullcalendar/fullcalendar.min.js') ?>"></script>

<script>
(function() {
    const calendarEvents = <?= $calendar_events_json ?>;
    const initialView = <?= json_encode($initial_view, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

    const $cal = $('#admin-booking-calendar');
    if (!$cal.length) return;

    // Admin controller passes v6-style view names; map to FullCalendar v3 views.
    const mappedInitialView =
        initialView === 'timeGridWeek' ? 'agendaWeek' :
        initialView === 'timeGridDay' ? 'agendaDay' :
        'month';

    function syncFilterButtons(viewType) {
        document.querySelectorAll('.cal-filter-btn').forEach(function(btn) {
            btn.classList.toggle('active', btn.getAttribute('data-cal-view') === viewType);
        });
    }

    function firstEventDateMoment() {
        if (!Array.isArray(calendarEvents) || calendarEvents.length === 0) return null;
        const sorted = calendarEvents
            .map(function(e) { return e && e.start ? moment(e.start) : null; })
            .filter(function(m) { return m && m.isValid(); })
            .sort(function(a, b) { return a.valueOf() - b.valueOf(); });
        return sorted.length ? sorted[0] : null;
    }

    function currentDateHasEvents(currentMoment) {
        if (!currentMoment || !currentMoment.isValid()) return false;
        return (calendarEvents || []).some(function(e) {
            if (!e || !e.start) return false;
            const sm = moment(e.start);
            return sm.isValid() && sm.isSame(currentMoment, 'day');
        });
    }

    $cal.fullCalendar({
        defaultView: mappedInitialView,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        height: 'auto',
        contentHeight: 560,
        nowIndicator: true,
        slotDuration: '00:30:00',
        // Keep full-day timeline so no bookings are hidden by time-window clipping.
        minTime: '00:00:00',
        maxTime: '24:00:00',
        displayEventEnd: true,
        events: calendarEvents,
        viewRender: function(view) {
            syncFilterButtons(view.name);
        },
        eventClick: function(event, jsEvent) {
            if (jsEvent && jsEvent.preventDefault) jsEvent.preventDefault();

            const p = (event.extendedProps || event.extendedProps === 0) ? event.extendedProps : (event.extendedProps || {});
            // Our events store metadata in extendedProps (from PHP). FullCalendar v3 keeps unknown keys at top-level.
            const oid = (p.orderId != null ? p.orderId : event.orderId);
            const kindRaw = (p.kind != null ? p.kind : event.kind);
            const kind = kindRaw === 'pickup' ? 'Pickup' : 'Drop-off';
            const st = (p.status != null ? p.status : event.status);
            const customer = (p.customer != null ? p.customer : event.customer);
            const service = (p.service != null ? p.service : event.service);

            let stLabel = 'Pending';
            if (st === 1) stLabel = 'In progress';
            if (st === 2) stLabel = 'Completed';

            const modalEl = document.getElementById('calendarEventModal');
            modalEl.dataset.orderId = String(oid || '');

            document.getElementById('calendarEventModalTitle').textContent = event.title || 'Booking';
            document.getElementById('calendarEventModalBody').innerHTML =
                '<dl class="row mb-0">' +
                '<dt class="col-sm-4">Order ID</dt><dd class="col-sm-8">#' + (oid || '-') + '</dd>' +
                '<dt class="col-sm-4">Customer</dt><dd class="col-sm-8">' + (customer || '—') + '</dd>' +
                '<dt class="col-sm-4">Service</dt><dd class="col-sm-8">' + (service ? String(service).toUpperCase() : '—') + '</dd>' +
                '<dt class="col-sm-4">Type</dt><dd class="col-sm-8">' + kind + '</dd>' +
                '<dt class="col-sm-4">Status</dt><dd class="col-sm-8">' + stLabel + '</dd>' +
                '<dt class="col-sm-4">Start</dt><dd class="col-sm-8">' + (event.start ? moment(event.start).format('YYYY-MM-DD HH:mm') : '—') + '</dd>' +
                (event.end ? '<dt class="col-sm-4">End</dt><dd class="col-sm-8">' + moment(event.end).format('YYYY-MM-DD HH:mm') + '</dd>' : '') +
                '</dl><p class="text-muted small mb-0 mt-2">Tip: use <strong>Full order details</strong> for the same breakdown as Order Management.</p>';

            document.getElementById('calendarFetchOrderDetails').style.display = oid ? 'inline-block' : 'none';

            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    });

    // Initial sync
    const currentView = $cal.fullCalendar('getView');
    if (currentView && currentView.name) syncFilterButtons(currentView.name);

    document.querySelectorAll('.cal-filter-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const v = btn.getAttribute('data-cal-view');
            if (!v) return;

            if (v === 'agendaDay') {
                const current = $cal.fullCalendar('getDate');
                if (!currentDateHasEvents(current)) {
                    const firstEvent = firstEventDateMoment();
                    if (firstEvent) {
                        $cal.fullCalendar('gotoDate', firstEvent);
                    }
                }
            }

            $cal.fullCalendar('changeView', v);
            syncFilterButtons(v);
        });
    });

    document.getElementById('calendarFetchOrderDetails').addEventListener('click', function() {
        const modalEl = document.getElementById('calendarEventModal');
        const id = modalEl.dataset.orderId;
        const body = document.getElementById('calendarEventModalBody');
        if (!id) return;

        body.innerHTML = '<div class="text-center py-3 text-muted"><i class="fa fa-spinner fa-spin me-2"></i>Loading…</div>';

        fetch('<?= base_url('/order/getDetails/'); ?>' + id)
            .then(function(r) { return r.json(); })
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

                const stHtml = o.status == 1 ? '<span class="badge bg-primary">In progress</span>' :
                    o.status == 2 ? '<span class="badge bg-success">Completed</span>' :
                    '<span class="badge bg-warning text-dark">Pending</span>';

                body.innerHTML =
                    '<div class="row g-3">' +
                    '<div class="col-md-6"><strong>Name</strong><br>' + (o.first_name || '') + ' ' + (o.last_name || '') + '</div>' +
                    '<div class="col-md-6"><strong>Phone</strong><br>' + (o.phone || '—') + '</div>' +
                    '<div class="col-md-6"><strong>Email</strong><br>' + (o.email || '—') + '</div>' +
                    '<div class="col-md-6"><strong>Status</strong><br>' + stHtml + '</div>' +
                    '<div class="col-12"><strong>Order details</strong>' +
                    '<div class="table-responsive mt-2"><table class="table table-sm table-bordered mb-0"><tbody>' + detailsRows + '</tbody></table></div></div>' +
                    '</div>';
            })
            .catch(function() {
                body.innerHTML = '<div class="alert alert-danger mb-0">Request failed.</div>';
            });
    });
})();
</script>
