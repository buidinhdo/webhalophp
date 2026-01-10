@extends('layouts.app')

@section('title', 'Về chúng tôi - HaloShop')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">
        <i class="fas fa-info-circle text-primary me-2"></i> Về chúng tôi
    </h1>

    <!-- Giới thiệu -->
    <div class="card product-card mb-4">
        <div class="card-body p-4">
            <h3 class="text-primary mb-4">Chào mừng đến với HaloShop</h3>
            <p class="lead">
                Cửa hàng game và thiết bị công nghệ hàng đầu Việt Nam
            </p>
            <p>
                HaloShop là địa chỉ tin cậy cho các game thủ và những người yêu thích công nghệ tại Việt Nam. 
                Chúng tôi chuyên cung cấp các sản phẩm gaming cao cấp, từ máy chơi game PlayStation, Xbox, Nintendo Switch 
                đến các thiết bị di động như iPhone, iPad và phụ kiện gaming chất lượng.
            </p>
            <p>
                Với cam kết mang đến sản phẩm chính hãng 100%, giá cả cạnh tranh và dịch vụ khách hàng tận tâm, 
                HaloShop đã và đang trở thành điểm đến yêu thích của hàng ngàn game thủ trên toàn quốc.
            </p>
        </div>
    </div>

    <!-- Giá trị cốt lõi -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card product-card h-100 text-center">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">Sản phẩm chính hãng</h5>
                    <p class="card-text">
                        100% hàng chính hãng, có tem nhận diện và bảo hành đầy đủ từ nhà phân phối
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card product-card h-100 text-center">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="fas fa-shipping-fast fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">Giao hàng nhanh chóng</h5>
                    <p class="card-text">
                        Giao hàng toàn quốc trong 24-48h. Miễn phí ship cho đơn hàng trên 500.000đ
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card product-card h-100 text-center">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="fas fa-headset fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">Hỗ trợ 24/7</h5>
                    <p class="card-text">
                        Đội ngũ tư vấn chuyên nghiệp, nhiệt tình hỗ trợ khách hàng mọi lúc mọi nơi
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm -->
    <div class="card product-card mb-4">
        <div class="card-body p-4">
            <h3 class="text-primary mb-4">Sản phẩm của chúng tôi</h3>
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-gamepad text-primary me-2"></i> Gaming Console</h5>
                    <ul>
                        <li>PlayStation 5 / PS4</li>
                        <li>Nintendo Switch / Switch 2</li>
                        <li>Xbox Series X/S</li>
                        <li>Tay cầm chơi game</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5><i class="fas fa-mobile-alt text-primary me-2"></i> Thiết bị di động</h5>
                    <ul>
                        <li>iPhone Series</li>
                        <li>iPad / iPad Pro</li>
                        <li>Phụ kiện công nghệ</li>
                        <li>Tai nghe gaming</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Cam kết -->
    <div class="card product-card">
        <div class="card-body p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h3 class="mb-4 text-white">Cam kết của chúng tôi</h3>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h6><i class="fas fa-check-circle me-2"></i> Giá tốt nhất thị trường</h6>
                    <p class="mb-0 small">Cam kết giá cạnh tranh, hoàn tiền nếu tìm được giá rẻ hơn</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6><i class="fas fa-check-circle me-2"></i> Bảo hành chính hãng</h6>
                    <p class="mb-0 small">Bảo hành 12-24 tháng theo chính sách nhà sản xuất</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6><i class="fas fa-check-circle me-2"></i> Đổi trả trong 7 ngày</h6>
                    <p class="mb-0 small">Đổi trả miễn phí nếu sản phẩm có lỗi từ nhà sản xuất</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6><i class="fas fa-check-circle me-2"></i> Tích điểm thành viên</h6>
                    <p class="mb-0 small">Tích điểm mỗi lần mua hàng, đổi quà hấp dẫn</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
