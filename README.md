# HaloShop - Laravel E-commerce Website

Website thương mại điện tử bán game và thiết bị công nghệ được xây dựng bằng Laravel MVC, dựa trên thiết kế của https://haloshop.vn/

## 🎮 Tính năng chính

### Frontend - Giao diện người dùng
- ✅ **Trang chủ**: Sản phẩm nổi bật, mới, preorder với Swiper carousel
- ✅ **Danh sách sản phẩm**: Bộ lọc đa chiều (danh mục, platform, thể loại) và phân trang
- ✅ **Chi tiết sản phẩm**: Hiển thị đầy đủ thông tin, ảnh, giá, mô tả
- ✅ **Giỏ hàng**: Session-based cart với cập nhật số lượng real-time
- ✅ **Thanh toán**: Form đầy đủ, tính phí vận chuyển, QR code thanh toán
- ✅ **Tìm kiếm & sắp xếp**: Tìm theo tên, sắp xếp theo giá, mới nhất
- ✅ **Chatbot AI**: Trợ lý ảo tư vấn sản phẩm thông minh

### Bộ lọc sản phẩm nâng cao
- ✅ **Lọc theo Platform**: PS4, PS5, Nintendo Switch, Xbox
- ✅ **Lọc theo Genre**: Action, Adventure, RPG, Fighting, Shooting (động từ database)
- ✅ **Lọc theo Category**: Danh mục cha và con với cấu trúc phân cấp
- ✅ **Tìm kiếm**: Full-text search theo tên sản phẩm
- ✅ **Sắp xếp**: Mới nhất, giá thấp-cao, giá cao-thấp, tên A-Z/Z-A

### Navigation & Header
- ✅ **Top Bar**: Hotline (028 7306 8666), địa chỉ Hà Nội - TP.HCM
- ✅ **Logo**: Thương hiệu HALO với hiệu ứng gradient
- ✅ **Menu Icon**: Font Awesome icons cho tất cả menu items
- ✅ **Category Dropdown**: Menu đổ xuống hiển thị tất cả danh mục
- ✅ **Search Box**: Tìm kiếm nhanh ngay trên header
- ✅ **Auth Buttons**: Đăng nhập, Đăng ký với styling hiện đại

### Footer - Đầy đủ thông tin
- ✅ **Thông tin liên hệ**: Hotline, Email (sales@halo.vn), Website (haloshop.vn), Zalo
- ✅ **Địa chỉ**: 2 chi nhánh tại TP.HCM với địa chỉ cụ thể
- ✅ **Giờ làm việc**: T2-T7 (9h-20h), CN & Lễ (9h-19h)
- ✅ **Logo Bộ Công Thương**: Badge đã thông báo website TMĐT
- ✅ **Social Media**: Links đến Facebook, YouTube, PS5 Group với icon đẹp
- ✅ **Thông tin pháp lý**: Quy định, chính sách, bảo hành, FAQs

### Backend Structure
- ✅ **Models**: Category, Product, ProductImage, Collection, Order, OrderItem, Customer, User
- ✅ **Controllers**: HomeController, ProductController, CategoryController, CartController, CheckoutController, Admin Controllers
- ✅ **View Composer**: Share categories globally cho navigation dropdown
- ✅ **Database Relationships**: Đầy đủ quan hệ giữa các bảng
- ✅ **Seeders**: Dữ liệu mẫu với 18+ game products

## 🛠️ Công nghệ sử dụng

- **Framework**: Laravel 10.x
- **Frontend**: Bootstrap 5.3, Font Awesome 6.4.0, Swiper.js v11
- **Database**: MySQL 8.0+
- **PHP Version**: 8.1+
- **JavaScript**: Vanilla JS cho chatbot, cart, interactive features
- **CSS**: Custom CSS với CSS Variables, Flexbox, Grid

## 📊 Cấu trúc Database

