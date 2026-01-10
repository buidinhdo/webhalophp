@extends('layouts.app')

@section('title', 'Liên hệ - HaloShop')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">
        <i class="fas fa-envelope text-primary me-2"></i> Liên hệ với chúng tôi
    </h1>

    <div class="row g-4">
        <!-- Form liên hệ -->
        <div class="col-lg-6">
            <div class="card product-card">
                <div class="card-body p-4">
                    <h4 class="mb-4">Gửi tin nhắn cho chúng tôi</h4>
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" id="subject">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-paper-plane me-2"></i> Gửi tin nhắn
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Thông tin liên hệ -->
        <div class="col-lg-6">
            <div class="card product-card h-100">
                <div class="card-body p-4">
                    <h4 class="mb-4">Thông tin liên hệ</h4>
                    <div class="mb-4">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i> Địa chỉ
                        </h6>
                        <p class="mb-0">Hà Nội, Việt Nam</p>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-phone me-2"></i> Hotline
                        </h6>
                        <p class="mb-0">1900 xxxx (8:00 - 22:00)</p>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-envelope me-2"></i> Email
                        </h6>
                        <p class="mb-0">contact@haloshop.vn</p>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-clock me-2"></i> Giờ làm việc
                        </h6>
                        <p class="mb-0">Thứ 2 - Chủ nhật: 8:00 - 22:00</p>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h5 class="mb-3">Kết nối với chúng tôi</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-outline-primary btn-lg">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-lg">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info btn-lg">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-success btn-lg">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
