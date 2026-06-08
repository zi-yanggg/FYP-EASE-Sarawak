<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/report.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/admin/profile.css') ?>">

<div class="rpt-page container-fluid prof-page--tight-head">

    <!-- ── Page Header ── -->
    <div class="ease-page-head d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="ease-crumb">EASE Admin &middot; Profile &middot; <b>Change Password</b></div>
            <h1 class="mb-0 prof-page-title">Change Password</h1>
        </div>
        <div>
            <a href="<?= base_url('/profile') ?>" class="btn rpt-export-btn">
                <i class="fas fa-arrow-left me-1"></i> Back to Profile
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-3 align-items-stretch">

        <!-- Left: Security Tips Card -->
        <div class="col-lg-4 col-md-5 d-flex">
            <div class="rpt-card prof-card w-100">
                <div class="rpt-card-header">
                    <span class="rpt-title"><i class="fas fa-shield-alt me-2"></i>Security</span>
                </div>
                <div class="card-body prof-card-body d-flex flex-column">
                    <div class="prof-security-icon mb-3">
                        <i class="fas fa-lock"></i>
                    </div>

                    <h5 class="prof-security-heading">Keep Your Account Safe</h5>
                    <p class="prof-security-text">
                        A strong password helps protect your account from unauthorized access.
                    </p>

                    <hr class="prof-divider">

                    <div class="prof-tips-label">Password Tips</div>
                    <ul class="prof-tips-list">
                        <li><i class="fas fa-check-circle"></i> Use at least 8 characters</li>
                        <li><i class="fas fa-check-circle"></i> Mix letters, numbers &amp; symbols</li>
                        <li><i class="fas fa-check-circle"></i> Avoid reusing old passwords</li>
                        <li><i class="fas fa-check-circle"></i> Don't share it with anyone</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right: Password form -->
        <div class="col-lg-8 col-md-7 d-flex">
            <div class="rpt-card prof-card w-100">
                <div class="rpt-card-header">
                    <span class="rpt-title"><i class="fas fa-key me-2"></i>Update</span>
                </div>
                <div class="card-body prof-card-body d-flex flex-column">
                    <?= form_open('change_password', ['id' => 'changePasswordForm', 'class' => 'd-flex flex-column flex-grow-1']) ?>

                    <div class="row g-3">

                        <!-- Current Password -->
                        <div class="col-12">
                            <label for="current_password" class="prof-form-label">
                                Current Password <span class="text-danger">*</span>
                            </label>
                            <div class="prof-pass-group">
                                <input
                                    type="password"
                                    name="current_password"
                                    id="current_password"
                                    class="form-control rpt-input"
                                    autocomplete="current-password"
                                    required>
                                <button type="button" class="prof-pass-toggle toggle-password" tabindex="-1" aria-label="Show password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('current_password')): ?>
                                <small class="text-danger d-block mt-1"><?= $validation->getError('current_password') ?></small>
                            <?php endif; ?>
                        </div>

                        <!-- New Password -->
                        <div class="col-md-6">
                            <label for="new_password" class="prof-form-label">
                                New Password <span class="text-danger">*</span>
                            </label>
                            <div class="prof-pass-group">
                                <input
                                    type="password"
                                    name="new_password"
                                    id="new_password"
                                    class="form-control rpt-input"
                                    minlength="8"
                                    autocomplete="new-password"
                                    required>
                                <button type="button" class="prof-pass-toggle toggle-password" tabindex="-1" aria-label="Show password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('new_password')): ?>
                                <small class="text-danger d-block mt-1"><?= $validation->getError('new_password') ?></small>
                            <?php else: ?>
                                <small class="prof-form-help">Min 8 chars, with uppercase, lowercase, number &amp; symbol.</small>
                            <?php endif; ?>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="col-md-6">
                            <label for="confirm_password" class="prof-form-label">
                                Confirm New Password <span class="text-danger">*</span>
                            </label>
                            <div class="prof-pass-group">
                                <input
                                    type="password"
                                    name="confirm_password"
                                    id="confirm_password"
                                    class="form-control rpt-input"
                                    minlength="8"
                                    autocomplete="new-password"
                                    required>
                                <button type="button" class="prof-pass-toggle toggle-password" tabindex="-1" aria-label="Show password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('confirm_password')): ?>
                                <small class="text-danger d-block mt-1"><?= $validation->getError('confirm_password') ?></small>
                            <?php else: ?>
                                <small class="prof-form-help">Re-enter your new password.</small>
                            <?php endif; ?>
                        </div>

                    </div>

                    <!-- Action Row -->
                    <div class="prof-actions mt-auto">
                        <hr class="prof-divider">
                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            <a href="<?= base_url('/profile') ?>" class="btn rpt-cancel-btn">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn rpt-export-btn">
                                <i class="fas fa-key me-1"></i> Update Password
                            </button>
                        </div>
                    </div>

                    <?= form_close() ?>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Toggle Password Visibility -->
<script>
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.closest('.prof-pass-group').querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
                this.setAttribute('aria-label', 'Hide password');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
                this.setAttribute('aria-label', 'Show password');
            }
        });
    });
</script>

<?= $this->include('admin/footer'); ?>
