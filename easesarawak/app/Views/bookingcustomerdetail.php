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
            border-radius: 15px;
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
            padding: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #007bff;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .file-upload {
            border: 2px dashed #ddd;
            border-radius: 8px;
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
            border-radius: 5px;
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
            border-radius: 25px;
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
    </style>
</head>

<body>
    <?= $this->include('navbar/navbar') ?>
    
    <main class="customer-detail-container">
        <div class="customer-detail-header">
            <h1>Customer Details</h1>
            <p>Please provide your contact information</p>
        </div>

        <form id="customerForm">
            <!-- Customer Information Section -->
            <div class="section">
                <h2 class="section-title">
                    <i class="bi bi-person-fill"></i> CUSTOMER INFORMATION
                </h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name <i class="bi bi-info-circle"></i></label>
                        <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name <i class="bi bi-info-circle"></i></label>
                        <input type="text" id="lastName" name="lastName" placeholder="Enter your last name" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="identificationNumber">Identification Number <i class="bi bi-info-circle"></i></label>
                    <input type="text" id="identificationNumber" name="identificationNumber" placeholder="Enter your identification" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address <i class="bi bi-info-circle"></i></label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number <i class="bi bi-info-circle"></i></label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone" required>
                </div>

                <div class="form-group">
                    <label>Social Contact <i class="bi bi-info-circle"></i></label>
                    <div class="form-row">
                        <div class="form-group">
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
                    <label>Baggage photo/Travel documents upload (Optional) <i class="bi bi-info-circle"></i></label>
                    <div class="file-upload" id="fileUpload">
                        <i class="bi bi-cloud-upload"></i>
                        <p>No file chosen</p>
                        <p>Select a file or drop it here</p>
                        <input type="file" id="documentUpload" name="documentUpload" style="display: none;" accept="image/*,.pdf,.doc,.docx">
                    </div>
                    <div class="file-info" id="fileInfo"></div>
                </div>
            </div>

            <div class="form-group">
                <label>Do you have a special luggage? <i class="bi bi-info-circle"></i></label>
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
                <div style="margin-top: 1.5rem; padding: 1rem; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px;">
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
                    <label for="specialLuggageNote">Special Luggage Note <i class="bi bi-info-circle"></i></label>
                    <textarea id="specialLuggageNote" name="specialLuggageNote" placeholder="Please describe your special luggage requirements..." rows="3"></textarea>
                </div>
            </div>
        </form>

        <div class="action-buttons">
            <button type="button" class="btn btn-submit" onclick="goToPayment()">Payment </button>
        </div>

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
                    updateFileInfo(files[0]);
                }
            });

            fileInput.addEventListener('change', function() {
                if (fileInput.files.length > 0) {
                    updateFileInfo(fileInput.files[0]);
                }
            });

            function updateFileInfo(file) {
                fileInfo.style.display = 'block';
                fileInfo.innerHTML = `
                    <i class="bi bi-file-earmark"></i>
                    <strong>${file.name}</strong> (${(file.size / 1024 / 1024).toFixed(2)} MB)
                `;
                fileUpload.querySelector('p').textContent = 'File selected: ' + file.name;
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
                formData.append('documentUpload', fileInput.files[0]);
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
                        // Clear session storage
                        sessionStorage.removeItem('bookingData');
                        sessionStorage.removeItem('customerData');
                        
                        // Show success message
                        alert('Booking submitted successfully! Order ID: ' + data.order_id);
                        
                        // Redirect to confirmation page
                        window.location.href = 'booking-confirmation?order_id=' + data.order_id;
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