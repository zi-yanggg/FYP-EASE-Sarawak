<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EASE SARAWAK | About Us</title>
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
            background-image: url("assets/images/ease-1-2.webp");
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

        /* Offer Section */
        .offer {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 50px;
            padding-bottom: 50px;
        }

        .left {
            flex: 1;
            padding: 20px;
        }

        .left h2 {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .left h3 {
            font-size: 42px;
            margin-bottom: 20px;
        }

        .left p {
            line-height: 1.6;
            font-size: 18px;
        }

        .right {
            flex: 1;
            padding: 20px;
        }

        .offer-image {
            width: 100%;
            height: 100%;
            background-image: url('assets/images/side-view-traveler-with-suitcase-1.webp');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        .btn-wrapper {
            margin-top: 2rem;
        }

        .btn-offer {
            padding: 14px;
            background: #f2be00;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }

        .btn-offer:hover {
            background: black;
        }

        /* Impact Section */
        .impact {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.5)),
                /* black filter */
                url('assets/images/ease-1-1.webp') center/cover no-repeat;
            padding: 60px 20px;
            color: #fff;
        }

        .impact-content {
            padding: 20px;
        }

        .impact-content h2 {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .impact-content h3 {
            font-size: 2.7rem;
            margin-bottom: 10px;
        }

        .impact-content p {
            font-size: 20px;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .impact-flex {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .impact-item {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            position: relative;
        }

        /* Vertical divider */
        .impact-item:not(:last-child) {
            border-right: 2px solid rgba(255, 255, 255, 0.57);
        }

        /* Icon style */
        .impact-icon {
            width: 40px;
            height: 40px;
            object-fit: contain;
            filter: brightness(0) invert(1);
            /* makes icons white-ish on dark bg */
        }

        /* Text style */
        .impact-text h4 {
            font-size: 2rem;
            margin: 0;
            font-weight: bold;
        }

        .impact-text p {
            margin: 0;
            font-size: 1rem;
        }

        .client-experience {
            text-align: center;
            padding: 50px 20px;
        }

        .client-experience-content h2 {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .client-experience-content h3 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .testimonial-carousel {
            position: relative;
            max-width: 1000px;
            margin: 30px auto;
            overflow: hidden;
            user-select: none;
            min-height: 260px;
            /* Changed from fixed height */
            height: auto;
            padding-bottom: 50px;
            /* Space for indicators */
        }

        .testimonial {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
            padding: 0 20px;
            box-sizing: border-box;
            pointer-events: none;
            /* Prevent interaction when hidden */
        }

        .testimonial.active {
            opacity: 1;
            position: relative;
            /* Changed to relative for active */
            pointer-events: auto;
        }

        .testimonial p {
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .testimonial strong {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #444;
        }

        /* Dot indicators */
        .indicators {
            margin-top: 15px;
            text-align: center;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            /* Better centering */
            bottom: 10px;
            width: 100%;
        }

        .indicators span {
            height: 12px;
            width: 12px;
            margin: 0 4px;
            display: inline-block;
            background-color: #bbb;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .indicators span.active {
            background-color: #f2be00;
        }

        /* ──────────────────────────────────────────────────────────────
   MOBILE: Offer section – Text first, then full-width image below
   ────────────────────────────────────────────────────────────── */
        @media (max-width: 768px) {
            .about-title{
                margin-top: 70px;
            }
            .offer {
                flex-direction: column;
                padding: 30px 20px;
                gap: 30px;
            }

            .left {
                order: 1;
                padding: 0;
                text-align: center;
            }

            .right {
                order: 2;
                width: 100%;
                height: 420px;
                /* adjust if you want it taller/shorter */
                margin-top: 10px;
                padding: 0;
            }

            /* This is the crucial part – force the background image on mobile */
            .offer-image {
                background: url('assets/images/side-view-traveler-with-suitcase-1.webp') center/cover no-repeat !important;
                width: 100% !important;
                height: 100% !important;
                border-radius: 12px;
            }

            .btn-wrapper {
                display: flex;
                justify-content: center;
                margin-top: 2rem;
            }

            .btn-offer {
                width: 100%;
                max-width: 320px;
            }
        }

        @media (max-width: 480px) {
            .right {
                height: 320px;
            }
        }

        @media (max-width: 768px) {

            .impact-flex {
                flex-direction: column;
                /* stack items vertically */
                gap: 0;
            }

            .impact-item {
                flex-direction: row;
                /* keep icon + text side by side */
                align-items: center;
                padding: 28px 20px;
                border-right: none !important;
                /* remove old vertical line */
                border-bottom: 2px solid rgba(255, 255, 255, 0.4);
                /* new horizontal divider */
            }

            /* Remove bottom border from the very last item */
            .impact-item:last-child {
                border-bottom: none;
            }
        }

        /* Tablet */
        @media screen and (max-width: 768px) {
            .testimonial-carousel {
                min-height: 300px;
                margin: 20px auto;
            }

            .testimonial {
                padding: 0 15px;
            }

            .testimonial p {
                font-size: 1rem;
                line-height: 1.5;
            }

            .client-experience-content h3 {
                font-size: 1.3rem;
                padding: 0 15px;
            }
        }

        /* Mobile */
        @media screen and (max-width: 480px) {
            .testimonial-carousel {
                min-height: 350px;
                /* More height for longer text */
                margin: 15px auto;
                padding-bottom: 40px;
            }

            .testimonial {
                padding: 0 10px;
            }

            .testimonial p {
                font-size: 0.9rem;
                line-height: 1.5;
                text-align: center;
            }

            .testimonial strong {
                font-size: 0.9rem;
            }

            .indicators span {
                height: 10px;
                width: 10px;
                margin: 0 3px;
            }

            .pill-title {
                font-size: 0.5rem;
                padding: 0 10px;
            }

            .client-experience-content h3 {
                font-size: 2rem;
                padding: 0 10px;
            }
        }

        /* Extra small devices */
        @media screen and (max-width: 360px) {
            .testimonial-carousel {
                min-height: 400px;
            }

            .testimonial p {
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <?= $this->include('navbar/navbar'); ?>

    <!-- Title -->
    <section class="about-title">
        <div class="title-overlay"></div>
        <h1>About Us</h1>
    </section>

    <!-- Offer -->
    <section class="offer">
        <div class="left">
            <h2 class="pill-title">
                <span class="dot"></span>
                WHAT WE OFFER?
                <span class="dot"></span>
            </h2>
            <h3>ABOUT EASE BAGGAGE STORAGE & DELIVERY</h3>
            <p>Founded in 2024, EASE is Kuching’s leading baggage storage and delivery service, offering a simple solution to one of the most common travel hassles: managing your luggage. We’re here to give travelers the freedom to explore the wonderful city of Kuching without the burden of baggage.

                Our mission is extremely clear—we take care of your belongings so you can explore Sarawak and create unforgettable moments. Whether you’re waiting for check-in, navigating an early check-out, or simply want to enjoy the city without the bother of luggage, we provide secure, convenient options for all your needs.

                Our commitment to customer satisfaction is reflected in our easy-to-use platform and friendly customer support. We understand that time is money and we do our best to ensure your process is quick and stress-free. We pride ourselves on offering a service that’s safe, reliable, and affordable. With us, your baggage is always in good hands.

                So let us handle the heavy lifting while you explore Kuching to the fullest.
            </p>
            <div class="btn-wrapper">
                <a href="#book" class="btn-offer">CONTACT US TODAY</a>
            </div>
        </div>
        <div class="right">
            <div class="offer-image"></div>
        </div>
    </section>

    <section class="impact">
        <div class="impact-content">
            <h2 class="pill-title">
                <span class="dot"></span>
                WHY CHOOSE EASE?
                <span class="dot"></span>
            </h2>
            <h3>OUR IMPACT AT EASE</h3>
            <p>
                We have proudly served numerous customers and stored countless pieces of luggage to ensure travelers can explore freely. Our efficient service has covered a significant distance, achieving a remarkable customer satisfaction rate.
            </p>
            <div class="impact-flex">
                <div class="impact-item">
                    <img src="assets/images/customer.png" alt="customer icon">
                    <div class="impact-text">
                        <h4>1,250</h4>
                        <p>Customers Served</p>
                    </div>
                </div>
                <div class="impact-item">
                    <img src="assets/images/luggage.png" alt="luggage icon">
                    <div class="impact-text">
                        <h4>5600</h4>
                        <p>Luggage Stored</p>
                    </div>
                </div>
                <div class="impact-item">
                    <img src="assets/images/distance.png" alt="distance icon">
                    <div class="impact-text">
                        <h4>1,250 km</h4>
                        <p>Km Covered</p>
                    </div>
                </div>
                <div class="impact-item">
                    <img src="assets/images/satisfaction.png" alt="satisfaction icon">
                    <div class="impact-text">
                        <h4>99%</h4>
                        <p>Customer Satisfaction</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="client-experience">
        <div class="client-experience-content">
            <h2 class="pill-title">
                <span class="dot"></span>
                CLIENT EXPERIENCE WITH EASE
                <span class="dot"></span>
            </h2>
            <h3>HEAR FROM OUR HAPPY CUSTOMERS</h3>

            <div class="testimonial-carousel">
                <div class="testimonial active">
                    <p>
                        We just used Ease Storage Sarawak in central Kuching and we highly recommend it.
                        We left our bags there this morning until the afternoon and the staff were super friendly and professional.
                        They even helped with the luggage transfer directly to the airport. 5 star service! Thanks guys!
                    </p>
                    <p><strong>Sara Calhas</strong></p>
                </div>

                <div class="testimonial">
                    <p>
                        We stored our luggage here for 3 days during our stay at Bako National Park. Everything went smooth.
                        They picked up our luggage from the hotel on time and also brought it to our new hotel at the scheduled time.
                        They are reachable via WhatsApp and answer really fast.
                    </p>
                    <p><strong>Rebecca G.</strong></p>
                </div>

                <div class="testimonial">
                    <p>
                        My partner and I needed to leave the luggage in Kuching for 4 nights. I reached out to Ease Storage Sarawak
                        and was provided a rapid response regarding pricing and arrangements. Amelia arranged for our luggage to be
                        collected from our apartment (at no additional charge) and returned to our preferred location. The communication
                        was excellent, and the service was extremely flexible (our plans changed and we needed our luggage earlier).
                        The price was very good as well. Alvin collected our luggage and secured it in front of us. He also gave us
                        some local recommendations and a lift to the town centre! I can't recommend the service enough.
                    </p>
                    <p><strong>Agnieszka Janiszewska</strong></p>
                </div>

                <!-- Dot indicators -->
                <div class="indicators"></div>
            </div>
        </div>
    </section>

    <?= $this->include('footer/footer'); ?>
    <script>
        let currentIndex = 0;
        const testimonials = document.querySelectorAll(".testimonial");
        const total = testimonials.length;
        const indicatorsContainer = document.querySelector(".indicators");

        // Generate dot indicators
        for (let i = 0; i < total; i++) {
            const dot = document.createElement("span");
            if (i === 0) dot.classList.add("active");
            dot.addEventListener("click", () => {
                currentIndex = i;
                showTestimonial(currentIndex);
                resetInterval();
            });
            indicatorsContainer.appendChild(dot);
        }
        const indicators = document.querySelectorAll(".indicators span");

        function showTestimonial(index) {
            testimonials.forEach((t, i) => {
                t.classList.remove("active");
                indicators[i].classList.remove("active");
                if (i === index) {
                    t.classList.add("active");
                    indicators[i].classList.add("active");
                }
            });
        }

        // Auto slide
        let autoSlide = setInterval(nextSlide, 6000);

        function nextSlide() {
            currentIndex = (currentIndex === total - 1) ? 0 : currentIndex + 1;
            showTestimonial(currentIndex);
        }

        function prevSlide() {
            currentIndex = (currentIndex === 0) ? total - 1 : currentIndex - 1;
            showTestimonial(currentIndex);
        }

        function resetInterval() {
            clearInterval(autoSlide);
            autoSlide = setInterval(nextSlide, 6000);
        }

        // Swipe detection (mobile)
        let startX = 0;
        let endX = 0;
        const carousel = document.querySelector(".testimonial-carousel");

        carousel.addEventListener("touchstart", (e) => {
            startX = e.touches[0].clientX;
        });

        carousel.addEventListener("touchend", (e) => {
            endX = e.changedTouches[0].clientX;
            handleSwipe(startX, endX);
        });

        // Mouse drag detection (desktop)
        let isDragging = false;
        let dragStartX = 0;
        let dragEndX = 0;

        carousel.addEventListener("mousedown", (e) => {
            isDragging = true;
            dragStartX = e.clientX;
        });

        carousel.addEventListener("mouseup", (e) => {
            if (!isDragging) return;
            isDragging = false;
            dragEndX = e.clientX;
            handleSwipe(dragStartX, dragEndX);
        });

        carousel.addEventListener("mouseleave", () => {
            isDragging = false; // cancel if user drags outside
        });

        function handleSwipe(start, end) {
            if (start - end > 50) {
                nextSlide(); // swipe/drag left → next
                resetInterval();
            } else if (end - start > 50) {
                prevSlide(); // swipe/drag right → prev
                resetInterval();
            }
        }
    </script>
</body>

</html>