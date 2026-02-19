@extends('layouts.app')

@section('title', 'Trang chủ - HaloShop')

@section('styles')
<style>
    .product-image-wrapper {
        position: relative;
        overflow: hidden;
    }
    .quick-view-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
        z-index: 10;
    }
    .product-card:hover .quick-view-overlay {
        opacity: 1;
    }
    .quick-view-icon {
        width: 60px;
        height: 60px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transform: scale(0.8);
        transition: transform 0.3s ease;
    }
    .quick-view-overlay:hover .quick-view-icon {
        transform: scale(1);
    }
    .quick-view-icon i {
        font-size: 24px;
        color: #007bff;
    }
    .product-title-link {
        color: #333;
        text-decoration: none;
        transition: color 0.3s ease;
        display: block;
        cursor: pointer;
    }
    .product-title-link:hover {
        color: #007bff;
    }
    .wishlist-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        cursor: pointer;
        z-index: 15;
        transition: all 0.3s ease;
    }
    .wishlist-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .wishlist-btn i {
        font-size: 18px;
        color: #999;
        transition: color 0.3s ease;
    }
    .wishlist-btn.active i {
        color: #ff4081;
    }
    .wishlist-btn:hover i {
        color: #ff4081;
    }
    
    /* Swiper Slider Styles */
    .product-slider {
        position: relative;
        padding: 0 50px;
    }
    .product-slider .swiper-button-next,
    .product-slider .swiper-button-prev {
        width: 45px;
        height: 45px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        z-index: 20;
        cursor: pointer;
    }
    .product-slider .swiper-button-next:after,
    .product-slider .swiper-button-prev:after {
        font-size: 20px;
        color: #333;
        font-weight: bold;
    }
    .product-slider .swiper-button-next:hover,
    .product-slider .swiper-button-prev:hover {
        background: var(--primary-color);
        transform: scale(1.1);
    }
    .product-slider .swiper-button-next:hover:after,
    .product-slider .swiper-button-prev:hover:after {
        color: white;
    }
    .product-slider .swiper-button-disabled {
        opacity: 0.35;
        cursor: not-allowed;
        pointer-events: none;
    }
    .product-slider .swiper-pagination {
        position: relative;
        margin-top: 25px;
        z-index: 10;
    }
    .product-slider .swiper-pagination-bullet {
        width: 10px;
        height: 10px;
        background: #ccc;
        opacity: 1;
        transition: all 0.3s ease;
    }
    .product-slider .swiper-pagination-bullet-active {
        background: var(--primary-color);
        width: 25px;
        border-radius: 5px;
    }
    .product-slider .swiper-slide {
        height: auto;
    }
    
    /* Genre Collections Styles */
    .genre-collections-slider {
        position: relative;
        padding: 0 50px;
    }
    .genre-card {
        display: block;
        text-decoration: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        height: 280px;
    }
    .genre-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
    .genre-image-wrapper {
        position: relative;
        height: 100%;
        overflow: hidden;
    }
    .genre-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .genre-card:hover .genre-image-wrapper img {
        transform: scale(1.1);
    }
    .genre-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.5) 70%, transparent 100%);
        padding: 30px 20px;
        color: white;
    }
    .genre-title {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #FFD700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .genre-count {
        margin: 5px 0 0 0;
        font-size: 14px;
        opacity: 0.9;
    }
    .genre-collections-slider .swiper-button-next,
    .genre-collections-slider .swiper-button-prev {
        width: 45px;
        height: 45px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }
    .genre-collections-slider .swiper-button-next:after,
    .genre-collections-slider .swiper-button-prev:after {
        font-size: 20px;
        color: #333;
        font-weight: bold;
    }
    .genre-collections-slider .swiper-button-next:hover,
    .genre-collections-slider .swiper-button-prev:hover {
        background: var(--primary-color);
        transform: scale(1.1);
    }
    .genre-collections-slider .swiper-button-next:hover:after,
    .genre-collections-slider .swiper-button-prev:hover:after {
        color: white;
    }
    .genre-collections-slider .swiper-pagination {
        position: relative;
        margin-top: 25px;
    }
    .genre-collections-slider .swiper-pagination-bullet {
        width: 10px;
        height: 10px;
        background: #ccc;
        opacity: 1;
        transition: all 0.3s ease;
    }
    .genre-collections-slider .swiper-pagination-bullet-active {
        background: var(--primary-color);
        width: 25px;
        border-radius: 5px;
    }
