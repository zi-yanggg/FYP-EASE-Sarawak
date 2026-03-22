<?= $this->include('admin/header'); ?>

<style>
    .dataTables_filter { display: none !important; }
</style>

<div class="container mt-4">
    <div class="page-inner" style="padding-top: 80px;">
        <div class="d-flex align-items-center mb-4">
            <h3 class="fw-bold mb-0 me-3"><i class="fas fa-ticket-alt me-2"></i>Promo Code Management</h3>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Promo Codes</h5>
                <div class="d-flex align-items-center">
                    <div class="input-group me-2">
                        <input type="text" id="promoSearch" class="form-control form-control-sm" placeholder="Search promo codes...">
                        <button class="btn btn-light btn-sm"><i class="fa fa-search"></i></button>
                    </div>
                    <a href="<?= base_url('/admin/promo_code/create') ?>" class="btn btn-primary btn-sm">Add Promo</a>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0 align-middle" id="promoTable">
                        <thead class="table-light">
                            <tr>  
                                <th>ID</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Discount</th>
                                <th>Valid From</th>
                                <th>Expires</th>
                                <th>Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $list = $promoCodes ?? []; ?>
                            <?php if (!empty($list) && is_array($list)): ?>
                                <?php foreach ($list as $p): ?>
                                    <tr>
                                        <td><?= esc($p['id']); ?></td>
                                        <td><?= esc($p['code']); ?></td>
                                        <td><?= esc($p['discount_type'] ?? 'percentage'); ?></td>
                                        <td>
                                            <?php if (($p['discount_type'] ?? 'percentage') === 'amount'): ?>
                                                RM<?= esc($p['discount_amount'] ?? '0.00') ?>
                                            <?php else: ?>
                                                <?= esc($p['discount_percentage'] ?? '0') ?>%
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($p['validation_date'] ?? ''); ?></td>
                                        <td><?= esc($p['expired_date'] ?? ''); ?></td>
                                        <td><?= esc($p['created_date'] ?? $p['created_at'] ?? ''); ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('/admin/promo_code/edit/'.$p['id']); ?>" class="btn btn-sm btn-dark me-1" title="Edit"><i class="fa fa-edit"></i></a>
                                            <a href="<?= base_url('/admin/promo_code/delete/'.$p['id']); ?>" class="btn btn-sm btn-danger btn-delete" data-id="<?= $p['id']; ?>" title="Delete"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No promo codes found.</td>
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
(function(){
    const search = document.getElementById('promoSearch');
    if (search) {
        search.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#promoTable tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (window.jQuery && $.fn.DataTable && document.querySelector('#promoTable')) {
            $('#promoTable').DataTable({ pageLength: 25, responsive: true, order: [[0, 'desc']] });
        }

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this promo code?')) {
                    window.location.href = this.getAttribute('href');
                }
            });
        });
    });
})();
</script>