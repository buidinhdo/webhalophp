<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HaloShop - Gaming Store')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
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
            padding: 10px 0;
            font-size: 14px;
        }
        
        .top-bar .container,
        .navbar .container {
            max-width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }
        
        .navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
            overflow: visible;
        }
        
        .navbar-brand {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color) !important;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-left: 0 !important;
            padding-left: 0 !important;
        }
        
        /* Navbar Menu */
        .navbar-expand-lg .navbar-collapse {
            display: flex !important;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .navbar-nav {
            flex-direction: row !important;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .navbar-nav .nav-item {
            display: inline-block;
            flex-shrink: 0;
        }
        
        @media (min-width: 992px) {
            .navbar-nav {
                flex-wrap: nowrap;
            }
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
        
        /* Dropdown Menu Styles */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border-radius: 8px;
            padding: 10px 0;
            margin-top: 5px;
        }
        
        .dropdown-item {
            padding: 10px 20px;
            font-size: 14px;
            transition: all 0.3s;
            color: #333;
        }
        
        .dropdown-item:hover {
            background-color: rgba(0, 217, 255, 0.1);
            color: var(--primary-color);
            padding-left: 25px;
        }
        
        .dropdown-item.active {
            background-color: var(--primary-color);
            color: #fff;
        }
        
        .dropdown-toggle::after {
            margin-left: 5px;
        }
        
        /* Auth Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            white-space: nowrap;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 217, 255, 0.4);
        }
        
        .navbar-nav .nav-item .btn-primary {
            margin-top: 0;
            margin-bottom: 0;
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
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-phone-alt me-2"></i> Hotline: 028 7306 8666
                </div>
                <div>
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
                        <a class="nav-link" href="{{ route('home') }}"><i class="fas fa-home me-2"></i>{{ __('general.home') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="productsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-gamepad me-2"></i>{{ __('general.products') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="productsDropdown">
                            <li><a class="dropdown-item" href="{{ route('products.index') }}"><i class="fas fa-th-large me-2"></i>Tất cả sản phẩm</a></li>
                            @if(isset($headerCategories) && $headerCategories->count() > 0)
                                <li><hr class="dropdown-divider"></li>
                                @foreach($headerCategories as $category)
                                    <li><a class="dropdown-item" href="{{ route('products.index', ['category' => $category->slug]) }}">
                                        <i class="fas fa-angle-right me-2"></i>{{ $category->name }}
                                    </a></li>
                                @endforeach
                            @endif
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('news.index') }}"><i class="fas fa-newspaper me-2"></i>{{ __('general.news') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}"><i class="fas fa-envelope me-2"></i>{{ __('general.contact') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}"><i class="fas fa-info-circle me-2"></i>{{ __('general.about') }}</a>
                    </li>
                </ul>
                
                <!-- Form tìm kiếm -->
                <form action="{{ route('products.index') }}" method="GET" class="d-none d-lg-flex mx-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('general.search_placeholder') }}" value="{{ request('search') }}" style="width: 180px; border-radius: 20px;">
                </form>
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-globe"></i> {{ app()->getLocale() == 'vi' ? 'Tiếng Việt' : 'English' }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="langDropdown">
                            <li><a class="dropdown-item {{ app()->getLocale() == 'vi' ? 'active' : '' }}" href="{{ route('language.switch', 'vi') }}"><i class="fas fa-flag me-2"></i>Tiếng Việt</a></li>
                            <li><a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('language.switch', 'en') }}"><i class="fas fa-flag me-2"></i>English</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="cart-badge">{{ count(session('cart')) }}</span>
                            @endif
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('wishlist.index') }}" title="Yêu thích">
                                <i class="fas fa-heart fa-lg"></i>
                                <span class="wishlist-count cart-badge" style="display: none;">0</span>
                            </a>
                        </li>
                    @endauth
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle fa-lg"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('account.profile') }}"><i class="fas fa-user me-2"></i>{{ __('general.account') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('account.orders') }}"><i class="fas fa-box me-2"></i>{{ app()->getLocale() == 'vi' ? 'Đơn hàng' : 'Orders' }}</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>{{ __('general.logout') }}</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> {{ __('general.login') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm ms-2" href="{{ route('register') }}" style="white-space: nowrap;">
                                <i class="fas fa-user-plus"></i> {{ __('general.register') }}
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
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5><img src="{{ asset('images/logo/logohalo.png') }}" alt="HaloShop" style="height: 35px;"></h5>
                    <p class="mt-3">HALO Shop cửa hàng Game Console cao cấp số 1 tại Tp.HCM. Chuyên kinh doanh về PlayStation, Nintendo Switch, XBox và các hệ máy handheld.</p>
                    
                    <h6 class="mt-4 fw-bold">MUA HÀNG - GÓP Ý</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><strong>Hotline:</strong> <span style="color: #ffc107;">02873068666</span></li>
                        <li class="mb-2"><strong>E-mail:</strong> sales@halo.vn</li>
                        <li class="mb-2"><strong>Website:</strong> haloshop.vn</li>
                        <li class="mb-2"><strong>Gọi qua Zalo:</strong> HALO SHOP QA</li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="fw-bold">ĐỊA CHỈ</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">• 92 Pasteur, P. Sài Gòn, TP.HCM</li>
                        <li class="mb-2">• 11 Nguyễn Hữu Cảnh, P. Thạnh Mỹ Tây, TP.HCM</li>
                    </ul>
                    
                    <h6 class="mt-4 fw-bold">THỜI GIAN LÀM VIỆC</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">• Các ngày trong tuần (T2 - T7): 9h - 20h</li>
                        <li class="mb-2">• Chủ nhật và ngày lễ: 9h - 19h</li>
                    </ul>
                    
                    <div class="mt-3">
                        <img src="{{ asset('images/logo/da-thong-bao.svg') }}" alt="Đã thông báo Bộ Công Thương" style="height: 50px;">
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="fw-bold">THEO DÕI HALO TẠI</h6>
                    <div class="d-flex flex-column gap-2">
                        <a href="https://www.facebook.com/halo.vn" target="_blank" class="btn btn-primary text-white" style="background: #1877f2; border: none; display: flex; align-items: center; justify-content: flex-start; padding: 10px 15px;">
                            <i class="fab fa-facebook" style="width: 24px; font-size: 18px; display: flex; align-items: center; justify-content: center; margin-right: 12px;"></i> 
                            <span style="flex: 1; text-align: left;">HALO.VN</span>
                        </a>
                        <a href="https://www.facebook.com/groups/ps5vietnam/?locale=vi_VN" target="_blank" class="btn text-white" style="background: #0088cc; border: none; display: flex; align-items: center; justify-content: flex-start; padding: 10px 15px;">
                            <i class="fas fa-users" style="width: 24px; font-size: 18px; display: flex; align-items: center; justify-content: center; margin-right: 12px;"></i> 
                            <span style="flex: 1; text-align: left;">HỘI PS5 VIỆT NAM</span>
                        </a>
                        <a href="#" target="_blank" class="btn text-white" style="background: #0068ff; border: none; display: flex; align-items: center; justify-content: flex-start; padding: 10px 15px;">
                            <i class="fas fa-comments" style="width: 24px; font-size: 18px; display: flex; align-items: center; justify-content: center; margin-right: 12px;"></i> 
                            <span style="flex: 1; text-align: left;">HALO SHOP</span>
                        </a>
                        <a href="https://www.youtube.com/@haloshopvn" target="_blank" class="btn btn-danger text-white" style="border: none; display: flex; align-items: center; justify-content: flex-start; padding: 10px 15px;">
                            <i class="fab fa-youtube" style="width: 24px; font-size: 18px; display: flex; align-items: center; justify-content: center; margin-right: 12px;"></i> 
                            <span style="flex: 1; text-align: left;">HALOSHOP.VN</span>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="fw-bold">THÔNG TIN</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Quy định chung</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Mua hàng trả góp</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Quy định đặt cọc</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Quy định bảo hành</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Chính sách vận chuyển</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Chính sách đổi / trả hàng</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Trung tâm bảo hành</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Tuyển dụng</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
            </div>
            
            <hr style="border-color: rgba(255,255,255,0.1)">
            <div class="text-center">
                <p>&copy; 2026 HaloShop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickViewModalLabel">{{ __('general.quick_view') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="quickViewContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chatbot Widget -->
    @include('partials.chatbot')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- Quick View JS -->
    <script>
        function quickView(productId) {
            const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
            const content = document.getElementById('quickViewContent');
            
            // Show loading
            content.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;
            
            modal.show();
            
            // Fetch product data
            fetch(`/api/san-pham/quick-view/${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const product = data.product;
                        content.innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="${product.image || 'https://via.placeholder.com/400'}" class="img-fluid rounded" alt="${product.name}" style="max-height: 400px; object-fit: contain; width: 100%;">
                                </div>
                                <div class="col-md-6">
                                    <h3 class="mb-3">${product.name}</h3>
                                    ${product.is_new ? '<span class="badge bg-success me-1">MỚI</span>' : ''}
                                    ${product.is_preorder ? '<span class="badge bg-warning">ĐẶT TRƯỚC</span>' : ''}
                                    
                                    ${product.platform ? `<p class="text-muted mt-3"><i class="fas fa-gamepad"></i> <strong>Nền tảng:</strong> ${product.platform}</p>` : ''}
                                    ${product.sku ? `<p class="text-muted"><strong>SKU:</strong> ${product.sku}</p>` : ''}
                                    
                                    <div class="my-4">
                                        ${product.sale_price_formatted ? `
                                            <span class="text-decoration-line-through text-muted d-block mb-2" style="font-size: 1.2rem;">${product.price_formatted}₫</span>
                                            <span class="text-danger fw-bold" style="font-size: 2rem;">${product.sale_price_formatted}₫</span>
                                            <span class="badge bg-danger ms-2">Giảm ${product.discount_percent}%</span>
                                        ` : `
                                            <span class="fw-bold" style="font-size: 2rem;">${product.price_formatted}₫</span>
                                        `}
                                    </div>
                                    
                                    ${product.stock > 0 ? `
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i> Còn hàng: ${product.stock} sản phẩm
                                        </div>
                                    ` : `
                                        <div class="alert alert-danger">
                                            <i class="fas fa-times-circle"></i> Hết hàng
                                        </div>
                                    `}
                                    
                                    ${product.short_description ? `
                                        <div class="mb-3">
                                            <h6>Mô tả ngắn:</h6>
                                            <p>${product.short_description}</p>
                                        </div>
                                    ` : ''}
                                    
                                    <div class="d-grid gap-2">
                                        <a href="/san-pham/${product.slug}" class="btn btn-primary btn-lg">
                                            <i class="fas fa-eye"></i> Xem chi tiết
                                        </a>
                                        ${product.stock > 0 ? `
                                            <form action="/gio-hang/them/${product.id}" method="POST">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-success btn-lg w-100">
                                                    <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                                                </button>
                                            </form>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        content.innerHTML = '<div class="alert alert-danger">Không thể tải thông tin sản phẩm</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML = '<div class="alert alert-danger">Có lỗi xảy ra khi tải dữ liệu</div>';
                });
        }
    </script>
    
    <!-- Clear chat session on logout -->
    <script>
        // Clear chat localStorage khi user logout
        const logoutForm = document.getElementById('logout-form');
        if (logoutForm) {
            logoutForm.addEventListener('submit', function() {
                localStorage.removeItem('chat_session_id');
                localStorage.removeItem('chat_user_id');
            });
        }
    </script>
    
    <!-- Load wishlist count -->
    @auth
    <script>
        // Load wishlist count on page load
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/yeu-thich/check/1') // Use any product ID just to get count
                .then(response => response.json())
                .then(data => {
                    if (data.count !== undefined) {
                        const wishlistCount = document.querySelector('.wishlist-count');
                        if (wishlistCount && data.count > 0) {
                            wishlistCount.textContent = data.count;
                            wishlistCount.style.display = 'inline-block';
                        }
                    }
                })
                .catch(error => console.log('Wishlist count load error:', error));
        });
    </script>
    @endauth
    
    @yield('scripts')
</body>
</html>
