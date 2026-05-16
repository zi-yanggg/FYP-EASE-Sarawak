<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container mt-4">
    <div class="page-inner">
        <div class="ease-page-head d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div class="ease-crumb">EASE Admin &middot; Promo Codes &middot; <b>Create</b></div>
                <h1 class="ease-page-title">Create Promo Code</h1>
            </div>
            <a href="<?= base_url('/admin/promo_code') ?>" class="btn rpt-export-btn">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('/admin/promo_code/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Code</label>
                        <input type="text" name="code" class="form-control" value="<?= esc(old('code')) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Discount Type</label>
                        <select name="discount_type" id="discount_type" class="form-control" required>
                            <option value="percentage" <?= old('discount_type') === 'percentage' ? 'selected' : '' ?>>Percentage (%)</option>
                            <option value="amount" <?= old('discount_type') === 'amount' ? 'selected' : '' ?>>Amount (RM)</option>
                        </select>
                    </div>

                    <div class="mb-3" id="percentage_input">
                        <label class="form-label">Discount (%)</label>
                        <input type="number" name="discount_percentage" class="form-control" min="0" max="100" value="<?= esc(old('discount_percentage')) ?>">
                    </div>

                    <div class="mb-3" id="amount_input" style="display:none;">
                        <label class="form-label">Discount Amount (RM)</label>
                        <input type="number" step="0.01" name="discount_amount" class="form-control" min="0" value="<?= esc(old('discount_amount')) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Valid From</label>
                        <input type="text" id="validation_date" name="validation_date" class="form-control datetimepicker" value="<?= esc(old('validation_date')) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Expires</label>
                        <input type="text" id="expired_date" name="expired_date" class="form-control datetimepicker" value="<?= esc(old('expired_date')) ?>" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('/admin/promo_code') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Promo</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    flatpickr(".datetimepicker", { enableTime: true, dateFormat: "Y-m-d H:i", time_24hr: true, allowInput: true });

    const typeSelect = document.getElementById('discount_type');
    const percentInput = document.getElementById('percentage_input');
    const amountInput = document.getElementById('amount_input');

    function toggleDiscountInputs() {
        if (typeSelect.value === 'percentage') {
            percentInput.style.display = '';
            amountInput.style.display = 'none';
        } else {
            percentInput.style.display = 'none';
            amountInput.style.display = '';
        }
    }

    typeSelect.addEventListener('change', toggleDiscountInputs);
    toggleDiscountInputs(); // Set initial state
});
</script>

<?= $this->include('admin/footer'); ?>