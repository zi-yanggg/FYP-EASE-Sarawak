<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Form #<?= esc($refundId) ?></title>

    <link rel="icon" type="image/png" href="<?= base_url('assets/images/cropped-Ease_PNG_File-09.png') ?>">

    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .pdf-wrap {
            width: 100%;
            height: 100vh;
        }

        .pdf-wrap iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <div class="pdf-wrap">
        <iframe src="<?= esc($pdfUrl) ?>"></iframe>
    </div>
</body>
</html>