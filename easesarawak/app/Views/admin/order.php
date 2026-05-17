<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/order.css') ?>">

<?php
$status    = $_GET['status'] ?? '';
$service   = $_GET['service_type'] ?? '';
$startDate = $_GET['start_date'] ?? '';
$endDate   = $_GET['end_date'] ?? '';

function ordSvcPill(string $type): string {
    $l         = strtolower($type);
    $isStorage = $l === 'storage';
    $cls       = $isStorage ? 'ord-svc-pill--storage' : 'ord-svc-pill--delivery';
    $icon      = $isStorage ? 'fa-warehouse' : 'fa-truck';
    $label     = htmlspecialchars($isStorage ? 'Storage' : 'Delivery');
    return '<span class="ord-svc-pill ' . $cls . '">'
        . '<span class="ord-svc-pill__ico" aria-hidden="true"><i class="fas ' . $icon . '"></i></span>'
        . $label
        . '</span>';
}

function ordAvColor(int $orderId): string {
    $palette = ['#5B532C', '#B8860B', '#0A0A0A', '#1A6CB0', '#2BA869', '#6A4FBB'];
    return $palette[$orderId % count($palette)];
}

function ordInitials(string $first, string $last): string {
    $name  = trim($first . ' ' . $last);
    $parts = preg_split('/\s+/', $name);
    $i     = strtoupper(substr($parts[0] ?? '', 0, 1));
    if (isset($parts[1])) {
        $i .= strtoupper(substr($parts[1], 0, 1));
    }
    return $i ?: '?';
}

function ordCleanDetailValue(string $v): string {
    $v = trim($v);
    return ($v === '' || strcasecmp($v, 'Null') === 0) ? '' : $v;
}

