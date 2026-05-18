<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/transaction_history.css') ?>">

<div class="txn-page">
    <div class="ease-page-head d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="ease-crumb">EASE Admin &middot; Reports &middot; <b>Transaction History</b></div>
            <h1 class="ease-page-title">Transaction History</h1>
        </div>
    </div>

    <div class="txn-card">
        <div class="txn-card__bar">
            <form method="get" action="" class="txn-srch" id="txnSearchForm">
                <i class="fa fa-search"></i>
                <input type="text" name="search" id="tableSearch"
                       placeholder="Search transactions..."
                       value="<?= esc($search ?? '') ?>">
            </form>
        </div>

        <div class="table-responsive">
            <table class="txn-tbl" id="transactionTable">
                <thead>
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
                            <?php
                                $rawStatus = strtolower(trim($txn['status'] ?? ''));
                                if ($rawStatus === 'succeeded') {
                                    $statusCls = 'txn-status--paid';
                                    $statusLbl = 'Paid';
                                } elseif ($rawStatus === 'failed') {
                                    $statusCls = 'txn-status--failed';
                                    $statusLbl = 'Failed';
                                } else {
                                    $statusCls = 'txn-status--pending';
                                    $statusLbl = ucfirst($rawStatus ?: 'Pending');
                                }
                            ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><span class="txn-id"><?= esc($txn['stripe_payment_id'] ?? '-') ?></span></td>
                                <td><span class="txn-id"><?= esc($txn['payment_intent_id'] ?? '-') ?></span></td>
                                <td><span class="txn-amount">RM <?= number_format(($txn['amount_cents'] ?? 0) / 100, 2) ?></span></td>
                                <td><span class="txn-currency"><?= esc(strtoupper($txn['currency'] ?? '-')) ?></span></td>
                                <td><span class="txn-status <?= $statusCls ?>"><?= esc($statusLbl) ?></span></td>
                                <td><span class="txn-date"><?= esc($txn['created_at'] ?? '-') ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="txn-empty">No transactions found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="txn-pagination d-flex justify-content-center">
            <?= $pager->links('group1', 'pagination') ?>
        </div>
    </div>
</div>

<?= $this->include('admin/footer'); ?>

<script>
(function () {
    var searchInput = document.getElementById('tableSearch');
    var searchForm  = document.getElementById('txnSearchForm');
    if (searchInput && searchForm) {
        var searchTimer;
        searchInput.addEventListener('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () { searchForm.submit(); }, 400);
        });
    }
})();
</script>
