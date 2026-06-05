<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/order.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/admin/refund_request.css') ?>">

<?php
function refundRowClass(int $status): string
{
    return match ($status) {
        1       => 'ord-row--done',
        2       => 'ord-row--rejected',
        default => 'ord-row--progress',
    };
}

function refundStatusBadge(int $status): array
{
    return match ($status) {
        1       => ['ord-status--done', 'Approved'],
        2       => ['ord-status--rejected', 'Rejected'],
        default => ['ord-status--progress', 'In Progress'],
    };
}
?>

<div class="ord-page">

    <div class="ease-page-head d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="ease-crumb">EASE Admin &middot; <b>Refund Requests</b></div>
            <h1 class="ease-page-title">Refund Requests</h1>
        </div>
    </div>

    <div class="ord-card">
        <div class="ord-card__bar">
            <form method="get" action="" class="ord-srch" id="refundSearchForm">
                <i class="fas fa-search"></i>
                <input type="text" name="search" id="refundSearch"
                       placeholder="Search refund requests…"
                       value="<?= esc($search ?? '') ?>"
                       autocomplete="off">
            </form>
        </div>

        <div class="table-responsive">
            <table class="ord-tbl" id="refundTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Order ID</th>
                        <th>Purchase Date</th>
                        <th>Reason</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">PDF</th>
                        <th>Submitted</th>
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

                                $refundStatus = isset($refund['status_progress']) ? (int) $refund['status_progress'] : 0;
                                [$statusCls, $statusLabel] = refundStatusBadge($refundStatus);
                                $updatedBy = $refund['status_updated_username'] ?? '';
                                $updatedAt = $refund['status_updated_at'] ?? '-';
                                $name      = trim((string) ($refund['full_name'] ?? ''));
                            ?>
                            <tr class="<?= esc(refundRowClass($refundStatus)) ?>">
                                <td>
                                    <span class="ord-id">#<?= esc($refund['id'] ?? '-') ?></span>
                                </td>
                                <td>
                                    <div class="ord-customer">
                                        <span class="ord-customer__name"><?= esc($name !== '' ? $name : '—') ?></span>
                                        <span class="ord-customer__phone"><?= esc($refund['email'] ?? '—') ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="refund-phone"><?= esc($refund['phone_number'] ?? '—') ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($refund['order_id'])): ?>
                                        <a href="<?= base_url('/admin/order_details/' . $refund['order_id']) ?>"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="ord-kv__link order-id-link"
                                           data-order-id="<?= esc($refund['order_id']) ?>">
                                            #<?= esc($refund['order_id']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="refund-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="ord-date"><?= esc($refund['date_of_purchase'] ?? '—') ?></span>
                                </td>
                                <td>
                                    <span class="refund-reason" title="<?= esc($refund['reason_for_refund'] ?? '', 'attr') ?>">
                                        <?= esc($refund['reason_for_refund'] ?? '—') ?>
                                    </span>
                                </td>
                                <td class="text-center ord-tbl__status">
                                    <span class="ord-status-badge <?= esc($statusCls) ?> refund-status-btn"
                                        role="button"
                                        data-refund-id="<?= esc($refund['id']) ?>"
                                        data-current-status="<?= esc($statusLabel) ?>"
                                        data-updated-by="<?= esc($updatedBy ?: '-') ?>"
                                        title="Status: <?= esc($statusLabel) ?> | By: <?= esc($updatedBy ?: '-') ?> | At: <?= esc($updatedAt) ?>">
                                        <?= esc($statusLabel) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if ($pdfLink): ?>
                                        <a href="<?= esc($pdfLink) ?>" target="_blank" rel="noopener noreferrer"
                                           class="ord-act-btn refund-pdf-btn" title="View PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="refund-muted">No PDF</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="ord-date-wrap">
                                        <?php
                                            $created = $refund['created_at'] ?? '';
                                            $createdTs = $created !== '' && $created !== '-' ? strtotime($created) : false;
                                        ?>
                                        <?php if ($createdTs): ?>
                                            <span class="ord-date"><?= date('d M Y', $createdTs) ?></span>
                                            <span class="ord-time"><?= date('H:i', $createdTs) ?></span>
                                        <?php else: ?>
                                            <span class="ord-date">—</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="ord-empty">No refund requests found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="ord-pagination d-flex justify-content-center">
            <?= $pager->links('group1', 'pagination') ?>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="refundOrderModal" tabindex="-1" aria-labelledby="refundOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="refundOrderModalDialog">
        <div class="modal-content ord-modal-content">
            <div class="modal-header ord-modal-head draggable-modal-header">
                <h5 class="modal-title" id="refundOrderModalLabel">
                    <i class="fas fa-file-alt me-2"></i>Order Details
                </h5>
                <button type="button" class="ease-modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="refundOrderDetailsContent" class="py-3 text-muted">
                    <i class="fas fa-spinner fa-spin me-2"></i>Loading...
                </div>
            </div>
            <div class="modal-footer ord-modal-foot d-flex justify-content-end">
                <button type="button" class="ord-btn-ghost" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->include('admin/footer'); ?>

