-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2025 at 03:59 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gadgetgarden`
--

-- --------------------------------------------------------

--
-- Table structure for table `basket`
--

CREATE TABLE `basket` (
  `basket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basket`
--

INSERT INTO `basket` (`basket_id`, `user_id`, `product_id`, `quantity`, `added_at`, `updated_at`) VALUES
(6, 1, 23, 1, '2024-11-22 21:04:31', '2024-11-22 21:04:31'),
(8, 7, 21, 1, '2025-02-18 11:41:30', '2025-02-18 11:41:30'),
(9, 8, 12, 1, '2025-02-18 14:26:50', '2025-02-18 14:26:50'),
(10, 9, 21, 1, '2025-02-18 14:53:05', '2025-02-18 14:53:05');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`) VALUES
(1, 'Laptops', 'Refurbished laptops of various brands and specifications.'),
(2, 'Audio', 'Headphones, speakers, and other audio equipment.'),
(3, 'Phones', 'Refurbished smartphones from leading brands.'),
(4, 'Gaming', 'Gaming consoles, accessories, and peripherals.'),
(5, 'Wearables', 'Smartwatches and other wearable tech.'),
(6, 'Tablets', 'Refurbished tablets for work, entertainment, and more.'),
(7, 'Accessories', 'Chargers, keyboards, and other peripherals.'),
(8, 'Computers', 'Desktop PCs and components.');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_price`) VALUES
(5, 1, '2024-11-22 21:00:12', 14988.00),
(6, 1, '2024-11-22 21:04:57', 15587.00),
(7, 1, '2024-11-22 22:53:44', 16185.00),
(8, 7, '2025-02-18 11:41:49', 899.99),
(9, 7, '2025-02-18 12:10:09', 899.99),
(10, 1, '2025-02-18 14:36:09', 599.99),
(11, 1, '2025-02-18 14:36:48', 599.99),
(12, 8, '2025-02-18 14:50:47', 899.99),
(13, 9, '2025-02-18 14:53:51', 899.99);

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `order_product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_products`
--

INSERT INTO `order_products` (`order_product_id`, `order_id`, `product_id`, `quantity`) VALUES
(1, 5, 7, 8),
(2, 5, 11, 1),
(3, 5, 36, 1),
(4, 5, 4, 1),
(5, 5, 10, 1),
(6, 6, 7, 8),
(7, 6, 11, 1),
(8, 6, 36, 1),
(9, 6, 4, 1),
(10, 6, 10, 1),
(11, 6, 23, 1),
(12, 7, 7, 8),
(13, 7, 11, 1),
(14, 7, 36, 1),
(15, 7, 4, 1),
(16, 7, 10, 1),
(17, 7, 23, 1),
(18, 7, 16, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `stock`, `state`, `category_id`, `image`) VALUES
(1, 'Logitech MX Master 3 Mouse', 'Wireless mouse with ergonomic design, multi-device control.', 99.99, 8, 'Like New', 7, '/public/assets/logitech-mouse.png'),
(2, 'Anker 20W USB-C Charger', 'Fast charging brick for smartphones and tablets.', 19.99, 20, 'Very Good', 7, '/public/assets/anker-charger.png'),
(3, 'Samsung 1TB Portable SSD T7', 'Compact external SSD with fast transfer speeds.', 129.99, 10, 'Like New', 3, '/public/assets/samsung.png'),
(4, 'Corsair K95 RGB Gaming Keyboard', 'Mechanical keyboard with RGB lighting and macro keys.', 199.99, 6, 'Very Good', 7, '/public/assets/gaming-keyboard.png'),
(5, 'Belkin 6-Port Surge Protector', 'Reliable power strip with surge protection.', 29.99, 25, 'Good', 7, '/public/assets/surge-protector.png'),
(6, 'Dell OptiPlex 3080 Desktop', 'Compact desktop PC, Intel i5, 8GB RAM, 256GB SSD, ideal for office tasks.', 499.99, 15, 'Very Good', 8, '/public/assets/desktop.png'),
(7, 'Apple iMac 24-inch (2021)', 'Refurbished all-in-one desktop, M1 chip, 8GB RAM, 512GB SSD.', 1499.99, 7, 'Like New', 8, '/public/assets/imac.png'),
(8, 'Lenovo IdeaCentre 3 Desktop', 'Affordable tower PC, AMD Ryzen 3, 8GB RAM, 1TB HDD, suitable for beginners.', 429.99, 10, 'Good', 8, '/public/assets/lenovo-desktop.png'),
(9, 'HP Pavilion Gaming Desktop', 'High-performance gaming PC, Intel i7, NVIDIA GTX 1660, 16GB RAM, 512GB SSD.', 1199.99, 5, 'Very Good', 8, '/public/assets/desktop.png'),
(10, 'Acer Aspire TC-895 Desktop', 'Mid-range PC with Intel i5, 12GB RAM, 1TB HDD + 256GB SSD combo storage.', 599.99, 8, 'Good', 8, '/public/assets/acer-computer.png'),
(11, 'Dell XPS 13 (2021)', 'Ultra-portable 13\" laptop, Intel i7, 16GB RAM, 512GB SSD.', 1099.99, 5, 'Like New', 1, '/public/assets/dell-desktop.png'),
(12, 'Apple MacBook Air M1', 'Lightweight laptop, M1 chip, 8GB RAM, 256GB SSD.', 899.99, 12, 'Very Good', 1, '/public/assets/macbook.png'),
(13, 'Lenovo ThinkPad T14', 'Business laptop, Intel i5, 8GB RAM, 256GB SSD, Windows 11 Pro.', 749.99, 10, 'Good', 1, '/public/assets/lenovo-laptop.png'),
(14, 'ASUS ROG Zephyrus G15', 'Gaming laptop, AMD Ryzen 9, RTX 3060, 16GB RAM, 1TB SSD.', 1599.99, 4, 'Very Good', 1, '/public/assets/asus-laptop.png'),
(15, 'HP Spectre x360 Convertible', '2-in-1 touchscreen laptop, Intel i7, 16GB RAM, 512GB SSD.', 1249.99, 7, 'Like New', 1, '/public/assets/hp-laptop.png'),
(16, 'Sony WH-1000XM4 Headphones', 'Noise-canceling wireless headphones, 30-hour battery life.', 299.99, 8, 'Very Good', 2, '/public/assets/sony-headphones.png'),
(17, 'Apple AirPods Pro', 'Wireless earbuds with active noise cancellation and spatial audio.', 249.99, 15, 'Like New', 2, '/public/assets/airpods.png'),
(18, 'JBL Charge 5 Bluetooth Speaker', 'Portable speaker, waterproof, 20 hours of playtime.', 179.99, 10, 'Good', 2, '/public/assets/jbl-speaker.png'),
(19, 'Bose SoundLink Revolve II', 'Premium portable Bluetooth speaker with 360° sound.', 199.99, 6, 'Very Good', 2, '/public/assets/bose-speaker.png'),
(20, 'Sennheiser Momentum True Wireless 3', 'Audiophile-grade wireless earbuds, ANC, and custom EQ.', 279.99, 5, 'Like New', 2, '/public/assets/speaker.png'),
(21, 'iPhone 13 Pro', '128GB, 6.1-inch Super Retina XDR display, A15 Bionic chip.', 899.99, 7, 'Like New', 3, '/public/assets/trending1.png'),
(22, 'Samsung Galaxy S22 Ultra', '256GB, 6.8-inch AMOLED, S Pen included.', 1099.99, 9, 'Very Good', 3, '/public/assets/samsung.png'),
(23, 'Google Pixel 6', '128GB, Google Tensor chip, Android 12, dual-camera system.', 599.99, 15, 'Good', 3, '/public/assets/google-pixel.png'),
(24, 'OnePlus 10 Pro', '256GB, Snapdragon 8 Gen 1, Hasselblad-tuned cameras.', 699.99, 5, 'Very Good', 3, '/public/assets/one-plus.png'),
(25, 'Motorola Edge+ 2022', '512GB, OLED 144Hz display, 50MP triple camera.', 799.99, 4, 'Like New', 3, '/public/assets/motorolla.png'),
(26, 'PlayStation 5', 'Next-gen console, 825GB SSD, DualSense controller.', 499.99, 6, 'Like New', 4, '/public/assets/playstation.png'),
(27, 'Xbox Series X', '1TB SSD, 4K gaming, backward compatibility.', 499.99, 8, 'Very Good', 4, '/public/assets/xbox.png'),
(28, 'Nintendo Switch OLED', '64GB storage, vibrant OLED display, Joy-Con controllers.', 349.99, 10, 'Like New', 5, '/public/assets/nintendo.png'),
(29, 'SteelSeries Arctis 7 Headset', 'Wireless gaming headset, DTS surround sound.', 179.99, 12, 'Good', 2, '/public/assets/headphones.png'),
(30, 'Razer DeathAdder V2 Mouse', 'Ergonomic gaming mouse, customizable RGB lighting.', 69.99, 15, 'Very Good', 7, '/public/assets/mouse.png'),
(31, 'Apple Watch Series 7', '45mm smartwatch, GPS, blood oxygen monitoring.', 399.99, 10, 'Like New', 5, '/public/assets/apple-watch.png'),
(32, 'Samsung Galaxy Watch 4', '40mm, Wear OS, body composition analysis.', 299.99, 15, 'Very Good', 5, '/public/assets/samsung-watch.png'),
(33, 'Fitbit Versa 3', 'Fitness tracker, heart rate monitoring, GPS.', 229.99, 12, 'Good', 5, '/public/assets/fitness-tracker.png'),
(34, 'Garmin Forerunner 945', 'GPS smartwatch, advanced fitness metrics.', 599.99, 6, 'Like New', 5, '/public/assets/fitness-tracker2.png'),
(35, 'Amazfit GTR 3', 'Smartwatch with AMOLED display, long battery life.', 179.99, 8, 'Very Good', 5, '/public/assets/fitness-tracker3.png'),
(36, 'iPad Pro 12.9\" (2021)', 'M1 chip, 128GB, Liquid Retina XDR display.', 1099.99, 5, 'Like New', 6, '/public/assets/ipad.png'),
(37, 'Samsung Galaxy Tab S8+', '128GB, AMOLED display, S Pen included.', 899.99, 7, 'Very Good', 6, '/public/assets/tablet.png'),
(38, 'Microsoft Surface Go 3', 'Intel Pentium, 64GB SSD, Windows 11.', 499.99, 10, 'Good', 6, '/public/assets/microsoft-surface.png'),
(39, 'Amazon Fire HD 10', '10.1-inch Full HD, 32GB, Alexa-enabled.', 149.99, 20, 'Like New', 6, '/public/assets/amazon-tablet.png'),
(40, 'Lenovo Tab P11 Pro', '11.5-inch OLED, 128GB, Dolby Atmos speakers.', 599.99, 8, 'Very Good', 6, '/public/assets/lenovo-tablet.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin` tinyint(1) DEFAULT 0,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `created_at`, `admin`, `phone`) VALUES
(1, 'timi', 'timi1@gmail.com', '$2y$10$qBbiZx43Ww0CueE9DWvjX.j4A9fStOZhaS7/YbWYLEyO5c4gVKPme', '2024-11-21 04:08:19', 1, NULL),
(2, 'user123', 'user@gmail.com', '$2y$10$iedZ9BN4Hko1/sNEKDcIsO2Xday31KBv622QCgS4IRCaKduPnAQIe', '2024-11-22 22:44:32', 0, NULL),
(6, 'usernew', 'fdaj@gmail.com', '$2y$10$GQmyTDvDKH10wTQaD2jla./tfUwUPhKsmhvu9VZhSa/sn2L64/pNu', '2024-11-22 22:48:39', 0, NULL),
(7, 'hammad', 'trdesyugyug@tutft', '$2y$10$2OOJAe1Y6NE/jXDtiHmqDeJlToW.2vF3yj5vhpGU0f4lRmMua5qY.', '2025-02-18 11:41:13', 0, 'estersrdrdt'),
(8, 'timi123', 'IUHuhuadjjewije@ewiuhiyeh', '$2y$10$v/oA7RYtsT26hHpoM6zYw.RgIEQ1Wc4I3CzM50P19ieB2dCvoXI6y', '2025-02-18 14:26:31', 0, 'wqwqewqewq'),
(9, '123', 'fhdrdrd@saydsad', '$2y$10$VbTaX5/hCNp8WAYhE228dObT6sqxxebj8yEzR.a5m72hv3sBpJv5K', '2025-02-18 14:52:53', 0, 'weweqe');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `basket`
--
ALTER TABLE `basket`
  ADD PRIMARY KEY (`basket_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`order_product_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

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
-- AUTO_INCREMENT for table `basket`
--
ALTER TABLE `basket`
  MODIFY `basket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `order_product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `basket`
--
ALTER TABLE `basket`
  ADD CONSTRAINT `basket_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `basket_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_products`
--
ALTER TABLE `order_products`
  ADD CONSTRAINT `order_products_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
