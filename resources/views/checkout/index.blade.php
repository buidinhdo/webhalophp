@extends('layouts.app')

@section('title', 'Thanh toán - HaloShop')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">
        <i class="fas fa-credit-card"></i> Thanh toán
    </h2>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                        @csrf
                        
                        @auth
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle"></i> Thông tin dưới đây được lấy từ tài khoản của bạn. Bạn có thể sửa nếu cần.
                        </div>
                        @endauth
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $user->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email', $user->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                value="{{ old('phone', $user->phone ?? '') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                value="{{ old('address', $user->address ?? '') }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Ghi chú đơn hàng (không bắt buộc)">{{ old('notes') }}</textarea>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Phương thức thanh toán</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                    <label class="form-check-label" for="cod">
                                        <i class="fas fa-money-bill-wave text-success"></i> Thanh toán khi nhận hàng (COD)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="bank" value="bank">
                                    <label class="form-check-label" for="bank">
                                        <i class="fas fa-university text-primary"></i> Chuyển khoản ngân hàng
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Đơn hàng của bạn</h5>
                </div>
                <div class="card-body">
                    @foreach($cart as $id => $item)
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <strong>{{ $item['name'] }}</strong><br>
                            <small class="text-muted">x{{ $item['quantity'] }}</small>
                        </div>
                        <span>{{ number_format($item['price'] * $item['quantity']) }}₫</span>
                    </div>
                    @endforeach
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <strong>{{ number_format($subtotal) }}₫</strong>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <h5>Tổng cộng:</h5>
                        <h5 class="text-primary">{{ number_format($subtotal) }}₫</h5>
                    </div>
                    
                    <button type="submit" form="checkoutForm" class="btn btn-primary w-100 btn-lg">
                        <i class="fas fa-check-circle"></i> Đặt hàng
                    </button>
                    
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-arrow-left"></i> Quay lại giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
