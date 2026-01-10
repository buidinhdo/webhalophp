@extends('layouts.app')

@section('title', 'Trang chủ - HaloShop')

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

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section class="container my-5">
    <h2 class="section-title">
        <i class="fas fa-star text-warning me-2"></i> Sản phẩm nổi bật
    </h2>
    <div class="row g-4">
        @foreach($featuredProducts as $product)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card product-card position-relative">
                @if($product->sale_price)
                    <span class="badge-sale">SALE</span>
                @endif
                @if($product->is_new)
                    <span class="badge-new">NEW</span>
                @endif
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                @else
                    <img src="https://via.placeholder.com/300x250?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ Str::limit($product->name, 45) }}</h5>
                    <p class="card-text mb-3">
                        @if($product->sale_price)
                            <span class="old-price d-block">{{ number_format($product->price) }}₫</span>
                            <span class="price">{{ number_format($product->sale_price) }}₫</span>
                        @else
                            <span class="price">{{ number_format($product->price) }}₫</span>
                        @endif
                    </p>
                    <div class="d-grid">
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary btn-sm">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- PlayStation Section -->
<div class="category-banner" style="background: linear-gradient(135deg, #0077ED 0%, #00A0DF 100%);">
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
    <div class="row g-4">
        @foreach($newProducts as $product)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card product-card position-relative">
                @if($product->is_preorder)
                    <span class="badge-preorder">PRE-ORDER</span>
                @else
                    <span class="badge-new">NEW</span>
                @endif
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                @else
                    <img src="https://via.placeholder.com/300x250?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ Str::limit($product->name, 45) }}</h5>
                    <p class="card-text mb-3">
                        @if($product->sale_price)
                            <span class="old-price d-block">{{ number_format($product->price) }}₫</span>
                            <span class="price">{{ number_format($product->sale_price) }}₫</span>
                        @else
                            <span class="price">{{ number_format($product->price) }}₫</span>
                        @endif
                    </p>
                    <div class="d-grid">
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary btn-sm">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Nintendo Switch Section -->
<div class="category-banner" style="background: linear-gradient(135deg, #E60012 0%, #FF6B6B 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-3"><i class="fas fa-gamepad me-3"></i>NINTENDO SWITCH 2</h2>
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
    <div class="row g-4">
        @foreach($preorderProducts as $product)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card product-card position-relative">
                <span class="badge-preorder">PRE-ORDER</span>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                @else
                    <img src="https://via.placeholder.com/300x250?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ Str::limit($product->name, 45) }}</h5>
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
                    <div class="d-grid">
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary btn-sm">
                            Đặt trước ngay
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Apple Section -->
<div class="category-banner" style="background: linear-gradient(135deg, #000000 0%, #434343 100%);">
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
