# Tính năng Ghi nhớ đăng nhập (Remember Me)

## Mô tả
Tính năng cho phép người dùng duy trì trạng thái đăng nhập trong thời gian dài (30 ngày) mà không cần đăng nhập lại mỗi khi đóng trình duyệt.

## Cách hoạt động

### 1. Cấu hình Session
- **File**: `config/session.php`
- **Setting**: `expire_on_close => false`
- Cho phép session tồn tại ngay cả khi đóng trình duyệt

### 2. Authentication Controller
- **File**: `app/Http/Controllers/AuthController.php` 
- **Method**: `login()`
- Sử dụng `Auth::attempt($credentials, $remember)`
- Lưu email vào cookie khi chọn "Ghi nhớ" (thời hạn 30 ngày)

### 3. User Model
- **File**: `app/Models/User.php`
- Model kế thừa `Authenticatable` đã có sẵn support cho remember_token
- Cột `remember_token` trong database tự động được Laravel quản lý

### 4. Login Form
- **File**: `resources/views/auth/login.blade.php`
- Checkbox với value="1" và name="remember"
- UI cải tiến: background màu xanh nhạt khi chọn
- JavaScript lưu preference vào localStorage
- Tự động điền email đã lưu từ lần đăng nhập trước

## Cách sử dụng

### Cho người dùng:
1. Truy cập trang đăng nhập
2. Nhập email và mật khẩu
3. **Tích chọn "Ghi nhớ đăng nhập"**
4. Nhấn "Đăng nhập"
5. Lần sau mở website sẽ vẫn đăng nhập tự động trong vòng 30 ngày

### Cho developer:
```php
// Kiểm tra trong AuthController
$remember = $request->has('remember');
Auth::attempt($credentials, $remember);

// Laravel tự động:
// - Tạo remember_token trong database
// - Set cookie "remember_web_[hash]" với thời hạn dài (mặc định 5 years)
// - Auto login user khi họ quay lại
```

## Thời gian lưu trữ
- **Remember Token Cookie**: ~5 năm (Laravel default)
- **Email Cookie**: 30 ngày (43200 phút)
- **Preference LocalStorage**: Vô thời hạn (cho UI state)

## Bảo mật
- Token được mã hóa và lưu trong database
- Mỗi lần login mới sẽ regenerate token
- Token tự động xóa khi logout
- Cookie chỉ accessible qua HTTP (httponly=true)

## Testing
```bash
# 1. Đăng nhập với remember me checked
# 2. Đóng trình duyệt hoàn toàn
# 3. Mở lại website
# 4. Kiểm tra: Should still be logged in

# Xóa remember me:
# - Logout bình thường
# - Hoặc clear cookies
```

## Files thay đổi
1. `config/session.php` - expire_on_close = false
2. `app/Http/Controllers/AuthController.php` - xử lý remember logic + save email cookie
3. `resources/views/auth/login.blade.php` - UI cải tiến + JavaScript
4. `app/Models/User.php` - Đã có sẵn support (không cần sửa)
5. `database/migrations/...create_users_table.php` - Đã có rememberToken() (không cần sửa)

## Cập nhật: 15/02/2026
- Thêm UI cải tiến với visual feedback
- Lưu email vào cookie khi remember me
- JavaScript localStorage để nhớ preference
- Hover effect và animation
