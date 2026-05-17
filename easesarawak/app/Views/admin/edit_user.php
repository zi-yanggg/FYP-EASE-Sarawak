<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/user.css') ?>">

<div class="ord-page">
    <div class="ease-page-head d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="ease-crumb">EASE Admin &middot; Users &middot; <b>Edit User</b></div>
            <h1 class="ease-page-title">Edit User</h1>
        </div>
        <a href="<?= base_url('/user') ?>" class="btn rpt-export-btn">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
        </a>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="usr-flash usr-flash--info">
            <i class="fas fa-check-circle"></i>
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="usr-flash usr-flash--error">
            <i class="fas fa-exclamation-circle"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php $errors = session()->getFlashdata('errors'); ?>
    <?php if (!empty($errors)): ?>
        <div class="usr-flash usr-flash--error">
            <i class="fas fa-exclamation-circle"></i>
            <ul class="mb-0 ps-3">
                <?php foreach ($errors as $e): ?>
                    <li><?= esc($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="usr-card">
        <form action="<?= base_url('/update_user/' . $user['user_id']) ?>" method="post" style="padding: 28px 24px;">

            <div class="mb-3">
                <label class="form-label fw-bold">Username</label>
                <input type="text" name="username" class="form-control"
                       value="<?= esc(old('username', $user['username'])) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= esc(old('email', $user['email'])) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Role</label>
                <select name="role" class="form-select" required>
                    <option value="0" <?= (old('role', $user['role'])) == 0 ? 'selected' : '' ?>>Admin</option>
                    <option value="1" <?= (old('role', $user['role'])) == 1 ? 'selected' : '' ?>>Superadmin</option>
                </select>
            </div>

            <button type="submit" class="btn" style="background:#F2BE00;color:#0A0A0A;font-weight:700;">
                <i class="fas fa-save me-1"></i> Update User
            </button>
            <a href="<?= base_url('/user') ?>" class="btn btn-dark ms-2" style="color:#fff;">Cancel</a>
        </form>
    </div>
</div>

<?= $this->include('admin/footer'); ?>
