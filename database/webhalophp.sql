-- ===================================================
-- DATABASE: webhalophp
-- Cơ sở dữ liệu cho website HaloShop
-- ===================================================

-- Tạo database
DROP DATABASE IF EXISTS `webhalophp`;
CREATE DATABASE `webhalophp` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `webhalophp`;

-- ===================================================
-- TABLE: categories (Danh mục sản phẩm)
-- ===================================================
CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Tên danh mục',
  `slug` varchar(255) NOT NULL COMMENT 'Đường dẫn thân thiện',
  `description` text DEFAULT NULL COMMENT 'Mô tả danh mục',
  `image` varchar(255) DEFAULT NULL COMMENT 'Hình ảnh danh mục',
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ID danh mục cha',
  `order` int(11) NOT NULL DEFAULT 0 COMMENT 'Thứ tự hiển thị',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái kích hoạt',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: products (Sản phẩm)
-- ===================================================
CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Tên sản phẩm',
  `slug` varchar(255) NOT NULL COMMENT 'Đường dẫn thân thiện',
  `description` text DEFAULT NULL COMMENT 'Mô tả chi tiết',
  `short_description` text DEFAULT NULL COMMENT 'Mô tả ngắn',
  `price` decimal(15,2) NOT NULL COMMENT 'Giá gốc',
  `sale_price` decimal(15,2) DEFAULT NULL COMMENT 'Giá khuyến mãi',
  `sku` varchar(255) DEFAULT NULL COMMENT 'Mã SKU',
  `stock` int(11) NOT NULL DEFAULT 0 COMMENT 'Số lượng tồn kho',
  `image` varchar(255) DEFAULT NULL COMMENT 'Hình ảnh chính',
  `category_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ID danh mục',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Sản phẩm nổi bật',
  `is_new` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Sản phẩm mới',
  `is_preorder` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Sản phẩm đặt trước',
  `release_date` date DEFAULT NULL COMMENT 'Ngày phát hành',
  `platform` varchar(255) DEFAULT NULL COMMENT 'Nền tảng (PS5, Switch, Xbox, iPhone...)',
  `status` varchar(255) NOT NULL DEFAULT 'active' COMMENT 'Trạng thái (active, inactive, out_of_stock)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: product_images (Hình ảnh sản phẩm)
-- ===================================================
CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID sản phẩm',
  `image_path` varchar(255) NOT NULL COMMENT 'Đường dẫn hình ảnh',
  `order` int(11) NOT NULL DEFAULT 0 COMMENT 'Thứ tự hiển thị',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: collections (Bộ sưu tập)
-- ===================================================
CREATE TABLE `collections` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Tên bộ sưu tập',
  `slug` varchar(255) NOT NULL COMMENT 'Đường dẫn thân thiện',
  `description` text DEFAULT NULL COMMENT 'Mô tả',
  `image` varchar(255) DEFAULT NULL COMMENT 'Hình ảnh',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái kích hoạt',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collections_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: collection_product (Pivot table)
-- ===================================================
CREATE TABLE `collection_product` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `collection_id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID bộ sưu tập',
  `product_id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID sản phẩm',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collection_product_collection_id_foreign` (`collection_id`),
  KEY `collection_product_product_id_foreign` (`product_id`),
  CONSTRAINT `collection_product_collection_id_foreign` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `collection_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: customers (Khách hàng)
-- ===================================================
CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Họ và tên',
  `email` varchar(255) NOT NULL COMMENT 'Email',
  `phone` varchar(255) DEFAULT NULL COMMENT 'Số điện thoại',
  `address` text DEFAULT NULL COMMENT 'Địa chỉ',
  `city` varchar(255) DEFAULT NULL COMMENT 'Thành phố',
  `district` varchar(255) DEFAULT NULL COMMENT 'Quận/Huyện',
  `ward` varchar(255) DEFAULT NULL COMMENT 'Phường/Xã',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: orders (Đơn hàng)
-- ===================================================
CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) NOT NULL COMMENT 'Mã đơn hàng',
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ID khách hàng',
  `subtotal` decimal(15,2) NOT NULL COMMENT 'Tạm tính',
  `shipping_fee` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Phí vận chuyển',
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Giảm giá',
  `total` decimal(15,2) NOT NULL COMMENT 'Tổng cộng',
  `status` varchar(255) NOT NULL DEFAULT 'pending' COMMENT 'Trạng thái (pending, processing, completed, cancelled)',
  `payment_method` varchar(255) DEFAULT NULL COMMENT 'Phương thức thanh toán',
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid' COMMENT 'Trạng thái thanh toán',
  `shipping_address` text DEFAULT NULL COMMENT 'Địa chỉ giao hàng',
  `note` text DEFAULT NULL COMMENT 'Ghi chú',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: order_items (Chi tiết đơn hàng)
-- ===================================================
CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID đơn hàng',
  `product_id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID sản phẩm',
  `quantity` int(11) NOT NULL COMMENT 'Số lượng',
  `price` decimal(15,2) NOT NULL COMMENT 'Đơn giá',
  `total` decimal(15,2) NOT NULL COMMENT 'Thành tiền',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: users (Admin users - Laravel default)
