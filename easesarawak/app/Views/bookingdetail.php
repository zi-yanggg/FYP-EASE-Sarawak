<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - EASE SARAWAK</title>
    <link rel="icon" type="image/png" href="assets/images/cropped-Ease_PNG_File-09.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/footer_style.css">
    <link rel="stylesheet" href="assets/css/navbar_style.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOEej3q--uJLfUydFQ1T2vlr3HJC35atQ"></script>

    <style>
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
            padding-top: 120px;
            font-size: 1.25rem;
        }

        .booking-detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            min-height: calc(100vh - 200px);
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 900px) {
            .booking-detail-container {
                grid-template-columns: 1fr;
                padding: 1rem;
                gap: 1rem;
            }
            .left-column, .right-column {
                width: 100%;
                max-width: 100%;
            }
        }

        .booking-detail-header {
            text-align: center;
            margin-bottom: 2rem;
            margin-top: 2rem;
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: start;
        }

        .left-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
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

        @media (max-width: 900px) {
            .booking-detail-header {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                text-align: center;
            }
            .left-content {
                order: 1;
                align-items: center;
            }
            .right-content {
                order: 2;
                padding-top: 0;
                margin-bottom: 1rem;
                justify-content: center;
            }
        }

        .booking-detail-header h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .booking-detail-header p {
            font-size: 1.1rem;
            color: #666;
        }

        .left-column {
            display: flex;
            flex-direction: column;
        }

        .right-column {
            display: flex;
            flex-direction: column;
        }

        .service-type-badge {
            display: inline-block;
            background: #f2be00;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            width: fit-content;
        }

        .booking-details-card, .pricing-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        @media (max-width: 600px) {
            .booking-details-card, .pricing-card {
                padding: 1rem;
                border-radius: 10px;
            }
        }

        .pricing-card {
            height: fit-content;
            position: sticky;
            top: 2rem;
        }

        .pricing-header {
            color: #333;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid #f2be00;
            padding-bottom: 0.5rem;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }

        .price-row:last-child {
            border-bottom: none;
            border-top: 2px solid #333;
            font-weight: bold;
            font-size: 1.2rem;
            margin-top: 1rem;
            padding-top: 1rem;
        }

        .price-label {
            color: #555;
        }

        .price-value {
            color: #333;
            font-weight: bold;
        }

        .discount-value {
            color: #dc3545;
        }

        .total-value {
            color: #28a745;
            font-size: 1.3rem;
        }

        .detail-section {
            margin-bottom: 2rem;
        }

        .detail-section h3 {
            color: #333;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid #f2be00;
            padding-bottom: 0.5rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: bold;
            color: #555;
            flex: 1;
        }

        .detail-value {
            color: #333;
            flex: 2;
            text-align: right;
        }

        .location-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin: 0.5rem 0;
        }

        .location-info strong {
            color: #333;
            display: block;
            margin-bottom: 0.25rem;
        }

        .location-info span {
            color: #666;
            font-size: 0.9rem;
        }

        .datetime-info {
            background: #e3f2fd;
            padding: 1rem;
            border-radius: 8px;
            margin: 0.5rem 0;
        }

        .datetime-info strong {
            color: #1976d2;
            display: block;
            margin-bottom: 0.25rem;
        }

        .quantity-controls {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.3rem;
        }

        .quantity-btn-vertical {
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.3rem;
            transition: background 0.3s ease;
            margin: 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }

        .quantity-btn-vertical:hover {
            background: #0056b3;
        }

        .quantity-btn-vertical:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .quantity-display {
            background: #f8f9fa;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            min-width: 60px;
            text-align: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .promo-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin: 1rem 0;
        }

        .promo-input-group {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        @media (max-width: 600px) {
            .promo-input-group {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }
        }

        .promo-input {
            flex: 1;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            width: 100%;
            box-sizing: border-box;
        }

        .promo-input:focus {
            outline: none;
            border-color: #007bff;
        }

        .promo-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
            white-space: nowrap;
            width: 100%;
            max-width: 350px;
        }

        .promo-btn:hover {
            background: #218838;
        }

        .promo-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .promo-message {
            margin-top: 0.5rem;
            padding: 0.5rem;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .promo-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .promo-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            grid-column: 1 / -1;
        }
        @media (max-width: 600px) {
            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
            .btn {
                width: 100%;
                min-width: 0;
            }
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 25px;
            font-size: 1.15rem;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: #6c757d;
            color: white;
        }

        .btn-edit:hover {
            background: #545b62;
        }

        .btn-proceed {
            background: #f2be00;
            color: white;
        }

        .btn-proceed:hover {
            background: #000000ff;
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .no-data i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #ddd;
        }

        .detail-label {
            font-weight: bold;
            color: #555;
            flex: 1;
            display: flex;
            align-items: center;
        }

        .detail-label img {
            flex-shrink: 0;
            object-fit: contain;
        }

        @media (max-width: 1024px) {
            .booking-detail-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .pricing-card {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .booking-detail-container {
                padding: 1rem;
            }
            
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .detail-value {
                text-align: left;
            }
            
            .promo-input-group {
                flex-direction: column;
                align-items: stretch;
            }
            
            .action-buttons {
                flex-direction: column;
            }

            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .detail-value {
                text-align: left;
            }
            
            .detail-label {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?= $this->include('navbar/navbar') ?>
    
    <main class="booking-detail-container">
        <div class="booking-detail-header">
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
            <div class="bottom-content">
                <h1>Booking Details</h1>
                <p>Please review your booking information</p>
            </div>
        </div>

        <div class="left-column">
            <div id="booking-details-content">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>

        <div class="right-column">
            <div class="pricing-card">
                <h3 class="pricing-header">
                    <i class="bi bi-calculator"></i> Pricing
                </h3>
                <div id="pricing-content">
                    <!-- Pricing will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button class="btn btn-edit" onclick="editBooking()">
                <i class="bi bi-pencil"></i> Edit Booking
            </button>
            <button class="btn btn-proceed" onclick="proceedToPayment()">
                <i class="bi bi-credit-card"></i> Proceed to Payment
            </button>
        </div>
    </main>
    
    <?= $this->include('footer/footer') ?>

    <script>
        let currentQuantity = 1;
        let appliedPromoCode = '';
        let promoDiscount = 0;
        let basePrice = 24;
        let promoType = 'percentage';
        let insuranceSelected = false;
        const DELIVERY_BASE_PRICE = <?= json_encode($deliveryPrice) ?>;
        const STORAGE_BASE_PRICE = <?= json_encode($storagePrice) ?>;

        document.addEventListener('DOMContentLoaded', function() {
            const bookingData = JSON.parse(sessionStorage.getItem('bookingData'));
            const contentDiv = document.getElementById('booking-details-content');

            if (bookingData) {
                if (bookingData.service === 'delivery') {
                    basePrice = DELIVERY_BASE_PRICE;
                } else {
                    basePrice = STORAGE_BASE_PRICE;
                }
            }
            
            if (!bookingData) {
                contentDiv.innerHTML = `
                    <div class="booking-details-card">
                        <div class="no-data">
                            <i class="bi bi-exclamation-triangle"></i>
                            <h3>No Booking Data Found</h3>
                            <p>Please go back to the booking page and complete your booking.</p>
                            <a href="booking" class="btn btn-proceed">Go to Booking</a>
                        </div>
                    </div>
                `;
                return;
            }

            // Initialize quantity and promo from booking data if exists
            currentQuantity = bookingData.quantity || 1;
            appliedPromoCode = bookingData.promoCode || '';
            promoDiscount = toNumber(bookingData.promoDiscount);
            promoType = bookingData.promoType || 'percentage';

            renderBookingDetails(bookingData);
            updatePricing();
        });

        function toNumber(val) {
            return parseFloat(val) || 0;
        }

        function renderBookingDetails(bookingData) {
            const contentDiv = document.getElementById('booking-details-content');
            let html = '';
            
            // Service type badge
            html += `<div class="service-type-badge">
                ${bookingData.service === 'delivery' ? 'In Town Delivery' : 'Luggage Storage'}
            </div>`;

            html += '<div class="booking-details-card">';

            if (bookingData.service === 'delivery') {
                // Delivery service details - GROUPED SEND FROM AND DELIVER TO
                html += `
                    <div class="detail-section">
                        <h3><i class="bi bi-geo-alt-fill"></i> Pickup & Delivery Details</h3>
                        
                    <!-- SEND FROM GROUP -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <strong style="font-size: 1.2rem; margin-bottom: 8px;">Send From</strong>
                                <img src="assets/images/send-from.png" alt="Send From" style="width: 120px; height: 120px; margin-bottom: 12px;">
                            </div>
                        </div>
                        <div class="detail-value">
                            <div class="location-info">
                                <strong>Origin: ${bookingData.origin}</strong>
                                ${bookingData.originAddress ? `<span>${bookingData.originAddress}</span>` : ''}
                                <div class="datetime-info" style="margin-top: 0.5rem;">
                                    <strong>Drop-off: ${formatDate(bookingData.dropoffDate)}</strong>
                                    <span>at ${formatTime(bookingData.dropoffTime)}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DELIVER TO GROUP -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <strong style="font-size: 1.2rem; margin-bottom: 8px;">Deliver To</strong>
                                <img src="assets/images/deliver-to.png" alt="Deliver To" style="width: 120px; height: 120px; margin-bottom: 12px;">
                            </div>
                        </div>
                        <div class="detail-value">
                            <div class="location-info">
                                <strong>Destination: ${bookingData.destination}</strong>
                                ${bookingData.destinationAddress ? `<span>${bookingData.destinationAddress}</span>` : ''}
                                <div class="datetime-info" style="margin-top: 0.5rem;">
                                    <strong>Pick-up: ${formatDate(bookingData.pickupDate)}</strong>
                                    <span>at ${formatTime(bookingData.pickupTime)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notice for Deliver To -->
                    <div style="background: #fdf5e6; border: 1px solid #deb887; border-radius: 8px; padding: 1rem; margin-top: 1rem; font-size: 1rem; color: #5d4037;">
                        <div style="display: flex; align-items: flex-start; gap: 0.5rem;">
                            <i class="bi bi-info-circle-fill" style="color: #ff6b35; margin-top: 2px; flex-shrink: 0;"></i>
                            <div>
                                <strong style="color: #d32f2f; font-size: 1.1rem;">Attention Please</strong><br>
                                Selected a hotel as your service point? Concierge/Front desk service is for hotel guests only. Kindly upload your stay documents to help us get access, or meet us outside at the main entrance.<br><br>
                                Please ensure you've selected the correct location, date and time for drop off or pick up. Additional charges may apply for late arrivals or incorrect destination delivery.
                            </div>
                        </div>
                    </div>

                    <!-- Quantity and Promo Section - ONLY for delivery -->
                    <div class="detail-section">
                        <h3><i class="bi bi-box"></i> Luggage Quantity & Promo</h3>
                        
                        <div class="detail-row">
                            <img src="assets/images/luggage-quantity.png" alt="Luggage Quantity" style="width: 120px; height: 120px; margin-right: 24px; vertical-align: middle;">
                            <div class="detail-label" style="flex: 2; flex-direction: column; align-items: flex-start; display: flex;">
                                <div style="font-weight:bold; font-size:1.1rem;">Max Weight and Dimension</div>
                                <div style="font-weight:normal; font-size:1rem; margin-top:4px;">
                                    23kg per item 180cm in total<br>
                                    dimensions (Length + Width + Height)
                                </div>
                            </div>
                            <div class="detail-value" style="flex: 1; margin-left: auto;">
                                <div class="quantity-controls" style="display: flex; flex-direction: row; align-items: center; gap: 1rem;">
                                    <div class="quantity-display" id="quantityDisplay">${currentQuantity}</div>
                                    <div style="display: flex; flex-direction: column; gap: 0.3rem;">
                                        <button class="quantity-btn-vertical" onclick="updateQuantity(1)" id="increaseBtn">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                        <button class="quantity-btn-vertical" onclick="updateQuantity(-1)" id="decreaseBtn">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">
                                <input type="checkbox" id="insuranceCheckbox" onchange="toggleInsurance()" />
                                <label for="insuranceCheckbox" style="margin-left:8px; font-weight:bold;">Add Insurance (RM 3 per luggage)</label>
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Promo Code:</div>
                            <div class="detail-value" style="flex: 3;">
                                <div class="promo-section">
                                    <div class="promo-input-group">
                                        <input type="text" 
                                            class="promo-input" 
                                            id="promoCodeInput" 
                                            placeholder="Enter promo code" 
                                            maxlength="20"
                                            value="${appliedPromoCode}"
                                            ${appliedPromoCode ? 'disabled' : ''}>
                                        <button type="button" class="promo-btn" onclick="applyPromoCode()" id="applyPromoBtn" ${appliedPromoCode ? 'disabled' : ''}>
                                            ${appliedPromoCode ? 'Applied' : 'Apply'}
                                        </button>
                                    </div>
                                    <div id="promoMessage" class="promo-message" style="display: ${appliedPromoCode ? 'block' : 'none'};">
                                        ${
                                            appliedPromoCode
                                                ? (promoType === 'amount'
                                                    ? `Promo code applied! RM${promoDiscount} discount`
                                                    : `Promo code applied! ${promoDiscount}% discount`)
                                                : ''
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                // Storage service details - GROUPED DROP-OFF AND PICK-UP
                html += `
                    <div class="detail-section">
                        <h3><i class="bi bi-building"></i> Storage Details</h3>
                        
                        <!-- STORAGE DROP-OFF GROUP -->
                        <div class="detail-row">
                            <div class="detail-label">
                                <div style="display: flex; flex-direction: column; align-items: center;">
                                    <strong style="font-size: 1.2rem; margin-bottom: 8px;">Storage Drop-off</strong>
                                    <img src="assets/images/send-from.png" alt="Drop-off" style="width: 120px; height: 120px; margin-bottom: 12px;">
                                </div>
                            </div>
                            <div class="detail-value">
                                <div class="location-info">
                                    <strong>Location: ${bookingData.storageLocation}</strong>
                                    <div class="datetime-info" style="margin-top: 0.5rem;">
                                        <strong>Drop-off: ${formatDate(bookingData.dropoffDate)}</strong>
                                        <span>at ${formatTime(bookingData.dropoffTime)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- STORAGE PICK-UP GROUP -->
                        <div class="detail-row">
                            <div class="detail-label">
                                <div style="display: flex; flex-direction: column; align-items: center;">
                                    <strong style="font-size: 1.2rem; margin-bottom: 8px;">Storage Pick-up</strong>
                                    <img src="assets/images/deliver-to.png" alt="Pick-up" style="width: 120px; height: 120px; margin-bottom: 12px;">
                                </div>
                            </div>
                            <div class="detail-value">
                                <div class="location-info">
                                    <strong>Location: ${bookingData.storageLocation}</strong>
                                    <div class="datetime-info" style="margin-top: 0.5rem;">
                                        <strong>Pick-up: ${formatDate(bookingData.pickupDate)}</strong>
                                        <span>at ${formatTime(bookingData.pickupTime)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="storageMap" style="width:100%;height:300px;margin-top:1rem;border-radius:8px;"></div>
                        <div style="background:#f5f5dc;border:1px solid #ddd;border-radius:8px;padding:1rem;margin-top:1rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);font-size:1rem;">
                            <strong>Address</strong><br>
                            No.118, Level 1, Plaza Aurora, Jln McDougall, Plaza Aurora, 93000 Kuching, Sarawak, Malaysia
                        </div>
                    <!-- Promo Section ONLY - NO quantity for storage -->
                    <div class="detail-section">
                        <h3><i class="bi bi-tag"></i> Promo Code</h3>
                        <div class="detail-row">
                            <img src="assets/images/luggage-quantity.png" alt="Luggage Quantity" style="width: 120px; height: 120px; margin-right: 24px; vertical-align: middle;">
                            <div class="detail-label" style="flex: 2; flex-direction: column; align-items: flex-start; display: flex;">
                                <div style="font-weight:bold; font-size:1.1rem;">Max Weight and Dimension</div>
                                <div style="font-weight:normal; font-size:1rem; margin-top:4px;">
                                    23kg per item 180cm in total<br>
                                    dimensions (Length + Width + Height)
                                </div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">
                                <input type="checkbox" id="insuranceCheckbox" onchange="toggleInsurance()" />
                                <label for="insuranceCheckbox" style="margin-left:8px; font-weight:bold;">Add Insurance (RM 3 per luggage)</label>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Promo Code:</div>
                            <div class="detail-value" style="flex: 3;">
                                <div class="promo-section">
                                    <div class="promo-input-group">
                                        <input type="text" 
                                            class="promo-input" 
                                            id="promoCodeInput" 
                                            placeholder="Enter promo code" 
                                            maxlength="20"
                                            value="${appliedPromoCode}"
                                            ${appliedPromoCode ? 'disabled' : ''}>
                                        <button type="button" class="promo-btn" onclick="applyPromoCode()" id="applyPromoBtn" ${appliedPromoCode ? 'disabled' : ''}>
                                            ${appliedPromoCode ? 'Applied' : 'Apply'}
                                        </button>
                                    </div>
                                    <div id="promoMessage" class="promo-message" style="display: ${appliedPromoCode ? 'block' : 'none'};">
                                        ${
                                            appliedPromoCode
                                                ? (promoType === 'amount'
                                                    ? `Promo code applied! RM${promoDiscount} discount`
                                                    : `Promo code applied! ${promoDiscount}% discount`)
                                                : ''
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            html += '</div>';
            contentDiv.innerHTML = html;

            if (bookingData.service === 'storage') {
                showStorageMap(1.556622, 110.344510);
            }
            
            // Only update quantity display for delivery service
            if (bookingData.service === 'delivery') {
                updateQuantityDisplay();
            }
        }

        function updatePricing() {
            const pricingDiv = document.getElementById('pricing-content');
            const bookingData = JSON.parse(sessionStorage.getItem('bookingData'));
            if (!bookingData) {
                pricingDiv.innerHTML = '<div class="no-data">No booking data found.</div>';
                return;
            }

            // Calculate time difference
            const start = new Date(bookingData.dropoffDate + ' ' + bookingData.dropoffTime);
            const end = new Date(bookingData.pickupDate + ' ' + bookingData.pickupTime);
            const diffHours = Math.ceil((end - start) / (1000 * 60 * 60));
            let html = '';

            let insuranceCharge = insuranceSelected ? (3 * currentQuantity) : 0;

            if (bookingData.service === 'delivery') {
                // In Town Delivery
                const baseHours = 24;
                const extraRate = 6;
                const exceededTimes = Math.max(0, Math.ceil((diffHours - baseHours) / 12));
                const baseStoragePrice = basePrice * currentQuantity;
                const extraStoragePrice = extraRate * exceededTimes * currentQuantity;

                html += `
                    <div class="price-row"><span class="price-label">Kuching Luggage Transfer</span></div>
                    <div class="price-row"><span class="price-label">Selected Transfer Point</span></div>
                    <div class="price-row">
                        <span class="price-label">${currentQuantity} Standard Luggage</span>
                    </div>
                    <div class="price-row"><span class="price-label">Kuching Luggage Storage</span></div>
                    <div class="price-row">
                        <span class="price-label">First 24 Hours</span>
                    </div>
                    <div class="price-row">
                        <span class="price-value">${currentQuantity} Standard Luggage</span>
                        <span class="price-value">MYR ${baseStoragePrice.toFixed(2)}</span>
                    </div>
                    <div class="price-row">
                        <span class="price-label">Subsequent 12 Hours x ${exceededTimes} Excess</span>
                        
                    </div>
                    <div class="price-row">
                        <span class="price-value">${currentQuantity} Standard Luggage</span>
                        <span class="price-value">MYR ${extraStoragePrice.toFixed(2)}</span>
                    </div>
                `;

                if (insuranceSelected) {
                    html += `
                        <div class="price-row">
                            <span class="price-label">Insurance</span>
                        </div>
                        <div class="price-row">
                            <span class="price-value">${currentQuantity} Standard Luggage</span>
                            <span class="price-value">MYR ${insuranceCharge.toFixed(2)}</span>
                        </div>
                    `;
                }

                // Discount
                let discountAmount = 0;
                if (appliedPromoCode && promoDiscount > 0) {
                    if (promoType === 'amount') {
                        discountAmount = promoDiscount;
                    } else {
                        discountAmount = (baseStoragePrice + extraStoragePrice) * promoDiscount / 100;
                    }
                    html += `
                        <div class="price-row">
                            <span class="price-label">Discount (${appliedPromoCode}):</span>
                            <span class="price-value discount-value">-MYR ${discountAmount.toFixed(2)}</span>
                        </div>
                    `;
                }

                const total = Math.max(0, baseStoragePrice + extraStoragePrice + insuranceCharge - discountAmount);
                html += `
                    <div class="price-row">
                        <span class="price-label">Total:</span>
                        <span class="price-value total-value">MYR ${total.toFixed(2)}</span>
                    </div>
                `;
            } else {
                // Luggage Storage
                const baseHours = 12;
                const extraRate = 6;
                const exceededTimes = Math.max(0, Math.ceil((diffHours - baseHours) / 12));
                const baseStoragePrice = basePrice * currentQuantity;
                const extraStoragePrice = extraRate * exceededTimes * currentQuantity;

                html += `
                    <div class="price-row"><span class="price-label">Kuching Luggage Storage</span></div>
                    <div class="price-row">
                        <span class="price-label">First 12 Hours</span>
                    </div>
                    <div class="price-row">
                    <span class="price-value">${currentQuantity} Standard Luggage</span>
                    <span class="price-value">MYR ${baseStoragePrice.toFixed(2)}</span>
                    </div>
                    <div class="price-row">
                        <span class="price-label">Subsequent 12 Hours x ${exceededTimes} Excess</span>
                        
                    </div>
                    <div class="price-row">
                        <span class="price-value">${currentQuantity} Standard Luggage</span>
                        <span class="price-value">MYR ${extraStoragePrice.toFixed(2)}</span>
                    </div>
                `;

                if (insuranceSelected) {
                    html += `
                        <div class="price-row">
                            <span class="price-label">Insurance</span>   
                        </div>
                        <div class="price-row">
                            <span class="price-value">${currentQuantity} Standard Luggage</span>
                            <span class="price-value">MYR ${insuranceCharge.toFixed(2)}</span>
                        </div>
                    `;
                }

                // Discount
                let discountAmount = 0;
                if (appliedPromoCode && promoDiscount > 0) {
                    if (promoType === 'amount') {
                        discountAmount = promoDiscount;
                    } else {
                        discountAmount = (baseStoragePrice + extraStoragePrice) * promoDiscount / 100;
                    }
                    html += `
                        <div class="price-row">
                            <span class="price-label">Discount (${appliedPromoCode}):</span>
                            <span class="price-value discount-value">-MYR ${discountAmount.toFixed(2)}</span>
                        </div>
                    `;
                }

                const total = Math.max(0, baseStoragePrice + extraStoragePrice + insuranceCharge - discountAmount);
                html += `
                    <div class="price-row">
                        <span class="price-label">Total:</span>
                        <span class="price-value total-value">MYR ${total.toFixed(2)}</span>
                    </div>
                `;
            }

            pricingDiv.innerHTML = html;
        }

        function updateQuantity(change) {
            const bookingData = JSON.parse(sessionStorage.getItem('bookingData'));
            
            // Only allow quantity changes for delivery service
            if (!bookingData || bookingData.service !== 'delivery') {
                console.log('Quantity changes not allowed for storage service');
                return;
            }
            
            const newQuantity = currentQuantity + change;
            
            // Minimum quantity is 1, maximum is 10
            if (newQuantity >= 1 && newQuantity <= 10) {
                currentQuantity = newQuantity;
                updateQuantityDisplay();
                updatePricing();
                updateBookingData();
            }
        }

        function updateQuantityDisplay() {
            const quantityDisplay = document.getElementById('quantityDisplay');
            const decreaseBtn = document.getElementById('decreaseBtn');
            const increaseBtn = document.getElementById('increaseBtn');
            
            if (quantityDisplay) {
                quantityDisplay.textContent = currentQuantity;
            }
            
            // Disable decrease button if quantity is 1
            if (decreaseBtn) {
                decreaseBtn.disabled = currentQuantity <= 1;
            }
            
            // Disable increase button if quantity is 10
            if (increaseBtn) {
                increaseBtn.disabled = currentQuantity >= 10;
            }
        }

        function applyPromoCode() {
            console.log('applyPromoCode function called');
            
            const promoInput = document.getElementById('promoCodeInput');
            const promoBtn = document.getElementById('applyPromoBtn');
            const promoMessage = document.getElementById('promoMessage');
            
            console.log('Elements found:', { promoInput, promoBtn, promoMessage });
            
            if (!promoInput || !promoBtn || !promoMessage) {
                console.error('Promo elements not found in DOM');
                alert('Error: Promo code elements not found');
                return;
            }
            
            const promoCode = promoInput.value.trim().toUpperCase();
            console.log('Promo code entered:', promoCode);
            
            if (!promoCode) {
                showPromoMessage('Please enter a promo code', 'error');
                return;
            }
            
            // Disable button and show loading
            promoBtn.disabled = true;
            promoBtn.textContent = 'Applying...';
            
            console.log('Sending request to check promo code...');
            
            // Check promo code with server
            fetch('checkPromoCode', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ promo_code: promoCode })
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.text(); // Get as text first for debugging
            })
            .then(text => {
                console.log('Raw response text:', text);
                
                try {
                    const data = JSON.parse(text);
                    console.log('Parsed response data:', data);
                    
                    if (data.success && data.valid) {
                        appliedPromoCode = promoCode;
                        promoDiscount = toNumber(data.discount);
                        promoType = data.discount_type ? data.discount_type : 'percentage';
                        
                        let promoMsg = '';
                        if (promoType === 'amount') {
                            promoMsg = `Promo code applied! RM${promoDiscount} discount`;
                        } else {
                            promoMsg = `Promo code applied! ${promoDiscount}% discount`;
                        }
                        showPromoMessage(promoMsg, 'success');
                        promoBtn.textContent = 'Applied';
                        promoInput.disabled = true;
                        updatePricing();
                        updateBookingData();
                    } else {
                        showPromoMessage(data.message || 'Invalid promo code', 'error');
                        promoBtn.disabled = false;
                        promoBtn.textContent = 'Apply';
                    }
                } catch (parseError) {
                    console.error('JSON parse error:', parseError);
                    showPromoMessage('Server returned invalid response', 'error');
                    promoBtn.disabled = false;
                    promoBtn.textContent = 'Apply';
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showPromoMessage('Error checking promo code: ' + error.message, 'error');
                promoBtn.disabled = false;
                promoBtn.textContent = 'Apply';
            });
        }

        function showPromoMessage(message, type) {
            console.log('Showing promo message:', message, type); // Debug log
            
            const promoMessage = document.getElementById('promoMessage');
            if (promoMessage) {
                promoMessage.textContent = message;
                promoMessage.className = `promo-message promo-${type}`;
                promoMessage.style.display = 'block';
                
                // Auto-hide error messages after 5 seconds
                if (type === 'error') {
                    setTimeout(() => {
                        promoMessage.style.display = 'none';
                    }, 5000);
                }
            } else {
                console.error('Promo message element not found');
                alert(message); // Fallback to alert
            }
        }

        function updateBookingData() {
            const bookingData = JSON.parse(sessionStorage.getItem('bookingData'));
            if (bookingData) {
                bookingData.quantity = currentQuantity;
                bookingData.promoCode = appliedPromoCode;
                bookingData.promoDiscount = toNumber(promoDiscount);
                bookingData.promoType = promoType;
                bookingData.basePrice = basePrice;
                bookingData.totalPrice = calculateTotal();
                bookingData.insuranceSelected = insuranceSelected;
                bookingData.insuranceAmount = insuranceSelected ? (3 * currentQuantity) : 0;
                sessionStorage.setItem('bookingData', JSON.stringify(bookingData));
            }
        }

        function calculateTotal() {
            const subtotal = basePrice * currentQuantity;
            let discountAmount = 0;
            if (appliedPromoCode && promoDiscount > 0) {
                if (promoType === 'amount') {
                    discountAmount = promoDiscount;
                } else {
                    discountAmount = subtotal * promoDiscount / 100;
                }
            }
            return Math.max(0, subtotal - discountAmount);
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            return date.toLocaleDateString('en-US', options);
        }

        function formatTime(timeString) {
            const [hours, minutes] = timeString.split(':');
            const time = new Date();
            time.setHours(parseInt(hours), parseInt(minutes));
            return time.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true 
            });
        }

        // Function to handle edit booking with data preservation
        function editBooking() {
            // Update booking data with current changes before going back
            updateBookingData();
            
            const bookingData = JSON.parse(sessionStorage.getItem('bookingData'));
            if (bookingData) {
                // Set a flag to indicate we're editing
                sessionStorage.setItem('isEditing', 'true');
                
                // Store current state to preserve all changes
                const editingData = {
                    ...bookingData,
                    quantity: currentQuantity,
                    promoCode: appliedPromoCode,
                    promoDiscount: promoDiscount,
                    totalPrice: calculateTotal(),
                    lastUpdated: new Date().getTime()
                };
                
                sessionStorage.setItem('bookingData', JSON.stringify(editingData));
                
                // Debug log
                console.log('Editing booking with data:', editingData);
                
                // Redirect to booking page
                window.location.href = 'booking';
            } else {
                alert('No booking data found. Starting fresh booking.');
                window.location.href = 'booking';
            }
        }

        function proceedToPayment() {
            const bookingData = JSON.parse(sessionStorage.getItem('bookingData'));
            if (bookingData) {
                // Update booking data with current quantity and promo code
                updateBookingData();
                // Redirect to customer details page
                window.location.href = 'bookingcustomerdetail';
            } else {
                alert('No booking data found. Please go back and complete your booking.');
                window.location.href = 'booking';
            }
        }

        function showStorageMap(lat, lng) {
            const mapDiv = document.getElementById('storageMap');
            if (!mapDiv) return;
            const map = new google.maps.Map(mapDiv, {
                center: { lat: lat, lng: lng },
                zoom: 16
            });
            new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map
            });
        }

        function calculateExtraCharge(serviceType, baseHours, extraRate, startDateTime, endDateTime, quantity) {
            // Parse dates
            const start = new Date(startDateTime);
            const end = new Date(endDateTime);
            // Calculate total hours
            const diffMs = end - start;
            const diffHours = Math.ceil(diffMs / (1000 * 60 * 60));
            // Calculate extra hours
            const extraHours = Math.max(0, diffHours - baseHours);
            // Number of extra 12-hour blocks
            const extraBlocks = Math.ceil(extraHours / 12);
            // Total extra charge
            return extraBlocks * extraRate * quantity;
        }

        function toggleInsurance() {
            insuranceSelected = document.getElementById('insuranceCheckbox').checked;
            updatePricing();
            updateBookingData();
        }
    </script>
</body>

</html>