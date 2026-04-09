-- ============================================================
-- EASE Sarawak — Order Table Seed Data
-- Database : easesarawak
-- Covers   : October 2025 – March 2026  (6 months)
-- Run in   : phpMyAdmin  or  mysql -u root easesarawak < seed_orders.sql
-- ============================================================

USE `easesarawak`;

INSERT INTO `order`
  (`service_type`, `first_name`, `last_name`, `id_num`, `email`, `phone`,
   `social`, `social_num`, `upload`, `special`, `special_note`,
   `order_details_json`, `promo_code`, `status`, `amount`,
   `payment_method`, `is_deleted`, `created_date`, `modified_date`)
VALUES

-- ── October 2025 ────────────────────────────────────────────
('storage',  'Ahmad',    'Razali',   901015017890, 'ahmad.razali@gmail.com',   60123456789, 1, 901015017890, 'upload_001.jpg', NULL, NULL,
 '{"numberOfBags":2,"storageType":"Small","duration":"3 days","pickupDate":"2025-10-03","returnDate":"2025-10-06"}',
 '', 2, 45, 'Online Banking', 0, '2025-10-03 09:14:00', '2025-10-06 11:00:00'),

('delivery', 'Nurul',    'Aisyah',   950820106754, 'nurul.aisyah@yahoo.com',   60194567890, 1, 950820106754, 'upload_002.jpg', NULL, NULL,
 '{"numberOfBags":1,"weight":"4kg","pickupAddress":"Kuching International Airport","deliveryAddress":"Hilton Kuching, Jalan Tunku Abdul Rahman"}',
 '', 2, 60, 'Credit Card', 0, '2025-10-07 13:30:00', '2025-10-07 17:45:00'),

('storage',  'Lee',      'Wei Jian', 880325085432, 'leewj88@hotmail.com',      60112233445, 1, 880325085432, 'upload_003.jpg', 1,  NULL,
 '{"numberOfBags":4,"storageType":"Large","duration":"7 days","pickupDate":"2025-10-10","returnDate":"2025-10-17"}',
 'EASE10', 2, 120, 'Online Banking', 0, '2025-10-10 08:00:00', '2025-10-17 10:30:00'),

('delivery', 'Siti',     'Hajar',    920601126543, 'sitihajar@gmail.com',      60187654321, 1, 920601126543, 'upload_004.jpg', NULL, NULL,
 '{"numberOfBags":2,"weight":"8kg","pickupAddress":"Miri Airport","deliveryAddress":"Boulevard Hotel, Miri"}',
 '', 1, 80, 'Credit Card', 0, '2025-10-15 10:45:00', '2025-10-15 14:00:00'),

('storage',  'Kumar',    'Selvam',   870912086321, 'kumar.selvam@gmail.com',   60135678901, 1, 870912086321, 'upload_005.jpg', NULL, NULL,
 '{"numberOfBags":1,"storageType":"Small","duration":"2 days","pickupDate":"2025-10-20","returnDate":"2025-10-22"}',
 '', 2, 30, 'Online Banking', 0, '2025-10-20 07:30:00', '2025-10-22 09:15:00'),

('delivery', 'Fatimah',  'Zahra',    991205056789, 'fatimah.z@gmail.com',      60168901234, 1, 991205056789, 'upload_006.jpg', NULL, NULL,
 '{"numberOfBags":1,"weight":"3kg","pickupAddress":"Sibu Airport","deliveryAddress":"Tanahmas Hotel, Sibu"}',
 'EASE10', 2, 54, 'Credit Card', 0, '2025-10-25 11:00:00', '2025-10-25 15:30:00'),

-- ── November 2025 ────────────────────────────────────────────
('storage',  'Mohd',     'Hafiz',    931120136201, 'mohd.hafiz@gmail.com',     60123987654, 1, 931120136201, 'upload_007.jpg', NULL, NULL,
 '{"numberOfBags":3,"storageType":"Medium","duration":"5 days","pickupDate":"2025-11-02","returnDate":"2025-11-07"}',
 '', 2, 90, 'Online Banking', 0, '2025-11-02 08:45:00', '2025-11-07 10:00:00'),

