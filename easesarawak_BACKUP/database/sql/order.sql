-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 09, 2026 at 01:43 PM
-- Server version: 8.0.43
-- PHP Version: 8.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `easesarawak`
--

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int NOT NULL,
  `service_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_num` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` int NOT NULL,
  `social` int NOT NULL,
  `social_num` int NOT NULL,
  `upload` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `special` int DEFAULT NULL,
  `special_note` int DEFAULT NULL,
  `order_details_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `promo_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `amount` int NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `created_date` datetime NOT NULL,
  `modified_date` datetime DEFAULT NULL,
  `insurance_selected` tinyint(1) NOT NULL DEFAULT '0',
  `insurance_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `service_type`, `first_name`, `last_name`, `id_num`, `email`, `phone`, `social`, `social_num`, `upload`, `special`, `special_note`, `order_details_json`, `promo_code`, `status`, `amount`, `payment_method`, `is_deleted`, `created_date`, `modified_date`, `insurance_selected`, `insurance_amount`, `modified_by`) VALUES
(1, 'delivery', 'Aung', 'Htet', '234567890', 'admin1@example.com', 123386340, 2, 123386340, '', NULL, NULL, '{\"service\":\"delivery\",\"origin\":\"Ease Storage Hub @ Plaza Aurora\",\"originAddress\":\"\",\"destination\":\"Pullman Kuching\",\"destinationAddress\":\"\",\"dropoffDate\":\"2025-10-07\",\"dropoffTime\":\"20:20\",\"pickupDate\":\"2025-10-07\",\"pickupTime\":\"20:32\"}', '', 0, 0, '', 0, '2025-10-06 11:25:17', NULL, 0, 0.00, NULL),
(2, 'delivery', 'Aung', 'Htet', '234567890', 'admin1@example.com', 123386340, 1, 123386340, '', NULL, NULL, '{\"service\":\"delivery\",\"origin\":\"Hilton Kuching Hotel\",\"originAddress\":\"\",\"destination\":\"CityOne Megamall\",\"destinationAddress\":\"\",\"dropoffDate\":\"2025-10-07\",\"dropoffTime\":\"21:29\",\"pickupDate\":\"2025-10-08\",\"pickupTime\":\"23:29\"}', '', 0, 0, '', 0, '2025-10-06 11:29:46', NULL, 0, 0.00, NULL),
(3, 'delivery', 'Aung', 'Htet', '2147483647', 'guide1@example.com', 123386340, 1, 123386340, '', NULL, NULL, '{\"service\":\"delivery\",\"origin\":\"Hock Lee Hotel & Residences\",\"originAddress\":\"\",\"destination\":\"Puteri Wing - Riverside Majestic Hotel\",\"destinationAddress\":\"\",\"dropoffDate\":\"2025-10-07\",\"dropoffTime\":\"21:33\",\"pickupDate\":\"2025-10-08\",\"pickupTime\":\"23:33\"}', '', 0, 0, '', 0, '2025-10-06 11:33:41', NULL, 0, 0.00, NULL),
(4, 'storage', 'Aung', 'Htet', '2147483647', 'visitor10@example.com', 123386340, 3, 123386340, '', NULL, NULL, '{\"service\":\"storage\",\"storageLocation\":\"EASE Storage Hub @ Plaza Aurora\",\"quantity\":\"3\",\"dropoffDate\":\"2025-10-06\",\"dropoffTime\":\"20:06\",\"pickupDate\":\"2025-10-06\",\"pickupTime\":\"22:06\"}', '', 0, 0, '', 0, '2025-10-06 11:36:36', NULL, 0, 0.00, NULL),
(5, 'delivery', 'Aung', 'Htet', '0', 'guide1@example.com', 123386340, 1, 123386340, '', NULL, NULL, '{\"service\":\"delivery\",\"origin\":\"Citadines Uplands Kuching\",\"originAddress\":\"\",\"destination\":\"Pullman Kuching\",\"destinationAddress\":\"\",\"dropoffDate\":\"2025-10-11\",\"dropoffTime\":\"18:35\",\"pickupDate\":\"2025-10-11\",\"pickupTime\":\"19:35\"}', '', 0, 0, '', 0, '2025-10-08 08:41:20', NULL, 0, 0.00, NULL),
(6, 'delivery', 'Aung', 'Htet', '0', 'admin1@example.com', 123386340, 3, 123386340, '', NULL, NULL, '{\"service\":\"delivery\",\"origin\":\"Ease Storage Hub @ Plaza Aurora\",\"originAddress\":\"\",\"destination\":\"The Spring Shopping Mall\",\"destinationAddress\":\"\",\"dropoffDate\":\"2025-10-10\",\"dropoffTime\":\"18:41\",\"pickupDate\":\"2025-10-11\",\"pickupTime\":\"20:41\"}', '', 1, 0, '', 0, '2025-10-08 08:43:24', '2026-04-07 16:19:57', 0, 0.00, '1'),
(7, 'delivery', 'Aung', 'Htet', '0', 'visitor10@example.com', 123386340, 3, 123386340, '', NULL, NULL, '{\"service\":\"delivery\",\"origin\":\"Citadines Uplands Kuching\",\"originAddress\":\"\",\"destination\":\"Kuching International Airport\",\"destinationAddress\":\"\",\"dropoffDate\":\"2025-10-08\",\"dropoffTime\":\"18:45\",\"pickupDate\":\"2025-10-08\",\"pickupTime\":\"20:44\"}', '', 0, 0, '', 0, '2025-10-08 08:48:49', NULL, 0, 0.00, NULL),
(8, 'delivery', 'Aung', 'Htet', 'MF2000456', 'visitor10@example.com', 123386340, 2, 123386340, '', NULL, NULL, '{\"service\":\"delivery\",\"origin\":\"Grand Margherita Hotel\",\"originAddress\":\"\",\"destination\":\"Kuching International Airport\",\"destinationAddress\":\"\",\"dropoffDate\":\"2025-10-10\",\"dropoffTime\":\"18:54\",\"pickupDate\":\"2025-10-11\",\"pickupTime\":\"20:54\"}', '', 0, 0, '', 0, '2025-10-08 08:55:26', NULL, 0, 0.00, NULL),
(9, 'delivery', 'Zi', 'Yang', 'asdfghjk56789', 'aung.prome@gmail.com', 123386340, 2, 123386340, '', NULL, NULL, '{\"service\":\"delivery\",\"origin\":\"Grand Margherita Hotel\",\"originAddress\":\"\",\"destination\":\"Plaza Merdeka Matang Jaya\",\"destinationAddress\":\"\",\"dropoffDate\":\"2025-10-10\",\"dropoffTime\":\"10:20\",\"pickupDate\":\"2025-10-11\",\"pickupTime\":\"12:20\"}', '', 0, 0, '', 0, '2025-10-09 00:23:02', NULL, 0, 0.00, NULL),
(10, 'delivery', 'Aung', 'Htet', '34567ghj', 'admin1@example.com', 123386340, 1, 123386340, '', NULL, NULL, '{\"service\":\"delivery\",\"origin\":\"Boulevard Shopping Mall\",\"originAddress\":\"\",\"destination\":\"Pullman Kuching\",\"destinationAddress\":\"\",\"dropoffDate\":\"2025-10-10\",\"dropoffTime\":\"11:43\",\"pickupDate\":\"2025-10-11\",\"pickupTime\":\"13:43\"}', '', 0, 0, '', 0, '2025-10-09 01:44:50', NULL, 0, 0.00, NULL),
(11, 'delivery', 'Aung', 'Htet', 'MF2000456', 'guide2@example.com', 123386340, 3, 123386340, '', NULL, NULL, '{\"service\":\"delivery\",\"origin\":\"Ease Storage Hub @ Plaza Aurora\",\"originAddress\":\"\",\"destination\":\"Hock Lee Hotel & Residences\",\"destinationAddress\":\"\",\"dropoffDate\":\"2025-10-10\",\"dropoffTime\":\"11:49\",\"pickupDate\":\"2025-10-11\",\"pickupTime\":\"13:49\"}', '', 0, 0, '', 0, '2025-10-09 01:52:10', NULL, 0, 0.00, NULL),
(12, 'delivery', 'Aung', 'Htet', 'lkadjfk-hc93', 'aung.prome@gmail.com', 123386340, 1, 123386340, '', NULL, NULL, '{\"service\":\"delivery\",\"origin\":\"Hilton Kuching Hotel\",\"originAddress\":\"\",\"destination\":\"Kuching International Airport\",\"destinationAddress\":\"\",\"dropoffDate\":\"2025-10-10\",\"dropoffTime\":\"13:12\",\"pickupDate\":\"2025-10-11\",\"pickupTime\":\"15:12\"}', '', 0, 0, '', 0, '2025-10-09 03:13:17', NULL, 0, 0.00, NULL),
(13, 'delivery', 'Aung', 'Htet', 'MF2000456', 'guide2@example.com', 123386340, 2, 123386340, '68f1c2d97a345_1760674521.jpg', NULL, NULL, '{\"service\":\"delivery\",\"origin\":\"The Waterfront Hotel Kuching\",\"originAddress\":\"\",\"destination\":\"Kuching International Airport\",\"destinationAddress\":\"\",\"dropoffDate\":\"2025-10-18\",\"dropoffTime\":\"13:51\",\"pickupDate\":\"2025-10-19\",\"pickupTime\":\"15:51\",\"quantity\":2,\"promoCode\":\"\",\"promoDiscount\":0,\"basePrice\":24,\"totalPrice\":48}', '', 0, 0, '', 0, '2025-10-17 04:15:21', NULL, 0, 0.00, NULL),
(14, 'delivery', 'Su myat', 'Thiri Htut', 'dfalc2222', 'aung.prome@gmail.com', 123386340, 2, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Delivery\",\n    \"Origin Location\": \"CityOne Megamall\",\n    \"Origin Address\": \"Null\",\n    \"Destination Location\": \"Grand Margherita Hotel\",\n    \"Destination Address\": \"Null\",\n    \"Drop-off DateTime\": \"2026-02-04 at 09:00\",\n    \"Pickup DateTime\": \"2026-02-05 at 07:00\",\n    \"Quantity\": \"1 item(s)\",\n    \"Base Price\": \"RM 24.00\",\n    \"Promo Code\": \"Null\",\n    \"Promo Discount\": \"Null\",\n    \"Total Price\": \"RM 24\",\n    \"Insurance Selected\": \"No\",\n    \"Insurance Amount\": \"RM 0.00\"\n}', '', 0, 24, 'pending', 0, '2026-02-03 10:18:56', NULL, 0, 0.00, NULL),
(15, 'delivery', 'Aung', 'Htet', 'dfghjkl;lkjhvbnm,', 'aung.prome@gmail.com', 123386340, 1, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Delivery\",\n    \"Origin Location\": \"The Spring Shopping Mall\",\n    \"Origin Address\": \"Null\",\n    \"Destination Location\": \"Ease Storage Hub @ Plaza Aurora\",\n    \"Destination Address\": \"Null\",\n    \"Drop-off DateTime\": \"2026-03-05 at 17:41\",\n    \"Pickup DateTime\": \"2026-03-06 at 07:00\",\n    \"Quantity\": \"1 item(s)\",\n    \"Base Price\": \"RM 24.00\",\n    \"Promo Code\": \"Null\",\n    \"Promo Discount\": \"Null\",\n    \"Total Price\": \"RM 24\",\n    \"Insurance Selected\": \"No\",\n    \"Insurance Amount\": \"RM 0.00\"\n}', '', 0, 24, 'pending', 0, '2026-03-05 07:12:28', NULL, 0, 0.00, NULL),
(16, 'delivery', 'Aung', 'Htet', '23456789021456789tyuiop[', 'aung.prome@gmail.com', 123386340, 2, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Delivery\",\n    \"Origin Location\": \"Sheraton Kuching Hotel\",\n    \"Origin Address\": \"Null\",\n    \"Destination Location\": \"CityOne Megamall\",\n    \"Destination Address\": \"Null\",\n    \"Drop-off DateTime\": \"2026-03-05 at 17:48\",\n    \"Pickup DateTime\": \"2026-03-06 at 07:00\",\n    \"Quantity\": \"1 item(s)\",\n    \"Base Price\": \"RM 24.00\",\n    \"Promo Code\": \"Null\",\n    \"Promo Discount\": \"Null\",\n    \"Total Price\": \"RM 24\",\n    \"Insurance Selected\": \"No\",\n    \"Insurance Amount\": \"RM 0.00\"\n}', '', 0, 24, 'pending', 0, '2026-03-05 07:19:18', NULL, 0, 0.00, NULL),
(17, 'storage', 'Aung', 'Htet', 'dfghjkl;lkjhvbnm,', 'aung.prome@gmail.com', 123386340, 1, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Storage\",\n    \"Storage Location\": \"EASE Storage Hub @ Plaza Aurora\",\n    \"Drop-off DateTime\": \"2026-03-22 at 16:14\",\n    \"Pickup DateTime\": \"2026-03-22 at 18:14\",\n    \"Quantity\": \"3 item(s)\",\n    \"Base Price\": \"RM 18.00\",\n    \"Promo Code\": \"Null\",\n    \"Promo Discount\": \"Null\",\n    \"Total Price\": \"RM 54\",\n    \"Insurance Selected\": \"No\",\n    \"Insurance Amount\": \"RM 0.00\"\n}', '', 0, 54, 'pending', 0, '2026-03-22 07:45:23', NULL, 0, 0.00, NULL),
(18, 'delivery', 'Aung', 'Htet', '23456789021456789tyuiop[', 'aung.prome@gmail.com', 123386340, 2, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Delivery\",\n    \"Origin Location\": \"Sheraton Kuching Hotel\",\n    \"Origin Address\": \"Null\",\n    \"Destination Location\": \"AEON Mall Kuching Central\",\n    \"Destination Address\": \"Null\",\n    \"Drop-off DateTime\": \"2026-03-22 at 18:14\",\n    \"Pickup DateTime\": \"2026-03-23 at 07:00\",\n    \"Quantity\": \"1 item(s)\",\n    \"Base Price\": \"RM 24.00\",\n    \"Promo Code\": \"Null\",\n    \"Promo Discount\": \"Null\",\n    \"Total Price\": \"RM 24\",\n    \"Insurance Selected\": \"No\",\n    \"Insurance Amount\": \"RM 0.00\"\n}', '', 0, 24, 'pending', 0, '2026-03-22 07:48:19', NULL, 0, 0.00, NULL),
(19, 'delivery', 'Aung', 'Htet', 'asdfghjk56789', 'aung.prome@gmail.com', 123386340, 3, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Delivery\",\n    \"Origin Location\": \"Plaza Merdeka Matang Jaya\",\n    \"Origin Address\": \"Null\",\n    \"Destination Location\": \"Other Location\",\n    \"Destination Address\": \"Kenny Hills\",\n    \"Drop-off DateTime\": \"2026-03-22 at 18:19\",\n    \"Pickup DateTime\": \"2026-03-23 at 07:00\",\n    \"Quantity\": \"1 item(s)\",\n    \"Base Price\": \"RM 24.00\",\n    \"Promo Code\": \"Null\",\n    \"Promo Discount\": \"Null\",\n    \"Total Price\": \"RM 24\",\n    \"Insurance Selected\": \"No\",\n    \"Insurance Amount\": \"RM 0.00\"\n}', '', 0, 24, 'pending', 0, '2026-03-22 07:50:13', NULL, 0, 0.00, NULL),
(20, 'delivery', 'Aung', 'Htet', 'asdfghjk56789', 'aung.prome@gmail.com', 123386340, 1, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Delivery\",\n    \"Origin Location\": \"Grand Margherita Hotel\",\n    \"Origin Address\": \"Null\",\n    \"Destination Location\": \"Boulevard Shopping Mall\",\n    \"Destination Address\": \"Null\",\n    \"Drop-off DateTime\": \"2026-03-22 at 18:23\",\n    \"Pickup DateTime\": \"2026-03-23 at 07:00\",\n    \"Quantity\": \"1 item(s)\",\n    \"Base Price\": \"RM 24.00\",\n    \"Promo Code\": \"Null\",\n    \"Promo Discount\": \"Null\",\n    \"Total Price\": \"RM 24\",\n    \"Insurance Selected\": \"No\",\n    \"Insurance Amount\": \"RM 0.00\"\n}', '', 0, 24, 'pending', 0, '2026-03-22 07:53:20', NULL, 0, 0.00, NULL),
(21, 'delivery', 'Aung', 'Htet', 'dfghjkl;lkjhvbnm,', 'aung.prome@gmail.com', 123386340, 1, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Delivery\",\n    \"Origin Location\": \"Puteri Wing - Riverside Majestic Hotel\",\n    \"Origin Address\": \"Null\",\n    \"Destination Location\": \"Boulevard Shopping Mall\",\n    \"Destination Address\": \"Null\",\n    \"Drop-off DateTime\": \"2026-03-22 at 18:24\",\n    \"Pickup DateTime\": \"2026-03-23 at 07:00\",\n    \"Quantity\": \"1 item(s)\",\n    \"Base Price\": \"RM 24.00\",\n    \"Promo Code\": \"Null\",\n    \"Promo Discount\": \"Null\",\n    \"Total Price\": \"RM 24\",\n    \"Insurance Selected\": \"No\",\n    \"Insurance Amount\": \"RM 0.00\"\n}', '', 0, 24, 'pending', 0, '2026-03-22 07:55:08', NULL, 0, 0.00, NULL),
(22, 'delivery', 'Aung', 'Htet', '234567890-=-0987', 'aung.prome@gmail.com', 123386340, 1, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Delivery\",\n    \"Origin Location\": \"Hock Lee Hotel & Residences\",\n    \"Origin Address\": \"Null\",\n    \"Destination Location\": \"Kuching International Airport\",\n    \"Destination Address\": \"Null\",\n    \"Drop-off DateTime\": \"2026-03-22 at 18:25\",\n    \"Pickup DateTime\": \"2026-03-23 at 07:00\",\n    \"Quantity\": \"3 item(s)\",\n    \"Base Price\": \"RM 24.00\",\n    \"Promo Code\": \"Null\",\n    \"Promo Discount\": \"Null\",\n    \"Total Price\": \"RM 72\",\n    \"Insurance Selected\": \"No\",\n    \"Insurance Amount\": \"RM 0.00\"\n}', '', 0, 72, 'pending', 0, '2026-03-22 07:56:07', NULL, 0, 0.00, NULL),
(23, 'delivery', 'Aung', 'Htet', 'MF2000456', 'aung.prome@gmail.com', 123386340, 1, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Delivery\",\n    \"Origin Location\": \"Ease Storage Hub @ Plaza Aurora\",\n    \"Origin Address\": \"Null\",\n    \"Destination Location\": \"Hilton Kuching Hotel\",\n    \"Destination Address\": \"Null\",\n    \"Drop-off DateTime\": \"2026-03-22 at 18:29\",\n    \"Pickup DateTime\": \"2026-03-23 at 07:00\",\n    \"Quantity\": \"2 item(s)\",\n    \"Base Price\": \"RM 24.00\",\n    \"Promo Code\": \"Null\",\n    \"Promo Discount\": \"Null\",\n    \"Total Price\": \"RM 48\",\n    \"Insurance Selected\": \"No\",\n    \"Insurance Amount\": \"RM 0.00\"\n}', '', 0, 48, 'pending', 0, '2026-03-22 07:59:56', NULL, 0, 0.00, NULL),
(24, 'delivery', 'Aung', 'Htet', '234567890-=-0987', 'aung.prome@gmail.com', 123386340, 2, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Delivery\",\n    \"Origin Location\": \"Ease Storage Hub @ Plaza Aurora\",\n    \"Origin Address\": \"Null\",\n    \"Destination Location\": \"AEON Mall Kuching Central\",\n    \"Destination Address\": \"Null\",\n    \"Drop-off DateTime\": \"2026-03-24 at 10:20\",\n    \"Pickup DateTime\": \"2026-03-24 at 12:20\",\n    \"Quantity\": \"2 item(s)\",\n    \"Base Price\": \"RM 24.00\",\n    \"Promo Code\": \"SUMYAT\",\n    \"Promo Discount\": \"11RM\",\n    \"Total Price\": \"RM 37\",\n    \"Insurance Selected\": \"No\",\n    \"Insurance Amount\": \"RM 0.00\"\n}', 'SUMYAT', 0, 37, 'pending', 0, '2026-03-23 23:54:01', NULL, 0, 0.00, NULL),
(25, 'delivery', 'Aung', 'Htet', 'dfghjkl;lkjhvbnm,', 'aung.prome@gmail.com', 123386340, 1, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Delivery\",\n    \"Origin Location\": \"Puteri Wing - Riverside Majestic Hotel\",\n    \"Origin Address\": \"Null\",\n    \"Destination Location\": \"Puteri Wing - Riverside Majestic Hotel\",\n    \"Destination Address\": \"Null\",\n    \"Drop-off DateTime\": \"2026-03-24 at 10:32\",\n    \"Pickup DateTime\": \"2026-03-24 at 12:32\",\n    \"Quantity\": \"3 item(s)\",\n    \"Base Price\": \"RM 24.00\",\n    \"Promo Code\": \"SUMYAT\",\n    \"Promo Discount\": \"11RM\",\n    \"Total Price\": \"RM 61\",\n    \"Insurance Selected\": \"Yes\",\n    \"Insurance Amount\": \"RM 9.00\"\n}', 'SUMYAT', 0, 61, 'pending', 0, '2026-03-24 00:02:49', NULL, 1, 9.00, NULL),
(26, 'storage', 'Aung', 'Htet', 'asdfghjk56789', 'aung.prome@gmail.com', 123386340, 1, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Storage\",\n    \"Storage Location\": \"EASE Storage Hub @ Plaza Aurora\",\n    \"Drop-off DateTime\": \"2026-03-24 at 08:33\",\n    \"Pickup DateTime\": \"2026-03-24 at 10:33\",\n    \"Quantity\": \"4 item(s)\",\n    \"Base Price\": \"RM 18.00\",\n    \"Promo Code\": \"Null\",\n    \"Promo Discount\": \"Null\",\n    \"Total Price\": \"RM 72\",\n    \"Insurance Selected\": \"Yes\",\n    \"Insurance Amount\": \"RM 12.00\"\n}', '', 0, 72, 'pending', 0, '2026-03-24 00:03:39', NULL, 1, 12.00, NULL),
(27, 'storage', 'Aung', 'Htet', 'dfghjkl;lkjhvbnm,', 'aung.prome@gmail.com', 123386340, 1, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Storage\",\n    \"Storage Location\": \"EASE Storage Hub @ Plaza Aurora\",\n    \"Drop-off DateTime\": \"2026-03-24 at 08:48\",\n    \"Pickup DateTime\": \"2026-03-24 at 10:48\",\n    \"Quantity\": \"4 item(s)\",\n    \"Base Price\": \"RM 18.00\",\n    \"Promo Code\": \"Null\",\n    \"Promo Discount\": \"Null\",\n    \"Total Price\": \"RM 72\",\n    \"Insurance Selected\": \"Yes\",\n    \"Insurance Amount\": \"RM 12.00\"\n}', '', 0, 72, 'pending', 0, '2026-03-24 00:18:38', NULL, 1, 12.00, NULL),
(28, 'storage', 'Aung', 'Htet', 'asdfghjk56789', 'aung.prome@gmail.com', 123386340, 3, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Storage\",\n    \"Storage Location\": \"EASE Storage Hub @ Plaza Aurora\",\n    \"Drop-off DateTime\": \"2026-03-24 at 08:48\",\n    \"Pickup DateTime\": \"2026-03-24 at 10:48\",\n    \"Quantity\": \"4 item(s)\",\n    \"Base Price\": \"RM 18.00\",\n    \"Promo Code\": \"Null\",\n    \"Promo Discount\": \"Null\",\n    \"Total Price\": \"RM 84\",\n    \"Insurance Selected\": \"Yes\",\n    \"Insurance Amount\": \"RM 12.00\"\n}', '', 0, 84, 'pending', 0, '2026-03-24 00:26:24', NULL, 1, 12.00, NULL),
(29, 'delivery', 'Aung', 'Htet', '23456789021456789', 'aung.prome@gmail.com', 123386340, 1, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Delivery\",\n    \"Origin Location\": \"Hilton Kuching Hotel\",\n    \"Origin Address\": \"Null\",\n    \"Destination Location\": \"UCSI Hotel Kuching\",\n    \"Destination Address\": \"Null\",\n    \"Drop-off DateTime\": \"2026-03-24 at 10:56\",\n    \"Pickup DateTime\": \"2026-03-24 at 12:56\",\n    \"Quantity\": \"5 item(s)\",\n    \"Base Price\": \"RM 24.00\",\n    \"Promo Code\": \"SUMYAT\",\n    \"Promo Discount\": \"11RM\",\n    \"Total Price\": \"RM 124\",\n    \"Insurance Selected\": \"Yes\",\n    \"Insurance Amount\": \"RM 15.00\"\n}', 'SUMYAT', 0, 124, 'pending', 0, '2026-03-24 00:27:02', NULL, 1, 15.00, NULL),
(30, 'delivery', 'Aung', 'Htet', '23456789021456789', 'aung.prome@gmail.com', 123386340, 1, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Delivery\",\n    \"Origin Location\": \"Ease Storage Hub @ Plaza Aurora\",\n    \"Origin Address\": \"Null\",\n    \"Destination Location\": \"Hilton Kuching Hotel\",\n    \"Destination Address\": \"Null\",\n    \"Drop-off DateTime\": \"2026-03-24 at 11:03\",\n    \"Pickup DateTime\": \"2026-03-24 at 13:03\",\n    \"Quantity\": \"3 item(s)\",\n    \"Base Price\": \"RM 24.00\",\n    \"Promo Code\": \"SUMYAT\",\n    \"Promo Discount\": \"11RM\",\n    \"Total Price\": \"RM 70\",\n    \"Insurance Selected\": \"Yes\",\n    \"Insurance Amount\": \"RM 9.00\"\n}', 'SUMYAT', 0, 70, 'pending', 0, '2026-03-24 00:34:27', NULL, 1, 9.00, NULL),
(31, 'storage', 'Aung', 'Htet', 'dfghjkl;lkjhvbnm,', 'aung.prome@gmail.com', 123386340, 2, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Storage\",\n    \"Storage Location\": \"EASE Storage Hub @ Plaza Aurora\",\n    \"Drop-off DateTime\": \"2026-03-24 at 09:05\",\n    \"Pickup DateTime\": \"2026-03-24 at 11:05\",\n    \"Quantity\": \"3 item(s)\",\n    \"Base Price\": \"RM 18.00\",\n    \"Promo Code\": \"SUMYAT\",\n    \"Promo Discount\": \"11RM\",\n    \"Total Price\": \"RM 43\",\n    \"Insurance Selected\": \"No\",\n    \"Insurance Amount\": \"RM 0.00\"\n}', 'SUMYAT', 0, 43, 'pending', 0, '2026-03-24 00:35:39', NULL, 0, 0.00, NULL),
(32, 'storage', 'Aung', 'Htet', '234567890-=-0987', 'aung.prome@gmail.com', 123386340, 2, 123386340, '', 0, NULL, '{\n    \"Service Type\": \"Storage\",\n    \"Storage Location\": \"EASE Storage Hub @ Plaza Aurora\",\n    \"Drop-off DateTime\": \"2026-03-24 at 09:58\",\n    \"Pickup DateTime\": \"2026-03-24 at 11:58\",\n    \"Quantity\": \"3 item(s)\",\n    \"Base Price\": \"RM 18.00\",\n    \"Promo Code\": \"SUMYAT\",\n    \"Promo Discount\": \"11RM\",\n    \"Total Price\": \"RM 52\",\n    \"Insurance Selected\": \"Yes\",\n    \"Insurance Amount\": \"RM 9.00\"\n}', 'SUMYAT', 0, 52, 'pending', 0, '2026-03-24 01:28:23', NULL, 1, 9.00, NULL),
(33, 'delivery', 'John ', 'Doe', '099023499430', 'johndoe231231@gmailcom', 149932932, 3, 149932932, '', 0, NULL, '{\n    \"Service Type\": \"Delivery\",\n    \"Origin Location\": \"Ease Storage Hub @ Plaza Aurora\",\n    \"Origin Address\": \"Null\",\n    \"Destination Location\": \"Ease Storage Hub @ Plaza Aurora\",\n    \"Destination Address\": \"Null\",\n    \"Drop-off DateTime\": \"2026-03-24 at 13:25\",\n    \"Pickup DateTime\": \"2026-03-24 at 15:25\",\n    \"Quantity\": \"3 item(s)\",\n    \"Base Price\": \"RM 27.00\",\n    \"Promo Code\": \"Null\",\n    \"Promo Discount\": \"Null\",\n    \"Total Price\": \"RM 90\",\n    \"Insurance Selected\": \"Yes\",\n    \"Insurance Amount\": \"RM 9.00\"\n}', '', 0, 90, 'pending', 0, '2026-03-24 03:44:38', NULL, 1, 9.00, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
