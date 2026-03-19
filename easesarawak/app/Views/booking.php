<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - EASE SARAWAK</title>
    <link rel="icon" type="image/png" href="assets/images/cropped-Ease_PNG_File-09.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/footer_style.css">
    <link rel="stylesheet" href="assets/css/navbar_style.css">
    <link rel="stylesheet" href="assets/css/navbar_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <style>

        .custom-content-block {
            color: #e60000ff;
            margin-top: 80px;
            margin-bottom: 2rem;
            border-radius: 12px;
            padding: 0;
            min-height: 0;
            height: auto;
        }

        .navbar-nav,
        .navbar .btn {
            margin-right: 60px !important;
        }
        .btn-book-now {
            margin-left: 0px !important;
        }
        
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
            padding-top: 80px;
            font-size: 1.15rem;
        }

        .booking-container {
            max-width: 1400px;
            margin-left: 60px;
            margin-right: 120px;
            margin-bottom: 0;
            margin-top: 0;
            padding: 2rem;
            min-height: calc(100vh - 200px);
        }

        @media (max-width: 900px) {
            .booking-container {
                margin-left: 0;
                margin-right: 0;
                padding: 1rem;
            }
        }

        /* Header and tagline section */
        .header-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: start;
            margin-bottom: 2rem;
        }

        .left-content {
            max-width: 600px;
        }

        .right-content {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 2rem;
        }

        .booking-image {
            max-width: 100%;
            height: auto;
            border-radius: 0;
            box-shadow: none;
            background: transparent;
            opacity: 0.95;
            margin-top: 0;
            margin-bottom: 0;
        }

        .booking-tagline {
            text-align: left;
            margin-bottom: 2rem;
        }

        .booking-tagline h1 {
            font-family: 'EurostarRegular', sans-serif;
            font-size: 3rem;
            color: #000000;
            font-weight: bold;
            margin-left: 20px;
            margin-top: 120px;
            line-height: 1.3;
        }

        .booking-tagline p {
            font-family: 'EurostarRegular', sans-serif;
            font-size: 1.3rem;
            color: #000000;
            margin-left: 20px;
            line-height: 1.6;
            max-width: 500px;
        }

        /* Service tabs - positioned above forms section */
        .service-tabs {
            display: flex;
            gap: 0; 
            margin-bottom: 0; 
            justify-content: flex-start;
            margin-top: 2rem;
        }

        .tab-btn {
            background: #000000; 
            border: 2px solid #000000; 
            border-bottom: none; 
            padding: 1.5rem 3rem; 
            border-radius: 10px 10px 0 0;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.2rem;
            position: relative;
            z-index: 2;
            color: white; 
            font-weight: bold;
            min-width: 200px; 
            text-align: center;
        }

        .tab-btn:hover {
            background: #333333; 
            border-color: #333333;
        }

        .tab-btn.active {
            background: #f2be00 !important; 
            color: #000000 !important; 
            border-color: #f2be00 !important; 
            border-bottom: 2px solid white !important; 
        }

        .tab-btn.active:hover {
            background: #e6a800 !important; 
            border-color: #e6a800 !important;
        }

        .tab-btn:not(.active) {
            background: #000000 !important; 
            color: white !important; 
            border-color: #000000 !important; 
        }

        .tab-btn:not(.active):hover {
            background: #333333 !important; 
            border-color: #333333 !important;
        }

        /* Full width forms section */
        .forms-section {
            width: 100%;
            margin-top: 0;
        }

        .booking-form {
            background: white;
            padding: 3rem;
            border-radius: 0 15px 15px 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 0; 
            width: 100%;
            border: 2px solid #f2be00; 
            border-top: none; 
            min-height: 400px;
        }

        @media (max-width: 600px) {
            .booking-form {
                padding: 0.5rem;
                border-radius: 10px;
            }
        }

        /* Two column layout for form fields */
        .form-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: 3.5rem;
            row-gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .form-column {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-columns > .form-group:nth-child(3),
        .form-columns > .form-group:nth-child(4) {
            border-top: 1px solid #e6e6e6;
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #333;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 1.2rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }

        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: #007bff;
        }

        .dropdown-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            align-items: center;
            min-height: 44px;
        }

        .datetime-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .hidden {
            display: none;
        }

        .time-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            color: #856404;
            padding: 0.75rem;
            margin-top: 0.5rem;
            font-size: 0.9rem;
            display: none;
        }

        .time-warning.show {
            display: block;
        }

        .address-input {
            margin-top: 0.5rem;
        }

        .address-input input {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .address-input input:focus {
            outline: none;
            border-color: #007bff;
        }

        .address-input input::placeholder {
            color: #999;
        }

        /* Continue button */
        .continue-section {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 0.5rem;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        @media (max-width: 600px) {
            .continue-section {
                justify-content: center;
            }
        }

        .continue-btn {
            background: #f2be00; 
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
            font-weight: bold; 
            width: 100%;
            max-width: 350px;
        }

        .continue-btn:hover {
            background: #000000ff; 
        }

        .service-description-section {
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        /* Service Description Section */
        .service-description-content {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 2rem;
            align-items: center;
        }

        .service-logo {
            display: flex;
            align-items: center;
        }

        .service-logo-img {
            height: 80px;
            width: auto;
            object-fit: contain;
        }

        .service-text {
            display: flex;
            align-items: center;
            background-color: #f8f8f8;
            padding: 2rem;
            border-radius: 8px;
            margin-left: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .service-description-text {
            font-family: 'EurostarRegular', sans-serif;
            font-size: 1.4rem;
            color: #333;
            line-height: 1.6;
            margin: 0;
            text-align: left;
        }

        /* Limit dropdown height to show 5 options with scroll */
        select#quantity {
            width: 100%;
            padding: 1.2rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            background-color: white;
            cursor: pointer;
            transition: border-color 0.3s ease;
            
            /* Custom dropdown arrow */
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 20px;
            padding-right: 40px;
        }

        select#quantity:focus {
            outline: none;
            border-color: #007bff;
        }

        /* Style the dropdown when it opens - limit visible options */
        select#quantity option {
            padding: 8px 12px;
            font-size: 1rem;
            background-color: white;
            color: #333;
        }

        /* For browsers that support limiting dropdown height */
        @supports (size: 5) {
            select#quantity {
                size: 1; /* Normal dropdown */
            }
        }

        /* Alternative approach for webkit browsers */
        select#quantity:focus {
            /* When focused/clicked, limit the dropdown height */
            max-height: 200px;
            overflow-y: auto;
        }

        @media (max-width: 1200px) {
            .header-section {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .left-content {
                order: 1;
            }
            
            .right-content {
                order: 2;
                padding-top: 0;
                margin-bottom: 2rem;
            }
            
            .booking-image {
                max-width: 400px;
                margin-top: 0;
            }
            
            body {
                padding-top: 100px;
            }
            
            .form-columns {
                gap: 2rem;
            }
        }

        @media (max-width: 768px) {
            .booking-container {
                padding: 0.5rem;
            }
            .booking-tagline {
                text-align: center;
            }
            .booking-tagline h1 {
                font-size: 1.5rem;
            }
            .booking-tagline p {
                font-size: 1rem;
                max-width: none;
            }
            .service-tabs {
                flex-direction: column;
                justify-content: center;
            }
            .tab-btn {
                border-radius: 10px !important; 
                border-bottom: 2px solid #000000 !important; 
                margin-bottom: 0.5rem;
            }
            .tab-btn.active {
                background: #f2be00 !important; 
                border-bottom: 2px solid #f2be00 !important; 
                color: #000000 !important; 
            }
            .tab-btn:not(.active) {
                background: #000000 !important; 
                color: white !important; 
                border-bottom: 2px solid #000000 !important; 
            }
            .booking-form {
                border-radius: 15px; 
                border-top: 2px solid #f2be00; 
                margin-top: 1rem;
            }
            .form-columns {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .form-group,
            .form-group label,
            .form-group input,
            .form-group select {
                width: 100%;
                box-sizing: border-box;
            }
            .dropdown-group,
            .datetime-group {
                grid-template-columns: 1fr;
            }
            .continue-btn {
                width: 100%;
                min-width: 0;
            }
            .booking-image {
                max-width: 300px;
                margin-top: 0;
            }
            body {
                padding-top: 90px;
            }
        }

        @media (max-width: 480px) {
            .booking-image {
                max-width: 250px;
            }
            
            body {
                padding-top: 80px;
            }
            
            .booking-form {
                padding: 1rem;
            }
        }

        /* Responsive design for service description */
        @media (max-width: 768px) {
            .service-description-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                text-align: center;
            }

            .service-text {
                margin-left: 0;                   /* Remove left margin on mobile */
                margin-top: 1rem;                 /* Add top margin instead */
                padding: 1.5rem;                  /* Reduce padding on mobile */
                min-height: auto;                 /* Remove min-height on mobile */
            }
            
            .service-logo-img {
                height: 60px;
            }
            
            .service-description-text {
                font-size: 1rem;
                text-align: center;
            }
            
            .service-description-section {
                margin-bottom: 2rem;
                padding-bottom: 1rem;
            }
        }

        @media (max-width: 480px) {
            .service-logo-img {
                height: 50px;
            }
            
            .service-description-text {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 700px) {
            .custom-content-block {
                flex-direction: column !important;
                text-align: center;
            }
            .custom-content-block img {
                border-radius: 12px 12px 0 0 !important;
                width: 100% !important;
                max-width: 350px;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <?= $this->include('navbar/navbar') ?>
    <main class="booking-container">

        <!-- Header Section -->
        <div class="header-section">
            <!-- Left Content -->
            <div class="left-content">      
                <div class="booking-tagline">
                    <h1>TRAVEL SMART WITH EASE</h1>
                    <p>Whether you need secure storage or prompt delivery, we provide reliable and convenient solutions to ensure your journey is as smooth as possible.</p>
                </div>
            </div>

            <!-- Right Content - Image -->
            <div class="right-content">
                <img src="<?= base_url('assets/images/bookingpage.png') ?>" alt="Booking Service" class="booking-image">
            </div>
        </div>

        <!-- Service Tabs - positioned above forms -->
        <div class="service-tabs">
            <button class="tab-btn active" onclick="showService('delivery')">In Town Delivery</button>
            <button class="tab-btn" onclick="showService('storage')">Luggage Storage</button>
        </div>

        <!-- Forms Section - Full Width -->
        <div class="forms-section">
            <!-- Delivery Form -->
            <div id="deliveryForm" class="booking-form">
                <!-- Service Description Section for Delivery -->
                <div class="service-description-section">
                    <div class="service-description-content">
                        <div class="service-logo">
                            <img src="<?= base_url('assets/images/booking-container.png') ?>" alt="Service Logo" class="service-logo-img">
                        </div>
                        <div class="service-text">
                            <p class="service-description-text">Explore Sarawak's beauty and culture without the hassle of managing luggage. Our easy-to-use luggage storage and delivery service ensures handsfree travel in Kuching.</p>
                        </div>
                    </div>
                </div>
                <div class="form-columns">
                    <!-- Top row: Origin | Destination -->
                    <div class="form-group">
                        <label for="origin">Where is your origin? <i class="bi bi-info-circle"></i></label>
                        <div class="dropdown-group">
                            <select id="origin-category" name="origin_category" onchange="updateOriginSpecific()">
                                <option value="">Choose Category</option>
                                <option value="ease-storage">Ease Storage Hub @ Plaza Aurora</option>
                                <option value="hotel">Hotel</option>
                                <option value="shopping-mall">Shopping Mall</option>
                                <option value="airport">Airport</option>
                                <option value="other">Other Location</option>
                            </select>
                            <select id="origin-specific" name="origin_specific" disabled>
                                <option value="">Select category first</option>
                            </select>
                        </div>
                        <div id="origin-address" class="address-input hidden">
                            <input type="text" id="origin-address-text" name="origin_address" placeholder="Please enter your specific address">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="destination">Where is your destination? <i class="bi bi-info-circle"></i></label>
                        <div class="dropdown-group">
                            <select id="destination-category" name="destination_category" onchange="updateDestinationSpecific()">
                                <option value="">Choose Category</option>
                                <option value="ease-storage">Ease Storage Hub @ Plaza Aurora</option>
                                <option value="hotel">Hotel</option>
                                <option value="shopping-mall">Shopping Mall</option>
                                <option value="airport">Airport</option>
                                <option value="other">Other Location</option>
                            </select>
                            <select id="destination-specific" name="destination_specific" disabled>
                                <option value="">Select category first</option>
                            </select>
                        </div>
                        <div id="destination-address" class="address-input hidden">
                            <input type="text" id="destination-address-text" name="destination_address" placeholder="Please enter your specific address">
                        </div>
                    </div>
                    <!-- Bottom row: Drop-off | Pick-up -->
                    <div class="form-group">
                        <label for="dropoff-datetime">Drop-off date & time <i class="bi bi-info-circle"></i></label>
                        <div class="datetime-group">
                            <input type="date" id="dropoff-date" name="dropoff_date">
                            <input type="time" id="dropoff-time" name="dropoff_time" value="14:00">
                        </div>
                        <div id="dropoff-time-warning" class="time-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            Please select a time that is at least 2 hours from current time which is 05 Oct 2025 Time: 16:00.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pickup-datetime">Pick-up date & time <i class="bi bi-info-circle"></i></label>
                        <div class="datetime-group">
                            <input type="date" id="pickup-date" name="pickup_date">
                            <input type="time" id="pickup-time" name="pickup_time" value="16:00">
                        </div>
                        <div id="pickup-time-warning" class="time-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            Please select a time that is at least 2 hours from current time which is 05 Oct 2025 Time: 16:00.
                        </div>
                    </div>
                </div>
                <div class="continue-section">
                    <button class="continue-btn" onclick="continueBooking()">CONTINUE</button>
                </div>
            </div>

            <!-- Storage Form -->
            <div id="storageForm" class="booking-form hidden">
                <!-- Service Description Section for Storage -->
                <div class="service-description-section">
                    <div class="service-description-content">
                        <div class="service-logo">
                            <img src="<?= base_url('assets/images/booking-container.png') ?>" alt="Service Logo" class="service-logo-img">
                        </div>
                        <div class="service-text">
                            <p class="service-description-text">Flexible storage service lets you store bags for hours or days—travel freely without the weight!</p>
                        </div>
                    </div>
                </div>
                <div class="form-columns">
                    <!-- Top row: Storage Location | Luggage Quantity -->
                    <div class="form-group">
                        <label for="storage-location">Storage Location <i class="bi bi-info-circle"></i></label>
                        <select id="storage-location" name="storage_location">
                            <option value="ease-plaza-aurora">EASE Storage Hub @ Plaza Aurora</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Luggage Quantity <i class="bi bi-info-circle"></i></label>
                        <select id="quantity" name="quantity">
                            <option value="1">1 piece</option>
                            <option value="2">2 pieces</option>
                            <option value="3">3 pieces</option>
                            <option value="4">4 pieces</option>
                            <option value="5">5 pieces</option>
                            <option value="6">6 pieces</option>
                            <option value="7">7 pieces</option>
                        </select>
                    </div>
                    <!-- Bottom row: Drop-off | Pick-up -->
                    <div class="form-group">
                        <label for="storage-dropoff-datetime">Drop-off date & time <i class="bi bi-info-circle"></i></label>
                        <div class="datetime-group">
                            <input type="date" id="storage-dropoff-date" name="storage_dropoff_date">
                            <input type="time" id="storage-dropoff-time" name="storage_dropoff_time" value="12:00">
                        </div>
                        <div id="storage-dropoff-time-warning" class="time-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            Please select a time that is at least 2 hours from current time which is 05 Oct 2025 Time: 16:00.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="storage-pickup-datetime">Pick-up date & time <i class="bi bi-info-circle"></i></label>
                        <div class="datetime-group">
                            <input type="date" id="storage-pickup-date" name="storage_pickup_date">
                            <input type="time" id="storage-pickup-time" name="storage_pickup_time" value="14:00">
                        </div>
                        <div id="storage-pickup-time-warning" class="time-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            Please select a time that is at least 2 hours from current time which is 05 Oct 2025 Time: 16:00.
                        </div>
                    </div>
                </div>
                <div class="continue-section">
                    <button class="continue-btn" onclick="continueBooking()">CONTINUE</button>
                </div>
            </div>
        </div>
    </main>
    
    <?= $this->include('footer/footer') ?>


    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // date inputs (format: YYYY-MM-DD)
        ['dropoff-date','pickup-date','storage-dropoff-date','storage-pickup-date'].forEach(function(id){
            var el = document.getElementById(id);
            if (!el) return;
            var fp = flatpickr(el, {
                dateFormat: 'Y-m-d',
                allowInput: true
            });
            if (el.value) fp.setDate(el.value, true, 'Y-m-d');
        });
 
        // time inputs (format: HH:MM 24h)
        ['dropoff-time','pickup-time','storage-dropoff-time','storage-pickup-time'].forEach(function(id){
            var el = document.getElementById(id);
            if (!el) return;
            var tp = flatpickr(el, {
                enableTime: true,
                noCalendar: true,
                dateFormat: 'H:i',
                time_24hr: true,
                allowInput: true
            });
            if (el.value) tp.setDate(el.value, true, 'H:i');
        });
    });
    </script>

    <script>
        let currentService = 'delivery';

        // Location data
        const locationData = {
            'ease-storage': {
                options: [
                    { value: 'ease-plaza-aurora', text: 'Ease Storage Hub @ Plaza Aurora' }
                ],
                autoSelect: true
            },
            'hotel': {
                options: [
                    { value: 'astana-wing-riverside', text: 'Astana Wing - Riverside Majestic Hotel' },
                    { value: 'citadines-uplands', text: 'Citadines Uplands Kuching' },
                    { value: 'grand-margherita', text: 'Grand Margherita Hotel' },
                    { value: 'hilton-kuching', text: 'Hilton Kuching Hotel' },
                    { value: 'hock-lee', text: 'Hock Lee Hotel & Residences' },
                    { value: 'imperial-hotel', text: 'Imperial Hotel Kuching' },
                    { value: 'merdeka-palace', text: 'Merdeka Palace Hotel & Suites' },
                    { value: 'pullman-kuching', text: 'Pullman Kuching' },
                    { value: 'puteri-wing-riverside', text: 'Puteri Wing - Riverside Majestic Hotel' },
                    { value: 'sheraton-kuching', text: 'Sheraton Kuching Hotel' },
                    { value: 'waterfront-hotel', text: 'The Waterfront Hotel Kuching' },
                    { value: 'ucsi-hotel', text: 'UCSI Hotel Kuching' }
                ]
            },
            'shopping-mall': {
                options: [
                    { value: 'aeon-mall', text: 'AEON Mall Kuching Central' },
                    { value: 'boulevard-shopping', text: 'Boulevard Shopping Mall' },
                    { value: 'cityone-megamall', text: 'CityOne Megamall' },
                    { value: 'plaza-merdeka', text: 'Plaza Merdeka Matang Jaya' },
                    { value: 'spring-shopping', text: 'The Spring Shopping Mall' },
                    { value: 'vivacity-megamall', text: 'Vivacity Megamall' }
                ]
            },
            'airport': {
                options: [
                    { value: 'kuching-airport', text: 'Kuching International Airport' }
                ],
                autoSelect: true
            }
        };

        // SINGLE DOMContentLoaded event listener
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded, initializing...');
            
            // First, set minimum date and time
            setMinDateTime();
            
            // NEW: Check for preferred service from home page
            const preferredService = sessionStorage.getItem('preferredService');
            if (preferredService) {
                console.log('Setting preferred service:', preferredService);
                showServiceTab(preferredService);
                // Clear the preference after using it
                sessionStorage.removeItem('preferredService');
            }
            
            // Check if we're editing and restore data BEFORE adding event listeners
            const isEditing = sessionStorage.getItem('isEditing');
            const bookingData = JSON.parse(sessionStorage.getItem('bookingData'));
            
            if (isEditing === 'true' && bookingData) {
                console.log('Restoring booking data:', bookingData);
                restoreBookingData(bookingData);
                // Clear the editing flag
                sessionStorage.removeItem('isEditing');
            }
            
            // Add event listeners for date-time validation AFTER restoration
            document.getElementById('dropoff-date').addEventListener('change', validateDropoffDateTime);
            document.getElementById('dropoff-time').addEventListener('change', validateDropoffDateTime);
            document.getElementById('pickup-date').addEventListener('change', validatePickupDateTime);
            document.getElementById('pickup-time').addEventListener('change', validatePickupDateTime);
            
            // Storage form listeners - with working hours check
            document.getElementById('storage-dropoff-date').addEventListener('change', validateStorageDropoffDateTime);
            document.getElementById('storage-dropoff-time').addEventListener('change', validateStorageDropoffDateTime);
            document.getElementById('storage-pickup-date').addEventListener('change', validateStoragePickupDateTime);
            document.getElementById('storage-pickup-time').addEventListener('change', validateStoragePickupDateTime);
            
            console.log('Initialization complete');
        });

        // NEW: Function to check if time is within working hours (7 AM to 7 PM)
        function isWithinWorkingHours(time) {
            if (!time) return false;
            
            const [hours, minutes] = time.split(':').map(Number);
            const timeInMinutes = hours * 60 + minutes;
            const startTime = 7 * 60; // 7:00 AM
            const endTime = 19 * 60; // 7:00 PM
            
            return timeInMinutes >= startTime && timeInMinutes <= endTime;
        }

        // Function to restore booking data when editing
        function restoreBookingData(data) {
            console.log('Starting restoration with data:', data);
            
            try {
                // Show the correct service tab first
                if (data.service === 'delivery') {
                    // Show delivery service
                    showServiceTab('delivery');
                    
                    // Restore delivery form data
                    if (data.origin) {
                        console.log('Restoring origin:', data.origin);
                        const originCategory = findCategoryForLocation(data.origin);
                        console.log('Found origin category:', originCategory);
                        
                        if (originCategory) {
                            document.getElementById('origin-category').value = originCategory;
                            updateOriginSpecific();
                            
                            // Set specific location if not 'other'
                            if (originCategory !== 'other') {
                                setTimeout(() => {
                                    const originSpecific = document.getElementById('origin-specific');
                                    const option = Array.from(originSpecific.options).find(opt => opt.text === data.origin);
                                    if (option) {
                                        originSpecific.value = option.value;
                                        console.log('Origin specific set to:', option.value);
                                    } else {
                                        console.log('Origin specific option not found for:', data.origin);
                                    }
                                }, 200);
                            } else {
                                // Handle 'other' category
                                setTimeout(() => {
                                    const addressInput = document.getElementById('origin-address-text');
                                    if (addressInput) {
                                        addressInput.value = data.originAddress || '';
                                        console.log('Origin address set to:', data.originAddress);
                                    }
                                }, 200);
                            }
                        }
                    }
                    
                    if (data.destination) {
                        console.log('Restoring destination:', data.destination);
                        const destinationCategory = findCategoryForLocation(data.destination);
                        console.log('Found destination category:', destinationCategory);
                        
                        if (destinationCategory) {
                            document.getElementById('destination-category').value = destinationCategory;
                            updateDestinationSpecific();
                            
                            // Set specific location if not 'other'
                            if (destinationCategory !== 'other') {
                                setTimeout(() => {
                                    const destinationSpecific = document.getElementById('destination-specific');
                                    const option = Array.from(destinationSpecific.options).find(opt => opt.text === data.destination);
                                    if (option) {
                                        destinationSpecific.value = option.value;
                                        console.log('Destination specific set to:', option.value);
                                    } else {
                                        console.log('Destination specific option not found for:', data.destination);
                                    }
                                }, 200);
                            } else {
                                // Handle 'other' category
                                setTimeout(() => {
                                    const addressInput = document.getElementById('destination-address-text');
                                    if (addressInput) {
                                        addressInput.value = data.destinationAddress || '';
                                        console.log('Destination address set to:', data.destinationAddress);
                                    }
                                }, 200);
                            }
                        }
                    }
                    
                    // Restore dates and times
                    if (data.dropoffDate) {
                        document.getElementById('dropoff-date').value = data.dropoffDate;
                        console.log('Dropoff date set to:', data.dropoffDate);
                    }
                    if (data.dropoffTime) {
                        document.getElementById('dropoff-time').value = data.dropoffTime;
                        console.log('Dropoff time set to:', data.dropoffTime);
                    }
                    if (data.pickupDate) {
                        document.getElementById('pickup-date').value = data.pickupDate;
                        console.log('Pickup date set to:', data.pickupDate);
                    }
                    if (data.pickupTime) {
                        document.getElementById('pickup-time').value = data.pickupTime;
                        console.log('Pickup time set to:', data.pickupTime);
                    }
                    
                } else if (data.service === 'storage') {
                    // Show storage service
                    showServiceTab('storage');
                    
                    // Restore storage form data
                    if (data.storageLocation) {
                        console.log('Restoring storage location:', data.storageLocation);
                        const storageSelect = document.getElementById('storage-location');
                        const option = Array.from(storageSelect.options).find(opt => opt.text === data.storageLocation);
                        if (option) {
                            storageSelect.value = option.value;
                            console.log('Storage location set to:', option.value);
                        }
                    }
                    
                    if (data.quantity) {
                        console.log('Restoring quantity:', data.quantity);
                        document.getElementById('quantity').value = data.quantity;
                    }
                    
                    // Restore dates and times
                    if (data.dropoffDate) {
                        document.getElementById('storage-dropoff-date').value = data.dropoffDate;
                        console.log('Storage dropoff date set to:', data.dropoffDate);
                    }
                    if (data.dropoffTime) {
                        document.getElementById('storage-dropoff-time').value = data.dropoffTime;
                        console.log('Storage dropoff time set to:', data.dropoffTime);
                    }
                    if (data.pickupDate) {
                        document.getElementById('storage-pickup-date').value = data.pickupDate;
                        console.log('Storage pickup date set to:', data.pickupDate);
                    }
                    if (data.pickupTime) {
                        document.getElementById('storage-pickup-time').value = data.pickupTime;
                        console.log('Storage pickup time set to:', data.pickupTime);
                    }
                }
                
                console.log('Booking data restored successfully');
                
            } catch (error) {
                console.error('Error restoring booking data:', error);
            }
        }

        // Helper function to show service tab without triggering onclick
        function showServiceTab(serviceType) {
            console.log('Switching to service:', serviceType);
            
            // Update tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Find and activate the correct tab
            const tabs = document.querySelectorAll('.tab-btn');
            tabs.forEach(tab => {
                if ((serviceType === 'delivery' && tab.textContent.includes('Delivery')) ||
                    (serviceType === 'storage' && tab.textContent.includes('Storage'))) {
                    tab.classList.add('active');
                }
            });

            // Show/hide forms
            if (serviceType === 'delivery') {
                document.getElementById('deliveryForm').classList.remove('hidden');
                document.getElementById('storageForm').classList.add('hidden');
            } else {
                document.getElementById('deliveryForm').classList.add('hidden');
                document.getElementById('storageForm').classList.remove('hidden');
            }

            currentService = serviceType;
            console.log('Current service set to:', currentService);
        }

        // Helper function to find category for a location
        function findCategoryForLocation(locationName) {
            console.log('Finding category for location:', locationName);
            
            // Check if it's Ease Storage
            if (locationName.includes('Ease Storage Hub') || locationName.includes('EASE Storage Hub')) {
                console.log('Found ease-storage category');
                return 'ease-storage';
            }
            
            // Check in hotel options
            const hotelOptions = locationData.hotel?.options || [];
            if (hotelOptions.some(option => option.text === locationName)) {
                console.log('Found hotel category');
                return 'hotel';
            }
            
            // Check in shopping mall options
            const mallOptions = locationData['shopping-mall']?.options || [];
            if (mallOptions.some(option => option.text === locationName)) {
                console.log('Found shopping-mall category');
                return 'shopping-mall';
            }
            
            // Check in airport options
            const airportOptions = locationData.airport?.options || [];
            if (airportOptions.some(option => option.text === locationName)) {
                console.log('Found airport category');
                return 'airport';
            }
            
            // If not found in predefined categories, it's likely 'other'
            console.log('Defaulting to other category');
            return 'other';
        }

        function setMinDateTime() {
            const now = new Date();
            const currentDate = now.toISOString().split('T')[0];
            
            // Set minimum date to today for all date inputs
            document.getElementById('dropoff-date').min = currentDate;
            document.getElementById('pickup-date').min = currentDate;
            document.getElementById('storage-dropoff-date').min = currentDate;
            document.getElementById('storage-pickup-date').min = currentDate;
            
            // Set default values for DELIVERY (2.5 hours from now)
            const deliveryFutureTime = new Date(now.getTime() + 2.5 * 60 * 60 * 1000);
            let deliveryTimeString = deliveryFutureTime.getHours().toString().padStart(2, '0') + ':' + deliveryFutureTime.getMinutes().toString().padStart(2, '0');
            let deliveryDateString = currentDate; // Always start with current date
            
            // Ensure delivery time is within working hours (7 AM to 7 PM)
            if (deliveryFutureTime.getHours() < 7) {
                deliveryTimeString = '07:00';
                // Keep current date, just adjust time
            } else if (deliveryFutureTime.getHours() >= 19) {
                // If it's past 7 PM, set to next day at 7 AM
                const nextDay = new Date(deliveryFutureTime.getTime() + 24 * 60 * 60 * 1000);
                deliveryDateString = nextDay.toISOString().split('T')[0];
                deliveryTimeString = '07:00';
            }
            
            // Set delivery dropoff date and time
            document.getElementById('dropoff-date').value = deliveryDateString;
            document.getElementById('dropoff-time').value = deliveryTimeString;
            
            // Set pickup time (2 hours after dropoff)
            const pickupTime = new Date(deliveryFutureTime.getTime() + 2 * 60 * 60 * 1000);
            let pickupTimeString = pickupTime.getHours().toString().padStart(2, '0') + ':' + pickupTime.getMinutes().toString().padStart(2, '0');
            let pickupDateString = deliveryDateString; // Same date as dropoff initially
            
            if (pickupTime.getHours() >= 19) {
                // If pickup would be past 7 PM, set to next day
                const nextDay = new Date(pickupTime.getTime() + 24 * 60 * 60 * 1000);
                pickupDateString = nextDay.toISOString().split('T')[0];
                pickupTimeString = '07:00';
            }
            
            document.getElementById('pickup-date').value = pickupDateString;
            document.getElementById('pickup-time').value = pickupTimeString;
            
            // Set default values for STORAGE (current date + 30 minutes from now)
            const storageTime = new Date(now.getTime() + 30 * 60 * 1000);
            let storageTimeString = storageTime.getHours().toString().padStart(2, '0') + ':' + storageTime.getMinutes().toString().padStart(2, '0');
            let storageDateString = currentDate; // Always start with current date
            
            // Ensure storage time is within working hours
            if (storageTime.getHours() < 7) {
                storageTimeString = '07:00';
                // Keep current date, just adjust time
            } else if (storageTime.getHours() >= 19) {
                const nextDay = new Date(storageTime.getTime() + 24 * 60 * 60 * 1000);
                storageDateString = nextDay.toISOString().split('T')[0];
                storageTimeString = '07:00';
            }
            
            // Set storage dropoff date and time
            document.getElementById('storage-dropoff-date').value = storageDateString;
            document.getElementById('storage-dropoff-time').value = storageTimeString;
            
            // Set storage pickup time (2 hours after dropoff)
            const storagePickupTime = new Date(storageTime.getTime() + 2 * 60 * 60 * 1000);
            let storagePickupTimeString = storagePickupTime.getHours().toString().padStart(2, '0') + ':' + storagePickupTime.getMinutes().toString().padStart(2, '0');
            let storagePickupDateString = storageDateString; // Same date as dropoff initially
            
            if (storagePickupTime.getHours() >= 19) {
                const nextDay = new Date(storagePickupTime.getTime() + 24 * 60 * 60 * 1000);
                storagePickupDateString = nextDay.toISOString().split('T')[0];
                storagePickupTimeString = '07:00';
            }
            
            document.getElementById('storage-pickup-date').value = storagePickupDateString;
            document.getElementById('storage-pickup-time').value = storagePickupTimeString;
        }

        // DELIVERY VALIDATION FUNCTIONS (with 2.5-hour restriction and working hours)
        function validateDropoffDateTime() {
            const selectedDate = document.getElementById('dropoff-date').value;
            const selectedTime = document.getElementById('dropoff-time').value;
            const warningDiv = document.getElementById('dropoff-time-warning');
            
            if (!isDateTimeValid(selectedDate, selectedTime)) {
                warningDiv.classList.add('show');
                return false;
            } else if (!isWithinWorkingHours(selectedTime)) {
                warningDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> The selected time must be between 07:00 and 19:00.';
                warningDiv.classList.add('show');
                return false;
            } else if (!isAtLeast2HoursFromNow(selectedDate, selectedTime)) {
                updateDeliveryWarningMessage(warningDiv);
                warningDiv.classList.add('show');
                return false;
            } else {
                warningDiv.classList.remove('show');
                updatePickupMinimum();
                return true;
            }
        }

        function validatePickupDateTime() {
            const selectedDate = document.getElementById('pickup-date').value;
            const selectedTime = document.getElementById('pickup-time').value;
            const dropoffDate = document.getElementById('dropoff-date').value;
            const dropoffTime = document.getElementById('dropoff-time').value;
            const warningDiv = document.getElementById('pickup-time-warning');
            
            if (!isDateTimeValid(selectedDate, selectedTime)) {
                updateDeliveryWarningMessage(warningDiv);
                warningDiv.classList.add('show');
                return false;
            } else if (!isWithinWorkingHours(selectedTime)) {
                warningDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> The selected time must be between 07:00 and 19:00.';
                warningDiv.classList.add('show');
                return false;
            } else if (!isAtLeast2HoursFromNow(selectedDate, selectedTime)) {
                updateDeliveryWarningMessage(warningDiv);
                warningDiv.classList.add('show');
                return false;
            } else if (isDateTime1BeforeDateTime2(selectedDate, selectedTime, dropoffDate, dropoffTime)) {
                warningDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Pick-up date and time must be after drop-off date and time.';
                warningDiv.classList.add('show');
                updatePickupMinimum();
                return false;
            } else {
                warningDiv.classList.remove('show');
                return true;
            }
        }

        // STORAGE VALIDATION FUNCTIONS (with working hours check)
        function validateStorageDropoffDateTime() {
            const selectedDate = document.getElementById('storage-dropoff-date').value;
            const selectedTime = document.getElementById('storage-dropoff-time').value;
            const warningDiv = document.getElementById('storage-dropoff-time-warning');
            
            if (!isDateTimeValid(selectedDate, selectedTime)) {
                warningDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Drop-off date and time cannot be in the past.';
                warningDiv.classList.add('show');
                return false;
            } else if (!isWithinWorkingHours(selectedTime)) {
                warningDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> The selected time must be between 07:00 and 19:00.';
                warningDiv.classList.add('show');
                return false;
            } else {
                warningDiv.classList.remove('show');
                updateStoragePickupMinimum();
                return true;
            }
        }

        function validateStoragePickupDateTime() {
            const selectedDate = document.getElementById('storage-pickup-date').value;
            const selectedTime = document.getElementById('storage-pickup-time').value;
            const dropoffDate = document.getElementById('storage-dropoff-date').value;
            const dropoffTime = document.getElementById('storage-dropoff-time').value;
            const warningDiv = document.getElementById('storage-pickup-time-warning');
            
            if (!isDateTimeValid(selectedDate, selectedTime)) {
                warningDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Pick-up date and time cannot be in the past.';
                warningDiv.classList.add('show');
                return false;
            } else if (!isWithinWorkingHours(selectedTime)) {
                warningDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> The selected time must be between 07:00 and 19:00.';
                warningDiv.classList.add('show');
                return false;
            } else if (isDateTime1BeforeDateTime2(selectedDate, selectedTime, dropoffDate, dropoffTime)) {
                warningDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Pick-up date and time must be after drop-off date and time.';
                warningDiv.classList.add('show');
                updateStoragePickupMinimum();
                return false;
            } else {
                warningDiv.classList.remove('show');
                return true;
            }
        }

        // NEW: Function to check if time is at least 2.5 hours from now (for delivery)
        function isAtLeast2HoursFromNow(date, time) {
            if (!date || !time) return false;
            
            const selectedDateTime = new Date(date + 'T' + time);
            const now = new Date();
            const twoHoursFromNow = new Date(now.getTime() + 2 * 60 * 60 * 1000); // 2 hours for validation
            
            return selectedDateTime >= twoHoursFromNow;
        }

        // NEW: Update warning message for delivery (2.5 hours)
        function updateDeliveryWarningMessage(warningDiv) {
            const now = new Date();
            const currentDateStr = now.toLocaleDateString('en-GB', { 
                day: '2-digit', 
                month: 'short', 
                year: 'numeric' 
            });
            const currentTimeStr = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: false
            });
            
            warningDiv.innerHTML = `<i class="bi bi-exclamation-triangle"></i> Please select a time that is at least 2 hours from current time which is ${currentDateStr} Time: ${currentTimeStr}.`;
        }

        function isDateTimeValid(date, time) {
            if (!date || !time) return false;
            
            const selectedDateTime = new Date(date + 'T' + time);
            const now = new Date();
            
            return selectedDateTime > now;
        }

        function isDateTime1BeforeDateTime2(date1, time1, date2, time2) {
            if (!date1 || !time1 || !date2 || !time2) return false;
            
            const dateTime1 = new Date(date1 + 'T' + time1);
            const dateTime2 = new Date(date2 + 'T' + time2);
            
            return dateTime1 <= dateTime2;
        }

        function updatePickupMinimum() {
            const dropoffDate = document.getElementById('dropoff-date').value;
            const dropoffTime = document.getElementById('dropoff-time').value;
            
            if (dropoffDate && dropoffTime) {
                document.getElementById('pickup-date').min = dropoffDate;
                
                const pickupDate = document.getElementById('pickup-date').value;
                if (pickupDate === dropoffDate) {
                    const dropoffDateTime = new Date(dropoffDate + 'T' + dropoffTime);
                    const minPickupTime = new Date(dropoffDateTime.getTime() + 60 * 60 * 1000);
                    let minTime = minPickupTime.getHours().toString().padStart(2, '0') + ':' + minPickupTime.getMinutes().toString().padStart(2, '0');
                    
                    // Ensure pickup time is within working hours
                    if (minPickupTime.getHours() >= 19) {
                        minTime = '07:00';
                        // Set to next day if needed
                        const nextDay = new Date(minPickupTime.getTime() + 24 * 60 * 60 * 1000);
                        document.getElementById('pickup-date').value = nextDay.toISOString().split('T')[0];
                    }
                    
                    if (document.getElementById('pickup-time').value <= dropoffTime) {
                        document.getElementById('pickup-time').value = minTime;
                    }
                }
            }
        }

        function updateStoragePickupMinimum() {
            const dropoffDate = document.getElementById('storage-dropoff-date').value;
            const dropoffTime = document.getElementById('storage-dropoff-time').value;
            
            if (dropoffDate && dropoffTime) {
                document.getElementById('storage-pickup-date').min = dropoffDate;
                
                const pickupDate = document.getElementById('storage-pickup-date').value;
                if (pickupDate === dropoffDate) {
                    const dropoffDateTime = new Date(dropoffDate + 'T' + dropoffTime);
                    const minPickupTime = new Date(dropoffDateTime.getTime() + 60 * 60 * 1000);
                    let minTime = minPickupTime.getHours().toString().padStart(2, '0') + ':' + minPickupTime.getMinutes().toString().padStart(2, '0');
                    
                    // Ensure pickup time is within working hours
                    if (minPickupTime.getHours() >= 19) {
                        minTime = '07:00';
                        // Set to next day if needed
                        const nextDay = new Date(minPickupTime.getTime() + 24 * 60 * 60 * 1000);
                        document.getElementById('storage-pickup-date').value = nextDay.toISOString().split('T')[0];
                    }
                    
                    if (document.getElementById('storage-pickup-time').value <= dropoffTime) {
                        document.getElementById('storage-pickup-time').value = minTime;
                    }
                }
            }
        }

        function updateOriginSpecific() {
            const category = document.getElementById('origin-category').value;
            const specificSelect = document.getElementById('origin-specific');
            const addressDiv = document.getElementById('origin-address');
            
            updateLocationDropdown(category, specificSelect, addressDiv);
        }

        function updateDestinationSpecific() {
            const category = document.getElementById('destination-category').value;
            const specificSelect = document.getElementById('destination-specific');
            const addressDiv = document.getElementById('destination-address');
            
            updateLocationDropdown(category, specificSelect, addressDiv);
        }

        function updateLocationDropdown(category, specificSelect, addressDiv) {
            if (category === 'other') {
                specificSelect.style.display = 'none';
                addressDiv.classList.remove('hidden');
                addressDiv.querySelector('input').setAttribute('required', 'required');
            } else if (category && locationData[category]) {
                specificSelect.style.display = 'block';
                specificSelect.disabled = false;
                addressDiv.classList.add('hidden');
                addressDiv.querySelector('input').removeAttribute('required');
                addressDiv.querySelector('input').value = '';
                
                const data = locationData[category];
                
                specificSelect.innerHTML = '';
                
                if (!data.autoSelect) {
                    specificSelect.innerHTML = '<option value="">Choose specific location</option>';
                }
                
                data.options.forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option.value;
                    optionElement.textContent = option.text;
                    specificSelect.appendChild(optionElement);
                });
                
                if (data.autoSelect && data.options.length === 1) {
                    specificSelect.value = data.options[0].value;
                }
            } else {
                specificSelect.style.display = 'block';
                specificSelect.disabled = true;
                specificSelect.innerHTML = '<option value="">Select category first</option>';
                addressDiv.classList.add('hidden');
                addressDiv.querySelector('input').removeAttribute('required');
                addressDiv.querySelector('input').value = '';
            }
        }

        function showService(serviceType) {
            // Update tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');

            // Show/hide forms
            if (serviceType === 'delivery') {
                document.getElementById('deliveryForm').classList.remove('hidden');
                document.getElementById('storageForm').classList.add('hidden');
            } else {
                document.getElementById('deliveryForm').classList.add('hidden');
                document.getElementById('storageForm').classList.remove('hidden');
            }

            currentService = serviceType;
        }

        function continueBooking() {
            // Basic form validation
            let isValid = true;
            let errorMessage = '';

            if (currentService === 'delivery') {
                const originCategory = document.getElementById('origin-category').value;
                const originSpecific = document.getElementById('origin-specific').value;
                const destinationCategory = document.getElementById('destination-category').value;
                const destinationSpecific = document.getElementById('destination-specific').value;
                
                if (!validateDropoffDateTime() || !validatePickupDateTime()) {
                    return;
                }
                
                if (!originCategory) {
                    isValid = false;
                    errorMessage = 'Please select an origin category.';
                } else if (originCategory !== 'other' && !originSpecific) {
                    isValid = false;
                    errorMessage = 'Please select a specific origin location.';
                } else if (originCategory === 'other' && !document.getElementById('origin-address-text').value.trim()) {
                    isValid = false;
                    errorMessage = 'Please enter your origin address.';
                } else if (!destinationCategory) {
                    isValid = false;
                    errorMessage = 'Please select a destination category.';
                } else if (destinationCategory !== 'other' && !destinationSpecific) {
                    isValid = false;
                    errorMessage = 'Please select a specific destination location.';
                } else if (destinationCategory === 'other' && !document.getElementById('destination-address-text').value.trim()) {
                    isValid = false;
                    errorMessage = 'Please enter your destination address.';
                }
            } else {
                if (!validateStorageDropoffDateTime() || !validateStoragePickupDateTime()) {
                    return;
                }
            }

            if (!isValid) {
                alert(errorMessage);
                return;
            }

            // Collect form data and store in session storage
            let bookingData = {};
            
            if (currentService === 'delivery') {
                const originCategory = document.getElementById('origin-category').value;
                let originLocation = '';
                let originAddress = '';
                
                if (originCategory === 'other') {
                    originLocation = 'Other Location';
                    originAddress = document.getElementById('origin-address-text').value;
                } else {
                    const originSpecific = document.getElementById('origin-specific');
                    originLocation = originSpecific.options[originSpecific.selectedIndex].text;
                }
                
                const destinationCategory = document.getElementById('destination-category').value;
                let destinationLocation = '';
                let destinationAddress = '';
                
                if (destinationCategory === 'other') {
                    destinationLocation = 'Other Location';
                    destinationAddress = document.getElementById('destination-address-text').value;
                } else {
                    const destinationSpecific = document.getElementById('destination-specific');
                    destinationLocation = destinationSpecific.options[destinationSpecific.selectedIndex].text;
                }
                
                bookingData = {
                    service: 'delivery',
                    origin: originLocation,
                    originAddress: originAddress,
                    destination: destinationLocation,
                    destinationAddress: destinationAddress,
                    dropoffDate: document.getElementById('dropoff-date').value,
                    dropoffTime: document.getElementById('dropoff-time').value,
                    pickupDate: document.getElementById('pickup-date').value,
                    pickupTime: document.getElementById('pickup-time').value
                };
            } else {
                const storageLocation = document.getElementById('storage-location');
                
                bookingData = {
                    service: 'storage',
                    storageLocation: storageLocation.options[storageLocation.selectedIndex].text,
                    quantity: document.getElementById('quantity').value,
                    dropoffDate: document.getElementById('storage-dropoff-date').value,
                    dropoffTime: document.getElementById('storage-dropoff-time').value,
                    pickupDate: document.getElementById('storage-pickup-date').value,
                    pickupTime: document.getElementById('storage-pickup-time').value
                };
            }
            
            sessionStorage.setItem('bookingData', JSON.stringify(bookingData));
            console.log('Booking data stored:', bookingData);
            
            window.location.href = 'bookingdetail';
        }
    </script>
</body>
</html>