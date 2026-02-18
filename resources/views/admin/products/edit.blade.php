@extends('admin.layouts.app')

@section('title', 'Sửa sản phẩm')
@section('page-title', 'Sửa sản phẩm')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
    <li class="breadcrumb-item active">Sửa</li>
@endsection

@section('content')
<div class="card">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Danh mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nền tảng</label>
                                <input type="text" name="platform" class="form-control" value="{{ old('platform', $product->platform) }}" placeholder="VD: PS5, Xbox, Nintendo Switch...">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Giá gốc <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Giá khuyến mãi</label>
                                <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tồn kho <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" required>
                                @error('stock')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Mô tả ngắn</label>
                        <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Mô tả chi tiết</label>
                        <textarea name="description" class="form-control" rows="5">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Hình ảnh hiện tại</label>
                        @if($product->image)
                            <div class="mb-2">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        <small class="form-text text-muted">Để trống nếu không muốn đổi hình</small>
                        @error('image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Trạng thái <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Đang bán</option>
                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Ngừng bán</option>
                            <option value="out_of_stock" {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Thuộc tính</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_featured">Sản phẩm nổi bật</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_new" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_new">Sản phẩm mới</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_preorder" name="is_preorder" value="1" {{ old('is_preorder', $product->is_preorder) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_preorder">Đặt trước</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Danh mục nhanh</label>
                        @php
                            $currentCategorySlug = $product->category ? $product->category->slug : '';
                        @endphp
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input category-quick-select" id="cat_ps4" name="category_quick" value="ps4" data-category-name="PlayStation 4" {{ $currentCategorySlug == 'ps4' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="cat_ps4"><i class="fab fa-playstation text-primary"></i> PlayStation 4</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input category-quick-select" id="cat_ps5" name="category_quick" value="ps5" data-category-name="PlayStation 5" {{ $currentCategorySlug == 'ps5' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="cat_ps5"><i class="fab fa-playstation text-info"></i> PlayStation 5</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input category-quick-select" id="cat_nintendo" name="category_quick" value="nintendo-switch" data-category-name="Nintendo Switch" {{ $currentCategorySlug == 'nintendo-switch' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="cat_nintendo"><img src="{{ asset('images/icons/nintendo-switch.svg') }}" alt="Nintendo" style="width: 18px; height: 18px;"> Nintendo Switch</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input category-quick-select" id="cat_xbox" name="category_quick" value="xbox" data-category-name="Xbox" {{ $currentCategorySlug == 'xbox' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="cat_xbox"><i class="fab fa-xbox text-success"></i> Xbox</label>
                        </div>
                        <small class="form-text text-muted">Hoặc chọn từ danh mục bên trên</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Hủy
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Sync radio buttons with category dropdown
    $('.category-quick-select').on('change', function() {
        if ($(this).is(':checked')) {
            var categorySlug = $(this).val();
            var categoryName = $(this).data('category-name');
            
            // Find and select the matching category in dropdown
            $('select[name="category_id"] option').each(function() {
                if ($(this).text().trim() === categoryName) {
                    $('select[name="category_id"]').val($(this).val());
                    return false;
                }
            });
        }
    });
    
    // When dropdown changes, update radio button
    $('select[name="category_id"]').on('change', function() {
        var selectedText = $(this).find('option:selected').text().trim();
        $('.category-quick-select').prop('checked', false);
        
        $('.category-quick-select').each(function() {
            if ($(this).data('category-name') === selectedText) {
                $(this).prop('checked', true);
            }
        });
    });
});
</script>
@endpush
