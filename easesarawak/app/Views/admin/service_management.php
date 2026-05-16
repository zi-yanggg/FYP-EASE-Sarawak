<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/service_management.css') ?>">

<div class="container">
    <div class="ease-page-head d-flex align-items-center justify-content-between flex-wrap gap-2" style="padding-left: 20px;">
        <div>
            <div class="ease-crumb">EASE Admin &middot; <b>Service Management</b></div>
            <h1 class="ease-page-title">Service Pricing Management</h1>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success text-center"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger text-center"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card shadow-sm" style="margin: 10px;">
        <div class="card-body">
            <table class="table table-hover align-middle service-table">
                <thead class="table-light">
                    <tr>
                        <th>Service Type</th>
                        <th>Base Price (RM)</th>
                        <th>Extra Rate (RM / 12 Hours)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($services as $service): ?>
                    <?php $formId = 'service-form-' . (int)$service['id']; ?>
                    <tr>
                        <td><?= esc(ucfirst($service['service_type'])) ?></td>

                        <td>
                            <input
                                type="number"
                                step="1"
                                min="1"
                                name="base_price"
                                value="<?= esc($service['base_price']) ?>"
                                class="form-control d-inline-block"
                                form="<?= esc($formId) ?>"
                                required
                            >
                        </td>

                        <td>
                            <input
                                type="number"
                                step="1"
                                min="1"
                                name="extra_rate"
                                value="<?= esc($service['extra_rate'] ?? 0) ?>"
                                class="form-control d-inline-block"
                                form="<?= esc($formId) ?>"
                                required
                            >
                        </td>

                        <td>
                            <form id="<?= esc($formId) ?>" method="post" action="<?= base_url('/admin/service_management/update/' . $service['id']) ?>" style="display:inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('admin/footer'); ?>