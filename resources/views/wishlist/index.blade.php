@extends('layouts.app')

@section('title', 'Danh sách yêu thích - HaloShop')

@section('styles')
<style>
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
</style>
@endsection

@section('content')
<div class="container my-5">
    <h2 class="mb-4"><i class="fas fa-heart text-danger me-2"></i> Danh sách yêu thích</h2>
    
    @if($wishlists->count() > 0)
    <div class="row g-4">
        @foreach($wishlists as $wishlist)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card product-card position-relative h-100">
                <!-- Remove from wishlist button -->
                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" style="z-index: 20;" onclick="removeFromWishlist({{ $wishlist->product_id }}, this)" title="Xóa khỏi yêu thích">
                    <i class="fas fa-times"></i>
                </button>
                
                @if($wishlist->product->sale_price)
                    <span class="badge-sale">SALE</span>
                @endif
                @if($wishlist->product->is_new)
                    <span class="badge-new">NEW</span>
                @endif
                
                <div class="product-image-wrapper">
                    @if($wishlist->product->image)
                        <img src="{{ asset($wishlist->product->image) }}" class="card-img-top" alt="{{ $wishlist->product->name }}">
                    @else
                        <img src="https://via.placeholder.com/300x250?text={{ urlencode($wishlist->product->name) }}" class="card-img-top" alt="{{ $wishlist->product->name }}">
                    @endif
                    <div class="quick-view-overlay" onclick="quickView({{ $wishlist->product->id }})">
                        <div class="quick-view-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <a href="{{ route('products.show', $wishlist->product->slug) }}" class="product-title-link">
                        <h5 class="card-title">{{ Str::limit($wishlist->product->name, 45) }}</h5>
                    </a>
                    <p class="card-text mb-3">
                        @if($wishlist->product->sale_price)
                            <span class="old-price d-block">{{ number_format($wishlist->product->price) }}₫</span>
                            <span class="price">{{ number_format($wishlist->product->sale_price) }}₫</span>
                        @else
                            <span class="price">{{ number_format($wishlist->product->price) }}₫</span>
                        @endif
                    </p>
                    
                    @if($wishlist->product->stock > 0)
                    <form action="{{ route('cart.add', $wishlist->product->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                        </button>
                    </form>
                    @else
                    <button class="btn btn-secondary btn-sm w-100" disabled>
                        <i class="fas fa-times-circle"></i> Hết hàng
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-heart fa-5x text-muted mb-4"></i>
        <h4>Danh sách yêu thích trống</h4>
        <p class="text-muted mb-4">Bạn chưa thêm sản phẩm nào vào danh sách yêu thích</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <i class="fas fa-shopping-bag me-2"></i> Khám phá sản phẩm
        </a>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function removeFromWishlist(productId, button) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi danh sách yêu thích?')) {
            return;
        }
        
        fetch(`/yeu-thich/xoa/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the product card
                const card = button.closest('.col-lg-3');
                card.remove();
                
                // Update wishlist count in navbar
                updateWishlistCount(data.count);
                
                // Show empty state if no items left
                const container = document.querySelector('.row.g-4');
                if (container && container.children.length === 0) {
                    location.reload();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, vui lòng thử lại');
        });
    }
    
    function updateWishlistCount(count) {
        const badge = document.querySelector('.wishlist-count');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    }
</script>
@endsection
