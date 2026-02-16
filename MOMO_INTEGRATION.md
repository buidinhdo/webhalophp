# Hướng dẫn tích hợp thanh toán MoMo

## Tổng quan

Tính năng thanh toán MoMo đã được tích hợp vào hệ thống HaloShop, cho phép khách hàng thanh toán đơn hàng bằng ví điện tử MoMo.

## Cấu hình

### 1. Cấu hình file .env

Thêm các dòng sau vào file `.env`:

```env
# MoMo Payment Configuration
MOMO_ENDPOINT=https://test-payment.momo.vn/v2/gateway/api/create
MOMO_PARTNER_CODE=YOUR_PARTNER_CODE
MOMO_ACCESS_KEY=YOUR_ACCESS_KEY
MOMO_SECRET_KEY=YOUR_SECRET_KEY
MOMO_RETURN_URL="${APP_URL}/thanh-toan/momo/callback"
MOMO_NOTIFY_URL="${APP_URL}/thanh-toan/momo/ipn"
MOMO_STORE_NAME="HaloShop - Game & Console Store"
MOMO_STORE_ADDRESS="Hà Nội, Việt Nam"
MOMO_STORE_PHONE=0123456789
MOMO_STORE_EMAIL=support@haloshop.vn
```

### 2. Đăng ký tài khoản MoMo Business

1. Truy cập: https://business.momo.vn/
2. Đăng ký tài khoản doanh nghiệp
3. Hoàn tất xác thực doanh nghiệp
4. Lấy thông tin API credentials:
   - Partner Code
   - Access Key
   - Secret Key

### 3. Môi trường Test vs Production

**Test Environment (Sandbox):**
- Endpoint: `https://test-payment.momo.vn/v2/gateway/api/create`
- Sử dụng credentials test từ MoMo Developer Portal
- Dùng để test tích hợp

**Production Environment:**
- Endpoint: `https://payment.momo.vn/v2/gateway/api/create`
- Sử dụng credentials production từ MoMo Business
- Cần xác thực doanh nghiệp hoàn tất

## Tính năng

### 1. Giao diện thanh toán

Trang thanh toán MoMo (`/thanh-toan/momo/{order}`) hiển thị đầy đủ:
- ✅ Thông tin người mua (tên, email, SĐT, địa chỉ)
- ✅ Thông tin nơi bán (tên cửa hàng, địa chỉ, SĐT, email)
- ✅ Danh sách sản phẩm với hình ảnh
- ✅ Số lượng và giá từng sản phẩm
- ✅ Tổng tiền thanh toán
- ✅ Mã đơn hàng và thời gian đặt
- ✅ Nút thanh toán kết nối MoMo

### 2. Quy trình thanh toán

1. Khách hàng chọn sản phẩm và thêm vào giỏ hàng
2. Tại trang thanh toán, chọn phương thức "Ví điện tử MoMo"
3. Hệ thống tạo đơn hàng và chuyển đến trang thanh toán MoMo
4. Khách hàng nhấn "Thanh toán bằng MoMo"
5. Được chuyển đến ứng dụng/trang MoMo
6. Đăng nhập MoMo và xác nhận thanh toán
7. Sau khi thanh toán thành công, được chuyển về trang thành công

### 3. Xử lý callback

**Return URL (Callback):**
- Route: `/thanh-toan/momo/callback`
- Method: GET
- Xử lý: Xác thực signature, cập nhật trạng thái đơn hàng
- Redirect: Trang thành công hoặc trang lỗi

**IPN (Instant Payment Notification):**
- Route: `/thanh-toan/momo/ipn`
- Method: POST
- Xử lý: Xác thực signature, cập nhật trạng thái đơn hàng
- Response: JSON status

### 4. Bảo mật

- ✅ Xác thực chữ ký HMAC-SHA256
- ✅ Validate signature từ MoMo
- ✅ SSL/TLS cho production
- ✅ Logging tất cả giao dịch
- ✅ Error handling

## Files đã tạo/cập nhật

### Files mới tạo:

1. **config/momo.php** - Cấu hình MoMo
2. **app/Services/MoMoPaymentService.php** - Service xử lý API MoMo
3. **resources/views/checkout/payment-momo.blade.php** - Giao diện thanh toán MoMo

### Files đã cập nhật:

1. **app/Http/Controllers/CheckoutController.php** - Thêm methods xử lý MoMo
2. **routes/web.php** - Thêm routes MoMo
3. **resources/views/checkout/index.blade.php** - Thêm option MoMo
4. **resources/views/checkout/success.blade.php** - Hiển thị MoMo
5. **resources/views/account/orders.blade.php** - Hiển thị MoMo
6. **resources/views/account/order-detail.blade.php** - Hiển thị MoMo
7. **resources/views/admin/orders/show.blade.php** - Hiển thị MoMo
8. **resources/views/admin/orders/edit.blade.php** - Hiển thị MoMo
9. **resources/views/admin/orders/pdf.blade.php** - Hiển thị MoMo
10. **.env.example** - Thêm MoMo config example

## Testing

### Test với MoMo Sandbox:

1. Sử dụng test credentials:
```env
MOMO_PARTNER_CODE=MOMOBKUN20180529
MOMO_ACCESS_KEY=klm05TvNBzhg7h7j
MOMO_SECRET_KEY=at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa
```

2. Đặt hàng và chọn thanh toán MoMo
3. Sử dụng test account MoMo (cung cấp bởi MoMo)
4. Kiểm tra callback và IPN

## Logs

Tất cả giao dịch MoMo được log tại:
- `storage/logs/laravel.log`

Các log bao gồm:
- Request tạo thanh toán
- Response từ MoMo
- Callback data
- IPN data
- Errors

## Troubleshooting

### Lỗi "Invalid Signature"
- Kiểm tra Secret Key trong .env
- Đảm bảo không có khoảng trắng thừa
- Kiểm tra endpoint đúng môi trường (test/production)

### Lỗi "Connection timeout"
- Kiểm tra firewall/proxy
- Kiểm tra SSL certificate
- Kiểm tra CURL extension đã enable

### Callback không hoạt động
- Kiểm tra MOMO_RETURN_URL và MOMO_NOTIFY_URL
- Đảm bảo URL public accessible (không localhost)
- Sử dụng ngrok cho local testing

## API Reference

### MoMo API Documentation
- Developer Portal: https://developers.momo.vn/
- API Docs: https://developers.momo.vn/#/docs/aio

## Support

Nếu cần hỗ trợ:
- MoMo Support: business@momo.vn
- MoMo Hotline: 1900 54 54 41

## Notes

- Chỉ chấp nhận thanh toán VNĐ
- Minimum amount: 1,000 VNĐ
- Maximum amount: Theo giới hạn tài khoản MoMo
- Transaction timeout: 15 phút
