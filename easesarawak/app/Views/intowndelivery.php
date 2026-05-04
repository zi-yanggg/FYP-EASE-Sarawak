<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EASE SARAWAK | In Town Delivery</title>
    <link rel="icon" type="image/png" href="assets/images/cropped-Ease_PNG_File-09.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/footer_style.css">
    <link rel="stylesheet" href="assets/css/payment_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">


</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <img src="assets/images/Ease_PNG_File-01.png" alt="EASE Logo">
        </div>
        <div class="menu">
            <div class="dropdown">
                <a>Menu <i class="bi bi-chevron-down"></i></a>
                <div class="dropdown-content">
                    <a href="#">Our Services</a>
                    <a href="#">How It Works</a>
                    <a href="#">Why Us</a>
                    <a href="#">About Us</a>
                    <a href="#">Contact Us</a>
                </div>
            </div>
            <a href="#" class="btn">Book Now</a>
        </div>
    </nav>

        <!-- Hero -->
    <section class="hero">
        <div class="hero-content">
            <h1>TRAVEL SMART WITH EASE</h1>
            <p>Whether you need secure storage or prompt delivery, we provide reliable and convenient solutions to ensure your journey is as smooth as possible.</p>
        </div>  
    </section>

        <!-- Main Content (havent complete)-->  
    <div class="container">  
        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step active">
                <div class="step-number">1</div>
                <div class="step-title">Booking Details</div>
                <div class="step-connector"></div>
            </div>
            <div class="step ">
                <div class="step-number">2</div>
                <div class="step-title">Information & Payment</div>
                <div class="step-connector"></div>
            </div>
            <div class="step ">
                <div class="step-number">3</div>
                <div class="step-title">Complete</div>
            </div>
        </div>

        <!--Service Section-->
    <div class="row">
        <div class="service">
            <h3> SERVICE </h3>
        <div>
    <div class="row">
        <div class="service-container">
            <h3> SEND FROM </h3>
            <p> Where is your origin? </p>

        </div>
    </div>


   <?= $this->include('footer/footer') ?>

</body>

</html>