function ordExtractQueueTime(string $raw): string {
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

function ordQueueTimeForOrder(array $ord, bool $usePickup): string {
    $d     = @json_decode($ord['order_details_json'] ?? '{}', true);
    $d     = is_array($d) ? $d : [];
    $field = $usePickup ? 'Pickup DateTime' : 'Drop-off DateTime';
    $time  = ordExtractQueueTime(trim($d[$field] ?? ''));
    if ($time !== '') {
        return $time;
    }
    $fallback = $usePickup ? ($ord['_pickup_time'] ?? '') : ($ord['_dropoff_time'] ?? '');
    $time     = ordExtractQueueTime((string) $fallback);
    if ($time !== '') {
        return $time;
    }
    $legacy = $usePickup
        ? trim($d['pickupTime'] ?? '')
        : trim($d['dropoffTime'] ?? '');
    return ordExtractQueueTime($legacy) ?: (preg_match('/^\d{1,2}:\d{2}/', $legacy) ? $legacy : '');
}

function ordFormatQueueDateTime(string $raw): string {
    $raw = ordCleanDetailValue($raw);
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

function ordParseRoute(array $ord): array {
    $d   = @json_decode($ord['order_details_json'] ?? '{}', true);
    $d   = is_array($d) ? $d : [];
    $svc = strtolower($ord['service_type'] ?? '');

    if ($svc === 'storage') {
        $storageLoc = ordCleanDetailValue($d['Storage Location'] ?? '')
            ?: ordCleanDetailValue($ord['_pickup_location'] ?? '');
        $originLoc = ordCleanDetailValue($d['Origin Location'] ?? $d['Origin Address'] ?? '');
        $dropoffRaw = ordCleanDetailValue($d['Drop-off DateTime'] ?? $ord['_dropoff_time'] ?? '');
        $pickupRaw  = ordCleanDetailValue($d['Pickup DateTime'] ?? $ord['_pickup_time'] ?? '');
        $dropoffFmt = ordFormatQueueDateTime($dropoffRaw);
        $pickupFmt  = ordFormatQueueDateTime($pickupRaw);
        $duration   = ($dropoffFmt && $pickupFmt)
            ? $dropoffFmt . ' → ' . $pickupFmt
            : ($dropoffFmt ?: $pickupFmt);

        return [
            'is_storage' => true,
            'leg1_label' => 'From',
            'leg1_value' => $originLoc ?: $storageLoc ?: '—',
            'leg1_meta'  => $dropoffFmt !== '' ? 'Drop-off · ' . $dropoffFmt : '',
            'leg2_label' => 'Store',
            'leg2_value' => $storageLoc ?: '—',
            'leg2_meta'  => $duration !== '' ? $duration : '',
        ];
    }

    $from = ordCleanDetailValue($d['origin'] ?? $d['originAddress'] ?? '')
        ?: ordCleanDetailValue($ord['_pickup_location'] ?? '');
    $to   = ordCleanDetailValue($d['destination'] ?? $d['destinationAddress'] ?? '')
        ?: ordCleanDetailValue($ord['_dropoff_location'] ?? '');

    return [
        'is_storage' => false,
        'leg1_label' => 'From',
        'leg1_value' => $from ?: '—',
        'leg1_meta'  => '',
        'leg2_label' => 'To',
        'leg2_value' => $to ?: '—',
        'leg2_meta'  => '',
    ];
}

function ordScheduledTime(array $order): string {
    $t = ordQueueTimeForOrder($order, false);
    return $t !== '' ? $t : '—';
}

function ordStatusBadge(int $s, int $orderId): string {
    $map = [
        0 => ['ord-status--pending',  'Pending'],
        1 => ['ord-status--progress', 'In Progress'],
        2 => ['ord-status--done',     'Completed'],
    ];
    [$cls, $label] = $map[$s] ?? ['', 'Unknown'];
    return '<span class="ord-status-badge ' . $cls . '" data-status="' . $s . '" data-order="' . $orderId . '"'
        . ' onclick="ordCycleStatus(this)" title="Click to advance status" role="button" tabindex="0">'
        . htmlspecialchars($label) . '</span>';
}

function ordRowClass(int $status): string {
    return ['ord-row--pending', 'ord-row--progress', 'ord-row--done'][$status] ?? 'ord-row--pending';
}
?>

<div class="ord-page">

    <div class="ease-page-head d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="ease-crumb">EASE Admin &middot; <b>Orders</b></div>
            <h1 class="ease-page-title">Order Management</h1>
        </div>
    </div>

    <div class="ord-card">
        <div class="ord-card__bar">
            <div class="ord-srch">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search orders…" id="orderSearch" autocomplete="off">
            </div>

            <button class="ord-filter-btn" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                <i class="fas fa-sliders-h"></i> Filter
            </button>

            <span class="ord-count"><?= count($orders ?? []) ?> on this page</span>
        </div>

        <div class="table-responsive">
            <table class="ord-tbl" id="orderTable">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Service</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Route</th>
                        <th>Dropoff Time</th>
                        <th>Order Date</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <?php
                            $route    = ordParseRoute($order);
                            $oid      = (int) $order['order_id'];
                            $status   = (int) ($order['status'] ?? 0);
                            $name     = trim(($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? ''));
                            $avBg     = ordAvColor($oid);
                            $avFg     = ($avBg === '#0A0A0A') ? '#F2BE00' : '#fff';
                            $routeCol = !empty($route['is_storage']) ? 'ord-route-col--store' : 'ord-route-col--to';
                            ?>
                            <tr class="<?= esc(ordRowClass($status)) ?>">
                                <td>
                                    <span class="ord-id">#<?= esc($oid) ?></span>
                                </td>
                                <td><?= ordSvcPill($order['service_type'] ?? '') ?></td>
                                <td>
                                    <div class="ord-customer">
                                        <span class="ord-av" style="background:<?= esc($avBg) ?>;color:<?= esc($avFg) ?>">
                                            <?= esc(ordInitials($order['first_name'] ?? '', $order['last_name'] ?? '')) ?>
                                        </span>
                                        <span class="ord-customer__name"><?= esc($name) ?></span>
                                    </div>
                                </td>
                                <td><span class="ord-contact"><?= esc($order['phone'] ?? '—') ?></span></td>
                                <td>
                                    <div class="ord-route-wrap">
                                        <div class="ord-route-col">
                                            <span class="ord-route-col__lbl"><?= esc($route['leg1_label']) ?></span>
                                            <span class="ord-route-col__val" title="<?= esc($route['leg1_value'], 'attr') ?>">
                                                <?= esc($route['leg1_value']) ?>
                                            </span>
                                            <?php if (!empty($route['leg1_meta'])): ?>
                                                <span class="ord-route-col__meta"><?= esc($route['leg1_meta']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ord-route-col <?= esc($routeCol) ?>">
                                            <span class="ord-route-col__lbl"><?= esc($route['leg2_label']) ?></span>
                                            <span class="ord-route-col__val" title="<?= esc($route['leg2_value'], 'attr') ?>">
                                                <?= esc($route['leg2_value']) ?>
                                            </span>
                                            <?php if (!empty($route['leg2_meta'])): ?>
                                                <span class="ord-route-col__meta"><?= esc($route['leg2_meta']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="ord-eta"><?= esc(ordScheduledTime($order)) ?></span></td>
                                <td>
                                    <span class="ord-date">
                                        <?= date('d M Y', strtotime($order['created_date'])) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="ord-price">RM <?= number_format((float) ($order['amount'] ?? 0), 2) ?></span>
                                </td>
                                <td class="text-center ord-tbl__status">
                                    <?= ordStatusBadge($status, $oid) ?>
                                </td>
                                <td>
                                    <div class="ord-actions">
                                        <button type="button" class="ord-act-btn viewOrderBtn"
                                            data-id="<?= esc($oid) ?>"
                                            title="View details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="ord-act-btn btn-add-note"
                                            data-id="<?= esc($oid) ?>"
                                            data-note="<?= htmlspecialchars($order['comment'] ?? '', ENT_QUOTES) ?>"
                                            title="Add note">
                                            <i class="fas fa-sticky-note"></i>
                                        </button>
                                        <button type="button" class="ord-act-btn btn-activity-log"
                                            data-id="<?= esc($oid) ?>"
                                            title="Activity log">
                                            <i class="fas fa-history"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="ord-empty">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="ord-pagination d-flex justify-content-center">
            <?php if (!empty($pager)): ?>
                <?= $pager->links('group1', 'pagination') ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Modals (moved to document.body on load — see ordMountOverlays) -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header ord-modal-head">
                <h5 class="modal-title fw-semibold" id="orderModalLabel">
                    <i class="fas fa-file-alt me-2"></i>Order Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="orderDetailsContent" class="py-3 text-muted">
                    <i class="fas fa-spinner fa-spin me-2"></i>Loading...
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ── Add Note Modal ──────────────────────────────────── -->
<div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header ord-modal-head">
                <h5 class="modal-title" id="noteModalLabel">Add Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="noteForm">
                    <input type="hidden" name="order_id" id="noteOrderId">
                    <div class="form-group">
                        <label for="orderNote">Note</label>
                        <textarea class="form-control" id="orderNote" name="note" rows="4"
                            placeholder="Write your note here…"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="saveNoteBtn" class="btn btn-update">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- ── Activity Log Modal ─────────────────────────────── -->
<div class="modal fade" id="logModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header ord-modal-head">
                <h5 class="modal-title"><i class="fas fa-history me-2"></i>Activity Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="activityLogContent" class="text-center py-3 text-muted">
                    <i class="fas fa-spinner fa-spin me-2"></i>Loading logs…
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button class="btn btn-update" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ── Right Offcanvas Filter Panel ───────────────────── -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="filterOffcanvas">
    <div class="offcanvas-header ord-canvas-head d-flex justify-content-center align-items-center">
        <button type="button" class="btn btn-sm me-2" data-bs-dismiss="offcanvas">
            <i class="fas fa-arrow-left"></i>
        </button>
        <h5 class="offcanvas-title flex-grow-1 text-center fw-semibold">
            Filter Orders
        </h5>
    </div>

    <div class="offcanvas-body">
        <form method="GET" action="<?= esc(ease_route('order'), 'attr') ?>">

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="0" <?= ($status === "0" ? "selected" : "") ?>>Pending</option>
                    <option value="1" <?= ($status === "1" ? "selected" : "") ?>>In Progress</option>
                    <option value="2" <?= ($status === "2" ? "selected" : "") ?>>Completed</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Date Range</label>
                <div class="input-group">
                    <input type="date" name="start_date" class="form-control" value="<?= esc($startDate) ?>">
                    <input type="date" name="end_date"   class="form-control" value="<?= esc($endDate) ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Service Type</label>
                <select name="service_type" class="form-select">
                    <option value="">All</option>
                    <option value="storage"  <?= ($service === "storage")  ? "selected" : "" ?>>Storage</option>
                    <option value="delivery" <?= ($service === "delivery") ? "selected" : "" ?>>Delivery</option>
                </select>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-update" type="submit">
                    <i class="fas fa-check me-1"></i> Apply Filters
                </button>
                <a href="<?= esc(ease_route('order'), 'attr') ?>" class="btn btn-cancel">
                    <i class="fas fa-undo me-1"></i> Reset
                </a>
            </div>

        </form>
    </div>
</div>

<?= $this->include('admin/footer'); ?>

<script>
var ORD_ROUTES = <?= json_encode([
    'changeStatus' => ease_route('change_status') . '/',
    'activityLog'  => ease_route('order_activity_log') . '/',
    'orderDetails' => ease_route('order_get_details') . '/',
    'saveNote'     => ease_route('save_note'),
], JSON_THROW_ON_ERROR) ?>;

function ordMountOverlays() {
    ['orderModal', 'noteModal', 'logModal', 'filterOffcanvas'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el && el.parentNode !== document.body) {
            document.body.appendChild(el);
        }
    });
}

function ordBsModal(id) {
    return bootstrap.Modal.getOrCreateInstance(document.getElementById(id));
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', ordMountOverlays);
} else {
    ordMountOverlays();
}

['orderModal', 'noteModal', 'logModal'].forEach(function(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.addEventListener('hidden.bs.modal', function() {
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('padding-right');
        document.body.style.removeProperty('overflow');
        document.querySelectorAll('.modal-backdrop').forEach(function(b) { b.remove(); });
    });
});

var ORD_STATUS_LABELS  = ['Pending', 'In Progress', 'Completed'];
var ORD_STATUS_CLASSES = ['ord-status--pending', 'ord-status--progress', 'ord-status--done'];
var ORD_ROW_CLASSES    = ['ord-row--pending', 'ord-row--progress', 'ord-row--done'];

function ordCycleStatus(el) {
    var orderId    = el.dataset.order;
    var cur        = parseInt(el.dataset.status, 10);
    var nxtPreview = (cur + 1) % 3;
    swal({
        title: 'Change order status?',
        text:  'Order #' + orderId + '\n' + ORD_STATUS_LABELS[cur] + ' → ' + ORD_STATUS_LABELS[nxtPreview],
        icon:  'warning',
        buttons: ['Cancel', 'Yes, change it'],
        dangerMode: true
    }).then(function(ok) {
        if (!ok) return;
        fetch(ORD_ROUTES.changeStatus + orderId, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                var nxt = data.new_status;
                el.dataset.status = nxt;
                el.textContent    = ORD_STATUS_LABELS[nxt];
                el.className      = 'ord-status-badge ' + ORD_STATUS_CLASSES[nxt];
                var row = el.closest('tr');
                if (row) {
                    row.classList.remove('ord-row--pending', 'ord-row--progress', 'ord-row--done');
                    row.classList.add(ORD_ROW_CLASSES[nxt]);
                }
                swal({
                    title: 'Updated!',
                    text: 'Order #' + orderId + ' is now ' + ORD_STATUS_LABELS[nxt] + '.',
                    icon: 'success',
                    button: 'OK'
                });
            } else {
                swal('Error', data.message || 'Could not update status.', 'error');
            }
        })
        .catch(function() { swal('Error', 'Network error.', 'error'); });
    });
}

