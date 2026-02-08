# HaloShop Admin Dashboard - AdminLTE 3

## Thông tin đăng nhập Admin

- **URL**: http://127.0.0.1:8000/admin/dashboard
- **Email**: admin@haloshop.com
- **Password**: admin123

## Chức năng đã tạo

### 1. Dashboard (/admin/dashboard)
- Thống kê tổng quan: Sản phẩm, Đơn hàng, Khách hàng, Doanh thu
- Biểu đồ doanh thu 7 ngày gần đây
- Sản phẩm bán chạy
- Đơn hàng gần đây
- Các chỉ số KPI: Đơn chờ xử lý, Đơn hàng hôm nay, Doanh thu tháng

### 2. Quản lý Sản phẩm (/admin/products)
- Danh sách sản phẩm với phân trang
- Thêm/Sửa/Xóa sản phẩm
- Upload hình ảnh sản phẩm
- Tìm kiếm và lọc theo danh mục, trạng thái
- Toggle trạng thái nổi bật (Featured)
- Quản lý giá, giá sale, tồn kho
- Đánh dấu sản phẩm: Nổi bật, Mới, Đặt trước

### 3. Quản lý Danh mục (/admin/categories)
- Danh sách danh mục
- Thêm/Sửa/Xóa danh mục
- Hỗ trợ danh mục cha-con
- Sắp xếp thứ tự hiển thị
- Toggle trạng thái kích hoạt

### 4. Quản lý Bộ sưu tập (/admin/collections)
- Tạo và quản lý bộ sưu tập sản phẩm
- Gắn nhiều sản phẩm vào bộ sưu tập
- Kích hoạt/Vô hiệu hóa bộ sưu tập

### 5. Quản lý Đơn hàng (/admin/orders)
- Danh sách đơn hàng với phân trang
- Chi tiết đơn hàng
- Cập nhật trạng thái: Chờ xử lý, Đang xử lý, Đang giao, Hoàn thành, Đã hủy
- Tìm kiếm theo mã đơn, tên, số điện thoại khách hàng
- Lọc theo trạng thái

### 6. Quản lý Khách hàng (/admin/customers)
- Danh sách khách hàng
- Xem thông tin chi tiết khách hàng
- Lịch sử đơn hàng của khách hàng
- Tìm kiếm khách hàng

## Cấu trúc File

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       ├── DashboardController.php
│   │       ├── ProductController.php
│   │       ├── CategoryController.php
│   │       ├── CollectionController.php
│   │       ├── OrderController.php
│   │       └── CustomerController.php
│   └── Middleware/
│       └── AdminMiddleware.php

resources/
└── views/
    └── admin/
        ├── layouts/
        │   └── app.blade.php          # Layout AdminLTE 3
        ├── dashboard.blade.php        # Trang chủ admin
        ├── products/
        │   └── index.blade.php        # Danh sách sản phẩm
        └── ...

routes/
├── web.php                            # Routes chính
└── admin.php                          # Routes admin
```

## Middleware Admin

File: `app/Http/Middleware/AdminMiddleware.php`

Hiện tại kiểm tra email admin là `admin@haloshop.com`. 
Bạn có thể mở rộng bằng cách thêm trường `role` hoặc `is_admin` vào bảng users.

## Cách sử dụng

1. **Khởi động server**:
```bash
cd /d/webhalophp/webhalophp
php artisan serve
```

2. **Đăng nhập admin**:
- Truy cập: http://127.0.0.1:8000/dang-nhap
- Email: admin@haloshop.com
- Password: admin123

3. **Vào trang admin**:
- URL: http://127.0.0.1:8000/admin/dashboard

## Công nghệ sử dụng

- **Framework**: Laravel 10
- **Template**: AdminLTE 3
- **CSS Framework**: Bootstrap 4
- **Icons**: Font Awesome 6
- **Charts**: Chart.js 3
- **Database**: MySQL

## Tính năng nổi bật

✅ Giao diện responsive, tương thích mobile
✅ Dashboard với biểu đồ thống kê realtime
✅ Quản lý đầy đủ CRUD cho tất cả module
✅ Upload và quản lý hình ảnh
✅ Tìm kiếm và lọc dữ liệu
✅ Phân trang hiệu quả
✅ Alert và thông báo
✅ Bảo mật với middleware

## Mở rộng

Để thêm tính năng role-based access control (RBAC):

1. Thêm migration cho bảng roles:
```bash
php artisan make:migration add_role_to_users_table
```

2. Cập nhật AdminMiddleware để kiểm tra role
3. Tạo seeder cho roles và permissions

## Liên hệ

Nếu có vấn đề hoặc cần hỗ trợ, vui lòng liên hệ team phát triển.
