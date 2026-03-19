<?= $this->include('admin/header'); ?>

<?php
$status     = $_GET['status'] ?? '';
$service    = $_GET['service_type'] ?? '';
$startDate  = $_GET['start_date'] ?? '';
$endDate    = $_GET['end_date'] ?? '';
?>

<div class="container mt-4">
    <div class="page-inner" style="padding-top: 80px;">
        <div class="d-flex align-items-center mb-4">
            <h3 class="fw-bold mb-0 me-3"><i class="fas fa-shopping-bag me-2"></i>Order Management</h3>
            <span class="text-muted">View all customer orders</span>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-softblue d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 text-white fw-semibold">Orders Overview</h5>
                <div class="input-group w-auto">
                    <!-- Filter Button -->
                    <button
                        class="btn me-4"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#filterOffcanvas"
                        style="background: #fff; border: 1px solid #a2a9afff; border-radius: 4px; color: #807a7aff; padding: 6px 10px;">
                        <i class="fa fa-filter"></i>
                    </button>

                    <!-- Search Bar -->
                    <input type="text" class="form-control form-control-sm" placeholder="Search orders..." id="orderSearch">
                    <button class="btn btn-light btn-sm"><i class="fa fa-search"></i></button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0 align-middle" id="orderTable">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Service Type</th>
                                <th>Customer Name</th>
                                <th>Contact Number</th>
                                <th>Order Date</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?= esc($order['order_id']); ?></td>
                                        <td><?= strtoupper(esc($order['service_type'])); ?></td>
                                        <td><?= esc($order['first_name']); ?> <?= esc($order['last_name']); ?></td>
                                        <td><?= esc($order['phone']); ?></td>
                                        <td><?= date('d M Y, h:i A', strtotime($order['created_date'])); ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('/change_status/' . $order['order_id']); ?>"
                                                class="btn btn-sm
                                                <?php
                                                if ($order['status'] == 0) echo 'btn-pending';
                                                elseif ($order['status'] == 1) echo 'btn-progress';
                                                else echo 'btn-completed';
                                                ?>">
                                                <?php
                                                if ($order['status'] == 0) echo '<i class="fa fa-hourglass-start"></i>';
                                                elseif ($order['status'] == 1) echo '<i class="fa fa-spinner"></i>';
                                                else echo '<i class="fa fa-check"></i>';
                                                ?>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center">
                                                <button type="button" class="btn btn-sm viewOrderBtn me-2"
                                                    data-id="<?= $order['order_id']; ?>"
                                                    style="background: #fff; border: 1px solid #a2a9afff; color: #504c4cff;">
                                                    <i class="fa fa-eye"></i>
                                                </button>

                                                <button class="btn btn-sm btn-add-note"
                                                    data-id="<?= $order['order_id']; ?>"
                                                    data-note="<?= htmlspecialchars($order['comment'] ?? '', ENT_QUOTES); ?>"
                                                    style="background: #fff; border: 1px solid #a2a9afff; color: #504c4cff;">
                                                    <i class="fa fa-sticky-note"></i>
                                                </button>

                                                <button class="btn btn-sm btn-activity-log ms-2"
                                                    data-id="<?= $order['order_id']; ?>"
                                                    style="background: #fff; border: 1px solid #a2a9afff; color: #504c4cff;">
                                                    <i class="fa fa-history"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No orders found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    <?= $pager->links('group1', 'pagination') ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-softblue text-white">
                <h5 class="modal-title fw-semibold" id="orderModalLabel">
                    <i class="fa fa-file-alt me-2"></i>Order Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="orderDetailsContent" class="py-3 text-muted">
                    <i class="fa fa-spinner fa-spin me-2"></i>Loading...
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Add Note Modal -->
<div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-softblue text-white">
                <h5 class="modal-title" id="noteModalLabel">Add Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="noteForm">
                    <input type="hidden" name="order_id" id="noteOrderId">
                    <div class="form-group">
                        <label for="orderNote">Note</label>
                        <textarea class="form-control" id="orderNote" name="note" rows="4" placeholder="Write your note here..."></textarea>
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

<!-- Activity Log Modal -->
<div class="modal fade" id="logModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fa fa-history me-2"></i>Activity Log</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="activityLogContent" class="text-center py-3 text-muted">
                    <i class="fa fa-spinner fa-spin me-2"></i>Loading logs...
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button class="btn btn-update" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Right Offcanvas Filter Panel -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="filterOffcanvas">
    <div class="offcanvas-header text-white d-flex justify-content-center align-items-center" style="background: #dbdee0ff;">
        <!-- Back button -->
        <button type="button" class="btn btn-sm me-2" data-bs-dismiss="offcanvas">
            <i class="fa fa-arrow-left"></i>
        </button>

        <h5 class="offcanvas-title flex-grow-1 text-center text-black fw-semibold">
            Filter Orders
        </h5>
    </div>


    <div class="offcanvas-body">
        <form method="GET" action="<?= base_url('order'); ?>">

            <!-- Status Filter -->
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="0" <?= ($status === "0" ? "selected" : "") ?>>Pending</option>
                    <option value="1" <?= ($status === "1" ? "selected" : "") ?>>In-Progress</option>
                    <option value="2" <?= ($status === "2" ? "selected" : "") ?>>Completed</option>
                </select>
            </div>

            <!-- Date Range -->
            <div class="mb-3">
                <label class="form-label">Date Range</label>
                <div class="input-group">
                    <input type="date" name="start_date" class="form-control" value="<?= $startDate ?>">
                    <input type="date" name="end_date" class="form-control" value="<?= $endDate ?>">
                </div>
            </div>

            <!-- Service Type -->
            <div class="mb-3">
                <label class="form-label">Service Type</label>
                <select name="service_type" class="form-select">
                    <option value="">All</option>
                    <option value="storage" <?= ($service === "storage") ? "selected" : "" ?>>Storage</option>
                    <option value="delivery" <?= ($service === "delivery") ? "selected" : "" ?>>Delivery</option>
                </select>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-update" type="submit">
                    <i class="fa fa-check me-1"></i> Apply Filters
                </button>
                <a href="<?= base_url('order'); ?>" class="btn btn-cancel">
                    <i class="fa fa-undo me-1"></i> Reset
                </a>
            </div>

        </form>
    </div>