</style>
@endsection

@section('content')
<!-- Hero Slider -->
<div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="hero-slide" style="background: url('{{ asset('images/banners/banner6.jpg') }}') center/cover no-repeat;">
            </div>
        </div>
        <div class="carousel-item">
            <div class="hero-slide" style="background: url('{{ asset('images/banners/banner3.jpg') }}') center/cover no-repeat;">
            </div>
        </div>
        <div class="carousel-item">
            <div class="hero-slide" style="background: url('{{ asset('images/banners/banner4.jpeg') }}') center/cover no-repeat;">
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Game Collections / Genre Themes -->
@if(isset($genreCollections) && $genreCollections->count() > 0)
<section class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">
            <i class="fab fa-playstation text-primary me-2"></i> Chủ đề Game
        </h2>
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">
            Xem thêm <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="genre-collections-slider">
        <div class="swiper genreSwiper">
            <div class="swiper-wrapper">
                @foreach($genreCollections as $collection)
                <div class="swiper-slide">
                    <a href="{{ route('products.index', ['genre' => $collection['genre']]) }}" class="genre-card">
                        <div class="genre-image-wrapper">
                            @if($collection['image'])
                                <img src="{{ asset($collection['image']) }}" alt="{{ $collection['genre'] }}">
                            @else
                                <img src="https://via.placeholder.com/400x250?text={{ urlencode($collection['genre']) }}" alt="{{ $collection['genre'] }}">
                            @endif
                            <div class="genre-overlay">
                                <h3 class="genre-title">{{ strtoupper($collection['genre']) }}</h3>
                                <p class="genre-count">{{ $collection['product_count'] }} sản phẩm</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
@endif

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section class="container my-5">
    <h2 class="section-title">
        <i class="fas fa-star text-warning me-2"></i> Sản phẩm nổi bật
    </h2>
    <div class="product-slider">
        <div class="swiper featuredSwiper">
            <div class="swiper-wrapper">
                @foreach($featuredProducts as $product)
                <div class="swiper-slide">
                    <div class="card product-card position-relative">
                        @if($product->sale_price)
                            <span class="badge-sale">SALE</span>
                        @endif
                        @if($product->is_new)
                            <span class="badge-new">NEW</span>
                        @endif
                        <div class="product-image-wrapper">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x250?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
                            @endif
                            @auth
                            <button type="button" class="wishlist-btn" onclick="toggleWishlist({{ $product->id }}, this)" title="Thêm vào yêu thích">
                                <i class="far fa-heart"></i>
                            </button>
                            @endauth
                            <div class="quick-view-overlay" onclick="quickView({{ $product->id }})">
                                <div class="quick-view-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('products.show', $product->slug) }}" class="product-title-link">
                                <h5 class="card-title">{{ Str::limit($product->name, 45) }}</h5>
                            </a>
                            <p class="card-text mb-3">
                                @if($product->sale_price)
                                    <span class="old-price d-block">{{ number_format($product->price) }}₫</span>
                                    <span class="price">{{ number_format($product->sale_price) }}₫</span>
                                @else
                                    <span class="price">{{ number_format($product->price) }}₫</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
@endif

<!-- PlayStation Section -->
<div class="category-banner" style="background: url('{{ asset('images/banners/banner7.jpg') }}') center/cover no-repeat;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-3"><i class="fab fa-playstation me-3"></i>PLAYSTATION PS2-PS5</h2>
                <p class="mb-4">Trải nghiệm gaming đỉnh cao với dòng máy chơi game PlayStation từ thế hệ PS2 đến PS5 Pro mới nhất</p>
                <a href="{{ route('products.index', ['platform' => 'PS5']) }}" class="btn btn-light px-4">
                    Xem thêm <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- New Products -->