-- ===================================================
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

-- ===================================================
-- TABLE: failed_jobs (Laravel default)
-- ===================================================
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: password_reset_tokens (Laravel default)
-- ===================================================
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- TABLE: personal_access_tokens (Laravel Sanctum)
-- ===================================================
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- DỮ LIỆU MẪU
-- ===================================================

-- Insert Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'PlayStation', 'playstation', 'Máy chơi game PlayStation từ PS2 đến PS5', NULL, 1, 1, NOW(), NOW()),
(2, 'PlayStation 2', 'playstation-2', 'Máy PlayStation 2 chính hãng', 1, 2, 1, NOW(), NOW()),
(3, 'PlayStation 3', 'playstation-3', 'Máy PlayStation 3 chính hãng', 1, 3, 1, NOW(), NOW()),
(4, 'PlayStation 4', 'playstation-4', 'Máy PlayStation 4 chính hãng', 1, 4, 1, NOW(), NOW()),
(5, 'PlayStation 5', 'playstation-5', 'Máy PlayStation 5 chính hãng', 1, 5, 1, NOW(), NOW()),
(6, 'Game PlayStation', 'game-playstation', 'Game cho các dòng máy PlayStation', NULL, 6, 1, NOW(), NOW()),
(7, 'Máy Nintendo Switch', 'may-nintendo-switch', 'Máy Nintendo Switch chính hãng', NULL, 7, 1, NOW(), NOW()),
(8, 'Game Nintendo Switch', 'game-nintendo-switch', 'Game Nintendo Switch chính hãng', NULL, 8, 1, NOW(), NOW()),
(9, 'iPhone', 'iphone', 'iPhone chính hãng VN/A', NULL, 9, 1, NOW(), NOW()),
(10, 'Máy Xbox', 'may-xbox', 'Máy Xbox Series X/S chính hãng', NULL, 10, 1, NOW(), NOW()),
(11, 'iPad', 'ipad', 'iPad chính hãng Apple', NULL, 11, 1, NOW(), NOW()),
(12, 'Tay cầm', 'tay-cam', 'Tay cầm chơi game', NULL, 12, 1, NOW(), NOW()),
(13, 'Phụ kiện', 'phu-kien', 'Phụ kiện gaming', NULL, 13, 1, NOW(), NOW());

-- Insert Products
INSERT INTO `products` (`id`, `name`, `slug`, `description`, `short_description`, `price`, `sale_price`, `stock`, `category_id`, `platform`, `is_featured`, `is_new`, `is_preorder`, `release_date`, `status`, `created_at`, `updated_at`) VALUES
-- PlayStation 2 Products
(1, 'PlayStation 2 Slim Secondhand', 'playstation-2-slim-secondhand', 'Máy PS2 Slim đã qua sử dụng, còn hoạt động tốt. Bảo hành 3 tháng.', 'PS2 Slim - Đã qua sử dụng', 1200000.00, NULL, 5, 2, 'PS2', 0, 0, 0, NULL, 'active', NOW(), NOW()),

