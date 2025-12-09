-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2025 at 01:16 AM
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
-- Database: `cateringdb`
--

-- --------------------------------------------------------
create database cateringdb;
--
-- Table structure for table `catering_packages`
--
use cateringdb;

CREATE TABLE `catering_packages` (
  `package_id` int(11) NOT NULL,
  `package_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE `menu_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `item_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `customer_last_name` varchar(150) NOT NULL,
  `customer_email` varchar(150) NOT NULL,
  `customer_phone` varchar(50) NOT NULL,
  `package_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_location` varchar(255) DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `customer_lookup` (`customer_last_name`, `customer_phone`),
  KEY `user_id` (`user_id`),
  KEY `package_id` (`package_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_status_history`
--

CREATE TABLE `order_status_history` (
  `status_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_items`
--

CREATE TABLE `package_items` (
  `package_item_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_features`
--

CREATE TABLE `package_features` (
  `feature_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `feature_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quote_requests`
--

CREATE TABLE `quote_requests` (
  `quote_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'New',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` VARCHAR(50) DEFAULT 'admin',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `payments`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `method` VARCHAR(50) NOT NULL,
  `status` VARCHAR(50) DEFAULT 'Pending',
  `paid_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) 
    REFERENCES `orders` (`order_id`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Indexes for table `catering_packages`
--
ALTER TABLE `catering_packages`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `orders`
--
 ALTER TABLE `orders`
  --ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `customer_lookup` (`customer_last_name`, `customer_phone`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `package_id` (`package_id`);


--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`status_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `package_features`
--
ALTER TABLE `package_features`
  ADD PRIMARY KEY (`feature_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `package_items`
--
ALTER TABLE `package_items`
  ADD PRIMARY KEY (`package_item_id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `quote_requests`
--
ALTER TABLE `quote_requests`
  ADD PRIMARY KEY (`quote_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `catering_packages`
--
ALTER TABLE `catering_packages`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package_features`
--
ALTER TABLE `package_features`
  MODIFY `feature_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package_items`
--
ALTER TABLE `package_items`
  MODIFY `package_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quote_requests`
--
ALTER TABLE `quote_requests`
  MODIFY `quote_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Data for catering packages
--

INSERT INTO `catering_packages` (`package_id`, `package_name`, `base_price`, `is_active`) VALUES
(1, 'Bronze Package', 25.00, 1),
(2, 'Silver Package', 40.00, 1),
(3, 'Gold Package', 60.00, 1),
(4, 'Platinum Package', 75.00, 1),
(5, 'Custom Package', 0.00, 1);

--
-- Data for package features
--

INSERT INTO `package_features` (`package_id`, `feature_text`) VALUES
-- Bronze Package
(1, '✓ Selection of 3 Appetizers'),
(1, '✓ 2 Main Course Options'),
(1, '✓ 1 Side Dish'),
(1, '✓ Basic Dessert Selection'),
(1, '✓ Non-alcoholic Beverages'),
(1, '✓ Chafing dishes & utensils'),
(1, '✓ Minimum 20 guests'),
(1, '✓ 2 weeks advance notice'),
-- Silver Package
(2, '✓ Selection of 5 Appetizers'),
(2, '✓ 3 Main Course Options'),
(2, '✓ 2 Side Dishes'),
(2, '✓ Premium Dessert Selection'),
(2, '✓ Non-alcoholic Beverages'),
(2, '✓ Coffee & Tea Service'),
(2, '✓ Elegant plating & presentation'),
(2, '✓ Wait staff service included'),
(2, '✓ Minimum 30 guests'),
(2, '✓ 1 week advance notice'),
-- Gold Package
(3, '✓ Selection of 7 Appetizers'),
(3, '✓ 4 Main Course Options'),
(3, '✓ 3 Side Dishes'),
(3, '✓ Gourmet Dessert Selection'),
(3, '✓ Premium Beverage Bar'),
(3, '✓ Coffee & Digestif Service'),
(3, '✓ Professional wait staff (1 per 8 guests)'),
(3, '✓ Customized menu planning'),
(3, '✓ Dietary accommodations'),
(3, '✓ Setup & full cleanup'),
-- Platinum Package
(4, '✓ Unlimited Appetizer Selection'),
(4, '✓ 5+ Main Course Options'),
(4, '✓ 4+ Side Dishes'),
(4, '✓ Multi-course Tasting Menu'),
(4, '✓ Full Bar Service'),
(4, '✓ Chef''s Special Creations'),
(4, '✓ Dedicated Event Coordinator'),
(4, '✓ Premium Linens & Décor'),
(4, '✓ Live Cooking Stations'),
(4, '✓ Complete Event Management');

-- Custom Package (package_id = 5)
(5, '✓ Fully customizable menu'),
(5, '✓ Personalized consultation & planning'),
(5, '✓ Price quoted per event'),
(5, '✓ Flexible guest counts & options'),
(5, '✓ Dedicated event coordinator'),
(5, '✓ On-site tasting available upon request'),
(5, '✓ Special dietary accommodations as required');

---------------------
UPDATE catering_packages
SET base_price = 75.00
WHERE package_id = 4;
---------------------

INSERT INTO catering_packages (package_id, package_name, base_price, is_active)
VALUES (5, 'Custom Package', 0.00, 1);
------------------------
INSERT INTO package_features (package_id, feature_text) VALUES
(5, '✓ Fully customizable menu'),
(5, '✓ Personalized consultation & planning'),
(5, '✓ Price quoted per event'),
(5, '✓ Flexible guest counts & options'),
(5, '✓ Dedicated event coordinator'),
(5, '✓ On-site tasting available upon request'),
(5, '✓ Special dietary accommodations as required');
--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`category_id`) ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `catering_packages` (`package_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`item_id`) ON UPDATE CASCADE;

--
-- Constraints for table `package_features`
--
ALTER TABLE `package_features`
  ADD CONSTRAINT `package_features_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `catering_packages` (`package_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `order_status_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `package_items`
--
ALTER TABLE `package_items`
  ADD CONSTRAINT `package_items_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `catering_packages` (`package_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `package_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



 

