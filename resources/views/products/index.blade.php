@extends('layouts.app')

@section('title', 'Sản phẩm - HaloShop')

@section('styles')
<style>
    /* Price Range Slider */
    .price-range-container {
        padding: 10px 0;
    }
    .price-range-slider {
        width: 100%;
        height: 6px;
        border-radius: 3px;
        background: #ddd;
        outline: none;
        -webkit-appearance: none;
    }
    .price-range-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #007bff;
        cursor: pointer;
    }
    .price-range-slider::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #007bff;
        cursor: pointer;
        border: none;
    }
    .price-display {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
        font-size: 14px;
        color: #666;
    }
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
                            <div class="list-group">
                                <label class="list-group-item list-group-item-action {{ !request('platform') ? 'active' : '' }}" style="cursor: pointer;">
                                    <input type="radio" name="platform" value="" {{ !request('platform') ? 'checked' : '' }} style="display: none;">
                                    <i class="fas fa-th-large me-2"></i>Tất cả
                                </label>
                                <label class="list-group-item list-group-item-action {{ request('platform') == 'PS1' ? 'active' : '' }}" style="cursor: pointer;">
                                    <input type="radio" name="platform" value="PS1" {{ request('platform') == 'PS1' ? 'checked' : '' }} style="display: none;">
                                    <i class="fab fa-playstation me-2" style="color: #003087;"></i>PlayStation 1
                                </label>
                                <label class="list-group-item list-group-item-action {{ request('platform') == 'PS2' ? 'active' : '' }}" style="cursor: pointer;">
                                    <input type="radio" name="platform" value="PS2" {{ request('platform') == 'PS2' ? 'checked' : '' }} style="display: none;">
                                    <i class="fab fa-playstation me-2" style="color: #003087;"></i>PlayStation 2
                                </label>
                                <label class="list-group-item list-group-item-action {{ request('platform') == 'PS3' ? 'active' : '' }}" style="cursor: pointer;">
                                    <input type="radio" name="platform" value="PS3" {{ request('platform') == 'PS3' ? 'checked' : '' }} style="display: none;">
                                    <i class="fab fa-playstation me-2" style="color: #0051a8;"></i>PlayStation 3
                                </label>
                                <label class="list-group-item list-group-item-action {{ request('platform') == 'PS4' ? 'active' : '' }}" style="cursor: pointer;">
                                    <input type="radio" name="platform" value="PS4" {{ request('platform') == 'PS4' ? 'checked' : '' }} style="display: none;">
                                    <i class="fab fa-playstation me-2" style="color: #003087;"></i>PlayStation 4
                                </label>
                                <label class="list-group-item list-group-item-action {{ request('platform') == 'PS5' ? 'active' : '' }}" style="cursor: pointer;">
                                    <input type="radio" name="platform" value="PS5" {{ request('platform') == 'PS5' ? 'checked' : '' }} style="display: none;">
                                    <i class="fab fa-playstation me-2" style="color: #0070cc;"></i>PlayStation 5
                                </label>
                                <label class="list-group-item list-group-item-action {{ request('platform') == 'Nintendo Switch' ? 'active' : '' }}" style="cursor: pointer;">
                                    <input type="radio" name="platform" value="Nintendo Switch" {{ request('platform') == 'Nintendo Switch' ? 'checked' : '' }} style="display: none;">
                                    <img src="{{ asset('images/icons/nintendo-switch.svg') }}" alt="Nintendo Switch" class="me-2" style="width: 20px; height: 20px; vertical-align: middle;">Nintendo Switch
                                </label>
                                <label class="list-group-item list-group-item-action {{ request('platform') == 'Xbox' ? 'active' : '' }}" style="cursor: pointer;">
                                    <input type="radio" name="platform" value="Xbox" {{ request('platform') == 'Xbox' ? 'checked' : '' }} style="display: none;">
                                    <i class="fab fa-xbox me-2" style="color: #107c10;"></i>Xbox
                                </label>
                                <label class="list-group-item list-group-item-action {{ request('platform') == 'GameCube' ? 'active' : '' }}" style="cursor: pointer;">
                                    <input type="radio" name="platform" value="GameCube" {{ request('platform') == 'GameCube' ? 'checked' : '' }} style="display: none;">
                                    <img src="{{ asset('images/icons/gamecube.svg') }}" alt="GameCube" class="me-2" style="width: 20px; height: 20px; vertical-align: middle;">Nintendo GameCube
                                </label>
                                <label class="list-group-item list-group-item-action {{ request('platform') == 'Wii' ? 'active' : '' }}" style="cursor: pointer;">
                                    <input type="radio" name="platform" value="Wii" {{ request('platform') == 'Wii' ? 'checked' : '' }} style="display: none;">
                                    <img src="{{ asset('images/icons/wii.svg') }}" alt="Nintendo Wii" class="me-2" style="width: 20px; height: 20px; vertical-align: middle;">Nintendo Wii
                                </label>
                                <label class="list-group-item list-group-item-action {{ request('platform') == 'Super Nintendo' ? 'active' : '' }}" style="cursor: pointer;">
                                    <input type="radio" name="platform" value="Super Nintendo" {{ request('platform') == 'Super Nintendo' ? 'checked' : '' }} style="display: none;">
                                    <img src="{{ asset('images/icons/super-nintendo.svg') }}" alt="Super Nintendo" class="me-2" style="width: 50px; height: 15px; vertical-align: middle;">Super Nintendo
                                </label>
                            </div>
                        </div>

                        <!-- Genre -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Thể loại game</label>
                            <select name="genre" class="form-select">
                                <option value="">Tất cả</option>
                                @foreach($genres as $genre)
                                    <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>{{ $genre }}</option>
                                @endforeach
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
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Đánh giá cao nhất</option>
                            </select>
                        </div>

                        <!-- Rating Filter -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Đánh giá</label>
                            <select name="rating" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>
                                    ⭐⭐⭐⭐⭐ 5 sao
                                </option>
                                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>
                                    ⭐⭐⭐⭐ 4 sao
                                </option>
                                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>
                                    ⭐⭐⭐ 3 sao
                                </option>
                                <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>
                                    ⭐⭐ 2 sao
                                </option>
                                <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>
                                    ⭐ 1 sao
                                </option>
                            </select>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Khoảng giá</label>
                            <div class="price-range-container">
                                <input type="hidden" name="min_price" id="minPriceInput" value="{{ request('min_price', 0) }}">
                                <input type="hidden" name="max_price" id="maxPriceInput" value="{{ request('max_price', 40000000) }}">
                                <div class="mb-2">
                                    <label class="form-label small">Từ:</label>
                                    <input type="range" class="price-range-slider" id="minPriceSlider" 
                                           min="0" max="40000000" step="100000" 
                                           value="{{ request('min_price', 0) }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Đến:</label>
                                    <input type="range" class="price-range-slider" id="maxPriceSlider" 
                                           min="0" max="40000000" step="100000" 
                                           value="{{ request('max_price', 40000000) }}">
                                </div>
                                <div class="price-display">
                                    <span id="minPriceDisplay">{{ number_format(request('min_price', 0)) }}₫</span>
                                    <span id="maxPriceDisplay">{{ number_format(request('max_price', 40000000)) }}₫</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Áp dụng
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-redo"></i> Đặt lại
                        </a>
                    </form>
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
                            
                            @if($product->is_new)
                                <span class="badge-new position-absolute top-0 end-0 m-2" style="z-index: 11;">MỚI</span>
                            @elseif($product->is_preorder)
                                <span class="badge-preorder position-absolute top-0 end-0 m-2" style="z-index: 11;">PRE-ORDER</span>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <a href="{{ route('products.show', $product->slug) }}" class="product-title-link">
                                <h5 class="card-title">{{ $product->name }}</h5>
                            </a>
                            
                            @if($product->platform)
                            <p class="text-muted small mb-2">
                                <i class="fas fa-gamepad"></i> {{ $product->platform }}
                            </p>
                            @endif
                            
                            @if(isset($product->avg_rating) && $product->avg_rating > 0)
                            <p class="mb-2">
                                @php
                                    $rating = round($product->avg_rating * 2) / 2; // Làm tròn đến 0.5
                                    $fullStars = floor($rating);
                                    $halfStar = ($rating - $fullStars) >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                @endphp
                                <span class="text-warning">
                                    @for($i = 0; $i < $fullStars; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                    @if($halfStar)
                                        <i class="fas fa-star-half-alt"></i>
                                    @endif
                                    @for($i = 0; $i < $emptyStars; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                </span>
                                <span class="text-muted small">({{ number_format($product->avg_rating, 1) }})</span>
                                @if(isset($product->reviews_count))
                                    <span class="text-muted small">- {{ $product->reviews_count }} đánh giá</span>
                                @endif
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

@section('scripts')
<script>
// Price Range Slider
document.addEventListener('DOMContentLoaded', function() {
    const minSlider = document.getElementById('minPriceSlider');
    const maxSlider = document.getElementById('maxPriceSlider');
    const minInput = document.getElementById('minPriceInput');
    const maxInput = document.getElementById('maxPriceInput');
    const minDisplay = document.getElementById('minPriceDisplay');
    const maxDisplay = document.getElementById('maxPriceDisplay');
    
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price) + '₫';
    }
    
    function updatePriceDisplay() {
        let minVal = parseInt(minSlider.value);
        let maxVal = parseInt(maxSlider.value);
        
        // Ensure min is not greater than max
        if (minVal > maxVal) {
            minVal = maxVal;
            minSlider.value = minVal;
        }
        
        minInput.value = minVal;
        maxInput.value = maxVal;
        minDisplay.textContent = formatPrice(minVal);
        maxDisplay.textContent = formatPrice(maxVal);
    }
    
    minSlider.addEventListener('input', updatePriceDisplay);
    maxSlider.addEventListener('input', updatePriceDisplay);
    
    // Initialize
    updatePriceDisplay();
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
