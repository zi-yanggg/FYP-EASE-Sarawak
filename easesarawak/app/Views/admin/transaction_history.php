<?= $this->include('admin/header'); ?>

<style>
    .dataTables_filter { display: none !important; }
    .dataTables_paginate {
        display: flex !important;
        justify-content: left !important;
    }
</style>

<div class="container mt-4">
    <div class="page-inner" style="padding-top: 80px;">
        <div class="d-flex align-items-center mb-4">
            <h3 class="fw-bold mb-0 me-3"><i class="fas fa-money-check-alt me-2"></i>Transaction History</h3>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Transactions</h5>
                <div class="d-flex align-items-center">
                    <div class="input-group me-2">
                        <input type="text" id="tableSearch" class="form-control form-control-sm" placeholder="Search...">
                        <button class="btn btn-light btn-sm"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0 align-middle" id="transactionTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Stripe Payment ID</th>
                                <th>Payment Intent ID</th>
                                <th>Amount</th>
                                <th>Currency</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($transactions)): ?>
                                <?php foreach ($transactions as $i => $txn): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= esc($txn['stripe_payment_id'] ?? '-') ?></td>
                                        <td><?= esc($txn['payment_intent_id'] ?? '-') ?></td>
                                        <td><?= number_format(($txn['amount_cents'] ?? 0) / 100, 2) ?></td>
                                        <td><?= esc($txn['currency'] ?? '-') ?></td>
                                        <td><?= esc($txn['status'] ?? '-') ?></td>
                                        <td><?= esc($txn['created_at'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No transactions found.</td>
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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
(function(){
    const search = document.getElementById('tableSearch');
    if (search) {
        search.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#transactionTable tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (window.jQuery && $.fn.DataTable && document.querySelector('#transactionTable')) {
        $('#transactionTable').DataTable({ pageLength: 7, responsive: true, order: [[0, 'desc']], info: false });        }
    });
})();
</script>