('delivery', 'Rachel',   'Tan',      000312126987, 'rachel.tan@gmail.com',     60114523678, 1, 000312126987, 'upload_008.jpg', 1,  NULL,
 '{"numberOfBags":2,"weight":"6kg","pickupAddress":"Kuching International Airport","deliveryAddress":"Pullman Kuching, Jalan Mathies"}',
 '', 2, 75, 'Credit Card', 0, '2025-11-05 14:00:00', '2025-11-05 18:15:00'),

('storage',  'Zainab',   'Ibrahim',  781005085231, 'zainab.ibrahim@yahoo.com', 60197865432, 1, 781005085231, 'upload_009.jpg', NULL, NULL,
 '{"numberOfBags":5,"storageType":"Large","duration":"10 days","pickupDate":"2025-11-08","returnDate":"2025-11-18"}',
 'WELCOME20', 2, 160, 'Online Banking', 0, '2025-11-08 09:00:00', '2025-11-18 11:00:00'),

('delivery', 'Boon',     'Kiat',     850715086542, 'boonkiat85@gmail.com',     60161234567, 1, 850715086542, 'upload_010.jpg', NULL, NULL,
 '{"numberOfBags":1,"weight":"2kg","pickupAddress":"Kuching Waterfront","deliveryAddress":"Grand Margherita Hotel, Kuching"}',
 '', 2, 50, 'Credit Card', 0, '2025-11-12 10:30:00', '2025-11-12 13:45:00'),

('storage',  'Amirul',   'Hadi',     010830016543, 'amirul.hadi@gmail.com',    60125678901, 1, 010830016543, 'upload_011.jpg', NULL, NULL,
 '{"numberOfBags":2,"storageType":"Small","duration":"4 days","pickupDate":"2025-11-14","returnDate":"2025-11-18"}',
 '', 0, 60, 'Online Banking', 0, '2025-11-14 07:00:00', NULL),

('delivery', 'Jenny',    'Wong',     940228126789, 'jenny.wong@gmail.com',     60193456789, 1, 940228126789, 'upload_012.jpg', NULL, NULL,
 '{"numberOfBags":3,"weight":"10kg","pickupAddress":"Bintulu Airport","deliveryAddress":"Regency Bintulu Hotel"}',
 'EASE10', 2, 108, 'Credit Card', 0, '2025-11-20 12:00:00', '2025-11-20 16:30:00'),

('storage',  'Rajan',    'Nair',     680506086123, 'rajan.nair@hotmail.com',   60126789012, 1, 680506086123, 'upload_013.jpg', NULL, NULL,
 '{"numberOfBags":2,"storageType":"Medium","duration":"3 days","pickupDate":"2025-11-22","returnDate":"2025-11-25"}',
 '', 2, 70, 'Online Banking', 0, '2025-11-22 09:30:00', '2025-11-25 10:15:00'),

('delivery', 'Nor',      'Hidayah',  960910076321, 'norhidayah@gmail.com',     60184567890, 1, 960910076321, 'upload_014.jpg', 1,  NULL,
 '{"numberOfBags":1,"weight":"5kg","pickupAddress":"Kuching International Airport","deliveryAddress":"Tune Hotel, Kuching"}',
 '', 2, 65, 'Credit Card', 0, '2025-11-28 15:00:00', '2025-11-28 19:00:00'),

-- ── December 2025 ────────────────────────────────────────────
('storage',  'Hassan',   'Aziz',     750301086789, 'hassan.aziz@gmail.com',    60128901234, 1, 750301086789, 'upload_015.jpg', NULL, NULL,
 '{"numberOfBags":6,"storageType":"Large","duration":"14 days","pickupDate":"2025-12-01","returnDate":"2025-12-15"}',
 'XMAS15', 2, 252, 'Online Banking', 0, '2025-12-01 08:00:00', '2025-12-15 10:00:00'),

