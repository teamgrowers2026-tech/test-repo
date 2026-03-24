-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2025 at 09:46 PM
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
-- Database: `inventorydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `capital`
--

CREATE TABLE `capital` (
  `id` int(11) NOT NULL,
  `capital_amount` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `capital`
--

INSERT INTO `capital` (`id`, `capital_amount`, `start_date`, `user_id`) VALUES
(1, 30000.00, '2025-12-06', 3);

-- --------------------------------------------------------

--
-- Table structure for table `daily_sales`
--

CREATE TABLE `daily_sales` (
  `id` int(11) NOT NULL,
  `sale_date` date NOT NULL,
  `total_sales` decimal(10,2) DEFAULT 0.00,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_sales`
--

INSERT INTO `daily_sales` (`id`, `sale_date`, `total_sales`, `user_id`) VALUES
(1, '2025-12-04', 0.00, 3);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `expense_label` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL DEFAULT curdate(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `expense_label`, `amount`, `expense_date`, `created_at`, `user_id`) VALUES
(1, 'Labor', 500.00, '2025-12-14', '2025-12-14 00:43:35', 3);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_type` varchar(100) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `volume_quantity` varchar(100) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_type`, `product_name`, `volume_quantity`, `expiry_date`, `price`, `created_at`, `user_id`) VALUES
(2, 'Food and Drinks', 'Coca-cola', '199', '2029-11-13', 20.00, '2025-11-13 05:44:32', 3),
(3, 'Food and Drinks', 'MIlo', '200', '2029-11-13', 10.00, '2025-11-13 09:08:35', 3),
(4, 'Toiletries and Personal Care', 'Safeguard White', '199', '2029-11-13', 28.00, '2025-11-13 09:09:26', 3),
(5, 'Other Items', 'Marlboro Blast', '199', '2029-11-13', 10.00, '2025-11-13 09:10:19', 3),
(6, 'Household and Cleaning Supplies', 'Ariel Twin-pack', '198', '2029-11-13', 13.00, '2025-11-13 09:28:10', 3),
(7, 'Food and Drinks', 'Sky Flakes', '500', '2029-12-04', 8.00, '2025-12-04 09:42:46', 3),
(8, 'Food and Drinks', 'Bread Stix', '200', '2029-11-04', 28.00, '2025-12-04 11:51:52', 3),
(9, 'Food and Drinks', 'Century Tuna', '98', '2029-12-04', 40.00, '2025-12-04 11:53:11', 3),
(11, 'Food and Drinks', 'Vitamilk', '400', '2027-12-05', 30.00, '2025-12-05 05:11:47', 3),
(12, 'Household and Cleaning Supplies', 'Downy', '200', '2029-12-08', 8.00, '2025-12-07 18:51:26', 3),
(13, 'Food and Drinks', 'Shabu', '999', '2025-12-17', 10000.00, '2025-12-17 06:10:03', 3),
(14, 'Food and Drinks', 'mineral', '1', '2025-11-16', 10.00, '2025-12-17 06:22:39', 3);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sale_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `date_time` datetime DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`sale_id`, `total_amount`, `date_time`, `user_id`) VALUES
(1, 138.00, '2025-12-04 20:34:05', 3),
(2, 128.00, '2025-12-04 21:00:50', 3),
(3, 84.00, '2025-12-04 21:21:33', 3),
(4, 131.00, '2025-12-05 13:09:13', 3),
(5, 10033.00, '2025-12-19 04:12:40', 3);

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_name`, `quantity`, `price`, `subtotal`, `user_id`) VALUES
(1, 1, 'Bread Stix', 1, 28.00, 28.00, 3),
(2, 1, 'Century Tuna', 2, 40.00, 80.00, 3),
(3, 1, 'Coca-cola', 1, 20.00, 20.00, 3),
(4, 1, 'MIlo', 1, 10.00, 10.00, 3),
(5, 2, 'Century Tuna', 2, 40.00, 80.00, 3),
(6, 2, 'Marlboro Blast', 2, 10.00, 20.00, 3),
(7, 2, 'Safeguard White', 1, 28.00, 28.00, 3),
(8, 3, 'MIlo', 1, 10.00, 10.00, 3),
(9, 3, 'Safeguard White', 1, 28.00, 28.00, 3),
(10, 3, 'Marlboro Blast', 2, 10.00, 20.00, 3),
(11, 3, 'Ariel Twin-pack', 2, 13.00, 26.00, 3),
(12, 4, 'Safeguard White', 1, 28.00, 28.00, 3),
(13, 4, 'Marlboro Blast', 1, 10.00, 10.00, 3),
(14, 4, 'Ariel Twin-pack', 1, 13.00, 13.00, 3),
(15, 4, 'Century Tuna', 2, 40.00, 80.00, 3),
(16, 5, 'Coca-cola', 1, 20.00, 20.00, 3),
(17, 5, 'Shabu', 1, 10000.00, 10000.00, 3),
(18, 5, 'Ariel Twin-pack', 1, 13.00, 13.00, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `userName` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT 'Male',
  `date_started` date DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userName`, `password`, `fullname`, `address`, `position`, `sex`, `date_started`, `profile_pic`) VALUES
(3, 'user', '$2y$10$LUd3TCKG4MDuAe/FCHZsO.5Wy3bswA7SjN4YDZNsnuY7MfJSeqGae', 'Joselito Hek', 'Catbalogan City', 'Owner', 'Male', '1999-08-10', 'uploads/kindpng_1653181.png'),
(4, 'venus@gmail.com', '$2y$10$xP/QYblR/2zHCFHVOKuUtOEJg.Ji0LguzHeaucV6sScvLpU1AECuG', 'Harvey Venus', 'Catbalogan City', 'Owner', 'Male', '2025-12-17', ''),
(5, 'ilao', '$2y$10$NV1nYESbiMUDLUlN9.iCGOAOXEnq.bcfoMPOfC920vWFs76gsBvbC', 'Robinson Ilao', 'Catbalogan City', 'Owner', 'Male', '2025-12-17', 'uploads/528153155_122113705508945843_1122543270353151904_n.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `capital`
--
ALTER TABLE `capital`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_capital_users` (`user_id`);

--
-- Indexes for table `daily_sales`
--
ALTER TABLE `daily_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_daily_users` (`user_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_expenses_users` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_users` (`user_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `fk_sales_users` (`user_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `fk_items_users` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `capital`
--
ALTER TABLE `capital`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `daily_sales`
--
ALTER TABLE `daily_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `capital`
--
ALTER TABLE `capital`
  ADD CONSTRAINT `fk_capital_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `daily_sales`
--
ALTER TABLE `daily_sales`
  ADD CONSTRAINT `fk_daily_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `fk_expenses_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `fk_items_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`sale_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
