<?= $this->include('admin/header'); ?>

<div class="container mt-5 pt-4">
    <div class="d-flex align-items-center mb-4" style="padding-top: 70px; padding-left: 20px;">
        <h3 class="fw-bold mb-0 me-3"><i class="fas fa-users me-2"></i>User Management</h3>
        <span class="text-muted">View all registered users</span>
    </div>

    <?php
    if (session()->getFlashdata('message')): ?>
        <div class="alert alert-info text-center"><?= session()->getFlashdata('message') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger text-center"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card shadow-sm" style="margin: 10px;">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Created Date</th>
                        <th>Modified Date</th>
                        <?php if (session()->get('role') === '1'): ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $index => $user): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($user['username']) ?></td>
                                <td>
                                    <!-- <span class="badge <?= $user['role'] == 1 ? 'badge-superadmin' : 'badge-admin' ?>"> -->
                                    <span>
                                        <?= $user['role'] == 1 ? 'Superadmin' : 'Admin' ?>
                                    </span>
                                </td>
                                <td><?= esc($user['email']) ?></td>
                                <td><?= date('M d, Y, g.i a', strtotime(esc($user['created_date']))) ?></td>
                                <?php if (empty($user['modified_date'])): ?>
                                    <td>-</td>
                                <?php else: ?>
                                    <td><?= date('M d, Y, g.i a', strtotime(esc($user['modified_date']))) ?></td>
                                <?php endif; ?>
                                <?php if (session()->get('role') === '1'): ?>
                                    <td>
                                        <a href="<?= base_url('edit_user/' . $user['user_id']); ?>" class="btn btn-sm" style="background: #f2be00; font-size:15px;"><i class="fa fa-edit"></i></a>
                                        <a href="<?= base_url('delete_user/' . $user['user_id']); ?>" class="btn btn-sm" style="color: #fff; background: #900707ff; font-size:15px;" title="Delete User" onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No users found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            <?= $pager->links('group1', 'pagination') ?>
        </div>
    </div>
</div>

<?= $this->include('admin/footer'); ?>