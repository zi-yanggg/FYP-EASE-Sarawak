<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/order.css') ?>">

<?php
$request   = service('request');
$status    = (string) ($request->getGet('status') ?? '');
$service   = (string) ($request->getGet('service_type') ?? '');
$startDate = (string) ($request->getGet('start_date') ?? '');
$endDate   = (string) ($request->getGet('end_date') ?? '');
$hasActiveFilters = $status !== '' || $service !== '' || $startDate !== '' || $endDate !== '';

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
        $isCamel = isset($d['storageLocation']);
        if ($isCamel) {
            $storageLoc = ordCleanDetailValue($d['storageLocation'] ?? '');
            $dropoffRaw = (isset($d['dropoffDate']) && isset($d['dropoffTime']))
                ? $d['dropoffDate'] . ' at ' . $d['dropoffTime'] : '';
            $pickupRaw  = (isset($d['pickupDate']) && isset($d['pickupTime']))
                ? $d['pickupDate'] . ' at ' . $d['pickupTime'] : '';
            $originLoc  = ordCleanDetailValue($d['origin'] ?? $d['originAddress'] ?? '');
        } else {
            $storageLoc = ordCleanDetailValue($d['Storage Location'] ?? '')
                ?: ordCleanDetailValue($ord['_pickup_location'] ?? '');
            $dropoffRaw = ordCleanDetailValue($d['Drop-off DateTime'] ?? $ord['_dropoff_time'] ?? '');
            $pickupRaw  = ordCleanDetailValue($d['Pickup DateTime'] ?? $ord['_pickup_time'] ?? '');
            $originLoc  = ordCleanDetailValue($d['Origin Location'] ?? $d['Origin Address'] ?? '');
        }
        $dropoffFmt = ordFormatQueueDateTime($dropoffRaw);
        $pickupFmt  = ordFormatQueueDateTime($pickupRaw);

        if ($originLoc === '') {
            return [
                'is_storage'  => true,
                'is_self_svc' => true,
                'leg1_label'  => 'Store',
                'leg1_value'  => $storageLoc ?: '—',
                'leg1_meta'   => $dropoffFmt !== '' ? 'Drop-off · ' . $dropoffFmt : '',
                'leg2_label'  => 'Pickup',
                'leg2_value'  => $pickupFmt ?: '—',
                'leg2_meta'   => 'By owner',
            ];
        }

        $duration = ($dropoffFmt && $pickupFmt)
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

    // Support both camelCase (new) and Title Case (legacy) delivery JSON formats
    $from = ordCleanDetailValue(
        $d['origin']           ?? $d['originAddress']      ??
        $d['Origin Location']  ?? $d['Origin Address']     ?? ''
    ) ?: ordCleanDetailValue($ord['_pickup_location'] ?? '');
    $to   = ordCleanDetailValue(
        $d['destination']          ?? $d['destinationAddress']     ??
        $d['Destination Location'] ?? $d['Destination Address']    ?? ''
    ) ?: ordCleanDetailValue($ord['_dropoff_location'] ?? '');

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

            <button class="ord-btn-gold<?= $hasActiveFilters ? ' ord-filter-active' : '' ?>" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                <i class="fas fa-sliders-h"></i> Filter
            </button>

        </div>

        <div class="table-responsive">
            <table class="ord-tbl" id="orderTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service</th>
                        <th>Customer</th>
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
                            $routeCol = !empty($route['is_storage'])
                                ? (!empty($route['is_self_svc']) ? 'ord-route-col--store ord-route-col--self' : 'ord-route-col--store')
                                : 'ord-route-col--to';
                            ?>
                            <tr class="<?= esc(ordRowClass($status)) ?>">
                                <td>
                                    <span class="ord-id">#<?= esc($oid) ?></span>
                                </td>
                                <td><?= ordSvcPill($order['service_type'] ?? '') ?></td>
                                <td>
                                    <div class="ord-customer">
                                        <span class="ord-customer__name"><?= esc($name) ?></span>
                                        <span class="ord-customer__phone"><?= esc($order['phone'] ?? '—') ?></span>
                                    </div>
                                </td>
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
                                    <div class="ord-date-wrap">
                                        <span class="ord-date"><?= date('d M Y', strtotime($order['created_date'])) ?></span>
                                        <span class="ord-time"><?= date('H:i', strtotime($order['created_date'])) ?></span>
                                    </div>
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
                            <td colspan="9" class="ord-empty">No orders found.</td>
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
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content ord-modal-content">
            <div class="modal-header ord-modal-head">
                <h5 class="modal-title" id="orderModalLabel">
                    <i class="fas fa-file-alt me-2"></i>Order Details
                </h5>
                <button type="button" class="ease-modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="orderDetailsContent">
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-spinner fa-spin me-2"></i>Loading…
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── Add Note Modal ──────────────────────────────────── -->
<div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content ord-modal-content">
            <div class="modal-header ord-modal-head">
                <h5 class="modal-title" id="noteModalLabel">
                    <i class="fas fa-sticky-note me-2"></i>Add Note
                </h5>
                <button type="button" class="ease-modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="noteForm">
                    <input type="hidden" name="order_id" id="noteOrderId">
                    <div id="existingNoteBlock" class="ord-existing-note" style="display:none">
                        <div class="ord-note-label">Current Note</div>
                        <div id="existingNoteText" class="ord-existing-note__text"></div>
                    </div>
                    <label class="ord-note-label" id="noteFieldLabel" for="orderNote">Note</label>
                    <textarea class="ord-note-area" id="orderNote" name="note" rows="3"
                        maxlength="500" placeholder="Write your note here…"></textarea>
                    <div class="ord-char-counter"><span id="noteCharCount">0</span> / 500</div>
                </form>
            </div>
            <div class="modal-footer ord-modal-foot">
                <button type="button" id="saveNoteBtn" class="ord-btn-gold">
                    <i class="fas fa-check"></i>Save
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ── Activity Log Modal ─────────────────────────────── -->
<div class="modal fade" id="logModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content ord-modal-content">
            <div class="modal-header ord-modal-head">
                <h5 class="modal-title"><i class="fas fa-history me-2"></i>Activity Log</h5>
                <button type="button" class="ease-modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="activityLogContent">
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-spinner fa-spin me-2"></i>Loading logs…
                    </div>
                </div>
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
                    <option value="0" <?= $status === "0" ? "selected" : "" ?>>Pending</option>
                    <option value="1" <?= $status === "1" ? "selected" : "" ?>>In Progress</option>
                    <option value="2" <?= $status === "2" ? "selected" : "" ?>>Completed</option>
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
                <button class="ord-btn-gold w-100 justify-content-center" type="submit">
                    <i class="fas fa-check"></i> Apply Filters
                </button>
                <a href="<?= esc(ease_route('order'), 'attr') ?>" class="ord-btn-ghost w-100 justify-content-center" style="text-decoration:none;">
                    <i class="fas fa-undo"></i> Reset
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
    'csrfHeader'   => config('Security')->headerName,
    'csrfCookie'   => config('Security')->cookieName,
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