@if($newProducts->count() > 0)
<section class="container my-5">
    <h2 class="section-title">
        <i class="fas fa-fire text-danger me-2"></i> Sản phẩm mới
    </h2>
    <div class="product-slider">
        <div class="swiper newSwiper">
            <div class="swiper-wrapper">
                @foreach($newProducts as $product)
                <div class="swiper-slide">
                    <div class="card product-card position-relative">
                        @if($product->is_preorder)
                            <span class="badge-preorder">PRE-ORDER</span>
                        @else
                            <span class="badge-new">NEW</span>
                        @endif
                        <div class="product-image-wrapper">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x250?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
                            @endif
                            @auth
                            <button type="button" class="wishlist-btn" onclick="toggleWishlist({{ $product->id }}, this)" title="Thêm vào yêu thích">
                                <i class="far fa-heart"></i>
                            </button>
                            @endauth
                            <div class="quick-view-overlay" onclick="quickView({{ $product->id }})">
                                <div class="quick-view-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('products.show', $product->slug) }}" class="product-title-link">
                                <h5 class="card-title">{{ Str::limit($product->name, 45) }}</h5>
                            </a>
                            <p class="card-text mb-3">
                                @if($product->sale_price)
                                    <span class="old-price d-block">{{ number_format($product->price) }}₫</span>
                                    <span class="price">{{ number_format($product->sale_price) }}₫</span>
                                @else
                                    <span class="price">{{ number_format($product->price) }}₫</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
@endif

<!-- Nintendo Switch Section -->
<div class="category-banner" style="background: url('{{ asset('images/banners/banner8.jpg') }}') center/cover no-repeat;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-3">
                    <img src="{{ asset('images/icons/nintendo-switch.svg') }}" alt="Nintendo Switch" style="width: 36px; height: 36px; margin-right: 1rem; vertical-align: middle;">NINTENDO SWITCH 2
                </h2>
                <p class="mb-4">Máy chơi game cầm tay lai mới nhất của Nintendo - Linh hoạt mọi lúc mọi nơi</p>
                <a href="{{ route('products.index', ['platform' => 'Nintendo Switch']) }}" class="btn btn-light px-4">
                    Xem thêm <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Preorder Products -->
@if($preorderProducts->count() > 0)
<section class="container my-5">
    <h2 class="section-title">
        <i class="fas fa-clock text-warning me-2"></i> Sản phẩm đặt trước
    </h2>
    <div class="product-slider">
        <div class="swiper preorderSwiper">
            <div class="swiper-wrapper">
                @foreach($preorderProducts as $product)
                <div class="swiper-slide">
                    <div class="card product-card position-relative">
                        <span class="badge-preorder">PRE-ORDER</span>
                        <div class="product-image-wrapper">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x250?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
                            @endif
                            @auth
                            <button type="button" class="wishlist-btn" onclick="toggleWishlist({{ $product->id }}, this)" title="Thêm vào yêu thích">
                                <i class="far fa-heart"></i>
                            </button>
                            @endauth
                            <div class="quick-view-overlay" onclick="quickView({{ $product->id }})">
                                <div class="quick-view-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('products.show', $product->slug) }}" class="product-title-link">
                                <h5 class="card-title">{{ Str::limit($product->name, 45) }}</h5>
                            </a>
                            @if($product->release_date)
                                <p class="text-muted small mb-2">
                                    <i class="far fa-calendar me-1"></i> {{ $product->release_date->format('d/m/Y') }}
                                </p>
                            @endif
                            <p class="card-text mb-3">
                                @if($product->sale_price)
                                    <span class="old-price d-block">{{ number_format($product->price) }}₫</span>
                                    <span class="price">{{ number_format($product->sale_price) }}₫</span>
                                @else
                                    <span class="price">{{ number_format($product->price) }}₫</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
@endif

<!-- PS4 Products -->
@if($ps4Products->count() > 0)
<section class="container my-5">
    <h2 class="section-title">
        <i class="fab fa-playstation text-primary me-2"></i> PlayStation 4
    </h2>
    <div class="product-slider">
        <div class="swiper ps4Swiper">
            <div class="swiper-wrapper">
                @foreach($ps4Products as $product)
                <div class="swiper-slide">
                    <div class="card product-card position-relative">
                        @if($product->sale_price)
                            <span class="badge-sale">SALE</span>
                        @endif
                        @if($product->is_new)
                            <span class="badge-new">NEW</span>
                        @endif
                        <div class="product-image-wrapper">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x250?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
                            @endif
                            @auth
                            <button type="button" class="wishlist-btn" onclick="toggleWishlist({{ $product->id }}, this)" title="Thêm vào yêu thích">
                                <i class="far fa-heart"></i>
                            </button>
                            @endauth
                            <div class="quick-view-overlay" onclick="quickView({{ $product->id }})">
                                <div class="quick-view-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('products.show', $product->slug) }}" class="product-title-link">
                                <h5 class="card-title">{{ Str::limit($product->name, 45) }}</h5>
                            </a>
                            <p class="card-text mb-3">
                                @if($product->sale_price)
                                    <span class="old-price d-block">{{ number_format($product->price) }}₫</span>
                                    <span class="price">{{ number_format($product->sale_price) }}₫</span>
                                @else
                                    <span class="price">{{ number_format($product->price) }}₫</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
