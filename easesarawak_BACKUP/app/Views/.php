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

        // Form validation
        document.querySelector('.btn-primary').addEventListener('click', function(e) {
            e.preventDefault();
            
            const cardName = document.getElementById('cardName').value;
            const cardNumber = document.getElementById('cardNumber').value;
            const expiryDate = document.getElementById('expiryDate').value;
            const cvv = document.getElementById('cvv').value;
            
            if (!cardName || !cardNumber || !expiryDate || !cvv) {
                alert('Please fill in all payment details');
                return;
            }
            
            // In a real application, you would process the payment here
            alert('Payment processed successfully!');
            
            // Redirect to next step
            window.location.href = 'bookingcustomerdetail';
        });
    </script>