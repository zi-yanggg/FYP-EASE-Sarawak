<?php
helper('translation');

$easeLang = normalize_site_locale(session('site_lang') ?? ($_COOKIE['site_lang'] ?? 'en'));
$easeCatalog = ease_translation_catalog();
?>
<!DOCTYPE html>
<html lang="<?= esc($easeLang) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EASE SARAWAK | Payment</title>
    <link rel="icon" type="image/png" href="assets/images/cropped-Ease_PNG_File-09.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/footer_style.css">
    <link rel="stylesheet" href="assets/css/payment_style.css">
    <link rel="stylesheet" href="assets/css/navbar_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <script src="https://js.stripe.com/v3/"></script>

    <style>
      .booking-progress {
        margin: 1rem auto 1.5rem;
        width: 100%;
        max-width: 1200px;
        box-sizing: border-box;
      }

      .booking-progress-service {
        font-size: 1.15rem;
        font-weight: 800;
        color: #222;
        letter-spacing: 0.04em;
        margin-bottom: 0.8rem;
        text-align: center;
      }

      .booking-progress-steps {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        gap: 0;
      }

      .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.35rem;
        color: #777;
        font-size: 0.95rem;
        white-space: normal;
        text-align: center;
        min-width: 96px;
      }

      .progress-step-number {
        width: 2.2rem;
        height: 2.2rem;
        border: 2px solid #000;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        font-weight: 800;
        color: #fff;
        background: #000;
      }

      .progress-step.active {
        color: #111;
        font-weight: bold;
      }

      .progress-step.active .progress-step-number {
        border-color: #f2be00;
        background: #f2be00;
        color: #111;
      }

      .progress-divider {
        width: 252px;
        height: 4px;
        background: #f2be00;
        margin-top: 0.9rem;
        margin-left: -30px;
        margin-right: -30px;
        position: relative;
        z-index: 0;
      }
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

      @media (max-width: 768px) {
        .booking-progress {
          margin-bottom: 1rem;
        }

        .booking-progress-steps {
          flex-wrap: wrap;
          row-gap: 0.6rem;
        }

        .progress-divider {
          display: none;
        }
      }
    </style>
</head>

