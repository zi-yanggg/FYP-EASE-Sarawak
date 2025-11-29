<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EASE SARAWAK | Payment</title>
    <link rel="icon" type="image/png" href="assets/images/cropped-Ease_PNG_File-09.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/footer_style.css">
    <link rel="stylesheet" href="assets/css/payment_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <script src="https://js.stripe.com/v3/"></script>
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


        <!-- Payment Section -->
        <div class="payment-section">
            <!-- Payment Form -->
            <div class="payment-form">
                <h2 class="section-title">Payment Information</h2>
                
                <div class="form-group">
                    <label for="cardName">Name on Card</label>
                    <input type="text" id="cardName" class="form-control" placeholder="John Doe">
                </div>
                
        <div class="form-group">
            <label>Card Number</label>
            <div id="card-number-element" class="form-control" style="padding: 10px 12px;"></div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Expiry Date</label>
                <div id="card-expiry-element" class="form-control" style="padding: 10px 12px;"></div>
            </div>
            <div class="form-group">
                <label>CVV</label>
                <div id="card-cvc-element" class="form-control" style="padding: 10px 12px;"></div>
            </div>
        </div>

        <div id="card-errors" style="color: red; margin-top: 8px; font-size: 0.9rem;"></div>

                
                <div class="form-group">
                    <label>Payment Method</label>
                    <div class="payment-methods">
                        <div class="payment-method">
                            <i class="bi bi-credit-card"></i>
                            <div>Credit Card</div>
                        </div>
                    </div>
                </div>
                
                <button class="btn-primary">Complete Payment</button>
            </div>
            
            <!-- Payment Summary -->
            <div class="payment-summary">
                <h2 class="section-title">Order Summary</h2>
                
                <!-- Filled by renderOrderSummary() -->
                    <div id="order-summary-content"></div>
                
                <div style="margin-top: 2rem;">
                    <h3 style="margin-bottom: 1rem;">Need Help?</h3>
                    <p>Contact our customer service:</p>
                    <p><i class="bi bi-telephone"></i> +60 12-345 6789</p>
                    <p><i class="bi bi-envelope"></i> easesarawak.com</p>
                </div>
            </div>
        </div>
    </div>


    <script>
    document.addEventListener('DOMContentLoaded', function () {
    var container = document.getElementById('order-summary-content');
    if (!container) return;

    var html = '';

    try {
        var raw = sessionStorage.getItem('bookingData');

        if (!raw) {
            html += '<div class="summary-item">' +
                        '<span>Service</span>' +
                        '<span>-</span>' +
                    '</div>';
            html += '<div class="summary-total">' +
                        '<span>Total</span>' +
                        '<span>RM 0.00</span>' +
                    '</div>';
            container.innerHTML = html;
            return;
        }

        var bookingData = JSON.parse(raw);
        console.log('bookingData in payment.php:', bookingData);

        // ===== （Service type：In Town Delivery / Luggage Storage） =====
        var serviceLabel = '-';
        if (bookingData.service === 'delivery') {
            serviceLabel = 'In Town Delivery';
        } else if (bookingData.service === 'storage') {
            serviceLabel = 'Luggage Storage';
        } else if (bookingData.service) {
            serviceLabel = bookingData.service;
        }

        html += '<div class="summary-item">' +
                    '<span>' + serviceLabel + '</span>' +
                    '<span></span>' +
                '</div>';

        // baseprice + exceedtimes =====
        var quantity  = Number(bookingData.quantity || 1);
        var basePrice = Number(bookingData.basePrice || 0);

        // baseprice：first 24 hours（delivery）or first 12 hours（storage）
        var baseStoragePrice = basePrice * quantity;

        // exceedTimes
        var extraStoragePrice = 0;
        var exceededTimes = 0;

        if (bookingData.dropoffDate && bookingData.dropoffTime &&
            bookingData.pickupDate && bookingData.pickupTime) {

            var start = new Date(bookingData.dropoffDate + ' ' + bookingData.dropoffTime);
            var end   = new Date(bookingData.pickupDate  + ' ' + bookingData.pickupTime);
            var diffMs = end - start;

            if (!isNaN(diffMs) && diffMs > 0) {
                var diffHours = Math.ceil(diffMs / (1000 * 60 * 60));

                // In Town Delivery：first 24hours；Luggage Storage：first 12 hours
                var baseHours = (bookingData.service === 'storage') ? 12 : 24;
                var extraRate = 6; 

                exceededTimes = Math.max(0, Math.ceil((diffHours - baseHours) / 12));
                extraStoragePrice = Math.max(0, exceededTimes * extraRate * quantity);
            }
        }

        // =====order summary base storage price =====
        if (baseStoragePrice > 0) {
            html += '<div class="summary-item">' +
                        '<span>' + quantity + ' Standard Luggage</span>' +
                        '<span>RM ' + baseStoragePrice.toFixed(2) + '</span>' +
                    '</div>';
        }

        // ===== order summary “Subsequent 12 Hours x 3 Excess”  =====
        if (extraStoragePrice > 0 && exceededTimes > 0) {
            html += '<div class="summary-item">' +
                        '<span>Subsequent 12 Hours x ' + exceededTimes + ' Excess</span>' +
                        '<span></span>' +
                    '</div>';

            // order summary total price
            html += '<div class="summary-item">' +
                        '<span>' + quantity + ' Standard Luggage</span>' +
                        '<span>RM ' + extraStoragePrice.toFixed(2) + '</span>' +
                    '</div>';
        }

        // ===== total price calculation =====
        var finalTotal = baseStoragePrice + extraStoragePrice;

        html += '<div class="summary-total">' +
                    '<span>Total</span>' +
                    '<span>RM ' + finalTotal.toFixed(2) + '</span>' +
                '</div>';

    } catch (e) {
        console.error('Order Summary error:', e);
        html = '<div class="summary-item">' +
                   '<span>Order summary not available</span>' +
                   '<span></span>' +
               '</div>';
    }

    container.innerHTML = html;
    });
    </script>


    <script>
        // Payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                document.querySelectorAll('.payment-method').forEach(m => {
                    m.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    </script>

    <script>
    // Order Summary Total 
    function getOrderTotalFromSummary() {
        var totalEl = document.querySelector('.summary-total span:last-child');
        if (!totalEl) {
            alert('Order total not found.');
            throw new Error("Order total not found.");
        }

        // exp： "RM 33.00"
        var text = totalEl.textContent.trim();

        // take off RM，change to decimal
        text = text.replace(/[^\d.]/g, '');
        var val = parseFloat(text);

        if (isNaN(val)) {
            alert('Order total invalid.');
            throw new Error("Order total invalid: " + text);
        }

        return val; 
    }
    </script>

