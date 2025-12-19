-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2025 at 02:27 PM
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
-- Database: `localtrade_db`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `activeproducts`
-- (See below for the actual view)
--
CREATE TABLE `activeproducts` (
`id` int(11)
,`brand_id` int(11)
,`name` varchar(200)
,`slug` varchar(200)
,`sku` varchar(100)
,`category` varchar(50)
,`price` decimal(10,2)
,`compare_at_price` decimal(10,2)
,`stock` int(11)
,`short_desc` text
,`long_desc` text
,`status` enum('draft','active','archived')
,`visibility` enum('public','hidden')
,`featured` tinyint(1)
,`main_image` varchar(500)
,`shipping_fee` decimal(10,2)
,`ships_from` varchar(100)
,`processing_time` varchar(50)
,`variants_text` text
,`rating` decimal(3,2)
,`total_reviews` int(11)
,`total_sales` int(11)
,`views` int(11)
,`created_at` datetime
,`updated_at` datetime
,`published_at` datetime
,`brand_name` varchar(100)
,`brand_slug` varchar(50)
,`brand_rating` decimal(3,2)
,`brand_location` varchar(100)
);

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` varchar(100) DEFAULT 'Nigeria',
  `postal_code` varchar(20) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `logo` varchar(500) DEFAULT NULL,
  `tagline` varchar(200) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  `twitter` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `shipping_policy` text DEFAULT NULL,
  `return_policy` text DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `total_reviews` int(11) DEFAULT 0,
  `total_sales` int(11) DEFAULT 0,
  `followers` int(11) DEFAULT 0,
  `products_count` int(11) DEFAULT 0,
  `store_views` int(11) DEFAULT 0,
  `since_year` year(4) DEFAULT NULL,
  `status` enum('pending','active','suspended','closed') DEFAULT 'pending',
  `verified` tinyint(1) DEFAULT 0,
  `featured` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `owner_name`, `email`, `password`, `brand_name`, `slug`, `category`, `location`, `logo`, `tagline`, `bio`, `whatsapp`, `instagram`, `facebook`, `twitter`, `website`, `contact_email`, `shipping_policy`, `return_policy`, `rating`, `total_reviews`, `total_sales`, `followers`, `products_count`, `store_views`, `since_year`, `status`, `verified`, `featured`, `created_at`, `updated_at`, `last_login`) VALUES
(1, 'Chidi Okafor', 'chidi@lagosstreet.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lagos Streetwear Co.', 'lagos-streetwear-co', 'Fashion', 'Lagos, Nigeria', NULL, 'Urban fashion made in Lagos', 'Urban fashion label from Lagos, blending African prints with modern streetwear silhouettes. All pieces made locally by Nigerian tailors.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4.80, 124, 0, 2300, 52, 0, '2021', 'active', 1, 0, '2025-12-16 12:40:29', NULL, NULL),
(2, 'Amaka Nwosu', 'amaka@abujabeauty.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Abuja Beauty Lab', 'abuja-beauty-lab', 'Beauty', 'Abuja, Nigeria', NULL, 'Clean skincare made in Nigeria', 'Clean skincare and self-care products made with Nigerian ingredients.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4.70, 89, 0, 1850, 24, 0, '2020', 'active', 1, 0, '2025-12-16 12:40:29', NULL, NULL),
(3, 'Ibrahim Hassan', 'ibrahim@naijatech.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Naija Tech Hub', 'naija-tech-hub', 'Electronics', 'Lagos, Nigeria', NULL, 'Smart tech for everyone', 'Affordable gadgets and smart accessories for everyday Nigerians.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4.60, 156, 0, 3200, 18, 0, '2019', 'active', 1, 0, '2025-12-16 12:40:29', NULL, NULL),
(4, 'EZE', 'dfsx@bnc.v', '$2y$10$QLAZhNOjnpC4QxqmevhiOuC9x1LocUAxPhdz7JC5yJXbiQUeSO0aC', 'localtrade', 'mer', 'Fashion', 'Enugu, Nigeria', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 0, 0, 0, 0, NULL, 'active', 0, 0, '2025-12-16 13:32:12', NULL, NULL),
(5, 'tfyufgui', 'john@example.com1', '$2y$10$ty55aY1NOCpFWDu8jgtM.ODtsQX3kKUh6qA3c/fq0tvK3FAmblKsG', 'gyugv', 'gxdty', 'Fashion', 'Enugu, Nigeria', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 0, 0, 0, 0, NULL, 'active', 0, 0, '2025-12-16 13:46:27', NULL, NULL),
(6, 'EZE', 'ekgauk@sdhh.bj', '$2y$10$5VVMX1TovEZNyXFilDdHeeICmNJzI2AW7.3IxdhLRbSpSaSa03RkG', 'me', 'me', 'Food & Drinks', 'Enugu, Nigeria', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 0, 0, 0, 0, NULL, 'active', 0, 0, '2025-12-16 13:47:17', NULL, NULL),
(7, 'Testing', 'dsjy@zjv.chb', '$2y$10$pc4S3MJPZMyIP1B7RW6iF.wR5LDY42Hw5O75IQl8hMiNvkeK3wNEK', 'sdhdshcvh', 'acuigco', 'Home & Living', 'Enugu, Nigeria', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, 0, 0, 0, 0, NULL, 'active', 0, 0, '2025-12-16 16:14:47', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `brandfollower`
--

CREATE TABLE `brandfollower` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `followed_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `brandfollower`
--
DELIMITER $$
CREATE TRIGGER `after_brand_follower_delete` AFTER DELETE ON `brandfollower` FOR EACH ROW BEGIN
    UPDATE Brand 
    SET followers = followers - 1 
    WHERE id = OLD.brand_id AND followers > 0;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_brand_follower_insert` AFTER INSERT ON `brandfollower` FOR EACH ROW BEGIN
    UPDATE Brand 
    SET followers = followers + 1 
    WHERE id = NEW.brand_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `brandperformance`
-- (See below for the actual view)
--
CREATE TABLE `brandperformance` (
`id` int(11)
,`brand_name` varchar(100)
,`slug` varchar(50)
,`category` varchar(50)
,`rating` decimal(3,2)
,`total_reviews` int(11)
,`followers` int(11)
,`products_count` int(11)
,`total_orders` bigint(21)
,`total_revenue` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `buyer`
--

CREATE TABLE `buyer` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(500) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `status` enum('active','suspended','deleted') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buyer`
--

