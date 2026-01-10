-- ============================================
-- XÓA CÁC BẢNG KHÔNG CẦN THIẾT
-- Chạy code này trong phpMyAdmin
-- ============================================

-- Xóa các bảng theo thứ tự (bảng con trước, bảng cha sau)

-- 1. Xóa bảng collection_product (bảng pivot)
DROP TABLE IF EXISTS collection_product;

-- 2. Xóa bảng product_images
DROP TABLE IF EXISTS product_images;

-- 3. Xóa bảng collections
DROP TABLE IF EXISTS collections;

-- 4. Xóa bảng customers
DROP TABLE IF EXISTS customers;

-- HOÀN THÀNH! Đã xóa 4 bảng không cần thiết.
