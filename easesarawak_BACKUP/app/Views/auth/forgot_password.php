<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot password | EASE Sarawak</title>
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
                            <h2 class="fw-bold mt-3 ease-auth-page-title">Forgot password</h2>
                            <p class="text-muted">Enter your email to reset your password.</p>
                        </div>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                        <?php endif; ?>
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                        <?php endif; ?>

                        <?= form_open('forgot_password') ?>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                            <?php if (isset($validation) && $validation->hasError('email')): ?>
                                <small class="text-danger"><?= $validation->getError('email') ?></small>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-round btn-block w-100" style="background: #FFE797;">
                            Send Reset Link
                        </button>
                        <?= form_close() ?>

                        <div class="text-center mt-3">
                            <a href="<?= base_url('login') ?>" class="text-muted">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
