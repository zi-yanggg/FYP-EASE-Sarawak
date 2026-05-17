<?= $this->include('admin/header'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/user.css') ?>">

<?php
$palette = ['#5B532C', '#B8860B', '#0A0A0A', '#1A6CB0', '#2BA869', '#6A4FBB'];
function usrAvColor(int $uid, array $palette): string {
    return $palette[$uid % count($palette)];
}
function usrAvFg(string $bg): string {
    return $bg === '#0A0A0A' ? '#F2BE00' : '#fff';
}
function usrInitials(string $username): string {
    return strtoupper(substr($username, 0, 2));
}
?>

<div class="ord-page">

    <!-- ── Page Head ──────────────────────────────────────── -->
    <div class="ease-page-head d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="ease-crumb">EASE Admin &middot; <b>Users</b></div>
            <h1 class="ease-page-title">User Management</h1>
        </div>
        <?php if (session()->get('role') === '1'): ?>
        <a href="<?= base_url('/create_user') ?>" class="btn rpt-export-btn">
            <i class="fas fa-user-plus me-1"></i> Add User
        </a>
        <?php endif; ?>
    </div>

    <!-- ── Flash Messages ────────────────────────────────── -->
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

    <!-- ── Card ──────────────────────────────────────────── -->
    <div class="usr-card">

        <!-- Card Bar -->
        <form method="get" action="<?= base_url('/user') ?>" id="usrSearchForm">
        <div class="usr-card__bar">
            <div class="usr-srch">
                <i class="fas fa-search"></i>
                <input type="text" name="search" id="usrSearch"
                       placeholder="Search by username or email…"
                       value="<?= esc($search ?? '') ?>"
                       autocomplete="off">
                <?php if (!empty($search)): ?>
                    <a href="<?= base_url('/user') ?>" class="usr-srch-clear" title="Clear search">
                        <i class="fas fa-times"></i>
                    </a>
                <?php endif; ?>
            </div>
            <span class="usr-count">
                <span id="usrVisibleCount"><?= count($users) ?></span> <?= !empty($search) ? 'result' . (count($users) !== 1 ? 's' : '') : 'users' ?>
            </span>
        </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="usr-tbl" id="usrTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Created</th>
                        <th>Modified</th>
                        <?php if (session()->get('role') === '1'): ?>
                        <th class="text-center">Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $index => $user): ?>
                            <?php
                            $uid   = (int)$user['user_id'];
                            $avBg  = usrAvColor($uid, $palette);
                            $avFg  = usrAvFg($avBg);
                            $inits = usrInitials($user['username'] ?? '?');
                            $isSuper = $user['role'] == 1;
                            ?>
                            <tr>
                                <td><span class="usr-idx"><?= $index + 1 ?></span></td>

                                <td>
                                    <div class="usr-who">
                                        <?php if (!empty($user['profile_picture'])): ?>
                                            <img src="<?= esc(base_url($user['profile_picture'])) ?>"
                                                 alt="<?= esc($inits) ?>"
                                                 class="usr-av usr-av--img">
                                        <?php else: ?>
                                            <span class="usr-av" style="background:<?= esc($avBg) ?>;color:<?= esc($avFg) ?>">
                                                <?= esc($inits) ?>
                                            </span>
                                        <?php endif; ?>
                                        <span class="usr-name"><?= esc($user['username']) ?></span>
                                    </div>
                                </td>

                                <td>
                                    <span class="usr-role-pill <?= $isSuper ? 'usr-role--super' : 'usr-role--admin' ?>">
                                        <?= $isSuper ? 'Superadmin' : 'Admin' ?>
                                    </span>
                                </td>

                                <td class="usr-email"><?= esc($user['email']) ?></td>

                                <td class="usr-date">
                                    <?= date('d M Y', strtotime($user['created_date'])) ?>
                                    <span class="usr-date__time"><?= date('g:i A', strtotime($user['created_date'])) ?></span>
                                </td>

                                <td class="usr-date">
                                    <?php if (empty($user['modified_date'])): ?>
                                        <span class="usr-date--na">—</span>
                                    <?php else: ?>
                                        <?= date('d M Y', strtotime($user['modified_date'])) ?>
                                        <span class="usr-date__time"><?= date('g:i A', strtotime($user['modified_date'])) ?></span>
                                    <?php endif; ?>
                                </td>

                                <?php if (session()->get('role') === '1'): ?>
                                <td>
                                    <div class="usr-actions">
                                        <a href="<?= base_url('edit_user/' . $uid) ?>"
                                           class="usr-act-btn usr-act-btn--edit"
                                           title="Edit user">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <?php if (!$isSuper): ?>
                                        <a href="<?= base_url('delete_user/' . $uid) ?>"
                                           class="usr-act-btn usr-act-btn--del"
                                           title="Delete user"
                                           onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="99" class="usr-empty">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($pager): ?>
        <div class="usr-pager">
            <?= $pager->links('group1', 'pagination') ?>
        </div>
        <?php endif; ?>

    </div>
</div>

<script>
(function () {
    'use strict';
    var input = document.getElementById('usrSearch');
    var form  = document.getElementById('usrSearchForm');
    if (!input || !form) return;
    var timer;
    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(function () { form.submit(); }, 400);
    });
}());
</script>

<?= $this->include('admin/footer'); ?>
