<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password | EASE Sarawak</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/cropped-Ease_PNG_File-09.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/kaiadmin.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/ease-auth-shell.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="ease-auth-shell d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card card-round ease-auth-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="<?= base_url('assets/images/Ease_PNG_File-01-1.png') ?>" alt="Logo" class="logo-img" style="min-height: 100px;">
                            <h2 class="fw-bold mt-3 ease-auth-page-title">Reset password</h2>
                        </div>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                        <?php endif; ?>
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                        <?php endif; ?>

                        <?= form_open('reset_password/' . $token) ?>
                        <input type="hidden" name="token" value="<?= $token ?>">

                        <div class="form-group">
                            <label>New Password</label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('password')): ?>
                                <small class="text-danger"><?= $validation->getError('password') ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label>Confirm Password</label>
                            <div class="input-group">
                                <input type="password" name="confirm_password" class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('confirm_password')): ?>
                                <small class="text-danger"><?= $validation->getError('confirm_password') ?></small>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-round btn-block w-100" style="background: #f2be00df;">
                            Update Password
                        </button>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.closest('.input-group').querySelector('input');
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });
    </script>
</body>

</html>
