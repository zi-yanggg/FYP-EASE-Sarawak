-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2025 at 12:52 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `promo_code`
--

CREATE TABLE `promo_code` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(100) NOT NULL,
  `discount_type` varchar(20) NOT NULL DEFAULT 'percentage',
  `discount_percentage` tinyint(3) UNSIGNED DEFAULT 0,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `validation_date` datetime NOT NULL,
  `expired_date` datetime NOT NULL,
  `max_uses` int(10) UNSIGNED DEFAULT NULL COMMENT 'NULL = unlimited uses',
  `used_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_date` datetime NOT NULL,
  `modified_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promo_code`
--

INSERT INTO `promo_code` (`id`, `code`, `discount_type`, `discount_percentage`, `discount_amount`, `validation_date`, `expired_date`, `is_deleted`, `created_date`, `modified_date`) VALUES
(1, '12345', 'percentage', 0, 0.00, '2025-11-02 22:04:50', '2025-11-30 22:04:50', 1, '2025-11-03 15:04:50', '2025-11-04 11:04:33'),
(2, '67890', 'percentage', 0, 0.00, '2025-11-03 19:06:58', '2025-11-30 19:06:58', 1, '2025-11-04 12:06:58', '2025-11-04 11:07:44'),
(3, '321321', 'percentage', 50, 0.00, '2025-11-03 19:19:00', '2025-11-30 19:19:00', 0, '2025-11-04 11:19:18', NULL),
(4, '212121', 'percentage', 15, 0.00, '2025-11-03 19:20:00', '2025-11-30 19:20:00', 0, '2025-11-04 11:20:18', '2025-11-19 01:38:08'),
(5, 'Brian', 'amount', 0, 7.00, '2025-11-03 12:00:00', '2025-11-30 12:00:00', 0, '2025-11-05 06:08:06', '2025-11-19 03:17:40'),
(6, 'Allanpromo', 'percentage', 75, 0.00, '2025-11-01 12:00:00', '2025-11-30 12:00:00', 0, '2025-11-05 08:59:24', NULL),
(7, '1234567890', 'percentage', 0, 0.00, '2025-11-19 12:00:00', '2025-12-31 12:00:00', 1, '2025-11-19 03:07:59', '2025-11-19 03:16:49'),
(8, '0987654321', 'amount', 0, 5.00, '2025-11-19 12:00:00', '2025-12-31 12:00:00', 0, '2025-11-19 03:16:39', NULL),
(9, 'abcde', 'amount', 0, 8.00, '2025-11-20 12:00:00', '2025-12-31 12:00:00', 0, '2025-11-20 04:07:22', NULL),
(10, 'abcd', 'amount', 0, 8.00, '2025-11-19 12:00:00', '2025-12-31 12:00:00', 0, '2025-11-20 04:08:29', NULL),
(11, 'asdf', 'amount', 0, 7.00, '2025-11-19 12:00:00', '2025-12-31 12:00:00', 0, '2025-11-20 05:22:13', NULL),
(12, '11111', 'amount', 0, 11.00, '2025-11-20 12:00:00', '2025-12-31 12:00:00', 0, '2025-11-21 11:11:43', NULL),
(13, '22222', 'percentage', 25, 0.00, '2025-11-20 12:00:00', '2025-12-31 12:00:00', 0, '2025-11-21 11:12:08', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `promo_code`
--
ALTER TABLE `promo_code`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `promo_code`
--
ALTER TABLE `promo_code`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