('delivery', 'Priya',    'Krishnan', 910618086432, 'priya.k@gmail.com',        60156789012, 1, 910618086432, 'upload_016.jpg', NULL, NULL,
 '{"numberOfBags":2,"weight":"7kg","pickupAddress":"Kuching International Airport","deliveryAddress":"Riverside Majestic Hotel"}',
 '', 2, 85, 'Credit Card', 0, '2025-12-05 10:00:00', '2025-12-05 14:30:00'),

('storage',  'Aina',     'Sofea',    020615016890, 'aina.sofea@gmail.com',     60171234567, 1, 020615016890, 'upload_017.jpg', NULL, NULL,
 '{"numberOfBags":3,"storageType":"Medium","duration":"7 days","pickupDate":"2025-12-08","returnDate":"2025-12-15"}',
 'XMAS15', 2, 127, 'Online Banking', 0, '2025-12-08 07:30:00', '2025-12-15 09:45:00'),

('delivery', 'Chong',    'Wei Lin',  890402086543, 'chong.wl@yahoo.com',       60192345678, 1, 890402086543, 'upload_018.jpg', NULL, NULL,
 '{"numberOfBags":4,"weight":"15kg","pickupAddress":"Miri Airport","deliveryAddress":"ParkCity Everly Hotel, Miri"}',
 '', 2, 120, 'Credit Card', 0, '2025-12-12 13:00:00', '2025-12-12 17:15:00'),

('storage',  'Yazid',    'Malik',    831204086210, 'yazid.malik@gmail.com',    60163456789, 1, 831204086210, 'upload_019.jpg', 1,  NULL,
 '{"numberOfBags":2,"storageType":"Small","duration":"5 days","pickupDate":"2025-12-20","returnDate":"2025-12-25"}',
 'XMAS15', 2, 76, 'Online Banking', 0, '2025-12-20 09:00:00', '2025-12-25 11:00:00'),

('delivery', 'Michelle', 'Lim',      001022126543, 'michelle.lim@gmail.com',   60143456789, 1, 001022126543, 'upload_020.jpg', NULL, NULL,
 '{"numberOfBags":1,"weight":"3kg","pickupAddress":"Kuching International Airport","deliveryAddress":"Fairfield by Marriott Kuching"}',
 '', 2, 55, 'Credit Card', 0, '2025-12-23 11:30:00', '2025-12-23 15:00:00'),

('storage',  'Firdaus',  'Roslan',   970707076543, 'firdaus.r@gmail.com',      60129876543, 1, 970707076543, 'upload_021.jpg', NULL, NULL,
 '{"numberOfBags":4,"storageType":"Large","duration":"7 days","pickupDate":"2025-12-26","returnDate":"2026-01-02"}',
 '', 2, 140, 'Online Banking', 0, '2025-12-26 08:00:00', '2026-01-02 10:00:00'),

-- ── January 2026 ─────────────────────────────────────────────
('delivery', 'Tan',      'Ah Kow',   720515086321, 'tan.ahkow@gmail.com',      60182345678, 1, 720515086321, 'upload_022.jpg', NULL, NULL,
 '{"numberOfBags":2,"weight":"8kg","pickupAddress":"Kuching International Airport","deliveryAddress":"Hotel Merdeka Palace, Kuching"}',
 '', 2, 80, 'Credit Card', 0, '2026-01-04 10:00:00', '2026-01-04 14:30:00'),

('storage',  'Haslinda', 'Bakar',    830916076210, 'haslinda.b@yahoo.com',     60175678901, 1, 830916076210, 'upload_023.jpg', NULL, NULL,
 '{"numberOfBags":2,"storageType":"Small","duration":"3 days","pickupDate":"2026-01-07","returnDate":"2026-01-10"}',
 'EASE10', 2, 54, 'Online Banking', 0, '2026-01-07 09:15:00', '2026-01-10 10:30:00'),

('delivery', 'Suresh',   'Menon',    880210086432, 'suresh.m@gmail.com',       60167890123, 1, 880210086432, 'upload_024.jpg', NULL, NULL,
 '{"numberOfBags":3,"weight":"12kg","pickupAddress":"Sibu Airport","deliveryAddress":"Li Hua Hotel, Sibu"}',
 '', 2, 110, 'Credit Card', 0, '2026-01-10 13:30:00', '2026-01-10 17:00:00'),

