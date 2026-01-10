# Import Database webhalophp

## Cách 1: Import file SQL (Nhanh nhất - Khuyến nghị)

### Sử dụng Command Line
```bash
mysql -u root -p < database/webhalophp.sql
```

### Sử dụng phpMyAdmin
1. Mở phpMyAdmin
2. Click tab "Import"
3. Chọn file: `database/webhalophp.sql`
4. Click "Go" để import

### Sử dụng MySQL Workbench
1. Mở MySQL Workbench
2. File > Run SQL Script
3. Chọn file: `database/webhalophp.sql`
4. Click "Run"

---

## Cách 2: Tạo thủ công và chạy migrations

### Bước 1: Tạo database
```sql
CREATE DATABASE webhalophp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Bước 2: Chạy migrations
```bash
php artisan migrate
```

### Bước 3: Seed dữ liệu mẫu
```bash
php artisan db:seed
```

---

## Kiểm tra kết quả

Sau khi import thành công, database `webhalophp` sẽ có:

### 📊 Tables (12 bảng):
1. ✅ `categories` - 9 danh mục
2. ✅ `products` - 12 sản phẩm
3. ✅ `product_images` - Hình ảnh sản phẩm
4. ✅ `collections` - Bộ sưu tập
5. ✅ `collection_product` - Pivot table
6. ✅ `customers` - Khách hàng
7. ✅ `orders` - Đơn hàng
8. ✅ `order_items` - Chi tiết đơn hàng
9. ✅ `users` - Admin users
10. ✅ `failed_jobs`
11. ✅ `password_reset_tokens`
12. ✅ `personal_access_tokens`

### 📦 Dữ liệu mẫu:
- ✅ 9 danh mục (PS5, Switch, Xbox, iPhone, iPad, Controller, Phụ kiện)
- ✅ 12 sản phẩm với đầy đủ thông tin

---

## Xác minh kết nối

```bash
php artisan migrate:status
```

Nếu thành công, bạn sẽ thấy danh sách các migrations đã chạy.

---

## Reset database (nếu cần)

```bash
php artisan migrate:fresh --seed
```

Lệnh này sẽ xóa tất cả dữ liệu và tạo lại từ đầu.

---

## Chạy website

```bash
php artisan serve
```

Truy cập: http://localhost:8000

Chúc bạn thành công! 🎉
