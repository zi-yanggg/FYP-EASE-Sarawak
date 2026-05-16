-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 18, 2026 at 06:40 AM
-- Server version: 8.0.43
-- PHP Version: 8.4.20

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
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `msg_id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `msg` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'New',
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `created_date` datetime NOT NULL,
  `modified_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`msg_id`, `email`, `phone`, `subject`, `msg`, `status`, `is_deleted`, `created_date`, `modified_date`) VALUES
(1, 'limziyang2003@gmail.com', '0123456789', 'Feedback', 'Love their services!', 'read', 0, '2025-12-12 04:57:22', NULL),
(2, 'cursor1960@outlook.com', '0123908273', 'Feedback', 'I really appreciate the services provided by your team. The process was smooth and the instructions were clear, which made everything much easier for me. The customer support was also responsive and friendly. Overall, I am satisfied with the experience an', 'read', 0, '2025-12-12 06:09:08', NULL),
(3, 'limziyang2003@gmail.com', '0123456789', 'service', '', 'read', 0, '0000-00-00 00:00:00', NULL),
(4, 'limziyang2003@gmail.com', '0123456789', 'service', 'service', 'read', 0, '2026-03-23 18:14:13', NULL),
(5, 'johndoe231231@gmailcom', '0198763456', 'Request Refund', 'I want my money back!', 'read', 0, '2026-04-07 14:58:52', NULL),
(6, 'peterpan@hotmail.com', '0123456789', 'Order', 'how to book a service?', 'read', 0, '2026-04-08 07:56:47', NULL),
(7, 'kingston@gmail.com', '0123456789', 'Troubleshoot', 'this is not functioning!', 'read', 0, '2026-04-08 08:01:35', NULL),
(8, 'shrek67@gmail.com', '0198765423', 'test', 'tehioadonfasdfoau0sdf fasdfa', 'read', 0, '2026-04-08 08:12:39', NULL),
(9, 'limziyang2003@gmail.com', '0123456789', 'Feedback', 'service', 'read', 0, '2026-04-08 08:21:27', NULL),
(10, 'monkeyape232@gmail.com', '0145678902', 'Travel', 'i want travel', 'read', 0, '2026-04-08 08:38:21', NULL),
(11, 'evelyn911@gmail.com', '0138031269', 'book a service', 'one luggage at kch international airport on 4th May', 'read', 0, '2026-04-08 09:23:25', NULL),
(12, 'limziyang2003@gmail.com', '0198765423', 'Request Refund', 'I want my money back!', 'read', 0, '2026-04-08 09:27:08', NULL),
(13, 'limziyang2003@gmail.com', '0198765423', 'Request Refund', 'i want travel', 'read', 0, '2026-04-09 13:10:12', NULL),
(14, 'limziyang2003@gmail.com', '0145678902', 'Order', 'tehioadonfasdfoau0sdf fasdfa', 'read', 0, '2026-04-09 13:13:10', NULL),
(15, 'ngsb71@yahoo.com', '01342334223', 'contact', 'how to contact you?', 'new', 0, '2026-04-09 14:19:04', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`msg_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `msg_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
