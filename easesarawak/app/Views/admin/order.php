<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/order.css') ?>">

<?php
$status    = $_GET['status'] ?? '';
$service   = $_GET['service_type'] ?? '';
$startDate = $_GET['start_date'] ?? '';
$endDate   = $_GET['end_date'] ?? '';

function ordParseRoute(array $order): array {
    $d    = @json_decode($order['order_details_json'] ?? '{}', true);
    $d    = is_array($d) ? $d : [];
    $from = trim($d['origin'] ?? ($d['originAddress'] ?? ''));
    $to   = trim($d['destination'] ?? ($d['destinationAddress'] ?? ''));
    $dropoffTime = trim($d['dropoffTime'] ?? '—');
    return ['from' => $from ?: '—', 'to' => $to ?: '—', 'dropoff' => $dropoffTime];
}

function ordInitials(string $first, string $last): string {
    return strtoupper(substr($first, 0, 1) . substr($last, 0, 1)) ?: '?';
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
        <!-- Bar: search + filter button + count -->
        <div class="ord-card__bar">
            <div class="ord-srch">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search orders…" id="orderSearch">
            </div>

            <button class="ord-filter-btn" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                <i class="fas fa-sliders-h"></i> Filter
            </button>

            <span class="ord-count"><?= count($orders ?? []) ?> on this page</span>
        </div>

        <!-- Table -->
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
                            $route      = ordParseRoute($order);
                            $initials   = ordInitials($order['first_name'] ?? '', $order['last_name'] ?? '');
                            $svcType    = strtolower($order['service_type'] ?? '');
                            $statusMap  = [0 => 'Pending', 1 => 'In Progress', 2 => 'Completed'];
                            $stClass    = $order['status'] == 0 ? 'pending' : ($order['status'] == 1 ? 'progress' : 'done');
                            $nextStatus = ($order['status'] + 1) % 3;
                            ?>
                            <tr>
                                <td>
                                    <span class="ord-id"><?= esc($order['order_id']) ?></span>
                                </td>
                                <td>
                                    <span class="ord-svc ord-svc--<?= esc($svcType) ?>">
                                        <i class="fas fa-<?= $svcType === 'delivery' ? 'truck' : 'warehouse' ?>"></i>
                                        <?= esc(ucfirst($svcType)) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="ord-av"><?= esc($initials) ?></span>
                                        <span style="font-weight:700;font-size:12.5px;">
                                            <?= esc($order['first_name']) ?> <?= esc($order['last_name']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td style="font-size:12px;color:#555;"><?= esc($order['phone']) ?></td>
                                <td>
                                    <span class="ord-route">
                                        <?= esc($route['from']) ?>
                                        <span class="ord-route-arrow">→</span>
                                        <?= esc($route['to']) ?>
                                    </span>
                                </td>
                                <td><span class="ord-eta"><?= esc($route['dropoff']) ?></span></td>
                                <td>
                                    <span class="ord-date">
                                        <?= date('d M Y', strtotime($order['created_date'])) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="ord-price">RM <?= number_format((float)($order['amount'] ?? 0), 2) ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('/change_status/' . $order['order_id']) ?>"
                                       class="ord-st ord-st--<?= esc($stClass) ?> change-status-btn"
                                       data-order-id="<?= esc($order['order_id']) ?>"
                                       data-current-status="<?= esc($statusMap[$order['status']]) ?>"
                                       data-next-status="<?= esc($statusMap[$nextStatus]) ?>">
                                        <?= esc($statusMap[$order['status']]) ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <button type="button" class="ord-act-btn viewOrderBtn"
                                            data-id="<?= esc($order['order_id']) ?>"
                                            title="View details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="ord-act-btn btn-add-note"
                                            data-id="<?= esc($order['order_id']) ?>"
                                            data-note="<?= htmlspecialchars($order['comment'] ?? '', ENT_QUOTES) ?>"
                                            title="Add note">
                                            <i class="fas fa-sticky-note"></i>
                                        </button>
                                        <button type="button" class="ord-act-btn btn-activity-log"
                                            data-id="<?= esc($order['order_id']) ?>"
                                            title="Activity log">
                                            <i class="fas fa-history"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center py-4" style="color:#6B7280;">
                                No orders found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3 mb-3">
            <?php if (!empty($pager)): ?>
                <?= $pager->links('group1', 'pagination') ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- ── Order Details Modal ──────────────────────────────── -->
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
        <form method="GET" action="<?= base_url('order') ?>">

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
                <a href="<?= base_url('order') ?>" class="btn btn-cancel">
                    <i class="fas fa-undo me-1"></i> Reset
                </a>
            </div>

        </form>
    </div>
</div>

<?= $this->include('admin/footer'); ?>

<script>
/* ── Status change (swal confirm) ─────────────────────── */
document.querySelectorAll('.change-status-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const url           = this.getAttribute('href');
        const orderId       = this.dataset.orderId;
        const currentStatus = this.dataset.currentStatus;
        const nextStatus    = this.dataset.nextStatus;
        swal({
            title: "Change order status?",
            text:  "Order ID: " + orderId +
                   "\nCurrent status: " + currentStatus +
                   "\nNew status: " + nextStatus,
            icon: "warning",
            buttons: ["Cancel", "Yes, change it"],
            dangerMode: true
        }).then(function(willChange) {
            if (willChange) window.location.href = url;
        });
    });
});

