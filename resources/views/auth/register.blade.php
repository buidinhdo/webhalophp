@extends('layouts.app')

@section('title', 'Đăng ký - HaloShop')

@section('styles')
<style>
    body {
        background: url('{{ asset('images/banners/banner15.webp') }}') center/cover no-repeat fixed;
        position: relative;
    }
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.65);
        z-index: -1;
    }
    .auth-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        padding: 40px 0;
    }
    .auth-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.4);
        border: none;
        backdrop-filter: blur(10px);
    }
    .auth-card .card-header {
        border-radius: 16px 16px 0 0 !important;
        padding: 1.5rem;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }
    .auth-card .card-body {
        padding: 2rem;
    }
    @media (max-width: 768px) {
        body::before {
            background: rgba(0, 0, 0, 0.75);
        }
        .auth-container {
            min-height: auto;
            padding: 20px 0;
        }
    }
</style>
@endsection

@section('content')
<div class="container auth-container">
    <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-7">
            <div class="card auth-card">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Đăng ký tài khoản</h4>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i>Họ và tên <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required 
                                       autofocus
                                       placeholder="Nhập họ và tên">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required
                                       placeholder="Nhập email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i>Mật khẩu <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required
                                       placeholder="Ít nhất 6 ký tự">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-1"></i>Xác nhận mật khẩu <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required
                                       placeholder="Nhập lại mật khẩu">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-1"></i>Số điện thoại
                            </label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   placeholder="Nhập số điện thoại (không bắt buộc)">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Địa chỉ
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="2"
                                      placeholder="Nhập địa chỉ (không bắt buộc)">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Đăng ký
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">Đã có tài khoản? 
                            <a href="{{ route('login') }}" class="text-primary fw-bold">Đăng nhập ngay</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
