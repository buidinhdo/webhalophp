@extends('layouts.app')

@section('title', 'Đặt hàng thành công - HaloShop')

@section('content')
<div class="container my-5">
    <div class="text-center mb-5">
        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
        <h1 class="display-4 mt-3">Đặt hàng thành công!</h1>
        <p class="lead text-muted">Cảm ơn bạn đã mua hàng tại HaloShop</p>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-receipt"></i> Thông tin đơn hàng
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Trạng thái:</strong> <span class="badge bg-warning">Đang xử lý</span></p>
                            <p><strong>Thanh toán:</strong> {{ $order->payment_method == 'cod' ? 'COD' : 'Chuyển khoản' }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">Thông tin nhận hàng</h5>
                    <p class="mb-1"><strong>Người nhận:</strong> {{ $order->customer_name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                    <p class="mb-1"><strong>Điện thoại:</strong> {{ $order->customer_phone }}</p>
                    <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->customer_address }}</p>
                    
                    @if($order->notes)
                    <p class="mb-1"><strong>Ghi chú:</strong> {{ $order->notes }}</p>
                    @endif
                    
                    <hr>
                    
                    <h5 class="mb-3">Chi tiết đơn hàng</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price) }}₫</td>
                                <td>{{ number_format($item->subtotal) }}₫</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <td colspan="3" class="text-end"><h5>Tổng cộng:</h5></td>
                                <td><h5 class="text-primary">{{ number_format($order->total_amount) }}₫</h5></td>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Lưu ý:</strong> Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận đơn hàng.
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-home"></i> Về trang chủ
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
