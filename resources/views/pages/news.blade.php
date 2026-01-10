@extends('layouts.app')

@section('title', 'Tin tức - HaloShop')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">
        <i class="fas fa-newspaper text-primary me-2"></i> Tin tức
    </h1>

    <div class="row g-4">
        <!-- Tin tức 1 -->
        <div class="col-md-6">
            <div class="card product-card h-100">
                <img src="{{ asset('images/news/news1.jpg') }}" class="card-img-top" alt="PS5 Pro" style="height: 250px; object-fit: cover;">
                <div class="card-body">
                    <span class="badge bg-primary mb-2">Gaming</span>
                    <h5 class="card-title">PlayStation 5 Pro chính thức ra mắt tại Việt Nam</h5>
                    <p class="text-muted small mb-2">
                        <i class="far fa-calendar me-1"></i> 10/01/2026
                    </p>
                    <p class="card-text">
                        PlayStation 5 Pro đã chính thức có mặt tại thị trường Việt Nam với nhiều cải tiến vượt trội về hiệu suất và đồ họa. Máy hỗ trợ chơi game ở độ phân giải 8K...
                    </p>
                    <a href="#news1" class="btn btn-primary btn-sm" data-bs-toggle="modal">Đọc thêm <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Tin tức 2 -->
        <div class="col-md-6">
            <div class="card product-card h-100">
                <img src="{{ asset('images/news/news2.jpg') }}" class="card-img-top" alt="Switch 2" style="height: 250px; object-fit: cover;">
                <div class="card-body">
                    <span class="badge bg-danger mb-2">Gaming</span>
                    <h5 class="card-title">Nintendo Switch 2 - Thế hệ máy chơi game mới</h5>
                    <p class="text-muted small mb-2">
                        <i class="far fa-calendar me-1"></i> 08/01/2026
                    </p>
                    <p class="card-text">
                        Nintendo Switch 2 được ra mắt với màn hình OLED lớn hơn, hiệu năng mạnh mẽ hơn và hỗ trợ nhiều tính năng mới. Máy có thể chơi ở cả chế độ cầm tay và kết nối TV...
                    </p>
                    <a href="#news2" class="btn btn-primary btn-sm" data-bs-toggle="modal">Đọc thêm <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Tin tức 3 -->
        <div class="col-md-6">
            <div class="card product-card h-100">
                <img src="{{ asset('images/news/news3.jpg') }}" class="card-img-top" alt="iPhone 17" style="height: 250px; object-fit: cover;">
                <div class="card-body">
                    <span class="badge bg-dark mb-2">Mobile</span>
                    <h5 class="card-title">iPhone 17 Series - Công nghệ đỉnh cao</h5>
                    <p class="text-muted small mb-2">
                        <i class="far fa-calendar me-1"></i> 05/01/2026
                    </p>
                    <p class="card-text">
                        Dòng iPhone 17 Series với chip A18 Bionic, camera 200MP và thiết kế hoàn toàn mới. Đây là bước tiến lớn trong công nghệ smartphone của Apple...
                    </p>
                    <a href="#news3" class="btn btn-primary btn-sm" data-bs-toggle="modal">Đọc thêm <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Tin tức 4 -->
        <div class="col-md-6">
            <div class="card product-card h-100">
                <img src="{{ asset('images/news/news4.jpg') }}" class="card-img-top" alt="Gaming" style="height: 250px; object-fit: cover;">
                <div class="card-body">
                    <span class="badge bg-success mb-2">Khuyến mãi</span>
                    <h5 class="card-title">Chương trình khuyến mãi tháng 1/2026</h5>
                    <p class="text-muted small mb-2">
                        <i class="far fa-calendar me-1"></i> 01/01/2026
                    </p>
                    <p class="card-text">
                        Mừng năm mới 2026, HaloShop giảm giá lên đến 30% cho tất cả các sản phẩm gaming. Đặc biệt có nhiều quà tặng hấp dẫn cho khách hàng...
                    </p>
                    <a href="#news4" class="btn btn-primary btn-sm" data-bs-toggle="modal">Đọc thêm <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal cho tin tức chi tiết -->
