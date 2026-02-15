@extends('layouts.app')

@section('title', 'Đăng nhập - HaloShop')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Đăng nhập</h4>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
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

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Email
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', request()->cookie('remember_email')) }}" 
                                   required 
                                   autofocus
                                   placeholder="Nhập email của bạn">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Mật khẩu
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required
                                   placeholder="Nhập mật khẩu">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check remember-box p-3 border rounded" style="background-color: #f8f9fa;">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                                <label class="form-check-label" for="remember" style="cursor: pointer;">
                                    <i class="fas fa-check-circle me-2 text-success"></i>
                                    <strong>Ghi nhớ đăng nhập</strong>
                                    <small class="d-block text-muted mt-1">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Giữ trạng thái đăng nhập trong 30 ngày
                                    </small>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">Chưa có tài khoản? 
                            <a href="{{ route('register') }}" class="text-primary fw-bold">Đăng ký ngay</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .remember-box {
        transition: all 0.3s ease;
    }
    .remember-box:hover {
        background-color: #e9ecef !important;
        cursor: pointer;
    }
    .remember-box.checked {
        background-color: #d4edda !important;
        border-color: #28a745 !important;
    }
    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rememberCheckbox = document.getElementById('remember');
    const rememberBox = rememberCheckbox.closest('.form-check');
    
    // Load saved remember preference from localStorage
    const savedRemember = localStorage.getItem('login_remember_preference');
    if (savedRemember === 'true') {
        rememberCheckbox.checked = true;
        rememberBox.classList.add('checked');
    }
    
    // Toggle visual state
    function updateRememberState() {
        if (rememberCheckbox.checked) {
            rememberBox.classList.add('checked');
            localStorage.setItem('login_remember_preference', 'true');
        } else {
            rememberBox.classList.remove('checked');
            localStorage.setItem('login_remember_preference', 'false');
        }
    }
    
    // Handle checkbox change
    rememberCheckbox.addEventListener('change', updateRememberState);
    
    // Handle click on entire box
    rememberBox.addEventListener('click', function(e) {
        if (e.target !== rememberCheckbox) {
            rememberCheckbox.checked = !rememberCheckbox.checked;
            updateRememberState();
        }
    });
    
    // Initial state
    updateRememberState();
    
    // Show success message if remember me is working
    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        if (rememberCheckbox.checked) {
            console.log('Remember me is enabled - user will stay logged in for 30 days');
        }
    });
});
</script>
@endsection
