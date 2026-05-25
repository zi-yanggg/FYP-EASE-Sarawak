-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2026 at 08:20 AM
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
  `promo_id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `validation_date` datetime NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_date` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_date` datetime DEFAULT NULL,
  `expired_date` datetime NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promo_code`
--

INSERT INTO `promo_code` (`promo_id`, `code`, `validation_date`, `created_date`, `modified_date`, `deleted_date`, `expired_date`, `is_deleted`) VALUES
(3, 'EASE01', '2025-10-17 06:40:02', '2025-10-17 12:40:16', NULL, NULL, '2025-10-17 06:40:02', 0),
(4, 'EASE01', '2025-10-17 06:40:02', '2025-10-17 12:40:21', NULL, NULL, '2025-10-17 06:40:02', 0),
(5, 'EASE02', '2025-10-17 06:40:31', '2025-10-17 12:40:38', NULL, NULL, '2025-10-17 06:40:31', 0),
(6, 'EASE02', '2025-10-17 06:40:31', '2025-10-17 12:40:40', NULL, NULL, '2025-10-17 06:40:31', 0),
(7, 'EASE03', '2025-10-17 06:40:53', '2025-10-17 12:41:03', NULL, NULL, '2025-10-17 06:40:53', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `promo_code`
--
ALTER TABLE `promo_code`
  ADD PRIMARY KEY (`promo_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `promo_code`
--
ALTER TABLE `promo_code`
  MODIFY `promo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