### Tables
1. **categories** - Danh mục sản phẩm (hỗ trợ parent-child hierarchy)
2. **products** - Sản phẩm (có thêm trường `genre` cho game categories)
3. **product_images** - Hình ảnh sản phẩm (multiple images per product)
4. **collections** - Bộ sưu tập sản phẩm
5. **collection_product** - Pivot table cho collections và products
6. **customers** - Thông tin khách hàng
7. **orders** - Đơn hàng với tracking status
8. **order_items** - Chi tiết đơn hàng
9. **users** - Admin/Staff users
10. **failed_jobs** - Queue management
11. **password_reset_tokens** - Password recovery
12. **personal_access_tokens** - API authentication

### Product Fields
- Basic: name, slug, description, short_description, sku, stock
- Pricing: price, sale_price
- Categorization: category_id, platform, **genre** (mới)
- Status: is_featured, is_new, is_preorder, status
- Media: image (primary), product_images relationship (gallery)
- Date: release_date, created_at, updated_at

## 📥 Hướng dẫn cài đặt

### 1. Yêu cầu hệ thống
- PHP >= 8.1
- Composer
- MySQL/MariaDB >= 8.0
- Apache/Nginx (hoặc PHP built-in server)
- Git

### 2. Clone project từ GitHub
```bash
git clone https://github.com/buidinhdo/webhalophp.git
cd webhalophp
```

### 3. Cài đặt dependencies
```bash
composer install
```

### 4. Cấu hình môi trường
Tạo file `.env` từ template:
```bash
cp .env.example .env
```

Cấu hình database trong `.env`:
```env
APP_NAME=HaloShop
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webhalophp
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Tạo application key
```bash
php artisan key:generate
```

### 6. Tạo database

**Cách 1: Import file SQL có sẵn (Khuyến nghị - Nhanh nhất)**
```bash
mysql -u root -p < database/webhalophp.sql
```

**Cách 2: Tạo thủ công và chạy migrations**
```sql
CREATE DATABASE webhalophp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Sau đó chạy migrations:
```bash
php artisan migrate
```

### 7. Seed dữ liệu mẫu (nếu dùng Cách 2)
```bash
php artisan db:seed
```

### 8. Tạo symbolic link cho storage
```bash
php artisan storage:link
```

### 9. Chạy development server
```bash
php artisan serve
```

### 10. Truy cập website
Mở trình duyệt và vào: **http://localhost:8000**

---

## ⚡ Lệnh cài đặt nhanh (All-in-one)

```bash
# Sau khi đã clone và cấu hình .env
composer install && php artisan key:generate && php artisan migrate --seed && php artisan storage:link && php artisan serve
```

## 📦 Dữ liệu mẫu

Sau khi chạy seeder, bạn sẽ có:
- **9 danh mục chính**: PS5, PS4, Nintendo Switch, Xbox, iPhone, iPad, Controller, Phụ kiện, Khác
- **18+ sản phẩm game** với đầy đủ thông tin:
  - Tên sản phẩm và slug SEO-friendly
  - Mô tả chi tiết và mô tả ngắn
  - Giá bán (từ 1,090,000₫ - 14,190,000₫)
  - Platform: PS5, PS4, Nintendo Switch, Xbox
  - Genre: Action, Adventure, Fighting, RPG, Shooting
  - Stock count và status
  - Đánh dấu: Featured, New, Pre-order
- **Sample orders & customers** cho testing

### Sản phẩm mẫu bao gồm:
- God of War Ragnarök (PS5) - Action/Adventure
- Horizon Forbidden West (PS5) - Action
- The Last of Us Part II (PS4) - Action/Adventure
- Ghost of Tsushima (PS4) - Action/Adventure
- The Legend of Zelda: Tears of the Kingdom (Switch) - Adventure
- Super Mario Odyssey (Switch) - Adventure
- Mortal Kombat 11 (Xbox) - Fighting
- Halo Infinite (Xbox) - Shooting
- Và nhiều game khác...

## 🛣️ Routes chính