('storage',  'Izzatul',  'Husna',    950303096543, 'izzatul.h@gmail.com',      60133456789, 1, 950303096543, 'upload_025.jpg', NULL, NULL,
 '{"numberOfBags":3,"storageType":"Medium","duration":"6 days","pickupDate":"2026-01-15","returnDate":"2026-01-21"}',
 '', 2, 105, 'Online Banking', 0, '2026-01-15 08:30:00', '2026-01-21 10:00:00'),

('delivery', 'Kevin',    'Ho',       910812086210, 'kevin.ho@gmail.com',       60153456789, 1, 910812086210, 'upload_026.jpg', 1,  NULL,
 '{"numberOfBags":1,"weight":"4kg","pickupAddress":"Kuching International Airport","deliveryAddress":"Citadel Inn, Kuching"}',
 'EASE10', 2, 54, 'Credit Card', 0, '2026-01-19 11:00:00', '2026-01-19 15:15:00'),

('storage',  'Roslan',   'Hamid',    761120086789, 'roslan.h@gmail.com',       60124567890, 1, 761120086789, 'upload_027.jpg', NULL, NULL,
 '{"numberOfBags":5,"storageType":"Large","duration":"10 days","pickupDate":"2026-01-22","returnDate":"2026-02-01"}',
 '', 2, 200, 'Online Banking', 0, '2026-01-22 07:45:00', '2026-02-01 09:30:00'),

-- ── February 2026 ────────────────────────────────────────────
('delivery', 'Vivian',   'Yong',     950514126321, 'vivian.yong@gmail.com',    60185678901, 1, 950514126321, 'upload_028.jpg', NULL, NULL,
 '{"numberOfBags":2,"weight":"9kg","pickupAddress":"Kuching International Airport","deliveryAddress":"Kingwood Boutique Hotel, Kuching"}',
 '', 2, 90, 'Credit Card', 0, '2026-02-02 09:30:00', '2026-02-02 14:00:00'),

('storage',  'Azman',    'Salleh',   820415086543, 'azman.s@gmail.com',        60176789012, 1, 820415086543, 'upload_029.jpg', NULL, NULL,
 '{"numberOfBags":2,"storageType":"Small","duration":"4 days","pickupDate":"2026-02-06","returnDate":"2026-02-10"}',
 'EASE10', 2, 54, 'Online Banking', 0, '2026-02-06 08:00:00', '2026-02-10 10:00:00'),

('delivery', 'Nurhafizah','Johari',  000908066321, 'nurhafizah.j@gmail.com',   60196789012, 1, 000908066321, 'upload_030.jpg', NULL, NULL,
 '{"numberOfBags":1,"weight":"3kg","pickupAddress":"Miri Airport","deliveryAddress":"Mega Hotel Miri"}',
 '', 2, 55, 'Credit Card', 0, '2026-02-10 12:00:00', '2026-02-10 16:30:00'),

('storage',  'Daniel',   'Ling',     890630086543, 'daniel.ling@gmail.com',    60147890123, 1, 890630086543, 'upload_031.jpg', NULL, NULL,
 '{"numberOfBags":4,"storageType":"Large","duration":"8 days","pickupDate":"2026-02-14","returnDate":"2026-02-22"}',
 'WELCOME20', 2, 176, 'Online Banking', 0, '2026-02-14 09:00:00', '2026-02-22 11:15:00'),

('delivery', 'Hafizuddin','Ahmad',   011215016890, 'hafizuddin.a@gmail.com',   60108901234, 1, 011215016890, 'upload_032.jpg', 1,  NULL,
 '{"numberOfBags":2,"weight":"7kg","pickupAddress":"Kuching International Airport","deliveryAddress":"RH Hotel, Kuching"}',
 '', 2, 80, 'Credit Card', 0, '2026-02-18 14:00:00', '2026-02-18 18:30:00'),

