-- ====================================
-- DATABASE: webhalophp
-- Date: 2026-01-10
-- ====================================

DROP DATABASE IF EXISTS `webhalophp`;
CREATE DATABASE `webhalophp` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `webhalophp`;

-- ====================================
-- TABLE: users
-- ====================================
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- TABLE: password_reset_tokens
-- ====================================
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- TABLE: failed_jobs
-- ====================================
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- TABLE: personal_access_tokens
-- ====================================
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- TABLE: categories
-- ====================================
CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- TABLE: products
-- ====================================
CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `short_description` varchar(500) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT '0',
  `platform` varchar(100) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_new` tinyint(1) NOT NULL DEFAULT '0',
  `is_preorder` tinyint(1) NOT NULL DEFAULT '0',
  `release_date` date DEFAULT NULL,
  `status` enum('active','inactive','out_of_stock') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- TABLE: orders
-- ====================================
CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `shipping_fee` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `shipping_address` text,
  `note` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- TABLE: order_items
-- ====================================
CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- TABLE: product_images
-- ====================================
CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- TABLE: collections
-- ====================================
CREATE TABLE `collections` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collections_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- TABLE: collection_product
-- ====================================
CREATE TABLE `collection_product` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `collection_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collection_product_collection_id_foreign` (`collection_id`),
  KEY `collection_product_product_id_foreign` (`product_id`),
  CONSTRAINT `collection_product_collection_id_foreign` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `collection_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- TABLE: customers
-- ====================================
CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- TABLE: migrations
-- ====================================
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- INSERT DATA: categories
-- ====================================
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `order`, `status`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'PlayStation', 'playstation', 'Máy PlayStation từ PS2 đến PS5', NULL, 1, 'active', 1, NOW(), NOW()),
(2, 'PlayStation 2 (PS2)', 'playstation-2', 'Máy PlayStation 2 chính hãng', 1, 1, 'active', 1, NOW(), NOW()),
(3, 'PlayStation 3 (PS3)', 'playstation-3', 'Máy PlayStation 3 chính hãng', 1, 2, 'active', 1, NOW(), NOW()),
(4, 'PlayStation 4 (PS4)', 'playstation-4', 'Máy PlayStation 4 và PS4 Pro', 1, 3, 'active', 1, NOW(), NOW()),
(5, 'PlayStation 5 (PS5)', 'playstation-5', 'Máy PlayStation 5 thế hệ mới nhất', 1, 4, 'active', 1, NOW(), NOW()),
(6, 'Game PlayStation', 'game-playstation', 'Game cho các máy PlayStation', NULL, 2, 'active', 1, NOW(), NOW()),
(7, 'Máy Nintendo Switch', 'may-nintendo-switch', 'Máy Nintendo Switch và Switch 2', NULL, 3, 'active', 1, NOW(), NOW()),
(8, 'Game Nintendo Switch', 'game-nintendo-switch', 'Game cho Nintendo Switch', NULL, 4, 'active', 1, NOW(), NOW()),
(9, 'iPhone', 'iphone', 'iPhone các dòng', NULL, 5, 'active', 1, NOW(), NOW()),
(10, 'Máy Xbox', 'may-xbox', 'Máy Xbox Series', NULL, 6, 'active', 1, NOW(), NOW()),
(11, 'iPad', 'ipad', 'iPad các dòng', NULL, 7, 'active', 1, NOW(), NOW()),
(12, 'Tay cầm', 'tay-cam', 'Tay cầm chơi game', NULL, 8, 'active', 1, NOW(), NOW()),
(13, 'Phụ kiện', 'phu-kien', 'Phụ kiện gaming', NULL, 9, 'active', 1, NOW(), NOW());

