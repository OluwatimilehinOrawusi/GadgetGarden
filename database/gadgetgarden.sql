-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 24, 2025 at 09:12 AM
-- Server version: 8.0.41-0ubuntu0.20.04.1
-- PHP Version: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cs2team33_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `basket`
--

CREATE TABLE `basket` (
  `basket_id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basket`
--

INSERT INTO `basket` (`basket_id`, `user_id`, `product_id`, `quantity`, `added_at`, `updated_at`) VALUES
(5, 1, 40, 1, '2025-03-24 04:13:44', '2025-03-24 04:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci
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
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'unreplied'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `user_id`, `name`, `phone`, `email`, `message`, `created_at`, `status`) VALUES
(1, 2, 'Kojo', '07512667451', 'Kojo-Testing@gmail.com', 'Hello this is a test to see if this works on the hosted website', '2025-03-24 00:47:01', 'replied'),
(2, 2, 'Kojo', '07512667451', 'Kojo-Testing@gmail.com', 'Hey please can you help me? I want to upload my own product', '2025-03-24 07:09:23', 'replied');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Paid','Dispatched','Delivered') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Paid',
  `order_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Paid',
  `return_status` enum('No Return','Pending','Approved','Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'No Return'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_price`, `status`, `order_status`, `return_status`) VALUES