### Trang chủ & Sản phẩm
- `GET /` - Trang chủ với featured products
- `GET /san-pham` - Danh sách tất cả sản phẩm
- `GET /san-pham/{slug}` - Chi tiết sản phẩm
- `GET /danh-muc/{slug}` - Sản phẩm theo danh mục
- `GET /api/san-pham/quick-view/{id}` - Quick view modal

### Giỏ hàng & Thanh toán
- `GET /gio-hang` - Xem giỏ hàng
- `POST /gio-hang/them` - Thêm sản phẩm vào giỏ
- `POST /gio-hang/cap-nhat` - Cập nhật số lượng
- `POST /gio-hang/xoa/{id}` - Xóa sản phẩm khỏi giỏ
- `GET /thanh-toan` - Form thanh toán
- `POST /thanh-toan/xu-ly` - Xử lý đơn hàng
- `GET /thanh-toan/thanh-cong/{order}` - Trang xác nhận
- `GET /thanh-toan/qr/{order}` - QR code thanh toán

### Chatbot API
- `POST /api/chatbot/message` - Xử lý tin nhắn chatbot
- `GET /api/chatbot/products` - Gợi ý sản phẩm

### Trang thông tin
- `GET /lien-he` - Liên hệ
- `GET /gioi-thieu` - Giới thiệu
- `GET /tin-tuc` - Tin tức

### Admin (nếu có authentication)
- `GET /admin/products` - Quản lý sản phẩm
- `GET /admin/orders` - Quản lý đơn hàng
- `GET /admin/categories` - Quản lý danh mục

## ⭐ Tính năng nổi bật

### 1. Quản lý sản phẩm đa dạng
- **Nhiều loại sản phẩm**: PS5, PS4, Nintendo Switch, Xbox, iPhone, iPad, Controller, Phụ kiện
- **Product Status**: Mới, Nổi bật, Pre-order, Active/Inactive
- **Pricing**: Giá gốc, giá sale với hiển thị % giảm giá
- **Genre System**: Phân loại game theo thể loại (Action, Adventure, RPG, Fighting, Shooting)
- **Stock Management**: Quản lý tồn kho, hiển thị trạng thái còn hàng
- **Image Gallery**: Nhiều ảnh cho một sản phẩm với primary image

### 2. Bộ lọc & Tìm kiếm thông minh
- **Multi-filter**: Lọc đồng thời theo category, platform, genre
- **Dynamic Filters**: Genre options tự động load từ database (không hardcode)
- **Search**: Full-text search theo tên sản phẩm
- **Sort Options**: 6 kiểu sắp xếp (newest, price asc/desc, name A-Z/Z-A, featured)
- **Pagination**: Phân trang với thông tin chi tiết
- **URL Parameters**: Duy trì filters khi chuyển trang

### 3. Giỏ hàng hiện đại
- **Session-based Cart**: Không cần đăng nhập
- **Real-time Update**: AJAX update số lượng và tổng tiền
- **Mini Cart Widget**: Hiển thị nhanh trong navigation
- **Cart Actions**: Thêm, sửa, xóa sản phẩm nhanh chóng
- **Stock Validation**: Kiểm tra tồn kho khi thêm vào giỏ
- **Price Calculation**: Tự động tính tổng, discount, shipping

### 4. Quy trình thanh toán hoàn chỉnh
- **Checkout Form**: Đầy đủ thông tin giao hàng (name, phone, address, city, district)
- **Payment Methods**: COD, Chuyển khoản ngân hàng
- **Shipping Fee**: Tính phí vận chuyển (30,000₫)
- **Order Creation**: Tự động tạo order với unique order number
- **QR Code Payment**: Hiển thị QR code cho thanh toán chuyển khoản
- **Order Confirmation**: Trang xác nhận đơn hàng với chi tiết đầy đủ
- **Email Notification**: (Ready to implement) Gửi email xác nhận

### 5. Chatbot AI thông minh
- **Interactive Chat**: Giao diện chat hiện đại với typing animation
- **Product Search**: Tìm kiếm sản phẩm qua chatbot
- **Product Recommendations**: Gợi ý sản phẩm dựa trên từ khóa
- **Quick Actions**: Shortcuts cho actions phổ biến
- **Product Cards**: Hiển thị sản phẩm trực tiếp trong chat
- **Add to Cart**: Thêm sản phẩm vào giỏ ngay từ chatbot

