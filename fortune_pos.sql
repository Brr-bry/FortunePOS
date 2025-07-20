-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2025 at 10:03 PM
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
-- Database: `fortune_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `amount_due` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amount_received` decimal(10,2) NOT NULL,
  `change_amount` decimal(10,2) DEFAULT 0.00,
  `payment_method` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `total`, `created_at`, `amount_due`, `amount_received`, `change_amount`, `payment_method`) VALUES
(1, 480.00, '2025-07-20 19:20:30', 528.00, 1223.00, 695.00, 'Cash'),
(2, 2498.00, '2025-07-20 19:21:05', 2747.80, 2747.80, 0.00, 'GCash'),
(3, 5145.00, '2025-07-20 19:21:33', 5659.50, 5700.00, 40.50, 'Cash'),
(4, 250.00, '2025-07-20 19:30:58', 275.00, 275.00, 0.00, 'GCash'),
(5, 250.00, '2025-07-20 19:32:08', 275.00, 275.00, 0.00, 'GCash'),
(6, 230.00, '2025-07-20 19:32:44', 253.00, 300.00, 47.00, 'Cash'),
(7, 460.00, '2025-07-20 19:33:12', 506.00, 506.00, 0.00, 'GCash'),
(8, 230.00, '2025-07-20 19:36:42', 253.00, 253.00, 0.00, 'GCash'),
(9, 4497.00, '2025-07-20 19:37:41', 4946.70, 4946.70, 0.00, 'GCash'),
(10, 1499.00, '2025-07-20 19:38:00', 1648.90, 2000.00, 351.10, 'Cash'),
(11, 230.00, '2025-07-20 19:50:02', 253.00, 500.00, 247.00, 'Cash'),
(12, 230.00, '2025-07-20 19:51:33', 253.00, 253.00, 0.00, 'GCash'),
(13, 516.00, '2025-07-20 19:52:09', 567.60, 1000.00, 432.40, 'Cash'),
(14, 1499.00, '2025-07-20 19:52:31', 1648.90, 2000.00, 351.10, 'Cash'),
(15, 3458.00, '2025-07-20 19:54:01', 3803.80, 4000.00, 196.20, 'Cash'),
(16, 1729.00, '2025-07-20 19:54:21', 1901.90, 1901.90, 0.00, 'GCash'),
(17, 8990.00, '2025-07-20 19:54:52', 9889.00, 10000.00, 111.00, 'Cash'),
(18, 8393.00, '2025-07-20 19:55:14', 9232.30, 10000.00, 767.70, 'Cash'),
(19, 1499.00, '2025-07-20 19:55:38', 1648.90, 2000.00, 351.10, 'Cash'),
(20, 4746.00, '2025-07-20 19:56:46', 5220.60, 5220.60, 0.00, 'GCash'),
(21, 3397.00, '2025-07-20 19:57:07', 3736.70, 3800.00, 63.30, 'Cash'),
(22, 2998.00, '2025-07-20 19:57:20', 3297.80, 4000.00, 702.20, 'Cash'),
(23, 2998.00, '2025-07-20 19:57:39', 3297.80, 3500.00, 202.20, 'Cash'),
(24, 1898.00, '2025-07-20 19:57:57', 2087.80, 2100.00, 12.20, 'Cash'),
(25, 1499.00, '2025-07-20 19:58:28', 1648.90, 1648.90, 0.00, 'GCash'),
(26, 4396.00, '2025-07-20 19:58:48', 4835.60, 5000.00, 164.40, 'Cash'),
(27, 4045.00, '2025-07-20 19:59:03', 4449.50, 4500.00, 50.50, 'Cash'),
(28, 3896.00, '2025-07-20 20:01:01', 4285.60, 4300.00, 14.40, 'Cash'),
(29, 1499.00, '2025-07-20 20:01:17', 1648.90, 1700.00, 51.10, 'Cash'),
(30, 3198.00, '2025-07-20 20:01:35', 3517.80, 3517.80, 0.00, 'Cash');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
