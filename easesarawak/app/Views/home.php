<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EASE SARAWAK | Kuching Luggage Storage & Delivery</title>
    <link rel="icon" type="image/png" href="assets/images/cropped-Ease_PNG_File-09.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/footer_style.css">
    <link rel="stylesheet" href="assets/css/navbar_style.css">

    <style>
        @font-face {
            font-family: 'BebasKai';
            src: url('assets/BebasKai.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

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

        body,
        html {
            font-family: 'EurostarRegular', sans-serif, Arial, 'BebasKai';
            line-height: 1.6;
            width: 100%;
            overflow-x: hidden;
        }

        /* Hero section */


        .btn-primary {
            background: #f2be00;
            color: #fff;
        }

        .btn-primary:hover {
            background: #000;
        }

        /* Zoom & Crossfade Animation */
        @keyframes zoom {
            0% {
                opacity: 0;
                transform: scale(1);
            }

            5% {
                opacity: 1;
            }

            45% {
                opacity: 1;
                transform: scale(1.1);
                /* slow zoom-in */
            }

            50% {
                opacity: 0;
                transform: scale(1.15);
            }

            100% {
                opacity: 0;
                transform: scale(1);
            }
        }


        /* INTRODUCING EASE Section */
        .intro {
            display: flex;
            flex-wrap: wrap;
            min-height: 80vh;
        }

        .intro-left,
        .intro-right {
            flex: 1;
            padding: 3rem;
        }

        .intro-left {
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .intro-left h2 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .intro-left h3 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .intro-left p {
            margin-bottom: 1rem;
            color: #333;
            font-size: 1.1rem;
        }

        .intro-right {
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .intro-right video {
            width: 100%;
            height: 100%;
            min-height: 300px;
            object-fit: contain;
            border: none;
            border-radius: 8px;
        }

        /* OUR SERVICES Section */
        .services {
            padding: 4rem 2rem;
            background: url('assets/images/service-v1-pattern.jpg') no-repeat center center/cover;
            text-align: center;
        }

        .services-header h2 {
            font-size: 1rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .services-header h3 {
            font-size: 3rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .services-header p {
            max-width: 700px;
            margin: 0 auto 3rem;
            color: #555;
            font-size: 1.1rem;
        }

        /* Cards container */
        .services-cards {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        /* Single card */
        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 300px;
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
            align-items: start;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-image {
            background: #f2be00;
            border-radius: 10px;
            width: 70px;
            padding: 5px 5px 0px 5px;
        }

        .card-image img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        .card h4 {
            font-size: 1.9rem;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .card .price {
            font-weight: bold;
            color: #f2be00;
            margin-bottom: 1rem;
        }

        .card .desc {
            flex-grow: 1;
            font-size: 0.95rem;
            color: #555;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .btn-card {
            background: #f2be00;
            color: #fff;
            text-decoration: none;
            padding: 0.7rem 1.5rem;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn-card:hover {
            background: black;
        }

        /* HOW IT WORKS Section */
        .how {
            padding: 4rem 2rem;
            background: #fff;
            text-align: center;
        }

        .how-header h2 {
            font-size: 1rem;
            color: #333;
            margin-bottom: 0.5rem;
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

        .how-header h3 {
            font-size: 3rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .how-header p {
            max-width: 700px;
            margin: 0 auto 3rem;
            color: #555;
            font-size: 1.1rem;
        }

        /* Cards container */
        .how-cards {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        /* Single step card */
        .step-card {
            background: url('assets/images/bg-003-6.png') no-repeat center center/cover;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            width: 250px;
            padding: 1rem;
            transition: transform 0.3s ease;
        }

        .step-card:hover {
            transform: translateY(-5px);
        }

        .step-card h4 {
            font-size: 2.4rem;
            color: #f2be00;
            margin-bottom: 0.5rem;
            font-weight: bold;
            text-align: right;
        }

        .step-card h5 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
            text-align: left;
        }

        .step-card p {
            font-size: 1.1rem;
            color: #555;
            text-align: left;
        }

        /* Footer button */
        .how-footer {
            margin-top: 2rem;
        }

        .btn-how {
            background: #f2be00;
            color: #fff;
            text-decoration: none;
            padding: 0.8rem 2rem;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn-how:hover {
            background: black;
        }

        /* WHY CHOOSE EASE Section */
        .why-choose-ease {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.5)),
                /* black filter */
                url('assets/images/valet-holding-baggage-side-view_23-2149901449-1 (1).webp') center/cover no-repeat;
            padding: 60px 20px;
            color: #fff;
        }

        .why-choose-ease .content {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
        }

        .left {
            flex: 1;
            padding: 20px;
        }

        .left h2 {
            font-size: 15px;
            margin-bottom: 10px;
        }

        .left h3 {
            font-size: 40px;
            margin-bottom: 20px;
        }

        .left p {
            line-height: 1.6;
            font-size: 20px;
        }

        /* RIGHT SIDE SPLIT INTO 4 QUADRANTS */
        .right {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 20px;
            /* spacing between quadrants */
            position: relative;
            padding: 20px;
        }

        /* Draw cross lines */
        .right::before,
        .right::after {
            content: "";
            position: absolute;
            background: rgba(255, 255, 255, 1);
        }

        .right::before {
            top: 50%;
            left: 0;
            width: 100%;
            height: 2px;
            /* horizontal line */
            transform: translateY(-50%);
        }

        .right::after {
            left: 50%;
            top: 0;
            height: 100%;
            width: 2px;
            /* vertical line */
            transform: translateX(-50%);
        }

        /* Each quadrant */
        .quadrant {
            /* background: rgba(0, 0, 0, 0.5); */
            /* semi-transparent bg for readability */
            padding: 15px;
            border-radius: 8px;
        }

        .quadrant h4 {
            margin-bottom: 10px;
            font-size: 23px;
        }

        .quadrant p {
            font-size: 18px;
            line-height: 1.4;
        }

        /* CONNECT WITH US Section */
        .connect-with-us {
            background: #f7f7f7;
            padding: 60px 20px;
        }

        .connect-with-us .content {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            gap: 40px;
        }

        .connect-with-us .connect-left {
            flex: 1;
            padding: 20px;
        }

        .connect-with-us .connect-left h2 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #333;
        }

        .connect-with-us .connect-left h3 {
            font-size: 40px;
            margin-bottom: 20px;
            color: #333;
        }

        .connect-with-us .connect-left p {
            font-size: 18px;
            margin-bottom: 15px;
            line-height: 1.6;
            color: #444;
        }

        .contact-info p {
            margin-bottom: 16px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .contact-item:hover .icon-circle {
            background-color: #f2be00;
            /* yellow fill */
            color: #000;
            /* make icon black */
            border-color: #f2be00;
            /* keep border consistent */
            transition: all 0.3s ease;
        }

        .icon-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border: 2px solid #f2be00;
            /* yellow border */
            border-radius: 50%;
            margin-right: 15px;
            color: #f2be00;
            font-size: 22px;
        }

        .contact-text .label {
            font-weight: 600;
            color: #000;
            font-size: 18px;
            margin-bottom: 2px;
            /* reduce space between heading and value */
        }

        .contact-text .value {
            font-size: 16px;
            color: #333;
        }

        .connect-with-us .connect-right {
            flex: 1;
            padding: 20px;
            background: black;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .connect-with-us .connect-right h2 {
            margin-top: 20px;
            font-size: 14px;
            margin-bottom: 10px;
            color: #333;
        }

        .connect-with-us .connect-right .tagline {
            font-size: 40px;
            color: #fff;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .message-desc {
            font-size: 20px;
            color: #fff;
            margin-bottom: 25px;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .row-inputs {
            display: flex;
            gap: 15px;
        }

        .row-inputs input {
            flex: 1;
        }

        .contact-form input,
        .contact-form textarea {
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
        }

        .contact-form input:focus,
        .contact-form textarea:focus {
            border-color: #0077cc;
        }

        .contact-form input::-webkit-input-placeholder,
        .contact-form textarea::-webkit-input-placeholder {
            font-family: 'EurostarRegular', sans-serif, Arial, 'BebasKai';
        }

        .contact-form input::-moz-placeholder,
        .contact-form textarea::-moz-placeholder {
            font-family: 'EurostarRegular', sans-serif, Arial, 'BebasKai';
        }

        .contact-form input:-ms-input-placeholder,
        .contact-form textarea:-ms-input-placeholder {
            font-family: 'EurostarRegular', sans-serif, Arial, 'BebasKai';
        }

        .contact-form input::placeholder,
        .contact-form textarea::placeholder {
            font-family: 'EurostarRegular', sans-serif, Arial, 'BebasKai';
        }

        .contact-form button {
            padding: 14px;
            background: #f2be00;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .contact-form button:hover {
            background: black;
        }

        /* CALL TO ACTION Section */
        .cta-section {
            background: #dbd9d9ff;
            /* light grey background */
            padding: 50px 20px;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 40px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .cta-section p {
            font-size: 22px;
            color: #f2be00;
            margin-bottom: 30px;
        }

        .cta-button {
            display: inline-block;
            padding: 14px 28px;
            background: #f2be00;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .cta-button:hover {
            background: black;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-content h2 {
                font-size: 1.2rem;
            }

            .intro {
                flex-direction: column;
            }

            .intro-left,
            .intro-right {
                padding: 2rem;
            }

            .intro-right iframe {
                min-height: 250px;
            }

            .services-cards {
                flex-direction: column;
                align-items: center;
            }

            .how-cards {
                flex-direction: column;
                align-items: center;
            }

            .connect-with-us .content {
                flex-direction: column;
            }
        }

        .hero {
            position: relative;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            overflow: hidden;
        }

        /* First background image (visible first) */
        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("assets/images/close-up-tourist-with-suitcase_11zon.webp") center/cover no-repeat;
            animation: fadeSlide 20s infinite;
            z-index: -2;
        }

        /* Second background image (appears after 10s) */
        .hero::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("assets/images/close-up-traveler-with-luggage_11zon.webp") center/cover no-repeat;
            animation: fadeSlide 20s infinite;
            animation-delay: 10s;
            z-index: -2;
        }

        /* Dark overlay */
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: -1;
        }

        /* Content */
        .hero-content {
            margin-top: 100px;
            max-width: 900px;
            padding: 1rem;
            z-index: 1;
        }

        .hero .pill {
            display: inline-block;
            background: #fff;
            color: #000;
            backdrop-filter: blur(10px);
            padding: 12px 32px;
            border-radius: 50px;
            font-size: 1.05rem;
            font-weight: 600;
            letter-spacing: 1.2px;
            margin-bottom: 20px;
        }

        .pill .dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #f2be00;
            /* yellow color */
            display: inline-block;
        }

        .hero h1 {
            font-size: 5rem;
            font-weight: 900;
            line-height: 1.1;
            margin: 20px 0;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.7);
        }

        .hero p {
            font-size: 1.32rem;
            max-width: 720px;
            margin: 0 auto 45px;
            line-height: 1.7;
            opacity: 0.95;
        }

        .hero-buttons {
            display: flex;
            gap: 22px;
            justify-content: center;
            flex-wrap: nowrap;
            /* ← keeps buttons side-by-side */
        }

        .btn-yellow {
            background: #f2be00;
            color: #fff;
            font-weight: bold;
            font-size: 1.12rem;
            padding: 15px 34px;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            min-width: 170px;
        }

        .btn-yellow:hover {
            background: black;
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }

        /* Cross-fade + subtle zoom animation */
        @keyframes fadeSlide {
            0% {
                opacity: 0;
                transform: scale(1);
            }

            5% {
                opacity: 1;
                transform: scale(1);
            }

            45% {
                opacity: 1;
                transform: scale(1.1);
            }

            50% {
                opacity: 0;
                transform: scale(1.12);
            }

            100% {
                opacity: 0;
            }
        }

        /* Mobile — exactly like capture3.png */
        @media (max-width: 768px) {
            .hero {
                padding-top: 90px;
                /* space for fixed navbar */
                align-items: flex-start;
            }

            .hero-content {
                margin-top: 8vh;
                padding: 15px;
            }

            .hero h1 {
                font-size: 3.3rem;
                line-height: 1.15;
            }

            .hero p {
                font-size: 1.15rem;
                margin-bottom: 35px;
            }

            .hero-buttons {
                gap: 16px;
            }

            .btn-yellow {
                padding: 13px 28px;
                font-size: 1rem;
                min-width: 150px;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 2.9rem;
            }

            .btn-yellow {
                padding: 12px 24px;
            }
        }

        /* ==================== WHY CHOOSE EASE – MOBILE LIKE CAPTURE.PNG ==================== */
        @media (max-width: 768px) {
            .why-choose-ease {
                padding: 4rem 1rem 5rem;
                background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.65)),
                    url('assets/images/valet-holding-baggage-side-view_23-2149901449-1 (1).webp') center/cover no-repeat;
            }

            .why-choose-ease .content {
                flex-direction: column;
                max-width: 100%;
            }

            /* Left side – text */
            .left {
                text-align: center;
                margin-bottom: 3rem;
                padding: 0 1rem;
            }

            .left h2.pill-title {
                justify-content: center;
                font-size: 1rem;
            }

            .left h3 {
                font-size: 2.3rem !important;
                margin: 1rem 0;
            }

            .left p {
                font-size: 1.15rem;
                line-height: 1.7;
                max-width: 100%;
            }

            /* Right side – 4 reasons stacked vertically */
            .right {
                display: flex;
                flex-direction: column;
                gap: 1.8rem;
                padding: 0 1rem;
            }

            /* Hide the cross lines on mobile */
            .right::before,
            .right::after {
                display: none;
            }

            .quadrant {
                background: rgba(255, 255, 255, 0.08);
                backdrop-filter: blur(8px);
                padding: 1.8rem 1.5rem;
                border-radius: 16px;
                border-left: 5px solid #f2be00;
                text-align: left;
            }

            .quadrant h4 {
                color: #f2be00;
                font-size: 1.55rem;
                font-weight: bold;
                margin-bottom: 0.8rem;
            }

            .quadrant p {
                font-size: 1.05rem;
                line-height: 1.6;
                color: #eee;
            }
        }

        /* Extra small phones */
        @media (max-width: 480px) {
            .left h3 {
                font-size: 2rem;
            }

            .quadrant {
                padding: 1.5rem 1.3rem;
            }

            .quadrant h4 {
                font-size: 1.45rem;
            }
        }

        /* Fix squeezed geo icon on mobile */
        @media (max-width: 480px) {
            .contact-item {
                display: flex;
                align-items: center;
                gap: 15px;
                /* space between icon and text */
                margin-bottom: 15px;
            }

            .icon-circle {
                flex-shrink: 0;
                /* ← THIS IS THE KEY LINE */
                width: 50px;
                height: 50px;
                min-width: 50px;
                /* prevents shrinking */
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .contact-text {
                flex: 1;
                min-width: 0;
                /* allows text to wrap properly */
            }

            .contact-text .label {
                font-size: 1.05rem;
            }

            .contact-text .value {
                font-size: 0.98rem;
                word-break: break-word;
                /* prevents overflow on long address */
            }
        }

        .connect-right .tagline,
        .connect-right .message-desc {
            text-align: center;
            margin-bottom: 1rem;
        }

        .connect-right .contact-form {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            max-width: 420px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .connect-right .contact-form .row-inputs {
            display: flex;
            flex-direction: column;
            /* stack on mobile */
            gap: 1.2rem;
        }

        .connect-right .contact-form input,
        .connect-right .contact-form textarea {
            width: 100%;
            padding: 1.1rem 1.4rem;
            border: none;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            color: white;
            font-size: 1.05rem;
            outline: none;
            transition: all 0.3s;
        }

        .connect-right .contact-form input::placeholder,
        .connect-right .contact-form textarea::placeholder {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
        }

        /* Focus glow */
        .connect-right .contact-form input:focus,
        .connect-right .contact-form textarea:focus {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 3px rgba(242, 190, 0, 0.4);
        }

        .connect-right .contact-form textarea {
            min-height: 130px;
            resize: vertical;
            font-family: inherit;
        }

        .connect-right .contact-form button {
            background: #f2be00;
            color: #fff;
            font-weight: bold;
            font-size: 1.15rem;
            padding: 1.1rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 0.5rem;
        }

        .connect-right .contact-form button:hover {
            background: #826704ff;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        /* Mobile – exactly like capture1.png */
        @media (max-width: 768px) {
            .connect-right {
                padding: 2.5rem 1rem;
            }

            .connect-right .contact-form {
                gap: 1.4rem;
                padding: 0;
            }

            .connect-right .contact-form input,
            .connect-right .contact-form textarea {
                font-size: 1.05rem;
                /* prevents zoom on iOS */
                padding: 1.3rem 1.6rem;
            }

            .connect-right .contact-form input::placeholder,
            .connect-right .contact-form textarea::placeholder {
                font-size: 1.02rem;
            }
        }
        @media (max-width: 768px) {
            .cta-section h2 {
                font-size: 2rem;
            }

            .cta-section p {
                font-size: 1rem;
            }

            .cta-button {
                padding: 6px 12px;
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <?= $this->include('navbar/navbar') ?>
    <!-- Hero -->
    <section class="hero">
        <!-- Two background images with cross-fade -->
        <div class="hero-overlay"></div>

        <div class="hero-content">
            <div class="pill">
                <span class="dot"></span>
                EASE BAGGAGE SOLUTIONS
                <span class="dot"></span>
            </div>

            <h1>KUCHING<br>HANDS-FREE TRAVEL</h1>

            <p>
                Discover the best of Kuching – We ensure you a smooth and hassle-free journey with
                our easy-to-use Kuching Luggage Storage and Delivery service.
            </p>

            <div class="hero-buttons">
                <a href="#contact" class="btn-yellow">CONTACT NOW</a>
                <a href="<?= base_url('/booking') ?>" class="btn-yellow">BOOK NOW</a>
            </div>
        </div>
    </section>

    <!-- INTRODUCING EASE Section -->
    <section class="intro">
        <div class="intro-left">
            <h2 class="pill-title">
                <span class="dot"></span>
                INTRODUCING EASE
                <span class="dot"></span>
            </h2>
            <h3>STREAMLINING YOUR TRAVEL</h3>
            <p>
                Every moment in Kuching is an opportunity for discovery. With EASE, you're free to seize each one.
                Our seamless luggage storage and delivery services let you explore without limits—no bags to hold you back, no burdens to slow you down.
            </p>
            <p>
                Imagine wandering through vibrant markets, indulging in local cuisine, or uncovering hidden gems, all with your hands free and your mind at ease.
                We handle your luggage, so you can immerse yourself fully in the beauty and culture of this incredible city.
            </p>
        </div>
        <div class="intro-right">
            <video width="100%" height="100%" autoplay muted loop playsinline controls>
                <source src="assets/images/EASE-v2-Sub-Ease.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </section>

    <!-- OUR SERVICES Section -->
    <section class="services" id="services">
        <div class="services-header">
            <h2 class="pill-title">
                <span class="dot"></span>
                OUR SERVICES
                <span class="dot"></span>
            </h2>
            <h3>TRAVEL LIGHT WITH EASE</h3>
            <p>
                Whether you need secure storage or prompt delivery, we provide reliable and convenient
                solutions to ensure your journey is as smooth as possible.
            </p>
        </div>

        <div class="services-cards">
            <!-- Card 1 - Basic (Luggage Storage) -->
            <div class="card">
                <div class="card-image">
                    <img src="assets/images/case-1.png" alt="Basic Service">
                </div>
                <h4>Basic</h4>
                <p class="price">Starts from <strong>RM<?= esc($prices['storage']) ?></strong></p>
                <p class="desc">
                    Looking for short-term storage? Our Kuching Luggage Storage service keeps your luggage safe
                    for as long as needed while you explore the city worry-free!
                </p>
                <a href="#" onclick="bookStorage()" class="btn-card">BOOK NOW</a>
            </div>

            <!-- Card 2 - Standard (In Town Delivery) -->
            <div class="card">
                <div class="card-image">
                    <img src="assets/images/baggage.png" alt="Standard Service">
                </div>
                <h4>Standard</h4>
                <p class="price">Starts from <strong>RM<?= esc($prices['delivery']) ?></strong></p>
                <p class="desc">
                    Enjoy our complimentary Kuching Luggage Transfer with 24 hours of secure storage, offering
                    seamless transfers between selected locations for added convenience!
                </p>
                <a href="#" onclick="bookDelivery()" class="btn-card">BOOK NOW</a>
            </div>

            <!-- Card 3 - On-demand -->
            <div class="card">
                <div class="card-image">
                    <img src="assets/images/suitcase .png" alt="On-demand Service">
                </div>
                <h4>On-demand</h4>
                <p class="price">Starts from <strong>RM30</strong></p>
                <p class="desc">
                    Carrying oversized luggage or need a specific pickup/drop-off location? Our Kuching Luggage
                    Delivery service got you covered—flexible and hassle-free!
                </p>
                <a href="#" onclick="bookDelivery()" class="btn-card">BOOK NOW</a>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS Section -->
    <section class="how" id="how">
        <div class="how-header">
            <h2 class="pill-title">
                <span class="dot"></span>
                HOW IT WORKS
                <span class="dot"></span>
            </h2>
            <h3>EASE TRAVEL PROCESS</h3>
            <p>
                Our process is designed to take the stress out of your travel experience:
            </p>
        </div>

        <div class="how-cards">
            <!-- Step 1 -->
            <div class="step-card">
                <h4>01</h4>
                <h5>Book Online</h5>
                <p>Reserve the luggage services you need in Kuching with just a few clicks.</p>
            </div>

            <!-- Step 2 -->
            <div class="step-card">
                <h4>02</h4>
                <h5>Get Confirmation</h5>
                <p>Receive an instant confirmation with all the details you need.</p>
            </div>

            <!-- Step 3 -->
            <div class="step-card">
                <h4>03</h4>
                <h5>Drop Off</h5>
                <p>Store your luggage at our location or schedule a pick-up whenever it suits you.</p>
            </div>

            <!-- Step 4 -->
            <div class="step-card">
                <h4>04</h4>
                <h5>Enjoy Your Trip</h5>
                <p>Explore Kuching without the extra weight.</p>
            </div>
        </div>

        <div class="how-footer">
            <a href="booking" class="btn-how">BOOK NOW</a>
        </div>
    </section>

    <section class="why-choose-ease" id="why-choose-ease">
        <div class="content">
            <!-- LEFT -->
            <div class="left">
                <h2 class="pill-title">
                    <span class="dot"></span>
                    WHY CHOOSE EASE?
                    <span class="dot"></span>
                </h2>
                <h3>YOUR TRAVEL, OUR COMMITMENT</h3>
                <p>
                    We understand that carrying your luggage through the city can be one of the biggest hassles
                    when traveling. Let us lift that burden off your shoulders, making your travel in Kuching
                    relaxing and enjoyable from start to end.
                </p>
            </div>

            <!-- RIGHT -->
            <div class="right">
                <div class="quadrant top-left">
                    <h4>Easy to Use</h4>
                    <p>Our user-friendly website and booking process allow you to arrange the service you need with just a few clicks.</p>
                </div>
                <div class="quadrant top-right">
                    <h4>Safe Assured</h4>
                    <p>Travel with peace of mind, knowing your luggage is protected by our top-notch security measures.</p>
                </div>
                <div class="quadrant bottom-left">
                    <h4>Optimal Flexibility</h4>
                    <p>Choose when and where to store or retrieve your luggage, giving you ultimate freedom and convenience.</p>
                </div>
                <div class="quadrant bottom-right">
                    <h4>Fast & Reliable</h4>
                    <p>Experience quick check-in for storage and on-time, prompt delivery.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="connect-with-us" id="contact">
        <div class="content">
            <!-- LEFT SIDE -->
            <div class="connect-left">
                <h2 class="pill-title">
                    <span class="dot"></span>
                    CONNECT WITH US
                    <span class="dot"></span>
                </h2>
                <h3>CONTACT US TODAY!</h3>
                <p>
                    Have any questions? Ready to book your baggage storage or delivery service in Kuching?
                </p>
                <p>
                    Reach us today through our form or the following contact information.
                </p>

                <div class="contact-info">
                    <div class="contact-item">
                        <div class="icon-circle">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="contact-text">
                            <div class="label">Phone Number</div>
                            <div class="value">+60 187773618</div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="icon-circle">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <div class="label">Email Address</div>
                            <div class="value">easesarawak@gmail.com</div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="icon-circle">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="contact-text">
                            <div class="label">Office Address</div>
                            <div class="value">No.118, Level 1, Plaza Aurora, Jalan McDougall, 93000 Kuching, Sarawak</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="connect-right">
                <h2 class="pill-title">
                    <span class="dot"></span>
                    MESSAGE US TODAY
                    <span class="dot"></span>
                </h2>
                <p class="tagline">FILL THE FORM BELOW</p>
                <p class="message-desc">Travel Light. Travel Smart. Travel with EASE.</p>

                <form class="contact-form">
                    <div class="row-inputs">
                        <input type="email" placeholder="Your Email" required>
                        <input type="text" placeholder="Your Phone Number" required>
                    </div>
                    <input type="text" placeholder="Subject" required>
                    <textarea placeholder="Your Message" rows="5" required></textarea>
                    <button type="submit">SUBMIT FORM</button>
                </form>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="cta-content">
            <h2>
                AT EASE, WE PROMISE YOU A WONDERFUL AND<br>MEMORABLE JOURNEY IN KUCHING.
            </h2>
            <p>
                Travel Light. Travel Smart. Travel with EASE.
            </p>
            <a href="#book" class="cta-button">SCHEDULE TODAY</a>
        </div>
    </section>

    <?= $this->include('footer/footer') ?>

    <script>
        // Function to book storage service (Basic card)
        function bookStorage() {
            // Set service preference in sessionStorage
            sessionStorage.setItem('preferredService', 'storage');

            // Clear any existing booking data to start fresh
            sessionStorage.removeItem('bookingData');
            sessionStorage.removeItem('isEditing');

            // Redirect to booking page
            window.location.href = 'booking';
        }

        // Function to book delivery service (Standard and On-demand cards)
        function bookDelivery() {
            // Set service preference in sessionStorage
            sessionStorage.setItem('preferredService', 'delivery');

            // Clear any existing booking data to start fresh
            sessionStorage.removeItem('bookingData');
            sessionStorage.removeItem('isEditing');

            // Redirect to booking page
            window.location.href = 'booking';
        }
    </script>

</body>

</html>