<?= $this->include('admin/header'); ?>

<style>
    .dataTables_filter { display: none !important; }
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
                                <th>Service Type</th>
                                <th>Bank Name</th>
                                <th>Account Holder</th>
                                <th>Account Number</th>
                                <th>Reason</th>
                                <th>Declaration</th>
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
                                        <td><?= esc($refund['order_id'] ?? '-') ?></td>
                                        <td><?= esc($refund['date_of_purchase'] ?? '-') ?></td>
                                        <td><?= esc($refund['service_type'] ?? '-') ?></td>
                                        <td><?= esc($refund['bank_name'] ?? '-') ?></td>
                                        <td><?= esc($refund['account_holder_name'] ?? '-') ?></td>
                                        <td><?= esc($refund['account_number'] ?? $refund['account_name'] ?? '-') ?></td>
                                        <td><?= esc($refund['reason_for_refund'] ?? '-') ?></td>
                                        <td>
                                            <?php if (!empty($refund['declaration']) && (int)$refund['declaration'] === 1): ?>
                                                <span class="badge bg-success">Agreed</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">No</span>
                                            <?php endif; ?>
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
                                    <td colspan="14" class="text-center py-4 text-muted">No refund requests found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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
})();
</script>