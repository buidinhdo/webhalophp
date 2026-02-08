@extends('layouts.app')

@section('title', $product->name . ' - HaloShop')

@section('content')
<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
            @if($product->category)
            <li class="breadcrumb-item"><a href="{{ route('categories.show', $product->category->slug) }}">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6">
            @if($product->image)
                <img src="{{ asset($product->image) }}" class="img-fluid rounded shadow" style="object-fit: contain; max-height: 500px; width: 100%; background: #fff; padding: 20px;" alt="{{ $product->name }}">
            @else
                <img src="https://via.placeholder.com/600x400?text={{ urlencode($product->name) }}" class="img-fluid rounded shadow" style="object-fit: contain; max-height: 500px; width: 100%; background: #fff; padding: 20px;" alt="{{ $product->name }}">
            @endif
            
            @if($product->images->count() > 0)
            <div class="row mt-3">
                @foreach($product->images as $image)
                <div class="col-3">
                    <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="col-md-6">
            <h1 class="display-5 fw-bold mb-3">{{ $product->name }}</h1>
            
            @if($product->is_new)
                <span class="badge-new me-2">MỚI</span>
            @endif
            @if($product->is_preorder)
                <span class="badge-preorder me-2">PRE-ORDER</span>
            @endif
            
            @if($product->platform)
            <p class="text-muted mt-3">
                <i class="fas fa-gamepad"></i> <strong>Nền tảng:</strong> {{ $product->platform }}
            </p>
            @endif
            
            @if($product->is_preorder && $product->release_date)
            <p class="text-muted">
                <i class="far fa-calendar"></i> <strong>Ngày phát hành:</strong> {{ $product->release_date->format('d/m/Y') }}
            </p>
            @endif
            
            @if($product->sku)
            <p class="text-muted">
                <strong>SKU:</strong> {{ $product->sku }}
            </p>
            @endif
            
            <div class="my-4">
                @if($product->sale_price)
                    <span class="old-price d-block mb-2" style="font-size: 1.5rem;">{{ number_format($product->price) }}₫</span>
                    <span class="price" style="font-size: 2.5rem;">{{ number_format($product->sale_price) }}₫</span>
                    <span class="badge bg-danger ms-2">Giảm {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%</span>
                @else
                    <span class="price" style="font-size: 2.5rem;">{{ number_format($product->price) }}₫</span>
                @endif
            </div>
            
            @if($product->stock > 0)
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Còn hàng: {{ $product->stock }} sản phẩm
                </div>
            @else
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle"></i> Hết hàng
                </div>
            @endif
            
            @if($product->short_description)
            <div class="mb-4">
                <h5>Mô tả ngắn</h5>
                <p>{{ $product->short_description }}</p>
            </div>
            @endif
            
            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                <div class="row align-items-center mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Số lượng</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}">
                    </div>
                </div>
                
                @if($product->stock > 0)
                <button type="submit" class="btn btn-primary btn-lg me-2">
                    <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                </button>
                @else
                <button type="button" class="btn btn-secondary btn-lg" disabled>
                    <i class="fas fa-times"></i> Hết hàng
                </button>
                @endif
            </form>
        </div>
    </div>
    
    <!-- Description -->
    @if($product->description)
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Mô tả chi tiết</h4>
                </div>
                <div class="card-body">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Sản phẩm liên quan</h3>
            <div class="row g-4">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card product-card h-100">
                        @if($relatedProduct->image)
                            <img src="{{ asset($relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                        @else
                            <img src="https://via.placeholder.com/300x250?text={{ urlencode($relatedProduct->name) }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ Str::limit($relatedProduct->name, 50) }}</h5>
                            <p class="card-text">
                                @if($relatedProduct->sale_price)
                                    <span class="old-price">{{ number_format($relatedProduct->price) }}₫</span><br>
                                    <span class="price">{{ number_format($relatedProduct->sale_price) }}₫</span>
                                @else
                                    <span class="price">{{ number_format($relatedProduct->price) }}₫</span>
                                @endif
                            </p>
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="btn btn-primary w-100">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
