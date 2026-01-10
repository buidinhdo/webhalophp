# Hướng dẫn chạy nhanh HaloShop

## Bước 1: Chuẩn bị database

**Cách 1: Import file SQL (Khuyến nghị - Nhanh nhất)**
```bash
mysql -u root -p < database/webhalophp.sql
```
File này sẽ tự động tạo database và import dữ liệu mẫu.

**Cách 2: Tạo thủ công và chạy migrations**
Mở MySQL/phpMyAdmin và chạy:
```sql
CREATE DATABASE webhalophp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## Bước 2: Chạy migrations
```bash
php artisan migrate
```

## Bước 3: Seed dữ liệu mẫu
```bash
php artisan db:seed
```

## Bước 4: Tạo storage link
```bash
php artisan storage:link
```

## Bước 5: Chạy server
```bash
php artisan serve
```

## Truy cập website
Mở trình duyệt và truy cập: **http://localhost:8000**

## Lệnh nhanh (Chạy tất cả một lần)
```bash
php artisan migrate --seed && php artisan storage:link && php artisan serve
```

## Nếu gặp lỗi, reset lại database
```bash
php artisan migrate:fresh --seed
```

---

## Thông tin đăng nhập (nếu có admin - cần phát triển thêm)
- Email: admin@haloshop.vn
- Password: 123456

## Dữ liệu mẫu sau khi seed
- 9 danh mục sản phẩm
- 12 sản phẩm (PS5, Switch, iPhone, Controller, v.v.)

## Các trang chính
- Trang chủ: http://localhost:8000
- Sản phẩm: http://localhost:8000/san-pham
- Giỏ hàng: http://localhost:8000/gio-hang
- Thanh toán: http://localhost:8000/thanh-toan

## Test flow mua hàng
1. Vào trang sản phẩm
2. Chọn sản phẩm và thêm vào giỏ
3. Vào giỏ hàng kiểm tra
4. Thanh toán và điền thông tin
5. Xem trang xác nhận đơn hàng

Chúc bạn test thành công! 🎉