-- PlayStation 3 Products
(2, 'PlayStation 3 Slim 500GB', 'playstation-3-slim-500gb', 'Máy PS3 Slim 500GB chính hãng. Bảo hành 6 tháng.', 'PS3 Slim 500GB', 3500000.00, 3200000.00, 8, 3, 'PS3', 0, 0, 0, NULL, 'active', NOW(), NOW()),

-- PlayStation 4 Products
(3, 'PlayStation 4 Slim 1TB', 'playstation-4-slim-1tb', 'Máy PS4 Slim 1TB chính hãng Sony. Bảo hành 12 tháng.', 'PS4 Slim 1TB - Chính hãng', 7500000.00, NULL, 12, 4, 'PS4', 1, 0, 0, NULL, 'active', NOW(), NOW()),
(4, 'PlayStation 4 Pro 1TB', 'playstation-4-pro-1tb', 'Máy PS4 Pro 1TB hỗ trợ 4K. Bảo hành chính hãng 12 tháng.', 'PS4 Pro 1TB - 4K Ready', 9500000.00, 8900000.00, 6, 4, 'PS4', 1, 0, 0, NULL, 'active', NOW(), NOW()),

-- PlayStation 5 Products
(5, 'PlayStation 5 Slim Digital Edition', 'playstation-5-slim-digital-edition', 'Máy PS5 Slim phiên bản Digital - Không đĩa. Bảo hành chính hãng 12 tháng.', 'PS5 Slim Digital - Chính hãng', 10500000.00, NULL, 10, 5, 'PS5', 1, 1, 0, NULL, 'active', NOW(), NOW()),
(6, 'PlayStation 5 Slim Standard Edition', 'playstation-5-slim-standard', 'Máy PS5 Slim bản Standard có ổ đĩa. Bảo hành chính hãng 12 tháng.', 'PS5 Slim Standard - Chính hãng', 11990000.00, NULL, 8, 5, 'PS5', 1, 1, 0, NULL, 'active', NOW(), NOW()),
(7, 'PlayStation 5 Pro', 'playstation-5-pro', 'Máy PS5 Pro - Hiệu suất cao nhất với hỗ trợ 8K. Bảo hành chính hãng 12 tháng.', 'PS5 Pro - Chính hãng', 19450000.00, NULL, 5, 5, 'PS5', 1, 1, 0, NULL, 'active', NOW(), NOW()),

-- PlayStation Games
(8, 'Game Ghost of Yotei - PS5', 'game-ghost-of-yotei-ps5', 'Game Ghost of Yotei cho PS5. Phần tiếp theo của Ghost of Tsushima.', 'Game nhập vai hành động', 1850000.00, NULL, 20, 6, 'PS5', 0, 0, 1, '2026-05-15', 'active', NOW(), NOW()),
(9, 'Game Final Fantasy VII Remake Intergrade - PS5', 'game-final-fantasy-vii-remake-ps5', 'Game Final Fantasy VII Remake Intergrade cho PS5.', 'Game nhập vai JRPG', 1650000.00, 1450000.00, 15, 6, 'PS5', 1, 0, 0, NULL, 'active', NOW(), NOW()),
(10, 'Game God of War Ragnarök - PS4/PS5', 'game-god-of-war-ragnarok', 'Game God of War Ragnarök cho PS4 và PS5.', 'Game hành động phiêu lưu', 1550000.00, NULL, 18, 6, 'PS5', 1, 0, 0, NULL, 'active', NOW(), NOW()),

