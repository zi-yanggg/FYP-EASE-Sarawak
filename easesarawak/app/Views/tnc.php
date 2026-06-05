<?php helper('translation'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EASE SARAWAK | <?= ease_translate('Terms and Conditions') ?></title>
    <link rel="icon" type="image/png" href="assets/images/cropped-Ease_PNG_File-09.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/footer_style.css">
    <link rel="stylesheet" href="assets/css/navbar_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

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
            background-image: url("assets/images/loading-of-luggage-to-airplane.webp");
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

        .content-container {
            padding: 70px 50px;
        }

        .content-container h3 {
            font-size: 1.8rem;
            /* margin-top: 30px; */
            margin-bottom: 15px;
            color: #333;
        }

        .content-container h4 {
            font-size: 1.4rem;
            margin-top: 30px;
            margin-bottom: 5px;
            color: #333;
        }

        .content-container p {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #333;
        }

        .content-container ul {
            font-size: 1.1rem;
            margin-bottom: 15px;
            color: #333;
        }

        .content-container ul li {
            margin-left: 40px;
        }

        .content-container a {
            color: #f2be00;
            /* text-decoration: none; */
        }
        @media (max-width: 768px) {
            .about-title{
                margin-top: 70px;
            }
        }
    </style>
</head>

<body>
    <?= $this->include('navbar/navbar'); ?>

    <!-- Title -->
    <section class="about-title">
        <div class="title-overlay"></div>
        <h1><?= ease_translate('TERMS & CONDITIONS') ?></h1>
    </section>

    <!-- Title -->
    <section class="content">
        <div class="content-container">
            <h3><?= ease_translate('Definition of Luggage') ?></h3>
            <p><strong><?= ease_translate('Standard Luggage') ?></strong> <?= ease_translate('is defined by airline regulations and can be checked or carried on the customer\'s intended flight. Each piece of standard luggage must not exceed a total of 180 cm in dimensions and must weigh no more than 23kg. Items such as sports equipment (including but not limited to surfboards, snowboards, golf bags, and bicycles), musical instruments (such as cellos, guitars, pianos, and drums), and baby strollers/carriages are NOT considered standard luggage.') ?>
                <?= ease_translate('Any luggage or items that do not meet these specifications are categorized as') ?> <strong><?= ease_translate('Special Luggage') ?></strong><?= ease_translate('. All luggage must be in good condition and securely closed, sealed, or zipped.') ?>
                <?= ease_translate('Each piece of luggage must be kept separate and not bundled or attached to any other items, including but not limited to plastic bags, travel pillows, or smaller bags.') ?>
                <?= ease_translate('Luggage MUST NOT contain any illegal, dangerous, prohibited, risky, or suspicious items.') ?> <strong>EASE STORAGE SDN. BHD.</strong> <?= ease_translate('reserves the right to refuse any luggage or belongings that are deemed unacceptable.') ?>
                <?= ease_translate('Attachments like name tags, accessory dolls, pillows, or small bags that are not counted as a single piece of luggage are NOT guaranteed in terms of their condition or risk of loss.') ?>
            </p>

            <h3><?= ease_translate('Booking Conditions') ?></h3>
            <ul>
                <li><?= ease_translate('Orders must be submitted prior to service usage and confirmed by') ?> <strong>EASE STORAGE SDN. BHD.</strong>.</li>
                <li><strong>EASE STORAGE SDN. BHD.</strong> <?= ease_translate('reserves the right to deny or cancel bookings if there is a suspicion of misuse of our services by you or a third party for commercial purposes.') ?></li>
                <li><?= ease_translate('Customers are responsible for ensuring that all provided information and contact details are accurate and accessible.') ?></li>
                <li><?= ease_translate('A booking is considered confirmed ONLY after payment is successfully completed and the customer has received a confirmation email from easesarawak@gmail.com') ?></li>
            </ul>

            <h3><?= ease_translate('Amendment Conditions') ?></h3>
            <ul>
                <li><?= ease_translate('To modify a booking, requests must be submitted via email or live chat at least 3 hours before the service is used.') ?></li>
                <li><?= ease_translate('Once the amendment is confirmed, the customer will receive an updated email from') ?> <strong>EASE STORAGE SDN. BHD.</strong>.</li>
            </ul>

            <h3><?= ease_translate('Cancellation and Refund Conditions') ?></h3>
            <ul>
                <li><?= ease_translate('To cancel an order and receive a full refund, requests must be submitted via email at least 2 hours before the service is used.') ?></li>
                <li><?= ease_translate('Bookings or orders that are canceled after the service has started or 1 hour before the service start time WILL NOT qualify for a refund.') ?></li>
                <li><?= ease_translate('Refunds will be processed within 7-14 business days.') ?></li>
                <li><?= ease_translate('Refunds will ONLY be issued through our accepted wireless transfer Methods.') ?></li>
                <li><?= ease_translate('The booking/order will be regarded as a “NO SHOW” starting from the scheduled service time.') ?></li>
            </ul>

            <h3><?= ease_translate('Notification of Change') ?></h3>
            <ul>
                <li><?= ease_translate('Any updates or notifications regarding changes to an order or booking will ONLY be sent from') ?> <a href=”mailto:easesarawak@gmail.com”>easesarawak@gmail.com</a>.</li>
                <li><?= ease_translate('After service amendments or changes, customers should keep the confirmation email for reference.') ?></li>
                <li><?= ease_translate('In the case of service cancellations, customers should also retain the confirmation email for reference.') ?></li>
                <li><strong>EASE STORAGE SDN. BHD.</strong> <?= ease_translate('retains the right to update these terms and conditions periodically in accordance with the Standard Terms and Conditions.') ?></li>
            </ul>

            <h3><?= ease_translate('Conditions of In-Town Delivery Service') ?></h3>
            <ul>
                <li><?= ease_translate('Order(s) and customer booking(s) are confirmed ONLY AFTER the payment has been successfully processed by the customer.') ?></li>
                <li><?= ease_translate('Scheduled deliveries will be made according to the established timetable and cannot be set for a specific time.') ?></li>
                <li><strong>EASE STORAGE SDN. BHD.</strong> <?= ease_translate('couriers, drivers, or staff will collect luggage from the designated location 15 minutes BEFORE or AFTER the scheduled drop-off time at the gate or lobby of the property for outbound deliveries. Please ensure that the luggage is ready for drop-off 15 minutes before the scheduled time.') ?></li>
                <li><?= ease_translate('For hotel drop-off and pick-up, the hotel concierge facility is only available to the hotel guests. Customers should provide their check-in and check-out details or confirmation for us to coordinate with the hotel for luggage transfer. Non-hotel guest customers must make their luggage available at the designated location as per the scheduled time.') ?></li>
                <li><?= ease_translate('The in-town delivery service is ONLY available in') ?> <strong>Kuching</strong> <?= ease_translate('and does not include any special areas.') ?></li>
                <li><?= ease_translate('If the customer is late in placing their luggage at the delivery location, including but not limited to airports, hotels, shopping malls, or homes, our courier will ONLY wait for up to 15 minutes.') ?></li>
                <li><?= ease_translate('If the customer is not available 15 minutes after the scheduled time or if we are unable to contact the customer, the order will be classified as a “No Show” and will be canceled WITHOUT a refund.') ?></li>
                <li><?= ease_translate('If') ?> <strong>EASE STORAGE SDN BHD.</strong> <?= ease_translate('couriers, drivers, or staff cannot locate the luggage at the designated location, they will contact the customer exclusively via phone or email.') ?></li>
                <li><strong>EASE STORAGE SDN. BHD.</strong> <?= ease_translate('SHALL NOT be liable for any FRAGILE, PERISHABLE, ILLEGAL, or PROHIBITED item(s) or content(s) of luggage delivered.') ?></li>
                <li><?= ease_translate('In-Town Delivery Service operates daily from 07:00 to 21:00.') ?></li>
            </ul>

            <h3><?= ease_translate('Conditions of Luggage Storage Service') ?></h3>
            <ul>
                <li><?= ease_translate('Order(s) and customer booking(s) are confirmed ONLY AFTER the payment has been successfully processed by the customer.') ?></li>
                <li><?= ease_translate('The storage duration begins at the scheduled reservation time even if the luggage is dropped off after the appointed time.') ?></li>
                <li><?= ease_translate('If the luggage is dropped off before the scheduled time, the storage duration will start from when the luggage is actually dropped off.') ?></li>
                <li><?= ease_translate('If the luggage is retrieved after the scheduled retrieval time, additional service fees will be applied.') ?></li>
                <li><?= ease_translate('If the luggage is retrieved before the scheduled retrieval time, no refund will be provided.') ?></li>
                <li><?= ease_translate('Customers may ONLY STORE luggage or belongings DURING SERVICE HOURS in accordance with the regulations of EASE STORAGE SDN. BHD..') ?></li>
                <li><?= ease_translate('To extend the storage duration, customers should contact us via website live chat or email and receive confirmation ONLY from EASE STORAGE SDN. BHD. via email at') ?> <a href="mailto:easesarawak@gmail.com">easesarawak@gmail.com</a>.</li>
                <li><?= ease_translate('We will confirm the extension of storage duration ONLY after a new Estimated Time Pick-up (ETP) is set AND the service fee for both the previous and new ETP is paid.') ?></li>
                <li><?= ease_translate('Luggage not picked up within 30 days after the ETP, without an extension for storage duration, will be considered abandoned property and disposed of.') ?></li>
                <li><?= ease_translate('If a customer wishes to pick up any belongings from their luggage during storage, they MUST retrieve all items AND close the order. We DO NOT accept partial retrieval requests.') ?></li>
                <li><?= ease_translate('We WILL NOT open customer luggage or remove any items from the luggage under any circumstances or upon customer request, EXCEPT in cooperation with official or governmental authorities during lawful investigations.') ?></li>
                <li><strong>EASE STORAGE SDN. BHD.</strong> <?= ease_translate('SHALL NOT be liable for any FRAGILE, PERISHABLE, ILLEGAL, or PROHIBITED item(s) or content(s) of luggage stored at our facility.') ?></li>
                <li><?= ease_translate('Storage Service operates daily from 07:00 to 21:00.') ?></li>
            </ul>

            <h3><?= ease_translate('Conditions of Depositing / Retrieving Luggage') ?></h3>
            <ul>
                <li><?= ease_translate('The customer MUST provide valid references, including a passport, Malaysian ID, or driver\'s license, along with the order confirmation or email, to verify the consignee\'s identity. Copies or photos of ID cards or passports will NOT be accepted.') ?></li>
                <li><?= ease_translate('If the customer does not have valid or correct order references,') ?> <strong>EASE STORAGE SDN. BHD.</strong> <?= ease_translate('reserves the right to deny the retrieval request.') ?></li>
                <li><strong>EASE STORAGE SDN. BHD.</strong> <?= ease_translate('does NOT accept any partial deposit/retrieval requests or allow the pickup of personal items from luggage during the storage period.') ?></li>
                <li><?= ease_translate('Customers may ONLY retrieve luggage or belongings during service hours in accordance with the regulations of') ?> <strong>EASE STORAGE SDN. BHD.</strong>.</li>
                <li><?= ease_translate('If stored belongings are to be retrieved by another individual or third party, the customer must provide the valid Malaysian ID or passport of the authorized consignee via website live chat or email. This information will be required for identity verification when the authorized consignee retrieves the belongings.') ?></li>
            </ul>

            <h3><?= ease_translate('Acceptance of Terms and Conditions of') ?> <strong>EASE STORAGE SDN. BHD.</strong></h3>
            <p><?= ease_translate('Please thoroughly review these terms and conditions before accessing and using our services. By using our services, you are agreeing to be bound by these terms. If you do not agree to these terms, you must CANCEL and refrain from accessing or using our services. In cases where your booking or service usage is handled by a third party on your behalf, it is implied that you accept these terms and our Privacy Policy.') ?></p>
            <p><?= ease_translate('Our services are only available to individuals aged 18 or older who are considered legal adults under Malaysian law. By using our website, application, or services, you confirm that you are 18 years of age or older, agree to abide by these terms and policies, and are capable of entering into a contract with') ?> <strong>EASE STORAGE SDN. BHD.</strong>.</p>

            <h3><?= ease_translate('Company Information') ?></h3>
            <p><strong>EASE STORAGE SDN. BHD.</strong> <?= ease_translate('(1589189-T) is a registered entity in Malaysia, with its registered office located at First Floor, Unit No E297, Lot 11382 or Old Lot No 11099, Block E, Section 64, ICOM SQUARE, Jalan Pending, 93450 Kuching, Sarawak.') ?></p>

            <h3><?= ease_translate('Pricing and Payments') ?></h3>
            <p><?= ease_translate('All prices, payments, and financial transactions are to be conducted exclusively via our website or at our storage hub. Any financial or transactional request that is made outside these official channels IS NOT authorized by us.') ?></p>
            <p><?= ease_translate('Service prices are listed on our official platforms, and while prices may change, the amount will be FIXED once a booking or reservation is confirmed. All listed prices include 6% SST. Any requests not included in the initial transaction will be treated as a separate order and may be subject to different pricing. Payments can be made by credit card, debit card, or cash in') ?> <strong><?= ease_translate('Ringgit Malaysia (RM)') ?></strong><?= ease_translate('. Accepted payment methods are listed below:') ?></p>

            <h4><?= ease_translate('Online booking:') ?></h4>
            <ul>
                <li><?= ease_translate('Credit/Debit Card (VISA, MasterCard)') ?></li>
                <li><?= ease_translate('QR Code') ?></li>
                <li><?= ease_translate('Online Transfer') ?></li>
            </ul>

            <h4><?= ease_translate('EASE Storage Hub (Malaysia):') ?></h4>
            <ul>
                <li><?= ease_translate('Cash') ?> <strong>(<?= ease_translate('Ringgit Malaysia (RM)') ?>)</strong></li>
                <li><?= ease_translate('Credit/Debit Card (VISA, MasterCard)') ?></li>
                <li><?= ease_translate('QR Code') ?></li>
                <li><?= ease_translate('Online Transfer') ?></li>
            </ul>

            <p><strong><?= ease_translate('You will receive a receipt (digital or printed) upon completion of payment.') ?></strong></p>

            <h3><?= ease_translate('Receipts') ?></h3>
            <p><?= ease_translate('Once payment is confirmed, all service pricing details will appear on your invoice, and no hidden charges will apply. You have the right to request clarification if anything in the invoice is unclear. The individual using the service must be the same person collecting the luggage. The amounts listed on the invoice and receipt will match exactly, and the receipt is provided once the transaction is complete, signaling agreement to these terms. By accepting the receipt, you acknowledge that the service has concluded.') ?></p>

            <h3><strong>EASE STORAGE SDN. BHD.</strong> <?= ease_translate('Responsibilities') ?></h3>
            <p><?= ease_translate('We commit to delivering services as described on our website or at our store. If we fail to complete an inbound delivery within the specified timeframe, you will be entitled to a 100% refund. If we fail to meet the scheduled time for an outbound delivery, we will cover the necessary costs to ensure your luggage reaches your final specified destination. Any changes in circumstances that could affect your service will be communicated to you using the contact details you have provided.') ?></p>

            <h3><?= ease_translate('User Responsibilities') ?></h3>
            <p><?= ease_translate('You confirm that no FRAGILE, PERISHABLE, ILLEGAL or PROHIBITED item(s) or content(s) are included in the luggage being delivered or stored, and that the services are used correctly. You are responsible for any loss or damage resulting from misuse or violation of these terms. Retain your invoice or certification for verification purposes during check-out. You confirm that all information you provide is accurate, whether given by you or a third party on your behalf. Our staff may request proof of identification, such as a credit card, driving licence or passport, to verify your identity.') ?></p>

            <h3><?= ease_translate('Delivery Policy and Security Procedures') ?></h3>
            <p><?= ease_translate('Our services must be used for lawful purposes. By utilizing our delivery service, you agree to our terms and comply with Malaysian law. We will not tamper with your luggage, but in certain circumstances, we may cooperate with Malaysian government officials for investigations or to prevent illegal activities. If any suspicious or prohibited items are discovered, either by official notice or through screening methods like X-rays, we may ask you to allow our staff to inspect your luggage. Refusing such inspection may result in the cancellation of your service without a refund for security reasons. We reserve the right to decline or cancel any booking, reservation, or service if terms are violated or if illegal activity is suspected.') ?></p>
            <ul>
                <li><?= ease_translate('A Standard Luggage is defined as luggage where the combined dimensions (Width, Length, and Height) do not exceed 180 cm, and the weight does not exceed 23kg.') ?></li>
                <li><?= ease_translate('A Special Luggage is defined as luggage that does not fall within the Standard Luggage category and will be charged based on a custom quote and will be subject to storage availability.') ?></li>
            </ul>

            <h3><?= ease_translate('Events Beyond Our Control') ?></h3>
            <p><?= ease_translate('We are not liable for any failure to provide services or protect your luggage due to circumstances outside our control, including but not limited to:') ?></p>
            <ul>
                <li><?= ease_translate('Failure to comply with aviation security.') ?></li>
                <li><?= ease_translate('Inability to deliver luggage to the airport or hotel on time by third parties.') ?></li>
                <li><?= ease_translate('Late bookings, reservations, or cancellations.') ?></li>
                <li><?= ease_translate('Actions of government or safety authorities (e.g., police, customs, airport operators).') ?></li>
                <li><?= ease_translate('Disruptions in regional or national transportation.') ?></li>
                <li><?= ease_translate('Natural disasters (e.g., floods, earthquakes, tsunamis).') ?></li>
                <li><?= ease_translate('Situations caused by third-party actions beyond our control (e.g., riots, strikes, fires, investigations).') ?></li>
            </ul>

            <h3><?= ease_translate('Prohibited Items') ?></h3>
            <p><?= ease_translate('For the safety and security of our clients and their belongings, we enforce strict guidelines on items that cannot be stored or delivered through our service. Below is a list of items that are prohibited for storage or transport with EASE STORAGE SDN. BHD.:') ?></p>
            <ul>
                <li><?= ease_translate('Living or deceased plants or animals.') ?></li>
                <li><?= ease_translate('Currency (banknotes, coins, credit/debit cards, and travelers cheques).') ?></li>
                <li><?= ease_translate('Fine art, antiques, jewelry, important documents, software, or any other irreplaceable valuables.') ?></li>
                <li><?= ease_translate('Materials deemed pornographic or indecent.') ?></li>
                <li><?= ease_translate('Precious metals (such as gold or silver in any form) and gemstones.') ?></li>
                <li><?= ease_translate('Explosives, fuel, hazardous, or flammable materials.') ?></li>
                <li><?= ease_translate('Radioactive substances or items that emit strong odors or fumes.') ?></li>
                <li><?= ease_translate('Firearms, ammunition, illegal drugs, or any other substances controlled by local law.') ?></li>
                <li><?= ease_translate('Items considered risky, dangerous, or harmful as defined by civil aviation authorities.') ?></li>
                <li><?= ease_translate('Stolen goods or contraband.') ?></li>
                <li><?= ease_translate('Perishable or spoiled food that may emit fumes, generate liquids, or strong odors.') ?></li>
                <li><?= ease_translate('Batteries and electronic devices (including watches, clocks, phones, laptops, or tablets containing batteries).') ?></li>
                <li><?= ease_translate('Waste or refuse.') ?></li>
            </ul>

            <h3><?= ease_translate('Liabilities of') ?> <strong>EASE STORAGE SDN. BHD.</strong></h3>
            <p><?= ease_translate('For confirmed orders, we ensure that the delivery or service will be completed on time and directed to the correct destination and recipient. We shall not be held liable for any loss or damage resulting from FRAGILE, PERISHABLE, ILLEGAL or PROHIBITED item(s) or content(s).') ?></p>

            <h3><?= ease_translate('Insurance Policy') ?></h3>
            <p><?= ease_translate('At') ?> <strong>EASE STORAGE SDN. BHD.</strong><?= ease_translate(', the safety and security of your belongings are our top priority. All luggage stored, transferred, or delivered with us is automatically covered by our standard insurance policy in the event of damage, theft, or complete loss of luggage caused by mishandling on our part. For added peace of mind, customers may choose to purchase additional insurance coverage.') ?></p>
            <ul>
                <li><?= ease_translate('For Standard Insurance, compensation is limited to the actual market value of the contents, up to RM1,000 per order.') ?></li>
                <li><?= ease_translate('For Extra Coverage, compensation is limited to the actual market value of the contents, up to RM2,500 per order.') ?></li>
                <li><?= ease_translate('Accessories such as luggage tags, padlocks, small bags, luggage covers, and decorative items are excluded from this insurance.') ?></li>
                <li><?= ease_translate('Lost individual items within a bag are not covered unless the entire luggage is lost or stolen.') ?></li>
                <li><?= ease_translate('Wear and tear such as scratches, scuff marks, or other minimal damage caused by normal handling is not covered.') ?></li>
            </ul>

            <h4><?= ease_translate('To ensure eligibility for coverage, customers must:') ?></h4>
            <ul>
                <li><?= ease_translate('Ensure all luggage is in good condition, securely closed, zipped, and sealed before handover.') ?></li>
                <li><?= ease_translate('No FRAGILE, PERISHABLE, ILLEGAL, or PROHIBITED item(s) or content(s) are included in the luggage being delivered or stored.') ?></li>
            </ul>

            <h4><?= ease_translate('In the event of damage, loss, or theft:') ?></h4>
            <ul>
                <li><?= ease_translate('Claims must be submitted in writing within 24 hours of the completion of the service.') ?></li>
                <li><?= ease_translate('A police report or incident documentation may be required for claims above RM1,000.') ?></li>
                <li><?= ease_translate('Our team will assess and respond to claims within 7-14 business days.') ?></li>
            </ul>

            <h3><?= ease_translate('Customer Rights') ?></h3>
            <p><?= ease_translate('You have the right to modify or cancel your booking or reservation up to 2 hours before the scheduled time for luggage storage and delivery service.') ?></p>

        </div>
    </section>

    <?= $this->include('footer/footer'); ?>
</body>

</html>