### 6. Giao diện UI/UX chuyên nghiệp
- **Responsive Design**: Hoàn hảo trên mọi thiết bị (desktop, tablet, mobile)
- **Modern Aesthetics**: Gradient colors, shadows, smooth animations
- **Bootstrap 5**: Grid system, components, utilities
- **Font Awesome Icons**: 100+ icons cho navigation, actions, social
- **Swiper Carousel**: Touch-enabled sliders cho featured products
- **Hover Effects**: Smooth transitions và interactive elements
- **CSS Variables**: Dễ dàng customize theme colors
- **Loading States**: Spinners và skeletons cho better UX

### 7. Navigation & Header thông minh
- **Sticky Navigation**: Menu cố định khi scroll
- **Dropdown Menus**: Category dropdown với submenus
- **Search Bar**: Tìm kiếm nhanh ngay trên header
- **Cart Counter**: Badge hiển thị số lượng sản phẩm trong giỏ
- **Authentication**: Nút đăng nhập/đăng ký với gradient styling
- **Top Bar**: Hotline và địa chỉ với icon đẹp mắt

### 8. Footer đầy đủ thông tin
- **Contact Info**: Hotline (028 7306 8666), Email, Website, Zalo
- **Store Locations**: 2 địa chỉ chi nhánh tại TP.HCM
- **Business Hours**: Giờ làm việc chi tiết (T2-T7, CN & Lễ)
- **BCT Badge**: Logo đã thông báo Bộ Công Thương
- **Social Media**: Links đến Facebook Page, YouTube Channel, PS5 Vietnam Group
- **Quick Links**: Chính sách, quy định, FAQs, tuyển dụng
- **Chatbot Toggle**: Click vào "HALO SHOP" mở chatbot

### 9. SEO & Performance
- **SEO-friendly URLs**: Slug-based routing (/san-pham/god-of-war-ragnarok)
- **Meta Tags**: Title, description cho từng trang
- **Image Optimization**: Lazy loading (ready to implement)
- **Caching**: Route cache, config cache, view cache
- **Database Indexing**: Optimized queries với indexes
- **Asset Optimization**: Minified CSS/JS (production)

## 🔧 Troubleshooting - Khắc phục lỗi

### Lỗi: "No application encryption key"
```bash
php artisan key:generate
```

### Lỗi: "Base table or view not found"
Reset database và migrate lại:
```bash
php artisan migrate:fresh --seed
```

### Lỗi: "Access denied for user 'root'@'localhost'"
- Kiểm tra lại username/password MySQL trong file `.env`
- Đảm bảo MySQL service đang chạy

### Lỗi: "Class not found" hoặc Autoload issues
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Lỗi: 404 Not Found trên routes
```bash
php artisan route:clear
php artisan route:cache
php artisan config:clear
```

### Lỗi: Không hiển thị ảnh sản phẩm
```bash
php artisan storage:link
```
Đảm bảo thư mục `public/images/products/` có quyền write

### Lỗi: Chatbot không hoạt động
- Kiểm tra console browser xem có lỗi JavaScript không
- Clear cache trình duyệt (Ctrl + F5)
- Kiểm tra routes chatbot API có hoạt động không

### Lỗi: Giỏ hàng bị mất sau khi refresh
- Kiểm tra session configuration trong `config/session.php`
- Đảm bảo `SESSION_DRIVER=file` trong `.env`
- Kiểm tra quyền write cho `storage/framework/sessions/`

### Performance issues (trang load chậm)
```bash
# Enable caching
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear all cache nếu có vấn đề
php artisan optimize:clear
```

---

## 🚀 Phát triển tiếp - Roadmap