-- Nintendo Switch Products
(11, 'Nintendo Switch 2', 'nintendo-switch-2', 'Máy Nintendo Switch 2 thế hệ mới nhất. Bảo hành 12 tháng.', 'Switch 2 - Chính hãng', 12350000.00, NULL, 8, 7, 'Nintendo Switch', 1, 1, 0, NULL, 'active', NOW(), NOW()),
(12, 'Game Pokemon Legends: Z-A - Nintendo Switch 2', 'game-pokemon-legends-z-a-switch-2', 'Game Pokemon Legends: Z-A cho Nintendo Switch 2.', 'Game Pokemon thế hệ mới', 1450000.00, NULL, 25, 8, 'Nintendo Switch', 0, 0, 1, '2026-06-20', 'active', NOW(), NOW()),
(13, 'Game Metroid Prime 4: Beyond - Nintendo Switch', 'game-metroid-prime-4-switch', 'Game Metroid Prime 4: Beyond cho Nintendo Switch.', 'Game bắn súng nhập vai', 1350000.00, NULL, 30, 8, 'Nintendo Switch', 0, 0, 0, NULL, 'active', NOW(), NOW()),

-- iPhone Products
(14, 'iPhone 17 Pro Max - Deep Blue VN', 'iphone-17-pro-max-deep-blue-vn', 'iPhone 17 Pro Max màu Deep Blue - Chính hãng VN/A. Bảo hành 12 tháng.', 'iPhone 17 Pro Max - VN/A', 37800000.00, NULL, 5, 9, 'iPhone', 1, 1, 0, NULL, 'active', NOW(), NOW()),
(15, 'iPhone Air - Space Black VN', 'iphone-air-space-black-vn', 'iPhone Air màu Space Black - Chính hãng VN/A. Bảo hành 12 tháng.', 'iPhone Air - VN/A', 28800000.00, NULL, 8, 9, 'iPhone', 1, 1, 0, NULL, 'active', NOW(), NOW()),

-- Xbox Products
(16, 'Xbox Series X', 'xbox-series-x', 'Máy Xbox Series X chính hãng. Bảo hành 12 tháng.', 'Xbox Series X - Chính hãng', 13990000.00, NULL, 6, 10, 'Xbox', 0, 0, 0, NULL, 'active', NOW(), NOW()),

-- iPad Products
(17, 'iPad Pro M5 - 13 inch', 'ipad-pro-m5-13-inch', 'iPad Pro M5 13 inch - Chính hãng VN/A. Bảo hành 12 tháng.', 'iPad Pro M5 - VN/A', 35800000.00, NULL, 4, 11, 'iPad', 0, 1, 0, NULL, 'active', NOW(), NOW()),

-- Controller Products
(18, 'PS5 DualSense - Wireless Controller', 'ps5-dualsense-wireless-controller', 'Tay cầm PS5 DualSense chính hãng Sony. Bảo hành 12 tháng.', 'DualSense - Chính hãng', 1850000.00, 1650000.00, 20, 12, 'PS5', 0, 0, 0, NULL, 'active', NOW(), NOW()),
(19, 'Xbox Series Wireless Controller - Robot White', 'xbox-series-controller-robot-white', 'Tay cầm Xbox Series X/S màu trắng. Chính hãng Microsoft.', 'Xbox Controller - Chính hãng', 1350000.00, NULL, 15, 12, 'Xbox', 0, 0, 0, NULL, 'active', NOW(), NOW()),

-- Accessories Products
(20, 'PS5 Charging Station - DualSense', 'ps5-charging-station-dualsense', 'Đế sạc tay cầm PS5 DualSense chính hãng Sony.', 'Đế sạc DualSense', 750000.00, NULL, 25, 13, 'PS5', 0, 0, 0, NULL, 'active', NOW(), NOW()),
(21, 'HDMI 2.1 Cable - 8K Ultra High Speed', 'hdmi-2-1-cable-8k', 'Cáp HDMI 2.1 hỗ trợ 8K 60Hz / 4K 120Hz. Dài 2m.', 'Cáp HDMI 2.1 - 2m', 350000.00, NULL, 40, 13, 'Universal', 0, 0, 0, NULL, 'active', NOW(), NOW());

-- ===================================================
-- END OF SQL SCRIPT
-- ===================================================
