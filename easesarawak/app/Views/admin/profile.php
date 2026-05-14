<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/report.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/admin/profile.css') ?>">

<div class="rpt-page container-fluid prof-page--tight-head">

    <!-- ── Page Header ── -->
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div>
            <h1 class="fw-bold mb-0 prof-page-title">Profile</h1>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ── Main Layout ── -->
    <div class="row g-3 align-items-stretch">

        <!-- Left: Profile Overview -->
        <div class="col-lg-8 col-md-7 d-flex">
            <div class="rpt-card prof-card w-100">
                <div class="rpt-card-header">
                    <span class="rpt-title"><i class="fas fa-user me-2"></i>Overview</span>
                </div>
                <div class="card-body prof-card-body d-flex flex-column">
                    <div class="prof-overview d-flex flex-column flex-sm-row align-items-center align-items-sm-start">
                        <!-- Avatar with Edit Overlay -->
                        <div class="prof-avatar-wrap me-sm-4 mb-3 mb-sm-0 flex-shrink-0">
                            <img
                                src="<?= esc($user['profile_picture'] ? base_url($user['profile_picture']) : base_url('assets/images/user.png')) ?>"
                                alt="Profile Picture"
                                class="prof-avatar">
                            <a href="<?= base_url('/edit_profile/' . $user['user_id']) ?>"
                               class="prof-avatar-edit"
                               title="Edit picture"
                               aria-label="Edit picture">
                                <i class="fas fa-camera"></i>
                            </a>
                        </div>

                        <!-- User Details -->
                        <div class="prof-details flex-grow-1 w-100">
                            <div class="prof-name text-center text-sm-start"><?= esc($user['username']) ?></div>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="prof-info-row">
                                        <span class="prof-info-label">Role</span>
                                        <?php
                                            $role       = $user['role'] == '1' ? 'Super Admin' : 'Admin';
                                            $badgeClass = $user['role'] == '1' ? 'prof-badge-super' : 'prof-badge-admin';
                                        ?>
                                        <span><span class="prof-badge <?= $badgeClass ?>"><?= esc($role) ?></span></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="prof-info-row">
                                        <span class="prof-info-label">Email</span>
                                        <span class="prof-info-value text-truncate"><?= esc($user['email']) ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="prof-info-row">
                                        <span class="prof-info-label">Password</span>
                                        <span class="prof-info-value">••••••••</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="prof-info-row">
                                        <span class="prof-info-label">Username</span>
                                        <span class="prof-info-value"><?= esc($user['username']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Button -->
                    <div class="prof-actions mt-auto">
                        <hr class="prof-divider">
                        <a href="<?= base_url('/edit_profile/' . $user['user_id']) ?>" class="btn rpt-export-btn">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Account Info + Security stacked -->
        <div class="col-lg-4 col-md-5 d-flex">
            <div class="prof-side-stack d-flex flex-column gap-3 w-100">
                <div class="rpt-card prof-card flex-fill">
                    <div class="rpt-card-header">
                        <span class="rpt-title"><i class="fas fa-info-circle me-2"></i>Account</span>
                    </div>
                    <div class="card-body prof-card-body prof-info-body">
                        <div class="prof-info-row mb-3">
                            <span class="prof-info-label">Member Since</span>
                            <span class="prof-info-value"><?= date('d M Y', strtotime($user['created_date'])) ?></span>
                        </div>
                        <hr class="prof-divider">
                        <div class="prof-info-row mb-2">
                            <span class="prof-info-label">Account Status</span>
                            <span><span class="prof-badge prof-badge-active">Active</span></span>
                        </div>
                    </div>
                </div>

                <div class="rpt-card prof-card flex-fill">
                    <div class="rpt-card-header">
                        <span class="rpt-title"><i class="fas fa-shield-alt me-2"></i>Security</span>
                    </div>
                    <div class="card-body prof-card-body d-flex flex-column">
                        <p class="prof-security-text">Keep your account secure by regularly updating your password.</p>
                        <div class="mt-auto">
                            <a href="<?= base_url('/change_password') ?>" class="btn rpt-export-btn">
                                <i class="fas fa-key me-1"></i> Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<?= $this->include('admin/footer'); ?>