document.querySelectorAll('.btn-activity-log').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var orderId    = this.dataset.id;
        var modal      = ordBsModal('logModal');
        var contentDiv = document.getElementById('activityLogContent');
        contentDiv.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading activity log…</div>';
        modal.show();
        fetch(ORD_ROUTES.activityLog + orderId)
            .then(function(r) { return r.text(); })
            .then(function(d) { contentDiv.innerHTML = d; })
            .catch(function() {
                contentDiv.innerHTML = '<div class="alert alert-danger">Failed to load logs.</div>';
            });
    });
});

document.getElementById('orderSearch').addEventListener('keyup', function() {
    var filter = this.value.toLowerCase();
    document.querySelectorAll('#orderTable tbody tr').forEach(function(row) {
        if (row.querySelector('.ord-empty')) return;
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
});

document.querySelectorAll('.viewOrderBtn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var orderId    = this.dataset.id;
        var modal      = ordBsModal('orderModal');
        var contentDiv = document.getElementById('orderDetailsContent');
        contentDiv.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading order details…</div>';
        modal.show();
        fetch(ORD_ROUTES.orderDetails + orderId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    var o = data.order;
                    var detailsObj = JSON.parse(o.order_details_json);
                    var tableRows = '';
                    Object.entries(detailsObj).forEach(function(entry) {
                        var key = entry[0];
                        var value = entry[1];
                        var prettyKey = key.replace(/([A-Z])/g, ' $1').replace(/^./, function(s) { return s.toUpperCase(); });
                        tableRows += '<tr><td class="fw-semibold">' + prettyKey + '</td><td>' + (value || '-') + '</td></tr>';
                    });
                    var statusIdx = parseInt(o.status, 10);
                    var statusBadge = '<span class="ord-status-badge ' + ORD_STATUS_CLASSES[statusIdx] + '">'
                        + ORD_STATUS_LABELS[statusIdx] + '</span>';
                    var socialMap = { 1: 'WhatsApp', 2: 'WeChat', 3: 'LINE' };
                    var uploadBase = <?= json_encode(ease_path('uploads')) ?>;
                    contentDiv.innerHTML =
                    '<div class="container-fluid">' +
                        '<div class="card border-0 shadow-sm mb-3 rounded-3">' +
                            '<div class="card-header fw-semibold" style="background:#F2BE00;">Customer Information</div>' +
                            '<div class="card-body"><div class="row g-3">' +
                                '<div class="col-md-6">' +
                                    '<p><strong>First Name:</strong> ' + o.first_name + '</p>' +
                                    '<p><strong>Last Name:</strong> ' + o.last_name + '</p>' +
                                    '<p><strong>Email:</strong> ' + o.email + '</p>' +
                                    '<p><strong>Phone:</strong> ' + o.phone + '</p>' +
                                '</div>' +
                                '<div class="col-md-6">' +
                                    '<p><strong>ID Number:</strong> ' + o.id_num + '</p>' +
                                    '<p><strong>Social:</strong> ' + (socialMap[o.social] || 'Unknown') + '</p>' +
                                    '<p><strong>Social Number:</strong> ' + o.social_num + '</p>' +
                                '</div>' +
                            '</div></div></div>' +
                        '<div class="card border-0 shadow-sm mb-3 rounded-3">' +
                            '<div class="card-header fw-semibold" style="background:#F2BE00;">Order Information</div>' +
                            '<div class="card-body"><div class="row g-3">' +
                                '<div class="col-md-6">' +
                                    '<p><strong>Service Type:</strong> ' + o.service_type + '</p>' +
                                    '<p><strong>Special:</strong> ' + o.special + '</p>' +
                                    '<p><strong>Special Note:</strong> ' + (o.special_note || '-') + '</p>' +
                                    '<p><strong>Promo Code:</strong> ' + (o.promo_code || '-') + '</p>' +
                                    '<p><strong>Last Modified:</strong> ' + (o.modified_date || '-') + '</p>' +
                                '</div>' +
                                '<div class="col-md-6">' +
                                    '<p><strong>Status:</strong> ' + statusBadge + '</p>' +
                                    '<p><strong>Amount:</strong> RM' + o.amount + '</p>' +
                                    '<p><strong>Payment Method:</strong> ' + o.payment_method + '</p>' +
                                    '<p><strong>Upload:</strong> ' + (o.upload
                                        ? '<a href="' + uploadBase + '/' + o.upload + '" target="_blank">View File</a>'
                                        : 'No file uploaded') + '</p>' +
                                    '<p><strong>Modified By:</strong> ' + (o.modified_by_username || '-') + '</p>' +
                                '</div>' +
                            '</div></div></div>' +
                        '<div class="card border-0 shadow-sm mb-3 rounded-3">' +
                            '<div class="card-header fw-semibold" style="background:#F2BE00;">Order Details</div>' +
                            '<div class="card-body">' +
                                '<table class="table table-bordered table-sm"><tbody>' + tableRows + '</tbody></table>' +
                            '</div></div>' +
                        '<div class="card border-0 shadow-sm rounded-3">' +
                            '<div class="card-header fw-semibold" style="background:#F2BE00;">Comment</div>' +
                            '<div class="card-body">' +
                                '<p class="bg-light p-3 rounded" style="font-size:.9rem;white-space:pre-wrap;">' + (o.comment || '-') + '</p>' +
                            '</div></div>' +
                    '</div>';
                } else {
                    contentDiv.innerHTML = '<div class="text-danger text-center py-4">' + data.message + '</div>';
                }
            })
            .catch(function() {
                contentDiv.innerHTML = '<div class="text-danger text-center py-4">Error loading order details.</div>';
            });
    });
});

$(document).ready(function() {
    $('.btn-add-note').on('click', function() {
        $('#noteOrderId').val($(this).data('id'));
        $('#orderNote').val($(this).data('note'));
        ordBsModal('noteModal').show();
    });

    $('#saveNoteBtn').on('click', function() {
        $.ajax({
            url: ORD_ROUTES.saveNote,
            type: 'POST',
            data: { order_id: $('#noteOrderId').val(), note: $('#orderNote').val() },
            success: function() {
                ordBsModal('noteModal').hide();
                swal({
                    title: 'Note Saved!',
                    text: 'Your note has been successfully saved.',
                    icon: 'success',
                    timer: 1500,
                    buttons: false
                });
            },
            error: function() {
                swal({ title: 'Error', text: 'Unable to save note. Please try again.', icon: 'error' });
            }
        });
    });
});

<?php if ($popup = session()->getFlashdata('order_status_success')): ?>
swal({
    title: 'Updated',
    text: 'Order status changed to <?= esc($popup['status']) ?>\nChanged by: <?= esc($popup['username']) ?>',
    icon: 'success',
    button: 'OK'
});
<?php endif; ?>
</script>
