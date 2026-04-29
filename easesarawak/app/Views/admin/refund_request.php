<?= $this->include('admin/header'); ?>

<style>
    .dataTables_filter { display: none; }
    .refund-status-btn {
    min-width: 44px;
    }
</style>

<div class="container mt-4">
    <div class="page-inner" style="padding-top: 80px;">
        <div class="d-flex align-items-center mb-4">
            <h3 class="fw-bold mb-0 me-3">
                <i class="fas fa-file-invoice-dollar me-2"></i>Refund Request
            </h3>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Refund Form Database</h5>
                <div class="input-group" style="max-width: 280px;">
                    <input type="text" id="refundSearch" class="form-control form-control-sm" placeholder="Search refund request...">
                    <button class="btn btn-light btn-sm"><i class="fa fa-search"></i></button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0 align-middle" id="refundTable">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Order ID</th>
                                <th>Purchase Date</th>
                                <th>Reason</th>
                                <th>Status Progress</th>                                
                                <th>PDF</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($refunds)): ?>
                                <?php foreach ($refunds as $refund): ?>
                                    <?php
                                        $pdfLink = '';
                                        if (!empty($refund['pdf_path'])) {
                                            $pdfLink = preg_match('#^https?://#', $refund['pdf_path'])
                                                ? $refund['pdf_path']
                                                : base_url($refund['pdf_path']);
                                        }
                                    ?>
                                    <tr>
                                        <td><?= esc($refund['id'] ?? '-') ?></td>
                                        <td><?= esc($refund['full_name'] ?? '-') ?></td>
                                        <td><?= esc($refund['email'] ?? '-') ?></td>
                                        <td><?= esc($refund['phone_number'] ?? '-') ?></td>
                                        <td>
                                            <?php if (!empty($refund['order_id'])): ?>
                                                <a href="<?= base_url('/admin/order_details/' . $refund['order_id']) ?>"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="order-id-link"
                                                data-order-id="<?= esc($refund['order_id']) ?>"
                                                style="color: black; text-decoration: none;">
                                                    <?= esc($refund['order_id']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span>-</span>
                                            <?php endif; ?>
                                        </td>                                       
                                        <td><?= esc($refund['date_of_purchase'] ?? '-') ?></td>
                                        <td><?= esc($refund['reason_for_refund'] ?? '-') ?></td>
                                        <td class="text-center">
                                            <?php
                                                $refundStatus = isset($refund['status_progress']) ? (int) $refund['status_progress'] : 0;

                                                if ($refundStatus === 1) {
                                                    $statusLabel = 'Agreed';
                                                    $statusClass = 'btn-completed';
                                                    $statusIcon  = 'fa-check';
                                                } elseif ($refundStatus === 2) {
                                                    $statusLabel = 'Rejected';
                                                    $statusClass = 'btn-pending';
                                                    $statusIcon  = 'fa-times';
                                                } else {
                                                    $statusLabel = 'In Progress';
                                                    $statusClass = 'btn-progress';
                                                    $statusIcon  = 'fa-spinner fa-spin';
                                                }

                                                $updatedBy = $refund['status_updated_username'] ?? '';
                                                $updatedAt = $refund['status_updated_at'] ?? '-';
                                            ?>

                                            <div class="d-inline-flex align-items-center gap-2">
                                                <button type="button"
                                                        class="btn btn-sm refund-status-btn <?= esc($statusClass) ?>"
                                                        data-refund-id="<?= esc($refund['id']) ?>"
                                                        data-current-status="<?= esc($statusLabel) ?>"
                                                        data-updated-by="<?= esc($updatedBy ?: '-') ?>"
                                                        title="Current status: <?= esc($statusLabel) ?> | Updated by: <?= esc($updatedBy ?: '-') ?> | Updated at: <?= esc($updatedAt) ?>">
                                                    <i class="fa <?= esc($statusIcon) ?>"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($pdfLink): ?>
                                                <a href="<?= esc($pdfLink) ?>" target="_blank" class="btn btn-sm btn-primary">
                                                    View PDF
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">No PDF</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($refund['created_at'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">No refund requests found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="refundOrderModal" tabindex="-1" aria-labelledby="refundOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="refundOrderModalDialog">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-softblue text-white draggable-modal-header" style="cursor: move;">
                <h5 class="modal-title fw-semibold" id="refundOrderModalLabel">
                    <i class="fa fa-file-alt me-2"></i>Order Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="refundOrderDetailsContent" class="py-3 text-muted">
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

<?= $this->include('admin/footer'); ?>

<script>
(function () {
    const search = document.getElementById('refundSearch');
    if (search) {
        search.addEventListener('keyup', function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#refundTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }

    const modalElement = document.getElementById('refundOrderModal');
    const modalDialog = document.getElementById('refundOrderModalDialog');
    const modalContent = document.getElementById('refundOrderDetailsContent');
    const modalHeader = modalElement.querySelector('.draggable-modal-header');

    document.querySelectorAll('.refund-order-link').forEach(button => {
        button.addEventListener('click', function () {
            const orderId = this.getAttribute('data-id');
            const modal = new bootstrap.Modal(modalElement);

            modalContent.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="fa fa-spinner fa-spin me-2"></i>Loading order details...
                </div>
            `;

            modal.show();

            fetch(`<?= base_url('/order/getDetails'); ?>/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const o = data.order;
                        let detailsObj = {};

                        try {
                            detailsObj = JSON.parse(o.order_details_json || '{}');
                        } catch (e) {
                            detailsObj = {};
                        }

                        let tableRows = '';

                        Object.entries(detailsObj).forEach(([key, value]) => {
                            const prettyKey = key.replace(/([A-Z])/g, ' $1')
                                                 .replace(/^./, str => str.toUpperCase());

                            tableRows += `
                                <tr>
                                    <td class="fw-semibold">${prettyKey}</td>
                                    <td>${value || '-'}</td>
                                </tr>
                            `;
                        });

                        modalContent.innerHTML = `
                            <div class="container-fluid">
                                <div class="card border-0 shadow-sm mb-3 rounded-3">
                                    <div class="card-header fw-semibold" style="background: #f2be00ff;">
                                        Customer Information
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <p><strong>First Name:</strong> ${o.first_name || '-'}</p>
                                                <p><strong>Last Name:</strong> ${o.last_name || '-'}</p>
                                                <p><strong>Email:</strong> ${o.email || '-'}</p>
                                                <p><strong>Phone:</strong> ${o.phone || '-'}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>ID Number:</strong> ${o.id_num || '-'}</p>
                                                <p><strong>Social:</strong> ${
                                                    o.social == 1 ? 'WhatsApp' :
                                                    o.social == 2 ? 'WeChat' :
                                                    o.social == 3 ? 'LINE' :
                                                    'Unknown'
                                                }</p>
                                                <p><strong>Social Number:</strong> ${o.social_num || '-'}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-0 shadow-sm mb-3 rounded-3">
                                    <div class="card-header fw-semibold" style="background: #f2be00ff;">
                                        Order Information
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <p><strong>Service Type:</strong> ${o.service_type || '-'}</p>
                                                <p><strong>Special:</strong> ${o.special || '-'}</p>
                                                <p><strong>Special Note:</strong> ${o.special_note || '-'}</p>
                                                <p><strong>Promo Code:</strong> ${o.promo_code || '-'}</p>
                                                <p><strong>Last Modified:</strong> ${o.modified_date || '-'}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Status:</strong> ${
                                                    o.status == 0
                                                        ? '<span class="badge bg-warning text-dark">Pending</span>'
                                                        : o.status == 1
                                                        ? '<span class="badge bg-primary">In Progress</span>'
                                                        : '<span class="badge bg-success">Completed</span>'
                                                }</p>
                                                <p><strong>Amount:</strong> RM${o.amount || '0.00'}</p>
                                                <p><strong>Payment Method:</strong> ${o.payment_method || '-'}</p>
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

                                <div class="card border-0 shadow-sm mb-3 rounded-3">
                                    <div class="card-header fw-semibold" style="background: #f2be00ff;">
                                        Order Details
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered table-sm">
                                            <tbody>
                                                ${tableRows || '<tr><td colspan="2">No order details found.</td></tr>'}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card border-0 shadow-sm rounded-3">
                                    <div class="card-header fw-semibold" style="background: #f2be00ff;">
                                        Comment
                                    </div>
                                    <div class="card-body">
                                        <p class="bg-light p-3 rounded" style="font-size: 0.9rem; white-space: pre-wrap;">${o.comment || '-'}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        modalContent.innerHTML = `
                            <div class="alert alert-danger mb-0">
                                ${data.message || 'Order not found.'}
                            </div>
                        `;
                    }
                })
                .catch(() => {
                    modalContent.innerHTML = `
                        <div class="alert alert-danger mb-0">
                            Error loading order details.
                        </div>
                    `;
                });
        });
    });

    let isDragging = false;
    let startX = 0;
    let startY = 0;
    let initialLeft = 0;
    let initialTop = 0;

    modalHeader.addEventListener('mousedown', function (e) {
        isDragging = true;

        const rect = modalDialog.getBoundingClientRect();
        startX = e.clientX;
        startY = e.clientY;
        initialLeft = rect.left;
        initialTop = rect.top;

        modalDialog.style.left = initialLeft + 'px';
        modalDialog.style.top = initialTop + 'px';
        modalDialog.style.transform = 'none';

        document.body.style.userSelect = 'none';
    });

    document.addEventListener('mousemove', function (e) {
        if (!isDragging) return;

        const dx = e.clientX - startX;
        const dy = e.clientY - startY;

        modalDialog.style.left = (initialLeft + dx) + 'px';
        modalDialog.style.top = (initialTop + dy) + 'px';
    });

    document.addEventListener('mouseup', function () {
        isDragging = false;
        document.body.style.userSelect = '';
    });

    modalElement.addEventListener('hidden.bs.modal', function () {
        modalDialog.style.top = '80px';
        modalDialog.style.left = '50%';
        modalDialog.style.transform = 'translateX(-50%)';
    });
})();

    document.querySelectorAll('.order-id-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            const orderId = this.getAttribute('data-order-id');
            const detailUrl = this.getAttribute('href');

            fetch(`<?= base_url('/order/getDetails') ?>/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.open(detailUrl, '_blank');
                    } else {
                        swal({
                            title: "Order not found",
                            text: "Order ID " + orderId + " does not exist in the Order page.",
                            icon: "warning",
                            button: "OK"
                        });
                    }
                })
                .catch(() => {
                    swal({
                        title: "Error",
                        text: "Unable to check the order right now. Please try again.",
                        icon: "error",
                        button: "OK"
                    });
                });
        });
    });

</script>

<script>
(function () {
    const search = document.getElementById('refundSearch');
    if (search) {
        search.addEventListener('keyup', function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#refundTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }

    const currentAdmin = <?= json_encode(session()->get('username') ?? 'Unknown') ?>;

    document.querySelectorAll('.refund-status-btn').forEach(button => {
        button.addEventListener('click', function () {
        const refundId = this.getAttribute('data-refund-id');
        const currentStatus = this.getAttribute('data-current-status');
        const updatedBy = this.getAttribute('data-updated-by');

        swal({
            title: "Select refund status",
            text: "Refund ID: " + refundId +
                "\nCurrent status: " + currentStatus +
                (updatedBy && updatedBy !== '-' ? "\nChanged by: " + updatedBy : ""),
                buttons: {
                    cancel: "Cancel",
                    reject: {
                        text: "Reject",
                        value: "2"
                    },
                    agree: {
                        text: "Agreed",
                        value: "1"
                    }
                }
            }).then((selectedStatus) => {
                if (!selectedStatus) return;

                const newStatusText = selectedStatus === "1" ? "Agreed" : "Rejected";

                swal({
                    title: "Change refund status?",
                    text: "Refund ID: " + refundId +
                          "\nCurrent status: " + currentStatus +
                          "\nNew status: " + newStatusText +
                          "\nChanged by: " + currentAdmin,
                    icon: "warning",
                    buttons: ["Cancel", "Yes, change it"],
                    dangerMode: true
                }).then((willChange) => {
                    if (!willChange) return;

                    $.ajax({
                        url: '<?= base_url('/admin/refund_request/change_status') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            refund_id: refundId,
                            new_status: selectedStatus
                        },
                        success: function(response) {
                            if (response.success) {
                                swal({
                                    title: "Updated",
                                    text: "Refund status changed to " + response.status_label +
                                          "\nChanged by: " + response.username,
                                    icon: "success"
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                swal({
                                    title: "Error",
                                    text: response.message || "Unable to update refund status.",
                                    icon: "error"
                                });
                            }
                        },
                        error: function() {
                            swal({
                                title: "Error",
                                text: "Unable to update refund status. Please try again.",
                                icon: "error"
                            });
                        }
                    });
                });
            });
        });
    });
})();
</script>