('storage',  'Mei Ling', 'Chan',     930301126789, 'meiling.chan@yahoo.com',   60173456789, 1, 930301126789, 'upload_033.jpg', NULL, NULL,
 '{"numberOfBags":3,"storageType":"Medium","duration":"5 days","pickupDate":"2026-02-22","returnDate":"2026-02-27"}',
 'EASE10', 2, 127, 'Online Banking', 0, '2026-02-22 08:30:00', '2026-02-27 10:00:00'),

-- ── March 2026 ───────────────────────────────────────────────
('delivery', 'Saiful',   'Nizam',    880912086432, 'saiful.nizam@gmail.com',   60128901234, 1, 880912086432, 'upload_034.jpg', NULL, NULL,
 '{"numberOfBags":2,"weight":"6kg","pickupAddress":"Kuching International Airport","deliveryAddress":"Waterfront Hotel, Kuching"}',
 '', 2, 75, 'Credit Card', 0, '2026-03-01 10:00:00', '2026-03-01 14:00:00'),

('storage',  'Jasmine',  'Ong',      010403126543, 'jasmine.ong@gmail.com',    60164567890, 1, 010403126543, 'upload_035.jpg', NULL, NULL,
 '{"numberOfBags":2,"storageType":"Small","duration":"3 days","pickupDate":"2026-03-04","returnDate":"2026-03-07"}',
 '', 2, 45, 'Online Banking', 0, '2026-03-04 08:00:00', '2026-03-07 10:30:00'),

('delivery', 'Khairul',  'Anwar',    940318076432, 'khairul.a@gmail.com',      60189012345, 1, 940318076432, 'upload_036.jpg', NULL, NULL,
 '{"numberOfBags":3,"weight":"11kg","pickupAddress":"Bintulu Airport","deliveryAddress":"Parkcity Everly Hotel, Bintulu"}',
 'EASE10', 1, 99, 'Credit Card', 0, '2026-03-07 13:00:00', '2026-03-07 16:00:00'),

('storage',  'Zahra',    'Hussein',  990210076543, 'zahra.hussein@gmail.com',  60175432109, 1, 990210076543, 'upload_037.jpg', NULL, NULL,
 '{"numberOfBags":4,"storageType":"Large","duration":"7 days","pickupDate":"2026-03-10","returnDate":"2026-03-17"}',
 '', 2, 140, 'Online Banking', 0, '2026-03-10 07:30:00', '2026-03-17 09:00:00'),

('delivery', 'Alicia',   'Sim',      020918126789, 'alicia.sim@gmail.com',     60154321098, 1, 020918126789, 'upload_038.jpg', NULL, NULL,
 '{"numberOfBags":1,"weight":"4kg","pickupAddress":"Kuching International Airport","deliveryAddress":"Tune Hotel Downtown Kuching"}',
 '', 0, 60, 'Credit Card', 0, '2026-03-13 11:00:00', NULL),

('storage',  'Razif',    'Zainudin', 870614086210, 'razif.z@gmail.com',        60123210987, 1, 870614086210, 'upload_039.jpg', NULL, NULL,
 '{"numberOfBags":2,"storageType":"Medium","duration":"4 days","pickupDate":"2026-03-15","returnDate":"2026-03-19"}',
 'EASE10', 1, 90, 'Online Banking', 0, '2026-03-15 09:00:00', '2026-03-15 10:00:00'),

('delivery', 'Aishah',   'Putri',    001105076321, 'aishah.putri@gmail.com',   60196543210, 1, 001105076321, 'upload_040.jpg', NULL, NULL,
 '{"numberOfBags":2,"weight":"8kg","pickupAddress":"Miri Airport","deliveryAddress":"Eastwood Valley Golf, Miri"}',
 '', 0, 80, 'Credit Card', 0, '2026-03-18 14:30:00', NULL);

-- ============================================================
-- Verify the inserts
-- ============================================================
SELECT
    DATE_FORMAT(created_date, '%b %Y')  AS month,
    service_type,
    COUNT(*)                            AS orders,
    SUM(amount)                         AS revenue
FROM `order`
WHERE is_deleted = 0
GROUP BY YEAR(created_date), MONTH(created_date), service_type
ORDER BY YEAR(created_date), MONTH(created_date), service_type;
