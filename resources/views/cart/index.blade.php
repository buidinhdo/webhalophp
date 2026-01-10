@extends('layouts.app')

@section('title', 'Giỏ hàng - HaloShop')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">
        <i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn
    </h2>
    
    @if(count($cart) > 0)
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Tổng</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $id => $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if(isset($item['image']))
                                            <img src="{{ asset('storage/' . $item['image']) }}" style="width: 80px; height: 80px; object-fit: cover;" class="me-3 rounded" alt="{{ $item['name'] }}">
                                        @else
                                            <img src="https://via.placeholder.com/80" class="me-3 rounded" alt="{{ $item['name'] }}">
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $item['name'] }}</h6>
                                            @if(isset($item['slug']))
                                            <small><a href="{{ route('products.show', $item['slug']) }}">Xem sản phẩm</a></small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    {{ number_format($item['price']) }}₫
                                </td>
                                <td class="align-middle">
                                    <form action="{{ route('cart.update', $id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control" style="width: 80px;" onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td class="align-middle">
                                    <strong>{{ number_format($item['price'] * $item['quantity']) }}₫</strong>
                                </td>
                                <td class="align-middle">
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-3">
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tổng đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <strong>{{ number_format($total) }}₫</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <strong>30,000₫</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h5>Tổng cộng:</h5>
                        <h5 class="text-primary">{{ number_format($total + 30000) }}₫</h5>
                    </div>
                    
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 btn-lg">
                        <i class="fas fa-credit-card"></i> Thanh toán
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
        <h3>Giỏ hàng của bạn đang trống</h3>
        <p class="text-muted">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-shopping-bag"></i> Khám phá sản phẩm
        </a>
    </div>
    @endif
</div>
@endsection