-- ====================================
-- INSERT DATA: products
-- ====================================
INSERT INTO `products` (`id`, `name`, `slug`, `description`, `short_description`, `price`, `sale_price`, `stock`, `category_id`, `platform`, `is_featured`, `is_new`, `is_preorder`, `release_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'PlayStation 2 Slim - Secondhand', 'playstation-2-slim-secondhand', 'Máy PS2 Slim đã qua sử dụng. Hoạt động tốt, bảo hành 3 tháng.', 'PS2 Slim - Đã qua sử dụng', 1200000, NULL, 5, 2, 'PS2', 0, 0, 0, NULL, 'active', NOW(), NOW()),
(2, 'PlayStation 3 Slim 500GB', 'playstation-3-slim-500gb', 'Máy PS3 Slim 500GB. Chơi game và xem phim Blu-ray.', 'PS3 Slim 500GB', 3500000, 3200000, 8, 3, 'PS3', 0, 0, 0, NULL, 'active', NOW(), NOW()),
(3, 'PlayStation 4 Slim 1TB', 'playstation-4-slim-1tb', 'Máy PS4 Slim 1TB. Bảo hành 12 tháng.', 'PS4 Slim 1TB - Chính hãng', 7500000, NULL, 12, 4, 'PS4', 1, 1, 0, NULL, 'active', NOW(), NOW()),
(4, 'PlayStation 4 Pro 1TB', 'playstation-4-pro-1tb', 'Máy PS4 Pro 1TB hỗ trợ 4K. Bảo hành 12 tháng.', 'PS4 Pro 1TB - Chính hãng', 9500000, 8900000, 6, 4, 'PS4', 1, 1, 0, NULL, 'active', NOW(), NOW()),
(5, 'PlayStation 5 Slim Digital Edition', 'playstation-5-slim-digital-edition', 'Máy PS5 Slim phiên bản Digital - Không đĩa. Bảo hành chính hãng 12 tháng.', 'PS5 Slim Digital - Chính hãng', 10500000, NULL, 10, 5, 'PS5', 1, 1, 0, NULL, 'active', NOW(), NOW()),
(6, 'PlayStation 5 Slim Standard Edition', 'playstation-5-slim-standard', 'Máy PS5 Slim phiên bản Standard có ổ đĩa. Bảo hành chính hãng 12 tháng.', 'PS5 Slim Standard - Chính hãng', 11990000, NULL, 8, 5, 'PS5', 1, 1, 0, NULL, 'active', NOW(), NOW()),
(7, 'PlayStation 5 Pro', 'playstation-5-pro', 'Máy PS5 Pro với GPU nâng cấp, hỗ trợ 8K. Bảo hành chính hãng 12 tháng.', 'PS5 Pro - 8K Ready', 15990000, NULL, 5, 5, 'PS5', 1, 1, 0, NULL, 'active', NOW(), NOW()),
(8, 'Game Ghost of Yotei - PS5', 'game-ghost-of-yotei-ps5', 'Game Ghost of Yotei cho PS5 - Phần tiếp theo của Ghost of Tsushima.', 'Ghost of Yotei - PS5', 1950000, NULL, 50, 6, 'PS5', 0, 0, 1, '2026-05-15', 'active', NOW(), NOW()),
(9, 'Game Final Fantasy VII Remake Part 2 - PS5', 'game-ff7-remake-part-2-ps5', 'Game Final Fantasy VII Remake Part 2 cho PS5.', 'FF7 Remake Part 2 - PS5', 1750000, NULL, 30, 6, 'PS5', 1, 0, 0, NULL, 'active', NOW(), NOW()),
(10, 'Game God of War Ragnarok - PS5', 'game-god-of-war-ragnarok-ps5', 'Game God of War Ragnarok cho PS5.', 'God of War Ragnarok', 1650000, 1450000, 25, 6, 'PS5', 1, 0, 0, NULL, 'active', NOW(), NOW()),
(11, 'Nintendo Switch 2 - OLED Model', 'nintendo-switch-2-oled-model', 'Nintendo Switch 2 với màn hình OLED lớn hơn. Bảo hành 12 tháng.', 'Switch 2 - OLED', 9800000, NULL, 15, 7, 'Nintendo Switch', 1, 1, 0, NULL, 'active', NOW(), NOW()),
(12, 'Game Super Mario Odyssey 2 - Switch 2', 'game-super-mario-odyssey-2-switch-2', 'Game Super Mario Odyssey 2 cho Nintendo Switch 2.', 'Super Mario Odyssey 2', 1450000, NULL, 40, 8, 'Nintendo Switch', 0, 0, 0, NULL, 'active', NOW(), NOW()),
(13, 'Game Pokemon Legends: Z-A - Switch 2', 'game-pokemon-legends-z-a-switch-2', 'Game Pokemon Legends: Z-A cho Nintendo Switch 2.', 'Pokemon Legends Z-A', 1450000, NULL, 25, 8, 'Nintendo Switch', 0, 0, 1, '2026-06-20', 'active', NOW(), NOW()),
(14, 'PlayStation 5 DualSense Edge Controller', 'ps5-dualsense-edge-controller', 'Tay cầm PS5 DualSense Edge cao cấp. Bảo hành 12 tháng.', 'DualSense Edge - Chính hãng', 5500000, NULL, 15, 12, 'PS5', 0, 1, 0, NULL, 'active', NOW(), NOW()),
(15, 'iPhone 17 Pro Max - Deep Blue VN', 'iphone-17-pro-max-deep-blue-vn', 'iPhone 17 Pro Max màu Deep Blue - Chính hãng VN/A. Bảo hành 12 tháng.', 'iPhone 17 Pro Max - VN/A', 37800000, NULL, 5, 9, 'iPhone', 1, 0, 0, NULL, 'active', NOW(), NOW()),
(16, 'iPhone Air - Space Black VN', 'iphone-air-space-black-vn', 'iPhone Air màu Space Black - Chính hãng VN/A. Bảo hành 12 tháng.', 'iPhone Air - VN/A', 28800000, NULL, 8, 9, 'iPhone', 1, 0, 0, NULL, 'active', NOW(), NOW()),
(17, 'PlayStation 5 Slim 2TB - Digital Edition', 'playstation-5-slim-2tb-digital', 'PS5 Slim 2TB Digital Edition - Dung lượng lưu trữ khủng. Chính hãng Sony.', 'PS5 Slim 2TB - Digital', 35800000, NULL, 4, 5, 'PS5', 0, 1, 0, NULL, 'active', NOW(), NOW()),
(18, 'Xbox Series X 1TB', 'xbox-series-x-1tb', 'Máy Xbox Series X 1TB. Bảo hành 12 tháng.', 'Xbox Series X', 12500000, NULL, 7, 10, 'Xbox', 0, 0, 0, NULL, 'active', NOW(), NOW()),
(19, 'PS5 DualSense - Wireless Controller', 'ps5-dualsense-wireless-controller', 'Tay cầm PS5 DualSense chính hãng. Bảo hành 12 tháng.', 'DualSense Controller', 1750000, NULL, 30, 12, 'PS5', 0, 0, 0, NULL, 'active', NOW(), NOW()),
(20, 'Xbox Wireless Controller - Carbon Black', 'xbox-wireless-controller-carbon-black', 'Tay cầm Xbox màu Carbon Black. Bảo hành 12 tháng.', 'Xbox Controller', 1550000, NULL, 25, 12, 'Xbox', 0, 0, 0, NULL, 'active', NOW(), NOW()),
(21, 'PS5 DualSense Charging Station', 'ps5-dualsense-charging-station', 'Dock sạc tay cầm PS5 chính hãng. Sạc được 2 tay cầm cùng lúc.', 'Charging Station', 850000, NULL, 20, 13, 'PS5', 0, 0, 0, NULL, 'active', NOW(), NOW()),
(22, 'HDMI 2.1 Cable - 8K/4K 120Hz', 'hdmi-21-cable-8k-4k-120hz', 'Dây HDMI 2.1 hỗ trợ 8K/4K 120Hz cho PS5, Xbox Series X.', 'HDMI 2.1 Cable', 450000, NULL, 50, 13, 'Multi', 0, 0, 0, NULL, 'active', NOW(), NOW()),
(23, 'Game Final Fantasy XVI - PS5', 'game-final-fantasy-xvi-ps5', 'Game Final Fantasy XVI cho PS5.', 'Final Fantasy XVI', 1750000, NULL, 20, 6, 'PS5', 0, 0, 1, '2026-07-15', 'active', NOW(), NOW()),
(24, 'Game The Legend of Zelda: Echoes of Wisdom - Switch', 'game-zelda-echoes-wisdom-switch', 'Game The Legend of Zelda: Echoes of Wisdom cho Nintendo Switch.', 'Zelda: Echoes of Wisdom', 1550000, NULL, 30, 8, 'Nintendo Switch', 0, 0, 1, '2026-08-10', 'active', NOW(), NOW()),
(25, 'Game Resident Evil 9 - PS5', 'game-resident-evil-9-ps5', 'Game Resident Evil 9 cho PS5.', 'Resident Evil 9', 1850000, NULL, 15, 6, 'PS5', 0, 0, 1, '2026-09-05', 'active', NOW(), NOW()),
(26, 'Game Spider-Man 3 - PS5', 'game-spider-man-3-ps5', 'Game Spider-Man 3 cho PS5.', 'Spider-Man 3', 1950000, NULL, 25, 6, 'PS5', 0, 0, 1, '2026-10-20', 'active', NOW(), NOW()),
(27, 'Game Horizon Forbidden West Complete - PS5', 'game-horizon-forbidden-west-complete-ps5', 'Game Horizon Forbidden West Complete Edition cho PS5.', 'Horizon Forbidden West', 1650000, NULL, 20, 6, 'PS5', 0, 0, 1, '2026-11-15', 'active', NOW(), NOW()),
(28, 'Game Metroid Prime 4: Beyond - Switch', 'game-metroid-prime-4-switch', 'Game Metroid Prime 4: Beyond cho Nintendo Switch.', 'Metroid Prime 4', 1350000, NULL, 30, 8, 'Nintendo Switch', 0, 0, 1, '2026-12-05', 'active', NOW(), NOW()),
(29, 'Game Death Stranding 2: On The Beach - PS5', 'game-death-stranding-2-ps5', 'Game Death Stranding 2: On The Beach cho PS5.', 'Death Stranding 2', 1850000, NULL, 18, 6, 'PS5', 0, 0, 1, '2026-12-20', 'active', NOW(), NOW());

-- ====================================
-- AUTO INCREMENT
-- ====================================
ALTER TABLE `categories` AUTO_INCREMENT = 14;
ALTER TABLE `products` AUTO_INCREMENT = 30;