</div>


<?= $this->include('admin/footer'); ?>

<script>
    document.querySelectorAll('.btn-activity-log').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id');
            const modal = new bootstrap.Modal(document.getElementById('logModal'));
            const contentDiv = document.getElementById('activityLogContent');

            contentDiv.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="fa fa-spinner fa-spin me-2"></i>Loading activity log...
            </div>`;

            modal.show();

            fetch("<?= base_url('/order_activity_log/') ?>" + orderId)
                .then(response => response.text())
                .then(data => {
                    contentDiv.innerHTML = data;
                })
                .catch(() => {
                    contentDiv.innerHTML = `<div class="alert alert-danger">Failed to load logs.</div>`;
                });
        });
    });

    document.getElementById('orderSearch').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#orderTable tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    document.querySelectorAll('.viewOrderBtn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id');
            const modal = new bootstrap.Modal(document.getElementById('orderModal'));
            const contentDiv = document.getElementById('orderDetailsContent');

            contentDiv.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="fa fa-spinner fa-spin me-2"></i>Loading order details...
            </div>`;
            modal.show();

            fetch(`<?= base_url('/order/getDetails'); ?>/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const o = data.order;
                        const detailsObj = JSON.parse(o.order_details_json);
                        let tableRows = '';

                        Object.entries(detailsObj).forEach(([key, value]) => {
                            // Convert camelCase to "Camel Case"
                            const prettyKey = key.replace(/([A-Z])/g, ' $1')
                                .replace(/^./, str => str.toUpperCase());

                            tableRows += `
                            <tr>
                                <td class="fw-semibold">${prettyKey}</td>
                                <td>${value || '-'}</td>
                            </tr>
                        `;
                        });

                        contentDiv.innerHTML = `
                        <div class="container-fluid">
                            <!-- Section 1 -->
                            <div class="card border-0 shadow-sm mb-3 rounded-3">
                                <div class="card-header fw-semibold" style="background: #f2be00ff;">
                                    Customer Information
                                </div>
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
                                            <p><strong>Social:</strong> ${
                                                o.social == 1 ? "WhatsApp" :
                                                o.social == 2 ? "WeChat" :
                                                o.social == 3 ? "LINE" :
                                                "Unknown"
                                            }</p>
                                            <p><strong>Social Number:</strong> ${o.social_num}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2 -->
                            <div class="card border-0 shadow-sm mb-3 rounded-3">
                                <div class="card-header fw-semibold" style="background: #f2be00ff;">
                                    Order Information
                                </div>
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
                                            <p><strong>Status:</strong> ${
                                                o.status == 0
                                                    ? '<span class="badge-pending">Pending</span>'
                                                    : o.status == 1
                                                    ? '<span class="badge-progress">In Progress</span>'
                                                    : '<span class="badge-completed">Completed</span>'
                                            }</p>
                                            <p><strong>Amount:</strong> RM${o.amount}</p>
                                            <p><strong>Payment Method:</strong> ${o.payment_method}</p>
                                            <p><strong>Upload:</strong> ${
                                                o.upload
                                                    ? `<a href="<?= base_url('uploads/'); ?>${o.upload}" target="_blank" class="text-decoration-none">View File</a>`
                                                    : 'No file uploaded'
                                            }</p>
                                            <p><strong>Modified By:</strong> ${o.modified_by_username || '-'}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3 -->
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header fw-semibold" style="background: #f2be00ff;">
                                    Order Details
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-sm">
                                        <tbody>
                                            ${tableRows}
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Section 4 -->
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header fw-semibold" style="background: #f2be00ff;">
                                    <i class="fa fa- me-2 text-primary"></i>Comment
                                </div>
                                <div class="card-body">
                                    <p class="bg-light p-3 rounded" style="font-size: 0.9rem; white-space: pre-wrap;">${o.comment || '-'}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    } else {
                        contentDiv.innerHTML = `<div class="text-danger text-center py-4">${data.message}</div>`;
                    }
                })
                .catch(() => {
                    contentDiv.innerHTML = `<div class="text-danger text-center py-4">Error loading order details.</div>`;
                });
        });
    });

    $(document).ready(function() {
        // Show modal with existing note if any
        $('.btn-add-note').on('click', function() {
            const orderId = $(this).data('id');
            const note = $(this).data('note');
            $('#noteOrderId').val(orderId);
            $('#orderNote').val(note);
            $('#noteModal').modal('show');
        });

        // Save note via AJAX
        $('#saveNoteBtn').on('click', function() {
            const orderId = $('#noteOrderId').val();
            const note = $('#orderNote').val();

            $.ajax({
                url: '<?= base_url("/save_note") ?>',
                type: 'POST',
                data: {
                    order_id: orderId,
                    note: note
                },
                success: function(response) {
                    $('#noteModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Note Saved!',
                        text: 'Your note has been successfully saved.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Unable to save note. Please try again.'
                    });
                }
            });
        });
    });
</script>