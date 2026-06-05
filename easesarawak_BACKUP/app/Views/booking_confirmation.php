<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - EASE SARAWAK</title>
    <link rel="icon" type="image/png" href="assets/images/cropped-Ease_PNG_File-09.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/footer_style.css">
    <link rel="stylesheet" href="assets/css/navbar_style.css">
    
    <style>
        @font-face {
            font-family: 'EurostarRegular';
            src: url('assets/Eurostar Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'EurostarRegular', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .confirmation-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
            min-height: calc(100vh - 200px);
        }

        .success-icon {
            font-size: 4rem;
            color: #28a745;
            margin: 2rem 0;
        }

        .confirmation-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin: 2rem 0;
        }

        .confirmation-card h1 {
            color: #333;
            margin-bottom: 1rem;
        }

        .confirmation-card p {
            color: #666;
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .order-id {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            font-size: 1.1rem;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
            background: #007bff;
            color: white;
            margin: 0.5rem;
        }

        .btn:hover {
            background: #0056b3;
            text-decoration: none;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        @media (max-width: 768px) {
            .confirmation-container {
                padding: 1rem;
            }
            
            .btn {
                display: block;
                margin: 0.5rem 0;
            }
        }
    </style>
</head>

<body>
    <?= $this->include('navbar/navbar') ?>
    
    <main class="confirmation-container">
        <div class="confirmation-card">
            <i class="bi bi-check-circle-fill success-icon"></i>
            <h1>Booking Confirmed!</h1>
            <p>Thank you for your booking. We have received your request and will contact you shortly to confirm the details.</p>
            
            <?php if (isset($order_id) && !empty($order_id)): ?>
                <div class="order-id">
                    <strong>Order ID:</strong> #<?= htmlspecialchars($order_id) ?>
                </div>
                <p>Please keep this Order ID for your records.</p>
            <?php endif; ?>
            
            <p>We will reach out to you via your provided contact information within 24 hours.</p>
            
            <div>
                <a href="<?= base_url() ?>" class="btn">Back to Home</a>
                <a href="<?= base_url('booking') ?>" class="btn btn-secondary">Make Another Booking</a>
            </div>
        </div>
    </main>
    
    <?= $this->include('footer/footer') ?>
</body>
</html>