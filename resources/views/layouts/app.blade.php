<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HaloShop - Gaming Store')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #00d9ff;
            --secondary-color: #00b8d4;
            --dark-color: #0a0e27;
            --dark-blue: #0f1419;
            --accent-color: #ff4081;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        
        /* Header Styles */
        .top-bar {
            background-color: var(--dark-color);
            color: #fff;
            padding: 8px 0;
            font-size: 13px;
        }
        
        .navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color) !important;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Navbar Menu */
        .navbar-expand-lg .navbar-collapse {
            display: flex !important;
            align-items: center;
            flex-wrap: nowrap;
        }
        
        .navbar-nav {
            flex-direction: row !important;
            align-items: center;
            flex-wrap: nowrap;
        }
        
        .navbar-nav .nav-item {
            display: inline-block;
            flex-shrink: 0;
        }
        
        .nav-link {
            color: #333 !important;
            font-weight: 500;
            font-size: 15px;
            padding: 0.5rem 0.75rem !important;
            transition: all 0.3s;
            position: relative;
            white-space: nowrap;
            display: inline-block;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: width 0.3s;
        }
        
        .nav-link:hover::after {
            width: 80%;
        }
        
        .navbar-nav.ms-auto {
            margin-left: auto !important;
        }
        
        .navbar-nav.me-auto {
            margin-right: auto !important;
        }
        
        /* Category Card Styles */
        .categories-scroll {
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) #f0f0f0;
            padding-bottom: 10px;
        }
        
        .categories-scroll::-webkit-scrollbar {
            height: 6px;
        }
        
        .categories-scroll::-webkit-scrollbar-track {
            background: #f0f0f0;
            border-radius: 10px;
        }
        
        .categories-scroll::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }
        
        .categories-scroll .row {
            flex-wrap: nowrap;
            margin: 0;
        }
        
        .categories-scroll .col-auto {
            width: 200px;
            flex: 0 0 auto;
        }
        
        .category-card {
            display: block;
            background: #fff;
            border-radius: 12px;
            padding: 20px 15px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 2px solid transparent;
            white-space: normal;
            width: 100%;
        }
        
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
            border-color: var(--primary-color);
        }
        
        .category-icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .category-icon i {
            font-size: 24px;
            color: #fff;
        }
        
        .category-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
            margin: 0;
            line-height: 1.3;
        }
        
        .category-card:hover .category-name {
            color: var(--primary-color);
        }
        
        /* Product Card Styles */
        .product-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            background: #fff;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .product-card img {
            height: 250px;
            width: 100%;
            object-fit: contain;
            background: #fff;
            padding: 15px;
            transition: transform 0.3s;
        }
        
        .product-card:hover img {
            transform: scale(1.05);
        }
        
        .product-card .card-body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        
        .product-card .d-grid {
            margin-top: auto;
        }
        
        .product-card .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.75rem;
            min-height: 48px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.5;
        }
        
        .product-card .price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--accent-color);
        }
        
        .product-card .old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 0.9rem;
        }
        
        .badge-sale {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--accent-color);
            color: #fff;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 1;
        }
        
        .badge-new {
            position: absolute;
            top: 8px;
            left: 8px;
            background: var(--primary-color);
            color: #fff;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
            z-index: 1;
        }
        
        .badge-preorder {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #ff9800;
            color: #fff;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 1;
        }
        
        /* Hero Carousel */
        .hero-carousel {
            margin-bottom: 60px;
        }
        
        .hero-slide {
            min-height: 500px;
            display: flex;
            align-items: center;
            padding: 80px 0;
        }
        
        /* Section Styles */
        .section-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }
        
        /* Category Banner */
        .category-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 60px 40px;
            margin-bottom: 60px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        
        .category-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        
        .category-banner h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .category-banner p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .product-card img {
            height: 250px;
            object-fit: cover;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 217, 255, 0.4);
        }
        
        .badge-new {
            background: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        
        .badge-preorder {
            background: #ff9800;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        
        .footer {
            background: var(--dark-color);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 5rem;
        }
        
        .footer h5 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .footer a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .footer a:hover {
            color: var(--primary-color);
        }
        
        /* Cart Badge */
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--accent-color);
            color: #fff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
        }
        
        /* Breadcrumb Styles */
        .breadcrumb-item a {
            text-decoration: none;
            color: var(--primary-color);
        }
        
        .breadcrumb-item a:hover {
            text-decoration: underline;
        }
        
        /* Pagination Styles */
        .pagination {
            margin: 40px 0;
        }
        
        .pagination .page-link {
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 6px;
            margin: 0 4px;
            border: 1px solid #dee2e6;
            color: #333;
        }
        
        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .pagination .page-link:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-slide {
                min-height: 350px;
                padding: 40px 0;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
            
            .category-banner h2 {
                font-size: 1.75rem;
            }
            
            .pagination .page-link {
                padding: 6px 12px;
                font-size: 13px;
            }
        }
        
        .price {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--accent-color);
        }
        
        .old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 1.2rem;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <i class="fas fa-phone-alt me-2"></i> Hotline: 1900 xxxx
                </div>
                <div class="col-md-6 text-end">
                    <i class="fas fa-map-marker-alt me-2"></i> Hà Nội - TP.HCM
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo/logohalo.png') }}" alt="HaloShop" style="height: 40px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('news.index') }}">Tin tức</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Liên hệ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">Về chúng tôi</a>
                    </li>
                </ul>
                
                <!-- Form tìm kiếm -->
                <form action="{{ route('products.index') }}" method="GET" class="d-none d-lg-flex mx-3">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Tìm sản phẩm..." value="{{ request('search') }}" style="width: 200px; border-radius: 20px;">
                </form>
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="cart-badge">{{ count(session('cart')) }}</span>
                            @endif
                        </a>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle fa-lg"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('account.profile') }}"><i class="fas fa-user me-2"></i>Tài khoản</a></li>
                                <li><a class="dropdown-item" href="{{ route('account.orders') }}"><i class="fas fa-box me-2"></i>Đơn hàng</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm ms-2" href="{{ route('register') }}" style="white-space: nowrap;">
                                <i class="fas fa-user-plus"></i> Đăng ký
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alerts -->
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-gamepad"></i> HaloShop</h5>
                    <p>Cửa hàng game và thiết bị công nghệ hàng đầu Việt Nam</p>
                </div>
                <div class="col-md-4">
                    <h5>Liên hệ</h5>
                    <p>
                        <i class="fas fa-phone"></i> 1900 xxxx<br>
                        <i class="fas fa-envelope"></i> contact@haloshop.vn<br>
                        <i class="fas fa-map-marker-alt"></i> Hà Nội, Việt Nam
                    </p>
                </div>
                <div class="col-md-4">
                    <h5>Theo dõi chúng tôi</h5>
                    <p>
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-youtube fa-2x"></i></a>
                    </p>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1)">
            <div class="text-center">
                <p>&copy; 2026 HaloShop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>
