@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - HaloShop')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">
        <i class="fas fa-box"></i> Đơn hàng của tôi
    </h2>
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="list-group">
                <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-user me-2"></i> Thông tin cá nhân
                </a>
                <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action active">
                    <i class="fas fa-box me-2"></i> Đơn hàng của tôi
                </a>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle"></i> Đây là danh sách đơn hàng được tạo từ tài khoản <strong>{{ Auth::user()->name }}</strong>
            </div>
            
            @if($orders->count() > 0)
                @foreach($orders as $order)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Mã đơn: {{ $order->order_number }}</strong>
                            <br>
                            <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <div>
                            @if($order->order_status == 'pending')
                                <span class="badge bg-warning">Chờ xử lý</span>
                            @elseif($order->order_status == 'processing')
                                <span class="badge bg-info">Đang xử lý</span>
                            @elseif($order->order_status == 'completed')
                                <span class="badge bg-success">Hoàn thành</span>
                            @else
                                <span class="badge bg-danger">Đã hủy</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="mb-1"><strong>Người nhận:</strong> {{ $order->customer_name }}</p>
                                <p class="mb-1"><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                                <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->customer_address }}</p>
                                <p class="mb-1">
                                    <strong>Thanh toán:</strong> 
                                    @if($order->payment_method == 'cod')
                                        <span class="badge bg-success">COD</span>
                                    @else
                                        <span class="badge bg-primary">Chuyển khoản</span>
                                    @endif
                                    -
                                    @if($order->payment_status == 'pending')
                                        <span class="badge bg-warning">Chưa thanh toán</span>
                                    @else
                                        <span class="badge bg-success">Đã thanh toán</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <h5 class="text-primary mb-3">{{ number_format($order->total_amount) }}₫</h5>
                                <a href="{{ route('account.order-detail', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i> Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <div class="d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h5>Chưa có đơn hàng nào</h5>
                    <p>Bạn chưa có đơn hàng nào. Hãy mua sắm ngay!</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>Mua sắm ngay
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