<script>
(function () {
    var modalElement = document.getElementById('refundOrderModal');
    if (modalElement && modalElement.parentNode !== document.body) {
        document.body.appendChild(modalElement);
    }

    var searchInput = document.getElementById('refundSearch');
    var searchForm  = document.getElementById('refundSearchForm');
    if (searchInput && searchForm) {
        var searchTimer;
        searchInput.addEventListener('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () { searchForm.submit(); }, 400);
        });
    }

    document.querySelectorAll('.order-id-link').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            var orderId   = this.getAttribute('data-order-id');
            var detailUrl = this.getAttribute('href');

            fetch('<?= base_url('/order/getDetails') ?>/' + orderId)
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) {
                        window.open(detailUrl, '_blank');
                    } else {
                        swal({ title: 'Order not found', text: 'Order ID ' + orderId + ' does not exist.', icon: 'warning', button: 'OK' });
                    }
                })
                .catch(function () {
                    swal({ title: 'Error', text: 'Unable to check the order right now.', icon: 'error', button: 'OK' });
                });
        });
    });

    var modalDialog = document.getElementById('refundOrderModalDialog');
    var modalHeader = modalElement ? modalElement.querySelector('.draggable-modal-header') : null;
    if (modalHeader && modalDialog) {
        var isDragging = false, startX = 0, startY = 0, initialLeft = 0, initialTop = 0;

        modalHeader.addEventListener('mousedown', function (e) {
            isDragging = true;
            var rect = modalDialog.getBoundingClientRect();
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
            modalDialog.style.left = (initialLeft + e.clientX - startX) + 'px';
            modalDialog.style.top  = (initialTop  + e.clientY - startY) + 'px';
        });

        document.addEventListener('mouseup', function () {
            isDragging = false;
            document.body.style.userSelect = '';
        });

        modalElement.addEventListener('hidden.bs.modal', function () {
            modalDialog.style.top = '80px';
            modalDialog.style.left = '50%';
            modalDialog.style.transform = 'translateX(-50%)';
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
            document.body.style.removeProperty('overflow');
            document.querySelectorAll('.modal-backdrop').forEach(function (b) { b.remove(); });
        });
    }

    var currentAdmin = <?= json_encode(session()->get('username') ?? 'Unknown') ?>;

    document.querySelectorAll('.refund-status-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            var refundId      = this.getAttribute('data-refund-id');
            var currentStatus = this.getAttribute('data-current-status');
            var updatedBy     = this.getAttribute('data-updated-by');

            swal({
                title: 'Select refund status',
                text: 'Refund ID: ' + refundId +
                    '\nCurrent status: ' + currentStatus +
                    (updatedBy && updatedBy !== '-' ? '\nChanged by: ' + updatedBy : ''),
                buttons: {
                    cancel: 'Cancel',
                    reject:  { text: 'Reject',   value: '2' },
                    approve: { text: 'Approve', value: '1' }
                }
            }).then(function (selectedStatus) {
                if (!selectedStatus) return;
                var newStatusText = selectedStatus === '1' ? 'Approved' : 'Rejected';

                swal({
                    title: 'Change refund status?',
                    text: 'Refund ID: ' + refundId + '\nCurrent: ' + currentStatus + '\nNew: ' + newStatusText + '\nBy: ' + currentAdmin,
                    icon: 'warning',
                    buttons: ['Cancel', 'Yes, change it'],
                    dangerMode: true
                }).then(function (willChange) {
                    if (!willChange) return;

                    $.ajax({
                        url: '<?= base_url('/admin/refund_request/change_status') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: { refund_id: refundId, new_status: selectedStatus },
                        success: function (response) {
                            if (response.success) {
                                swal({ title: 'Updated', text: 'Status changed to ' + response.status_label + '\nBy: ' + response.username, icon: 'success' })
                                    .then(function () { window.location.reload(); });
                            } else {
                                swal({ title: 'Error', text: response.message || 'Unable to update.', icon: 'error' });
                            }
                        },
                        error: function () {
                            swal({ title: 'Error', text: 'Unable to update refund status.', icon: 'error' });
                        }
                    });
                });
            });
        });
    });
})();
</script>
