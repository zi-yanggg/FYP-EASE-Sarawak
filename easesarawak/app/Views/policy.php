<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EASE SARAWAK | Privacy Policy</title>
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

        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'EurostarRegular', sans-serif, Arial, 'BebasKai';
            line-height: 1.6;
        }

        /* Title section */
        .about-title {
            position: relative;
            height: 40vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            background-image: url("assets/images/man-pulling-out-hand-luggage-from-compartment-while-traveling-by-plane-e1726141753879.jpg");
            background-size: cover;
            background-position: center;
            margin-top: 90px;
        }

        .title-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            /* semi-transparent black */
            z-index: 1;
        }

        .about-title h1 {
            position: relative;
            z-index: 2;
            font-size: 3rem;
            letter-spacing: 1px;
        }

        .pill-title {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            gap: 15px;
            /* space between text and dots */
            background: #fff;
            border-radius: 50px;
            padding: 10px 25px;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #000;
            text-transform: uppercase;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .pill-title .dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #f2be00;
            /* yellow color */
            display: inline-block;
        }

        .content-container {
            padding: 70px 50px;
        }

        .content-container h2 {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #333;
        }

        .content-container h3 {
            font-size: 2rem;
            margin-top: 30px;
            margin-bottom: 15px;
            color: #333;
        }

        .content-container p {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #555;
        }

        @media (max-width: 768px) {
            .about-title{
                margin-top: 70px;
            }
        }
    </style>
</head>

<body>
    <?= $this->include('navbar/navbar'); ?>

    <!-- Title -->
    <section class="about-title">
        <div class="title-overlay"></div>
        <h1>Privacy Policy</h1>
    </section>

    <!-- Content -->
    <section class="content">
        <div class="content-container">
            <h2>Introduction</h2>
            <p>Welcome to EASE, your trusted partner for seamless baggage storage and delivery in Kuching.
                We are committed to protecting your privacy and ensuring that your personal information is handled securely.
                This Privacy Policy outlines how we collect, use, disclose, and protect your information when you use our website and services.
            </p>

            <h3>Information We Collect</h3>
            <p>We collect various types of information to provide and improve our services:<br>
                <strong>Personal Information:</strong> When you use our services, you may provide us with personal details such as your name, contact information, and payment details.
                <br><strong>Usage Data:</strong> We collect information about how you interact with our website, including your IP address, browser type, and browsing activity.
                <br><strong>Cookies and Tracking Technologies:</strong> We use cookies and similar technologies to enhance your experience on our website.
                You can manage your cookie preferences through your browser settings.
            </p>

            <h3>How We Use Your Information</h3>
            <p>
                We use your information for the following purposes:
            </p>
            <p>
                <strong>To Provide Services:</strong> To process and manage your baggage storage and delivery requests.<br>
                <strong>To Improve Our Website:</strong> To analyze usage trends and improve the functionality of our site.<br>
                <strong>To Communicate with You:</strong> To send updates, confirmations, and promotional materials related to our services.<br>
                <strong>To Ensure Security:</strong> To detect, prevent, and address fraudulent activities and other security issues.
            </p>

            <h3>Information Sharing and Disclosure</h3>
            <p>
                We may share your information in the following circumstances:
            </p>
            <p>
                <strong>Service Providers:</strong> We may share your information with third-party service providers who assist us in delivering our services,
                such as payment processors and IT support.<br>
                <strong>Legal Requirements:</strong> We may disclose your information if required by law or to respond to legal processes or requests.<br>
                <strong>Business Transfers:</strong> In the event of a merger, acquisition, or sale of assets, your information may be transferred as part of the transaction.
            </p>

            <h3>Data Security</h3>
            <p>We implement industry-standard security measures to protect your personal information from unauthorized access, use, or disclosure.
                However, please be aware that no method of transmission over the internet or electronic storage is completely secure.
            </p>

            <h3>Your Rights and Choices</h3>
            <p>You have the following rights regarding your personal information:</p>
            <p><strong>Access and Correction:</strong> You can request access to and correction of your personal information.<br>
                <strong>Opt-Out:</strong> You can opt-out of receiving promotional communications from us by following the instructions provided in those communications.<br>
                <strong>Cookies:</strong> You can manage your cookie preferences through your browser settings.
            </p>

            <h3>Changes to This Privacy Policy</h3>
            <p>We may update this Privacy Policy from time to time.
                Any changes will be posted on this page with an updated effective date.
                We encourage you to review this policy periodically to stay informed about how we are protecting your information.
            </p>

            <h3>Contact Us</h3>
            <p>If you have any questions or concerns about this Privacy Policy or our data practices, please contact us at:<br>

                <strong>Email:</strong> easesarawak@gmail.com<br>

                Thank you for choosing EASE. We are dedicated to making your travel experience as enjoyable and stress-free as possible.
            </p>
        </div>
    </section>
    <?= $this->include('footer/footer'); ?>
</body>

</html>