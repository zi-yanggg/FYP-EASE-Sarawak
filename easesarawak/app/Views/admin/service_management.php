<?= $this->include('admin/header'); ?>

<style>
    .service-container {
        max-width: 800px;
        margin: 2.5rem auto;
        margin-top: 8rem;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.09);
        padding: 2.5rem 2rem 2rem 2rem;
    }
    .service-container h3 {
        font-weight: 700;
        margin-bottom: 2rem;
        color: #1e88e5;
        text-align: center;
    }
    .service-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.5rem;
    }
    .service-table th, .service-table td {
        border: 1px solid #e0e0e0;
        padding: 0.8rem 0.6rem;
        text-align: left;
    }
    .service-table th {
        background: #f5f7fa;
        font-weight: 600;
        color: #2a3b4c;
    }
    .service-table input[type="number"] {
        width: 90px;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        padding: 0.4rem 0.7rem;
        font-size: 1rem;
        margin-right: 0.5rem;
    }
    .service-table .btn-primary {
        background: #1e88e5;
        border: none;
        padding: 0.4rem 1.2rem;
        font-size: 1rem;
        border-radius: 6px;
        font-weight: 600;
        transition: background 0.2s;
    }
    .service-table .btn-primary:hover {
        background: #1565c0;
    }
    .alert-success {
        margin-bottom: 1.2rem;
    }
</style>

<div class="container mt-5 pt-4">
    <div class="d-flex align-items-center mb-4" style="padding-top: 70px; padding-left: 20px;">
        <h3 class="fw-bold mb-0 me-3"><i class="fas fa-cogs me-2"></i>Service Base Prices</h3>
        <span class="text-muted">Manage base prices for each service</span>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success text-center"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card shadow-sm" style="margin: 10px;">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Service Type</th>
                        <th>Base Price (RM)</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?= esc(ucfirst($service['service_type'])) ?></td>
                        <td>
                            <form method="post" action="<?= base_url('/admin/service_management/update/'.$service['id']) ?>" style="display:inline;">
                                <?= csrf_field() ?>
                                <input type="number" step="1" name="base_price" value="<?= esc($service['base_price']) ?>" class="form-control d-inline-block" style="width: 100px;">
                                <button type="submit" class="btn btn-primary btn-sm ms-2">Save</button>
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