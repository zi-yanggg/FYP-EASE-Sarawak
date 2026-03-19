<?= $this->include('admin/header'); ?>

<div class="container mt-5">
    <h3 class="fw-bold mb-4" style="padding-top: 60px; padding-left: 20px;"><i class="fas fa-user-plus me-2"></i>Create User</h3>

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