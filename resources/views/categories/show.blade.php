@extends('layouts.app')

@section('title', $category->name . ' - HaloShop')

@section('content')
<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="text-center mb-5">
        @if($category->image)
            <img src="{{ asset('storage/' . $category->image) }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;" alt="{{ $category->name }}">
        @endif
        <h1 class="display-4 mt-3">{{ $category->name }}</h1>
        @if($category->description)
            <p class="lead text-muted">{{ $category->description }}</p>
        @endif
    </div>

    @if($products->count() > 0)
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-md-3">
            <div class="card product-card h-100">
                <div class="position-relative">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/300x250?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
                    @endif
                    
                    @if($product->is_new)
                        <span class="badge-new position-absolute top-0 end-0 m-2">MỚI</span>
                    @elseif($product->is_preorder)
                        <span class="badge-preorder position-absolute top-0 end-0 m-2">PRE-ORDER</span>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ Str::limit($product->name, 60) }}</h5>
                    
                    @if($product->platform)
                    <p class="text-muted small mb-2">
                        <i class="fas fa-gamepad"></i> {{ $product->platform }}
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
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                    </div>
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
        <i class="fas fa-info-circle"></i> Không có sản phẩm nào trong danh mục này.
    </div>
    @endif
</div>
@endsection
