<?= $this->include('admin/header'); ?>

<div class="container">
    <div class="ease-page-head d-flex align-items-center justify-content-between flex-wrap gap-2" style="padding-left: 20px;">
        <div>
            <div class="ease-crumb">EASE Admin &middot; Users &middot; <b>Create User</b></div>
            <h1 class="ease-page-title">Create User</h1>
        </div>
        <a href="<?= base_url('/user') ?>" class="btn rpt-export-btn">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('/create_user'); ?>" style="padding: 30px;">
        <div class="mb-3">
            <label for="role" class="form-label"><b>Role</b></label>
            <select name="role" class="form-select" required>
                <option value="">Select Role</option>
                <option value="0">Admin</option>
                <option value="1">Superadmin</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label"><b>Username</b></label>
            <input type="text" name="username" class="form-control" placeholder="Enter username" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label"><b>Email</b></label>
            <input type="email" name="email" class="form-control" placeholder="Enter email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label"><b>Password</b></label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
        </div>

        <button type="submit" class="btn" style="background: #f2be00;"><i class="fas fa-save me-2"></i>Create</button>
        <a href="<?= base_url('admin/user'); ?>" class="btn btn-dark ms-2">Cancel</a>
    </form>
</div>

<?= $this->include('admin/footer'); ?>