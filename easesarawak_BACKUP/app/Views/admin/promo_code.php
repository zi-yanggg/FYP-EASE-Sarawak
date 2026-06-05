<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/promo_code.css') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="promo-page">
    <div class="ease-page-head d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="ease-crumb">EASE Admin &middot; <b>Promo Codes</b></div>
            <h1 class="ease-page-title">Promo Code Management</h1>
        </div>
    </div>

    <div class="promo-card">
        <div class="promo-card__bar">
            <div class="promo-srch">
                <i class="fa fa-search"></i>
                <input type="text" id="promoSearch" placeholder="Search promo codes...">
            </div>
            <a href="#" class="promo-btn-add" data-bs-toggle="modal" data-bs-target="#createPromoModal">
                <i class="fa fa-plus"></i> Add Promo
            </a>
        </div>

        <div class="table-responsive">
            <table class="promo-tbl" id="promoTable">
                <thead>
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
                            <?php
                                $dtype     = $p['discount_type'] ?? 'percentage';
                                $validFrom = !empty($p['validation_date']) ? date('Y-m-d H:i', strtotime($p['validation_date'])) : '';
                                $expires   = !empty($p['expired_date'])    ? date('Y-m-d H:i', strtotime($p['expired_date']))    : '';
                            ?>
                            <tr>
                                <td><?= esc($p['id']) ?></td>
                                <td><span class="promo-code-badge"><?= esc($p['code']) ?></span></td>
                                <td>
                                    <span class="promo-type-pill <?= $dtype === 'amount' ? 'promo-type-pill--amt' : 'promo-type-pill--pct' ?>">
                                        <?= $dtype === 'amount' ? 'Amount' : 'Percent' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($dtype === 'amount'): ?>
                                        RM<?= esc($p['discount_amount'] ?? '0.00') ?>
                                    <?php else: ?>
                                        <?= esc($p['discount_percentage'] ?? '0') ?>%
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($p['validation_date'] ?? '') ?></td>
                                <td><?= esc($p['expired_date'] ?? '') ?></td>
                                <td><?= esc($p['created_date'] ?? $p['created_at'] ?? '') ?></td>
                                <td class="text-center">
                                    <div class="promo-actions">
                                        <button type="button" class="promo-act-btn edit-promo-btn" title="Edit"
                                            data-id="<?= esc($p['id']) ?>"
                                            data-code="<?= esc($p['code'], 'attr') ?>"
                                            data-discount-type="<?= esc($dtype, 'attr') ?>"
                                            data-discount-pct="<?= esc($p['discount_percentage'] ?? '', 'attr') ?>"
                                            data-discount-amt="<?= esc($p['discount_amount'] ?? '', 'attr') ?>"
                                            data-valid-from="<?= esc($validFrom, 'attr') ?>"
                                            data-expires="<?= esc($expires, 'attr') ?>">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <a href="<?= base_url('/admin/promo_code/delete/' . $p['id']) ?>"
                                           class="promo-act-btn promo-act-btn--delete promo-delete-btn"
                                           data-id="<?= esc($p['id']) ?>" title="Delete">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="promo-empty">No promo codes found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ── Create Promo Modal ──────────────────────────────────── -->
<div class="modal fade" id="createPromoModal" tabindex="-1" aria-labelledby="createPromoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content promo-modal-content">
            <div class="modal-header promo-modal-head">
                <h5 class="modal-title" id="createPromoModalLabel">
                    <i class="fas fa-plus me-2"></i>Add Promo Code
                </h5>
                <button type="button" class="ease-modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body px-4 py-3">
                <div id="createPromoErrors" class="promo-modal-errors" style="display:none;"></div>
                <form id="createPromoForm">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="promo-field-label">Code</label>
                        <input type="text" id="createPromoCode" name="code" class="promo-field-input" required placeholder="e.g. SAVE20">
                    </div>

                    <div class="mb-3">
                        <label class="promo-field-label">Discount Type</label>
                        <select id="createPromoType" name="discount_type" class="promo-field-select" required>
                            <option value="percentage">Percentage (%)</option>
                            <option value="amount">Amount (RM)</option>
                        </select>
                    </div>

                    <div class="mb-3" id="createPromoPercentBlock">
                        <label class="promo-field-label">Discount (%)</label>
                        <input type="number" id="createPromoPct" name="discount_percentage"
                               class="promo-field-input" min="0" max="100" placeholder="0 – 100">
                    </div>

                    <div class="mb-3" id="createPromoAmtBlock" style="display:none;">
                        <label class="promo-field-label">Discount Amount (RM)</label>
                        <input type="number" id="createPromoAmt" name="discount_amount"
                               class="promo-field-input" step="0.01" min="0" placeholder="0.00">
                    </div>

                    <div class="mb-3">
                        <label class="promo-field-label">Valid From</label>
                        <input type="text" id="createPromoValidFrom" name="validation_date"
                               class="promo-field-input" placeholder="Select date & time" required>
                    </div>

                    <div class="mb-3">
                        <label class="promo-field-label">Expires</label>
                        <input type="text" id="createPromoExpires" name="expired_date"
                               class="promo-field-input" placeholder="Select date & time" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer promo-modal-foot justify-content-end">
                <button type="button" id="saveCreatePromoBtn" class="promo-btn-gold">
                    <i class="fas fa-plus me-1"></i>Create Promo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ── Edit Promo Modal ──────────────────────────────────── -->
