<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details - EASE SARAWAK</title>
    <link rel="icon" type="image/png" href="assets/images/cropped-Ease_PNG_File-09.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/footer_style.css">
    <link rel="stylesheet" href="assets/css/navbar_style.css">

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
        }

        .customer-detail-container {
            max-width: 900px;
            margin: 0 auto;
            margin-top: 2rem;
            padding: 2rem;
            min-height: calc(100vh - 200px);
            margin-left: 0;
            padding-left: 70px;
        }

        .customer-detail-main {
            display: flex;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            margin-top: 2rem;
            padding: 2rem 0;
            justify-content: flex-start;
        }

        #customerForm {
            flex: 2;
            min-width: 900px;
            margin-left: 0;
        }

        #bookingSummaryForm {
            flex: 1;
            min-width: 400px;
            height: 900px;
        }

        /* Booking summary section styles */
        .booking-summary-section {
            margin-bottom: 1.5em;
            padding-bottom: 1.5em;
            border-bottom: 2px solid #f2be00;
        }

        .booking-summary-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .booking-summary-heading {
            color: #f2be00;
            font-weight: bold;
            font-size: 1.15em;
            margin-bottom: 0.5em;
            letter-spacing: 0.5px;
        }

        .booking-summary-label {
            color: #888;
            font-size: 0.98em;
            margin-bottom: 0.2em;
        }

        .booking-summary-value {
            font-weight: bold;
            font-size: 1.08em;
            margin-bottom: 0.7em;
        }

        .booking-summary-total {
            color: #f2be00;
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 0.5em;
        }

        @media (max-width: 1000px) {
            .customer-detail-main {
                flex-direction: column;
            }
            #bookingSummaryForm {
                margin-top: 2rem;
            }
        }

        .price-heading {
            color: #f2be00;
            font-weight: bold;
            font-size: 1.15em;
            margin-top: 1.5em;
            margin-bottom: 0.5em;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }
        .price-label {
            color: #000;
            font-weight: bold;
        }
        .price-value {
            font-weight: bold;
            color: #333;
        }
        .discount-value {
            color: #e67e22;
        }
        .total-value {
            color: #f2be00;
            font-size: 1.15em;
        }

        .customer-detail-header {
            text-align: center;
            margin-bottom: 2rem;
            margin-top: 2rem;
        }

        .customer-detail-header h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .customer-detail-header p {
            font-size: 1.1rem;
            color: #666;
        }

        .section {
            background: white;
            padding: 2rem;
            border-radius: 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f2be00;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding-left: 0.8rem;
            padding-right: 0.8rem;
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 0;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }

        .socialform-group select {
            width: 100%;
            padding-left: 0.8rem;
            padding-right: 0.8rem;
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 0;
            font-size: 1rem;
            background-color: white;
            cursor: pointer;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='black' xmlns='http://www.w3.org/2000/svg'%3E%3Cpolygon points='5,7 10,13 15,7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 18px center;
            background-size: 20px;
            padding-right: 48px;
        }

        select::-ms-expand {
            display: none;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #ddd;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2.5rem;
        }

        .file-upload {
            border: 2px dashed #ddd;
            border-radius: 0;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .file-upload:hover {
            border-color: #007bff;
        }

        .file-upload i {
            font-size: 2rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        .file-upload p {
            margin: 0;
            color: #666;
        }

        .file-upload.dragover {
            border-color: #007bff;
            background-color: #f8f9ff;
        }

        .file-info {
            display: none;
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 0;
            font-size: 0.9rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 0;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background: #545b62;
        }

        .btn-submit {
            background: #28a745;
            color: white;
        }

        .btn-submit:hover {
            background: #218838;
        }

        @media (max-width: 768px) {
            .customer-detail-container {
                padding: 1rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }

        .custom-tooltip-wrapper {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .custom-tooltip-text {
            visibility: hidden;
            width: 240px;
            background-color: #f2be00;
            color: #333;
            text-align: left;
            border-radius: 0;
            padding: 8px 12px;
            position: absolute;
            z-index: 10;
            bottom: 125%; /* Show above the icon */
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.2s;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .custom-tooltip-wrapper:hover .custom-tooltip-text,
        .custom-tooltip-wrapper:focus-within .custom-tooltip-text {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>

<body>
    <?= $this->include('navbar/navbar') ?>
    
    <main class="customer-detail-container">
        <div class="customer-detail-header">
            <h1>Customer Details</h1>
            <p>Please provide your contact information</p>
        </div>

        <div class="customer-detail-main">
            <form id="customerForm">
                <!-- Customer Information Section -->
                <div class="section">
                    <h2 class="section-title">
                        <i class="bi bi-person-fill"></i> CUSTOMER INFORMATION
                    </h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name
                                <span class="custom-tooltip-wrapper">
                                    <i class="bi bi-info-circle"></i>
                                    <span class="custom-tooltip-text">Enter your legal first name as it appears on your ID</span>
                                </span></label>
                            <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name
                                <span class="custom-tooltip-wrapper">
                                    <i class="bi bi-info-circle"></i>
                                    <span class="custom-tooltip-text">Enter your legal last name as it appears on your ID</span>
                                </span></label>
                            <input type="text" id="lastName" name="lastName" placeholder="Enter your last name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="identificationNumber">Identification Number 
                            <span class="custom-tooltip-wrapper">
                                    <i class="bi bi-info-circle"></i>
                                    <span class="custom-tooltip-text">Provide your ID number (e.g., Passport, driving license or NRIC) for verification</span>
                                </span></label>
                        <input type="text" id="identificationNumber" name="identificationNumber" placeholder="Enter your identification" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address 
                            <span class="custom-tooltip-wrapper">
                                    <i class="bi bi-info-circle"></i>
                                    <span class="custom-tooltip-text">Enter a valid email address where we can send your booking details and updates.</span>
                                </span></label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number 
                            <span class="custom-tooltip-wrapper">
                                    <i class="bi bi-info-circle"></i>
                                    <span class="custom-tooltip-text">Provide a contact number where we can reach you for booking-related updates.</span>
                                </span></label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone" required>
                    </div>

                    <div class="form-group">
                        <label>Social Contact 
                            <span class="custom-tooltip-wrapper">
                                    <i class="bi bi-info-circle"></i>
                                    <span class="custom-tooltip-text">Add your social media handle (e.g., WhatsApp, Facebook, Line or WeChat) for easier communication.</span>
                                </span></label>
                        <div class="form-row">
                            <div class="socialform-group">
                                <select id="socialContactType" name="socialContactType" required>
                                    <option value="">Select social platform</option>
                                    <option value="whatsapp">WhatsApp</option>
                                    <option value="wechat">WeChat</option>
                                    <option value="line">Line</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" id="socialContactValue" name="socialContactValue" placeholder="Enter your contact number" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Baggage photo/Travel documents upload (Optional) 
                            <span class="custom-tooltip-wrapper">
                                    <i class="bi bi-info-circle"></i>
                                    <span class="custom-tooltip-text">Upload a photo of your luggage, or travel documents if you are using hotel concierge/front desk service.</span>
                                </span></label>
                        <div class="file-upload" id="fileUpload">
                            <i class="bi bi-cloud-upload"></i>
                            <p>No file chosen</p>
                            <p>Select a file or drop it here</p>
                            <input type="file" id="documentUpload" name="documentUpload[]" style="display: none;" accept="image/*,.pdf,.doc,.docx" multiple>
                        </div>
                        <div class="file-info" id="fileInfo"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Do you have a special luggage? 
                        <span class="custom-tooltip-wrapper">
                                    <i class="bi bi-info-circle"></i>
                                    <span class="custom-tooltip-text">For oversized items. Please select if your baggage exceeds the standard size limit.</span>
                                </span></label>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: inline-flex; align-items: center; margin-right: 2rem; font-weight: normal; cursor: pointer;">
                            <input type="radio" id="specialLuggageYes" name="specialLuggage" value="1" style="margin-right: 0.5rem; width: auto;">
                            Yes
                        </label>
                        <label style="display: inline-flex; align-items: center; font-weight: normal; cursor: pointer;">
                            <input type="radio" id="specialLuggageNo" name="specialLuggage" value="0" style="margin-right: 0.5rem; width: auto;" checked>
                            No
                        </label>
                    </div>
                    
                    <!-- Attention notice -->
                    <div style="margin-top: 1.5rem; padding: 1rem; background-color: #fff3cd; border: 0 solid #ffeaa7; border-radius: 8px;">
                        <h4 style="margin: 0 0 0.5rem 0; color: #856404; font-size: 1rem;">
                            <i class="bi bi-exclamation-triangle-fill" style="color: #f39c12; margin-right: 0.5rem;"></i>
                            Attention Please
                        </h4>
                        <p style="margin: 0; color: #856404; font-size: 0.9rem;">
                            Please ensure all information provided is accurate and complete to avoid any delays or service interruptions.
                        </p>
                    </div>

                    <!-- Special Luggage Note input (hidden by default) -->
                    <div id="specialLuggageNoteDiv" style="display: none; margin-top: 1rem;">
                        <label for="specialLuggageNote">Special Luggage Note </label>
                        <textarea id="specialLuggageNote" name="specialLuggageNote" placeholder="Please describe your special luggage requirements..." rows="3"></textarea>
                    </div>
                </div>
            </form>

            <form id="bookingSummaryForm" class="section">
                <h2 class="section-title"><i class="bi bi-clipboard-check"></i> BOOKING SUMMARY</h2>
                <div id="bookingSummary"></div>
                <div id="pricing-content"></div>
            </form>
        </div>

        <!-- <div class="action-buttons">
            <button type="button" class="btn btn-submit" onclick="goToPayment()">Payment </button>
        </div> -->

        <div class="action-buttons">
            <a href="bookingdetail" class="btn btn-back">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <button class="btn btn-submit" onclick="submitBooking()">
                <i class="bi bi-check-circle"></i> Submit Booking
            </button>
        </div>
    </main>
    
    <?= $this->include('footer/footer') ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // File upload handling
            const fileUpload = document.getElementById('fileUpload');
            const fileInput = document.getElementById('documentUpload');
            const fileInfo = document.getElementById('fileInfo');

            fileUpload.addEventListener('click', function() {
                fileInput.click();
            });

            fileUpload.addEventListener('dragover', function(e) {
                e.preventDefault();
                fileUpload.classList.add('dragover');
            });

            fileUpload.addEventListener('dragleave', function() {
                fileUpload.classList.remove('dragover');
            });

            fileUpload.addEventListener('drop', function(e) {
                e.preventDefault();
                fileUpload.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    updateFileInfo(files);
                }
            });

            fileInput.addEventListener('change', function() {
                if (fileInput.files.length > 0) {
                    updateFileInfo(fileInput.files);
                }
            });

            function updateFileInfo(files) {
                fileInfo.style.display = 'block';
                let html = '';
                for (let i = 0; i < files.length; i++) {
                    html += `<div><i class="bi bi-file-earmark"></i> <strong>${files[i].name}</strong> (${(files[i].size / 1024 / 1024).toFixed(2)} MB)</div>`;
                }
                fileInfo.innerHTML = html;
                fileUpload.querySelector('p').textContent = files.length === 1
                    ? 'File selected: ' + files[0].name
                    : files.length + ' files selected';
            }

            // Special luggage handling
            const specialLuggageYes = document.getElementById('specialLuggageYes');
            const specialLuggageNo = document.getElementById('specialLuggageNo');
            const specialLuggageNoteDiv = document.getElementById('specialLuggageNoteDiv');
            const specialLuggageNote = document.getElementById('specialLuggageNote');

            function toggleSpecialLuggageNote() {
                if (specialLuggageYes.checked) {
                    specialLuggageNoteDiv.style.display = 'block';
                    specialLuggageNote.setAttribute('required', 'required');
                } else {
                    specialLuggageNoteDiv.style.display = 'none';
                    specialLuggageNote.removeAttribute('required');
                    specialLuggageNote.value = ''; // Clear the note when "No" is selected
                }
            }

            specialLuggageYes.addEventListener('change', toggleSpecialLuggageNote);
            specialLuggageNo.addEventListener('change', toggleSpecialLuggageNote);
            
            // Initialize on page load
            toggleSpecialLuggageNote();

            const bookingSummaryDiv = document.getElementById('bookingSummary');
            if (bookingSummaryDiv) {
                const bookingData = JSON.parse(sessionStorage.getItem('bookingData'));
                let summaryHtml = '';
                let pricingHtml = '';

                if (bookingData) {
                    // --- Summary Section ---
                    if (bookingData.service === 'storage') {
                        summaryHtml = `
                            <div class="booking-summary-section">
                                <div class="booking-summary-heading">Luggage Storage</div>
                            </div>
                            <div class="booking-summary-section">
                                <div class="booking-summary-label">Storage Location</div>
                                <div class="booking-summary-value">${bookingData.storageLocation || '-'}</div>
                            </div>
                            <div class="booking-summary-section">
                                <div class="booking-summary-label">Drop-off date & time</div>
                                <div class="booking-summary-value">${bookingData.dropoffDate || '-'} Time: ${bookingData.dropoffTime || '-'}</div>
                            </div>
                            <div class="booking-summary-section">
                                <div class="booking-summary-label">Pick-up date & time</div>
                                <div class="booking-summary-value">${bookingData.pickupDate || '-'} Time: ${bookingData.pickupTime || '-'}</div>
                            </div>
                        `;
                    } else {
                        summaryHtml = `
                            <div class="booking-summary-section">
                                <div class="booking-summary-heading">${bookingData.service ? bookingData.service.replace(/-/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) : 'In-town Delivery'}</div>
                            </div>
                            <div class="booking-summary-section">
                                <div class="booking-summary-label">Drop-off your luggage from</div>
                                <div class="booking-summary-value">${bookingData.origin || '-'}</div>
                            </div>
                            <div class="booking-summary-section">
                                <div class="booking-summary-label">Drop-off date & time</div>
                                <div class="booking-summary-value">${bookingData.dropoffDate || '-'} Time: ${bookingData.dropoffTime || '-'}</div>
                            </div>
                            <div class="booking-summary-section">
                                <div class="booking-summary-label">Pick-up your luggage at</div>
                                <div class="booking-summary-value">${bookingData.destination || '-'}</div>
                            </div>
                            <div class="booking-summary-section">
                                <div class="booking-summary-label">Pick-up date & time</div>
                                <div class="booking-summary-value">${bookingData.pickupDate || '-'} Time: ${bookingData.pickupTime || '-'}</div>
                            </div>
                        `;
                    }
                    bookingSummaryDiv.innerHTML = summaryHtml;
                } else {
                    bookingSummaryDiv.innerHTML = '<div style="color:#888;">No booking data found.</div>';
                }
            }
        });

        function submitBooking() {
            // Validate form
            const form = document.getElementById('customerForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Get booking data from previous page
            const bookingData = JSON.parse(sessionStorage.getItem('bookingData'));
            if (!bookingData) {
                alert('Booking data not found. Please go back and complete your booking.');
                window.location.href = 'booking';
                return;
            }

            // Show loading message
            const submitButton = document.querySelector('.btn-submit');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Submitting...';
            submitButton.disabled = true;

            // Create FormData object to handle file upload
            const formData = new FormData();
            
            // Add customer data
            formData.append('firstName', document.getElementById('firstName').value);
            formData.append('lastName', document.getElementById('lastName').value);
            formData.append('identificationNumber', document.getElementById('identificationNumber').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('phone', document.getElementById('phone').value);
            formData.append('socialContactType', document.getElementById('socialContactType').value);
            formData.append('socialContactValue', document.getElementById('socialContactValue').value);
            
            // Add special luggage data
            const specialLuggageValue = document.querySelector('input[name="specialLuggage"]:checked').value;
            formData.append('specialLuggage', specialLuggageValue);
            
            if (specialLuggageValue === '1') {
                formData.append('specialLuggageNote', document.getElementById('specialLuggageNote').value);
            }
            
            // Add booking data as JSON string
            formData.append('bookingData', JSON.stringify(bookingData));
            
            // Add file if selected
            const fileInput = document.getElementById('documentUpload');
            if (fileInput.files.length > 0) {
                for (let i = 0; i < fileInput.files.length; i++) {
                    formData.append('documentUpload[]', fileInput.files[i]);
                }
            }

            // Debug: Log the data being sent
            console.log('Sending form data with file upload');

            // Send data to server using FormData (not JSON)
            fetch('saveOrder', {
                method: 'POST',
                body: formData 
            })
            .then(response => {
                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.text();
            })
            .then(text => {
                console.log('Raw response:', text);
                
                try {
                    const data = JSON.parse(text);
                    console.log('Parsed response data:', data);
                    
                    if (data.success) {
                        sessionStorage.setItem('bookingData', JSON.stringify(bookingData));
                        
                        // Show success message
                        alert('Booking submitted successfully! Order ID: ' + data.order_id);
                        
                        // Redirect to confirmation page
                        window.location.href = 'payment';
                    } else {
                        alert('Error submitting booking: ' + data.message);
                        submitButton.innerHTML = originalText;
                        submitButton.disabled = false;
                    }
                } catch (parseError) {
                    console.error('JSON parse error:', parseError);
                    alert('Server returned invalid response: ' + text);
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('An error occurred while submitting your booking: ' + error.message);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            updatePricing();
        });

        function updatePricing() {
            const pricingDiv = document.getElementById('pricing-content');
            const bookingData = JSON.parse(sessionStorage.getItem('bookingData'));
            if (!bookingData) {
                pricingDiv.innerHTML = '<div class="no-data">No booking data found.</div>';
                return;
            }

            // Get values from bookingData
            const currentQuantity = parseInt(bookingData.quantity) || 1;
            const insuranceSelected = bookingData.insuranceSelected === true
                || bookingData.insuranceSelected === 'true'
                || bookingData.insuranceSelected === 1
                || bookingData.insuranceSelected === '1'
                || bookingData.insuranceSelected === 'on';
            const basePrice = bookingData.service === 'delivery' ? 24 : 18; // 24 for delivery, 18 for storage
            const promoDiscount = parseFloat(bookingData.promoDiscount) || 0;
            const promoType = bookingData.promoType || 'amount';
            const appliedPromoCode = bookingData.promoCode || '';
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
                    <div class="price-heading">Pricing</div>
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
                    <div class="price-heading">Pricing</div>
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
    </script>

    <script>
    function goToPayment() {
    // take the email from the email address
        const emailInput = document.getElementById('email');
        const email = emailInput ? emailInput.value.trim() : '';

        if (!email) {
            alert('Please fill in your Email Address before going to payment.');
            if (emailInput) {
                emailInput.focus();
        }
        return;
    }

    // bring email to payment page using POST, so email will not appear in URL
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'payment';  // 指向 /payment

    const emailInputHidden = document.createElement('input');
    emailInputHidden.type = 'hidden';
    emailInputHidden.name = 'email';
    emailInputHidden.value = email;

    form.appendChild(emailInputHidden);
    document.body.appendChild(form);
    form.submit();
    }
    </script>


</body>

</html>