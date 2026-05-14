<?= $this->include('admin/header'); ?>

<?php
function pretty_label($key)
{
    $key = preg_replace('/([a-z])([A-Z])/', '$1 $2', $key);
    $key = str_replace('_', ' ', $key);
    return ucwords($key);
}
?>

<div class="container mt-4">
    <div class="page-inner" style="padding-top: 80px;">
        <div class="ease-page-head d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <div class="ease-crumb">EASE Admin &middot; Orders &middot; <b>#<?= esc($order['order_id']) ?></b></div>
                <h1 class="ease-page-title">Order Details</h1>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= base_url('/admin/refund_request') ?>" class="btn btn-cancel">
                    <i class="fa fa-arrow-left me-1"></i>Refund Request
                </a>
                <a href="<?= base_url('/order') ?>" class="btn rpt-export-btn">
                    <i class="fa fa-list me-1"></i>Order List
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3 rounded-3">
            <div class="card-header fw-semibold" style="background: #f2be00ff;">
                Customer Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p><strong>First Name:</strong> <?= esc($order['first_name'] ?? '-') ?></p>
                        <p><strong>Last Name:</strong> <?= esc($order['last_name'] ?? '-') ?></p>
                        <p><strong>Email:</strong> <?= esc($order['email'] ?? '-') ?></p>
                        <p><strong>Phone:</strong> <?= esc($order['phone'] ?? '-') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>ID Number:</strong> <?= esc($order['id_num'] ?? '-') ?></p>
                        <p><strong>Social:</strong>
                            <?=
                                ($order['social'] ?? '') == 1 ? 'WhatsApp' :
                                (($order['social'] ?? '') == 2 ? 'WeChat' :
                                (($order['social'] ?? '') == 3 ? 'LINE' : 'Unknown'))
                            ?>
                        </p>
                        <p><strong>Social Number:</strong> <?= esc($order['social_num'] ?? '-') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3 rounded-3">
            <div class="card-header fw-semibold" style="background: #f2be00ff;">
                Order Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p><strong>Service Type:</strong> <?= esc($order['service_type'] ?? '-') ?></p>
                        <p><strong>Special:</strong> <?= esc($order['special'] ?? '-') ?></p>
                        <p><strong>Special Note:</strong> <?= esc($order['special_note'] ?? '-') ?></p>
                        <p><strong>Promo Code:</strong> <?= esc($order['promo_code'] ?? '-') ?></p>
                        <p><strong>Created Date:</strong> <?= esc($order['created_date'] ?? '-') ?></p>
                        <p><strong>Last Modified:</strong> <?= esc($order['modified_date'] ?? '-') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <strong>Status:</strong>
                            <?php if (($order['status'] ?? null) == 0): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php elseif (($order['status'] ?? null) == 1): ?>
                                <span class="badge bg-primary">In Progress</span>
                            <?php else: ?>
                                <span class="badge bg-success">Completed</span>
                            <?php endif; ?>
                        </p>
                        <p><strong>Amount:</strong> RM<?= esc($order['amount'] ?? '0.00') ?></p>
                        <p><strong>Payment Method:</strong> <?= esc($order['payment_method'] ?? '-') ?></p>
                        <p>
                            <strong>Upload:</strong>
                            <?php if (!empty($order['upload'])): ?>
                                <a href="<?= base_url('uploads/' . $order['upload']) ?>" target="_blank">View File</a>
                            <?php else: ?>
                                No file uploaded
                            <?php endif; ?>
                        </p>
                        <p><strong>Modified By:</strong> <?= esc($order['modified_by_username'] ?? '-') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3 rounded-3">
            <div class="card-header fw-semibold" style="background: #f2be00ff;">
                Order Details
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <tbody>
                            <?php if (!empty($details)): ?>
                                <?php foreach ($details as $key => $value): ?>
                                    <tr>
                                        <td class="fw-semibold" style="width: 35%;">
                                            <?= esc(pretty_label($key)) ?>
                                        </td>
                                        <td>
                                            <?= esc(is_array($value) ? json_encode($value) : ($value !== '' ? $value : '-')) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="text-muted text-center">No order details found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header fw-semibold" style="background: #f2be00ff;">
                Comment
            </div>
            <div class="card-body">
                <div class="bg-light p-3 rounded" style="white-space: pre-wrap;">
                    <?= esc($order['comment'] ?? '-') ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('admin/footer'); ?>