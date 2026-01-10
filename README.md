# HaloShop - Laravel E-commerce Website

Website thương mại điện tử bán game và thiết bị công nghệ được xây dựng bằng Laravel MVC, dựa trên thiết kế của https://haloshop.vn/

## Tính năng chính

### Frontend
- ✅ Trang chủ với sản phẩm nổi bật, mới, preorder
- ✅ Danh sách sản phẩm với bộ lọc và phân trang
- ✅ Chi tiết sản phẩm
- ✅ Giỏ hàng
- ✅ Thanh toán đơn hàng
- ✅ Danh mục sản phẩm
- ✅ Tìm kiếm và sắp xếp sản phẩm

### Backend Structure
- ✅ Models: Category, Product, ProductImage, Collection, Order, OrderItem, Customer
- ✅ Controllers: HomeController, ProductController, CategoryController, CartController, CheckoutController
- ✅ Database với relationships đầy đủ
- ✅ Seeders với dữ liệu mẫu

## Công nghệ sử dụng

- **Framework**: Laravel 10.x
- **Frontend**: Bootstrap 5.3, Font Awesome
- **Database**: MySQL
- **PHP Version**: 8.1+

## Cấu trúc Database

### Tables
1. **categories** - Danh mục sản phẩm (có hỗ trợ parent-child)
2. **products** - Sản phẩm
3. **product_images** - Hình ảnh sản phẩm
4. **collections** - Bộ sưu tập
5. **collection_product** - Pivot table cho collections và products
6. **customers** - Khách hàng
7. **orders** - Đơn hàng
8. **order_items** - Chi tiết đơn hàng

## Hướng dẫn cài đặt

### 1. Yêu cầu hệ thống
- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Apache/Nginx

### 2. Đã có source code tại
```bash
cd d:\webhalophp
```

### 3. Đã cài đặt dependencies
```bash
composer install
```

### 4. Cấu hình môi trường
File `.env` đã được cấu hình với:
```
APP_NAME=HaloShop
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webhalophp
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Tạo database
Tạo database MySQL với tên `webhalophp`:

**Cách 1: Sử dụng file SQL có sẵn**
```bash
mysql -u root -p < database/webhalophp.sql
```

**Cách 2: Tạo thủ công**
```sql
CREATE DATABASE webhalophp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Chạy migrations
```bash
php artisan migrate
```

### 7. Seed dữ liệu mẫu
```bash
php artisan db:seed
```

### 8. Tạo symbolic link cho storage
```bash
php artisan storage:link
```

### 9. Chạy server
```bash
php artisan serve
```

Website sẽ chạy tại: **http://localhost:8000**

## Dữ liệu mẫu

Sau khi chạy seeder, bạn sẽ có:
- **9 danh mục** (PS5, Nintendo Switch, Xbox, iPhone, iPad, Controller, v.v.)
- **12 sản phẩm mẫu** với đầy đủ thông tin

## Routes chính

- `GET /` - Trang chủ
- `GET /san-pham` - Danh sách sản phẩm
- `GET /san-pham/{slug}` - Chi tiết sản phẩm
- `GET /danh-muc/{slug}` - Sản phẩm theo danh mục
- `GET /gio-hang` - Giỏ hàng
- `POST /gio-hang/them/{id}` - Thêm vào giỏ hàng
- `GET /thanh-toan` - Trang thanh toán
- `POST /thanh-toan/xu-ly` - Xử lý đơn hàng
- `GET /thanh-toan/thanh-cong/{order}` - Trang thành công

## Tính năng nổi bật

### 1. Quản lý sản phẩm
- Hỗ trợ nhiều loại sản phẩm (PS5, Nintendo Switch, Xbox, iPhone, iPad, Controller)
- Sản phẩm có thể đánh dấu: Mới, Nổi bật, Pre-order
- Giá sale và giá gốc
- Quản lý tồn kho
- Nhiều ảnh cho một sản phẩm

### 2. Giỏ hàng
- Session-based cart
- Cập nhật số lượng
- Xóa sản phẩm
- Tính tổng tự động

### 3. Thanh toán
- Form thông tin giao hàng đầy đủ
- Tính phí vận chuyển (30,000₫)
- Hỗ trợ COD và chuyển khoản
- Tạo đơn hàng tự động
- Trang xác nhận đơn hàng

### 4. Giao diện
- Responsive design với Bootstrap 5
- Modern UI với gradient và shadows
- Icon Font Awesome
- Animations và hover effects

## Troubleshooting

### Lỗi "Base table or view not found"
```bash
php artisan migrate:fresh --seed
```

### Lỗi "Class not found"
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Lỗi 404 trên routes
```bash
php artisan route:clear
php artisan route:cache
```

### Lỗi hiển thị ảnh
```bash
php artisan storage:link
```

## Phát triển tiếp

Các tính năng có thể thêm:
- Admin panel (CRUD cho products, categories, orders)
- User authentication và profile
- Wishlist
- Product reviews và ratings
- Payment gateway integration (VNPay, Momo)
- Email notifications
- Order tracking
- Coupon/Discount system
- Advanced search và filters

## License

Open source project for learning purposes.

## Credits

- Design inspired by: https://haloshop.vn/
- Framework: Laravel
- UI: Bootstrap 5, Font Awesome
