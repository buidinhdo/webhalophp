<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Đơn hàng #{{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .header {
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #007bff;
            font-size: 32px;
            margin-bottom: 5px;
            font-weight: 700;
        }
        
        .header p {
            color: #666;
            font-size: 12px;
        }
        
        .invoice-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .invoice-info table {
            width: 100%;
        }
        
        .invoice-info td {
            padding: 8px 0;
            vertical-align: top;
        }
        
        .invoice-info td:first-child {
            width: 50%;
            padding-right: 20px;
        }
        
        .invoice-info strong {
            color: #000;
            font-weight: 600;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #007bff;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .customer-info {
            background: #ffffff;
            border: 1px solid #e9ecef;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        
        .customer-info p {
            margin-bottom: 10px;
            line-height: 1.8;
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
        }
        
        .products-table thead {
            background: #007bff;
            color: white;
        }
        
        .products-table th {
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
        }
        
        .products-table th:nth-child(1) {
            text-align: center;
            width: 80px;
        }
        
        .products-table th:nth-child(3) {
            text-align: center;
            width: 80px;
        }
        
        .products-table th:nth-child(4),
        .products-table th:nth-child(5) {
            text-align: right;
            width: 120px;
        }
        
        .products-table tbody tr {
            border-bottom: 1px solid #e9ecef;
        }
        
        .products-table tbody tr:last-child {
            border-bottom: 2px solid #007bff;
        }
        
        .products-table td {
            padding: 12px 10px;
        }
        
        .products-table td:nth-child(1) {
            text-align: center;
        }
        
        .products-table td:nth-child(3) {
            text-align: center;
        }
        
        .products-table td:nth-child(4),
        .products-table td:nth-child(5) {
            text-align: right;
        }
        
        .products-table tfoot {
            background: #f8f9fa;
        }
        
        .products-table tfoot td {
            padding: 15px 10px;
            font-weight: 700;
            font-size: 16px;
            border-top: 2px solid #007bff;
        }
        
        .total-amount {
            color: #dc3545;
            font-size: 18px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-processing {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .status-shipping {
            background: #cce5ff;
            color: #004085;
        }
        
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .payment-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .payment-paid {
            background: #d4edda;
            color: #155724;
        }
        
        .payment-unpaid {
            background: #fff3cd;
            color: #856404;
        }
        
        .notes-section {
            background: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .notes-section strong {
            color: #856404;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            text-align: center;
            color: #666;
            font-size: 11px;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        .footer strong {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>HALOSHOP</h1>
            <p>Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM</p>
            <p>Điện thoại: 0123-456-789 | Email: support@haloshop.vn</p>
        </div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <table>
                <tr>
                    <td>
                        <strong>Mã đơn hàng:</strong> #{{ $order->order_number }}<br>
                        <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
                        <strong>Phương thức thanh toán:</strong> {{ $order->payment_method == 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản ngân hàng' }}
                    </td>
                    <td>
                        <strong>Trạng thái đơn hàng:</strong><br>
                        @switch($order->order_status)
                            @case('pending')
                                <span class="status-badge status-pending">Chờ xử lý</span>
                                @break
                            @case('processing')
                                <span class="status-badge status-processing">Đang xử lý</span>
                                @break
                            @case('shipping')
                                <span class="status-badge status-shipping">Đang giao hàng</span>
                                @break
                            @case('completed')
                                <span class="status-badge status-completed">Hoàn thành</span>
                                @break
                            @case('cancelled')
                                <span class="status-badge status-cancelled">Đã hủy</span>
                                @break
                        @endswitch
                        <br><br>
                        <strong>Trạng thái thanh toán:</strong><br>
                        <span class="payment-badge {{ $order->payment_status == 'paid' ? 'payment-paid' : 'payment-unpaid' }}">
                            {{ $order->payment_status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Customer Information -->
        <h2 class="section-title">THÔNG TIN KHÁCH HÀNG</h2>
        <div class="customer-info">
            <p><strong>Họ và tên:</strong> {{ $order->customer_name }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
            <p><strong>Địa chỉ giao hàng:</strong> {{ $order->customer_address }}</p>
        </div>

        <!-- Products -->
        <h2 class="section-title">CHI TIẾT SẢN PHẨM</h2>
        <table class="products-table">
            <thead>
                <tr>
                    <th width="80">Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td style="text-align: center;">
                        @if($item->product_image)
                            <img src="{{ public_path($item->product_image) }}" alt="{{ $item->product_name }}" style="width: 50px; height: 50px; object-fit: cover;">
                        @endif
                    </td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                    <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right;">TỔNG CỘNG:</td>
                    <td class="total-amount">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                </tr>
            </tfoot>
        </table>

        <!-- Notes -->
        @if($order->notes)
        <div class="notes-section">
            <strong>Ghi chú đơn hàng:</strong><br>
            {{ $order->notes }}
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>Cảm ơn quý khách đã mua hàng tại HALOSHOP!</strong></p>
            <p>Mọi thắc mắc xin vui lòng liên hệ: 0123-456-789 hoặc support@haloshop.vn</p>
            <p style="margin-top: 15px; font-style: italic;">Đơn hàng được in tự động từ hệ thống - Ngày in: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