<div class="modal fade" id="editPromoModal" tabindex="-1" aria-labelledby="editPromoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content promo-modal-content">
            <div class="modal-header promo-modal-head">
                <h5 class="modal-title" id="editPromoModalLabel">
                    <i class="fas fa-tag me-2"></i>Edit Promo Code
                </h5>
                <button type="button" class="ease-modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body px-4 py-3">
                <div id="editPromoErrors" class="promo-modal-errors" style="display:none;"></div>
                <form id="editPromoForm">
                    <?= csrf_field() ?>
                    <input type="hidden" id="editPromoId">

                    <div class="mb-3">
                        <label class="promo-field-label">Code</label>
                        <input type="text" id="editPromoCode" name="code" class="promo-field-input" required>
                    </div>

                    <div class="mb-3">
                        <label class="promo-field-label">Discount Type</label>
                        <select id="editPromoType" name="discount_type" class="promo-field-select" required>
                            <option value="percentage">Percentage (%)</option>
                            <option value="amount">Amount (RM)</option>
                        </select>
                    </div>

                    <div class="mb-3" id="editPromoPercentBlock">
                        <label class="promo-field-label">Discount (%)</label>
                        <input type="number" id="editPromoPct" name="discount_percentage"
                               class="promo-field-input" min="0" max="100" placeholder="0 – 100">
                    </div>

                    <div class="mb-3" id="editPromoAmtBlock" style="display:none;">
                        <label class="promo-field-label">Discount Amount (RM)</label>
                        <input type="number" id="editPromoAmt" name="discount_amount"
                               class="promo-field-input" step="0.01" min="0" placeholder="0.00">
                    </div>

                    <div class="mb-3">
                        <label class="promo-field-label">Valid From</label>
                        <input type="text" id="editPromoValidFrom" name="validation_date"
                               class="promo-field-input" placeholder="Select date & time" required>
                    </div>

                    <div class="mb-3">
                        <label class="promo-field-label">Expires</label>
                        <input type="text" id="editPromoExpires" name="expired_date"
                               class="promo-field-input" placeholder="Select date & time" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer promo-modal-foot justify-content-end">
                <button type="button" id="saveEditPromoBtn" class="promo-btn-gold">
                    <i class="fas fa-check me-1"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->include('admin/footer'); ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
