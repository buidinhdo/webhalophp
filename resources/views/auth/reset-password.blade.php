@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu - HaloShop')

@section('styles')
<style>
    .auth-page-wrapper {
        background: url('{{ asset('images/banners/banner15.webp') }}') center/cover no-repeat fixed;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: -1;
    }
    .auth-page-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
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
    }
    .auth-card .card-header {
        border-radius: 16px 16px 0 0 !important;
        padding: 1.5rem;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }
    .auth-card .card-body {
        padding: 2rem;
    }
    .toggle-password {
        cursor: pointer;
        border-left: none;
    }
    @media (max-width: 768px) {
        .auth-container { padding: 20px 0; }
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
                    <h4 class="mb-0"><i class="fas fa-lock me-2"></i>Đặt lại mật khẩu</h4>
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

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Email
                            </label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $email) }}"
                                   required
                                   autofocus
                                   placeholder="Nhập email của bạn">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Mật khẩu mới
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       required
                                       placeholder="Nhập mật khẩu mới (ít nhất 6 ký tự)">
                                <span class="input-group-text toggle-password" onclick="togglePassword('password', this)">
                                    <i class="fas fa-eye"></i>
                                </span>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock me-1"></i>Xác nhận mật khẩu mới
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       required
                                       placeholder="Nhập lại mật khẩu mới">
                                <span class="input-group-text toggle-password" onclick="togglePassword('password_confirmation', this)">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Đặt lại mật khẩu
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-primary fw-bold">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại đăng nhập
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePassword(fieldId, icon) {
    const input = document.getElementById(fieldId);
    const iconEl = icon.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        iconEl.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        iconEl.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endsection
