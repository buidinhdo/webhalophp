-- ============================================
-- CHẠY CODE NÀY TRONG PHPMYADMIN
-- ============================================

-- Bước 1: Xóa bảng cũ (nếu có) để tạo lại
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;

-- Bước 2: Tạo bảng orders với đầy đủ các cột
CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    order_number VARCHAR(20) UNIQUE NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_address TEXT NOT NULL,
    total_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
    payment_method VARCHAR(50) DEFAULT 'cod',
    payment_status VARCHAR(50) DEFAULT 'pending',
    order_status VARCHAR(50) DEFAULT 'pending',
    notes TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bước 3: Tạo bảng order_items
CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_image VARCHAR(255) NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(15,2) NOT NULL,
    subtotal DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bước 4: Thêm dữ liệu đơn hàng mẫu
INSERT INTO orders (user_id, order_number, customer_name, customer_email, customer_phone, customer_address, total_amount, payment_method, payment_status, order_status, created_at, updated_at) VALUES
(1, 'ORD-20260110001', 'Nguyễn Văn A', 'user1@example.com', '0901234567', '123 Đường ABC, Quận 1, TP.HCM', 15990000, 'cod', 'pending', 'pending', NOW(), NOW()),
(2, 'ORD-20260110002', 'Trần Thị B', 'user2@example.com', '0912345678', '456 Đường XYZ, Quận 3, TP.HCM', 29990000, 'bank_transfer', 'paid', 'processing', NOW(), NOW());

-- Bước 5: Thêm chi tiết đơn hàng mẫu
INSERT INTO order_items (order_id, product_id, product_name, product_image, quantity, price, subtotal, created_at, updated_at) VALUES
(1, 1, 'PlayStation 5 Pro Console', 'products/sanpham1.jpg', 1, 15990000, 15990000, NOW(), NOW()),
(2, 2, 'PlayStation 5 Pro Digital Edition', 'products/sanpham2.jpg', 1, 29990000, 29990000, NOW(), NOW());

-- HOÀN THÀNH!
