<?php
use Illuminate\Support\Facades\DB;

// Disable FK checks
DB::statement('SET FOREIGN_KEY_CHECKS=0');

// Drop if exists first to be sure
DB::statement('DROP VIEW IF EXISTS activeproducts');
DB::statement('DROP TRIGGER IF EXISTS after_product_insert');
DB::statement('DROP TRIGGER IF EXISTS after_product_delete');
DB::statement('DROP TABLE IF EXISTS product');

// Create table
$createSql = "CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `published_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_brand_id` (`brand_id`),
  KEY `idx_slug` (`slug`),
  KEY `idx_category` (`category`),
  KEY `idx_status` (`status`),
  KEY `idx_visibility` (`visibility`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
DB::statement($createSql);

// Insert data
$insertSql = "INSERT INTO `product` (`id`, `brand_id`, `name`, `slug`, `sku`, `category`, `price`, `compare_at_price`, `stock`, `short_desc`, `long_desc`, `status`, `visibility`, `featured`, `main_image`, `shipping_fee`, `ships_from`, `processing_time`, `variants_text`, `rating`, `total_reviews`, `total_sales`, `views`, `created_at`, `updated_at`, `published_at`) VALUES
(1, 1, 'Ankara Panel Hoodie', 'ankara-panel-hoodie', 'ANK-HOOD-001', 'Fashion', 18500.00, NULL, 12, 'Handmade Ankara hoodie with premium fabric', 'This handcrafted Ankara panel hoodie is made by local artisans in Lagos. Features premium cotton blend, reinforced stitching, and vibrant African prints.', 'active', 'public', 1, NULL, NULL, NULL, NULL, NULL, 4.90, 45, 0, 620, '2025-12-16 12:40:29', NULL, NULL),
(2, 1, 'Naija Drip Tee', 'naija-drip-tee', 'TEE-NG-002', 'Fashion', 7500.00, NULL, 8, 'Classic Nigerian streetwear tee', 'Comfortable cotton tee with bold Nigerian-inspired graphics. Perfect for everyday wear.', 'active', 'public', 1, NULL, NULL, NULL, NULL, NULL, 4.70, 63, 0, 810, '2025-12-16 12:40:29', NULL, NULL),
(3, 2, 'Shea Butter Glow Kit', 'shea-butter-glow-kit', 'BEAUTY-KIT-01', 'Beauty', 9900.00, NULL, 34, 'Natural skincare set with shea butter', 'Complete skincare kit featuring raw shea butter, coconut oil, and natural extracts. Perfect for moisturizing and rejuvenating skin.', 'active', 'public', 1, NULL, NULL, NULL, NULL, NULL, 4.80, 78, 0, 550, '2025-12-16 12:40:29', NULL, NULL),
(4, 3, 'Wireless Earbuds Pro', 'wireless-earbuds-pro', 'TECH-EAR-001', 'Electronics', 14200.00, NULL, 25, 'Noise cancelling wireless earbuds', 'Premium wireless earbuds with active noise cancellation, 24-hour battery life, and crystal-clear audio.', 'active', 'public', 1, NULL, NULL, NULL, NULL, NULL, 4.50, 92, 0, 1200, '2025-12-16 12:40:29', NULL, NULL)";
DB::statement($insertSql);

// Triggers
DB::unprepared("CREATE TRIGGER `after_product_insert` AFTER INSERT ON `product` FOR EACH ROW BEGIN
    UPDATE brand SET products_count = products_count + 1 WHERE id = NEW.brand_id;
END");
DB::unprepared("CREATE TRIGGER `after_product_delete` AFTER DELETE ON `product` FOR EACH ROW BEGIN
    UPDATE brand SET products_count = products_count - 1 WHERE id = OLD.brand_id AND products_count > 0;
END");

// View
$viewSql = "CREATE OR REPLACE VIEW `activeproducts` AS SELECT `p`.`id` AS `id`, `p`.`brand_id` AS `brand_id`, `p`.`name` AS `name`, `p`.`slug` AS `slug`, `p`.`sku` AS `sku`, `p`.`category` AS `category`, `p`.`price` AS `price`, `p`.`compare_at_price` AS `compare_at_price`, `p`.`stock` AS `stock`, `p`.`short_desc` AS `short_desc`, `p`.`long_desc` AS `long_desc`, `p`.`status` AS `status`, `p`.`visibility` AS `visibility`, `p`.`featured` AS `featured`, `p`.`main_image` AS `main_image`, `p`.`shipping_fee` AS `shipping_fee`, `p`.`ships_from` AS `ships_from`, `p`.`processing_time` AS `processing_time`, `p`.`variants_text` AS `variants_text`, `p`.`rating` AS `rating`, `p`.`total_reviews` AS `total_reviews`, `p`.`total_sales` AS `total_sales`, `p`.`views` AS `views`, `p`.`created_at` AS `created_at`, `p`.`updated_at` AS `updated_at`, `p`.`published_at` AS `published_at`, `b`.`brand_name` AS `brand_name`, `b`.`slug` AS `brand_slug`, `b`.`rating` AS `brand_rating`, `b`.`location` AS `brand_location` FROM (`product` `p` join `brand` `b` on(`p`.`brand_id` = `b`.`id`)) WHERE `p`.`status` = 'active' AND `p`.`visibility` = 'public' AND `b`.`status` = 'active'";
DB::statement($viewSql);

DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "Restoration Complete!\n";
