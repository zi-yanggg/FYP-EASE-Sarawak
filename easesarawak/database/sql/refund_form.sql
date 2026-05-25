-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2026 at 06:44 AM
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
-- Table structure for table `refund_form`
--

CREATE TABLE `refund_form` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `date_of_purchase` date NOT NULL,
  `service_type` enum('Town Delivery','Luggage Storage') NOT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_holder_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `status_progress` tinyint(1) NOT NULL DEFAULT 0,
  `status_updated_by` int(11) DEFAULT NULL,
  `status_updated_at` datetime DEFAULT NULL,
  `reason_for_refund` text DEFAULT NULL,
  `declaration` tinyint(1) NOT NULL,
  `pdf_path` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `refund_form`
--

INSERT INTO `refund_form` (`id`, `full_name`, `email`, `phone_number`, `order_id`, `date_of_purchase`, `service_type`, `bank_name`, `account_holder_name`, `account_number`, `status_progress`, `status_updated_by`, `status_updated_at`, `reason_for_refund`, `declaration`, `pdf_path`, `created_at`) VALUES
(1, 'chok jostin', 'jostinchok@gmail.com', '32423', '23', '2026-04-08', 'Town Delivery', 'we', 'swe', '16546', 0, NULL, NULL, 'erer', 1, NULL, '2026-04-08 16:48:38'),
(2, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '25', '2026-04-03', 'Town Delivery', 'Maybank', 'jostin', '123456', 0, NULL, NULL, 'Refund', 1, NULL, '2026-04-08 16:51:12'),
(3, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '12', '2026-04-03', 'Luggage Storage', 'Maybank', 'jostin', '123456', 0, NULL, NULL, 'Refund', 1, 'uploads/refunds/refund_3_20260408_172603.pdf', '2026-04-08 17:26:03'),
(4, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '22', '2026-04-03', 'Luggage Storage', 'Maybank', 'jostin', '123456', 0, NULL, NULL, 'Refund', 1, 'uploads/refunds/refund_4_20260408_185709.pdf', '2026-04-08 18:57:09'),
(5, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '23', '2026-04-03', 'Luggage Storage', 'Maybank', 'jostin', '123456', 0, NULL, NULL, 'Refund', 1, 'http://easesarawak.test/uploads/refunds/refund_5_20260408_190359.pdf', '2026-04-08 19:03:59'),
(6, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '25', '2026-04-02', 'Town Delivery', 'Maybank', 'jostin', '123456', 0, NULL, NULL, 'ea', 1, 'http://easesarawak.test/uploads/refunds/refund_6_20260408_191021.pdf', '2026-04-08 19:10:21'),
(7, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '23', '2026-04-03', 'Luggage Storage', 'Maybank', 'jostin', '123456', 0, NULL, NULL, 'redfds', 1, 'http://easesarawak.test/refund/view/7', '2026-04-08 19:26:32'),
(8, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '12', '2026-04-03', 'Town Delivery', 'Maybank', 'jostin', '123456', 0, NULL, NULL, 'fdfdsf', 1, 'http://easesarawak.test/refund/view/8', '2026-04-09 09:44:09'),
(9, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '12', '2026-04-04', 'Town Delivery', 'Maybank', 'jostin', '123456', 0, NULL, NULL, 'refund', 1, 'http://easesarawak.test/refund/view/9', '2026-04-09 13:25:58'),
(10, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '12', '2026-04-02', 'Luggage Storage', 'Maybank', 'jostin', '123456', 0, NULL, NULL, 'Refund 2', 1, 'http://easesarawak.test/refund/view/10', '2026-04-09 13:26:50'),
(11, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '12', '2026-04-10', 'Luggage Storage', 'Maybank', 'jostin', '123456', 0, NULL, NULL, 'reason3', 1, 'http://easesarawak.test/refund/view/11', '2026-04-16 15:13:15'),
(12, 'chok jostin', 'jostinchok@gmail.com', '0168491315', '23', '2026-04-09', 'Town Delivery', 'Maybank', 'jostin', '123456wefwf', 0, NULL, NULL, 'efef', 1, 'http://easesarawak.test/refund/view/12', '2026-04-16 15:19:02'),
(13, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '12', '2026-04-07', 'Luggage Storage', 'Maybank', 'sdsad', '123456', 0, NULL, NULL, '21313123', 1, 'http://easesarawak.test/refund/view/13', '2026-04-16 15:20:06'),
(14, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '12', '2026-04-01', 'Town Delivery', 'Maybank', 'jostin', '123456', 0, NULL, NULL, '232131', 1, NULL, '2026-04-16 15:30:11'),
(15, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '12', '2026-04-03', 'Luggage Storage', 'Maybank', 'jostin', '123456', 0, NULL, NULL, '2313', 1, NULL, '2026-04-16 15:32:05'),
(16, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '12', '2026-04-09', 'Luggage Storage', 'Maybank', 'jostin', '123456213123', 2, 1, '2026-04-25 12:05:55', '', 1, NULL, '2026-04-16 15:33:39'),
(17, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '1', '2026-04-09', 'Luggage Storage', 'Maybank', 'jostin', '123456', 1, 1, '2026-04-25 11:49:10', 'sdasdasd', 1, 'http://easesarawak.test/refund/view/17', '2026-04-16 15:37:10'),
(18, 'JKJ', 'jostinchok@gmail.com', '0168491315', '18', '2026-04-16', 'Luggage Storage', 'Maybank', 'jostin', '123456', 1, 1, '2026-04-25 16:36:16', 'ewrewrqer', 1, 'http://easesarawak.test/refund/view/18', '2026-04-25 11:50:03'),
(19, 'Jostin Chok Yaw Seng', 'jostinchok@gmail.com', '0168491315', '1', '2026-04-16', 'Luggage Storage', 'Maybank', 'jostin', '123456', 1, 1, '2026-04-30 07:10:07', 'adadasa', 1, 'http://easesarawak.test/refund/view/19', '2026-04-25 16:37:26'),
(20, 'ben dover', 'jostinchok@gmail.com', '0168491315', '23', '2026-05-06', 'Luggage Storage', 'Maybank', 'jostin', '123456', 2, 1, '2026-05-08 12:29:34', 'luaggage is stuck', 1, 'http://easesarawak.test/refund/view/20', '2026-05-05 08:11:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `refund_form`
--
ALTER TABLE `refund_form`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `refund_form`
--
ALTER TABLE `refund_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
