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
    
    /* Image Zoom Modal */
    .image-zoom-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        overflow: hidden;
    }
    
    .image-zoom-modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .zoom-container {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        cursor: move;
    }
    
    .zoom-container.zoomed {
        cursor: grab;
    }
    
    .zoom-container.dragging {
        cursor: grabbing;
    }
    
    .zoom-image {
        max-width: 90%;
        max-height: 90vh;
        object-fit: contain;
        transition: transform 0.3s ease;
        user-select: none;
        -webkit-user-drag: none;
    }
    
    .zoom-controls {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        background: rgba(255, 255, 255, 0.2);
        padding: 10px 20px;
        border-radius: 30px;
        backdrop-filter: blur(10px);
    }
    
    .zoom-btn {
        background: rgba(255, 255, 255, 0.9);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        font-size: 18px;
        color: #333;
    }
    
    .zoom-btn:hover {
        background: white;
        transform: scale(1.1);
    }
    
    .zoom-close {
        position: absolute;
        top: 20px;
        right: 30px;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 24px;
        color: #333;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .zoom-close:hover {
        background: white;
        transform: rotate(90deg);
    }
    
    .zoom-info {
        position: absolute;
        top: 30px;
        left: 30px;
        color: white;
        background: rgba(0, 0, 0, 0.5);
        padding: 10px 20px;
        border-radius: 20px;
        font-size: 14px;
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
                <img id="main-product-image" 
                     src="{{ asset($product->image) }}" 
                     class="img-fluid rounded shadow zoomable-image" 
                     style="object-fit: contain; max-height: 500px; width: 100%; background: #fff; padding: 20px; cursor: zoom-in;" 
                     alt="{{ $product->name }}"
                     data-zoom-src="{{ asset($product->image) }}">
            @else
                <img id="main-product-image" 
                     src="https://via.placeholder.com/600x400?text={{ urlencode($product->name) }}" 
                     class="img-fluid rounded shadow" 
                     style="object-fit: contain; max-height: 500px; width: 100%; background: #fff; padding: 20px;" 
                     alt="{{ $product->name }}">
            @endif
            
            <!-- Gallery Slider -->
            <div class="gallery-slider-container">
                <button class="slider-nav-btn slider-prev" id="sliderPrev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <div class="gallery-slider" id="gallerySlider">
                    <!-- Main image thumbnail -->
                    <div class="gallery-slide-item">
                        <img src="{{ asset($product->image) }}" 
                             class="img-fluid rounded gallery-thumbnail" 
                             style="cursor: pointer; border: 3px solid #007bff; width: 100%; height: 80px; object-fit: cover; transition: all 0.3s;"
                             data-image="{{ asset($product->image) }}"
                             alt="{{ $product->name }}">
                    </div>
                    
                    <!-- Gallery images -->
                    @if($product->images->count() > 0)
                        @foreach($product->images as $image)
                        <div class="gallery-slide-item">
                            <img src="{{ asset($image->image_path) }}" 
                                 class="img-fluid rounded gallery-thumbnail zoomable-image" 
                                 style="cursor: pointer; border: 3px solid #e0e0e0; width: 100%; height: 80px; object-fit: cover; transition: all 0.3s;"
                                 data-image="{{ asset($image->image_path) }}"
                                 data-zoom-src="{{ asset($image->image_path) }}"
                                 alt="{{ $product->name }}">
                        </div>
                        @endforeach
                    @endif
                </div>
                
                <button class="slider-nav-btn slider-next" id="sliderNext">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
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
                        @if($product->reviews->where('status', 'approved')->count() > 0)
                            <span class="badge bg-warning text-dark ms-2">
                                {{ number_format($product->reviews->where('status', 'approved')->avg('rating'), 1) }} <i class="fas fa-star"></i>
                                ({{ $product->reviews->where('status', 'approved')->count() }} đánh giá)
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
                    @if($product->reviews->where('status', 'approved')->count() > 0)
                        <hr>
                        <h5 class="mb-3">Các đánh giá</h5>
                        @foreach($product->reviews->where('status', 'approved') as $review)
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

<!-- Image Zoom Modal -->
<div class="image-zoom-modal" id="imageZoomModal">
    <button class="zoom-close" id="zoomClose">×</button>
    <div class="zoom-info" id="zoomInfo">Click để zoom | Kéo để di chuyển | Scroll để zoom</div>
    <div class="zoom-container" id="zoomContainer">
        <img src="" alt="Zoomed Image" class="zoom-image" id="zoomImage">
    </div>
    <div class="zoom-controls">
        <button class="zoom-btn" id="zoomOut" title="Thu nhỏ"><i class="fas fa-minus"></i></button>
        <button class="zoom-btn" id="zoomReset" title="Đặt lại"><i class="fas fa-sync"></i></button>
        <button class="zoom-btn" id="zoomIn" title="Phóng to"><i class="fas fa-plus"></i></button>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gallery Slider Navigation
    const slider = document.getElementById('gallerySlider');
    const prevBtn = document.getElementById('sliderPrev');
    const nextBtn = document.getElementById('sliderNext');
    
    function updateSliderButtons() {
        if (slider.scrollLeft <= 0) {
            prevBtn.classList.add('disabled');
        } else {
            prevBtn.classList.remove('disabled');
        }
        
        if (slider.scrollLeft >= slider.scrollWidth - slider.clientWidth - 1) {
            nextBtn.classList.add('disabled');
        } else {
            nextBtn.classList.remove('disabled');
        }
    }
    
    prevBtn.addEventListener('click', function() {
        if (!this.classList.contains('disabled')) {
            slider.scrollBy({ left: -200, behavior: 'smooth' });
        }
    });
    
    nextBtn.addEventListener('click', function() {
        if (!this.classList.contains('disabled')) {
            slider.scrollBy({ left: 200, behavior: 'smooth' });
        }
    });
    
    slider.addEventListener('scroll', updateSliderButtons);
    updateSliderButtons();
    
    // Gallery thumbnail click handler
    const thumbnails = document.querySelectorAll('.gallery-thumbnail');
    const mainImage = document.getElementById('main-product-image');
    
    thumbnails.forEach(function(thumbnail) {
        // Click event
        thumbnail.addEventListener('click', function(e) {
            // Check if not right click (for zoom)
            if (e.which !== 1 && e.button !== 0) return;
            
            // Get image URL from clicked thumbnail
            const newImageSrc = this.getAttribute('data-image');
            
            // Update main image
            mainImage.src = newImageSrc;
            mainImage.setAttribute('data-zoom-src', this.getAttribute('data-zoom-src'));
            
            // Reset all thumbnails
            thumbnails.forEach(function(thumb) {
                thumb.style.border = '3px solid #e0e0e0';
            });
            
            // Highlight selected thumbnail
            this.style.border = '3px solid #007bff';
        });
        
        // Hover effect - slightly scale up
        thumbnail.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        thumbnail.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Image Zoom Modal Functionality
    const modal = document.getElementById('imageZoomModal');
    const zoomImage = document.getElementById('zoomImage');
    const zoomContainer = document.getElementById('zoomContainer');
    const zoomClose = document.getElementById('zoomClose');
    const zoomIn = document.getElementById('zoomIn');
    const zoomOut = document.getElementById('zoomOut');
    const zoomReset = document.getElementById('zoomReset');
    const zoomableImages = document.querySelectorAll('.zoomable-image');
    
    let scale = 1;
    let translateX = 0;
    let translateY = 0;
    let isDragging = false;
    let startX = 0;
    let startY = 0;
    
    // Open zoom modal
    zoomableImages.forEach(function(img) {
        img.addEventListener('click', function(e) {
            if (e.target.classList.contains('gallery-thumbnail')) return; // Let thumbnail handler work first
            
            const zoomSrc = this.getAttribute('data-zoom-src') || this.src;
            zoomImage.src = zoomSrc;
            modal.classList.add('active');
            resetZoom();
            document.body.style.overflow = 'hidden';
        });
        
        // Double click to zoom
        img.addEventListener('dblclick', function() {
            const zoomSrc = this.getAttribute('data-zoom-src') || this.src;
            zoomImage.src = zoomSrc;
            modal.classList.add('active');
            resetZoom();
            document.body.style.overflow = 'hidden';
        });
    });
    
    // Close modal
    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        setTimeout(resetZoom, 300);
    }
    
    zoomClose.addEventListener('click', closeModal);
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal || e.target === zoomContainer) {
            closeModal();
        }
    });
    
    // Zoom controls
    zoomIn.addEventListener('click', function() {
        scale += 0.3;
        if (scale > 5) scale = 5;
        updateTransform();
    });
    
    zoomOut.addEventListener('click', function() {
        scale -= 0.3;
        if (scale < 0.5) scale = 0.5;
        updateTransform();
    });
    
    zoomReset.addEventListener('click', resetZoom);
    
    function resetZoom() {
        scale = 1;
        translateX = 0;
        translateY = 0;
        updateTransform();
        zoomContainer.classList.remove('zoomed');
    }
    
    function updateTransform() {
        zoomImage.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
        
        if (scale > 1) {
            zoomContainer.classList.add('zoomed');
        } else {
            zoomContainer.classList.remove('zoomed');
        }
    }
    
    // Mouse wheel zoom
    zoomContainer.addEventListener('wheel', function(e) {
        e.preventDefault();
        
        if (e.deltaY < 0) {
            scale += 0.1;
            if (scale > 5) scale = 5;
        } else {
            scale -= 0.1;
            if (scale < 0.5) scale = 0.5;
        }
        
        updateTransform();
    });
    
    // Pan functionality
    zoomImage.addEventListener('mousedown', function(e) {
        if (scale <= 1) return;
        
        isDragging = true;
        startX = e.clientX - translateX;
        startY = e.clientY - translateY;
        zoomContainer.classList.add('dragging');
    });
    
    document.addEventListener('mousemove', function(e) {
        if (!isDragging) return;
        
        e.preventDefault();
        translateX = e.clientX - startX;
        translateY = e.clientY - startY;
        updateTransform();
    });
    
    document.addEventListener('mouseup', function() {
        isDragging = false;
        zoomContainer.classList.remove('dragging');
    });
    
    // Touch support for mobile
    let touchStartX = 0;
    let touchStartY = 0;
    let lastTouchDistance = 0;
    
    zoomImage.addEventListener('touchstart', function(e) {
        if (e.touches.length === 1 && scale > 1) {
            isDragging = true;
            touchStartX = e.touches[0].clientX - translateX;
            touchStartY = e.touches[0].clientY - translateY;
        } else if (e.touches.length === 2) {
            // Pinch to zoom
            const touch1 = e.touches[0];
            const touch2 = e.touches[1];
            lastTouchDistance = Math.hypot(
                touch2.clientX - touch1.clientX,
                touch2.clientY - touch1.clientY
            );
        }
    });
    
    zoomImage.addEventListener('touchmove', function(e) {
        e.preventDefault();
        
        if (e.touches.length === 1 && isDragging) {
            translateX = e.touches[0].clientX - touchStartX;
            translateY = e.touches[0].clientY - touchStartY;
            updateTransform();
        } else if (e.touches.length === 2) {
            const touch1 = e.touches[0];
            const touch2 = e.touches[1];
            const distance = Math.hypot(
                touch2.clientX - touch1.clientX,
                touch2.clientY - touch1.clientY
            );
            
            const delta = distance - lastTouchDistance;
            scale += delta * 0.01;
            if (scale < 0.5) scale = 0.5;
            if (scale > 5) scale = 5;
            
            lastTouchDistance = distance;
            updateTransform();
        }
    });
    
    zoomImage.addEventListener('touchend', function() {
        isDragging = false;
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (!modal.classList.contains('active')) return;
        
        switch(e.key) {
            case 'Escape':
                closeModal();
                break;
            case '+':
            case '=':
                zoomIn.click();
                break;
            case '-':
            case '_':
                zoomOut.click();
                break;
            case '0':
                zoomReset.click();
                break;
        }
    });
});
</script>
@endsection