@endif

<!-- PS5 Products -->
@if($ps5Products->count() > 0)
<section class="container my-5">
    <h2 class="section-title">
        <i class="fab fa-playstation text-info me-2"></i> PlayStation 5
    </h2>
    <div class="product-slider">
        <div class="swiper ps5Swiper">
            <div class="swiper-wrapper">
                @foreach($ps5Products as $product)
                <div class="swiper-slide">
                    <div class="card product-card position-relative">
                        @if($product->sale_price)
                            <span class="badge-sale">SALE</span>
                        @endif
                        @if($product->is_new)
                            <span class="badge-new">NEW</span>
                        @endif
                        <div class="product-image-wrapper">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x250?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
                            @endif
                            @auth
                            <button type="button" class="wishlist-btn" onclick="toggleWishlist({{ $product->id }}, this)" title="Thêm vào yêu thích">
                                <i class="far fa-heart"></i>
                            </button>
                            @endauth
                            <div class="quick-view-overlay" onclick="quickView({{ $product->id }})">
                                <div class="quick-view-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('products.show', $product->slug) }}" class="product-title-link">
                                <h5 class="card-title">{{ Str::limit($product->name, 45) }}</h5>
                            </a>
                            <p class="card-text mb-3">
                                @if($product->sale_price)
                                    <span class="old-price d-block">{{ number_format($product->price) }}₫</span>
                                    <span class="price">{{ number_format($product->sale_price) }}₫</span>
                                @else
                                    <span class="price">{{ number_format($product->price) }}₫</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
@endif

<!-- Nintendo Switch Products -->
@if($nintendoProducts->count() > 0)
<section class="container my-5">
    <h2 class="section-title">
        <img src="{{ asset('images/icons/nintendo-switch.svg') }}" alt="Nintendo Switch" style="width: 28px; height: 28px; margin-right: 0.5rem; vertical-align: middle;"> Nintendo Switch
    </h2>
    <div class="product-slider">
        <div class="swiper nintendoSwiper">
            <div class="swiper-wrapper">
                @foreach($nintendoProducts as $product)
                <div class="swiper-slide">
                    <div class="card product-card position-relative">
                        @if($product->sale_price)
                            <span class="badge-sale">SALE</span>
                        @endif
                        @if($product->is_new)
                            <span class="badge-new">NEW</span>
                        @endif
                        <div class="product-image-wrapper">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x250?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
                            @endif
                            @auth
                            <button type="button" class="wishlist-btn" onclick="toggleWishlist({{ $product->id }}, this)" title="Thêm vào yêu thích">
                                <i class="far fa-heart"></i>
                            </button>
                            @endauth
                            <div class="quick-view-overlay" onclick="quickView({{ $product->id }})">
                                <div class="quick-view-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('products.show', $product->slug) }}" class="product-title-link">
                                <h5 class="card-title">{{ Str::limit($product->name, 45) }}</h5>
                            </a>
                            <p class="card-text mb-3">
                                @if($product->sale_price)
                                    <span class="old-price d-block">{{ number_format($product->price) }}₫</span>
                                    <span class="price">{{ number_format($product->sale_price) }}₫</span>
                                @else
                                    <span class="price">{{ number_format($product->price) }}₫</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
@endif

<!-- Xbox Products -->
@if($xboxProducts->count() > 0)
<section class="container my-5">
    <h2 class="section-title">
        <i class="fab fa-xbox text-success me-2"></i> Xbox
    </h2>
    <div class="product-slider">
        <div class="swiper xboxSwiper">
            <div class="swiper-wrapper">
                @foreach($xboxProducts as $product)
                <div class="swiper-slide">
                    <div class="card product-card position-relative">
                        @if($product->sale_price)
                            <span class="badge-sale">SALE</span>
                        @endif
                        @if($product->is_new)
                            <span class="badge-new">NEW</span>
                        @endif
                        <div class="product-image-wrapper">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x250?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
                            @endif
                            @auth
                            <button type="button" class="wishlist-btn" onclick="toggleWishlist({{ $product->id }}, this)" title="Thêm vào yêu thích">
                                <i class="far fa-heart"></i>
                            </button>
                            @endauth
                            <div class="quick-view-overlay" onclick="quickView({{ $product->id }})">
                                <div class="quick-view-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('products.show', $product->slug) }}" class="product-title-link">
                                <h5 class="card-title">{{ Str::limit($product->name, 45) }}</h5>
                            </a>
                            <p class="card-text mb-3">
                                @if($product->sale_price)
                                    <span class="old-price d-block">{{ number_format($product->price) }}₫</span>
                                    <span class="price">{{ number_format($product->sale_price) }}₫</span>
                                @else
                                    <span class="price">{{ number_format($product->price) }}₫</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
