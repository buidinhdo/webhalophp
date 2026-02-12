@if($item->product_image && file_exists(public_path($item->product_image)))
    <img src="{{ asset($item->product_image) }}" alt="{{ $item->product_name }}" 
        class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
@elseif($item->product && $item->product->image && file_exists(public_path($item->product->image)))
    <img src="{{ asset($item->product->image) }}" alt="{{ $item->product_name }}" 
        class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
@else
    <div class="bg-light border rounded d-flex align-items-center justify-content-center" 
        style="width: 60px; height: 60px;">
        <i class="fas fa-image text-muted"></i>
    </div>
@endif