/* ── Activity log modal ───────────────────────────────── */
document.querySelectorAll('.btn-activity-log').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const orderId    = this.dataset.id;
        const modal      = new bootstrap.Modal(document.getElementById('logModal'));
        const contentDiv = document.getElementById('activityLogContent');
        contentDiv.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading activity log…</div>';
        modal.show();
        fetch("<?= base_url('/order_activity_log/') ?>" + orderId)
            .then(r => r.text())
            .then(d => { contentDiv.innerHTML = d; })
            .catch(function() { contentDiv.innerHTML = '<div class="alert alert-danger">Failed to load logs.</div>'; });
    });
});

/* ── Live search ──────────────────────────────────────── */
document.getElementById('orderSearch').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    document.querySelectorAll('#orderTable tbody tr').forEach(function(row) {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
});

/* ── View order details modal ─────────────────────────── */
document.querySelectorAll('.viewOrderBtn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const orderId    = this.dataset.id;
        const modal      = new bootstrap.Modal(document.getElementById('orderModal'));
        const contentDiv = document.getElementById('orderDetailsContent');
        contentDiv.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading order details…</div>';
        modal.show();
        fetch("<?= base_url('/order/getDetails') ?>/" + orderId)
            .then(r => r.json())
            .then(function(data) {
                if (data.success) {
                    const o = data.order;
                    const detailsObj = JSON.parse(o.order_details_json);
                    let tableRows = '';
                    Object.entries(detailsObj).forEach(function([key, value]) {
                        const prettyKey = key.replace(/([A-Z])/g, ' $1').replace(/^./, s => s.toUpperCase());
                        tableRows += '<tr><td class="fw-semibold">' + prettyKey + '</td><td>' + (value || '-') + '</td></tr>';
                    });
                    const statusBadge = o.status == 0
                        ? '<span class="ord-st ord-st--pending">Pending</span>'
                        : o.status == 1
                        ? '<span class="ord-st ord-st--progress">In Progress</span>'
                        : '<span class="ord-st ord-st--done">Completed</span>';
                    const socialMap = { 1: 'WhatsApp', 2: 'WeChat', 3: 'LINE' };
                    contentDiv.innerHTML = `
                    <div class="container-fluid">
                        <div class="card border-0 shadow-sm mb-3 rounded-3">
                            <div class="card-header fw-semibold" style="background:#F2BE00;">Customer Information</div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <p><strong>First Name:</strong> ${o.first_name}</p>
                                        <p><strong>Last Name:</strong> ${o.last_name}</p>
                                        <p><strong>Email:</strong> ${o.email}</p>
                                        <p><strong>Phone:</strong> ${o.phone}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>ID Number:</strong> ${o.id_num}</p>
                                        <p><strong>Social:</strong> ${socialMap[o.social] || 'Unknown'}</p>
                                        <p><strong>Social Number:</strong> ${o.social_num}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card border-0 shadow-sm mb-3 rounded-3">
                            <div class="card-header fw-semibold" style="background:#F2BE00;">Order Information</div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <p><strong>Service Type:</strong> ${o.service_type}</p>
                                        <p><strong>Special:</strong> ${o.special}</p>
                                        <p><strong>Special Note:</strong> ${o.special_note || '-'}</p>
                                        <p><strong>Promo Code:</strong> ${o.promo_code || '-'}</p>
                                        <p><strong>Last Modified:</strong> ${o.modified_date || '-'}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status:</strong> ${statusBadge}</p>
                                        <p><strong>Amount:</strong> RM${o.amount}</p>
                                        <p><strong>Payment Method:</strong> ${o.payment_method}</p>
                                        <p><strong>Upload:</strong> ${o.upload ? '<a href="<?= base_url('uploads/') ?>' + o.upload + '" target="_blank">View File</a>' : 'No file uploaded'}</p>
                                        <p><strong>Modified By:</strong> ${o.modified_by_username || '-'}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card border-0 shadow-sm mb-3 rounded-3">
                            <div class="card-header fw-semibold" style="background:#F2BE00;">Order Details</div>
                            <div class="card-body">
                                <table class="table table-bordered table-sm"><tbody>${tableRows}</tbody></table>
                            </div>
                        </div>
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header fw-semibold" style="background:#F2BE00;">Comment</div>
                            <div class="card-body">
                                <p class="bg-light p-3 rounded" style="font-size:.9rem;white-space:pre-wrap;">${o.comment || '-'}</p>
                            </div>
                        </div>
                    </div>`;
                } else {
                    contentDiv.innerHTML = '<div class="text-danger text-center py-4">' + data.message + '</div>';
                }
            })
            .catch(function() {
                contentDiv.innerHTML = '<div class="text-danger text-center py-4">Error loading order details.</div>';
            });
    });
});

/* ── Add / save note ──────────────────────────────────── */
$(document).ready(function() {
    $('.btn-add-note').on('click', function() {
        $('#noteOrderId').val($(this).data('id'));
        $('#orderNote').val($(this).data('note'));
        $('#noteModal').modal('show');
    });

    $('#saveNoteBtn').on('click', function() {
        $.ajax({
            url: '<?= base_url("/save_note") ?>',
            type: 'POST',
            data: { order_id: $('#noteOrderId').val(), note: $('#orderNote').val() },
            success: function() {
                $('#noteModal').modal('hide');
                swal({ title: "Note Saved!", text: "Your note has been successfully saved.", icon: "success", timer: 1500, buttons: false });
            },
            error: function() {
                swal({ title: "Error", text: "Unable to save note. Please try again.", icon: "error" });
            }
        });
    });
});

/* ── Flash popup ──────────────────────────────────────── */
<?php if ($popup = session()->getFlashdata('order_status_success')): ?>
swal({
    title: "Updated",
    text: "Order status changed to <?= esc($popup['status']) ?>\nChanged by: <?= esc($popup['username']) ?>",
    icon: "success",
    button: "OK"
});
<?php endif; ?>
</script>