<!-- News 1 Modal -->
<div class="modal fade" id="news1" tabindex="-1" aria-labelledby="news1Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="news1Label">PlayStation 5 Pro chính thức ra mắt tại Việt Nam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('images/news/news1.jpg') }}" class="img-fluid mb-3" alt="PS5 Pro">
                <p class="text-muted"><i class="far fa-calendar me-1"></i> 10/01/2026</p>
                <p>PlayStation 5 Pro đã chính thức có mặt tại thị trường Việt Nam với nhiều cải tiến vượt trội về hiệu suất và đồ họa. Máy hỗ trợ chơi game ở độ phân giải 8K với tốc độ khung hình ổn định.</p>
                <p>Với chip xử lý mới mạnh mẽ hơn 45% so với PS5 thường, PS5 Pro mang đến trải nghiệm gaming tuyệt vời nhất. Hệ thống làm mát được cải tiến giúp máy hoạt động ổn định ngay cả khi chơi game nặng trong thời gian dài.</p>
                <p>Giá bán chính thức tại Việt Nam: 19.990.000₫. Máy đi kèm 1 tay cầm DualSense, cáp HDMI 2.1 và sách hướng dẫn sử dụng.</p>
            </div>
        </div>
    </div>
</div>

<!-- News 2 Modal -->
<div class="modal fade" id="news2" tabindex="-1" aria-labelledby="news2Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="news2Label">Nintendo Switch 2 - Thế hệ máy chơi game mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('images/news/news2.jpg') }}" class="img-fluid mb-3" alt="Switch 2">
                <p class="text-muted"><i class="far fa-calendar me-1"></i> 08/01/2026</p>
                <p>Nintendo Switch 2 được ra mắt với màn hình OLED 7 inch lớn hơn, hiệu năng mạnh mẽ hơn và hỗ trợ nhiều tính năng mới. Máy có thể chơi ở cả chế độ cầm tay và kết nối TV với độ phân giải 4K.</p>
                <p>Bộ nhớ trong được nâng cấp lên 256GB, đủ để lưu trữ nhiều game lớn. Pin được cải thiện cho phép chơi liên tục 9 giờ ở chế độ cầm tay.</p>
                <p>Switch 2 tương thích ngược với hầu hết game của Switch thế hệ cũ. Giá dự kiến: 13.990.000₫.</p>
            </div>
        </div>
    </div>
</div>

<!-- News 3 Modal -->
<div class="modal fade" id="news3" tabindex="-1" aria-labelledby="news3Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="news3Label">iPhone 17 Series - Công nghệ đỉnh cao</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('images/news/news3.jpg') }}" class="img-fluid mb-3" alt="iPhone 17">
                <p class="text-muted"><i class="far fa-calendar me-1"></i> 05/01/2026</p>
                <p>Dòng iPhone 17 Series với chip A18 Bionic, camera 200MP và thiết kế hoàn toàn mới. Đây là bước tiến lớn trong công nghệ smartphone của Apple với hiệu năng vượt trội.</p>
                <p>Màn hình ProMotion 120Hz mang lại trải nghiệm mượt mà. Công nghệ AI tích hợp sâu giúp tối ưu hóa hiệu suất và pin. Camera Night Mode được cải tiến chụp ảnh đêm rõ nét hơn.</p>
                <p>Pin sử dụng được cả ngày dài với công nghệ sạc nhanh 45W và sạc không dây MagSafe 25W. Giá từ 29.990.000₫.</p>
            </div>
        </div>
    </div>
</div>

<!-- News 4 Modal -->
<div class="modal fade" id="news4" tabindex="-1" aria-labelledby="news4Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="news4Label">Chương trình khuyến mãi tháng 1/2026</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('images/news/news4.jpg') }}" class="img-fluid mb-3" alt="Gaming">
                <p class="text-muted"><i class="far fa-calendar me-1"></i> 01/01/2026</p>
                <p>Mừng năm mới 2026, HaloShop giảm giá lên đến 30% cho tất cả các sản phẩm gaming. Đặc biệt có nhiều quà tặng hấp dẫn cho khách hàng mua sắm trong tháng 1.</p>
                <p><strong>Ưu đãi đặc biệt:</strong></p>
                <ul>
                    <li>Giảm 30% tất cả game PS5</li>
                    <li>Giảm 25% phụ kiện gaming</li>
                    <li>Tặng game khi mua máy PlayStation</li>
                    <li>Miễn phí vận chuyển toàn quốc</li>
                </ul>
                <p>Chương trình áp dụng từ 01/01/2026 đến 31/01/2026. Nhanh tay đặt hàng để nhận ưu đãi tốt nhất!</p>
            </div>
        </div>
    </div>
</div>
@endsection