<body>
<?= $this->include('navbar/navbar') ?>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-content">
            <h1>TRAVEL SMART WITH EASE</h1>
            <p>Whether you need secure storage or prompt delivery, we provide reliable and convenient solutions to ensure your journey is as smooth as possible.</p>
        </div>
    </section>

    <div class="booking-progress" aria-label="Booking progress">
      <div class="booking-progress-service" id="bookingProgressService">LUGGAGE STORAGE</div>
      <div class="booking-progress-steps">
        <div class="progress-step">
          <span class="progress-step-number">1</span>
          <span>Booking Details</span>
        </div>
        <span class="progress-divider"></span>
        <div class="progress-step">
          <span class="progress-step-number">2</span>
          <span>Information</span>
        </div>
        <span class="progress-divider"></span>
        <div class="progress-step active">
          <span class="progress-step-number">3</span>
          <span>Payment</span>
        </div>
      </div>
    </div>

    <script>
    (function() {
      function updateProgressServiceLabel() {
        var serviceEl = document.getElementById('bookingProgressService');
        if (!serviceEl) return;

        var label = 'LUGGAGE STORAGE';

        try {
          var bookingData = JSON.parse(sessionStorage.getItem('bookingData') || '{}');
          if (bookingData && bookingData.service === 'delivery') {
            label = 'IN-TOWN DELIVERY';
          }
        } catch (error) {
          label = 'LUGGAGE STORAGE';
        }

        serviceEl.textContent = label;
      }

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updateProgressServiceLabel);
      } else {
        updateProgressServiceLabel();
      }
    })();
    </script>


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

    <!-- Translate script -->
    <script>
    const EASE_LANG = <?= json_encode($easeLang, JSON_UNESCAPED_UNICODE) ?>;
    const EASE_TRANSLATIONS = <?= json_encode($easeCatalog, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

    function t(key) {
        return (EASE_TRANSLATIONS[EASE_LANG] && EASE_TRANSLATIONS[EASE_LANG][key]) || key;
    }

    function bookingLocale() {
        if (EASE_LANG === 'zh') return 'zh-CN';
        if (EASE_LANG === 'ms') return 'ms-MY';
        return 'en-US';
    }
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
    var container = document.getElementById('order-summary-content');
    const DELIVERY_EXTRA_RATE = <?= json_encode($deliveryExtraRate ?? 6) ?>;
    const STORAGE_EXTRA_RATE = <?= json_encode($storageExtraRate ?? 6) ?>;
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

        var insuranceSelected = bookingData.insuranceSelected === true
          || bookingData.insuranceSelected === 'true'
          || bookingData.insuranceSelected === 1
          || bookingData.insuranceSelected === '1'
          || bookingData.insuranceSelected === 'on';
        var promoDiscount = Number(bookingData.promoDiscount || 0);
        var promoType = bookingData.promoType || 'amount';
        var appliedPromoCode = bookingData.promoCode || '';

        // baseprice + exceedtimes =====
        var quantity  = Number(bookingData.quantity || 1);
        var basePrice = Number(bookingData.basePrice || 0);
        var insuranceCharge = insuranceSelected ? (3 * quantity) : 0;

        html += '<div class="summary-item">' +
                    '<span>' + serviceLabel + '</span>' +
                    '<span></span>' +
                '</div>';

        if (insuranceSelected) {
          html += '<div class="booking-summary-section">' +
                      '<div class="booking-summary-label">Insurance</div>' +
                      '<div class="booking-summary-value">RM ' + insuranceCharge.toFixed(2) + '</div>' +
                  '</div>';
        }

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
                var storedExtraRate = Number(bookingData.extraRate || 0);
                var defaultExtraRate = (bookingData.service === 'storage')
                ? Number(STORAGE_EXTRA_RATE)
                : Number(DELIVERY_EXTRA_RATE);
                var extraRate = storedExtraRate > 0 ? storedExtraRate : defaultExtraRate;

                exceededTimes = Math.max(0, Math.ceil((diffHours - baseHours) / 12));
                extraStoragePrice = Math.max(0, exceededTimes * extraRate * quantity);
            }
        }

        // =====order summary base storage price =====
          if (baseStoragePrice > 0) {
            html += '<div class="booking-summary-section">' +
                  '<div class="booking-summary-label">' + quantity + '' + t('Standard Luggage') + '</div>' +
                  '<div class="booking-summary-value">RM ' + baseStoragePrice.toFixed(2) + '</div>' +
                '</div>';
        }

        // ===== order summary “Subsequent 12 Hours x 3 Excess”  =====
        if (extraStoragePrice > 0 && exceededTimes > 0) {
            html += '<div class="booking-summary-section">' +
                  '<div class="booking-summary-label">' + t('Subsequent 12 Hours x') + exceededTimes + ' ' + t('Excess') + ' (RM ' + extraRate + '/12h)</div>' +
                  '<div class="booking-summary-value"></div>' +
                '</div>';

            // order summary total price
            html += '<div class="booking-summary-section">' +
                  '<div class="booking-summary-label">' + quantity + '' + t('Standard Luggage') + '</div>' +
                  '<div class="booking-summary-value">RM ' + extraStoragePrice.toFixed(2) + '</div>' +
                '</div>';
        }

              var discountAmount = 0;
              if (appliedPromoCode && promoDiscount > 0) {
                if (promoType === 'amount') {
                  discountAmount = promoDiscount;
                } else {
                  discountAmount = (baseStoragePrice + extraStoragePrice + insuranceCharge) * promoDiscount / 100;
                }

                html += '<div class="booking-summary-section">' +
                      '<div class="booking-summary-label">' + t('Discount') + ' (' + appliedPromoCode + ')</div>' +
                      '<div class="booking-summary-total">-RM ' + discountAmount.toFixed(2) + '</div>' +
                    '</div>';
              }

        // ===== total price calculation =====
        var finalTotal = (bookingData.totalPrice !== undefined)
            ? Number(bookingData.totalPrice)
                : (baseStoragePrice + extraStoragePrice + insuranceCharge - discountAmount);

          html += '<div class="booking-summary-section">' +
                '<div class="booking-summary-label">' + t('Total') + '</div>' +
                '<div class="booking-summary-total">RM ' + finalTotal.toFixed(2) + '</div>' +
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
      var totalEl = document.querySelector('.booking-summary-total');
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
function getStripeLocale() {
    if (EASE_LANG === 'zh') return 'zh';
    if (EASE_LANG === 'ms') return 'ms';
    return 'en';
}

const STRIPE_PUBLISHABLE_KEY = "<?= esc(env('STRIPE_PUBLISHABLE_KEY')) ?>";

const stripe = Stripe(STRIPE_PUBLISHABLE_KEY, {
    locale: getStripeLocale()
});

const elements = stripe.elements({
    locale: getStripeLocale()
});

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

    function getBookingData() {
    try {
        return JSON.parse(sessionStorage.getItem('bookingData') || '{}');
    } catch (e) {
        console.error('Cannot read bookingData:', e);
        return {};
    }
    }

    function getReceiptEmail() {
    const hiddenEmail = (document.getElementById('customerEmail')?.value || '').trim();

    if (hiddenEmail) {
        return hiddenEmail;
    }

    const bookingData = getBookingData();
    return (bookingData.email || bookingData.customerEmail || '').trim();
    }

    function getOrderId() {
    const bookingData = getBookingData();
    return bookingData.order_id || bookingData.orderId || '';
    }

  // Complete Payment
  document.querySelector('.btn-primary').addEventListener('click', async function (e) {
    e.preventDefault();

    const cardName = document.getElementById('cardName').value.trim();
    if (!cardName) {
      alert('Please enter the name on card.');
      return;
    }

    const receiptEmail = getReceiptEmail();
    const orderId = getOrderId();

    // Order Summary （RM）
    if (!orderId) {
      alert('Order ID is missing. Please complete booking again.');
      return;
    }

    const orderTotalRm = getOrderTotalFromSummary();

    try {
      // Server computes authoritative amount from order_id
      const intentRes = await fetch("<?= site_url('card-payment/intent') ?>", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
        order_id: parseInt(orderId, 10),
        receipt_email: receiptEmail,
        metadata: {
            card_name: cardName,
            customer_email: receiptEmail,
            order_id: String(orderId)
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
      const amountCents = intentData.amount_cents || Math.round(orderTotalRm * 100);

      // card payment function
      const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
        payment_method: {
          card: cardNumberElement,          
          billing_details: { 
            name: cardName,
            email: receiptEmail || undefined
        }
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
      receiptForm.append('order_id', orderId);
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
