@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng - HaloShop')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-receipt"></i> Chi tiết đơn hàng</h2>
        <a href="{{ route('account.orders') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Trạng thái:</strong>
                                @if($order->order_status == 'pending')
                                    <span class="badge bg-warning">Chờ xử lý</span>
                                @elseif($order->order_status == 'processing')
                                    <span class="badge bg-info">Đang xử lý</span>
                                @elseif($order->order_status == 'completed')
                                    <span class="badge bg-success">Hoàn thành</span>
                                @else
                                    <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </p>
                            <p>
                                <strong>Thanh toán:</strong>
                                @if($order->payment_method == 'cod')
                                    COD
                                @else
                                    Chuyển khoản
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6 class="mb-3">Thông tin nhận hàng</h6>
                    <p class="mb-1"><strong>Người nhận:</strong> {{ $order->customer_name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                    <p class="mb-1"><strong>Điện thoại:</strong> {{ $order->customer_phone }}</p>
                    <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->customer_address }}</p>
                    
                    @if($order->notes)
                    <p class="mb-1"><strong>Ghi chú:</strong> {{ $order->notes }}</p>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Chi tiết sản phẩm</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="80">Ảnh</th>
                                    <th>Sản phẩm</th>
                                    <th width="100">Số lượng</th>
                                    <th width="120">Đơn giá</th>
                                    <th width="120">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        @include('components.order-item-image', ['item' => $item])
                                    </td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price) }}₫</td>
                                    <td><strong>{{ number_format($item->subtotal) }}₫</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Tổng đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h5>Tổng cộng:</h5>
                        <h5 class="text-primary">{{ number_format($order->total_amount) }}₫</h5>
                    </div>
                    
                    <hr>
                    
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i>
                        <small>Nếu có thắc mắc vui lòng liên hệ hotline: <strong>1900-xxxx</strong></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