function ordGetCsrfToken() {
    var match = document.cookie.match(new RegExp('(?:^|;\\s*)' + ORD_ROUTES.csrfCookie + '=([^;]+)'));
    return match ? decodeURIComponent(match[1]) : '';
}

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
        var headers = { 'X-Requested-With': 'XMLHttpRequest' };
        headers[ORD_ROUTES.csrfHeader] = ordGetCsrfToken();
        fetch(ORD_ROUTES.changeStatus + orderId, {
            method: 'POST',
            headers: headers
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

function escHtml(s) {
    if (s == null) return '';
    return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

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
                    var detailRows = '';
                    Object.entries(detailsObj).forEach(function(entry) {
                        var key = entry[0];
                        var value = entry[1];
                        var prettyKey = escHtml(key.replace(/([A-Z])/g, ' $1').replace(/^./, function(s) { return s.toUpperCase(); }));
                        detailRows += '<div class="ord-kv"><div class="ord-kv__k">' + prettyKey + '</div><div class="ord-kv__v">' + escHtml(value || '—') + '</div></div>';
                    });
                    var statusIdx = parseInt(o.status, 10);
                    var statusBadge = '<span class="ord-status-badge ' + ORD_STATUS_CLASSES[statusIdx] + '">'
                        + ORD_STATUS_LABELS[statusIdx] + '</span>';
                    var socialMap = { 1: 'WhatsApp', 2: 'WeChat', 3: 'LINE' };
                    var uploadBase = <?= json_encode(ease_path('uploads')) ?>;
                    contentDiv.innerHTML =
                    '<div class="ord-detail-wrap">' +
                        '<div class="ord-detail-section">' +
                            '<div class="ord-detail-section__head">Customer Information</div>' +
                            '<div class="ord-detail-section__body">' +
                                '<div class="ord-kv-grid">' +
                                    '<div class="ord-kv"><div class="ord-kv__k">First Name</div><div class="ord-kv__v">' + escHtml(o.first_name) + '</div></div>' +
                                    '<div class="ord-kv"><div class="ord-kv__k">Last Name</div><div class="ord-kv__v">' + escHtml(o.last_name) + '</div></div>' +
                                    '<div class="ord-kv"><div class="ord-kv__k">Email</div><div class="ord-kv__v">' + escHtml(o.email) + '</div></div>' +
                                    '<div class="ord-kv"><div class="ord-kv__k">Phone</div><div class="ord-kv__v">' + escHtml(o.phone) + '</div></div>' +
                                    '<div class="ord-kv"><div class="ord-kv__k">ID Number</div><div class="ord-kv__v">' + escHtml(o.id_num) + '</div></div>' +
                                    '<div class="ord-kv"><div class="ord-kv__k">Social</div><div class="ord-kv__v">' + escHtml(socialMap[o.social] || 'Unknown') + (o.social_num ? ' · ' + escHtml(o.social_num) : '') + '</div></div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="ord-detail-section">' +
                            '<div class="ord-detail-section__head">Order Information</div>' +
                            '<div class="ord-detail-section__body">' +
                                '<div class="ord-kv-grid">' +
                                    '<div class="ord-kv"><div class="ord-kv__k">Service Type</div><div class="ord-kv__v">' + escHtml(o.service_type) + '</div></div>' +
                                    '<div class="ord-kv"><div class="ord-kv__k">Status</div><div class="ord-kv__v">' + statusBadge + '</div></div>' +
                                    '<div class="ord-kv"><div class="ord-kv__k">Amount</div><div class="ord-kv__v ord-kv__v--price">RM ' + parseFloat(o.amount).toFixed(2) + '</div></div>' +
                                    '<div class="ord-kv"><div class="ord-kv__k">Payment Method</div><div class="ord-kv__v">' + escHtml(o.payment_method || '—') + '</div></div>' +
                                    '<div class="ord-kv"><div class="ord-kv__k">Special</div><div class="ord-kv__v">' + escHtml(o.special || '—') + '</div></div>' +
                                    '<div class="ord-kv"><div class="ord-kv__k">Promo Code</div><div class="ord-kv__v">' + escHtml(o.promo_code || '—') + '</div></div>' +
                                    '<div class="ord-kv"><div class="ord-kv__k">Upload</div><div class="ord-kv__v">' + (o.upload ? '<a href="' + uploadBase + '/' + encodeURIComponent(o.upload) + '" target="_blank" class="ord-kv__link">View File</a>' : '—') + '</div></div>' +
                                    '<div class="ord-kv"><div class="ord-kv__k">Modified By</div><div class="ord-kv__v">' + escHtml(o.modified_by_username || '—') + '</div></div>' +
                                    '<div class="ord-kv"><div class="ord-kv__k">Last Modified</div><div class="ord-kv__v">' + escHtml(o.modified_date || '—') + '</div></div>' +
                                '</div>' +
                                (o.special_note ? '<div class="ord-kv ord-kv--full" style="margin-top:12px"><div class="ord-kv__k">Special Note</div><div class="ord-kv__v">' + escHtml(o.special_note) + '</div></div>' : '') +
                            '</div>' +
                        '</div>' +
                        '<div class="ord-detail-section">' +
                            '<div class="ord-detail-section__head">Order Details</div>' +
                            '<div class="ord-detail-section__body">' +
                                '<div class="ord-kv-grid">' + detailRows + '</div>' +
                            '</div>' +
                        '</div>' +
                        (o.comment ?
                        '<div class="ord-detail-section">' +
                            '<div class="ord-detail-section__head">Admin Note</div>' +
                            '<div class="ord-detail-section__body">' +
                                '<div class="ord-note-display">' + escHtml(o.comment) + '</div>' +
                            '</div>' +
                        '</div>' : '') +
                    '</div>';
                } else {
                    contentDiv.innerHTML = '<div class="text-danger text-center py-4">' + escHtml(data.message) + '</div>';
                }
            })
            .catch(function() {
                contentDiv.innerHTML = '<div class="text-danger text-center py-4">Error loading order details.</div>';
            });
    });
});