<script>
  // Highlight selected payment method
  document.querySelectorAll('.payment-method').forEach(method => {
    method.addEventListener('click', function () {
      document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
      this.classList.add('active');
    });
  });


  // read stripe public key from .env
  const STRIPE_PUBLISHABLE_KEY = "<?= esc(env('STRIPE_PUBLISHABLE_KEY')) ?>";
  const stripe = Stripe(STRIPE_PUBLISHABLE_KEY);
  const elements = stripe.elements();

  // card Elements
  const cardNumberElement = elements.create('cardNumber', { hidePostalCode: true });
  cardNumberElement.mount('#card-number-element');

  const cardExpiryElement = elements.create('cardExpiry');
  cardExpiryElement.mount('#card-expiry-element');

  const cardCvcElement = elements.create('cardCvc');
  cardCvcElement.mount('#card-cvc-element');

  // error message 
  const errorDiv = document.getElementById('card-errors');
  [cardNumberElement, cardExpiryElement, cardCvcElement].forEach(el => {
    el.on('change', function (event) {
      if (event.error) {
        errorDiv.textContent = event.error.message;
      } else {
        errorDiv.textContent = '';
      }
    });
  });

  
  // Complete Payment
  document.querySelector('.btn-primary').addEventListener('click', async function (e) {
    e.preventDefault();

    const cardName = document.getElementById('cardName').value.trim();
    if (!cardName) {
      alert('Please enter the name on card.');
      return;
    }

    // Order Summary （RM）
    const orderTotalRm = getOrderTotalFromSummary();

    //  Stripe cents
    const amountCents = Math.round(orderTotalRm * 100);

    console.log("Final charge amount:", orderTotalRm, "RM →", amountCents, "cents");

    try {
      //  PaymentIntent
      const intentRes = await fetch("<?= site_url('card-payment/intent') ?>", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          amount: amountCents,
          currency: "myr",
          metadata: {
            card_name: cardName
          }
        })
      });

    if (!intentRes.ok) {
        const text = await intentRes.text();
        console.error('createIntent error', intentRes.status, text);
        alert(
            'Server error when creating payment.\n\n' +
            'Status: ' + intentRes.status + '\n' +
            text.slice(0, 300)   // mention the error if got 
        );
        return;
    }


      const intentData = await intentRes.json();
      const clientSecret = intentData.client_secret;

      // card payment function
      const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
        payment_method: {
          card: cardNumberElement,          
          billing_details: { name: cardName }
        }
      });

      if (error) {
        console.error(error);
        alert(error.message || 'Payment failed.');
        return;
      }

      if (paymentIntent.status !== 'succeeded') {
        alert('Payment status: ' + paymentIntent.status);
        return;
      }

      // Database
      const storeRes = await fetch("<?= site_url('card-payment/store') ?>", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ payment_intent_id: paymentIntent.id })
      });
      const receiptEmail = document.getElementById('customerEmail')?.value || "";
  
    if (!storeRes.ok) {
        const text = await storeRes.text();
        console.error('store error', storeRes.status, text);

    alert(
        'Payment succeeded, but failed to store in database.\n\n' +
        'Status: ' + storeRes.status + '\n' +
        text.slice(0, 400)   // mention the error if got 
    );
    return;
      }

    if (receiptEmail) {
    try {
      const receiptForm = new FormData();
      receiptForm.append('email', receiptEmail);
      receiptForm.append('amount_cents', amountCents);            
      receiptForm.append('currency', 'myr');
      receiptForm.append('status', paymentIntent.status);
      receiptForm.append('payment_intent_id', paymentIntent.id);

    const receiptRes = await fetch("<?= site_url('send-receipt') ?>", {
      method: "POST",
      body: receiptForm
    });

    if (!receiptRes.ok) {
      const t = await receiptRes.text();
      console.error('send-receipt failed', receiptRes.status, t);
    }
  } catch (e) {
    console.error('send-receipt error', e);
  }
}

      alert('Payment processed successfully!');
      // after payment success page
      window.location.href = '<?= base_url('booking_confirmation') ?>';

    } catch (err) {
      console.error(err);
      alert(err.message || "Network error");
    }
  });
</script>

<input type="hidden" id="customerEmail" value="<?= esc($receiptEmail ?? '') ?>">


    <?= $this->include('footer/footer') ?>
</body>

</html>