### Tính năng sắp tới
- [ ] **Admin Panel**: CRUD đầy đủ cho products, categories, orders
- [ ] **User Authentication**: Login, Register, Profile management
- [ ] **Wishlist**: Danh sách yêu thích sản phẩm
- [ ] **Product Reviews**: Đánh giá và rating sản phẩm
- [ ] **Advanced Search**: Search với nhiều tiêu chí, autocomplete
- [ ] **Order Tracking**: Theo dõi đơn hàng theo mã
- [ ] **Email Notifications**: Xác nhận đơn hàng, cập nhật trạng thái
- [ ] **Payment Gateway**: Tích hợp VNPay, Momo, ZaloPay
- [ ] **Coupon/Discount**: Mã giảm giá, chương trình khuyến mãi
- [ ] **Product Comparison**: So sánh sản phẩm
- [ ] **Social Login**: Đăng nhập qua Facebook, Google
- [ ] **Advanced Analytics**: Dashboard với charts và statistics
- [ ] **Multi-language**: Hỗ trợ đa ngôn ngữ (VI/EN)
- [ ] **PWA**: Progressive Web App support
- [ ] **Image Upload**: Upload ảnh sản phẩm từ admin
- [ ] **PDF Invoice**: In hóa đơn PDF cho đơn hàng
- [ ] **Inventory Alerts**: Cảnh báo sản phẩm sắp hết hàng
- [ ] **Customer Dashboard**: Trang quản lý tài khoản, đơn hàng

### Cải thiện kỹ thuật
- [ ] **API RESTful**: Xây dựng API đầy đủ cho mobile app
- [ ] **Unit Tests**: Viết tests cho các chức năng chính
- [ ] **CI/CD**: GitHub Actions cho auto-deploy
- [ ] **Docker**: Containerize application
- [ ] **Redis Cache**: Caching với Redis
- [ ] **Queue System**: Background jobs cho email, notifications
- [ ] **Image Optimization**: Auto resize và compress ảnh
- [ ] **Security**: Rate limiting, CSRF protection, XSS prevention
- [ ] **Logging**: Structured logging với Monolog

---

## 📞 Thông tin liên hệ

- **Hotline**: 028 7306 8666  
- **Email**: sales@halo.vn  
- **Website**: [haloshop.vn](https://haloshop.vn)  
- **Facebook**: [facebook.com/halo.vn](https://www.facebook.com/halo.vn)  
- **YouTube**: [youtube.com/@HaLoShopGame](https://www.youtube.com/@HaLoShopGame)  
- **PS5 Community**: [facebook.com/groups/ps5vietnam](https://www.facebook.com/groups/ps5vietnam)

## 📄 License

Open source project for learning purposes.

## 🙏 Credits

- **Design inspired by**: [haloshop.vn](https://haloshop.vn/)
- **Framework**: Laravel 10.x
- **UI Framework**: Bootstrap 5.3
- **Icons**: Font Awesome 6.4.0
- **Carousel**: Swiper.js v11
- **Contributors**: [Your team/contributors]

---

## 📝 Changelog

### v2.0.0 (2026-02-19)
- ✅ Thêm Genre filter cho sản phẩm game
- ✅ Dynamic filters load từ database
- ✅ Category dropdown navigation
- ✅ Icons cho tất cả menu items
- ✅ Footer đầy đủ thông tin doanh nghiệp
- ✅ Logo Bộ Công Thương
- ✅ Social media links (Facebook, YouTube, PS5 Group)
- ✅ Chatbot integration với footer
- ✅ Hotline và top bar alignment
- ✅ View Composer cho global categories
- ✅ UI/UX improvements với flexbox

### v1.0.0 (Initial Release)
- ✅ Core e-commerce features
- ✅ Product catalog với filtering
- ✅ Shopping cart system
- ✅ Checkout process
- ✅ Basic admin functions
- ✅ Database structure với seeders

---

**⭐ Nếu project này hữu ích, đừng quên star repo trên GitHub!**

**🐛 Phát hiện bug? Tạo issue tại**: [GitHub Issues](https://github.com/buidinhdo/webhalophp/issues)
