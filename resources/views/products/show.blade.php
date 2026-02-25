@extends('layouts.app')

@section('title', $product->name . ' - HaloShop')

@section('styles')
<style>
    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 5px;
    }
    .rating-input input {
        display: none;
    }
    .rating-input label {
        cursor: pointer;
        font-size: 2rem;
        color: #ddd;
        transition: color 0.2s;
    }
    .rating-input label:hover,
    .rating-input label:hover ~ label,
    .rating-input input:checked ~ label {
        color: #ffc107;
    }
    .review-item {
        transition: box-shadow 0.2s;
    }
    .review-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
        text-decoration: none;
    }
    .product-image-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 0.5rem;
    }
    .quick-view-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
        z-index: 10;
    }
    .product-image-wrapper:hover .quick-view-overlay {
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
</style>
@endsection

@section('content')
<div class="container my-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
    
    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-star"></i> Đánh giá sản phẩm
                        @if($product->reviews->count() > 0)
                            <span class="badge bg-warning text-dark ms-2">
                                {{ number_format($product->reviews->avg('rating'), 1) }} <i class="fas fa-star"></i>
                                ({{ $product->reviews->count() }} đánh giá)
                            </span>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    @auth
                        <!-- Form đánh giá -->
                        <div class="mb-4 p-3 bg-light rounded">
                            <h5 class="mb-3">Viết đánh giá của bạn</h5>
                            <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Đánh giá của bạn <span class="text-danger">*</span></label>
                                    <div class="rating-input">
                                        <input type="radio" name="rating" id="star5" value="5" required>
                                        <label for="star5" title="5 sao"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" id="star4" value="4">
                                        <label for="star4" title="4 sao"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" id="star3" value="3">
                                        <label for="star3" title="3 sao"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" id="star2" value="2">
                                        <label for="star2" title="2 sao"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" id="star1" value="1">
                                        <label for="star1" title="1 sao"><i class="fas fa-star"></i></label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nhận xét của bạn <span class="text-danger">*</span></label>
                                    <textarea name="comment" class="form-control" rows="4" required placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Gửi đánh giá
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            Vui lòng <a href="{{ route('login') }}" class="alert-link">đăng nhập</a> để viết đánh giá.
                        </div>
                    @endauth

                    <!-- Danh sách đánh giá -->
                    @if($product->reviews->count() > 0)
                        <hr>
                        <h5 class="mb-3">Các đánh giá</h5>
                        @foreach($product->reviews as $review)
                            <div class="review-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $review->user->name ?? 'Khách hàng' }}</strong>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                </div>
                                <p class="mt-2 mb-0">{{ $review->comment }}</p>
                                
                                @if($review->admin_reply)
                                    <div class="admin-reply mt-3 p-2 bg-light rounded">
                                        <strong class="text-primary"><i class="fas fa-reply"></i> Phản hồi từ HaloShop:</strong>
                                        <p class="mb-0 mt-1">{{ $review->admin_reply }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-4">Chưa có đánh giá nào cho sản phẩm này.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Sản phẩm liên quan</h3>
            <div class="row g-4">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card product-card h-100">
                        <div class="product-image-wrapper">
                            @if($relatedProduct->image)
                                <img src="{{ asset($relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x250?text={{ urlencode($relatedProduct->name) }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                            @endif
                            <div class="quick-view-overlay" onclick="quickView({{ $relatedProduct->id }})">
                                <div class="quick-view-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="product-title-link">
                                <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                            </a>
                            <p class="card-text">
                                @if($relatedProduct->sale_price)
                                    <span class="old-price">{{ number_format($relatedProduct->price) }}₫</span><br>
                                    <span class="price">{{ number_format($relatedProduct->sale_price) }}₫</span>
                                @else
                                    <span class="price">{{ number_format($relatedProduct->price) }}₫</span>
                                @endif
                            </p>
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