@endif

<!-- Apple Section -->
<div class="category-banner" style="background: url('{{ asset('images/banners/banner9.jpg') }}') center/cover no-repeat;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-3"><i class="fab fa-apple me-3"></i>APPLE ECOSYSTEM</h2>
                <p class="mb-4">iPhone 17, iPad Pro M5, MacBook - Hệ sinh thái Apple đẳng cấp, chính hãng VN/A</p>
                <a href="{{ route('products.index', ['platform' => 'iPhone']) }}" class="btn btn-light px-4">
                    Xem thêm <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- News Section -->
@if(isset($posts) && $posts->count() > 0)
<section class="container my-5">
    <h2 class="section-title">
        <i class="far fa-newspaper text-info me-2"></i> Tin tức mới
    </h2>
    <div class="row g-4">
        @foreach($posts as $post)
        <div class="col-md-4">
            <div class="card product-card h-100 d-flex flex-column">
                @if($post->image)
                    <img src="{{ asset($post->image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                @else
                    <img src="https://via.placeholder.com/400x200?text={{ urlencode($post->title) }}" class="card-img-top" alt="{{ $post->title }}">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ Str::limit($post->title, 60) }}</h5>
                    <p class="card-text text-muted small mb-2">
                        <i class="far fa-calendar me-1"></i> {{ $post->published_at->format('d/m/Y') }}
                    </p>
                    <p class="card-text flex-grow-1">{{ Str::limit($post->excerpt, 100) }}</p>
                    <a href="{{ route('news.show', $post->slug) }}" class="btn btn-outline-primary btn-sm mt-auto">Đọc thêm <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Collections -->
@if($collections->count() > 0)
<section class="container my-5">
    <h2 class="section-title">
        <i class="fas fa-th-large text-primary me-2"></i> Bộ sưu tập
    </h2>
    <div class="row g-4">
        @foreach($collections as $collection)
        <div class="col-md-4">
            <div class="card product-card">
                @if($collection->image)
                    <img src="{{ asset('storage/' . $collection->image) }}" class="card-img-top" alt="{{ $collection->name }}">
                @else
                    <img src="https://via.placeholder.com/400x200?text={{ urlencode($collection->name) }}" class="card-img-top" alt="{{ $collection->name }}">
                @endif
                <div class="card-body text-center">
                    <h4 class="card-title">{{ $collection->name }}</h4>
                    <p class="card-text">{{ Str::limit($collection->description, 100) }}</p>
                    <a href="#" class="btn btn-primary">Xem thêm</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif
@endsection

