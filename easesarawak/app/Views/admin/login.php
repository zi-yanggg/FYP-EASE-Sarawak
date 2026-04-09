<!doctype html>
<html lang="zh">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EASE SARAWAK | Sign In</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/cropped-Ease_PNG_File-09.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Noto Sans SC', sans-serif;
            background: url("<?= base_url('assets/images/ease_login_background_image.png') ?>") no-repeat center center;
            background-size: cover;
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: flex-end;
            /* moves content to the right */
            align-items: center;
        }

        .card-login {
            width: 100%;
            max-width: 400px;
            margin-right: 5%;
            background-color: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .hero {
            text-align: center;
            padding: 2rem 1rem 1rem;
        }

        .btn-dark {
            background-color: #343a40;
            border-color: #343a40;
        }

        .btn-dark:hover {
            background-color: #23272b;
        }

        @media (max-width: 768px) {
            body {
                justify-content: center;
                /* center form on mobile */
                align-items: center;
                background: url("<?= base_url('assets/images/bg-small-screen-login.png') ?>") no-repeat center center;
                background-size: cover;
            }

            .card-login {
                margin: 1rem;
                width: 90%;
            }
        }
    </style>
</head>

<body>

    <div class="card card-login">
        <div class="hero">
            <h2 class="fw-bold mb-1">Sign In</h2>
            <p class="mb-2">Welcome back! Login to your account.</p>
        </div>

        <div class="card-body p-4">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('login_submit') ?>">
                <?= csrf_field() ?>

                <div class="form-floating mb-3">
                    <input name="email" class="form-control" id="floatingInput" placeholder="Email" required>
                    <label for="floatingInput">Email</label>
                </div>

                <div class="form-floating mb-4">
                    <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                    <label for="floatingPassword">Password</label>
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="remember" value="1" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">
                        Remember me
                    </label>
                </div>

                <button class="btn btn-dark w-100 py-2 mt-2" type="submit">Login</button>
            </form>
            <p class="text-center" style="margin-top: 15px; color: #000;">
                <a href="<?= base_url('forgot_password') ?>" class="text-muted">Forgot Password?</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>