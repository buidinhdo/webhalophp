@extends('layouts.app')

@section('title', 'Sản phẩm - HaloShop')

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
</style>
@endsection

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Bộ lọc</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('products.index') }}">
                        <!-- Search -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tìm kiếm</label>
                            <input type="text" name="search" class="form-control" placeholder="Nhập tên sản phẩm..." value="{{ request('search') }}">
                        </div>

                        <!-- Platform -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nền tảng</label>
                            <select name="platform" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="PS2" {{ request('platform') == 'PS2' ? 'selected' : '' }}>PS2</option>
                                <option value="PS3" {{ request('platform') == 'PS3' ? 'selected' : '' }}>PS3</option>
                                <option value="PS4" {{ request('platform') == 'PS4' ? 'selected' : '' }}>PS4</option>
                                <option value="PS5" {{ request('platform') == 'PS5' ? 'selected' : '' }}>PS5</option>
                                <option value="Nintendo Switch" {{ request('platform') == 'Nintendo Switch' ? 'selected' : '' }}>Nintendo Switch</option>
                                <option value="Xbox" {{ request('platform') == 'Xbox' ? 'selected' : '' }}>Xbox</option>
                                <option value="iPhone" {{ request('platform') == 'iPhone' ? 'selected' : '' }}>iPhone</option>
                                <option value="iPad" {{ request('platform') == 'iPad' ? 'selected' : '' }}>iPad</option>
                            </select>
                        </div>

                        <!-- Sort -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sắp xếp</label>
                            <select name="sort" class="form-select">
                                <option value="">Mới nhất</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Áp dụng
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-redo"></i> Đặt lại
                        </a>
                    </form>

                    <!-- Categories -->
                    @if($categories->count() > 0)
                    <hr>
                    <h6 class="fw-bold">Danh mục</h6>
                    <div class="list-group">
                        @foreach($categories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}" class="list-group-item list-group-item-action">
                            {{ $category->name }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="col-md-9">
            <h2 class="mb-4">Sản phẩm ({{ $products->total() }})</h2>
            
            @if($products->count() > 0)
            <div class="row g-4">
                @foreach($products as $product)
                <div class="col-md-4">
                    <div class="card product-card h-100">
                        <div class="product-image-wrapper">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x250?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
                            @endif
                            
                            <div class="quick-view-overlay" onclick="quickView({{ $product->id }})">
                                <div class="quick-view-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                            
                            @if($product->is_new)
                                <span class="badge-new position-absolute top-0 end-0 m-2" style="z-index: 11;">MỚI</span>
                            @elseif($product->is_preorder)
                                <span class="badge-preorder position-absolute top-0 end-0 m-2" style="z-index: 11;">PRE-ORDER</span>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <a href="{{ route('products.show', $product->slug) }}" class="product-title-link">
                                <h5 class="card-title">{{ Str::limit($product->name, 60) }}</h5>
                            </a>
                            
                            @if($product->platform)
                            <p class="text-muted small mb-2">
                                <i class="fas fa-gamepad"></i> {{ $product->platform }}
                            </p>
                            @endif
                            
                            @if($product->is_preorder && $product->release_date)
                            <p class="text-muted small mb-2">
                                <i class="far fa-calendar"></i> {{ $product->release_date->format('d/m/Y') }}
                            </p>
                            @endif
                            
                            <p class="card-text mt-auto">
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

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $products->links('vendor.pagination.custom') }}
            </div>
            @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> Không tìm thấy sản phẩm nào.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