INSERT INTO `buyer` (`id`, `fullname`, `email`, `password`, `avatar`, `phone`, `created_at`, `updated_at`, `last_login`, `email_verified`, `status`) VALUES
(1, 'John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, '2025-12-16 12:40:29', NULL, NULL, 1, 'active'),
(2, 'Emmameki', 'emmameki283@gmail.com', '$2y$10$qC1SnRGBsJIWeJAtXS28BueSmioCIcJkHx.Br2GI2xIdTmKZ2S0dO', 'Assets/avatars/avatar_69416b50241c3_logo bg.png', '09037688626', '2025-12-16 13:07:24', NULL, NULL, 0, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `user_type` enum('buyer','brand') NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(500) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `status` enum('processing','paid','shipped','delivered','cancelled','refunded') DEFAULT 'processing',
  `subtotal` decimal(10,2) NOT NULL,
  `shipping_fee` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `payment_channel` varchar(50) DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `shipping_name` varchar(100) NOT NULL,
  `shipping_address1` varchar(255) NOT NULL,
  `shipping_address2` varchar(255) DEFAULT NULL,
  `shipping_city` varchar(100) NOT NULL,
  `shipping_state` varchar(100) NOT NULL,
  `shipping_country` varchar(100) DEFAULT 'Nigeria',
  `shipping_note` text DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `courier` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `shipped_at` datetime DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderitem`
--

CREATE TABLE `orderitem` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_variant` varchar(200) DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `compare_at_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `short_desc` text NOT NULL,
  `long_desc` text DEFAULT NULL,
  `status` enum('draft','active','archived') DEFAULT 'draft',
  `visibility` enum('public','private') DEFAULT 'public',
  `featured` tinyint(1) DEFAULT 0,
  `main_image` varchar(500) DEFAULT NULL,
  `shipping_fee` decimal(10,2) DEFAULT NULL,
  `ships_from` varchar(100) DEFAULT NULL,
  `processing_time` varchar(50) DEFAULT NULL,
  `variants_text` text DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `total_reviews` int(11) DEFAULT 0,
  `total_sales` int(11) DEFAULT 0,
  `views` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `published_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `brand_id`, `name`, `slug`, `sku`, `category`, `price`, `compare_at_price`, `stock`, `short_desc`, `long_desc`, `status`, `visibility`, `featured`, `main_image`, `shipping_fee`, `ships_from`, `processing_time`, `variants_text`, `rating`, `total_reviews`, `total_sales`, `views`, `created_at`, `updated_at`, `published_at`) VALUES
(1, 1, 'Ankara Panel Hoodie', 'ankara-panel-hoodie', 'ANK-HOOD-001', 'Fashion', 18500.00, NULL, 12, 'Handmade Ankara hoodie with premium fabric', 'This handcrafted Ankara panel hoodie is made by local artisans in Lagos. Features premium cotton blend, reinforced stitching, and vibrant African prints.', 'active', 'public', 1, NULL, NULL, NULL, NULL, NULL, 4.90, 45, 0, 620, '2025-12-16 12:40:29', NULL, NULL),
(2, 1, 'Naija Drip Tee', 'naija-drip-tee', 'TEE-NG-002', 'Fashion', 7500.00, NULL, 8, 'Classic Nigerian streetwear tee', 'Comfortable cotton tee with bold Nigerian-inspired graphics. Perfect for everyday wear.', 'active', 'public', 1, NULL, NULL, NULL, NULL, NULL, 4.70, 63, 0, 810, '2025-12-16 12:40:29', NULL, NULL),
(3, 2, 'Shea Butter Glow Kit', 'shea-butter-glow-kit', 'BEAUTY-KIT-01', 'Beauty', 9900.00, NULL, 34, 'Natural skincare set with shea butter', 'Complete skincare kit featuring raw shea butter, coconut oil, and natural extracts. Perfect for moisturizing and rejuvenating skin.', 'active', 'public', 1, NULL, NULL, NULL, NULL, NULL, 4.80, 78, 0, 550, '2025-12-16 12:40:29', NULL, NULL),
(4, 3, 'Wireless Earbuds Pro', 'wireless-earbuds-pro', 'TECH-EAR-001', 'Electronics', 14200.00, NULL, 25, 'Noise cancelling wireless earbuds', 'Premium wireless earbuds with active noise cancellation, 24-hour battery life, and crystal-clear audio.', 'active', 'public', 1, NULL, NULL, NULL, NULL, NULL, 4.50, 92, 0, 1200, '2025-12-16 12:40:29', NULL, NULL);

---
--- New activity table
---

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL,
  `activity` text NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_brand_id` (`brand_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `product`
--
DELIMITER $$
CREATE TRIGGER `after_product_delete` AFTER DELETE ON `product` FOR EACH ROW BEGIN
    UPDATE Brand 
    SET products_count = products_count - 1 
    WHERE id = OLD.brand_id AND products_count > 0;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_product_insert` AFTER INSERT ON `product` FOR EACH ROW BEGIN
    UPDATE Brand 
    SET products_count = products_count + 1 
    WHERE id = NEW.brand_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `productimage`
--

CREATE TABLE `productimage` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `title` varchar(200) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure for view `activeproducts`
--
DROP TABLE IF EXISTS `activeproducts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `activeproducts`  AS SELECT `p`.`id` AS `id`, `p`.`brand_id` AS `brand_id`, `p`.`name` AS `name`, `p`.`slug` AS `slug`, `p`.`sku` AS `sku`, `p`.`category` AS `category`, `p`.`price` AS `price`, `p`.`compare_at_price` AS `compare_at_price`, `p`.`stock` AS `stock`, `p`.`short_desc` AS `short_desc`, `p`.`long_desc` AS `long_desc`, `p`.`status` AS `status`, `p`.`visibility` AS `visibility`, `p`.`featured` AS `featured`, `p`.`main_image` AS `main_image`, `p`.`shipping_fee` AS `shipping_fee`, `p`.`ships_from` AS `ships_from`, `p`.`processing_time` AS `processing_time`, `p`.`variants_text` AS `variants_text`, `p`.`rating` AS `rating`, `p`.`total_reviews` AS `total_reviews`, `p`.`total_sales` AS `total_sales`, `p`.`views` AS `views`, `p`.`created_at` AS `created_at`, `p`.`updated_at` AS `updated_at`, `p`.`published_at` AS `published_at`, `b`.`brand_name` AS `brand_name`, `b`.`slug` AS `brand_slug`, `b`.`rating` AS `brand_rating`, `b`.`location` AS `brand_location` FROM (`product` `p` join `brand` `b` on(`p`.`brand_id` = `b`.`id`)) WHERE `p`.`status` = 'active' AND `p`.`visibility` = 'public' AND `b`.`status` = 'active' ;

-- --------------------------------------------------------

--
-- Structure for view `brandperformance`
--
DROP TABLE IF EXISTS `brandperformance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `brandperformance`  AS SELECT `b`.`id` AS `id`, `b`.`brand_name` AS `brand_name`, `b`.`slug` AS `slug`, `b`.`category` AS `category`, `b`.`rating` AS `rating`, `b`.`total_reviews` AS `total_reviews`, `b`.`followers` AS `followers`, `b`.`products_count` AS `products_count`, count(distinct `o`.`id`) AS `total_orders`, coalesce(sum(`o`.`total`),0) AS `total_revenue` FROM (`brand` `b` left join `order` `o` on(`b`.`id` = `o`.`brand_id` and `o`.`status` in ('paid','shipped','delivered'))) WHERE `b`.`status` = 'active' GROUP BY `b`.`id` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_buyer_id` (`buyer_id`),
  ADD KEY `idx_is_default` (`is_default`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_verified` (`verified`),
  ADD KEY `idx_featured` (`featured`),
  ADD KEY `idx_rating` (`rating`),
  ADD KEY `idx_created_at` (`created_at`);
ALTER TABLE `brand` ADD FULLTEXT KEY `idx_search` (`brand_name`,`tagline`,`bio`);

--
-- Indexes for table `brandfollower`
--
ALTER TABLE `brandfollower`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_follower` (`buyer_id`,`brand_id`),
  ADD KEY `idx_buyer_id` (`buyer_id`),
  ADD KEY `idx_brand_id` (`brand_id`);

--
-- Indexes for table `buyer`
--
ALTER TABLE `buyer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_item` (`buyer_id`,`product_id`),
  ADD KEY `idx_buyer_id` (`buyer_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_type`,`user_id`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_order_number` (`order_number`),
  ADD KEY `idx_buyer_id` (`buyer_id`),
  ADD KEY `idx_brand_id` (`brand_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_customer_email` (`customer_email`),
  ADD KEY `idx_order_buyer_status` (`buyer_id`,`status`),
  ADD KEY `idx_order_brand_status` (`brand_id`,`status`);

--
-- Indexes for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_brand_id` (`brand_id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_visibility` (`visibility`),
  ADD KEY `idx_featured` (`featured`),
  ADD KEY `idx_price` (`price`),
  ADD KEY `idx_rating` (`rating`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_stock` (`stock`),
  ADD KEY `idx_product_brand_status` (`brand_id`,`status`,`visibility`),
  ADD KEY `idx_product_featured_status` (`featured`,`status`,`visibility`);
ALTER TABLE `product` ADD FULLTEXT KEY `idx_search` (`name`,`short_desc`,`long_desc`,`sku`);

--
-- Indexes for table `productimage`
--
ALTER TABLE `productimage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_sort_order` (`sort_order`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_buyer_id` (`buyer_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_rating` (`rating`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wishlist_item` (`buyer_id`,`product_id`),
  ADD KEY `idx_buyer_id` (`buyer_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `brandfollower`
--
ALTER TABLE `brandfollower`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buyer`
--
ALTER TABLE `buyer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderitem`
--
ALTER TABLE `orderitem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `productimage`
--
ALTER TABLE `productimage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `brandfollower`
--
ALTER TABLE `brandfollower`
  ADD CONSTRAINT `brandfollower_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `brandfollower_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderitem_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `productimage`
--
ALTER TABLE `productimage`
  ADD CONSTRAINT `productimage_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