(function () {
    var PROMO_UPDATE_URL = '<?= base_url('/admin/promo_code/update_ajax/') ?>';
    var PROMO_STORE_URL  = '<?= base_url('/admin/promo_code/store_ajax') ?>';

    /* ── DataTable ── */
    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery && $.fn.DataTable && document.querySelector('#promoTable')) {
            var table = $('#promoTable').DataTable({
                pageLength: 10,
                order: [[0, 'desc']],
                info: false,
                stripeClasses: [],
                pagingType: 'full_numbers',
                language: {
                    paginate: {
                        first:    '<i class="bi bi-chevron-double-left"></i>',
                        previous: '<i class="bi bi-chevron-left"></i>',
                        next:     '<i class="bi bi-chevron-right"></i>',
                        last:     '<i class="bi bi-chevron-double-right"></i>'
                    }
                }
            });
            document.getElementById('promoSearch').addEventListener('keyup', function () {
                table.search(this.value).draw();
            });
        }
    });

    /* ── Delete confirm ── */
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.promo-delete-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this promo code?')) {
                    window.location.href = this.getAttribute('href');
                }
            });
        });
    });

    /* ── Create modal ── */
    document.addEventListener('DOMContentLoaded', function () {
        var createModalEl = document.getElementById('createPromoModal');
        var createErrDiv  = document.getElementById('createPromoErrors');
        var createType    = document.getElementById('createPromoType');

        if (createModalEl && createModalEl.parentNode !== document.body) {
            document.body.appendChild(createModalEl);
        }

        var fpCreateFrom    = flatpickr('#createPromoValidFrom', { enableTime: true, dateFormat: 'Y-m-d H:i', time_24hr: true, allowInput: true });
        var fpCreateExpires = flatpickr('#createPromoExpires',   { enableTime: true, dateFormat: 'Y-m-d H:i', time_24hr: true, allowInput: true });

        function toggleCreateInputs() {
            var isAmt = createType.value === 'amount';
            document.getElementById('createPromoPercentBlock').style.display = isAmt ? 'none' : '';
            document.getElementById('createPromoAmtBlock').style.display     = isAmt ? ''     : 'none';
        }
        createType.addEventListener('change', toggleCreateInputs);

        createModalEl.addEventListener('show.bs.modal', function () {
            createErrDiv.style.display = 'none';
            createErrDiv.innerHTML     = '';
            document.getElementById('createPromoForm').reset();
            fpCreateFrom.clear();
            fpCreateExpires.clear();
            createType.value = 'percentage';
            toggleCreateInputs();
        });

        document.getElementById('saveCreatePromoBtn').addEventListener('click', function () {
            var formData = new FormData(document.getElementById('createPromoForm'));
            fetch(PROMO_STORE_URL, {
                method:  'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body:    formData
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    bootstrap.Modal.getOrCreateInstance(createModalEl).hide();
                    window.location.reload();
                } else {
                    createErrDiv.innerHTML     = (data.errors || ['An error occurred.']).join('<br>');
                    createErrDiv.style.display = '';
                }
            })
            .catch(function () {
                createErrDiv.innerHTML     = 'Network error. Please try again.';
                createErrDiv.style.display = '';
            });
        });

        createModalEl.addEventListener('hidden.bs.modal', function () {
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
            document.body.style.removeProperty('overflow');
            document.querySelectorAll('.modal-backdrop').forEach(function (b) { b.remove(); });
        });
    });

    /* ── Edit modal ── */
    document.addEventListener('DOMContentLoaded', function () {
        var modalEl    = document.getElementById('editPromoModal');
        var errDiv     = document.getElementById('editPromoErrors');
        var typeSelect = document.getElementById('editPromoType');

        if (modalEl && modalEl.parentNode !== document.body) {
            document.body.appendChild(modalEl);
        }

        var fpFrom    = flatpickr('#editPromoValidFrom', { enableTime: true, dateFormat: 'Y-m-d H:i', time_24hr: true, allowInput: true });
        var fpExpires = flatpickr('#editPromoExpires',   { enableTime: true, dateFormat: 'Y-m-d H:i', time_24hr: true, allowInput: true });

        function toggleDiscountInputs() {
            var isAmt = typeSelect.value === 'amount';
            document.getElementById('editPromoPercentBlock').style.display = isAmt ? 'none' : '';
            document.getElementById('editPromoAmtBlock').style.display     = isAmt ? ''     : 'none';
        }
        typeSelect.addEventListener('change', toggleDiscountInputs);

        document.querySelectorAll('.edit-promo-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                errDiv.style.display = 'none';
                errDiv.innerHTML     = '';

                document.getElementById('editPromoId').value   = this.dataset.id;
                document.getElementById('editPromoCode').value = this.dataset.code;
                typeSelect.value                               = this.dataset.discountType;
                document.getElementById('editPromoPct').value  = this.dataset.discountPct;
                document.getElementById('editPromoAmt').value  = this.dataset.discountAmt;
                fpFrom.setDate(this.dataset.validFrom, true);
                fpExpires.setDate(this.dataset.expires, true);

                toggleDiscountInputs();

                document.getElementById('editPromoModalLabel').innerHTML =
                    '<i class="fas fa-tag me-2"></i>Edit: ' + this.dataset.code;

                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            });
        });

        document.getElementById('saveEditPromoBtn').addEventListener('click', function () {
            var id       = document.getElementById('editPromoId').value;
            var formData = new FormData(document.getElementById('editPromoForm'));

            fetch(PROMO_UPDATE_URL + id, {
                method:  'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body:    formData
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                    window.location.reload();
                } else {
                    errDiv.innerHTML     = (data.errors || ['An error occurred.']).join('<br>');
                    errDiv.style.display = '';
                }
            })
            .catch(function () {
                errDiv.innerHTML     = 'Network error. Please try again.';
                errDiv.style.display = '';
            });
        });

        modalEl.addEventListener('hidden.bs.modal', function () {
            errDiv.style.display = 'none';
            errDiv.innerHTML     = '';
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
            document.body.style.removeProperty('overflow');
            document.querySelectorAll('.modal-backdrop').forEach(function (b) { b.remove(); });
        });
    });
})();
</script>