(1, 1, '2025-01-08 00:27:42', 149.99, 'Paid', 'Paid', 'Approved'),
(2, 1, '2025-02-17 00:30:38', 899.99, 'Paid', 'Paid', 'No Return'),
(3, 1, '2025-03-10 00:43:31', 899.99, 'Paid', 'Delivered', 'No Return'),
(4, 2, '2025-03-19 00:45:23', 499.99, 'Paid', 'Delivered', 'No Return'),
(5, 1, '2025-03-24 00:55:18', 4499.95, 'Paid', 'Delivered', 'No Return'),
(6, 2, '2025-03-24 07:55:44', 499.99, 'Paid', 'Paid', 'Pending'),
(7, 2, '2025-03-24 08:16:24', 129.99, 'Paid', 'Delivered', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `order_product_id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_products`
--

INSERT INTO `order_products` (`order_product_id`, `order_id`, `product_id`, `quantity`) VALUES
(1, 2, 21, 1),
(2, 3, 21, 1),
(3, 4, 27, 1),
(4, 6, 26, 1),
(5, 7, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `state` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'New',
  `category_id` int DEFAULT NULL,
  `image` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `stock`, `state`, `category_id`, `image`) VALUES
(2, 'Anker 20W USB-C Charger', 'Fast charging brick for smartphones and tablets.', 19.99, 18, 'Very Good', 7, '../public/assets/anker-charger.png'),
(3, 'Samsung 1TB Portable SSD T7', 'Compact external SSD with fast transfer speeds.', 129.99, 7, 'Like New', 7, '../uploads/ProductImageChange/1742807331_DALL__E_2025-03-24_04.52.59_-_A_realistic_image_of_a_Samsung_Portable_SSD._The_SSD_is_sleek__rectangular__and_compact_with_a_smooth_metallic_finish_in_dark_gray._The_Samsung_logo_i.webp'),
(4, 'Corsair K95 RGB Gaming Keyboard', 'Mechanical keyboard with RGB lighting and macro keys.', 199.99, 6, 'Very Good', 7, '../public/assets/gaming-keyboard.png'),
(5, 'Belkin 6-Port Surge Protector', 'Reliable power strip with surge protection.', 29.99, 25, 'Good', 7, '../public/assets/surge-protector.png'),
(6, 'Dell OptiPlex 3080 Desktop', 'Compact desktop PC, Intel i5, 8GB RAM, 256GB SSD, ideal for office tasks.', 499.99, 15, 'Very Good', 8, '../public/assets/desktop.png'),
(7, 'Apple iMac 24-inch (2021)', 'Refurbished all-in-one desktop, M1 chip, 8GB RAM, 512GB SSD.', 1499.99, 7, 'Like New', 8, '../public/assets/imac.png'),
(8, 'Lenovo IdeaCentre 3 Desktop', 'Affordable tower PC, AMD Ryzen 3, 8GB RAM, 1TB HDD, suitable for beginners.', 429.99, 10, 'Good', 8, '../public/assets/lenovo-desktop.png'),
(9, 'HP Pavilion Gaming Desktop', 'High-performance gaming PC, Intel i7, NVIDIA GTX 1660, 16GB RAM, 512GB SSD.', 1199.99, 5, 'Very Good', 8, '../public/assets/desktop.png'),
(10, 'Acer Aspire TC-895 Desktop', 'Mid-range PC with Intel i5, 12GB RAM, 1TB HDD + 256GB SSD combo storage.', 599.99, 8, 'Good', 8, '../public/assets/acer-computer.png'),
(11, 'Dell XPS 13 (2021)', 'Ultra-portable 13\" laptop, Intel i7, 16GB RAM, 512GB SSD.', 1099.99, 5, 'Like New', 1, '../public/assets/dell-desktop.png'),
(12, 'Apple MacBook Air M1', 'Lightweight laptop, M1 chip, 8GB RAM, 256GB SSD.', 899.99, 6, 'Very Good', 1, '../public/assets/macbook.png'),
(13, 'Lenovo ThinkPad T14', 'Business laptop, Intel i5, 8GB RAM, 256GB SSD, Windows 11 Pro.', 749.99, 7, 'Good', 1, '../public/assets/lenovo-laptop.png'),
(14, 'ASUS ROG Zephyrus G15', 'Gaming laptop, AMD Ryzen 9, RTX 3060, 16GB RAM, 1TB SSD.', 1599.99, 4, 'Very Good', 1, '../public/assets/asus-laptop.png'),
(15, 'HP Spectre x360 Convertible', '2-in-1 touchscreen laptop, Intel i7, 16GB RAM, 512GB SSD.', 1249.99, 7, 'Like New', 1, '../public/assets/hp-laptop.png'),
(16, 'Sony WH-1000XM4 Headphones', 'Noise-canceling wireless headphones, 30-hour battery life.', 299.99, 8, 'Very Good', 2, '../public/assets/sony-headphones.png'),
(17, 'Apple AirPods Pro', 'Wireless earbuds with active noise cancellation and spatial audio.', 249.99, 15, 'Like New', 2, '../public/assets/airpods.png'),
(18, 'JBL Charge 5 Bluetooth Speaker', 'Portable speaker, waterproof, 20 hours of playtime.', 179.99, 10, 'Good', 2, '../public/assets/jbl-speaker.png'),
(19, 'Bose SoundLink Revolve II', 'Premium portable Bluetooth speaker with 360° sound.', 199.99, 6, 'Very Good', 2, '../public/assets/bose-speaker.png'),
(20, 'Sennheiser Momentum True Wireless 3', 'Audiophile-grade wireless earbuds, ANC, and custom EQ.', 279.99, 5, 'Like New', 2, '../public/assets/speaker.png'),
(21, 'iPhone 13 Pro', '128GB, 6.1-inch Super Retina XDR display, A15 Bionic chip.', 899.99, 3, 'Like New', 3, '../public/assets/trending1.jpg'),
(22, 'Samsung Galaxy S22 Ultra', '256GB, 6.8-inch AMOLED, S Pen included.', 1099.99, 9, 'Very Good', 3, '../public/assets/samsung.png'),
(23, 'Google Pixel 6', '128GB, Google Tensor chip, Android 12, dual-camera system.', 599.99, 0, 'Good', 3, '../public/assets/google-pixel.png'),
(24, 'OnePlus 10 Pro', '256GB, Snapdragon 8 Gen 1, Hasselblad-tuned cameras.', 699.99, 5, 'Very Good', 3, '../public/assets/one-plus.png'),
(25, 'Motorola Edge+ 2022', '512GB, OLED 144Hz display, 50MP triple camera.', 799.99, 4, 'Like New', 3, '../public/assets/motorolla.png'),
(26, 'PlayStation 5', 'Next-gen console, 825GB SSD, DualSense controller.', 499.99, 5, 'Like New', 4, '../public/assets/playstation.png'),
(27, 'Xbox Series X', '1TB SSD, 4K gaming, backward compatibility.', 499.99, 5, 'Very Good', 4, '../public/assets/xbox.png'),
(28, 'Nintendo Switch OLED', '64GB storage, vibrant OLED display, Joy-Con controllers.', 349.99, 10, 'Like New', 4, '../public/assets/nintendo.png'),
(29, 'SteelSeries Arctis 7 Headset', 'Wireless gaming headset, DTS surround sound.', 179.99, 12, 'Good', 2, '../public/assets/headphones.png'),
(30, 'Razer DeathAdder V2 Mouse', 'Ergonomic gaming mouse, customizable RGB lighting.', 69.99, 15, 'Very Good', 7, '../public/assets/mouse.png'),
(31, 'Apple Watch Series 7', '45mm smartwatch, GPS, blood oxygen monitoring.', 399.99, 10, 'Like New', 5, '../public/assets/apple-watch.png'),
(32, 'Samsung Galaxy Watch 4', '40mm, Wear OS, body composition analysis.', 299.99, 15, 'Very Good', 5, '../public/assets/samsung-watch.png'),
(33, 'Fitbit Versa 3', 'Fitness tracker, heart rate monitoring, GPS.', 229.99, 12, 'Good', 5, '../public/assets/fitness-tracker.png'),
(34, 'Garmin Forerunner 945', 'GPS smartwatch, advanced fitness metrics.', 599.99, 6, 'Like New', 5, '../public/assets/fitness-tracker2.png'),
(35, 'Amazfit GTR 3', 'Smartwatch with AMOLED display, long battery life.', 179.99, 8, 'Very Good', 5, '../public/assets/fitness-tracker3.png'),
(36, 'iPad Pro 12.9\" (2021)', 'M1 chip, 128GB, Liquid Retina XDR display.', 1099.99, 5, 'Like New', 6, '../public/assets/ipad.png'),
(37, 'Samsung Galaxy Tab S8+', '128GB, AMOLED display, S Pen included.', 899.99, 7, 'Very Good', 6, '../public/assets/tablet.png'),
(38, 'Microsoft Surface Go 3', 'Intel Pentium, 64GB SSD, Windows 11.', 499.99, 10, 'Good', 6, '../public/assets/microsoft-surface.png'),
(39, 'Amazon Fire HD 10', '10.1-inch Full HD, 32GB, Alexa-enabled.', 149.99, 18, 'Like New', 6, '../public/assets/amazon-tablet.png'),
(40, 'Lenovo Tab P11 Pro', '11.5-inch OLED, 128GB, Dolby Atmos speakers.', 599.99, 8, 'Very Good', 6, '../public/assets/lenovo-tablet.png');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `reply_id` int NOT NULL,
  `query_id` int NOT NULL,
  `reply_message` text COLLATE utf8mb4_general_ci NOT NULL,
  `reply_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`reply_id`, `query_id`, `reply_message`, `reply_date`, `created_at`) VALUES
(1, 1, '', '2025-03-24 00:55:21', '2025-03-24 00:55:21'),
(2, 2, 'Go to product page and got to upload product and click the link.', '2025-03-24 07:10:23', '2025-03-24 07:10:23');

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `return_id` int NOT NULL,
  `user_id` int NOT NULL,
  `order_id` int NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `status` enum('Pending','Approved','Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`return_id`, `user_id`, `order_id`, `reason`, `details`, `status`, `created_at`) VALUES
(3, 2, 6, 'Not satisfied', 'Not as good as the xbox series X', 'Rejected', '2025-03-24 08:10:04'),
(4, 2, 6, 'Not satisfied', 'Xbox series x is so much better', 'Rejected', '2025-03-24 08:10:29'),
(5, 2, 6, 'Not satisfied', 'I would like to get my money back for an xbox', 'Pending', '2025-03-24 08:13:55'),
(6, 2, 7, 'Damaged item', 'This item wasn&#039;t in a good condition', 'Approved', '2025-03-24 08:16:47');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `rating` int NOT NULL,
  `review_text` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `product_id`, `rating`, `review_text`, `created_at`) VALUES
(1, 2, 27, 5, 'Very good gaming console. Even better than the compertition due to game pass.', '2025-03-24 00:48:12'),
(3, 3, 23, 4, 'The Pixel 6 has been a fantastic upgrade! The Google Tensor chip makes everything feel super responsive, and the Android 12 interface is clean and intuitive. The dual-camera system takes stunning photos, especially in low light. I love the vibrant 6.4&quot; display, and the battery easily lasts me a full day. Only reason I’m giving 4 stars instead of 5 is that the fingerprint sensor can be a little slow sometimes. Other than that, it’s a top-tier phone at a great price!', '2025-03-24 05:21:45'),
(4, 3, 16, 3, 'I expected a bit more considering all the hype. Noise cancelling works well, but not perfect—sometimes sudden sounds still get through. They’re comfortable and the sound is decent, but I’ve used cheaper headphones that sound just as good. Battery life is great though, and the build quality feels premium.', '2025-03-24 05:23:56'),
(6, 3, 2, 4, 'Picked this up as a backup charger, but it’s quickly become my go-to. Charges my phone from 20% to 80% in no time, and despite being used, it works perfectly. No overheating or weird noises like some cheap brands. It&#039;s small enough to toss in my bag without noticing. Docking one star only because I wish it came with a USB-C cable but for the price can not complain', '2025-03-24 05:25:45');

-- --------------------------------------------------------

--
-- Table structure for table `upload_products`
--

CREATE TABLE `upload_products` (
  `upload_id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `Admin_approve` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `condition` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `image_path` text COLLATE utf8mb4_general_ci,
  `category_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin` tinyint(1) DEFAULT '0',
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('admin','manager','user') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `memorable_phrase` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `created_at`, `admin`, `phone`, `role`, `memorable_phrase`) VALUES
(1, 'timi', 'timi1@gmail.com', '$2y$10$qBbiZx43Ww0CueE9DWvjX.j4A9fStOZhaS7/YbWYLEyO5c4gVKPme', '2024-11-21 04:08:19', 1, NULL, 'admin', ''),
(2, 'Kojo', 'Kojo-Testing@gmail.com', '$2y$10$TmCJ1cWqsyMmE5IjJj7wouy36gbedhTV5a8zDtDj1UmA8qmb4R4fW', '2025-03-24 00:44:36', 0, '07465414151', 'user', 'Trump'),
(3, 'Hammad Ali', 'Hammad12398@test.com', '$2y$10$gmB8411mKQ7qwSV5B0BZCOuioLCqzc5muo8Nyzl47Rg3HY.G6U/8a', '2025-03-24 04:23:20', 0, '07132123212', 'user', '12');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `basket`
--
ALTER TABLE `basket`
  ADD PRIMARY KEY (`basket_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

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
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `query_id` (`query_id`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`return_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `upload_products`
--
ALTER TABLE `upload_products`
  ADD PRIMARY KEY (`upload_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `basket`
--
ALTER TABLE `basket`
  MODIFY `basket_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `order_product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `reply_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `return_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `upload_products`
--
ALTER TABLE `upload_products`
  MODIFY `upload_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`query_id`) REFERENCES `contact` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `upload_products`
--
ALTER TABLE `upload_products`
  ADD CONSTRAINT `upload_products_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
