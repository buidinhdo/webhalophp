@extends('layouts.app')

@section('title', 'Quên mật khẩu - HaloShop')

@section('styles')
<style>
    .auth-page-wrapper {
        background: url('{{ asset('images/banners/banner15.webp') }}') center/cover no-repeat fixed;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: -1;
    }
    .auth-page-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.35);
        z-index: -1;
    }
    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 40px 0;
        position: relative;
        z-index: 1;
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
        .auth-page-overlay {
            background: rgba(0, 0, 0, 0.45);
        }
        .auth-container {
            padding: 20px 0;
        }
    }
</style>
@endsection

@section('content')
<div class="auth-page-wrapper"></div>
<div class="auth-page-overlay"></div>
<div class="container auth-container">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-5">
            <div class="card auth-card">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-key me-2"></i>Quên mật khẩu</h4>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        <i class="fas fa-info-circle me-1"></i>
                        Nhập địa chỉ email đã đăng ký. Chúng tôi sẽ gửi link đặt lại mật khẩu đến email của bạn.
                    </p>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Email
                            </label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   placeholder="Nhập email của bạn">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Gửi link đặt lại mật khẩu
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-1">
                            <a href="{{ route('login') }}" class="text-primary fw-bold">
                                <i class="fas fa-arrow-left me-1"></i>Quay lại đăng nhập
                            </a>
                        </p>
                        <p class="mb-0 text-muted">Chưa có tài khoản?
                            <a href="{{ route('register') }}" class="text-primary fw-bold">Đăng ký ngay</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
