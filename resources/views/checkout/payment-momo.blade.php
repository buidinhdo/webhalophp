@extends('layouts.app')

@section('title', 'Thanh toán MoMo - HaloShop')

@section('styles')
<style>
    .payment-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    .momo-header {
        background: linear-gradient(135deg, #A50064 0%, #D82D8B 100%);
        color: white;
        padding: 30px;
        border-radius: 15px 15px 0 0;
        text-align: center;
    }
    .momo-logo {
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }
    .momo-logo i {
        font-size: 40px;
        color: #A50064;
    }
    .order-card {
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }
    .info-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #dee2e6;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .product-item {
        display: flex;
        gap: 15px;
        padding: 15px;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        margin-bottom: 10px;
        background: white;
    }
    .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #f0f0f0;
    }
    .product-details {
        flex: 1;
    }
    .product-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    .product-price {
        color: #A50064;
        font-weight: 600;
    }
    .total-amount {
        font-size: 28px;
        font-weight: bold;
        color: #A50064;
        text-align: center;
        padding: 20px;
        background: linear-gradient(135deg, #fff5f7 0%, #ffe5f0 100%);
        border-radius: 10px;
        margin: 20px 0;
    }
    .payment-button {
        background: linear-gradient(135deg, #A50064 0%, #D82D8B 100%);
        border: none;
        padding: 15px 40px;
        font-size: 18px;
        font-weight: 600;
        color: white;
        border-radius: 50px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(165, 0, 100, 0.3);
    }
    .payment-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(165, 0, 100, 0.4);
        background: linear-gradient(135deg, #8B0055 0%, #C0247A 100%);
    }
    .error-message {
        background: #fff3cd;
        border: 1px solid #ffc107;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .store-info {
        background: white;
        padding: 20px;
        border-radius: 10px;
        border: 2px solid #e9ecef;
    }
    .store-info h5 {
        color: #A50064;
        font-weight: 700;
        margin-bottom: 15px;
    }
    .badge-momo {
        background: #A50064;
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="container my-5 payment-container">
    <div class="order-card">
        <div class="momo-header">
            <div class="momo-logo">
                <i class="fas fa-wallet"></i>
            </div>
            <h2 class="mb-2">Thanh toán MoMo</h2>
            <p class="mb-0">Đơn hàng #{{ $order->order_number }}</p>
            <span class="badge-momo mt-2">Ví điện tử MoMo</span>
        </div>

        <div class="card-body p-4">
            @if(session('error'))
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Lỗi:</strong> {{ session('error') }}
            </div>
            @endif

            <div class="row">
                <div class="col-md-7">
                    <!-- Thông tin người mua -->
                    <div class="info-section">
                        <h5><i class="fas fa-user-circle text-primary"></i> Thông tin người mua</h5>
                        <div class="info-row">
                            <strong>Họ tên:</strong>
                            <span>{{ $order->customer_name }}</span>
                        </div>
                        <div class="info-row">
                            <strong>Email:</strong>
                            <span>{{ $order->customer_email }}</span>
                        </div>
                        <div class="info-row">
                            <strong>Số điện thoại:</strong>
                            <span>{{ $order->customer_phone }}</span>
                        </div>
                        <div class="info-row">
                            <strong>Địa chỉ:</strong>
                            <span>{{ $order->customer_address }}</span>
                        </div>
                    </div>

                    <!-- Thông tin nơi bán -->
                    <div class="store-info">
                        <h5><i class="fas fa-store"></i> Thông tin nơi bán</h5>
                        <div class="info-row">
                            <strong>Cửa hàng:</strong>
                            <span>{{ config('momo.store_name') }}</span>
                        </div>
                        <div class="info-row">
                            <strong>Địa chỉ:</strong>
                            <span>{{ config('momo.store_address') }}</span>
                        </div>
                        <div class="info-row">
                            <strong>Số điện thoại:</strong>
                            <span>{{ config('momo.store_phone') }}</span>
                        </div>
                        <div class="info-row">
                            <strong>Email:</strong>
                            <span>{{ config('momo.store_email') }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <!-- Sản phẩm -->
                    <div class="mb-4">
                        <h5 class="mb-3"><i class="fas fa-shopping-bag text-success"></i> Sản phẩm đặt mua</h5>
                        @foreach($order->items as $item)
                        <div class="product-item">
                            @if($item->product_image && file_exists(public_path($item->product_image)))
                            <img src="{{ asset($item->product_image) }}" alt="{{ $item->product_name }}" class="product-image">
                            @elseif($item->product && $item->product->image && file_exists(public_path($item->product->image)))
                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product_name }}" class="product-image">
                            @else
                            <div class="product-image" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                            @endif
                            <div class="product-details">
                                <div class="product-name">{{ $item->product_name }}</div>
                                <div class="text-muted small">Số lượng: {{ $item->quantity }}</div>
                                <div class="product-price">{{ number_format($item->price) }}₫</div>
                            </div>
                            <div class="text-end">
                                <strong class="product-price">{{ number_format($item->total) }}₫</strong>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Tổng tiền -->
                    <div class="total-amount">
                        <div class="text-muted" style="font-size: 14px; font-weight: normal;">Tổng thanh toán</div>
                        {{ number_format($order->total_amount) }}₫
                    </div>

                    <!-- Thông báo đặt hàng thành công -->
                    <div class="alert alert-success text-center">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <h5 class="mb-2">Đặt hàng thành công!</h5>
                        <p class="mb-0">Đơn hàng của bạn đã được tiếp nhận và đang chờ xử lý. Vui lòng thanh toán qua ví MoMo khi nhận hàng.</p>
                    </div>

                    <!-- Thông tin đơn hàng -->
                    <div class="mt-3">
                        <small class="text-muted">
                            <strong>Mã đơn hàng:</strong> {{ $order->order_number }}<br>
                            <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
                            <strong>Trạng thái:</strong> <span class="badge bg-warning">Chờ thanh toán</span>
                        </small>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home"></i> Về trang chủ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ghi chú -->
            @if($order->notes)
            <div class="mt-4">
                <div class="alert alert-secondary">
                    <strong><i class="fas fa-sticky-note"></i> Ghi chú đơn hàng:</strong><br>
                    {{ $order->notes }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Hỗ trợ -->
    <div class="text-center mt-4">
        <p class="text-muted">
            <i class="fas fa-question-circle"></i> 
            Cần hỗ trợ? Liên hệ: <strong>{{ config('momo.store_phone') }}</strong> hoặc 
            <strong>{{ config('momo.store_email') }}</strong>
        </p>
    </div>
</div>
@endsection
