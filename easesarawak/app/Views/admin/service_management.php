<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/service_management.css') ?>">

<div class="svc-page">
    <div class="ease-page-head d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="ease-crumb">EASE Admin &middot; <b>Service Management</b></div>
            <h1 class="ease-page-title">Service Pricing Management</h1>
        </div>
    </div>

    <div class="svc-card">
        <div class="svc-card__bar">
            <span class="svc-card__title">Service Pricing</span>
        </div>
        <div class="table-responsive">
            <table class="svc-tbl">
                <thead>
                    <tr>
                        <th>Service Type</th>
                        <th>Base Price (RM)</th>
                        <th>Extra Rate (RM / 12 Hours)</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td><span class="svc-name"><?= esc(ucfirst($service['service_type'])) ?></span></td>
                            <td>
                                <span class="svc-price">
                                    <span class="svc-price__prefix">RM</span><?= esc($service['base_price']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="svc-price">
                                    <span class="svc-price__prefix">RM</span><?= esc($service['extra_rate'] ?? 0) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="svc-actions">
                                    <button type="button"
                                        class="svc-act-btn svc-edit-btn"
                                        title="Edit <?= esc(ucfirst($service['service_type'])) ?>"
                                        data-id="<?= esc($service['id']) ?>"
                                        data-type="<?= esc(ucfirst($service['service_type'])) ?>"
                                        data-base="<?= esc($service['base_price']) ?>"
                                        data-extra="<?= esc($service['extra_rate'] ?? 0) ?>"
                                        data-url="<?= base_url('/admin/service_management/update/' . $service['id']) ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="svcEditModal" tabindex="-1" aria-labelledby="svcEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content svc-modal-content">
            <div class="modal-header svc-modal-head">
                <h5 class="modal-title" id="svcEditModalLabel">Edit Service Pricing</h5>
                <button type="button" class="ease-modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="svcEditForm" method="post" action="">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="svc-field-label">Service Type</label>
                        <input type="text" id="svcModalType" class="svc-field-input" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="svc-field-label">Base Price (RM)</label>
                        <input type="number" name="base_price" id="svcModalBase" class="svc-field-input" step="1" min="1" required>
                    </div>
                    <div class="mb-0">
                        <label class="svc-field-label">Extra Rate (RM / 12 Hours)</label>
                        <input type="number" name="extra_rate" id="svcModalExtra" class="svc-field-input" step="1" min="1" required>
                    </div>
                </div>
                <div class="modal-footer svc-modal-foot justify-content-end">
                    <button type="submit" class="svc-btn-gold">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('admin/footer'); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('svcEditModal');

    if (modalEl && modalEl.parentNode !== document.body) {
        document.body.appendChild(modalEl);
    }

    var svcModal = bootstrap.Modal.getOrCreateInstance(modalEl);

    document.querySelectorAll('.svc-edit-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('svcModalType').value  = this.dataset.type;
            document.getElementById('svcModalBase').value  = this.dataset.base;
            document.getElementById('svcModalExtra').value = this.dataset.extra;
            document.getElementById('svcEditForm').action  = this.dataset.url;
            svcModal.show();
        });
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('padding-right');
        document.body.style.removeProperty('overflow');
        document.querySelectorAll('.modal-backdrop').forEach(function (b) { b.remove(); });
    });
});
</script>
