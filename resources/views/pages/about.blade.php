@extends('layouts.app')

@section('title', 'Về chúng tôi - HaloShop')

@section('styles')
<style>
    .about-us-page {
        padding: 24px 0 40px;
    }

    .about-us-hero {
        background: linear-gradient(120deg, #1b54d8 0%, #0f5ee8 55%, #2a7bff 100%);
        border-radius: 10px;
        padding: 18px;
        color: #fff;
        box-shadow: 0 8px 24px rgba(11, 66, 175, 0.22);
    }

    .about-us-hero h2 {
        font-weight: 800;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .about-us-hero p {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 14px;
        font-size: 14px;
        line-height: 1.55;
    }

    .about-us-feature-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .about-us-feature-item {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 8px;
        padding: 11px 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-height: 86px;
    }

    .about-us-feature-item i {
        font-size: 14px;
    }

    .about-us-feature-item span {
        font-size: 13px;
        line-height: 1.35;
        font-weight: 600;
    }

    .about-us-hero-image {
        width: 100%;
        height: 100%;
        min-height: 255px;
        border-radius: 8px;
        object-fit: cover;
    }

    .about-us-heading {
        margin: 28px 0 6px;
        font-weight: 700;
        letter-spacing: 0.2px;
        font-size: 28px;
        color: #1e2430;
    }

    .about-us-subtext {
        color: #6c757d;
        margin-bottom: 18px;
        font-size: 14px;
    }

    .about-us-store-card {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        min-height: 255px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.14);
    }

    .about-us-store-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .about-us-store-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.12) 55%, rgba(0, 0, 0, 0));
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px;
    }

    .about-us-store-title {
        color: #fff;
        font-size: 30px;
        line-height: 1;
        font-weight: 800;
        text-transform: uppercase;
        margin: 0;
        text-shadow: 0 2px 6px rgba(0, 0, 0, 0.35);
    }

    .about-us-store-btn {
        display: inline-flex;
        width: fit-content;
        background: #ffd51c;
        color: #1f1f1f;
        border-radius: 999px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        text-decoration: none;
    }

    .about-us-address {
        margin-top: 18px;
    }

    .about-us-address h5 {
        font-weight: 700;
        margin-bottom: 10px;
    }

    .about-us-address ul {
        margin: 0;
        padding-left: 16px;
        color: #212529;
        line-height: 1.9;
    }

    @media (max-width: 991.98px) {
        .about-us-hero-image {
            min-height: 220px;
            margin-top: 14px;
        }

        .about-us-store-title {
            font-size: 24px;
        }
    }

    @media (max-width: 767.98px) {
        .about-us-feature-grid {
            grid-template-columns: 1fr;
        }

        .about-us-store-card {
            min-height: 220px;
        }

        .about-us-heading {
            font-size: 24px;
        }
    }
</style>
@endsection

@section('content')
<div class="container about-us-page">
    <div class="about-us-hero">
        <div class="row g-3 align-items-stretch">
            <div class="col-lg-6 d-flex flex-column justify-content-between">
                <div>
                    <h2>CỬA HÀNG HALO</h2>
                    <p>
                        HALO là điểm đến dành cho cộng đồng yêu công nghệ và game tại TP.HCM, nơi bạn có thể tìm thấy
                        đầy đủ các dòng máy chơi game, thiết bị di động và phụ kiện chính hãng từ nhiều thương hiệu lớn.
                        Chúng tôi xây dựng trải nghiệm mua sắm theo hướng rõ ràng, minh bạch và thuận tiện để mỗi khách hàng
                        đều có thể chọn đúng sản phẩm phù hợp với nhu cầu sử dụng thực tế.
                    </p>
                    <p>
                        Từ các sản phẩm mới nhất đến những mẫu máy được game thủ săn đón, HALO luôn cập nhật liên tục
                        kèm chính sách giá tốt, ưu đãi theo từng thời điểm và nhiều chương trình quà tặng hấp dẫn.
                        Đội ngũ tư vấn tại cửa hàng luôn sẵn sàng hỗ trợ từ khâu chọn cấu hình, kiểm tra tình trạng máy,
                        đến hướng dẫn sử dụng sau khi mua để bạn yên tâm trong suốt quá trình trải nghiệm.
                    </p>
                    <p>
                        Bên cạnh mua sắm, khách hàng còn được tích điểm thành viên, tham gia các sự kiện cộng đồng,
                        và sử dụng dịch vụ bảo hành - hậu mãi chuyên nghiệp ngay tại hệ thống cửa hàng.
                        Nếu bạn cần một nơi đáng tin cậy để nâng cấp thiết bị, săn deal công nghệ hoặc tìm giải pháp
                        kỹ thuật nhanh chóng, HALO luôn sẵn sàng đồng hành cùng bạn.
                    </p>
                </div>

                <div class="about-us-feature-grid">
                    <div class="about-us-feature-item">
                        <i class="fas fa-cart-plus"></i>
                        <span>Mua sắm</span>
                    </div>
                    <div class="about-us-feature-item">
                        <i class="fas fa-gift"></i>
                        <span>Tích điểm</span>
                    </div>
                    <div class="about-us-feature-item">
                        <i class="far fa-life-ring"></i>
                        <span>Trung tâm bảo hành</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <img src="{{ asset('images/banners/bannercuahang1.jpg') }}" class="about-us-hero-image" alt="Cửa hàng Halo">
            </div>
        </div>
    </div>

    <h3 class="about-us-heading">HỆ THỐNG CỬA HÀNG</h3>
    <p class="about-us-subtext">
        HALO là hệ thống bán lẻ chính hãng các sản phẩm công nghệ cao cấp: máy chơi game, điện thoại,
        máy tính bảng, laptop và các linh kiện - phụ kiện chất lượng.
    </p>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="about-us-store-card">
                <img src="{{ asset('images/banners/bannercuahang2.webp') }}" alt="HALO Pasteur">
                <div class="about-us-store-overlay">
                    <h4 class="about-us-store-title">HALO PASTEUR</h4>
                    <a href="javascript:void(0)" class="about-us-store-btn">Xem chi tiết</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="about-us-store-card">
                <img src="{{ asset('images/banners/bannercuahang3.webp') }}" alt="HALO Nguyễn Hữu Cảnh">
                <div class="about-us-store-overlay">
                    <h4 class="about-us-store-title">HALO NGUYỄN HỮU CẢNH</h4>
                    <a href="javascript:void(0)" class="about-us-store-btn">Xem chi tiết</a>
                </div>
            </div>
        </div>
    </div>

    <div class="about-us-address">
        <h5>ĐỊA CHỈ</h5>
        <ul>
            <li>92 Pasteur, P. Sài Gòn, TP.HCM</li>
            <li>11 Nguyễn Hữu Cảnh, P. Thạnh Mỹ Tây, TP.HCM</li>
        </ul>
    </div>
</div>
@endsection
