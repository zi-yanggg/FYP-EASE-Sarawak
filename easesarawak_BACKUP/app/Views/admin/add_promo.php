<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/promo_code.css') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="promo-page">
    <div class="ease-page-head d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="ease-crumb">EASE Admin &middot; Promo Codes &middot; <b>Create</b></div>
            <h1 class="ease-page-title">Create Promo Code</h1>
        </div>
        <a href="<?= base_url('/admin/promo_code') ?>" class="promo-btn-back">
            <i class="fas fa-arrow-left"></i> Back
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

    <div class="promo-form-card">
        <div class="promo-form-card__head">
            <h5 class="promo-form-card__head-title">New Promo Code</h5>
        </div>
        <div class="promo-form-card__body">
            <form action="<?= base_url('/admin/promo_code/store') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-4">
                    <label class="promo-field-label">Code</label>
                    <input type="text" name="code" class="promo-field-input" value="<?= esc(old('code')) ?>" placeholder="e.g. SAVE20" required>
                </div>

                <div class="mb-4">
                    <label class="promo-field-label">Discount Type</label>
                    <select name="discount_type" id="discount_type" class="promo-field-select" required>
                        <option value="percentage" <?= old('discount_type') === 'percentage' ? 'selected' : '' ?>>Percentage (%)</option>
                        <option value="amount" <?= old('discount_type') === 'amount' ? 'selected' : '' ?>>Amount (RM)</option>
                    </select>
                </div>

                <div class="mb-4" id="percentage_input">
                    <label class="promo-field-label">Discount (%)</label>
                    <input type="number" name="discount_percentage" class="promo-field-input" min="0" max="100" placeholder="0 – 100" value="<?= esc(old('discount_percentage')) ?>">
                </div>

                <div class="mb-4" id="amount_input" style="display:none;">
                    <label class="promo-field-label">Discount Amount (RM)</label>
                    <input type="number" step="0.01" name="discount_amount" class="promo-field-input" min="0" placeholder="0.00" value="<?= esc(old('discount_amount')) ?>">
                </div>

                <div class="mb-4">
                    <label class="promo-field-label">Valid From</label>
                    <input type="text" id="validation_date" name="validation_date" class="promo-field-input datetimepicker" value="<?= esc(old('validation_date')) ?>" placeholder="Select date & time" required>
                </div>

                <div class="mb-4">
                    <label class="promo-field-label">Expires</label>
                    <input type="text" id="expired_date" name="expired_date" class="promo-field-input datetimepicker" value="<?= esc(old('expired_date')) ?>" placeholder="Select date & time" required>
                </div>

                <div class="promo-form-actions">
                    <a href="<?= base_url('/admin/promo_code') ?>" class="promo-btn-ghost">Cancel</a>
                    <button type="submit" class="promo-btn-gold">Create Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    flatpickr('.datetimepicker', { enableTime: true, dateFormat: 'Y-m-d H:i', time_24hr: true, allowInput: true });

    const typeSelect   = document.getElementById('discount_type');
    const percentInput = document.getElementById('percentage_input');
    const amountInput  = document.getElementById('amount_input');

    function toggleDiscountInputs() {
        if (typeSelect.value === 'percentage') {
            percentInput.style.display = '';
            amountInput.style.display  = 'none';
        } else {
            percentInput.style.display = 'none';
            amountInput.style.display  = '';
        }
    }

    typeSelect.addEventListener('change', toggleDiscountInputs);
    toggleDiscountInputs();
});
</script>

<?= $this->include('admin/footer'); ?>