$(document).ready(function() {
    $('.btn-add-note').on('click', function() {
        var existingNote = $(this).data('note') || '';
        $('#noteOrderId').val($(this).data('id'));
        $('#orderNote').val(existingNote);
        $('#noteCharCount').text(existingNote.length);
        $('.ord-char-counter').removeClass('ord-char-warn ord-char-limit');
        if (existingNote.trim() !== '') {
            $('#existingNoteText').text(existingNote);
            $('#existingNoteBlock').show();
            $('#noteFieldLabel').text('Edit Note');
        } else {
            $('#existingNoteBlock').hide();
            $('#noteFieldLabel').text('Note');
        }
        ordBsModal('noteModal').show();
    });

    $('#orderNote').on('input', function() {
        var len = $(this).val().length;
        var $ctr = $('#noteCharCount').text(len).closest('.ord-char-counter');
        $ctr.toggleClass('ord-char-warn',  len >= 450 && len < 500);
        $ctr.toggleClass('ord-char-limit', len >= 500);
    });

    $('#saveNoteBtn').on('click', function() {
        var ordId   = $('#noteOrderId').val();
        var newNote = $('#orderNote').val().trim();
        $.ajax({
            url: ORD_ROUTES.saveNote,
            type: 'POST',
            data: { order_id: ordId, note: newNote },
            success: function(data) {
                $('.btn-add-note[data-id="' + ordId + '"]').data('note', newNote);
                ordBsModal('noteModal').hide();
                var titles = { added: 'Note Added!', edited: 'Note Updated!', deleted: 'Note Deleted!' };
                var texts  = {
                    added:   'A note has been added to this order.',
                    edited:  'The note has been updated.',
                    deleted: 'The note has been removed from this order.'
                };
                var act = data.action || 'edited';
                swal({
                    title: titles[act] || 'Done!',
                    text:  texts[act]  || '',
                    icon:  act === 'deleted' ? 'info' : 'success',
                    timer: 1800,
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
    text: 'Order status changed to <?= esc($popup['status'], 'js') ?>\nChanged by: <?= esc($popup['username'], 'js') ?>',
    icon: 'success',
    button: 'OK'
});
<?php endif; ?>
</script>
