<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #f2be00;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Password Reset Request</h2>
        <p>You requested a password reset for your EASE Sarawak Admin account.</p>
        <p>Click the button below to reset your password. This link expires in <strong>1 hour</strong>.</p>
        <p class="text-center">
            <a href="<?= $resetLink ?>" class="btn">Reset Password</a>
        </p>
        <p>If you didn't request this, ignore this email.</p>
        <hr>
        <small>EASE Sarawak Admin Portal</small>
    </div>
</body>

</html>