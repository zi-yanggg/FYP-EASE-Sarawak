<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/report.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/admin/profile.css') ?>">

<div class="rpt-page container-fluid prof-page--tight-head">

    <!-- ── Page Header ── -->
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div>
            <h1 class="fw-bold mb-0 prof-page-title">Edit</h1>
        </div>
        <div>
            <a href="<?= base_url('/profile') ?>" class="btn rpt-export-btn">
                <i class="fas fa-arrow-left me-1"></i> Profile
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?= form_open_multipart('update_profile/' . $user['user_id'], ['id' => 'editProfileForm']) ?>

    <div class="row g-3 align-items-stretch">

        <!-- Left: Profile Picture Card -->
        <div class="col-lg-4 col-md-5 d-flex">
            <div class="rpt-card prof-card w-100">
                <div class="rpt-card-header">
                    <span class="rpt-title"><i class="fas fa-camera me-2"></i>Picture</span>
                </div>
                <div class="card-body prof-card-body d-flex flex-column align-items-center text-center">

                    <div class="prof-avatar-wrap mb-3">
                        <img
                            src="<?= esc($user['profile_picture'] ? base_url($user['profile_picture']) : base_url('assets/images/user.png')) ?>"
                            alt="Profile Picture"
                            id="profilePreview"
                            class="prof-avatar">
                        <label for="profile_picture"
                               class="prof-avatar-edit"
                               title="Change Photo"
                               aria-label="Change Photo">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input
                            type="file"
                            name="profile_picture"
                            id="profile_picture"
                            class="d-none"
                            accept="image/*"
                            onchange="previewImage(event)">
                    </div>

                    <div class="prof-name mb-1"><?= esc($user['username']) ?></div>
                    <?php
                        $role       = $user['role'] == '1' ? 'Super Admin' : 'Admin';
                        $badgeClass = $user['role'] == '1' ? 'prof-badge-super' : 'prof-badge-admin';
                    ?>
                    <span class="prof-badge <?= $badgeClass ?> mb-3"><?= esc($role) ?></span>

                    <hr class="prof-divider w-100">

                    <p class="prof-security-text mb-3">
                        Upload a new profile photo. Max size: 2MB. JPG or PNG format.
                    </p>

                    <label for="profile_picture" class="btn rpt-export-btn">
                        <i class="fas fa-upload me-1"></i> Change Photo
                    </label>
                </div>
            </div>
        </div>

        <!-- Right: Account Details Card -->
        <div class="col-lg-8 col-md-7 d-flex">
            <div class="rpt-card prof-card w-100">
                <div class="rpt-card-header">
                    <span class="rpt-title"><i class="fas fa-user-edit me-2"></i>Details</span>
                </div>
                <div class="card-body prof-card-body d-flex flex-column">

                    <div class="row g-3">

                        <!-- Username -->
                        <div class="col-md-6">
                            <label for="username" class="prof-form-label">
                                Username <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                name="username"
                                id="username"
                                class="form-control rpt-input"
                                value="<?= esc(old('username', $user['username'])) ?>"
                                required>
                            <?php if (isset($validation) && $validation->hasError('username')): ?>
                                <small class="text-danger d-block mt-1"><?= $validation->getError('username') ?></small>
                            <?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="prof-form-label">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control rpt-input"
                                value="<?= esc(old('email', $user['email'])) ?>"
                                required>
                            <?php if (isset($validation) && $validation->hasError('email')): ?>
                                <small class="text-danger d-block mt-1"><?= $validation->getError('email') ?></small>
                            <?php endif; ?>
                        </div>

                        <!-- Role -->
                        <div class="col-md-6">
                            <label for="role" class="prof-form-label">Role</label>
                            <input
                                type="text"
                                id="role"
                                class="form-control rpt-input"
                                value="<?= $user['role'] == '1' ? 'Super Admin' : 'Admin' ?>"
                                disabled>
                            <input type="hidden" name="role" value="<?= esc($user['role']) ?>">
                            <small class="prof-form-help">Role is managed by Super Admin and cannot be changed here.</small>
                        </div>

                        <!-- Member Since -->
                        <div class="col-md-6">
                            <label class="prof-form-label">Member Since</label>
                            <input
                                type="text"
                                class="form-control rpt-input"
                                value="<?= !empty($user['created_date']) ? date('d M Y', strtotime($user['created_date'])) : '—' ?>"
                                disabled>
                            <small class="prof-form-help">Read-only — your account creation date.</small>
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
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <?= form_close() ?>

</div>

<!-- Image Preview Script -->
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('profilePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(file);
    }
</script>

<?= $this->include('admin/footer'); ?>