@section('scripts')
<script>
// Initialize Swiper Sliders
document.addEventListener('DOMContentLoaded', function() {
    // Featured Products Slider
    const featuredSwiper = new Swiper('.featuredSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        navigation: {
            nextEl: '.featuredSwiper .swiper-button-next',
            prevEl: '.featuredSwiper .swiper-button-prev',
        },
        pagination: {
            el: '.featuredSwiper .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        breakpoints: {
            576: {
                slidesPerView: 2,
                spaceBetween: 15,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            992: {
                slidesPerView: 4,
                spaceBetween: 20,
            }
        }
    });

    // Genre Collections Slider
    const genreSwiper = new Swiper('.genreSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        navigation: {
            nextEl: '.genre-collections-slider .swiper-button-next',
            prevEl: '.genre-collections-slider .swiper-button-prev',
        },
        pagination: {
            el: '.genre-collections-slider .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        breakpoints: {
            576: {
                slidesPerView: 2,
                spaceBetween: 15,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            992: {
                slidesPerView: 4,
                spaceBetween: 20,
            }
        }
    });

    // New Products Slider
    const newSwiper = new Swiper('.newSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        navigation: {
            nextEl: '.newSwiper .swiper-button-next',
            prevEl: '.newSwiper .swiper-button-prev',
        },
        pagination: {
            el: '.newSwiper .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        breakpoints: {
            576: {
                slidesPerView: 2,
                spaceBetween: 15,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            992: {
                slidesPerView: 4,
                spaceBetween: 20,
            }
        }
    });

    // Preorder Products Slider
    const preorderSwiper = new Swiper('.preorderSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        navigation: {
            nextEl: '.preorderSwiper .swiper-button-next',
            prevEl: '.preorderSwiper .swiper-button-prev',
        },
        pagination: {
            el: '.preorderSwiper .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        breakpoints: {
            576: {
                slidesPerView: 2,
                spaceBetween: 15,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            992: {
                slidesPerView: 4,
                spaceBetween: 20,
            }
        }
    });

    // PS4 Products Slider
    const ps4Swiper = new Swiper('.ps4Swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 3200,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        navigation: {
            nextEl: '.ps4Swiper .swiper-button-next',
            prevEl: '.ps4Swiper .swiper-button-prev',
        },
        pagination: {
            el: '.ps4Swiper .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        breakpoints: {
            576: {
                slidesPerView: 2,
                spaceBetween: 15,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            992: {
                slidesPerView: 4,
                spaceBetween: 20,
            }
        }
    });

    // PS5 Products Slider
    const ps5Swiper = new Swiper('.ps5Swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 3400,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        navigation: {
            nextEl: '.ps5Swiper .swiper-button-next',
            prevEl: '.ps5Swiper .swiper-button-prev',
        },
        pagination: {
            el: '.ps5Swiper .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        breakpoints: {
            576: {
                slidesPerView: 2,
                spaceBetween: 15,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            992: {
                slidesPerView: 4,
                spaceBetween: 20,
            }
        }
    });

    // Nintendo Switch Products Slider
    const nintendoSwiper = new Swiper('.nintendoSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 3600,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        navigation: {
            nextEl: '.nintendoSwiper .swiper-button-next',
            prevEl: '.nintendoSwiper .swiper-button-prev',
        },
        pagination: {
            el: '.nintendoSwiper .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        breakpoints: {
            576: {
                slidesPerView: 2,
                spaceBetween: 15,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            992: {
                slidesPerView: 4,
                spaceBetween: 20,
            }
        }
    });

    // Xbox Products Slider
    const xboxSwiper = new Swiper('.xboxSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 3800,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        navigation: {
            nextEl: '.xboxSwiper .swiper-button-next',
            prevEl: '.xboxSwiper .swiper-button-prev',
        },
        pagination: {
            el: '.xboxSwiper .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        breakpoints: {
            576: {
                slidesPerView: 2,
                spaceBetween: 15,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            992: {
                slidesPerView: 4,
                spaceBetween: 20,
            }
        }
    });
});

function toggleWishlist(productId, button) {
    fetch(`/yeu-thich/toggle/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const icon = button.querySelector('i');
            if (data.inWishlist) {
                button.classList.add('active');
                icon.className = 'fas fa-heart';
            } else {
                button.classList.remove('active');
                icon.className = 'far fa-heart';
            }
            
            // Update wishlist count in navbar
            updateWishlistCount(data.count);
            
            // Show toast notification
            showToast(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra, vui lòng thử lại!', 'error');
    });
}

function updateWishlistCount(count) {
    const wishlistCount = document.querySelector('.wishlist-count');
    if (wishlistCount) {
        wishlistCount.textContent = count;
        wishlistCount.style.display = count > 0 ? 'inline-block' : 'none';
    }
}

function showToast(message, type = 'success') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${type === 'success' ? '#28a745' : '#dc3545'};
        color: white;
        border-radius: 5px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Load initial wishlist state on page load
document.addEventListener('DOMContentLoaded', function() {
    @auth
    const wishlistButtons = document.querySelectorAll('.wishlist-btn');
    wishlistButtons.forEach(button => {
        const productId = button.getAttribute('onclick').match(/\d+/)[0];
        fetch(`/yeu-thich/check/${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.inWishlist) {
                    button.classList.add('active');
                    button.querySelector('i').className = 'fas fa-heart';
                }
            });
    });
    @endauth
});
</script>

<style>
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
</style>
@endsection
